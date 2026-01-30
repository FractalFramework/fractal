<?php
class admin_apps{
static $private=0;
	
//content
static function content($p){$ret='';
$r=applist::comdir();
if($r)foreach($r as $k=>$v){
	$app=$v[4]; $a=new $app;
	$lk=lk('/'.$app,$app);
	$lk=popup($app,$app,'btxt');
	$private=isset($a::$private)?$a::$private:0;
	$ret[]=[$lk,$v[0],$private];}
return div(tabler($ret),'board');}
}
?>