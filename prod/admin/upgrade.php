<?php
class upgrade{
static $private=6;

static function js(){return 'ajx("popup|upgrade,auto");';}
//static function headers(){head::add('jscode',self::js());}

static function auto(){
$ret=update::loaddl();
$r=['lang','icons','help','desktop'];
foreach($r as $k=>$v){$ret.=upsql::call(['app'=>$v]).br();}// sleep(1);
return $ret;}

#interface
static function content($p){
$f='version.txt';
$local=is_file($f)?file_get_contents($f):'';
$f=srv(1).'/version.txt';
$distant=is_file($f)?file_get_contents($f):'';
if($distant>$local)head::add('jscode',self::js());
ses('updated',1);}
}
?>