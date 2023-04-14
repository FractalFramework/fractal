<?php

class micron{
static $private=6;
static $a='micron';
static $db='micron';
static $cols=['tit','txt'];
static $typs=['var','bvar'];

function __construct(){
$r=['a','db','cols'];
foreach($r as $v)appx::$$v=self::$$v;}

static function install($p=''){
appx::install(array_combine(self::$cols,self::$typs));}

static function admin(){
$r[]=['','j','popup|micron,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=micron_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=gaia','code','Code'];
return $r;}

static function js(){return '
var lapsetime=3000;
function batchtime(){
var iterations=100;
var n=getbyid("step").value; //alert(n);
if(n<iterations)ajx("div,gaiaa|micron,call|p1="+n);//
setTimeout("batchtime()",lapsetime);}
//setTimeout("batchtime()",10);//on/off
';}

static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'ra',$id);
return $r;}

#read
static function verifid($r,$n){
if(!isset($r[$n]))return $n; else $id=$r[$n];
$md5=sql('md5','multilang','v',$id);
$ex=sql('id','multilang','v','where md5="'.$md5.'" and lang="fr"'); //echo $ex.'-';
if($ex)$n=self::verifid($r,$n+1);
return $n;}

static function call($p){
$n=$p['p1']??''; $ret=''; //echo $n.'-';
/*$r=sql('id','multilang','rv','where lang="it" and id>17'); //pr($r);
$n=self::verifid($r,$n);//search last
if(isset($r[$n]))app('multilang',['id'=>$r[$n],'lang'=>'fr'],'newfrom'); else return 'end';*/
$n=$n+1;
$ret.=span(date('H:i:s'),'small').' ';
$ret.=lk('/micron/'.$n,$n,'btsav').' ';
//$ret.=lk('/micron/'.count($r),langp('stop'),'btdel');
$ret.=hidden('step',$n);
return $ret;}

static function com($p){
return self::content($p);}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??''; $n=0;
if($p['p1'])$ret=self::call($p);
else $ret=input('inp1',1).bj('gaiaa|micron,call||inp1',lang('send'),'btn').hidden('step',$n);
return div($ret,'pane','gaiaa');}
}
?>