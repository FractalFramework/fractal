<?php

class spilog{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='spl';
static $mx=40;
static $algo='linear';//music,linear,power,fibo,exp,atomic

/*
//bases maths
static $hz440=234.63;
static $hz432=256.87;
static $hz256=256;
//sound=ocatve 8 (256->512 Hz) / oreille : 10 -> 15000 Hz
//light=octave 28 (788 927 521 052->405 124 943 243 Hz) / wavelength : 380->790 nm / iterations 38->39
//grow base $d
$d*(2**($i/12))
//f=fréquence(Hz);t=période(m/s);l=longueur d'onde(m);c=lightspeed
l=c*t ; f=c*l ; l=c/f ; f=c/l ;//6:256 (LA at 432Hz); //base for LA at 440Hz:234.63;
//29-30:light (380-760nm)
//$d=256.87; $d=234.63;
*/

static function admin(){
$r=admin::app(['a'=>self::$a]);
$r[]=['others','pop','spectral','','spectral'];
$r[]=['others','pop','musical','','musical'];
$r[]=['others','pop','spilog','','spilog'];
return $r;}
static function js(){return;}
static function headers(){head::add('jscode',self::js());}

//give rgb from wavelength (nm)
//http://pierreontheweb.free.fr/RGB-wavelength/wavelength2RGB.htm
static function clrhz2(){//380->781
$r=db_read('db/system/clrwav2'); //p($r);
foreach($r as $k=>$v)$rb[($v[0])]=$v[1];//nm2thz
return $rb;}

static function power($d){
if(strpos($d,'E+'))return strend($d,'E+');
else return strlen($d)-1;}

static function electrowaves(){
return [6=>'radio',7=>'radar',8=>'microwaves',11=>'infrared',14=>'light',15=>'ultraviolet',16=>"&chi;".'-rays',19=>'gamma '."&gamma;"];}

//grow rule (octaves)
//gamme tempérée
static function grow($d,$i,$s,$r){$mode=self::$algo;
if($mode=='music')return $d*(2**bcdiv($i,$s));
if($mode=='linear')return $d+($i*($d/$s));
if($mode=='power')return $d*bcdiv($d,$s/($s-$i));
if($mode=='fibo')return val($r,$i-2,1)+val($r,$i-1,1);
if($mode=='exp')return exp($i);
if($mode=='atomic')return $d*$i*4;}

static function build($d,$s){$r=[];//$s:range of the nb of iterations
//$s*=2;//or other suite
for($i=0;$i<=$s;$i++)$r[]=self::grow($d,$i,$s,$r);
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
static function graph_colors_spi($r,$d,$mi,$ray,$h){//fluid
$ret=''; $c=''; $rez=6;
$rd=self::trace($r,$d); $re=self::angles($rd); $rc=self::spire($rd,$ray,10); //pr($re);
$rk=spectral::clrhz1();//clrhz2//clrhz2
$min=min(array_keys($rk)); $max=max(array_keys($rk)); //pr($rd);
foreach($re as $k=>$v){$v=0-deg2rad($v+180); $kb=round($rd[$k]); $ray=$rc[$k]; //$ray=$v/3.6;
	$xa=$mi+sin($v)*$ray; $ya=$mi+cos($v)*$ray;
	if($ray+$h>($mi-$h/2))$ray2=$mi-$h/2; else $ray2=$ray+$h;
	$xb=$mi+sin($v)*$ray2; $yb=$mi+cos($v)*$ray2;
	$xb2=$mi+sin($v)*($ray2-1); $yb2=$mi+cos($v)*($ray2-1);
	if(isset($rk[$kb]))$c=$rk[$kb]; if($kb<$min or $kb>$max)$c=0;
	if($c)$ret.='[white,#'.$c.',3px:attr]['.$xa.','.$ya.','.$xb2.','.$yb2.':line]';}
return $ret;}

static function graph_waves_spi($mi,$ray,$h,$do,$d,$clr){
$ret=''; $zxa=0; $zya=$mi-$ray; $rez=$d*2;//resolution
$r=self::build($do,$d); $rb=self::angles($r); $rc=self::spire($r,$ray,$h); //pr($rc);
//$ret='[white,black:attr]['.$mi.','.$mi.','.$ray.':circle]';
$rw=self::electrowaves();
if($clr)$ret.=self::graph_colors_spi($r,$d,$mi,$ray,$h);
foreach($rb as $k=>$a){$v=0-deg2rad($a+180);
	$ray=$rc[$k]; $ray2=$ray-10; $ray3=$ray+10; 
	$xa=$mi+sin($v)*$ray; $ya=$mi+cos($v)*$ray;
	$xb=$mi+sin($v)*$ray2; $yb=$mi+cos($v)*$ray2;
	$xc=$mi+sin($v)*$ray3; $yc=$mi+cos($v)*$ray3;
	$ret.='[white,silver:attr]['.$xb.','.$yb.','.$xa.','.$ya.':line]';
	if($k/$rez==round($k/$rez)){$hz=maths::powers($r[$k],'Hz');//self::unit
		$pw=self::power($r[$k]); if(isset($rw[$pw]))$hz.=' '.$rw[$pw];
		$ret.='[black,,,10:attr]['.($xc).','.($yc).'*'.$hz.':text]';}
	if($zxa)$ret.='[white,black:attr]['.$zxa.','.$zya.','.$xa.','.$ya.':line]';
	$zxa=$xa; $zya=$ya; $zxb=$xb; $zyb=$yb;}
return $ret;}

static function graph($ro,$d){
$w=$h=800; $mi=$w/2; $ray=$mi-50; $s=12; $h1=$ray/$s;
$ret='[white,black:attr]['.$mi.','.$mi.','.$ray.':circle]';
$min=16; $max=68; $n=$max-$min; $u=$n/$s;
for($i=1;$i<$s;$i++){$a=ceil($min+($u*$i)); $ray=$h1*$i; $do=$ro[$a][1];
	$ret.=self::graph_waves_spi($mi,$ray,$h1,$do,$a,0);}
//$d=8; echo $ray=$h1*5; $do=$ro[$d][1]; $ret.=self::graph_waves_spi($mi,$ray,$h1,$do,$d,0);//
return svg::call(['code'=>$ret,'w'=>$w,'h'=>$h]);}

static function octaves($d){$r[]=['octaves','Hz','pow'];
for($i=$d;$i<=$d+68;$i++){$a=2**$i; $r[]=[$i,$a,maths::powers($a)];}
return $r;}

#call
static function call($p){
$v=$p['inp2']??1;
$m=$p['mode']??'mul';
//$algo=val($p,'algo',3);
bcscale(40);
//$ra=['do','do#','re','re#','mi','fa','fa#','sol','sol#','la','la#','si','do'];
$rb['_']=['','Hz'];
$ro=self::octaves(1);//octaves
$d=$ro[$v][1]??0;
$ret=self::graph($ro,$v);
$ret.=tabler($ro,1);
return $ret;}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call|';
$bt=bj($j.'mode=432|inp2','ok','btn'); return $bt;
$v=$p['p1']??8;
return inputcall($j.'mode=mul|inp2','inp2',$v,22,lang('iteration')).$bt;}

#content
static function content($p){
$p['p1']=$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}//
}
?>