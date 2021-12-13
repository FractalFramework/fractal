<?php

class admin_lib{
static $private=0;
static $db=['','syslib','syscore'];
static $cols=['page','func','vars','code','txt','lang','cat'];
static $typs=['svar','var','var','var','text','var','svar'];
static $maj='';

static function install(){
$r=array_combine(self::$cols,self::$typs);
sqlcreate(self::$db[1],['func'=>'var','vars'=>'var','code'=>'var','txt'=>'text','lang'=>'var'],1);
sqlcreate(self::$db[2],['func'=>'var','vars'=>'var','code'=>'var','txt'=>'text','lang'=>'var'],1);}

//save
static function save($p,$b){
$func=val($p,'func'); $lang=val($p,'lang'); //$b=val($p,'b');
$w='where func="'.$func.'" and lang="'.$lang.'"';
$id=sql('id',self::$db[$b],'v',$w);
if($id){
	$txt=sql('txt',self::$db[$b],'v',$id);
	if($txt && !$p['txt'])$p['txt']=conn::com($txt);
	sqlups(self::$db[$b],$p,$id);}
else $id=sqlsav(self::$db[$b],$p);
if(isset(self::$maj[$id]))unset(self::$maj[$id]);
return $id;}

static function del($p){
$id=val($p,'id'); $b=val($p,'b');
sqldel(self::$db[$b],$id);
return self::play($p);}

static function update($p){
$id=val($p,'id'); $txt=val($p,'tx'.$id); $rid=val($p,'rid'); $b=val($p,'b');
sqlup(self::$db[$b],'txt',$txt,$id);
return conn::com($txt);}

static function modif($p){$id=val($p,'id'); $b=val($p,'b'); $rid=val($p,'rid');
$txt=sql('txt',self::$db[$b],'v',$id);
$ret=bj('md'.$id.'|admin_lib,update|b='.$b.',rid='.$rid.',id='.$id.'|tx'.$id,langp('save'),'btsav');
$ret.=div(textarea('tx'.$id,delbr($txt),60,6));
return div($ret,'','md'.$id);}

//read
static function seecode($p){$id=val($p,'id'); $b=val($p,'b');
$ret=sql('code',self::$db[$b],'v',$id);
return div(build::Code($ret),'paneb');}

//build
static function build($f){$rf=explode('/',$f);
$d=read_file($f); $cat='';
$ra=explode('function ',$d);
foreach($ra as $v){
	$fnc=strto($v,'{');
	$vr=explode('(',$fnc); $func=$vr[0];
	$vars=(isset($vr[1])?substr($vr[1],0,-1):'');
	$code=trim(accolades($v));
	if($code)$rb[]=['func'=>$func,'vars'=>$vars,'code'=>$code,'txt'=>'','lang'=>ses('lng')];}
return $rb;}

//operation
static function reflush($p){$b=val($p,'b');
self::$maj=sql('id',self::$db[$b],'k','where lang="'.ses('lng').'"');
if($b==1)$f='prog/lib.php'; elseif($b==2)$f='prog/core.php';
$r=self::build($f,'lib');
if($r)foreach($r as $v)$rb[]=self::save($v,$b);
if(isset(self::$maj))foreach(self::$maj as $k=>$v)sqldel(self::$db[$b],$k);//obsoletes
if(isset($rb))return implode(',',$rb);}

//play
static function docs($p){$rid=val($p,'rid'); $b=val($p,'b'); $ret='';
$r=sql('id,func,vars,txt',self::$db[$b],'rr','where lang="'.ses('lng').'" order by func');
if($r)foreach($r as $k=>$v){$id=$v['id'];
	$bt=tag('h2','',$v['func'].'('.$v['vars'].')');
	$bt.=div(conn::com($v['txt']),'','md'.$id);
	$ret.=div($bt,'paneb');}
return $ret;}

static function play($p){$rid=val($p,'rid'); $b=val($p,'b'); $ret='';
$r=sql('id,func,vars,txt',self::$db[$b],'rr','where lang="'.ses('lng').'"');// order by func
$j='b='.$b.',rid='.$rid;
if(auth(6))$ret=bj($rid.'|admin_lib,reflush|'.$j,langp('update'),'btn');
if(auth(6))$ret.=bj('popup,,xx|core,mkbcp|b='.self::$db[$b],langp('backup'),'btsav');
if(auth(6))$ret.=bj('popup,,xx|core,rsbcp|b='.self::$db[$b],langp('restore'),'btdel');
$ret.=bj('popup|admin_lib,docs|'.$j,langp('doc'),'btn');
$ret.=bj($rid.'|admin_lib,play|b=1,rid='.$rid,'lib','btn'.act($b,1));
$ret.=bj($rid.'|admin_lib,play|b=2,rid='.$rid,'core','btn'.act($b,2));
//$ret.=bj('popup|admin_lib,play|o=1',langp('view'),'btn');
if($r)foreach($r as $k=>$v){$id=$v['id'];
	$bt=tag('h2','',$v['func'].'('.$v['vars'].')');
	if(auth(6))$bt.=bj('popup|admin_lib,seecode|'.$j.',id='.$id,langp('view'),'btn');
	if(auth(6))$bt.=bj('md'.$id.'|admin_lib,modif|'.$j.',id='.$id,langp('modif'),'btn');
	if(auth(6))$bt.=bj($rid.'|admin_lib,del|'.$j.',id='.$id,langp('del'),'btn');
	$bt.=div(conn::com($v['txt']),'','md'.$id);
	$ret.=div($bt,'paneb');}
return $ret;}

//interface
static function content($p){
//self::install();
$p['rid']=randid('dcl'); $p['b']=val($p,'b',1);
$ret=self::play($p);
return div($ret,'board',$p['rid']);}
}

?>