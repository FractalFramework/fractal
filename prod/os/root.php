<?php
class root{
static $private=0;
static $a='rooter';
static $db='';
static $cb='cbck';
static $title='';
static $apnav='';
static $pm='';

static function demo($p){
$ty=valb($p,'ty','public'); $r=applist::build($ty); $ret=help('apps_'.$ty);
//$ra=sql('com,auth','desktop','kv','where dir like "/apps/'.$ty.'%"');
foreach($r as $k=>$v){$bt=pic($k).span(helpx($k)); $ath=false; $c='';
	if(class_exists($k)){$ath=$k::$private??false;
		if(method_exists($k,'com') && isset($k::$db))$call=$k.',com'; else $call=$k;}
	if($ath===0)$ret.=popup($call,div($bt)); else $ret.=div($bt,'opac');}
return div($ret,'board cicon');}

static function role($usr){
return sql('role','profile','v',['pusr'=>$usr]);}

//root determination
static function prm($p){
$usr=$p['usr']??''; $id=$p['id']??''; $app=$p['app']??''; $p['id']=''; $p['own']='';
$cusr=ses('usr'); $cuid=ses('uid'); $p['ub']=ses::$cnfg['usrboot']; if($p['ub'])$usr=$p['ub'];
$p['role']=self::role($usr?$usr:$cusr);
$p['vu']=$usr?1:0; $p['noab']=$usr?1:0; $p['badid']=0; $p['badusr']=0;
$op='posts'; //cookie('opening');
$p['index']=ses::$cnfg['index'];
if($p['vu'] && $op=='datas')$op='posts'; if(!$op)$op='apps';
if($id){$gusr=vrfid($id,'tlex');
	if($gusr){$p['id']=$id; $usr=$gusr; tlex::$opn=1;} else $p['badid']=1;}
if($usr==$cusr)$p['own']=$cusr;
if($usr && !$p['own']){$guid=vrfusr($usr);
	if($guid)$uid=$guid; else {$usr=$cusr; $uid=$cuid; $p['badusr']=1; $p['noab']=0;}}
elseif(!$usr){$usr=$cusr; $uid=$cuid;} else $uid=$cuid;//own
$p+=['usr'=>$usr,'uid'=>$uid,'tm'=>$usr,'opn'=>$op,'cusr'=>$cusr,'cuid'=>$cuid,'app'=>$app];
return $p;}

static function template($nav2,$nav3,$ret){
return div($nav2,'lisb','nav2').div($nav3,'','nav3').div($ret,'','cbck');}

static function tlex($p){
$nav=tlxf::menu($p); $ret=tlex::read($p);
return self::template($nav,'',$ret);}

static function call($p){
$op=$p['opn']??''; $index=$p['index'];
if($op=='posts')self::$pm='tm='.$p['tm'].',noab='.$p['noab'];
if($p['app']=='install')$ret=install::automate();//
elseif($p['app'])$ret=app($p['app'],$p,'');
elseif($op=='apps')$ret=app('tlxf',[],'apps');
elseif($op=='docs')$ret=app('desktop',['dir'=>'/documents']);
elseif($op=='files')$ret=app('explorer',['d3'=>$p['uid']]);
elseif($op=='site')$ret=app('site',['uid'=>$p['uid']],'call');
elseif($op=='posts')$ret=self::tlex($p);
else $ret=app($index,$p,'call');
return $ret;}

static function com($p){
if(!isset($p['badid']))$p=self::prm($p); $ret='';
self::$title=$p['usr']; //$index=ses::$cnfg['index'];
if($p['id']){$ret=tlex::one($p);}//bootheme($p['usr']); 
elseif($p['badid'])$ret=help('404iderror','board');
elseif($p['badusr'])$ret=help('404usrerror','board');
//elseif(!$p['cusr'] && !$p['app']??'')$ret=self::demo([]);
elseif($p['ub'])$ret=tlex::read($p);
elseif($p['vu']){
	//if($p['role']==5){$q=new site; $ret.=$q::call(['usr'=>$p['cusr'],'uid'=>$p['cuid']]);}
	if($p['role']==5 && $p['own'])$ret=app('site',['usr'=>$p['cusr'],'uid'=>$p['cuid']],'call');
	else $ret=self::call($p);}
else $ret=self::call($p);
//if($p['vu'] && !$p['badusr'])$ret=div(profile::big($p)).$ret;// or $p['id']
return $ret;}

//$index must have: headers,demo,mnuapps,dashboard 
static function content($p){
$p=self::prm($p);
$index=ses::$cnfg['index'];
//$ret=self::com($p);
//return div($ret,'container').hidden('prmtm',self::$pm);
return $index::content($p);}
}

?>