<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
  header("Cache-Control:max-age=0");

  try {
      $myCLIL = SM_myCLIL::singleton();
      if (!SM_Sruth::ceadSgriobhaidh()) { $myCLIL->diultadh(''); }
  } catch (Exception $e) {
      $myCLIL->toradh = $e->getMessage();
  }

  try {
    $T = new SM_T('sruth/sDeasaich');
    $hl = $T::hl0();
    $T_Canan                         = $T->_('Language');
    $T_Abairt                        = $T->_('Abairt');
    $T_Bitheantas                    = $T->_('Bitheantas');
    $T_Faclan_cinn                   = $T->_('Faclan-cinn');
    $T_Sabhail                       = $T->_('Sàbhail');
    $T_Cruthaich_abairt_ur           = $T->_('Cruthaich_abairt_ur');
    $T_Deasaich_abairt_n             = $T->_('Deasaich_abairt_n');
    $T_Paramter_p_a_dhith            = $T->_('Parameter_p_a_dhith');
    $T_Parameters_a_dhith            = $T->_('Parameters a dhìth');
    $T_Feumaidh_tu_canan_a_thaghadh  = $T->_('Feumaidh tu cànan a thaghadh');
    $T_Cha_ghabh_abairt_chur_gu_null = $T->_('Cha_ghabh_abairt_chur_gu_null');
    $T_Abairt_ann_cheana             = $T->_('Abairt_ann_cheana');
    $T_Chaidh_abairt_a_cho_mheasgadh = $T->_('Chaidh_abairt_a_cho_mheasgadh');

    $myCLIL->dearbhaich();
    $smid = $myCLIL->id ?? 'test';

    $HTML = $cinnInputHtml = $cLiostaHtml = $cLiostaAttr = $tRoimhe = $aRoimhe = $refreshHtml = $fiosMearachd = '';
    $pailtRoimhe = 0;
    $refreshDelay = 1;
    $sUr = -1;
    $utime = time();
    $nc = 5;

    $navbar      = SM_Sruth::navbar($T->domhan);
    $stordataCss = SM_Sruth::stordataCss();
    $sruthUrl    = SM_Sruth::sruthurl();
    $ainmTeanga  = SM_Sruth::ainmTeanga();
    $teangaithe = array_keys($ainmTeanga);

    if (!isset($_REQUEST['s'])) { throw new SM_Exception(sprintf($T_Paramter_p_a_dhith,'s')); }
    $s = intval($_REQUEST['s']);
    $n = $_REQUEST['n'] ?? 0; //Am buidheann far an déid am facal a chur

    $stordataConnector = SM_Sruth::stordataConnector();
    $DbSruth = $stordataConnector::singleton('rw');

    if ($s<>0) {
        $stmtSEL = $DbSruth->prepare('SELECT a,t,pailt FROM sruths WHERE s=:s');
        $stmtSEL->execute(array(':s'=>$s));
        $stmtSEL->bindColumn(1,$aRoimhe);
        $stmtSEL->bindColumn(2,$tRoimhe);
        $stmtSEL->bindColumn(3,$pailtRoimhe);
        if (!$stmtSEL->fetch()) { throw new SM_Exception("Chan eil abairt $s ann idir"); }
        $aRoimhe = htmlspecialchars($aRoimhe);
        $tRoimhe = htmlspecialchars($tRoimhe);
        $cLiostaHtml = SM_Sruth::cLiosta($tRoimhe);
        $cLiostaAttr = "list='cLiosta_$tRoimhe'"; 
        $stmtSELc = $DbSruth->prepare('SELECT c FROM sruthc WHERE s=:s ORDER BY c');
        $stmtSELc->execute(array(':s'=>$s));
        $cRoimheArr = $stmtSELc->fetchAll(PDO::FETCH_COLUMN,0);
        $cRoimheCount = count($cRoimheArr);
        $nc = max($nc,$cRoimheCount+1);
        $nc = min($nc,7);
        for ($i=$cRoimheCount+1; $i<=$ns; $i++) { $cRoimheArr[] = ''; }
    }

    if (isset($_REQUEST['sabhail'])) {

        if (!isset($_REQUEST['t']) || !isset($_REQUEST['a']) || !isset($_REQUEST['pailt'])) { throw new SM_Exception($T_Parameters_a_dhith); }
        $t     = trim($_REQUEST['t']);
        $a     = trim($_REQUEST['a']);
        $pailt = intval(trim($_REQUEST['pailt']));
        if (empty($t)) { throw new SM_Exception($T_Feumaidh_tu_canan_a_thaghadh); }
        if (empty($a)) { throw new SM_Exception($T_Cha_ghabh_abairt_chur_gu_null); }

        $stmtSELs = $DbSruth->prepare('SELECT s FROM sruths WHERE t=:t AND a=:a');
        $stmtSELs->execute(array(':t'=>$t,':a'=>$a));
        $stmtSELs->bindColumn(1,$sEile);
        if ($stmtSELs->fetch() && $s<>$sEile ) {
            $refreshDelay = 3;
            if ($s==0) {
                $HTML .= "<p>$T_Abairt_ann_cheana</p>\n";
                $sUr = $sEile;
            } elseif ($s<>$sEile) {  //Dèan co-measgadh
                $stmtSELsruthc = $DbSruth->prepare('SELECT c FROM sruthc WHERE s=:s');
                $stmtSELsruthc->execute(array(':s'=>$s));
                $cinnFhacail = $stmtSELsruthc->fetchAll(PDO::FETCH_COLUMN,0);
                $stmtSELsruthns = $DbSruth->prepare('SELECT n FROM sruthns WHERE s=:s');
                $stmtSELsruthns->execute(array(':s'=>$s));
                $buidhnean = $stmtSELsruthns->fetchAll(PDO::FETCH_COLUMN,0);
                $DbSruth->beginTransaction();
                $sUr = SM_Sruth::insertAbairt($t,$a,$cinnFhacail,$buidhnean,$smid,time(),1);
                $stmtDELETEsruthns = $DbSruth->prepare('DELETE FROM sruthns WHERE s=:s')->execute(array(':s'=>$s));
                $stmtDELETEsruthc  = $DbSruth->prepare('DELETE FROM sruthc WHERE s=:s')->execute(array(':s'=>$s));
                $stmtDELETEsruths  = $DbSruth->prepare('DELETE FROM sruths WHERE s=:s')->execute(array(':s'=>$s));
                $DbSruth->commit();
                $HTML .= "<p style='font-size:160%;color:#c80'>$T_Chaidh_abairt_a_cho_mheasgadh</p>\n";
            }
        } elseif ($s==0) {
            $stmtINS = $DbSruth->prepare('INSERT INTO sruths (t, a, pailt, csmid, cutime, msmid, mutime) VALUES (:t, :a, :pailt, :csmid, :cutime, :msmid, :mutime)');
            $stmtINS->execute( array(':t'=>$t, ':a'=>$a, ':pailt'=>$pailt, ':csmid'=>$smid, ':cutime'=>$utime, ':msmid'=>$smid, ':mutime'=>$utime) );
            $HTML .= "<p><img src='/icons-smo/tick.gif' alt=''> Chaidh abairt ùr a chruthachadh</p>\n";
            $sUr = $DbSruth->lastInsertId();
        } elseif ($t<>$tRoimhe || $a<>$aRoimhe || $pailt<>$pailtRoimhe) {
            $stmtUPD = $DbSruth->prepare('UPDATE sruths SET t=:t, a=:a, pailt=:pailt, msmid=:msmid, mutime=:mutime WHERE s=:s');
            $stmtUPD = $stmtUPD->execute( array(':s'=>$s, ':t'=>$t, ':a'=>$a, ':pailt'=>$pailt, ':msmid'=>$smid, ':mutime'=>$utime) );
            $HTML .= "<p><img src='/icons-smo/tick.gif' alt=''> Atharrachadh soirbheachail</p>\n";
            $sUr = $s;
        } else {
            $HTML .= "<p>Cha deach an abairt atharrachadh</p>\n";
            $sUr = $s;
        }
        $cArr = $_REQUEST['c'];
        $DbSruth->beginTransaction();
        $stmtDELETEc = $DbSruth->prepare('DELETE FROM sruthc WHERE s=:s')->execute(array(':s'=>$sUr));
        $stmtINSERTc = $DbSruth->prepare('INSERT INTO sruthc(s,c,smid) VALUES(:s,:c,:smid)');
        foreach ($cArr as $c) {
            if (!empty($c)) { $stmtINSERTc->execute(array(':s'=>$sUr,':c'=>$c,':smid'=>$smid)); }
        }
        $DbSruth->commit();
        if (!empty($n)) {
            $stmtINSns = $DbSruth->prepare('INSERT IGNORE INTO sruthns (n, s, smid) VALUES (:n, :s, :smid)');
            $stmtINSns->execute( array(':n'=>$n, ':s'=>$sUr,':smid'=>$smid) );
            $HTML .= "<p>Chaidh an abairt a chur ri buidheann $n</p>\n";
        }
        $refreshHtml = "<meta http-equiv=refresh content='$refreshDelay;url=$sruthUrl/s.php?s=$sUr'>\n";

    } else {

        $h1 = ( $s==0 ? $T_Cruthaich_abairt_ur : sprintf($T_Deasaich_abairt_n,$s) );
        $HTML .= <<<EODHtmlCeann
<a href="./"><img src="dealbhan/sruth64.png" style="float:left;border:1px solid black;margin:0 2em 2em 0" alt=""></a>
<h1 class=smo>$h1</h1>
EODHtmlCeann;

        if (!empty($fiosMearachd)) {
            $HTML .= "<p style=\"color:red;font-weight:bold\">$fiosMearachd</p>\n";
        } else {
            $tRoimheHtml = htmlspecialchars($tRoimhe);
            $aRoimheHtml = htmlspecialchars($aRoimhe);
            $selectTHtml  = "<select name='t' required onchange='teangaUr(this);'>\n";
            $selectTHtml .= "<option value=''" . ($tRoimhe=='' ? ' selected' : '') . "></option>\n";
            foreach ($teangaithe as $t) { $selectTHtml .= "<option value='$t'" . ($tRoimhe==$t ? ' selected' : '') . '>' . $ainmTeanga[$t] . "</option>\n"; }
            $selectTHtml .= "</select>\n";
            for ($i=0; $i<$nc; $i++) {
                $cRoimhe = htmlspecialchars($cRoimheArr[$i],ENT_QUOTES);
                $cinnInputHtml .= "<input name='c[]' value='$cRoimhe' style='width:10em' class=ceann $cLiostaAttr>\n";
            }
            $HTML .= <<<EODHtmlFoirm
<form id=formid method=post action="" required style="clear:both" spellcheck="true" lang="$tRoimhe">
$cLiostaHtml
<input type="hidden" name="s" value="$s">
<input type="hidden" name="n" value="$n">
<table id=formTable>
<tr><td>$T_Canan</td><td>$selectTHtml</td></tr>
<tr><td>$T_Abairt</td><td><input style="width:100%" name="a" value="$aRoimheHtml"></td></tr>
<tr><td>$T_Bitheantas</td><td><input name="pailt" type=range min=0 max=3 step=1 value=$pailtRoimhe style="width:12em"></td></tr>
<tr><td>$T_Faclan_cinn</td><td>
$cinnInputHtml
</td></tr>
<tr><td><input type=submit name="sabhail" value="$T_Sabhail"></td></tr>
</table>
</form>
EODHtmlFoirm;
        }

    }

  } catch (Exception $e) {
      if (strpos($e,'Sgrios')!==FALSE) { $HTML = ''; }
      $HTML .= $e;
  }

  $duilleagHTML = <<<EODduilleag
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <title>An Sruth: $h1</title>
    $refreshHtml<link rel="StyleSheet" href="/css/smo.css">
    <link rel="StyleSheet" href="snas.css">$stordataCss
    <style>
        table#formTable { width:100%; }
        table#formTable td:nth-child(1) { width:6em; text-align:right; }
    </style>
    <script>
        function teangaUr(sel) {
            document.getElementById('formid').lang = sel.value;
        }
    </script>
</head>
<body>

$navbar
<div class="smo-body-indent">

$HTML

</div>
$navbar

<div class="smo-latha">2017-03-04 <a href="//www.smo.uhi.ac.uk/~caoimhin/cpd.html">CPD</a></div>
</body>
</html>
EODduilleag;

echo $duilleagHTML;
?>
