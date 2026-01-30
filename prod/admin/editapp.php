<?php

class editapp{
static $private=1;
static $a=__CLASS__;
static $db='edtapp';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='edt';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function url($p){
return applist::url($p);}

static function save($p){
[$p1,$p2]=vals($p,['p1','p2']);
if(auth(6))echo write_file(self::url($p1),'<?php '.$p2);
return self::play($p,$p2);}

#build
static function build($p){
$f=self::url($p['p1']); $ret=read_file($f);
if($ret)return substr($ret,6);}

#play
static function play($p,$d){$ret=''; //pr($p);
[$p1]=vals($p,['p1']);
$j=self::$cb.'|'.self::$a.',save|p1='.$p1.'|p2';
$ret=bj($j,langp('save'),'btn');
$ret.=span('','','edtk');
$ret.=div(textarea('p2',$d,80,30));
return $ret;}

#call
static function call($p){
$d=self::build($p);
$ret=self::play($p,$d);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||p1'; $p1=$p['p1']??'';
$ret=inputcall($j,'p1',$p['p1']??'',32);
$ret.=bj($j,langp('ok'),'btn');
//$ret=form::call(['p1'=>['inputcall',$p1,'url',$j],['submit',$j,'ok','']]);
return $ret;}

#content
static function content($p){
$p['p1']=$p['p1']??''; $ret='';
$bt=self::menu($p);
return $bt.div($ret,'board',self::$cb);}
}
?>