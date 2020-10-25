<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
  header('Cache-Control:max-age=0');

  try {
      $moSMO = SM_moSMO::singleton();
      if (!SM_Sruth::ceadSgriobhaidh()) { $moSMO->diultadh(''); }
  } catch (Exception $e) {
      $moSMO->toradh = $e->getMessage();
  }

  try {

    $stordataConnector = SM_Sruth::stordataConnector();
    $DbSruth = $stordataConnector::singleton('rw');
    $moSMO->dearbhaich();

    $T = new SM_T('sruth/nSguab');
    $hl = $T::hl0();
    $T_Parameter_mi_iom = $T->_('Parameter_mi_iom');

    $n = (!isset($_REQUEST['n']) ? 0 : $_REQUEST['n']);
    $n = htmlspecialchars($n);
    if (!is_numeric($n) || intval($n)<>$n || $n<1) { throw new SM_Exception("$T_Parameter_mi_iom: n=$n"); }

    $stmtDELETEbundf = $DbSruth->prepare('DELETE FROM sruthns WHERE n=:n')->execute(array(':n'=>$n));
    $stmtDELETEbund  = $DbSruth->prepare('DELETE FROM sruthn  WHERE n=:n')->execute(array(':n'=>$n));

    $HTML = "<p style='font-size:140%;font-weight:bold'><img src='/icons-smo/sgudal.png' alt=''> Chaidh buidheann $n a sguabadh às</p>\n";

  } catch (Exception $e) {
      if (strpos($e,'Sgrios')!==FALSE) { $HTML = ''; }
      $HTML .= $e;
  }

  $stordataCss = SM_Sruth::stordataCss();
  echo <<<EODduilleag
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="robots" content="noindex,nofollow">
    <title>Sruth: Sguab às buidheann $n</title>
    <meta http-equiv=refresh content="1;url=./">
    <link rel="StyleSheet" href="/css/smo.css">$stordataCss
</head>
<body>
$HTML
EODduilleag;

?>
