<?php
class spilib{
static $nt='';
static $max=120;
static $rsub=[];
static $rhid=[];
static $rx=[];

static $keys=['name','symbole','family','layer','orbital level','fusion','ebulition','discovery','mass','atomic mass','isotopes','atomic number','origin','degree','position','valence'];

/*static $clr0=[''=>'ccc','Nonmetals'=>'92FF10','Nobles Gasses'=>'05FFFF','Alkali Metals'=>'FF9801','Alkali Earth Metals'=>'BF6700','Metalloids'=>'91C9D6','Halogens'=>'FFFF00','Metals'=>'ABABB0','Transactinides'=>'C9C97A','Lanthanides'=>'B3D7AB','Actinides'=>'75ADAB','undefined'=>'ffffff'];
static $clr1=[''=>'ccc','Nonmetals'=>'e8592d','Nobles Gasses'=>'fbbf09','Alkali Metals'=>'c1cf00','Alkali Earth Metals'=>'95a112','Metalloids'=>'6b7612','Halogens'=>'52c2e9','Metals'=>'adaeac','Transactinides'=>'3d80be','Lanthanides'=>'4e68ab','Actinides'=>'a377ae','undefined'=>'ffffff'];*/

static $clr=['Nonmetals'=>'5FA92E','Nobles Gasses'=>'00D0F9','Alkali Metals'=>'FF0008','Alkali Earth Metals'=>'FF00FF','Metalloids'=>'1672B1','Halogens'=>'F6E617','Metals'=>'999999','Transactinides'=>'FF9900','Lanthanides'=>'666698','Actinides'=>'9D6568','undefined'=>'ffffff'];
static $origin_clr=[1=>'ff0000',2=>'ff2222',3=>'ff4444',4=>'ff6666',5=>'ff8888',6=>'ffaaaa',7=>'ffcccc',8=>'ffeeee'];
static $origin_names=[1=>'Big Bang',2=>'cosmic ray collisions',3=>'dying low-mass stars',4=>'dying high-mass stars',5=>'white dwarf supernovae',6=>'merging neutron stars',7=>'radioactive decay',8=>'human-made'];
static $rc=[];//legends

static function css(){$ret='rect:hover{box-shadow:0px 0px 4px #aaa;}
.atom_num{font-size:14px; font-style:italic;}
.atom_sym{font-size:18px; font-weight:bold;}
.atom_sym2{font-size:36px; font-weight:normal; text-align:center; padding:10px;}
.atom_mass{font-size:12px; float:right;}
.atom_name{font-size:12px; font-style:capitalize; text-align:center;}
.atom_block{display:block; width:100px; height:100px; border:2px solid black;}
.atom_text{display:table-cell; background:var(--w5);}
.atom_content{display:table-row; background:var(--w5);}';
//for($i=1;$i<=self::$max;$i++)$ret.='#id'.$i.':hover{background:rgba(255,255,255,0.4);}'."\n";
return $ret;}

static function js($n=1,$o='',$nt=''){//self::patch();
$j='spt|spitable'.$nt.',call';
$atoms=db_read('db/public/atomic','','',1);
$ra=$atoms?json_encode(($atoms)):[];//utf_r
return 'var n='.$n.';
//addEvent(document,"DOMMouseScroll",function(){wheelcount(event,"'.$j.'","'.$o.'")});

function checkArrows(e,id){
var n=getbyid(\'bar\'+id).value; //alert(n);
if(e && e.which)var c=e.which; else var c=e.keyCode; //alert(c);
if(c==37)var nb=n-1; if(c==39)var nb=n-(-1); //alert(nb);
getbyid(\'bar\'+id).value=nb;
getbyid(\'lbl\'+id).innerHTML=nb;
ajbt(\''.$j.'|\'+(nb)+\',\'+\''.$o.'\');}
addEvent(document,\'keypress\',function(event){checkArrows(event,id)});

function spiatom(n,id){
	getbyid(\'bar\'+id).value=n;
	getbyid(\'lbl\'+id).innerHTML=n;
	ajbt(\''.$j.'|\'+n+\',\'+\''.$o.'\');}

atoms='.$ra.'; //atoms[1][2];
//import atoms from ./js/atoms.js;

function coloriz(n){n=n==1?12:16;
for(i=1;i<=120;i++)window["id"+i].style.fill="#"+atoms[i][n];}

function coloriz2(n){
if(n==1||n==2)return coloriz(n); var r=atoms_clr(n);
for(i=1;i<=120;i++)window["id"+i].style.fill=r[i];}

function atoms_clr(n){
var r=[]; var min=0; var max=0; var ratio=1; var diff=0; var res=0; var ret=0;
for(i=1;i<=120;i++){
	ret=atoms[i][n]; //alert(ret);
	ret=ret.replace(",","."); //ret=parseFloat(ret); //var ret=ret*1; //alert(n);
	if(ret=="N/A" || ret=="---")ret="-";
	else if(ret.indexOf("?")!=-1)ret=ret.substr(0,ret.indexOf("?"));
	else if(ret.indexOf("@")!=-1)ret=ret.substr(0,ret.indexOf("@"));
	else if(ret.indexOf("±")!=-1)ret=ret.substr(0,ret.indexOf("±"));
	else if(ret.indexOf("/")!=-1)ret=ret.substr(0,ret.indexOf("/"));
	else if(ret.indexOf(" ")!=-1)ret=ret.substr(0,ret.indexOf(" "));
	ret=parseFloat(ret);
	if(ret<min)min=ret; if(ret>max)max=ret; r[i]=ret;}
	//console.log(i+":"+ret+"="+max);
//if(!min)min=Math.min(r); if(!max)var max=Math.max(r); 
var diff=max-min; var ratio=255/diff; var ratio1=255/max; if(min)var ratio2=255/min;
var red=255; var green=255; var blue=255;
for(i=1;i<=120;i++){
	if(min<0){
		if(r[i]>=0){res=Math.round(r[i]*ratio1); red=255; green=res; blue=res;}
		else{res=Math.round(r[i]*ratio2); red=255-res; green=255-res; blue=255;}}
	else if(min>=0){res=Math.round((r[i]-min)*ratio); red=255; green=255-res; blue=255-res;}
	else{red=127; green=127; blue=127;}
	r[i]="rgb("+red+","+green+","+blue+")";
	r[i]="#"+dec2hex(red)+""+dec2hex(green)+""+dec2hex(blue);
	//console.log(i+":"+r[i]+"="+res+" clr:"+red+","+green+","+blue);
	}
return r;}

function anim(n){n++; x=setTimeout(function(){play(n)},500);}
function play(n=0,p){
	if(n==0 && typeof x=="number"){clearTimeout(x); n=120; x="";}
	ajbt("'.$j.'|"+n+","+p);
	if(n<120)anim(n);}

//getbyid("btfs").addEventListener("click",()=>{toggleFullscreen("spt");});
';}

static function infos($p){
$d=$p['p1']??''; $o=$p['o']??'';
$r=db_read_row('db/public/atomic',$d);
if(!isset($r['name']))return span('Element '.$d,'btn');
[$or1,$or2]=expl('/',$r['origin']);
$r['origin']=self::$origin_names[$or1];
if($or2)$r['origin'].=' / '.self::$origin_names[$or2];
$ret=self::vue($p,$r);
if(!$o)$ret.=div(tabler($r,'',1),'atom_text');
return div($ret,'atom_content');}

static function vue($p,$r=[]){$d=$p['p1']??'';
if(!$r)$r=db_read_row('db/public/atomic',$d);
$ret=div($r['atomic mass'],'atom_mass');
$ret.=div($r['atomic number'],'atom_num');
$ret.=div($r['symbole'],'atom_sym2');
$ret.=div($r['name'],'atom_name');
$clr=spilib::findclr($r['family']);
$s='background:#'.$clr.'; ';
$s.='color:#'.clrneg($clr,1).';';
return div($ret,'atom_block','',$s);}

static function legend($o){$rt=[]; $bt='';
if($o==5 or $o==6 or $o==8 or $o==9 or $o==10)$r=self::$rc;
elseif($o==12)$r=array_combine(self::$origin_names,self::$rc);//self::$origin_clr
elseif($o==2){$r=self::clr2(); $r=array_keys(array_flip($r));}
else $r=self::basic_colors();
$s='display:inline-block; color:black; padding:4px; border:1px solid black;';
foreach($r as $k=>$v)$rt[]=span($k,'','','background:#'.$v.'; '.$s);
if($o==5 or $o==6)$bt='C°'; elseif($o==8)$bt='g/L'; elseif($o==9)$bt='g/Mol';
elseif($o==10)$bt='isotopes'; elseif($o==2)$bt='positions'; elseif($o==12)$bt='origin';
if($bt)$rt[]=span($bt,'','','background:#ffffff; '.$s);
return div(join('',$rt));}

/*static function legend_svg(){$i=0; $ret='';
$r=spilib::basic_colors(); $w=100; $h=20; $y=20; $x=60; $n=count($r); $nc=16500000/$n;
$sz=[1=>70,2=>100,3=>90,4=>120,5=>70,6=>60,7=>50,8=>90,9=>80,10=>60,11=>70];
foreach($r as $k=>$v)if($k){$i++; 
	$w=strlen($k)*8; $w=$sz[$i];
	$ret.='[#'.$v.',gray:attr]['.$x.','.$y.','.$w.','.$h.':rect]';
	$ret.='[black,,,14px:attr]['.($x+4).','.($y+15).',font-size:12px*'.$k.':text]';
	$x+=$w;}
	$ret.='[50,640,9*@Davy 2003-2025:text]';
return $ret;}*/

/*static function patch(){
$r=db_read('db/public/atomic','','',1); $rb=self::$clr;
$rh=['Nom','Symbole','Famille','Couche','Niveau orbital','Fusion','Ebulition (C°)','Découverte','Masse','Masse atomique (u)','Isotopes','Numéro atomique','clr','pos','free','deg','clr2'];
foreach($r as $k=>$v)$r[$k][12]=$rb[$v[2]];
db_save('db/public/atomic',$r);}*/

//clr
static function basic_colors(){return self::$clr;}
static function findclr($k){$r=self::basic_colors();
return $r[$k]?$r[$k]:'ffffff';}

static function build_clr($v,$min,$ratio,$ratio1,$ratio2){
if(!is_numeric($v)){$red=127; $green=127; $blue=127;}
elseif($min<0){
	if($v>=0){$d=round($v*$ratio1); $red=255; $green=$d; $blue=$green;}
	else{$d=0-round($v*$ratio2); $red=255-$d; $green=$red; $blue=255;}}
elseif($min>=0){$d=round(($v-$min)*$ratio); $red=255; $green=255-$d; $blue=$green;}
return rgb2hex([$red,$green,$blue]);}

static function build_hsl($v,$ratio,$l=360){//hsl()
if(!is_numeric($v))$h=0; else $h=round($v*$ratio);
return rgb2hex(hsl2rgb($l-$h,50,50));}

static function clr3($r,$col){
$ra=array_keys_r($r,$col); $min=0; $max=1; $rb=[]; $rc=[]; $ratio=1; $ratio1=1; $ratio2=1;
foreach($ra as $k=>$v){$v=str_replace(',','.',$v);
	if($v=='N/A' or $v=='---')$v='-';
	elseif(strpos($v,'@')!==false)$v=substr($v,0,strpos($v,'@'));
	elseif(strpos($v,'±')!==false)$v=substr($v,0,strpos($v,'±'));
	elseif(strpos($v,' ')!==false)$v=substr($v,0,strpos($v,' '));
	elseif(strpos($v,'/')!==false)$v=substr($v,0,strpos($v,'/'));
	if(intval($v)<$min)$min=$v; if(intval($v)>$max)$max=$v; $ra[$k]=$v;}//pr($ra);
//$min=min($ra); $max=max($ra); 
$diff=$max-$min; 
$ratio=255/$diff; 
$ratio1=255/$max; 
if($min)$ratio2=255/(0-$min);//rgb
//$diff=$max-($min); $ratio=360/$diff; $ratio1=360/$max; if($min)$ratio2=360/(0-$min);//hsl
foreach($ra as $k=>$v)
	//$rb[$k]=self::build_clr($v,$min,$ratio,$ratio1,$ratio2);
	$rb[$k]=self::build_hsl($v,$ratio,255);
$n=20; if($n>$diff)$n=$diff; $sec=round($diff/$n);
for($i=1;$i<=$n;$i++){$k=round($min+($sec*$i));
	//$clr=self::build_clr($k,$min,$ratio,$ratio1,$ratio2);
	$clr=self::build_hsl($k,$ratio,255);
	spilib::$rc[$k]=$clr;}
return $rb;}

static function clr2(){
$r=db_read('db/public/atoms','','',1);
return array_keys_r($r,4);}

static function center_text($t,$w,$sz=12){
$n=strlen($t); $l=$n*($sz/2);
return round(($w-$l)/2);}

//response
static function search($p){
$srch=strtolower($p['srch']); $t='spitable'.$p['nt'];
$r=db_read('db/public/atomic','','',1);
foreach($r as $k=>$v)if(strtolower($v[0])==$srch||strtolower($v[1])==$srch||$v[4]==$srch)$p['p1']=$k;
return $t::call($p);}

static function clrtypes($rid,$c,$nt){$ret='';
$rm=[1=>'clr1',2=>'clr2',5=>'fusion',6=>'ebulition',8=>'mass',9=>'atomic mass',10=>'isotopes',12=>'origin'];
foreach($rm as $k=>$v)$ret.=bj($rid.'|spitable'.$nt.',call|clr='.$k.'|lbar,mode,helico',$v,'btn'.active($c,$k));
return div($ret);}

//build
static function findpos2($i){
$r=[1=>2,6,10,14,18]; $n=$i-120; $sub=0;
if($n<=2)$ring=8; elseif($n<=20)$ring=5; elseif($n<=34)$ring=6;
elseif($n<=44)$ring=7; elseif($n<=52)$ring=8; elseif($n<=54)$ring=9;
foreach($r as $k=>$v)if($n<=$v && !$sub){$sub=$k; $pos=$n;}
return [$ring,$sub,$pos];}

static function findpos($level,$i){
//if($i>120)return self::findpos2($i);
$ring=substr($level,0,1); $sub=0;
$subring=substr($level,1,1);
$pos=substr($level,2);
if($subring=='s')$sub=1;
elseif($subring=='p')$sub=2;
elseif($subring=='d')$sub=3;
elseif($subring=='f')$sub=4;
elseif($i==119)return [8,1,1];
elseif($i==120)return [8,1,2];
return [$ring,$sub,$pos];}

static function atom_toggle($r,$ring){
[$name,$sym,$fam,$layer,$level]=arr($r,5);
$ra=explode('-','-'.$layer);
$max=val($ra,$ring); self::$rx[$ring][]=1;//count elements in rings
return count(self::$rx[$ring])<=$max?0:1;}

static function attempt_subrings($r,$p){$ra=[]; $rb=[]; $rh=[];
for($i=1;$i<=self::$max;$i++){$level=$r[$i][4]; //if($i<=$p)
	[$ring,$subring,$pos]=self::findpos($level,$i);
	$hide=self::atom_toggle($r[$p]??'',$ring); $rh[$i]=$hide;
	if(!$hide or $p==$i)$ra[$ring][$i]=$subring;}
foreach($ra as $k=>$v){$rb[$k]=max($v);}
self::$rx=[];
self::$rhid=$rh;
self::$rsub=$rb;}

//nav
static function nav($a,$o,$c,$h,$nt,$noclr=0){$t='spitable'.$nt; $rid=$t::$cb;
$ret=bj('main|spitable|mode=radial|lbar,clr',langp('spitable'),'btn'.active($nt,'')).' ';
$ret.=bj('main|spitable2|mode=radial|lbar,clr',langp('spitable-linear'),'btn'.active($nt,'2')).' ';
$ret.=bj('main|spitable4|mode=diamond|lbar,clr',langp('spitable-layers'),'btn'.active($nt,'4')).' ';
//$ret.=bj('main|spitable5|mode=spherical|lbar,clr',langp('spitable-spherical'),'btn'.active($nt,'5')).' ';
$ret.=bj('main|spitable6|mode=classical|lbar,clr',langp('spitable-biface'),'btn'.active($nt,'6')).' ';
if(ses('dev')=='prog')$ret.=bj('main|spitable7|mode=classical|lbar,clr',langp('spitable-rings'),'btn'.active($nt,'7')).' ';
$ret.=bj('main|spitable8||lbar,clr',langp('subrings'),'btn'.active($nt,'8')).' ';
$ret.=$t::navb($a,$o,$c,$h);
if(!$noclr)$ret.=self::clrtypes($rid,$c,$nt);
return div($ret);}

static function menu($p,$o,$rid,$nt){
//$j=$rid.'|spitable'.$nt.',call|p2='.$o.'|inpspi';
//$ret=inputcall($j,'inpspi',$p);
//$ret.=bj($j,picto('ok',24),'btsav').' ';
$t='spitable'.$nt; $max=$t::$max;
$j=$rid.'|spitable'.$nt.',call||lbar,mode,clr,helico';
$ret=bar('lbar',$p,1,1,$max,'1',$j,'','');
//if($p>0)$ret.=bj($rid.'|spitable'.$nt.',call|p1='.($p-1).',p2='.$o,picto('previous'),'btn').'';
//if($p<self::$max)$ret.=bj($rid.'|spitable'.$nt.',call|p1='.($p+1).',p2='.$o,picto('next'),'btn').' ';
$ret.=hlpbt('spitable_app');
$ret.=db::bt('db/public/atomic');
$ret.=btj(langp('fullscreen'),atj('toggleFullscreen','sptcb'),'btn','btfs');
$ret.=bj('pagup|web,playnet|url=newsnet.fr/155555',langp('article'),'btn');
//$ret.=btn('txtx','Davy@2025');
$j=$rid.'|spilib,search|nt='.$nt.'|srch,lbar,mode,clr,helico';
$ret.=inputcall($j,'srch','',8,lang('element')).hlpbt('spitable_search');
//$ret.=lk('/spitable'.$nt.'',pic('url'),'btn');
return div($ret);}

static function iframe($p){
head::add('csslink','/css/global.css');
head::add('csslink','/css/apps.css');
head::add('csslink','/css/fa.css');
head::add('jslink','/js/ajax.js');
head::add('jslink','/js/utils.js');
$ret=self::content($p);
$ret=tag('body',['onmousemove'=>'popslide(event)','onmouseup'=>'closebub(event)'],$ret);
$ret.=tag('div',['id'=>'closebub','onclick'=>'bubClose()'],'');
$ret.=tag('div',['id'=>'popup'],'');
return head::run().$ret;}

}
?>