<?php

class yandex{
//https://tech.yandex.com/translate/doc/dg/reference/translate-docpage/static $private=1;

/*
$r=['CN'=>'Chine','IN'=>'Inde','US'=>'�tats-Unis','ID'=>'Indon�sie','BR'=>'Br�sil','RU'=>'Russie','JP'=>'Japon','MX'=>'Mexique','DE'=>'Allemagne','TR'=>'Turquie','FR'=>'France','GB'=>'Royaume-Uni','IT'=>'Italie','ZA'=>'Afrique du Sud','ES'=>'Espagne','AR'=>'Argentine','CA'=>'Canada','SA'=>'Arabie saoudite','KR'=>'Cor�e du Nord','AU'=>'Australie','BN'=>'Bengali','PA'=>'Pakistan'];

$r=['cn'=>'zh','in'=>'mr','us'=>'en','id'=>'id','br'=>'pt','ru'=>'ru','jp'=>'ja','es'=>'es','de'=>'de','tr'=>'tr','fr'=>'fr','gb'=>'en','it'=>'it','za'=>'sw','es'=>'es','ar'=>'es','ca'=>'fe','sa'=>'ar','kr'=>'ko','au'=>'en','bn'=>'bn','pa'=>'pa'];

$r=['us'=>'en','cn'=>'zh','es'=>'es','sa'=>'ar','in'=>'mr','fr'=>'fr','ru'=>'ru','bn'=>'bn','pt'=>'pt','id'=>'id','pa'=>'pa','de'=>'de','jp'=>'ja','tr'=>'tr','it'=>'it','za'=>'sw','kr'=>'ko'];

*/

static function getkey(){$k=ses('yndxkey');
if(!$k)ses('yndxkey',read_file('cnfg/yandex.txt'));
//$k='trnsl.1.1.20170206T173119Z.092e1dd0a9954253.db344b1e497240fb68fd4b1f5150a3d25d9c4e95';//nfo
$k='trnsl.1.1.20180424T150654Z.ad62660ecf66eace.b9aae90ac4dc2fb31c0391fe393f2b84e6a14208';//tlx
$k=ses('yndxkey',$k);
return $k;}

static function api($vr,$mode){
$vr['key']=self::getkey();
if(!$mode)$mode='translate';//detect//getlangs
$u='https://translate.yandex.net/api/v1.5/tr.json/'.$mode.'?'.mkprm($vr);
if($vr)$d=@file_get_contents($u);
$r=json_decode($d,true);
//$r=json_dec($d);
return $r;}

static function getlangs(){$rb=[];
$r=self::api('','getlangs');
foreach($r['dirs'] as $v)$rb=merge($rb,explode('-',$v));
return implode(',',$rb);}

static function detect($p){
$txt=rawurlencode(html_entity_decode(val($p,'txt')));
$r=self::api(['text'=>$txt],'detect');
return $r['lang']??'';}

static function cut($txt){$na=2000; $ret=''; $nc=0;
$nb=strlen($txt); $n=ceil($nb/$na); $r=explode(' ',$txt);
if($nb>$na){foreach($r as $k=>$v){$nc+=strlen($v)+1;
	if($nc<$na)$ret.=$v.' '; else{$rb[]=$ret; $nc=0; $ret='';}}
	if($ret)$rb[]=$ret;}
else $rb[]=$txt;
return $rb;}

#reader
static function build($p){$id=$p['id']??''; $ret='';
$txt=val($p,'txt','');
$from=val($p,'from','');//use comma as separator
$mode=val($p,'mode','html');
$to=val($p,'to',ses('lng'));//default lang
$format=val($p,'format',$mode);//plain//html
$options=val($p,'option','1');//1 for autodetect (empty) from
if($from)$lang=$from.'-'.$to; else $lang=$to;
$r=self::cut($txt); //p($r);
if($r)foreach($r as $k=>$v){
	$txt=rawurlencode(html_entity_decode($v)); //eco($txt);
	$vr=['text'=>$txt,'lang'=>$lang,'format'=>$format,'options'=>$options];
	$rb=self::api($vr,'translate'); //pr($rb);
	$rc[]=$rb['text'][0];}
$ret=implode('',$rc);
return [$ret,$rb['detected']['lang']];}

static function read($p){
list($txt,$lang)=self::build($p);
$txt=($txt);//decode
$ret=div(lang('detected_lang').' '.$lang,'grey').div($txt,'pane');
return $ret;}

//com (apps)
static function com($p,$o=''){return;//dead
list($txt,$lang)=self::build($p);
$_POST['lng']=$lang;
$ret=rawurldecode($txt);//if($o)
if(val($p,'dtc'))$ret.=' ('.$lang.')';
return $ret;}

//interface
static function content($p){
$rid=randid('yd');
$p['txt']=val($p,'txt',$p['p1']??'');
$ret=input('txt',$p['txt']);
$ret.=bj($rid.'|yandex,read||txt',lang('translate'),'btn');
//$ret.=bj('popup|yandex,getlangs||txt',lang('lang'),'btn');
$ret.=bj('popup|yandex,detect||txt',lang('detect'),'btn');
return $ret.div('','board',$rid);}
}
?>