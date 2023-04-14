<?php
class art extends appx{
static $private=0;
static $a='art';
static $db='arts';
static $cb='artwrp';
static $cols=['tit','txt','pub','edt'];
static $typs=['var','long','int','int'];
static $open=2;
static $conn=1;
static $title='/art';
static $descr='Articles';
static $tags=1;
static $image;

function __construct(){
$r=['a','db','cb','cols','conn'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function js(){return '';}

static function headers(){
head::add('csscode','');
head::prop('og:title',addslashes_b(self::$title));
head::prop('og:description',addslashes_b(self::$descr));
head::prop('og:image',self::$image);}

//edit
static function wsgbt($id){$ret=build::wsgbt('txt'.$id,1);
$ret.=self::editbt(['id'=>$id,'o'=>1]);
return div($ret,'sticky-edt','edt'.$id,'display:none;');}

//save
static function untitled($p){$id=$p['id']??''; 
$tit=$p['tit'.$id]??lang('title');
$txt=$p['txt'.$id]??tag('p','',lang('text'));
$r=['uid'=>ses('uid'),'tit'=>$tit,'txt'=>$txt,'pub'=>0,'edt'=>0];
$id=sql::sav(self::$db,$r); $p['id']=$id;
//$nid=sql::sav('desktop',[ses('uid'),'/documents/art','pop','art,call|id='.$id,'file-o',$tit,2]);//desk
return $id;}

static function reinit($p){$id=$p['id']??''; 
$tit=lang('title'); $txt=tag('p','',lang('text'));
$r=['tit'=>$tit,'txt'=>$txt,'pub'=>3,'edt'=>0];
sql::up2(self::$db,$r,$id);
return $id;}

static function create($p){
$r=['uid'=>ses('uid'),'tit'=>lang('title'),'txt'=>tag('p','',lang('text'))];//
$id=sql('id',self::$db,'v',$r); $p['opn']=1;
if($id){$p['id']=$id; sql::up(self::$db,'up',date('Y-m-d H:i:s',time()),$id);}
else $p['id']=self::untitled($p);
return self::edit($p);
return div(self::call($p),'',self::$cb);}

static function savetxt($p){
$id=$p['id']??''; $tit=$p['tit'.$id]??''; $conn=$p['conn']??'';
$p['txt']=$p['txt'.$id]??($p['txt-conn'.$id]??'');
$editable=self::perms(self::$db,$id,'edt');
if(!$editable)return;
if($conn)$txt=$p['txt']; else $txt=conv::call($p);
if($tit){$tit=trim(strip_tags(delbr($tit,' ')));
	if(strlen($tit)>144)$tit=substr($tit,0,144);
	sql::up(self::$db,'tit',$tit,$id); return $tit;}
if($txt)sql::up(self::$db,'txt',trim($txt),$id,'id');
if($conn)return self::play($p);
return conn::call(['msg'=>$txt,'ptag'=>1]);}

//js
static function playconn($p){//from utils.js
$ret=sql('txt',self::$db,'v',$p['id']);
$ret=conn::call(['msg'=>$ret,'mth'=>'minconn','ptag'=>1]);
return $ret;}

static function editbt($p){$id=$p['id']; $o=$p['o'];
if($o)$ret=btj(langpi('save'),atj('editbt',$id),'btsav');
else $ret=btj(langpi('edition'),atj('editbt',$id),'btsav');
$ret.=bj('popup|art,editconn|id='.$id,ico('edit'),'btn');
return $ret;}

static function modiftxt($p){
$id=$p['id']??''; $rid=$p['rid']??'';
$d=$p[$rid]??''; $d=cleanconn($d);
sql::up(self::$db,'txt',$d,$id);
return conn::call(['msg'=>$d,'ptag'=>1]);}

static function editconn($p){$id=$p['id']??'';
$txt=sql('txt',self::$db,'v',$id); $rid=randid('art');
$ret=bj('txt'.$id.'|art,modiftxt|id='.$id.',rid='.$rid.'|'.$rid,langp('save'),'btsav').br();
$ret.=build::connbt($rid).textarea($rid,$txt,'64','22','','console');
return $ret;}

//appx
static function edit($p){
return parent::edit($p);}

//play
static function build($p){$lnk='';//extract($p);
$rp=['id','rid','uid','name','date','pub','edt','ptagg','tit','txt'];
[$id,$rid,$uid,$name,$date,$pub,$edt,$ptg,$tit,$txt]=vals($p,$rp);
$editable=parent::permission($uid,$edt);//perms=0:no,1:net,2:clan,3:clan,4:owner
$date=lk('/art/'.substr(md5($id),7,7),picto('url').$date,'grey');//
//$rtg=sql::inner('ref','tags_r','tags','bid','rv',['app'=>'art','aid'=>$id]);
$rtg=sql::inr('ref',[['tags_r','bid','tags','id']],'rv',['app'=>'art','aid'=>$id]);
$tags=picto('tags').implode(' ',$rtg);
//$lnk=lk('/art/'.$id,langpi('url'),'btn',1);
$prmb=['id'=>'tit'.$id,'class'=>'editoff','contenteditable'=>$editable?'true':'false'];
if($editable){$prmb['onclick']=atj('editxt',['tit',$id]);
	$prmb['onblur']=atj('savtxt',['tit',$id]);
	//$lnk=btj(langpi('restore'),atj('restore_art',$id),'btn');
	//$lnk=popup('art,edit|id='.$id,ico('edit'));
	$lnk=span(self::editbt(['id'=>$id,'o'=>0]),'','bt'.$id);}
//elseif(val($p,'opn'))$ret['mnu']=bj(self::$cb.'|art,stream|rid='.$p['rid']??'',langp('back'),'btn');
if($edt)$edd=span('('.lang(appx::$privedt[$edt]).')','small').' '; else $edd='';
//$tags=admin_tags::call(['id'=>$id,'a'=>self::$a,'lg'=>lng(),'edt'=>$edt]);
$ret['ref']=div($edd.' '.$lnk,'sticky right');//$tags.
$ret['t']=tag('h1',$prmb,$tit);
//$ret['by']=lk('/@'.$name,'@'.$name,'btxt',1);
$ret['by']=popup('profile,call|usr='.$name,'@'.$name,'btlk',[],1).' '.span($date,'small grey').' '.span($tags,'small');
$ret['edit']=self::wsgbt($id);
//$ret['edit']=span(build::connbt($p['id']),'connbt','edt'.$id,'display:none;');
$prm=['id'=>'txt'.$id,'class'=>'editoff','contenteditable'=>'off'];
if($editable){$prm['ondblclick']=atj('editbt',[$id,1]);//
	//$prm['onblur']=atj('savtxt',['txt',$id]);//not work with wsyg
	//$prm['onblur']=atj('editbt',$id);
	$prm['onkeypress']='backsav(event,\''.$id.'\')';}
//eco(($txt));
$txt=conn::call(['msg'=>$txt,'ptag'=>1]);//$ptg
$rtx=tag('div',$prm,$txt);
$ret['m']=div($rtx,'article txt');
self::$title=$tit;
self::$descr=etc(strip_tags($txt));
if(isset(conn::$imgs[0]))self::$image=imgroot(conn::$imgs[0],'full');
return implode('',$ret);}

static function play($p){$id=$p['id']??''; //p($p);
$cols='name,uid,tit,txt,DATE_FORMAT('.self::$db.'.up,"%d/%m/%Y") as date,pub,edt';
if($id)$r=sql::inner($cols,self::$db,'login','uid','ra','where '.self::$db.'.id='.$id);
//if($id)$r=sql::join($cols,'login',self::$db,'uid','ra',$id);
if(isset($r))$p=merge($p,$r);
$ret=self::build($p);
$apf=val($p,'appFrom');//$apf && 
if($p['id']){tlex::$title=self::$title;//meta
	tlex::$descr=self::$descr;
	tlex::$image=self::$image;}
return div($ret,'content','art'.$id,'');}

static function brut($p){$id=$p['id']??'';
$cols='name,tit,txt,DATE_FORMAT('.self::$db.'.up,"%d/%m/%Y") as date,pub,edt';
if($id)$r=sql::inner($cols,self::$db,'login','uid','ra','where '.self::$db.'.id='.$id);
$ret=conn::call(['msg'=>$r['txt'],'ptag'=>1]);
return $ret;}

//stream
static function stream($p){
return parent::stream($p);}

//call
static function read($p){return self::play($p);}//old

static function txt($p){$id=$p['id']??'';
if($id)$txt=sql('txt',self::$db,'v',$id);
if($txt)return conn::call(['msg'=>$txt,'ptag'=>1]);}

static function tit($p){$id=$p['id']??'';
if($id)return sql('tit',self::$db,'v',$id);}

static function call($p){
$id=$p['id']??($p['p1']??'');
return parent::call($p);
$p['id']=sql('id',self::$db,'v',$id);
if($p['id']){
	$readable=self::perms(self::$db,$id,'pub');
	if(!$readable)$ret=help('access not granted','board');
	else $ret=self::play($p);}
else $ret=help('article not exists','board');
return div($ret,'',self::$cb);}

static function com($p){
return parent::com($p);}

#content
static function content($p){
//self::install();
return parent::content($p);}

//api
static function api($p){$id=$p['id']??''; $cn=$p['conn']??'';
$a=self::$a; $cols=implode(',',self::$cols); //$cols=sql::cols($db,1,0);
$r=sql('uid,'.$cols.',timeup',self::$db,'ra',$id); $txt=$r['txt'];
if(!$cn)$txt=conn::com($txt,1);
$txt=str_replace('/img/full',host(1).'/img/full',$txt);
$ra=['title'=>$r['tit'],'content'=>$txt,'date'=>$r['time'],'author'=>usrid($r['uid'])];
if($r)return json_enc($ra,true);}
}
?>