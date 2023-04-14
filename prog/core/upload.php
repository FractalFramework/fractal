<?php 

class upload{

/**/static function closebt($ret,$f,$rid){
$cl=btj(ico('close'),atj('closebt',[$f,$rid]));
return span($ret.$cl,'icones','bt'.$rid);}

static function goodir($xt){
if(stristr('.m4a.mpg.mp4.wmv.mov',$xt)!==false)$dir='video';
elseif(stristr('.rar.zip.tar.gz',$xt)!==false)$dir='archive';
elseif(stristr('.txt.docx',$xt)!==false)$dir='word';
elseif(stristr('.pdf',$xt)!==false)$dir='pdf';
elseif(stristr('.jpg.png.gif',$xt)!==false)$dir='img';
elseif(stristr('.mp3.mid',$xt)!==false)$dir='audio';
elseif(stristr('.xls.json.csv',$xt)!==false)$dir='excel';
elseif(stristr('.xhtml.html.htm.xlm',$xt)!==false)$dir='xml';
else $dir='docs';
return $dir;}

static function add_img_catalog($f,$src){
$ex=sql('id','images','v',['img'=>$f,'uid'=>ses('uid')]);
if(!$ex)sql::sav('images',[ses('uid'),substr($f,0,-4),$f,$src,3]);}

static function srv_img($u){$f=strend($u,'/');
$d=get_file($u); if($d)write_file('img/full/'.$f,$d);
self::add_img_catalog($f,'srv');
self::thumb($f,590);
return $f;}

static function import_img($p){
$u=$p['urlim']; $f=strid($u).'.jpg';
$d=get_file($u); if($d)write_file('img/full/'.$f,$d);
self::add_img_catalog($f,'b64');
self::thumb($f,590);
if($p['hk']??'')$f='['.$f.']';
return $f;}

static function import_mov($p){
$u=$p['urlmv']; $hk=$p['hk']??'';
$f=strid($u).ext($u);
$root='disk/usr/'.ses('usr').'/video/'.$f;
$d=get_file($u); if($d)write_file($root,$d);
self::add_img_catalog($f,'mov');
if($hk)$f='['.ses('usr').'/video/'.$f.':video]';
return $f;}

static function thumb($nm,$w,$h=''){if(!$h)$h=$w;
$fa='img/full/'.$nm; mkdir_r($fa);
$fb='img/mini/'.$nm; mkdir_r($fb);
$fc='img/medium/'.$nm; mkdir_r($fc);
mkthumb($fa,$fb,170,170,0);
[$wa,$ha]=getimagesize($fa);
if($wa>$w or $ha>$h)mkthumb($fa,$fc,$w,$h,0);}

static function progress($p){//done in ajax
$rid='upfile'.$p['rid']??''; $ty=$p['ty']??'';
if(isset($_FILES[$rid]))return fsize($_FILES[$rid]['tmp_name'],1);
else return 'uploading...';}

static function save($p){$error=''; $rid='upfile'.($p['rid']??''); $ty=$p['ty']??''; 
$f=$_FILES[$rid]['name']??''; $f_tmp=$_FILES[$rid]['tmp_name']??''; //pr($p);
//if(!$f)return 'no file uploaded ';
$xt=ext($f); $f=normalize(struntil($f,'.'));
$goodxt='.mp4.m4a.mov.mpg.mp3.wav.wmv.jpg.png.gif.pdf.txt.xls.csv.json.docx.7z.zip.tar.gz.mid.xhtml.html';
if(stristr($goodxt,$xt)===false)$error=$xt.'=forbidden; authorized='.$goodxt.br();
$fsize=$_FILES[$rid]['size']/1024; $uplimit=250000;
if($fsize>=$uplimit || $fsize==0)$error.=$fsize.'<'.($uplimit/1024).'Mo ';
if(!$ty)$ty=self::goodir($xt);
if($ty=='img')$dir='img/full/';
else $dir='disk/usr/'.ses('usr').'/'.$ty.'/';
if(!is_dir($dir))mkdir_r($dir);
//$fa=strid($f,8).$xt;//defined by js
$fa=$f.$xt; $fb=$dir.$fa;
if(is_uploaded_file($f_tmp) && !$error){
	if(!move_uploaded_file($f_tmp,$fb))$error.='not saved';
	else{$ico='file-'.$ty.'-o';
		tlxf::dsksav(['p1'=>$fa,'com'=>$ty,'tit'=>$f]);
	if($xt=='.tar.gz')$fb=tar::untar($fb,1);
	if($xt=='.tar')$fb=tar::untar($fb);}}
else $error.='upload refused: '.$fa;
if($error)$ret=div($error,'alert');
elseif($ty=='img'){self::thumb($fa,590);
	self::add_img_catalog($fa,'tlx');
	$ret=img('/img/mini/'.$fa,72,72);}
else $ret=ico($ico,24).$fa;
//if($p['getinp'])$ret=$fb;
$ret=self::closebt($ret,$f,$p['rid']);
return $ret;}

static function pick($p){$id=$p['id']; $ret='';
$r=sql('img','images','rv','where uid="'.ses('uid').'" order by up desc'); $o=$p['o']??'';
if($r)foreach($r as $k=>$v){
	$im=img2($v,'micro'); if($o==1)$j='['.$v.']'; elseif($o==2)$j=$v.','; else $j=$v;
	$ret.=btj($im,atj($o?'insert':'val',[$j,$id]));}//.atj('Close','popup')
return div($ret,'scroll','','min-width:320px;');}

static function img($rid,$o='',$cb=''){
return pickim($rid,$o,$cb).self::call($rid);}

static function call($rid,$o=''){$ret='';
if($o=='img')$ret=build::import_img(['tg'=>$rid,'hk'=>0]);
if($o=='mov')$ret=build::import_mov(['tg'=>$rid,'hk'=>0]);
$ret.='<form id="upl'.$rid.'" action="" method="POST" onchange="upload(\''.$rid.'\',\''.ses('usr').'\')"><label class="uplabel btn"><input type="file" id="upfile'.$rid.'" name="upfile'.$rid.'" multiple />'.ico('upload').'</label></form><span id="'.$rid.'up"></span>';
return $ret;}

}
?>