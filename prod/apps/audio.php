<?php
class audio extends appx{
static $private=0;
static $a=__CLASS__;
static $db=__CLASS__;
static $cb='aud';
static $cols=['tit','txt','url','pub'];
static $typs=['var','bvar','var','int'];
static $conn=1;
static $gen=0;
static $open=1;
static $tags=1;
static $qb='';//db

function __construct(){//informe parent
$r=['a','db','cb','cols','conn'];//'db2',
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'var'],1);//id,...,day
parent::install(array_combine(self::$cols,self::$typs));}//id,uid,...,day

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
static function fc_url($k,$v){
$ret=input_label($k,$v,$k);
$ret.=upload::call($k);
return $ret;}

static function form($p){
$p['fcurl']=1;
//$p['bttxt']=1;
return parent::form($p);}

static function edit($p){
//$p['help']=1;
return parent::edit($p);}

#build
static function build($p){
return parent::build($p);}

static function template(){
return '[[tit:var]*class=tit:div][[txt:gen]*class=txt:div]';}

#play
static function play($p){
$r=self::build($p); $a=self::$a;
$usr=usrid($r['uid']);
$u='usr/'.$usr.'/'.$r['url'];
//$ret=gen::com($a::template(),$r);
//$ret=parent::play($p);
$ret=div($r['tit'],'tit');
if($r['txt'])$ret.=div($r['txt'],'txt');
$ret.=audio($u);
return $ret;}

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
//self::install();
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>