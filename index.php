<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  try {
      $myCLIL = SM_myCLIL::singleton();
//      if (!$myCLIL->cead('{logged-in}')) { $myCLIL->diultadh(''); }
  } catch (Exception $e) {
      $myCLIL->toradh = $e->getMessage();
  }

  try {
    $T = new SM_T('sruth/index');
    $hl = $T::hl0();
    $T_Liosta                      = $T->h('Liosta');
    $T_Lorg_abairt                 = $T->h('Lorg abairt','hsc');
    $T_Lorg_facal_cinn             = $T->h('Lorg facal-cinn','hsc');
    $T_fo_thiotal                  = $T->h('T_fo_thiotal','hsc');
    $T_stordata                    = $T->h('Stòr-dàta','hsc');
    $T_stordata_fios               = $T->h('stordata_fios','hsc');
    $T_Cruthaich_buidheann_ur      = $T->h('Cruthaich_buidheann_ur');
    $T_Cruthaich_buidheann_ur_fios = $T->h('Cruthaich_buidheann_ur_fios');
    $T_Cruthaich_abairt_ur         = $T->h('Cruthaich_abairt_ur');
    $T_Cruthaich_abairt_ur_fios    = $T->h('Cruthaich_abairt_ur_fios');
    $T_seann_ghoireas              = $T->h('seann ghoireas');
    $T_Ath_bhreithnich             = $T->h('Ath-bhreithnich');
    $T_Ath_bhreithnich_fios        = $T->h('Ath_bhreithnich_fios');
    $T_mineachadhStordata          = $T->h('mineachadhStordata');
    $T_mun_tsruth                  = $T->h('mun_tsruth');
    $T_taicColmcille               = $T->h('taicColmcille');

    $T_mun_tsruth = strtr ( $T_mun_tsruth, [ '{' => '<i>', '}' => '</i>' ] );
    $T_taicColmcille = sprintf($T_taicColmcille,
                              '<a href="//www.forasnagaeilge.ie/"><img src="/favicons/fnag.png" alt=""> Foras na Gaeilge</a>',
                              '<a href="//www.gaidhlig.org.uk/"><img src="/favicons/bng.png" alt=""> Bòrd na Gàidhlig</a>',
                              '<a href="//www.colmcille.net"><img src="/favicons/colmcille.png" alt=""> Colmcille</a>');

    $ceangalRiMoSMO = ( isset($myCLIL->id)
                      ? '<li class="deas"><a href="https://claran.smo.uhi.ac.uk/myCLIL/" title="Login/Logout/roghainnean airson làrach-lìn SMO">myCLIL</a></li>'
                      : '<li class="deas"><a href="https://login.smo.uhi.ac.uk/?till_gu=https://www2.smo.uhi.ac.uk/teanga/sruth" title="Log a-steach airson deasachadh a dhèanamh">Log air</a></li>'
                      );

    $T_mineachadhStordata = strtr ( $T_mineachadhStordata, [ '{'=>'<b>', '}'=>'</b>' ] );

    $sruthURL = SM_Sruth::sruthurl();
    $sruthdb = SM_Sruth::sruthdb();
    $stordataCss = SM_Sruth::stordataCss();
    if ($sruthdb=='sruth') {
        $h1 = "<h1>An Sruth</h1>";
    } elseif ($sruthdb=='sruthTest') {
        $h1 = <<<EODh1SruthTest
<h1 style="margin:0.2em">SruthTest</h1>
<p class="mineachadhStordata">$T_mineachadhStordata</p>
EODh1SruthTest;
    }

    $deasaichHtml = '';

    if (SM_Sruth::ceadSgriobhaidh()) {
        $deasaichHtml = <<<EODdeasaich
<ul id='deasaich'>
<li><a href="sDeasaich.php?s=0" title="$T_Cruthaich_abairt_ur_fios"><img src="/icons-smo/plusStar.png" alt=""> $T_Cruthaich_abairt_ur</a>
</ul>
<ul style="margin-top:2.5em;font-size:80%">
<li><a href="nDeasaich.php?s=0&amp;n=0" title="$T_Cruthaich_buidheann_ur_fios">$T_Cruthaich_buidheann_ur</a>
<li><a href="sgrud.php">$T_Ath_bhreithnich</a> - <span class="fios">$T_Ath_bhreithnich_fios</span>
</ul>
EODdeasaich;
    }

    $sruthSel = $sruthTestSel = '';
    if      ($sruthdb=='sruth')     { $sruthSel     = 'selected'; }
     elseif ($sruthdb=='sruthTest') { $sruthTestSel = 'selected'; }
    $sruthdbForm = <<<EODsruthdbForm
<div style="float:right;font-size:70%" title="$T_stordata_fios">
$T_stordata: <select name="sruthdb" onchange="document.cookie='sruthdb='+this.options[this.selectedIndex].value;location.reload(true);">
<option value="sruth" $sruthSel>sruth</option>
<option value="sruthTest" $sruthTestSel>sruthTest</option>
</select>
</div>
EODsruthdbForm;

    $navbar = SM_Sruth::navbar($T->domhan,1);
    $HTML = <<<EODHTML
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <title>An Sruth</title>
    <link rel="StyleSheet" href="/css/smo.css">
    <link rel="StyleSheet" href="snas.css">$stordataCss
    <style>
        ul#deasaich { margin-left:1em; padding-left:0; margin-bottom:0; }
        ul#deasaich li { list-style-type:none; }
        p.mineachadhStordata { margin:0.2em; font-style:italic; background-color:white; }
    </style>
</head>
<body>

$navbar
<div class="smo-body-indent">

<img src="dealbhan/sruth.png" style="float:left;border:1px solid black;margin:0 2em 2em 0" alt="">
$h1
<p style="font-weight:bold">$T_fo_thiotal</p>

<ul style="clear:both">
<li><b>$T_Liosta</b><br>&nbsp;<a href="liosta.php?t1=ga&amp;t2=gd">Gaeilge ▶ Gàidhlig</a><br>&nbsp;<a href="liosta.php?t1=gd&amp;t2=ga">Gàidhlig ▶ Gaeilge</a>
<li style="margin-top:1em"><a href="lorg.php" title="Cuardaigh leagan">$T_Lorg_abairt <img src="/icons-smo/lorg.gif" alt=""></a>
    <form action=lorg.php style="display:inline"><input name="a" style="width:22em"></form>
    <a href="lorgc.php" title="Cuardaigh ceannfhocal" style="margin-left:3em">$T_Lorg_facal_cinn <img src="/icons-smo/lorg.gif" alt=""></a>
    <form action=lorgc.php style="display:inline"><input name="c" style="width:10em"></form>
</ul>
$deasaichHtml

<p style="max-width:72em;margin:3.5em 0.2em 0 0.2em;border:2px solid green; border-radius:0.5em;background-color:#dfd;padding:0.5em;font-size:80%;color:green">$T_mun_tsruth</p>

$sruthdbForm
</div>
$navbar

<div style="clear:both;margin:0 0 4px 0;padding:1px 4px;background-color:#ffa;color:brown;font-size:85%;font-weight:bold">$T_taicColmcille</div>
<div class="smo-latha">
2019-03-08 <a href="/~caoimhin/cpd.html">CPD</a><br />
</div>
</body>
</html>
EODHTML;

    echo $HTML;

  } catch (exception $e) { echo $e; }
?>
