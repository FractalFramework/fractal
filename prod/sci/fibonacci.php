<?php

class fibonacci{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='fbn';
static $mx=144;

static function admin(){return admin::app(['a'=>self::$a]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function clr($va,$v){
$ra=['','000000','0000ff','ff0000','00ff00','00ff00','ff0000','0000ff','000000','ffffff'];
$s='padding:4px; background-color:#';
$c=$ra[$v];
if($v<5)$c=$v.$v.$v.$v.'ff;'; else $c='ff'.$v.$v.$v.$v.';';
$c2=clrneg($c,1);
$t=span($va.'->'.$v,'','','color:#'.$c2);//
$w=' width:'.($v*10).'px;';
$ret=div($t,'','',$s.$c.$w);
return $ret;}

static function numerology($v){
$n=strlen($v); $ret=0;
for($i=0;$i<$n;$i++){$s=substr($v,$i,1); if(is_numeric($s))$ret+=$s;}
//$ret=base_convert($ret,$p,10);
if(strlen($ret)>1)$ret=self::numerology($ret);
return $ret;}//$v.'->'.

/*static function graph($r){
$min=min($r); $max=max($r); $diff=$max-$min; //octave
foreach($r as $k=>$v){$vb=$v-$min; $rb[]=360*$vb/$diff; $rc[]=100+(($vb/$diff)*100);}//radian
$w=$h=600; $mi=$w/2; $pi=pi();
$ra=['do','do#','re','re#','mi','fa','fa#','sol','sol#','la','la#','si','do'];
$ret='[white,black:attr][300,300,200:circle]';
foreach($rb as $k=>$v){
	$ray1=200; $ray2=220; $v=0-deg2rad($v+180);
	$x=$mi+sin($v)*$ray1; $y=$mi+cos($v)*$ray1;
	$xb=$mi+sin($v)*$rc[$k]; $yb=$mi+cos($v)*$rc[$k];
	$xc=$mi+sin($v)*($rc[$k]+20); $yc=$mi+cos($v)*($rc[$k]+20);
	$ret.='[white,silver:attr][300,300,'.($x).','.($y).':line]';
	$ret.='[white,red:attr][300,300,'.($xb).','.($yb).':line]';
	$ret.='[black:attr]['.($xc).','.($yc).'*'.$ra[$k].':'.(round($r[$k],2)).'Hz:text]';}
//echo $ret;
return svg::call(['code'=>$ret,'w'=>$w,'k'=>$h]);}*/

static function text(){
bcscale(80);
$r=[1,1.6];
//(80)1:190;2:190;3:184;4:184;5:182;6:180;7:178;8:174;9:172;10:170;11:168;12:166;13:164;14:161;15:161
//for($i=2;$i<100;$i++){$r[]=$r[$i-2]+$r[$i-1]; $rb=$r[$i]/$r[$i-1];}
for($i=2;$i<190;$i++){$r[]=bcadd($r[$i-2],$r[$i-1]); $rb=bcdiv($r[$i],$r[$i-1]);}
pr($rb);
echo $d='1.61803398874989484820458683436563811772030917980576286213544862270526046281890244';
}

static function build($r){
for($i=2;$i<=self::$mx;$i++)$r[$i]=$r[$i-2]+$r[$i-1];
return $r;}

#call
static function fibo($i){//!
$ra[0]=$i;
//$ra[1]=$v;
$ra=self::build($ra);
foreach($ra as $k=>$v){
	$a=self::numerology($v);
	$c=self::clr($v,$a);
	$ret[$i]=$c;}
return $ret;}

static function call($p){
$v=val($p,'inp2',1);
$m=$p['mode']??'mul';
bcscale(40);
$ra[0]=1;
//$ra[1]=$v;
//$ra=self::build($ra);
for($i=1;$i<10;$i++){
	$ra[1]=$i;
	$ra=self::build($ra);
	foreach($ra as $k=>$v){
		$a=self::numerology($v);
		$c=self::clr($v,$a);
		//$ret[$k][]=$v;
		$ret[$k][]=$c;}}
return tabler($ret,1,1);}

static function com($p){
$j=self::$cb.'|'.self::$a.',call|';
$bt=bj($j.'mode=1|inp2',langp('fibonacci'),'btn');
$v=val($p,'p1',1);
return inputcall($j.'mode=mul|inp2','inp2',$v,22,lang('precision')).$bt;}

#content
static function content($p){
echo $p['p1']=$p['p1']??'';
$bt=self::com($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}//
}
?>
