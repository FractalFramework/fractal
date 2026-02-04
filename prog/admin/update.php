<?php
class update{
static $private=6;

static $dr=['prog','fonts','cnfg/site.com.php','disk/db/sys','usr/tlex/','tar','api.php','boot.php','call.php','index.php','pub','disk/json/system/colors.json','disk/db/system/connectors.php','img/full/82e1fe.png','usr/_pub','usr/_db','favicon.ico','version.txt'];

static function archive(){
$r=self::$dr; $f='fractal.tar';
upsql::archive();//essential datas in usr/_db
if(is_file($f.'.gz'))unlink($f.'.gz');
$f=tar::buildFromList($f,$r);
return lk('/'.$f,ico('download').$f,'btn');}

static function echo_r($r,$o=''){$ret=[];
foreach($r as $k=>$v)if(is_array($v))$ret[]=self::echo_r($v,$o); else $ret[]=$v;
return implode($o,$ret);}

static function mk_r($d){
$r=explode(';',$d);
if($r)foreach($r as $v){
	[$f,$date]=explode(':',$v);
	$ret[$f]=$date;}
return $ret;}

#get dates (local or distant)
static function w_date($dr,$f){
$fb=($dr?$dr.'/':'').$f;
return $fb.':'.date('ymd.His',filemtime($fb));}

static function localfdates($p=''){$ret=[];
$r=self::$dr; $r[]='prog';
foreach($r as $v){
	if(is_dir($v))$ret[$v]=walkdir('update::w_date',$v);
	elseif(is_file($v)){
		$dr=struntil($v,'/'); $f=strend($v,'/');
		$ret[$v]=self::w_date('',$v);}}
if(isset($ret))return self::echo_r($ret,';');}

#load dl (client)
static function dlfile($p){$f=$p['file'];
if($f) return base64_encode(file_get_contents($f));}

//build list of files to dl
static function files2dl(){$ret=[];
$d=self::localfdates();//local
$local=self::mk_r($d); //pr($local);
$f=srv(1).'/api/update/mth:localfdates';//distant
$d=get_file($f);
$distant=self::mk_r($d); //pr($distant);
if($distant)foreach($distant as $k=>$v)
	if(array_key_exists($k,$local)){if($v>$local[$k])$ret[]=$k;}
if($local)foreach($local as $k=>$v)//obsoletes
	if(!array_key_exists($k,$distant))unlink($k);
return $ret;}

static function preview($p){
$ra=self::mk_r(self::localfdates());
$distfdates=get_file(srv(1).'/api/update/mth:localfdates');
$rb=self::mk_r($distfdates);
$ret[]=['file','local','distant'];
if($rb)foreach($rb as $k=>$v)
	if(array_key_exists($k,$ra))$ret[]=[$k,$ra[$k],$v,$v>$ra[$k]?ico('warning'):''];
	else $ret[]=[$k,'',$v,''];
return tabler($ret);}

//dl(distant)
static function builddl($p){
$r=explode('|',get('files'));
if($r)return tar::buildFromList('pub/dl/ffw.tar',$r);}

//dl(local)
static function loaddl(){//return 'need dev';
$rid=randid('dl');
if(srv()==host())return;
$r=self::files2dl(); //pr($r);
if($r)foreach($r as $k=>$v){
	$f=srv(1).'/api/update/mth:dlfile,file:'.$v;
	$d=get_file($f); $d=base64_decode($d); $er='';
	if($d)$er=write_file($v,$d); else $er=1;
	if($d && $er){unset($r[$k]); $rb[]=$v;}}
$ret=hr().count($r).' '.lang('files updated').hr().self::echo_r($r,br());
if(isset($rb))$ret.=hr().count($rb).' '.lang('errors').hr().self::echo_r($rb,br());
return $ret;}

#interface
static function content($p){
$f=$p['f']??''; $ret='';
if(auth(4))$ret=toggle('cbupd,,z|update,preview',langp('preview'),'btn');
if(auth(4))$ret.=toggle('cbupd,,z|update,loaddl',langp('update software'),'btdel');
if(auth(6))$ret.=toggle('cbupd|upsql|local=0',langp('databases'),'btn');
if(auth(6))$ret.=toggle('cbupd,,z|upsql,upim|local=0',langp('images'),'btn');
if(auth(6))$ret.=toggle('cbupd,,z|upsql,upfl|local=0',langp('files'),'btn');
if(auth(2))$ret.=toggle('cbupd,,z|update,archive',langp('software'),'btn');
return $ret.div('','','cbupd');}
}
?>