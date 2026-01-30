<?php

class vortex{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='vtx';
static $mx=40;

static function admin(){return admin::app(['a'=>self::$a]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function clr($r,$m){
$ra=['','000000','0000ff','ff0000','00ff00','00ff00','ff0000','0000ff','000000','ffffff'];
$s='padding:4px; background-color:#';
foreach($r as $k=>$v)if($k){//$c=$v.$v.$v;
	if($v<5)$c=$v.$v.$v.$v.'ff;'; else $c='ff'.$v.$v.$v.$v.';';
	if($m=='pow')$c=$ra[$v];
	$c2=clrneg($c,1);
	$t=span($v,'','','color:#'.$c2);
	$r[$k]=div($t,'','',$s.$c);}
return $r;}

static function numerology($v){
$n=strlen($v); $ret=0;
for($i=0;$i<$n;$i++){$s=substr($v,$i,1); if(is_numeric($s))$ret+=$s;}
//$ret=base_convert($ret,$p,10);
if(strlen($ret)>1)$ret=self::numerology($ret);
return $ret;}//$v.'->'.

static function build($v,$m){$r[]=$v; $n=$v;
for($i=0;$i<=self::$mx;$i++){$res='';
	//$v=base_convert($n,10,$p);
	if($m=='mul')$n=bcmul($v,$i);
	if($m=='pow')$n=pow($v,$i);
	if($m=='dob')$n=bcmul($v,$i*2);
	//if($m=='dob')$n*=2;
	$res=self::numerology($n);
	$r[]=$res;}
return $r;}

#call
static function call($p){
$v=val($p,'inp2',1);
$m=$p['mode']??'mul';
$r['_'][]=''; $n=$v;
bcscale(40);
for($i=0;$i<=self::$mx;$i++){
	if($m=='mul')$n=bcmul($v,$i);
	elseif($m=='pow')$n=pow($v,$i);
	elseif($m=='dob')$n=bcmul($v,$i*2);
	//elseif($m=='dob')$n*=2;
	//$n=$i;
	$r['_'][]=$i;
	$ra=self::build(round($n),$m);
	$r[$i]=self::clr($ra,$m);}
return tabler($r,1,1);}

static function com($p){
$j=self::$cb.'|'.self::$a.',call|';
$bt=bj($j.'mode=mul|inp2',langp('multiples'),'btn');
$bt.=bj($j.'mode=pow|inp2',langp('powers'),'btn');
$bt.=bj($j.'mode=dob|inp2',langp('doubles'),'btn');
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