<?php
//Sguabaidh seo an abairt s bhon bhuidheann n
error_log('Ann an sguabSbhoN.php');

  if (!include('autoload.inc.php')) { die('include autoload failed'); }
  if (!isset($_REQUEST['s']))   { die('s is not set'); }
  if (!isset($_REQUEST['n']))   { die('n is not set'); }
  $s = $_REQUEST['s'];
  $n = $_REQUEST['n'];
  if (!SM_Sruth::ceadSgriobhaidh()) die('Duilich - Chan eil cead-sgrìobhaidh agad'); 
  $stordataConnector = SM_Sruth::stordataConnector();
  $DbSruth = $stordataConnector::singleton('rw');
  $stmtDEL = $DbSruth->prepare('DELETE FROM sruthns WHERE s=:s AND n=:n');
  $stmtDEL->execute(array(':s'=>$s,':n'=>$n));
  echo 'OK';

?>
