<?php
class upgrade{
static $private=6;

static function injectJs(){return 'ajx("popup|upgrade,auto");';}
//static function headers(){add_head('jscode',self::injectJs());}

static function auto(){
$ret=update::loaddl();
$r=['lang','icons','help','desktop'];
foreach($r as $k=>$v){$ret.=upsql::call(['app'=>$v]).br();}// sleep(1);
return $ret;}

#interface
static function content($p){
$f='version.txt';
$local=is_file($f)?file_get_contents($f):'';
$f=serv().'/version.txt';
$distant=is_file($f)?file_get_contents($f):'';
if($distant>$local)add_head('jscode',self::injectJs());
ses('updated',1);}
}
?>