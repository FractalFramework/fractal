<?php
class imk{
public $im;

function __constuct(){$this->obj();}

public function obj(){
$this->im=new Imagick();}

public function newimg($x=0,$y=100,$c='red'){
$this->im->newImage($x,$y,new ImagickPixel($c));}

public function png(){
$this->im->setImageFormat('png');}


public function resize($w,$h){
$this->im->resizeImage(200,200,Imagick::FILTER_LANCZOS,1);}

public function resize_adapt($w,$h){
$this->im->adaptiveResizeImage($w,$h,1);}

public function text($t,$fnt,$clr,$sz,$x,$y,$z,$cs=''){
$draw=new ImagickDraw();
$draw->setFillColor($clr);
$draw->setFontSize($sz);
if($cs)$draw->setStrokeColor($cs);
$draw->setStrokeWidth(1);
//$draw->setFont('fonts/Ubuntu.woff');
$draw->setFont('fonts/'.$fnt.'.woff');
$this->im->annotateImage($draw,$x,$y,$z,$t);}

public function blur($radius,$sigma,$channel){//{'red','green','blue','alpha','all'}
$this->im->adaptiveBlurImage($radius,$sigma,$channel);}

public function sharp($radius,$sigma,$channel){
$this->im->adaptiveSharpenImage($radius,$sigma,$channel);}

public function threshold($w,$h,$offset){
$n=intval($offset*\Imagick::getQuantum());
$this->im->adaptiveThresholdImage($w,$h,$n);}

public function convert($im,$nm,$xt='jpg'){
$this->im->readImageFile($im);
$this->im->setFormat($xt);
$this->im->setFileName($nm.'.'.$xt);}

public function mini($f){
//$f='1768030188589.jpg';
//echo $_REQUEST['codemd19fti8'];
//$ob=self::obj('img/full/'.$f);
$this->im->resize_adapt('200','200');
$this->im->text('Hello World !','Lato-Black','black',20,80,80,0,'');
$this->im->blur(20,10,1);
$this->im->blob();
$this->im->render();}

public function save($f){
$this->im->writeImage($f);}

public function blob(){
//header('Content-Type: image/jpeg');
$this->im->getImageBlob();}

public function render(){
header('Content-Type: image/jpeg');
$this->im->getImageBlob();
return img64($this->im);}

}
?>