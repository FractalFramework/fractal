<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
if(isset($_GET['reset'])){$dev=$_SESSION['dev']; $_SESSION=[]; $_SESSION['dev']=$dev;}
if(isset($_GET['dev']))$_SESSION['dev']=$_GET['dev']=='='?'prog':'prod';
if(!isset($_SESSION['dev']))$_SESSION['dev']='prod';
require($_SESSION['dev'].'/lib.php');
require($_SESSION['dev'].'/core.php');
if(!isset($_SESSION['home']) or !isset($_SESSION['cnfg']) or isset($_GET['reset'])){
	$f='cnfg/'.str_replace('www.','',$_SERVER['HTTP_HOST']).'.php'; require($f); $_SESSION['cnfg']=$f;
	ses::$cnfg=$r; new sql($s); $s=[];
	$_SESSION['updated']=0;
	$_SESSION['lng']=$_COOKIE['lng']??getlng();
	$_SESSION['srvlng']=$_SESSION['lng'];}
require($_SESSION['cnfg']);
if(isset($_GET['logout'])){auth::logout(); reload('/');}
if(!sql::ex('login'))install::content([]);
if(!isset($_SESSION['time']))auth::autolog();
$_SESSION['time']=time();
?>