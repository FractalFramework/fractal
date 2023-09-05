<?php
class three extends appx{
static $private=0;
static $a='three';
static $db='three';
static $cb='mdl';
static $cols=['tit','code','codb','pub'];
static $typs=['var','text','text','int'];
static $tags=1;
static $open=0;
static $qb='db';

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'var'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}
static function js(){return ses('thrjs');}
static function headers(){
//html,body{overflow:hidden; width:100%; height:100%; padding:0; margin:0;}
//.canvas{width:100%; height:100%; touch-action:none;}
head::add('jscode','body{overflow: hidden;color:white;text-align:center;}
h1{position:absolute;width:100%;z-index:1;font-size:1.5rem;}
a{color:white;}
a:hover{color:purple;}
#scene-container{position:absolute;width:100%;height:100%;}');
head::add('jscode',self::js());}

#edit
static function collect($p){
return parent::collect($p);}

static function del($p){
//$p['db2']=self::$db2;
return parent::del($p);}

static function save($p){
return parent::save($p);}

static function modif($p){
return parent::modif($p);}

static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}
static function subform($r){
	$ret=hidden('bid',$r['bid']);
	//$ret.=div(input('chapter',$r['chapter'],63,lang('chapter'),'',512));
	return $ret;}

//static function fc_tit($k,$v){}
static function form($p){$a=self::$a;
$p['execcodb']=1;
return parent::form($p);}

static function edit($p){
//localStorage['m4']=res;
//$p['collect']=self::$db2;
$p['btcode']=btj(lang('cleanup',1),'repaircode(\'code\')','btn','');
$p['help']=1;
//$p['sub']=1;
return parent::edit($p);}

#build
static function build($p){
return parent::build($p);}

static function play($p){
$r=self::build($p); $rid=randid('thr');
head::add('csscode','#'.$rid.'{width:100%; height:100%; touch-action:none;}');
head::add('meta',['attr'=>'name','prop'=>'viewport','content'=>'width=device-width, initial-scale=1']);
//head::add('rel',['name'=>'icon','value'=>'https://discoverthreejs.com/favicon.ico']);
//head::add('jslink','https://threejs.org/build/three.js');
head::add('jslink','/prog/js/three.min.js');

/*
//import{Mesh,SpotLight,} from "./js/vendor/three.module.js";
//const mesh = new Mesh();
//const light = new SpotLight();*/
$js='
//import * as THREE from "/prog/js/three.module.js";
	'.$r['code'].'
	'.$r['codb'].'
';
/*
window.addEventListener("DOMContentLoaded",function(){
	var canvas = document.getElementById("'.$rid.'");
	var engine = new BABYLON.Engine(canvas, true);
	var createthree = function(){
	var three = new BABYLON.three(engine);
	'.$bab.'
	return three;}
	var three = createthree();
	engine.runRenderLoop(function(){three.render();});
	window.addEventListener("resize", function(){engine.resize();});
});*/
ses('thrjs',$js);
//head::add('jscode',$js);
$ret=div(tag('canvas',['id'=>$rid,'class'=>'canvas'],''),'','scene-container');
$ret.=head::jscode($js);
return $ret;}

static function stream($p){
//$p['t']=self::$cols[0];
return parent::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){post('pw',720);
//return self::play($p);
return iframe('/frame/three/'.$p['id']);}

static function iframe($p){
head::add('csscode','html,body{overflow:hidden; width:100%; height:100%; padding:0; margin:0;}
.canvas{width:100%; height:100%; touch-action: none;}');
return parent::iframe($p);}

#com (edit)
static function com($p){return parent::com($p);}
static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
//self::install();
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>