<?php

class urgency extends appx{
static $private=0;
static $a='urgency';
static $db='urgency';
static $cb='smcbk';
static $cols=['tit','txt','sky'];
static $typs=['var','bvar','svar'];
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

static function js(){return '';}

static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#operations
static function del($p){return parent::del($p);}
static function modif($p){return parent::modif($p);}
static function save($p){return parent::save($p);}
static function create($p){return parent::create($p);}

static function fc_sky($k,$v){$ret='';
//$r=['','sunset','evening','blue','automn','red','orange','green','sea','purple','night'];
$r=sql('tit','sky','rv','where uid="'.ses('uid').'" or pub>2');
return select($k,$r,$v,1);}
static function form($p){
$p['fcsky']=1;
return parent::form($p);}

#editor
static function edit($p){return parent::edit($p);}

#reader
static function stream($p){return parent::stream($p);}
static function build($p){return parent::build($p);}

static function play($p){$c='';
if(parent::own($p['id']))$bt=div(popup('urgency|edit=1,id='.$p['id'],ico('edit')),'right');
$ret=self::build($p);
if($ret['sky'])$c='skytxt sky_'.$ret['sky'];
return div($ret['txt'],'urgency '.$c);}

#interfaces
static function tit($p){
return parent::tit($p);}

//call (connectors)
static function call($p){
$p['conn']=1; 
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