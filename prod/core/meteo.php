<?php
class meteo{
static $private=0;
static $a=__CLASS__;
static $db='meteo';
static $cb='mto';
static $cols=['uid','insee','day','prm','res'];
static $typs=['int','int','int','svar','text'];
static $towns=['92012'=>'Boulogne-Billancourt','75101'=>'Paris','69381'=>'Lyon','67482'=>'Strasbourg','66136'=>'Perpignan','64102'=>'Bayonne','59350'=>'Lille','38185'=>'Grenoble','35238'=>'Rennes','34172'=>'Montpellier','33063'=>'Bordeaux','31555'=>'Toulouse','13210'=>'Marseille','06088'=>'Nice'];//,'63113'=>'Clermont-Ferrand''44109'=>'Nantes','29019'=>'Brest','2B033'=>'Bastia',

static function js(){}
static function admin($p){}

static function install($p=''){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

#api
static function getkey(){$k=ses('mtkey');
if(!$k)$k=read_file('cnfg/weather.txt');
return ses('mtkey',$k);}

//https://api.meteo-concept.com/documentation#forecast-map-day
//https://api.meteo-concept.com/api/ephemeride/0?token=
static function apicall($vr,$mode){
$k=self::getkey(); //if(!$mode)$mode='ephemeride';
$u='https://api.meteo-concept.com/api/'.$mode.'?'.mkprm($vr).'&token='.$k;
$day=date('ymdH'); $d=$_SESSION['d'.$day][$u]??'';
if(!$d){$d=read_try($u); if($d)$_SESSION['d'.$day][$u]=$d;}
return json_decode($d,true);}

#vars
static function varlib($k){
$r['city']=['insee','cp','latitude','longitude','altitude','name'];//'country',
$r['cities']=['insee','cp','latitude','longitude','altitude','name'];
$r['ephemeride']=['latitude','longitude','insee','day','datetime','sunrise','sunset','duration_day','diff_duration_day','moon_age','moon_phase'];		
$r['forecast']=['insee','cp','latitude','longitude','day','datetime','wind10m','gust10m','dirwind10m','rr10','rr1','probarain','weather','tmin','tmax','sun_hours','etp','probafrost','probafog','probawind70','probawind100','gustx'];//'tmin','tmax','sunHours','etp',
$r['observation']=['station','time','outside_temperature','dewpoint','windchill','rainfall','barometer','solar_radiation','wind_speed','wind_direction','windgust_speed','outside_humidity','insolation_time'];					
//climatology
$r['station']=['name','uuid','latitude','longitude','elevation','city'];
$r['datas']=['name','longname','unit','resolution'];//inside variables
$r['variables']=['barometer_max','barometer_min','barometer','insolation_time','outside_humidity_min','outside_humidity_max','outside_humidity','outside_temperature_min','outside_temperature_max','outside_temperature','rainfall','solar_radiation','solar_radiation_max','wind_direction','wind_speed','windgust_speed_max'];
$r['climatology']=['time','barometer_max','barometer_min','barometer','insolation_time','outside_humidity_min','outside_humidity_max','outside_humidity','outside_temperature_min','outside_temperature_max','outside_temperature','rainfall','solar_radiation','solar_radiation_max','wind_direction','wind_speed','windgust_speed_max'];				
return $r[$k]??[];}

static function insee(){
return ['92012'=>'Boulogne-Billancourt','75101'=>'Paris','35238'=>'Rennes','59350'=>'Lille','67482'=>'Strasbourg','69381'=>'Lyon','06088'=>'Nice','13210'=>'Marseille','34172'=>'Montpellier','66136'=>'Perpignan','31555'=>'Toulouse','64102'=>'Bayonne','33063'=>'Bordeaux','29019'=>'Brest','2B033'=>'Bastia','44109'=>'Nantes','63113'=>'Clermont-Ferrand','73296'=>'Tignes'];}

#render
static function render($m,$r){$ret=[]; //pr($r);
if($m=='forecast')$rw=self::codes();
//$ra=self::varlib($m);
if(isset($r[$m][0][0]))$ra=array_keys($r[$m][0][0]);
elseif(isset($r[$m][0]))$ra=array_keys($r[$m][0]);
elseif(isset($r[$m]))$ra=array_keys($r[$m]);
else $ra=array_keys($r); //pr($ra);
$rk=array_flip($ra);//good order
foreach($r[$m] as $k=>$v){
	if(is_array($v)){foreach($v as $ka=>$va){
		if(is_array($va)){foreach($va as $kb=>$vb){
			$kc=$rk[$kb]; $ret[$k.$ka][$kb]=$vb;}}
		else{$kb=$rk[$ka]; $ret[$k][$ka]=$va;}}}// if($ka=='weather')$va=$rw[$va];
	else{$ret[1][$k]=$v;}} //pr($ret);
return [$ret,$ra];}

static function render_observation($m,$r){$ret=[];//pr($r);
$ra=array_keys($r[0][$m]); array_unshift($ra,'station'); $rk=array_flip($ra);//good order
if($r)foreach($r as $k=>$v){//pr($v->station);
	foreach($ra as $ka=>$va)$ret[$k][$va]='';//init empty cases
	$ret[$k][0]=$v['station']['name'].' ('.$v['station']['city'].', alt. '.$v['station']['elevation'].'m)';
	//pr($v[$m]);
	foreach($v[$m] as $ka=>$va){$kb=$rk[$ka];//case nb
		if(is_array($va))$ret[$k][$ka]=$va['value'].' '.$va['unit'];
		else $ret[$k][$ka]=$va;}}
return [$ret,$ra];}

//climatology
static function climatology($vr){$n=0;//0->13
$nb=1;//Nuit=0; Matin=1; Apr�s-midi=2; Soir=3
$mode='climatology/day/station';//&date=2019-03-04
//$mode='climatology/day/around';//&radius=50
//$mode='climatology/day/bbox';//&north=48.3&south=47.5&west=-1.8&east=-1.6
//$mode='climatology/month/station';//&date=2019-03
//$mode='climatology/month/around';//&radius=10
//$mode='climatology/month/bbox';//&north=48.2&south=48.1&west=-1.7&east=-1.6
$r=self::apicall($vr,$mode);
return self::render('climatology',$r);}

//observaations
static function observations($vr){$n=0;//0->13
//$vr['insee']='92012'; //$vr['radius']='50';
//$mode='observations/station';//&date=2018-01-04T00:00
$mode='observations/around';//&radius=50 //access basic
//$mode='observations/bbox';//&north=48.2&south=48.1&west=-1.7&east=-1.6
$r=self::apicall($vr,$mode);
return self::render_observation('observation',$r);}

//forecast
static function forecast($vr){
//$vr['insee']='92012';
$n=$vr['day']??0;//0->13
$nb=$vr['period']??1;//Nuit=0; Matin=1; Apr�s-midi=2; Soir=3
if(isset($vr['nexthours']))$q=5; 
elseif(isset($vr['day']) && isset($vr['period']))$q=4;
elseif(isset($vr['period']))$q=3;
elseif(isset($vr['day']))$q=2;else $q=1;
if($q==1)$mode='forecast/daily';//14 days//access basic
if($q==2)$mode='forecast/daily/'.$n;//0->13//access basic
if($q==3)$mode='forecast/daily/periods';//14 days//access basic
#$mode='forecast/daily/'.$n.'/periods';//access basic
if($q==4)$mode='forecast/daily/'.$n.'/period/'.($nb);//access basic
if($q==5)$mode='forecast/nextHours';//access basic
//$mode='forecast/daily/'.$n.'/hours';
//$mode='forecast/daily/'.$n.'/map';
//$mode='forecast/daily/'.$n.'/bbox';//&north=49&south=48.6&west=-2&east=-1.6
$r=self::apicall($vr,$mode);
return self::render('forecast',$r);}

//ephemeride
static function ephemeride($vr){
//$vr['insee']='92012';
$n=$vr['day']??0;//0->13
$mode='ephemeride/'.($n??0);//access basic
$r=self::apicall($vr,$mode);
return self::render('ephemeride',$r);}

//cities
static function cities($vr){
//$vr['search']='92100';//Boulogne
$mode='location/cities';//access basic
$r=self::apicall($vr,$mode);
return self::render('cities',$r);}

//city
static function city($vr){
//$vr['insee']='92012';
//$vr['latlng']='48.086,-2.635';
$mode='location/city';//access basic
$r=self::apicall($vr,$mode);
return self::render('city',$r);}

#com
static function codes(){
$r=db::lang('weather'); $rb=[];
foreach($r as $k=>$v)$rb[$v[0]]=$v[1];
return $rb;}

static function icotyp($n){//$nm='Soleil'; $ic='day-sunny';
$r=db::read('db/public/weather-icons.php'); $ret='';
foreach($r as $k=>$v)if($v[0]==(string)$n)return $v[2];}

static function icosvg($n){//$nm='Soleil'; $ic='day';
$r=db::read('db/public/weather-svg.php');
foreach($r as $k=>$v)if($v[0]==(string)$n)return $v[2];}

static function icoascii($n){
//return ['sunshine'=>9728,'smallclouds'=>127780,' localclouds'=>127781,'heavyclouds'=>9729,'localrain'=>127782,'rain'=>127783,'stormyrain'=>9928,'storm'=>127785,'snowrain'=>127784,'snow'=>10052,'bigsnow'=>9731];
$r=db::read('db/public/weather-ascii.php');
foreach($r as $k=>$v)if($v[0]==(string)$n)return '&#'.$v[2].';';}

static function good_period(){$h=date('H'); 
if($h<4)$d=0; elseif($h<10)$d=1; elseif($h<16)$d=2; elseif($h<22)$d=3; else $d=0;
return $d;}

//com
static function com_observation($r){//pr($r);
$rb['station']=$r[0]['station']['city']??''; $ob=$r[0]['observation']??[];
$ra=['temperature','barometer','rainfall','solar_radiation','wind_speed','windchill','windgust_speed','outside_humidity'];
foreach($ra as $k=>$v)$rb[$v]=[$ob[$v]['value']??'',$ob[$v]['unit']??''];
return $rb;}

static function com_forecast($r,$o=''){//pr($r);
$rb['town']=$r['city']['name']??'';
$rb['update']=$r['update']??'';
$r=$r['forecast']??''; if(array_key_exists(0,$r))$r=$r[0]; //pr($r);
$ra=['datetime','rr10','rr1','weather','temp2m','wind10m','gust10m','dirwind10m','probarain','probafog','probafrost','probawind70','probawind100','gustx','iso0'];//rr10:cumul pluie,rr1:max,gustx:rafales,iso0:altitude du 0�
foreach($ra as $k=>$v)$rb[$v.$o]=$r[$v]??'';
return $rb;}

static function com_ephemeride($r){
$r=$r['ephemeride']; //pr($r);
$rb['sunrise']=$r['sunrise'];
$rb['sunset']=$r['sunset'];
$rb['diffday']=$r['diff_duration_day'];
$rb['moon_age']=$r['moon_age'];
$rb['moon_phase']=$r['moon_phase'];
return $rb;}

static function com_render($r){
$tmp=$r['temp2m']??''; if(!$tmp)$tmp=$r['temperature'][0]??''; if(!$tmp)return;
$n=$r['weather']; $rw=self::codes(); $nm=$rw[$n?$n:0]??''; //pr($r);
//$ic=self::icotyp($n); $ret=span($nm,'wi-'.$ic);
/*if(strpos($nm,'orage')!==false)$ic='flash';
elseif(strpos($nm,'neige')!==false)$ic='snowflake-o';
elseif(strpos($nm,'pluie')!==false)$ic='umbrella';//shower
elseif(strpos($nm,'nuage')!==false)$ic='cloud'; else $ic='sun-o';
$ret=icoxt($ic,$nm).' ';*/
$ic=self::icoascii($n);
$ret=$r['town'];
$ret.=span($ic,'','','font-size:xx-large;').' '.$nm.' ';
if($tmp<0)$ic=ico('thermometer-0'); elseif($tmp<10)$ic=ico('thermometer-1');
elseif($tmp<20)$ic=ico('thermometer-2'); elseif($tmp<30)$ic=ico('thermometer-3');
else $ic=ico('thermometer-4');
$ret.=$ic.$tmp.' '.$r['temperature'][1].' ';
$ret.=ico('dashboard').$r['barometer'][0].' '.$r['barometer'][1].' ';
//[$n,$nm,$ic]=self::icosvg($n); $ret=img('/usr/_/weather/'.$ic.'.svg').span($nm,'bold');
$ret.=ico('angle-up').$r['sunrise'].' '.ico('angle-down').$r['sunset'];
return $ret;}

static function daytemp($insee,$date,$n=1){$rb=[];
//$insee='75101'; //$date='2021-11-25';
$rd=[$date,date('Y-m-d',nbday($date,$n))];
$r=sql('day,res','meteo','kv',['up'=>$rd,'insee'=>$insee,'_order'=>'up'],0); //pr($r);
foreach($r as $k=>$v){$ra=json_decode($v,true); $hr=substr($k,6); if($hr==0)$hr=24; ksort($rb);
if($ra['temperature'][0])$rb[$hr]=$ra['temperature'][0];} //pr($rb);
return graphs::com($rb,0,1,1,$insee.': '.$date);}

//weather::status(['insee'=>'92012']);
static function status($p){$rb=[];
$vr['insee']=$p['insee']??'92012';
$n=$p['qday']??0; $nb=$p['period']??0;
$vr['radius']=$p['radius']??10; 
$day=date('ymdH'); //$_SESSION['d'.$day]=[]; 
//$vr['period']=$p['period']??self::good_period();
$r=self::apicall($vr,'forecast/daily/'.$n.'/period/'.$nb); //pr($r);
//$r=self::apicall($vr,'forecast/nextHours'); //pr($r);
if(!$r)return;
$rb=self::com_forecast($r); //pr($rb);
$r=self::apicall($vr,'forecast/daily/1/period/'.$nb); $rb+=self::com_forecast($r,'_1'); //pr($rb);
$r=self::apicall($vr,'observations/around'); $rb+=self::com_observation($r);
$r=self::apicall($vr,'ephemeride/0'); $rb+=self::com_ephemeride($r); //pr($rb);
return $rb;}

//build
static function build($p){$id=$p['id']??'';
$cols=implode(',',self::$cols); //$cols=sql::cols($db,2);
return sql('uid,'.$cols.',up',self::$db,'ra',$id);}

static function backup($p){$ret=''; $in=$p['insee']??92012;
$r=sql('res',self::$db,'rv',['insee'=>$in]);
return $ret;}

#call
static function call($p){
$r=self::build($p); if(!$r)return;
if(is_numeric($r['com']))$vr['insee']=$r['com'];
else $vr=explode_k($r['com'],'|',':');
$va=$r['mode']??'status';
[$rb,$ra]=self::$va($vr); pr($rb);
if($va=='status')return self::com_render($rb);
else return tabler($rb,$ra);}

//return meteo::com(['insee'=>'75101']);
static function com($p){
[$re,$ve]=expl('-',$p['opt']??'',2); if($re=='0')$p['x']=1; if($ve)$p['verbose']=1;
//foreach(self::$towns as $k=>$v)self::refresh(['insee'=>$k]);//,'x'=>$x
$res=self::refresh($p); if(!$res)return;
$r=json_decode($res,true); //pr($r);
if(!$r)return 'nothing';
$ret=self::com_render($r);
return $ret;}

static function refresh($p){
$uid=$p['uid']??ses('uid'); $insee=$p['insee']??''; if(!$insee)$insee=92012;//75101
$day=date('ymdH'); $x=$p['x']??''; $p['x']=''; $pe=self::good_period(); //$date=date('Y-m-d H:00:00');
$res=sql('res',self::$db,'v',['insee'=>$insee,'day'=>$day,'_limit'=>1],0);
if($res)$res=str_replace('u0','\u0',$res);
//db::add('usr/dav/meteo/snifer',[day(),ip(),$uid,$insee,$res],['time','ip','uid','insee','res']);
//return $res;
$ra=json_decode($res,true); $tw=$ra['town']??''; //p($res);
if(!$tw or $x){$vr['insee']=$insee; $vr['qday']=0; $vr['period']=$pe; $vr['radius']=$p['radius']??10;
	$rb=self::status($vr); $res=json_encode($rb,JSON_HEX_TAG); if($p['verbose']??'')eco($res);
	if($x){$id=sql('id',self::$db,'v',['insee'=>$insee,'day'=>$day]); if($id && $rb)sql::up(self::$db,'res',$res,$id,'',0);}
	elseif($rb)sql::sav(self::$db,[$uid,$insee,$day,prm($p),$res],0);}
return $res;}

//api/meteo/insee:75101
static function api($p){$x=$p['x']??'';
//foreach(self::$towns as $k=>$v)self::refresh(['insee'=>$k]);//,'x'=>$x
return self::refresh($p);}

static function content($p){
//self::install();
$j=self::$cb.'|'.self::$a.',com||insee,opt';
$bt=inputcall($j,'insee',$p['p1']??'',32);
$bt=datalistcall('insee',array_keys(self::$towns),$p['p1']??'',$j,'insee');
$bt.=bj($j,langp('ok'),'btn');
$bt.=checkbox('opt',['refresh','verbose'],'');
return $bt.div('','pane',self::$cb);}
}
?>