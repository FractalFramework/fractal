<?php
class workgroup extends appx{
static $private=0;
static $a='workgroup';
static $db='workgroup';
static $cb='mdl';
static $cols=['tit','txt','nb1','pub'];
static $typs=['var','bvar','int','int'];
static $conn=1;
static $gen=1;
static $db2='workgroup_usrs';
static $db3='workgroup_vote';
static $tags=0;
static $open=0;
static $qb='db';

static function install($p=''){
sql::create(self::$db3,['bid'=>'int','cid'=>'int','uid'=>'int','vote'=>'int'],1);
sql::create(self::$db2,['bid'=>'int','uid'=>'int','competence'=>'bvar','score'=>'int'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}

static function js(){return 'function barlabel(v,id){var d="";
	var r=["","broken","bad","works","good","new","","",""];
	inn(r[v],id);}';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){
$p['pub']=0;//default privacy
return parent::create($p);}

#subcall
//used in secondary database db2
static function subops($p){$p['t']='competence'; return parent::subops($p);}//$p['bt']='';
static function subform($r){return parent::subform($r);}
static function subedit($p){$p['t']='competence'; return parent::subedit($p);}//$p['data-jb']//2nd save
static function subcall($p){$p['t']='competence'; return parent::subcall($p);}//$p['bt']='';

#form
/*static function fc_txt($k,$val,$v){
return textarea($k,$val,40,strlen($val)>500?26:16,'','',$v=='var'?512:0);}*/

static function form($p){
//$p['html']='txt';//contenteditable for txt
//$p['fctxt']=1;//form col call fc_tit();
//$p['bttxt']=1;//label for txt;
//$p['barfunc']='barlabel';//js function for bar()
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
$p['help']=1;
$p['sub']=1;
//$p['execcode']=1;
//$p['bt']='';
return parent::edit($p);}

static function score($p){
return;}

#sav
static function vote($p){
if($p['vote']=='ok'){$p['op2']=1;
	sql::sav(self::$db3,[$p['id'],$p['cid'],ses('uid'),1]);}
elseif($p['vote']=='ko'){
	sql::del(self::$db3,['bid'=>$p['id'],'cid'=>$p['cid'],'uid'=>ses('uid')]);}
$score=sql('count(id)',self::$db3,'v',['bid'=>$p['id'],'cid'=>$p['cid']]);
sql::upd(self::$db2,['score'=>$score],$p['cid']);
return self::call($p);}

static function participation($p){
if($p['subscribe']=='ok'){$p['op1']=1;
	sql::sav(self::$db2,[$p['id'],ses('uid'),'',1]);}
elseif($p['subscribe']=='ko'){
	sql::del(self::$db2,['bid'=>$p['id'],'uid'=>ses('uid')]);
	sql::del(self::$db3,['bid'=>$p['id'],'uid'=>ses('uid')]);}
return self::call($p);}

#bt
static function vote_bt($r,$id){
$ok=sql('id',self::$db3,'v',['bid'=>$id,'cid'=>$r['id'],'uid'=>ses('uid')]);
$j=self::$cb.'|workgroup,vote|id='.$id.',cid='.$r['id'];
$nm=usrid($r['uid']).' ('.langnb('vote',$r['score']).')';
if($r['uid']==ses('uid'))return span($nm,'btn');
elseif(!$ok)return bj($j.',vote=ok',langp('vote').' '.lang('for',1).' '.$nm,'btsav').' ';
else return bj($j.',vote=ko',langp('unvote').' '.lang('for',1).' '.$nm,'btdel').' ';}

static function subscribe_bt($id){
$ok=sql('id',self::$db2,'v',['bid'=>$id,'uid'=>ses('uid')]);
$j=self::$cb.'|workgroup,participation|id='.$id;
if(!$ok)return bj($j.',subscribe=ok',langp('subscribe'),'btsav').' ';
else return bj($j.',subscribe=ko',langp('unsubscribe'),'btdel').' ';}

#build
static function build($p){
$ra=parent::build($p); $id=$p['id']??'';
$rb=sql('all',self::$db2,'rr','where bid="'.$id.'" order by score desc');
return [$ra,$rb];}

static function template(){
return '[[(tit)*class=tit:div][[txt:var]*class=txt:div]*class=paneb:div]';}

#play
static function play($p){$ret=''; $id=$p['id']; $uid=ses('uid');
[$ra,$rb]=self::build($p); $n=count($rb); $free=$ra['nb1']-$n;
$ret=gen::com(self::template(),$ra);
$bt=langnb('participant',$n).' / '.langnb('freesit',$free);
$ret.=tag('h2','',ico('users').' '.$bt);
if($uid=ses('uid'))$ret.=self::subscribe_bt($id);
if($rb)foreach($rb as $k=>$v)if($k<$ra['nb1']){
	$ret.=self::vote_bt($v,$id);
	$ret.=profile::standard(['uid'=>$v['uid'],'sz'=>'big']);}
return $ret;}

//static function cover($id){}

static function stream($p){
//$p['t']=self::$cols[0];
//$p['cover']=1;
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
self::install();
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>