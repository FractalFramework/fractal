<?php

class opr{	
static $private=0;
static $db='_model';
static $a='opr';
static $cb='mnt';

static function install(){
sql::create(self::$db,['tit'=>'var','txt'=>'bvar'],0);}

static function admin(){
$r[]=['','j','popup|opr,content','plus',lang('open')];
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

#call
static function call($p){$ret='';
//$r=self::build($p);
//$ret=self::play($p);
$ret=self::op($p);
return $ret;}

static function com(){
return self::content($p);}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=input('inp1','value1','','1');
$bt.=bj(self::$cb.'|opr,call|v1=hello|inp1',lang('send'),'btn');
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>