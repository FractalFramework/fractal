<?php

//application not based on appx
class drawpi{
static $private=0;
static $a=__CLASS__;
static $db='drawpi';
static $cb='dwp';
//static $clr=['ffffff','000000','ff0000','00ff00','0000ff','ffff00','00ffff','ff9900','cccccc','666666'];
//static $clr=['white','red','orange','yellow','green','darkgreen','lightblue','blue','fuschia','pink'];
static $clr=['FFFFFF','FF0000','FF8000','FFFF00','00FF00','008000','00FFFF','0000FF','800080','FF00FF'];

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

//static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function admin(){return menu::call(['app'=>'admin','mth'=>'app','drop'=>1,'a'=>self::$a]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function f(){return 'disk/usr/dav/img/pi.png';} //mkdir_r($f);

static function clr(){
$r=self::$clr; $rt=[];
foreach($r as $k=>$v)$rt[$k]=rgb($v);
return $rt;}

static function build_gd($p,$pi){
$sz=$p['p1']??''; $w=600; $h=400;
$f=self::f();
$nw=ceil($w/$sz); $nh=ceil($h/$sz); $pi=substr($pi,$nw*$nh);
$im=imagecreatetruecolor($w,$h);
$rc=self::clr($im); $i=0; //pr($rc);
for($a=0;$a<$nh;$a++){
	$y=ceil($a*$sz); $yb=ceil($y+$sz);
	for($b=0;$b<$nw;$b++){
		$v=substr($pi,$i,1); $x=ceil($b*$sz); $xb=ceil($x+$sz);
		[$rh,$gh,$bh]=$rc[$v];
		//echo $x.'-'.$y.'/'.$xb.'-'.$yb.' ';
		$c=imagecolorallocate($im,$rh,$gh,$bh);
		imagefilledrectangle($im,$x,$y,$xb,$yb,$c);
		$i++;}}
$c=imagecolorallocate($im,255,255,255);
imagecolortransparent($im,$c);
imagealphablending($im,false);
imagepng($im,$f);
return img($f);}

static function build_svg($p,$pi){
$sz=$p['p1']??''; $w=800; $h=800; $rt=[]; $ret='';
$f=self::f();
$nw=ceil($w/$sz); $nh=ceil($h/$sz); //$pi=substr($pi,$nw*$nh);
$rc=self::$clr; $i=0;
for($a=0;$a<$nh;$a++){$y=round($a*$sz,2);
	for($b=0;$b<$nw;$b++){
		$v=substr($pi,$i,1); $x=round($b*$sz,2);
		$ret.=tag('rect',['x'=>$x,'y'=>$y,'width'=>$sz,'height'=>$sz,'fill'=>'#'.$rc[$v]],'');
		//$ret.='<rect x="'.$x.'" y="'.$y.'" width="'.$sz.'" height"'.$sz.'" fill="#'.$rc[$v].'></rect>';
		//'[#'.$rc[$v].':attr]['.$x.','.$y.','.$sz.','.$sz.':rect]';
		$i++;}};
return tag('svg',['version'=>'1.1','width'=>$w,'height'=>$h],$ret);}

#play
static function play($p){
$pi=pi::com();
$ret=self::build_svg($p,$pi);
return $ret;}

#call
static function call($p){
$ret=self::play($p);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){}

static function menu($p){
$j=self::$cb.',,z|'.self::$a.',call||p1';
$ret=inpnb('p1','20','2','1','20',$j);
$ret.=label('p1',lang('size'));
$ret.=bj($j,langp('ok'),'btn');
return div($ret);}

#content
static function content($p){
//self::install();
//$bt=self::admin();
$bt=self::menu($p);
$ret='';//self::call(['p1'=>'10']);
return $bt.div($ret,'pane',self::$cb);}
}
?>