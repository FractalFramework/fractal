<?php

class nnim{	
static $private=2;
static $db='nnim';
static $a='nnim';
static $cb='mdb';

static function install(){
sql::create(self::$db,['t'=>'svar'],0);}

static function admin(){
$r[]=['','j','popup|nnim,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=nnim_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=nnim','code','Code'];
return $r;}

static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}


#play
static function play($p){
$ret=val($p,'inp1');
return $ret;}

#call
static function call($p){$i=val($p,'i'); $d=$p['p']??'';
$f='http://newsnet.fr/call.php?plug=backupim&fc=port&p='.$i.'&o=1';
$fb='usr/nnim/'.$d.'.gz.tar';
//unlink($fb);
if(!is_file($fb)){echo $fa=get_file($f); if($fa)$ok=copy($fa,$fb);}
return $fb;}

static function com($p){
$n=232224; $l=5000; $n/=$l; $ret=''; mkdir_r('usr/nnim');
for($i=0;$i<$n;$i++){
	$min=$i*$l; $max=$min+$l;
	$d='img_'.$min.'-'.$max;
	$f='usr/nn/'.$d.'.gz.tar';
	if(is_file($f))$c='active'; else $c='';
	$ret.=bj(self::$cb.',,z|nnim,call|i='.$i.',p='.$d,$i*$l).' ';}
return div($ret,'nbp');}

#content
static function content($p){$ret='';
//self::install();
$bt=self::com($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>