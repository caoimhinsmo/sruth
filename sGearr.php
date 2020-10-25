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

  $s    = (empty($_REQUEST['s'])    ? '' : $_REQUEST['s']);
  $till = (empty($_REQUEST['till']) ? '' : $_REQUEST['till']);
  $s    = htmlspecialchars($s);    //paranoia
  $till = htmlspecialchars($till);

  try {
    $T = new SM_T('sruth/sGearr');
    $hl = $T::hl0();
    $navbar = SM_Sruth::navbar($T->domhan);

    $T_Chan_eil_abairt_le_aireamh_sin   = $T->_('Chan_eil_abairt_le_aireamh_sin');
    $T_Chaidh_abairt_s_a_ghearradh      = $T->_('Chaidh_abairt_s_a_ghearradh');

    $moSMO->dearbhaich();
    $stordataConnector = SM_Sruth::stordataConnector();
    $DbSruth = $stordataConnector::singleton('rw');
    $navbar = SM_Sruth::navbar();
    $stordataCss = SM_Sruth::stordataCss();

    $DbSruth->beginTransaction();
    $stmtSELsruths = $DbSruth->prepare('SELECT * FROM sruths WHERE s=:s');
    $stmtSELsruths->execute(array(':s'=>$s));
    if (!$row = $stmtSELsruths->fetch()) { throw new SM_Exception("$T_Chan_eil_abairt_le_aireamh_sin, s=$s"); }
    extract($row);
    $mirean = explode(';',$a);
    foreach ($mirean as &$mir) {
        $mir = trim($mir);
        if (empty($mir)) { unset($mir); }
    }
    unset($mir);
    $stmtSELsruthc = $DbSruth->prepare('SELECT c FROM sruthc WHERE s=:s');
    $stmtSELsruthc->execute(array(':s'=>$s));
    $cinnFhacail = $stmtSELsruthc->fetchAll(PDO::FETCH_COLUMN,0);
    $stmtSELsruthns = $DbSruth->prepare('SELECT n FROM sruthns WHERE s=:s');
    $stmtSELsruthns->execute(array(':s'=>$s));
    $buidhnean = $stmtSELsruthns->fetchAll(PDO::FETCH_COLUMN,0);
    foreach ($mirean as $mir) {
        SM_Sruth::insertAbairt($t,$mir,$cinnFhacail,$buidhnean,$csmid,$cutime,1);
    }
    $stmtDELETEsruthns = $DbSruth->prepare('DELETE FROM sruthns WHERE s=:s')->execute(array(':s'=>$s));
    $stmtDELETEsruthc  = $DbSruth->prepare('DELETE FROM sruthc WHERE s=:s')->execute(array(':s'=>$s));
    $stmtDELETEsruths  = $DbSruth->prepare('DELETE FROM sruths WHERE s=:s')->execute(array(':s'=>$s));
    $DbSruth->commit();
    $T_Chaidh_abairt_s_a_ghearradh = sprintf($T_Chaidh_abairt_s_a_ghearradh,$s);
 
    echo <<<EODHtml
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <title>An Sruth: Geàrr abairt $s na mìrean</title>
    <meta http-equiv=refresh content="1;url=$till">
    <link rel="StyleSheet" href="/css/smo.css">
</head>
<body>
<p style="font-size:140%;font-weight:bold"><img src="/icons-smo/tick.gif" alt="">$T_Chaidh_abairt_s_a_ghearradh</p>
EODHtml;

  } catch (Exception $e) { echo $e; }

?>
