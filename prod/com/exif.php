<?php

class exif{	
static $private=0;
static $a=__CLASS__;
static $cb='exf';

static function admin(){return admin::app(['a'=>self::$a,'cb'=>self::$cb]);}
static function injectJs(){return;}
static function headers(){
add_head('csscode','');
add_head('jscode',self::injectJs());}

#call
static function call($p){
$f=imgroot($p['inp2']);
$r=exif_read_data($f); //pr($r);
$ret=play_r($r);
return $ret;}

#content
static function content($p){
$p['p1']=val($p,'param');
$j=self::$cb.'|'.self::$a.',call||inp2';
$bt=bj($j,langp('ok'),'btn').hlpbt('exif_app');
$ret=inputcall($j,'inp2',val($p,'p1'),32,lang('url')).$bt;
return $ret.div('','pane',self::$cb);}
}
?>