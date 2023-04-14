<?php 
//draft
class video{

static function js(){}

//from u
static function provider($f){
$fa=domain($f); $fb=substr($fa,0,strpos($fa,'.'));
$r=['youtube','youtu','dailymotion','vimeo','rutube'];
if(in_array($fb,$r)!==false)return $fb;}

/*static function exractts($u){
if(strpos($u,'&')){$u=strto($u,'&'); $ts=strfrom($u,'=');}
if(strpos($u,'?')){$u=strto($u,'?'); $ts=strfrom($u,'=');}
return [$id,$ts];}*/

static function extractid($f,$fb=''){
if(!$fb)$fb=self::provider($f); $ret='';
switch($fb){
case('youtube')://if(strpos($f,'channel')!==false)return http($f);
	$p=strpos($f,'v='); $pb=strpos($f,'embed/'); 
	if($p!==false){$ret=substr($f,$p+2); $pe=strpos($f,'&'); if($pe!==false)$ret=substr($ret,0,$pe);}
	elseif($pb!==false){{$ret=substr($f,$pb+6); $pe=strpos($f,'?'); if($pe!==false)$ret=substr($ret,0,$pe);}}
	else $ret=$f; break;
case('youtu'):$p=strrpos($f,'/'); $f=substr($f,$p+1); $pe=strpos($f,'?');
	if($pe!==false)$ret=substr($f,0,$pe); else $ret=$f; break;
case('dailymotion'):$ret=between($f,'video/','-');
	if(!$ret)$ret=substr($f,strpos($f,'video/')+6); break;
case('vimeo'):$ret=substr($f,strrpos($f,'/')+1); break;
case('rutube'):$ret=between($f,'tracks/','.'); break;}
return $ret;}

//from id
static function provider_from_id($d){$nb=strlen($d);
if($nb==32)$ret='rutube';
elseif($nb==11)$ret='youtube'; //elseif($nb==9)$ret='vk';
elseif($nb==7 && is_numeric($d))$ret='rutube';
elseif($nb==5 or $nb==6 or $nb==7 or $nb==18 or $nb==19)$ret='daily';
elseif($nb==36)$ret='peertube';//d2a5ec78-5f85-4090-8ec5-dc1102e022ea
elseif(is_numeric($d))$ret='vimeo'; 
elseif(strpos($d,'_'))$ret='ted';
//else $ret='livestream';
else $ret='';
return $ret;}

static function url($id,$p){$u='';
if($p=='vimeo')$u='https://vimeo.com/'.$id;
elseif($p=='youtube')$u='https://youtube.com/watch?v='.$id;
elseif($p=='daily')$u='https://dailymotion.com/video/'.$id;
elseif($p=='ted')$u='https://embed.ted.com/talks/'.$id;
elseif($p=='peertube')$u='https://framatube.org/videos/watch/'.$id;
return $u;}

static function img($id,$p){$u='';
if($p=='youtube')$u='https://img.youtube.com/vi/'.$id.'/hqdefault.jpg';
//elseif($p=='vimeo')$u='https://vimeo.com/'.$id;
//elseif($p=='daily')$u='https://dailymotion.com/video/'.$id;
//elseif($p=='peertube')$u='https://framatube.org/videos/watch/'.$id;
//elseif($p=='ted')$u='https://embed.ted.com/talks/'.$id;
return $u;}

//build
static function player($id,$p,$ts=''){$w='800px'; $h='450px';
if($p=='youtube' or $p=='youtu')return iframe('http://www.youtube.com/embed/'.$id.'?border=0&version=3&autohide=1&showinfo=0&rel=0&fs=1&t='.$ts,$w,$h);
elseif($p=='daily')return iframe('http://www.dailymotion.com/embed/video/'.$id,$w,$h);
elseif($p=='vimeo'){return iframe('http://player.vimeo.com/video/'.$id,$w,$h);}
elseif($p=='rutube')return '<embed src="http://video.rutube.ru/'.$id.'" type="application/x-shockwave-flash" wmode="window" width="100%" height="auto" allowFullScreen="true">';
elseif(strpos($id,'.mp4'))return video($id);}

static function playbt($u,$t=''){
$vid=''; $rid='opn'.randid(); $ret=''; $p=''; $ts='';
if(strpos($u,'&')){$ts=strfrom($u,'='); $u=strto($u,'&');}
if(strpos($u,'?')){$ts=strfrom($u,'='); $u=strto($u,'?');}
if(substr($u,-4)=='.mp4'){$ic='film'; $r=['','','']; $id='';}
elseif(substr($u,0,4)=='http'){$p=self::provider($u); $id=self::extractid($u,$p);}
else{$p=self::provider_from_id($u); $id=$u; $u=self::url($id,$p);}
if($p){$r=web::build($u,0); $ic='youtube-play';}//tit,txt,img
$dom=domain($u); $tb=$t?$t:($r[0]?$r[0]:$dom); $url=lk($u,pic('url'),'btxt',1);//.$dom
if($p)$j='video,call|p1='.$p.',id='.$id.',ts='.$ts;
else $j='video,call|id='.jurl($u).',ts='.$ts;
if($t)return pagup($j,ico('youtube-play',16).' '.$t,'');//popup
if($r[2])$ret.=playimg($r[2],'mini',1);
if(conn::$opn)$ret.=pagup($j,span(ico($ic,16).' '.$tb,'apptit'),'appicon').div($r[1],'stxt');
else $ret.=toggle($rid.',,z|'.$j,span(ico($ic).' '.$tb,'apptit'),'appicon').div($r[1],'stxt');
$ret.=div('','',$rid).div('','clear');//div($url).
return $ret;}

static function mkconn($u){
$p=self::provider($u); if($p)$id=self::extractid($u,$p);
if($p && $id)return '['.$id.':video]';}//*'.$p.'

static function bt($u){
$p=self::provider($u); $id=self::extractid($u,$p);
if($p && $id)return bj('popup|video,call|p1='.$p.',id='.$id,picto('video').' '.$p,'btn');
else return lk($u,domain($u)."&nbsp;".picto('get'),'btxt',1);}

static function lk($d,$t=''){
$p=self::provider_from_id($d); $u=self::url($d,$p);
return lk($u,ico('external-link').($t?$t:$p),'',1);}

static function call($p){
$id=jurl($p['id']??'',1); $pv=$p['p1']??''; $ts=$p['ts']??'';
if(!$pv)$pv=self::provider($id); if(!$pv)$pv=self::provider_from_id($id);
if($id)return self::player($id,$pv,$ts);}

static function com($u){//from url//play
$pv=self::provider($u); $id=self::extractid($u,$pv);
if($pv && $id)return self::player($id,$pv);}

static function com2($id){//epub
return iframe('http://www.youtube.com/embed/'.$id.'?border=0&version=3&autohide=0&showinfo=0&rel=0&fs=1','100%','50');}

static function content($p){
$u=jurl($p['p1']??'',1); $j='vd|video,call||id';
$bt=form::call(['id'=>['inputcall',$u,'url',$j],['submit',$j,'ok','']]);
return $bt.div('','board','vd');}

static function api($p){
$u=jurl($p['p1']??'',1);
//http://logic.ovh/frame/video/https(ddot)(slash)(slash)video(dot)twimg(dot)com(slash)ext*tw*video(slash)1094182177242263552(slash)pu(slash)vid(slash)1280x720(slash)pcPd6LUOqt4bs1DL(dot)mp4?tag(equal)6
$fb='usr/videos/'.strprm($u,'/',4).'.mp4'; mkdir_r($fb);
if(!is_file($fb) && $u)copy($u,$fb);
if(is_file($fb))return video($fb);}

static function iframe($p){
return self::content($p);}
}
?>