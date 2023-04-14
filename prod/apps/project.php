<?php
class project extends appx{
static $private=0;
static $a='project';
static $db='project';
static $cb='flp';
static $cols=['tit','txt','cl','pub'];
static $typs=['var','bvar','int','int'];
static $conn=0;
static $db2='project_opts';
static $tags=0;
static $open=0;
static $qb='';

//first col,txt,answ,com(settings),code,day,clr,img,nb,cl,pub
//$db2 must use col "bid" <-linked to-> id

function __construct(){
$r=['a','db','cb','cols','db2'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
sql::create(self::$db2,['bid'=>'int','step'=>'var','app'=>'var','appid'=>'var','open'=>'int'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}

//static function subform($r){return parent::subform($r);}

static function newapp($p){$id=$p['id']; $app=$p['app']; $ret='';
	$id=$app::create();
	//$ret=bj('input,app'.$id.'|project,newapp|id='.$id.',app='.$app,langp('new app'),'btn');;
	return $ret;}

static function subform($r){$ret='';
	$applist=sql('com','desktop','rv','where dir like "/apps/public/%" and auth<=2 order by id');
	$ret=hidden('bid',$r['bid']); array_shift($r);
	foreach($r as $k=>$v){
		if($k=='app')$ret.=div(datalist($k,$applist,$v).label($k,lang('app')));
		elseif($k=='appid'){$ret.=div(input($k,$v,'4','',1).label($k,lang('id'))); //$ret.=hidden($k,$v);
			if(!$v)$ret.=popup($v.',com|add=1',langp($v),'btsav').br();}
		elseif($k=='open')
			$ret.=build::toggle(['id'=>$k,'v'=>$v]).label($k,lang('closed'));
		else $ret.=div(input($k,$v,63,$k,'',512));}
return $ret;}

//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
$p['help']=1;
$p['sub']=1;
return parent::edit($p);}

#build
static function build($p){
return parent::build($p);}

static function template(){
//return parent::template();
return '[[(tit)*class=tit:div][(txt)*class=txt:div]*class=paneb:div]';}

static function play($p){
$r=self::build($p);
$rb=sql('id,step,app,appid,open',self::$db2,'rr',['bid'=>$p['id']]); //p($rb);
$ret=div($r['tit'],'txt');//if($v['open'])
//foreach($rb as $k=>$v)$ret.=toggle('|'.$v['app'].',call|id='.$v['appid'],$v['step'],'licon');
foreach($rb as $k=>$v){
	$bt=voc($v['step'],self::$db2.'-step-'.$v['id']);//userlang when saving (todo)
	//$bt=helpx($v['step']);
	$rc[]=toggle('pjm'.$p['id'].'|'.$v['app'].',call|id='.$v['appid'],$bt,'');}
if(isset($rc))$ret.=div(implode('',$rc),'tabs');
return $ret.div('','','pjm'.$p['id']);}

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
}
?>