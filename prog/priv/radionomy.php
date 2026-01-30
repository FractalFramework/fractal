<?php

class radionomy{	
static $private=2;
static $db='radionomy';
static $cols=['tit','txt'];
static $typs=['var','text'];
static $a='radionomy';
static $cb='rdo';
static $uid='4b2e071c-6a5d-444c-89d4-1d881a07d2a7';//Radio UID
static $apikey='4f5fd829-127f-4ed4-b68c-da5461f2fe11';//Radio API Key
static $dir='./usr/radionomy/';//cache folder

static function install(){
sql::create(self::$db,[array_combine(self::$cols,self::$typs)],0);}

static function admin(){
$r[]=['','j','popup|radionomy,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=radionomy_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=radionomy','code','Code'];
return $r;}

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

#api
static 
function getParam($id,$default){return ;}

static function url($p,$call){
$u='http://api.radionomy.com/';
$nfo=self::api_infos(); $apikey=self::$apikey; $uid=self::$uid;
$u.=$nfo[$call]['url'].'?radiouid='.$uid.'&apikey='.$apikey.'&type=xml';
foreach($nfo[$call]['params'] as $k=>$v)$u.='&'.$k.'='.$p[$k]??$v;
return $u;}

static function api_infos(){
return ['toptracks'=>['url'=>'toptracks.cfm','params'=>['amount'=>'1','days'=>'1','cover'=>'NO']],
	'tracklist'=>['url'=>'tracklist.cfm','params'=>['amount'=>'1','cover'=>'NO']],
	'currentsong'=>['url'=>'currentsong.cfm','params'=>['callmeback'=>'yes','cover'=>'NO']],
	'audience'=>['url'=>'currentaudience.cfm','params'=>[]]];}

static function api($p){
$call=$p['api']??'toptracks';//toptracks/tracklist/currentsong/audience
$dir=self::$dir; mkdir_r($dir);
$f=$dir.$call.'.txt';
$cbk=$dir.'callmeback.txt';
$tim=time();
$valid=false;
if($call=='currentsong'){
    if(@file_exists($cbk))$valid=(file_get_contents($cbk)>=$tim)?false:true;
    else{touch($cbk); $valid=true;}}
else{$expire=$tim-310;
    $valid=(@file_exists($f) && @filemtime($f)>$expire)?false:true;}
if($valid){
    if(!@file_exists($f))touch($f);
    $context=stream_context_create(['http'=>['timeout'=>30]]);
    $url=self::url($p,$call);
    $res=@file_get_contents($url);
    if($res){@file_put_contents($f,$res);
		if($call=='currentsong'){$xml=new SimpleXMLElement($res);
		@file_put_contents($cbk,$tim+($xml->track->callmeback/1000));}}}
$d=file_get_contents($f);
$r=simplexml_load_string($d); //pr($r);
//$ret=json_encode($r);
//return json_decode($d,true);
}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p){}

#stream
static function stream($p){
$r=self::build($p); $ret=''; //pr($r);
if($r)foreach($r as $k=>$v){$rb='';
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
if(!$ret)return help('no element','txt');
return div($ret,'');}

#call
static function call($p){
return self::stream($p);}

static function com($p){
$j=self::$cb.'|radionomy,play|v1=hello|inp1';
$bt=bj($j,langp('send'),'btn');
return inputcall($j,'inp1',$p['p1']??'',32).$bt;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
//$bt=self::com($p);
//$ret=self::stream($p);
//header('Content-Type: application/xml');
$r=self::api($p); eco($r);
$ret=$r;
return div($ret,'pane',self::$cb);}

}
?>