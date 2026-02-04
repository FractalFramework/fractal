<?php

//application not based on appx
class model0{
static $private=0;
static $a=__CLASS__;
static $db='model';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='mdb';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

//static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function admin(){return menu::call(['app'=>'admin','mth'=>'app','drop'=>1,'a'=>self::$a]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??''; return [];//!
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p,$r){$ret=''; //pr($r);
if($r)foreach($r as $k=>$v){$rb=[];
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
return $ret;}

#call
static function call($p){
$r=self::build($p);
$ret=self::play($p,$r);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||p1'; $p1=$p['p1']??'';
//$bt=bj($j,langp('ok'),'btn');
//$ret=$bt.textarea('p1','',60,4);
//$ret=inputcall($j,'p1',$p['p1']??'',32).$bt;
$ret=form::call(['p1'=>['inputcall',$p1,'url',$j],['submit',$j,'ok','']]);
return div($ret);}

#content
static function content($p){
//self::install();
//$bt=self::admin();
$p['p1']=$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>