<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
  header('Cache-Control:max-age=0');

  try {
      $myCLIL = SM_myCLIL::singleton();
      if (!SM_Sruth::ceadSgriobhaidh()) { $myCLIL->diultadh(''); }
  } catch (Exception $e) {
      $myCLIL->toradh = $e->getMessage();
  }

  function uairHtml ($utime) {
      if (empty($utime)) { return ''; }
      $uairObject = new DateTime("@$utime");
      $latha     = date_format($uairObject, 'Y-m-d');
      $lathaUair = date_format($uairObject, 'Y-m-d H:i:s');
      return "<span title=\"$lathaUair UT\">$latha</span>";
  }

  try {
    $hl = SM_T::hl();
    $T = new SM_T('sruth/sgrud');
    $hl = $T::hl0();
    $navbar = SM_Sruth::navbar($T->domhan);

    $T_Ath_bhreithnich_fios    = $T->h('Ath_bhreithnich_fios');
    $T_Chan_eil_abairt         = $T->h('Chan_eil_abairt');
    $T_ntoraidheanFios         = $T->h('ntoraidheanFios');
    $T_abairt                  = $T->h('abairt');
    $T_abairtean               = $T->h('abairtean');
    $T_Cruthachadh             = $T->h('Cruthachadh');
    $T_Atharrachadh            = $T->h('Atharrachadh');
    $T_fiosSgrud1              = $T->h('fiosSgrud1');
    $T_fiosSgrud2              = $T->h('fiosSgrud2');
    $T_fiosSgrud3              = $T->h('fiosSgrud3');
    $T_Ceart                   = $T->h('Ceart');

    $T_fiosSgrud2 = strtr ( $T_fiosSgrud2, [ '✓' => "<img src='/icons-smo/ceart.png' alt='$T_Ceart'>" ] );

    $myCLIL->dearbhaich();
    $smid = $myCLIL->id;

    $stordataConnector = SM_Sruth::stordataConnector();
    $DbSruth = $stordataConnector::singleton('rw');
    $stordataCss = SM_Sruth::stordataCss();
    $liostaTeanga = SM_Sruth::liostaTeanga();
    $toraidheanHtml = '';
    $stmtSEL = $DbSruth->prepare("SELECT * FROM sruths WHERE sgrud=1 AND t IN $liostaTeanga ORDER BY mutime DESC,t,a");
    $stmtSEL->execute();
    $toraidhean = $stmtSEL->fetchAll(PDO::FETCH_ASSOC);
    $ntoraidhean = count($toraidhean);
    foreach ($toraidhean as $r) {
        extract($r);
        $cuair = uairHtml($cutime);
        $muair = uairHtml($mutime);
        $toraidheanHtml .= '<tr><td>' . SM_Sruth::sHtml($s) . "</td><td>$cuair $csmid</td><td>$muair $msmid</td></tr>\n";
    }
    $abairtHtml = ( $ntoraidhean==1 || ( $ntoraidhean==2 && in_array($hl,['gd','ga','gv']) )
                  ? $T_abairt
                  : $T_abairtean );
    if (empty($toraidheanHtml)) {
        $toraidheanHtml = <<<EODTOR0
<p style="font-style:italic">($T_Chan_eil_abairt)</p>
EODTOR0;
    } else {
        $ntoraidheanFios = sprintf($T_ntoraidheanFios,$ntoraidhean,$abairtHtml);
        $toraidheanHtml = <<<EODTOR
<p>$ntoraidheanFios</p>
<table id='tor'>
<tr style="text-decoration:underline;text-align:center"><td></td><td>$T_Cruthachadh</td><td>$T_Atharrachadh</td></tr>
$toraidheanHtml
</table>
EODTOR;
    }

  } catch (Exception $e) { echo $e; }

  echo <<<EOD_DUILLEAG
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <title>An Sruth: $T_Ath_bhreithnich_fios</title>
    <link rel="StyleSheet" href="/css/smo.css">
    <link rel="StyleSheet" href="snas.css">$stordataCss
    <style>
       table#tor { border-collapse:collapse; margin-top:1em; }
       table#tor td { padding:3px; }
       table#tor td:nth-child(2),
       table#tor td:nth-child(3)  { font-size:60%; padding:3px 12px; }
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

<a href="./"><img src="dealbhan/sruth64.png" style="float:left;border:1px solid black;margin:0 2em 2em 0" alt=""></a>
<h1 class=smo>$T_Ath_bhreithnich_fios</h1>
<div class="fios" style="clear:both;max-width:72em;padding:4px;border-radius:4px;border:1px solid green">
$T_fiosSgrud1<br>
$T_fiosSgrud2<br>
$T_fiosSgrud3</div>

$toraidheanHtml

</div>
$navbar

<div class="smo-latha">2017-04-28 <a href="//www.smo.uhi.ac.uk/~caoimhin/cpd.html">CPD</a></div>
</body>
</html>
EOD_DUILLEAG;

?>
