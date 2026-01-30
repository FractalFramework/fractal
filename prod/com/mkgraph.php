<?php

//application not based on appx
class mkgraph{	
static $private=2;
static $a=__CLASS__;
static $db='graphs';
static $cols=['tit','txt'];
static $typs=['var','text'];
static $cb='mdb';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['id'=>$id]);//'uid'=>ses('uid')
return $r;}

#play
static function play($p,$r){$ret=''; //pr($r);
if($r)foreach($r as $k=>$v){$rb=[];
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
return $ret;}

#call
static function call($p){
$rp['com']=$p['com']??'';
$rp['typ']=$p['typ']??''; $opt=$p['opt']??'';
$rp['dk']=strpos($opt,'dates-keys')!==false?1:0;
$rp['dv']=strpos($opt,'dates-values')!==false?1:0;
$rp['ad']=strpos($opt,'adapt-height')!==false?1:0;
$rp['lb']=strpos($opt,'labels')!==false?1:0;
return graphs::call($rp);
$r=self::build($p);
$ret=self::play($p,$r);
if(!$ret)return help('no element','txt');
return $ret;}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||com,typ,opt';
$ret=bj($j,langp('send'),'btn');
$ret.=radio('typ',['histo','lines','boxes'],2,1,0);
$ret.=checkbox('opt',['dates-keys','dates-values','adapt-height','labels'],'',1,0);
$ret.=div(textarea('com',$p['p1']??'',32));
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['param']??$p['p1']??'';
$p['p1']='201217,18,25,30,38
201225,21,31,38,49
210102,24,35,45,61
210111,29,43,51,73
210119,33,50,61,92
210127,37,56,71,100';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>