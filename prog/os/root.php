<?php
class root{
static $private=0;
static $a='rooter';
static $db='';
static $cb='cbck';
static $title;
static $descr;
static $image;
static $apnav='';
static $out=[];

static function headers(){
//head::prop('og:title',addslashes_b(self::$title));
//head::prop('og:description',addslashes_b(self::$descr));
//head::prop('og:image',self::$image);
}

static function demo($p){
$ty=valb($p,'ty','public'); $r=applist::build($ty); $ret=help('apps_'.$ty);
//$ra=sql('com,auth','desktop','kv','where dir like "/apps/'.$ty.'%"');
foreach($r as $k=>$v){$bt=pic($k).span(helpx($k)); $ath=false; $c=''; //?
	if(class_exists($k)){$ath=$k::$private??false;
		if(method_exists($k,'com') && isset($k::$db))$call=$k.',com'; else $call=$k;}
	if($ath===0)$ret.=popup($call,div($bt)); else $ret.=div($bt,'opac');}
return div($ret,'board cicon');}

static function role($usr){
return sql('role','profile','v',['pusr'=>$usr]);}

static function template(){
return [
['div'=>['class'=>'container','id'=>'main'],[
	['div'=>[],[
		['div'=>['class'=>'lisb','id'=>'nav1'],'{nav1}'],
		['div'=>['class'=>'lisb','id'=>'nav2'],'{nav2}'],
		['div'=>['class'=>'lisb','id'=>'nav3'],'{nav3}']]]],
	['div'=>['id'=>self::$cb],'{cnt}']]];}

static function render(){
[$cnt,$nav1,$nav2,$nav3,$pm]=vals(self::$out,['cnt','nav1','nav2','nav3','pm']);
//self::template();
$nav1=div($nav1,'lisb','nav1');
$nav2=div($nav2,'lisb','nav2');
$nav3=div($nav3,'','nav3');
$ret=div($nav1.$nav2.$nav3);
$ret.=div($cnt,'',self::$cb);//cbck
return div($ret,'container','main').hidden('prmtm',$pm);}

static function call($p){//pr($p);
[$op,$index,$app]=vals($p,['opn','index','app']);
//if($index!='home')$p['app']=$index;
//if($op=='posts')//self::$out['prmt']=hidden('prmtm','')
if($app=='install')$ret=install::automate();//
elseif($index=='home' && $app)$app=$p['app']; else $app=$index;
self::$out['cnt']=app($app,$p,'');
//else $ret=app($index,$p,'content');
/*else $ret=match($op){
'docs'=>app('desktop',['dir'=>'/documents']),
'files'=>app('explorer',['d3'=>$p['uid']]),
'site'=>app('site',['uid'=>$p['uid']],'call'),
'posts'=>home::tlex($p),
default=>app($op,$p,'call')};*/
return self::render();}

static function com($p){$ret='';
if(!isset($p['badid']))$p=self::prm($p);
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

static function prm($p){
[$usr,$id,$app]=vals($p,['usr','id','app']);
$p['id']=''; $p['own']='';
$cusr=ses('usr'); $cuid=ses('uid');
$p['ub']=ses::$cnfg['usrboot']; if($p['ub'])$usr=$p['ub'];
$p['role']=self::role($usr?$usr:$cusr);
$p['vu']=$usr?1:0; $p['noab']=$usr?1:0; $p['badid']=0; $p['badusr']=0;
$p['index']=ses::$cnfg['index'];
$op='contents'; //cookie('opening');//obs
//if($p['vu'] && $op=='datas')$op='posts'; if(!$op)$op='apps';
if($id){$gusr=vrfid($id,'tlex');
	if($gusr){$p['id']=$id; $usr=$gusr; tlex::$opn=1;} else $p['badid']=1;}
if($usr==$cusr)$p['own']=$cusr;
if($usr && !$p['own']){$guid=vrfusr($usr);
	if($guid)$uid=$guid; else {$usr=$cusr; $uid=$cuid; $p['badusr']=1; $p['noab']=0;}}
elseif(!$usr){$usr=$cusr; $uid=$cuid;} else $uid=$cuid;//own
$p+=['usr'=>$usr,'uid'=>$uid,'tm'=>$usr,'opn'=>$op,'cusr'=>$cusr,'cuid'=>$cuid,'app'=>$app];
$p['headers']=1;
return $p;}

//$index must have: headers,demo,mnuapps,dashboard 
static function content($p){
$p=self::prm($p); //pr($p);
return self::com($p);}
}
?>