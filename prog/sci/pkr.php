<?php
//poker
class pkr extends appx{
static $private=0;
static $a='pkr';
static $db='pkr';
static $cb='pkr';
static $cols=['tit'];
static $typs=['var'];
static $conn=0;
static $db2='pkr_flop';
static $db3='pkr_gamr';
static $open=0;
static $qb='';//db
static $crd=[1=>2,2=>3,3=>4,4=>5,5=>6,6=>7,7=>8,8=>9,9=>10,'a'=>'J','b'=>'Q','c'=>'K','d'=>'A'];
static $clr=['p'=>'peak','c'=>'clover','d'=>'diam','h'=>'heart'];
static $cardset=[];
static $cards=[];
static $flop=[];
static $find=[];

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
$r=['bid'=>'int','uid'=>'int','cards'=>'var','p1'=>'svar','p2'=>'svar','p3'=>'svar','p4'=>'svar','p5'=>'svar','p6'=>'svar','p7'=>'svar'];//
sql::create(self::$db2,$r,1);}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','.card1,.card2{border:1px solid black; padding:2px; margin:2px; size:20px; line-height:28px;}
.card1{color:black;} .card2{color:red;}
.card1 .philum{color:black;} .card2 .philum{color:red;}');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2;return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){$p['tit']=date('ymd'); return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){$p['t']='cards'; return parent::subcall($p);}
static function subform($r){return parent::subform($r);}

//form
static function form($p){
return parent::form($p);}

static function edit($p){
$p['collect']=self::$db2;
$p['sub']=1;
return parent::edit($p);}

static function delflop($p){
sql::del(self::$db2,$p['bid']);
return self::play($p);}

static function addflop($p){
sql::sav(self::$db2,[$p['id'],$p['cdr']]);}

static function dellast($p){
$d=sql('cards',self::$db2,'v',$p['bid']);
sql::up(self::$db2,'cards',substr($d,0,2),$p['bid']);
return self::play($p);}

static function compsave($p){
$r=vals($p,['bid','uid','cd0','cd1','cd2','cd3','','','','']); pr($r);
//$bid=sql::up(self::$db2,$r,$p['bid']);
if($p['cd0'])$p['bid']=sql::sav(self::$db2,$r); $p['opn']=1;
return self::edit($p);}

static function addone($p){
$r0=explode('-',$p['cd0']); $n0=count($r0);
$r1=explode('-',$p['cd1']); $n1=count($r1);
$r2=explode('-',$p['cd2']); $n2=count($r2);
$r3=explode('-',$p['cd3']); $n3=count($r3);
if($n1<2)$r1[]=$p['card'];
elseif($n0<5)$r0[]=$p['card'];
elseif($n2<2)$r2[]=$p['card'];
elseif($n3<2)$r3[]=$p['card'];
if(!$r0[0])unset($r0[0]);
if(!$r1[0])unset($r1[0]);
if(!$r2[0])unset($r2[0]);
if(!$r3[0])unset($r3[0]);
$p['cd0']=implode('-',$r0);
$p['cd1']=implode('-',$r1);
$p['cd2']=implode('-',$r2);
$p['cd3']=implode('-',$r3);
return self::compose($p);}

#game
static function card($n,$c){$ret='';
if($n)$ret.=self::$crd[$n];
if($c)$ret.=picto(self::$clr[$c]);
$s=$c=='h'||$c=='d'?'card2':'card1';
return span($ret,$s);}

static function compose($p){
[$id,$cd0,$cd1,$cd2,$cd3]=vals($p,['id','cd0','cd1','cd2','cd3']); $ret=''; self::set();
$ret=div(self::display($cd0).'|'.self::display($cd1).'|'.self::display($cd2).'|'.self::display($cd3));
$r=self::build($p); $cb=self::$cb.$id; $prm='id='.$id;
foreach(self::$crd as $k=>$v){$bt='';
	foreach(self::$clr as $ka=>$va){$card=self::card($k,$ka);
		$bt.=bj('drwf|pkr,addone|id='.$id.',card='.$k.$ka.'|bid,uid,cd0,cd1,cd2,cd3',$card,'');}
	$ret.=div($bt);}
	//$ret.=bj('drwf|pkr,dellast|id='.$id,pic('erase'),'btdel');
$ret.=bj($cb.',,x|pkr,compsave|id='.$id.'|bid,uid,cd0,cd1,cd2,cd3',langp('save'),'btsav');
$ret.=hidden('bid',$p['id']).hidden('uid',ses('uid'));
$ret.=hidden('cd0',$cd0).hidden('cd1',$cd1).hidden('cd2',$cd2).hidden('cd3',$cd3);
return $ret;}

static function display($d){$ret=''; $r=explode('-',$d);
foreach($r as $k=>$v){$n=substr($v,0,1); $c=substr($v,1); $ret.=self::card($n,$c);}
//$ret.=self::win($d);
return $ret;}

//algo
static function win($d){$ret=''; $r=explode('-',$d); $high=''; //sometimes 7?
$pairs=0; $brelan=0; $quinte=[]; $flush=0; $quads=0; $ace=0; $kc=''; $rb=[];
$ra=[1=>2,2=>3,3=>4,4=>5,5=>6,6=>7,7=>8,8=>9,9=>10,'a'=>11,'b'=>12,'c'=>13,'d'=>14];
foreach($r as $k=>$v)if($v){$n=substr($v,0,1); $c=substr($v,1); $n=$ra[$n]; $rb[$k]=[$n,$c];
	$rn[$n][$k]=1; $rc[$c][$k]=1; if($n==14)$ace=1;} ksort($rn); $vn=key($rn);//a2345
foreach($rn as $k=>$v){//quinte
	if($vn==$k-1){if(!$quinte)$quinte[]=$vn; $quinte[]=$k;}
	elseif($vn!=$k && count($quinte)<4)$quinte=[]; $vn=$k;}
if($quinte && $vn==14 && $quinte[0]==2)$quinte[]=1; sort($quinte); $nq=count($quinte); 
foreach($rc as $k=>$v){$n=count($v); if($n>=5){$flush=1; $kc=$k; $high=$k;}}//colors
foreach($rn as $k=>$v){$n=count($v); if($n==2){$pairs+=1; $high=$k;} if($n==3){$brelan=1; $high=$k;} 
	if($n==4){$quads=1; $high=$k;}}
if($quads)$ret=7; elseif($pairs && $brelan)$ret=6; elseif($flush)$ret=5; elseif($nq>=5)$ret=4;
elseif($brelan)$ret=3; elseif($pairs==2)$ret=2; elseif($pairs)$ret=1;
if($flush && $nq>=5){$qf=0; sort($rb);
	foreach($rb as $k=>$v){$n=$v[0]; $c=$v[1]; if(in_array($n,$quinte) && $c==$kc)$qf+=1;}
if($qf==7)$ret=$ace?9.2:8.2; elseif($qf==6)$ret=$ace?9.1:8.1; elseif($qf==5)$ret=$ace?9:8;}
return $ret;}

static function hands($r,$id){$ret=''; $rw=[];
$rb['_']=['flop','p1','p2','p2','p4','p5','p6','p7','win']; $cb=self::$cb.$id;
$rc=[1=>'pair','double pair','brelan','quinte','flush','full','quads','Quinte flush','Royale'];
foreach($r as $k=>$v){$rb[$k][0]=self::display($v['cards']); $rs=[]; if($k==0)$lastid=$v['id'];
	for($i=1;$i<8;$i++){$rb[$k][$i]=self::display($v['p'.$i]);
		$score=self::win($v['cards'].'-'.$v['p'.$i]); $rs[$i]=$score;
		$rb[$k][$i].=br().($score?$rc[$score]:'');}
	arsort($rs); $win=current($rs); $ka=key($rs); $rb[$k][]=$ka.':'.$rc[$win]; $rw[]=$ka;}
if(!$rw)return;
$rwin=array_count_values($rw); arsort($rwin); //pr($rwin);
//if($rwin)//ex-aequos
$ret=div('big winner: '.(key($rwin)).' - '.count($r).' flops - '.prm($rwin));
$ret.=bj($cb.'|pkr,newflop|id='.$id,langp('newflop'),'btn');
$ret.=bj('popup|pkr,drawflop|id='.$id,langp('drawflop'),'btn');
$ret.=bj($cb.'|pkr,delflop|id='.$id.',bid='.$lastid,langp('delflop'),'btdel');
$ret.=bj($cb.',,z|pkr,findflop|id='.$id,langp('find'),'btn');
$ret.=tabler($rb);
return $ret;}

static function findflop($p){
$id=$p['id']; $ret=''; $n=10000; $to=$p['to']??2; $ok=0; $io=0; $cb=self::$cb.$id; self::set();
$j=$cb.',,z|pkr,findflop|id='.$id;
$rc=[1=>'pair','double pair','brelan','quinte','flush','full','quads','Quinte flush','Royale'];
foreach($rc as $k=>$v)$ret.=bj($j.',to='.$k,$v,'btn').' '; $ret.=bj($j.',to=test','test','btn').' ';
if($to=='test'){$ret.=br();
	$r=['royale2'=>'7h-8h-9h-ah-bh-ch-dh','royale1'=>'6h-8h-9h-ah-bh-ch-dh','royale'=>'5h-6h-9h-ah-bh-ch-dh','qf2'=>'7h-8h-9h-ah-bh-ch-6h','qf1'=>'7h-8h-9h-ah-bh-ch-5p','qf1'=>'7h-8h-9h-ah-bh-ch-5h','qf'=>'7h-8h-9h-ah-bh-4p-5p','quads'=>'2d-8h-8d-8c-8p-ch-dh','full'=>'bd-8h-8d-8c-bh-ch-dh','flush'=>'2h-8h-9h-ch-dh-4c-6d','quinte'=>'2d-4h-5d-6c-bh-3p-7d','quinte'=>'1d-2h-3d-4c-bh-4p-dd','brelan'=>'1d-2h-5h-7h-7c-7p-dh','double'=>'8h-8c-ap-ac-ch-dh-2p','pair'=>'8h-8c-2p-4c-ch-dh-7p'];
	foreach($r as $k=>$v){$s=self::win($v); $ret.=self::display($v).' '.$k.' '.$rc[round($s)].' '.$s.br();}
	return $ret;}
for($i=0;$i<$n;$i++){
	$r=self::draw(); //pr($r);
	$flop=$r[0]; 
	for($ia=1;$ia<=7;$ia++){$hand=$r[$ia]; $io++;
		$score=self::win($flop.'-'.$hand);
		//self::$find[]=$score;
		if(round($score)==$to){$ok++;}}// $i=$n;
		if(!$ok && $n<100000)$n+=1;}
//echo $k=in_array_k($to,self::$find);
if($ok){$ret.=$rc[$to].' ('.$score.') found at: '.$ok.'/'.$io.'='.(round($ok/$io,6)*100).'%'; self::$find=[];}
else $ret.='not found in '.$io.' runs';
//else{self::findflop($p); $i++;}
return $ret;}

#flop
static function onecard(){$r=self::$cards; sort($r);
$n=count($r)-1; $k=rand(0,$n); //p($r); echo $k; echo br();
$d=$r[$k]; self::$flop[]=$d; unset(self::$cards[$k]); //echo $d.'-';
return $d;}

static function set(){
foreach(self::$crd as $k=>$v)foreach(self::$clr as $kb=>$vb)$r[]=$k.$kb; self::$cardset=$r;}

static function draw(){self::$cards=self::$cardset;
for($i=0;$i<5;$i++)$rb[]=self::onecard(); $ret[]=implode('-',$rb); $rb=[];
for($a=0;$a<7;$a++)$ret[]=self::onecard().'-'.self::onecard();
return $ret;}

#build
static function build($p){
//$t=sql('tit',self::$db,'v',$p['id']);//
$r=sql('id,cards,p1,p2,p3,p4,p5,p6,p7',self::$db2,'rr',['bid'=>$p['id'],'_order'=>'id desc']); //pr($r);
//if($r)foreach($r as $k=>$v)$r[$k]=self::display($v);
return $r;}

#play
static function play($p){
$r=self::build($p);
if(!$r){self::flop($p['id']); $r=self::build($p);}
$ret=self::hands($r,$p['id']);
return $ret;}

static function flop($id){
self::set(); $r=self::draw();
array_unshift($r,ses('uid'));
array_unshift($r,$id);
$id=sql::sav(self::$db2,$r);
$rb[$id]=$r[0];
return $rb;}

static function newflop($p){
$r=self::flop($p['id']);
return self::play($p);}

static function drawflop($p){$id=$p['id'];
//$r=[$id,ses('uid'),'','','','','','','',''];
//$bid=sql::sav(self::$db2,$r);
$ret=self::compose($p);
return div($ret,'','drwf');}

static function stream($p){
//$p['t']=self::$cols[0];
return parent::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
$ret=div(self::play($p),'',self::$cb.$p['id']);
return div($ret,'',self::$cb);}

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