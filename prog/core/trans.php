<?php

class trans{

static function getkey(){$k=ses('yndxkey');
if(!$k)ses('yndxkey',read_file('cnfg/deepl.txt'));
return $k;}

static function post($url,$post){$d=curl_init();
curl_setopt($d,CURLOPT_URL,$url);
curl_setopt($d,CURLOPT_HTTPHEADER,[]);
if($post){
	curl_setopt($d,CURLOPT_POST,TRUE);
	curl_setopt($d,CURLOPT_POSTFIELDS,'text='.rawurlencode($post));}
curl_setopt($d,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0');
curl_setopt($d,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($d,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($d,CURLOPT_RETURNTRANSFER,1);
$ret=curl_exec($d); //if($ret===false)echo curl_error($d);
curl_close($d);
return $ret;}

static function api($prm,$txt,$mode=''){
$txt=str::utf8enc($txt); $r=[];
$prm['auth_key']=self::getkey();
$mode=$mode?$mode:'translate';
$u='https://api-free.deepl.com/v2/'.$mode.'?'.implode_k($prm,'&','=');
$ret=self::post($u,$txt);
$r=json_decode($ret,true); //pr($r);
if(isset($r['message'])){echo $r['message']; $r=['text'=>$txt];}
else $r=$r['translations'][0]??'';
return $r;}

static function getlangs(){
$rb=['EN','FR','ES','IT','DE','NL','PL','JA','PT','RU','SV','TR','ZH'];
//,'BG','CS','DA','EL','ET','HU','ID','LT','LV','PL','RO','SK','SL','UK'
return implode(',',$rb);}

#reader
static function build($p){$prm=[];
[$d,$to,$from,$format]=vals($p,['txt','to','from','format']);
if(!$to)$to=ses('lng');
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
$r=self::api($prm,$d,'translate');
$from=$r['detected_source_language']??''; $txt=$r['text']??'';
return [$txt,$from];}

static function detect($p){
$p['target_lang']='EN';
[$txt,$lang]=self::build($p);
if(!$lang)$lang=ses('lng');
return $lang;}

static function cut($txt){$na=2000; $ret=''; $nc=0;
$nb=strlen($txt); $n=ceil($nb/$na); $r=explode(' ',$txt);
if($nb>$na){foreach($r as $k=>$v){$nc+=strlen($v)+1;
	if($nc<$na)$ret.=$v.' '; else{$rb[]=$ret; $nc=0; $ret='';}}
	if($ret)$rb[]=$ret;}
else $rb[]=$txt;
return $rb;}

//tools
static function correct($d){
$ra=[":j]",':p]',':vidéo]',':efecto','[///img/',':centro]',':non]',':not]'];
$rb=[':i]',':q]',':video]','effect','[',':center]',':no]',':no]'];
return str_replace($ra,$rb,$d);}

static function convhtml($d){
$d=conv::com($d);
$d=str::clean_br($d);
$d=str::clean_lines($d);
return $d;}

#translate
static function read($p){
[$d,$lang]=self::build($p);
//$d=utf8dec($d);
$d=self::correct($d);
if($p['dtc']??'')$d.=' ('.$lang.')';
return $d;}

//com
static function com($p){
[$d,$to,$from,$dtc]=vals($p,['txt','to','from','dtc']);
if(!$to)$p['to']=ses('lng');
if(!$from)$p['from']=self::detect(['txt'=>$d]);
if($from!=$to)$d=self::read($p);
$d=self::correct($d);
return $d;}

//interface
static function content($p){
$rid=randid('yd');
$p['txt']=$p['txt']??($p['p1']??'');
$ret=input('txt',$p['txt']);
$ret.=bj($rid.'|trans,read||txt',lang('translate'),'btn');
//$ret.=bj('popup|trans,getlangs||txt',lang('lang'),'btn');
$ret.=bj($rid.'|trans,detect||txt',lang('detect'),'btn');
return $ret.div('','board',$rid);}
}
?>