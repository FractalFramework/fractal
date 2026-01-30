<?php

class devnote extends appx{
static $private=0;
static $a='devnote';
static $db='devnote';
static $cb='dvn';
static $cols=['tit','txt'];
static $typs=['var','text'];
static $open=1;

static function install($p=''){
sql::create(self::$db,['uid'=>'int','tit'=>'var','txt'=>'text'],1);}

static function admin($p){$p['o']='0';
return parent::admin($p);}

static function js(){return '';}

static function headers(){
head::add('csscode','.txt textarea{width:100%;}');
head::add('jscode',self::js());}

#operations
static function del($p){
return parent::del($p);}

static function modif($p){
return parent::modif($p);}

static function save($p){
return parent::save($p);}

static function create($p){
return parent::create($p);}

#editor
static function form($p){
return parent::form($p);}

static function edit($p){
return parent::edit($p);}

#reader
static function build($p){
return parent::build($p);}

static function play($p){
//$r=self::build($p);
return parent::play($p);}

static function stream($p){$ret=''; //return parent::stream($p);
$dsp=ses('devnotedsp',$p['display']??''); $uid=ses('uid');
$r=sql('id,uid,tit,dateup',self::$db,'rr','order by id desc limit 100');
$c=$dsp==1?'bicon':'licon';
if($r)foreach($r as $k=>$v){
	$tit=$v['tit']?$v['tit']:$v['id']; 
	$bt=span(ico('file-o')).span($tit.' '.$v['date'],'date');
	if($v['uid']==$uid)$app='edit'; else $app='call';
	$ret.=bj(self::$cb.'|devnote,'.$app.'|id='.$v['id'],div($bt));}
return div($ret,$c);}

/*static function stream0($p){
return parent::stream($p);}*/

#interfaces
static function tit($p){
$p['t']='tit';
return parent::tit($p);}

static function template(){
return parent::template();}

//call
static function call($p){
$p['conn']=0;
return parent::call($p);}

//com (apps)
static function com($p){
return parent::com($p);}

//interface
static function content($p){
//self::install();
return parent::content($p);}
}

?>