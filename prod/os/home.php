<?php
class home{
static $private=0;
static $a='';
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

//menus
static function menus($p){$c='react'; $ret=''; $rt=[]; $ko=1;
[$uid,$usr,$vu,$role,$op,$app]=vals($p,['uid','usr','vu','role','opn','app']);
$vu=1; $mode=',mode='.($vu?'private':'public'); $my=$vu?'my ':''; $my='';
if($app)$op=$app;//opening or app
$rp=['data-prmtm'=>'no'];//continuous scrolling
$rc=[];//['onmouseup'=>atj('closediv','nav2')];
//apps
$rp['title']=helpx('mac_apps'); $rp['data-u']='apps'; $c=active($op,'apps');
$rt[]=toggle('cbck|tlxf,apps|b=public',langph('apps'),$c,$rp,1,$ko);
$rp['title']=helpx('goodies'); $rp['data-u']='goodies'; $c=active($op,'goodies');
$rt[]=toggle('cbck|tlxf,apps|b=goodies',langph('goodies'),$c,$rp,'',$ko);
//$rp['title']=helpx('mac_tlex');
//$rt[]=toggle('cbck|home,showusrs',langph('global'),active($op,'global'),$rp,'',$ko);
if($vu && $role==5){$rp['title']=helpx('pinned'); $c=active($op,'site');
	$rt[]=toggle('cbck|site,call|usr='.$usr.',uid='.$uid,langph('webpage'),'',$rp,1,$ko);}
$rp['title']=helpx('mac_desktop'); $c=active($op,'docs');//desktop
$rt[]=toggle('cbck|desktop|dir=/documents,cuid='.$uid,langph('documents'),$c,$rp,'',$ko);
$rt[]=toggle('cbck|tlex,tag2bt|usr='.$usr.$mode,langph($my.'tags'),$c,$rp,'',$ko);
$rt[]=toggle('cbck|friends',langph('friends'),$c,$rp,'',$ko);
$rp['title']=helpx('mac_explorer'); $rp['data-u']=''; $c=active($op,'datas');
$rt[]=toggle('cbck|explorer',langph('datas'),$c,$rp,'',$ko);
if(auth(6))$rt[]=toggle('cbck|loadapp,com|tg=cbck,bt=1',langph('loader'),$c,$rp,'',$ko);
//$rp['title']=''; $rt[]=toggle('cbck|profile,edit',langph('profile'),'',$rp,'',$ko);
return div(implode('',$rt),'lisb');}

static function call($p){
$p=root::prm($p); $nav2=''; $nav3=''; $ret='';
$op=$p['op']??''; if(!$op)$op='tlex';
switch($op){
case('menu'):$nav2=self::menus($p); $ret=tlxf::apps(['b'=>'public']); break;
//$ret=desktop::content(['dir'=>'/documents','cuid'=>ses('cuid')]);
case('posts'):$nav2=tlxf::menu($p); $ret=tlex::read($p); break;
case('my'):$nav2=tlxf::menu($p); $ret=tlex::read($p); return json_enc([$nav2,$ret]); break;
case('new'):$nav2=tlxf::editor($p); $nav3=apps::call($p); return $nav2.$nav3; break;
case('app'):$ret=root::call($p);break;}
return root::template($nav2,$nav3,$ret);}

static function com($p){
$ret=root::call($p);
return $ret;}

static function nav($p){$c='react'; $rt=[];
//$rt[]=toggle('cbck|root,com|usr='.$p['cusr'],ico('user').' '.$p['cusr'],$c);
$rt[]=toggle('main|home,call|op=posts',langp('posts'),$c,['data-u'=>'/'],1,1);
$rt[]=toggle('main|home,call|op=menu',langp('contents'),$c,['data-u'=>'/'],'',1);
//if(ses('usr'))$rt[]=toggle('main|home,call|op=new',langp('new'),$c,[],'',1);
//$rt[]=toggle('main|tlxf,apps|b=public',langph('apps'),$c,[],'',1);
if(ses('usr'))$rt[]=toggle('main|profile,edit|op=identity',ico('gears').ses('usr'),$c,['data-u'=>'/profile'],'',1);
else $rt[]=toggle('main|login,com',langp('login'),$c,['data-u'=>'/login'],'',1);
return implode('',$rt);}

static function content($p){
//self::install();
$mnu=self::nav($p);
$ret=root::com($p);
return div(div($mnu,'lisb','nav').div($ret,'','main'),'container').hidden('prmtm',root::$pm);}
}

?>