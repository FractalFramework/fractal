<?php

//application not based on appx
class meteograph{	
static $private=2;
static $a=__CLASS__;
static $db='meteograph';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='mdb';
static $rh=['weather','temperature','barometer','humidity','rain','radiation','wind'];

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function mode($r){
if(!is_array($r))return 0;
$rb=array_count_values($r);
$rb=array_flip($rb); ksort($rb);
return array_pop($rb);}

static function average($r){
if(!is_array($r))return 0;
return round(array_sum($r)/count($r));}

static function middle($r){sort($r);
$n=floor(count($r)/2); $odd=$n%2;
return $odd?$r[$n]:($r[$n-1]+$r[$n])/2;}

static function mediane($r,$nq=2){sort($r); $ns=0; $range=0;
$n=count($r); $sum=array_sum($r); $odd=round($sum)%2; $n2=floor($sum/$nq)+1;//middle value
foreach($r as $k=>$v){$ns+=$v; if($ns<$n2)$range=$k;}//search range
return [$range,$r[$range]??0];}

static function boxplot($r){sort($r); //p($r);
$n=count($r); $min=$r[0]??0; $max=$r[$n-1]??1;
//[$rn2,$qv2]=self::mediane($r,2);//median
[$rn1,$qv1]=self::mediane($r,4);//first quartile
[$rn3,$qv3]=self::mediane($r,4/3);//last quartile
foreach($r as $k=>$v)$rb[$v][]=1;//effectifs
return [$min,$qv1,$qv3,$max];}

static function build($p){
$day=date('ymdH'); $insee=$p['insee']??''; if(!$insee)$insee=75101;//92012
$r=sql('day,res','meteo','kv',['insee'=>$insee],0); //pr($r);
if($r)foreach($r as $k=>$v){$rb=json_decode($v,true); $ka=substr($k,0,6); $kb=substr($k,6);
	$rc[$ka][]=[$rb['weather'],$rb['temperature'][0],$rb['barometer'][0],$rb['outside_humidity'][0],$rb['probarain'],$rb['solar_radiation'][0],$rb['wind_speed'][0]];}
return $rc;}

#play
static function stats($p,$r,$m){$ret=''; //pr($r);
$rb=[]; $rc=[]; if(!is_numeric($m))$rc['_']=self::$rh;
if($r)foreach($r as $k=>$v)foreach($v as $ka=>$va)foreach($va as $kb=>$vb)if($vb)$rb[$k][$kb][]=$vb; //pr($rb);
if($rb)foreach($rb as $k=>$v){//pr($v[0]);
	if(isset($v[0]))$rc[$k][0]=self::mode($v[0]);//weather
	if($m==1)$rc[$k][1]=self::boxplot($v[1]??[]);//temp
	elseif($m==2){if($v[1]??'')$rc[$k][]=$v[1]??[];}//alltemp
	else $rc[$k][1]=self::average($v[1]??[]);//temp
	$rc[$k][2]=self::average($v[2]??0);//baro
	$rc[$k][3]=self::average($v[3]??0);//hum
	$rc[$k][4]=isset($v[4])?self::average($v[4]):0;//rain
	$rc[$k][5]=isset($v[5])?max($v[5]):'';//radiations
	$rc[$k][6]=self::mode($v[6]??0);}//wind
return $rc;}

#play
static function play($r,$m){//pr($r);
if($m==1)$typ='boxes'; elseif($m==4)$typ='histo'; else $typ='lines';
//$rb=[]; foreach($r as $k=>$v){$k=array_shift($v); $rb[$k==0?'_':$k]=$v;}
$rp=['typ'=>$typ,'dk'=>1,'dv'=>0,'ad'=>1,'dc'=>'0','pr'=>is_numeric($m)?0:1,'lb'=>0,'r'=>$r,'t'=>'meteograph:'.$m]; //pr($r);
return graphs::call($rp);}

#compare
static function build2($p){$p1=$p['p1']??''; $p2=$p['p2']??'';
$f='http://logic.ovh/api/meteodb/p1:'.$p1;
$d=get_file($f);//avgweeks//weeks//days
$ra=json_decode($d,true); //pr($ra);
//$date=new DateTime(time()); $wk=$date->format('W'); $dmin=mktime(0,0,0,7,1,2000);
//$wk=date('W'); $hr=date('H'); $hr=$hr-$hr%3; //WEEKOFYEAR(up)='.$wk.'
$day=date('ymdH'); //$day=date('Y-m-d H:00:00');
$day1=date('ymdH',time()-86400*7); $day2=date('ymdH',time()-86400*30);
$rc=[];//need date sys
if($p1=='days')$r=sql('DATE_FORMAT(up,"%Y%m%d%H") as day,res','meteo','kv','where insee=75101 and DAYOFYEAR(up)='.date('z').'');
if($p1=='weeks')$r=sql('DATE_FORMAT(up,"%Y%m%d%H") as day,res','meteo','kv','where insee=75101 and day<='.$day.' and day>'.$day1.' ',0);
if($p1=='months')$r=sql('DATE_FORMAT(up,"%Y%m%d%H") as day,res','meteo','kv','where insee=75101 and day<='.$day.' and day>'.$day2.' order by day',0);
if($r)foreach($r as $k=>$v){$rb=json_decode($v,true); //$ka=substr($k,0,6); $kb=substr($k,6);
	$ra[$k]=$rb['temperature'][0];} //pr($ra);
return $ra;}

static function compare($ra){$ret='';
foreach($ra as $k=>$v){$yr=substr($k,0,4); $dy=substr($k,6,2); $hr=substr($k,8,2); if($v)$rd[$yr][$dy][$hr]=$v;}
foreach($rd as $k=>$v){$re=[]; foreach($v as $kb=>$vb)$re[$kb]=self::boxplot($vb); ksort($re); //pr($re);
	$rp=['typ'=>'boxes','dk'=>0,'dv'=>0,'ad'=>1,'dc'=>'0','pr'=>0,'lb'=>0,'r'=>$re,'t'=>$k.'-'.date('m')];
	$ret.=graphs::call($rp);}
return $ret;}

static function call2($p){$p1=$p['p1']??''; $p2=$p['p2']??''; $rd=[]; $re=[];
$ra=self::build2($p); //pr($ra);
if($p2=='all')return self::compare($ra);
foreach($ra as $k=>$v){$yr=substr($k,0,4); $dy=substr($k,6,2); $hr=substr($k,8,2); if($v)$rd[$yr][$dy.$hr]=$v;} //pr($rd);
foreach($rd as $k=>$v)$re[$k]=self::boxplot($v); //pr($re);
$rp=['typ'=>'boxes','dk'=>0,'dv'=>0,'ad'=>1,'dc'=>'0','pr'=>0,'lb'=>0,'r'=>$re,'t'=>'meteohistory:'.$p1];
return graphs::call($rp);}

#call
static function call($p){
$ret=''; $m=$p['m']??1; if($m=='on')$m=0;
$rm=explode('-',$m);
if(!is_numeric($m))$m='all';
$r=self::build($p); //pr($r);
$rb=self::stats($p,$r,$m); //pr($rb);
if(is_numeric($m))foreach($rb as $k=>$v)$rc[$k]=$m==1?($v[$m]??0):[($v[$m]??0)];
else foreach($rb as $k=>$v)foreach($rm as $ka=>$va)$rc[$k][]=$v[$va]??0;
//else $rc=$rb;
ksort($rc); //pr($rc);
$f='usr/'.ses('usr').'/weather/'.$m;
if($rc)$ret=db::save($f,$rc).self::play($rc,$m);
$fb='usr/'.ses('usr').'/graphics/7';
if($rc)explorer::opsav(['f'=>$fb,'op'=>'import_table','nm'=>$f]);
if(!$ret)return help('no element','txt');
return $ret;}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||insee,m';
$bt=bj($j,langp('ok'),'btn');
$bt.=checkbox('m',self::$rh,1);
$bt.=bj(self::$cb.'|'.self::$a.',call2|p1=days',langp('day'),'btn');
$bt.=bj(self::$cb.'|'.self::$a.',call2|p1=weeks',langp('week'),'btn');
$bt.=bj(self::$cb.'|'.self::$a.',call2|p1=months',langp('month'),'btn');
$bt.=bj(self::$cb.'|'.self::$a.',call2|p1=weeks,p2=all',langp('weeks'),'btn');
$bt.=bj(self::$cb.'|'.self::$a.',call2|p1=months,p2=all',langp('months'),'btn');
$ret=inputcall($j,'insee',$p['p1']??'',32).$bt;
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['param']??$p['p1']??'75101';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>