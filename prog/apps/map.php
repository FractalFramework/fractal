<?php

class map extends appx{
static $private=0;
static $a='map';
static $db='map';
static $cb='map';
static $cols=['address','coords'];
static $typs=['var','var'];
static $tags=0;
static $open=1;

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1';
return parent::admin($p);}

#edit
static function form($p){$loc=val($p,'coords'); $ret=''; $pop='';
$bt=bj('gpsinp|map,search||address',langp('find',1),'btsav');
$bt.=btj(langp('use my location'),'geo2(\'coords\')','btsav').' ';
$ret=div(input('address',val($p,'address'),28,lang('address')).$bt);
if($loc)$pop=popup('map,play|coords='.$loc,ico('map'),'btn');;
$ret.=div(input('coords',$loc,28,lang('gps coords')).$pop);
return $ret.div('','','gpsinp');}

static function del($p){return parent::del($p);}
static function save($p){return parent::save($p);}
static function create($p){return parent::create($p);}
static function modif($p){return parent::modif($p);}
static function edit($p){return parent::edit($p);}

//gps (for tlex)
static function search($p){
$r=gps::search($p); $ret='';
if($r)foreach($r['features'] as $k=>$v){
	$city=mb_convert_encoding($v['properties']['city'],'UCS-2BE','UTF-8');
	$t=$city.' '.$v['properties']['postcode'];
	$loc=$v['geometry']['coordinates'][1].'/'.$v['geometry']['coordinates'][0];//lat,lon
	$slct=btj($t,atj('valfromval',['gps'.$k,'coords']),'btn').hidden('gps'.$k,$loc);
	$pop=popup('map,call|coords='.$loc,pic('gps'),'btn');
	$ret.=div($slct.$pop);}
return $ret;}

static function request($p){$r=self::search($p);
//$r=gps::api(['req'=>val($p,'address'),'mode'=>'search','limit'=>'1']);
$rb=$r['features'][0]['geometry']['coordinates']; $gps=$rb[1].'/'.$rb[0];
$ret=input('coords',$gps,28,lang('gps coords'));
$ret.=popup('map,call|coords='.$gps,pic('map'),'btn');
return $ret;}

static function build($p){$id=$p['id']??'';
if($id)return sql('address,coords',self::$db,'ra',$id);}

static function play($p){
$r=self::build($p);
$pw=val($p,'_pw'); $w=val($p,'w','600'); $h=val($p,'h','400');
if($pw){if($w>$pw){$w=$pw-50; $h=$pw*1.4;} else{$w=$pw-140; $h=$pw*0.5;}}
[$lat,$lon]=explode('/',val($r,'coords','0/0'));
$f='http://cartosm.eu/map?lon='.$lon.'&lat='.$lat.'&zoom=14&max-width='.$w.'&height='.$h.'&mark=true&nav=true&pan=true&zb=inout&style=default&icon=down';
return iframe($f,'100%',$h.'px');}

static function stream($p){
return parent::stream($p);}

static function tit($p){
return parent::tit($p);}

//call (read)
static function call($p){
return parent::call($p);}

//com (write)
static function com($p){
return parent::com($p);}

//interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>
