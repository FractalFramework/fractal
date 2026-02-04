<?php

class admin_icons{
static $private=6;
static $a='admin_icons';
static $db='icons';
static $cb='admpc';

static function headers(){
head::add('csscode','');}

//install
static function install(){
sql::create(self::$db,['ref'=>'var','icon'=>'var']);}

static function admin(){
return admin::app(['a'=>self::$a,'db'=>self::$db]);}

//save
static function update($p){$rid=$p['rid'];
sql::upd(self::$db,['icon'=>$p[$rid]],$p['id']);
$r=sesf('icon_com','',1);
return self::com($p);}

static function del($p){
$nid=sql::del(self::$db,$p['id']);
return self::com($p);}

static function save($p){//$lang=$p['lang']??'';,$lang
$nid=sql::sav(self::$db,[$p['ref'],$p['icon']]);
$r=sesf('icon_com','',1);
return self::com($p);}

static function edit($p){$rid=randid('icons');//id
$r=sql('ref,icon',self::$db,'ra',$p['id']);
$ret=label($rid,$r['ref']);
$ret.=goodinput($rid,$r['icon']);
$ret.=bj(self::$cb.',,x|admin_icons,update|id='.$p['id'].',rid='.$rid.'|'.$rid,lang('save'),'btsav');
$ret.=bj(self::$cb.',,x|admin_icons,del|id='.$p['id'],lang('del'),'btdel');
$ret.=bj('popup|icons',pic('pictos'),'btn');
return $ret;}

static function open($p){$ref=val($p,'ref');
$p['id']=sql('id',self::$db,'v',['ref'=>$ref]);
if(!$p['id'])$p['id']=sql::sav(self::$db,[$ref,'']);
if($p['id'])return div(self::edit($p),'',self::$cb);}

static function add($p){//ref,icon
$ref=val($p,'ref'); $icon=val($p,'icon');
$ret=input('ref',$ref?$ref:'',16,'ref').input('icon',$icon?$icon:'',16,'icon');
$ret.=bj(self::$cb.',,x|admin_icons,save||ref,icon',lang('save'),'btsav');
return $ret;}

static function rename($p){
if($p['ok']??''){
	$r=sql('id,ref',self::$db,'kv',['%ref'=>$p['ref1']]);
	foreach($r as $k=>$v){$v=str_replace($p['ref1'],$p['ref2'],$v); sql::upd(self::$db,['ref'=>$v],$k);}
	//return self::com([]]);
	return bj(self::$cb.',,x|'.self::$a.',com',lang('ok'),'btn');}
$ret=input('ref1',$p['ref1']??'',16,'ref old').input('ref2',$p['ref2']??'',16,'ref new');
$ret.=bj('rnlng|'.self::$a.',rename|ok=1|ref1,ref2',lang('rename'),'btsav');
return div($ret,'','rnlng');}

static function delempty($p){
$r=sql('id',self::$db,'rv',['icon'=>'is empty']);
sql::del(self::$db,['(id'=>$r]);
return count($r).' erased';}

//table
static function select(){$ret='';
if(auth(6)){
	$ret.=bj('popup|admin_icons,add',langp('add'),'btn');
	$ret.=bj('popup,,xx|core,mkbcp|b=icons',langp('backup'),'btsav');
	if(sql::ex('z_icons_'))
	$ret.=bj('popup,,xx|core,rsbcp|b=lang',langpi('restore'),'btdel');
	$ret.=bj('popup|icons',pic('pictos'),'btn');
	$ret.=bj(self::$cb.'|admin_icons',pic('reload'),'btn');
	$ret.=bj('popup|'.self::$a.',rename',langpi('rename'),'btn');
	$ret.=bj(self::$cb.'|'.self::$a.',delempty|',langpi('del_empty'),'btn');}
return div($ret);}

static function com(){$rb=array();
$bt=self::select();
$r=sql('id,ref,icon',self::$db,'','order by up desc');
if($r)foreach($r as $k=>$v){
	$ref=bj('popup|admin_icons,edit|id='.$v[0],$v[1],'btn');
	$icon=ico($v[2],32);
	if(!$v[2])$rc[$k]=[$ref,$icon]; 
	else $rb[$k]=[$ref,$icon.' '.$v[2]];}
if(isset($rc))$rb=array_merge($rc,$rb);
array_unshift($rb,['ref','icon']);
return $bt.tabler($rb,1);}

//content
static function content($p){$ret='';
//self::install();
$ret=self::com();
return div($ret,'board',self::$cb);}

}
?>