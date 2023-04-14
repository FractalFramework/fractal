<?php
class csv2sql{	
static $private=2;
static $db='csv2sql';
static $cols=['tit','txt'];
static $typs=['var','bvar'];
static $a='csv2sql';
static $cb='dbs';

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
$ret=lang('result').': '.val($p,'v1').' '.val($p,'inp1');
return $ret;}

#readcsv
static function nbcls($r,$na){$rb=[];
foreach($r as $k=>$v)if($v){
	foreach($v as $ka=>$va){if(!$va)$r[$k][$ka]='null';}
	$n=count($r[$k]); if($n<$na)$r[$k]=array_pad($r[$k],$na,'null');}
return $r;}

#call
static function call($p){$ret='';
$fa=val($p,'inp1'); $dba=strfrom($fa,'/'); $db='cls_'.$dba;
$dr='disk/usr/'.ses('usr').'/'; $u=$dr.$fa;
sql::savif(self::$db,['tit'=>$fa,'txt'=>$db]);
$xt=ext($fa);
if($xt=='.gz'){$d=readgz($u); $xt=ext($fa); $fa=strend($fa,'/'); $fa=struntil($fa,'.'); 
	$dr=$dr.'csv/'.$dba.'/'; $u=$dr.$fa.'.csv'; echo $u.' ';
	if(!is_dir($dr))mkdir_r($dr);
	if(!is_file($u))write_file($u,$d);}
//$fb='disk/usr/'.ses('usr').'/gz'; if(is_file($fb))unlink($fb); rmdir_r($fb);
$r=readcsv($u,';'); $cols=[]; //pr($r);
$rk=array_shift($r); //p($rk);
//if($cols)sql::create($db,$cols,1);
$r=self::nbcls($r,59);
//sql::sav2($db,$r,1);
$n=count($r); $ret=$n.' lines';
//if($n>100)$r=array_chunk($r,100); pr($r); //$ret.=tabler($r);
return $ret;}

#content
static function content($p){
self::install();
$p['p1']=$p['p1']??'';
//$bt=bj(self::$cb.',,z|csv2sql,batch','go','btn');
$j=self::$cb.',,z|'.self::$a.',call||inp1';
$bt=inputcall($j,'inp1','','','1');
$bt.=bj($j,pic('go'),'btn');
$bt.=upload::call('inp1');
return $bt.div('','pane',self::$cb);}
}
?>