<?php
class voc{
static $private=0;
static $a='voc';
static $db='voc_app';
static $cb='mdl';
static $cols=['tit','txt'];
static $typs=['var','text'];
//static $cols=['ref','lang','trad','vrf'];
//static $typs=['var','var','text','tiny'];
static $db2='voc';
static $lang=['tit'=>1,'txt'=>1];

//first col,txt,answ,com(settings),code,day,clr,img,nb,cl,pub
//$db2 must use col "bid" <-linked to-> id

static function install($p=''){
//sql::create(self::$db,['uid'=>'int','t_fr'=>'var'],1);//appx::install
//sql::create(self::$db2,['uid'=>'int','t_en'=>'var'],1);
//sql::create(self::$db3,['uid'=>'int','t_es'=>'var'],1);
sql::create(self::$db2,['ref'=>'var','lang'=>'var','trad'=>'text','vrf'=>'tiny'],1);
appx::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return appx::admin($p);}
static function titles($p){return appx::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return appx::collect($p);}
static function del($p){
//$p['db2']=self::$db2;
return appx::del($p);}

static function save($p){return appx::save($p);}
static function modif($p){return appx::modif($p);}
static function create($p){
//$p['pub']=0;//default privacy
return appx::create($p);}

//subcall
static function subops($p){return appx::subops($p);}
static function subedit($p){return appx::subedit($p);}
static function subcall($p){return appx::subcall($p);}
static function subform($r){return appx::subform($r);}

//form
//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['barfunc']='barlabel';
return appx::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
//$p['help']=1;
//$p['sub']=1;
//$p['execcode']=1;
return appx::edit($p);}

static function translate($d,$ref){$lng='en';//ses('lng');
[$db,$col,$id]=explode('-',$ref); $vrf=md5($d);
$lg=sql('lang','voc','v','where vrf="'.$vrf.'"');
if(!$lg){//update changes
	$ex=sql('id','voc','v',['ref'=>$ref,'lang'=>$lng]);
	//if($ex)sql::up($db,[$col=>$d,'vrf'=>$vrf],$ex);
	if($ex)sql::del('voc',$ref,'ref');}
if(!$lg){
	$lg=trans::detect(['txt'=>$d]); //echo $lg.'-';
	if($lg)$id=sql::sav('voc',[$ref,$lg,$d,$vrf]);}
if($lg && $lg!=$lng){
	$b=sql('trad','voc','v',['ref'=>$ref,'lang'=>$lng]);
	if(!$b){$c=trans::com(['from'=>$lg,'to'=>$lng,'txt'=>$d]);
		if($c)sql::sav('voc',[$ref,$lng,$c,md5($c)]); $d=$c;}
	else $d=$b;}
return $d;}

#build
static function build($p){
//$lg=ses('lng'); $lg=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
$r=sql('tit,txt',self::$db,'ra',$p['id']);
//if(isset(self::$lang['txt']))
$tit=self::translate($r['tit'],self::$db.'-tit-'.$p['id']);//voc()
$txt=self::translate($r['txt'],self::$db.'-txt-'.$p['id']);
$ret=div($tit,'tit');
$ret.=div($txt,'txt');
return $ret;}

static function build0($p){
$r=sql('tit,txt',self::$db,'ra',$p['id']);
$ret=div($r['tit'],'tit');
$ret.=div($r['tit'],'txt');
return $ret;}

static function play($p){
return self::build($p);}

static function stream($p){
//$p['t']=self::$cols[0];
return appx::stream($p);}

#call (read)
static function call($p){
return self::play($p);}

#interface
static function content($p){
//self::install();
return appx::content($p);}

static function api($p){
return appx::api($p);}
}
?>