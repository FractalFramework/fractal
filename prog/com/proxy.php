<?php

class proxy{
static $private=0;
static $a=__CLASS__;
static $dr='usr/if';

static function admin(){
$r=admin::app(['a'=>self::$a]);
$r[]=['menu','pop','proxy,com','','com'];
$r[]=['menu','pop','proxy,comim','','img'];
$r[]=['menu','pop','proxy,deldr','','del'];
return $r;}

static function protect($d,$f){$fb=domain($f); return $d;
/*$d=str_replace('href="http://','href="###',$d);
$d=str_replace('src="http://','src="###',$d);
$d=str_replace('href="/','href="http://'.$fb.'/',$d);
$d=str_replace('src="/','src="http://'.$fb.'/',$d);
$d=str_replace('url(/','url(http://'.$fb.'/',$d);
$d=str_replace('href="###','href="http://',$d);
$d=str_replace('src="###','src="http://',$d);*/
$r=explode('src="',$d); $ret='';
if($r)foreach($r as $k=>$v){
$im=substr($v,0,strpos($v,'"'));
if(substr($v,0,4)!='http' && $k>0)$ret.=$fb.'/'.$v; else $ret.=$v; if(is_img($im))$rb[]=$im;}
//p($rb);
return $ret;}

static function get($p){$ret='';
$rid=$p['rid']??''; $f=$p[$rid]??'';
if($f){$fa=http($f);
	if(is_img($f))$ret=img($fa);
	//$fb='logic.ovh/api/proxy/'.$f; $$fb=$fa;
	else{$ret=curl($fa); $ret=self::protect($ret,$fa);}}
return $ret;}

static function delf($p){
$f=val($p,'f');
if($f)unlink($f); $dr=self::$dr;
if(is_dir($dr)){$dir=opendir($dr); $ret=$dr.br();
while($f=readdir($dir)){$drb=$dr.'/'.$f;
if(is_dir($drb) && $f!='..' && $f!='.'){rmdir_r($drb); if(is_dir($drb))rmdir($drb);}
elseif(is_file($drb)){unlink($drb); $ret.=$drb.br();}} rmdir($dr);}
return 'del:'.$f;}

static function deldr($p){$ret='';
$dr=self::$dr; $f=$dr.date('ymd').'.tar';
if(!is_file($f.'.gz') && !is_file($f))$fb=tar::buildFromdir($f,$dr);
chmod(self::$dr,0777);
sez('auth',6); rmdir_r($dr); sez('auth',2);
$ret.=lk($f.'.gz','','btn',1);
$ret.=bj('popup|proxy,delf|f='.$f.'.gz','x','btn');
return $ret;}

static function getim($p){
$rid=$p['rid']??''; $u=$p[$rid]??''; $ret=''; $min=''; $max=''; $n='';
$r=preg_split('/[()]/',$u);
if(isset($r[1])){
	if(strpos($r[1],'-'))[$min,$max]=explode('-',$r[1]);
	elseif(strpos($r[1],','))$rb=explode(',',$r[1]);}
$l=strlen($min);
$dr=self::$dr; mkdir_r($dr);
if(isset($rb))foreach($rb as $v){$f=$r[0].$v.$r[2]; $fa=$dr.'/'.strend($f,'/');
	$ok=@copy($f,$fa); $ret.=img('/'.$fa);}
else for($i=$min;$i<=$max;$i++){
	if($l==2){if($i<=9)$n='0'.$i; else $n=$i;}
	elseif($l==3){if($i<=9)$n='00'.$i; elseif($i<=99)$n='0'.$i; else $n=$i;}
	elseif($l==4){if($i<=9)$n='000'.$i; elseif($i<=999)$n='00'.$i; elseif($i<=99)$n='0'.$i; else $n=$i;}
	$f=$r[0].$n.(isset($r[2])?$r[2]:''); $fa=$dr.'/'.strend($f,'/');
	//if(fopen($f,'r'))
	$ok=@copy($f,$fa);
	//if(!$ok){$d=@file_get_contents($f); if($d)$er=write_file($fa,$d);}
	$ret.=img('/'.$fa);}
return $ret;}

static function comim($p){
$f=val($p,'url'); $rid=randid('prm');
$j='popup|proxy,getim|rid='.$rid.'|'.$rid;
$ret=inputcall($j,$rid,$f,32);
//$ret=input($rid,$f,32).' ';
$ret.=bj($j,'ok','btn');
$ret.=btj('x',atj('val',['',$rid]),'btn');
return $ret;}

static function com($p){
$f=val($p,'url'); $rid=randid('prx');
$j='popup|proxy,get|rid='.$rid.'|'.$rid;
$ret=inputcall($j,$rid,$f,32);
//$ret=input($rid,$f,32);
$ret.=bj($j,'ok','btn');
return $ret;}

static function content($p){$ret='';
$f=val($p,'url');
if($f)$ret=self::get($f);
else $ret=self::com($p);
return div($ret,'','prx');}

static function api($p){$f=$p['p1']??'';
if($f)return curl(http($f));}
}
?>
