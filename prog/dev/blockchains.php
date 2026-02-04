<?php

class blockchains{
static $private=4;
static $a=__CLASS__;

static function admin(){return admin::app(['a'=>self::$a]);}

static function js(){
return '
function connread(){
	ajx("div,blkc,2|conn,z|app=blockchains,mth=reader|msg");}';}
static function headers(){
head::add('jscode',self::js());}

//editor
static function edit($id){
$ret=btj('[]','embed_slct(\'[\',\']\',\''.$id.'\')','btn');
$r=['furniture','alimentation','building','work','resources'];
foreach($r as $k=>$v)
	$ret.=btj($v,'embed_slct(\'[\',\':'.$v.']\',\''.$id.'\')','btn');
return $ret;}

//blocks
static function furniture($p,$o){return $p*$o+1;}
static function alimentation($p,$o){return $p*$o+($p*2);}
static function building($p,$o){return $p*$o+($p);}
static function work($p,$o){return $p*$o-($o/2);}
static function resources($p,$o){return $p*$o-($p/2);}

//secondary connectors
static function reader($d,$p=''){
	[$p,$o,$c]=readgen($d);
	if(method_exists('blockchains',$c))$ret=self::$c($p,$o?$o:1);
	//else $ret=conn::reader($d,$p);//default connectors
	//$_SESSION['bctot'][]=$ret;
return $ret;}

//sample
static function ex(){
	return '[1234*0:furniture]
[1234*1:alimentation]
[1234*2:building]
[1234*3:work]
[1234*4:resources]';}

//interface
static function content($p){
$p['rid']='blkc';
$p['inp']='bcmsg';
$p['msg']=val($p,'msg',self::ex()); 
$p['app']='blockchains'; $p['mth']='reader';//use local connectors
$ret=self::edit('msg');
$ret.=hlpbt('blockchains_app').br();
$ret.=tag('textarea',['id'=>'msg','cols'=>'100%','rows'=>'10','onkeyup'=>'connread()'],$p['msg']);
//param (app=blockchains,mth=reader) will use local connectors instead of default
$ret.=bj($p['rid'].'|conn,call|app=blockchains,mth=reader|msg',lang('convert'),'btn').br();
return $ret.div(conn::call($p),'',$p['rid']);}
}
?>