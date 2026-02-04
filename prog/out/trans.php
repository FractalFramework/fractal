<?php

class trans{

static function skey(){
return read_file('cnfg/deepl.txt');}

static function getkey(){
return sesm('trans','skey');}

static function post($url,$head,$post){$d=curl_init();
curl_setopt($d,CURLOPT_URL,$url);
//curl_setopt($d,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
curl_setopt($d,CURLOPT_RETURNTRANSFER,1);
curl_setopt($d,CURLOPT_HTTPHEADER,$head);
if($post){
	curl_setopt($d,CURLOPT_POST,1);
	curl_setopt($d,CURLOPT_POSTFIELDS,$post);}
curl_setopt($d,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($d,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($d,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($d,CURLOPT_RETURNTRANSFER,1);
$ret=curl_exec($d); if($ret===false)echo curl_error($d);
return $ret;}

static function api($prm,$head,$mode=''){
$mode=$mode?$mode:'translate';
$u='https://api-free.deepl.com/v2/'.$mode;
$ret=self::post($u,$head,$prm);
$r=json_decode($ret,true); //pr($r); echo datz('H-i-s');
if(isset($r['message'])){echo $r['message']; $r=['text'=>$txt];}
else $r=$r['translations'][0]??'';
return $r;}

static function getlangs(){
$rb=['EN','FR','ES','IT','DE','NL','PL','JA','PT','RU','SV','TR','ZH'];
//,'BG','CS','DA','EL','ET','HU','ID','LT','LV','PL','RO','SK','SL','UK'
return implode(',',$rb);}

#reader
static function build($d,$to,$from){$json=1;
if($json)$head['Accept']='application/json';
else $head['Content-Type']='application/x-www-form-urlencoded';
$head['Authorization']='DeepL-Auth-Key '.self::getkey();
if($json)$prm['text']=[$d]; else $prm['text']=[$d];
if(!$to)$to=ses('lng'); //if(!$from)$from=ses('lng');
if($from)$prm['source_lang']=strtoupper($from);
if($to)$prm['target_lang']=strtoupper($to);
$prm['tag_handling']='xml';
$prm['preserve_formatting']=1;
//$prm['split_sentences']=1;//default
//$prm['formality']='prefer_more';//more,less,prefer_more,prefer_less
//$prm['glossary_id]='';
//$prm['tag_handling']='xml'; $prm['ignore_tags']='x';
//$prm['tag_handling']='xml';
//$prm['split_sentences']='nonewlines';
//$prm['outline_detection']='0';
//$prm['splitting_tags']='par,title';
//$prm['tag_handling']='off';//html//xml
if($json)$prm=json_encode($prm);
else $prm=http_build_query($prm);
$r=self::api($prm,$head,'translate');
$from=$r['detected_source_language']??''; $txt=$r['text']??'';
return [$txt,$from];}

//sh
static function sh($txt,$to,$from='fr'){
if(!auth(6))return;
$f='_datas/sh/trad.sh'; mkdir_r($f);
$fb='_datas/sh/result.txt';
if(is_file($fb))unlink($fb);
$context='';//Use a friendly, diplomatic tone
$custom='';//'"custom_instructions": ["Use a friendly, diplomatic tone"],';
$d='#! /bin/bash

curl --request POST \
  --url https://api-free.deepl.com/v2/translate \
  --header "Authorization: DeepL-Auth-Key '.trans::getkey().'" \
  --header "Content-Type: application/json" \
  --data \'
{
  "text": [
    "'.(addslashes($txt)).'"
  ],
  "target_lang": "'.strtoupper($to).'",
  "source_lang": "'.strtoupper($from).'",
  "context": "'.$context.'",
  "show_billed_characters": true,
  "split_sentences": "1",
  "preserve_formatting": true,
  "formality": "default",
  '.$custom.'
  "tag_handling": "html",
  "tag_handling_version": "v1",
  "non_splitting_tags": [
  ],
  "splitting_tags": [
  ],
  "ignore_tags": [
  ]
}
\' > '.$fb.'
';
file_put_contents($f,$d);
passthru('sh '.$f,$s); //echo $s; //pr($r); //proc_open
$ret=is_file($fb)?file_get_contents($fb):'{}';
$r=json_decode($ret,true); //pr($r);
if(isset($r['message'])){echo $r['message']; $r=['text'=>$txt];}
else $r=$r['translations'][0]??'';
$from=$r['detected_source_language']??''; $txt=$r['text']??'';
//$n=$r['billed_characters']??''; if($n)json::add('','trans',[$txt,$n,'',$from,$to,ip()]);
return [$txt,$from];}

//tools
static function correct($d){
$ra=[":j]",':p]',':vidéo]',':efecto','[///img/',':centro]',':non]',':not]'];
$rb=[':i]',':q]',':video]','effect','[',':center]',':no]',':no]'];
return str_replace($ra,$rb,$d);}

static function detect($p){
[$d,$to,$from]=vals($p,['txt','to','from']);
$to='en';
//[$txt,$lang]=self::build($d,$to,$from);
[$txt,$lang]=self::sh($d,$to,$from);
if(!$lang)$lang=ses('lng');
return $lang;}

static function cut($txt){$na=2000; $ret=''; $nc=0;
$nb=strlen($txt); $n=ceil($nb/$na); $r=explode(' ',$txt);
if($nb>$na){foreach($r as $k=>$v){$nc+=strlen($v)+1;
	if($nc<$na)$ret.=$v.' '; else{$rb[]=$ret; $nc=0; $ret='';}}
	if($ret)$rb[]=$ret;}
else $rb[]=$txt;
return $rb;}

static function convhtml($d){
$d=conv::com($d);
$d=str::clean_br($d);
$d=str::clean_lines($d);
return $d;}

#translate
static function read($p){
[$d,$to,$from]=vals($p,['txt','to','from']);
//[$d,$lang]=self::build($d,$to,$from);
[$d,$from]=self::sh($d,$to,$from);
//$d=self::correct($d);
//if($p['dtc']??'')$d.=' ('.$lang.')';
return $d;}

//com
static function com($p){
[$d,$to,$from,$dtc]=vals($p,['txt','to','from','dtc']);
if(!$to)$p['to']=ses('lng');
if(!$from)$p['from']=self::detect($d);
if($from!=$to)$d=self::read($p);
$d=self::correct($d);
return $d;}

//interface
static function content($p){
$rid=randid('yd');
$p['txt']=$p['txt']??($p['p1']??'');
$ret=input('txt',$p['txt']);
$j=$rid.'|trans,read|from=fr,to=en|txt';
$ret.=bj($j,lang('translate'),'btn');
//$ret.=bj('popup|trans,getlangs||txt',lang('lang'),'btn');
$ret.=bj($rid.'|trans,detect||txt',lang('detect'),'btn');
return $ret.div('','board',$rid);}
}
?>