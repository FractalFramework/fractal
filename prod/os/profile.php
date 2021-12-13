<?php
class profile{
static $private=1;
static $db='profile';
static $default_clr='1da1f2';
static $roles=['human','worker','publication','group','services','website','non-terrestrial','admin'];//

//install
static function install(){
sqlcreate(self::$db,['puid'=>'int','pusr'=>'var','pname'=>'var','status'=>'var','clr'=>'var','avatar'=>'var','banner'=>'var','web'=>'var','gps'=>'var','location'=>'var','privacy'=>'int','oAuth'=>'var','ntf'=>'int','role'=>'int'],1);}

static function injectJs(){return '';}
static function headers(){}

#tools
static function default_clr(){return clrand();}

static function init_clr($p){$usr=$p['usr'];
$clr=sesr('clr',$usr); if($clr)return $clr;
$clr=sql('clr',self::$db,'v','where pusr="'.$usr.'"');
if(!$clr)$clr=self::default_clr();
return sesr('clr',$usr,$clr);}

//privacy
static function privbt($p){$state=$p['privacy']; $sav=$p['sav']??'';
if($sav){$state=$state==1?'0':'1';
	sqlup(self::$db,'privacy',$state,ses('uid'),'puid');}
if($state==1){$ic='toggle-on'; $bt='private'; $hlp=help('privacy_on','alert');}
else{$ic='toggle-off'; $bt='public'; $hlp=help('privacy_off','valid');}
return bj('prvc|profile,privbt|sav=1,privacy='.$state,ico($ic,22).lang($bt)).div($hlp);}

//notifs
static function ntfbt($p){$state=$p['ntf']; $sav=$p['sav']??'';
//$r=valk($r['ntf'],[0,1,2,3]);
if($sav){$state=$state==1?'0':'1';
	sqlup(self::$db,'ntf',$state,ses('uid'),'puid');}
if($state==0){$ic='toggle-on'; $bt='on'; $hlp=help('notifs_on','valid');}
else{$ic='toggle-off'; $bt='off'; $hlp=help('notifs_off','alert');}
return bj('prnt|profile,ntfbt|sav=1,ntf='.$state,ico($ic,22).lang($bt)).div($hlp);}

//oAuth
static function oAuthsav($p){$ret=keygen::build([]);
if($id=$p['id'])sqlup(self::$db,'oAuth',$ret,$id);
return $ret;}

static function oAuth($p){
$srv=$_SERVER['HTTP_HOST']; $oauth=$p['oAuth'];
$ret=span($oauth,'grey','oath').' ';
$ret.=bj('oath|profile,oAuthsav|id='.$p['id'],langp('gen oAuth'),'btn').' ';
$ret.=tag('h4','',lang('call timeline'));
$ret.=div('http://'.$srv.'/api/tlex/tm:'.ses('user'),'console');
$ret.=tag('h4','',lang('call id'));
$ret.=div('http://'.$srv.'/api/tlex/id:312','console');
$ret.=tag('h4','',lang('post telex'));
$ret.=div('http://'.$srv.'/api.php?oAuth:'.$oauth.'&msg:hello','console');
return $ret;}

#edit
static function modifpass($p){
$op=$p['oldpsw']??''; $np=$p['newpsw']??'';
if($op && $np){
	$ok=sql('id','login','v','where id='.ses('uid').' and password=password("'.$op.'")');
	if($ok){
	qr('update login set password=password("'.$np.'") where id="'.ses('uid').'"');
	//update('login','password','password("'.$np.'")',ses('uid'));
		return help('new password saved');}}
$ret=input_label('oldpsw','',lang('old password')).br();
$ret.=input_label('newpsw','',lang('new password')).br();
$ret.=bj('mdfp|profile,modifpass||oldpsw,newpsw',lang('save'),'btsav');
return $ret;}

static function deleteaccount($p){$ret='';
$prm='rmprf|profile,deleteaccount|id='.$p['id'];
$open=sql('auth','login','v','where name="'.ses('user').'"');
if($p['confirm']??''){
	//sqlup('profile','privacy',2,ses('uid'),'puid');
	sqlup('login','auth',1,ses('uid'));
	return help('account disactivated');}
elseif($p['del']??''){$prm.=',confirm=1';
	$ret.=help('tlex_remove_account','alert').br();
	$ret.=bj($prm,langp('confirm deleting'),'btdel');}
elseif($p['restore']??''){
	sqlup('login','auth',2,ses('uid'));
	$ret.=bj($prm.',del=1',langp('remove account'),'btdel');}
elseif($open==1)$ret.=bj($prm.',restore=1',langp('restore account'),'btdel');
else $ret.=bj($prm.',del=1',langp('remove account'),'btdel');
return div($ret,'','rmprf');}

//gps
static function gpsav($p){$gps=$p['gps'];
$id=sql('id',self::$db,'v','where puid='.ses('uid'));
sqlup(self::$db,'gps',$gps,$id);
if($gps)$loc=gps::com(['coords'=>$gps]); else $loc='';
sqlup(self::$db,'location',$loc,$id);
return self::gps(['pusr'=>ses('user'),'gps'=>$gps,'location'=>$loc]);}

static function gps($r){$ret='';
if($gps=$r['gps'] && $loc=$r['location'])
	$ret=popup('map,call|coords='.$gps,pic('location').$loc,'btsav');
elseif($r['pusr']==ses('user'))
	$ret=btj(span(pic('location'),'','gpsloc'),'geo()','grey');
return $ret;}

//mail_edit
static function mail_edit($p){
if($p['sav']??''){sqlup('login','mail',$p['mail'],ses('uid')); sez('mail',$p['mail']);}
$mail=sql('mail','login','v',['id'=>ses('uid')]);
$ret=bj('prml,,z|profile,mail_edit|sav=1|mail',langpi('save'),'btsav').' ';
$ret.=input_pic('mail',$mail,lang('mail',1),'mail',1);
return $ret;}

#banner		
/*static function banner_save($p){$f=$p['bkgim'];
if(substr($f,0,4)=='http')$f=saveimg($f,'ban','300','100');
sqlup(self::$db,'banner',$f,ses('uid'),'puid');
return self::standard(['usr'=>ses('user'),'uid'=>ses('uid')]);}*/

static function banner_edit($p,$sz){
$im=$p['banner']; $usr=$p['pusr'];
//$f='/img/medium/'.$im; if(is_file($f))$ret.=img($f).br();
$ret=bj('prfl|profile,banner_save|usr='.$usr.',sz='.$sz.'|bkgim',langpi('save'),'btsav').' ';
$ret.=input_pic('bkgim',$im,lang('banner',1),'img',1);
$ret.=upload::img('bkgim');
return $ret;}

static function banner($r,$sz){
$ban='img/full/'.$r['banner']; $clr=$r['clr']??'efddfe';
if(is_file($ban))$sty='background-image:url(/'.$ban.');';
else $sty='background-image:linear-gradient(#97c2ff,#'.$clr.');';
$st=$r['cntban']??'';
$tit=$st?div($st,'bantit'):'';
if($sz=='small')$c='banner_small'; elseif($sz=='simple')$c='banner_simple'; else $c='banner';
$ret=div($tit,$c,'',$sty);
//imgup($ban,$ret) 
return $ret;}

#avatar
static function avatar_im($im,$sz){//mini,full
$f='img/'.$sz.'/'.$im; if(!is_file($f))$f=imgthumb($im);
return $f;}

/*static function avatar_save($p){$f=$p['avtim']; $usr=$p['pusr'];
if(substr($f,0,4)=='http')$f=saveimg($f,'avt','140','140');
sqlup(self::$db,'avatar',$f,ses('uid'),'puid'); $p['avatar']=$f;
return self::avatar($p,'big');}*/

static function avatar_edit($p,$sz){
$im=$p['avatar']; $usr=$p['pusr'];
//$f='/img/mini/'.$im; if(is_file($f))$ret=img($f).br();
$prm='pusr='.$usr.',sz='.$sz.',clr='.diez($p['clr'],1);
$ret=bj('avt|profile,avatar_save|'.$prm.'|avtim',langpi('save'),'btsav').' ';
$ret.=input_pic('avtim',$im,lang('avatar',1),'card',1);
$ret.=upload::img('avtim');
return $ret;}

/*
static function avatar($p,$sz=''){
$usr=$p['pusr']; $im=$p['avatar']; $clr=$p['clr'];//diez
$clr=self::init_clr(['usr'=>$p['usr']]);
if($sz=='big')$c='avatarbig'; elseif($s=='small')$c='avatarsmall'; else $c='avatar';
$f=self::avatar_im($im,$sz=='big'?'full':'mini');
$bt=self::divim($f,$$c,$clr);
$ret=imgup(self::avatar_im($im,'full'),$bt);
return $ret;}*/

static function avatar($p,$sz=''){
$usr=$p['pusr']; $im=$p['avatar']; $clr=$p['clr'];//diez
$f=self::avatar_im($im,$sz=='big'?'full':'mini');
$bt=self::divim($f,$sz=='big'?'avatarbig':'avatar',$clr);
$ret=imgup(self::avatar_im($im,'full'),$bt);
return $ret;}

static function avatarsmall($usr){
$clr=self::init_clr(['usr'=>$usr]);
$im=sql('avatar','profile','v',['pusr'=>$usr]);
$f=self::avatar_im($im,'mini',$clr);
return self::divim($f,'avatarsmall',$clr);}

static function avatar_big($p){$im=$p['im'];
$f=self::avatar_im($im,'full');
return img($f);}

static function divim($f,$c,$clr){
if($clr)$clr='background-color:#'.$clr.'; ';
return span('',$c,'',$clr.'background-image:url(\'/'.$f.'\');');}

#status
static function status_save($p){$id=$p['id'];
$rk=['pname','status','web','gps','clr','role','avatar','banner'];
$r=valk($p,$rk); $r=validcols($r,self::$db);//ses('clr'.$p['usr'],$p['clr']);
if(substr($r['avatar'],0,4)=='http')$r['avatar']=saveimg($r['avatar'],'avt','140','140');
if(substr($r['banner'],0,4)=='http')$r['banner']=saveimg($r['banner'],'ban','300','100');
sqlups(self::$db,$r,['puid'=>ses('uid')]);
sesr('clr',$p['usr'],$r['clr']);
return self::standard($p);}

//authorize levels//by
static function roles($d){
/*$w='inner join login on '.self::$db.'.puid=login.id 
inner join tlex_ab on login.id=tlex_ab.usr 
where ab="'.ses('uid').'"';*/
//$r=sql('puid,login.name,role',self::$db,'rr',$w); p($r);
if(auth(6))$n=8; else $n=7;
foreach(self::$roles as $k=>$v)if($k<$n)$r[]=$v;
return $r;}

static function status_edit($p){$pusr=$p['pusr'];
$ra=['id','pname','status','role','web','clr','avatar','banner'];
list($id,$pname,$status,$role,$web,$clr,$avatar,$banner)=vals($p,$ra);
$ret=div(self::standard(['usr'=>$pusr]),'','banr');
//$ret.=bj('popup|profile,standard|usr='.$pusr,langp('visit card'),'btn').br();
$ret.=bj('banr,,z|profile,status_save|usr='.$pusr.'|id,pname,status,web,gps,clr,role,avatar,banner',langp('save'),'btsav').br();//prfl
foreach($ra as $k=>$v){
	if($v=='id')$ret.=hidden($v,$id);
	if($v=='pname')$ret.=input_pic($v,$pname,lang('name',1),'user').br();
	if($v=='status')$ret.=tag('textarea',['id'=>$v,'placeholder'=>lang('presentation',1),'maxlength'=>255],$status).br();
	if($v=='role')$ret.=select($v,self::roles($v),$role,0,1).br();
	if($v=='web')$ret.=input_pic($v,$web,lang('web',1),'link').br();
	if($v=='clr')$ret.=inpclr($v,$clr,30,1,1).br();
	if($v=='avatar')$ret.=div(input_pic($v,$avatar,lang($v,1),'img',1).upload::img($v),'');
	if($v=='banner')$ret.=div(input_pic($v,$banner,lang($v,1),'img',1).upload::img($v),'');}
$ret.=span('','','cklr');
return $ret;}

static function username($p){
$usr=$p['pusr']??ses('user'); $name=$p['pname'];
if($p['privacy'])$name.=ico('lock',14,'grey');
return tag('a',['href'=>'/@'.$usr,'title'=>'@'.$usr,'class'=>'usrnam'],$name);}

/**/static function opening($p){$r=[];
if($op=$p['op'])cookie('opening',$op);
else $op=cookie('opening'); if(!$op)$op='apps';
$r=['posts','','apps','docs','datas'];
if(ses('user')){$role=sql('role','profile','v',['pusr'=>ses('user')]);
	if($role==5)array_unshift($r,'site');}
return div(batch($r,'edtop|profile,opening|op=$v',$op),'list','edtop');}

#edit
static function edit($p){
$usr=$p['usr']??ses('user');
//$usr=$p['usr']; $own=isown($usr); if(!$own)$usr=ses('user');
//$cols='id,puid,pusr,pname,status,clr,avatar,banner,web,gps,location,privacy,oAuth,ntf,role';
//$r=sql($cols,self::$db,'ra','where puid="'.ses('uid').'"'); //pr($r);
$r=self::datas($usr);
$t=tag('h2','',langp('identity'));
$reb=div(self::status_edit($r),'');
//$reb.=div(self::banner_edit($r,$p['sz']),'');
//$reb.=div(self::avatar_edit($r,$p['sz']),'');
if($r['gps'])$del=bj('prfloc|profile,gpsav',pic('delete')); else $del='';
$reb.=div(lang('location').' '.self::gps($r).$del,'','prfloc');
$ret=div($t.$reb,'board');
//$t=tag('h2','',langp('boot'));
//$ret.=div($t.self::opening($p),'board');
$t=tag('h2','',langp('mail'));
$reb=div(self::mail_edit($r),'','prml');
$ret.=div($t.$reb,'board');
$t=tag('h2','',langp('notifications'));
$ret.=div($t.div(self::ntfbt($r),'','prnt'),'board');
$t=tag('h2','',langp('privacy'));
$ret.=div($t.div(self::privbt($r),'','prvc'),'board');
$t=tag('h2','',langp('Api'));//if(auth(6)){}
$ret.=div($t.self::oAuth($r),'board');
$t=tag('h2','',langp('twitterApi')).hlpbt('twitterApi');
$ret.=div($t.app('admin_twitter'),'board');
$t=tag('h2','',langp('modif password'));
$ret.=div($t.div(self::modifpass($r),'','mdfp'),'board');
$t=tag('h2','',langp('remove account'));
$ret.=div($t.self::deleteaccount($r),'board');
return div($ret,'','');}

static function follow($p){
$usr=$p['usr']; $sz=$p['sz']??''; $wait=$p['wait']??'';
if($p['approve']??''){
	$bt=bj('cbck|tlxf,follow|approve='.$usr,langp('approve'),'btsav');
	$bt.=bj('cbck|tlxf,follow|refuse='.$usr,langp('refuse'),'btdel');
	$ret=div($bt,'followbt');}
else $ret=tlex::followbt(['usr'=>$usr,'sz'=>$sz,'wait'=>$wait]);
return $ret;}

#build
static function datas($usr){
$cols='id,puid,pusr,pname,status,clr,avatar,banner,web,gps,location,privacy,oAuth,ntf,role';
$r=sql($cols,self::$db,'ra','where pusr="'.$usr.'"');
if(!$r && $usr==ses('user'))$r=self::create($usr);
$clr=sesr('clr',$usr);
if(!$r['clr'])$r['clr']=sesrif('clr',$usr,$clr?$clr:self::default_clr());
return $r;}

static function build($p){
list($usr,$uid,$wait,$sz)=vals($p,['usr','uid','wait','sz']);//modes
if(!$usr)$p['usr']=usrid($uid);///si ne reçoit que uid...
$r=self::datas($p['usr']); //$r['cntban']=$r['status'];
$ret=valk($r,['puid','pusr','role']);
//$wait=sql('wait','tlex_ab','v','where ab="'.$uid.'"');//pending
$ret['banner']=div(self::banner($r,$sz),'banr');
$ret['avatar']=span(self::avatar($r,$sz),'','avt');
if(ses('user') && ses('user')!=$p['usr'])$ret['follow']=self::follow($p);
else $ret['follow']='';//bj('cbck|profile,edit',langp('profile'),'btn');
//$ret['subscribe']=tlex::subscribt($p['usr'],$uid,$r['role']);
$ret['username']=self::username($r);
$rol=$r['role']??0; $rol=profile::$roles[$rol]; $ret['role']=span(langp($rol),'role');
if($web=$r['web'])$ret['site']=lk(http($web),ico('link',12).$web,'grey nfo',1); else $ret['site']='';
$ret['gps']=self::gps($r);
if($sz!='small')$ret['status']=div($r['status'],'bantxt'); else $ret['status']='';
if($sz!='small')$ret['usrnfo']=$ret['role'].$ret['site'].$ret['gps'].$ret['status']; else $ret['usrnfo']='';
return $ret;}

#abs
static function abs($p){
$uid=$p['uid']; $usr=$p['usr']; $rp=['data-prmtm'=>'no'];
$n1=sql('count(id)','tlex_ab','v','where usr="'.$uid.'"');
$n2=sql('count(id)','tlex_ab','v','where ab="'.$uid.'"');
$bt=langph('notifications').' '.span('','nbntf','tlxntf'); //$rb=['data-prmtm'=>'ntf=1'];
//$ret.=toggle('cbck|tlex,read|ntf=1',$bt,$c,$rb,'',1);
$ret.=tlex::loadtm('ntf=1',$bt);
$bt=langph('subscriptions').' '.span($n1,'nbntf','tlxsub'); //$rb=['data-prmtm'=>'ntf='];
$ret.=toggle('cbck|tlxf,subscriptions|type=ption,usr='.$usr,$bt,$c,$rp);
$role=sql('role','profile','v','where pusr="'.$usr.'"');
$bt=langph($role?'members':'subscribers').' '.span($n2,'nbntf','tlxabs');
$ret.=toggle('cbck|tlxf,subscriptions|type=ber,usr='.$usr.'|tlxsub',$bt,$c,$rp);
$ret.=hidden('tlxabsnb',$n2);//.hidden('tlxsubnb',$n1)
$ret.=toggle('cbck|profile,edit',langph('profile'),$c,$rp);
return $ret;}

#render
static function small($p){
$usr=$p['usr']; $p['sz']='small';
$r=self::datas($usr); 
//$r['cntban']=tlex::avatar($r);
$r['cntban']=self::username($r);
$ret=self::banner($r,'',1);
return $ret;}

static function standard($p){
$p['sz']='simple'; $r=self::build($p);
$ret=div($r['username'].$r['usrnfo']);
$ret=div($r['banner'].$r['avatar'].$r['follow'].$ret,'');//,'','prfl'
return div($ret,'profile');}

static function big($p){$usr=$p['usr'];
$r=self::build(['usr'=>$usr,'sz'=>'big']); $ret='';
$ret=div($r['follow'],'right');
$ret.=$r['avatar'].$r['username'].$r['usrnfo'];
return div($r['banner'].div($ret,'subban'),'bigprofile');}

static function simple($p){$usr=$p['usr'];
$r=self::build(['usr'=>$usr,'sz'=>'simple']); $ret='';
//if(ses('user')!=$usr)$ret=div($r['follow'],'right');
$ret.=div($r['username'].$r['status'],'bloc_content','prfl');
return div($r['banner'].div($ret,'subban'),'','banr');}

//create	
static function create($usr){//$uid=ses('uid');
$id=sql('id',self::$db,'v','where pusr="'.$usr.'"');
$uid=idusr($usr);
if(!$id && $usr && $uid==ses('uid')){
	$kg=keygen::build();
	//$clr=sesif('clr'.$usr,self::default_clr());
	$clr=sesrif('clr',$usr,profile::default_clr());
	$r=['puid'=>$uid,'pusr'=>$usr,'pname'=>$usr,'status'=>'','clr'=>$clr,'avatar'=>'','banner'=>'','web'=>'','gps'=>'','location'=>'','privacy'=>0,'oAuth'=>$kg,'ntf'=>0,'role'=>0];
	$r['id']=sqlsav(self::$db,$r);
	return $r;}}

//com
static function com($usr,$o=''){
if(is_numeric($usr))$w='puid'; else $w='pusr';
$r=sql('pname,avatar,status,clr',self::$db,'rw',[$w=>$usr]);
$f=self::avatar_im($r[1],'mini');
$ret=self::divim($f,'avatarsmall',diez($r[3]));
if($o==2)$ret.=span($r[0],'btxt');
if(!$o)$ret.=lk('/@'.$usr,$r[0],'btxt');
return $ret;}

static function call($p){$ret='';
$usr=$p['usr']??''; $uid=$p['uid']??''; $sz=$p['sz']??'';
list($usr,$uid)=self::usrid($usr,$uid);
if($usr){$rp=self::build($p);
	//$usn=div($rp['username'].$rp['status'],'bloc_content');
	$usn=$rp['username'].$rp['usrnfo'];
	if(ses('user')!=$usr)$subsc=div($rp['follow'],'right'); else $subsc='';
	$ret=span($rp['banner'].$subsc.$rp['avatar'].$usn,'','prfl');
	return span($ret,'profile');}}

static function name($uid,$o=''){
$ret=sql('pname',self::$db,'v','where puid="'.$uid.'"');
if($o)$usr=sql('name','login','v','where id="'.$uid.'"');
if($o)$ret=lk('/@'.$usr,$ret,'btxt');
return $ret;}

static function usrid($usr,$uid){
if($usr && !$uid)$uid=idusr($usr);
if(!$usr && $uid)$usr=usrid($uid);
return [$usr,$uid];}

//interface
static function content($p){
//self::install();
$usr=$p['user']??ses('user'); $id=$p['id']; $uid=idusr($usr);
if(ses('uid'))self::create($usr);
$ret=self::standard(['id'=>$id,'usr'=>$usr]);
return $ret;}
}
?>