<?php

//application not based on appx
class stardate{
static $private=0;
static $a=__CLASS__;
static $db='stardate';
static $cb='std';

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build

static function decimal($t){
//$dt=new Datetime($t);
$y=date('Y',$t);
$m=date('d',$t)/date('t',$t)*10;
$d=date('m',$t);
$h=date('H',$t)/2.4;
$i=date('i',$t)/60;
$s=date('s',$t)/60;
return [$y,round($m),round($d/1.2),$h,$i,$s];}

/*static function angle($t){
return [$y,round($m),round($d/1.2),$h,$i,$s];}*/

#call
static function call($p){
$t=strtotime($p['p1'])??time(); //echo date('ymd',$t);
[$y,$m,$d,$h,$i,$s]=self::decimal($t);
$t=ascii(128336+round($h));
$ry=str_split($y); $ty=walkd($ry,'asciinb');
$s='padding:10px;font-size:1.6em; display:inline-block;';
$ret='&#128172;'.helpx('logbook').br().'&#10024;'.helpx('stardate').$t.join('-',$ry).'&bull;'.$m.'&bull;'.$d;
return div($ret,'valid','',$s);}

static function com($p){return self::content($p);}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||p1'; $p1=$p['p1']??'';
//$bt=bj($j,langp('ok'),'btn');
//$ret=$bt.textarea('p1','',60,4);
//$ret=inputcall($j,'p1',$p['p1']??'',32).$bt;
$ret=form::call(['p1'=>['input',$p1,'url','date'],['submit',$j,'ok','']]);
$ret.=hlpbt('stardate_app');
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=valb($p,'p1',date('Y-m-d'));
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>
