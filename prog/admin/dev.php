<?php

class dev extends appx{
static $private=4;
static $a='dev';
static $cb='dvdt';

static function js(){return '';}
static function headers(){
head::add('jscode',self::js());
head::add('csscode','');}

static function titles($p){return parent::titles($p);}
static function admin($p){return;}

static function seeCode($p){$f=val($p,'f');
if($f)$ret=file_get_contents(self::opn($f));
return div(build::Code($ret),'');}

static function model($p){$app=$p['app']??'';
$d=read_file(ses('dev').'/x/model.php');
$d=str_replace('model',$app,$d);
return $d;}

static function reinit($p){$app=$p['app']??'';
$d=self::model(['app'=>$app]); $f=self::opn($app);
if(auth(6) && $f && $d)write_file($f,$d);
return self::read(['f'=>$f]);}

static function exists($f,$dr){
$r=dirlist($dr);
foreach($r as $k=>$v)$r[$k]=between($v,'/','.',1,1);
if(in_array($f,$r))return true;}

static function save($p){
$f=val($p,'f'); $d=val($p,'nwc');
if(auth(6) && $f && $d)write_file($f,$d);
return self::read($p);}

static function edit($p){$ret=''; $f=val($p,'f');
if($f)$d=file_get_contents($f); else return '';
$ret=bj('devedit|dev,save|f='.$f.'|nwc',langp('save'),'btsav').br();
$ret.=textarea('nwc',htmlentities($d),60,20,'','console');
return $ret;}

static function create($p){
$new=val($p,'inp1');
$p['f']=ses('dev').'/dev/'.$new.'.php';
$p['nwc']=self::model(['app'=>$new]);
$ex=self::exists($new,ses('dev'));
if(!$ex)$ex=self::exists($new,ses('dev').'/core');
if(!$ex)return self::save($p); else return lang('already exists');}

static function add($p){
$ret=input('inp1','appname','',1);
$ret.=bj('devedit|dev,create||inp1',lang('create'),'btsav').' ';
return $ret;}

static function del($p){
if(!val($p,'ok'))return bj('devedit|dev,del|ok=1,f='.val($p,'f'),lang('really?'),'btdel');
else unlink(val($p,'f')); return;}

//editfunc
static function opn($a){$root='prog'; $dr='';//ses('dev')
$f=$root.'/'.$a.'.php'; if(file_exists($f))$dr='/';
if(!$dr){$r=sesf('scandir_a',$root,1);
	foreach($r as $k=>$v)if(file_exists($root.'/'.$v.'/'.$a.'.php'))$dr=$v.'/';}
if($dr)return $root.'/'.$dr.$a.'.php';}

static function resetfunc($p){$a=val($p,'a'); $fc=val($p,'fc'); $f=self::opn($a);
$d=file_get_contents($f);
$df=innerfunc($d,$fc);
return trim($df);
return textarea('',$df,'','','','console');}

static function savecnfg($p){$f=val($p,'f'); $k=val($p,'k'); $nwc=val($p,'v'.$k,"''");
if($f)$d=file_get_contents($f); else return;
$var=between($d,'$'.$k.'=',';');
$p['nwc']=str_replace('$'.$k.'='.$var.';','$'.$k.'='.$nwc.';',$d);
self::save($p);
return self::editvars($p);}

static function editvars($p){$ret='';
$f=val($p,'f'); $app=between($f,'/','.',1,1);
if($f)$d=file_get_contents($f);
$vr=['private','db','cb','cols','typs','conn','db2','open','qb','tags'];//,'a'
foreach($vr as $v)$rb[$v]=between($d,'$'.$v.'=',';'); //p($rb);
foreach($rb as $k=>$v){
	$ret.=div(input('v'.$k,$v?$v:'0').
	bj('cnf'.$app.'|dev,savecnfg|f='.$f.',k='.$k.'|v'.$k,langpi('save'),'btsav').
	label('v'.$k,$k).hlpbt('modelvar-'.$k));}
return div($ret,'','cnf'.$app);}

static function savefunc($p){$f=val($p,'f'); $fc=val($p,'fc'); $nwc=val($p,'nwc'.$fc);
if($f)$d=file_get_contents($f); else return; //echo $f;
$df=innerfunc($d,$fc); //echo $nwc;
$p['nwc']=str_replace($df,$nwc,$d);
self::save($p);//self::read($p)//
//return textarea('',htmlentities($p['nwc']),'60','20','','console');
return span(lang('ok'),'btok');}

static function editfunc($p){
$f=val($p,'f'); $fc=val($p,'fc'); $app=between($f,'/','.',1,1);
if($f)$d=file_get_contents($f); else return;
$df=innerfunc($d,$fc);
$ret=bj('edtc'.$fc.',,xz|dev,savefunc|f='.$f.',fc='.$fc.'|nwc'.$fc,langp('save').' : '.$fc,'btsav');
$ret.=bj('nwc'.$fc.'|dev,resetfunc|a='.$app.',fc='.$fc.'|nwc'.$fc,langp('restore'),'btdel');
$ret.=bj('nwc'.$fc.'|dev,resetfunc|a=_model,fc='.$fc.'|nwc'.$fc,langp('reset'),'btdel');
$ret.=bj('nwc'.$fc.'|dev,resetfunc|a=appx,fc='.$fc.'|nwc'.$fc,langp('appx'),'btdel');
$ret.=span('','','edtc'.$fc).br();
$ret.=textarea('nwc'.$fc,htmlentities($df),'40','16','','console');
return div($ret,'','edtb'.$fc);}

static function funcs($d){$r=explode('function ',$d);
foreach($r as $k=>$v)$ret[]=substr($v,0,strpos($v,'('));
return $ret;}

static function read($p){$ret='';
$f=val($p,'f'); $app=between($f,'/','.',1,1);
if($app)$_SESSION['afc'][$f]=$app; //echo $app;
if($f)$d=file_get_contents($f); else return '';
$bt=bj('devedit|dev,read|f='.$f,langp($app),'btn').' ';
$bt.=bj('popup|dev,edit|f='.$f,langp('edit'),'btn').' ';
$bt.=bj('popup|dev,seeCode|f='.$app,langp('code'),'btn').' ';
$bt.=bj('devedit|dev,reinit|app='.$app,langp('reset'),'btn').' ';
$bt.=bj('devedit|dev,del|f='.$f,langp('erase'),'btdel').' ';
$bt.=bj('popup|'.$app,langp('load'),'btn').' ';
$bt.=lk('/'.$app,pic('url'),'btn',1).br();
$fs=['js','headers','subform','template','play'];
$rf=self::funcs($d); $rfb=['call','template','play','build','edit','headers'];
$ret.=toggle('edtcnfg|dev,editvars|f='.$f.',a='.$app,lang('config'),'btn').div('','','edtcnfg');
if($rf)foreach($rf as $k=>$v)if($v!='__construct' && strpos($v,'<?')===false && $v){
	if(in_array($v,$rfb))$c='btok'; else $c='btn';
	$ret.=toggle('edt'.$v.'|dev,editfunc|f='.$f.',fc='.$v,$v,$c).div('','','edt'.$v);}
return $bt.div($ret,'','').div('','','appedit');}

static function menu(){$r=dirlist('prog');
foreach($r as $k=>$v){$f=strend($v,'/'); $dr=struntil($v,'/'); $xt=ext($f);
	if($xt=='.php')$rb[$f]=bj('devedit|dev,read|f='.$dr.'/'.$f,struntil($f,'.'),'');}
if($rb)ksort($rb,SORT_STRING);
if($rb)$ret=implode('',$rb);
return div($ret,'list');}

static function call($p){
if(strpos($p['f'],'.')===false)$p['f']=applist::url($p['f']);
$ret=self::read($p);
return div($ret,'','devedit');}

static function content($p){$p['f']=$p['p1']??'';
$bt=popup('dev,add',langp('new'),'btn').' ';
$bt.=bubble('dev,menu',langp('open'),'btn').' ';
$bt.=batch(ses('afc'),'devedit|dev,read|f=$k');
return $bt.div(self::call($p),'board');}
}
?>