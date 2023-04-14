<?php

class poster extends appx{
static $private=0;
static $a='poster';
static $db='poster';
static $cb='stc';
static $cols=['tit','img','com'];
static $typs=['var','var','text'];
static $tags=1;
static $open=1;

function __construct(){
$r=['a','db','cb','cols'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}
static function js(){return '';}
static function headers(){}

#edit
static function collect($p){
return parent::collect($p);}

static function del($p){
return parent::del($p);}

static function save($p){
return parent::save($p);}

static function modif($p){
self::savim($p);
return parent::modif($p);}

static function form($p){
$p['btcom']=hlpbt('poster_edit');
return parent::form($p);}

static function edit($p){
//$p['help']='poster_edit';
return parent::edit($p);}

static function create($p){
return parent::create($p);}

static function savim($p){
$id=$p['id']??'';
$r=['9','16'];//car-width,line-height
$font=val($p,'font','Fixedsys');
$clr=val($p,'clr');
$img=val($p,'img');
$src=imgroot($img,'medium');
$url='img/full/'.self::$a.$id.'.png';
self::imgtx($p['com'],$url,$src);
return img('/'.$url.'?'.randid(),'','');}

#build
/*static function lines($t,$maxl){$n=0;
$t=str_replace("\n"," \n",$t); $r=explode(' ',$t); $nb=0; $ret='';
foreach($r as $k=>$v){$len=strlen($v); $nb+=$len+1; 
	$pos=strpos($v,"\n"); if(!isset($ret[$n]))$ret[$n]='';
	if($nb>$maxl){$nb=strlen($v); $n++; $nbb=floor($nb/$maxl);
		for($i=0;$i<$nbb;$i++){$ret[$n]=substr($v,$maxl*$i,$maxl); $n++;}}
	elseif($pos!==false){$ret[$n].=substr($v,0,$pos); $n++;
		$ret[$n]=substr($v,$pos+1).' '; $nb=strlen($ret[$n]);}
	else $ret[$n].=trim($v).' ';}
return $ret;}*/

static function position($d,$sz,$w){
if(strpos($d,'/')===false)return [20,60];
[$x,$y]=explode('/',$d);
if($x=='left')$x=10;
if($x=='center')$x=(500/2)-($w/2);
if($x=='right')$x=500-10-$w;
if($y=='top')$y=10+$sz;
if($y=='middle')$y=500/2+$sz/2;
if($y=='bottom')$y=500-10;
return [$x,$y];}

//hello,72,ff00ff,180/300,10
static function setxt($t,$im){
$r=explode('|',$t);
$font='fonts/ttf/ariblk.ttf';//LCDN//verdanab
foreach($r as $k=>$v){
	[$txt,$sz,$clr,$pos,$ang]=expl(',',$v,6);
	if(!$sz)$sz=36; if(!$ang)$ang=0;
	if($klr=clrget($clr))$clr=$klr; $width=$sz*strlen($txt)/2;
	[$rh,$gh,$bh]=rgb($clr); [$x,$y]=self::position($pos,$sz,$width);
	$c=imagecolorallocate($im,$rh,$gh,$bh);
	imagettftext($im,$sz,$ang,$x,$y,$c,$font,$txt);}
return $im;}

static function imgtx($t,$url,$src){
$t=str_replace("&nbsp;",' ',$t);
$im=imagecreatefromjpeg($src);
$blanc=imagecolorallocate($im,255,255,255);
$im=self::setxt($t,$im);
imagecolortransparent($im,$blanc);
imagepng($im,$url);}

#build
static function build($p){$id=$p['id']??'';
return sql('uid,txt',self::$db,'ra',$id);}

static function play($p){
$id=$p['id']??'';
$url='img/full/'.self::$a.$id.'.png';
return img('/'.$url.'?'.randid(),'','');}

static function stream($p){
return parent::stream($p);}

#call (read)
static function tit($p){
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){
return parent::com($p);}

#interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>