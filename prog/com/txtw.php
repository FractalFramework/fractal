<?php

//application not based on appx
class txtw{	
static $private=2;
static $a=__CLASS__;
static $db='txtw';
static $cols=['tit','txt'];
static $typs=['var','text'];
static $cb='mdb';

static function install(){
sql::create(self::$db,[array_combine(self::$cols,self::$typs)],0);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
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

#play
static function build($d){
$m=180; $n=strlen($d); $a=floor($n/$m);
$na=0; $s="&rArr;"; $r=[];
for($i=0;$i<$a;$i++){$b='';
	//$na=$i*180; $nb=$na+180;
	$v=substr($d,$na,180);
	$nb=strrpos($v,"\n"); //echo $nb.br();
	if(!$nb)$nb=strrpos($v,'. ');
	if(!$nb){$nb=strrpos(substr($d,$na,178),' '); $b=' '.$s;}
	$t=trim(substr($d,$na,$nb)).$b;
	$r[]=$t;//.strlen($t)
	$na+=$nb;}
return $r;}

static function play($p){
$d=$p['txt']??''; $ret='';
$r=self::build($d);
foreach($r as $k=>$v)$ret.=div($v,'pane');
return $ret;}

static function call($p){
return self::play($p);}

static function com($d){
return self::play(['txt'=>$d]);}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$j=self::$cb.'|'.self::$a.',call||txt';
$bt=bj($j,langp('send'),'btn');
$bt.=div(textarea('txt',$p['p1'],64,20));
$ret='';//self::com($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>