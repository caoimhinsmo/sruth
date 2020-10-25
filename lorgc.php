<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
  header('Cache-Control:max-age=0');

  try {
      $moSMO = SM_moSMO::singleton();
//      if (!$moSMO->cead('{logged-in}')) { $moSMO->diultadh(''); }
  } catch (Exception $e) {
      $moSMO->toradh = $e->getMessage();
  }
  $smid = $moSMO->id;

  $T = new SM_T('sruth/lorgc');
  $hl = $T::hl0();
  $T_Lorg_facal_cinn      = $T->_('Lorg facal-cinn','hsc');
  $T_Lorg                 = $T->_('Lorg');
  $T_Canan                = $T->_('Language');
  $T_Facal_cinn           = $T->_('Facal-cinn');
  $T_Facal_cinn_taic      = $T->_('Facal_cinn_taic');
  $T_abairt               = $T->_('abairt');
  $T_abairtean            = $T->_('abairtean');
  $T_Chaidh_n_rud_a_lorg  = $T->_('Chaidh_n_rud_a_lorg');

  $stordataConnector = SM_Sruth::stordataConnector();
  $DbSruth = $stordataConnector::singleton('rw');
  $navbar = SM_Sruth::navbar($T->domhan);
  $stordataCss = SM_Sruth::stordataCss();
  $ainmTeanga = SM_Sruth::ainmTeanga();
  $teangaithe = array_keys($ainmTeanga);
  $liostaTeanga = SM_Sruth::liostaTeanga();
  if (!isset($_REQUEST['c']) || trim($_REQUEST['c'])=='') {
      $cqHtml = '';
      $tq = '';
  } else {
      $cq = strtr($_REQUEST['c'],array('*'=>'%'));
      $cq = strtr($cq,array('%_'=>'%','_%'=>'%','%%'=>'%'));
      $cqHtml = strtr(htmlspecialchars($cq),array('%'=>'*'));
      $tq = ( empty($_REQUEST['t']) ? '' : $_REQUEST['t'] );
      if (!in_array($tq,$teangaithe)) { $tq = ''; }
  }
  $selectTHtml  = "<select name='t'>\n";
  $selectTHtml .= "<option value=''" . ($tq=='' ? ' selected' : '') . '' . "</option>\n";
  foreach ($teangaithe as $t) { $selectTHtml .= "<option value='$t'" . ($tq==$t ? ' selected' : '') . '>' . $ainmTeanga[$t] . "</option>\n"; }
  $selectTHtml .= "</select>\n";  

  $toraidheanHtml = '';
  if (isset($_REQUEST['c'])) {
      $tCondition = ( $tq=='' ? "t IN $liostaTeanga" : "t LIKE '$tq'" );
      $stmtSEL = $DbSruth->prepare("SELECT sruths.s,t,a FROM sruthc,sruths WHERE sruthc.s=sruths.s AND $tCondition AND c LIKE :c ORDER BY t,a");
      $stmtSEL->execute(array(':c'=>$cq));
      $toraidhean = $stmtSEL->fetchAll(PDO::FETCH_OBJ);
      $ntoraidhean = count($toraidhean);
      foreach ($toraidhean as $r) {
          $s = $r->s;
          $t = $r->t;
          $a = $r->a;
          $toraidheanHtml .= '<tr><td>' . SM_Sruth::sHtml($s) . "</td></tr>\n";
      }
      $abairtHtml = ( $ntoraidhean==1 || ( $ntoraidhean==2 && $T->tArr[0]<>'en' )
                    ? $T_abairt
                    : $T_abairtean );
      $T_Chaidh_n_rud_a_lorg = sprintf($T_Chaidh_n_rud_a_lorg,$ntoraidhean,$abairtHtml);
      $toraidheanHtml = <<<EODTOR
<p style="margin-top:2em;background-color:grey; color:white; padding:2px 6px; max-width:50em">$T_Chaidh_n_rud_a_lorg</p>
<table id='tor'>
$toraidheanHtml
</table>
EODTOR;
  }

  $formHtml = <<<EODFORM
<form method="get" style="clear:both">
<table>
<tr><td>$T_Canan</td><td>$T_Facal_cinn</td><td></td></tr>
<tr>
<td>$selectTHtml</td>
<td><input name="c" value="$cqHtml" placeholder="$T_Facal_cinn_taic" style="width:25em"></td>
<td><input type="submit" name="lorg" value="$T_Lorg"></td>
</table>
</tr>
</form>
EODFORM;

  echo <<<EODHtmlTus
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="robots" content="noindex,nofollow">
    <title>An Sruth: $T_Lorg_facal_cinn</title>
    <link rel="StyleSheet" href="/css/smo.css">
    <link rel="StyleSheet" href="snas.css">$stordataCss
    <style>
       table#tor { border-collapse:collapse; margin-top:1em; color:red; }
       table#tor td { padding:5px; }
    </style>
    <script>
        function teangaUr(sel) {
            sel.parentNode.parentNode.lang = sel.value;
        }
    </script>
</head>
<body>

$navbar
<div class="smo-body-indent">
EODHtmlTus;

  try {
    $moSMO->dearbhaich();
    $smid = $moSMO->id;
    echo <<<EODHtmlCeann
<a href="./"><img src="dealbhan/sruth64.png" style="float:left;border:1px solid black;margin:0 2em 2em 0" alt=""></a>
<h1 class=smo>$T_Lorg_facal_cinn</h1>

$formHtml

$toraidheanHtml
EODHtmlCeann;

  } catch (Exception $e) { echo $e; }

  echo <<<EODHtmlEis
</div>
$navbar

<div class="smo-latha">2017-01-20 <a href="//www.smo.uhi.ac.uk/~caoimhin/cpd.html">CPD</a></div>
</body>
</html>
EODHtmlEis

?>
