<?php

class twbot{
static $private=6;
static $a='twbot';
static $db='twbot';
static $cols=['twid','name','screen_name','date','text','media','reply_id','reply_name','favs','retweet','followers','friends','quote_id','quote_name','action'];
static $typs=['int','var','var','var','var','var','int','var','var','int','int','int','int','var','var'];

static function install($p=''){
appx::install(array_combine(self::$cols,self::$typs));}

static function admin(){
$r[]=['','j','popup|twbot,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=twbot_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=gaia','code','Code'];
return $r;}

static function js(){return '
var lapsetime=60000;
function batchtime(){
var iterations=100;
var n=getbyid("step").value; //alert(n);
var q=getbyid("req").value; //alert(n);
if(n<iterations)ajx("div,gaiaa|twbot,call|p1="+n+",inp1="+q);//
setTimeout("batchtime()",lapsetime);}
setTimeout("batchtime()",10);//on/off
';}

static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'ra',$id);
return $r;}

#read
static function call($p){
$req=$p['inp1']??'';//oyagaa_ayuyisaa
$max=$p['p1']??val($p,'step'); $ret=''; $id='';//echo $n.'-';
$t=new twit(twitter::init());
$q=$t->search($req,40,$max);
if(isset($q['statuses']))foreach($q['statuses'] as $k=>$v){
	if(!$id)$id=isset($v['id'])?$v['id']:'';
	$ret.=twitter::read($v);
}
$id=isset($v['id'])?$v['id']:'';
$bt=lk('/twbot/'.$id.'/'.$req,date('H:i:s'),'btsav').' ';
$bt.=hidden('req',$req);
$bt.=hidden('step',$id);
return $bt.$ret;}

static function com(){
return self::content($p);}

#content
static function content($p){
//self::install();
$n=$p['p1']??''; $q=$p['p2']??''; $bt=''; $ret='';
$bt=input('inp1',$q).bj('gaiaa|twbot,call||inp1,step',lang('send'),'btn');
$ret.=hidden('req',$q).hidden('step',$n);
if($n)$ret=self::call(['p'=>$n,'inp1'=>$q]);
return $bt.div($ret,'pane','gaiaa');}
}
?>