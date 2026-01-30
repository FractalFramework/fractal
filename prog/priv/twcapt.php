<?php

class twcapt{
static $private=0;
static $a='twcapt';
static $db='twcapt';
static $cols=['twid'];
static $typs=['bint'];
static $cb='twc';

static function install($p=''){
appx::install(array_combine(self::$cols,self::$typs));}

static function admin(){
$r[]=['','pop','core,help|ref=twcapt_app','help','-'];
return $r;}

static function js(){return '';}

static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'ra',$id);
return $r;}

static function mkfile($r,$twid,$o){
$txt=csv($r);
//$ret.=textarea('',$txt,50,10);
$f='usr/tw/'.$twid.'_'.$o.'.csv'; write_file($f,$txt);
$ret=lk('/'.$f,picxt('download',$twid),'btn',1).' ';
return $ret;}

static function lik($p){$ret=''; $rb='';
$twid=val($p,'twid'); $d=val($p,'twlk'); $d=conv::com($d); //eco($d);
$r=explode("\n",$d); //pr($r);
foreach($r as $k=>$v){if(substr($v,0,1)=='@')$rb[]=[$r[$k-2],$v,$r[$k+2]];}
$ret=tabler($rb);
$ret.=download::mkcsv($rb,$twid);
return $ret;}

static function rtw($p){
$twid=val($p,'twid'); $o=val($p,'mode'); $rb=''; $ret='';
$t=new twit(twitter::init());
$q=$t->retweeters($twid,5); //pr($q); return;
if($er=twitter::error($q))return $er;
$ra=['id','screen_name','name','description','created_at','date','hour','statuses_count','following','friends_count','followers_count','favourites_count','location','verified','protected','url','profile_image_url','profile_background_image_url'];//
foreach($q['ids'] as $k=>$v){$qu=$t->lookup($v); //pr($qu);
	if($qu){foreach($ra as $va)$rb[$va]=isset($qu[0][$va])?$qu[0][$va]:'';
		$time=strtotime($qu[0]['status']['created_at']);
		//$rb['created_at']=date('d/m/Y',strtotime($time));
		$rb['date']=date('d/m/Y',$time);
		$rb['hour']=date('H:i',$time);
		unset($rb['created_at']);
		$rc[]=$rb;}}
unset($ra[4]);
if($rc){array_unshift($rc,$ra); $ret=tabler($rc,1);}
$ret.=download::mkcsv($rc,$twid.'_retweets');
return $ret;}

static function sav_twits($r){$rb='';
$t=new twit(twitter::init()); $bid=20;
if($r)foreach($r as $k=>$v)if(is_numeric($v)){
	//$ret.=twitter::read($twid);
	$q=$t->read($v); //p($q);
	if(!$er=twitter::error($q)){$r=twitter::datas($q); $rb[]=$r; twapi::twsave($r,$bid,$v);}}
return $rb;}

static function capt_twits($p){$ret=''; $rb=''; $rc=''; $rd=''; $bid=20;
$twid=val($p,'twid'); $d=val($p,'twlk'); //$ret=eco($d,1);
$q=dom($d); //pr($q);
$r1=$q->getElementsByTagName('li');
foreach($r1 as $k1=>$v1){
	$id=domattr($v1,'data-item-id');
	$ra=$v1->getElementsByTagName('div');
	foreach($ra as $ka=>$va){
		$idok=domattr($va,'data-tweet-id'); //echo $idok;
		if($idok==$id){
		$id=domattr($va,'data-tweet-id');
		$lk=domattr($va,'data-permalink-path');
		$sn=domattr($va,'data-screen-name');
		$nm=domattr($va,'data-nm');
		$ud=domattr($va,'data-user-id');
		$me=domattr($va,'data-mentions');
		if(is_numeric($id) && !isset($r0[$id])){$rb[]=[$id,$lk,$sn,$nm,$ud,$me]; $r0[$id]=1;}}
	}
}
$r=$q->getElementsByTagName('div');
foreach($r as $k=>$v){
	$cs=domattr($v,'class');
	if($cs=='js-tweet-text-container')$rc[]=($v->nodeValue);}//utf8dec
foreach($rb as $k=>$v){$rb[$k][]=$rc[$k]; $re[]=$v[0];}
//pr($re);
echo implode(' ',$re);
//if($rb)$rc=self::sav_twits($rb);
/*
foreach($rb as $k=>$v){
	$ex=sql('id',twapi::$db2,'v','where aid='.$v[0].' and bid='.$bid);
	if(!$ex)$rd[]=[$bid,$v[0]];}
if($rd)sql::sav2(twapi::$db2,$rd);*/
//$ret=twapi::playtab($p);
//$rc=twapi::build($p);
$r2[]=['id','link','screen_name','name','user_id','mention','content'];
$rb=array_merge($r2,$rb); //pr($rb);
if($rb)$ret.=download::mkcsv($rb,'boycott_lemondefr');
return $ret;}

static function capt_list($p){
$r=explode(' ',$val($p,'twls')); $bid=20;
foreach($r as $k=>$v){
	$ex=sql('id',self::$db2,'v','where aid='.$v.' and bid='.$bid);
	if(!$ex)sql::sav(twapi::$db2,[$bid,$aid]);}}

#read
static function call($p){
$twid=val($p,'twid'); $o=val($p,'mode'); $ret=''; $id=''; $bt='';
$u='https://mobile.twitter.com//status/'.$twid.'/'.$o;
if($twid)$bt=lk($u,picxt('link',$o),'btn',1).' ';
if($o=='retweets')$ret=self::rtw($p);
if($o=='likes')$ret=tag('div',['id'=>'twlk','class'=>'txth','contenteditable'=>'true'],'paste here').bj('cblk,,z|twcapt,lik|twid='.$twid.'|twlk',langp('ok'),'btsav');
if($o=='twits')$ret=tag('div',['id'=>'twlk','class'=>'txth','contenteditable'=>'true'],'paste here').bj('cblk,,z|twcapt,capt_twits|twid='.$twid.'|twlk',langp('ok'),'btsav');
if($o=='list')$ret=tag('div',['id'=>'twls','class'=>'txth','contenteditable'=>'true'],'paste here').bj('cblk,,z|twcapt,capt_list|twid='.$twid.'|twlk',langp('ok'),'btsav');
return $bt.$ret.div('','','cblk');}

static function com(){
return self::call($p);}

#content
static function content($p){
//self::install();//1111381590226042880
$twid=$p['p1']??''; $o=$p['p2']??'twits'; $ret='';
$bt=input('twid',$twid,20,lang('twid'));
$bt.=span(radio('mode',['twits','retweets','likes','list'],$o,1),'btn');
$bt.=bj(self::$cb.',,z|twcapt,call||twid,mode',langp('ok'),'btn');
$ret=self::call(['twid'=>$twid,'mode'=>$o]);
return $bt.div($ret,'pane',self::$cb);}
}
?>