<?php

class bitmap extends appx{
static $private=0;
static $a='bitmap';
static $db='bitmap';
static $cb='vct';
static $cols=['tit','code','pub'];
static $typs=['var','text','int'];
static $tags=1;
static $open=1;
//static $svg=1;

function __construct(){
$r=['a','db','cb','cols'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}
static function js(){return '';}
static function headers(){}

#edit
static function collect($p){
return parent::collect($p);}

static function del($p){
return parent::del($p);}

static function save($p){
return parent::save($p);}

static function modif($p){
$ret=parent::modif($p); self::play($p,1);
return $ret;}

//menubt
static function el($k,$v,$rid){return insert('['.implode(',',$v).':'.$k.']',$rid);}
static function menu($p){$r=[];
$ra=['frequents'=>['dim','beginPath','moveTo','lineTo','fill','stroke','clearRect'],'shapes'=>['fillRect','strokeRect','closePath','rect','arc','strokeStyle','fillStyle','lineWidth','lineCap','lineJoin'],'operations'=>['translate','rotate','scale','save','restore','pi','tau'],'complex'=>['miterLength','setLineDash','lineDashOffset','createLinearGradient','createRadialGradient','createLinearGradient','addColorStop']];
return build::iterbt($p,$ra,$r,'functions','bitmap');}

static function bmpbt($rid,$id){$ret=''; $r=svg::motor(); $t=$id; //sql('tit',self::$db,'v',$id);
$ret=div(menu::call(['app'=>'bitmap','mth'=>'menu','bid'=>'code','drop'=>0]));
//$ret.=bj('popup|svg',lang('examples'),'btsav');
//$ret.=bj('codcb|svg,save|t='.$t.'|code',lang('save file'),'btsav');
return div($ret);}

static function fc_code($k,$v,$ty,$id){
$ret=self::bmpbt($k,$id);
//$ret=div(menu::call(['app'=>'bitmap','mth'=>'menu','bid'=>'code']));
$js='codcb|canvas,call|opt=0,t='.$id.'|code';
$ret.=textareact($k,$v,64,12,$js);
$ret.=div('','','codcb');
return $ret;}

static function form($p){
$p['fccode']=1;
return parent::form($p);}

static function edit($p){
$p['help']='bitmap_edit';
return parent::edit($p);}

static function create($p){
return parent::create($p);}

#build
static function build($p){
return parent::build($p);}

static function draw($p,$r){
$d=val($p,'code'); $o=$p['opt']??''; $t=$p['id'];
$ret=canvas::call(['code'=>$d,'opt'=>$o,'t'=>$t]);
return $ret;}

static function cache($p){
$f='/img/canvas/'.$p['id'].'.html';
if(file_exists($f))return $f;}

static function play($p,$x=''){
$f=self::cache($p); if($f && !$x)return iframe($f);
$r=self::build($p);
return self::draw($p,$r);}

static function stream($p){
return parent::stream($p);}

#call (read)
static function tit($p){
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){
return parent::com($p);}

#interface
static function content($p){
self::install();
return parent::content($p);}
}
?>