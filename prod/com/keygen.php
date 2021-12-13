<?php
class keygen{
static $private=0;

static function build($p){
$l=val($p,'length',12); $a='';
$r=explode('-',$p['cmp']??'0-1-2'); $r=array_flip($r);
list($c0,$c1,$c2,$c3)=vals($r,[0,1,2,3]);
if(isset($r[0]))$a='abcdefghijklmnopqrstuvwxyz';
if(isset($r[1]))$a.='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
if(isset($r[2]))$a.='012345678901234567890123456789';
if(isset($r[3]))$a.='$%*.,?;:/!#{}[]()-|_=';
$r=str_split($a); $n=count($r)-1; $ret='';
for($i=0;$i<$l;$i++)$ret.=$r[rand(0,$n)];
return $ret;}

static function content($p){
$j='gnpw|keygen,build||length,cmp';
$bt=inputcall($j,'length','16','').' ';
$bt.=bj($j,langp('ok'),'btsav').' ';//$ret.=hlpbt('keygen');
$bt.=checkbox('cmp',['alpha','capitals','numbers','signs'],'0-1-2-3',0);
return div($bt.div(input('gnpw','',24)),'board');}
}
?>