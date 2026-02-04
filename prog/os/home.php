<?php
class home{
static $private=0;
static $a='';
static $cb='cbck';
static $title='';
static $descr='';
static $image='';
static $home='';

static function install(){}
static function admin($p){return;}
static function js(){}
static function headers(){
root::$title=self::$title;
root::$descr=self::$descr;
root::$image=self::$image;
head::add('jslink','/js/tlex.js');
head::add('jscode',self::js());}

#pub
static function showusrs($p){$usr=$p['usr']??'';
$mail=sql('mail','login','v',['name'=>$usr]);
$r=sql::inner('name','profile','login','puid','rv','where mail="'.$mail.'" and name!="'.$usr.'" and auth>1 and privacy=0');
if($r){foreach($r as $v)$ret[]=profile::small(['usr'=>$v]);
return implode('',$ret);}}

static function pages(){$r=sql::inner('name','profile','login','puid','rv','where role=5 and auth>1 and privacy=0 order by login.up desc');
if($r){foreach($r as $v)$ret[]=profile::small(['usr'=>$v]);
return implode('',$ret);}}

//menus
static function nav3($p){$c='react'; $ret=''; $rt=[]; $ko=1;
[$uid,$usr,$vu,$role,$op,$app]=vals($p,['uid','usr','vu','role','opn','app']);
$vu=1; $mode=',mode='.($vu?'private':'public'); $my=$vu?'my ':''; $my='';
if($app)$op=$app;//opening or app
$rp=['data-prmtm'=>'no'];//continuous scrolling
$rt[]=toggle('cbck|tlex,tag2bt|usr='.$usr.$mode,langph($my.'tags'),$c,$rp,'',$ko);
$rt[]=toggle('cbck|friends',langph('friends'),$c,$rp,'',$ko);
return div(implode('',$rt),'lisb');}

static function nav2($p){$c='react'; $ret=''; $rt=[]; $ko=1; //pr($p);
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
//$rt[]=toggle('cbck|friends',langph('friends'),$c,$rp,'',$ko);
$rp['title']=helpx('mac_explorer'); $rp['data-u']=''; $c=active($op,'datas');
$rt[]=toggle('cbck|explorer',langph('datas'),$c,$rp,'',$ko);
if(auth(6))$rt[]=toggle('cbck|loadapp,com|tg=cbck,bt=1',langph('loader'),$c,$rp,'',$ko);
//$rp['title']=''; $rt[]=toggle('cbck|profile,edit',langph('profile'),'',$rp,'',$ko);
return div(implode('',$rt),'lisb');}

static function nav1($p){$c='react'; $rt=[]; $op=$p['op']??($p['opn']??''); $tg='nav2;nav3;cbck';
//$rt[]=toggle($tg.'|root,com|usr='.$p['cusr'],ico('user').' '.$p['cusr'],$c);
$rt[]=toggle($tg.'|home,com2|op=posts',langp('posts'),$c,['data-u'=>'/'],active($op,'posts'),1);
$rt[]=toggle($tg.'|home,com2|op=contents',langp('contents'),$c,['data-u'=>'/'],active($op,'contents'),1);
//if(ses('usr'))$rt[]=toggle($tg.'|home,com2|op=new',langp('new'),$c,[],'',1);
//$rt[]=toggle($tg.'|tlxf,apps|b=public',langph('apps'),$c,[],'',1);
$bt=ico('gears').ses('usr');
if(ses('usr'))$rt[]=toggle($tg.'|profile,comedit|op=identity',$bt,$c,['data-u'=>'/profile'],'',1);
else $rt[]=toggle($tg.'|login,com',langp('login'),$c,['data-u'=>'/login'],'',1);
$rt[]=toggle($tg.'|friends,call|op=reciproques',langph('friends'),$c,[],'',1);
return implode('',$rt);}

static function tlex($p){
root::$out['pm']='tm='.$p['tm'].',noab='.$p['noab'];
return app('tlex',$p+['headers'=>1],'read');}

static function com($p){
if(!isset($p['badid']))$p=root::prm($p); $op=$p['op']??($p['opn']??'');
$nav1=self::nav1($p); $nav2=''; $nav3=''; $ret='';
switch($op){
case('posts'):$nav2=tlxf::menu($p); $ret=self::tlex($p); break;
case('contents'):$nav2=self::nav2($p); $ret=tlxf::apps(['b'=>'public']); break;
case('desk'):$nav2=self::nav2($p); $ret=desktop::content(['dir'=>'/documents','cuid'=>ses('cuid')]); break;
case('my'):$nav2=tlxf::menu($p); $ret=tlex::read($p); break;
case('new'):$nav3=tlxf::editor($p); $nav3.=apps::call($p); break;
case('app'):$ret=app($p['app'],$p+['headers'=>1],''); break;}
return [$nav1,$nav2,$nav3,$ret];}

static function com2($p){$op=$p['op']??($p['opn']??'');
[$nav1,$nav2,$nav3,$ret]=self::com($p); //$jr=explode(';',get('json'));
return ['nav2'=>$nav2,'nav3'=>$nav3,'cbck'=>$ret];}

static function call($p){
[$nav1,$nav2,$nav3,$ret]=self::com($p);
root::$out['nav1']=$nav1;
root::$out['nav2']=$nav2;
root::$out['nav3']=$nav3;
//root::$out['cbck']=$ret;
return $ret;}

static function content($p){
return self::call($p);}
}

?>