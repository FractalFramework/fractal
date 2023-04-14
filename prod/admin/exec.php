<?php

class exec{
static $private=6;
static $a=__CLASS__;
static $cb='exc';

static function admin(){return admin::app(['a'=>self::$a]);}
static function js(){return;}

static function build($p){$ret=''; bcscale(20);
$rid=$p['rid']??''; $code=$p['code'.$rid]??''; $ind=$p['ind']??date('ymd');
if(!auth(6))$code=str_replace('sql','xxx',$code);
$code=str_replace('--','echo "<br>";'.n(),$code);
$f='disk/_/'.$ind.'.php'; mkdir_r($f);
write_file($f,'<?php '.html_entity_decode($code));
require $f; return $ret;}

static function read($p){$ind=$p['ind']??date('ymd');
$f='disk/_/'.$ind.'.php'; $ret=read_file($f);
if($ret)return substr($ret,6);}

static function edit($p){//from scene
$rid=$p['rid']??randid('md'); $ind=$p['ind']??date('ymd'); $d=$p['code']??'';
if(!$d)$d=self::read($p); $j=$rid.',,z|exec,build|rid='.$rid.',ind='.$ind.'|code'.$rid;
$ret=bj($j,lang('exec'),'btsav');
$ret.=bj('input,code'.$rid.'|exec,read|ind='.$ind,$ind,'btn').br();
$ret.=textareact('code'.$rid,$d,40,34,$j);
return $ret;}

static function call($p){//from appx
$code=$p['exec']??($p['p1']??'');
//$code=sql('exec','scene','v',$p['id']??'');
$ret=self::build(['code'=>$code,'ind'=>$p['ind']??date('ymd')]);
return textarea('',$ret,40,34);}

static function com($d){//from conn
return self::call(['code'=>$d]);}

static function menu($p){
$ind=$p['ind']??date('ymd');
$j='input,code'.$p['rid'].'|exec,read||ind';
$dr='disk/_/'; $r=scan_dir($dr);// mkdir_r($dr);
if($r)foreach($r as $k=>$v)$r[$k]=substr($v,0,-4); if($r)$r=array_reverse($r);
$ret=datalistcall('ind',$r,'',$j,'',16); //$ret=input('ind','');
$ret.=bj($j,lang('call',1),'btsav').' ';
return $ret;}

static function content($p){
$p['rid']=randid('md');
$ret=self::menu($p);
$ret.=self::edit($p);
$c='col2 paneb scroll'; $id=$p['rid']; $s='max-height:800px;';
$c2='grid wrapper'; $s2='grid-column:2; grid-template-columns: 45% 55%; min-width:596px;';
//$r=['div',[['div',$ret,'col1'],['div','',$c,$id,$s]],$c2,'',$s2];
//return mktags($r);
return div(div($ret,'col1').div('',$c,$id,$s),$c2,'',$s2);}
}
?>
