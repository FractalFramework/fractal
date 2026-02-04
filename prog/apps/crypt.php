<?php
class crypt extends appx{
static $private=0;
static $a='crypt';
static $db='crypt';
static $cb='mdl';
static $cols=['tit','txt','ek','iv'];
static $typs=['var','long','var','var'];
static $conn=1;
static $gen=0;
static $open=0;
static $tags=1;
static $qb='';

static function install($p=''){parent::install(array_combine(self::$cols,self::$typs));}
static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){return parent::del($p);}

static function save($p){
	$p['txt']=self::batch_encrypt($p['txt']??'',$p['ek'],$p['iv'],0);
	return parent::save($p);}
static function modif($p){
	$p['txt']=self::batch_encrypt($p['txt']??'',$p['ek'],$p['iv'],0);
	return parent::modif($p);}
static function create($p){return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subform($r){return parent::subform($r);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}

//form
static function fc_ek($k,$v){if(!$v)$v=self::mkkey(32); return hidden($k,$v);}
//static function fc_iv($k,$v){$v=self::mkkey(16); $bt=div($v,'btn'); return hidden($k,$v);}//$bt.
static function fc_iv($k,$v){if(!$v)$v=substr(self::mkkey(16),0,-2);
	return input($k,$v,32,'','','','',1);}

static function form($p){$p['fcek']=1; $p['fciv']=1;
	if(isset($p['txt']))$p['txt']=self::batch_decrypt($p['txt']??'',$p['ek'],$p['iv'],1);
	return parent::form($p);}

static function edit($p){return parent::edit($p);}

#build
static function build($p){return parent::build($p);}

static function mkkey($n){//n=32:256bits,16=128bits
return base64_encode(openssl_random_pseudo_bytes($n,$strong));}//substr(,0,-2)
static function encrypt($d,$ek,$iv){
return openssl_encrypt($d,'AES-256-CBC',$ek,0,$iv);}
static function decrypt($d,$ek,$iv){
return openssl_decrypt($d,'AES-256-CBC',$ek,0,$iv);}

static function mkkey2($n){return base64_encode(sodium_crypto_secretbox_keygen());}
static function nounce($d,$n){return base64_encode(sodium_randombytes_buf($d,$n));}
static function encrypt2($d,$ek,$iv){return sodium_crypto_secretbox($d,$iv,$ek);}
static function decrypt2($d,$ek,$iv){return sodium_crypto_secretbox_open($d,$iv,$ek);}

static function batch_encrypt($d,$ek,$iv){
$r=str_split($d,500); $ret=''; $iv=base64_decode($iv.'==');
foreach($r as $k=>$v)$ret.=self::encrypt($v,$ek,$iv).'0000';
return $ret;}

static function batch_decrypt($d,$ek,$iv){
$r=explode('0000',$d); $ret=''; $iv=base64_decode($iv.'==');
foreach($r as $k=>$v)$ret.=self::decrypt($v,$ek,$iv);
return $ret;}

#play
static function read($p){
$id=$p['id']; $iv=val($p,'iv'.$id);
$r=sql('txt,ek,iv',self::$db,'ra',$id);
if($iv!=$r['iv'])return help('bad key');
$ret=self::batch_decrypt($r['txt'],$r['ek'],$iv,1);
$ret=conn::com($ret,1);
return $ret;}

static function play($p){
$r=self::build($p);
$id=$p['id'];
$j='txt'.$id.'|crypt,read|id='.$id.'|iv'.$id;
$ret=tag('h2','',$r['tit']);
$ret.=div(inputcall($j,'iv'.$id,'','24',lang('public key'),'',[]),'article','txt'.$id);
return $ret;}

static function stream($p){return parent::stream($p);}

#call (read)
static function tit($p){return parent::tit($p);}

static function call($p){return parent::call($p);}

#com (edit)
static function com($p){return parent::com($p);}
static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
//self::install();
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>