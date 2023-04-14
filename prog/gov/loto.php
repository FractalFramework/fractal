<?php

class loto extends appx{
static $private=2;
static $a='loto';
static $db='loto';
static $cb='lto';
static $cols=['tit','nb','result','day'];
static $typs=['var','int','int','date'];
static $db2='loto_vals';
static $db3='loto_win';
static $tags=0;
static $open=1;
static $price=10;
static $ty=0;

function __construct(){
$r=['a','db','db2','db3','cb','cols'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'int','bet'=>'int'],1);
sql::create(self::$db3,['bid'=>'int','uid'=>'int','price'=>'int'],1);}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}
static function js(){return 'function barlabel(v,id){var d="";
var r=["","broken","bad","works","good","new","","",""];
inn(r[v],id);}';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}

static function fc_nb($k,$v){return hidden($k,$v).div($v,'inp opac');}
static function fc_result($k,$v){return hidden($k,$v).div($v?$v:lang('raffle'),'inp opac');}
static function fc_day($k,$v){$val=$v && $v!='0000-00-00'?$v:date('Y-m-d',time()+86400);
return input($k,$val,8,lang('date'),'','','',$v?1:0);}
static function form($p){$p['fcresult']=1; $p['fcday']=1; //$p['barfunc']='barlabel'; 
if($p['id']??'')$p['fcnb']=1; return parent::form($p);}

static function create($p){return parent::create($p);}
static function edit($p){$p['collect']=self::$db2; return parent::edit($p);}

#bank
static function bank_condition($p){//needed by bank
[$id,$cnd]=vals($p,['aid','cnd']);
if($id)$ex=sql('id',self::$db,'v',$id);
if($cnd==2){//usr is winner
	$res=sql('result',self::$db,'v',$id);
	$ply=sql('val',self::$db2,'v',['bid'=>$id,'uid'=>ses('uid')]);
	if($res!=$ply)$id=0;}
return $id?1:0;}

static function bank_trigger($p){//needed by bank
$a=self::$a; $ty=self::$ty; $uid=ses('uid');
[$bid,$val]=vals($p,['aid','val'],0);
$r=['bid'=>$bid,'uid'=>$uid,'val'=>$val,'bet'=>self::$price];//'bk'=>$ok
return sql::sav(self::$db2,$r);}

static function bank_finalization($p){//needed by bank
[$bid,$cid,$don,$rat,$rf,$cnd]=vals($p,['aid','cid','value','rat','rf','cnd'],0);
if($cnd==1)$bt='good luck'; elseif($cnd==2)$bt='congratulations'; else $bt='back';
$ret=bj(self::$cb.$bid.'|loto,call|id='.$bid,langp($bt),'btok');
//if($rf)$ret.=trace(bank::$rf);
return $ret;}

#build
static function participate($p){
$id=$p['id']??''; $val=$p['val'.$id]; $a=self::$a; 
$lbl=$id.':'.$a; $cb=self::$cb.$id; $p['label']=$lbl;
if(strpos($val,'0')!==false)return self::play($p);
//$uid=sql('uid',self::$db,'v',$id);//payer
$rp=['from'=>ses('uid'),'label'=>$lbl,'value'=>self::$price,'type'=>self::$ty,'app'=>self::$a,'aid'=>$id,'at'=>0,'cnd'=>1,'cnd'=>1,'vb'=>0,'ok'=>1,'rid'=>$cb,'ct'=>0,'rf'=>1,'val'=>$val];
$er=bank::transaction($rp); if(is_numeric($er))$ret=bank::verbose($er); else $ret=$er;
return $ret.self::play($p);}

static function draw($r,$id){
for($i=0;$i<$r['nb'];$i++)$rv[]=rand(1,9); $val=implode('',$rv);
sql::up(self::$db,'result',$val,$id);
return $val;}

//game
static function balls($v){$ret='';
$d='10101 10102 10103 10104 10105 10106 10107 10108 10109 10110 10111';
//$d='10111 10112 10113 10114 10115 10116 10117 10118 10119 10120 10121';//1-10
//$d='65295 65296 65297 65298 65299 65300 65301 65302 65303 65304 65305';
$ra=explode(' ',$d); $r=str_split($v);
foreach($r as $v)$ret.=ascii($ra[$v]);
return $ret;}

static function numbers($id,$n,$v){$ret='';
for($i=1;$i<10;$i++){$c=$v==$i?' btok':'';
	$j='loto'.$id.'|loto,game|id='.$id.',n='.$n.',v='.$i.'|val'.$id;
	$ret.=bj($j,ascii(10101+$i),'btsav'.$c);}//$i==0?9471:
return div($ret);}

static function game($p){$ret='';
$id=$p['id']??''; $n=val($p,'n',0); $v=val($p,'v',0); $a=self::$a; $cb=self::$cb;
$nb=sql('nb',self::$db,'v',$id);
$val=val($p,'val'.$id,str_pad('',$nb,'0'));
$rv=str_split($val); if($v)$rv[$n]=$v; $val=implode('',$rv);
for($i=0;$i<$nb;$i++)$ret.=self::numbers($id,$i,$rv[$i]);
$bt=langp('play it').' '.bank::coin(self::$price,self::$ty);
$ret.=bj('lto'.$id.'|'.$a.',participate|id='.$id.'|val'.$id.'',$bt,'btsav');
$ret.=hidden('val'.$id,$val);
return $ret;}

//results
static function paywin($p){
$id=$p['id']; $price=$p['price']; $uid=ses('uid'); $lbl=$id.':'.self::$a.'.winner';
$ex=sql::sav(self::$db3,['bid'=>$id,'uid'=>$uid,'price'=>$price]);
$rp=['from'=>0,'label'=>$lbl,'value'=>$price,'type'=>self::$ty,'app'=>self::$a,'aid'=>$id,'at'=>$uid,'cnd'=>2,'cnd'=>2,'vb'=>1,'ok'=>1,'rid'=>'','ct'=>0];//ct
return bank::transaction($rp);}

static function results($r,$ex,$id){$val='';
if($ex)$val=sql('val',self::$db2,'v',$ex);//choice
$players=sql('count(id)',self::$db2,'v',['bid'=>$id]);
$winners=sql::inner('name',self::$db2,'login','uid','rv',['bid'=>$id,'val'=>$r['result']]);
$ret=div(lang('winning number').' '.self::balls($r['result']),'nfo');
if($val)$ret.=div(lang('you played').' '.self::balls($val),'nfo');
if($winners){$nb=count($winners); $cagnote=ceil(self::$price*$players/$nb);
	$bt=$nb.' '.langs('player',$nb,1).' '.langs('won',$nb,1).' ';
	$bt.=bank::coin($cagnote).' ('.implode(', ',$winners).')';
	$ret.=div($bt,'valid');}
else $ret.=div(lang('no winner'),'alert');
if($val && $val==$r['result']){
	//foreach($winner as $k=>$v){}//winner have to take his lot himself
	$payed=sql('id',self::$db3,'v',['bid'=>$id,'uid'=>ses('uid')]);
	if(!$payed)$ret.=self::paywin(['id'=>$id,'price'=>$cagnote]);
	$ret.=div(lang('you win').' '.bank::coin($cagnote),'valid');}
elseif($val)$ret.=div(lang('you loose'),'alert');
return $ret;}

#play
static function build($p){return parent::build($p);}

static function play($p){
$id=$p['id']??'';
$r=self::build($p);
$ret=div($r['tit'],'tit');
$maxday=$r['day']; $end=strtotime($maxday); 
if($end>ses('time'))$current=1; else $current=0;
if(!$current && !$r['result'])$r['result']=self::draw($r,$id);
$ex=sql('id',self::$db2,'v',['bid'=>$id,'uid'=>ses('uid')]);
if(!$ex && $current)$bt=self::game($p);
elseif($ex && $current)$bt=div(lang('thank for playing'),'valid');
elseif(!$current)$bt=self::results($r,$ex,$id);
$ret.=div($bt,'','loto'.$id);
if($current)$ret.=div(lang('time left',1).' : '.build::leftime($end),'nfo');
else $ret.=div(lang('loto finished').' '.$maxday,'nfo');
return $ret;}

static function stream($p){
return parent::stream($p);}

#call (read)
static function tit($p){
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){
return parent::com($p);}

#interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>