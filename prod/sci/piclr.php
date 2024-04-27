<?php

//http://www.piday.org/million/
class piclr{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='pi4';

static function admin(){return admin::app(['a'=>self::$a]);}
static function js(){return;}
static function headers(){
$r=self::clr(); $ret='';
foreach($r as $k=>$v)$ret.='.clr'.$k.'{background-color:#'.$v.';}'.n();
//$n=0; $ret.='.clr'.$n.'{background-color:#'.$r[$n].';}'.n();
head::add('csscode','.cube{display:inline-block; width:5px; height:5px; margin:0;}
.pane{line-height:0px;}
'.$ret);
head::add('jscode',self::js());}

#pi
static function pi4(int $n):int{
static $pi4; if(!$pi4)$pi4=1;
static $i; $i+=2;
static $o; $o=$o?0:1;
//$current=1/(1+$i);
$current=bcdiv(1,1+$i);
if($o)$pi4=bcsub($pi4,$current); else $pi4=bcadd($pi4,$current);
//if($o)echo $pi4.'+'.$current.br(); else echo $pi4.'-'.$current.br();
if($i<$n)return self::build($i,$n); else return $pi4;}

//build
static function clr(){return ['ff0000','ff7f00','ffff00','80ff00','00ff80','00ffff','0080ff','0000ff','8000ff','ff00ff','ff007f'];}//4=>'00ff00',

static function build($pi,$n){$ret='';
$pi=substr($pi,2,$n);
$r=str_split($pi);
$clr=self::clr();
foreach($r as $k=>$v)$ret.=div('','cube clr'.$v,'','');
return $ret;}

static function svg($pi,$n){$ret='';
$pi=substr($pi,2,$n);
$r=str_split($pi); $face=480;
$clr=self::clr(); $i=0; $l=0; $w=2;
foreach($r as $v){
	if($i==$face){$l++; $i=0;}
	$x=$w*$i; $y=$w*$l;
	$ret.='[#'.$clr[$v].':attr]['.$x.','.$y.',2px,2px:rect]';
	$i++;}
$ret=svg::call(['code'=>$ret,'w'=>$face,'h'=>$face]);
return $ret;}

#call
static function call($p){
$n=$p['inp2']??10000;
//bcscale(40);
//$pi4=self::p4($n);
$pi=pi::result();
$ret=self::build($pi,$n);
//$ret=self::svg($pi,$n);
return $ret;}

static function com($p){
$j=self::$cb.',,z|'.self::$a.',call||inp2';
$bt=bj($j,langp('ok'),'btn').hlpbt('pi');
$v=$p['p1']??10000;
return inputcall($j,'inp2',$v,22,lang('iterations')).$bt;}

#content
static function content($p){
$p['p1']=$p['p1']??'';
$bt=self::com($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}//
}
?>
