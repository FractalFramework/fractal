<?php

class twpy{	
static $private=2;
static $db='twpy';
static $cols=['tit','txt'];
static $typs=['var','text'];
static $a='twpy';
static $cb='mdb';

static function install(){
sql::create(self::$db,[array_combine(self::$cols,self::$typs)],0);}

static function admin(){
$r[]=['','j','popup|twpy,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=twpy_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=twpy','code','Code'];
return $r;}

static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function titles($p){
$d=$p['_m']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p){
$ret=lang('result').': '.val($p,'v1').' '.val($p,'inp1');
return $ret;}

#stream
static function stream($p){
$ret=bj('','','btn');
return div($ret,'');}

#call
static function call($p){
$twid=val($p,'twid'); $o=val($p,'mode'); $ret=''; $id=''; $bt='';
$bt=$d='python /home/tlex/usr/python/'.$o.'.py '.$twid;
$ret=exe($d); p($ret);
return $bt.div($ret,'','cblk');}

static function com($p){
$j=self::$cb.'|twpy,play|v1=hello|inp1';
$bt=bj($j,langp('send'),'btn');
return inputcall($j,'inp1',$p['p1']??'',32).$bt;}

#content
static function content($p){
//self::install();
$twid=$p['p1']??''; $o=$p['p2']??''; $ret='';
$bt=input('twid',$twid,20,lang('twid'));
$bt.=span(radio('mode',['answers','retweets','likes'],$o,1,1),'btn');
$bt.=bj(self::$cb.',,z|twpy,call||twid,mode',langp('ok'),'btn');
$ret=self::call(['twid'=>$twid,'mode'=>$o]);
return $bt.div($ret,'pane',self::$cb);}
}
?>