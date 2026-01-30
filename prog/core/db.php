<?php
class db{
static $private=0;
static $open=0;

static function fu($f){
if(strpos($f,'.')===false)$f.='.php'; return 'disk/'.$f;}

static function secu($f,$x){$rf=explode('/',$f);
	if($rf[1]??''==ses('usr') or $x or auth(6))return 1; else echo '!';}

static function bt($f){
return popup('explorer|f='.$f.'.php',icoit('database',$f),'btn');}

static function init($f,$ra=[]){
$u=self::fu($f);//$u=explorer::furl($f);
if(is_file($u))return $u; if(!is_dir($u))mkdir_r($u);
$ra=['_'=>$ra?$ra:['col1']]; $d=self::dump($ra,$f); self::write($u,$d);
return $u;}

static function ex($f,$o=''){
$u=$o?explorer::furl($f):self::fu($f);
return is_file($u)?1:0;}

//static function filters($u,$o){$r=self::read($u); if(isset($o['sort']))sort($r); return $r;}
static function slashes($d){return str_replace("'","\'",stripslashes($d));}

static function dump($r,$p){$rc=[];
if(is_array($r))foreach($r as $k=>$v){$rb=[];
	if(is_array($v))foreach($v as $ka=>$va)$rb[]="'".self::slashes($va)."'";
	if($rb)$rc[]=(is_int($k)?$k:"'".$k."'").'=>['.implode(',',$rb).']';}
if($rc)return "<?php //frct/db/".$p."\n".'$r=['.implode(',',$rc).'];';}

static function edit($p){$f=$p['f']; $k=$p['k']; $v=$p['v'];
$prm='f='.$f.',k='.$k.'|v'.$k;
$ret=input('v'.$k,$v).bj($k.'|db,sav|'.$prm,langpi('modif'),'btsav');
$ret.=bj($k.'|db,bt|'.$prm,langpi('close'),'btn');
return $ret;}

//0,1|1=a,2=b
static function rq($c,$f,$w=''){$ret=[]; $rd=[]; $c='';
$rc=explode(',',$c); $rw=prmr($w); $n=count($rw); $r=self::read($f);
if($r)foreach($r as $k=>$rb){if($w){$ok=0;//find where
		foreach($rw as $ka=>$va){if($rb[$ka]==$va)$ok+=1;}
		if($ok==$n)$rd[$k]=$rb;}}
if($c && is_array($rd))foreach($rd as $k=>$rb){//slct cols
	foreach($rb as $kb=>$vb){if(in_array($kb,$rc))$ret[$k][$kb]=$vb;}}
else $ret=$rd;
return $ret;}

static function conformity($r){
foreach($r as $k=>$v)if(!is_array($v))$r[$k]=[$v];
return $r;}

static function reorder($r){$i=0;
if(isset($r['_'])){$rb['_']=$r['_']; unset($r['_']);}
if($r){foreach($r as $k=>$v){$i++; if($k==0 or $k==$i-1)$k=$i; $rb[$k]=$v;}
return $rb;}}

static function add($f,$rb,$ra=[]){
$u=self::init($f,$ra);
$r=self::read($f); $r[]=$rb;
self::save($f,$r,[],1);}

static function save($f,$r,$ra=[],$x=''){
$u=self::init($f); if(!$r)return;
if(isset($r[0]))$r=self::reorder($r);
$r=self::conformity($r);
$d=self::dump($r,$f);
if(self::secu($f,$x))
self::write($u,$d);
return self::bt($f);}

static function write(string $u,$d):void{
if(!is_file($u))mkdir_r($u);
file_put_contents($u,$d);
opcache_invalidate($u);}

static function read($f,$o=''){
$u=self::fu($f);//echo $u=explorer::furl($f);
if(is_file($u))require $u;
if($o && isset($r['_']))unset($r['_']);
return isset($r)?$r:[];}

static function lang($fa,$o=''){
$f='db/lang/'.lng().'/'.$fa;
return self::read($f,$o);}

static function sys($f,$o=''){
return self::read('db/'.$f,$o);}

static function usr($fa,$o=''){
$f='usr/'.ses('usr').'/'.$fa;
return self::read($f,$o);}

static function api($p){
$f=$p['f']; $r=self::read($f);
return json_encode($r,true);}

static function call($p){
$f=$p['f']; $r=self::read($f);
return tabler($r);}

}
?>