<?php

class tabler extends appx{
static $private=0;
static $a='tabler';
static $db='tabler';
static $cb='tbl';
static $cols=['tit','txt','pub','edt'];
static $typs=['var','text','int','int'];
static $tags=1;
static $open=1;
static $qb='db';

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
if(substr($d,-7)==':table]')$d=substr($d,1,-7);
return trim($d);}

static function sav_qb($uid,$id,$d){
$r=explode_r($d,"\n",'|');
$f=self::nod($id,$uid); db::save($f,$r);
explorer::opsav(['op'=>'repair','f'=>$f]);
explorer::opsav(['op'=>'reset_header','f'=>$f]);}

static function nod($id,$uid=''){//explorer::nod($app,$id)
if(!$uid && is_numeric($id))$uid=sql('uid',self::$db,'v',$id);
if($uid){$nm=usrid($uid); return 'usr/'.$nm.'/tabler/'.$id.'.php';}}

#edit
static function del($p){return parent::del($p);}
static function create($p){return parent::create($p);}
static function edit($p){return parent::edit($p);}

static function save($p){
$a=self::$a; $db=self::$db; //$cb=self::$cb;
$txt=self::trans($p['txt']??''); $r=[ses('uid')];//$r=explorer::repair($r);
foreach(self::$cols as $v){if($v=='txt')$r[$v]=$txt; else $r[$v]=$p[$v]??'';}
$r=sql::vrf($r,$db);
$p['id']=sql::sav($db,$r);
self::sav_qb('',$p['id'],$txt);
return $a::edit($p);}

static function modif($p){$id=$p['id']??'';
if(!$p['txt'])$p['txt']=sql('txt',self::$db,'v',$id);
else{$p['txt']=self::trans($p['txt']??''); self::sav_qb('',$id,$p['txt']);}
return parent::modif($p);}

static function form($p){$ret=''; $edt=''; //$cb=self::$cb;
$r=valk($p,self::$cols); $id=$p['id']??''; $uid=$p['uid']??ses('uid');
$ret=div(input('tit',$r['tit'],'44',lang('title')));
$f=self::nod($id,$uid); $ex=db::ex($f,1); if($ex)$edt=explorer::play(['f'=>$f,'x'=>1]); //$o=$ex?0:1;
if($id)$ret.=toggle('|tabler,reform|id='.$id.',uid='.$uid,langp('original html'),'btn');
$ret.=div($edt,'','fcb');
$ret.=hidden('pub',$r['pub']);
return div($ret);}

static function import_web($p){
$tg=$p['tg']; $u=$p['url']??''; $ret='';
$j='slctdb|tabler,import_web|tg='.$tg.'|url';
$ret=inputcall($j,'url',$u,22,lang('url'));
$ret.=bj($j,langp('ok'),'btn').hlpbt('import_web_table');
if($u)$ret.=conv::call_table($p);
return $ret;}

static function reform($p){
$uid=val($p,'uid',ses('uid')); $fa='usr/'.usrid($uid);
$bt=toggle('slctdb|explorer,select|tg=txt,a=tabler,f='.$fa,langp('import_db'),'btsav');
$bt.=toggle('slctdb|tabler,import_web|tg=txt',langp('import_web'),'btsav');
$bt.=div('','','slctdb');
$id=$p['id']??''; $txt=$id?sql('txt',self::$db,'v',$id):''; if($txt)$txt=conn::tabler($txt);
if(!$txt)$txt='<table><tbody><tr><td>col1</td><td>col2</td></tr></tbody></table>';
return $bt.div($txt,'article','txt','',['contenteditable'=>'true']);}

#build
static function build($p){return parent::build($p);}

static function play($p){
$p['conn']=0; $id=$p['id']; $ret='';
$r=self::build($p);
//$ret=lk('/api/db/f:/usr/'.$f,pic('api'),'btn',1);
//if($r['uid']==ses('uid'))$ret.=bj(self::$cb.'|tabler,edit|id='.$id,langpi('edit'),'btn');
$f=self::nod($id,$r['uid']); $rb=db::read($f);
$fb=self::nod(normalize($r['tit']),ses('uid'));
if(ses('uid'))$ret.=popup('explorer,opsav|op=export,f='.$f.',nm='.$fb,langp('save datas'),'btsav');
$ret=div($ret,'right');
$ret.=div($r['tit'],'tit');
if($rb)$txt=tabler($rb,1,1);
else $txt=conn::tabler($r['txt']);
//$ret.=db::bt($f);
$ret.=div($txt,'txt');
return $ret;}

static function stream($p){
return parent::stream($p);}

#interfaces
static function tit($p){
return parent::tit($p);}

//call (read)
static function call($p){
return parent::call($p);
return div(self::play($p),'',self::$cb.$p['id']);}

//com (write)
static function com($p){
return parent::com($p);}

//interface
static function content($p){
//self::install();
return parent::content($p);}

static function api($p){
return parent::api($p);}
}

?>