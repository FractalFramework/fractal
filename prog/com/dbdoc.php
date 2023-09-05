<?php
class dbdoc extends appx{
static $private=0;
static $a='dbdoc';
static $db='dbdoc';
static $cb='mdl';
static $cols=['tit','file','txt','pub','edt'];
static $typs=['var','var','long','int','int'];
static $conn=1;
static $gen=0;
//static $db2='dbdoc_vals';//sub
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

static function getfile($p){
$f='usr/'.ses('usr').'/'.val($p,'f');
if(is_file($f))return get_file($f);
else return help('error');}

static function redo($p){
$d=self::getfile($p);
sql::up(self::$db,'txt',$d,$p['id']);
return self::edit($p);}

static function save($p){
$p['txt']=self::getfile($p);
return parent::save($p);}

static function modif($p){
//$p['txt']=self::getfile($p);
return parent::modif($p);}

static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}
static function subform($r){return parent::subform($r);}

//form
static function fc_txt($k,$val,$v){
$ret=textarea($k,$val,40,26);
return $ret;}

static function form($p){
//$p['html']='txt';
$p['btfile']=bj(self::$cb.'|dbdoc,redo|id='.$p['id'].',f='.$p['file'],langp('redo'),'btn');
$p['fctxt']=1;
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
$p['help']=1;
//$p['sub']=1;
//$p['execcode']=1;
return parent::edit($p);}

#build
static function build($p){
return parent::build($p);}

static function template(){
//return parent::template();
return '[[[tit:var]*class=tit:div][[txt:gen]*class=txt:div]*class=paneb:div]';}

static function play($p){
//$r=self::build($p);
return parent::play($p);}//will use template

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
?>