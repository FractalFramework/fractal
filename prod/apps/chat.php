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

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create('chatxt',['bid'=>'int','rusr'=>'var','txt'=>'bvar'],1);
sql::create('chatmb',['bid'=>'int','muid'=>'var'],1);}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}

//read
static function js(){
head::add('jscode','chatlive();');}

static function headers(){
head::add('csscode','');}

#edit
static function del($p){$p['db2']=self::$db2; $p['db3']=self::$db3; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}

static function archive($p){$id=$p['del']??''; $act=$p['act']??'';
if($act=='remove')sql::up(self::$db,'old',1,$id);}

static function chatprivacy($p){
[$id,$pub,$bid,$rid,$sav]=vals($p,['id','pub','bid','rid','sav']);
if($sav)sql::up(self::$db,$id,$pub,$bid);
if(!$pub){$ic='user-secret'; $t='private';}else{$ic='users'; $t='public';}
$j=$rid.'|chat,chatprivacy|sav=1,id='.$id.',bid='.$bid.',rid='.$rid.',pub='.($pub?0:3);
return span(bj($j,ico(''.$ic,22).lang($t),'',['title'=>helpx('chat'.$t)]).hidden($id,$pub),'',$rid).' ';}

static function listers($id,$o=''){
$r=sql::join('muid,name','chatmb','login','muid','kv',['bid'=>$id]);
if($o){$ra=sql::inner('uid,name',self::$db,'login','uid','kv',['chat.id'=>$id]); $r=$ra+$r;}
if($r)return $r;}

static function invitations($p){$id=$p['id']??''; $ra=self::listers($id,1);
$ret=bj('invits|chat,chat_friends|id='.$id,div(langp('cancel')));
$r=sql::join('ab,name','tlex_ab','login','ab','kv','where usr="'.ses('uid').'"');
if($r)foreach($r as $k=>$v)if(!isset($ra[$k])){$bt=profile::com($v,1).div($v);
	$ret.=bj('invits|chat,save_friends|op=add,id='.$id.',uid='.$k,div($bt));}
return div($ret,'cicon');}

static function save_friends($p){$d=''; $r='';
$id=$p['id']??''; $uid=$p['uid']??''; $op=$p['op']??'';
if($id){if($op=='add')sql::sav('chatmb',[$id,$uid]); elseif($op=='del')sql::del('chatmb',['bid'=>$id,'muid'=>$uid]);}
return self::chat_friends($p);}

static function chat_friends($p){$id=$p['id']??''; $ret='';
$r=self::listers($id);
if($r)foreach($r as $k=>$v){$bt=profile::com($v,1).div($v);//avatar
	$ret.=bj('invits|chat,save_friends|op=del,id='.$id.',uid='.$k,div($bt),'',['title'=>lang('delete')]);}
return div($ret,'cicon');}

//form
static function form($p){$id=$p['id']??''; $ret='';
$ret=div(input('tit',$p['tit']??'',63,lang('title'),'',255));
$ret.=self::chatprivacy(['id'=>'pub','bid'=>$id,'pub'=>$p['pub']??'','rid'=>randid('tg')]);
$ret.=hidden('old','0');
if($id){
	$ret.=bj('invits|chat,invitations|id='.$id,langp('invite'),'btn');
	$ret.=div(self::chat_friends($p),'','invits');}
return div($ret,'','people');}

static function create($p){return parent::create($p);}
static function edit($p){return parent::edit($p);}

static function newchat($p){$ret=''; 
$cusr=$p['usr']??''; $cuid=$p['uid']??''; $usr=ses('usr'); $uid=ses('uid');
$t=$usr.', '.$cusr;
$id=sql('id',self::$db,'v',['tit'=>$t]);
if(!$id)$id=sql::sav(self::$db,[$uid,$t,0,0]);
if($id){sql::savif(self::$db3,[$id,$cuid]); self::chatntf($id);}
return self::play(['id'=>$id]);}

//ntf
static function clearntf($id){
$q=['typntf'=>'5','txid'=>$id,'4usr'=>ses('usr'),'state'=>1];
$ntf=sql('id','tlex_ntf','v',$q);
if($ntf)sql::up('tlex_ntf','state','0',$ntf);}

static function chatntf($id){
$r=self::listers($id,1);
//$r=sql('rusr','chatxt','k',['bid'=>$id]);
if($r)foreach($r as $k=>$v)if($k!=ses('uid')){
	$ra=['4usr'=>usrid($k),'byusr'=>ses('usr'),'typntf'=>5,'txid'=>$id];
	$ex=sql('id,state','tlex_ntf','rw',$ra);
	if(!$ex){$ra['state']=1; $rb[]=$ra;}
	elseif($ex[0] && !$ex[1])sql::up('tlex_ntf','state',1,$ex[0]);}
if(isset($rb))sql::sav2('tlex_ntf',$rb);}

#read
static function say($p){
$txt=trim($p['chtsav']); $id=$p['id']??'';
if($txt){$p['nid']=sql::sav('chatxt',[$id,ses('usr'),($txt)]);//unicode
	self::chatntf($id);
	if($p['nid'])return self::read($p);}}

static function delmsg($p){$bid=$p['bid'];
$p['id']=sql('bid','chatxt','v',$bid);//find bid
if(!isset($p['ok'])){$x=bj('chtbck|chat,read|id='.$p['id'],langp('cancel'),'btn');
	return bj('chtbck|chat,delmsg|ok=1,bid='.$bid,langp('confirm deleting'),'btdel').$x;}
if($bid)sql::del('chatxt',$bid);
return self::read($p);}

#play
static function roomusers($p){$id=$p['id']??''; $ret=''; $r=self::listers($id,1);
if($r)foreach($r as $k=>$v)$ret.=bj('popup|profile,call|sz=small,usr='.$v,$v);
return div($ret,'list');}

static function pane($r){$ret=''; $usr=ses('usr');
if($r)foreach($r as $k=>$v){$del=''; $sty='';
	$by=bubble('profile,call|usr='.$v[1].',sz=small',$v[1],'bold');
	$txt=$v[2];
	if(0)$txt=voc($txt,'chatxt-txt-'.$v[0]);//translate
	$txt=nl2br($txt);
	$date=span(date('d/m/Y H:i:s',$v[3]),'grey small');//relativetime2($v[3])
	if($v[1]==$usr)$del=bj('del'.$k.'|chat,delmsg|bid='.$v[0],ico('flash'),'',['id'=>'del'.$k]);
	$ret.=div($by.' '.$date.' '.$del.div($txt,'chatxt'));}
return $ret;}

static function read($p){$id=$p['id']??''; $nid=$p['nid']??'';
if($p['vu']??'')self::clearntf($id);
if($nid)$w='where id='.$nid;
else $w='where bid="'.$id.'" order by id desc limit 100';
$r=sql('id,rusr,txt,timeup','chatxt','',$w);
if($r)$r=array_reverse($r);
return self::pane($r);}

static function count($id){
return sql('count(id)','chatxt','v',['bid'=>$id]);}

static function play($p){$id=$p['id'];
//$head=btj(ico('close'),'Close(\'popup\');','btn');
$head=bj(self::$cb.'|chat,calltlx|',pic('back'),'btn');
$head.=bubble('chat,roomusers|id='.$id,lang('members'),'btn').' ';
$rt=sql::inner('name,tit,pub',self::$db,'login','uid','rw',$id);
$head.=span($rt[1],'bold').' '.bj(self::$cb.'|chat,call|id='.$id,'#'.$id,'small').' '.span($rt[0],'small').' ';
//if($rt[2]){$ic='user-secret'; $t='private';}else{$ic='users'; $t='public';}
$head.=span('('.helpx('chat'.($rt[2]?'private':'public')).')','small');
$txt=self::read(['id'=>$id,'vu'=>1]);
$ret=div($head,'chatform');
$ret.=div($txt,'chatcontent','chtbck');
$j='atend,chtbck,resetform,scrollBottom|chat,say|id='.$id.'|chtsav';
$ret.=areacall($j,'chtsav','');
$ret.=hidden('chtroom',$id);
return $ret;}

static function stream_notifs($p){$ret=''; $a=self::$a; $cb=self::$cb; $usr=ses('usr');
$r=sql('byusr,txid,dateup','tlex_ntf','',['typntf'=>5,'4usr'=>$usr,'state'=>1]);
if($r)foreach($r as $k=>$v){
	$tit=sql('tit',self::$db,'v',$v[1]);
	$bt=ico('arrow-right').' '.$tit.' '.span($v['2'],'date').' '.span(lang('by').' '.$v[0],'small');
	$ret.=bj($cb.'|'.$a.',call|id='.$v[1],div($bt),'active');}
return div($ret,'licon');}

static function stream_tlx($p){$ret=''; $cusr=$p['usr']??''; $cuid=$p['uid']??'';
$a=self::$a; $db=self::$db; $usr=ses('usr'); $uid=ses('uid'); $cb='cbck';//self::$cb;
if($cusr==$usr)$cusr='';
$w='where (uid="'.$uid.'" or muid="'.$uid.'")';
if($cusr)$w.=' and (uid="'.$cuid.'" or muid="'.$cuid.'")';
$r=sql::join($db.'.id,uid,tit,pub,name,state,dateup',$db,'login','uid','rr','
left join chatmb on chatmb.bid=chat.id
left join tlex_ntf on txid='.$db.'.id and 4usr="'.$usr.'" and typntf=5
'.$w.' group by '.$db.'.id order by tlex_ntf.up desc, state desc',0);
if($r)foreach($r as $k=>$v){$c=$v['state']==1?' active':'';
	if(($cusr && $v['pub']==0) or $usr){
	$rb=self::listers($v['id']); $with=$rb?implode(', ',$rb):'';
	if($v['name']==$usr)$ic='arrow-right'; else $ic='arrow-left';
	$lock=$v['pub']==3?ico('unlock'):ico('lock');
	$bt=ico($ic).' '.$v['tit'].' '.span($v['date'],'date').' '.span(lang('by').' '.$v['name'].' '.lang('with',1).' '.$with.' '.$lock,'small');
	$ret.=bj($cb.'|'.$a.',call|id='.$v['id'],div($bt));
	//$ret.=lku($a.'/'.$v['id'],$bt);
	}}
if(!$ret)$ret=help('nothing found');
if($cusr){
	$bt=ico('plus').' '.lang('create discussion').' '.span(lang('with',1).' '.$cusr,'small');
	$ret.=bj($cb.'|'.$a.',newchat|usr='.$cusr.',uid='.$cuid,div($bt),'');}
return div($ret,'licon');}

/*static function patch(){$r=sql('id,list',self::$db,'kv','');
foreach($r as $k=>$v){$rb=explode(',',$v);
if($rb)foreach($rb as $ka=>$va)if($va)sql::savif('chatmb',[$k,idusr($va)]);}}*/

static function discussion($p){$uid=ses('uid');
$id=sql('id',self::$db,'v',['tit'=>$p['id']]);
if(!$id){$id=sql::sav(self::$db,[$uid,$p['id'],1,0]);
	sql::sav('chatmb',[$id,$uid]);
	$uid2=sql('uid','tlex','v',$p['id']);
	if($uid2!=$uid){sql::sav('chatmb',[$id,$uid2]); self::chatntf($id);}}
else{$is=sql('id','chatmb','v',['bid'=>$id,'muid'=>$uid]);
	if(!$is)sql::sav('chatmb',[$id,$uid]);}
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