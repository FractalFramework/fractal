<?php
//api/app/a:1,b:2
session_start();
header('Content-Type: application/json');
require('boot.php'); $ret=''; $prm=get('p');
if(is_numeric($prm))$p['id']=$prm;
elseif(strpos($prm,':'))$p=_jrb($prm);
else $p['p1']=$prm;
if($app=get('app')){$mth=$p['mth']??'';
	if($mth && method_exists($app,$mth))$ret=$app::$mth($p);//app($app,$p['p1'],$p['mth']);
	elseif(method_exists($app,'api')){$a=new $app(); $ret=$a::api($p);}}
elseif($app=get('frame')){
	if(method_exists($app,'iframe')){$a=new $app(); $ret=$a::iframe($p);}}
elseif($oAuth=get('oAuth'))$ret=tlxf::post(['oAuth'=>$oAuth]);//api.php?oAuth=xxx&msg=hello
echo $ret;
?>