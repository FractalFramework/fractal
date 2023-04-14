<?php
class admin{

//logon
//$r[]=['root','mode','app','ico','name'];//modes: '',pop,pag,j,lk,in,cb,bub,img,lkt,js
static function about($a){
$root=ses::$cnfg['site']; //if(!$root)$root='fractal';
$r[]=[$root,'lk','/','','home'];
//if($a)$r[]=[$root,'cb','core,help|ref='.$a.',conn=1',$a,$a];
//$r[]=[$root,'cb','core,help|ref=features,conn=1','art','features'];
//$r[]=[$root,'cb','core,help|ref=fractal,conn=1','fractal','Fractal'];
$r[]=[$root,'j','cbck|core,help|ref=mac_welcome,conn=1,css=article','mac','welcome'];
$rb=lngs();//['en','es','fr'];
foreach($rb as $k=>$v)$r[]=[$root.'/lang','j','returnVar,lng,reload|core,lang_set|lang='.$v,'flag',$v];
//if(auth(2))$r[]=[$root.'/apps','in','applist,menuapp','','apps'];
//else $r[]=[$root,'cb','applist,showroom','art','applist'];
$r[]=[$root.'/about','cb','core,help|ref=confidentiality,conn=1','info','confidentiality'];
//$r[]=[$root.'/about','cb','multilang,call|id=b2a8c40049','art','developpers'];
//$r[]=[$root.'/about','cb','art,call|id=5','art','developpers'];
//$r[]=[$root,'cb','download','download','Fractal']; //http://logic.ovh/download
$r[]=[$root.'/utils','','txt','','txt'];
$r[]=[$root.'/utils','','pad','','pad'];
$r[]=[$root.'/utils','','conn','','conn'];
$r[]=[$root.'/utils','','replace','','replace'];
$r[]=[$root.'/utils','','convert','','convert'];
$r[]=[$root.'/utils','','keygen','','keygen'];
if(auth(2))$r[]=[$root.'/utils','','explorer','','explorer'];
if(auth(6))$r[]=[$root.'/utils','','exec','','exec'];
$r[]=[$root.'/apps','in','loadapp,com|tg=cbck','','loadapp'];
$r=applist::menuapp2($r,$root);//if(auth(2))
//if(auth(2))
//$r[]=[$root.'/apps','in','applist,menuapp','','apps'];
/*$r[]=[$root.'/tlex','cb','art|id=6','tlex','welcome'];
$r[]=[$root.'/tlex','cb','contact','','contact'];*/
if(auth(2))$r[]=[$root.'/about','cb','tickets','','tickets'];
$r[]=[$root.'/about','cb','devnote','','devnote'];
$r[]=[$root.'/about','cb','contact','','contact'];
//$r[]=[$root,'cb','core,help|ref=credits,conn=1','info','credits'];
$r[]=[$root.'/about','cb','paypal','money','donation'];
$r[]=[$root.'/about','cb','core,help|ref=legals,conn=1','','legals'];
return $r;}

//logoff
static function badger($p){$usr=$p['usr']??'';
$r=sql('name','login','rv','where mail="'.ses('mail').'" and auth>1 order by name');
foreach($r as $v){//$rb[]=bj('bdg|admin,badger_switch|usr='.$v,$v,'');
	$rb[$v]=bj('reload,bdg,loged_ok|login,badger|user='.$v,pic('user').$v,'');} //ksort($rb,SORT_NATURAL);
$ret=div(implode('',$rb),'');
if($usr)$ret.=password('psw','').bj('bdg|login|psw',lang('login'),'btsav');//
$ret.=div('','','bdg');
return $ret;}

static function dev($r){$dev=ses('dev');
if($dev=='prog'){
	//if(auth(2))$r[]=[$dev.'/apps','in','applist,menuapp','','apps'];
	$r[]=[$dev.'','j','ses,,reload||k=dev,v=prod','','prod'];
	if(auth(6)){$r[]=[$dev.'','pop','admin_lang','','lang'];
		$r[]=[$dev,'pop','admin_icons','','pictos'];
		$r[]=[$dev,'pop','admin_help','help','helps'];
		$r[]=[$dev,'pop','admin_labels','','labels'];
		$r[]=[$dev.'/sys','pop','admin_conn','','conn'];
		$r[]=[$dev.'/sys','pop','admin_sql','','sql'];}
	if(auth(4))$r[]=[$dev.'/sys','pop','admin_sys','','sys'];
	if(auth(2))$r[]=[$dev.'/sys','pop','admin_lib','','lib'];
	//if($_SERVER['HTTP_HOST']!=srv())
	$r[]=[$dev.'','lk','?reset==','','reboot'];
	if(auth(6))$r[]=[$dev.'/sys','pop','upsql','','upsql'];
	if(auth(6))$r[]=[$dev.'/sys','pop','update','','update'];//erase all!
	if(auth(6))$r[]=[$dev.'','j','popup,,xx|dev2prod','','push'];}
	//if(auth(6))$r[]=[$dev.'','pop','devnote','','devnote'];
return $r;}

static function profile($r,$a){
$usr=ses('usr')?ses('usr'):'profile'; //$r[]=['','lk','/'.$a,'',$a];
$r[]=[$usr,'lk','/@'.$usr,'at',$usr];
$r[]=[$usr,'j','cbck|profile,edit','','profile'];
//$r[]=[$usr.'/boot','in','profile,opening','','boot'];//if($a=='tlex' or $a=='mac')
$n=sql('count(id)','login','v','where mail="'.ses('mail').'" and auth>1');
if($n>1)$r[]=[$usr.'/badger','in','admin,badger','','badger'];
if(auth(6) && ses('dev')=='prod')$r[]=[$usr,'j','ses,,reload||k=dev,v=prog','','prog'];
//$r[]=[$usr,'cb','desktop|dir=/documents','','desktop'];
$r[]=[$usr,'j',',,reload|login,disconnect','','logout'];
return $r;}

static function logon($p){
$a=$p['a'];
$r=self::about($a);
$r=self::profile($r,$a);
$r=self::dev($r);
return $r;}

static function logoff($p){
$a=$p['a']; $r=self::about($a);
//$r[]=['','cb','login,com','login','login'];//|auth=2
if($dev=ses('dev'))$r[]=[$dev.'','j','ses,,reload||k=dev,v=prod','','prod'];
return $r;}

//app
static function app($p){$nm='app'; $a=$p['a']; $db=$p['db']??''; $db2=$p['db2']??''; $db3=$p['db3']??'';
$r[]=['','bub','core,help|ref='.$a.'_app','',$a];
if(auth(4)){$r[]=[$nm,'j','pagup|dev,seeCode|f='.$a,'code','Code'];
	$r[]=[$nm,'pop','dev,com|f='.$a,'terminal','dev'];
	$r[]=[$nm,'pop','admin_sys,call|app='.$a,'file-code-o','code-comment'];}
if(auth(6)){
	$r[]=[$nm,'pop','admin_lang,open|ref='.$a.',app='.$a,'lang','name'];
	$r[]=[$nm,'pop','admin_help,open|ref='.$a,'help','identity'];
	$r[]=[$nm,'pop','admin_help,open|ref='.$a.'_app','help','help'];
	$r[]=[$nm,'pop','admin_icons,open|ref='.$a,'picto','pictos'];
	$r[]=[$nm,'pop','admin_labels,open|ref='.$a,'tag','label'];
	$r[]=[$nm,'pop','desktop,tlex_app|app='.$a,'desktop','publish App'];
	if($db)$r[]=[$nm.'/db','j','popup|admin_sql|b='.$db,'db',$db];
	if($db2)$r[]=[$nm.'/db','j','popup|admin_sql|b='.$db2,'db',$db2];
	if($db2)$r[]=[$nm.'/db','j','popup|admin_sql|b='.$db3,'db',$db3];
	//if($db)$r[]=[$nm.'/db','j','popup|core,mkbcp|b='.$db,'db','backup '.$db];
	}
$r[]=['','lk','/'.$a,'url',''];
return $r;}

//com
static function com(){
$keys='id,dir,type,com,picto,bt';// or auth=0 
$r=sql($keys,'desktop','id','where uid="'.ses('uid').'" or dir="/apps/public" order by dir');
if(is_array($r))foreach($r as $k=>$v)$r[$k][0]='root'.$r[$k][0];//add root
return $r;}

#content
static function content($p){$ret='';
$app=$p['app']??''; ses('app',$app); $own=ses('usr');
$usr=$p['usr']??''; $id=$p['id']??($p['th']??'');
if(is_numeric($usr)){$id=$usr; $usr='';}
//$ret=lk(host(1),ico('star'),'btn abbt');//nav
$ret=menu::call(['app'=>'admin','mth'=>$own?'logon':'logoff','drop'=>1,'a'=>$app]);
//if($app!='tlex')$ret.=lk('/'.$app,langp($app),'btn abbt');
//if($app && method_exists($app,'admin_bt'))$ret.=$app::admin_bt($usr); else 
/*if($app && method_exists($app,'admin'))
	$ret.=menu::call(['app'=>$app,'mth'=>'admin','drop'=>1,'id'=>$id]);*/
return div($ret,'tpbl').div('','adminheight');}
}
?>