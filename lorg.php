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

  $T = new SM_T('sruth/lorg');
  $hl = $T::hl0();
  $T_Lorg_abairt          = $T->_('Lorg abairt','hsc');
  $T_Lorg                 = $T->_('Lorg');
  $T_Canan                = $T->_('Language');
  $T_Abairt               = $T->_('Abairt');
  $T_Abairt_taic          = $T->_('Abairt_taic');
  $T_abairt               = $T->_('abairt');
  $T_abairtean            = $T->_('abairtean');
  $T_Bitheantas           = $T->_('Bitheantas');
  $T_Chaidh_n_rud_a_lorg  = $T->_('Chaidh_n_rud_a_lorg');

  $stordataConnector = SM_Sruth::stordataConnector();
  $DbSruth = $stordataConnector::singleton('rw');
  $navbar = SM_Sruth::navbar($T->domhan);
  $stordataCss = SM_Sruth::stordataCss();
  $ainmTeanga = SM_Sruth::ainmTeanga();
  $teangaithe = array_keys($ainmTeanga);
  $liostaTeanga = SM_Sruth::liostaTeanga();
  if (!isset($_REQUEST['a']) || trim($_REQUEST['a'])=='') {
      $aqHtml = '';
      $tq = '';
  } else {
      $aq = $_REQUEST['a'];
      if (is_numeric($aq)) {
          $aqHtml = $aq;
          $tq = '';
      } else {
          $aq = '%' . strtr($aq,array('*'=>'%')) . '%';
          $aq = strtr($aq,array('%_'=>'%','_%'=>'%','%%'=>'%'));
          $aqHtml = strtr(htmlspecialchars(substr($aq,1,strlen($aq)-2)),array('%'=>'*'));
          if (empty($aqHtml)) { $aqHtml = '*'; }
          $tq = ( empty($_REQUEST['t']) ? '' : $_REQUEST['t'] );
          if (!in_array($tq,$teangaithe)) { $tq = ''; }
      }
  }
  $pailtq = $_REQUEST['pailt'] ?? 0;
  $selectTHtml  = "<select name='t'>\n";
  $selectTHtml .= "<option value=''" . ($tq=='' ? ' selected' : '') . '' . "</option>\n";
  foreach ($teangaithe as $t) { $selectTHtml .= "<option value='$t'" . ($tq==$t ? ' selected' : '') . '>' . $ainmTeanga[$t] . "</option>\n"; }
  $selectTHtml .= "</select>\n";  

  $toraidheanHtml = '';
  if (isset($_REQUEST['a'])) {
      $aCondition = ( is_numeric($aq) ? 's=:a' : 'a LIKE :a' );
      $tCondition = ( $tq=='' ? "t IN $liostaTeanga" : "t LIKE '$tq'" );
      $querySEL = "SELECT * FROM sruths WHERE $tCondition AND $aCondition AND pailt>=:pailt ORDER BY t,a";
      $stmtSEL = $DbSruth->prepare($querySEL);
      $stmtSEL->execute(array(':a'=>$aq, ':pailt'=>$pailtq));
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
<table id=formtab>
<tr><td>$T_Canan</td><td>$T_Abairt</td><td style="text-align:center">$T_Bitheantas</td><td></td></tr>
<tr>
<td>$selectTHtml</td>
<td><input name="a" value="$aqHtml" placeholder="$T_Abairt_taic" style="width:25em"></td>
<td><input name="pailt" value="$pailtq" min=0 max=3 step=1 type="range">
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
    <title>An Sruth: $T_Lorg_abairt</title>
    <link rel="StyleSheet" href="/css/smo.css">
    <link rel="StyleSheet" href="snas.css">$stordataCss
    <style>
       table#formtab { border-collapse:collapse; }
       table#formtab td { padding:0 4px; }
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
<h1 class=smo>$T_Lorg_abairt</h1>

$formHtml

$toraidheanHtml
EODHtmlCeann;

  } catch (Exception $e) { echo $e; }

  echo <<<EODHtmlEis
</div>
$navbar

<div class="smo-latha">2017-05-15 <a href="//www.smo.uhi.ac.uk/~caoimhin/cpd.html">CPD</a></div>
</body>
</html>
EODHtmlEis

?>
