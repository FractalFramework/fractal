<?php
class spitable3{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='spt';
static $max=120;
static $unit=40;
static $mode='radial';
static $src='img/svg/spihelix.svg';

static function admin(){
$r=admin::app(['a'=>self::$a,'db'=>self::$db]);
//$r[]=['','lk','/spitable','','spitable2'];
return $r;}

static function injectJs($j,$n=1,$o){//self::patch();
$atoms=db_read('db/public/atomic','','',1);
$ra=$atoms?json_encode(utf_r($atoms)):''; $j='spt|spitable3,call';
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

function coloriz2(n){var r=atoms_clr(n); //alert(r);
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

static function css(){$ret='rect:hover{box-shadow:0px 0px 4px #aaa;}';
//for($i=1;$i<=self::$max;$i++)$ret.='#id'.$i.':hover{background:rgba(255,255,255,0.4);}'."\n";
return $ret;}
static function headers(){
//add_head('jscode',self::injectJs('spt|spitable3,call',$p,$o));
add_head('csscode',self::css());}

static function infos($p){$d=val($p,'p');
$r=db_read_row('db/public/atomic',$d);
if(!isset($r['Nom']))return span('Element '.$d,'btn');
return tabler(array2r($r));}

static function clr(){return [''=>'ccc','Nonmetals'=>'5FA92E','Nobles Gasses'=>'00D0F9','Alkali Metals'=>'FF0008','Alkali Earth Metals'=>'FF00FF','Metalloids'=>'1672B1','Halogens'=>'F6E617','Metals'=>'999999','Transactinides'=>'FF9900','Lanthanides'=>'666698','Actinides'=>'9D6568','undefined'=>'ffffff'];}

static function findclr($d){$r=self::clr();
return $r[$d]?$r[$d]:'ffffff';}

static function legend($o){$i=0; $ret='';
$r=self::clr(); $w=100; $h=20; $y=1; $x=1; $max=self::$max;
list($mode,$clr,$size)=expl('-',$o,3); if(!$mode)$mode=self::$mode;
$sz=[1=>74,2=>104,3=>88,4=>124,5=>72,6=>68,7=>50,8=>94,9=>88,10=>68,11=>70];
foreach($r as $k=>$v)if($k){$i++;
	$w=$sz[$i];//$w=strlen($k)*8;
	$ret.='[#'.$v.',gray:attr]';
	$ret.='['.$x.','.$y.','.$w.','.$h.':rect]';
	$ret.='[black,:attr]['.($x+4).','.($y+16).',font-size:12px§'.$k.':text]';
	$x+=$w;}
$ret.='['.($x+4).',16,9*@Davy 2003-2021:text]';
$s='12px'; $j='[spt|spitable3;call|p1='.$max; $s='font-size:12px';
if($mode=='linear')$ret.=$j.';p2=radial§[0,40,'.$s.'§linear:text]:bj]';
else $ret.=$j.';p2=linear§[0,40,'.$s.'§radial:text]:bj]';
$ret.=$j.';p2='.$mode.'-1§[40,40,'.$s.'§clr1:text]:bj]';
$ret.=$j.';p2='.$mode.'-2§[65,40,'.$s.'§clr2:text]:bj]';
$ret.=$j.';p2='.$mode.'-3§[100,40,'.$s.'§atomic mass:text]:bj]';
$ret.=$j.';p2='.$mode.'-4§[180,40,'.$s.'§mass:text]:bj]';
$ret.=$j.';p2='.$mode.'-5§[220,40,'.$s.'§fusion:text]:bj]';
$ret.=$j.';p2='.$mode.'-6§[260,40,'.$s.'§ebulition:text]:bj]';
if(auth(4)){
$ret.='[coloriz(1)§[40,60,'.$s.'§clr1:text]:js]';
$ret.='[coloriz(2)§[65,60,'.$s.'§clr2:text]:js]';
$ret.='[coloriz2(9)§[100,60,'.$s.'§atomic mass:text]:js]';
$ret.='[coloriz2(8)§[180,60,'.$s.'§mass:text]:js]';
$ret.='[coloriz2(5)§[220,60,'.$s.'§fusion:text]:js]';
$ret.='[coloriz2(6)§[260,60,'.$s.'§ebulition:text]:js]';
$ret.='[play(0)§[320,40,'.$s.'§anim:text]:js]';}
return $ret;}

//angular
static function atompos($ring,$sub,$pos,$i,$v,$o){
$u=self::$unit;//unit
list($mode,$clr,$size)=expl('-',$o,3);
$zerox=1; $zeroy=4;
//position on rings
$rx[1]=[1=>-1,0];
$ry[1]=[1=>-1,-1];
$rx[2]=[1=>-1,-1,1,-2,1,-2];
$ry[2]=[1=>1,-2,0,-2,-2,0];
$rx[3]=[1=>0,2=>-2,3=>-2,4=>0,5=>-3,6=>2,7=>-3,8=>2,9=>-3,10=>2];
$ry[3]=[1=>-3,2=>2,3=>-3,4=>2,5=>-3,6=>1,7=>-1,8=>-1,9=>1,10=>-3];
$rx[4]=[1=>-3,2=>1,3=>-1,4=>-1,5=>1,6=>-3,7=>3,8=>-4,9=>3,10=>-4,11=>3,12=>-4,13=>3,14=>-4];
$ry[4]=[1=>3,2=>-4,3=>3,4=>-4,5=>3,6=>-4,7=>2,8=>-4,9=>0,10=>-2,11=>-2,12=>0,13=>-4,14=>2];
//ring_distance//if radial2
$rd=[1=>1,2=>5,3=>11,4=>19,5=>28,6=>36,7=>42,8=>46];
//ring distant//vertical
$rdx=[1=>2,2=>6,3=>12,4=>20,5=>4,6=>12,7=>18,8=>22,9=>26];
$rdy=[1=>1,2=>1,3=>1,4=>1,5=>8,6=>8,7=>8,8=>8,9=>8];
//atom angle
$rg[1]=str_split('011');
$rg[2]=str_split('0001111');
$rg[3]=str_split('00000111111');
$rg[4]=str_split('000000011111111');
//mode
if($mode=='linear'){$addx=($rd[$ring]*$u); $addy=20;}
else{$addx=($rdx[$ring]*$u); $addy=($rdy[$ring]*$u);}
//x,y,w,h
$x=($rx[$sub][$pos]+$zerox)*$u+$addx;
$y=($ry[$sub][$pos]+$zeroy)*$u+$addy;
//t, vertical-horizontal
$mg=round($u/4)-3; $mg2=round($u/2)+4; $sz=10+($u/10); $s='font-size:'.$sz.'px';
if($rg[$sub][$pos]){$w=$u; $h=$u*2;//horizontal
$t1='[spt|spitable3;call|p1='.$i.',|mode§['.($x+$mg).','.($y+$mg2).','.$s.'§'.$i.':text]:bj]';
$t2='[popup|spitable3;infos|p1='.$i.'§['.($x+$mg).','.($y+$h-$mg2).','.$s.'§'.$v.':text]:bj]';}
else{$w=$u*2; $h=$u;
$t1='[spt|spitable3;call|p1='.$i.'|mode§['.($x+$mg).','.($y+$mg2).','.$s.'§'.$i.':text]:bj]';
$t2='[popup|spitable3;infos|p1='.$i.'§['.($x+$w/2+6).','.($y+$mg2).','.$s.'§'.$v.':text]:bj]';}
$ret='[black,,,14px:attr]'.$t1.$t2;
//echo $x.'-'.$y.'-'.$w.'-'.$h.br();
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
list($name,$sym,$fam,$layer,$level)=arr($r,5);
$ra=explode('-','-'.$layer);
$max=val($ra,$ring); $rx[$ring][]=1;//count elemenrs in rings
return count($rx[$ring])<=$max?0:1;}

static function atom($r,$i,$p,$o,$rc){
list($name,$sym,$fam,$layer,$level)=arr(val($r,$i),5); //echo $name.'-'.$sym.'-'.$fam.'-'.$level.br();
list($ring,$subring,$pos)=self::findpos($level,$i); //echo $ring.'-'.$subring.'-'.$pos.br();
list($mode,$clr,$size)=expl('-',$o,3);
list($x,$y,$w,$h,$t)=self::atompos($ring,$subring,$pos,$i,$sym,$o); //echo $x.'-'.$y.'-'.$w.'-'.$h;
$clr=self::findclr($fam); $hide=$i<=$p?0:1; if($rc)$clr=$rc[$i];
$hide=self::active(val($r,$p),$ring);//anomalies
$bdr=$p==$i?'red':($hide?'gray':'black'); $sz=$p==$i?'2':'1'; $alpha=$hide?'0.2':'1';
$atr='[#'.$clr.','.$bdr.','.$sz.',,'.$alpha.':attr]';
$rect='['.$x.','.$y.','.$w.','.$h.',,id'.$i.':rect]';
return $atr.$rect.$t;}

static function clr3($r,$col){
$ra=array_keys_r($r,$col); $min=0; $max=0;
foreach($ra as $k=>$v){$v=str_replace(',','.',$v);
	if($v=='N/A' or $v=='---')$v='-';
	elseif(strpos($v,'@')!==false)$v=substr($v,0,strpos($v,'@'));
	elseif(strpos($v,'±')!==false)$v=substr($v,0,strpos($v,'±'));
	elseif(strpos($v,' ')!==false)$v=substr($v,0,strpos($v,' '));
	if(intval($v)<$min)$min=$v; if(intval($v)>$max)$max=$v; $ra[$k]=$v;}//pr($ra);
//$min=min($ra); $max=max($ra); 
$diff=$max-($min); $ratio=255/$diff; $ratio1=255/$max; if($min)$ratio2=255/(0-$min);
foreach($ra as $k=>$v){
	if(!is_numeric($v)){$red=127; $green=127; $blue=127;}
	elseif($min<0){
		if($v>=0){$d=round($v*$ratio1); $red=255; $green=$d; $blue=$green;}
		else{$d=0-round($v*$ratio2); $red=255-$d; $green=$red; $blue=255;}}
	elseif($min>=0){$d=round(($v-$min)*$ratio); $red=255; $green=255-$d; $blue=$green;}
	$rb[$k]=rgb2hex([$red,$green,$blue]);}
return $rb;}

static function clr2(){
//$r=db_read('db/public/atoms','','',1);
$d='E42824 E53D2B E95F36 EF8744 F8B355 F7D35D C0D14A 92BF3A 72B225 5EAD25 57AB27 4FAA35 3BAB6C 12AE9F 0CA3C9 4A88C2 416BAD 4558A0 4D4B97 5D3E8E 6F398B 83378A 9C3789 B83589 D32E87';
$r=explode(' ',$d); $rb[]='';
for($i=2;$i<3;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//1-2
for($i=2;$i<6;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//3-10
for($i=2;$i<6;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//11-18
for($i=2;$i<3;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//19-20
for($i=6;$i<11;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//21-30
for($i=3;$i<6;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//31-36
for($i=2;$i<3;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//37-38
for($i=6;$i<11;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//39-48
for($i=3;$i<6;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//49-54
for($i=2;$i<3;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//55-56
for($i=14;$i<21;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//57-70
for($i=6;$i<11;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//71-80
for($i=3;$i<6;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//81-86
for($i=2;$i<3;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//87-88
for($i=14;$i<21;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//89-102
for($i=6;$i<11;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//103-112
for($i=3;$i<6;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//113-118
for($i=2;$i<3;$i++){$rb[]=$r[$i]; $rb[]=$r[$i];}//119-120
return $rb;}

/*static function patch(){
$r=db_read('db/public/atomic','','',1); $rb=self::clr();
$rh=['Nom','Symbole','Famille','Couche','Niveau orbital','Fusion','Ebulition (C°)','Découverte','Masse','Masse atomique (u)','Isotopes','Numéro atomique','clr','pos','free','deg','clr2'];
foreach($r as $k=>$v)$r[$k][12]=$rb[$v[2]];
db_save('db/public/atomic',$r);}*/

//build
static function build($p,$o){//$o=0;
$r=db_read('db/public/atomic','','',1);
list($mode,$clr,$size)=expl('-',$o,3);
$u=self::$unit;//unit
if($mode=='linear'){$u=self::$unit=30; $w=49*$u; $h=10*$u;}
else{$mode='radial'; $u=self::$unit=40; $w=25*$u; $h=18*$u;}//w/h
$max=self::$max; //p($r[$p]);
if($clr==2)$rc=self::clr2(); //p($rc);
elseif($clr==3)$rc=self::clr3($r,9);
elseif($clr==4)$rc=self::clr3($r,8);
elseif($clr==5)$rc=self::clr3($r,5);
elseif($clr==6)$rc=self::clr3($r,6);
else $rc=[];
for($i=1;$i<=$max;$i++)$rb[]=self::atom($r,$i,$p,$o,$rc);
$ret='[white,black,1:attr][0,0,'.$w.','.$h.':rect]';
$ret=implode("\n",$rb);
$ret.=self::legend($o);
if($ret)$ret=svg::call(['code'=>$ret,'w'=>$w,'h'=>$h,'fit'=>1]);
return $ret;}

static function call($p){//pr($p);
$a=val($p,'p1',val($p,'lblbar',val($p,'inpspi',self::$max)));
$o=val($p,'p2',val($p,'mode',self::$mode)); $bt=hidden('mode',$o);
if($a>self::$max)$a=self::$max;
return $bt.self::build($a,$o);}

static function menu($p,$o,$rid){
//$j=$rid.'|spitable3,call|p2='.$o.'|inpspi';
//$ret=inputcall($j,'inpspi',$p);
//$ret.=bj($j,picto('ok',24),'btsav').' ';
$j=$rid.'|spitable3,call||lblbar,mode';
$ret=bar('bar',$p,1,1,120,'1',ajx($j),'','');
//if($p>0)$ret.=bj($rid.'|spitable3,call|p1='.($p-1).',p2='.$o,picto('previous'),'btn').'';
//if($p<self::$max)$ret.=bj($rid.'|spitable3,call|p1='.($p+1).',p2='.$o,picto('next'),'btn').' ';
$ret.=hlpbt('spitable3');
$ret.=db::bt('db/public/atomic');
$ret.=btj(langp('fullscreen'),atj('toggleFullscreen','sptcb'),'btn','btfs');
$ret.=lk('/spitable3',pic('url'),'btn');
return div($ret);}

static function content($p){
$a=val($p,'p1',self::$max); $o=val($p,'p2');
$rid=self::$cb; //$o=1;
$bt=self::menu($a,$o,$rid);
add_head('jscode',self::injectJs('spt|spitable3,call',$a,$o));
$ret=self::call($p);
return div($bt.div($ret,'',$rid),'board','sptcb');}

static function iframe($p){
add_head('csslink','/css/global.css');
add_head('csslink','/css/apps.css');
add_head('csslink','/css/fa.css');
add_head('jslink','/js/ajax.js');
add_head('jslink','/js/utils.js');
$ret=self::content($p);
$ret=tag('body',['onmousemove'=>'popslide(event)','onmouseup'=>'closebub(event)'],$ret);
$ret.=tag('div',['id'=>'closebub','onclick'=>'bubClose()'],'');
$ret.=tag('div',['id'=>'popup'],'');
return generate().$ret;}

}

?>