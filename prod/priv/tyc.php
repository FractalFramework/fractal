<?php
class tyc{	
static $private=2;
static $db='tyc';
static $db2='tyc2';
static $a='tyc';
static $cb='vzr';

/*static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}*/

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
$ret=lang('result').': '.val($p,'v1').' '.val($p,'inp1');
return $ret;}

static function readcsv($f,$s="\t",$n=0,$l=1000){
$rb=[]; $a=$l*$n; $b=$a+$l;
if(($h=fopen($f,'r'))!==false){$k=0; $na=0;
while(($r=fgetcsv($h,300,$s))!==false){$nb=count($r);
if($na>=$a && $na<$b)for($i=0;$i<$nb;$i++)$rb[$k][]=$r[$i]; $k++; $na++;} fclose($h);}
return $rb;}

static function bigcsv($f,$s,$n,$l,$ml=false){
$h=fopen($f,'r'); $r=[]; $i=0; $a=$l*$n; $b=$a+$l;
while($i>=$a && $i<$b && ($b=fgets($h))!==false){$r[]=fgetcsv($h,$ml,$s); $i++;}
fclose($h); return $r;}

static function bigcsv1($f,$s='\t',$n=0,$mx=10,$l=8000){
$fp=fopen($f,'r'); $r=[]; if($n>0)$ia=0; else $ia=-1;
while($ia<$mx && ($rk=fgetcsv($fp,$l,$s))!==false){$r[]=$rk; if($mx>0)$n++;}
fclose($fp);
return $r;}

static function bigcsv2($f,$n=0,$l=10){
$r=file($f); $r=array_slice($r,$l*$n,$l); $rc=[];
foreach($r as $k=>$v){$rb=explode('|',$v); foreach($rb as $kb=>$vb)$rc[$k][$kb]=trim($vb);}
return $rc;}

#readcsv
static function tyccls($r){$rb=[];
foreach($r as $k=>$v){
	if($v=='tyc123' or $v=='pflag' or $v=='posflg' or $v=='TYC')$typ='svar';
	elseif($v=='HIP' or $v=='prox' or $v=='Num' or $v=='e_RAmdeg' or $v=='e_DEmdeg')$typ='int';
	else $typ='double';
	if($v)$rb[$v]=$typ;}
return $rb;}

static function tycvls($r){$rb=[]; $na=32;
foreach($r as $k=>$v)if($v){
	foreach($v as $ka=>$va){$va=trim($va);
		if($va=='###' or $va==''){
			if($ka==1 or $ka==30)$r[$k][$ka]='';// && $ka==22 && $ka==23
			else $r[$k][$ka]='null';}
		}
	//$n=count($r[$k]); if($n<$na)$r[$k]=array_pad($r[$k],$na,'null'); echo $n.'-'.count($r[$k]).' ';
	}
return $r;}

#call
static function call($p){$ret='';
$fa=val($p,'inp1'); $db=self::$db; $nb=$p['n'];
$dr='usr/'.ses('usr').'/tyc/'; $u=$dr.$fa;
//$dr2='disk/usr/'.ses('usr').'/csv/tyc/'; if(!is_dir($dr2))mkdir_r($dr2);
$xt=ext($fa);
if($xt=='.gz'){$d=readgz($u); $xt=ext($fa); $fa=strend($fa,'/'); $fa=struntil($fa,'.'); 
	$u=$dr2.$fa.'.csv'; echo $u.' '; if(!is_dir($dr))mkdir_r($dr);
	//if(is_file($u))unlink($u); //rmdir_r($fb);
	if(!is_file($u))write_file($u,$d);}
chrono('');
//$r=self::readcsv($u,'|',0,100); $cols=[]; //pr($r);
//$r=self::bigcsv($u,'|',0,1000); $cols=[]; //pr($r);
//$bc=new bigcsv($u,false,'|',300); $r=$bc->get(10);
//$r=self::bigcsv1($u,'|',0,10,300);
//$r=self::bigcsv2($u,1,10);
$r=bigcsv($u,$nb,20000,'|');
echo chrono('csv');
//$rk=array_shift($r); //p($rk);
//$cols=self::tyccls($rk); p($cols); echo count($cols);
//if($cols)sql::create($db,$cols,1);
$r=self::tycvls($r); 
//pr($r);
$rt=$r[0]; $di=$rt[0]; $ex=sql('id',self::$db,'v',['tyc123'=>$di],0); echo $ex;
if($ex)echo 'already exists:'.$di.' '; else 
sql::sav2($db,$r,1);
$n=count($r); $ret=$n.' lines';
//if($n>100)$r=array_chunk($r,100); pr($r); //$ret.=tabler($r);
return $ret;}

static function batch(){
$dr='usr/dav/tyc'; $ret='';
$ra=scandir_a($dr); //pr($ra);
//$dr2='disk/usr/'.ses('usr').'/csv/tyc'; //rmdir_r($dr2);
if($ra)foreach($ra as $k=>$v){
	$fa=strfrom($v,'.');
	$u=$dr.'/'.$v; //echo $u.':';
	//$ret.=self::call(['inp1'=>'archive/'.$fa]);
	//$rb=file($u); $n=count($rb); $m=ceil($n/20000);
	$n=ceil(127/20);
	$j=self::$cb.',,z|tyc,call|inp1='.$v.',n=';
	$ret.=$v.': ';
	for($i=0;$i<$n;$i++)$ret.=bj($j.$i,$i,'btn').' ';
	$ret.=br();}
//rmdir_r($dr);
return div($ret,'');}

static function secondary(){
$cls='tyc123,RAmdeg,DCmdeg,pmRA,pmDE,BTmag,RAdeg,DEdeg,HIP';
$clt='svar,double,double,double,double,double,double,double,svar';
$rc=explode(',',$cls); $rt=explode(',',$clt); $cols=array_combine($rc,$rt);
if($cols)sql::create(self::$db2,$cols,1);
$r=sql($rc,self::$db,'','');
}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
//$bt=bj(self::$cb.',,z|tyc,batch','go','btn');
$j=self::$cb.',,z|'.self::$a.',call||inp1';
$bt=inputcall($j,'inp1','','','1');
$bt.=bj($j,pic('go'),'btn');
$bt.=upload::call('inp1');
$bt.=bj('kmnu|'.self::$a.',batch','batch','btn');
$bt.=bj('kmnu|'.self::$a.',secondary','secondary','btn');
return $bt.div('','','kmnu').div('','pane',self::$cb);}
}

class bigcsv{
private $fp; private $ph; private $h; private $s; private $l;
function __construct($f,$ph=false,$s='\t',$n=0,$l=8000){$this->fp=fopen($f,'r');
$this->ph=$ph; $this->s=$s; $this->l=$l; if($this->ph)$this->h=fgetcsv($this->fp,$this->l,$this->s);}
function __destruct(){if($this->fp)fclose($this->fp);}
function get($mx=0){$r=[]; if($mx>0)$n=0; else $n=-1;
while($n<$mx && ($rk=fgetcsv($this->fp,$this->l,$this->s))!==false){
if($this->ph){foreach($this->h as $i=>$v)$rb[$v]=$rk[$i]; $r[]=$rb;} else $r[]=$rk;
if($mx>0)$n++;}
return $r;}}

?>