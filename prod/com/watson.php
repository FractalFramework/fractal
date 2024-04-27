<?php

class watson{
//https://gateway-lon.watsonplatform.net/assistant/api
//static $private=1;

static function getkey(){$k=ses('wtsnkey');
if(!$k)ses('wtsnkey',read_file('cnfg/watson.txt'));
$k='f_Pt_yx8OpRDGvmQxAPaTujTRl1SWAor4Jdn85D5veyJ';
$k=ses('wtsnkey',$k);
return $k;}

static function url(){
return 'https://gateway-lon.watsonplatform.net/assistant/api/';}

static function api($u,$get,$post,$format='json'){
$post['apikey']=self::getkey();
$u='https://gateway-lon.watsonplatform.net/assistant/api/'.$u.'?'.mkprm($get);
//if($get)$d=@file_get_contents($u);
$d=curl($u,$format,$post);
$r=json_decode($d,true); pr($r); echo $u;
//$r=json_decode($d,true);
return $r;}

static function natural_langage($d){
$u='v1/analyze'; $post=[];
$get['url']=$d;
$get['version']='2018-11-16';
$r=self::api($u,$get,$post);
if(isset($r))return $r['output'];}

static function text2speech($d){
$u='v1/synthesize'; $post=[]; $format='audio';
$get['accept']='audio%2Fwav';
$get['text']=$d;
$get['voice']='fr-FR_EnriqueVoice';
$r=self::api($u,$get,$post,$format);
if(isset($r))return $r['output'];}

static function translate($d,$lang){
$u='v3/translate'; $post=[]; $get['version']='2018-05-01';
//$get['text']=$d; $get['model_id']=$lang;
$post=json_encode(['text'=>$d,'model_id'=>$lang]);
$r=self::api($u,$get,$post);
if(isset($r['output']))return $r['output'];}

static function getlang($d){
$u='v3/identify'; $post=[]; $get['version']='2018-05-01'; $format='text/plain';
$post['data']=json_encode([$d]);
$r=self::api($u,$get,$post);
if(isset($r))return $r['output'];}

static function read($p){p($p);
$d=$p['p1']??''; $mode=val($p,'p2');
$d=rawurlencode(html_entity_decode($d));
if($mode=='natlang')$r=self::natural_langage($d);
if($mode=='speech')$r=self::text2speech($d);
if($mode=='translate')$r=self::translate($d,'en-fr');
if($mode=='getlang')$r=self::getlang($d);
if(isset($r))return $r['output'];}

//com (apps)
static function com($p,$o=''){
[$txt,$lang]=self::read($p);
$_POST['lng']=$lang;
$ret=rawurldecode($txt);//if($o)
if(val($p,'dtc'))$ret.=' ('.$lang.')';
return $ret;}

//interface
static function content($p){
$rid=randid('yd');
$ret=input('p1',$p['p1']??'');
$ret.=radio('p2',['natlang','speech','translate','getlang'],'',1,'');
$ret.=bj($rid.'|watson,read||p1,p2',lang('ok'),'btn');
return $ret.div('','board',$rid);}
}
?>
