<?php

class bkg{	
static $private=0;
static $a=__CLASS__;
static $cb='bkgfs';

static function admin(){return admin::app(['a'=>self::$a,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','.full{width:100%; height:100vh;}');
head::add('jscode',self::js());}

#play
static function play($clr){
$sty=theme($clr);
$bt=bj('popup|bkg,menu|p1='.$clr,'-');
return div('','full','',$sty);}

#call
static function call($p){
$clr=$p['clr']??'cdcdcd';
$ret=self::play($clr);
return $ret;}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||clr';
$bt=bj($j,langp('ok'),'btn');
return inpclr('clr',$p['p1'],'',1).$bt;
return inputcall($j,'clr',$p['p1']??'',32).$bt;}

#content
static function content($p){
$p['p1']=$p['param']??$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'',self::$cb);}
}
?>