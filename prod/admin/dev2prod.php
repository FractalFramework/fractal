<?php

class dev2prod{
static $private=6;

static function menu($p){$app=$p['app']??'';
$r[]=bj('ses,,reload||k=dev,v=prog','prog');
$r[]=bj('ses,,reload||k=dev,v=prod','prod');
$r[]=bj('popup,,xx|dev2prod','publish');
return div(implode('',$r),'list');}

static function op($dr,$f){
$db=0; $sb=0; $sdir=substr($dr,5);
$old='_bckp/'.date('ymd').'/'.$sdir.'/';
$fa=$dr.'/'.$f; $da=filemtime($fa); $sa=filesize($fa);
$fb='prod/'.$sdir.'/'.$f; mkdir_r($fb); //substr($fa,5)
if(is_file($fb)){$db=filemtime($fb); $sb=filesize($fb);}
if($sa!=$sb or $da>$db){
	if(is_file($fb)){mkdir_r($old); copy($fb,$old.$f);}
	copy($fa,$fb);
	return $f;}}

static function walkMethod($dir,$file){
return strfrom($dir.'/'.$file,'/');}

static function emptdir($dir){
$r=scandir_r($dir); //pr($r);
foreach($r as $k=>$v)foreach($v as $ka=>$va)if(is_array($va) && count($va)==0)
	rmdir($dir.'/'.$k.'/'.$ka);}

static function obsoletes(){
$ra=walkdir('dev2prod::walkMethod','prog','',0);
$rb=walkdir('dev2prod::walkMethod','prod','',0);
$r=array_diff($rb,$ra);
unset($r[in_array_k('admin/admin_help.php',$r)]);//?
unset($r[in_array_k('admin/admin_htaccess.php',$r)]);
foreach($r as $v)unlink('prod/'.$v);
return $r;}

static function content($p){
$old='_bckp/'.date('ymd').'/'; mkdir_r($old);
//exc('chmod.sh');
$r=walkdir('dev2prod::op','prog','',0);
$rb=self::obsoletes();
//self::emptdir('_bckp');//
$ret=div('updated','valid').' '.implode(' ',$r);
if($rb)$ret.=div('deleted','alert').' '.implode(' ',$rb);
$f='version.txt'; mkdir_r($f); write_file($f,date('ymd'));
return $ret;}
}
?>