<?php
class starmap1{
static $private=0;
static $a='starmap1';
static $cb='stm';
static $default='81693,99461,88601';
static $w=1200;

function __construct(){starlib::$w=self::$w;}
static function admin(){return admin::app(['a'=>self::$a,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){head::add('jscode',self::js());}

static function legend($r){$w=self::$w; $h=$w/2; $sz=16; $x=40; $y=0; $i=0;
[$white,$black,$red,$green,$blue,$yellow,$cyan,$orange,$silver,$gray]=starlib::$clr;
$r=['amical'=>$green,'neutre'=>$yellow,'inamical'=>$orange,'danger'=>$red,'indéfini'=>$white];
$i++; $y=$i*$sz;
svg::text(10,$x,$y+12,'Stars',$white);
svg::text(10,$w-100,$y+12,day('d/m/Y'),$white);
foreach($r as $k=>$v){$i++;
	$y=$i*$sz;
	svg::circle($x+8,$y+8,10,$v,$black);
	svg::text(12,$x+$sz+8,$y+12,$k,$white);}
$i++; $y=$sz+$i*$sz;
svg::text(10,$x,$y+12,'Zones',$white);
$r=['Hostiles'=>'#530002','Roswell New Dominion'=>'#4f5900','Ancien Aliens Dominion'=>'#a62a00','Friends or Neutral'=>'#025100'];
foreach($r as $k=>$v){$i++; $y=$i*$sz;
	svg::rect($x,$y,$sz,$sz,$v,$black);
	svg::text(12,$x+$sz+8,$y+12,$k,$white);}}

static function dots($r){
$w=self::$w; $h=$w/2; $mw=$w/24; $mh=$h/12; $sz=10; $xs=12; //pr($r);
[$white,$black,$red,$green,$blue,$yellow,$cyan,$orange,$silver,$gray]=starlib::$clr;
$rz=starlib::sz($r);
if($r)foreach($r as $k=>$v){
	$x=$v['x']; $y=$v['y']; $st=$v['star']??''; $pl=$v['planet']??''; $stt=$v['status']??'';
	$nm=$st?$st:($v['hd']?'HD'.$v['hd']:''); //$pl?$pl: //$nm='HD'.$v['hd'];
	if($stt=='amical')$clr=$green;
	elseif($stt=='inamical')$clr=$orange;
	elseif($stt=='danger')$clr=$red;
	elseif($stt=='neutre')$clr=$yellow;
	elseif($stt=='indéfini')$clr=$white;
	elseif($stt=='galaxy')$clr=$blue;
	else $clr=$silver;
	$sz=$rz[$k]*2;
	svg::circle($x,$y,$sz,$clr,'none',2);
	$xb=$x-20; $yb=$y+16;
	if($nm=='6 G. Piscium' or $nm=='38 Piscium' or $nm=='Iota Piscium' or $nm=='Gliese 250'){$xb=$x+8; $yb=$y+5;}
	//if($v['hd']=='217877'){$xb=$x+8; $yb=$y+5;}//OOYAAUYIEE WEE
	if($v['hd']=='114710'){$xb=$x-60;}//Berenice
	svg::bj($xb,$yb,$xs,$white,'popup|starlib,info|com='.$v['hip'],$nm);}}

static function correct($v,$cx){
$w=self::$w; $h=$w/2; $mw=$w/24; $mh=$h/180; $cy=90;
[$x,$y]=explode('/',$v);
if($x>=20 && $x<=24)$x-=24;//next time, use iu
$x+=$cx; $y+=$cy;
$x=0-$x+24; if($x<0)$x=0; if($x>24)$x=24;
$y=(0-$y)+180; if($y<0)$y=0; if($y>180)$y=180;
return round($x*$mw).'/'.round($y*$mh);}

static function zones(){
[$r0,$r1,$r2,$r3]=starlib::zpt(); $r0b=$r0; $r3b=$r3; $cx=12;
foreach($r0 as $k=>$v)$r0[$k]=self::correct($v,$cx); svg::poly($r0,'#025100');//p($r0);
foreach($r0b as $k=>$v)$r0b[$k]=self::correct($v,-12); svg::poly($r0b,'#025100');//p($r0b);
foreach($r1 as $k=>$v)$r1[$k]=self::correct($v,$cx); svg::poly($r1,'#a62a00');//p($r1);
foreach($r2 as $k=>$v)$r2[$k]=self::correct($v,$cx); svg::poly($r2,'#530002');//p($r2);
foreach($r3 as $k=>$v)$r3[$k]=self::correct($v,$cx); svg::poly($r3,'#4f5900');
foreach($r3b as $k=>$v)$r3b[$k]=self::correct($v,-12); svg::poly($r3b,'#4f5900');}

static function map($r){$w=self::$w; $h=$w/2; $mw=$w/24; $mh=$h/12;
[$white,$black,$red,$green,$blue,$yellow,$cyan,$orange,$silver,$gray]=starlib::$clr;
for($i=0;$i<=24;$i++){$x=$mw*(24-$i);
	svg::line($x,0,$x,$h,$i==12?$white:$gray); $t=12+$i; if($t>=24)$t-=24;
	svg::text(10,$x-12,10,$t,$yellow);}
for($i=0;$i<=12;$i++){$y=$mh*$i;
	svg::line(0,$y,$w,$y,$i==6?$white:$gray); $t=90-$i*15;
	svg::text(10,0,$y,$t,$yellow);}
starlib::months();
starlib::galaxy();
starlib::sun();}

static function draw($r){
$w=self::$w; $h=$w/2; //$im=new svg($w,$h);
[$white,$black]=starlib::$clr;
svg::rect(0,0,$w,$h,$black);
self::zones();
self::map($r);
self::dots($r);//stars
self::legend($r);
$w=self::$w; $h=$w/2; $t='Starmap';
return svg::com('',$w,$h,$t);}//draw

static function call($p){
$p1=$p['p1']; $ra=db::read('db/public/stars/1',1);
if(strpos($p1,';'))$p1=str_replace(';',',',$p1);
if($p1=='knownstars' or $p1=='allstars'){
	if($p1=='allstars'){$rb=db::read('db/public/stars/2',1); $ra=array_merge($ra,$rb);}
	$p1=implode(',',array_keys_r($ra,8));}
$w=self::$w; $h=$w/2;
svg::init($w,$h,'starmap1');
$sq=starlib::sq($p1);//pr($sq);
$r=starlib::build($sq,1);//pr($r);
$rb=starlib::prep($r,$ra,$p1);//pr($rb);
$ret=self::draw($rb);
return $ret;}

static function content($p){
$p['p1']=!empty($p['p1'])?$p['p1']:self::$default;
$j=self::$cb.'|starmap1,call||p1';
$bt=inputcall($j,'p1',$p['p1'],32,'','',['type'=>'number']);
$bt.=bj($j,langp('ok'),'btn').hlpbt('starmap2_app');
$bt.=db::bt('db/public/stars/1');
return $bt.div('','',self::$cb);}
}

?>