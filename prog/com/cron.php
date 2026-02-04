<?php
class cron{
static $private=6;
static $a='cron';
static $db='model';
static $cb='mdl';
static $cols=['tit','txt','pub'];
static $typs=['var','bvar','int'];
static $conn=1;
static $gen=1;
static $db2='cron_vals';
static $open=0;
static $qb='db';

static function install($p=''){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);
sql::create(self::$db2,['bid'=>'int','tit2'=>'var','txt2'=>'var'],1);}

static function admin($p){}
static function js(){}
static function headers(){}

static function call($p){//actions
$r=sql('id','login','rv','');
//foreach($r as $k=>$v){bank::init($v); $rt[]=[$v,100];}
$rb=income::home(['datas'=>1]); //pr($r);
foreach($rb as $k=>$v)if(is_numeric($k)){$rt[]=[$k,$v];
	$ok=bank::transaction(['value'=>$v,'type'=>0,'at'=>$k,'label'=>'income','from'=>'sys','cnd'=>1]);}
return tabler($rt);}

#interface
static function content($p){
//self::install();
return div(bj(self::$cb.'|cron,call|',langp('run'),'btsav').hlpbt('cron_app'),'',self::$cb);}
}
?>