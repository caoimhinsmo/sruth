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

  try {
    $T = new SM_T('sruth/sSguab');
    $hl = $T::hl0();
    $T_fiosSoirbheis   = $T->h('fiosSoirbheis');

    $stordataConnector = SM_Sruth::stordataConnector();
    $DbSruth = $stordataConnector::singleton('rw');
    $stordataCss = SM_Sruth::stordataCss();
    $myCLIL->dearbhaich();

    $s    = $_REQUEST['s']    ?? '';
    $till = $_REQUEST['till'] ?? '';
    $s    = htmlspecialchars($s);    //paranoia
    $till = htmlspecialchars($till);

    $stmtDELETEsruthns = $DbSruth->prepare('DELETE FROM sruthns WHERE s=:s')->execute(array(':s'=>$s));
    $stmtDELETEsruthc  = $DbSruth->prepare('DELETE FROM sruthc WHERE s=:s')->execute(array(':s'=>$s));
    $stmtDELETEsruths  = $DbSruth->prepare('DELETE FROM sruths WHERE s=:s')->execute(array(':s'=>$s));

    $T_fiosSoirbheis = sprintf($T_fiosSoirbheis,$s);
    $HTML = "<p style='font-size:140%;font-weight:bold'><img src='/icons-smo/sgudal.png' alt=''> $T_fiosSoirbheis</p>";

  } catch (Exception $e) { $HTML = $e; }

  echo <<<EOD_DUILLEAG
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <title>An Sruth: Sguab às abairt $s</title>
    <meta http-equiv=refresh content="1;url=$till">
    <link rel="StyleSheet" href="/css/smo.css">
</head>
<body>

$HTML

</body>
</html>
EOD_DUILLEAG;

?>
