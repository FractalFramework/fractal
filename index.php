<?php
//Fractal@2015-2026 gnu/gpl v3+
//session_name('frct');
ini_set('session.cookie_lifetime',0);
ini_set('session.use_only_cookies','on');
ini_set('session.use_strict_mode','on');
$start=$_SERVER['REQUEST_TIME_FLOAT'];
session_start();
require('boot.php');
$app=get('app');
#/app/p1=v1,p2=v2
$p=_jrb(get('p'));
if(strpos($app??'',',')){[$app,$mth]=explode(',',$app);
if($mth)$p['_m']=$mth;}
sez('applng',$app);
require($_SESSION['dev'].'/index.php');
sql::close();
?>