<?php

class tar{
static function addheader($fp,$fa,$fb){
$info=stat($fa);
$ouid=sprintf("%6s ",decoct($info[4]));
$ogid=sprintf("%6s ",decoct($info[5]));
$omode=sprintf("%6s ",decoct(fileperms($fa)));
$omtime=sprintf("%11s",decoct(filemtime($fa)));
if(@is_dir($fa)){$type="5";
	 $osize=sprintf("%11s ",decoct(0));}
else{$type='';
	 $osize=sprintf("%11s ",decoct(filesize($fa)));
	 clearstatcache();}
$dmajor=''; $dminor=''; $gname=''; $linkname=''; $magic=''; $prefix='';
$uname=''; $version='';
$chunkbeforeCS=pack("a100a8a8a8a12A12",$fb,$omode,$ouid,$ogid,$osize,$omtime);
$chunkafterCS=pack("a1a100a6a2a32a32a8a8a155a12",$type,$linkname,$magic,$version,$uname,$gname,$dmajor,$dminor ,$prefix,'');
$checksum=0;
for($i=0; $i<148; $i++)$checksum+=ord(substr($chunkbeforeCS,$i,1));
for($i=148; $i<156; $i++)$checksum+=ord(' ');
for($i=156,$j=0; $i<512; $i++,$j++)$checksum+=ord(substr($chunkafterCS,$j,1));
fwrite($fp,$chunkbeforeCS,148);
$checksum=sprintf("%6s ",decoct($checksum));
$bdchecksum=pack("a8",$checksum);
fwrite($fp,$bdchecksum,8);
fwrite($fp,$chunkafterCS,356);
return true;}

static function writeContents($fp,$fa){
if(@is_dir($fa))return;
else{
	$size=filesize($fa);
	$padding=$size % 512 ? 512-$size%512 : 0;
	$f2=fopen($fa,"rb");
	while(!feof($f2))fwrite($fp,fread($f2,1024*1024));
	$pstr=sprintf("a%d",$padding);
	fwrite($fp,pack($pstr,''));}}

static function addFooter($f){fwrite($f,pack('a1024',''));}

static function tarfile($fp,$f){
self::addheader($fp,$f,$f); self::writeContents($fp,$f);}

static function tardir($f,$r){$fp=fopen($f,'w+');
foreach($r as $v)self::tarfile($fp,$v);
self::addFooter($fp);
fclose($fp);}

static function read($dr,$o=''){
$fp=opendir($dr); static $i; $ret=array();
while($d=readdir($fp)){$drb=$dr.'/'.$d; $i++;
if(is_dir($drb) && $d!='..' && $d!='.' && $d!='_notes' && !$o)
	$ret=array_merge($ret,self::read($drb));
elseif(is_file($drb) && $d!='.php')$ret[$i]=$drb;}
return $ret;}

static function gz($f){$s=file_get_contents($f);
$ok=file_put_contents($f.'.gz',gzencode($s,9));
if($ok)unlink($f); return $f.'.gz';}

static function buildFromdir($f,$dir){
$r=self::read($dir);
self::tardir($f,$r);
$f=self::gz($f);
return $f;}

static function buildFromList($f,$list){
if(is_file($f))unlink($f);
if(substr($f,-3)=='.gz')$f=substr($f,0,-3);
if(!is_array($list))return;
$fp=fopen($f,'w+');
foreach($list as $v){
	if(is_dir($v)){$r=self::read($v);
		foreach($r as $vb)self::tarfile($fp,$vb);}
	elseif(is_file($v))self::tarfile($fp,$v);}
self::addFooter($fp);
fclose($fp);
$f=self::gz($f);
return $f;}

static function untar($f,$o=''){
$dr=__DIR__; $r=explode('/',$dr); $dr='/'.$r[1].'/'.$r[2];
$e='chmod -R 777 '.$dr; exc($e);
$mode=$o?'zxvf':'xvf'; $xt=$o?'.tar.gz':'.tar';
$e='tar -'.$mode.' '.$dr.'/'.$f.''; exc($e);
return str_replace($xt,'',$f);}

static function call($p){
$f=$p['inp1']??''; $op=val($p,'op');
$f=semf::untar($f);
return $f;}

static function content($p){
$bt=inputcall('tarcb|tar,call|op=untar|inp1','inp1','value1','','1');
return $bt.div('','','tarcb');}

static function zip($fb,$f){
if(!extension_loaded('zip')||!file_exists($f))return false;
$zip=new ZipArchive();
if(!$zip->open($fb,ZIPARCHIVE::CREATE))return false;
$f=str_replace('\\','/',realpath($f));
if(is_dir($f)===true){
$files=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($f),RecursiveIteratorIterator::SELF_FIRST);
foreach($files as $file){
	$file=str_replace('\\','/',$file);
	//Ignore "." and ".." folders
	if(in_array(substr($file,strrpos($file,'/')+1),['.','..']))continue;
	$file=realpath($file);
	if(is_dir($file)===true)$zip->addEmptyDir(str_replace($f.'/','',$file.'/'));
	elseif(is_file($file)===true)$zip->addFromString(str_replace($f.'/','',$file),file_get_contents($file));}}
elseif(is_file($f)===true)$zip->addFromString(basename($f),file_get_contents($f));
return $zip->close();}

}

?>