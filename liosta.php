<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
  header('Cache-Control:max-age=0');

  try {
    $T = new SM_T('sruth/liosta');
    $hl = $T::hl0();
    $T_Liosta                 = $T->h('Liosta');
    $T_gu                     = $T->h('gu');
    $T_Nochd_gach_abairt      = $T->h('Nochd_gach_abairt');
    $T_Nochd_gach_abairt_fios = $T->h('Nochd_gach_abairt_fios');
    $T_Astar                  = $T->h('Astar');
    $T_Astar_fios             = $T->h('Astar_fios');
    $T_astar                  = $T->h('astar');
    $T_Uraich                 = $T->h('Uraich');
    $T_Uraich_fios            = $T->h('Uraich_fios');
    $T_Bitheantas             = $T->h('Bitheantas');
    $T_Bitheantas_fios        = $T->h('Bitheantas_fios');
    $T_Briog_gus_suaip        = $T->h('Briog_gus_suaip');

    $ainmTeanga = SM_Sruth::ainmTeanga();
    $teangaithe = array_keys($ainmTeanga);
    $t1 = 'ga';
    $t2 = 'gd';
    if (isset($_GET['t1'])) { $t1 = $_GET['t1']; }
    if (isset($_GET['t2'])) { $t2 = $_GET['t2']; }
    if (!in_array($t1,$teangaithe)) { throw new SM_Exception("Parameter ceàrr: chan eil t1='$t1' ceadaichte"); }
    if (!in_array($t2,$teangaithe)) { throw new SM_Exception("Parameter ceàrr: chan eil t2='$t2' ceadaichte"); }
    $teanga1 = $ainmTeanga[$t1];
    $teanga2 = $ainmTeanga[$t2];
    $astarMax  = ( isset($_GET['astarMax'])  ? $_GET['astarMax'] : 2 );
    $nochdUile = ( empty($_GET['nochdUile']) ? FALSE : TRUE );
    $nochdUileChecked = ( $nochdUile ? ' checked' : '');
    $T_Nochd_gach_abairt_fios = sprintf($T_Nochd_gach_abairt_fios,$teanga1,$teanga2);

    $selectT1Html = "<select name='t1' onchange='priomhSubmit()'>\n";
    $selectT2Html = "<select name='t2' onchange='priomhSubmit()'>\n";
    foreach ($teangaithe as $t) { $selectT1Html .= "<option value='$t'" . ($t==$t1?' selected':'') . '>' . $ainmTeanga[$t] . "</option>\n"; }
    foreach ($teangaithe as $t) { $selectT2Html .= "<option value='$t'" . ($t==$t2?' selected':'') . '>' . $ainmTeanga[$t] . "</option>\n"; }
    $selectT1Html .= "</select>\n";
    $selectT2Html .= "</select>\n";

    $stordataConnector = SM_Sruth::stordataConnector();
    $DbSruth = $stordataConnector::singleton('rw');
    $navbar = SM_Sruth::navbar($T->domhan);
    $stordataCss = SM_Sruth::stordataCss();

    $pailt1 = $_REQUEST['pailt1'] ?? 0;  $rionnagan1 = str_repeat('★',$pailt1);
    $pailt2 = $_REQUEST['pailt2'] ?? 0;  $rionnagan2 = str_repeat('★',$pailt2);
    $stmt = $DbSruth->prepare("SELECT DISTINCT c,sruthc.s FROM sruthc, sruths WHERE sruths.s=sruthc.s AND sruths.t=:t AND sruths.pailt>=$pailt1 ORDER BY c,sruths.a");
    $stmt->execute(array(':t'=>$t1));
    $cSruthArr = [];
    $cRoimhe = null;

    foreach ($stmt->fetchAll() as $res) {
        extract($res);
        if ($cRoimhe<>$c) { $cSruthArr[$c]   = [$s]; }
          else            { $cSruthArr[$c][] =  $s;  }
        $cRoimhe = $res['c'];
    }

    $sruth = new SM_Sruth();

    $stmtSELs = $DbSruth->prepare('SELECT t, pailt FROM sruths WHERE s=:s');
    $stmtSELs->bindColumn(1,$t);
    $stmtSELs->bindColumn(2,$pailt);
    $resultHTML = '';
    $nabairt = 0;
    foreach ($cSruthArr as $c=>$sArr) {
        $cStuth = '';
        foreach ($sArr as $s) {
            $nabArr = $sruth->nabaidhean($s,$astarMax);
            $nabHtmlArr = [];
            foreach ($nabArr as $nab=>$astar) {
                $stmtSELs->execute([':s'=>$nab]);
                $stmtSELs->fetch();
                if ($pailt<$pailt2 || $t<>$t2) { continue; }
                $put = SM_Sruth::sHtml($nab);
                $fontsize = round(600.0/(4+$astar));
                $put = "<span style='font-size:$fontsize%' title='$T_astar: $astar'>$put</span>";
                $nabHtmlArr[] = $put;
            }
            $nabHTML = implode(' ',$nabHtmlArr);
            if ($nochdUile || !empty($nabHTML)) {
                $cStuth .= '<p class=abairt>' . SM_Sruth::sHtml($s) . " — $nabHTML</p>\n";
                $nabairt++;
            }
        }
        if (!empty($cStuth)) { $resultHTML .= "<p class='ceann' lang='$t1'>$c</p>" . $cStuth; }
    }
    $php_self = $_SERVER['PHP_SELF'];

    $html = <<<END_HTML
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <title>An Sruth: $T_Liosta $teanga1 $T_gu $teanga2</title>
    <link rel="StyleSheet" href="/css/smo.css">
    <link rel="StyleSheet" href="snas.css">$stordataCss
    <style>
        p.ceann { margin:24px 0 12px 0; font-weight:bold; background-color:#eee; border-top:3px solid #444; border-bottom:1px solid #444; }
        p.abairt { margin:0 0 2px 3em; line-height:150%; text-indent:-2em; }
        select, option { font-size:100%; }
        form#priomhFoirm table { margin 4px 0; }
        form#priomhFoirm table td:nth-child(1) { text-align:right; }
        form#priomhFoirm table td:nth-child(2) { text-align:center; }
        form#priomhFoirm table td:nth-child(4) { text-align:center; }
    </style>
    <script>
        function priomhSubmitIf() {
            if (document.getElementById('astarMax').value != astarMaxRoimhe) { priomhSubmit(); }
        }

        function priomhSubmit() {
            document.getElementById('priomhFoirm').style.backgroundColor='#a88';
            document.getElementById('priomhFoirm').submit();
        }

        function pailtRionagan(id,val) {
            document.getElementById(id).value = '★'.repeat(val);
        }
    </script>
</head>
<body style="font-size:125%">

$navbar
<div class="smo-body-indent">

<a href="./"><img src="dealbhan/sruth64.png" alt="An Sruth" style="float:left;border:1px solid black;margin:0 1em 1em 0"></a>
<form id='priomhFoirm'>
<table style="border-collapse:collapse">
<tr style="font-weight:bold;font-size:160%">
<td>$T_Liosta</td>
<td>$selectT1Html</td>
<td><a href="$php_self?t1=$t2&amp;t2=$t1" title="$T_Briog_gus_suaip">▶</a></td>
<td>$selectT2Html</td>
</tr>
<tr title="$T_Bitheantas_fios">
<td>$T_Bitheantas</td>
<td><input id=pailt1 type=range name=pailt1 value="$pailt1" min=0 max=3 step=1 style="width:150px;height:20px" onchange="pailtRionagan('pailt1out',this.value)" oninput="pailtRionagan('pailt1out',this.value)"></td><td></td>
<td><input id=pailt2 type=range name=pailt2 value="$pailt2" min=0 max=3 step=1 style="width:150px;height:20px" onchange="pailtRionagan('pailt2out',this.value)" oninput="pailtRionagan('pailt2out',this.value)"></td>
</tr>
<tr style="color:orange;font-size:80%;font-weight:bold">
<td></td><td><output for="pailt1" id="pailt1out">$rionnagan1</output></td>
<td></td><td><output for="pailt2" id="pailt2out">$rionnagan2</output></td>
</tr>
</table>
<div style="margin-left:90px;border2:1px solid;padding:1px">
<p style="margin:4px 0;font-size:80%" title="$T_Nochd_gach_abairt_fios">
 <input type="checkbox" name="nochdUile" id="nochdUile"$nochdUileChecked onclick="priomhSubmit();">
 <label for="nochdUile">$T_Nochd_gach_abairt</label></p>
<p style="margin:7px 0"><span title="$T_Astar_fios">
 <label for="astarMax">$T_Astar</label>
 <input id="astarMax" name="astarMax" type="range" min="0" max="8" step="0.1" value="$astarMax" style="width:60em;height:20px;padding:0" list="astTicks" oninput="document.getElementById('astarMaxOut').value=this.value;" onchange="document.getElementById('astarMaxOut').value=this.value;" onmouseout="priomhSubmitIf();">
 <datalist id="astTicks"><option>2</option><option>4</option><option>6</option></datalist>
 <output for="astarMax" id="astarMaxOut">$astarMax</output></span>
 <input type="submit" name="cuir" value="$T_Uraich" title="$T_Uraich_fios"></p>
</div>
</form>
<script> astarMaxRoimhe = $astarMax; </script>
<p style="clear:both"></p>


$resultHTML
<div style="float:left;border:1px solid brown;font-size:30%;padding:1px;color:brown">$nabairt</div><br>

</div>
$navbar

<div class="smo-latha">2017-09-04 <a href="/~caoimhin/cpd.html">CPD</a></div>
</body>
</html>
END_HTML;

    echo $html;

  } catch (exception $e) { echo $e; }
?>
