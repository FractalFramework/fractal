<?php
class apisql{
static $private=6;

static function call($p){
$p=$p['app']??'';
$f=srv(1).'/api.php?app=apisql&mth=render&p='.$p;
$d=get_file($f);
$r=json_decode($d,true); //p($r);
if(host()!=srv())
	if(isset($r) && is_array($r)){
		sql::sav2($p,$r,0,0,1);
		if($p=='lang')ses('lang',lang_com(ses('lng')));
		if($p=='icons')ses('icon',icon_com());
		return 'renove '.$p.' ok';}
	else return 'nothing'.upsql::error();}

static function render($table){
$keys=sql::cols($table,0,1);
if($table=='login')return;
elseif($table=='desktop')$wh='where uid=1';
elseif($table=='articles')$wh='where uid=1';
else $wh='';
$r=sql($keys,$table,'rr',$wh,0);
$ret=($r);//json_enc
return $ret;}

static function menu($p){//system tables
$r=['lang','icons','help','desktop','labels','articles','sys','syslib','devnote'];
foreach($r as $k=>$v)
	if($v!='login')$ret[]=bj($p['rid'].'|apisql,call|app='.$v,$v,'btn');
return implode('',$ret);}

static function content($p){
$p['rid']=randid('md');
$p['p1']=$p['p1']??'';
$bt=hlpbt('apisql');
$bt.=self::menu($p);
return $bt.div('','',$p['rid']);}
}
?>