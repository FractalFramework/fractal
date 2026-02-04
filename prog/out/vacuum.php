<?php

class vacuum{
//http://philum.fr/plug/vacuum/https://trackchanges.postlight.com/building-awesome-cms-f034344d8ed

static function skey(){
return read_file('cnfg/vacuum.txt');}

static function getkey(){
return sesm('vacuum','skey');}

static function copen($url,$key){$d=curl_init();
curl_setopt($d,CURLOPT_URL,$url);
curl_setopt($d,CURLOPT_HTTPHEADER,['x-api-key: '.$key]);//,'Content-Type: application/json'
curl_setopt($d,CURLOPT_RETURNTRANSFER,1);
curl_setopt($d,CURLOPT_CUSTOMREQUEST,'GET');
$ret=curl_exec($d);
//$error=curl_error($d); $erno=curl_errno($d);
//if($ret==false)trigger_error(curl_error($d));//;throw new \RuntimeException($error,$errno);
return $ret;}

static function api($u,$m){
$key=self::getkey();
if(!$u)$u='http://newsnet.fr/133333';
//$u='http://newsnet.fr/call.php?a=vacuum&mode='.$m.'&p='.urlencode($u);
$u='http://newsnet.fr/call/vacuum/'.str_replace('/','|',nohttp($u)).'&o='.$m;
$ret=self::copen($u,$key);
//echo $enc=mb_detect_encoding($ret);
$r=json_decode($ret,true); //pr($r);
if(isset($r['code'])=='404')echo $r['content'];
//if(auth(6)){echo $u; p($r); return;}
return $r;}

static function api_philum($u){
$dom=domain($u); $id=strend($u,'/');
$f='http://'.$dom.'/apicom/id:'.$id.',json:1';//,conn:1 //conn not works with json
$d=get_file($f); //eco($d);
$r=json_decode($d,true); //echo upsql::error();
if(isset($r[$id]))$r=$r[$id]; //pr($r);
//if($r)foreach($r as $k=>$v)if(is_string($v))$r[$k]=html_entity_decode($v);
//$r['content']=str::striptags($r['content']??'');
return $r;}

#utf8
static function utf8($u){
$d=file_get_contents($u);
$enc=between($d,'charset="','"');
if(!$enc)$enc=between($d,'charset=','"');
if(!$enc)$enc=mb_detect_encoding($d);
return strtolower($enc);}

//com
static function com($u,$o=''){$dom=domain($u); $mode='';//'conn';//conn-html
if($dom=='philum.ovh' or $dom=='newsnet.fr' or $dom=='oomo.ovh')$phi=1; else $phi=0;
if($phi==1)$r=self::api_philum($u); else $r=self::api($u,$mode);
if(!$r)return ['','',''];
$tit=$r['title']??'';
$img=$r['image']??'';//lead_image_url
//if($mode=='conn')$txt=val($r,'conn'); else //miss img+domain
//$txt=conv::com(val($r,'content'));
$txt=$r['content']??'';
/*$enc=self::utf8($u);
if($enc=='utf-8'){
	$tit=str::utfdec($tit);
	$img=str::utfdec($img);
	$txt=str::utfdec($txt);}*/
if($mode=='conn'){
	//$txt=str_replace(htmlentities('§'),'|',$txt);
	$txt=str_replace(':twitter',':twit',$txt);//eco($txt);
	$txt=conn::com($txt,1);}
else{$txt=conv::call(['txt'=>$txt]); $txt=conn::call(['msg'=>$txt,'ptag'=>1]);}
if($o)$txt=etc($txt,240);
$txt.=tag('p',[],lk($u));
return [$tit,$txt,$img];}

#call
static function play($p){
$u=http(val($p,'url'));
[$tit,$txt,$img]=self::com($u);
$ret=tag('h2','',$tit);
//$ret.=image($img);
$ret.=div($txt,'');
return $ret;}

static function call($p){
[$tit,$txt,$img]=self::com($p);
$txt=conn::call(['msg'=>$txt,'ptag'=>1]);
$ret=tag('h2','',$tit);
$ret.=div($txt,'');
return $ret;}

//interface
static function content($p){
$rid=randid('yd');
$p['txt']=$p['url']??$p['p1']??'';
$ret=input('url',$p['txt'],40);
$ret.=bj($rid.'|vacuum,play||url',lang('import'),'btn');
return $ret.div('','board',$rid);}
}
?>