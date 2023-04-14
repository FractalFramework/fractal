<?php
class spitable{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='spt';
static $max=120;
static $unit=40;
static $src='img/svg/spihelix.svg';

static function admin(){
$r=admin::app(['a'=>self::$a,'db'=>self::$db]);
//$r[]=['','lk','/spitable','','spitable2'];
return $r;}

static function js($n=1,$o=''){//self::patch();
$j='spt|spitable,call';
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
static function headers(){
//head::add('jscode',self::js($p,$o));
head::add('csscode',self::css());}

static function infos($p){$d=$p['p1']??'';
$r=db_read_row('db/public/atomic',$d);
if(!isset($r['name']))return span('Element '.$d,'btn');
[$or1,$or2]=expl('/',$r['origin']);
$r['origin']=self::$origin_names[$or1];
if($or2)$r['origin'].=' / '.self::$origin_names[$or2];
$ret=self::vue($p,$r);
$ret.=div(tabler($r,'',1),'atom_text');
return div($ret,'atom_content');}

static function vue($p,$r=[]){$d=$p['p1']??'';
if(!$r)$r=db_read_row('db/public/atomic',$d);
$ret=div($r['atomic mass'],'atom_mass');
$ret.=div($r['atomic number'],'atom_num');
$ret.=div($r['symbole'],'atom_sym2');
$ret.=div($r['name'],'atom_name');
$s='background:#'.self::$clr[$r['family']];
return div($ret,'atom_block','',$s);}

static $keys=['name','symbole','family','layer','orbital level','fusion','ebulition','discovery','mass','atomic mass','isotopes','atomic number','origin','degree','position','valence'];
static $clr=['Nonmetals'=>'5FA92E','Nobles Gasses'=>'00D0F9','Alkali Metals'=>'FF0008','Alkali Earth Metals'=>'FF00FF','Metalloids'=>'1672B1','Halogens'=>'F6E617','Metals'=>'999999','Transactinides'=>'FF9900','Lanthanides'=>'666698','Actinides'=>'9D6568','undefined'=>'ffffff'];
static $origin_clr=[1=>'ff0000',2=>'ff2222',3=>'ff4444',4=>'ff6666',5=>'ff8888',6=>'ffaaaa',7=>'ffcccc',8=>'ffeeee'];
static $origin_names=[1=>'Big Bang',2=>'cosmic ray collisions',3=>'dying low-mass stars',4=>'dying high-mass stars',5=>'white dwarf supernovae',6=>'merging neutron stars',7=>'radioactive decay',8=>'human-made'];
static $rc=[];//legends

static function findclr($d){$r=self::$clr;
return $r[$d]?$r[$d]:'ffffff';}

static function legend($o){$rt=[]; $bt='';
if($o==5 or $o==6 or $o==8 or $o==9 or $o==10)$r=self::$rc;
elseif($o==12)$r=array_combine(self::$origin_names,self::$rc);//self::$origin_clr
elseif($o==2){$r=self::clr2(); $r=array_keys(array_flip($r));}
else $r=self::$clr;
$s='display:inline-block; color:black; padding:4px; border:1px solid black;';
foreach($r as $k=>$v)$rt[]=span($k,'','','background:#'.$v.'; '.$s);
if($o==5 or $o==6)$bt='C°'; elseif($o==8)$bt='g/L'; elseif($o==9)$bt='g/Mol';
elseif($o==10)$bt='isotopes'; elseif($o==2)$bt='positions'; elseif($o==12)$bt='origin';
if($bt)$rt[]=span($bt,'','','background:#ffffff; '.$s);
return div(join('',$rt));}

static function helicoid(){
$rx[1]=[1=>-1,0];
$ry[1]=[1=>0,0];
$rx[2]=[1=>0,-2,-2,-2,0,1];
$ry[2]=[1=>-1,-1,0,2,2,0];
$rx[3]=[1=>2,2=>0,3=>-2,4=>-3,5=>-3,6=>-3,7=>-2,8=>0,9=>2,10=>2];
$ry[3]=[1=>-2,2=>-2,3=>-2,4=>-2,5=>0,6=>2,7=>3,8=>3,9=>2,10=>0];
$rx[4]=[1=>3,2=>2,3=>0,4=>-2,5=>-4,6=>-4,7=>-4,8=>-4,9=>-4,10=>-2,11=>0,12=>2,13=>3,14=>3];
$ry[4]=[1=>-2,2=>-3,3=>-3,4=>-3,5=>-3,6=>-2,7=>0,8=>2,9=>4,10=>4,11=>4,12=>4,13=>2,14=>0];
//atom angle
$rg[1]=str_split('011');
$rg[2]=str_split('0001001');
$rg[3]=str_split('01001110011');
$rg[4]=str_split('010000111000011');
return [$rx,$ry,$rg];}

static function helicoid2(){
$rx[1]=[1=>-1,0];
$ry[1]=[1=>0,0];
$rx[2]=[1=>-1,-1,1,-2,1,-2];
$ry[2]=[1=>2,-1,1,-1,-1,1];
$rx[3]=[1=>0,2=>-2,3=>-2,4=>0,5=>-3,6=>2,7=>-3,8=>2,9=>-3,10=>2];
$ry[3]=[1=>-2,2=>3,3=>-2,4=>3,5=>-2,6=>2,7=>0,8=>0,9=>2,10=>-2];
$rx[4]=[1=>-3,2=>1,3=>-1,4=>-1,5=>1,6=>-3,7=>3,8=>-4,9=>3,10=>-4,11=>3,12=>-4,13=>3,14=>-4];
$ry[4]=[1=>4,2=>-3,3=>4,4=>-3,5=>4,6=>-3,7=>3,8=>-3,9=>1,10=>-1,11=>-1,12=>1,13=>-3,14=>3];
//atom angle
$rg[1]=str_split('011');
$rg[2]=str_split('0001111');
$rg[3]=str_split('00000111111');
$rg[4]=str_split('000000011111111');
return [$rx,$ry,$rg];}

static function xwidth($d){
}

//atom
static function atompos($ring,$sub,$pos,$i,$v,$nm,$o,$h,$iso){
$u=self::$unit;//unit
[$mode,$clr,$size]=expl('-',$o,3);
$zerox=1; $zeroy=4;
//position on rings
if($h==2)[$rx,$ry,$rg]=self::helicoid2();
else [$rx,$ry,$rg]=self::helicoid();
//ring_distance//if radial2
$rd=[1=>1,2=>5,3=>11,4=>19,5=>28,6=>36,7=>42,8=>46];
//ring distant//vertical
$rdx=[1=>2,2=>6,3=>12,4=>20,5=>4,6=>12,7=>18,8=>22,9=>26];
//$rdy=[1=>1,2=>1,3=>1,4=>1,5=>8,6=>8,7=>8,8=>8,9=>8];
$rdy=[1=>0,2=>0,3=>0,4=>0,5=>7,6=>7,7=>7,8=>7,9=>7];
//mode
if($mode=='linear'){$addx=($rd[$ring]*$u); $addy=20;}
else{$addx=($rdx[$ring]*$u); $addy=($rdy[$ring]*$u);}
//x,y,w,h
$x=($rx[$sub][$pos]+$zerox)*$u+$addx;
$y=($ry[$sub][$pos]+$zeroy)*$u+$addy;
//t, vertical-horizontal
$dcx=round($u/2); $ub=$u*2;
$l1=strlen($i); $dcx1=$dcx-$l1*4.5;
$l2=strlen($v); $dcx2=$dcx-$l2*6;
$l3=strlen($nm); $dcx3=$dcx-$l3*2;
$dcy=$dcx+6; $sz=10+($u/10); //$s='font-size:'.$sz.'px';
$ret='[black:attr]';//,,,14px
$j='spt|spitable,call|p1='.$i.',|mode,clr,helico';
//$ret.='['.$j.'*['.($x+$dcx1).','.($y+$dcy).',,atom_num*'.$i.':text]:bj]';
//$ret.='['.$j.'*['.($x+$dcx1).','.($y+$dcy).',,atom_num*'.$i.':text]:bjo]';
$rp=['onclick'=>'ajbt(this);'.atj('val',[$i,'lbar']).atj('val',[$i,'lbllbar'])];//onmouseover
$ret.=bj($j,'['.($x+$dcx1).','.($y+$dcy).',,atom_num*'.$i.':text]','',$rp);
$ret.='['.($x+2).','.($y+10).',9*'.$pos.':text]';
$j='popup|spitable;infos|p1='.$i.'';
if($rg[$sub][$pos]){$h=$ub; $w=$u;//vertical
	//$ret.='['.($x+$w-12).','.($y+10).',9*'.$iso.':text]';
	//$ret.='['.$j.'*['.($x+$dcx2).','.($y+$ub-$dcy+2).',,atom_sym*'.$v.':text]:bj]';
	$ret.='['.$j.'*['.($x+$dcx2).','.($y+$dcy+$u).',,atom_sym*'.$v.':text]:bubj]';}
else{$h=$u; $w=$ub;
	//$ret.='['.($x+$w-12).','.($y+10).',9*'.$iso.':text]';
	//$ret.='['.$j.'*['.($x+$u+$dcx2).','.($y+$dcy+2).',,atom_sym*'.$v.':text]:bj]';
	$ret.='['.$j.'*['.($x+$u+$dcx2).','.($y+$dcy).',,atom_sym*'.$v.':text]:bubj]';}
return [$x,$y,$w,$h,$ret];}

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

static function active($r,$ring){static $rx;
[$name,$sym,$fam,$layer,$level]=arr($r,5);
$ra=explode('-','-'.$layer);
$max=val($ra,$ring); $rx[$ring][]=1;//count elemenrs in rings
return count($rx[$ring])<=$max?0:1;}

static function atom($r,$i,$p,$o,$rc,$hl){
[$name,$sym,$fam,$layer,$level]=arr($r[$i],5); //echo $name.'-'.$sym.'-'.$fam.'-'.$level.br();
[$ring,$subring,$pos]=self::findpos($level,$i); //echo $ring.'-'.$subring.'-'.$pos.br();
[$mode,$clr,$size]=expl('-',$o,3);
[$x,$y,$w,$h,$t]=self::atompos($ring,$subring,$pos,$i,$sym,$name,$o,$hl,$r[$i][10]); //echo $x.'-'.$y.'-'.$w.'-'.$h;
$clr=self::findclr($fam); if($rc)$clr=$rc[$i];
$hide=$i<=$p?0:1; $hide=self::active($r[$p]??'',$ring);//anomalies
$bdr=$p==$i?'white':($hide?'gray':'black'); $sz=$p==$i?'2':'1'; $alpha=$hide?'0.3':'1';
$atr='[#'.$clr.','.$bdr.','.$sz.',,'.$alpha.':attr]';
$rect='['.$x.','.$y.','.$w.','.$h.',,id'.$i.':rect]';
return $atr.$rect.$t;}

//clr
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
$ra=array_keys_r($r,$col); $min=0; $max=0; $rb=[]; $rc=[]; $ratio=1; $ratio1=1; $ratio2=1;
foreach($ra as $k=>$v){$v=str_replace(',','.',$v);
	if($v=='N/A' or $v=='---')$v='-';
	elseif(strpos($v,'@')!==false)$v=substr($v,0,strpos($v,'@'));
	elseif(strpos($v,'±')!==false)$v=substr($v,0,strpos($v,'±'));
	elseif(strpos($v,' ')!==false)$v=substr($v,0,strpos($v,' '));
	elseif(strpos($v,'/')!==false)$v=substr($v,0,strpos($v,'/'));
	if(intval($v)<$min)$min=$v; if(intval($v)>$max)$max=$v; $ra[$k]=$v;}//pr($ra);
//$min=min($ra); $max=max($ra); 
$diff=$max-($min); $ratio=255/$diff; $ratio1=255/$max; if($min)$ratio2=255/(0-$min);//rgb
//$diff=$max-($min); $ratio=360/$diff; $ratio1=360/$max; if($min)$ratio2=360/(0-$min);//hsl
foreach($ra as $k=>$v)
	//$rb[$k]=self::build_clr($v,$min,$ratio,$ratio1,$ratio2);
	$rb[$k]=self::build_hsl($v,$ratio,255);
$n=20; if($n>$diff)$n=$diff; $sec=round($diff/$n);
for($i=1;$i<=$n;$i++){$k=round($min+($sec*$i));
	//$clr=self::build_clr($k,$min,$ratio,$ratio1,$ratio2);
	$clr=self::build_hsl($k,$ratio,255);
	self::$rc[$k]=$clr;}
return $rb;}

static function clr2(){
$r=db_read('db/public/atoms','','',1);
return array_keys_r($r,4);}

/*static function patch(){
$r=db_read('db/public/atomic','','',1); $rb=self::$clr;
$rh=['Nom','Symbole','Famille','Couche','Niveau orbital','Fusion','Ebulition (C°)','Découverte','Masse','Masse atomique (u)','Isotopes','Numéro atomique','clr','pos','free','deg','clr2'];
foreach($r as $k=>$v)$r[$k][12]=$rb[$v[2]];
db_save('db/public/atomic',$r);}*/

//build
static function build($p,$o,$c,$hl){//$o=0;
$r=db_read('db/public/atomic','','',1);
$u=self::$unit;//unit
if($o=='linear'){$u=self::$unit=30; $w=49*$u; $h=10*$u;}
elseif($o=='radial2'){$u=self::$unit=60; $w=25*$u; $h=18*$u;}
else{$o='radial'; $u=self::$unit; $w=25*$u; $h=18*$u;}
$max=self::$max; //p($r[$p]);
if($c==1)$rc=[];
elseif($c==2)$rc=self::clr2();
elseif($c)$rc=self::clr3($r,$c);
else $rc=[];//p($rc);
for($i=1;$i<=$max;$i++)$rb[]=self::atom($r,$i,$p,$o,$rc,$hl);
//$ret='[white,black,1:attr][0,0,'.$w.','.$h.':rect]';
$ret=implode("\n",$rb);
$bt=self::nav($p,$o,$c,$hl);
$bt.=self::legend($c);
if($ret)$ret=svg::call(['code'=>$ret,'w'=>$w,'h'=>$h,'fit'=>1]);
return $bt.$ret;}

static function call($p){//pr($p);
$inpspi=$p['inpspi']??self::$max;
$a=$p['p1']??($p['lbar']??$inpspi);
$o=$p['p2']??($p['mode']??'radial');
$c=$p['p3']??($p['clr']??1);
$h=$p['p4']??($p['helico']??1);
$bt=hidden('mode',$o);
$bt.=hidden('clr',$c);
$bt.=hidden('helico',$h);
if($a>self::$max)$a=self::$max;
return $bt.self::build($a,$o,$c,$h);}

static function search($p){
$srch=strtolower($p['srch']);
$r=db_read('db/public/atomic','','',1);
foreach($r as $k=>$v)if(strtolower($v[0])==$srch||strtolower($v[1])==$srch||$v[4]==$srch)$p['p1']=$k;
return self::call($p);}

static function nav($p,$o,$c,$h){$rid=self::$cb;//echo $h;
$ret=bj($rid.'|spitable,call|mode=radial|lbar,clr,helico',lang('radial'),'btn'.active($o,'radial'));
$ret.=bj($rid.'|spitable,call|mode=radial2|lbar,clr,helico',lang('big'),'btn'.active($o,'radial2'));
$ret.=bj($rid.'|spitable,call|mode=linear|lbar,clr,helico',lang('linear'),'btn'.active($o,'linear')).' | ';
$ret.=bj($rid.'|spitable,call|helico=1|lbar,mode,clr',lang('propeller'),'btn'.active($h,'1'));
$ret.=bj($rid.'|spitable,call|helico=2|lbar,mode,clr',lang('double-helix'),'btn'.active($h,'2')).' | ';
$rm=[1=>'clr1',2=>'clr2',5=>'fusion',6=>'ebulition',8=>'mass',9=>'atomic mass',10=>'isotopes',12=>'origin'];
foreach($rm as $k=>$v)$ret.=bj($rid.'|spitable,call|clr='.$k.'|lbar,mode,helico',$v,'btn'.active($c,$k));
//foreach($rm as $k=>$v)$ret.=btj($v,'coloriz2('.$k.')','btn');
return div($ret);}

static function menu($p,$o,$rid){
//$j=$rid.'|spitable,call|p2='.$o.'|inpspi';
//$ret=inputcall($j,'inpspi',$p);
//$ret.=bj($j,picto('ok',24),'btsav').' ';
$j=$rid.'|spitable,call||lbar,mode,clr,helico';
$ret=bar('lbar',$p,1,1,120,'1',ajx($j),'','');
//if($p>0)$ret.=bj($rid.'|spitable,call|p1='.($p-1).',p2='.$o,picto('previous'),'btn').'';
//if($p<self::$max)$ret.=bj($rid.'|spitable,call|p1='.($p+1).',p2='.$o,picto('next'),'btn').' ';
$ret.=hlpbt('spitable_app');
$ret.=db::bt('db/public/atomic');
$ret.=btj(langp('fullscreen'),atj('toggleFullscreen','sptcb'),'btn','btfs');
$ret.=lk('/spitable',pic('url'),'btn');
$ret.=lk('/spitable2',langpi('spitable2'),'btn');
//$ret.=btn('txtx','Davy@2022');
$j=$rid.'|spitable,search||srch,lbar,mode,clr,helico';
$ret.=inputcall($j,'srch','',8,lang('search')).hlpbt('spitable_search');
return div($ret);}

static function content($p){
$a=$p['p1']??self::$max; $o=$p['p2']??'';
$rid=self::$cb; //$o=1;
$bt=self::menu($a,$o,$rid);
head::add('jscode',self::js($a,$o));
$ret=self::call($p);
return div($bt.div($ret,'',$rid),'board','sptcb');}

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