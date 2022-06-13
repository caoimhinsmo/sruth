<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
  header('Cache-Control:max-age=0');

  try {
      $myCLIL = SM_myCLIL::singleton();
  } catch (Exception $e) {
      $myCLIL->toradh = $e->getMessage();
  }

  $T = new SM_T('sruth/cobhair');
  $hl = $T::hl0();
  $T_Cobhair      = $T->h('Cobhair');
  $T_Books        = $T->h('Books');
  $T_Multilingual = $T->h('Multilingual');

  $stordataConnector = SM_Sruth::stordataConnector();
  $DbSruth = $stordataConnector::singleton('rw');
  $navbar = SM_Sruth::navbar($T->domhan);
  $stordataCss = SM_Sruth::stordataCss();

  if($hl=='gd')       { $h1 = "Làraichean-lìn eile a dh’fhaodadh a bhith feumail"; }
   elseif ($hl=='ga') { $h1 = "Láithreáin ghréasáin eile a dh’féadfá a bheith cabhrach"; }
   else               { $h1 = "Other useful websites";
                        $T_Multilingual = 'Multilingual';
                        $T_Books = 'Books'; }

  echo <<<EOD_HTML
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <title>An Sruth: $T_Cobhair</title>
    <link rel="StyleSheet" href="/css/smo.css">
    <link rel="StyleSheet" href="snas.css">$stordataCss
    <style>
        ul.naisg { margin-top:0.5em; }
        ul.naisg > li { margin-top:0.5em; }
    </style>
</head>
<body>

$navbar
<div class="smo-body-indent">
<a href="./"><img src="dealbhan/sruth64.png" style="float:left;border:1px solid black;margin:0 2em 2em 0" alt=""></a>

<h1 class=smo>$h1</h1>

<ul class=naisg style="clear:both">
<li>Gaeilge
   <ul>
   <li><a href="https://www.gaois.ie/ga/idioms/about/">Bailiúchán Nathanna an Athar Peadar Ó Laoghaire</a>
   </ul>
<li>$T_Multilingual
   <ul>
   <li><a href="https://tatoeba.org/">Tatoeba</a>
   </ul>
</ul>

<p style="margin:1.5em 0 0 0;border-top:2px solid blue;font-weight:bold;font-size:120%">$T_Books<p>
<ul class=naisg>
<li>Gaeilge
   <ul>
   <li><a href="https://books.google.co.uk/books?id=az3BDwAAQBAJ">Colourful Irish Phrases</a>, le Micheál Ó Conghaile
   </ul>
</ul>


</div>
$navbar

<div class="smo-latha">2022-05-04 <a href="//www.smo.uhi.ac.uk/~caoimhin/cpd.html">CPD</a></div>
</body>
</html>
EOD_HTML;

?>
