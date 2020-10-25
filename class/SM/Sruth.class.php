<?php
class SM_Sruth
{
//  private const LUCHD_SGRIOBHAIDH_gagden = '1991-cpd|2000-mmd|1987-rg|1999-mb|2015-cc';
//  private const LUCHD_SGRIOBHAIDH_uile   = '1991-cpd|2000-mmd';
  private const LUCHD_SGRIOBHAIDH_gagden = 'caoimhinsmo';
  private const LUCHD_SGRIOBHAIDH_uile   = 'caoimhinsmo';
  public static function sruthurl() {
       $url = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . '/teanga/sruth';
       return $url;
  }

  public $sArr, $snArr, $nsArr;

  public static function sruthdb() {
      if (empty($_COOKIE['sruthdb']))       { return 'sruth'; }
      if ($_COOKIE['sruthdb']=='sruth')     { return 'sruth'; }
      if ($_COOKIE['sruthdb']=='sruthTest') { return 'sruthTest'; }
      return 'sruth';
  }

  public static function sruthSeall() {
      if (empty($_COOKIE['sruthSeall']))    { return 'gagden'; }
      if ($_COOKIE['sruthSeall']=='gagden') { return 'gagden'; }
      if ($_COOKIE['sruthSeall']=='uile')   { return 'uile';   }
      return 'gagden';
  }

  public static function stordataConnector() {
      $sruthdb = self::sruthdb();
      if ($sruthdb=='sruth') { return 'SM_SruthPDO'; }
      if ($sruthdb=='sruthTest') { return 'SM_SruthTestPDO'; }
      throw new SM_Exception("\$stordata = $sruthdb - mì-laghail");
  }

  public static function ceadSgriobhaidh() {
      if (self::sruthdb()=='sruthTest') { return 1; }
      $myCLIL = SM_myCLIL::singleton();
      if (self::sruthSeall()=='uile'   && $myCLIL->cead(self::LUCHD_SGRIOBHAIDH_uile))   { return 1; }
      if (self::sruthSeall()=='gagden' && $myCLIL->cead(self::LUCHD_SGRIOBHAIDH_gagden)) { return 1; }
      return 0;
  }

  public static function stordataCss() {
      $sruthdb = self::sruthdb();
      if ($sruthdb=='sruth') { return ''; }
      if ($sruthdb=='sruthTest') { return "\n    <link rel='StyleSheet' href='snasTest.css'>"; }
      if ($sruthdb=='bunw')      { return "\n    <link rel='StyleSheet' href='snasw.css'>"; }
  }

  public static function navbar($domhan='',$duilleagAghaidh=0) {
      $hl0 = SM_T::hl0();
      $T = new SM_T('sruth/navbar');
      $T_sruthPutanTitle    = $T->_('sruthPutanTitle');
      $T_canan_eadarAghaidh = $T->_('canan_eadarAghaidh');
      $T_Cobhair            = $T->_('Cobhair');
      $T_Log_air            = $T->_('Log_air');
      $T_Log_air_fios       = $T->_('Log_air_fios');
      $sruthdb = ucfirst(self::sruthdb());
      $sruthCeangal = ( $duilleagAghaidh ? '' : "\n<li><a href='/teanga/sruth/' title='$T_sruthPutanTitle'>$sruthdb</a>" );
      $sruthSeall = self::sruthSeall();
      if ($sruthSeall=='gagden') {
          $sruthSeallEile = 'uile';
          $teacsaPutan     = $T->_('Seall gach cànan');
          $teacsaPutanFios = $T->_('Seall gach cànan - fios');
      } else {
          $sruthSeallEile = 'gagden';
          $teacsaPutan     = $T->_('Seall ga-gd-en');
          $teacsaPutanFios = $T->_('Seall ga-gd-en - fios');
      }
      $hlArr = array(
          'br'=>'Brezhoneg',
          'de'=>'Deutsch',
          'en'=>'English',
          'fr'=>'Français',
          'ga'=>'Gaeilge',
          'gd'=>'Gàidhlig',
          'it'=>'Italiano',
          'lt'=>'Lietuvių',
          'pt'=>'Português',
          'bg'=>'Български',
          'sh'=>'Srpskohrvatsk',
            '----1'=>'',  //Partial translations
          'da'=>'Dansk');
      $options = '';
      foreach ($hlArr as $hl=>$hlAinm) {
          if (substr($hl,0,4)=='----') { $options .= "<option value='' disabled>&nbsp;_{$hlAinm}_</option>/n"; }  //Divider in the list of select options
            else                       { $options .= "<option value='$hl|en'" . ( $hl==$hl0 ? ' selected' : '' ) . ">$hlAinm</option>\n"; }
      }
      $selCanan = <<< END_selCanan
<script>
    function atharraichCanan(hl) {
        document.cookie='Thl=' + hl + ';path=/teanga/sruth/;max-age=15000000';  //Math airson sia mìosan
        var paramstr = location.search;
        if (/Trident/.test(navigator.userAgent) || /MSIE/.test(navigator.userAgent)) {
          //Rud lag lag airson seann Internet Explorer, nach eil eòlach air URLSearchParams. Sguab ás nuair a bhios IE marbh.
            if (paramstr.length==6 && paramstr.substring(0,4)=='?hl=') { paramstr = ''; }
            paramstr = paramstr;
        } else {
            const params = new URLSearchParams(paramstr)
            params.delete('hl');
            paramstr = params.toString();
            if (paramstr!='') { paramstr = '?'+paramstr; }
        }
        loc = window.location;
        location = loc.protocol + '//' + loc.hostname + loc.pathname + paramstr;
    }
</script>
<form>
<select name="hl" style="display:inline-block;background-color:#eef;margin:0 4px" onchange="atharraichCanan(this.options[this.selectedIndex].value)">
$options</select>
</form>
END_selCanan;
      $myCLIL  = SM_myCLIL::singleton();
      $myCLIL = SM_myCLIL::singleton();
      if ($myCLIL->cead(SM_myCLIL::LUCHD_EADARTHEANGACHAIDH) && !empty($domhan))
        { $trPutan = "\n<li class=deas><a href='//www3.smo.uhi.ac.uk/teanga/smotr/tr.php?domhan=$domhan' target='_blank'>tr</a>"; } else { $trPutan = ''; }
      $sruthURL = self::sruthurl();
      $smotr = ( strpos($sruthURL,'www2')!==false ? 'smotr_dev' : 'smotr'); //Adhockery - Cleachd 'smotr_dev' airson login air www2.smo.uhi.ac.uk
      $ceangalRiMoSMO = ( isset($myCLIL->id)
                        ? "<li class='deas'><a href='/teanga/$smotr/logout.php' title='Log out from myCLIL'>Logout</a></li>"
                        : "<li class='deas'><a href='/teanga/$smotr/login.php?till_gu=$sruthURL' title='$T_Log_air_fios'>$T_Log_air</a></li>"
                        );
      $cobhairHtml = ( $duilleagAghaidh ? "<li class='deas'><a href='cobhair.php'>$T_Cobhair</a>" : '' );
      $navbar = <<<EOD_NAVBAR
<ul class="smo-navlist">
<li><a href="/toisich/" title="Sabhal Mór Ostaig - prìomh dhuilleag (le dà briog)">SMO</a>
<li><a href="/teanga/" title="Goireasan iol-chànanach aig SMO">Teanga</a>$sruthCeangal
$ceangalRiMoSMO
<li class="deas" onclick="document.cookie='sruthSeall=$sruthSeallEile';location.reload();"><a title="$teacsaPutanFios">$teacsaPutan</a>
$cobhairHtml
<li style="float:right" title="$T_canan_eadarAghaidh">$selCanan$trPutan
</ul>
EOD_NAVBAR;
      return $navbar;
  }

  public function __construct ($smid=null) {
      $stordataConnector = self::stordataConnector();
      $DbSruth = $stordataConnector::singleton('rw');
      $this->sArr = $this->sArr= $this->snArr = $this->nsArr = [];
      $stmtSELsruths = $DbSruth->prepare('SELECT * FROM sruths ORDER BY s');
      $stmtSELsruths->execute();
      while ($row = $stmtSELsruths->fetch()) {
          extract($row); 
          $this->sArr[$s] = array($t,$csmid);
          $this->nArr[$s] = $this->snArr[$s] = [];
      }
      $stmtSELsruthns = $DbSruth->prepare('SELECT * FROM sruthns');
      $stmtSELsruthns->execute();
      while ($row = $stmtSELsruthns->fetch()) {
          extract($row);
          $this->snArr[$s][$n] = $astar;
          if (!isset($this->nsArr[$n])) { $this->nsArr[$n] = []; } 
          $this->nsArr[$n][$s] = $astar;
      }
  }


  public static function sHtml($s,$ceangal=1) { //Cruthaich HTML airson putan a sheallas abairt le ceangal
      $T = new SM_T('sruth/s');
      $stordataConnector = self::stordataConnector();
      $DbSruth = $stordataConnector::singleton('rw');
      $stmt = $DbSruth->prepare('SELECT t,a,pailt FROM sruths WHERE s=:s');
      $stmt->execute(array(':s'=>$s));
      if (!($row = $stmt->fetch(PDO::FETCH_ASSOC)))
          { throw new SM_Exception('Mearachd sa function <b>putan</b>: Chan eil abairt ' . htmlspecialchars($s) . ' ann'); }
      extract($row);
      if ($pailt==0) { $pailtClass = ''; }
      if ($pailt==1) { $pailtClass = ' pailt1'; }
      if ($pailt==2) { $pailtClass = ' pailt2'; }
      if ($pailt==3) { $pailtClass = ' pailt3'; }

      if ($pailt==0) { $pailtHtml = ''; }
      if ($pailt==1) { $pailtHtml = '★';   $title = $T->_('cumanta'); }
      if ($pailt==2) { $pailtHtml = '★★';  $title = $T->_('glé chumanta'); }
      if ($pailt==3) { $pailtHtml = '★★★'; $title = $T->_('glé glé chumanta'); }
      if ($pailtHtml) { $pailtHtml = "<span style='color:orange' title='$title'>$pailtHtml</span>"; }

      $aHtml = htmlspecialchars($a) . " $pailtHtml";
      if ($ceangal) { $html = "<a class='abairt$pailtClass' draggable=false lang=$t href=\"s.php?s=$s\">$aHtml</a>";
                      $html = "<div class=s draggable=true data-name=s$s>$html</div>"; }
       else         { $html = "<span class='abairt$pailtClass' lang=$t>$aHtml</span>"; }
      return $html;
  }


  public static function ainmTeanga() {
      $myCLIL = SM_myCLIL::singleton();
      if (self::sruthSeall()=='uile') {
          $ainmean = array('gd' =>'Gàidhlig',
                           'ga' =>'Gaeilge',
                           'gv' =>'Gaelg',
                           'en' =>'English',
                           'fr' =>'Français',
                           'it' =>'Italiano',
                           'es' =>'Español',
                           'la' =>'Latine',
                           'de' =>'Deutsch',
                           'cy' =>'Cymraeg',
                           'sga'=>'Sengoídelc');
      } else {
          $ainmean = array('gd'=>'Gàidhlig',
                           'ga'=>'Gaeilge',
                           'en'=>'English');
      }
      return $ainmean;
  }
 

  public static function liostaTeanga() {
  //Returns leithid:  ('gd','ga','gv','en','fr')
      $ainmTeanga = self::ainmTeanga();
      $teangaithe = array_keys($ainmTeanga);
      foreach ($teangaithe as &$t) { $t = "'$t'"; }
      $liosta = '(' . implode(',',$teangaithe) . ')';
      return $liosta;
  }


  public static function meitHtmlArr() {
      $arr = array(
        -3 => '≪',   // U+226A  MUCH LESS THAN
        -2 => '≺',   // U+227A  PRECEEDS 
        -1 => '≼',   // U+227C  PRECEEDS OR EQUAL TO
         0 => '–',   // U+2013  EN DASH
         1 => '≽',   // U+227D  SUCCEEDS OR EQUAL TO
         2 => '≻',   // U+227B  SUCCEEDS
         3 => '≫'); // U+226B  MUCH GREATER THAN
      return $arr;
  }

  public static function meitHtml($meit) {
      switch ($meit) {
        case -3: return '≪';
        case -2: return '≺';
        case -1: return '<span style="color:#999">≼</span>';
        case  0: return '<span style="color:#ddd">–</span>';
        case  1: return '<span style="color:#999">≽</span>';
        case  2: return '≻';
        case  3: return '≫';
        default: throw new SM_Exception("\$meit neo-iomchaidh: $meit");
      }
  }
 

  public static function fiosAbairt($s) {
  //Returns am fiosrachadh gu léir bhon record airson abairt $s
      $stordataConnector = self::stordataConnector();
      $DbSruth = $stordataConnector::singleton('rw');
      $stmt = $DbSruth->prepare('SELECT * FROM sruths WHERE s=:s');
      $stmt->execute(array(':s'=>$s));
      $res = $stmt->fetch(PDO::FETCH_ASSOC);
      return $res;
  }


  public static function insertAbairt($t,$a,$cinnFhacail,$buidhnean,$csmid=NULL,$cutime=NULL,$sgrud=1) {
  //A' gabhail teanga, abairt, array de dh'fhacail-cinn, array de bhuidhnean; agus
  //a' cur na facail-cinn agus buidhnean ris an abairt sin ma tha e sa stòras cheana.
  //Mura bheil, cleachdar am fiosrachadh airson abairt ùr a chur sa stòras.
      if (!is_array($cinnFhacail)) { throw new SM_Exception('Feumaidh am parameter $cinnFhacail dhan function insertAbairt bhith na array'); }
      if (!is_array($buidhnean))   { throw new SM_Exception('Feumaidh am parameter $buidhnean dhan function insertAbairt bhith na array');   }
      if ($sgrud<>1) { $sgrud=0; } //paranoia
      $myCLIL = SM_myCLIL::singleton();
      $smid = $moSMO->id;
      if (is_null($csmid))  { $csmid  = $smid;  }
      if (is_null($cutime)) { $cutime = time(); }
      $msmid  = $smid;
      $mutime = time();
      $stordataConnector = self::stordataConnector();
      $DbSruth = $stordataConnector::singleton('rw');
      $stmtSEL = $DbSruth->prepare('SELECT s FROM sruths WHERE t=:t AND a=:a');
      $stmtSEL->execute(array(':t'=>$t,':a'=>$a));
      if ($res = $stmtSEL->fetch()) {
          $s = $res['s'];
          $stmtUPDsruths = $DbSruth->prepare('UPDATE sruths SET sgrud=1 WHERE s=:s');
          $stmtUPDsruths->execute(array(':s'=>$s));
      } else {
          $stmtINSsruths = $DbSruth->prepare('INSERT INTO sruths(t,a,csmid,cutime,msmid,mutime,sgrud) VALUES (:t,:a,:csmid,:cutime,:msmid,:mutime,:sgrud)');
          $stmtINSsruths->execute(array(':t'=>$t,':a'=>$a,':csmid'=>$csmid,':cutime'=>$cutime,':msmid'=>$msmid,':mutime'=>$mutime,':sgrud'=>$sgrud));
          $s = $DbSruth->lastInsertId();
      }
      $stmtINSsruthc = $DbSruth->prepare('INSERT IGNORE INTO sruthc(s,c,smid) VALUES (:s,:c,:smid)');
      foreach ($cinnFhacail as $c) { $stmtINSsruthc->execute(array(':s'=>$s,':c'=>$c,':smid'=>$smid)); }
      $stmtINSsruthns = $DbSruth->prepare('INSERT IGNORE INTO sruthns(n,s,smid) VALUES (:n,:s,:smid)');
      foreach ($buidhnean as $n) { $stmtINSsruthns->execute(array(':n'=>$n,':s'=>$s,':smid'=>$smid)); }
      return $s;
  }


  public function nabaidhean($s0,$astarMax=2) {
  //Tillidh seo array de na nabaidhean a tha taobh a-stigh astar $astarMax de abairt $s
      $stordataConnector = self::stordataConnector();
      $DbSruth = $stordataConnector::singleton('rw');
      $nabArr = [$s0=>0];
      $piseach = 1;
      while ($piseach>0) {
          $piseach = 0;
          foreach ($nabArr as $s1=>$ast1) {
              foreach ($this->snArr[$s1] as $n=>$astarn) {
                  foreach ($this->nsArr[$n] as $s2=>$astars) {
                      $ast = $ast1 + $astarn + $astars;
                      if ($ast<=$astarMax) {
                          if (!isset($nabArr[$s2]) || $nabArr[$s2]>$ast) { 
                              $nabArr[$s2] = $ast;
                              $piseach=1;
                          }
                      }
                  }
              }
          }
      }
      unset($nabArr[$s0]);
      asort($nabArr);
      return $nabArr;
  }


  public static function cLiosta ($t=null) {
  //Tillidh seo data list HTML de dh'fhacail-cinn airson cànan $t
      $stordataConnector = self::stordataConnector();
      $DbSruth = $stordataConnector::singleton('rw');
      $stmt = $DbSruth->prepare('SELECT DISTINCT c FROM sruthc,sruths WHERE sruthc.s=sruths.s AND t LIKE :t ORDER BY c');
      if (empty($t)) { $t = '%'; }
      $stmt->execute(array(':t'=>$t));
      $cLiosta = $stmt->fetchAll(PDO::FETCH_COLUMN,0);
      $cLiostaHtmlArr = [ ];
      foreach ($cLiosta as $c) { $cLiostaHtmlArr[] = "<option value=\"$c\">"; }
      $cLiostaHtml = implode("\n",$cLiostaHtmlArr);
      $cLiostaHtml = <<<EODcLiosta
<datalist id="cLiosta_$t">
$cLiostaHtml
</datalist>
EODcLiosta;
      return $cLiostaHtml;
  }

}
?>
