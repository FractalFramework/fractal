<?php
class randstat extends appx{
static $private=0;
static $a='randstat';
static $db='randstat';
static $cb='gst';
static $cols=['n1','n2','tmin','tmax','diff','percent'];
static $typs=['int','int','float','float','float','float'];
static $conn=1;
static $gen=1;
static $open=0;
static $qb='db';

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','t1'=>'var'],'1');//subcall case
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}

static function js(){return '';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){$p['pub']=0; return parent::create($p);}

#subcall
static function subops($p){$p['t']='tit2'; return parent::subops($p);}//$p['bt']='';
static function subform($r){return parent::subform($r);}
static function subedit($p){$p['t']='tit2'; return parent::subedit($p);}//$p['data-jb']
static function subcall($p){$p['t']='tit2'; return parent::subcall($p);}//$p['bt']='';

#form
static function form($p){return parent::form($p);}
static function edit($p){return parent::edit($p);}

#build
static function build($p){
return sql('used',self::$db,'rr','order by diff');}

static function template(){
return '[[(tit)*class=tit:div][[txt:var]*class=txt:div]*class=paneb:div]';}//will nl2br()

#play
static function play($p){
$r=self::build($p);
//$ret=gen::com(self::template(),$ra);
//$ret.=gen::com2(self::template2(),$r);
array_unshift($r,self::$cols);
foreach($r as $k=>$v)array_unshift($r[$k],$k==0?'':bj(self::$cb.'sub|'.self::$a.',calc|a='.$v['n1'].',b='.$v['n2'],$v['n1'].'-'.$v['n2'],'btn'));
$ret=tabler($r,1);
return $ret;}

#calc
static function re($a){
for($i=0;$i<$a;$i++)$r[]=rand(1,100);
$a=array_sum($r); $n=count($r); return $a/$n;}

static function calc($p){
$id=$p['id']??''; $a=val($p,'a',100); $b=val($p,'b',100);
for($i=0;$i<$b;$i++)$r[]=self::re($a);
$min=min($r); $max=max($r); $diff=$max-$min; $percent=$diff/100;
$ret=['uid'=>ses('uid'),'n1'=>$a,'n2'=>$b,'tmin'=>$min,'tmax'=>$max,'diff'=>$diff,'percent'=>$percent];
$rb=sql::savup(self::$db,$ret,['n1'=>$a,'n2'=>$b],0);
return self::play($p);}

static function stream($p){$bt='';
foreach([10,100,1000,10000] as $a)foreach([10,100,1000,10000] as $b)
$bt.=bj(self::$cb.'sub|'.self::$a.',calc|id='.$p['id'].',a='.$a.',b='.$b.'',''.$a.'-'.$b.'','btn').' ';
$ret=self::play($p);
return $bt.div($ret,'',self::$cb.'sub');}

#call
static function tit($p){
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com
static function com($p){return parent::com($p);}
static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
self::install();
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>