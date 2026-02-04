<?php
class apps{
static $private=0;
static $a='apps';
static $db='os';
static $cb='appmain';
static $title='';
static $descr='';
static $image='';
static $home='';

static function install(){
sql::create(self::$db,['uid'=>'int','app'=>'var'],1);}

static function admin($p){return;
$usr=ses('usr')?ses('usr'):'profile'; $a=self::$a;
//$r[]=[$usr,'j','popup|profile,edit','','edit profile'];
//$r[]=['','bub','core,help|ref='.$a.'_app','question-circle-o',''];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f='.$a,'code','Code'];
if(auth(6)){
	$r[]=['admin/identity','pop','admin_lang,open|ref='.$a.',app='.$a,'lang','name'];
	$r[]=['admin/identity','pop','admin_help,open|ref='.$a,'help','name'];
	$r[]=['admin/identity','pop','admin_help,open|ref='.$a.'_app','help','help'];
	$r[]=['admin/identity','pop','admin_icons,open|ref='.$a,'picto','pictos'];}
return $r;}

static function js(){}

static function headers(){
root::$title=self::$title;
root::$descr=self::$descr;
root::$image=self::$image;
head::add('jslink','/js/tlex.js');
head::add('jscode',self::js());}

#mac
static function init(){$uid=ses('uid');
$r=['art','stext','tabler','slide','movie','audio','ideas'];//,'sticker','poll'
foreach($r as $k=>$v)$rb[]=['uid'=>$uid,'app'=>$v];
sql::sav2(self::$db,$rb);
return $r;}

static function reinit($p){
sql::del(self::$db,ses('uid'),'uid'); self::init();
return self::play($p);}

static function add($p){pr($p); echo 'ee';
sql::savif(self::$db,['uid'=>ses('uid'),'app'=>$p['app']]);
return self::play($p);}

static function del($p){pr($p); echo 'ee';
$id=sql('id',self::$db,'v',['uid'=>ses('uid'),'app'=>$p['app']]);
if($id)sql::del(self::$db,$id);
return self::play($p);}

static function prefs(){$ret=''; $rt=[]; $c='';
$r=self::build(); $rb=applist::build('public');
//$rp['data-jb']=self::$cb.'|apps,com|prefs=1';
$rp['data-jb']=self::$cb.'|apps,call|';
//$ret=bj(self::$cb.'|apps,com',ico('check').lang('ok'),$c);
$ret.=bj(self::$cb.'|apps,reinit',langp('reset'),'btdel',$rp);
foreach($rb as $k=>$v){$bt=helpx($k); $cat=lang(strend($v,'/')); //$op=self::appbt($k,'');
	if(in_array($k,$r)){$go='del'; $ic='toggle-on';} else{$go='add'; $ic='toggle-off';}
	$rp['title']=helpx($k.'_app');
	$rt[$cat][$bt]=div(bj(self::$cb.'|apps,'.$go.'|app='.$k,pic($k).' '.ico($ic).$bt,$c,$rp));}
foreach($rt as $k=>$v){ksort($v); $ret.=div($k,'tit').div(implode('',$v),'cols');}
return div($ret,'');}

static function build(){
return sql('app',self::$db,'rv',['uid'=>ses('uid'),'_order'=>'id']);}

static function appbt($v,$c){
$auth=sesif('auth',0);
if(method_exists($v,'com')){
	$j='cbck|'.$v.',com|headers=1'; $rp['title']=helpx($v.'_app'); $rp['data-u']='/'.$v;
	if($auth>=$v::$private??0)return toggle($j,pic($v),$c,$rp);}}

static function play($p){
$r=self::build(); $ret='';
$nav=toggle('appedt|apps,prefs',langpi('prefs'),'').' ';
if($r)foreach($r as $k=>$v)$nav.=self::appbt($v,'').' ';
if($p['app']??'')$ret=self::prefs();
return div($nav,'lisb','appmnu').div($ret,'board','appedt');}

static function call($p){
$ret=self::play($p);
return div($ret,'','appmain');}

static function content($p){
//self::install();
//return tlxf::apps(['b'=>'public']);
return self::call($p);}

}
?>