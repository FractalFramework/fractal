<?php

class admin_lang{
static $private=6;
static $a='admin_lang';
static $db='lang';
static $cb='admlng';

//install
static function install(){
sql::create('lang',['ref'=>'var','voc'=>'var','app'=>'var','lang'=>'var']);}

//create new language
static function create($p){
$newlng=$p['newlng']; $lng='fr';
$ret=input('newlng',$newlng);
$ret.=bj(self::$cb.'|admin_lang,create||newlng',langp('add language'),'btn');
if($newlng){
	$r=sql('ref,voc,app',self::$db,'rr','where lang="'.$lng.'" limit 450,50');
	foreach($r as $k=>$v){
		$ex=sql('voc',self::$db,'v','where ref="'.$v['ref'].'" and lang="'.$newlng.'"');
		if(!$ex){$v['lang']=$newlng;
			$v['voc']=trans::com(['from'=>$lng,'to'=>$newlng,'txt'=>$v['voc']]);
			sql::sav(self::$db,$v); $r[$k]=$v;}
		else $r[$k]['voc']=$ex;}
$ret.=tabler($r);}
return $ret;}

static function translate($p){$voc=''; $txt=''; $copy=$p['copy']??'';
$r=sql('lang,voc',self::$db,'kv',['ref'=>$p['ref']]);
foreach($r as $k=>$v){
	if($p['lang']!='en' && isset($r['en'])){$from='en'; $txt=$r['en'];}
	if($p['lang']!='fr' && isset($r['fr'])){$from='fr'; $txt=$r['fr'];}}
if($copy)$voc=(($txt));//html_entity_decode//utf8_decode
elseif($txt)$voc=trans::com(['from'=>$from,'to'=>$p['lang'],'txt'=>$txt]);
return $voc;}

static function equalize($p){
$r=sql('ref,lang,voc',self::$db,'kkv','where app="'.$p['app'].'"');
$rb=array_keys($r);
foreach($rb as $k=>$v)
	if(!isset($r[$v][$p['lang']])){$txt=''; $voc='';
		if($p['lang']!='en' && isset($r[$v]['en'])){$from='en'; $txt=$r[$v]['en'];}
		if($p['lang']!='fr' && isset($r[$v]['fr'])){$from='fr'; $txt=$r[$v]['fr'];}
		if($txt)$voc=trans::com(['from'=>$from,'to'=>$p['lang'],'txt'=>$txt]);
		sql::sav(self::$db,[$v,$voc,$p['app'],$p['lang']]);}
return self::com($p);}

static function duplicates($p){
$r=sql::query('select id,ref,count(*) from lang where lang="'.$p['lang'].'" group by ref having count(*)>1','');
return tabler($r);}

//autolang
static function otherlangs($lg){
return sql('distinct(lang)',self::$db,'rv',['!lang'=>$lg]);}

static function saveotherlangs($ref,$txt,$lg,$app){
$r=self::otherlangs($lg);
if($r)foreach($r as $k=>$v){
	if($txt)$voc=trans::com(['from'=>$lg,'to'=>$v,'txt'=>$txt]); else $voc='';
	sql::sav(self::$db,[$ref,$voc,$app,$v]);}}

static function modifotherlangs($ref,$txt,$lg,$app){
$r=self::otherlangs($lg); $voc='';
if($r)foreach($r as $k=>$v){
	//if($txt)$voc=trans::com(['from'=>$lg,'to'=>$v,'txt'=>$txt]);
	if($voc)sql::savup(self::$db,['voc'=>$voc,'app'=>$app],['ref'=>$ref,'lang'=>$v]);}}

//save
static function update($p){$rid=$p['rid'];
sql::upd(self::$db,['voc'=>$p[$rid],'app'=>$p['app'.$rid]],$p['id'],0);
if($p['lang']==lng())sesf('lang_com',$p['lang'],1);//update session
self::modifotherlangs($p['ref'],$p[$rid],$p['lang'],$p['app'.$rid]);
return self::com($p);}

static function del($p){
if($id=$p['id']??'')$nid=sql::del(self::$db,$id);
if($ref=$p['ref']??'')$nid=sql::del(self::$db,$ref,'ref');
if($p['lang']==lng())sesf('lang_com',$p['lang'],1);//update session
return self::com($p);}//self::add($p).br().

static function save($p){
$nid=sql::sav(self::$db,[$p['ref'],$p['voc'],$p['app'],$p['lang']]);
if($p['lang']==lng())sesf('lang_com',$p['lang'],1);//update session
self::saveotherlangs($p['ref'],$p['voc'],$p['lang'],$p['app']);
return self::com($p);}

static function addfrom($p){
$p['voc']=trans::com(['from'=>$p['from'],'to'=>$p['lang'],'txt'=>$p['fvoc']]);
$p['id']=sql::sav(self::$db,[$p['ref'],$p['voc'],$p['app'],$p['lang']]);
if($p['lang']==lng())sesf('lang_com',$p['lang'],1);//update session
return self::edit($p);}

static function edit($p){
$app=$p['app']??''; $rid=randid('voc');//id
$r=sql('ref,voc,lang,app',self::$db,'ra',$p['id']);
$ref=$r['ref']??''; $lang=$r['lang']??''; $voc=$r['voc']??'';
$bt=btj($ref,atj('val',[$ref,$rid]));
$j=self::$cb.'|admin_lang,update|id='.$p['id'].',rid='.$rid.','.prmb($r,['ref','lang','app']).'|'.$rid.',app'.$rid;
$ret=label($rid,$bt.' ('.$lang.')').inputcall($j,$rid,$voc,16);
$ro=sql('distinct(app)',self::$db,'rv','');
$ret.=datalist('app'.$rid,$ro,$r['app'],8,'');
//$prm=prm(['id'=>$p['id'],'rid'=>$rid,'lang'=>$lang,'app'=>$r['app']]);
//$ret.=bj(self::$cb.',,x|admin_lang,update|id='.$p['id'].',rid='.$rid.',lang='.$lang.',app='.$r['app'].'|'.$rid.',app'.$rid,langp('save'),'btsav');
$ret.=aj(self::$cb.',,zx','admin_lang,update',['id'=>$p['id'],'rid'=>$rid,'ref'=>$ref,'lang'=>$lang,'app'=>$r['app']],[$rid,'app'.$rid],langp('save'),'btsav');
$ret.=bj('input,'.$rid.'|admin_lang,translate|ref='.$ref.',lang='.$lang,langpi('translate'),'btn');
$del=self::$cb.',,x|admin_lang,del|lang='.$lang.',app='.$r['app'];
$ret.=bj($del.',id='.$p['id'].',app='.$app,langpi('del'),'btdel');
$ret.=bj($del.',ref='.$ref,langpi('del all'),'btdel').br();
foreach(lngs() as $v)if($v!=$lang){
	$id=sql('id',self::$db,'v',['ref'=>$ref,'lang'=>$v]);
	if($id)$ret.=bj('popup|admin_lang,edit|id='.$id,$v,'btn');
	else $ret.=bj('popup|admin_lang,addfrom|app='.$r['app'].',lang='.$v.',ref='.$ref.',from='.$lang.',fvoc='.$voc,$v,'btsav');}
return $ret;}

static function open($p){$ref=$p['ref']; $app=$p['app'];
$p['id']=sql('id',self::$db,'v',['ref'=>$ref]);
if(!$p['id'])$p['id']=sql::sav(self::$db,[$ref,'',$app,ses('lng')]);
if($p['id'])return div(self::edit($p),'',self::$cb);}

static function add($p){//ref,voc
$ref=$p['ref']??''; $voc=$p['voc']??'';
$ret=input('ref',$ref?$ref:'',16,'ref').input('voc',$voc?$voc:'',16,'voc');
$ret.=bj(self::$cb.',,x|admin_lang,save||app,lang,ref,voc',langp('save'),'btsav');
return $ret;}

static function rename($p){
if($p['ok']??''){
	$r=sql('id,ref',self::$db,'kv',['%ref'=>$p['ref1']]);
	foreach($r as $k=>$v){$v=str_replace($p['ref1'],$p['ref2'],$v); sql::upd(self::$db,['ref'=>$v],$k);}
	//return self::com(['lang'=>$p['lang']]);
	return bj(self::$cb.',,x|'.self::$a.',com|lang='.$p['lang'],lang('ok'),'btn');}
$ret=input('ref1',$p['ref1']??'',16,'ref old').input('ref2',$p['ref2']??'',16,'ref new');
$ret.=bj('rnlng|'.self::$a.',rename|ok=1,lang='.$p['lang'].'|ref1,ref2',lang('rename'),'btsav');
return div($ret,'','rnlng');}

static function delempty($p){
$r=sql('id',self::$db,'rv',['voc'=>'is empty']);
//sql::del(self::$db,['voc'=>''],'is empty',1);
sql::del(self::$db,['(id'=>$r]);
return count($r).' erased';}

//table
static function select($app,$lang){
$ret=hidden('app',$app).hidden('lang',$lang);
$r=sql('distinct(lang)',self::$db,'rv','');//langs
foreach($r as $v){$c=$v==$lang?' active':'';
	$rc[]=bj(self::$cb.'|admin_lang,com|lang='.$v.'|app',$v,'btn'.$c);}
$bt=implode(' ',$rc).' :: ';
//apps
$r=sql('distinct(app)',self::$db,'rv','order by app');
if(!$r)$r=lngs();
$bt.=bj(self::$cb.',,y|admin_lang,com|app=new|lang','new','btn'.($app=='new'?' active':''));
$bt.=bj(self::$cb.',,y|admin_lang,com|app=all|lang','all','btn'.($app=='all'?' active':''));
//foreach($r as $v){$c=$v==$app?' active':'';
	//$bt.=bj(self::$cb.',,y|admin_lang,com|app='.$v.'|lang',$v,'btn'.$c);}
$bt.=select('slctapp',$r,$app,1,0,self::$cb.',,y|admin_lang,com|lang='.$lang.',app=');
$ret.=div($bt,'pane');
if(auth(6)){
	$ret.=bj('popup|admin_lang,add|app='.$app,langpi('add'),'btn');
	$ret.=bj(self::$cb.'|admin_lang,equalize||app,lang',langpi('equalize'),'btn');
	$ret.=bj('popup,,xx|core,mkbcp|b=lang',langpi('backup'),'btsav');
	if(sql::ex('z_lang_'))
	$ret.=bj('popup,,xx|core,rsbcp|b=lang',langpi('restore'),'btdel');
	//$ret.=bj(self::$cb.'|admin_lang',langp('reload'),'btn').br();
	$ret.=bj('popup|admin_lang,duplicates|lang='.$lang,langpi('duplicates'),'btn');
	$ret.=bj(self::$cb.'|admin_lang,create',langpi('add language'),'btn');
	$ret.=bj('popup|'.self::$a.',rename|lang='.$lang,langpi('rename'),'btn');
	$ret.=bj(self::$cb.'|'.self::$a.',delempty|lang='.$lang,langpi('del_empty'),'btn');}
return $ret;}

static function com($p){$rb=[];
$app=val($p,'app','new'); $lang=$p['lang'];
$bt=self::select($app,$lang).br();
if($app=='new')$wh=' and voc=""';
elseif($app!='all')$wh=' and app="'.$app.'"'; else $wh='';
$r=sql('id,ref,voc',self::$db,'','where lang="'.$lang.'"'.$wh.' order by up desc');
$n=count($r);
$bt.=langnb('occurence',$n,'small');
foreach($r as $k=>$v){
	if(auth(6))$ref=bj('popup|admin_lang,edit|id='.$v[0].',app='.$app,$v[1],'btn');
	else $ref=$v[1];
	if($v[2])$rb[$k]=[$ref,$v[2]];
	else $rc[$k]=[$ref,$v[2]];}
if(isset($rc))$rb=array_merge($rc,$rb);
array_unshift($rb,['ref',$lang]);
return $bt.tabler($rb,1);}

//content
static function content($p){$ret='';
//self::install();
$app=$p['app']??''; $lang=val($p,'lang',lng());
$ret=self::com(['app'=>$app,'lang'=>$lang]);
return div($ret,'board',self::$cb);}
}
?>