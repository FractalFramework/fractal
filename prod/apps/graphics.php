<?php

class graphics extends appx{
static $private=0;
static $a='graphics';
static $db='graphics';
static $cb='tbl';
//static $cols=['tit','txt','typ','dk','dv','ad','dc','pr','lb','pub'];
//static $typs=['var','text','var','int','int','int','int','int','int','int'];
static $cols=['tit','txt','cfg','pub'];
static $typs=['var','text','json','int'];
static $json=['typ','dk','dv','ad','dc','pr','lb'];
static $open=1;
static $conn=0;
static $tags=1;
static $qb='db';
static $db2='graphics2';

static function patch(){return;
sql::create(self::$db2,['uid'=>'int','tit'=>'var','txt'=>'text','cfg'=>'json','pub'=>'int'],1);
$r=sql('*',self::$db,''); $rb=[]; pr($r);
foreach($r as $k=>$v){[$id,$uid,$tit,$txt,$typ,$dk,$dv,$ad,$dc,$pr,$lb,$pub,$up]=$v; //pr($v);
	$rb[]=[$id,$uid,$tit,$txt,json_encode(['typ'=>$typ,'dk'=>$dk,'dv'=>$dv,'ad'=>$ad,'dc'=>$dc,'pr'=>$pr,'ib'=>$lb]),$pub,$up];}
//pr($rb);
sql::trunc('graphics2'); sql::sav2('graphics2',$rb,5);
sql::rn('graphics','graphics0');
sql::rn('graphics2','graphics');
}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}

static function js(){return '';}

static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

//trans
static function trans($d){
if(strpos($d,'<tr')!==false)$d=conv::call(['txt'=>$d]);
else $d=str_replace(',','|',delbr($d,"\n"));
if(strpos($d,'<div')!==false)$d=str_replace(['<div>','</div>'],['',"\n"],$d);
if(substr($d,-7)==':table]')$d=substr($d,1,-7);
return trim($d);}

static function sav_qb($uid,$id,$d){
$r=explode_r($d,"\n",'|'); $rb=[];
foreach($r as $k=>$v){$k=array_shift($v); $rb[$k]=$v;}
$f=self::nod($id,$uid); db::save($f,$rb);
explorer::opsav(['op'=>'repair','f'=>$f]);}

static function nod($id,$uid=''){//explorer::nod($app,$id)
if(!$uid && is_numeric($id))$uid=sql('uid',self::$db,'v',$id);
if($uid){$nm=usrid($uid); return 'usr/'.$nm.'/graphics/'.$id.'.php';}}

#edit
static function del($p){return parent::del($p);}
static function create($p){return parent::create($p);}
static function edit($p){$p['help']=1; $p['json']=self::$json; return parent::edit($p);}

static function save($p){
$a=self::$a; $db=self::$db; $cb=self::$cb;
$txt=self::trans($p['txt']??'');//$r=explorer::repair($r);
$rb=valk($p,self::$json); $cfg=json_encode($rb);
$r=['bid'=>ses('uid'),'tit'=>$p['tit'],'txt'=>$txt,'cfg'=>$cfg,'pub'=>$p['pub']];
$r=sql::vrf($r,$db);
$p['id']=sql::sav($db,$r);
self::sav_qb('',$p['id'],$txt);
return $a::edit($p);}

static function modif($p){$id=$p['id'];
if(!$p['txt'])$p['txt']=sql('txt',self::$db,'v',$id);
else{$p['txt']=self::trans($p['txt']); self::sav_qb('',$id,$p['txt']);}
$rb=valk($p,self::$json); $p['cfg']=json_encode($rb); //pr($p);
$ret=parent::modif($p); self::play($p,1);
return $ret;}

static function form($p){$cb=self::$cb; $ret=''; $edt='';
$r=valk($p,self::$cols); $id=$p['id']??''; $uid=$p['uid']??ses('uid');
$rb=json_decode($r['cfg'],true);
$ret=div(input('tit',$r['tit'],'44',lang('title')));
$f='usr/'.usrid($uid).'/graphics/'.$id.'.php';
$ex=db::ex($f,1); if($ex)$edt=explorer::play(['f'=>$f,'x'=>0]); $o=$ex?0:1; $o=0;
if($id)$ret.=toggle('|graphics,reform|id='.$id.',uid='.$uid,langp('original html'),'btn',[],$o);
else $ret.=textarea('txt','',24,8,'0,1,2','console');
$ret.=radio('typ',['histo','lines','boxes'],$rb['typ']??0,1).br();
$ret.=checkbox('dk',[1=>'daykey'],$rb['dk']??0);
$ret.=checkbox('dv',[1=>'dayval'],$rb['dv']??0);
$ret.=checkbox('ad',[1=>'adapt-height'],$rb['ad']??1);
$ret.=checkbox('dc',[1=>'decimals'],$rb['dc']??0);
$ret.=checkbox('pr',[1=>'multiscales'],$rb['pr']??0);
$ret.=checkbox('lb',[1=>'labels'],$rb['lb']??0);
//$ret.=checkbox('ky',[1=>'keys'],$rb['ky']??1);
$ret.=div($edt,'','fcb');
$ret.=hidden('pub',$r['pub']);
return div($ret);}

static function import_web($p){
$tg=$p['tg']; $u=$p['url']??''; $ret='';
$j='slctdb|graphics,import_web|tg='.$tg.'|url';
$ret=inputcall($j,'url',$u,22,lang('url'));
$ret.=bj($j,langp('ok'),'btn').hlpbt('import_web_table');
if($u)$ret.=conv::call_table($p);
return $ret;}

static function reform($p){
$uid=val($p,'uid',ses('uid')); $fa='usr/'.usrid($uid);
$bt=toggle('slctdb|explorer,select|tg=txt,a=graphics,f='.$fa,langp('import_db'),'btsav');
$bt.=toggle('slctdb|graphics,import_web|tg=txt',langp('import_web'),'btsav');
$bt.=div('','','slctdb');
$id=$p['id']??''; $txt=$id?sql('txt',self::$db,'v',$id):''; if($txt)$txt=conn::tabler($txt);
if(!$txt)$txt='<table><tbody><tr><td>col1</td><td>col2</td></tr></tbody></table>';
return $bt.div($txt,'article','txt','',['contenteditable'=>'true']);}

#build
static function build($p){
$id=$p['id']??''; $cols=implode(',',self::$cols);
return sql('uid,'.$cols,self::$db,'ra',$id);}//self::$conn hérite de tlex!

static function draw($p,$r,$im=''){
$f=self::nod($p['id'],$r['uid']); $rp=json_decode($r['cfg'],true);
$rp['r']=db::read($f); $rp['t']=$r['tit']; $rp['im']=$im;
if($rp['r'])return graphs::call($rp);}

static function cache($p){
$id=$p['id']; $f='img/svg/graphics'.$id.'.svg'; //$ft=fdate($f);
//$dt=sql('numsec',self::$db,'v',$id); echo $ft.'---'.$dt;
if(file_exists($f))return $f;}

static function read($f){
return read_file($f);
return img('/'.$f.'?'.randid());}

static function play($p,$x=''){$x=1;
$r=self::build($p); //pr($r);
$ret=div($r['tit'],'tit');
$f=self::cache($p);
if($f && !$x)$ret.=self::read($f);
else $ret.=self::draw($p,$r,0);//img
return $ret;}

static function stream($p){
return parent::stream($p);}

#interfaces
static function tit($p){
return parent::tit($p);}

//call (read)
static function call($p){
return parent::call($p);}

//com (write)
static function com($p){
return parent::com($p);}

//interface
static function content($p){self::patch();
//self::install();
return parent::content($p);}

static function api($p){
return parent::api($p);}
}

?>