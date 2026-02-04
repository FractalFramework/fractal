<?php
class discorddb{	
static $private=2;
static $db='discorddb';
static $a='discorddb';
static $cb='dcs';
static $cols=['data-message-id','source','avatar','short-timestamp','author','userid','user','date','timestamp','content','notification','image','audio','youtube','reactions'];
static $typs=['bint','var','var','var','var','bint','var','var','int','text','json','json','json','var','json'];

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){
return admin::app(['a'=>self::$a,'db'=>self::$db]);}

static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p){
$ret=lang('result').': '.val($p,'v1').' '.val($p,'inpdc');
return $ret;}

#readcsv
static function meteocls($r){$rb=[];
foreach($r as $k=>$v){
	if($v=='date')$typ='bint';
	elseif($v=='ff' or $v=='t' or $v=='td')$typ='double';
	else $typ='int';
	if($v)$rb[$v]=$typ;}
return $rb;}

static function extract($dom,$va){$ret='';
$at='class'; $tg='div'; //[$c,$at,$tg,$g]=expl(':',$va,4);
$r=$dom->getElementsByTagName($tg);
foreach($r as $k=>$v){$attr=$v->getAttribute($at); $ok=0;
//if($dom->hasChildNodes())
	if($strict && $c==$attr)$ok=1; elseif(!$strict && $c && strpos($attr,$c)!==false)$ok=1;
	if(!$ret && ($ok or !$c))
		$ret.=$g?domattr($v,$g):$v->nodeValue;}
//$ret=str::utf2ascii($ret);
return $ret;}

//$o='chatlog__message-group::';
//$dom=dom::getfrom($d,'chatlog::',$st=1);
//$dom=self::extract($dom,'');
//$r=dom::getfrom($d,$o,1);
//$r=dom::extract_batch($d,$o);

static function datamsg($q,$mid){$rt=[];
//$rt[]=$v->nodeValue;
//$el=$v->firstChild; $c=$el->;
$r=$q->getElementsByTagName('div');
//$r=$q->childNodes;
//$rt['data-message-id']=$q->getAttribute('data-message-id');
$rt['data-message-id']=$mid;
if($q)foreach($r as $k=>$v)if($v){
$c=$v->getAttribute('class');
if($c=='data-message-id')$rt['data-message-id']=$v->getAttribute('data-message-id');
elseif($c=='chatlog__message-container')$rt['data-message-id']=$v->getAttribute('data-message-id');
elseif($c=='chatlog__message-aside'){$el=$v->firstChild; $rt['avatar']=$el->getAttribute('src');}
elseif($c=='chatlog__short-timestamp')$rt['short-timestamp']=$v->getAttribute('title');
elseif($c=='chatlog__header'){
	$rb=$v->getElementsByTagName('span');
	foreach($rb as $kb=>$vb)if($vb){
		$c=$vb->getAttribute('class');
		if($c=='chatlog__author'){
			$rt['author']=$vb->getAttribute('title');
			$rt['userid']=$vb->getAttribute('data-user-id');
			$rt['user']=$vb->nodeValue;}
		if($c=='chatlog__timestamp'){$el=$vb->firstChild;
			$rt['date']=$vb->getAttribute('title');
			$rt['timestamp']=strtotime($el->nodeValue);}}}
elseif($c=='chatlog__content chatlog__markdown'){
	$rt['content']=$v->nodeValue;}
elseif($c=='chatlog__message-primary'){
	$rb=$v->getElementsByTagName('span');
	foreach($rb as $kb=>$vb){$c=$vb->getAttribute('class');
		if($c=='chatlog__system-notification-author')$rt['notification']['author']=$vb->nodeValue;
		elseif($c=='chatlog__system-notification-content')$rt['notification']['msg']=$vb->nodeValue;
		elseif($c=='chatlog__system-notification-timestamp')$rt['notification']['timestamp']=$vb->nodeValue;}}
elseif($c=='chatlog__attachment'){
	//if($v->hasChildNodes())foreach($v->childNodes as $k=>$v){}
	//$rb->xpath("/a/img");
	$rb=$v->getElementsByTagName('img');
	foreach($rb as $kb=>$vb){
		$rt['image'][$kb]['src']=$vb->getAttribute('src');
		$rt['image'][$kb]['title']=$vb->getAttribute('title');}
	$rb=$v->getElementsByTagName('source')[0];
	if($rb){//$el=$rb->item(0); 
		$rt['audio']['src']=$rb->getAttribute('src');
		$rt['audio']['title']=$rb->getAttribute('title');}}
elseif($c=='chatlog__embed-content'){
	$rb=$v->getElementsByTagName('img');
	foreach($v as $kb=>$vb){
		if($c=='chatlog__embed-author-link')$rt['youtube']['link']=$rb->getAttribute('href');}}
elseif($c=='chatlog__reactions'){
	$rb=$v->getElementsByTagName('div');
	foreach($rb as $kb=>$vb)if($vb){
		$c=$vb->getAttribute('class');
		if($c=='chatlog__reaction'){
			$el=$vb->getElementsByTagName('img');
			$rt['reactions'][$kb]['alt']=$el->item(0)->getAttribute('alt');
			$rt['reactions'][$kb]['src']=$el->item(0)->getAttribute('src');
			$el=$vb->getElementsByTagName('span');
			$rt['reactions'][$kb]['count']=$el->item(0)->nodeValue;}}}
}
return $rt;}

static function domexplore0($dom){$rt=[];
foreach($dom->childNodes as $k=>$v)if($v){
	$rt+=self::datamsg($v,$v->getAttribute('data-message-id'));}
return $rt;}

static function domexplore($dom){$rt=[];
foreach($dom->childNodes as $k=>$v)if($v){
	$rt+=self::datamsg($v,$dom->getAttribute('data-message-id'));}
return $rt;}

static function datagroup($d){$rt=[];
$q=dom($d); 
$ra=$q->documentElement;
$r=$ra->childNodes; //eco($q);
$xpath=new DOMXPath($q);
$r=$xpath->query("//element[@attribute='class']");
//$r=$v->getElementsByTagName('div');
if($r)foreach($r as $k=>$v)if($v){$c='';
	//if($v->hasAttribute('class'))
	//if($v->nodeType==XML_ELEMENT_NODE)
	if($v instanceof DOMElement)$c=$v->getAttribute('class'); //echo $c;
	if($c=='chatlog__message-container')$rt[]=self::datamsg($v,$v->getAttribute('data-message-id'));}
return $rt;}

static function dcls($d){$rt=[]; $dom=dom($d);
$r=$dom->getElementsByTagName('div'); //eco($r);//pr($r);
if($r)foreach($r as $k=>$v)if($v){$c=$v->getAttribute('class');
if($c=='chatlog__message-container')$rt[]=self::domexplore($v);}
return $rt;}

static function save($r,$f){
$rc=self::$cols; $rc[]='up'; $rb=[]; $ex=0; pr($rc);
foreach($r as $k=>$v){
//$ex=sql('id',self::$db,'v',['data-message-id'=>$v['data-message-id','source'=>$f]]);
if(!$ex)foreach($rc as $ka=>$va){$vb=$v[$ka]??'';
	if(is_array($vb))$vb=json_encode($vb);
	$rb[$k][$va]=$vb;}}
if($rb)sql::sav2('discorddb',$rb,1);//,'','',1
$n=count($rb); return $n.' entries';}

#call
static function call($p){$ret='';
$fa=$p['inpdc']; $db=self::$db; $ex='';
$dr='disk/usr/dav/'; $u=$dr.$fa; $xt=ext($fa);
$dr2='disk/usr/'.ses('usr').'/html/dsdb/'; mkdir_r($dr2);
/*if($xt=='.gz'){$fa=struntil(strend($fa,'/'),'.');
	$ub=$dr2.$fa.'.html'; echo $ub.' ';
	if(!is_dir($dr2))mkdir_r($dr2);
	if(!is_file($ub)){$d=readgz($u); write_file($ub,$d);} $u=$ub;
	//$fb='disk/usr/'.ses('usr').'/csv/'.$fa; if(is_file($fb))unlink($fb);
	}*/
$d=read_file($u); $cols=[]; //eco($d);
$r=self::dcls($d); pr($r);
$ret=self::save($r,$fa);
return $ret;}

/*static function batch(){
$dr='usr/dav/discorddb'; $ret='';
$ra=scandir_a($dr); //pr($ra);
$dr2='disk/usr/'.ses('usr').'/'; //rmdir_r($dr2.'html/dsdb'); //rmdir_r($dr2.'archive');
if(!is_dir($dr2.'html/dsdb'))mkdir_r($dr2.'html/dsdb');
$n=6; $l=48; $a=$n*$l; $b=$a+$l;
if($ra)foreach($ra as $k=>$v){
	$fa=strfrom($v,'.');
	$u=$dr.'/'.$v; //echo $u.':';
	//$ub=$dr2.'/archive/'.$fa; //echo $ub.' '; 
	//if(!is_file($ub))copy($u,$ub);
	//if($k>=$a && $k<$b)//$ret.=$k.'-';
	//$ret.=self::call(['inpdc'=>$v]);
	}
//rmdir_r($dr);
return $ret;}*/

static function res($p){$p1=$p['p1']??''; $r=[];
$wk=date('W'); $hr=date('H'); $dayr=date('z');
if($p1=='avgweeks')
return sql('YEAR(date) as yr,ROUND(AVG(temperature),2)','meteo_paris','kv','WHERE WEEKOFYEAR(date)='.$wk.' and HOUR(date)="'.$hr.'" group by yr');
return $r;}

static function avgv($p){
$r=self::res($p);
return tabler($r,'',1);}

static function api($p){
$r=self::res($p);
return json_encode($r);}

#content
static function content($p){
self::install();
$p['p1']=$p['p1']??'';
//$bt=bj(self::$cb.',,z|discorddb,batch','go','btn');
$j=self::$cb.',,z|'.self::$a.',call||inpdc';
$bt=inputcall($j,'inpdc','','','1');
$bt.=bj($j,pic('go'),'btn');
$bt.=upload::call('inpdc');
//$bt.=bj(self::$cb.',,z|'.self::$a.',batch','batch','btn');
$bt.=toggle(self::$cb.',,z|'.self::$a.',avgv|p1=weeks','avg','btn');
return $bt.div('','paneb',self::$cb);}
}
?>