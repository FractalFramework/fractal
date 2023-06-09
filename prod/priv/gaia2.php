<?php

class gaia2{	
static $private=6;
static $a='gaia';
static $db='gaia2';
static $db2='gaia2_index';
static $cols=['gid','ra','dc','parallax','mag','radius','lum'];//uid used for gaia id
static $typs=['bint','double','double','double','double','double','double'];

function __construct(){
$r=['a','db','cols','db2'];
foreach($r as $v)appx::$$v=self::$$v;}

static function install($p=''){
sqlcreate(self::$db2,['f'=>'var','ok'=>'int'],1);
appx::install(array_combine(self::$cols,self::$typs));}

static function admin(){
$r[]=['','j','popup|gaia2,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=gaia2_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=gaia','code','Code'];
return $r;}

static function injectJs(){return '
function batchtime(){
	var n=getbyid("step").value; //alert(n);
	ajx("div,gaiaa|gaia2,call|p1="+n);//if(n<10000)
	x=setTimeout("batchtime()",3000);}
//setTimeout("batchtime()",10);
function playstop(){if(typeof x!="undefined")clearTimeout(x);}
';}
static function headers(){
	add_head('csscode','');
	add_head('jscode',self::injectJs());}

static function titles($p){
$d=$p['appMethod']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#build
/*static function build($p){$id=val($p,'id');
	$r=sql('all',self::$db,'ra',$id);
	return $r;}*/

static function search($p){$id=val($p,'inp1');
$r=sql('f',self::$db2,'rv',''); $ra='';
foreach($r as $k=>$v){
	list($a,$s,$e)=explode('_',$v);
	if($s<$id && $e>$id)$ra[]=$v;}
if($ra)$f=$ra[4];
if(!$f)return span('no','error');
$u='http://cdn.gea.esac.esa.int/Gaia/gdr2/gaia_source/csv/';
foreach($ra as $k=>$f){
	$fb='usr/gaia2/'.substr($f,0,-3); mkdir_r($fb); //echo $fb.br();
	if(!is_file($fb.'.gz'))copy($u.$f,$fb.'.gz');
	if(is_file($fb.'.gz') && !is_file($fb)){$d=readgz($fb.'.gz'); $er=write_file($fb,$d);}
	if(is_file($fb)){$h=fopen($fb,'r'); $i=0;
		while($r=fgets($h))if($r){$ra=explode(',',$r);//each line
			$gid=str_replace('Gaia DR2 ','',$ra[1]);
			if($gid==$id or $i==0)$rb[]=$ra; $i++;}
		fclose($h); unlink($fb.'.gz'); unlink($fb);}
	$bt=lk($u.$f,$f,'small');
}
return $bt.tabler($rb,1);}

#dl
//56 columns : https://www.rave-survey.org/project/documentation/dr5/rave_tgas/
//astrometric_weight_al = 34
//phot_g_mean_flux = 47
//phot_g_mean_mag = 50 (mag)
//radius_val = 88
//lum = 91 //tot=93
static function fscan($f){$h=fopen($f,'r'); $rb=[]; $ret='';
//if($fp){while(!feof($fp))$ret.=fread($fp,8192); fclose($h);} 
while($r=fgets($h))if($r){$ra=explode(',',$r);//each line with parallax//fgets($h,8192)
	//list($gid,$ad,$dec,$par,$mag,$radius,$lum)=vals($ra,[1,5,7,9,50,88,91]);
	//echo $gid.'-'.$ad.'-'.$dec.'-'.$par.'-'.$tad.'-'.$tdc.'-'.$lum;
	$gid=str_replace('Gaia DR2 ','',$ra[1]);//$ra[12],$ra[14],
	//Iumma: 12h 31m 14s, +9� 18' 7, 14.6Al = 4,47638004pc = 223,39479469218614423095318779055 mas
	//find Iumma ra:188.675, dec:9.3117, parallax:223.395
	//ra between: 12h 20m 00s=185, 12h 44m 00s=191
	//dec between: +9� 00' 00"=9, +9� 36' 00"=9.6
	//parallax between: 14Al=4.29pc=233, 15Al=4.6pc=217
	//pr([$gid,$ra[5],$ra[7],$ra[9],$ra[50],$ra[88],$ra[91]]);
	if(!empty($ra[9])){$ra[5]=abs($ra[5]); $ra[7]=abs($ra[7]); $ra[9]=abs($ra[9]);
		//if($ra[5]>185 && $ra[5]<191 && $ra[7]>9 && $ra[7]<9.6 && $ra[9]>217 && $ra[9]<233)
		//if($ra[5]>180 && $ra[5]<200 && $ra[7]>8.5 && $ra[7]<10 && $ra[9]>217 && $ra[9]<240)
		if($ra[5]>180 && $ra[5]<200 && $ra[7]>8 && $ra[7]<11)// && $ra[9]>217 && $ra[9]<240
		//if($ra[9]<0.24)
		$rb[]=[$gid,$ra[5],$ra[7],$ra[9],$ra[50],$ra[88],$ra[91]];}}//>0.01 or $ra[9]<-0.01
fclose($h);
if($rb)array_shift($rb); //pr($rb);
return $rb;}

static function dlgz($f,$id){$er='';
$u='http://cdn.gea.esac.esa.int/Gaia/gdr2/gaia_source/csv/';
//GaiaSource_1000172165251650944_1000424567594791808.csv.gz
$fb='usr/gaia2/'.substr($f,0,-3); mkdir_r($fb); //echo $fb.br();
if(!is_file($fb.'.gz'))copy($u.$f,$fb.'.gz');
if(is_file($fb.'.gz') && !is_file($fb)){$d=readgz($fb.'.gz'); $er=write_file($fb,$d);}
if(is_file($fb)){
	$r=self::fscan($fb);
	if($r)sqlsav2(self::$db,$r,1); 
	//$er=write_file($fc,$fb);
	sqlup(self::$db2,'ok',1,$id);
	unlink($fb.'.gz'); unlink($fb);}//lock
return lk($u,$f,'btn',1).' '.count($r).' objects added';}

static function batch($r){$i=0; $w=10; $n=12; //5
foreach($r as $k=>$v){$i++; if($i>=$n*$w && $i<($n+1)*$w)$rb[]=self::dlgz($v);}// sleep(20);
return $rb;}

#read
static function call($p){
$id=val($p,'p1'); $ret=''; //echo $n.' - ';
$d=sql('f',self::$db2,'v',$id);
$ok=sql('ok',self::$db2,'v',$id);
if($ok)list($id,$d)=sql('id,f',self::$db2,'rw','where id>'.$id.' and ok=0 limit 1');
//$ret=self::batch($r);
if(!$ok && auth(6))$ret=self::dlgz($d,$id); $n=$id+1;
$bt=lk('/gaia2/'.$n,$n,'btsav').' ';
$bt.=btj(pic('stop'),'playstop()','btn').' ';
$bt.=span($ret,'small').hidden('step',$n);
return $bt;}

static function com(){
return self::content($p);}

static function repair(){
$min=1805172020; $max=1805172150;
for($i=$min;$i<$max;$i++)$r[]='z_gaia2_'.$i;
	qr('DROP TABLE '.implode(',',$r),1);
}

#content
static function content($p){//p($p);
//self::install();
//self::repair();
$p['p1']=$p['p1']??''; $n=0;
//$dr='usr/gaia/GaiaSource_000-000-';
//for($i=0;$i<256;$i++)if(file_exists($dr.str_pad($i,3,'0',STR_PAD_LEFT).'.txt'.$i.''))$n=$i;
$bt=input('inp1',1).bj('gaiaa|gaia2,call||inp1',lang('send'),'btn');
$bt.=bj('gaiaa,,z|gaia2,search||inp1',lang('search'),'btn');
$bt.=btj(pic('play'),atj('setTimeout',['batchtime()',10]),'btn').' ';
if($p['p1'])$ret=self::call($p); else $ret='';
return $bt.div($ret,'pane','gaiaa');}
}
?>