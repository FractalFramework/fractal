<?php

class auth {
static $db='login';
static $mailadmin='bot@logic.ovh';
static $noregister='0';

static function install(){//,'priv'=>'int'
$r=['name'=>'var','password'=>'var','auth'=>'int','mail'=>'var','ip'=>'var'];
sql::create(self::$db,$r);}

static function updateIp($uid){
$r=['name'=>'var','password'=>'var','ip'=>'var'];
sql::up(self::$db,'ip',ip(),$uid);}

static function logout(){
unset($_SESSION['usr']); unset($_SESSION['uid']);
unset($_SESSION['auth']); unset($_SESSION['clr']);
setcookie('usr','',0); setcookie('uid','',0);
if(self::$noregister)return 'loged_private';
else return 'loged_out';}

static function recovery($user,$mail){
$mail=sql('mail',self::$db,'v',['name'=>$user,'mail'=>$mail]);
if(!$mail)return 'unknown_user';
$id=sql('id',self::$db,'v',['name'=>$user]);
$rid=randid('rcvd'); ses('recoveryId',$id); ses('recoveryRid',$rid); ses('recoveryUsr',$user);
$title=lang('reset_pswd');
$msg=host(1).'/login/recovery:'.$rid;
mail::send($mail,$title,$msg,self::$mailadmin,'text');
return 'recovery_mailsent';}

static function register($user,$pass,$mail,$auth){$ip=ip();
if(!filter_var($mail,FILTER_VALIDATE_EMAIL))return 'register_fail_mail';
if(sql('id','login','v','where name="'.$user.'"'))return 'register_fail_aex';
$r=[$user,'PASSWORD("'.$pass.'")',$auth,strtolower($mail),$ip];//,'0'
$uid=sql::sav(self::$db,$r);
if($uid)self::activateSession($uid,$user,$auth);
self::activateCookie($uid,$user);
$title=lang('register');
if($uid>0){$msg=lang('register_success');
	mail::send($mail,$title,$msg,self::$mailadmin,'text');
	return 'register_ok';}
else return 'register_error';}

static function activateSession($uid,$user,$auth){
$mail=sql('mail','login','v',['id'=>$uid]); ses('mail',$mail);
sez('uid',$uid); sez('usr',$user); sez('auth',$auth);}

static function activateCookie($uid,$user){
cookie('uid',$uid); cookie('usr',$user);}

static function getUserFromCookie(){
return sql('id,name,auth',self::$db,'ra',['id'=>cookie('uid')]);}

static function logUserFromCookie($user){
$r=sql('id,name,auth',self::$db,'ra',['id'=>$user]);
if(cookie('usr')==$user)return $r;}

static function getUserFromIp(){
return sql('id,name,auth',self::$db,'ra',['ip'=>ip()]);}

static function logUserFromIp($usr){
return sql('id,auth',self::$db,'ra',['ip'=>ip(),'name'=>$usr]);}

static function getUserByUid($uid){
return sql('name',self::$db,'v',['id'=>$uid]);}

static function getUidOfUser($user){
return sql('id',self::$db,'v',['name'=>$user]);}

static function logon($uid,$user,$auth){
self::activateSession($uid,$user,$auth);
self::activateCookie($uid,$user);
return 'loged_ok';}

static function login($user='',$pass=''){
//self::install();
$uid=ses('uid'); //if($uid)return 'loged';
$user=str::normalize($user);
$pass=str::normalize($pass);
//$uid=cookie('uid'); if($uid)$state='cookie_found';//login with cookies
if(self::$noregister)$state='loged_private'; else $state='loged_out';
if($user){
	$uid=self::getUidOfUser($user);
	if($uid && $user && !$pass){//recognize
		if($ra=self::logUserFromCookie($user))//logUserFromIp
			$state=self::logon($ra['id'],$user,$ra['auth']);
		else{$ra=self::getUserFromCookie();//getUserFromIp
			if(isset($ra['name']) && $ra['name']==$user)
				$state=self::logon($ra['id'],$ra['name'],$ra['auth']);}}
	elseif($user && $pass){
		$sq=['name'=>$user,'password'=>'PASSWORD("'.$pass.'")'];
		$rb=sql('id,ip,auth',self::$db,'ra',$sq);
		if($rb){
			$state=self::logon($rb['id'],$user,$rb['auth']);
			if($rb['ip']!=ip())self::updateIp($uid);}
		elseif($uid)$state='bad_password';
		else $state='unknown_user';}
	else $state='unknown_user';}
elseif($uid)$state='loged';
return $state;}

static function autolog(){
$r=self::getUserFromCookie(); //p($r);
//if(!$r)$r=self::getUserFromIp();
if(isset($r['name']))self::activateSession($r['id'],$r['name'],$r['auth']);}

static function logbt($o=''){
if($o){$mode='menu'; login::$css='';} else{$mode='pagup'; login::$css='btn abbt';}
//if(!ses('time'))self::autolog();
$bt=ico('user').' '; $bt.=ses('usr')?ses('usr'):lang('login');
$call='login|auth=2,o='.$o;
if($o)return bubble($call,$bt,$c='',$o='');
return pagup($call,$bt,$c='',$o='');}

}
?>