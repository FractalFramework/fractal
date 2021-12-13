<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
setlocale(LC_TIME,'fr_FR');
if(isset($_GET['reset'])){$dev=$_SESSION['dev']; $_SESSION=[]; $_SESSION['dev']=$dev;}
$f='cnfg/connect.php'; include($f);
if(!isset($_SESSION['time']) or isset($_GET['reset'])){
	$_SESSION['site']=$site; $_SESSION['macboot']=$macboot; $_SESSION['updated']=0;
	if(isset($_COOKIE['lng']))$_SESSION['lng']=$_COOKIE['lng'];
	elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		$_SESSION['lng']=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
	else $_SESSION['lng']='fr';
	$_SESSION['srvlng']=$_SESSION['lng'];}
if(isset($_GET['dev']))$_SESSION['dev']=$_GET['dev']=='='?'prog':'prod';
if(!isset($_SESSION['dev']))$_SESSION['dev']='prod';
require($_SESSION['dev'].'/lib.php');
require($_SESSION['dev'].'/core.php');
ses('dbq',$dbq);
if(isset($_GET['logout'])){auth::logout(); reload('/');}
if(!isset($_SESSION['time']))auth::autolog();
$_SESSION['time']=time();
?>