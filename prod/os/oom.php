<?php
class oom{
static $private=0;
static $a='oom';
static $cb='cbck';
static $title='home';
static $descr='';
static $image='';
static $home='';

static function install(){}

static function admin($p){return;}

static function js(){}

static function headers(){
head::prop('og:title',addslashes(self::$title));
head::prop('og:description',addslashes(self::$descr));
head::prop('og:image',self::$image);
head::add('jslink','/js/tlex.js');
head::add('jscode',self::js());}

#pub
static function showusrs($p){$usr=$p['usr']??'';
$mail=sql('mail','login','v','where name="'.$usr.'"');
$r=sql::inner('name','profile','login','puid','rv','where mail="'.$mail.'" and name!="'.$usr.'" and auth>1 and privacy=0');
if($r){foreach($r as $v)$ret[]=profile::small(['usr'=>$v]);
return implode('',$ret);}}

static function pages(){$r=sql::inner('name','profile','login','puid','rv','where role=5 and auth>1 and privacy=0 order by login.up desc');
if($r){foreach($r as $v)$ret[]=profile::small(['usr'=>$v]);
return implode('',$ret);}}

#board
static function subscribers($p){
$rp=['data-prmtm'=>'no'];//stop continuous scrolling!
$usr=$p['usr']??''; $uid=$p['uid']??''; $c=''; $ret=''; $ko=1;
$n1=sql('count(id)','tlex_ab','v',['usr'=>$uid]);
$n2=sql('count(id)','tlex_ab','v',['ab'=>$uid]);
//$n3=sql('count(id)','tlex_ntf','v',['4usr'=>$usr,'state'=>1,'typntf'=>5]);
//$n4=sql('count(id)','tlex_ntf','v',['4usr'=>$usr,'state'=>1,'typntf'=>4]);
$bt=langph('subscriptions').' '.span($n1?$n1:'','nbntf react');//
$ret.=toggle('cbck|friends,subscriptions|usr='.$usr,$bt,$c,[],'',$ko);
$role=sql('role','profile','v','where pusr="'.$usr.'"');
$bt=langph($role==3?'members':'subscribers').' '.span($n2,'nbntf react','tlxsub');//.($n4?' active':'')
$ret.=toggle('cbck|friends,subscribers|usr='.$usr,$bt,$c,[],'',$ko);
//$ret.=hidden('tlxabsnb',$n2);//.hidden('tlxsubnb',$n1)
//$ret.=toggle('cbck|oom,showusrs|usr='.$usr,langpi('other accounts'),$c);
return $ret;}

static function dashboard($p){$c=''; $ret=''; $rt=''; $ko=1; //pr($p);
[$uid,$usr,$vu,$role,$op,$app]=vals($p,['uid','usr','vu','role','opn','app']);
if($app)$op=$app;//opening or app
$rp=['data-prmtm'=>'no'];//continuous scrolling
$rc=[];//['onmouseup'=>atj('closediv','appnav')];
/*if($vu){$bt=langph('my posts'); $noab=1; $c=$op=='posts'&&$role!=5?1:0;}
else{$bt=langph('publications'); $noab=0; $c=$op=='posts'?1:0;}
$rt.=tlex::loadtm('tm='.$usr.',noab='.$noab,$bt.span('','nbntf','tlxrec'),$c);*/
//$rp['title']=helpx('mac_tlex');
//$rt.=toggle('cbck|oom,showusrs',langph('global'),active($op,'global'),$rp,'',$ko);
if($vu && $role==5){$rp['title']=helpx('pinned'); $c=active($op,'site');
	$rt.=toggle('cbck|site,call|usr='.$usr.',uid='.$uid,langph('webpage'),'',$rp,1,$ko);}
$rp['title']=helpx('mac_desktop'); $c=active($op,'docs');//desktop
$rt.=toggle('cbck|desktop|dir=/documents,cuid='.$uid,langph('documents'),$c,$rp,'',$ko);
$rt.=self::subscribers($p);
//apps
$rp['title']='';
if(!$vu){
	$n0=sql('count(id)','tlex_ntf','v','where 4usr="'.$usr.'" and state=1 and typntf in (1,2,3)');
	$bt=langph('notifications').' '.span($n0?$n0:'','nbntf','tlxntf');//notifs
	$rt.=tlex::loadtm('ntf=1',$bt,'');//,$rc
	//$rt.=toggle('|tlex,mntsbt|usr='.$usr,langph('mentions'),$c,$rp,'',$ko);
	$rt.=tlex::loadtm('mnt=1',langph('mentions'),'',$rc);//mention
	$bt=langph('messages').span('','nbntf','tlxcht'); $c=active($op,'chat');//$n3?$n3:
	$rt.=toggle('cbck|chat,calltlx|usr='.$usr.',uid='.$uid.',headers=1',$bt,$c,$rp,'',$ko);//if(auth(6))
	$rt.=tlex::loadtm('likes=1',langph('likes'),'',$rc);//likes
	$rp['title']=helpx('mac_apps'); $rp['data-u']='apps'; $c=active($op,'apps');
	$rt.=toggle('cbck|tlxf,apps|b=public',langph('apps'),$c,$rp,'',$ko);
	$rp['title']=helpx('goodies'); $rp['data-u']='goodies'; $c=active($op,'goodies');
	$rt.=toggle('cbck|tlxf,apps|b=goodies',langph('goodies'),$c,$rp,'',$ko);
	//$rt.=toggle('cbck|oom,demo|ty=goodies',langph('goodies'),'');
	$rp['title']=helpx('mac_explorer'); $rp['data-u']=''; $c=active($op,'datas');
	$rt.=toggle('cbck|explorer',langph('datas'),$c,$rp,'',$ko);
	//$rp['title']=''; $rt.=toggle('cbck|profile,edit',langph('profile'),'',$rp,'',$ko);
}
$ret.=div($rt,'lish');
return div($ret,'dashboard');}

static function dashboard2($p){
$o=isset($p['app'])?0:1;
$bt=toggle('|core,help|ref=tlex_welcome,css=paneb',langph('home'),'',[],$o);//lk is better
$bt.=toggle('cbck|login,com',langph('login'),'');
$ret=div($bt,'lish');
$bt=toggle('cbck,,z|tlex,read',langph('telex'),'',[],0);
$bt.=toggle('cbck|oom,demo|',langph('apps'),'',[],1);
$bt.=toggle('cbck|oom,demo|ty=goodies',langph('goodies'),'');
$ret.=div($bt,'lish');
return div($ret,'dashboard');}

static function appnav($p){$nav='';
//if(!$p)$p=root::prm($p);
if(auth(6))$nav=bj('cbck|loadapp,com|tg=cbck,bt=1',langph('loader'),'',[],'',0);
if($p['id'])$ret=div(lk('/',langp('home')),'dashboard lish');
elseif($p['cusr'])$ret=self::dashboard($p);
else $ret=self::dashboard2($p);
return self::template($nav,$ret);}

static function template($nav,$ret){
return div($nav,'lisb','appnav').div($ret,'','main');}

static function call($p){
$p=root::prm($p);
$op=$p['op']??''; $nav=''; if(!$op)$op='tlex';
switch($op){
case('menu'):$ret=self::appnav($p);break;
case('tlex'):$nav=tlxf::menu($p); $ret=tlex::read($p);break;
case('new'):$nav=apps::call($p); $ret=tlxf::editor($p);break;
case('app'):$ret=root::call($p);break;}
return self::template($nav,$ret);}

static function com($p){
$ret=root::call($p);
return $ret;}

static function menu($p){$c='react'; $c='';
//$ret.=bj('cbck|root,com|usr='.$p['cusr'],ico('user').' '.$p['cusr'],$c).' ';
$ret=bj('cbck|oom,call|op=tlex',langp('home'),$c).' ';
$ret.=bj('cbck|oom,call|op=menu',langp('menu'),$c).' ';
if(ses('usr'))$ret.=bj('cbck|oom,call|op=new',langp('new'),$c).' ';
return $ret;}

static function content($p){
//self::install();
$mnu=self::menu($p);
$ret=root::com($p);
return div(div($mnu,'lisb','nav').div($ret,'','cbck'),'container').hidden('prmtm',root::$pm);}
}

?>