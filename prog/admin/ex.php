<?php

class ex{	
static $private=2;
static $a=__CLASS__;
static $cb='ex';

static function install(){}
static function admin(){}
static function js(){return;}
static function headers(){}

#build
static function findapp($d){
	if(is_string($d)){$app=struntil($d,'.');
		if(method_exists($app,'content'))return $app;}}

static function allapps(){
$r=applist::appdirs();
foreach($r as $dir=>$rb){
	if(is_array($rb) && $dir)foreach($rb as $k=>$v){
		$a=self::findapp($v);
		if($a)$ret[]=$a;}
	else $ret[]=self::findapp($rb);}
return $ret;}

#call
static function call($p){
$d=$p['p1']??''; $ret='';
//$r=ex::allapps();
//if(in_array($d,$r))echo 'e';
$r=dirlist(ses('dev')); //pr($r);
//$k=in_array_like($d.'.php',$r);
//if($k)return $r[$k];
foreach($r as $k=>$v)if($v && $d && strpos($v,$d)!==false)$ret.=div($r[$k]);
if($ret)return $ret;
else return lang('no');}

static function com($p){
$j=self::$cb.'|ex,call||p1';
$bt=bj($j,langp('ok'),'btn');
return inputcall($j,'p1',$p['p1']??'',32).$bt;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=self::com($p);
$ret='';//self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>