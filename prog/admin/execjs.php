<?php

class execjs{
static $private=6;
static $a=__CLASS__;
static $db='';
static $cb='exj';

static function admin(){return admin::app(['a'=>self::$a]);}

static function js(){}
static function headers(){
head::add('jslink','/js/canvas.js');
head::add('jscode',self::js());}

static function build($p){$ret=''; bcscale(20);
$rid=$p['rid']??''; $code=$p['code'.$rid]; $ind=$p['ind']??date('ymd');
$code=str_replace('{rid}',$rid,$code);
$f='disk/_/js/'.$ind.'.js'; mkdir_r($f);
$ret=html_entity_decode($code);
write_file($f,$ret);
$ret='var ob=getbyid("'.$rid.'");'.n().$ret;
return ($ret);}//jscode

static function read($p){$ind=$p['ind']??date('ymd');
$f='disk/_/js/'.$ind.'.js'; $ret=read_file($f);
if($ret)return substr($ret,6);
else return 'pr(ob); ob.innerHTML="hello";';}

static function edit($p){//from scene
$rid=val($p,'rid',randid('md')); $ind=$p['ind']??date('ymd');
$d=self::read($p);
//$j=$rid.',,z|execjs,build|rid='.$rid.',ind='.$ind.'|code'.$rid;
$j='injectJs|execjs,build|rid='.$rid.',ind='.$ind.'|code'.$rid;
$ret=bj('input,code'.$rid.'|execjs,read|ind='.$ind,$ind,'btn').' ';
$ret.=bj($j,lang('exec'),'btsav');
$ret.=div(textareact('code'.$rid,$d,40,30,$j));
return $ret;}

static function call($p){//from appx
$code=$p['execjs'];
$ret=self::build(['code'=>$code,'ind'=>$p['ind']]);
return textarea('',$ret,40,30);}

static function content($p){
$p['rid']=randid('md'); $ind=$p['ind']??date('ymd');
$j='input,code'.$p['rid'].',,1|execjs,read||ind'; $dr='disk/_/js/';
$r=scan_dir($dr); mkdir_r($dr);
if($r)foreach($r as $k=>$v)$r[$k]=substr($v,0,-4); $r=array_reverse($r);
$ret=datalistcall('ind',$r,'',$j,'',16); //$ret=input('ind','');
$ret.=bj($j,lang('call',1),'btn');
$ret.=self::edit($p);
return div(div($ret,'col1').div('','col2 board',$p['rid'],''),'grid wrapper','','grid-column:2; grid-template-columns:50% 50%;');}
}
?>