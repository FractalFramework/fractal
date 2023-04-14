<?php
class count extends appx{
static $private=2;
static $a='count';
static $db='count';
static $cb='mdl';
static $cols=['tit','number','pub'];
static $typs=['var','int','int'];
static $conn=0;
static $open=0;
static $tags=0;
static $qb='db';

//first col,txt,answ,com(settings),code,day,clr,img,nb,cl,pub
//$db2 must use col "bid" <-linked to-> id

function __construct(){
$r=['a','db','cb','cols'];
foreach($r as $v)parent::$$v=self::$$v;}

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
static function collect($p){
return parent::collect($p);}

static function del($p){
//$p['db2']=self::$db2;
return parent::del($p);}

static function save($p){
return parent::save($p);}

static function modif($p){
return parent::modif($p);}

static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}
static function subform($r){
	$ret=hidden('bid',$r['bid']);
	//$ret.=div(input('chapter',$r['chapter'],63,lang('chapter'),'',512));
	return $ret;}

static function fc_number($k,$v){return hidden($k,$v);}
static function form($p){
//$p['fcnumber']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
//$p['help']=1;
return parent::edit($p);}

#build
static function build($p){
return parent::build($p);}

#add
static function add($p){
$r=self::build($p); $r['id']=$p['id'];
if(self::permission($r['uid'],$r['pub']))$r['number']+=1;
$er=self::modif($r); //if($er)return $er;
return self::play($p);}

static function play($p){
$id=$p['id']; $rid=self::$cb.$id;
$r=self::build($p);
$ret=div($r['tit'],'tit');
if(parent::permission($r['uid'],$r['pub']))
	$bt=bj($rid.'|count,add|id='.$id,$r['number'].picto('add'));
else $bt=$r['number'];
return $ret.div($bt,'bigbt');}

static function stream($p){
//$p['t']=self::$cols[0];
return parent::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
$id=$p['id']; $rid=self::$cb.$id;
return div(parent::call($p),'',$rid);}

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