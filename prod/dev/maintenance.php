<?php

class maintenance{//todo:multicron
static $private=6;
static $db='_model';
static $a='maintenance';
static $cb='mnt';

static function install(){
sqlcreate(self::$db,['tit'=>'var','txt'=>'bvar'],0);}

static function admin(){
$r[]=['','j','popup|maintenance,content','plus',lang('open')];
return $r;}

static function injectJs(){return '
var r=["az","ae","rt","rz","rzr","rze","ey"];
var ret=r.sort();
//alert(JSON.stringify(ret));';}
static function headers(){
add_head('csscode','');
add_head('jscode',self::injectJs());}

static function titles($p){
$d=$p['appMethod']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#play
static function play($p){
$ret=lang('result').': '.val($p,'v1').' '.val($p,'inp1');
return $ret;}

#build
static function build($p){$id=val($p,'id');
$r=sql('all',self::$db,'ra',$id);
return $r;}

static function op($p){$ret='';
//$d=sql('txt','multilang','v',150); sqlup('book_chap','txt',$d,28);
return $ret;}

#call
static function call($p){$ret='';
//$r=self::build($p);
$ret=self::play($p);
//$ret=self::op($p);
return $ret;}

static function com(){
return self::content($p);}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=input('inp1','value1','','1');
$bt.=bj(self::$cb.'|maintenance,call|v1=hello|inp1',lang('send'),'btn');
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>