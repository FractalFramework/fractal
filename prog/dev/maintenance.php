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
//$d=sql('txt','multilang','v',150); sql::upd('book_chap',['txt'=>$d],28);
return $ret;}

static function newhasher($p){$ret='';//book_chap,comic_cases,note,slide_r,stext,stx,sys,syslib,tlex,tlex_web,userguide,vector,
	$b=$p['inp1']??''; $rb=[];
	$r=applist::allapps(); //p($r);
	foreach($r as $k=>$v){
		$cls=isset($v::$cols)?$v::$cols:[];
		if(in_array('txt',$cls))$rb[]=$v;
	}
	if($b)sql::qr('UPDATE '.$b.' SET `txt`=REPLACE(txt,"§","|");',1);
	else foreach($rb as $k=>$v)qr('UPDATE '.$v.' SET `txt`=REPLACE(txt,"§","|");',1);
	//$r=applist::build();
	//pr($rb);
    //$b=$p['inp1']??self::$db;
    //$d=sql('txt',$b,'ra','');
    //$d=str_replace('|','|',$d);
    //sql::upd($b,['txt'=>$d],28);
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
$bt=input('inp1','','','1');
$bt.=bj(self::$cb.'|maintenance,call|op='.$op.'|inp1',lang('send'),'btn');
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>