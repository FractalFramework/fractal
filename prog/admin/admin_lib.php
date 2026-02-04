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
<<<<<<< HEAD
	sql::upd(self::$db[$b],$p,$id);}
=======
<<<<<<< HEAD
	sql::upd(self::$db[$b],$p,$id);}
=======
	sql::up2(self::$db[$b],$p,$id);}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
else $id=sql::sav(self::$db[$b],$p,0,0,1);
if(isset(self::$maj[$id]))unset(self::$maj[$id]);
return $id;}

static function del($p){
<<<<<<< HEAD
[$id,$b,$rid,$ok]=vals($p,['id','b','rid','ok']);
if(!$ok)return bj($rid.'|admin_lib,del|b='.$b.',id='.$id.',ok=1',langp('really?'),'btdel');
=======
<<<<<<< HEAD
[$id,$b,$rid,$ok]=vals($p,['id','b','rid','ok']);
if(!$ok)return bj($rid.'|admin_lib,del|b='.$b.',id='.$id.',ok=1',langp('really?'),'btdel');
=======
$id=$p['id']??''; $b=$p['b']??'';
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
if($id)sql::del(self::$db[$b],$id);
return self::play($p);}

static function update($p){
$id=$p['id']??''; $txt=val($p,'tx'.$id); $rid=$p['rid']??''; $b=$p['b']??'';
<<<<<<< HEAD
sql::upd(self::$db[$b],['txt'=>$txt],$id);
return conn::com($txt,1);}
=======
<<<<<<< HEAD
sql::upd(self::$db[$b],['txt'=>$txt],$id);
return conn::com($txt,1);}
=======
sql::up(self::$db[$b],'txt',$txt,$id);
return conn::com($txt);}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235

static function modif($p){$id=$p['id']??''; $b=$p['b']??''; $rid=$p['rid']??'';
$txt=sql('txt',self::$db[$b],'v',$id);
$ret=bj('md'.$id.'|admin_lib,update|b='.$b.',rid='.$rid.',id='.$id.'|tx'.$id,langp('save'),'btsav');
$ret.=div(textarea('tx'.$id,delbr($txt),60,6));
return div($ret,'','md'.$id);}

//read
static function seecode($p){$id=$p['id']??''; $b=$p['b']??'';
$ret=sql('code',self::$db[$b],'v',$id);
<<<<<<< HEAD
return div(build::code($ret),'paneb');}
=======
<<<<<<< HEAD
return div(build::code($ret),'paneb');}
=======
return div(build::Code($ret),'paneb');}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235

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
<<<<<<< HEAD
	$bt.=div(conn::com($v['txt'],1),'','md'.$id);
=======
<<<<<<< HEAD
	$bt.=div(conn::com($v['txt'],1),'','md'.$id);
=======
	$bt.=div(conn::com($v['txt']),'','md'.$id);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
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

<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
static function pane($p){
[$id,$b]=vals($p,['id','b']);
$d=sql('txt',self::$db[$b],'v',['id'=>$id]);
return conn::com($d,1);}

<<<<<<< HEAD
=======
static function play($p){$rid=$p['rid']??''; $b=$p['b']??''; $ret='';
$r=sql('id,func,vars,txt',self::$db[$b],'rr',['lang'=>ses('lng')]);// order by func
$j='b='.$b.',rid='.$rid;
if($r)foreach($r as $k=>$v){$id=$v['id'];
	$bt=span($v['func'].'('.$v['vars'].')','tit').' ';
	if(auth(6)){
		//$bt.=toggle('md'.$id.'|admin_lib,seecode|'.$j.',id='.$id,langp('view'),'btn');
		$ja='md'.$id.'|admin_lib,seecode|'.$j.',id='.$id;
		$jb='md'.$id.'|admin_lib,pane|id='.$id.',b='.$b;
		$bt.=togbt($ja,$jb,langp('view'),'btn');
		//$bt.=toggle('md'.$id.'|admin_lib,modif|'.$j.',id='.$id,langp('modif'),'btsav');
		$ja='md'.$id.'|admin_lib,modif|'.$j.',id='.$id;
		$bt.=togbt($ja,$jb,langp('modif'),'btsav');
		//$bt.=toggle('md'.$id.'|admin_lib,del|'.$j.',id='.$id,langp('del'),'btdel');
		$ja='md'.$id.'|admin_lib,del|'.$j.',id='.$id;
		$bt.=togbt($ja,$jb,langp('del'),'btdel');}
	$bt.=div(conn::com($v['txt'],1),'paneb','md'.$id);
=======
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
static function play($p){$rid=$p['rid']??''; $b=$p['b']??''; $ret='';
$r=sql('id,func,vars,txt',self::$db[$b],'rr',['lang'=>ses('lng')]);// order by func
$j='b='.$b.',rid='.$rid;
if($r)foreach($r as $k=>$v){$id=$v['id'];
	$bt=span($v['func'].'('.$v['vars'].')','tit').' ';
<<<<<<< HEAD
	if(auth(6)){
		//$bt.=toggle('md'.$id.'|admin_lib,seecode|'.$j.',id='.$id,langp('view'),'btn');
		$ja='md'.$id.'|admin_lib,seecode|'.$j.',id='.$id;
		$jb='md'.$id.'|admin_lib,pane|id='.$id.',b='.$b;
		$bt.=togbt($ja,$jb,langp('view'),'btn');
		//$bt.=toggle('md'.$id.'|admin_lib,modif|'.$j.',id='.$id,langp('modif'),'btsav');
		$ja='md'.$id.'|admin_lib,modif|'.$j.',id='.$id;
		$bt.=togbt($ja,$jb,langp('modif'),'btsav');
		//$bt.=toggle('md'.$id.'|admin_lib,del|'.$j.',id='.$id,langp('del'),'btdel');
		$ja='md'.$id.'|admin_lib,del|'.$j.',id='.$id;
		$bt.=togbt($ja,$jb,langp('del'),'btdel');}
	$bt.=div(conn::com($v['txt'],1),'paneb','md'.$id);
=======
	if(auth(6))$bt.=toggle('md'.$id.'|admin_lib,seecode|'.$j.',id='.$id,langp('view'),'btn');
	if(auth(6))$bt.=toggle('md'.$id.'|admin_lib,modif|'.$j.',id='.$id,langp('modif'),'btsav');
	if(auth(6))$bt.=toggle($rid.'|admin_lib,del|'.$j.',id='.$id,langp('del'),'btdel');
	$bt.=div(conn::com($v['txt']),'pane','md'.$id);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
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