<?php

//edit js of app
class editjs{
static $private=6;
static $a=__CLASS__;
static $db='';
static $cb='exj';

static function admin(){return admin::app(['a'=>self::$a]);}

static function build($p){//pr($p);
$a=$p['ind']??''; $rid=$p['rid']; $code=val($p,'code'.$rid); $f=dev::opn($a);
dev::savefunc(['f'=>$f,'fc'=>'js','nwc'=>$code]);
return app($a,['headers'=>1]);}

static function read($p){$a=$p['ind'];
$f=dev::opn($a); $d=read_file($f);
return innerfunc($d,'js');}

static function edit($p){//from scene
$rid=val($p,'rid',randid('md')); $ind=$p['ind']??'';
if($ind)$d=self::read($p); else $d='';
$j=$rid.'|editjs,build|rid='.$rid.'|ind,code'.$rid;
//$j='injectJs|editjs,build|rid='.$rid.'|ind,code'.$rid;
$ret=bj($j,lang('exec'),'btn').br();
$ret.=textareact('code'.$rid,$d,40,30,$j);
return $ret;}

static function call($p){//from appx
$code=val($p,'editjs');
$ret=self::build(['code'=>$code,'ind'=>$p['ind']??'']);
return textarea('',$ret,40,30);}

static function content($p){
$p['rid']=randid('md'); $ind=val($p,'ind');
$j='input,code'.$p['rid'].'|editjs,read||ind';
$ret=inputcall($j,'ind','',16);
$ret.=bj($j,lang('call',1),'btsav').' ';
$ret.=self::edit($p);
$s='grid-column:2; grid-template-columns: 50% 50%;';
return div(div($ret,'col1').div('','col2 board',$p['rid'],''),'grid wrapper','',$s);}
}
?>