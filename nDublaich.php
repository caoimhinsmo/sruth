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
    $myCLIL->dearbhaich();
    $smid = $myCLIL->id;
    $navbar = SM_Sruth::navbar();
    $sruthURL = SM_Sruth::sruthurl();

    $T = new SM_T('sruth/nDublaich');
    $hl = $T::hl0();
    $T_Parameter_mi_iom = $T->h('Parameter_mi_iom');

    $nUr = -1;
    $HTML = $refreshHTML = '';

    $n = (!isset($_REQUEST['n']) ? 0 : $_REQUEST['n']);
    $n = htmlspecialchars($n);
    if (!is_numeric($n) || intval($n)<>$n || $n<1) { throw new SM_Exception("$T_Parameter_mi_iom: n=$n"); }

    $stordataConnector = SM_Sruth::stordataConnector();
    $DbSruth = $stordataConnector::singleton('rw');
    $stmtNSEL = $DbSruth->prepare('SELECT fios FROM sruthn WHERE n=:n');
    $stmtNSEL->execute(array(':n'=>$n));
    if (!$row = $stmtNSEL->fetch(PDO::FETCH_ASSOC)) { throw new SM_Exception("Chan eil buidheann ann leis an àireamh sin, $n"); }
    $fios   = $row['fios'];
    $DbSruth->beginTransaction();
    $stmtNINS = $DbSruth->prepare('INSERT INTO sruthn (fios) VALUES (:fios)');
    $stmtNINS->execute(array(':fios'=>$fios));
    $nUr = $DbSruth->lastInsertId();
    $stmtNSSEL = $DbSruth->prepare('SELECT s,astar,meit FROM sruthns WHERE n=:n');
    $stmtNSSEL->execute(array(':n'=>$n));
    $rows = $stmtNSSEL->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $s     = $row['s'];
        $astar = $row['astar'];
        $meit  = $row['meit'];
        $stmtNSINS = $DbSruth->prepare('INSERT INTO sruthns (n,s,astar,meit) VALUES (:n,:s,:astar,:meit)');
        $stmtNSINS->execute(array(':n'=>$nUr,':s'=>$s,':astar'=>$astar,'meit'=>$meit));
    }
    $DbSruth->commit();

    if ($nUr>0) {
        $refreshHtml = "\n    <meta http-equiv=refresh content='1;url=$sruthURL/n.php?n=$nUr'>";
        $HTML = "<p>Chaidh buidheann $n a chopaigeadh gu buidheann ùr $nUr</p>\n";
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
    <title>An Sruth: Dùblaich buidheann $n</title>$refreshHtml
    <link rel="StyleSheet" href="/css/smo.css">$stordataCss
</head>
<body>

$navbar
<div class="smo-body-indent">

$HTML

</div>
$navbar

<div class="smo-latha">2016-04-12 <a href="//www.smo.uhi.ac.uk/~caoimhin/cpd.html">CPD</a></div>
</body>
</html>
EODduilleag;

echo $duilleagHTML;

?>
