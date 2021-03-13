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
    $T = new SM_T('sruth/nDeasaich');
    $hl = $T::hl0();
    $T_Cruthaich_buidheann_ur        = $T->h('Cruthaich_buidheann_ur');
    $T_Parameter_mi_iom              = $T->h('Parameter mi_iom');
    $T_Chan_eil_buidheann_ann        = $T->h('Chan_eil_buidheann_ann');
    $T_Buidheann_ann_mu_thrath       = $T->h('Buidheann_ann_mu_thrath');
    $T_Cha_ghabhadh_abairt_INSERTadh = $T->h('Cha_ghabhadh_abairt_INSERTadh');
    $T_Atharrachadh_soirbheachail    = $T->h('Atharrachadh soirbheachail');
    $T_Chaidh_buidheann_ur_chur_ann  = $T->h('Chaidh_buidheann_ur_chur_ann');
    $T_Deasaich_buidheann_n          = $T->h('Deasaich_buidheann_n');
    $T_fios                          = $T->h('fios');
    $T_Sabhail                       = $T->h('Sàbhail');

    $myCLIL->dearbhaich();
    $smid = $myCLIL->id;
    $navbar = SM_Sruth::navbar($T->domhan);
    $sruthURL = SM_Sruth::sruthurl();

    $nUr = -1;
    $HTML = $foirmHTML = $fiosMearachd = '';

    $n = (!isset($_REQUEST['n']) ? 0 : $_REQUEST['n']);
    $n = htmlspecialchars($n);
    if (!is_numeric($n) || intval($n)<>$n || $n<0) { throw new SM_Exception("$T_Parameter_mi_iom: n=$n"); }
    $s = (!isset($_REQUEST['s']) ? 0 : $_REQUEST['s']);
    $s = htmlspecialchars($s);
    if (!is_numeric($s) || intval($s)<>$s || $s<0) { throw new SM_Exception("$T_Parameter_mi_iom: s=$s"); }

    $stordataConnector = SM_Sruth::stordataConnector();
    $DbSruth = $stordataConnector::singleton('rw');
    $stmtSEL = $DbSruth->prepare('SELECT fios FROM sruthn WHERE n=:n');
    $stmtSEL->execute(array(':n'=>$n));
    $stmtSEL->bindColumn(1,$fiosRoimhe);
    if ($n==0) {  //Buidheann ùr
        $fiosRoimhe = '';
    } elseif (!$stmtSEL->fetch()) {
        throw new SM_Exception("$T_Chan_eil_buidheann_ann, $n");
    } else {
        $fiosRoimhe   = htmlspecialchars($fiosRoimhe);
    }

    if (!empty($_REQUEST['sabhail'])) {
        $fiosUr = ( empty($_REQUEST['fios'])   ? '' : $_REQUEST['fios']   );
        if ($n==0) {
            $stmtATHARRAICH = $DbSruth->prepare('INSERT IGNORE INTO sruthn (fios) VALUES (:fios)');
            $stmtATHARRAICH->execute( array(':fios'=>$fiosUr) );
        } else {
            $stmtATHARRAICH = $DbSruth->prepare('UPDATE IGNORE sruthn SET fios=:fios WHERE n=:n');
            $stmtATHARRAICH->execute( array(':n'=>$n, ':fios'=>$fiosUr ) );
        }
        if ($stmtATHARRAICH->rowCount()==1) {
            if ($n==0) { $nUr = $DbSruth->lastInsertId(); }
             else      { $nUr = $n; }
        } else {
            $fiosMearachd = $T_Buidheann_ann_mu_thrath;
        }
        if ($s<>0) {
            $stmtINSERTs = $DbSruth->prepare('INSERT IGNORE INTO sruthns(n,s,astar,meit) VALUES (:n,:s,1,0)');
            $stmtINSERTs->execute( array(':n'=>$nUr,':s'=>$s) );
            if ($stmtINSERTs->rowCount()==0) { throw new SM_Exception(sprintf($T_Cha_ghabhadh_abairt_INSERTadh,$s,$nUR)); }
        }
    }

    $refreshHtml = ( $nUr>0 ? "\n    <meta http-equiv=refresh content='1;url=$sruthURL/n.php?n=$nUr'>" : '');

    if ($nUr==$n) {
        $HTML .= "<p><img src='/icons-smo/tick.gif' alt=''> $T_Atharrachadh_soirbheachail</p>\n";
    } elseif ($nUr>0) {
        $HTML .= "<p><img src='/icons-smo/tick.gif' alt=''> $T_Chaidh_buidheann_ur_chur_ann</p>\n";
    } else {
        $h1 = ( $n==0 ? $T_Cruthaich_buidheann_ur : sprintf($T_Deasaich_buidheann_n,$n) );
        $HTML .= <<<EODHtmlCeann
<a href="./"><img src="dealbhan/sruth64.png" style="float:left;border:1px solid black;margin:0 2em 2em 0" alt=""></a>
<h1 class=smo>$h1</h1>
EODHtmlCeann;

        if (!empty($fiosMearachd)) {
            $HTML .= "<p style=\"color:red;font-weight:bold\">$fiosMearachd</p>\n";
        } else {
            $HTML .= <<<EODHtmlFoirm
<form method=get action="" style="clear:both">
<input type="hidden" name="n" value="$n">
<input type="hidden" name="s" value="$s">
<table id=form>
<tr><td>$T_fios</td><td><input style="width:40em" name="fios" value="$fiosRoimhe"></td></tr>
<tr><td colspan=2 style='text-align:left'><input type=submit name="sabhail" value="$T_Sabhail"></td></tr>
</table>
</form>
EODHtmlFoirm;
        }
    }

  } catch (Exception $e) {
      if (strpos($e,'Sgrios')!==FALSE) { $HTML = ''; }
      $HTML .= $e;
  }

  $stordataCss = SM_Sruth::stordataCss();
  $duilleagHTML = <<<EODduilleag
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="robots" content="noindex,nofollow">
    <title>An Sruth: $h1</title>$refreshHtml
    <link rel="StyleSheet" href="/css/smo.css">$stordataCss
    <style>
        table#form tr td:first-child { text-align:right; }
    </style>
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
