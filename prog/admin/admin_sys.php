<?php

class admin_sys{
static $private=4;
static $db='sys';
static $maj='';

static function install(){
sqlcreate(self::$db,['dir'=>'var','app'=>'var','func'=>'var','vars'=>'var','code'=>'bvar','txt'=>'bvar','lang'=>'svar'],1);}

//edit
/*static function edit(){
$ret=form::com(['table'=>self::$db]);}*/

static function del($p){
sqldel(self::$db,val($p,'id'));}

//save
static function save($p){
$app=val($p,'app'); $func=val($p,'func'); $lang=val($p,'lang');
$w='where app="'.$app.'" and func="'.$func.'" and lang="'.$lang.'"';
$id=sql('id',self::$db,'v',$w);
if($id){
	$txt=sql('txt',self::$db,'v',$id);
	if($txt && !$p['txt'])$p['txt']=$txt;
	sqlups(self::$db,$p,$id);}
else $id=sqlsav(self::$db,$p);
if(isset(self::$maj[$id]))unset(self::$maj[$id]);
return $id;}

static function update($p){
$id=val($p,'id'); $txt=val($p,'tx'.$id); $rid=val($p,'rid');
sqlup(self::$db,'txt',trim($txt),$id);
return self::modif($id,$txt,$rid,val($p,'app'));}

static function modif($id,$txt,$rid,$app=''){
if(auth(6))$ret=textarea('tx'.$id,$txt,40,4);
else $ret=div($txt,'pane');
if(auth(6))$ret.=bj($rid.',,z|admin_sys,update|id='.$id.',rid='.$rid.'|tx'.$id,pic('save'),'btsav');
if(auth(6))$ret.=bj($rid.'|admin_sys,del|app='.$app.',id='.$id,pic('del'),'btdel');
$ret.=toggle('cod'.$rid.'|admin_sys,seecode|id='.$id,pic('view'),'btn');
return $ret.div('','','cod'.$rid);}

//build
static function mklistfunc($r,$f,$app){
$rf=explode('/',$f); $lg=ses('lng');
$rb[]=['dir'=>$rf[1],'app'=>$app,'func'=>'Abstract','vars'=>'','code'=>'','txt'=>helpx($app.'_app'),'lang'=>$lg];
foreach($r as $k=>$v){
	$fnc=trim(strto($v,'{'));
	if(!$fnc){echo $v; return;}
	if($fnc && substr($fnc,0,4)!='class' && substr($fnc,0,8)!='static $'){
		$vr=explode('(',$fnc); $func=$vr[0];
		$vars=(isset($vr[1])?substr($vr[1],0,-1):'');
		$na=substr_count($v,'{'); $nb=substr_count($v,'}');//verif balance
		if($na!=$nb){$nc=strrpos($v,'}'); $v=substr($v,0,$nc);}
		$na=substr_count($v,'{'); $nb=substr_count($v,'}');
		if($na!=$nb){echo $app.'/'.$fnc.':'.$na.'-'.$nb.br(); return;}
		$code=trim(accolades($v));
		if($code)$rb[]=['dir'=>$rf[1],'app'=>$app,'func'=>$func,'vars'=>$vars,'code'=>$code,'txt'=>'','lang'=>$lg];}}
return $rb;}

//dirs
static function build($f,$app){
$d=read_file($f);
$d=str_replace(['<?php','?>','class '.$app.'{','class '.$app.' extends appx{'],'',$d);
if($app=='core')$d=str_replace('val($p,\'p\'));}}','val($p,\'p\'));}',$d);//patch
if($app=='lib' or $app=='core')$spliter='function '; else $spliter='static function ';
$ra=explode($spliter,$d); //pr($ra);
$rb=self::mklistfunc($ra,$f,$app);
return $rb;}

static function reflush_lib($app){
$f='prog/'.$app.'.php';
$r=self::build($f,$app);
if($r)foreach($r as $v)$rb[]=self::save($v);
if(isset($rb))return implode(',',$rb);}

static function reflush($p){
$rid=val($p,'rid'); $app=val($p,'app'.$rid); if(!$app)return;
$f=val($p,'f'); if(!$f)$f=unit::locate($app);
$r=self::build($f,$app); //pr($r);
if($r)foreach($r as $v)$rb[]=self::save($v);
if(isset($rb))return implode(',',$rb);}

//batch
static function batch($dir){$dr=ses('dev');//.'/'.$dir
//$r=applist::appdirs(); pr($r);
$r=scan_dir($dr); //pr($r);
if($r)foreach($r as $k=>$v){
	if(is_file($dr.'/'.$v))$rb[]=self::reflush(['app'=>substr($v,0,-4),'f'=>$dr.'/'.$v]);
	elseif(is_dir($dr.'/'.$v)){
		$ra=read_dir($dr.'/'.$v);
		if($ra)foreach($ra as $va)if(is_file($dr.'/'.$v.'/'.$va))
			$rb[]=self::reflush(['app'=>substr($va,0,-4)]);}}
if(isset($rb))return implode(' ',$rb);}

//operation
static function pushall($p){$ret='';
self::$maj=sql('id',self::$db,'k','where lang="'.ses('lng').'"');
/*$ret=self::batch('admin');
$ret.=self::batch('apps');
$ret.=self::batch('com');
$ret=self::batch('core');
$ret.=self::batch('tlex');
$ret.=self::batch('mac');*/
$ret.=self::batch('');
$ret.=self::reflush_lib('lib');
$ret.=self::reflush_lib('core');
foreach(self::$maj as $k=>$v)sqldel(self::$db,$k);//obsoletes
return $ret;}

//menu
static function menu($rid){
$r=sql('distinct(app)',self::$db,'rv','where lang="'.ses('lng').'" order by app'); //sort($r);
return select('app'.$rid,$r,'',1);}

//call
static function seecode($p){$id=val($p,'id');
$ret=sql('code',self::$db,'v',$id);
return div(build::Code($ret),'paneb');}

static function play($p){$rid=val($p,'rid'); $app=val($p,'app'.$rid); if(!$app)return;
$w='where app="'.$app.'" and lang="'.ses('lng').'" order by id';
$bt=bj($rid.'|admin_sys,reflush|app='.$app,langp('update'),'btn'); if(!auth(6))$bt='';
$r=sql('id,func,vars,txt',self::$db,'rr',$w);
foreach($r as $k=>$v){$rid=randid('tx');
	$e=self::modif($v['id'],$v['txt'],$rid,$app);
	if($v['id']=='Abstract')$kb=$k;
	$r[$k]['txt']=div($e,'',$rid);}
//if(isset($kb) && array_key_exists($kb,$r)){$rb=$r[$kb]; unset($r[$kb]); array_unshift($r,$rb);}
return $bt.tabler($r);}

static function call($p){
$p['app']=val($p,'app','lib');
$ret=tag('h1','',$p['app']);
$ret.=self::play($p);
return div($ret,'board');}

//interface
static function content($p){
self::install();
$rid=randid('dcl');
$bt=self::menu($rid);
//$bt.=input('app','','10',1);
$bt.=bj($rid.',,y|admin_sys,play|rid='.$rid.'|app'.$rid,langp('view'),'btn');
if(auth(6)){
	$bt.=bj($rid.'|admin_sys,reflush|rid='.$rid.'|app'.$rid,langp('update'),'btn');
	$bt.=bj($rid.',,z|admin_sys,pushall|dir=app,rid='.$rid,langp('update all'),'btn');
	$bt.=bj('popup,,xx|core,mkbcp|b='.self::$db,langp('backup'),'btsav');}
return $bt.div('','board',$rid);}
}