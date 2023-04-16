<?php

class maintenance{//todo:multicron
static $private=6;
static $db='model';
static $a='maintenance';
static $cb='mnt';

static function install(){
sql::create(self::$db,['tit'=>'var','txt'=>'bvar'],0);}

static function admin(){
$r[]=['','j','popup|maintenance,content','plus',lang('open')];
return $r;}

static function js(){return '
var r=["az","ae","rt","rz","rzr","rze","ey"];
var ret=r.sort();
//alert(JSON.stringify(ret));';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function titles($p){
$d=$p['_m']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#play
static function play($p){
$ret=lang('result').': '.val($p,'v1').' '.val($p,'inp1');
return $ret;}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'ra',$id);
return $r;}

static function op($p){$ret='';
//$d=sql('txt','multilang','v',150); sql::up('book_chap','txt',$d,28);
return $ret;}

static function newhasher($p){$ret='';
    $b=$p['inp1']??self::$db;
    $d=sql('txt',$b,'ra','');
    $d=str_replace('§','|',$d);
    //sql::up($b,'txt',$d,28);
return $ret;}

#call
static function call($p){$ret='';
$op=$p['op']??'play';
//$r=self::build($p);
//$ret=self::play($p);
$ret=self::$op($p);
return $ret;}

static function com(){
return self::content($p);}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$op='newhasher';//
$bt=input('inp1','value1','','1');
$bt.=bj(self::$cb.'|maintenance,call|v1=hello,op='.$op.'|inp1',lang('send'),'btn');
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>