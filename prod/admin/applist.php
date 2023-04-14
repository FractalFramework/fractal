<?php

class applist{
public $ret;

static function headers(){
head::add('csscode','
.block{border:1px solid grey; border-radius:2px; background:white;
padding:10px; margin:10px 0;}
.block a:hover{text-decoration:underline;}
.block span{display:block; cursor:auto;}
.block span:hover{background:white;}
.block div{}');}

#dirs
static function appdirs($o=''){
$r=scandir_r(ses('dev'));
$rb=['js','css',0,1,2]; if(!auth(6)){$rb[]='admin'; $rb[]='core';} if(!$o)$rb[]='dev';
foreach($rb as $v)if(isset($r[$v]))unset($r[$v]);
return $r;}

static function findapp($d){
if(is_string($d) && $d){$app=struntil($d,'.');
	if($app=='index')return;
	if(method_exists($app,'content')){
		$private=isset($app::$private)?$app::$private:0;
		if(!$private or auth($private))return $app;}}}

static function allapps(){
$r=self::appdirs();
//$r=scandir_r(ses('dev'));
foreach($r as $dir=>$rb){
	if(is_array($rb) && $dir)foreach($rb as $k=>$v){
		$a=self::findapp($v);
		if($a)$ret[]=$a;}
	elseif($rb){$a=self::findapp($rb); if($a)$ret[]=$a;}}
sort($ret);
return $ret;}

static function folder($d){
$r=scandir_r(ses('dev').'/'.$d);
if($r)foreach($r as $k=>$v){$a=self::findapp($v); if($a)$ret[]=$a;}
return $ret;}

static function comdir(){
$r=self::appdirs();
if($r)foreach($r as $dir=>$files){
	if(is_array($files) && $dir)foreach($files as $k=>$v){
		$a=self::findapp($v);
		if($a)$ret[]=[$dir,'pop',$a.'|headers=1','',$a];}}
return $ret;}

static function url($a){
$r=self::appdirs();
foreach($r as $dir=>$v){
	if(is_array($v))foreach($v as $k=>$va){
		$f='prog/'.$dir.'/'.$va; if($va==$a.'.php' && is_file($f))return $f;}
	else{$f='prog/'.$dir.'/'.$v; if($v==$a.'.php' && is_file($v))return $f;}}}

static function appsofdir($dir,$files){$ret='';
foreach($files as $k=>$v){$a=self::findapp($v);
	if($a)$ret.=popup($a.'|headers=1',langp($a),'');}
if($ret)return div(div($dir),'block').div($ret,'list');}

#desktop
static function build0($cat='',$tag=''){
$ath=ses('auth'); if($ath<4)$ath=4;
if($tag)$r=sql('app,callid,dir,ref','desktop','rr','inner join lang on ref=com 
inner join tags_r on tags_r.app=desktop.app and tags_r.aid=desktop.callid
inner join tags on tags.id=tags_r.bid
where lang="'.ses('lng').'" and dir like "/apps/'.$cat.'%" and auth<="'.$ath.'" order by voc');
else $r=sql('com,dir','desktop','kv','inner join lang on ref=com
where lang="'.ses('lng').'" and dir like "/apps/'.$cat.'%" and auth<="'.$ath.'" order by voc');
return $r;}

static function build($cat='',$tag=''){
$ath=ses('auth'); if($ath<4)$ath=4;
$r=sql('com,dir','desktop','kv','inner join lang on ref=com
where lang="'.ses('lng').'" and dir like "/apps/'.$cat.'%" and auth<="'.$ath.'" order by voc');
return $r;}

static function pub(){$ath=ses('auth'); //if($ath<4)$ath=4;
$r=sql('com','desktop','rv','where dir like "/apps%" and auth<="'.$ath.'" order by com');
return $r;}

static function menuapp($p){//admin
$ret=loadapp::com($p);
$r=self::build(''); $rb=[];
foreach($r as $k=>$v)$rb[strend($v,'/')][]=$k;
if($rb)foreach($rb as $k=>$v){$ret.=div($k,'btn');
	foreach($v as $ka=>$va)$ret.=popup($va,langp($va));}
return div($ret,'list');}

static function menuapp2($r,$root){//admin
$ra=self::build('');
if($ra)foreach($ra as $k=>$v)$r[]=[$root.$v,'jk',$k,'',strend($k,'/')];//j,jk,cb,pop...
return $r;}

static function com($dr=''){$rb=[];
$r=self::build($dr);
foreach($r as $k=>$v)$rb[]=strend($k,'/');
return $rb;}

static function showroom(){$ret='';
//$r=sql('com','desktop','rv','where dir like "/apps/public%" and auth<=2 order by com');
$r=self::build('public'); //p($r);
$bt=tag('h1','',lang('applist'));
if($r)foreach($r as $k=>$v){$nm=span('[:'.$k.']','grey small');
	$ret.=div(tag('h3','',pic($k,32).lang($k).' '.$nm).helpx($k.'_app','panec'));}
return $bt.div($ret,'board');}

static function content($prm){$ret='';
if(isset($prm['iframe']))$mod=$prm['iframe'];
else $mod=get('app');	
$r=self::appdirs();
if(isset($r))foreach($r as $k=>$v){
	if(is_array($v))$ret.=self::appsofdir($k,$v);
	else $rb[$k]=$v;}
if(isset($rb))$first=self::appsofdir('root',$rb);
else $first='';
return div($first.$ret,'board');}

}
?>