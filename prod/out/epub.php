<?php

class epub{	
static $private=6;
static $a=__CLASS__;
static $cb='ebk';

#build
static function css($bkg){return '
body{border:0; margin:0; padding:0;}
a{color:inherit; text-decoration:underligne;}
img{border:0;}
blockquote{padding:16px; border:1px solid grey; border-radius:6px; background-image:linear-gradient(rgba(119,119,119,0.15),rgba(119,119,119,0.2)); box-shadow:0px 0px 18px 0px rgba(119,119,119,0.4); border-radius:4px; border-width:2px; border-style:solid; border-color:rgba(119,119,119,0.4);}
.cover{padding:80px 40px; color:white; '.theme($bkg).'
background-repeat:no-repeat; background-attachment:fixed;}';}

static function manifest($r,$rb,$dr,$ti,$rim){$n=count($rb); $lg='fr';
$d='<?xml version="1.0" encoding="utf-8"?>
<package unique-identifier="unique-identifier" version="3.0" xmlns="http://www.idpf.org/2007/opf" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:opf="http://www.idpf.org/2007/opf"><metadata><dc:identifier id="unique-identifier">4d07bf09-0a07-4c49-8f91-bcf5adcebad1</dc:identifier>
<dc:title>'.$r['tit'].'</dc:title>
<dc:creator>'.$r['name'].'</dc:creator>
<dc:language>'.$lg.'</dc:language>
<meta property="dcterms:modified">'.date('Y-m-d\TH:i:s').'</meta>
<meta content="fractal" name="generator"/>
</metadata><manifest>
<item href="toc.xhtml" id="toc.xhtml" media-type="application/xhtml+xml" properties="nav"/>
<item href="styles/stylesheet.css" id="stylesheet.css" media-type="text/css"/>
<item href="images/image0001.jpg" id="image0001" media-type="image/jpeg" properties="cover-image"/>
<item href="toc.ncx" id="toc.ncx" media-type="application/x-dtbncx+xml"/>';
$im=imgroot($r['img']); if(is_file($im))copy($im,'usr/_epub/OEBPS/images/image0001.jpg');
for($i=1;$i<=$n;$i++){$ib=str_pad($i,4,0,STR_PAD_LEFT);
	$d.='<item href="sections/section'.$ib.'.xhtml" id="section'.$ib.'" media-type="application/xhtml+xml"/>';
	if(isset($rim[$i]))$d.=implode('',$rim[$i]);}
$d.='</manifest>
<spine toc="toc.ncx">'; //eco($d);
for($i=1;$i<=$n;$i++){$ib=str_pad($i,4,0,STR_PAD_LEFT);
	$d.='<itemref idref="section'.$ib.'"/>';}
$d.='</spine></package>';
write_file($dr.'/content.opf',$d);
//
$d='<?xml version="1.0" encoding="utf-8"?>
<ncx version="2005-1" xmlns="http://www.daisy.org/z3986/2005/ncx/"><head><meta content="" name="" scheme=""/></head><docTitle><text/></docTitle><navMap>';
//$d.='<navPoint class="document" id="section1" playOrder="1"><navLabel><text>Section 1</text></navLabel><content src="sections/section0001.xhtml"/></navPoint>';//section1
foreach($rb as $k=>$v){$i=$k+1; $ib=str_pad($i,4,0,STR_PAD_LEFT);
	$d.='<navPoint class="document" id="section'.$i.'" playOrder="'.$i.'"><navLabel><text>'.$v['chapter'].'</text></navLabel><content src="sections/section'.$ib.'.xhtml"/></navPoint>';}
$d.='</navMap></ncx>';
write_file($dr.'/toc.ncx',$d);
//
$d='<?xml version="1.0" encoding="utf-8"?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops"><head/><body><nav epub:type="toc"><ol>';
//$d.='<li><a href="sections/section0001.xhtml">Section 1</a></li>';//section1
foreach($rb as $k=>$v){$i=$k+1; $ib=str_pad($i,4,0,STR_PAD_LEFT);
	$d.='<li><a href="sections/section'.$ib.'.xhtml">'.$v['chapter'].'</a></li>';}
$d.='</ol></nav></body></html>';
write_file($dr.'/toc.xhtml',$d);}

static function build($r,$rb,$fa){
$ret=''; $dy=date('ymd'); $ti=$r['tit']; $author=$r['name']; $rim=[];//rmdir_r('usr/dl/epub/');
$dr='usr/_epub'; rmdir_r($dr); //$gz=$dr.'zip'; //gz_write2();
mkdir_r($dr); mkdir_r($dr.'/OEBPS'); mkdir_r($dr.'/META-INF');
$d='application/epub+zip'; write_file($dr.'/mimetype',$d);
$d='<?xml version="1.0" encoding="utf-8"?>
<container version="1.0" xmlns="urn:oasis:names:tc:opendocument:xmlns:container"><rootfiles><rootfile full-path="OEBPS/content.opf" media-type="application/oebps-package+xml"/></rootfiles></container>';
write_file($dr.'/META-INF/container.xml',$d);
mkdir_r($dr.'/OEBPS/images'); mkdir_r($dr.'/OEBPS/sections'); mkdir_r($dr.'/OEBPS/styles');
write_file($dr.'/OEBPS/styles/stylesheet.css',self::css($r['bkg']));
if($r)foreach($rb as $k=>$v){$i=$k+1;
	$f=$dr.'/OEBPS/sections/section'.str_pad($i,4,0,STR_PAD_LEFT).'.xhtml'; $ri=[];
	$rt=tag('h1','',$v['chapter']);//maths::roman($v['idn']).' - '.
	$rt.=conn::call(['msg'=>$v['txt'],'mth'=>'minconn','ptag'=>1,'opt'=>'epub']);
	//img for manifest
	if(isset(conn::$obj['img']))$ri=conn::$obj['img']; //pr($ri);
	if($ri)foreach($ri as $kb=>$vb){$im=$vb[0]; $xt=xt($im);
		if($xt=='svg')$xt='svg+xml'; elseif($xt=='jpg')$xt='jpeg';
		$rim[$i][]='<item href="images/'.$im.'" id="'.$im.'" media-type="image/'.$xt.'"/>';}
	$rt=str_replace('usr/_epub/OEBPS/','../',$rt);
	//if($i==1)$rt=div($rt,'cover');
	$doc='<?xml version="1.0" encoding="utf-8"?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
	<link href="../styles/stylesheet.css" rel="stylesheet" type="text/css"/>
	<title>'.$v['chapter'].'</title></head>
<body xmlns:epub="http://www.idpf.org/2007/ops">'.$rt.'</body></html>';
	write_file($f,$doc);}
if($r){self::manifest($r,$rb,$dr.'/OEBPS',$ti,$rim);
	$f='usr/'.$r['name'].'/'.$fa.'.epub';
	if(is_file($f))unlink($f);
	//$lk=tar::buildFromdir($f.'.tar',$dr);
	tar::zip($f,$dr);
	return count($rb).' chapters: '.lk($f);}
else return 'no results';}

#play
static function play($p){$ret=''; //pr($r);
//$r=self::build($p);
//$f=val($p,'inp2');
$f=$p['inp2']??'';
$ret='disk/usr/'.ses('usr').'/'.$f;
//decompose
return $ret;}

#call
static function call($p){
$ret=self::play($p,);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){
$j=self::$cb.'|'.self::$a.',call||inp2';
$ret=inputcall($j,'inp2',$p['p1']??'',32);
$ret.=upload::call('inp2');
$ret.=bj($j,langp('send'),'btn');
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
//$bt=self::com($p);
return div(self::$a,'pane',self::$cb);}
}
?>