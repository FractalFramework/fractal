<?php
class dom{

//domdel
static function remove($v){$n=$v->childNodes->length;
for($i=0;$i<$n;$i++)$v->removeChild($v->childNodes->item(0));
return $v;}

static function del($d,$o){
$r=explode('|',$o); $dom=dom($d);
if($dom)foreach($r as $va){
	[$c,$at,$tg,$op]=expl(':',$va,4); if(!$at)$at='class'; if(!$tg)$tg='div';//id,href,...
	foreach($dom->getElementsByTagName($tg) as $k=>$v){$attr=$v->getAttribute($at);
	if($op=='del')$v->removeAttribute($at);//:data-image-caption:img:del
	elseif($op=='x')self::remove($v);//::noscript:x
	elseif($op=='clean'){$dest=$dom->createElement('img');//:src:img:clean
		$src=$v->getAttribute($at); $dest->setAttribute('src',$src); $v->parentNode->replaceChild($dest,$v);}
	elseif(($c && strpos($attr,$c)!==false) or !$c){self::remove($v); $v->parentNode->removeChild($v);}}}
$ret=$dom->saveHTML(); //eco($ret);
return $ret;}

static function cleanimg($d){
$dom=dom($d); $rec=dom(''); $dest=$dom->createElement('img');
if($dom)foreach($dom->getElementsByTagName('img') as $k=>$v){
	$src=$v->getAttribute('src');
	$dest->setAttribute('src',$src);
	$v->parentNode->replaceChild($dest,$v);}
$ret=$dom->saveHTML();
return $ret;}

//dom
static function importnode($dom,$rec,$v,$tg){
if($tg=='img' or $tg=='meta')$tag='div'; else $tag=$tg;
$dest=$rec->appendChild($rec->createElement($tag));
if($tg=='img')$dest->nodeValue=urlroot($v->getAttribute('src'));
elseif($tg=='meta')$dest->nodeValue=$v->getAttribute('content');
elseif($v->childNodes)foreach($v->childNodes as $k=>$el)$dest->appendChild($rec->importNode($el,true));
return $rec;}

static function capture($dom,$va,$rec){//todo:iterate it
[$c,$at,$tg,$cn]=expl(':',$va,4); if(!$at)$at='class'; if(!$tg)$tg='div'; //id,a,...
$r=$dom->getElementsByTagName($tg); $n=0;
foreach($r as $k=>$v){$attr=$v->getAttribute($at);//domattr($v,$at) //echo $v->nodeName.'-';
if(($c && strpos($attr,$c)!==false) or !$c){$n++;//nb of similar captures
	if($n==$cn or !$cn)self::importnode($dom,$rec,$v,$tg);}}
return $rec;}

static function detect($d,$o){
$r=explode('|',$o); $dom=dom($d); $rec=dom(''); $rec->formatOutput=true;
if($dom)foreach($r as $k=>$va)self::capture($dom,$va,$rec);//var_dump($rec);
$ret=$rec->saveHTML();
if($ret)return trim($ret);}

//dom2
static function extract($dom,$va){$ret='';//all-in-one
[$c,$at,$tg,$g]=expl(':',$va,4); if(!$at)$at='class'; if(!$tg)$tg='div';//id,href,...
if(!$g){if($tg=='img')$g='src'; elseif($tg=='meta')$g='content';}//props
$r=$dom->getElementsByTagName($tg); $c=str_replace('(ddot)',':',$c);
foreach($r as $k=>$v){$attr=$v->getAttribute($at);
	if(!$ret && ($c==$attr or ($c && strpos($attr,$c)!==false) or !$c))
		$ret.=$g?domattr($v,$g):$v->nodeValue;}
return $ret;}

static function extract_batch($d,$o){$ret='';
$r=explode('|',$o); $dom=dom($d);
if($dom)foreach($r as $v)$ret.=self::extract($dom,$v);
return $ret;}

//href
static function href($d){$lk=''; $va=''; $dom=dom($d);
$r=$dom->getElementsByTagName('a');
foreach($r as $k=>$v){$lk=domattr($v,'href'); $va=$v->nodeValue;}
return '['.$lk.($va?'§'.$va:'').']';}

//dom2conn//dev
/**/static function dc($v){$at=[];
$tg=isset($v->tagName)?$v->tagName:$v->nodeName;//domattr($v,$at);
//if($v->hasAttributes())$at=$v->attributes; else $at=[];
if($v->hasAttributes())foreach($v->attributes as $vb)$at[]=self::dom2conn($vb);
$rb=$v->textContent;
return [$tg,$at];}

static function dom2conn($dom){$rb=[];
if($dom->hasChildNodes())foreach($dom->childNodes as $k=>$v){
	[$tg,$at]=self::dc($v);
	$rb[]=[$tg,$at,self::dom2conn($v)];}
elseif($dom->textContent){
	[$tg,$at]=self::dc($dom);
	$rb[]=[$tg,$at,$dom->textContent];}//nodeValue//
return $rb;}

static function dom2array($dom){
$r=$dom->getElementsByTagName('Type');
return iterator_to_array($r);}

static function obj2array($r){
return json_decode(json_encode($r),true);}

//get
static function getxt($el,$ret=''){$attr='';
if(!isset($el->tagName))return $ret.$el->textContent;
$el=$el->firstChild; if($el!=null)$ret=self::getxt($el,$ret);
while(isset($el->nextSibling)){$ret=self::getxt($el->nextSibling,$ret); $el=$el->nextSibling;}
return $ret;}

static function detect_table($dom){$rt=[];
$r=$dom->getElementsByTagName('tr');
foreach($r as $k=>$v){$rt[$k]=[];
	$rb=$v->getElementsByTagName('th'); if(!$rb['length'])$rb=$v->getElementsByTagName('td');
	if($rb)foreach($rb as $kb=>$el)$rt[$k][$kb]=self::getxt($el);}
return $rt;}

static function select_table($dom){
$r=$dom->getElementsByTagName('table');
$rt=self::detect_table($r[0]);
return tabler($rt);}

//call
static function call($p){
$d=$p['txt']??''; $o=$p['o']??'';
$ret=self::detect($d,$o);
return $ret?$ret:'';}

static function com($d,$fc,$o=''){
$dom=dom($d);
self::$fc($dom,$o);
return $dom->saveHTML();}

static function menu($p){
$rid=$p['rid']; $o=$p['o']??'';
$bid='inp'.$rid; $cid='txt'.$rid;
$j=$rid.'|dom,call|rid='.$rid.'|'.$bid.','.$cid;
$ret=inputcall($j,$bid,$o);
$ret.=bj($j,picto('ok'),'btn').br();
$ret.=textarea($cid,'',40);
return $ret;}

static function content($p){
$rid=randid();
$p['rid']=$rid;
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'',$rid);}
}
?>