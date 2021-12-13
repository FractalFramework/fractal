<?php
class home{
static $private=0;
static $a='mac';
static $db='os';
static $cb='cbck';
static $title='home';
static $descr='';
static $image='';

static function install(){
sqlcreate(self::$db,['uid'=>'int','app'=>'var'],1);}

static function admin($p){return;
$usr=ses('user')?ses('user'):'profile'; $a=self::$a;
//$r[]=[$usr,'j','popup|profile,edit','','edit profile'];
//$r[]=['','bub','core,help|ref='.$a.'_app','question-circle-o',''];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f='.$a,'code','Code'];
if(auth(6)){
	$r[]=['admin/identity','pop','admin_lang,open|ref='.$a.',app='.$a,'lang','name'];
	$r[]=['admin/identity','pop','admin_help,open|ref='.$a,'help','name'];
	$r[]=['admin/identity','pop','admin_help,open|ref='.$a.'_app','help','help'];
	$r[]=['admin/identity','pop','admin_icons,open|ref='.$a,'picto','pictos'];}
return $r;}

static function injectJs(){
if(ses('uid'))return '
var usr="'.ses('user').'";
document.addEventListener("visibilitychange",tlexlive);
setTimeout("tlexlive()",1000);
';}

static function headers(){
add_prop('og:title',addslashes(self::$title));
add_prop('og:description',addslashes(self::$descr));
add_prop('og:image',self::$image);
add_head('jslink','/js/tlex.js');
add_head('jscode',self::injectJs());}

#mac
static function init(){$uid=ses('uid');
$r=['art','stext','tabler','slide','movie','audio','ideas'];//,'sticker','poll'
foreach($r as $k=>$v)$rb[]=['uid'=>$uid,'app'=>$v];
sqlsav2(self::$db,$rb);
return $r;}

static function reinit($p){$uid=ses('uid');
sqldel(self::$db,$uid,'uid'); self::init();
return self::prefs();}

static function add($p){$uid=ses('uid');
sqlsav(self::$db,['uid'=>$uid,'app'=>$p['app']]);
return self::prefs();}

static function del($p){
$id=sql('id',self::$db,'v',['uid'=>ses('uid'),'app'=>$p['app']]);
if($id)sqldel(self::$db,$id);
return self::prefs();}

static function prefs(){$ret=''; $rt=[]; $c='';
$r=self::build(); $rb=applist::build('public');
//$rp['data-jb']='cbck|home,com|prefs=1';
$rp['data-jb']='dboard|home,play|';
//$ret=bj('cbck|home,com',ico('check').lang('ok'),$c);
$ret.=bj('cbck|home,reinit',langp('reset'),'btdel',$rp);
foreach($rb as $k=>$v){$bt=helpx($k); $cat=lang(strend($v,'/')); $op=self::appbt($k,'appicon');
	if(in_array($k,$r)){$go='del'; $ic='toggle-on';} else{$go='add'; $ic='toggle-off';}
	$rp['title']=helpx($k.'_app');
	$rt[$cat][$bt]=div($op.' '.bj('cbck|home,'.$go.'|app='.$k,ico($ic).$bt,$c,$rp));}
foreach($rt as $k=>$v){ksort($v); $ret.=div($k,'tit').div(implode('',$v),'cols');}
return div($ret,'paneb colsmall');}

static function build(){
return sql('app',self::$db,'rv','where uid="'.ses('uid').'" order by id');}

static function appbt($v,$c){
$auth=sesif('auth',0);
if(method_exists($v,'com')){
	$j='cbck,,,1|'.$v.',com|headers=1,add=1'; $rp['title']=helpx($v.'_app');
	if($auth>=$v::$private??0)return toggle($j,pic($v),$c,$rp);}}

static function play($p){$r=self::build(); 
$ret=toggle('cbck|home,prefs',langpi('prefs'),'btprm');
if($r)foreach($r as $k=>$v)$ret.=self::appbt($v,'btn');
$rp['title']=helpx('publication'); $rp['data-cl']='dboard';
if(ses('user'))$ret.=bj('dboard|tlxf,editor',langph('new_telex'),'btn',$rp,'',0);
return div($ret,'tlxapps');}

#pub
static function showusrs($p){$usr=$p['usr']??'';
$mail=sql('mail','login','v','where name="'.$usr.'"');
$r=sqlin('name','profile','login','puid','rv','where mail="'.$mail.'" and name!="'.$usr.'" and auth>1 and privacy=0');
if($r){foreach($r as $v)$ret[]=profile::small(['usr'=>$v]);
return implode('',$ret);}}

static function pages(){$r=sqlin('name','profile','login','puid','rv','where role=5 and auth>1 and privacy=0 order by login.up desc');
if($r){foreach($r as $v)$ret[]=profile::small(['usr'=>$v]);
return implode('',$ret);}}

static function demo($p){
$ty=valb($p,'ty','public'); $r=applist::build($ty); $ret=help('apps_'.$ty);
//$ra=sql('com,auth','desktop','kv','where dir like "/apps/'.$ty.'%"');
foreach($r as $k=>$v){$bt=pic($k).span(helpx($k)); $ath=false; $c='';
	if(class_exists($k)){$ath=$k::$private??false;
		if(method_exists($k,'com') && isset($k::$db))$call=$k.',com'; else $call=$k;}
	if($ath===0)$bt=popup($call,pic($k).span(helpx($k)),''); else $c=' opac';
	$ret.=div($bt,'cicon'.$c);}
return div($ret,'board');}

#board
static function subscribers($p){
$rp=['data-prmtm'=>'no'];//stop continuous scrolling!
$usr=$p['usr']??''; $uid=$p['uid']??''; $c=''; $ret=''; $ko=1;
$n1=sql('count(id)','tlex_ab','v',['usr'=>$uid]);
$n2=sql('count(id)','tlex_ab','v',['ab'=>$uid]);
//$n3=sql('count(id)','tlex_ntf','v',['4usr'=>$usr,'state'=>1,'typntf'=>5]);
//$n4=sql('count(id)','tlex_ntf','v',['4usr'=>$usr,'state'=>1,'typntf'=>4]);
$bt=langph('subscriptions').' '.span($n1?$n1:'','nbntf react');//
$ret.=toggle('cbck|tlxf,subscriptions|usr='.$usr,$bt,$c,[],'',$ko);
$role=sql('role','profile','v','where pusr="'.$usr.'"');
$bt=langph($role==3?'members':'subscribers').' '.span($n2,'nbntf react','tlxsub');//.($n4?' active':'')
$ret.=toggle('cbck|tlxf,subscribers|usr='.$usr,$bt,$c,[],'',$ko);
//$ret.=hidden('tlxabsnb',$n2);//.hidden('tlxsubnb',$n1)
//$ret.=toggle('cbck|home,showusrs|usr='.$usr,langpi('other accounts'),$c);
return $ret;}

static function dashboard($p){$c=''; $ret=''; $rt=''; $ko=1; //pr($p);
[$uid,$usr,$vu,$role,$op,$app]=vals($p,['uid','usr','vu','role','opn','app']);
if($app)$op=$app;//opening or app
$rp=['data-prmtm'=>'no'];//continuous scrolling
$rc=['onmouseup'=>atj('closediv','dboard')];
//$rp['data-u']='/'; if($vu)$rp['data-u'].='@'.$usr;
$rt.=lk('/',langph('home'),'react'.act($vu,0));
$rt.=lk('/@'.$p['cusr'],ico('user').' '.$p['cusr'],'react'.act($vu,1));
$ret=div($rt,'lish'); $rt=''; $rp['title']='';
//$rp['title']=helpx('publication');
//if(ses('user'))$rt.=toggle('dboard|tlxf,editor',langph('new_post'),'',$rp,'',0);//
if(ses('user'))$rt.=toggle('dboard|home,play',langph('fast menu'),'',$rp,$vu?0:1,0);
$mode=',mode='.($vu?'private':'public'); $my=$vu?'my ':'';
$rt.=toggle('dboard|tlex,searchbt|usr='.$usr.$mode,langph('search'),$c,$rp,'',0);
$rt.=toggle('dboard,,z|tlex,listbt|usr='.$usr.',list='.ses('list'),langph('lists'),$c,$rp,'',0);
//$rt.=toggle('dboard,,z|tlex,lablbt|usr='.$usr.$mode,langph($my.'labels'),$c,$rp,'',0);
$rt.=toggle('dboard,,z|tlex,tagsbt|usr='.$usr.$mode,langph($my.'hashtags'),$c,$rp,'',0);
$rt.=toggle('dboard,,z|tlex,tag2bt|usr='.$usr.$mode,langph($my.'tags'),$c,$rp,'',0);
$rt.=toggle('dboard,,z|tlex,appsbt|usr='.$usr.$mode,langph($my.'apps'),$c,$rp,'',0);
if(auth(6))$rt.=toggle('dboard|loadapp,com|tg=cbck,bt=1',langph('loader'),$c,$rp,'',0);
$ret.=div($rt,'lish'); $rt='';
if($vu){$bt=langph('my posts'); $noab=1; $c=$op=='posts'&&$role!=5?1:0;}
else{$bt=langph('posts'); $noab=0; $c=$op=='posts'?1:0;}
$rt.=tlex::loadtm('tm='.$usr.',noab='.$noab,$bt.span('','nbntf','tlxrec'),$c);
//$rp['title']=helpx('mac_tlex');
//$rt.=toggle('cbck|home,showusrs',langph('global'),act($op,'global'),$rp,'',$ko);
if($vu && $role==5){$rp['title']=helpx('pinned'); $c=act($op,'site');
	$rt.=toggle('cbck|site,call|usr='.$usr.',uid='.$uid,langph('webpage'),'',$rp,1,$ko);}
$rp['title']=helpx('mac_desktop'); $c=act($op,'docs');//desktop
$rt.=toggle('cbck|desktop|dir=/documents,cuid='.$uid,langph('documents'),$c,$rp,'',$ko);
$rt.=self::subscribers($p);
//apps
$rp['title']='';
if(!$vu){
	$n0=sql('count(id)','tlex_ntf','v','where 4usr="'.$usr.'" and state=1 and typntf in (1,2,3)');
	$bt=langph('notifications').' '.span($n0?$n0:'','nbntf','tlxntf');//notifs
	$rt.=tlex::loadtm('ntf=1',$bt,'');//,$rc
	//$rt.=toggle('dboard|tlex,mntsbt|usr='.$usr,langph('mentions'),$c,$rp,'',$ko);
	$rt.=tlex::loadtm('mnt=1',langph('mentions'),'',$rc);//mention
	$bt=langph('messages').span('','nbntf','tlxcht'); $c=act($op,'chat');//$n3?$n3:
	$rt.=toggle('cbck|chat,calltlx|usr='.$usr.',uid='.$uid.',headers=1',$bt,$c,$rp,'',$ko);//if(auth(6))
	$rt.=tlex::loadtm('likes=1',langph('likes'),'',$rc);//likes
	$rp['title']=helpx('mac_apps'); $rp['data-u']='apps'; $c=act($op,'apps');
	$rt.=toggle('cbck|tlxf,apps|b=public',langph('apps'),$c,$rp,'',$ko);
	$rp['title']=helpx('goodies'); $rp['data-u']='goodies'; $c=act($op,'goodies');
	$rt.=toggle('cbck|tlxf,apps|b=goodies',langph('goodies'),$c,$rp,'',$ko);
	//$rt.=toggle('cbck|home,demo|ty=goodies',langph('goodies'),'');
	$rp['title']=helpx('mac_explorer');  $rp['data-u']=''; $c=act($op,'datas');
	$rt.=toggle('cbck|explorer',langph('datas'),$c,$rp,'',$ko);
	//$rp['title']=''; $rt.=toggle('cbck|profile,edit',langph('profile'),'',$rp,'',$ko);
	}
$ret.=div($rt,'lish');
return div($ret,'dashboard');}

static function dashboard2(){
$bt=toggle('dboard,,2|core,help|ref=tlex_welcome,css=paneb',langph('home'),'',[],1);
$bt.=toggle('dboard,,2|login,com',langph('login'),'');
$ret=div($bt,'lish');
$bt=toggle('cbck,,z|tlex,read',langph('telex'),'',[],0);
$bt.=toggle('cbck|home,demo|',langph('apps'),'',[],1);
$bt.=toggle('cbck|home,demo|ty=goodies',langph('goodies'),'');
$ret.=div($bt,'lish');
return div($ret,'dashboard');}

static function com($p){$op=$p['opn']??'';
if($op=='apps')$ret=app('tlxf',[],'apps');
elseif($op=='docs')$ret=app('desktop',['dir'=>'/documents']);
elseif($op=='files')$ret=app('explorer',['d3'=>$p['uid']]);
elseif($op=='site')$ret=app('site',['uid'=>$p['uid']],'call');
elseif($p['app'])$ret=app($p['app'],$p,'');
else $ret=tlex::read($p);
return $ret;}

static function prm($p){
$usr=$p['usr']??''; $id=$p['id']??''; $app=$p['app']??''; $p['id']=''; $p['own']='';
$cusr=ses('user'); $cuid=ses('uid'); $p['ub']=ses('macboot'); if($p['ub'])$usr=$p['ub'];
$p['role']=sql('role','profile','v',['pusr'=>$usr?$usr:$cusr]);
$p['vu']=$usr?1:0; $p['noab']=$usr?1:0; $p['badid']=0; $p['badusr']=0;
$op='posts'; //cookie('opening');
if($p['vu'] && $op=='datas')$op='posts'; if(!$op)$op='apps';
if($id){$gusr=vrfid($id,'tlex');
	if($gusr){$p['id']=$id; $usr=$gusr; tlex::$opn=1;} else $p['badid']=1;}
if($usr==$cusr)$p['own']=$cusr;
if($usr && !$p['own']){$guid=vrfusr($usr);
	if($guid)$uid=$guid; else {$usr=$cusr; $uid=$cuid; $p['badusr']=1; $p['noab']=0;}}
elseif(!$usr){$usr=$cusr; $uid=$cuid;} else $uid=$cuid;//own
$p+=['usr'=>$usr,'uid'=>$uid,'tm'=>$usr,'opn'=>$op,'cusr'=>$cusr,'cuid'=>$cuid,'app'=>$app];
return $p;}

static function template($p){
$r='{[ed:{},main:{}],container};';
//$ret=phylo($rb,['container'=>['tit'=>'job','txt'=>'description']]);
return templater($r);}

static function notloged($p){
$r='{[ed:{},main:{}],container};';
//$ret=phylo($rb,['container'=>['tit'=>'job','txt'=>'description']]);
return templater($r);}

static function content($p){
//self::install();
$p=self::prm($p); $ret=''; $ed=''; self::$title=$p['usr'];
if($p['opn']=='posts')$pm='tm='.$p['tm'].',noab='.$p['noab']; else $pm='';
if($p['id']){bootheme($p['usr']); $main=tlex::one($p);}
elseif($p['badid'])$main=help('404iderror','board');
elseif($p['badusr'])$main=help('404usrerror','board');
elseif(!$p['cusr'])$main=self::demo([]);
elseif($p['ub'])$main=tlex::read($p);
elseif($p['vu']){
	//if($p['role']==5){$q=new site; $main.=$q::call(['usr'=>$p['cusr'],'uid'=>$p['cuid']]);}
	if($p['role']==5 && $p['own'])$main=app('site',['usr'=>$p['cusr'],'uid'=>$p['cuid']],'call');
	else $main=self::com($p);}
else $main=self::com($p);
//lbar
if($p['id'])$lbar=div(lk('/',langp('home')),'dashboard lish');
elseif($p['cusr']){if(!$p['vu'])$ed=self::play($p); $lbar=self::dashboard($p);}
else{$lbar=self::dashboard2(); $ed=help('tlex_welcome','paneb',1);}
//ban
if($p['vu'] && !$p['badusr'])$ret=div(profile::big($p));// or $p['id']
//if(!$p['cuid'])$ed=login::com($p);
$s=$p['vu']||!$p['cusr']?'absolute':'fixed';
$ret.=div($lbar.div('','','board2'),'barleft','','position:'.$s);//
$ret.=div(div($ed,'','dboard').div($main,'','cbck'),'main');
return div($ret,'container').hidden('prmtm',$pm);}
}

?>