<?php

class randnb extends appx{//	
static $private=2;
static $db='randnb';
static $a='randnb';
static $cb='mdb';

static function install($p=''){
sql::create(self::$db,['nb'=>'int'],'1');}//'uid'=>'int'

static function admin($p=''){}
static function js(){return '
function batchtime(){
	ajx("div,'.self::$cb.'|randnb,call");
	x=setTimeout("batchtime()",3000);}
//setTimeout("batchtime()",10);
function playstop(){if(typeof x!="undefined")clearTimeout(x);}
';}
static function headers(){
head::add('csscode','.bar div{display:inline-block; width:2px; background:rgba(0,0,0,0.4); vertical-align:bottom;}');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('nb,dateup',self::$db,'rr','order by id desc limit 1000');//where uid="'.ses('uid').'" 
return $r;}

#play
static function play($p){
$r=self::build($p); $r=array_reverse($r);$ret=''; //pr($r);
if($r)foreach($r as $k=>$v)$ret.=div('','','','height:'.($v['nb']*4).'px;');
if(!$ret)return help('no element','txt');
return div($ret,'bar');}

#call
static function call($p){
$n=rand(1,100);
sql::sav(self::$db,[$n]);
return self::play($p);}

#content
static function content($p){
//self::install();
$bt=btj(pic('play'),atj('setTimeout',['batchtime()',10]),'btn').' ';
$bt.=btj(pic('stop'),'playstop()','btn').' ';
$bt.=bj(self::$cb.'|randnb,call|',langp('go'),'btn');
$ret=self::play($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>