<?php

class longdoc{	
static $private=2;
static $db='longdoc';
static $cols=['tit','txt'];
static $typs=['var','text'];
static $a='longdoc';
static $cb='lgd';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),0);}

static function admin(){
$r[]=['','j','popup|longdoc,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=longdoc_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=longdoc','code','Code'];
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

static function save0($t,$d){
$id=sql('id',self::$db,'v',['tit'=>$t]);
if($id)sql::upd(self::$db,['txt'=>$d],$id); else $id=sql::sav(self::$db,[$t,$d]);
return $id;}

static function save($t,$d){
$db='multilang'; $uid=ses('uid'); $md5=random();
$id=sql('id',$db,'v',['tit'=>$t]);
if($id)sql::upd($db,['txt'=>$d],$id); else $id=sql::sav($db,[$uid,$t,$d,'it',$md5,0,0]);
return $id;}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',$id);
return $r;}

/*static function lastagpos($v,$ab,$ba){$d=str::substrpos($v,$ab,$ba);
$nb_aa=substr_count($d,'<'); $nb_bb=substr_count($d,'>'); $nb=$nb_aa-$nb_bb;
if($nb>0){for($i=0;$i<$nb;$i++)$ba=strpos($v,'}',$ba+1); $ba=lastagpos($v,$ab,$ba);}
return $ba;}*/

#read
static function play($p){
$n=$p['n']; $start=$n*self::$sz;
$bt=div($n,'valid');
//$f='usr/'.ses('usr').'/'.$p['file'];
$f='usr/dav/WaletHumm3.html';
//$d=file_get_contents($f);
//echo array_sum(count_chars($d));
$d=file_get_contents($f,NULL,NULL,$start,self::$sz);
//$d=file_get_contents($f);
//$d=str::goodend($d,$start,self::$sz);
//$d=conv::call(['txt'=>$d]);
//$d=strip_tags($d);
//sql::upd('dbdoc',['txt'=>$d],,1);
//self::save($n,$d);
return $bt.$d;}

static function stream($p){
$r=self::build($p); $ret='';
if($r)foreach($r as $k=>$v){$rb='';
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
if(!$ret)return help('no element','txt');
return div($ret,'');}

static $sz=100000;

static function com($p){$ret='';
$f='usr/dav/WaletHumm3.html';
$d=file_get_contents($f);
//self::save('WaletHumm3',$d);
echo $nb=array_sum(count_chars($d));//1528301
//$n=$nb/self::$sz;
//for($i=0;$i<$n;$i++)$ret.=bj(self::$cb.'|longdoc,play|n='.$i,$i,'btn').' ';
$r=explode('<h1>',$d);
foreach($r as $k=>$v){
	if(substr($v,-4)=='<h1>')$v=substr($v,0,-4);
	$t=substr($v,0,strpos($v,'</h1>'));
	$v='<h1>'.$v;
	$ret.=div($t);
	if($t=='CAPITOLO XL')echo 'yes';
	//$id=self::save($t,$v);
}
//$ret.=bj(self::$cb.'|longdoc,play|n=','go','btn').' ';
return div($ret,'');}

static $lg='fr';

//tools
static function compile($p){$ret='';
$r=sql('tit,txt','multilang','kv','where lang="'.self::$lg.'" and id>17 order by id');
$ret.=head::meta('http-equiv','Content-Type','text/html; charset=utf8');
$ret.='<body style="white-space:pre-line;">';
foreach($r as $k=>$v)$ret.=conn::com($v,1);//tag('h2','',$k).
$ret.='</body>';
$f='usr/dav/WaletHumm_'.self::$lg.'.html';
write_file($f,$ret);
return $ret;}

static function reflush($p){$ret='';
for($i=203;$i>199;$i--)sql::upd('multilang',['id'=>$i+1],$i);
return $ret;}

static function book($p){$ret=''; $lg=self::$lg; $lg='fr';
$r=sql('tit,txt','multilang','kv','where lang="'.$lg.'" and id>17 order by id'); //pr($r);
foreach($r as $k=>$v){//sql::sav('book_chap',[5,$k,$v]);
	$idb=sql('id','book_chap','v',['chapter'=>$k,'bid'=>5]);
	$v=str_replace(['<h1>','</h1>'],['[',':h1]'],$v);
	if($idb)sql::upd('book_chap',['txt'=>$v],$idb);}
return $ret;}

static function call($p){$ret='';
//$ret=self::stream($p);
//$ret=self::compile($p);
//$ret=self::reflush($p);
//$ret=self::book($p);
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??''; $bt='';
//$bt=self::com($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>