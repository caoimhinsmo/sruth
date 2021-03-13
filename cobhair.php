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
  $T_Cobhair = $T->h('Cobhair');

  $stordataConnector = SM_Sruth::stordataConnector();
  $DbSruth = $stordataConnector::singleton('rw');
  $navbar = SM_Sruth::navbar($T->domhan);
  $stordataCss = SM_Sruth::stordataCss();

  if ($hl=='gd') { $cobhairHtml = <<<EOD_gd
<p>Is féidir <i>An Sruth</i> a chuardach, mar fhoclóir, agus mar thesaurus geall leis, mar seo a leanas:</p>

<ul>
<li>Fo <b>Liosta</b> (anns a bheil na h-abairtean air fad a rèir na h-aibidile) a’ cleachdadh Find/Ctrl+F:
</ul>

<p class=eagaich>
Gàidhlig &gt; Gaeilge<br>
Gàidhlig &gt; English<br>
Gàidhlig &gt; Gàidhlig<br>
<br>
Gaeilge &gt; Gàidhlig<br>
Gaeilge &gt; English<br>
Gaeilge &gt; Gaeilge<br>
<br>
English &gt; Gàidhlig<br>
English &gt; Gaeilge<br>
English &gt; English</p>

<p class=eagaich>Mar eisimpleir: Cuir a-steach ‘ciamar’ sa bhogsa iomchaidh aig a’ bhonn agus nochdaidh an dèidh a chèile na toraidhean far a bheil am facal sin a’ nochdadh.</p>

<ul>
<li>Fo <b>Lorg abairt</b>: Cànan-Abairt-Bitheantas (a’ leigeil fhaicinn cho cumanta agus a tha cleachdadh a rèir nan tomhas 0 – ainneamh; 1 - cumanta; 2 - gu math cumanta; 3 – anabarrach fhèin cumanta.). Gheibhear a h-uile gin de na h-abairtean le bhith a’ cleachdadh * agus gabhaidh sin briseadh sìos a rèir cànain an uair sin.
</ul>

<p class=eagaich>Mar eisimpleir: Cuir a-steach ‘ciamar a tha thu’ san raon seo agus nochdaidh na toraidhean air fad far a bheil an abairt sin.</p>

<ul>
<li>Fo <b>Lorg facal-cinn</b>: Cànan-Facal-cinn. Gheibhear a h-uile gin de na faclan-cinn le bhith a’ cleachdadh * agus gabhaidh sin briseadh sìos a rèir cànain an uair sin.
</ul>

<p class=eagaich>Mar eisimpleir: Cuir a-steach ‘uisge’ san raon seo agus nochdaidh na toraidhean air fad far a bheil am facal-cinn sin.</p>
EOD_gd;

  } elseif ($hl=='ga') { $cobhairHtml = <<<EOD_ga
<p>Gabhaidh An Sruth rannsachadh, mar bhriathrachan, agus mar thesaurus gu ìre, mar a leanas:</p>

<ul>
<li>Faoi <b>Liosta</b> (ina bhfuil na leaganacha ar fad de réir na haibítre) ag baint feidhme as Find/Ctrl+F:
</ul>

<p class=eagaich>
Gàidhlig &gt; Gaeilge<br>
Gàidhlig &gt; English<br>
Gàidhlig &gt; Gàidhlig<br>
<br>
Gaeilge &gt; Gàidhlig<br>
Gaeilge &gt; English<br>
Gaeilge &gt; Gaeilge<br>
<br>
English &gt; Gàidhlig<br>
English &gt; Gaeilge<br>
English &gt; English</p>

<p class=eagaich>Mar shampla: Cuir isteach ‘conas’ sa bhosca cuí ag bun an leathanaigh agus nochtfaidh i ndiaidh a chéile na torthaí ina nochtann an focal sin.</p>

<ul>
<li>Faoi <b>Cuardaigh leagan</b>: Teanga-Leagan-Coitiantacht (ag tabhairt le fios chomh coitianta agus atá leagan de réir na dtomhas 0 – annamh; 1 - coitianta; 2 – cineál coitianta; 3 – thar a bheith coitianta.). Tá an uile cheann de na leaganacha le fáil trí fheidhm a bhaint as * agus is féidir sin a bhriseadh síos de réir teanga ansin.
</ul>

<p class=eagaich>Mar shampla: Cuir isteach ‘conas tá tú’ sa réimse seo agus nochtfaidh na torthaí ina bhfuil an leagan sin.</p>

<ul>
<li>Faoi Lorg <b>Cuardaigh ceannfhocal</b>: Tá an uile cheann de na ceannfhocail le fáil trí fheidhm a bhaint as * agus is féidir sin a bhriseadh síos de réir teanga ansin.
</ul>

<p class=eagaich>Mar shampla: Cuir isteach ‘báisteach’ sa réimse seo agus nochtfaidh na torthaí ina bhfuil an ceannfhocal sin.</p>
EOD_ga;

  } elseif ($hl=='en') { $cobhairHtml = <<<EOD_en
<p><i>An Sruth</i> is searchable, as a vocabulary, and a thesaurus to an extent, as follows:</p>

<ul>
<li>Under <b>Liosta</b> (containing all usages in alphabetical order) using Find/Ctrl+F:
</ul>

<p class=eagaich>
Gàidhlig &gt; Gaeilge<br>
Gàidhlig &gt; English<br>
Gàidhlig &gt; Gàidhlig<br>
<br>
Gaeilge &gt; Gàidhlig<br>
Gaeilge &gt; English<br>
Gaeilge &gt; Gaeilge<br>
<br>
English &gt; Gàidhlig<br>
English &gt; Gaeilge<br>
English &gt; English</p>

<p class=eagaich>For example: Enter ‘how’ in the relevant box at the bottom of tha page and results including that word will appear in sequence.</p>

<ul>
<li>Under <b>Find phrase</b>: Language-Phrase-Frequency (indicating how common phrases are, according to the scale 0 – rare; 1 - common; 2 – quite common; 3 – exceedingly common.). All phrases appear when using * and this can be broken down then according to language.
</ul>

<p class=eagaich>For example: Enter ‘how are you’ in this field and the results in which that usage appears will be returned.</p>

<ul>
<li>Under <b>Find keyword</b>: All keywords appear when using * and this can be broken down then according to language.
</ul>

<p class=eagaich>Enter ‘rain’ in this field and the results in which that headword appears will be returned</p>
EOD_en;

  } else { $cobhairHtml = <<<EOD_gunCobhair
<p>Duilich - Chan eil duilleag cóbhrach againn sa chànan agad<br><br>Sorry - We have no help page in your language</p>
EOD_gunCobhair;
  }

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
        p.eagaich { margin-left:2.5em; }
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
<h1 class=smo>$T_Cobhair</h1>

<div style="clear:both">
$cobhairHtml
</div>

</div>
$navbar

<div class="smo-latha">2019-03-07 <a href="//www.smo.uhi.ac.uk/~caoimhin/cpd2.html">CPD</a></div>
</body>
</html>
EOD_HTML;

?>
