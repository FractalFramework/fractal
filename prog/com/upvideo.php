<?php
class upvideo{	
static $private=0;
static $db='upvideo';
static $cb='upv';

#call
//http://logic.ovh/frame/video/'.$f.'/'.$fid;http://rutube.ru/play/embed/12509941
static function call($p){
//self::install();
$d=val($p,'f'); $o=val($p,'o'); $nm=val($p,'nm');
if(!$d)$f=http(val($p,'up')); else $f=jurl2($d,1);
$u=urlencode($d); $fid=$o=='fb'?strprm($f,'_',4):''; $r=explode('_',$fid);
if($o=='fb')$fa='https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2F'.$r[0];
elseif($o=='tw')$fa='https://video.twimg.com/ext_tw_video/'.$r[0].'/pu/vid/1280x720/'.$r[1].'.mp4';
else $fa=$u;
$fb='usr/videos/'.$o.'_'.$fid.'.mp4'; mkdir_r($fb);
if(!is_file($fb))copy($fa,$fb);
if(is_file($fb))return video($fb);}

#content
static function content($p){
$bt=inputcall('upv|upvideo||up','up',val($p,'up','url'));
$ret=self::call($p);
return $bt.div($ret,'','upv');}

static function iframe($p){
return self::content($p);}
}
?>