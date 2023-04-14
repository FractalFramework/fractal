<?php
class json2sql{	
static $private=2;
static $db='json2sql';
static $cols=['tit','txt'];
static $typs=['var','bvar'];
static $a='json2sql';
static $cb='dbs';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){
return admin::app(['a'=>self::$a,'db'=>self::$db]);}

static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function parts($sz,$n=1,$l=100){
$max=$n*$l; $min=$max-$l;
return [$min,$max];}

static function divide($f,$n){
$sz=filesize($f); $l=ceil($sz/$n); $d='';
for($i=0;$i<$n;$i++)$d.=file_get_contents($f,false,null,$i*$l,$l);
return $d;}

#build
static function build0($f){
$u='disk/usr/'.ses('usr').'/json/'.$f.'.json';
//$d=self::divide($u,10);
$sz=filesize($u); //140521653
$l=10000000; //$l=2;
$rk=['nb'=>'int','txt'=>'long']; $db='aaa_txt';
//sql::create($db,$rk,1);
$n=16;
if(!sql('id',$db,'v',$n)){
[$min,$max]=self::parts($sz,$n,$l); //echo $min.'-'.$max.' ';
$d=file_get_contents($u,false,null,$min,$l);
sql::sav($db,[$n,$d]);}
eco($d);
$d='{}';
$r=json_decode($d,true);
//$r=readcsv($u);
return $r;}

static function build1($f){$db='aaa_txt';
$r=sql('txt',$db,'rv','');
$d=implode('',$r);
$r=json_decode($d,true);
return $r;}

static function build($f){
$u='disk/usr/'.ses('usr').'/json/'.$f.'.json';
$d=file_get_contents($u);
$r=json_decode($d,true);
return $r;}

#play
static function play0($r,$f,$from=''){$ret=''; 
static $ia=0; $ia++; static $ra=[]; $ra[]=$ia;
if($r)foreach($r as $k=>$v){$rid=randid($k); $rt='';
if(is_array($v))$rt.=toggle($rid.'|json2sql,call|inp1='.$f.',from='.$k,$k,'btn').div('','',$rid);
else $rt.=div($v,'txt');
if(is_array($v) && $from==$k)$rt.=ul(self::play($v,$f));
$ret.=li($rt);}
$ia--; $ib--;
return ul($ret);}

static function play($r,$f,$from=''){$ret=''; 
//static $ia=0; $ia++; static $ra=[]; $ra[]=$ia;
if($r)foreach($r as $k=>$v)if($k<100){$rt=''; $rid=randid($k); $rt.=$k;
if(is_array($v))
	//$rt.=toggle($rid.'|json2sql,call|inp1='.$f.',from='.$k,$k,'btn');
	$rt.=ul(self::play($v,$f));
else $rt.=div($v,'txt');
$ret.=li($rt,'',$rid);}
//$ia--; $ib--;
return ul($ret);}

static function tracks($r){$rb=[];
foreach($r as $k=>$v){
	$ib=$v['id'];
	$ra=$v['comments'];
	foreach($ra as $ka=>$va){
//$rb[]=[$ib,$va['id'],$va['body'],strtotime($va['created_at']),$va['alignment'],$va['decidim_author_id']];
	$rb[]=[$va['id'],$ib,$va['decidim_author_id'],'',strtotime($va['created_at']),'gov','',$va['alignment'],$va['body'],'1','1','','fr'];}}
return $rb;}

#call
static function call($p){$ret='';
$from=val($p,'from'); $fa=val($p,'inp1');
//$dba=strfrom($fa,'/'); $dba=normalize(strto($fa,'.')); $db=self::$db.'_'.$dba;
//sql::savif(self::$db,['tit'=>$fa,'txt'=>$db]);
//$r=json::read($u);
$cols=[]; //pr($r);
//if($cols)sql::create($db,$cols,1); sql::sav2($db,$r,0);
//$n=count($r); $ret=$n.' lines';
$r=self::build($fa); //pr($r);
$r=$r['proposals'];
if($r)$rb=self::tracks($r);
//$rk=['ib'=>'int','ida'=>'int','txt'=>'var','tim'=>'int','vote'=>'int','uid'=>'int'];$db='json2sql_trk';
$rk=['ib'=>'int','name'=>'var','mail'=>'var','day'=>'int','nod'=>'var','frm'=>'var','suj'=>'var','msg'=>'text','re'=>'int','host'=>'svar','lg'=>'svar']; $db='pub_trk';
sql::create($db,$rk,1); sql::sav2($db,$rb,5);
//pr($rb);
$ret=count($rb);
//$ret.=self::play($r,$fa,$from);
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
//$bt=bj(self::$cb.',,z|json2sql,batch','go','btn');
$j=self::$cb.',,z|'.self::$a.',call||inp1';
$bt=inputcall($j,'inp1','decidim_export','',0);
$bt.=bj($j,pic('go'),'btn');
$bt.=upload::call('inp1');
return $bt.div('','pane',self::$cb);}
}
?>