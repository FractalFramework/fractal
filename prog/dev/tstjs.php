<?php

class tstjs{	
static $private=0;
static $db='tstjs';
static $a='tstjs';
static $cb='cnj';

/*static function install(){
sql::create(self::$db,['tit'=>'var','txt'=>'bvar'],0);}*/

static function admin(){
$r[]=['','j','popup|tstjs,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=tstjs_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=tstjs','code','Code'];
return $r;}

static function js(){return '
function execode(id){
	var d=getbyid(id).value; //alert(d);
	d+=" getbyid(\'cnjtst\').innerHTML=ret;";
	getbyid("cnjs").innerHTML=d;
	eval(d);
}
';}
static function headers(){
head::add('csscode','textarea{width:auto;}');
head::add('code','<script type="text/javascript" id="cnjs"></script>');
head::add('jscode',self::js());}

#build
/*static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'ra',$id);
return $r;}*/

#read
static function call($p){
return $p['inp1'];}

static function com($p){
$ret=textarea('inp1','','60','30','','console');
//$bt=bj(self::$cb.',,1|tstjs,call||inp1',langp('send'),'btn');
$bt=btj(langp('send'),atj('execode','inp1'),'btn').br();
//$ret=inputcall($j,'inp1',$p['p1']??'',32).$bt;
return div($bt.$ret);}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$ret=div('','right','cnjtst','width:400px; border:1px dashed gray; padding:9px;');
$ret.=self::com($p);
return div($ret,'pane');}
}
?>