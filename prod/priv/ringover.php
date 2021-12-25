<?php

//application not based on appx
class ringover{
static $private=0;
static $a=__CLASS__;
static $db='ringover';
static $db2='ringover_weather';
static $cols=['country','city_label'];
static $typs=['var','var'];
static $cb='mdb';

static function install(){
sqlcreate(self::$db,array_combine(self::$cols,self::$typs),1);
sqlcreate(self::$db2,['city_id'=>'var','temperature'=>'float','weather'=>'var','precipitation'=>'float','humidity'=>'float','wind'=>'int','date'=>'date'],1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function injectJs(){return;}
static function headers(){
add_head('csscode','');
add_head('jscode',self::injectJs());}

#api
static function apicall($r,$mode){
	if(!$mode)$mode='get';//post,put,get,del
	$ubase='';//https://logic.ovh///unused
	$u=$ubase.'api/ringover/'.$mode.'?'.mkprm($r);
	$d=@file_get_contents($u);
	return json_decode($d,true);}

#build
static function build($p){$id=$p['id']??''; return [];//!
	$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
	return $r;}

#play
static function play($p,$r){$ret=''; //pr($r);
if($r)foreach($r as $k=>$v){$rb=[];
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
return $ret;}

//render
static function icosvg($n){
	//$r=json('../json/weather-svg.json');
	$r=db::read('db/public/weather-svg.php');
	foreach($r as $k=>$v)if($v[0]==(string)$n)return $v;}

static function render($n){
	[$n,$nm,$ic]=self::icosvg($n);//$nm='Soleil'; $ic='day';
	$im=img('usr/_/weather-svg-anim/'.$ic.'.svg',36);
	$tx=span($nm,'bold');
	$ret=div(div($im,'cell').div($tx,'cell'),'row');
	return $ret;}

#call
static function call($p){
//$r=self::build($p);
//$ret=self::play($p,$r);
$n=$p['p1']??1;
$ret=self::render($n);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){}

static function menu($p){$p1=$p['p1']??'';
$ret=bj(self::$cb.',,z|'.self::$a.',call|act=all|p1',langp('all'),'btsav');
$ret.=bj(self::$cb.',,z|'.self::$a.',call|act=add|p1',langp('add'),'btsav');
$ret.=bj(self::$cb.',,z|'.self::$a.',call|act=del|p1',langp('delete'),'btsav');
$ret.=bj(self::$cb.',,z|'.self::$a.',call|act=wheather|p1',langp('weather'),'btsav');
$ret.=bj(self::$cb.',,z|'.self::$a.',call|act=create_weather|p1',langp('create'),'btsav');
$ret.=bj(self::$cb.',,z|'.self::$a.',call|act=del_weather|p1',langp('remove'),'btsav');
$ret.=lk('http://logic.ovh/api/cartapi/view&',langp('api'),'btn');
$ret.=hlpbt('cartapi_app');
return $ret;}

#content
static function content($p){
self::install();
$p['p1']=$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}

?>