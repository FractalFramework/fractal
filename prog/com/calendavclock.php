<?php

class calendavclock{
static $a='calendavclock';
static $private=1;
static $sz=300;
static $cb='clk2';

static function admin(){return admin::app(['a'=>self::$a]);}

static function js(){
$cb=self::$cb; $sz=self::$sz; $m=$sz/2; $t=$sz/3; $t2=$t*2; $s=0.5;
}

static function headers(){
head::add('csscode','.hrclr{color:#990000;} .mnclr{color:#000099;} .scclr{color:#009900;} .msclr{color:#990099;}');
head::add('jslink','/js/canvas.js');
//head::add('jslink','/js/clock.js');
head::add('jscode',self::js());}

#content
static function content($p){
$bt=btj('o-clock','world=0','btn').btj('u-clock','world=1','btn');
//$bt.=input('p1','1','','',1);
$ret=tag('canvas',['id'=>self::$cb,'width'=>self::$sz.'px','height'=>self::$sz.'px'],'');
return $bt.div($ret,'board');}
}

?>