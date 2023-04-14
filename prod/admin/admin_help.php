<?php

class admin_help{
static $private=6;
static $a='admin_help';
static $db='help';

//install
static function install(){
sql::create(self::$db,['ref'=>'var','txt'=>'text','lang'=>'var'],1);}

//create lang
static function create($p){
$newlng=$p['newlng']??''; $lng='fr';
$ret=input('newlng',$newlng);
$ret.=bj('admcnn|'.self::$a.',create||newlng',langp('add language'),'btn');
if($newlng){
	$r=sql('ref,txt',self::$db,'rr','where lang="'.$lng.'" limit 80,10'); //p($r);
	foreach($r as $k=>$v){
		$ex=sql('txt',self::$db,'v','where ref="'.$v['ref'].'" and lang="'.$newlng.'"');
		if(!$ex){
			$res=trans::com(['from'=>$lng,'to'=>$newlng,'txt'=>$v['txt']]);
			$v['txt']=($res); $v['lang']=$newlng;//utf8_decode
			sql::sav(self::$db,$v); $r[$k]=$v;}
		else $r[$k]['txt']=$ex;}
$ret.=tabler($r);}
return $ret;}

//tools
static function goodid($p){
return sql('id',self::$db,'v',['ref'=>$p['ref'],'lang'=>$p['lang']]);}

static function insertup($p){$id=self::goodid($p);
if($id)sql::up(self::$db,'txt',$p['txt'],$id);
else sql::sav(self::$db,[$p['ref'],$p['txt'],$p['lang']]);}

static function translate($p){$voc=''; $txt=''; $copy=$p['copy']??'';
$r=sql('lang,txt',self::$db,'kv',['ref'=>$p['ref']]);
foreach($r as $k=>$v){
	if($p['lang']!='en' && isset($r['en'])){$from='en'; $txt=$r['en'];}
	if($p['lang']!='fr' && isset($r['fr'])){$from='fr'; $txt=$r['fr'];}}
if($copy)$voc=(($txt));//html_entity_decode//utf8_decode
elseif($txt)$voc=trans::com(['from'=>$from,'to'=>$p['lang'],'txt'=>$txt]);
return $voc;}

static function equalize($p){
$r=sql('ref,lang,txt',self::$db,'kkv','');
$rb=array_keys($r);
foreach($rb as $k=>$v)
	if(!isset($r[$v][$p['lang']])){$txt=''; $voc='';
		if($p['lang']!='en' && isset($r[$v]['en'])){$from='en'; $txt=$r[$v]['en'];}
		if($p['lang']!='fr' && isset($r[$v]['fr'])){$from='fr'; $txt=$r[$v]['fr'];}
		if($txt)$voc=trans::com(['from'=>$from,'to'=>$p['lang'],'txt'=>$txt]);
		self::insertup(['ref'=>$v,'txt'=>$voc,'lang'=>$p['lang']]);}
return self::com($p);}

//save
static function update($p){$rid=$p['rid'];
sql::up(self::$db,'txt',val($p,$rid),$p['id']);
return self::com($p);}

static function del($p){
$nid=sql::del(self::$db,$p['id']);
return self::com($p);}

static function save($p){
$nid=sql::sav(self::$db,[$p['ref'],$p['txt'],$p['lang']]);
return self::com($p);}

static function addfrom($p){
$p['voc']=trans::com(['from'=>$p['from'],'to'=>$p['lang'],'txt'=>$p[$p['rid']]]);
$p['id']=sql::sav(self::$db,[$p['ref'],$p['voc'],$p['lang']]);
return self::edit($p);}

static function edit($p){$rid=randid('ref');
$to=$p['to']??''; $cb=$to?'socket,,x':'admcnn,,x';
$r=sql('ref,txt,lang',self::$db,'ra','where id='.$p['id']);
$ref=$r['ref']??''; $lang=$r['lang']??'';
$ret=label($rid,$ref.' ('.$lang.')');
$ret.=bj($cb.'|'.self::$a.',update|id='.$p['id'].',rid='.$rid.',lang='.$lang.'|'.$rid,lang('save'),'btsav');
$ret.=bj($cb.'|'.self::$a.',del|id='.$p['id'].',lang='.$lang,lang('del'),'btdel');
$ret.=bj('input,'.$rid.'|'.self::$a.',translate|ref='.$ref.',lang='.$lang.',copy='.$p['id'],pic('copy'),'btn');
$ret.=bj('input,'.$rid.'|'.self::$a.',translate|ref='.$ref.',lang='.$lang,pic('translate'),'btn');
$lgb=$lang=='fr'?'en':'fr';
$ret.=bj('popup,,x|'.self::$a.'|lang='.$lgb,ico('window-maximize'),'btn');
foreach(lngs() as $v)if($v!=$lang){
	$id=sql('id',self::$db,'v',['ref'=>$ref,'lang'=>$v]);
	if($id)$ret.=bj('popup|'.self::$a.',edit|id='.$id.',to='.$to,$v,'btn');
	else $ret.=bj('popup|'.self::$a.',addfrom|lang='.$v.',ref='.$ref.',from='.$lang.',to='.$to.',rid='.$rid.'|'.$rid,$v,'btsav');}
$ret.=br().textarea($rid,$r['txt'],60,6);
return $ret;}

static function open($p){$ref=$p['ref']??'';
$p['id']=sql('id',self::$db,'v',['ref'=>$ref]);
if(!$p['id'])$p['id']=sql::sav(self::$db,[$ref,'',ses('lng')]);
if($p['id'])return div(self::edit($p),'','admcnn');}

static function add($p){//ref,txt
$ref=$p['ref']??''; $txt=$p['txt']??'';
$ret=input('ref',$ref?$ref:'',16,'ref').textarea('txt',$txt?$txt:'',40,4,'ref');
$ret.=bj('admcnn,,x|'.self::$a.',save||lang,ref,txt',lang('save'),'btsav');
return $ret;}

static function rename($p){
if($p['ok']??''){
	$r=sql('id,ref',self::$db,'kv',['%ref'=>$p['ref1']]);
	foreach($r as $k=>$v){$v=str_replace($p['ref1'],$p['ref2'],$v); sql::up(self::$db,'ref',$v,$k);}
	//return self::com(['lang'=>$p['lang']]);
	return bj('admcnn,,x|'.self::$a.',com|lang='.$p['lang'],lang('ok'),'btn');}
$ret=input('ref1',$p['ref1']??'',16,'ref old').input('ref2',$p['ref2']??'',16,'ref new');
$ret.=bj('rnhlp|'.self::$a.',rename|ok=1,lang='.$p['lang'].'|ref1,ref2',lang('rename'),'btsav');
return div($ret,'','rnhlp');}

//table
static function select($lang){
$ret=hidden('lang',$lang);
//$r=sql('distinct(lang)',self::$db,'rv','');
$r=lngs();
foreach($r as $v){$c=$v==$lang?' active':'';
	$rc[]=bj('admcnn|'.self::$a.',com|lang='.$v,$v,'btn'.$c);}
$ret.=div(implode('',$rc),'');
if(auth(6)){
	$ret.=bj('popup|'.self::$a.',add',langpi('add'),'btn');
	$ret.=bj('admcnn|'.self::$a.',equalize||lang',langpi('equalize'),'btn');
	$ret.=bj('popup,,xx|core,mkbcp|b=help',langpi('backup'),'btsav');
	if(sql::ex('z_help_'))
	$ret.=bj('popup,,xx|core,rsbcp|b=lang',langpi('restore'),'btdel');
	$ret.=bj('admcnn|'.self::$a.',create',langpi('add language'),'btn');
	$ret.=bj('popup|'.self::$a.',rename|lang='.$lang,langpi('rename'),'btn');}
return div($ret);}

static function com($p){$rb=[];
$lang=$p['lang']??'';
$bt=self::select($lang);
$r=sql('id,ref,txt',self::$db,'','where lang="'.$lang.'" order by ref');
$n=count($r);
$bt.=langnb('occurence',$n,'small');
if($r)foreach($r as $k=>$v){$v[2]=nl2br($v[2]);
	if(auth(6))$ref=bj('popup|'.self::$a.',edit|id='.$v[0],$v[1],'btn');
	else $ref=$v[1];
	if($v[2])$rb[$k]=[$ref,$v[2]];
	else $rc[$k]=[$ref,$v[2]];}
if(isset($rc))$rb=merge($rc,$rb);
array_unshift($rb,['ref',$lang]);
return $bt.tabler($rb,1);}

//content
static function content($p){$ret='';
//self::install();
$lang=$p['lang']??lng();
$ret=self::com(['lang'=>$lang]);
return div($ret,'board','admcnn');}

}
?>