<?php

//application not based on appx
class twitmachine{	
static $private=2;
static $a=__CLASS__;
static $db='';//twitmachine
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='mdb';
static $nb=280;
static $home=1;

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return '
var ob=getbyid("twtx"); var l=ob.

';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function playsub($t){$ret=''; $nb=self::$nb;
$l=strlen($t); $n=false; $s=1; if($l>$nb)$n=$nb; else $n=$l;
$tb=substr($t,0,$n); $nb=strrpos($tb,'.'); if($nb!==false)$n=$nb+1; //echo ':'.$n.'-';
$tb=substr($tb,0,$n); $nb=strpos($tb,'--'); if($nb!==false){$n=$nb; $s=2;}
$tb=substr($tb,0,$n);
if($tb)$ret.=div(trim($tb),'board');
if($l>$n)$ret.=self::playsub(substr($t,$n+$s));
if($ret)return $ret;}

static function play($p){
$ret=self::playsub($p['txt']??'');
return div($ret,'pane','','white-space:pre-line;');}

#call
static function call($p){
//$r=self::build($p);
$ret=self::play($p);
if(!$ret)return help('no element','txt');
return $ret;}

static function menu($p){
$j='twt|twitmachine,play||txt';
$bt=bj($j,langp('ok'),'btn');
$ret=textareact('txt',$p['p1']??'',64,24,$j);
return $ret;}

#content
static function content($p){
//self::install();
$p['txt']=$p['param']??$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return div(div($bt,'col1').div($ret,'col2 pane scroll','twt'),'grid');}
}
?>