<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
  header('Cache-Control:max-age=0');

  try {
      $myCLIL = SM_myCLIL::singleton();
  } catch (Exception $e) {
      $myCLIL->toradh = $e->getMessage();
  }

  $dublaichHtml = $sguabHtml = $cuirRiHtml = $nDeasaichHtml = $sDeasaichHtml = $sSguabCeistHtml = $javascriptDeasachaidh = $HTML = '';

  try {
    $T = new SM_T('sruth/n');
    $hl = $T::hl0();
    $navbar = SM_Sruth::navbar($T->domhan);

    $T_Buidheann                      = $T->_('Buidheann');
    $T_Atharraich_abairt_ri_buidheann = $T->_('Atharraich_abairt_ri_buidheann');
    $T_Deasaich                       = $T->_('Deasaich');
    $T_Deasaich_am_buidheann          = $T->_('Deasaich am buidheann');
    $T_Cruthaich_abairt_don_bhuidheann= $T->_('Cruthaich abairt ùr don bhuidheann seo');
    $T_Dublaich                       = $T->_('Dùblaich');
    $T_Dublaich_am_buidheann          = $T->_('Dùblaich am buidheann');
    $T_Sguab                          = $T->_('Sguab');
    $T_Sguab_as_am_buidheann          = $T->_('Sguab às am buidheann');
    $T_Sguab_abairt_a_buidheann       = $T->_('Sguab_abairt_a_buidheann');
    $T_Lorg_abairt_legend             = $T->_('Lorg_abairt_legend');
    $T_Canan                          = $T->_('Language');
    $T_Abairt                         = $T->_('Abairt');
    $T_Lorg                           = $T->_('Lorg');
    $T_Dublaich_an_darireabh          = $T->_('Dublaich_an_darireabh');
    $T_Air_neo                        = $T->_('Air neo');
    $T_Sguir                          = $T->_('Sguir');
    $T_Sguab_as_buidheann_an_darireabh= $T->_('Sguab_as_buidheann_an_darireabh');
    $T_Sguab_as                       = $T->_('Sguab às');
    $T_Parameter_mi_iom               = $T->_('Parameter_mi_iom');
    $T_Parameter_p_a_dhith            = $T->_('Parameter_p_a_dhith');
    $T_Chan_eil_abairt_ra_lorg        = $T->_('Chan_eil_abairt_ra_lorg');
    $T_Chaidh_cus_abairtean_a_lorg    = $T->_('Chaidh_cus_abairtean_a_lorg');
    $T_Tagh_abairt                    = $T->_('Tagh_abairt');
    $T_Sgriobh_pairt_den_abairt       = $T->_('Sgrìobh pàirt den abairt');
    $T_Cuir_ris_a_bhuidheann          = $T->_('Cuir_ris_a_bhuidheann');
    $T_Chan_eil_buidheann_ann         = $T->_('Chan_eil_buidheann_ann');

    $T_Chan_eil_abairt_le_aireamh_sin = $T->_('Chan_eil_abairt_le_aireamh_sin');
    $T_Cruthachadh                    = $T->_('Cruthachadh');
    $T_Atharrachadh                   = $T->_('Atharrachadh');
    $T_le                             = $T->_('le');
    $T_Sguab_as_abairt_an_dariribh    = $T->_('Sguab_as_abairt_an_dariribh');
    $T_agus_faclan_cinn_agus          = $T->_('agus_faclan_cinn_agus');
    $T_mirean			      = $T->_('mìrean');
    $T_mhir                           = $T->_('mhìr');
    $T_Dearbhaich_ceart_fios          = $T->_('Dearbhaich_ceart_fios');
    $T_Chan_urrainn_dhut_sguabadh_as  = $T->_('Chan_urrainn_dhut_sguabadh_as');
    $T_Buidheann_ur                   = $T->_('Buidheann ùr');
    $T_Buidheann_ur_fios              = $T->_('Buidheann_ur_fios');
    $T_Error_in                       = $T->_('Error_in');

    $stordataConnector = SM_Sruth::stordataConnector();
    $DbSruth = $stordataConnector::singleton('rw');
    $sruthurl = SM_Sruth::sruthurl();
    $stordataCss = SM_Sruth::stordataCss();

    if (!isset($_REQUEST['n'])) { throw new SM_Exception(sprintf($T_Parameter_p_a_dhith,'n')); }
    $n = htmlspecialchars($_REQUEST['n']);
    if (!is_numeric($n) || intval($n)<>$n || $n<1) { throw new SM_Exception("$T_Parameter_mi_iom: n=$n"); }

    $deasaich = SM_Sruth::ceadSgriobhaidh();

    $ainmTeanga = SM_Sruth::ainmTeanga();
    $teangaithe = array_keys($ainmTeanga);
    $meitArr = SM_Sruth::meitHtmlArr();

    function uairHtml ($utime) {
        $uairObject = new DateTime("@$utime");
        $latha     = date_format($uairObject, 'Y-m-d');
        $lathaUair = date_format($uairObject, 'Y-m-d H:i:s');
        return "<span title=\"$lathaUair UT\">$latha</span>";
    }

    if ($deasaich) {
        if (isset($_GET['dublaich'])) {
            $dublaichHtml = <<< END_dublaich
<div class=sguab>
$T_Dublaich_an_darireabh&nbsp;&nbsp; <a href="nDublaich.php?n=$n" class=sguab>$T_Dublaich</a>
<br><br>
$T_Air_neo <a href=n.php?n=$n>$T_Sguir</a>
</div>
END_dublaich;
        } elseif (isset($_GET['sguab'])) {
            $sguabHtml = <<< END_sguab
<div class=sguab>
$T_Sguab_as_buidheann_an_darireabh&nbsp;&nbsp; <a href="nSguab.php?n=$n&amp;till=./" class=sguab>$T_Sguab_as</a>
<br><br>
$T_Air_neo <a href=n.php?n=$n>$T_Sguir</a>
</div>
END_sguab;
        } elseif (isset($_GET['cuirRi'])) {
            $cuirRi = htmlspecialchars($_REQUEST['cuirRi']);
            $meit   = htmlspecialchars($_REQUEST['meit']);
            $astar  = htmlspecialchars($_REQUEST['astar']); 
            if (!is_numeric($cuirRi) || intval($cuirRi)<>$cuirRi || $cuirRi<1) { throw new SM_Exception("$T_Parameter_mi_iom: cuirRi=$cuirRi"); }
            if (!in_array($meit,array_keys($meitArr)))                         { throw new SM_Exception("$T_Parameter_mi_iom: meit=$meit");     }
            if (!is_numeric($astar) || $astar<=0)                              { throw new SM_Exception("$T_Parameter_mi_iom: astar=$astar");   }
            $myCLIL = SM_myCLIL::singleton();
            $smid = $myCLIL->id;
            $stmtINS = $DbSruth->prepare("REPLACE INTO sruthns (n,s,astar,meit,smid) VALUES (:n,:s,:astar,:meit,:smid)");
            $stmtINS->execute(array(':n'=>$n,':s'=>$cuirRi,':astar'=>$astar,':meit'=>$meit,':smid'=>$smid));
        }

        if (!empty($_GET['lorg'])) {
            $tq = ( empty($_GET['t']) ? '%' : $_GET['t'] ); 
            $aq = ( empty($_GET['a']) ? '%' : $_GET['a'] );
            $aq = '%' . strtr($aq,array('*'=>'%','?'=>'_')) .'%';
            $aq = strtr($aq,array('%%'=>'%'));
            $stmtSEL = $DbSruth->prepare('SELECT sruths.s,astar,meit'
                                       . ' FROM sruths LEFT JOIN sruthns ON n=:n AND sruthns.s=sruths.s'
                                       . ' WHERE sruths.t LIKE :t AND a LIKE :a'
                                       . ' ORDER BY t,a');
            $stmtSEL->execute(array(':n'=>$n,':t'=>$tq,':a'=>$aq));
            $torArr = $stmtSEL->fetchAll(PDO::FETCH_OBJ);
            $count = count($torArr);
            if ($count==0) {
                $cuirRiHtml .= "<p class='mearachd'>$T_Chan_eil_abairt_ra_lorg</p>\n";
            } elseif ($count>100) {
                $cuirRiHtml .= "<p class='mearachd'>$T_Chaidh_cus_abairtean_a_lorg</p>\n";
            } else {
                $cuirRiHtml .= "<fieldset class=cuirRis style='background-color:#fee'>\n";
                if ($count>1) { $cuirRiHtml .= "<legend style='background-color:black'>$T_Tagh_abairt</legend>\n"; }
                $cuirRiHtml .= "<table>\n";
                foreach ($torArr as $tor) {
                    $s     = $tor->s;
                    $annAs = ( isset($tor->astar) ? 'tick' : 'null' ); $annAs = "<img src='/favicons/$annAs.png' alt=''>";
                    $astar = ( isset($tor->astar) ? $tor->astar : 1 );
                    $meit  = ( isset($tor->meit)  ? $tor->meit  : 0 );
                    $astarMax = max($astar+2,5);
                    $optionsHtml = '';
                    foreach ($meitArr as $val=>$symb) { $optionsHtml .= "<option value=$val" . ($val==$meit ? ' selected' : '') . ">$symb</option>"; }
                    $sHTML = SM_Sruth::sHTML($s);
                    $cuirRiHtml .= <<<END_cuirRiHtmlrow
<form><tr><td>$annAs</td><td>$sHTML<input type='hidden' name='cuirRi' value='$s'><input type='hidden' name='n' value='$n'></td>
<td><select name='meit'>$optionsHtml</select></td>
<td id='astar$s' class='astar'>$astar</td>
<td><input name='astar' type='range' min=0 max=$astarMax step=0.1 value=$astar style='width:25em;color:#aaa' list=ticks oninput="setAstar('astar$s',value);" onchange="setAstar('astar$s',value);"></td>
<td><input type='submit' value='$T_Cuir_ris_a_bhuidheann'></td></tr></form>
END_cuirRiHtmlrow;
                }
                $cuirRiHtml .= "</table>\n</fieldset>\n";
            }
        }
        //Cruthaich foirm airson abairt a lorg
        $ainmTeanga = SM_Sruth::ainmTeanga();
        $teangaithe = array_keys($ainmTeanga);
        $selectTHtml  = "<select name='t'>\n";
        $selectTHtml .= "<option value='' selected></option>\n";
        foreach ($teangaithe as $t) { $selectTHtml .= "<option value='$t' lang='$t'>" .  $ainmTeanga[$t] . "</option>\n"; }
        $selectTHtml .= "</select>\n";  
        $cuirRiHtml .= <<<END_cuirRiHtml
<fieldset class="cuirRis" style="margin:1.5em 0 0.5em 0">
<legend>$T_Lorg_abairt_legend</legend>
<form method="get" style="clear:both">
<table>
<tr><td>$T_Canan</td><td>$T_Abairt</td><td></td></tr>
<tr>
<td>$selectTHtml</td>
<td><input name="a" placeholder="$T_Sgriobh_pairt_den_abairt" autofocus style="width:35em"></td>
<td><input type="submit" name="lorg" value="$T_Lorg"></td>
</table>
</tr>
<input type="hidden" name=n value=$n>
</form>
</fieldset>
END_cuirRiHtml;

    $javascriptDeasachaidh = <<<END_javascriptDeasachaidh
    <script>
        function setAstar(id,astar) {
            document.getElementById(id).innerHTML=parseFloat(astar);
        }
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

    $stmtn1 = $DbSruth->prepare('SELECT n FROM sruthn WHERE n=:n');
    $stmtn1->execute(array(':n'=>$n));
    if (!$row = $stmtn1->fetch()) { throw new SM_Exception("$T_Chan_eil_buidheann_ann, $n"); }

    $queryn2 = 'SELECT sruths.s AS s2, sruths.t AS t2, sruths.a AS a2, meit, astar FROM sruthns,sruths'
             . ' WHERE sruthns.n=:n'
             . '   AND sruthns.s=sruths.s'
             . ' ORDER BY t2,a2';
    $stmtn2 = $DbSruth->prepare($queryn2);

    if ($deasaich) { $nDeasaichHtml =  "<a href=nDeasaich.php?n=$n><img src=/icons-smo/peann.png title='$T_Deasaich_am_buidheann' alt='$T_Deasaich'></a>"
                                    .  " <a href=sDeasaich.php?s=0&amp;n=$n><img src=/icons-smo/plusStar.png title='$T_Cruthaich_abairt_don_bhuidheann'></a>"
                                    . " <a href=n.php?n=$n&amp;dublaich><img src=/icons-smo/dubladh.png title='$T_Dublaich_am_buidheann' alt='$T_Dublaich'></a>"
                                    . " <a href=n.php?n=$n&amp;sguab><img src=/icons-smo/curAs2.png title='$T_Sguab_as_am_buidheann' alt='$T_Sguab'></a>"; }
    $buidheanHtml = "<div class=drong><span style='font-size:80%;font-weight:bold;padding-right:0.5em'>$T_Buidheann $n</span> $nDeasaichHtml<br>\n";
    $stmtn2->execute(array(':n'=>$n));
    $buidheanHtml .= '<table>';
    while ($row2 = $stmtn2->fetch(PDO::FETCH_ASSOC)) {
        extract($row2);
        $meitHtml = SM_Sruth::meitHtml($meit);
        if ($deasaich) { $sDeasaichHtml = "<td><a href='n.php?n=$n&amp;s=$s2'><img src='/icons-smo/peann.png' title='$T_Atharraich_abairt_ri_buidheann' alt='$T_Deasaich'></a>"
                                         .   " <img src='/icons-smo/curAs.png' onclick=\"sguabSbhoN($s2,$n)\" title='$T_Sguab_abairt_a_buidheann' alt='Sguab'></td>"; }
        $buidheanHtml .=  '<tr><td>' . SM_Sruth::sHTML($s2) . "</td><td>$meitHtml</td><td class='astar'>$astar</td>$sDeasaichHtml</tr>\n";
    }
    $buidheanHtml .= "</table>\n</div>\n";
    $HTML = <<<END_HTML
$dublaichHtml$sguabHtml$sSguabCeistHtml

<a href="./"><img src="dealbhan/bunadas64.png" style="float:left;border:1px solid black;margin:0 2em 2em 0" alt=""></a>
$buidheanHtml
$cuirRiHtml
END_HTML;

  } catch (Exception $e) {
      if (strpos($e,'Sgrios')!==FALSE) { $HTML = ''; }
      $HTML .= $e;
  }

  echo <<<END_duilleag
<!DOCTYPE html>
<html lang="$hl">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="robots" content="noindex,nofollow">
    <title>An Sruth: Fiosrachadh mu Bhuidheann $n</title>
    <link rel="StyleSheet" href="/css/smo.css">$stordataCss
    <link rel="StyleSheet" href="snas.css">
    <style>
        div.drong { background-color:#ffd; clear:both; margin:0.8em 0; border:1px solid; border-radius:0.4em; padding:0.2em; }
        div.sguab         { margin:0.4em 0; border:6px solid red; border-radius:7px; background-color2:#fdd; padding:0.7em; }
        div.sguab a       { font-size:112%; background-color:#55a8eb; color:white; font-weight:bold; padding:3px 10px; border:0; border-radius:8px; text-decoration:none; }
        div.sguab a:hover { background-color:blue; }
        div.sguab a.sguab       { background-color:#f84; }
        div.sguab a.sguab:hover { background-color:red; font-weight:bold; }
        p.mearachd { color:red; font-size:85%; }
        td.astar { color:grey; font-size:80%; width:1.8em; }
        fieldset.cuirRis        { margin-top:0.7em; background-color:#eee; border:1px solid grey; border-radius:10px; }
        fieldset.cuirRis legend { background-color:grey; color:white; padding:1px 4px; border:1px solid grey; border-radius:4px; font-weight:bold; font-size:70%; }
    </style>
$javascriptDeasachaidh
</head>
<body>
<datalist id=ticks>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
<option>7</option>
<option>8</option>
<option>9</option>
<option>10</option>
</datalist>
$navbar
<div class="smo-body-indent">

$HTML

</div>
$navbar
<div class="smo-latha">2017-03-04 <a href="/~caoimhin/cpd.html">CPD</a></div>
</body>
</html>
END_duilleag;

?>
