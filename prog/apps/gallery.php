<?php
class gallery extends appx{
static $private=0;
static $a='gallery';
static $db='gallery';
static $cb='mdl';
static $cols=['tit','img','pub'];
static $typs=['var','var','int'];
static $conn=1;
static $gen=0;
//static $db2='gallery_vals';//sub
static $open=0;
static $tags=1;
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

//subcall
static function subops($p){return parent::subops($p);}
static function subform($r){return parent::subform($r);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}

//form
static function fc_img($k,$val){return textarea($k,$val,40,12,'','',1000).pickim($k,2);}
static function form($p){$p['fcimg']=1; return parent::form($p);}

static function edit($p){return parent::edit($p);}

#build
static function build($p){return parent::build($p);}

static function template(){
return '[[[tit:var]*class=tit:div][[img:var]*class=txt:div]*class=paneb:div]';}

#play
static function play($p){
//$r=self::build($p);
return parent::play($p);}

static function stream($p){
return parent::stream($p);}

#call (read)
static function tit($p){return parent::tit($p);}
static function call($p){return parent::call($p);}

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