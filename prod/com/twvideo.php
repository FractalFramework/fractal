<?php

class twvideo{	
static $private=2;
static $db='twvideo';
static $cols=['tit','txt'];
static $typs=['var','text'];
static $a='twvideo';
static $cb='mdb';

static function install(){
sql::create(self::$db,[array_combine(self::$cols,self::$typs)],0);}


static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}

static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function titles($p){
$d=$p['_m']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p){
$ret=lang('result').': '.val($p,'v1').' '.val($p,'inp1');
return $ret;}

#stream
static function stream($p){
$r=self::build($p); $ret='';
if($r)foreach($r as $k=>$v){$rb=[];
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
if(!$ret)return help('no element','txt');
return div($ret,'');}

#call
static function call($p){
return self::stream($p);}

static function com($p){
$j=self::$cb.'|twvideo,play|v1=hello|inp1';
$bt=bj($j,langp('send'),'btn');
return inputcall($j,'inp1',$p['p1']??'',32).$bt;}

#content
static function content($p){
//self::install();
$f=$p['p1']??'';//1109149865387401216_pu_vid_640x360_VSYp0UCxMxaNZmNB
//http://logic.ovh/frame/video/'.$f;
$f=str_replace('(dot)','.',$f);
[$f,$xt]=split_one('.',$f,1); if(!$xt)$xt='mp4';
$fa='https://video.twimg.com/ext_tw_video/'.str_replace('_','/',$f).'.'.$xt;//
//if(!is_file($fa))$fa='https://video.twimg.com/ext_tw_video/'.str_replace('_','/',$f).'.m3u8';
$fb='usr/videos/'.strprm($f,'_',4).'.'.$xt; mkdir_r($fb);//
if(!is_file($fb))copy($fa,$fb);
if(is_file($fb))return video($fb);}

static function iframe($p){
return self::content($p);}
}
?>