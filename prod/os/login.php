<?php
class login{
static $private=0;
static $authlevel='2';
static $css='btn';
static $sz='30';

static function headers(){
head::add('csscode','#cbklg{margin:0px;}');}

//install
static function install(){
sql::create('login',['name'=>'var','password'=>'var','auth'=>'int','mail'=>'var','ip'=>'var']);}

static function js(){return;}

//recover
static function recover($p){
$user=$p['user']??''; $mail=$p['mail']??'';
$state=auth::recovery($user,$mail);
return self::reaction($state,$user);}

static function recoverform($p){
$user=$p[lang('nickname')]??'';
$ret=input('mail','',self::$sz,lang('mail'));
$j='cbklg|login,recover|user='.$p['user'].',time='.time().'|mail';
$ret.=bj($j,lang('recover_pswd'),'btdel');
return $ret;}

static function recoverbt($user=''){
$j='cbklg|login,recoverform|user='.$user;
return bj($j,lang('forgotten_pswd'),'btdel');}

static function recoverVerif($reco){
if($reco!=ses('recoveryRid') or !ses('recoveryUsr'))return 'recovery_fail';
return 'recovery_set';}

static function recoverValidation($user){
if(ses('usr') or !ses('recoveryUsr'))return lang('error');
$ret=input('recpsw','',self::$sz,'',1);
$ret.=bj('recocbk|login,recoverSave||recpsw',lang('set as new password'),'btsav');
return div($ret,'','recocbk');}

static function recoverSave($p){
$pswd=$p['recpsw']; $user=ses('recoveryUsr');
if(!$id=ses('recoveryId'))return 'recovery_fail';
if($user && $pswd){
		$hash=auth::create_password($pswd);
		sql::upd('login',['password'=>$hash],$id);
sez('recoveryUsr'); sez('recoveryRid'); sez('recoveryId');
return auth::login($user,$pswd);}}

//register
static function register($p){
[$user,$pass,$mail,$auth]=vals($p,['user','pass','mail','auth']);
if($user && $pass && $mail){
	$state=auth::register($user,$pass,$mail,$auth);
	profile::create($user);}
else $state='register_fail';
return self::reaction($state,$user);}

static function verifusr($p){
return sql('id','login','v',['name'=>$p['user']]);}

static function superadmin(){
$ex=sql('count(id)','login','v','');
if(!$ex)return 6; else return ses('authlevel');}

static function registerform($p){$ret='';
$user=$p['user']??''; //$cntx=$p['cntx']??'';
$ret=tag('input',['id'=>'user','placeholder'=>lang('nickname',1),'size'=>self::$sz,'maxlength'=>20,'onkeyup'=>'verifchars(this); verifusr(this);'],'',1).span(lang('user used'),'alert hide','usrexs').br();
$ret.=password('pass','',self::$sz,lang('password',1));
//$ret.=bj('lgkg|keygen,build',ico('key')).div('','','lgkg');
$ret.=div(input('mail','',self::$sz,lang('mail',1)));
$auth=self::superadmin();//first user
$ret.=hidden('auth',$auth);
//$ret.=hidden('cntx',$cntx);
$j='reload,cbklg,register_ok|login,register|time='.time().'|user,pass,mail,auth';
$ret.=bj($j,langp('register'),'btsav');
return $ret;}

static function registerbt($user=''){
$j='cbklg|login,registerform|user='.$user;
return bj($j,langp('register'),self::$css);}

//logout
static function disconnect(){
$state=auth::logout();
return self::reaction($state);}

static function logoutbt($user){
$ret=tag('span','class=small',$user).' ';
$j='div,cbklg,reload|login,disconnect';
return bj($j,langp('logout'),self::$css);}

static function loged($user){
return span(lang('logok').' '.$user.' (auth:'.ses('auth').')','valid');}

//login
static function authentificate($p){
$user=$p['user']??''; $pass=$p['pass']??'';
$state=auth::login($user,$pass);
if($state=='loged_ok')return $state;//expected for reload
return self::reaction($state,$user);}

static function badger($p){$user=$p['user']??'';
$r=sql('id,auth','login','ra',['name'=>$user,'mail'=>ses('mail')]);
if($r){profile::init_clr(['usr'=>$user]);
	ses('usr',$user); ses('uid',$r['id']); ses('auth',$r['auth']); auth::activateCookie($r['id'],$user);
	return 'loged_ok';}}

static function loginform($p){$ret=''; $user=$p['user']??'';
if(!$user)$user=sql('name','login','v','where ip="'.ip().'"');
$j='reload,cbklg,loged_ok|login,authentificate|time='.time().'|user,pass';
$ret=div(inputcall($j,'user',$user?$user:'',self::$sz,lang('user',1)));
$ret.=div(inputcall($j,'pass','',self::$sz,'*****','',['type'=>'password']));
$ret.=bj($j,langp('login'),self::$css).' ';
$ret.=bj('cbklg|login,registerform||user',langp('register'),self::$css);
$ret.=hlpbt('login_sign');
return $ret;}

static function loginbt($user=''){
if(!$user)$user=cookie('usr');
$j='cbklg|login,loginform|user='.$user;
return bj($j,langp('login'),self::$css);}

//alerts
static function reaction($state,$user=''){
$alert=lang($state);
switch($state){
	case('loged'):$ret=self::loged($user).self::logoutbt($user); break;
	case('loged_ok'):$ret=self::loged($user).self::logoutbt($user); break;
	case('loged_out'):$ret=self::loginform($user); break;
	case('loged_private'):$ret=self::loginform($user); break;
	case('bad_password'):$ret=self::loginform($user).self::recoverbt($user); break;
	case('unknown_user'):$ret=self::loginform($user); break;
	case('register_ok'):$ret=$state; break;//used for reload
	case('register_fail'):$ret=self::loginform($user); break;
	case('register_error'):$ret=self::registerbt($user); break;
	case('register_fail_mail'):$ret=self::registerbt($user); break;
	case('register_fail_aex'):$ret=self::registerbt($user); break;
	case('recovery_mailsent'):$ret=help('recovery_mailsent'); break;
	case('recovery_set'):$ret=self::recoverValidation($user); break;
	default:$ret=span($alert,'small'); break;
}
if($alert && $state!='loged' && $state!='loged_ok' && $state!='loged_out' && $state!='register_ok')$ret=div($alert,'alert').$ret;
return $ret;}

static function bt(){
$usr=ses('usr'); $uid=ses('uid');
if(!$usr && !$uid)return div(self::loginbt($usr),'','cbklg');
else return bj(',,reload|login,disconnect',langp('logout'),'btdel');}

//tlex
static function com($p){$user='';
if($p['o']??'')self::$css='btn abbt';
$auth=$p['auth']??'';
ses('authlevel',$auth?$auth:self::$authlevel);
$state=auth::login('','');
if($state=='ip_found')$user=auth::getUserByUid(ses('uid'));
elseif($state=='cookie_found')$user=cookie('usr');
elseif($state=='loged')$user=ses('usr');
$ret=self::reaction($state,$user);
return div($ret,'board','cbklg');}

//content
static function content($p){$ret='';
//auth::install();
//self::install();
$user=$p['user']??'';
$pass=$p['pass']??'';
$auth=$p['auth']??2;
if($p['o']??'')self::$css='';
ses('authlevel',$auth?$auth:self::$authlevel);
$state=auth::login($user,$pass);
$rec=$p['recovery']??''; if($rec)$state=self::recoverVerif($rec);
if($state=='ip_found')$user=auth::getUserByUid(ses('uid'));
elseif($state=='cookie_found')$user=cookie('usr');
elseif($state=='loged')$user=ses('usr');
$ret.=self::reaction($state,$user);
return div($ret,'paneb','cbklg');}
}