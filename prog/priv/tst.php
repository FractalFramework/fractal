<?php

class tst{	
static $private=0;
static $db='_model';
static $a='tst';
static $cb='mnt';

static function install(){
sql::create(self::$db,['tit'=>'var','txt'=>'bvar'],0);}

static function admin(){
$r[]=['','j','popup|tst,content','plus',lang('open')];
return $r;}

static function js(){return '';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function titles($p){
$d=$p['_m']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#play
static function play($p){
$ret=lang('result').': '.val($p,'v1').' '.val($p,'inp1');
return $ret;}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'ra',$id);
return $r;}

static function op($p){$ret=''; $u=val($p,'inp1');
//$d=sql('txt','multilang','v',150); sql::upd('book_chap',['txt'=>$d],28);
$ret=btj('ok','Mercury.parse('.$u.').then(result => console.log(result));','btn');
return $ret;}

static function json($p){
$r=['hello1','hello2','hello3','hello4'];
return json_encode($r);}

static function spl($p){
spl_autoload_extensions('.php'); 
spl_autoload_register();
}

static function splfile($p){
$q=new SplFileInfo('/tmp/foo.txt');
if($q->isWritable()){$q=$fileinfo->openFile('a');
	$q->fwrite("appended this sample text");}
}

static function ns($p){
require('prog/admin/admin_ascii.php');
//use admin;
return admin\admin_ascii::content($p);
}

static function ts($p){//pr($p);
//setlocale(LC_ALL,'US_us');
$ret=gmmktime(0,0,0,1,1,1970);
return $ret;}

#call
static function call($p){$ret='';
$call='ts';//build/play/op/json
$ret=self::$call($p);
return $ret;}

static function com(){
return self::content($p);}


#content
static function content($p){//pr($p);
//self::install();
$p['p1']=$p['p1']??'';
$bt=input('inp1','value1','','1');
//$bt.=bj(self::$cb.'|tst,call|v1=hello|inp1',lang('send'),'btn');
$bt.=bj('cb1;cb2;cb3;cb4|tst,call|v1=hello|inp1',lang('send'),'btn');
$bt.=bjk(self::$cb.'|tst,call|p1=hello','ok','btn','tst/hello');
//$bt.=bjk(self::$cb,'tst','hello','ok','btn');
$ret=self::call($p);
$cbj=div('','','cb1').div('','','cb2').div('','','cb3').div('','','cb4');
return $bt.div($ret,'pane',self::$cb).$cbj;}
}
?>