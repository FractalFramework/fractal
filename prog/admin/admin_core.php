<?php

class admin_core{
static $private='1';
static $db='syscore';
static $maj='';

static function install(){
sql::create(self::$db,['func'=>'var','vars'=>'var','code'=>'var','txt'=>'text','lang'=>'var'],1);}

//edit
static function edit(){
$ret=form::com(['table'=>self::$db]);}

//save
static function save($p){
$func=val($p,'func'); $lang=val($p,'lang');
$w='where func="'.$func.'" and lang="'.$lang.'"';
$id=sql('id',self::$db,'v',$w);
if($id){
	$txt=sql('txt',self::$db,'v',$id);
	if($txt && !$p['txt'])$p['txt']=$txt;
<<<<<<< HEAD
	sql::upd(self::$db,$p,$id);}
=======
	sql::up2(self::$db,$p,$id);}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
else $id=sql::sav(self::$db,$p);
if(isset(self::$maj[$id]))unset(self::$maj[$id]);
return $id;}

static function update($p){
$id=val($p,'id'); $txt=val($p,'tx'.$id); $rid=val($p,'rid');
<<<<<<< HEAD
sql::upd(self::$db,['txt'=>$txt],$id);
=======
sql::up(self::$db,'txt',$txt,$id);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
return tag('pre','',($txt));}

static function modif($p){$id=val($p,'id');
$txt=sql('txt',self::$db,'v',$id);
$ret=textarea('tx'.$id,$txt,60,6);
$ret.=bj('md'.$id.'|admin_core,update|id='.$id.'|tx'.$id,pic('save'));
return div($ret,'','md'.$id);}

//read
static function seecode($p){$id=val($p,'id');
$ret=sql('code',self::$db,'v',$id);
return div(build::Code($ret),'paneb');}

//build
static function build($f){$rf=explode('/',$f);
$d=read_file($f);
$ra=explode('function ',$d);
foreach($ra as $v){
	$fnc=struntil($v,'{');
	$vr=explode('(',$fnc); $func=$vr[0];
	$vars=(isset($vr[1])?substr($vr[1],0,-1):'');
	$code=trim(str::accolades($v));
	if($code)$rb[]=['func'=>$func,'vars'=>$vars,'code'=>$code,'txt'=>'','lang'=>ses('lng')];}
return $rb;}

//operation
static function reflush(){
self::$maj=sql('id',self::$db,'k','where lang="'.ses('lng').'"');
$f='prog/core.php';
$r=self::build($f,'core');
if($r)foreach($r as $v)$rb[]=self::save($v);
if(isset(self::$maj))foreach(self::$maj as $k=>$v)sql::del(self::$db,$k);//obsoletes
if(isset($rb))return implode(',',$rb);}

//menu
static function menu($p){
$r=sql('id,func,vars,txt',self::$db,'rr','where lang="'.ses('lng').'"');
if($r)foreach($r as $k=>$v){$id=$v['id'];
	$bt=tag('h2','',$v['func'].'('.$v['vars'].')');
	if(auth(6))$bt.=bj('popup|admin_core,seecode|id='.$id,langp('view'),'btn');
	if(auth(6))$bt.=bj('md'.$id.'|admin_core,modif|id='.$id,langp('modif'),'btn').br().br();
	$bt.=div(htmlentities($v['txt']),'','md'.$id);
	$ret[]=div($bt,'board').br();}
if(isset($ret))return implode('',$ret);}

//interface
static function content($p){
self::install();
$rid=randid('dcl');
$bt=bj($rid.'|admin_core,reflush|rid='.$rid,langp('update'),'btn');
$bt.=bj('popup,,xx|core,mkbcp|b=syscore',langp('backup'),'btsav');
$bt.=bj('popup,,xx|core,rsbcp|b=syscore',langp('restore'),'btdel');
//$bt.=aj('popup|admin_core,menu|o=1',langp('view'),'btn');
$ret=self::menu($p);
return $bt.div($ret,'board',$rid);}
}