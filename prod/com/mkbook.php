<?php

//application not based on appx
class mkbook{	
static $private=2;
static $a=__CLASS__;
static $db=__CLASS__;
static $cols=['tit','txt'];
static $typs=['var','text'];
static $cb='mdb';

static function install(){
sqlcreate(self::$db,[array_combine(self::$cols,self::$typs)],0);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function injectJs(){return;}
static function headers(){
add_head('csscode','');
add_head('jscode',self::injectJs());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p){$ret=''; //pr($r);
//$r=self::build($p);
//$f=val($p,'inp2');
$nm=$p['inp2']??'ebook';
$f='disk/usr/'.ses('user').'/'.$nm.'.epub';
$r=[];
$ret=epub::build($r,$rb);
return $ret;}

#call
static function call($p){
$ret=self::play($p,);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){
$j=self::$cb.'|'.self::$a.',call||inp2';
$ret=inputcall($j,'inp2',val($p,'p1'),32);
$ret.=upload::call('inp2');
$ret.=bj($j,langp('send'),'btn');
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=self::com($p);
//$ret=self::call($p);
return $bt.div('','pane',self::$cb);}
}
?>