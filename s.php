<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
  header('Cache-Control:max-age=0');

  try {
      $myCLIL = SM_myCLIL::singleton();
  } catch (Exception $e) {
      $myCLIL->toradh = $e->getMessage();
  }

  try {
    $T = new SM_T('sruth/s');
    $hl = $T::hl0();
    $navbar = SM_Sruth::navbar($T->domhan);

    $T_Canan                          = $T->_('Language');
    $T_Abairt                         = $T->_('Abairt');
    $T_Parameter_p_a_dhith            = $T->_('Parameter_p_a_dhith');
    $T_Parameter_mi_iom               = $T->_('Parameter_mi_iom');
    $T_Faclan_cinn                    = $T->_('Faclan-cinn');
    $T_Buidheann                      = $T->_('Buidheann');
    $T_Lorg_le_Google                 = $T->_('Lorg le Google');
    $T_Chan_eil_abairt_le_aireamh_sin = $T->_('Chan_eil_abairt_le_aireamh_sin');
    $T_Cruthachadh                    = $T->_('Cruthachadh');
    $T_Atharrachadh                   = $T->_('Atharrachadh');
    $T_le                             = $T->_('le');
    $T_Sguab_as_abairt_an_dariribh    = $T->_('Sguab_as_abairt_an_dariribh');
    $T_Sguab_as                       = $T->_('Sguab às');
    $T_Sguab                          = $T->_('Sguab');
    $T_agus_faclan_cinn_agus          = $T->_('agus_faclan_cinn_agus');
    $T_Air_neo                        = $T->_('Air neo');
    $T_Sguir                          = $T->_('Sguir');
    $T_Gearr_abairt_an_dariribh       = $T->_('Gearr_abairt_an_dariribh');
    $T_Gearr                          = $T->_('Geàrr');
    $T_mirean			      = $T->_('mìrean');
    $T_mhir                           = $T->_('mhìr');
    $T_Dearbhaich_ceart_fios          = $T->_('Dearbhaich_ceart_fios');
    $T_Chan_urrainn_dhut_sguabadh_as  = $T->_('Chan_urrainn_dhut_sguabadh_as');
    $T_Gearr_aig                      = $T->_('Gearr_aig');
    $T_Gearr_aig_fios                 = $T->_('Gearr_aig_fios');
    $T_Deasaich                       = $T->_('Deasaich');
    $T_Deasaich_an_abairt             = $T->_('Deasaich an abairt');
    $T_Buidheann_ur                   = $T->_('Buidheann ùr');
    $T_Buidheann_ur_fios              = $T->_('Buidheann_ur_fios');
    $T_OsAbairtean                    = $T->_('Os-abairtean');
    $T_FoAbairtean                    = $T->_('Fo-abairtean');
    $T_Cruthaich_abairt_don_bhuidheann= $T->_('Cruthaich abairt ùr don bhuidheann seo');
    $T_Dublaich                       = $T->_('Dùblaich');
    $T_Dublaich_am_buidheann          = $T->_('Dùblaich am buidheann');
    $T_Atharraich_abairt_ri_buidheann = $T->_('Atharraich_abairt_ri_buidheann');
    $T_Sguab_abairt_a_buidheann       = $T->_('Sguab_abairt_a_buidheann');
    $T_Ceart                          = $T->_('Ceart');
    $T_Error_in                       = $T->_('Error_in');

    $deasaich = SM_Sruth::ceadSgriobhaidh();
    if (!isset($_GET['s'])) { throw new SM_Exception(sprintf($T_Parameter_p_a_dhith,'s')); }
    $s = $_GET['s'];
    if (intval($s)<>$s) { throw new SM_Exception("$T_Parameter_mi_iom: s=$s"); }

    $stordataConnector = SM_Sruth::stordataConnector();
    $DbSruth = $stordataConnector::singleton('rw');
    $sruthurl = SM_Sruth::sruthurl();
    $stordataCss = SM_Sruth::stordataCss();
    $ainmTeanga = SM_Sruth::ainmTeanga();
    $teangaithe = array_keys($ainmTeanga);

    function uairHtml ($utime) {
        $uairObject = new DateTime("@$utime");
        $latha     = date_format($uairObject, 'Y-m-d');
        $lathaUair = date_format($uairObject, 'Y-m-d H:i:s');
        return "<span title=\"$lathaUair UT\">$latha</span>";
    }

    $liostaTeanga = SM_Sruth::liostaTeanga();
    $stmt = $DbSruth->prepare("SELECT * FROM sruths WHERE s=:s AND t IN $liostaTeanga");
    $stmt->execute(array(':s'=>$s));
    if (!$row = $stmt->fetch(PDO::FETCH_ASSOC)) { throw new SM_Exception("$T_Chan_eil_abairt_le_aireamh_sin $s"); }
    extract($row);
    $teanga = $ainmTeanga[$t];
    $fiosCo   = ( empty($csmid) ? '' : "<br>$T_Cruthachadh: " . uairHtml($cutime) . " $T_le $csmid" );
    $fiosCo  .= ( empty($msmid) ? '' : "<br>$T_Atharrachadh: " . uairHtml($mutime) . " $T_le $msmid" );
    if (!empty($fiosCo)) { $fiosCo = "<span style='font-size:65%'>$fiosCo</span>"; }
    $deasaichSruthHTML = $deasaichSruthcHTML = $deasaichSruthnHTML = $sguabSruthHTML = $gearrSruthHTML = $sDeasaichHtml = $javascriptDeasachaidh = '';

    if ($deasaich) {
        if (isset($_GET['sguab'])) {
            $sguabSruthHTML = <<< EODsguab
<div class=sguab>
$T_Sguab_as_abairt_an_dariribh&nbsp;&nbsp; <a href="sSguab.php?s=$s&amp;till=./" class=sguab>$T_Sguab_as</a><br>
<span style="font-size:60%">($T_agus_faclan_cinn_agus)</span> 
<br><br>
$T_Air_neo <a href=s.php?s=$s>$T_Sguir</a>
</div>
EODsguab;
        } elseif (isset($_GET['gearr'])) {
            $mirean = explode(';',$a);
            foreach ($mirean as &$mir) {
                $mir = trim($mir);
                if (empty($mir)) { unset($mir); }
                $mir = "<br><br>&nbsp;&nbsp&nbsp;<span class='abairt' lang='$t'>$mir</span>";
            }
            $liostaMirean = implode(' ',$mirean);
            $nmirean = count($mirean);
            $fiosMirean = ( $nmirean==2 ? "2 $T_mhir" : "$nmirean $T_mirean" );
            $gearrSruthHTML = <<<EODgearr
<div class=sguab>
$T_Gearr_abairt_an_dariribh $fiosMirean?$liostaMirean<br><br><a href="sGearr.php?s=$s&amp;till=sgrud.php" class=sguab>$T_Gearr</a>
<br><br>
$T_Air_neo <a href=s.php?s=$s>$T_Sguir</a>
</div>
EODgearr;
        }
        $stmtSELnFalaichte = $DbSruth->prepare('SELECT sn1.n'
                                              .' FROM sruthns AS sn1, sruthns AS sn2, sruths'
                                              ." WHERE sn1.s=:s AND sn1.n=sn2.n AND sn2.s=sruths.s AND sruths.t NOT IN $liostaTeanga");
        $stmtSELnFalaichte->execute(array(':s'=>$s));
        $dealbhagCeartHTML = ( $sgrud==0
                             ? ''
                             : " <a href='sCeart.php?s=$s&amp;till=sgrud.php'><img src='/icons-smo/ceart.png' alt='$T_Ceart' title='$T_Dearbhaich_ceart_fios'></a>"
                             );
        $dealbhagSguabHTML = ( ($stmtSELnFalaichte->fetch())
                             ? " <img src='/icons-smo/curAsLiath.png' title='$T_Chan_urrainn_dhut_sguabadh_as'>"
                             : " <a href='s.php?s=$s&amp;sguab'><img src='/icons-smo/curAs2.png' title='$T_Sguab_as' alt='$T_Sguab_as'></a>"
                             );
        $dealbhagGearrHTML = ( strpos($a,';')===FALSE
                             ? ''
                             : " <a href='s.php?s=$s&amp;gearr'><img src='/icons-smo/siosar.png' title='$T_Gearr_aig_fios' alt='$T_Gearr_aig ;'></a>"
                             ); 
        $deasaichSruthHTML  = " <a href='sDeasaich.php?s=$s'><img src='/icons-smo/peann.png' alt='$T_Deasaich' title='$T_Deasaich_an_abairt'></a>"
                            . " <a href='nDeasaich.php?s=$s&amp;n=0'><img src='/favicons/drongUr.png' alt='$T_Buidheann_ur' title='$T_Buidheann_ur_fios'></a>"
                            . $dealbhagSguabHTML
                            . $dealbhagGearrHTML
                            . $dealbhagCeartHTML;
    }

    $ceangalGoogle = 'https://www.google.co.uk/search?q=“' . $a . '”';
    $ceanglaicheanHtml = "<a href='$ceangalGoogle'><img src='/favicons/google.png' alt='Google' title='$T_Lorg_le_Google'></a>";
    if ($t=='gd') {
        $ceangalDASG = '//www.dasg.ac.uk/corpus/concordance.php?theData=' . $a . '&amp;qmode=sq_nocase&amp;pp=50&amp;del=end&amp;uT=y&amp;del=begin&amp;del=end&amp;uT=y';
        $ceanglaicheanHtml .= " <a href='$ceangalDASG'><img src='//multidict.net/multidict/icon.php?dict=DASG' alt='DASG' title='Lorg ann an DASG'></a>";
    }
    $sHtml = SM_Sruth::sHtml($s,0);
    $fiosHTML = <<<ENDfiosHTML
<p style='margin:2px 0'><span style='font-size:80%;font-weight:bold'>$T_Abairt $s</span> $ceanglaicheanHtml</p>
<p style='margin:2px 0'><span style='font-size:125%'>$sHtml</span>$deasaichSruthHTML</p>
<p style='margin-top:4px;font-size:90%'>$T_Canan: $teanga $fiosCo</p>
ENDfiosHTML;

    $stmtc = $DbSruth->prepare('SELECT * FROM sruthc WHERE s=:s');
    $ceannfhacailArr = array();
    $stmtc->execute(array(':s'=>$s));
    $rows = $stmtc->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        extract($row);
        $ceannfhacailArr[] = "<a class=ceann lang=$t href='lorgc.php?t=$t&amp;c=$c'>$c</a>";
    }
    if (empty($ceannfhacailArr)) { $ceannfhacailArr = array('<i>chan eil gin</i>'); }
    $fiosHTML .= "<p><b>$T_Faclan_cinn:</b> " . implode(', ',$ceannfhacailArr) . "</p>\n";

    $stmtOs = $DbSruth->prepare('SELECT s AS s2, a AS a2 FROM sruths WHERE a LIKE :aPat AND t=:t AND NOT s=:s');
    $stmtOs->execute(array(':aPat'=>"%$a%",':t'=>$t,':s'=>$s));
    $rows = $stmtOs->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows)==0) {
        $osHTML = '';
    } else {
        $osHTML = "<div class=buidheann style='background-color:#fdd;font-size:80%'><span style='font-size:80%;font-weight:bold'>$T_OsAbairtean</span><br>\n";
        $osHTML .= '<p style="margin:0.5em 0 0.5em 1em;line-height:150%">';
        foreach ($rows as $r) {
            extract($r);
            $osHTML .= SM_Sruth::sHtml($s2) . "<br>\n";
        }
        $osHTML .= "</p>\n</div>\n";
    }

    $stmtFo = $DbSruth->prepare('SELECT s AS s2, a AS a2 FROM sruths WHERE LOCATE(a,:a)>0 AND t=:t AND NOT s=:s');
    $stmtFo->execute(array(':a'=>$a,':t'=>$t,':s'=>$s));
    $rows = $stmtFo->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows)==0) {
        $foHTML = '';
    } else {
        $foHTML = "<div class=buidheann style='background-color:#fdd;font-size:80%'><span style='font-size:80%;font-weight:bold'>$T_FoAbairtean</span><br>\n";
        $foHTML .= '<p style="margin:0.5em 0 0.5em 1em;line-height:150%">';
        foreach ($rows as $r) {
            extract($r);
            $foHTML .= SM_Sruth::sHtml($s2) . "<br>\n";
        }
        $foHTML .= "</p>\n</div>\n";
    }

    $stmtn1 = $DbSruth->prepare('SELECT * FROM sruthns WHERE s=:s ORDER BY n');
    $queryn2 = 'SELECT sruths.s AS s2, sruths.t AS t2, sruths.a AS a2, meit, astar FROM sruthns,sruths'
             . ' WHERE sruthns.n=:n'
             . '   AND sruthns.s=sruths.s'
             . "   AND sruths.t IN $liostaTeanga"
             . ' ORDER BY n,t2,a2';
    $stmtn2 = $DbSruth->prepare($queryn2);
    $buidhneanHTML = '';
    $stmtn1->execute(array(':s'=>$s));
    while ($row1 = $stmtn1->fetch(PDO::FETCH_ASSOC)) {
        extract($row1);
        $nDeasaichHtml = ( $deasaich
                         ? " <a href=sDeasaich.php?s=0&amp;n=$n><img src=/icons-smo/plusStar.png title='$T_Cruthaich_abairt_don_bhuidheann'></a>"
                          ." <a href=n.php?n=$n&amp;dublaich><img src=/icons-smo/dubladh.png title='$T_Dublaich_am_buidheann' alt='$T_Dublaich'></a>"
                          ." <a href=n.php?n=$n&amp;sguab><img src=/icons-smo/curAs2.png title='$T_Sguab_as'></a>"
                         : ''
                         );
        $buidheannHTML = "<div class=buidheann id=n$n><a href=n.php?n=$n style='font-size:80%;font-weight:bold;padding-right:0.5em'>$T_Buidheann $n</a>$nDeasaichHtml<br>\n";
        $stmtn2->execute(array(':n'=>$n));
        $buidheannHTML .= "<table>\n";
        while ($row2 = $stmtn2->fetch(PDO::FETCH_ASSOC)) {
            extract($row2);
            if ($deasaich) { $sDeasaichHtml = "<td><a href='n.php?n=$n&amp;s=$s2'><img src='/icons-smo/peann.png' title='$T_Atharraich_abairt_ri_buidheann' alt='$T_Deasaich'></a>"
                                             .   " <img src='/icons-smo/curAs.png' onclick=\"sguabSbhoN($s2,$n)\" title='$T_Sguab_abairt_a_buidheann' alt='$T_Sguab'></td>"; }
            $buidheannHTML .= '<tr><td>' . SM_Sruth::sHtml($s2) . "</td><td class='astar'>$astar</td>$sDeasaichHtml</tr>\n";
        }
        $buidheannHTML .= "</table>\n</div>\n";
        $buidhneanHTML .= $buidheannHTML;
    }

    if ($deasaich) { $javascriptDeasachaidh = <<<END_javascriptDeasachaidh
    <script>
      //Javascript airson drag-and-drop
        var buidheannStart, nDrag, shiftKey, ctrlKey;
        function findAncestor (el, cls) {
          //Cleachd “closest” an àite seo, nuair a bhios e mu dheireadh thall aig IE agus Edge
            while ((el = el.parentElement) && !el.classList.contains(cls));
            return el;
        }
        function handleDragStart(e) {
             buidheannStart = findAncestor(e.target,'buidheann');
             if (buidheannStart) { nDrag = buidheannStart.id.substring(1); }
              else               { nDrag = -1; }
             var name = e.target.getAttribute('data-name');
             e.dataTransfer.setData('text/x-sruth', name);
             e.effectAllowed = 'copyMove';
             if (e.shiftKey) { shiftKey = true; } else { shiftKey = false; }
             if (e.ctrlKey)  { ctrlKey  = true; } else { ctrlKey  = false; }
        }
        function handleDragEnter(e) {
            if (this!=buidheannStart) { this.classList.add('over'); }
        }
        function handleDragLeave(e) {
            this.classList.remove('over');
        }
        function handleDragOver(e) {
            if (e.preventDefault) { e.preventDefault(); }
            e.dataTransfer.dropEffect = 'copy';
            if (shiftKey || ctrlKey || e.shiftKey || e.ctrlKey ) { e.dataTransfer.dropEffect = 'move'; }
        }
        function handleDrop(e) {
            if (e.stopPropogation) { e.stopPropogation(); }
            if (e.preventDefault) { e.preventDefault(); }
            var sDrag = e.dataTransfer.getData('text/x-sruth').substring(1);
            var nDrop = this.id.substring(1);
            if (this!=buidheannStart) {
                var url = '$sruthurl/ajax/DnD.php?sDrag=' + sDrag + '&nDrop=' + nDrop;
                if (e.dataTransfer.dropEffect == 'move' || e.shiftKey || e.ctrlKey) { url += '&nDrag=' + nDrag; }
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET", url, false);
                xmlhttp.send();
                var resp = xmlhttp.responseText;
                if (resp!='OK') { alert('$T_Error_in DnD: ' + resp); }
                location.reload();
            }
        }
        function handleDragEnd(e) {
            e.dataTransfer.clearData();
            shiftKey = false;
            ctrlKey  = false;
        }
        function deasaichDnD() {
            var buidhnean = document.getElementsByClassName("buidheann");
            [].forEach.call(buidhnean, function(buidheann) {
                buidheann.addEventListener('drop',      handleDrop,      false);
                buidheann.addEventListener('dragenter', handleDragEnter, false);
                buidheann.addEventListener('dragleave', handleDragLeave, false);
                buidheann.addEventListener('dragover',  handleDragOver,  false);
            });
            var draggables = document.querySelectorAll('div.s[draggable=true]');
            [].forEach.call(draggables, function(draggable) {
                draggable.addEventListener('dragstart', handleDragStart, false);
            });
        }
      //Javascript airson sguab s bho n
        function sguabSbhoN(s,n) {
            var url = '$sruthurl/ajax/sguabSbhoN.php?s=' + s + '&n=' + n;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open('GET', url, false);
            xmlhttp.send();
            var resp = xmlhttp.responseText;
            if (resp!='OK') { alert('$T_Error_in sguabSbhoN: ' + resp); }
            location.reload();
        }
    </script>
END_javascriptDeasachaidh;
    }

    $HTML = <<<END_HTML
$sguabSruthHTML
$gearrSruthHTML
<a href="./"><img src="dealbhan/sruth64.png" style="float:left;border:1px solid black;margin:0 2em 2em 0" alt=""></a>
<div style="float:left">
$fiosHTML
</div>

$buidhneanHTML
$osHTML
$foHTML
END_HTML;

  } catch (Exception $e) { $HTML = $e; }

  echo <<<END_DUILLEAG
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <title>An Sruth: Fiosrachadh mu abairt $s</title>
    <link rel="StyleSheet" href="/css/smo.css">
    <link rel="StyleSheet" href="snas.css">$stordataCss
    <style>
        div.buidheann { background-color:#ffd; clear:both; margin:0.8em 0; border:1px solid; border-radius:0.4em; padding:0.2em; }
        div.sguab         { margin:0.4em 0; border:6px solid red; border-radius:7px; background-color2:#fdd; padding:0.7em; }
        div.sguab a       { font-size:112%; background-color:#55a8eb; color:white; font-weight:bold; padding:3px 10px; border:0; border-radius:8px; text-decoration:none; }
        div.sguab a:hover { background-color:blue; }
        div.sguab a.sguab       { background-color:#f84; }
        div.sguab a.sguab:hover { background-color:red; font-weight:bold; }
        td.astar { color:grey; font-size:80%; width:1.8em; }
    </style>
$javascriptDeasachaidh
</head>
<body onload='deasaichDnD();'>

$navbar
<div class="smo-body-indent">

$HTML

</div>
$navbar

<div class="smo-latha">2019-01-11 <a href="/~caoimhin/cpd.html">CPD</a></div>
</body>
</html>
END_DUILLEAG;

?>
