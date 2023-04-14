<?php
class weather2 extends appx{
static $private=0;
static $a=__CLASS__;
static $db='weather2';
static $db2='weather2_r';
static $cb='wth2';
static $cols=['tit','mode','com'];
static $typs=['var','svar','var'];
static $conn=0;
static $gen=0;
static $tags=0;
static $open=1;
static $qb='db';

function __construct(){//informe parent
$r=['a','db','db2','cb','cols','conn','qb'];//'db2',
foreach($r as $v)parent::$$v=self::$$v;}

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
static function fc_mode($k,$v){
$r=['city','cities','ephemeride','forecast','observations'];
return datalist($k,$r,$v,34,'mode').hlpbt('weather2_options');}

static function form($p){
//$p['html']='txt';
$p['fcmode']=1;
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
static function build($p){$id=$p['id'];
$cols=implode(',',self::$cols); //$cols=sql::cols($db,1,0);
return sql('uid,'.$cols.',up',self::$db,'ra',$id);
return parent::build($p);}

#play
static function play($p){
$r=self::build($p); $id=$p['id']; $rb=[]; $ra=[]; $x=$p['x']??''; $bt='';
if(is_numeric($r['com']))$vr['insee']=$r['com']; else $vr=explode_k($r['com'],'|',':');
$va=$r['mode']??'status'; $prm=$r['mode'].'|'.$r['com'];
$res=sql('res',self::$db2,'v',['bid'=>$id,'prm'=>$prm],0);  $res=str_replace('u0','\u0',$res);
if($res)$rb=json_decode($res,true); //pr($rb);
if(!$rb or $x){[$rb,$ra]=meteo::$va($vr); $rs=json_encode($rb); //pr($rb);//eco($rs);
	$time=$rb['time'][0]??($rb['time']??''); if(!$time)$time=$r['up'];
	if($x){$id=sql('id',self::$db2,'v',['bid'=>$id]); sql::up2(self::$db2,['res'=>$rs],$id,0);} else
	sql::sav(self::$db2,[$id,strtotime($time),$prm,$rs],0);
	$f=explorer::nod('weather2',$id); db::save($f,$rb);}
if(self::own($id))$bt=bj(self::$cb.$id.'|weather2,play|id='.$id.',x=1',pic('refresh'),'');
if(is_array($rb)){
	if(is_array(current($rb))){$rbb=current($rb);
		if(is_array(current($rbb)))$ra=array_keys(current($rbb));
		else $ra=array_keys($rbb);}
	else $ra=array_keys($rb);} //pr($ra);
return tabler($rb,$ra).$bt;}

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
self::install();
return parent::content($p);}

//http://logic.ovh/api/weather/id:1
static function api($p){$id=$p['id']??'';
return sql('res',self::$db2,'v',['bid'=>$id]);}
}
?>