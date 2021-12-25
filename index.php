<?php
//Fractal@2016-2021 gnu/gpl v3+
//session_name('frct');
ini_set('session.cookie_lifetime',0);
ini_set('session.use_only_cookies','on');
ini_set('session.use_strict_mode','on');
$start=$_SERVER['REQUEST_TIME_FLOAT'];
session_start();
require('boot.php');
$app=$_GET['app']??$index;
sez('app',$app);
#/app/p1=v1,p2=v2
$p=_jrb(get('p'));
sez('applng',$app);
require($_SESSION['dev'].'/index.php');
//Sql::close();
sqlclose();
?>