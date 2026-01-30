<?php

class oay{	
static $private=6;
static $db='oay';
static $a='oay';
static $cols=['date','txt'];
static $typs=['date','bvar'];

static function install($p=''){
appx::install(array_combine(self::$cols,self::$typs));}

static function js(){return '
function batchtime(){ajx("div,gaia|oay,call|"); setTimeout("batchtime()",7000);}
//setTimeout("batchtime()",10);
';}

static function headers(){
head::add('jscode',self::js());}

#build
/*static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'ra',$id);
return $r;}*/

#read
static function call($p){
$twid=twitter::init();
$usr=$p['usr']??'';
$t=new twit($twid);
$q=$t->timeline($usr,10,'',0); //eco($q);
//if(array_key_exists('errors',$q))$er=$q['errors'][0]['message'];
if(array_key_exists('error',$q))$er=$q['error'];
if(isset($er))return div(helpx('error').' : '.$er,'alert');
if($q)foreach($q as $k=>$v){$r[]=twitter::datas($v);}
$ret=tabler($r);
return $ret;}

static function com(){
return self::content($p);}

#content
static function content($p){
//self::install();
$p['inp1']=$p['p1']??'';
if($p['inp1'])$ret=self::call($p);
$bt=bj('gaia|oay,call|usr=oyagaa_ayuyisaa|inp1','oay','btn');
$bt.=bj('gaia|oay,call|usr=oomo_toa|inp1','ot','btn');
return $bt.div('','pane','gaia');}
}
?>