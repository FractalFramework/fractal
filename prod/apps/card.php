<?php

class card extends appx{
static $private=0;
static $a='card';
static $db='card';
static $cb='cdcbk';
static $cols=['person','corporation','status','address','mail','site','phone','pub'];
static $typs=['var','var','var','var','var','var','var','int'];
static $conn=1;
static $open=1;
static $tags=0;

function __construct(){
$r=['a','db','cb','cols'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function js(){return '';}

static function headers(){
head::add('csscode','');
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

static function form($p){
return parent::form($p);}

#editor	
static function edit($p){
return parent::edit($p);}

#reader
static function build($p){$id=$p['id']??'';
$cols=implode(',',self::$cols);
$r=sql($cols,self::$db,'ra',$id);
return $r;}

static function stream($p){
$p['t']='person';
return parent::stream($p);}

#interfaces
static function tit($p){
$p['t']='person';
return parent::tit($p);}

//template
static function template(){
return '[(card)[[(person):b]*class=cstitle:div]
[[(corporation)*class=csfunction:span] - [(status)*class=csname:span]:div]
[(address)*class=cssite:div]
[(mail)*class=csinfos:div]
[(phone)*class=csinfos:div]
[[(url)*(site):a]*class=cssite:div]
*class=paneb cscard:div]';}

//call
static function play($p){
$r=self::build($p); 
$r['card']=ico('vcard-o',32);
$r['url']=http($r['site']);
$r['site']=nohttp($r['site']);
$template=self::template();
$ret=gen::com($template,$r);
//$ret=conn::call(['msg'=>$ret,'ptag'=>0]);
return $ret;}

static function call($p){
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