<?php
//Cuir an abairt sDrag anns a’ bhuidheann nDrop
//agus sguab às a’ bhuidheann sDrag i, ma tha parameter nDrag ann.

  if (!include('autoload.inc.php')) { die('include autoload failed'); }
  if (!isset($_REQUEST['sDrag']))   { die('sDrag is not set'); }
  if (!isset($_REQUEST['nDrop']))   { die('nDrop is not set'); }
  $sDrag = $_REQUEST['sDrag'];
  $nDrop = $_REQUEST['nDrop'];
  if (!SM_Sruth::ceadSgriobhaidh()) die('Duilich - Chan eil cead-sgrìobhaidh agad');
  $stordataConnector = SM_Sruth::stordataConnector();
  $DbSruth = $stordataConnector::singleton('rw');
  $DbSruth->beginTransaction();
  $stmtCuirriBuidheann = $DbSruth->prepare("INSERT IGNORE INTO sruthns(s,n) VALUES (:s,:n)");
  $stmtCuirriBuidheann->execute( array(':s'=>$sDrag, ':n'=>$nDrop) );
  if (isset($_REQUEST['nDrag'])) {
      $nDrag = $_REQUEST['nDrag'];
      $stmtDEL = $DbSruth->prepare('DELETE FROM sruthns WHERE s=:s AND n=:n');
      $stmtDEL->execute(array(':s'=>$sDrag,':n'=>$nDrag));
  }
  $DbSruth->commit();
  echo 'OK';

?>
