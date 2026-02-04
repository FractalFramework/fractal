<?php
class algo extends appx{
static $private=0;
static $a='algo';
static $db='algo';
static $cb='alg';
static $cols=['tit','txt','pub','edt'];
static $typs=['svar','bvar','int','int'];
static $conn=0;
static $gen=0;
static $open=1;
static $tags=0;
static $qb='';//db
static $r=[];

//first col,txt,answ,com(settings),code,lang,day,clr,img,nb,cl,pub,edt
//$db2 must use col "bid" <-linked to-> id

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'var'],1);//id,...,day
parent::install(array_combine(self::$cols,self::$typs));}//id,uid,...,day

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){return parent::del($p);}

static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subform($p){return parent::subform($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}

//form
//static function fc_tit($k,$v){}
static function form($p){return parent::form($p);}

static function edit($p){
$p['bt']=bj('popup|algo,mktable_menu|id='.$p['id'],langp('create_table'),'btn');
return parent::edit($p);}

#build
static function build($p){
return parent::build($p);}

static function mkgraph($p){$r=self::build($p); $id=$p['id']; $g=new graphics;
$gid=$g::idsuj($r['tit']); $f='usr/'.ses('usr').'/algo/'.$id; $rb=db::read($f);
if(!$gid){$txt=tabler($rb);
	$ret=$g::save(['tit'=>$r['tit'],'txt'=>$txt,'type'=>1,'dk'=>0,'dv'=>0,'ad'=>1,'dc'=>1,'pr'=>0,'lb'=>0]);}
else{$fb='usr/'.ses('usr').'/graphics/'.$gid; db::save($fb,$rb); $ret=$g::edit(['id'=>$gid]);}
return div($ret,'','tbl');}

static function mktable($p){$ret='';
$id=$p['id']; $a=$p['almin']??0; $b=$p['almax']; $ia=$p['alinc'];
$r=self::build($p); $d=gen::com($r['txt'],$r); $rb=[];//$d=gen::com('[txt:gen]',$r);
conn::com2($d,'algo','exeread'); $ra=self::$r; $ka=$ra[0];
for($i=$a;$i<$b;$i+=$ia){$da=$d;
	$da=str_replace('['.$ka.':x]',$i,$da);
	$n=exec::build(['ind'=>'algo'.$id,'code'=>'$ret='.$da.';']);
	if(is_numeric($n))$rb[]=$n;}
$f='usr/'.ses('usr').'/algo/'.$id;
db::save($f,$rb);
$bt=db::bt($f);
$bt.=bj('popup|algo,mkgraph|id='.$id,langp('create_graph'),'btn');
$ret=tabler($rb);
return $bt.$ret;}

static function mktable_menu($p){$id=$p['id']; $cb=self::$cb.$id.'e';;
$bt=input('almin',0,'','minimum',1);
$bt.=input('almax',100,'','maximum',1);
$bt.=input('alinc',1,'','increment',1);
$bt.=bj($cb.'|algo,mktable|id='.$p['id'].'|almin,almax,alinc',langp('create_table'),'btn');
return $bt.div('','',$cb);}

#exec
static function exec($p){$ret=''; $id=$p['id'];
$r=self::build($p); $d=gen::com($r['txt']);
conn::com2($d,'algo','exeread'); $ra=self::$r; $rp=[];
foreach($ra as $k=>$v)$rp[$v]=is_numeric($p['ex'.$v])?$p['ex'.$v]:0;
foreach($rp as $k=>$v)$d=str_replace('['.$k.':x]',$v,$d);//eq to parse :var
$d=gen::com($d,$rp);
return exec::build(['ind'=>'algo'.$id,'code'=>'$ret='.$d.';']);}

static function exeread($da,$b){
[$p,$c]=split_one(':',$da,1);
//if($c=='date')self::$r[]=datz($p);
if($c=='x')self::$r[]=$p;}

#play
static function play($p){$id=$p['id'];
$r=self::build($p); $cb=self::$cb.'o'.$id;//p($r);
$d=gen::com($r['txt']); //$d=gen::com('[txt:gen]',$r);
$bt=div($r['tit'],'tit');
conn::com2($d,'algo','exeread'); $ra=self::$r;
$in=$ra?'ex'.implode(',ex',$ra):'';
$j=$cb.'|algo,exec|id='.$id.'|'.$in;
foreach($ra as $k=>$v)$d=str_replace('['.$v.':x]',inpnb('ex'.$v,1,4,'','',$j),$d);
//$bt.=bj($j,langp('ok'),'btn');
return $bt.$d.' = '.span('','',$cb);}

static function stream($p){
return parent::stream($p);}

#call (read)
static function tit($p){
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){return parent::com($p);}
static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
//self::install();
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>