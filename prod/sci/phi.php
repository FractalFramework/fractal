<?php

class phi{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='phy';

static function admin(){return admin::app(['a'=>self::$a]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($n){
static $i; $i++;
if($i==$n)return 1;
return bcadd(1,bcdiv(1,self::build($n)));}

#call
static function call($p){
$n=$p['inp2']??100;
bcscale(40);
$ret=self::build($n);
return $ret;}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||inp2';
$bt=bj($j,langp('ok'),'btn').hlpbt('phi_app');
$v=$p['p1']??12;
return inputcall($j,'inp2',$v,22,lang('precision')).$bt;}

#content
static function content($p){
echo $p['p1']=$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}//
}
?>