<?php

class note extends appx{
static $private=0;
static $a='note';
static $db='note';
static $cb='nte';
static $cols=['tit','txt','pub'];
static $typs=['var','text','int'];
static $conn=1;
static $tags=1;
static $open=0;

static function install($p=''){//sql::rn('note','simpletext');
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}
static function js(){return '';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function form($p){return parent::form($p);}
static function del($p){return parent::del($p);}
static function save($p){return parent::save($p);}
static function create($p){return parent::create($p);}
static function modif($p){return parent::modif($p);}
static function edit($p){return parent::edit($p);}

#build
static function build($p){return parent::build($p);}

#play
static function template(){
return parent::template();}

static function play($p){
//$r=self::build($p);
return parent::play($p);}

static function stream($p){
$p['t']=self::$cols[0];
return parent::stream($p);}

#call (read)
static function tit($p){
$p['t']=self::$cols[0];//used is title
return parent::tit($p);}

static function call($p){
return parent::call($p);
return div(self::play($p),'',self::$cb.$p['id']);}

#com (edit)
static function com($p){return parent::com($p);}

#interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>