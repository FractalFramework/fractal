<?php

//application not based on appx
class ssh{	
static $private=6;
static $a=__CLASS__;
static $db='';
static $cb='ssh';

static function admin(){return admin::app(['a'=>self::$a,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#call
static function call($p){
$d=$p['inp2']??'';
if($d)return exc($d);}//exe

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||inp2';
$bt=bj($j,langp('send'),'btn');
$ret=textarea('inp2','',60,4,'console','console');
return div($ret).$bt;}

#content
static function content($p){
//self::install();
$p['p1']=$p['param']??$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>