<?php

class chat extends appx{	
static $private=1;
static $a='chat';
static $db='chat';
static $db2='chatxt';
static $db3='chatmb';
static $cb='cbck';
static $cols=['tit','uid','pub','old'];
static $typs=['var','int','int','int'];
static $tags=1;
static $boot;

function __construct(){
$r=['a','db','cb','cols'];
foreach($r as $v)parent::$$v=self::$$v;}

static function boot(){$a=self::$a;
if(self::boot==null)self::$boot=new $a;}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sqlcreate('chatxt',['bid'=>'int','rusr'=>'var','txt'=>'bvar'],1);
sqlcreate('chatmb',['bid'=>'int','muid'=>'var'],1);}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}

//read
static function relativetime($sec){$ret=lang('there_was').' '; $sec=time()-$sec;
if($sec>84600*365){$n=floor($sec/84600/365); return $ret.$n.' '.langs('year',$n,1);}
if($sec>84600*30){$n=floor($sec/84600/30); return $ret.$n.' '.langs('month',$n,1);}
elseif($sec>84600){$n=floor($sec/84600); return $ret.$n.' '.langs('day',$n,1);}
elseif($sec>3600){$n=floor($sec/3600); return $ret.$n.' '.langs('hour',$n,1);}
elseif($sec>60){$n=floor($sec/60); return $ret.$n.' '.langs('minute',$n,1);}
else return $ret.$sec.' s';}

static function injectJs(){
add_head('jscode','chatlive();');}

static function headers(){
add_head('csscode','');}

#edit
static function del($p){$p['db2']=self::$db2; $p['db3']=self::$db3; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}

static function archive($p){$id=val($p,'del'); $action=val($p,'act');
if($act=='remove')sqlup(self::$db,'old',1,$id);}

static function chatprivacy($p){$pub=$p['pub']; $id=$p['id']; $bid=$p['bid']; $rid=$p['rid'];
if(val($p,'sav'))sqlup(self::$db,$id,$pub,$bid);
if(!$pub){$ic='user-secret'; $t='private';}else{$ic='users'; $t='public';}
$j=$rid.'|chat,chatprivacy|sav=1,id='.$id.',bid='.$bid.',rid='.$rid.',pub='.($pub?0:3);
return span(bj($j,ico(''.$ic,22).lang($t),'',['title'=>helpx('chat'.$t)]).hidden($id,$pub),'',$rid).' ';}

static function listers($id,$o=''){
$r=sqljoin('muid,name','chatmb','login','muid','kv',['bid'=>$id]);
if($o){$ra=sqlin('uid,name',self::$db,'login','uid','kv',['chat.id'=>$id]); $r=$ra+$r;}
if($r)return $r;}

static function invitations($p){$id=$p['id']??''; $ra=self::listers($id,1);
$ret=bj('invits|chat,chat_friends|id='.$id,langp('cancel'),'cicon').' ';
$r=sqljoin('ab,name','tlex_ab','login','ab','kv','where usr="'.ses('uid').'"');
if($r)foreach($r as $k=>$v)if(!isset($ra[$k])){$bt=profile::com($v,1).div($v);
	$ret.=bj('invits|chat,save_friends|op=add,id='.$id.',uid='.$k,$bt,'cicon').' ';}
return $ret;}

static function save_friends($p){$d=''; $r='';
$id=$p['id']??''; $uid=$p['uid']??''; $op=$p['op']??'';
if($id){if($op=='add')sqlsav('chatmb',[$id,$uid]); elseif($op=='del')sqldel('chatmb',['bid'=>$id,'muid'=>$uid]);}
return self::chat_friends($p);}

static function chat_friends($p){$id=$p['id']??''; $ret='';
$r=self::listers($id);
if($r)foreach($r as $k=>$v){$bt=profile::com($v,1).div($v);//avatar
	$ret.=bj('invits|chat,save_friends|op=del,id='.$id.',uid='.$k,$bt,'cicon',['title'=>lang('delete')]);}
return $ret;}

//form
static function form($p){$id=$p['id']??''; $ret='';
$ret=div(input('tit',$p['tit']??'',63,lang('title'),'',255));
$ret.=self::chatprivacy(['id'=>'pub','bid'=>$id,'pub'=>val($p,'pub'),'rid'=>randid('tg')]);
$ret.=hidden('old','0');
if($id){
	$ret.=bj('invits|chat,invitations|id='.$id,langp('invite'),'btn');
	$ret.=div(self::chat_friends($p),'','invits');}
return div($ret,'','people');}

static function create($p){return parent::create($p);}
static function edit($p){return parent::edit($p);}

static function newchat($p){$ret=''; 
$cusr=val($p,'usr'); $cuid=$p['uid']??''; $usr=ses('user'); $uid=ses('uid');
$t=$usr.', '.$cusr;
$id=sql('id',self::$db,'v',['tit'=>$t]);
if(!$id)$id=sqlsav(self::$db,[$uid,$t,0,0]);
if($id){sqlsavif(self::$db3,[$id,$cuid]); self::chatntf($id);}
return self::play(['id'=>$id]);}

//ntf
static function clearntf($id){
$q=['typntf'=>'5','txid'=>$id,'4usr'=>ses('user'),'state'=>1];
$ntf=sql('id','tlex_ntf','v',$q);
if($ntf)sqlup('tlex_ntf','state','0',$ntf);}

static function chatntf($id){
$r=self::listers($id,1);
//$r=sql('rusr','chatxt','k',['bid'=>$id]);
if($r)foreach($r as $k=>$v)if($k!=ses('uid')){
	$ra=['4usr'=>usrid($k),'byusr'=>ses('user'),'typntf'=>5,'txid'=>$id];
	$ex=sql('id,state','tlex_ntf','rw',$ra);
	if(!$ex){$ra['state']=1; $rb[]=$ra;}
	elseif($ex[0] && !$ex[1])sqlup('tlex_ntf','state',1,$ex[0]);}
if(isset($rb))sqlsav2('tlex_ntf',$rb);}

#read
static function say($p){
$txt=trim(val($p,'chtsav')); $id=$p['id']??'';
if($txt){$p['nid']=sqlsav('chatxt',[$id,ses('user'),($txt)]);//unicode
	self::chatntf($id);
	if($p['nid'])return self::read($p);}}

static function delmsg($p){$id=$p['id'];
if(!isset($p['ok']))
	return bj('chtbck,,x|chat,delmsg|ok=1,id='.$id,langp('confirm deleting'),'btdel');
$p['id']=sql('bid','chatxt','v',$id);//find bid
if($id)sqldel('chatxt',$id);
return self::read($p);}

#play
static function roomusers($p){$id=$p['id']??''; $ret=''; $r=self::listers($id,1);
if($r)foreach($r as $k=>$v)$ret.=bj('popup|profile,call|sz=small,usr='.$v,$v);
return div($ret,'list');}

static function pane($r){$ret='';
if($r)foreach($r as $k=>$v){$del=''; $sty='';
	//$clr=profile::init_clr(['usr'=>$v[1]]); $txclr=clrneg($clr,1);
	//$sty='background-color:#'.$clr.'; color:#'.$txclr.';';
	$by=bubble('profile,call|usr='.$v[1].',sz=small',$v[1],'small');
	$user=tag('li',['class'=>'chatprofile','style'=>$sty],$by); $txt=$v[2];
	if(0)$txt=voc($txt,'chatxt-txt-'.$v[0]);//translate
	$txt=tag('li',['class'=>'chatpane'],nl2br($txt));
	$date=tag('div',['class'=>'chatdate'],self::relativetime($v[3]));
	if($v[1]==ses('user')){$css='row-reverse';//
		$bt=bj('del'.$k.'|chat,delmsg|id='.$v[0],ico('flash'),'',['id'=>'del'.$k]);
		$del=tag('li',['class'=>'chatdate'],$bt);}
	else $css='row ';
	$ret.=div($user.$txt.$date.$del,'flex-container '.$css);}
return $ret;}

static function read($p){$id=$p['id']??''; $nid=val($p,'nid');
if(val($p,'vu'))self::clearntf($id);
if($nid)$w='where id='.$nid;
else $w='where bid="'.$id.'" order by id desc limit 100';
$r=sql('id,rusr,txt,timeup','chatxt','',$w);
//if($r)$r=array_reverse($r);
return self::pane($r);}

static function play($p){$id=$p['id'];
//$head=btj(ico('close'),'Close(\'popup\');','btn');
$head=bj(self::$cb.'|chat,calltlx|',pic('back'),'btn');
$head.=bubble('chat,roomusers|id='.$id,lang('members'),'btn').' ';
$rt=sqlin('name,tit,pub',self::$db,'login','uid','rw',$id);
$head.=span($rt[1],'bold').' '.bj(self::$cb.'|chat,call|id='.$id,'#'.$id,'small').' '.span($rt[0],'small').' ';
//if($rt[2]){$ic='user-secret'; $t='private';}else{$ic='users'; $t='public';}
$head.=span('('.helpx('chat'.($rt[2]?'public':'private')).')','small');
$txt=self::read(['id'=>$id,'vu'=>1]);
$ret=div($head,'chatform');
$j='begin,chtbck,resetform,scrollTop|chat,say|id='.$id.'|chtsav';
$ret.=areacall($j,'chtsav','','chatarea',lang('message'));
$ret.=div($txt,'chatcontent','chtbck');
$ret.=hidden('chtroom',$id);
return $ret;}

static function stream_notifs($p){$ret=''; $a=self::$a; $cb=self::$cb; $usr=ses('user');
$r=sql('byusr,txid,dateup','tlex_ntf','',['typntf'=>5,'4usr'=>$usr,'state'=>1]);
if($r)foreach($r as $k=>$v){
	$tit=sql('tit',self::$db,'v',$v[1]);
	$bt=ico('arrow-right').' '.$tit.' '.span($v['2'],'date').' '.span(lang('by').' '.$v[0],'small');
	$ret.=bj($cb.'|'.$a.',call|id='.$v[1],$bt,'licon active');}
return $ret;}

static function stream_tlx($p){$ret=''; $cusr=val($p,'usr'); $cuid=$p['uid']??'';
$a=self::$a; $db=self::$db; $usr=ses('user'); $uid=ses('uid'); $cb='cbck';//self::$cb;
if($cusr==$usr)$cusr='';
$w='where (uid="'.$uid.'" or muid="'.$uid.'")';
if($cusr)$w.=' and (uid="'.$cuid.'" or muid="'.$cuid.'")';
$r=sqljoin($db.'.id,uid,tit,pub,name,state,dateup',$db,'login','uid','rr','
left join chatmb on chatmb.bid=chat.id
left join tlex_ntf on txid='.$db.'.id and 4usr="'.$usr.'" and typntf=5
'.$w.' group by '.$db.'.id order by tlex_ntf.up desc, state desc',0);
if($r)foreach($r as $k=>$v){$c=$v['state']==1?' active':'';
	if(($cusr && $v['pub']==0) or $usr){
	$rb=self::listers($v['id']); $with=$rb?implode(', ',$rb):'';
	if($v['name']==$usr)$ic='arrow-right'; else $ic='arrow-left';
	$lock=$v['pub']==3?ico('unlock'):ico('lock');
	$ret.=bj($cb.'|'.$a.',call|id='.$v['id'],ico($ic).' '.$v['tit'].' '.span($v['date'],'date').' '.span(lang('by').' '.$v['name'].' '.lang('with',1).' '.$with.' '.$lock,'small'),'licon'.$c);}}
if(!$ret)$ret=help('nothing found');
if($cusr)$ret.=bj($cb.'|'.$a.',newchat|usr='.$cusr.',uid='.$cuid,ico('plus').' '.lang('create discussion').' '.span(lang('with',1).' '.$cusr,'small'),'licon');
return div($ret,'list');}

/*static function patch(){$r=sql('id,list',self::$db,'kv','');
foreach($r as $k=>$v){$rb=explode(',',$v);
if($rb)foreach($rb as $ka=>$va)if($va)sqlsavif('chatmb',[$k,idusr($va)]);}}*/

static function discussion($p){$uid=ses('uid');
$id=sql('id',self::$db,'v',['tit'=>$p['id']]);
if(!$id){$id=sqlsav(self::$db,[$uid,$p['id'],1,0]);
	sqlsav('chatmb',[$id,$uid]);
	$uid2=sql('uid','tlex','v',$p['id']);
	if($uid2!=$uid){sqlsav('chatmb',[$id,$uid2]); self::chatntf($id);}}
else{$is=sql('id','chatmb','v',['bid'=>$id,'muid'=>$uid]);
	if(!$is)sqlsav('chatmb',[$id,$uid]);}
$ret=self::play(['id'=>$id]);
return $ret;}

static function stream($p){
//return self::stream_tlx($p);
$p['t']=self::$cols[0];
$ret=self::stream_notifs($p);
$ret.=parent::stream($p);
return $ret;}

#interfaces
static function tit($p){
$p['t']=self::$cols[0];//first column is title
return parent::tit($p);}

//call (read)
static function access($p){
$ra=sql('id,uid,pub',self::$db,'ra',$p['id']);
if(!$ra)return help('chat not exists'); $uid=ses('uid');
if($ra['uid']!=$uid && $ra['pub']==1){
	sesr('chat',$ra['id'],$ra['uid']);//owner
	$ex=sql('id','chatmb','v',['bid'=>$p['id'],'muid'=>$uid]);
	if(!$ex)return div(pic('private').' '.helpx('private chat'),'board');}}

static function calltlx($p){$id=$p['id']??'';
return div(self::stream_tlx($p),'',self::$cb);}

static function call($p){$id=$p['id']??'';
$er=self::access($p); if($er)return $er;
return div(self::play($p),'chatwrapper',self::$cb.$id);}

//com (write)
static function com($p){
return parent::com($p);}

//interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>