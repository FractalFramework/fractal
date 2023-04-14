<?php

class sticker extends appx{
static $private=0;
static $a='sticker';
static $db='sticker';
static $cb='stc';
static $cols=['tit','bkg','clr','txt'];
static $typs=['var','var','svar','text'];
static $tags=1;
static $open=1;
static $conn=1;

function __construct(){
$r=['a','db','cb','cols','conn'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}
static function js(){return '';}
static function headers(){
head::add('csscode','.sticker{font-family:Ubuntu; font-size:24px; text-align:center; padding:40px 20px; background-size:cover; background-position:center center;}
.stickin{vertical-align:middle;}');
head::add('jscode',self::js());}

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
$p['help']='sticker_edit';
return parent::edit($p);}

static function create($p){
return parent::create($p);}

#build
static function build($p){
return parent::build($p);}

static function play($p){
$r=self::build($p);
$s=theme($r['bkg']);
$ret=div($r['txt'],'stickin');
$s.=' color:#'.$r['clr'].';';
return div($ret,'sticker','',$s);}

static function cover($id,$v=[]){
return self::play(['id'=>$id]);}

static function stream($p){$p['cover']=1;
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