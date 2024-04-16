<?php
class weather extends appx{
static $private=0;
static $a=__CLASS__;
static $db='weather';
static $db2='weather_r';
static $cb='wth';
static $cols=['town','insee','hid'];
static $typs=['var','int','int'];
static $conn=0;
static $gen=0;
static $tags=0;
static $open=1;
static $qb='';//db

static function install($p=''){
sql::create(self::$db2,['bid'=>'int','day'=>'int','prm'=>'var','res'=>'text'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csslink','/css/weather.css');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){
//$p['db2']=self::$db2;
return parent::del($p);}

static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subform($r){return parent::subform($r);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}

//form
static function ret($p){//p($p);
$vr['search']=$p['town']??'92100';
$r=meteo::apicall($vr,'location/cities');
return $r['cities'][0]['insee']??'';}

static function search($p){$ret='';
$vr['search']=$p['tit']??'92100';//Boulogne
$r=meteo::apicall($vr,'location/cities');//pr($r);
//[$rb,$ra]=meteo::render('cities',$r); pr($rb);
if($r['cities']??'')foreach($r['cities'] as $k=>$v)$ret.=tag('option',['value'=>$v['name']],'',1);
//,'label'=>$v['insee']
return $ret;}

static function fc_town($k,$v){
//$ret=datalistj($k,$v,'insee|weather,ret||'.$k,'weather,search|'.$k,lang('town'),46);
$ret=input($k,$v,36);
$ret.=bj('insee|weather,ret||'.$k,langp('search'),'btn');
return $ret;}

static function fc_hid($k,$v){
return hidden('hid',$v?$v:time());}

static function form($p){
//$p['html']='txt';
$p['fctown']=1;
$p['fchid']=1;
//$p['bttxt']=1;
//$p['barfunc']='barlabel';
//$p['labeltit']='title';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
//$p['help']=1;
//$p['sub']=1;
//$p['execcode']=1;
return parent::edit($p);}

#build
static function build($p){$id=$p['id']??'';
//$cols=implode(',',self::$cols); //$cols=sql::cols($db,1,0);
//return sql('uid,'.$cols.',timeup',self::$db,'ra',$id);
return parent::build($p);}

#play
static function play($p){
$r=self::build($p); $id=$p['id']; $rb=[];//p($r);
$vr['insee']=$r['insee']; $va=$r['mode']??'status'; $x=$p['x']??'';
$bt=span($r['town'],'bold').' '; $prm=['bid'=>$id,'prm'=>$r['insee']];
$res=sql('res',self::$db2,'v',$prm); $res=str_replace('u0','\u0',$res);
if($res)$rb=json_decode($res,true); //pr($rb);
if(isset($rb['update']))$bt.=span('('.day('d/m/Y-H:i',strtotime($rb['update'])).')','small').' ';
if(!$rb or $x){$rb=meteo::$va($vr); $res=json_encode($rb); //pr($rb);//list($rb,$ra)??
	if($x){$ex=sql('id',self::$db2,'v',$prm); sql::up2(self::$db2,['res'=>$res],$ex,0);}
	else{$ex=[$id,date('ymdH'),$r['insee'],$res];
		if(!$ex)sql::sav(self::$db2,[$id,date('ymdH'),$r['insee'],$res],0);}}
$ret=meteo::com_render($rb);
if(self::own($id))$ret.=' '.bj(self::$cb.$id.'|weather,play|id='.$id.',x=1',pic('refresh'),'');
return $bt.div($ret,'scroll');}

static function stream($p){
//$p['t']=self::$cols[0];
return parent::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){return parent::com($p);}
static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
//self::install();
return parent::content($p);}

//http://logic.ovh/api/weather/id:1
static function api($p){$id=$p['id']??'';
return sql('res',self::$db2,'v',['bid'=>$id]);}
}
?>