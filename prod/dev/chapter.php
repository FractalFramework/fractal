<?php
class chapter{
static $private=2;
static $a='chapter';
static $db='chapter';
static $cb='chp';
static $cols=['tit'];
static $typs=['var'];
static $conn=0;
static $db2='chapter_vals';
static $open=1;

static function install($p=''){
appx::install(array_combine(self::$cols,self::$typs));
sql::create(self::$db2,['bid'=>'int','num'=>'int','tit'=>'var','txt'=>'text'],1);}

static function admin($p){$p['o']='1';
return appx::admin($p);}

static function titles($p){return appx::titles($p);}
static function js(){return '';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){
return appx::collect($p);}

static function del($p){
$p['db2']=self::$db2;
return appx::del($p);}

static function save($p){
return appx::save($p);}

static function modif($p){
return appx::modif($p);}

static function subops($p){
$id=$p['id']??''; $idb=val($p,'idb'); $op=val($p,'op');
$cb=self::$cb; $a=self::$a; $db2=self::$db2;
if($op=='add'){$cols=sql::cols($db2,1,0);
	foreach($cols as $k=>$v)
		if($k=='bid')$rc[$k]=$id; elseif($k=='uid')$rc[$k]=ses('uid'); else $rc[$k]='';
	sql::sav($db2,$rc);}
elseif($op=='del')sql::del($db2,$idb);
elseif($op=='sav'){$cols=sql::cols($db2,3,2);
	$r=valk($p,$cols); sql::up2($db2,$r,$idb);}
return self::subcall($p);}

static function subcall($p){$id=$p['id']??''; $ret='';
$a=self::$a; $cb=self::$cb; $db=self::$db; $db2=self::$db2;
$cols=sql::cols($db2,0,1);
$r=sql('id,'.$cols,$db2,'rr',['bid'=>$id]); //p($r);
$ret.=tag('h3','',lang('chapters'));
if($r)foreach($r as $k=>$v){$bt=ico('edit').' '.$v['tit'];
	$ret.=bj($cb.'edit|'.$a.',subedit|id='.$id.',idb='.$v['id'],$bt,'licon');}
$ret.=bj($cb.'sub|'.$a.',subops|op=add,id='.$id,langp('add'),'btn');
return div($ret,'',$cb.'sub');}

//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['barfunc']='barlabel';
return appx::form($p);}

static function subedit($p){
$id=$p['id']??''; $idb=val($p,'idb');
$a=self::$a; $cb=self::$cb; $j='id='.$id.',bid='.$id.',idb='.$idb;
$r=sql('tit,txt',self::$db2,'ra',$idb);
$ret=bj($cb.'edit|'.$a.',subops|'.$j,langp('back'),'btn');
$ret.=bj($cb.'edit|'.$a.',subops|'.$j.',op=sav|tit,txt',langp('save'),'btsav');
$ret.=bj($cb.'edit|'.$a.',subops|'.$j.',op=del',langp('delete'),'btdel');
$ret.=div(input('tit',$r['tit'],63,lang('chapter'),'',255));
$ret.=divarea('txt',$r['txt'],'article',1);
return $ret;}

static function edit($p){
//$p['collect']=self::$db2;
//$p['help']='model_edit';
$p['sub']=1;
return appx::edit($p);}

static function create($p){
//$p['pub']=0;//default privacy
return appx::create($p);}

#build
static function build($p){
return appx::build($p);}

static function template(){
//return appx::template();
return '[[(tit)*class=tit:div][(txt)*class=txt:div]*class=paneb:div]';}

static function play($p){$a=self::$a; $id=$p['id']??'';
$ra=sql::inner('name,tit',self::$db,'login','uid','ra',$id);
$rb=sql('tit,txt',self::$db2,'rr',['bid'=>$id]);
$ret=gen::com('[[(tit):h1][(name)*class=btit:div]*class=booknfo:div]',$ra);
$ret.=gen::com2('[[[(tit):h3](txt)*class=txt:div]:div]',$rb);
return div($ret,'book');}

static function stream($p){
//$p['t']=self::$cols[0];
return appx::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];
return appx::tit($p);}

static function call($p){
return appx::call($p);}

#com (edit)
static function com($p){
return appx::com($p);}

#interface
static function content($p){
//self::install();
return appx::content($p);}
}
?>