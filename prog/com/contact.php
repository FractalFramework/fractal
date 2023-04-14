<?php

class contact{
static $private=0;
static $db='contact';
static $a='contact';
static $open=1;
static $rt=['general','technical','groups','dev','ideas'];
	
static function install(){
sql::create(self::$db,['vuid'=>'int','cto'=>'int','cmail'=>'var','ctit'=>'var','ctxt'=>'text'],1);}

static function headers(){}
static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db]);}

static function del($p){
sql::del(self::$db,$p['id']);
return self::read($p);}

static function save($p){$nid='';
//[$cto,$mail,$tit,$txt]=vals($p,['cto','cmail','ctit','ctxt']);
['cto'=>$cto,'cmail'=>$mail,'ctit'=>$tit,'ctxt'=>$txt]=$p;
$r=[ses('uid'),$cto,$mail,$tit,$txt];
if($txt)$nid=sql::sav(self::$db,$r);
if($cto)$to=sql('mail','login','v','where id='.$cto);
if($nid && $mail && $to)mail::send($to,$tit,$txt,$mail,'txt');
if($nid && $mail)$ret=help('message posted','valid');
else $ret=help('message not posted','alert');
return $ret.' '.bj($p['rid'].'|contact,call',langp('back'),'btn');}

//builder
static function read($p){$rid=$p['rid']??'';
$r=sql::inner('contact.id,name,cto,cmail,ctit,ctxt,dateup',self::$db,'login','vuid','rr','where cto="'.ses('uid').'" order by contact.id desc');
$tmp='[[(ctit):b] : (name) ((cmail)) [(date)*class=date:span] (del)
[(ctxt)*class=txt:div]*class=menu:div]';
$ret=bj($rid.'|contact',pic('back'),'btn').br();
if($r)foreach($r as $k=>$v){
	if(is_numeric($v['ctit']))$v['ctit']=self::$rt[$v['ctit']];
	$v['del']=bj($rid.'|contact,del|rid='.$rid.',id='.$v['id'],langp('del'),'btdel');
	$ret.=gen::com($tmp,$v);}
return $ret;}

//com (tlex apps)
static function tit($p){$id=$p['id']??'';
return sql('ctit',self::$db,'v',$id);}

static function com($p){$id=$p['id']??'';
return self::content($p);}

//call (connectors)
static function call($p){
return self::content($p);}

//interface
static function content($p){
//self::install();
$rid=randid('md'); $ret='';
$bt=tag('h1','',lang('contact'));
if(ses('uid'))$mail=sql('mail','login','v','where id='.ses('uid')); else $mail='';
$ret.=input_pic('cmail',$mail,lang('from'),'mail').' ';
$ret.=hidden('cto','1');
$ret.=select('ctit',self::$rt,0,0,1).' ';
$ret.=bj($rid.'|contact,save|rid='.$rid.'|cmail,cto,ctit,ctxt',langp('send'),'btsav').' ';
if(auth(6))$ret.=bj($rid.'|contact,read|rid='.$rid,langp('view'),'btn');
$ret.=textarea('ctxt','',64,14,lang('message'));
return div($ret,'',$rid);}
}
?>