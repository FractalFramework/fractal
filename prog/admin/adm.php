<?php

class adm{
static $private=0;
static $a='adm';
static $db='lang';
static $dbr=['lang','help','icons','labels','voc'];

static function admin(){
return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}

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

//create new language
static function create($p){
$newlng=val($p,'newlng'); $lng='fr';
$ret=input('newlng',$newlng);
$ret.=bj('admlng|adm,create||newlng',langp('add language'),'btn');
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

static function translate($p){$voc=''; $txt=''; $copy=val($p,'copy');
$r=sql('lang,voc',self::$db,'kv',['ref'=>$p['ref']]);
foreach($r as $k=>$v){
	if($p['lang']!='en' && isset($r['en'])){$from='en'; $txt=$r['en'];}
	if($p['lang']!='fr' && isset($r['fr'])){$from='fr'; $txt=$r['fr'];}}
if($copy)$voc=(($txt));//html_entity_decode//utf8_decode
elseif($txt)$voc=trans::com(['from'=>$from,'to'=>$p['lang'],'txt'=>$txt]);
return $voc;}

//save
static function update($p){$rid=$p['rid'];
sql::up(self::$db,'voc',$p[$rid],$p['id']);
sql::up(self::$db,'app',$p['app'.$rid],$p['id']);
if($p['lang']==lng())sesf('lang_com',$p['lang'],1);//update session
return self::com($p);}

static function del($p){
$id=$p['id']??''; $ref=$p['ref']??'';
if($id)$nid=sql::del(self::$db,$id);
if($ref)$nid=sql::del(self::$db,$ref,'ref');
if($p['lang']==lng())sesf('lang_com',$p['lang'],1);//update session
return self::com($p);}//self::add($p).br().

static function save($p){
$nid=sql::sav(self::$db,[$p['ref'],$p['voc'],$p['app'],$p['lang']]);
if($p['lang']==lng())sesf('lang_com',$p['lang'],1);//update session
return self::com($p);}

static function addfrom($p){
$p['voc']=trans::com(['from'=>$p['from'],'to'=>$p['lang'],'txt'=>$p['fvoc']]);
$p['id']=sql::sav(self::$db,[$p['ref'],$p['voc'],$p['app'],$p['lang']]);
if($p['lang']==lng())sesf('lang_com',$p['lang'],1);//update session
return self::edit($p);}

static function edit($p){$rid=randid('voc');//id
$r=sql('ref,voc,lang,app',self::$db,'ra','where id='.$p['id']);
$ret=label($rid,$r['ref'].' ('.$r['lang'].')').input($rid,$r['voc'],16);
$ro=sql('distinct(app)',self::$db,'rv','');
$ret.=datalist('app'.$rid,$ro,$r['app'],8,'app');
$ret.=bj('admlng,,x|adm,update|id='.$p['id'].',rid='.$rid.',lang='.$r['lang'].',app='.$r['app'].'|'.$rid.',app'.$rid,langp('save'),'btsav');
$ret.=bj('input,'.$rid.'|adm,translate|ref='.$r['ref'].',lang='.$r['lang'],langpi('translate'),'btn');
$del='admlng,,x|adm,del|lang='.$r['lang'].',app='.$r['app'];
$ret.=bj($del.',id='.$p['id'],langpi('del'),'btdel');
$ret.=bj($del.',ref='.$r['ref'],langpi('del all'),'btdel').br();
foreach(lngs() as $v)if($v!=$r['lang']){
	$id=sql('id',self::$db,'v',['ref'=>$r['ref'],'lang'=>$v]);
	if($id)$ret.=bj('popup|adm,edit|id='.$id,$v,'btn');
	else $ret.=bj('popup|adm,addfrom|app='.$r['app'].',lang='.$v.',ref='.$r['ref'].',from='.$r['lang'].',fvoc='.$r['voc'],$v,'btsav');}
return $ret;}

static function open($p){$ref=val($p,'ref'); $app=$p['app']??'';
$p['id']=sql('id',self::$db,'v',['ref'=>$ref]);
if(!$p['id'])$p['id']=sql::sav(self::$db,[$ref,'',$app,ses('lng')]);
if($p['id'])return self::edit($p);}

static function add($p){//ref,voc
$ref=val($p,'ref'); $voc=val($p,'voc');
$ret=input('ref',$ref?$ref:'',16,'ref').input('voc',$voc?$voc:'',16,'voc');
$ret.=bj('admlng,,x|adm,save||app,lang,ref,voc',langp('save'),'btsav');
return $ret;}

//table
static function select($app,$lang){
$ret=hidden('app',$app).hidden('lang',$lang);
//langs
$r=sql('distinct(lang)',self::$db,'rv','');
foreach($r as $v){$c=$v==$lang?' active':'';
	$rc[]=bj('admlng|adm,com|lang='.$v.'|app',$v,'btn'.$c);}
$bt=implode(' ',$rc).' :: ';
//apps
$r=sql('distinct(app)',self::$db,'rv','order by app');
if(!$r)$r=lngs();
$rb[]=bj('admlng,,y|adm,com|app=new|lang','new','btn'.($app=='new'?' active':''));
$rb[]=bj('admlng,,y|adm,com|app=all|lang','all','btn'.($app=='all'?' active':''));
foreach($r as $v){$c=$v==$app?' active':'';
	$rb[]=bj('admlng,,y|adm,com|app='.$v.'|lang',$v,'btn'.$c);}
$bt.=implode(' ',$rb);
$ret.=div($bt,'pane');
if(auth(6)){
	$ret.=bj('popup|adm,add|app='.$app,langp('add'),'btn');
	$ret.=bj('admlng|adm,equalize||app,lang',langp('equalize'),'btn');
	$ret.=bj('popup,,xx|core,mkbcp|b=lang',langp('backup'),'btsav');
	if(sql::ex('lang_bak'))
	$ret.=bj('popup,,xx|core,rsbcp|b=lang',langp('restore'),'btdel');
	//$ret.=bj('admlng|adm',langp('reload'),'btn').br();
	$ret.=bj('popup|adm,duplicates|lang='.$lang,langp('duplicates'),'btn');
	$ret.=bj('admlng|adm,create',langp('add language'),'btn');}
return $ret;}

static function com($p){$rb=array();
$app=val($p,'app','new'); $lang=$p['lang']??'';
$bt=self::select($app,$lang).br();
if($app=='new')$wh=' and voc=""';
elseif($app!='all')$wh=' and app="'.$app.'"'; else $wh='';
$r=sql('id,ref,voc',self::$db,'','where lang="'.$lang.'"'.$wh.' order by ref');
$n=count($r);
$bt.=langnb('occurence',$n,'small');
foreach($r as $k=>$v){
	if(auth(6))$ref=bj('popup|adm,edit|id='.$v[0],$v[1],'btn');
	else $ref=$v[1];
	if($v[2])$rb[$k]=[$ref,$v[2]];
	else $rc[$k]=[$ref,$v[2]];}
if(isset($rc))$rb=array_merge($rc,$rb);
array_unshift($rb,['ref',$lang]);
return $bt.tabler($rb,1);}

//content
static function content($p){$ret='';
//self::install();
$app=$p['app']??''; $lang=$p['lang']??lng();
$ret=self::com(['app'=>$app,'lang'=>$lang]);
return div($ret,'','admlng');}
}
?>