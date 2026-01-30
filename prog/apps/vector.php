<?php

class vector extends appx{
static $private=0;
static $a='vector';
static $db='vector';
static $cb='vct';
static $cols=['tit','code','pub'];
static $typs=['var','text','int'];
static $tags=1;
static $open=1;
//static $svg=1;

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
static function el($k,$v,$rid){//called bu build::iterbt
return insert('['.implode(',',$v).':'.$k.']',$rid);}

static function menu($p){$r=svg::motor();
$ra=['attr','stroke','shapes'=>['rect','circle','ellipse','line','polyline','polygon','path','arc'],'btn'=>['group','text','tspan','a','bj','js'],'defs'=>['filter'=>['feBlend','feOffset','feGaussianBlur','feColorMatrix'],'animate'],'linearGradient'=>['linearGradient','stop'],'specials'=>['dim','grid','setvar','var','g','gp','verbose']];
return build::iterbt($p,$ra,$r,'functions','vector');}

static function svgbt($rid,$id){$ret=''; $r=svg::motor(); $t='vector'.$id; //sql('tit',self::$db,'v',$id);
$ret=div(menu::call(['app'=>'vector','mth'=>'menu','bid'=>'code','drop'=>0]));
//foreach($r as $k=>$v)$ret.=btj($k,insert('['.implode(',',$v).':'.$k.']',$rid),'btn').' ';
$r=['dim'=>'600,400','grid'=>20,'attr'=>',white,2','stroke'=>'black,10,0.5,round,2/4,arcs/miter/round','setvar'=>'v1*40','var'=>'v1','g'=>1,'gp'=>'1/1 2/2','verbose'=>1];
$ret.=build::cbt($rid,$r);
//$ret.=build::sample(['a'=>'svg','b'=>$rid]);
$ret.=bj('popup|svg',lang('examples'),'btsav');
$ret.=bj('codcb|svg,save|t='.$t.'|code',lang('save file'),'btsav');
return div($ret);}

static function fc_code($k,$v,$ty,$id){
$ret=self::svgbt($k,$id);
$js='codcb|svg,call|fit=1,t=vector'.$id.'|code';
$ret.=textareact($k,$v,64,12,$js);
$ret.=div('','','codcb');
return $ret;}

static function form($p){
$p['fccode']=1;
return parent::form($p);}

static function edit($p){
$p['help']='vector_edit';
return parent::edit($p);}

static function create($p){
return parent::create($p);}

#build
static function build($p){
return parent::build($p);}

static function draw($p,$r){
$rp=['code'=>$r['code'],'fit'=>1,'t'=>'vector'.$p['id']];
svg::save($rp);
return svg::call($rp);}

static function cache($p){
$f='img/svg/vector'.$p['id'].'.svg';
if(file_exists($f))return $f;}

static function play($p,$x=''){
$f=self::cache($p); if($f && !$x)return read_file($f);//img('/'.$f.'?'.randid());
$r=self::build($p);
return self::draw($p,$r);}

static function cover($id,$r=[]){$f=self::cache(['id'=>$id]); $s=48;
[$w,$h]=svgdim($f,$s,$s); [$wo,$ho]=scale($w,$h,$s,$s,0); //$d=read_file($f);
return img('/'.$f,$wo,$ho);
return span(img('/'.$f,$wo,$ho).span($r['tit']),'');}

static function stream($p){$p['cover']=1;
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
//self::install();
return parent::content($p);}
}
?>