<?php
class upsql{
static $private=6;
static $dba=['lang','icons','help','desktop','labels','conn','sys','syslib','devnote'];
static $dbb=['arts','tlex','tlex_web','tlex_app','tlex_tag','tags','tags_r'];
static $rno=['iter','appx'];
static function js(){return;}

#client
static function installdb($a,$r){
if(isset($r) && is_array($r)){
	//sql::trunc($a);
	sql::sav2($a,$r,5,1,0,1);
	if($a=='lang')ses('lang',lang_com(ses('lng')));
	if($a=='icons')ses('icon',icon_com());
	return 'renove '.$a.' ok';}
else return 'nothing:'.json_er();}

static function call($p){$a=$p['app']; $r=[];
$f=srv(1).'/api/upsql/mth:render,p:'.$a.'';
if(in_array($a,self::$rno))return; echo $a.' ';
$d=get_file($f); if($d)$r=json_decode($d,true);
if(host()!=srv() && $r)return self::installdb($a,$r);}

static function loccall($p){
$a=$p['app']??''; $f='usr/_db/'.$a.'.json';
$d=get_file($f); if($d)$r=json_decode($d,true);
if(host()!=srv())return self::installdb($a,$r);}
 
static function utf_rd($r){
foreach($r as $k=>$v){
	if(is_array($v))$ret[$k]=self::utf_rd($v);
	else $ret[$k]=rawurldecode($v);}
return $ret;}

#server
static function render($p){
$a=$p['p']??'';
$keys=sql::cols($a,0,1);
if($a=='login')return;
elseif($a=='desktop')$wh='where uid=1';
elseif($a=='articles')$wh='where uid=1';
else $wh='';
$r=sql($keys,$a,'rr',$wh,0);
return json_enc($r);}

static function last($p){$b=$p['b']??'';
if($b)return sql::lastid($b);}

static function whatsnew($p){
$r=explode('-',$p['dbs']); $rt=[];
$ra=sql::query('show tables','rv'); //pr($r);
foreach($r as $k=>$v)if(class_exists($v)){
	if(isset($v::$db)){$b=$v::$db; if(in_array($b,$ra))$rt[]=[$b,sql::lastid($b)];}
	if(isset($v::$db2)){$b=$v::$db2; if(in_array($b,$ra))$rt[]=[$b,sql::lastid($b)];}}
	elseif(in_array($v,$ra) && sql::ex($v))$rt[]=[$v,sql::lastid($v)];
return implode_r($rt,';','-');}

#img
static function newim($p){//distant
$ra=scandir_a('img/full'); $rc=[];
$srv=$p['srv']??''; $d=get_file(http($srv).'/usr/dl/img.txt'); $rb=explode("\n",$d);
foreach($ra as $k=>$v)if(!in_array($v,$rb))$rc[]=$v;
$d=implode("\n",$rc); write_file('usr/dl/imb.txt',$d);
return count($rc).' files need to be downloaded';}
 
static function upim($r){$rc=[];//local
$r=scandir_a('img/full'); $d=implode("\n",$r); write_file('usr/dl/img.txt',$d);
$f=srv(1).'/api/upsql/mth:newim,srv:'.host(); $d=get_file($f); $ret=$d.br();
if($d)$d=get_file(srv(1).'/usr/dl/imb.txt'); $rb=explode("\n",$d);
foreach($rb as $k=>$v)if($v && $k<10)$rc[]=upload::srv_img(srv(1).'/img/full/'.$v);
return $ret.implode(br(),$rc).br().'ok';}
 
static function upfl($p){$rid=randid('upfl'); $ok='';
$f=$p['f']??''; if($f)$ok=copy(srv(1).'/disk/'.$f,'disk/'.$f);
$ret=input('f',$f,26).bj($rid.'|upsql,upfl||f',langp('download'),'btn');
if($ok)$ret.='ok';
return div($ret,'',$rid);}

#action
static function archive(){
$r=self::$dba; $dr='usr/_db'; mkdir_r($dr);
foreach($r as $k=>$v){$d=self::render(['p'=>$v]); file_put_contents($dr.'/'.$v.'.json',$d);}}

static function install($p){$r=self::$dba; $ret=[];
if($p['local']??'')$a='loccall'; else $a='call';//local or distant
foreach($r as $k=>$v)$ret[]=self::$a(['app'=>$v]);
return implode(br(),$ret);}

static function lastest($p){$r=self::$dba;
if(auth(6))$r=array_merge($r,self::$dbb,applist::pub());
$f=srv(1).'/api/upsql/mth:whatsnew,dbs:'.implode('-',$r); $d=get_file($f);
return $d?explode_r($d,';','-'):[];}

static function batch($p){
$r=self::lastest($p); $ret=[];
foreach($r as $k=>$v){$lid=sql::lastid($v[0]);
	if($v[1]>$lid)$ret[]=self::call(['app'=>$v[0]]);}
return implode('',$ret);}

static function menu($p){
$r=self::lastest($p); $ret=[];
if($p['local']??'')$a='loccall'; else $a='call';//source of datas
$ret[]=bj($p['rid'].'|upsql,batch|',lang('batch'),'btdel').' ';
foreach($r as $k=>$v){$lid=sql::lastid($v[0]); $c=$v[1]>$lid?'btsav':'btn';
	$ret[]=bj($p['rid'].'|upsql,'.$a.'|app='.$v[0],$v[0],$c).' ';}
return implode('',$ret);}

static function content($p){
$p['rid']=randid('md');
$p['p1']=$p['p1']??'';//unamed param before
$bt=hlpbt('upsql');
$bt.=self::menu($p);
return $bt.div('','',$p['rid']);}
}
?>