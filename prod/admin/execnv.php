<?php

class execnv{
static $private=6;
static $a=__CLASS__;
static $db='';
static $cb='cnv';

static function admin(){return admin::app(['a'=>self::$a]);}

static function build($p){//bcscale(20);
$rid=$p['rid']??''; $code=val($p,'code'.$rid); $ind=val($p,'ind',date('ymd'));
//if(!auth(6))$code=str_replace('sql','xxx',$code);
//$code=str_replace('--','echo "<br>";'.n(),$code);
$f='disk/_/js/'.$ind.'.js'; mkdir_r($f);
$d=html_entity_decode($code); write_file($f,$d);
$ret=tag('canvas',['id'=>self::$cb],'');
$ret.=head::jscode($d);
return $ret;}

static function read($p){$ind=val($p,'ind',date('ymd'));
$f='disk/_/js/'.$ind.'.js'; $ret=read_file($f);
if($ret)return substr($ret,6);}

static function edit($p){//from scene
$rid=val($p,'rid',randid('md')); $ind=val($p,'ind',date('ymd'));
$d=self::read($p);
$j=$rid.',,z|execnv,build|rid='.$rid.',ind='.$ind.'|code'.$rid;
//$j='injectJs|execnv,build|rid='.$rid.',ind='.$ind.'|code'.$rid;
$ret=bj($j,lang('exec'),'btsav');
$ret.=bj('input,code'.$rid.'|execnv,read|ind='.$ind,$ind,'btn').br();
$ret.=textareact('code'.$rid,$d,40,30,$j);
return $ret;}

static function call($p){//from appx
$code=$p['code']??'';
$ret=self::build(['code'=>$code,'ind'=>val($p,'ind')]);
return textarea('',$ret,40,30);}

static function content($p){
$p['rid']=randid('md'); $ind=val($p,'ind',date('ymd'));
$j='input,code'.$p['rid'].'|execnv,read||ind'; $dr='disk/_/js/';
$r=scan_dir($dr); mkdir_r($dr);
if($r)foreach($r as $k=>$v)$r[$k]=substr($v,0,-4); $r=array_reverse($r);
$ret=datalistcall('ind',$r,'',$j,'',16); //$ret=input('ind','');
$ret.=bj($j,lang('call',1),'btsav').' ';
$ret.=self::edit($p);
$s='grid-column:2; grid-template-columns: 50% 50%;';
return div(div($ret,'col1').div('','col2 board',$p['rid']),'grid wrapper','',$s);}
}
?>