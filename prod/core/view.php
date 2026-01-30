<?php
class view{//extends explorer
static $private=0;
static $ra=[];
static $rc=[];
static $rb=[];
static $rt=[];

static function detectvars($r){
foreach($r as $k=>$v)
	if(is_array($v))self::detectvars($v);
	elseif(strpos($v,'{')!==false)self::$rb[]=strto($v,'}');}

static function detect_vars($r){$rt=[];
foreach($r as $k=>$v)if(is_array($v))self::detect_vars($v); else self::$rb[]=$v[1];}

//r to render
static function repl($c,$p,$pr,$d){
//$p=$pr['class']??'';//plaster
return match($c){''=>$d,
//'url'=>lka($p,$d?$d:preplink($p)),
//'jurl'=>lj('',$p,$d),
//'clear'=>divc($c,$d),
//'thumb'=>artim::thumb_d($d,$p,''),
//'image'=>image($p),
//'anchor'=>tag('a',['name'=>$p],''),
//'conn'=>conn::connectors($p.':'.$o,3,'','',''),
//'app'=>appin($p,''),
default=>tag($c,$pr,$d)."\n"};}

static function play($r){$ret='';
$ra=self::$ra; $rc=self::$rc; //pr($ra);
if($r)foreach($r as $k=>$v){[$c,$p,$d]=$v; $pr=[];
	if(is_array($v[2]))$d=self::play($d);
	else $d=str_replace($rc,$ra,$d);
	//$pr=is_array($p)?$p:['class'=>$p];//bad service
	if(is_array($p))foreach($p as $kp=>$vp)
		$pr[$kp]=str_replace($rc,$ra,$vp);
	else $p=str_replace($rc,$ra,$p);
	if($d)$ret.=self::repl($c,$p,$pr,$d);}
$ret=str_replace('<p></p>','',$ret);
return $ret;}

static function call($r,$ra){
$rc=[]; self::detectvars($ra); $rb=sel::$rb;
foreach($r as $k=>$v)$rc[$k]='{'.$k.'}';
self::$ra=$ra; self::$rc=$rc;
$d=self::play($r);
return $d;}

static function com($r,$a){
$ra=$a::template();
return self::call($r,$ra);}

static function batch($r,$rb){$rt=[];
foreach($rb as $k=>$v)$rt[]=self::call($r,$v);
return join('',$rt);}

}
?>