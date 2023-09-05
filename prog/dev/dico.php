<?php
//more infos on dico2
class dico extends appx{
static $private=0;
static $a='dico';
static $db='dico';
static $cb='mdl';
static $cols=['tit','txt'];
static $typs=['var','bvar'];
static $conn=0;
static $gen=0;
//static $db2='dico_vals';//sub
static $open=0;
static $qb='';//db

//first col,txt,answ,com(settings),code,lang,day,clr,img,nb,cl,pub,edt
//$db2 must use col "bid" <-linked to-> id

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'var'],1);
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
static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//form
//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['bttxt']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
//$p['help']=1;
//$p['sub']=1;
//$p['execcode']=1;
return parent::edit($p);}

static function web($word){
if(!$word)$word='Main Page';
$lg=ses('lng'); $t=rawurlencode($word);
$u='https://'.$lg.'.wiktionary.org/wiki/'.$t;
$d=curl($u);
return $d;}

#build
static function build($p){
return self::web(val($p,'word')); //pr($r);
return parent::build($p);}

static function play($p){
$d=self::build($p);
//$d=decode($d);
//$ret=embed_detect($d,'<ol>');//obs
$ret=between($ret,'<ol>','</ol>');
$ret=strip_tags($ret,''); //eco($ret);
$ret=nl2br($ret);
return div($ret,'pane');
return parent::play($p);}

static function stream($p){
//$p['t']=self::$cols[0];
return parent::stream($p);}

#call (play)
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){
$d=self::build($p);
return between($d,'<ol>','</ol>');//embed_detect
return parent::com($p);}

static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
self::install();//
$rid=randid('yd');
$p['txt']=val($p,'txt',$p['p1']??'');
$ret=input('word',$p['txt']);
$ret.=bj($rid.'|dico,play||word',langp('search'),'btn');
return $ret.div('','board',$rid);
return parent::content($p);}

static function api($p){
return parent::api($p);}
}