<?php

//http://www.piday.org/million/
class pi4{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='pi4';
static $r=[];

static function admin(){return admin::app(['a'=>self::$a]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($n){
static $pi4; if(!$pi4)$pi4=1;
static $i; $i+=2;
static $o; $o=$o?0:1;
//$current=1/(1+$i);
$current=bcdiv(1,bcadd(1,$i));
//$pi4=$o?$pi4-$current:$pi4+$current;
if($o)$pi4=bcsub($pi4,$current); else $pi4=bcadd($pi4,$current);
if($i<$n)return self::build($n); else return $pi4;}

static function run($n){//echo $n.' ';
$pi4=self::build($n);
//$ret=$pi4*4;
$ret=bcmul($pi4,4);
return $ret;}

#find iteration
static function find_ieration($min=1000,$lenght=1000,$rnd=3){//nb after comma
$pi=pi(); $ret=''; $r=[]; $max=$min+$lenght;
for($i=$min;$i<$max;$i++){//$ret='';
	//if(isset($_SESSION['pi_r'][$i]))$pi2=$_SESSION['pi_r'][$i]; else
	if(!$ret){$pi2=self::run($i); //$_SESSION['pi_r'][$i]=$pi2;
		if(round($pi2,$rnd)==round($pi,$rnd))$ret=$i;}
	if($ret)$r[]=[$i,round($pi2,$rnd)];}
//$ret=round($pi,2);
$ret=tabler($r);
return $ret;}

#call
static function call($p){$n=$p['inp2']??40000;
bcscale(40); $ret=''; $_SESSION['pi_r']=[];
//$ret='from calc: '.self::run($n).br();
$ret.='from php: '.pi().br();
//$ret.='from  '.lk('http://www.piday.org/million/').': '.span(pi::result(),'code','','word-wrap:break-word').br();
//$ret.=self::find_ieration(1000,1000,3);//from 1602
//$ret.=self::find_ieration(20000,10000,4);//from 27438
//$ret.=self::find_ieration(200000,100000,5);//from 230657
$ret.=self::find_ieration(1000000,1,6);
return $ret;}

static function com($p){
$j=self::$cb.'|'.self::$a.',call||inp2';
$bt=bj($j,langp('ok'),'btn').hlpbt('pi');
$v=$p['p1']??40000;
return inputcall($j,'inp2',$v,22,lang('iterations')).$bt;}

#content
static function content($p){
$p['p1']=$p['p1']??'';
$bt=self::com($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}//
}
?>