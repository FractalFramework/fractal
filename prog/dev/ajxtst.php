<?php

//application not based on appx
class ajxtst{
static $private=0;
static $a=__CLASS__;
static $db='ajxtst';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='mdb';

static function install(){
sqlcreate(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function injectJs(){return;}
static function headers(){
add_head('csscode','');
add_head('jscode',self::injectJs());}

#build
static function build($p){$id=$p['id']??''; return [];//!
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p,$r){$ret=''; //pr($r);
if($r)foreach($r as $k=>$v){$rb=[];
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
return $ret;}

#call
static function call($p){
$r=self::build($p);
$ret=self::play($p,$r);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||p1'; $p1=$p['p1']??'';
//$bt=bj($j,langp('ok'),'btn');
//$ret=$bt.textarea('p1','',60,4);
//$ret=inputcall($j,'p1',$p['p1']??'',32).$bt;
//$ret=form::call(['p1'=>['inputcall','url',$p1,$j],['submit','ok',$j,'']]);
$ret=form::call(['p1'=>['input','url',$p1,''],'p2'=>['radio','choice1','one',['one','two']],'p3'=>['checkbox','choice1','one',['one','two']],['submit','ok',$j,'']]);
/*$j=self::$cb.'|ajxtst,call||form';
$r=[['type'=>'text','name'=>'tit','value'=>'title','label'=>'tit'],
['type'=>'textarea','name'=>'txt','value'=>'text','label'=>'txt'],
['type'=>'checkbox','name'=>'check','value'=>'chk1','label'=>'chk1'],
['type'=>'checkbox','name'=>'check','value'=>'chk2','label'=>'chk2'],
['type'=>'radio','name'=>'radio','value'=>'rad1','label'=>'rad1'],
['type'=>'radio','name'=>'radio','value'=>'rad2','label'=>'rad2'],];
$ret=mkform('form',$j,$r);*/
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>