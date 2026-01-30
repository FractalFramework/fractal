<?php
class meteodb{	
static $private=2;
static $db='meteodb';
static $a='meteodb';
static $cb='dbs';
static $db2='meteo_paris';
static $stations=["07005"=>"ABBEVILLE","07015"=>"LILLE-LESQUIN","07020"=>"PTE DE LA HAGUE","07027"=>"CAEN-CARPIQUET","07037"=>"ROUEN-BOOS","07072"=>"REIMS-PRUNAY","07110"=>"BREST-GUIPAVAS","07117"=>"PLOUMANAC'H","07130"=>"RENNES-ST JACQUES","07139"=>"ALENCON","07149"=>"ORLY","07168"=>"TROYES-BARBEREY","07181"=>"NANCY-OCHEY","07190"=>"STRASBOURG-ENTZHEIM","07207"=>"BELLE ILE-LE TALUT","07222"=>"NANTES-BOUGUENAIS","07240"=>"TOURS","07255"=>"BOURGES","07280"=>"DIJON-LONGVIC","07299"=>"BALE-MULHOUSE","07314"=>"PTE DE CHASSIRON","07335"=>"POITIERS-BIARD","07434"=>"LIMOGES-BELLEGARDE","07460"=>"CLERMONT-FD","07471"=>"LE PUY-LOUDES","07481"=>"LYON-ST EXUPERY","07510"=>"BORDEAUX-MERIGNAC","07535"=>"GOURDON","07558"=>"MILLAU","07577"=>"MONTELIMAR","07591"=>"EMBRUN","07607"=>"MONT-DE-MARSAN","07621"=>"TARBES-OSSUN","07627"=>"ST GIRONS","07630"=>"TOULOUSE-BLAGNAC","07643"=>"MONTPELLIER","07650"=>"MARIGNANE","07661"=>"CAP CEPET","07690"=>"NICE","07747"=>"PERPIGNAN","07761"=>"AJACCIO","07790"=>"BASTIA","61968"=>"GLORIEUSES","61970"=>"JUAN DE NOVA","61972"=>"EUROPA","61976"=>"TROMELIN","61980"=>"GILLOT-AEROPORT","61996"=>"NOUVELLE AMSTERDAM","61997"=>"CROZET","61998"=>"KERGUELEN","67005"=>"PAMANDZI","71805"=>"ST-PIERRE","78890"=>"LA DESIRADE METEO","78894"=>"ST-BARTHELEMY METEO","78897"=>"LE RAIZET AERO","78922"=>"TRINITE-CARAVEL","78925"=>"LAMENTIN-AERO","81401"=>"SAINT LAURENT","81405"=>"CAYENNE-MATOURY","81408"=>"SAINT GEORGES","81415"=>"MARIPASOULA","89642"=>"DUMONT D'URVILLE"];

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

#readcsv
static function meteocls($r){$rb=[];
foreach($r as $k=>$v){
	if($v=='date')$typ='bint';
	elseif($v=='ff' or $v=='t' or $v=='td')$typ='double';
	else $typ='int';
	if($v)$rb[$v]=$typ;}
return $rb;}

static function meteovls($r){$rb=[]; $na=59; echo 'e';
foreach($r as $k=>$v)if($v){
	foreach($v as $ka=>$va){
		if($ka==1)$va=date('Y-m-d H:i:s',strtotime($va));
		if($va=='mq' or !$va or $va=='null')$r[$k][$ka]='0';
		if(($va=='ff' or $va=='t' or $va=='td') && !$va)$r[$k][$ka]='null';}
	$n=count($r[$k]); if($n<$na)$r[$k]=array_pad($r[$k],$na,'0');
	if($n>$na)$r[$k]=array_slice($r[$k],0,$na);
	//echo $n.'-'.count($r[$k]).' ';//
	}
return $r;}

#call
static function call($p){$ret='';
$fa=val($p,'inp1'); $db=self::$db; $ex='';
$dr='disk/usr/dav/'; $u=$dr.$fa; $xt=ext($fa);
$dr2='disk/usr/'.ses('usr').'/csv/mfr/'; //rmdir_r($dr2);
if($xt=='.gz'){$fa=struntil(strend($fa,'/'),'.');
	$ub=$dr2.$fa.'.csv'; echo $ub.' ';
	if(!is_dir($dr2))mkdir_r($dr2);
	if(!is_file($ub)){$d=readgz($u); write_file($ub,$d);} $u=$ub;
	//$fb='disk/usr/'.ses('usr').'/csv/'.$fa; if(is_file($fb))unlink($fb);
	}
$r=readcsv($ub,';'); $cols=[]; //echo count(current($r));//pr($r);
$rk=array_shift($r); //p($rk);
//$cols=self::meteocls($rk); p($cols);
//if($cols)sql::create($db,$cols,1);
$r=self::meteovls($r);//archive/1626029836642.gz
$rt=array_shift($r); $dt=$rt[1]; $ex=sql('date',self::$db,'v',['date'=>$dt]);
if($ex)echo 'already exists:'.$dt.' '; else 
sql::sav2('meteodb',$r,1);//,'','',1
$n=count($r); $ret=$n.' lines'.br();
//if($n>100)$r=array_chunk($r,100); pr($r); //$ret.=tabler($r);
return $ret;}

static function batch(){
$dr='usr/dav/meteodb'; $ret='';
$ra=scandir_a($dr); //pr($ra);
$dr2='disk/usr/'.ses('usr').'/'; //rmdir_r($dr2.'csv/mfr'); //rmdir_r($dr2.'archive');
if(!is_dir($dr2.'csv/mfr'))mkdir_r($dr2.'csv/mfr');
$n=6; $l=48; $a=$n*$l; $b=$a+$l;
if($ra)foreach($ra as $k=>$v){
	$fa=strfrom($v,'.');
	$u=$dr.'/'.$v; //echo $u.':';
	//$ub=$dr2.'/archive/'.$fa; //echo $ub.' '; 
	//if(!is_file($ub))copy($u,$ub);
	//if($k>=$a && $k<$b)//$ret.=$k.'-';
	//$ret.=self::call(['inp1'=>$v]);
	}
//rmdir_r($dr);
return $ret;}

static function secondary(){
$cls1='date,pmer,dd,ff,t,u,cod_tend,pres,ht_neige,rr3,ctype3,hnuage3';//ww
$cls='date,pressure,dirwind,windspeed,temperature,humidity,weather,barometer,snow,rain,clouds,cloudheight';
$clt='datetime,int,int,double,double,int,int,int,int,int,int,int';
$rc=explode(',',$cls); $rt=explode(',',$clt); //$cols=array_combine($rc,$rt); sql::create(self::$db2,$cols,1); //pr($cols);
$r=sql($cls1,self::$db,'',['numer_sta'=>'07149']); echo count($r);
foreach($r as $k=>$v){//$r[$k][0]=date('Y-m-d H:i:s',strtotime($v[0]));//0°=273.15K
	foreach($v as $kb=>$vb)$r[$k][$kb]=$vb?$vb:($rt[$kb]=='date'?'':'null');
	if(is_numeric($r[$k][4]))$r[$k][4]=round($r[$k][4]-273.15,2);}
$r=sql::vrf($r,self::$db2);
sql::sav2(self::$db2,$r,1);}

static function res($p){$p1=$p['p1']??''; $r=[];
//$date=new DateTime(time()); $week=$date->format('W');
//SELECT YEAR(date) as yr,AVG(temperature) FROM `meteo_paris` WHERE MONTH(date)=5 group by yr
$wk=date('W'); $hr=date('H'); $dayr=date('z'); //$hr=$hr-$hr%3;////MONTH ////
if($p1=='avgweeks')
return sql('YEAR(date) as yr,ROUND(AVG(temperature),2)','meteo_paris','kv','WHERE WEEKOFYEAR(date)='.$wk.' and HOUR(date)="'.$hr.'" group by yr');
if($p1=='months')
return sql('DATE_FORMAT(date,"%Y%m%d%H") as yr,temperature','meteo_paris','kv','WHERE DAYOFYEAR(date)>='.($dayr-30).' && DAYOFYEAR(date)<='.($dayr).'');
if($p1=='weeks')
return sql('DATE_FORMAT(date,"%Y%m%d%H") as yr,temperature','meteo_paris','kv','WHERE WEEKOFYEAR(date)='.$wk.'');
if($p1=='days')
return sql('DATE_FORMAT(date,"%Y%m%d%H") as yr,temperature','meteo_paris','kv','WHERE DAYOFYEAR(date)=DAYOFYEAR(CURDATE())');//DATE(NOW())////CURDATE()-interval 7 day
return $r;}

static function avgv($p){
$r=self::res($p);
return tabler($r,'',1);}

static function api($p){
$r=self::res($p);
return json_encode($r);}

static function patch(){$n=7; $l=10000; $a=$n*$l; $b=$a+$l;
$r=sql('date,cod_tend',self::$db,'kv','where numer_sta=07149 limit '.$a.','.$b);
foreach($r as $k=>$v)sql::upd('meteo_paris',['weather'=>$v?$v:'null'],['date'=>$k]);}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
//$bt=bj(self::$cb.',,z|meteodb,batch','go','btn');
$j=self::$cb.',,z|'.self::$a.',call||inp1';
$bt=inputcall($j,'inp1','','','1');
$bt.=bj($j,pic('go'),'btn');
$bt.=upload::call('inp1');
//$bt.=bj(self::$cb.',,z|'.self::$a.',batch','batch','btn');
//$bt.=bj(self::$cb.',,z|'.self::$a.',secondary','paris','btn');
//$bt.=bj(self::$cb.',,z|'.self::$a.',patch','patch','btn');
$bt.=bj(self::$cb.',,z|'.self::$a.',avgv|p1=weeks','avg','btn');
return $bt.div('','pane',self::$cb);}
}
?>