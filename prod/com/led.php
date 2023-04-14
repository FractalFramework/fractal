<?php
class led extends appx{
static $private=0;
static $a=__CLASS__;
static $db='led';
static $cb='mdl';
static $cols=['tit','txt'];
static $typs=['svar','svar'];
static $open=1;//1=open,2=preview,3=iframe,4=link
static $conn=1;
static $gen=0;
static $db2='';//led_r
static $tags=0;
static $qb='';//db

//first col,txt,answ,com(settings),code,lang,day,clr,img,nb,cl,pub,edt
//$db2 must use col "bid" <-linked to-> id

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
static function subform($p){return parent::subform($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}

//form
//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['bttxt']=1;
//$p['barfunc']='barlabel';
//$p['labeltit']='title';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
//$p['help']=1;
//$p['sub']=1;
//$p['execcode']=1;
//$p['bt']='';
return parent::edit($p);}

#build
static function build($p){
return sql('tit,txt',self::$db,'ra',$p['id']);}

static function digit($n=8,$p=0){$x=130*$p; $rc=[];
$r=[1=>'0101000',2=>'0110111',3=>'0101111',4=>'1101010',5=>'1001111',6=>'1011111',7=>'0101100',8=>'1111111',9=>'1101111',0=>'1111101'];
$r=str_split($r[$n]); foreach($r as $k=>$v)$rc[]=$v?'black':'silver';
$ret='
['.$rc[0].':attr][#gr1,'.($x).',10:use]
['.$rc[1].':attr][#gr1,'.($x+80).',10:use]
['.$rc[2].':attr][#gr1,'.($x).',90:use]
['.$rc[3].':attr][#gr1,'.($x+80).',90:use]
['.$rc[4].':attr][#gr2,'.($x+5).',5:use]
['.$rc[5].':attr][#gr2,'.($x+5).',85:use]
['.$rc[6].':attr][#gr2,'.($x+5).',165:use]';
return $ret;}

#play
static function play($p){
$r=self::build($p); $a=self::$a;
$d='
[*[gr1*[10/10 20/20 20/70 10/80 0/70 0/20 10/10:polygon]:group]:defs]
[*[gr2*[10/10 20/0 70/0 80/10 70/20 20/20 10/10:polygon]:group]:defs]
[#cccccc:attr][0,0,600,190:rect]';
$rb=str_split($r['txt']);
foreach($rb as $k=>$v)$d.=self::digit($v,$k);
$ret=svg::com($d,500,190,$r['tit']);
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
self::install();//
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>