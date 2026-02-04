<?php
class keygen{
static $private=0;

static function build($p){
$l=val($p,'length',12); $a='';
$r=explode('-',$p['cmp']??'1-2-3'); $rc=array_flip($r);
if(isset($rc[1]))$a='abcdefghijklmnopqrstuvwxyz';
if(isset($rc[2]))$a.='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
if(isset($rc[3]))$a.='012345678901234567890123456789';
if(isset($rc[4]))$a.='$%*.,?;:/!#{}[]()-|_=';
$r=str_split($a); $n=count($r)-1; $ret='';
for($i=0;$i<$l;$i++)$ret.=$r[rand(0,$n)];
return $ret;}

static function content($p){
$j='gnpw|keygen,build||length,cmp';
$bt=inputcall($j,'length','16','').' ';
$bt.=bj($j,langp('ok'),'btsav').' ';//$ret.=hlpbt('keygen');
$bt.=checkbox('cmp',['alpha','capitals','numbers','signs'],'0-1-2',0);
return div($bt.div(input('gnpw','',24)),'board');}
}
?>