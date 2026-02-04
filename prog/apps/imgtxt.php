<?php

class imgtxt extends appx{
static $private=0;
static $a='imgtxt';
static $db='imgtxt';
static $cb='imt';
<<<<<<< HEAD
static $cols=['tit','txt','clr'];
static $typs=['var','bvar','svar'];
=======
<<<<<<< HEAD
static $cols=['tit','txt','clr'];
static $typs=['var','bvar','svar'];
=======
static $cols=['tit','txt'];
static $typs=['var','bvar'];
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
static $tags=1;
static $open=1;

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
<<<<<<< HEAD
$p['jp']='savim|clr='.$p['clr'];
=======
<<<<<<< HEAD
$p['jp']='savim|clr='.$p['clr'];
=======
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
return parent::form($p);}

static function edit($p){
return parent::edit($p);}

static function create($p){
return parent::create($p);}

static function savim($p){
$id=$p['id']??'';
<<<<<<< HEAD
$font=$p['font']??'';
$clr=$p['clr']??'ffffff';
$bkg=$p['bkg']??'000000';
$f='img/full/'.self::$a.$id.'.png';
=======
$r=['9','16'];//car-width,line-height
<<<<<<< HEAD
$font=$p['font']??'Fixedsys';
$clr=$p['clr']??'ffffff';
=======
$font=val($p,'font','Fixedsys');
$clr=val($p,'clr');
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
$url='img/full/'.self::$a.$id.'.png';
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
$txt=str::utf8dec($p['txt']);
self::imgtx($f,$txt,$font,$clr,$bkg);
//self::imtx($txt,$rt[0],$rt[1],$font,$clr,$f);
return img('/'.$f.'?'.randid(),'','');}

#build
<<<<<<< HEAD
static function lines($t,$maxl){$n=0; $rt=[];
//$t=str_replace("\n",' ',$t); 
$r=explode(' ',$t); $nb=0;
=======
<<<<<<< HEAD
static function lines($t,$maxl){$n=0; $rt=[];
//$t=str_replace("\n",' ',$t); 
$r=explode(' ',$t); $nb=0;
foreach($r as $k=>$v){$len=strlen($v); $nb+=$len+1; $pos=strpos($v,"\n");
	if(!isset($rt[$n]))$rt[$n]=''; //else $nb+=strlen($rt[$n]);
	if($nb>$maxl){$nb=strlen($v); $n++; $nbb=floor($nb/$maxl);
		for($i=0;$i<=$nbb;$i++){$rt[$n]=substr($v,$maxl*$i,$maxl); $n++;}}
	elseif($pos!==false){$rt[$n].=substr($v,0,$pos); $n++;
		$rt[$n]=substr($v,$pos+1).' '; $nb=strlen($rt[$n]);}
	else $rt[$n].=trim($v).' ';}
return $rt;}
=======
static function lines($t,$maxl){$n=0;
$t=str_replace("\n",' ',$t); $r=explode(' ',$t); $nb=0; $ret='';
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
foreach($r as $k=>$v){$len=strlen($v); $nb+=$len+1; $pos=strpos($v,"\n");
	if(!isset($rt[$n]))$rt[$n]=''; //else $nb+=strlen($rt[$n]);
	if($nb>$maxl){$nb=strlen($v); $n++; $nbb=floor($nb/$maxl);
<<<<<<< HEAD
		for($i=0;$i<=$nbb;$i++){$rt[$n]=substr($v,$maxl*$i,$maxl); $n++;}}
	elseif($pos!==false){$rt[$n].=substr($v,0,$pos); $n++;
		$rt[$n]=substr($v,$pos+1).' '; $nb=strlen($rt[$n]);}
	else $rt[$n].=trim($v).' ';}
return $rt;}
=======
		for($i=0;$i<=$nbb;$i++){$ret[$n]=substr($v,$maxl*$i,$maxl); $n++;}}
	elseif($pos!==false){$ret[$n].=substr($v,0,$pos); $n++;
		$ret[$n]=substr($v,$pos+1).' '; $nb=strlen($ret[$n]);}
	else $ret[$n].=trim($v).' ';}
return $ret;}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235

/*function imt_mk($p,$n){ 
$l=50; $s=strlen($p); $n=ceil($s/$l); $sz=$s<$l?($s/5)*58:500;
for($i=0;$i<$n;$i++){$v=substr($p,$i*$l,$l); $pos=strpos($v,"\n");
if($pos!==false){$r[]=substr($v,0,$pos); $r[]=substr($p,$pos+1);}
else $r[]=$v;}
return $r;}*/

<<<<<<< HEAD
static function mkim3($r,$p){//pr($p);
[$f,$w,$sz,$lh,$wa,$ha,$clr,$bkg,$fnt]=vals($p,['f','w','sz','lh','wa','ha','clr','bkg','fnt']);
if(!$fnt)$fnt='Lato-Black'; if(!$clr)$clr='ff0000'; if(!$bkg)$bkg='000000';
$n=count($r); $ha=$n*$lh+$sz;
$im=new Imagick();
$im->newImage($wa,$ha,new ImagickPixel('#'.$bkg));
$im->resizeImage($wa,$ha,Imagick::FILTER_LANCZOS,1);
//$im->adaptiveResizeImage($wa,$ha,1);
$draw=new ImagickDraw();
$draw->setFont('fonts/'.$fnt.'.woff');
$draw->setFontSize($sz);
$draw->setFillColor('#'.$clr);
if($r)foreach($r as $k=>$v){$fx=$k*$w; $fy=$k*$lh+$sz;
	$im->annotateImage($draw,10,$fy,0,$v);}
//echo img64($im);
$im->writeImage($f);
}

static function mkim2($r,$f,$w,$h,$wa,$ha,$clr,$bkg,$fnt){
if(!$fnt)$fnt='Lato-Black';
/*$im=new imk();
$im->text('Hello World !','Lato-Black','black',20,80,80,0,'');
$im->save($f);*/
$ob=img::obj();
$ob=img::newimg($ob,0,100,'red');
img::text($ob,'Hello World !','Lato-Black','black',20,80,80,0,'');
img::save($ob,$f);
}

static function mkim($r,$f,$w,$h,$wa,$ha,$clr,$bkg,$fnt){
if(!$fnt)$fnt='Fixedsys';
$clr=$clr?$clr:'000000'; [$rh,$gh,$bh]=rgb($clr);
$im=imagecreate($wa,$ha);
=======
static function imgtx($t,$lac,$hac,$fnt,$clr,$url){
<<<<<<< HEAD
$t=str_replace("&nbsp;",' ',$t); $r=[]; $fx=''; $fy=''; $l=8;
$nb_chars=strlen($t); $width=500;
if($lac && $width)$maxl=floor($width/$lac); else $maxl=50;
$la=$nb_chars*$lac; 
$la=$la>$width-$l?$width-$l:$la;
=======
$t=str_replace("&nbsp;",' ',$t); $r=[]; $fx=''; $fy='';
$nb_chars=strlen($t); $width=500;
if($lac && $width)$maxl=floor($width/$lac); else $maxl=50;
$la=$nb_chars*$lac; 
$la=$la>$width-8?$width-8:$la;
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
if($nb_chars>$maxl or strpos($t,"\n")!==false)$r=self::lines($t,$maxl);
//$r=self::imt_mk($t); p($r);
$ha=$r?$hac*count($r):($hac?$hac:20); $clr=$clr?$clr:'000000';
//$rh=hexdec(substr($clr,0,2));$gh=hexdec(substr($clr,2,2));$bh=hexdec(substr($clr,4,2));
[$rh,$gh,$bh]=rgb($clr);
$im=imagecreate($la,$ha);
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
$blanc=imagecolorallocate($im,255,255,255);
$color=imagecolorallocate($im,$rh,$gh,$bh);
$font=imageloadfont('fonts/gdf/'.$fnt.'.gdf');
if($r)foreach($r as $k=>$v){$fx=$k*$w; $fy=$k*$h;
	imagestring($im,$font,1,$fy,$v,$color);}
else imagestring($im,$font,$fx?$fx:0,$fy?$fy:0,$t,$color);
imagecolortransparent($im,$blanc);
imagepng($im,$f);}

static function imgtx($f,$t,$fnt,$clr,$bkg){
$t=str_replace("&nbsp;",' ',$t); $rt=[]; $fx=''; $fy='';
$sz=20; $fw=16; $lh=30;//fontsize,fontwidth,lineheight
$n=strlen($t); $width=500;
if($fw && $width)$maxl=floor($width/$fw); else $maxl=50;
$wa=$n*$fw;
$wa=$wa>$width-$fw?$width-$fw:$wa;
if($n>$maxl or strpos($t,"\n")!==false)$rt=self::lines($t,$maxl);
//$rt=self::imt_mk($t); p($rt);
$ha=$rt?$lh*count($rt):($h?$h:20);
//$rh=hexdec(substr($clr,0,2));$gh=hexdec(substr($clr,2,2));$bh=hexdec(substr($clr,4,2));
//self::mkim($rt,$f,$fw,$h,$wa,$ha,$clr,$bkg,$fnt);
$pr=['f'=>$f,'w'=>$width,'sz'=>$sz,'lh'=>$lh,'wa'=>$wa,'ha'=>$ha,'clr'=>$clr,'bkg'=>$bkg,'fnt'=>$fnt];
self::mkim3($rt,$pr);
}

static function build($p){$id=$p['id']??'';
return sql('uid,txt',self::$db,'ra',$id);}

static function play($p){
$id=$p['id']??'';
$f='img/full/'.self::$a.$id.'.png';
return img('/'.$f.'?'.randid(),'','');}

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
<<<<<<< HEAD
self::install();
=======
<<<<<<< HEAD
self::install();
=======
//self::install();
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
return parent::content($p);}
}
?>