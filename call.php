<?php
#Fractal license GNU/GPL
session_start();
require('boot.php');
$a=get('_a');
$m=get('_m');
$p=get('_p');
$prm=$p?_jrb($p):[];
if(isset($_POST))$prm+=$_POST;
$prm['_a']=$a; $prm['_m']=$m;
if(isset($prm['verbose']))pr($prm);
$content=app($a,$prm);
//add_head('charset','UTF-8');
$ret='';//build_head();
if(get('popup'))$ret.=mkpopup($content,$prm);
elseif(get('pagup'))$ret.=mkpagup($content,$prm);
elseif(get('imgup'))$ret.=mkimgup($content);
elseif(get('bubble'))$ret.=mkbubble($content);
elseif(get('menu'))$ret.=mkmenu($content);
elseif(get('drop'))$ret.=mkmenu($content);
elseif(get('ses'))sez($prm['k'],$prm['v']);
else $ret.=$content;
//stats::add($app,$prm);
//if(ses('enc'))$ret=utf8_encode($ret);
echo $ret;
sql::close();
?>