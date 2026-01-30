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
$cnt=app($a,$prm);
if(is_array($cnt))$cnt=json_enc($cnt);//todo: kill json_enc in apps
//add_head('charset','UTF-8');
$ret='';//build_head();
if(get('popup'))$ret.=mkpopup($cnt,$prm);
elseif(get('pagup'))$ret.=mkpagup($cnt,$prm);
elseif(get('imgup'))$ret.=mkimgup($cnt);
elseif(get('bubble'))$ret.=mkbubble($cnt);
elseif(get('menu'))$ret.=mkmenu($cnt);
elseif(get('drop'))$ret.=mkmenu($cnt);
elseif(get('ses'))sez($prm['k'],$prm['v']);
else $ret.=$cnt;
//stats::add($app,$prm);
echo $ret;
sql::close();
?>