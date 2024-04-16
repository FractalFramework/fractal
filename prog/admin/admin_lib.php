<?php

class admin_lib{
static $private=0;
static $db=['','syslib','syscore'];
static $defs=['func'=>'var','vars'=>'var','code'=>'text','txt'=>'text','lang'=>'svar'];
static $maj='';
static $cb='admlb';

static function install(){//$r=array_combine(self::$cols,self::$typs);
sql::create(self::$db[1],self::$defs,0);
sql::create(self::$db[2],self::$defs,);}

//save
static function save($p,$b){
$func=$p['func']??''; $lang=$p['lang']??''; //$b=$p['b']??'';
$id=sql('id',self::$db[$b],'v',['func'=>$func,'lang'=>$lang]);
if($id){
	$txt=sql('txt',self::$db[$b],'v',$id);
	if($txt && !$p['txt'])$p['txt']=($txt);//conn::com
	sql::up2(self::$db[$b],$p,$id);}
else $id=sql::sav(self::$db[$b],$p,0,0,1);
if(isset(self::$maj[$id]))unset(self::$maj[$id]);
return $id;}

static function del($p){
$id=$p['id']??''; $b=$p['b']??'';
if($id)sql::del(self::$db[$b],$id);
return self::play($p);}

static function update($p){
$id=$p['id']??''; $txt=val($p,'tx'.$id); $rid=$p['rid']??''; $b=$p['b']??'';
sql::up(self::$db[$b],'txt',$txt,$id);
return conn::com($txt);}

static function modif($p){$id=$p['id']??''; $b=$p['b']??''; $rid=$p['rid']??'';
$txt=sql('txt',self::$db[$b],'v',$id);
$ret=bj('md'.$id.'|admin_lib,update|b='.$b.',rid='.$rid.',id='.$id.'|tx'.$id,langp('save'),'btsav');
$ret.=div(textarea('tx'.$id,delbr($txt),60,6));
return div($ret,'','md'.$id);}

//read
static function seecode($p){$id=$p['id']??''; $b=$p['b']??'';
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
	$code=str::utf8enc(trim(str::accolades($v)));
	if($func && $code)$rb[]=['func'=>$func,'vars'=>$vars,'code'=>$code,'txt'=>'','lang'=>ses('lng')];}
return $rb;}

//operation
static function reflush($p){$b=$p['b']??1;
self::$maj=sql('id',self::$db[$b],'k',['lang'=>ses('lng')]);
if($b==1)$f='prog/lib.php'; elseif($b==2)$f='prog/core.php';
$r=self::build($f); //pr($r);
if($r)foreach($r as $v)$rb[]=self::save($v,$b);
if(isset(self::$maj))foreach(self::$maj as $k=>$v)sql::del(self::$db[$b],$k);//obsoletes
if(isset($rb))return bj($p['rid'].'|admin_lib,play|'.prm($p),langp('back'),'btn').' '.implode(',',$rb);}

//play
static function docs($p){$rid=$p['rid']??''; $b=$p['b']??''; $ret='';
$r=sql('id,func,vars,txt',self::$db[$b],'rr','where lang="'.ses('lng').'" order by func');
if($r)foreach($r as $k=>$v){$id=$v['id'];
	$bt=tag('h2','',$v['func'].'('.$v['vars'].')');
	$bt.=div(conn::com($v['txt']),'','md'.$id);
	$ret.=div($bt,'paneb');}
return $ret;}

static function menu($p){$rid=$p['rid']??''; $b=$p['b']??''; $ret=''; $j='b='.$b.',rid='.$rid;
if(auth(6))$ret=bj($rid.'|admin_lib,reflush|'.$j,langp('update'),'btn');
if(auth(6))$ret.=bj('popup,,xx|core,mkbcp|b='.self::$db[$b],langp('backup'),'btsav');
if(auth(6))$ret.=bj('popup,,xx|core,rsbcp|b='.self::$db[$b],langp('restore'),'btdel');
$ret.=bj('popup|admin_lib,docs|'.$j,langp('doc'),'btn');
$ret.=bj($rid.'|admin_lib,play|b=1,rid='.$rid,'lib','btn'.active($b,1));
$ret.=bj($rid.'|admin_lib,play|b=2,rid='.$rid,'core','btn'.active($b,2));
//$ret.=bj('popup|admin_lib,play|o=1',langp('view'),'btn');
return $ret;}

static function play($p){$rid=$p['rid']??''; $b=$p['b']??''; $ret='';
$r=sql('id,func,vars,txt',self::$db[$b],'rr','where lang="'.ses('lng').'"');// order by func
$j='b='.$b.',rid='.$rid;
if($r)foreach($r as $k=>$v){$id=$v['id'];
	$bt=span($v['func'].'('.$v['vars'].')','tit').' ';
	if(auth(6))$bt.=toggle('md'.$id.'|admin_lib,seecode|'.$j.',id='.$id,langp('view'),'btn');
	if(auth(6))$bt.=toggle('md'.$id.'|admin_lib,modif|'.$j.',id='.$id,langp('modif'),'btsav');
	if(auth(6))$bt.=toggle($rid.'|admin_lib,del|'.$j.',id='.$id,langp('del'),'btdel');
	$bt.=div(conn::com($v['txt']),'pane','md'.$id);
	$ret.=div($bt,'');}
return $ret;}

//interface
static function content($p){
//self::install();
$p['rid']=randid('dcl'); $p['b']=$p['b']??1;
$bt=self::menu($p);
$ret=self::play($p);
return $bt.div($ret,'board',$p['rid']);}
}

?>