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

static function injectJs(){return '';}

static function headers(){
add_head('csscode','');
add_prop('og:title',addslashes_b(self::$title));
add_prop('og:description',addslashes_b(self::$descr));
add_prop('og:image',self::$image);}

//edit
static function wsgbt($id){$ret=build::wsgbt('txt'.$id,1);
$ret.=self::editbt(['id'=>$id,'o'=>1]);
return div($ret,'sticky-edt','edt'.$id,'display:none;');}

//save
static function untitled($p){$id=$p['id']??''; 
$tit=val($p,'tit'.$id,lang('title'));
$txt=val($p,'txt'.$id,tag('p','',lang('text')));
$savr=['uid'=>ses('uid'),'tit'=>$tit,'txt'=>$txt,'pub'=>3,'edt'=>0];
$id=sqlsav(self::$db,$savr); $p['id']=$id;
$com='art,call|id='.$id;//desk
//$nid=sqlsav('desktop',[ses('uid'),'/documents/art','pop',$com,'file-o',$tit,2]);
return $id;}

static function create($p){
$r=['uid'=>ses('uid'),'tit'=>lang('title'),'txt'=>tag('p','',lang('text'))];//
$id=sql('id',self::$db,'v',$r);
if($id){$p['id']=$id; sqlup(self::$db,'up',date('Y-m-d H:i:s',time()),$id);}
else $p['id']=self::untitled($p);
return div(self::call($p),'',self::$cb);}

static function savetxt($p){
$id=$p['id']??''; $tit=val($p,'tit'.$id);
$p['txt']=val($p,'txt'.$id,val($p,'txt-conn'.$id));
$editable=self::perms(self::$db,$id,'edt');
if(!$editable)return;
if(val($p,'conn'))$txt=$p['txt']; else $txt=conv::call($p);
if($tit){$tit=trim(strip_tags(delbr($tit,' ')));
	if(strlen($tit)>144)$tit=substr($tit,0,144);
	sqlup(self::$db,'tit',$tit,$id); return $tit;}
if($txt)sqlup(self::$db,'txt',trim($txt),$id,'id');
if(val($p,'conn'))return self::play($p);
return conn::call(['msg'=>$txt,'ptag'=>1]);}

//js
static function playconn($p){//from utils.js
$ret=sql('txt',self::$db,'v',$p['id']);
$ret=conn::call(['msg'=>$ret,'mth'=>'minconn','ptag'=>1]);
return $ret;}

static function editbt($p){$id=$p['id'];
if(val($p,'o'))$ret=btj(langpi('save'),atj('editbt',$id),'btsav');
else $ret=btj(langpi('edition'),atj('editbt',$id),'btsav');
$ret.=bj('popup|art,editconn|id='.$id,ico('edit'),'btn');
return $ret;}

static function modiftxt($p){$id=$p['id']??'';
$d=val($p,$p['rid']??''); $d=cleanconn($d);
sqlup(self::$db,'txt',$d,$id);
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
static function build($p){
$id=$p['id']??''; $name=val($p,'name'); $uid=$p['uid']??'';
$date=val($p,'date'); $pub=val($p,'pub'); $edt=val($p,'edt'); $ptg=val($p,'ptag',1);
$title=$p['tit']??''; $txt=val($p,'txt'); $rid=$p['rid']??''; $lnk='';
$editable=parent::permission($uid,$edt);//perms=0:no,1:net,2:clan,3:clan,4:owner
$date=lk('/art/'.substr(md5($id),7,7),picto('url').$date,'grey');//
//$rtg=sqlin('ref','tags_r','tags','bid','rv',['app'=>'art','aid'=>$id]);
$rtg=sqlrin('ref',[['tags_r','bid','tags','id']],'rv',['app'=>'art','aid'=>$id]);
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
$ret['t']=tag('h1',$prmb,$title);
//$ret['by']=lk('/@'.$name,'@'.$name,'btxt',1);
$ret['by']=bubble('profile,call|usr='.$name,'@'.$name,'btlk',1).' '.span($date,'small grey').' '.span($tags,'small');
$ret['edit']=self::wsgbt($id);
//$ret['edit']=span(build::connbt($p['id']),'connbt','edt'.$id,'display:none;');
$prm=['id'=>'txt'.$id,'class'=>'editoff','contenteditable'=>'off'];
if($editable){$prm['ondblclick']=atj('editbt',[$id,1]);//
	//$prm['onblur']=atj('savtxt',['txt',$id]);//not work with wsyg
	//$prm['onblur']=atj('editbt',$id);
	$prm['onkeypress']='backsav(event,\''.$id.'\')';}
//eco(($txt));
$txt=conn::call(['msg'=>$txt,'ptag'=>$ptg]);
$rtx=tag('div',$prm,$txt);
$ret['m']=div($rtx,'article txt');
self::$title=$title;
self::$descr=etc(strip_tags($txt));
if(isset(conn::$imgs[0]))self::$image=imgroot(conn::$imgs[0],'full');
return implode('',$ret);}

static function play($p){$id=$p['id']??''; //p($p);
$cols='name,uid,tit,txt,DATE_FORMAT('.self::$db.'.up,"%d/%m/%Y") as date,pub,edt';
if($id)$r=sqlin($cols,self::$db,'login','uid','ra','where '.self::$db.'.id='.$id);
//if($id)$r=sqljoin($cols,'login',self::$db,'uid','ra',$id);
if(isset($r))$p=merge($p,$r);
$ret=self::build($p);
$apf=val($p,'appFrom');//$apf && 
if($p['id']){tlex::$title=self::$title;//meta
	tlex::$descr=self::$descr;
	tlex::$image=self::$image;}
return div($ret,'content','art'.$id,'');}

static function brut($p){$id=$p['id']??'';
$cols='name,tit,txt,DATE_FORMAT('.self::$db.'.up,"%d/%m/%Y") as date,pub,edt';
if($id)$r=sqlin($cols,self::$db,'login','uid','ra','where '.self::$db.'.id='.$id);
$ret=conn::call(['msg'=>$r['txt'],'ptag'=>1]);
return $ret;}

//stream
static function stream($p){
return parent::stream($p);}

//call
static function read($p){return self::play($p);}//old

/*static function preview($p){$id=$p['id']??'';
$r=sql('tit,txt',self::$db,'rw',$id); if(!$r)return; $dots='';
$t=popup('art,call|id='.$id,span(pic('art',32).' '.$r[0]),'apptit');
//$t.=lk('/art/'.$id,pic('url'),'btxt');
$txt=conn::call(['msg'=>$r[1],'app'=>'conn','mth'=>'noconn','ptag'=>'no']);
$max=strlen($txt); if($max>140)$max=strpos($txt,'.',140);
if($max>240){$max=strpos($txt,' ',140); $dots='...';}
$txt=substr($txt,0,$max+1).$dots;
$ret=div($t,'app').div($txt,'stxt').div('','clear');
return div($ret,'appicon');}*/

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
$a=self::$a; $cols=implode(',',self::$cols); //$cols=sqlcols($db,2);
$r=sql('uid,'.$cols.',timeup',self::$db,'ra',$id); $txt=$r['txt'];
if(!$cn)$txt=conn::com($txt,1);
$txt=str_replace('/img/full',host(1).'/img/full',$txt);
$ra=['title'=>$r['tit'],'content'=>$txt,'date'=>$r['time'],'author'=>usrid($r['uid'])];
if($r)return json_enc($ra,true);}
}
?>