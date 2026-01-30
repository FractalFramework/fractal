<?php

class admin_conn{
static $private=6;
static $db='conn';
static $ad='admin_conn';

//install
static function install(){
sql::create(self::$db,['ref'=>'var','txt'=>'text','lang'=>'var'],1);}

//create lang
static function create($p){
$newlng=val($p,'newlng'); $lng='fr';
$ret=input('newlng',$newlng);
$ret.=bj('admcnn|'.self::$ad.',create||newlng',langp('add language'),'btn');
if($newlng){
	$r=sql('ref,txt',self::$db,'rr','where lang="'.$lng.'"'); p($r);// limit 80,10
	if($r)foreach($r as $k=>$v){
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
if($id)sql::upd(self::$db,['txt'=>$p['txt']],$id);
else sql::sav(self::$db,[$p['ref'],$p['txt'],$p['lang']]);}

static function translate($p){$voc=''; $txt=''; $copy=val($p,'copy');
$r=sql('lang,txt',self::$db,'kv',['ref'=>$p['ref']]);
if($r)foreach($r as $k=>$v){
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
		//if($txt)$voc=trans::com(['from'=>$from,'to'=>$p['lang'],'txt'=>$txt]);
		self::insertup(['ref'=>$v,'txt'=>$voc,'lang'=>$p['lang']]);}
return self::com($p);}

//save
static function update($p){
$rid=$p['rid']; $txt=$p[$rid];
sql::upd(self::$db,['txt'=>$txt],$p['id']);
return self::com($p);}

static function del($p){
$nid=sql::del(self::$db,$p['id']);
return self::com($p);}

static function save($p){
$nid=sql::sav(self::$db,[$p['ref'],$p['txt'],$p['lang']]);
return self::com($p);}

static function edit($p){$rid=randid('ref');
$to=val($p,'to')?'socket,,x':'admcnn,,x';
$r=sql('ref,txt,lang',self::$db,'ra',$p['id']);
$ret=label($rid,$r['ref'].' ('.$r['lang'].')');
$ret.=bj($to.'|'.self::$ad.',update|id='.$p['id'].',rid='.$rid.',lang='.$r['lang'].'|'.$rid,lang('save'),'btsav');
$ret.=bj($to.'|'.self::$ad.',del|id='.$p['id'].',lang='.$r['lang'],lang('del'),'btdel');
$ret.=bj('input,'.$rid.'|'.self::$ad.',translate|ref='.$r['ref'].',lang='.$r['lang'].',copy='.$p['id'],pic('copy'),'btn');
$ret.=bj('input,'.$rid.'|'.self::$ad.',translate|ref='.$r['ref'].',lang='.$r['lang'],pic('language'),'btn');
foreach(lngs() as $v)if($v!=$r['lang']){
	$id=sql('id',self::$db,'v',['ref'=>$r['ref'],'lang'=>$v]);
	if($id)$ret.=bj('popup|'.self::$ad.',edit|id='.$id,$v,'btn');}
$lgb=$r['lang']=='fr'?'en':'fr';
$ret.=bj('popup,,x|'.self::$ad.'|lang='.$lgb,ico('window-maximize'),'btn');
$ret.=br().textarea($rid,$r['txt'],40,4);
return $ret;}

static function add($p){//ref,txt
$ref=val($p,'ref'); $txt=$p['txt']??'';
$ret=input('ref',$ref?$ref:'',16,'ref').textarea('txt',$txt?$txt:'',40,4,'ref');
$ret.=bj('admcnn,,x|'.self::$ad.',save||lang,ref,txt',lang('save'),'btn');
return $ret;}

//table
static function select($lang){
$ret=hidden('lang',$lang);
//$r=sql('distinct(lang)',self::$db,'rv','');
$r=lngs();
foreach($r as $v){$c=$v==$lang?' active':'';
	$rc[]=bj('admcnn|'.self::$ad.',com|lang='.$v,$v,'btn'.$c);}
$ret.=div(implode('',$rc),'');
if(auth(6)){
	$ret.=bj('popup|'.self::$ad.',add',langp('add'),'btn');
	$ret.=bj('admcnn|'.self::$ad.',equalize||lang',langp('equalize'),'btn');
	$ret.=bj('popup,,xx|core,mkbcp|b=help',langp('backup'),'btsav');
	if(sql::ex('help_bak'))
	$ret.=bj('popup,,xx|core,rsbcp|b=lang',langp('restore'),'btdel');
	$ret.=bj('admcnn|'.self::$ad.',create',langp('add language'),'btn').br();}
return $ret;}

static function com($p){$rb=array();
$lang=$p['lang']??'';
$bt=self::select($lang).br();
$r=sql('id,ref,txt',self::$db,'','where lang="'.$lang.'"');
$n=count($r);
$bt.=langnb('occurence',$n,'small');
if($r)foreach($r as $k=>$v){$v[2]=nl2br($v[2]);
	if(auth(6))$ref=bj('popup|'.self::$ad.',edit|id='.$v[0],$v[1],'btn');
	else $ref=$v[1];
	if($v[2])$rb[$k]=[$ref,$v[2]];
	else $rc[$k]=[$ref,$v[2]];}
if(isset($rc))$rb=merge($rc,$rb);
array_unshift($rb,['ref',$lang]);
return $bt.tabler($rb,1);}

//content
static function content($p){$ret='';
//self::install();
$lang=val($p,'lang',lng());
$ret=self::com(['lang'=>$lang]);
return div($ret,'board','admcnn');}

}