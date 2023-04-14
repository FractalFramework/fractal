<?php

class admin_labels{
static $private=6;
static $db='labels';

static function headers(){
head::add('csscode','');}

//install
static function install(){
sql::create(self::$db,['ref'=>'var','icon'=>'var']);}

//save
static function update($p){$rid=$p['rid'];
sql::up(self::$db,'icon',$p[$rid],$p['id']);
return self::com($p);}

static function del($p){
$nid=sql::del(self::$db,$p['id']);
return self::com($p);}

static function save($p){//$lang=$p['lang']??'';,$lang
$nid=sql::sav(self::$db,[$p['ref'],$p['icon']]);
return self::com($p);}

static function edit($p){$rid=randid('labels');//id
$r=sql('ref,icon',self::$db,'ra','where id='.$p['id']);
$ret=label($rid,$r['ref']);
$ret.=goodinput($rid,$r['icon']);
$ret.=bj('admm,,x|admin_labels,update|id='.$p['id'].',rid='.$rid.'|'.$rid,lang('save'),'btsav');
$ret.=bj('admm,,x|admin_labels,del|id='.$p['id'],lang('del'),'btdel');
$ret.=bj('popup|icons',ico('eye'),'btn');
return $ret;}

static function add($p){//ref,icon
$ref=val($p,'ref'); $icon=val($p,'icon');
$ret=input('ref',$ref?$ref:'',16,'ref').input('icon',$icon?$icon:'',16,'icon');
$ret.=bj('admm,,x|admin_labels,save||ref,icon',lang('save'),'btsav');
return $ret;}

static function open($p){$ref=val($p,'ref');
$p['id']=sql('id',self::$db,'v',['ref'=>$ref]);
if(!$p['id'])$p['id']=sql::sav(self::$db,[$ref,'']);
if($p['id'])return div(self::edit($p),'','admm');}

//table
static function select(){$ret='';
if(auth(6)){
	$ret.=bj('popup|admin_labels,add',langp('add'),'btn');
	$ret.=bj('popup,,xx|core,mkbcp|b=labels',langp('backup'),'btsav');
	if(sql::ex('labels_bak'))
	$ret.=bj('popup,,xx|core,rsbcp|b=labels',langp('restore'),'btdel');}
	$ret.=bj('admm|admin_labels',langp('reload'),'btn').br();
return $ret;}

static function com(){$rb=array();
$bt=self::select().br();
$r=sql('id,ref,icon',self::$db,'','order by ref');
if($r)foreach($r as $k=>$v){
	$ref=bj('popup|admin_labels,edit|id='.$v[0],$v[1],'btn');
	$icon=ico($v[2],32);
	if(!$v[2])$rc[$k]=[$ref,$icon]; 
	else $rb[$k]=[$ref,$icon.' '.$v[2]];}
if(isset($rc))$rb=array_merge($rc,$rb);
array_unshift($rb,['ref','icon']);
return $bt.tabler($rb,1);}

static function conv(){
$r=sql('id,ref','labels_all','kv','order by ref'); //pr($r);
foreach($r as $k=>$v){
	$voc=trans::com(['from'=>'fr','to'=>'en','txt'=>$v]);
	sql::up('labels_all','ref',$voc,$k,'id');}}

static function fusion(){
$r=sql('ref,icon','labels_all','kv','order by ref'); //pr($r);
foreach($r as $k=>$v){
	$ex=sql('id','labels','v',['ref'=>$k]); echo $ex.' ';
	if(!$ex)sql::sav('labels',['ref'=>$k,'icon'=>$v]);}}

//content
static function content($p){$ret='';
//self::install();
$ret=self::com();
//self::fusion();
return div($ret,'board','admm');}

}
?>