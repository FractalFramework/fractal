<?php
class gold extends appx{
static $private=0;
static $a='gold';
static $db='gold';
static $cb='mdl';
static $cols=['tit','txt','pub'];
static $typs=['var','bvar','int'];
static $conn=0;
static $db2='gold_vals';
static $open=0;
static $qb='';//db

//first col,txt,answ,com(settings),code,day,clr,img,nb,cl,pub
//$db2 must use col "bid" <-linked to-> id

function __construct(){
$r=['a','db','cb','cols','db2','conn'];
foreach($r as $v)appx::$$v=self::$$v;}

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'var'],1);
appx::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return appx::admin($p);}
static function titles($p){return appx::titles($p);}
static function js(){return;}
static function headers(){
head::add('jscode',self::js());}

#edit
static function collect($p){return appx::collect($p);}
static function del($p){
//$p['db2']=self::$db2;
return appx::del($p);}

static function save($p){return appx::save($p);}
static function modif($p){return appx::modif($p);}
static function create($p){
//$p['pub']=0;//default privacy
return appx::create($p);}

//subcall
static function subops($p){return appx::subops($p);}
static function subedit($p){return appx::subedit($p);}
static function subcall($p){return appx::subcall($p);}
static function subform($r){return appx::subform($r);}

//form
//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['barfunc']='barlabel';
return appx::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
//$p['help']=1;
//$p['sub']=1;
//$p['execcode']=1;
return appx::edit($p);}

#build
static function build($p){
return appx::build($p);}

static function template(){
//return appx::template();
return '[[(tit)*class=tit:div][(txt)*class=txt:div]*class=paneb:div]';}

static function play($p){
//$r=self::build($p);
return appx::play($p);}

static function stream($p){
//$p['t']=self::$cols[0];
return appx::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];
return appx::tit($p);}

static function call($p){
return appx::call($p);}

#com (edit)
static function com($p){return appx::com($p);}
static function uid($id){return appx::uid($id);}
static function own($id){return appx::own($id);}

#interface
static function content($p){
self::install();//
return appx::content($p);}

static function api($p){
return appx::api($p);}
}
?>