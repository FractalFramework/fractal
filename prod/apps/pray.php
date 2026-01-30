<?php

class pray extends appx{
static $private=1;
static $a='pray';
static $db='pray';
static $cb='prwrp';
static $cols=['tit','txt','day','pub'];
static $typs=['var','text','date','int'];
static $conn=0;
static $tags=0;
static $open=0;

//install
static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create('pray_group',['bid'=>'int','uid'=>'int'],1);
sql::create('pray_valid',['bid'=>'int','uid'=>'int','day'=>'date','ok'=>'int'],1);}

static function admin($p){$p['o']='1'; return parent::admin($p);}
static function headers(){head::add('csscode','');}

#edit
static function del($p){
$p['db2']='pray_group';
return parent::del($p);}

static function modif($p){
return parent::modif($p);}

static function form($p){
return parent::form($p);}

static function edit($p){
$p['conn']=self::$conn;
$p['collect']='pray_valid';
$p['help']='pray_edit';
return parent::edit($p);}

static function collect($p){
return parent::collect($p);}

static function save($p){
return parent::save($p);}

static function create($p){
return parent::create($p);}

#check
static function checkDay($p){
if(val($p,'status')){
	$id=sql('id','pray_valid','v','where bid="'.$p['id'].'" and uid="'.ses('uid').'" and day="'.$p['day'].'"');
	if($p['status']==1)sql::upd('pray_valid',['ok'=>2],$id);
	elseif($p['status']==2)sql::upd('pray_valid',['ok'=>1],$id);}
else sql::sav('pray_valid',[$p['id'],ses('uid'),$p['day'],1]);
return self::week($p);}

#week
static function week($p){$rb=[]; $rh=[]; $rs=[];
$id=$p['id']; $now=time(); $usr=ses('usr'); 
$date=sql('day',self::$db,'v',$id); $firstDay=strtotime($date);
$w='left join login on uid=login.id where bid='.$id;
$rusr=sql('name','pray_group','rv',$w);
$rvalid=sql('name,day,ok','pray_valid','kkv',$w);
setlng();//setlocale
for($i=0;$i<7;$i++)$rh[]=date('d/m/Y',$firstDay+(86400*$i));//headers
for($i=0;$i<7;$i++)$rdates[]=date('Y-m-d',$firstDay+(86400*$i));//headers
//pr($rusr);//0=>dav
//pr($rvalid);//dav=>[date=>1/2]
//scores
if($rvalid)foreach($rvalid as $k=>$v){$rord[$k]=0;
	if($v)foreach($v as $vb)if($vb==1)$rord[$k]+=1; else $rord[$k]=0;}
//order
if($rusr)foreach($rusr as $k=>$v)$rok[$v]=isset($rord[$v])?$rord[$v]:'';
//view
if(isset($rok)){//arsort($rok);//pr($rok);
foreach($rok as $k=>$v){
	for($i=0;$i<7;$i++){
		$currentTime=$firstDay+(86400*$i);
		$currentDate=date('Y-m-d',$currentTime);
		if(isset($rvalid[$k][$currentDate]))
			$ok=$rvalid[$k][$currentDate];
		else $ok=0;
		if($currentTime<=$now){
			if($ok==2){$c=' disactive'; $ico=ico('close');}
			elseif($ok==1){$c=' active'; $ico=ico('check');}
			else{$c=''; $ico=ico('minus');}
			if($k==$usr)$bt=bj('rv'.$id.'|pray,checkDay|id='.$id.',status='.$ok.',day='.$currentDate,$ico,'minicon'.$c);
			else $bt=tag('span',['class'=>'minicon opac'.$c],$ico);}
		else $bt=span('-','minicon off');
		if($ok==1)$rc[$k][]=1;
		$rsum[$k][$i]=$ok==1?1:0;
		$rb[$k][]=$bt;}
	//score
	if(isset($rc[$k])){
		$n=count($rc[$k]);
		if($n>=7)$c=' yes'; else $c=' no';
		if($firstDay+592200<ses('time'))$rb[$k][]=span($n,'success'.$c);
		if($n>=7)$rd[$k][]=1;}}}//else $rd[$k][]=0;
//sum
if($rb)for($i=0;$i<7;$i++)$rs[]=array_sum(array_column($rsum,$i)); //pr($rs);
$n=count($rd??0);
$rs[]=span($n,$n>=7?'valid':'alert');
if($rs)array_push($rb,$rs);
//render
//pr($rb);
$ret=tabler($rb,$rh,1);
//total
if($n>=7)$ret.=div(lang('success').' : '.$n.' / 7','valid');
else $ret.=div(lang('fail').' : '.$n.' / 7','alert');
return $ret;}

#play
static function participation($p){
if($p['subscribe']=='ok'){$p['op']=1;
	sql::sav('pray_group',[$p['id'],ses('uid')]);}
elseif($p['subscribe']=='ko'){
	sql::del('pray_group',['bid'=>$p['id'],'uid'=>$p['uid']]);
	sql::del('pray_valid',['bid'=>$p['id'],'uid'=>$p['uid']]);}
return self::build($p);}

#pane
static function build($p){$id=$p['id']; $op=val($p,'op'); $ret='';
$n=sql('count(id)','pray_group','v','where bid='.$id);
$bt=ico('bars').' '.langnb('participant',$n);
//$ret.=toggle('rv'.$id.'|pray,week|id='.$id,$bt,'nfo',$op?1:0);
if($uid=ses('uid')){
	$uidok=sql('id','pray_group','v','where bid="'.$id.'" and uid="'.$uid.'"');
	$ex=sql('id','pray_valid','v','where bid="'.$id.'" and uid="'.$uid.'"');
	$j='ev'.$id.'|pray,participation|id='.$id.',uid='.$uid;
	if(!$uidok)$ret.=bj($j.',subscribe=ok',lang('participate'),'btsav').' ';
	elseif(!$ex)$ret.=bj($j.',subscribe=ko',lang('unsubscribe'),'btdel').' ';}//
else $uid='';
//if($op)$week=self::week(['id'=>$id]); else $week='';
$week=self::week($p);
$ret.=div($week,'','rv'.$id);
return div($ret,'','ev'.$id);}

#stream
static function play($p){$id=$p['id']; $p['conn']=self::$conn;
$w='left join profile on '.self::$db.'.uid=profile.puid where '.self::$db.'.id='.$id;
$r=sql('uid,txt,day,pname',self::$db,'ra',$w);
if(!$r)return lang('entry not exists');
//$bt=lk('/pray/'.$id,ico('link'));
//if(ses('uid'))if($rid=$p['rid']??'')$bt.=tlxf::publishbt(lang('use'),$id.':pray',$rid);
$bt=hlpbt('pray_how');
$ret=div($bt,'right');
$tx=voc($r['txt'],self::$db.'-txt-'.$id);
if(val($p,'conn')=='no')$txt=$tx; else $txt=nl2br($tx);//!!
$go=bj('mee'.$id.'|pray,play|op=1,id='.$id,'#'.$id,'btn');
$time=strtotime($r['day']);
if($time+592200<ses('time'))$c='alert '; else $c='valid ';
$date=span(lang('date',1).' : '.$r['day'],$c.'nfo');
//$ret.=div($r['pname'].' '.$date);//$go.' '.span(lang('by'),'small').' '.
$ret.=div($txt,'txt');
$ret.=self::build($p);
return div($ret,'');}

static function stream($p){
return parent::stream($p);}

#interfaces
static function tit($p){
return parent::tit($p);}

static function call($p){
return parent::call($p);}

//com (edit)
static function com($p){
return parent::com($p);}

//interface
static function content($p){
//self::install();
return parent::content($p);}
}

?>