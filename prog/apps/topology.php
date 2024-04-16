<?php
class topology extends appx{
static $private=0;
static $a='topology';
static $db='topology';
static $cb='mdl';
static $cols=['tit','txt','pub'];
static $typs=['var','bvar','int'];
static $conn=1;
static $gen=0;
static $db2='topology_vals';
static $db3='topology_tags';
static $tags=1;
static $open=0;
static $qb='db';

static function install($p=''){
//sql::create(self::$db3,['cid'=>'int','bid'=>'int','val'=>'var'],1);
sql::create(self::$db2,['bid'=>'int','uid'=>'int','root'=>'bvar','element'=>'text','tag'=>'int'],1);
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
static function create($p){$p['pub']=0; return parent::create($p);}

#subcall
static function subops($p){$p['t']='root'; return parent::subops($p);}//$p['bt']='';
static function subform($r){return parent::subform($r);}//$r['html']='element';
static function subedit($p){$p['t']='root'; return parent::subedit($p);}//$p['data-jb']
static function subcall($p){$p['t']='root'; return parent::subcall($p);}//$p['bt']='';

#form
/*static function fc_txt($k,$val,$v){
return textarea($k,$val,40,strlen($val)>500?26:16,'','',$v=='var'?512:0);}*/

static function form($p){
//$p['html']='txt';
//$p['fctxt']=1;
//$p['bttxt']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
$p['help']=1;
$p['sub']=1;
//$p['execcode']=1;
//$p['bt']='';
return parent::edit($p);}

static function editopo($p){$ret='';
$id=$p['id']??''; $t=val($p,'inpt'); $o=val($p,'o'); $rid=randid('tpl');
if($t)sql::up(self::$db2,'root',$t,$id);
$t=sql('root',self::$db2,'v',$id);
$j=$rid.'|topology,editopo|id='.$id;
if($o)$ret=inputcall($j.'|inpt','inpt',$t,22).bj($j,pic('cancel',12),'btn');
else $ret=bj($j.',o=1',pic('edit',12),'');
return span($ret,'',$rid);}

#build
static function build($p){
$ra=parent::build($p); $id=$p['id']??'';
$rb=sql('all',self::$db2,'rr',['bid'=>$id]);
return [$ra,$rb];}

static function template(){
return '[[(tit)*class=tit:div][[txt:var]*class=txt:div]*class=paneb:div]';}

static function topo($r,$f){$ret='';
if($r)foreach($r as $k=>$v)$ret.=$f($k,$v);
return $ret;}

static function root($p){
$dir=$p['dir']; $cuid=$p['cuid']??''; $bid=$p['bid'];
$r=sql('all',self::$db2,'rr',['bid'=>$bid]);
if($r)foreach($r as $k=>$v){
	$ic='folder';//$ic=icon_ex(substr($v['root'],1));
	//$v['root']=substr($v['root'],1);
	$rb[$v['id']]=[$v['root'],'in','topology,playobj|id='.$v['id'],$ic,$v['id'],'',''];}
if($r)return $rb;}

static function playobj($p){$id=$p['id']??'';
$r=sql('all',self::$db2,'ra',['id'=>$id]); $bt='';
//$ret=gen::com('[[[element:var]*class=txt:div]*class=paneb:div]',$r);
if($r['uid']==ses('uid'))$bt=self::editopo(['id'=>$id]);
$ret=div('#'.$r['id'].'. '.$r['element'].' '.$bt,'paneb');
return $ret;}

#play
static function play($p){
$id=$p['id']??'';
[$ra,$rb]=self::build($p);
$ret=gen::com(self::template('tit','txt'),$ra);
//$ret='';
//$ret.=desk::load('topology','root','','',$id);
$ret.=tree::load('topology','root','','',$id);
return $ret;}

//static function cover($id){}

static function stream($p){
//$p['t']=self::$cols[0];
//$p['cover']=1;
return parent::stream($p);}

#call
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com
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