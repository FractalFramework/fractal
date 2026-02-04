<?php
class multilang extends appx{
static $private=0;
static $a='multilang';
static $db='multilang';
static $cb='mtl';
static $cols=['tit','txt','lang','md5','pub','edt'];
static $typs=['var','text','var','var','int','int'];
static $conn=0;
static $gen=0;
//static $db2='multilg_b';//sub
static $open=0;
static $tags=1;
static $qb='';//db

//first col,txt,answ,com(settings),code,day,clr,img,nb,cl,pub,edt
//$db2 must use col "bid" <-linked to-> id

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','idb'=>'int','lang'=>'var'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){
//$p['db2']=self::$db2;
return parent::del($p);}

static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){return parent::create($p);}//$p['pub']=0;//default privacy

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}
static function subform($r){return parent::subform($r);}

//form
//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
return parent::form($p);}

static function edit($p){
//$p['help']=1;
return parent::edit($p);}

#translations
static function refresh($p){$id=$p['id']??'';//make backup
$md5=sql('md5',self::$db,'v',$id);
sql::upd(self::$db,['md5'=>'_'.$md5],$id);
$id=sql('id',self::$db,'v',['md5'=>$md5,'lang'=>'fr']);
if(!$id)$id=sql('id',self::$db,'v',['md5'=>$md5,'lang'=>'en']);
if(!$id)$id=sql('id',self::$db,'v',['md5'=>$md5,'lang'=>'es']); $p['id']=$id;
return self::newfrom($p);}

static function redo($p){
$id=$p['id']??''; $lang=$p['lang']??'';
$md5=sql('md5',self::$db,'v',$id); $p['opn']=1;
$r=sql('lang,id',self::$db,'kv','where md5="'.$md5.'" and lang!="'.$lang.'"');
if(isset($r['fr'])){$idb=$r['fr']; $lg='fr';}
if(isset($r['en'])){$idb=$r['en']; $lg='en';}
if(isset($r['es'])){$idb=$r['es']; $lg='es';}
if(isset($r['it'])){$idb=$r['it']; $lg='it';}
if(isset($idb)){
	[$t,$d]=sql('tit,txt',self::$db,'rw',$idb);
	$t2=trans::com(['from'=>$lg,'to'=>$lang,'txt'=>$t]);
	if($t2)sql::upd(self::$db,['tit'=>$t2],$id);
	$d2=trans::com(['from'=>$lg,'to'=>$lang,'txt'=>$d]);
	if($d2)sql::upd(self::$db,['txt'=>$d2],$id);}
return self::edit($p);}

static function newfrom($p){
$id=$p['id']??''; $lang=$p['lang']??''; $p['opn']=1;
[$t,$d,$lg,$md5]=sql('tit,txt,lang,md5',self::$db,'rw',$id);
$t2=trans::com(['from'=>$lg,'to'=>$lang,'txt'=>$t]);
$d2=trans::com(['from'=>$lg,'to'=>$lang,'txt'=>$d]);
$p['id']=sql::sav(self::$db,[ses('uid'),$t2,$d2,$lang,$md5,0,0]);
return self::edit($p);}

static function translations($p){$ret='';
$id=$p['id']??''; $lang=$p['lang']??''; $md5=val($p,'md5');
$cb=self::$cb; $ra=['fr','en','es','it','de'];//self::langs();
$r=sql('lang,id',self::$db,'kv',['md5'=>$md5]); //pr($r);
if(!isset($r[$lang]))$r[$lang]=$id; //$cb=$cb.'play';
foreach($ra as $k=>$v){
	if($v==$p['lang'])$c=' active'; else $c='';
	if(isset($r[$v]))$ret.=bj($cb.'|'.self::$a.',edit|opn=1,id='.$r[$v],lang($v),'btn'.$c);
	else $ret.=bj($cb.',,z|'.self::$a.',newfrom|id='.$id.',lang='.$v,lang($v),'btno'.$c);}
//$ret.=bj($cb.'|'.self::$a.',refresh|id='.$id.',lang='.$lang,langp('refresh'),'btdel'.$c);
$ret.=bj($cb.',,z|'.self::$a.',redo|id='.$id.',lang='.$lang,langp('refresh'),'btdel'.$c);
return $ret;}

#build
static function build($p){
return parent::build($p);}

static function template(){
//return parent::template();
return '[[[tit:var]*class=tit:div][[txt:var]*class=article:div]*class=paneb:div]';}

static function play($p){
//$p['lg']=ses('mlg',$p['lang']??'');//,ses('lng')
$r=self::build($p); $a=self::$a;
$p['lang']=$r['lang'];
$p['md5']=$r['md5'];
$r['txt']=conn::com($r['txt'],1);
$ret=self::translations($p);
$ret.=gen::com($a::template(),$r); 
$ret=cleansp($ret);
return div($ret,'',self::$cb.'play');}

static function stream($p){
//$p['t']=self::$cols[0];
return parent::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){return parent::com($p);}
static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
self::install();//
return parent::content($p);}

static function api($p){
return parent::api($p);}
}