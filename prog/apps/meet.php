<?php

class meet extends appx{
static $private=1;
static $a='meet';
static $db='meet';
static $cb='mee';
static $cols=['tit','txt','loc','day','pub'];
static $typs=['var','bvar','var','date','int'];
static $tags=0;
static $open=1;

//install
static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create('meet_valid',['bid'=>'int','uid'=>'int','ok'=>'int'],1);}

static function admin($p){$p['o']='1'; return parent::admin($p);}
static function headers(){head::add('csscode','');}

#edit
static function gps($d){
$ret=input('loc',$d,28);
$ret=input('loc',$d,28);
$ret.=bj('cbkmap|map,request||loc',lang('ok',1),'btn');
return $ret.span('','','cbmap');}

static function modif($p){
$r=valk($p,['txt','day','loc']);
if($p['id'])sql::upd(self::$db,$r,$p['id']);
return self::edit($p);}

#editor
static function form($p){return parent::form($p);}

static function edit($p){
$p['collect']='meet_valid';
return parent::edit($p);}

static function collect($p){return parent::collect($p);}
static function del($p){return parent::del($p);}

//static function save($p){return parent::save($p);}
static function save($p){
$r=[ses('uid')]; foreach(self::$cols as $v)$r[]=val($p,$v,0);
if($p['txt'])$nid=sql::sav(self::$db,$r);
if(isset($nid))return div(self::play(['id'=>$nid]),'','mee'.$nid);}

//static function create($p){return parent::create($p);}
static function create($p){
$ret=input('day',date('Y-m-d',time()),8);
$ret.=input('loc','','32',lang('address')).br();
$ret.=textarea('txt','',70,4,lang('presentation'),'',216).br();
$ret.=bj(self::$cb.'|meet,save||txt,day,loc',lang('save'),'btsav');
return $ret;}

#check
static function checkDay($p){//p($p);
if($p['status']==1)sql::upd('meet_valid',['ok'=>2],$p['uid']);
elseif($p['status']==2)sql::upd('meet_valid',['ok'=>1],$p['uid']);
return self::rendezvous($p);}

#rendezvous
static function rendezvous($p){
$id=$p['id']; $uid=ses('uid');
$r=sql('id,uid,ok','meet_valid','rr','where bid='.$id);
if($r)foreach($r as $k=>$v){
	$name=profile::name($v['uid']);
	if($v['ok']==2){$c=' disactive'; $ico=ico('close').$name;}
	else{$c=' active'; $ico=ico('check').$name;}
	if($v['uid']!=$uid)$bt=tag('span','class=line opac'.$c,$ico);
	else $bt=bj('rv'.$id.'|meet,checkDay|id='.$id.',uid='.$v['id'].',status='.$v['ok'],$ico,'line'.$c);
	$rb[]=$bt;}
if(isset($rb))return scroll($rb,10,'400');}

#play
static function participation($p){
if($p['subscribe']=='ok')
	sql::sav('meet_valid',[$p['id'],ses('uid'),1]);
elseif($p['subscribe']=='ko')
	sql::del('meet_valid',$p['uid']);
return self::build($p);}

#pane
static function build($p){$id=$p['id']; $ret='';
$n=sql('count(id)','meet_valid','v','where bid='.$id);
$bt=$n. ' '.lang('participants');
//$ret=toggle('rv'.$id.'|meet,rendezvous|id='.$id,$bt,'nfo').' ';
$meet=self::rendezvous($p);
if($uid=ses('uid')){
	$uid=sql('id','meet_valid','v','where bid="'.$id.'" and uid="'.$uid.'"');
	$j='ev'.$id.'|meet,participation|id='.$id.',uid='.$uid;
	if(!$uid)$ret.=bj($j.',subscribe=ok',lang('participate'),'btsav').' ';
	else $ret.=bj($j.',subscribe=ko',lang('unsubscribe'),'btdel').' ';}
$ret.=div($meet,'','rv'.$id);
return div($ret,'','ev'.$id);}

#stream
static function play($p){$id=$p['id']; $rid=$p['rid']??'';
$r=sql('uid,txt,day,loc',self::$db,'ra',$id);
if(!$r)return lang('entry not exists');
$bt=lk('/meet/'.$id,ico('link'));
if($rid)$bt.=tlxf::publishbt(lang('use'),$id.':meet',$rid);
$ret=div($bt,'right');
if(val($p,'conn')=='no')$txt=$r['txt']; else $txt=nl2br($r['txt']);
$go=bj('mee'.$id.'|meet,play|id='.$id,'#'.$id,'btn');
//$go=lk('/meet/'.$id,pic('url').' #'.$id,'btn');
$name=profile::name($r['uid'],1);
if(strtotime($r['day'])<ses('time'))$c='alert '; else $c='valid ';
$date=span($r['day']?lang('date',1).' : '.$r['day']:lang('undefined'),$c.'date');
$gps=bj('popup|map,request|request='.$r['loc'],pic('gps').' '.$r['loc'],'btn');
$ret.=div($date.' '.$gps);//$go.' '.span(lang('by'),'small').' '.$name.' '.
$ret.=div($txt,'tit');
$ret.=self::build(['id'=>$id]);
return div($ret,'paneb');}

static function stream($p){return parent::stream($p);}
static function call($p){return parent::call($p);}
static function tit($p){return parent::tit($p);}

//com (edit)
static function com($p){return parent::com($p);}

//interface
static function content($p){
//self::install();
return parent::content($p);}
}

?>