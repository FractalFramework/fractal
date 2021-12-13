<?php

class apicom{
static $private=1;

//reader
static function read($p){
$app=$p['app']??'';
$mth=$p['mth']??'';
$msg=val($p,'msg');
$msg=urlencode($msg);
$oAuth=$p['oAuth']??'';
$f=host(1).'/api.php?app='.$app.'&mth='.$mth.'&msg='.$msg.'&prm=oAuth:'.$oAuth;
$ret=read_file($f);
//$ret=file_get_contents($f);
return $ret;}

//interface
static function content($p){
$p['rid']=randid('md');
$p['p1']=$p['p']??$p['p1']??'';
$ret=input('oAuth','','10','oAuth').hlpbt('oAuth').br();
$ret.=textarea('msg','',64,14,lang('message'),'console').br();
$ret.=bj('popup|apicom,read|app=tlxf,mth=post|oAuth,msg',langp('send'),'btn');
return div($ret,'',$p['rid']);}
}
?>
