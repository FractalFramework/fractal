<?php

class code extends appx{
static $private=0;
static $a='code';
static $db='code';
static $cb='cod';
static $cols=['tit','code'];
static $typs=['var','text'];
static $open=1;
static $tags=1;

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){}

#edit
static function collect($p){
return parent::collect($p);}

static function del($p){
return parent::del($p);}

static function save($p){
return parent::save($p);}

static function modif($p){
return parent::modif($p);}

static function form($p){
return parent::form($p);}

static function edit($p){
$p['help']='code_edit';
return parent::edit($p);}

static function create($p){
return parent::create($p);}

#build
static function build($p){
return parent::build($p);}

static function play($p){
$r=self::build($p);
$ret=div($r['tit'],'tit');
$ret.=build::code($r['code'],'');
//$ret.=popup('exec|code='.jurl($r['code']),langp('exec'),'btn');
return $ret;}

static function stream($p){
return parent::stream($p);}

#call (read)
static function tit($p){
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){
return parent::com($p);}

#interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>