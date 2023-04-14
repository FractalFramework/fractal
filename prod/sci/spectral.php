<?php

class spectral{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='spc';
static $mx=40;
static $algo='music';//music,linear,power,fibo,exp,atomic

/*
//bases maths
static $hz440=261.63;
static $hz432=256.87;
static $hz256=256;
//sound=ocatve 8 (256->512 Hz) / oreille : 10 -> 15000 Hz
//light=octave 28 (788 927 521 052->405 124 943 243 Hz) / wavelength : 380->790 nm / iterations 38->39
//grow base $d
$d*(2**($i/12))
//f=fr�quence(Hz);t=p�riode(m/s);l=longueur d'onde(m);c=lightspeed
l=c*t ; f=c*l ; l=c/f ; f=c/l ;
*/

static function admin(){
$r=admin::app(['a'=>self::$a]);
$r[]=['others','pop','spectral','','spectral'];
$r[]=['others','pop','musical','','musical'];
$r[]=['others','pop','spilog','','spilog'];
return $r;}
static function js(){return;}
static function headers(){head::add('jscode',self::js());}

static function notes(){
return ['do','do#','re','re#','mi','fa','fa#','sol','sol#','la','la#','si','do'];
return ['C','C#','D','D#','E','F','F#','G','G#','A','A#','B','C'];}

static function conv(){
$r=db_read('db/system/clrwav','','',1);//algo2
foreach($r as $k=>$v){$kb=$k; $kb=maths::nm2thz($k); $kb=round($kb);
if($v[0]==256)$v[0]=255; if($v[1]==256)$v[1]=255; if($v[2]==256)$v[2]=255;
$v[0]=round($v[0]); $v[1]=round($v[1]); $v[2]=round($v[2]); $rb[]=[$kb,rgb2hex($v)];}
db_write('db/system/clrwav2',$rb);}

static function wavelength2rgb($v){$v=maths::nm2thz($v);
$min=384; $max=789; $g=0.8; $i=255;//gamma,intensity
if($v>=380 && $v<440){$red=-($v-440)/(440-380); $green=0.0; $blue=1.0;}
elseif($v>=440 && $v<490){$red=0.0; $green=($v-440)/(490-440); $blue=1.0;}
elseif($v>=490 && $v<510){$red=0.0; $green=1.0; $blue=-($v-510)/(510-490);}
elseif($v>=510 && $v<580){$red=($v-510)/(580-510); $green=1.0; $blue=0.0;}
elseif($v>=580 && $v<645){$red=1.0; $green=-($v-645)/(645-580); $blue=0.0;}
elseif($v>=645 && $v<781){$red=1.0; $green=0.0; $blue=0.0;}
else{$red=0.0; $green=0.0; $blue=0.0;}
//Let the intensity fall off near the vision limits
if($v>=380 && $v<420){$factor=0.3+0.7*($v-380)/(420-380);}
elseif($v>= 420 && $v<701){$factor=1.0;}
elseif($v>= 701 && $v<781){$factor=0.3 + 0.7*(780-$v)/(780-700);}
else{$factor=0.0;}
$rgb=[];
//Don't want 0^x=1 for x <> 0
$rgb[0]=$red==0.0?0:(int) round($i*pow($red*$factor,$g));
$rgb[1]=$green==0.0?0:(int) round($i*pow($green*$factor,$g));
$rgb[2]=$blue==0.0?0:(int) round($i*pow($blue*$factor,$g));
return rgb2hex($rgb);}

static function clrhz3(){$ret=[];
for($i=384;$i<790;$i++)$ret[$i]=self::wavelength2rgb($i);
return $ret;}

//give rgb from wavelength (nm)
//http://pierreontheweb.free.fr/RGB-wavelength/wavelength2RGB.htm
static function clrhz2(){//380->781
$r=db_read('db/system/clrwav2'); //p($r);
foreach($r as $k=>$v)$rb[($v[0])]=$v[1];//nm2thz
return $rb;}

//https://fr.wikipedia.org/wiki/Mod%C3%A8le:Lo_vers_rvb/algo4
static function clrhz1(){//450->700THz
$r=[450=>'5700A1',453=>'5400A8',456=>'5200B0',459=>'5000B8',463=>'3C1BBA',466=>'233DBB',469=>'0A60BB',472=>'0074B9',475=>'007EB5',478=>'0088B1',481=>'0090AE',484=>'0097AB',488=>'009EA9',491=>'00A5A8',494=>'00ACA8',497=>'00B3A8',500=>'00BBA9',503=>'00C2AA',506=>'00C9AC',509=>'00D0AE',513=>'00D7B0',516=>'00DDB1',519=>'00E3B3',522=>'00E8B2',525=>'00ECB1',528=>'00F0AF',531=>'00F4AB',534=>'00F6A4',538=>'00F99E',541=>'07FB8F',544=>'2BFB5F',547=>'4FFC2F',550=>'74FD00',553=>'85FB00',556=>'97F900',559=>'A9F800',563=>'B4F500',566=>'BEF100',569=>'C8EE00',572=>'D0E900',575=>'D7E400',578=>'DEDF00',581=>'E4D800',584=>'E9D100',588=>'EEC900',591=>'F2C000',594=>'F5B500',597=>'F8AA00',600=>'FB9F00',603=>'FB8800',606=>'FC7100',609=>'FC5A00',613=>'F8401E',616=>'F32543',619=>'EE0A69',622=>'E80078',625=>'E1007A',628=>'DA007B',631=>'D4007B',634=>'CD0079',638=>'C60077',641=>'BF0075',644=>'B80071',647=>'B1006E',650=>'AA006B',653=>'A30066',656=>'9C0062',659=>'95005E',663=>'8E005A',666=>'870056',669=>'800051',672=>'7A004D',675=>'74004A',678=>'6E0047',681=>'680043',684=>'630040',688=>'5E003C',691=>'590039',694=>'540036',697=>'4F0033',700=>'4B0030'];
foreach($r as $k=>$v)$rb[maths::nm2thz($k)]=$v; return $rb;}

static function clrwav($d){$r=spectral::clrhz1();//p($r);
return div($d,'','','background:#'.$r[$d].';');}

//grow rule (octaves)
//gamme temp�r�e
static function grow($d,$i,$s,$r){
$mode=self::$algo; $mode='music';//music
if($mode=='music')return $d*(2**bcdiv($i,$s));
if($mode=='linear')return $d+bcdiv($d,$s/($s-$i));
if($mode=='power')return $d*bcdiv($d,$s/($s-$i));
if($mode=='fibo')return val($r,$i-2,1)+val($r,$i-1,1);
if($mode=='exp')return exp($i);
if($mode=='atomic')return $d*$i*4;}

static function build($d){$s=12; $r=[];//segments
for($i=0;$i<=$s;$i++)$r[]=self::grow($d,$i,$s,$r);
return $r;}

static function build2($d){$s=12; $r=[1,2];//fibo
for($i=2;$i<=$s;$i++)$r[$i]=$r[$i-2]+$r[$i-1];
return $r;}

static function angles($r){$rb=[];
$min=min($r); $max=max($r); $diff=$max-$min;//octave
if($diff)foreach($r as $k=>$v)if($v)$rb[]=round(360*($v-$min)/$diff,2);//radian
return $rb;}

static function spire($r,$ray,$h){$ray-=$h;
$min=min($r); $max=max($r); $diff=$max-$min; $rc=[];
if($diff)foreach($r as $k=>$v){$vb=$v-$min; $rc[]=$ray+(($vb/$diff)*$h);}
return $rc;}

static function trace($r,$d){$s=360; $rb=[];
foreach($r as $k=>$v){$a=maths::measures($v,-9,1); $rb[$k]=$a;} //p($rb);
if(!$rb)return; $min=min($rb); $max=max($rb); $diff=$max-$min; $rc=[];
//for($i=$min;$i<=$max;$i++)$rc[$i]=round(360*($i-$min)/$diff,2);
for($i=0;$i<=$s;$i++)$rc[]=self::grow($min,$i,$s,$r);
return $rc;}

#build
static function graph_colors_spi($r,$d,$mi,$ray){//fluid
$ret=''; $zxa=0; $zya=0; $c=''; $rez=6;
$rd=self::trace($r,$d); $re=self::angles($rd); $rc=self::spire($rd,$ray,100); //pr($re);
$al=self::$algo; if($al==1)$rk=self::clrhz1(); if($al==2)$rk=self::clrhz2(); if($al==3)$rk=self::clrhz3();
$min=min(array_keys($rk)); $max=max(array_keys($rk)); //pr($rd);
foreach($re as $k=>$v){$v=0-deg2rad($v+180); $kb=round($rd[$k]); $ray=$rc[$k]; //$ray=$v/3.6;
	$xa=$mi+sin($v)*$ray; $ya=$mi+cos($v)*$ray;
	if($ray+100>($mi-50))$ray2=$mi-50; else $ray2=$ray+100;
	$xb=$mi+sin($v)*$ray2; $yb=$mi+cos($v)*$ray2;
	$xb2=$mi+sin($v)*($ray2-1); $yb2=$mi+cos($v)*($ray2-1);
	if(isset($rk[$kb]))$c=$rk[$kb]; if($kb<$min or $kb>$max)$c=0;
	$ret.='[white,#'.$c.',3px:attr]['.$xa.','.$ya.','.$xb2.','.$yb2.':line]';
	if($k/$rez==round($k/$rez)){
		if($zxa)$ret.='[white,black:attr]['.$zxa.','.$zya.','.$xa.','.$ya.':line]';
		$zxa=$xa; $zya=$ya; $zxb=$xb; $zyb=$yb;}}
return $ret;}

static function graph_waves_spi($mi,$h,$do,$d,$clr){
$ray=$mi-$h; $ray2=$ray-54; $h=$mi/4; $ret='';
$r=self::build($do); $rb=self::angles($r); $rc=self::spire($r,$ray,$h);
//$ret='[white,black:attr]['.$mi.','.$mi.','.$ray.':circle]';
if($clr)$ret.=self::graph_colors_spi($r,$d,$mi,$ray);//$ray
foreach($rb as $k=>$a){$v=0-deg2rad($a+180); $hz=maths::magnitude($r[$k],'Hz');
	$x=$mi+sin($v)*$ray; $y=$mi+cos($v)*$ray;
	$xb=$mi+sin($v)*$rc[$k]; $yb=$mi+cos($v)*$rc[$k];
	$xc=$mi+sin($v)*($rc[$k]+20); $yc=$mi+cos($v)*($rc[$k]+20);
	$ret.='[white,silver:attr]['.$mi.','.$mi.','.$x.','.$y.':line]';
	$ret.='[black,,,10:attr]['.($xb-16).','.($yb-14).'*'.$hz.':text]';}
return $ret;}

static function graph_colors($r,$d,$mi,$ray){
$rd=self::trace($r,$d); $re=self::angles($rd); //p($re);
$rk=self::clrhz1(); $ret=''; $c='';//colors
foreach($re as $k=>$v){$v=0-deg2rad($v+180); $k=$rd[$k];
	$x=$mi+sin($v)*$ray; $y=$mi+cos($v)*$ray;
	if(isset($rk[$k]))$c=$rk[$k]; if($k<380 or $k>750)$c=0;
	$ret.='[white,#'.$c.',3px:attr]['.$mi.','.$mi.','.($x).','.($y).':line]';}
return $ret;}

static function graph_waves($mi,$h,$do,$d,$clr){
$ray=$mi-$h; $ray2=$ray-54; $ret='';
$r=self::build($do);
$rb=self::angles($r);
//$ret='[white,black:attr]['.$mi.','.$mi.','.$ray.':circle]';
if($clr)$ret.=self::graph_colors($r,$d,$mi,$ray);
foreach($rb as $k=>$a){$v=0-deg2rad($a+180); $hz=maths::magnitude($r[$k],'Hz');
	$x=$mi+sin($v)*$ray; $y=$mi+cos($v)*$ray;
	$xb=$mi+sin($v)*$ray2; $yb=$mi+cos($v)*$ray2;
	if($k==12){$xb=$mi+sin($v)*($ray2+148); $yb=$mi+cos($v)*($ray2+14);}
	$ret.='[white,silver:attr]['.$mi.','.$mi.','.($x).','.($y).':line]';
	$ret.='[black,,,10:attr]['.($xb-16).','.($yb+4).',font-size:10px;*'.$hz.':text]';}
return $ret;}

static function graph_notes($mi,$h,$do,$d){
$ray=$mi-$h; $ray2=$ray-14; $ret='';
$r=self::build($do); $ra=self::notes(); $rb=self::angles($r); //p($rb);
$ret='[white,black:attr]['.$mi.','.$mi.','.$ray.':circle]';
foreach($rb as $k=>$a){$v=0-deg2rad($a+180); $hz=maths::magnitude($r[$k],'Hz');
	$x=$mi+sin($v)*$ray; $y=$mi+cos($v)*$ray;
	$xb=$mi+sin($v)*$ray2; $yb=$mi+cos($v)*$ray2;
	$ret.='[white,silver:attr]['.$mi.','.$mi.','.($x).','.($y).':line]';
	$ret.='[black,,,10px:attr]['.($xb-6).','.($yb+4).',font-size:10px;*'.$ra[$k].':text]';}
return $ret;}//transform:rotate('.$a.'deg);

static function graph($ro,$d){
$w=$h=800; $mi=$w/2; $ray=$mi-50; //echo $ro[40][1];
$ret='[white,black:attr]['.$mi.','.$mi.','.$ray.':circle]';
//light2
$h1=50; $d=37; $do=$ro[$d][1];
$ret.=self::graph_waves_spi($mi,$h1,$do,$d,1);
//light1
$h1=150; $d=36; $do=$ro[$d][1];
$ret.=self::graph_waves_spi($mi,$h1,$do,$d,1);
//sound
$h1=250; $d=6; $do=$ro[$d][1];
$ret.=self::graph_waves($mi,$h1,$do,$d,0);
//notes
$h1=320; $d=6; $do=$ro[$d][1];
$ret.=self::graph_notes($mi,$h1,$do,$d);
//echo $ret;
return svg::call(['code'=>$ret,'w'=>$w,'h'=>$h]);}

static function octaves($d){
for($i=$d;$i<=$d+64;$i++)$r[]=[$i,2**$i,strlen(2**$i)];
return $r;}

#call
static function call($p){
$v=$p['inp2']??36;
$m=$p['mode']??'mul';
$algo=val($p,'algo',3); self::$algo=$algo;
bcscale(40);
$ra=self::notes();
$rb['_']=['','Hz'];
$ro=self::octaves(2);//octaves
$d=$ro[$v][1]??0;
$ret=self::graph($ro,$v);
$r=self::build($d);
foreach($r as $k=>$v)$rb[]=[$ra[$k],$v];
$ret.=tabler($rb,1,1);
array_unshift($ro,['octaves','Hz']);
$ret.=tabler($ro);
return $ret;}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call|';
$bt=bj($j.'mode=432|inp2,algo','ok','btn');
$bt.=radio('algo',['1'=>'algo1','2'=>'algo2','3'=>'algo3'],'3');
$v=val($p,'p1',8);
return inputcall($j.'mode=mul|inp2','inp2',$v,22,lang('iteration')).$bt;}

#content
static function content($p){
$p['p1']=$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}//
}
?>