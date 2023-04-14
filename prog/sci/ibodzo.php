<?php

//application not based on appx
class ibodzo{	
static $private=2;
static $a=__CLASS__;
static $db='ibodzo';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='mdb';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p){$ret=''; //pr($r);
[$iu1,$iu2,$iu3,$iu4]=vals($p,['iu1','iu2','iu3','iu4']);

return $ret;}

#call
static function call($p){
//$r=self::build($p);
$ret=self::play($p);
if(!$ret)return help('no element','txt');
return $ret;}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||iu1,iu2,iu3,iu4';
$ret=input('iu1','',4,'x',1,3,$j);
$ret.=input('iu2','',4,'y',1,3,$j);
$ret.=input('iu3','',4,'width',1,3,$j);
$ret.=input('iu4','',4,'clr',1,3,$j);
$ret.=bj($j,langp('ok'),'btn');
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['param']??$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>