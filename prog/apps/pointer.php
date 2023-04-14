<?php
class pointer extends appx{
static $private=0;
static $a='pointer';
static $db='pointer';
static $cb='ddl';
static $cols=['tit','pub'];
static $typs=['var','int'];
static $conn=0;
static $db2='pointer_cases';
static $db3='pointer_valid';
static $tags=0;
static $open=0;
static $qb='';

//first col,txt,answ,com(settings),code,day,clr,img,nb,cl,pub
//$db2 must use col "bid" <-linked to-> id

function __construct(){
$r=['a','db','cb','cols','db2','conn'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
sql::create(self::$db2,['bid'=>'int','col'=>'var'],1);
sql::create(self::$db3,['cid'=>'int','uid'=>'int'],1);
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
static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}
static function subform($r){return parent::subform($r);}

//form
//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
//$p['help']=1;
$p['sub']=1;
//$p['execcode']=1;
return parent::edit($p);}

#sav
static function register($p){$id=$p['id']??''; $ret='';
$r=sql('id',self::$db2,'rv',['bid'=>$id]);
if($r)foreach($r as $k=>$v)$rs[]=[$v,ses('uid')]; //pr($rs);
if($r)sql::sav2(self::$db3,$rs);
return self::play($p);}

static function pointer($p){$bid=$p['bid']??'';
$id=sql('id',self::$db3,'v',['cid'=>$bid,'uid'=>ses('uid')]);
if(!$id)sql::sav(self::$db3,[$p['bid'],ses('uid')]);
else sql::del(self::$db3,$id);
return self::play($p);}

#build
static function build($p){$id=$p['id']??'';
//$r=sql::join('tit,'.self::$db2.'.id',self::$db,self::$db2,'bid','kr',$id,1);
$r=sql('tit',self::$db,'ra',$id); //pr($r);
return $r;}

static function usrbt($p){$usr=sql('name','login','v',$p['uid']);
return $bt=bubble('profile,call|usr='.$usr.',sz=small',ico('user').$usr,'minicon',1);}

static function play($p){$id=$p['id']??'';
$r=self::build($p); $rc=[];//pr($r);
$ex=sql::join('uid',self::$db3,self::$db2,'cid','v',['bid'=>$id,'uid'=>ses('uid')]);
$ra=sql('id,col',self::$db2,'kv',['bid'=>$id]); //pr($ra);
$rb=sql::join('col,uid,'.self::$db3.'.id,cid',self::$db3,self::$db2,'cid','rr',['bid'=>$id]); //pr($rb);
if($rb)foreach($rb as $k=>$v)$rc[$v['uid']][$v['cid']]=$v['id']; //pr($rc);
if($ra)foreach($ra as $k=>$v)$re['_k'][]=$v; array_unshift($re['_k'],'');//pr($re);
if($rc)foreach($rc as $k=>$v)if($k){//$re[$k][]=$k;
	foreach($ra as $ka=>$va){
		if(isset($v[$ka])){$c='active'; $bt=ico('pointer');} else{$c='disactive'; $bt=ico('close');}
		$re[$k][]=bj(self::$cb.$id.'|pointer,pointer|bid='.$ka.',id='.$id,$bt,'minicon '.$c);}
	if($re[$k])array_unshift($re[$k],self::usrbt(['uid'=>$k]));} //pr($re);
$ret=div($r['tit'],'tit');
$ret.=tabler($re);
if(!$ex)$ret.=bj(self::$cb.$id.'|pointer,register|id='.$id,langp('register'),'btsav');
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