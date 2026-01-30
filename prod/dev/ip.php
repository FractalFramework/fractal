<?php
class ip{
static $private=0;

static function hostname(){$ip=$_SERVER['REMOTE_ADDR']??'';
if(strstr($ip,' ')){$r=explode(' ',$ip); return $r[0];}
else return gethostbyaddr($ip);}

static function build($p){
$r['ip']=self::hostname();
$r['rfr']=$_SERVER['HTTP_REFERER']??'';
$r['agent']=$_SERVER['HTTP_USER_AGENT']??'';
$r['remote']=$_SERVER['REMOTE_ADDR']??'';
return $r;}

static function call($p){
$r=self::build($p);
return implode(br(),$r);}

#interface
static function content($p){
return self::call($p);}

static function api($p){
$r=self::build($p);
return json_encode($r);}

}
?>