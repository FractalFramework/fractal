<?php
#Fractal license GNU/GPL
session_start();
require('boot.php');
$app=get('appName');
$mth=get('appMethod');
$prm=get('params');
$p=$prm?_jrb($prm):[];
//foreach($p as $k=>$v)if($v=='_post')$p[$k]=post($k);
if(isset($_POST))$p+=$_POST;
$p['appName']=$app;
$p['appMethod']=$mth;
//$p['callBack']=get('div');
if(isset($p['verbose']))pr($p);
$content=app($app,$p);
$ret=build_head();
if(get('popup'))$ret.=mkpopup($content,$p);
elseif(get('pagup'))$ret.=mkpagup($content,$p);
elseif(get('imgup'))$ret.=mkimgup($content);
elseif(get('bubble'))$ret.=mkbubble($content);
elseif(get('menu'))$ret.=mkmenu($content);
elseif(get('drop'))$ret.=mkmenu($content);
elseif(get('ses'))sez($p['k'],$p['v']);
else $ret.=$content;
//stats::add($app,$p);
if(ses('enc'))$ret=utf8_encode($ret);
echo $ret;
sqlclose();
?>