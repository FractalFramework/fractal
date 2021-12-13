<?php
class tlxf{
static $private=0;
static $db='tlex';

//menu apps
static function apps($p){
$cuid=ses('cuid'); $b=$p['b']??'public'; $cat=$p['cat']??''; $auth=ses('auth'); if(!$auth)$auth=0; $ret='';
$dsp=ses('dskdsp',val($p,'display')); if(!$dsp)$dsp=ses('dskdsp',2); $c=$dsp==1?'licon':'cicon';
$ja='cbck,,1|tlxf,apps|b='.$b;
if($dsp==2)$bt=bj($ja.',display=1',langpi('icons'),'');
else $bt=bj($ja.',display=2',langpi('list'),'');
//$bt.=bj($ja,lang('all'),act($cat,''));
$r=applist::build($b);
if($r)foreach($r as $k=>$v){$dr=strend($v,'/'); $rb[$dr]='';
	if(method_exists($k,'com') && isset($k::$db))$call=$k.',com'; else $call=$k;
	$rp['title']=helpx($k.'_app'); $rp['data-u']=$k; $rp['data-cl']='cbck';
	$j='cbck,,,1|'.$call.'|headers=1'; if($k=='desktop')$j.=',cuid='.$cuid.',dir=/documents';
	$private=class_exists($k) && isset($k::$private)?$k::$private:0;
	if($cat==$dr or !$cat)$ok=1; else $ok=0;
	if($auth>=$private && $ok)$ret.=bj($j,pic($k).span(helpx($k)),$c,$rp,'');
	if($dsp==10 && $ok)$ret.=help($k.'_app');}
if(isset($rb))foreach($rb as $k=>$v)$bt.=bj($ja.',cat='.($k==$cat?'':$k),lang($k),act($cat,$k));
return div(div($bt,'tabs').$ret,'board');}

static function goodies($p){$ret='';
$r=applist::build('goodies'); p($r);
$r=['txt','pad','convert','keygen','clock','exif','oracle','pkr','randomizer','pi','phi','spectral','spitable','spitable2','spilog','biogen','weather2'];
foreach($r as $k=>$v)$ret.=bj('cbck,,,1|'.$v.(method_exists($v,'com')?',com':'').'|headers=1',pic($v).span($v),'cicon');
return div($ret,'board');}

static function tagapp($p){$ret=''; //return div(admin_tags::search($p),'board');
$r=sql('aid,app','tags_r','kv',['bid'=>$p['bid']]);
foreach($r as $k=>$v)if(class_exists($v))$ret.=pagup($v.',com|'.$k,pic($v).span($v::tit(['a'=>$v,'id'=>$k])),'licon');
return $ret;}

//keep
static function dsksav($p){//dir,type,com,picto,bt
[$id,$idv,$com,$d,$pub,$o,$ict,$tit]=vals($p,['id','idv','com','p1','pub','p2','ict','tit']);
$t=val($p,$ict,$tit); $uid=ses('uid'); if(!$pub)$pub=2; $ic='';
if($com=='img'){$ncom=$d; $t=struntil($d,'.'); $ic='image';}
elseif($com=='web'){$ncom='web,call|p1='.nohttp($d);}
elseif($com=='video'){$ncom='video,call|id='.$d; $ic='video';}
elseif($com=='art'){$ncom='art,call|id='.$d; $ic='newspaper-o';}//dont'change it!
elseif($com=='chat'){$ncom='chat|id='.$d; $ic='comments';}
elseif($com=='gps'){$ncom='map,call|coords='.$d; $ic='map';}
elseif($com=='poll')$ncom='poll,readtlx|id='.$d;
elseif($com=='slide')$ncom='slide,call|tid='.$d;
elseif($com=='tabler')$ncom='tabler,call|id='.$d;
elseif($com=='db')$ncom='db,call|f=usr/'.$d;
else{$ncom=$com.',call|id='.$d;}
$ic=$ic?$ic:$ic=icolg($com); $ty=$com=='img'?$com:'pop';
$ex=sql('id','desktop','v',['uid'=>$uid,'dir'=>'/documents/'.$com,'com'=>$ncom]);
if(!$ex){$nid=sqlsav('desktop',[$uid,'/documents/'.$com,$ty,$ncom,$ic,$t,$pub]);
$bt=div(lang('added to desktop'),'valid').div(ico($ic).' '.$t,'tit');}
else $bt=help('already exists','alert');
return desktop::content(['dir'=>'/documents/'.$com]);
return div(bj('popup|desktop|dir=/documents/'.$com,$bt,'',['onclick'=>atj('Close','popup')]),'');}

static function dskdel($p){
$com=val($p,'com'); $t=val($p,'tit'); $uid=ses('uid');
$ex=sql('id','desktop','v',['uid'=>$uid,'dir'=>'/documents/'.$com,'bt'=>$t]);
if($ex)sqldel('desktop',$ex);
return desktop::content(['dir'=>'/documents/'.$com]);}

static function keep($p){$ret=''; $ex=''; $txt='';
[$id,$idv,$com,$dir,$pic,$bt,$auth]=vals($p,['id','idv','conn','dir','pic','bt','auth']);
$ret=div(help('keep'),'btit');
if(!$dir){
	if($id)$txt=sql('txt',self::$db,'v',$id);
	if($txt)conn::call(['msg'=>$txt,'app'=>'conn','mth'=>'reader']); $r=conn::$obj;
	if($r)foreach($r as $kr=>$vr)foreach($vr as $k=>$v){$im=''; $pic=''; $t=''; list($p,$o)=$v;
	switch($kr){
	case('img'):$t=strend($p,'/'); $im=imgroot($t,'micro'); break;
	case('video'):$rt=web::metas($p); $t=isset($rt[0])?$rt[0]:nohttp($p);
		if(!empty($rt[2]))$im=imgroot($rt[2],'micro'); $pic='youtube'; break;
	case('web'):$rt=web::metas($p); $t=!empty($rt[0])?$rt[0]:nohttp($p);
		if(!empty($rt[2]))$im=imgroot($rt[2],'micro'); $pic='road'; break;
	case('chat'):$t=chat::tit(['id'=>$p]); $pic='comments'; break;
	case('art'):$t=art::tit(['id'=>$p]); $pic='newsparer-o'; break;
	case('gps'):$t=gps::com(['coords'=>$p]); $pic='map-marker'; break;
	//case('poll'):$t=poll::tit(['id'=>$p]); $pic=icolg('poll'); break;
	//case('slide'):$t=slide::tit(['id'=>$p]); $pic=icolg('slide'); break;
	default: $pic=icolg($kr);
		if(method_exists($kr,'tit'))$t=$kr::tit(['id'=>$p]); else $t=$p; break;}
	if($pic)$pic=ico($pic,24); if($im)$im=img('/'.$im,45); $rid=randid('imk');
	$logo=($im?$im:$pic).' '; $bt=pic('add2desktop').' ';
	$edit=input($rid,$t,'').' ';
	$bt=popup('tlxf,dsksav|com='.$kr.',p1='.nohttp($p).',p2='.$o.',ict='.$rid.'|'.$rid,$bt,'btsav');//',tit='.$t.
	$ret.=div($logo.$edit.$bt);}}
return div($ret,'bloc_content objects');}

#actions
static function app_action($obj){$r=prmr($obj); $ret='';
if($r)foreach($r as $k=>$v)if(method_exists($k,'own')){$a=new $k;
	if($k::own($v))$ret.=popup($k.'|edit=1,id='.$v.',rid='.randid('edt'),pic('edit').lang($k));}
return $ret;}

static function actions($p){$ret=''; $sz=''; $us=ses('user');
$id=$p['id']; $idv=$p['idv']; $pr='pn'.$idv; $usr=$p['usr']; $uid=$p['uid'];
$lg=$p['lg']; $pv=$p['pv']; $lbl=$p['lbl']; $own=$usr==$us?1:0;
if($id)$txt=sql('txt',self::$db,'v',$id);
if($txt)$obj=conn::call(['msg'=>$txt,'app'=>'conn','mth'=>'appreader']); //pr($r=conn::$obj);
$ret.=toggle($pr.'|tlxf,editor|idv='.$idv.',to='.$usr.',ib='.$id,langp('reply',$sz));
$ret.=toggle($pr.'|tlxf,editor|idv='.$idv.',qo='.$id,langp('relay',$sz));
$ret.=toggle($pr.'|tlxf,share|id='.$id,langp('share',$sz));
if(conn::$obj)$ret.=toggle($pr.'|tlxf,keep|idv='.$idv.',id='.$id,langp('keep',$sz));//
if($own or auth(6))$ret.=toggle($pr.'|tlxf,redit|id='.$id,langp('modif'),'').' ';
else $ret.=toggle($pr.'|tlxf,report|idv='.$idv.',id='.$id.',cusr='.$usr,langp('report'),'').' ';
$ret.=self::app_action($obj);
//$ret.=bj($pr.'|tlxf,translate|id='.$id,langp('translate')).' ';
if($own)$ret.=toggle($pr.'|tlxf,editpv|idv='.$idv.',id='.$id.',pv='.$pv,langp('privacy'),'').' ';
//if($own)$ret.=bj($pr.'|tlxf,editlbl|idv='.$idv.',id='.$id.',lbl='.$lbl,langp('label'),'').' ';
$ret.=toggle($pr.'|chat,discussion|id='.$id,langp('discussion')).' ';//if($usr!=$us)
if($own or auth(6))$ret.=toggle($pr.'|tlxf,del|idv='.$idv.',did='.$id,langp('delete'),'').' ';//??own
return div($ret,'list');}//div(,'actions')

static function editlbl($p){
$idv=$p['idv']; $id=$p['id']; $lbl=$p['lbl']; $ret='';
//$lbl=sql('lbl','tlex','v',$id); return admin_labels::edit(['id'=>$lbl]);
$r=[0=>'none',284=>'free speech',283=>'fact',425=>'opinion',424=>'theory',287=>'advertising'];
$ret=radio('lbl'.$id,$r,$lbl,0,1);
$ret.=bj($idv.'|tlex,modiflbl|id='.$id.'|lbl'.$id,langp('modif'),'btsav');
$ret.=close('pn'.$idv,langp('cancel'),'btn');
return $ret;}

static function editpv($p){
$idv=$p['idv']; $id=$p['id']; $pv=$p['pv']; $ret='';
$r=[0=>'public',2=>'subscribers',3=>'mentions',4=>'private'];//,1=>'followed'
$ret=radio('pv'.$id,$r,$pv,0,1);
$ret.=bj($idv.'|tlex,modifpv|id='.$id.'|pv'.$id,langp('modif'),'btsav');
$ret.=close('pn'.$idv,langp('cancel'),'btn');
return $ret;}

static function redit($p){$id=$p['id']; $rid=randid('ids');
$msg=sql('txt',self::$db,'v',$id);
$js='strcount(\''.$rid.'\',768); resizearea(\''.$rid.'\');';
$r=['class'=>'area','id'=>$rid,'onkeyup'=>$js,'onmousedown'=>$js,'cols'=>84,'rows'=>8];
$ret=build::connbt($rid).tag('textarea',$r,$msg);//form
$count=span(768-mb_strlen(html_entity_decode($msg)),'btxt small','strcnt'.$rid).' ';
$cancel=bj('tlx'.$id.'|tlex,one|id='.$id,langp('cancel'),'btn');
$j='tlx'.$id.'|tlex,modif|id='.$id.',ids='.$rid.'|'.$rid;
$ret.=div($count.bj($j,langp('modif'),'btsav').$cancel).' ';
$ret.=div('','clear');
return $ret;}

//share
static function sendmail($p){$id=val($p,'id');
$ret=input('to','','20',lang('to'));
$ret.=hidden('subject',ses('user').' '.lang('send you',1).' '.lang('a',1).' '.lang('telex',1));
$txt=sql('txt',self::$db,'v',$id);
$txt.="\n".host(1).'/'.$id;
$ret.=hidden('message',$txt);
$ret.=bj('sndml'.$id.'|sendmail,send|mode=text|subject,message,to',lang('send'),'btsav');
return $ret;}

static function twit_send($p){
$txt=val($p,'twitxt'); $twid=val($p,'twid'); $t=new twit($twid);
$txt=html_entity_decode(utf8_decode($txt));//
if($t)$q=$t->update($txt);
if(array_key_exists('errors',$q))$er=$q['errors'][0]['message'];
if(isset($er))return help('error','alert').$er;
return help('twit sent','valid');}

static function twit($p){$id=val($p,'id'); $twid=val($p,'twid');
$txt=sql('txt',self::$db,'v',$id);
$txt=conn::call(['msg'=>$txt,'app'=>'conn','mth'=>'noconn','ptag'=>'no']);
$txt.="\n".'http://'.host().'/'.$id;
$ret=div(textarea('twitxt',$txt,60,4,'','',140));
$ret.=bj('sndtw'.$id.'|tlxf,twit_send|id='.$id.',twid='.$twid.'|twitxt',lang('tweet'),'btsav',['id'=>'edtbttwitxt']);
return $ret;}

static function share($p){$id=val($p,'id'); $root=host(1).'/'.$id;
$txt=sql('txt',self::$db,'v',$id);
$txt=conn::call(['msg'=>$txt,'app'=>'conn','mth'=>'noconn','ptag'=>0]);
$txt=(utf8_encode(strip_tags($txt)));
//$obj=tlex::objects(); if($obj)$txt.=trim(strip_tags($obj));
$tw='http://twitter.com/intent/tweet?original_referer='.$root.'&url='.$root.'&text='.utf8_encode($txt).' #tlex'.'&title=Tlex:'.$id; $fb='http://www.facebook.com/sharer.php?u='.$root;
$ptw=ico('twitter-square','24','twitter'); $pfb=ico('facebook-official','24','facebook');
$ret=lk($tw,$ptw,'',1).lk($fb,$pfb,'',1);
$ret.=popup('iframe,getcode|url='.host().'/frame/tlex/'.$id,ico('code',24)).' ';
$ret.=toggle('sndml'.$id.'|tlxf,sendmail|id='.$id,ico('envelope-o',24)).span('','','sndml'.$id);
$r=sql('id,owner','twitter','kv',['uid'=>ses('uid')]);
if($r){foreach($r as $k=>$v)
	$twapi[]=bj('sndtw'.$id.'|tlxf,twit|id='.$id.',twid='.$k,ico('twitter',24).$v);
	$ret.=' '.span(implode('',$twapi),'','sndtw'.$id);}
return $ret;}

//del
static function del($p){$id=val($p,'did');
$uid=sql('uid',self::$db,'v',$id);
if($uid!=ses('uid'))return lang('operation not permitted');
if(!val($p,'confirm')){
	$cancel=bj('tlx'.$id.'|tlex,one|id='.$id,langp('cancel'),'btn');
	$ja='cbck|tlxf,del|did='.$id.',confirm=1';
	return bj($ja,langp('confirm deleting').' telex #'.$id,'btdel').$cancel;}
else{sqldel(self::$db,$id); sqldel('tlex_ntf',$id,'txid'); sqldel('tlex_mnt',$id,'tousr'); sqldel('tlex_tag',$id,'tlxid'); sqldel('tlex_app',$id,'tlxid');}//del img
$p['id']='';
return tlex::read($p);}

//report
static function report($p){$id=val($p,'id'); //$idv=val($p,'idv');
$uid=ses('uid'); $usr=val($p,'cusr'); $idp=''; $nb=0; $cuid=idusr($usr);
if($uid && $id)$idp=sql('id','tlex_rpt','v',['rpuid'=>$uid,'tlxid'=>$id]);
$nb=sql('count(id)','tlex_rpt','v',['tlxid'=>$id]);//nb reports
$max=sql('count(ab)','tlex_ab','v',['usr'=>$cuid]);//nb ab
$prm='id='.$id.',cusr='.$usr;//.',idv='.$idv
if(val($p,'cancel')){sqldel('tlex_rpt',$idp);
	//echo $nb.'<='.ceil($max/20);
	if($max)if($nb<=ceil($max/20))sqlup(self::$db,'no','0',$id);}
elseif($idp){$and='';//already
	if($nb>1)$and=', '.lang('and',1).' '.$nb.' '.lang('others',1);
	$prb=['data-prmtm'=>'id='.$id];
	$ccl=bj('cbck|tlxf,report|'.$prm.',cancel=1',langp('cancel'),'btxt',$prb);
	return div(help('telex_reported').$and.' '.$ccl,'alert');}
elseif(val($p,'confirm')){sqlsav('tlex_rpt',[$uid,$id]); $nb+=1;
	//echo $nb.'>='.ceil($max/20);
	if($max)if($nb>=ceil($max/20))sqlup(self::$db,'no','1',$id);}
else{$ja='cbck|tlxf,report|'.$prm.',confirm=1'; $prb=['data-prmtm'=>'id='.$id];
	$ret=lang('telex_max_reports').' : '.$nb.'/'.ceil($max/20);
	return bj($ja,langp('confirm reporting'),'btdel',$prb).' '.span($ret,'alert');}
return tlex::read($p);}

//translate
static function translate($p){$id=val($p,'id'); $lg=val($p,'lg');
$txt=sql('txt',self::$db,'v',$id);
$txt=conn::call(['msg'=>$txt,'app'=>'conn','mth'=>'noconn','ptag'=>0]);
//return yandex::read(['txt'=>$txt]);
return voc($txt,'tlex-text-'.$id,$lg);}

//labels		
static function labels_in($p){$id=val($p,'lbl'); if(!$id)return;
list($ico,$ref)=sql('icon,ref','labels','rw',$id);
return span(ico($ico).$ref,'','lblxt').hidden('lbl',$id);}

//ascii
static function ascii($p){
$id=val($p,'rid'); $all=val($p,'all'); $ret='';
$r=explode(' ',ascii::smileys());
foreach($r as $v)$ret.=btj('&#'.$v.';',insert('&#'.$v.';',$id),'btn').' ';
return $ret;}

#notifications
static function notifs($p){$ret='';$t='';
$r=sql('id,byusr,typntf,txid','tlex_ntf','rr',['4usr'=>ses('user'),'state'=>1]);
if($r)foreach($r as $k=>$v){$st=$v['typntf']; $usr=$v['byusr']; 
	if($st==4)$t='has subscribe'; elseif($st==6)$t='has approved'; elseif($st==5)$t='sent you a message';
	$bt=bubble('profile,call|usr='.$usr.',sz=small','@'.$usr,'btit',1);
	if($st==5)$bt.=bj('cbck|chat,calltlx|headers=1',pic('chat'),'btn');
	if($st>3)$ret.=tag('h3','',$bt.' '.lang($t),'');
	sqlup('tlex_ntf','state','0',$v['id']);}
return div($ret,'');}

#subscrip-bers-tions
static function subscriptions($p){
$usr=$p['usr']; $cuid=idusr($usr); $ret='';
$r=sql('ab','tlex_ab','k',['usr'=>$cuid,'_order'=>'up desc']);
$n=isset($r)?count($r):'';
$tit=div($n.' '.langs('subscription',$n),'btit');
if($r)foreach($r as $k=>$v){if(isset($rc[$k]))$wait=1; else $wait=0;
	$ret.=profile::standard(['uid'=>$k]);}
return $tit.div($ret,'board');}

static function subscribers($p){
$usr=$p['usr']; $cuid=idusr($usr); $ret='';
$r=sql('usr','tlex_ab','k',['ab'=>$cuid,'_order'=>'up desc']);
$n=isset($r)?count($r):'';
$tit=div($n.' '.langs('subscriber',$n),'btit');
$tit.=self::notifs($p);
//pending subscr
$rc=sql('usr','tlex_ab','k',['ab'=>$cuid,'wait'=>'1']);
if($n=count($rc)){
	$tit.=div($n.' '.langs('pending subscriber',$n),'alert').br();
	foreach($rc as $k=>$v){unset($r[$k]);
		$tit.=profile::standard(['uid'=>$k,'approve'=>1]);}}
if($r)foreach($r as $k=>$v){if(isset($rc[$k]))$wait=1; else $wait=0;
	$ret.=profile::standard(['uid'=>$k]);}
return $tit.div($ret,'board');}

//notification (likes,follow)
static function saventf1($tousr,$id,$type){
$send=''; $usr=ses('user'); if($tousr==$usr)return;
$r=['4usr'=>$tousr,'byusr'=>$usr,'typntf'=>$type,'txid'=>$id];
$ex=sql('id','tlex_ntf','v',$r);
if(!$ex)sqlsav('tlex_ntf',[$tousr,$usr,$type,$id,'1']);
else sqlup('tlex_ntf','state',1,$ex);
$send=sql('ntf','profile','v',['pusr'=>$tousr]);
if($send!=1){$subject=lang('tlex');
	$mail=sql('mail','login','v',['name'=>$tousr]);
	$rn=[1=>'quote',2=>'reply',3=>'like',4=>'follow',5=>'chat',6=>'subscr',7=>'bank'];
	$hlp='notif_'.$rn[$type]; $url=host(1).'/'.$id;
	$msg=$usr.' '.helpx($hlp).n().$url;
	mail::send($mail,$subject,$msg,'bot@'.host().'','text');}}

//likes
static function savelike($p){$id=val($p,'id'); $lid=val($p,'lid'); $nlik=val($p,'nlik');
if($lid){sqldel('tlex_lik',$lid); $p['lid']='';
	$r=['4usr'=>$p['name'],'byusr'=>ses('user'),'typntf'=>3,'txid'=>$id];
	$ex=sql('id','tlex_ntf','v',$r); if($ex)sqldel('tlex_ntf',$ex);}
elseif(ses('uid')){$p['lid']=sqlsav('tlex_lik',[ses('uid'),$id]); self::saventf1($p['name'],$id,3);}
return tlex::likebt($p);}

//follow
static function follow($p){
$usr=val($p,'usr'); $list=val($p,'subslist',val($p,'follow')); $rid=val($p,'rid'); $uid=ses('uid');
$cuid=idusr($usr);
if($list){//save
	$id=sql('id','tlex_ab','v',['usr'=>$uid,'ab'=>$cuid]);
	if($id)sqlup('tlex_ab','list',$list,$id);
	else{$private=sql('privacy','profile','v',['pusr'=>$usr]);
		sqlsav('tlex_ab',[$uid,$cuid,$list,$private,0]);
		self::saventf1($usr,ses('user'),4);}
	return tlex::followbt($p);}
elseif($block=val($p,'block')){
	$id=sql('id','tlex_ab','v',['usr'=>$uid,'ab'=>$cuid]);
	if($block==2)sqlup('tlex_ab','block',0,$id);
	elseif($id)sqlup('tlex_ab','block',1,$id);
	else sqlsav('tlex_ab',[$uid,$cuid,'','',1]);
	return tlex::followbt($p);}
elseif($apr=val($p,'refuse')){$apr=idusr($apr);
	qr('delete from tlex_ab where usr="'.$apr.'" and ab="'.$uid.'"');
	return self::subscriptions(['usr'=>$uid]);}
elseif($apr=val($p,'approve')){$apr=idusr($apr);
	qr('update tlex_ab set wait=0 where usr="'.$apr.'" and ab="'.$uid.'"');
	self::saventf1($apr,ses('user'),6);
	return self::subscribers(['usr'=>ses('user')]);}
elseif($unf=val($p,'unfollow')){sqldel('tlex_ab',$unf);//unfollow
	$ntf=sql('id','tlex_ntf','v',['4usr'=>$usr,'typntf'=>4]);
	sqldel('tlex_ntf',$ntf); return tlex::followbt($p);}
elseif(val($p,'list')){$bt='';//display
	$r=sql('distinct(list)','tlex_ab','k',['usr'=>$uid,'block'=>0]);
	$ra=sql('list,block','tlex_ab','rw',['usr'=>$uid,'ab'=>$cuid]); if(!$ra)$ra=[0,0];
	$r=merge($r,['mainstream'=>1,'local'=>1,'global'=>1,'passion'=>1,'extra'=>1]);
	//$ret=div(lang('subscribe_list'),'btit');
	$ret=input('subslist',lang('new group'),18,1).' ';
	$ret.=bj($rid.'|tlxf,follow|usr='.$usr.',rid='.$rid.'|subslist',lang('ok',1),'btsav');
	foreach($r as $k=>$v){$c=act($k,$ra[0]);
		$bt.=bj($rid.'|tlxf,follow|usr='.$usr.',rid='.$rid.',follow='.$k,$k,$c);}
	if($ra[1])$bt.=bj($rid.'|tlxf,follow|usr='.$usr.',rid='.$rid.',block=2',lang('blocked'),'active');
	else $bt.=bj($rid.'|tlxf,follow|usr='.$usr.',rid='.$rid.',block=1',lang('block'),'del');
	return div($ret.div($bt,'list'),'pane','','');}}

//new user
static function one($p){$r=tlex::api($p);
if($r)return self::pane(current($r),$p['id']);}

static function pane($v,$current=''){$id=$v['id']; $usr=$v['name'];
$v['idv']='tlx'.$id; $tg='popup';
$avatar='';//bubble('profile,call|usr='.$usr.',sz=small',profile::avatarsmall($v),'btxt',1);
$head=tlex::panehead($v,$tg);
if($v['ko'])$msg=div(help('telex_banned'),'alert');
else $msg=conn::call(['msg'=>$v['txt'],'app'=>'tlex','mth'=>'reader','ptag'=>1]);
$msg=div($msg,'message');//div($avatar,'bloc_left').
$ret=div($head.$msg,'bloc_content');
$ret.=div('','','pn'.$v['idv']);
return $ret;}

//apicom
///api.php?app=tlxf&mth=apicom&msg=hello&prm=oAuth:XXX
static function post($p){
$p['msg']=get('msg'); $p['lbl']=get('label');
$p['ids']='msg'; $p['apicom']=1; $p['lbl']=0; $p['pv']=0;
$id=tlex::save($p);
if(is_numeric($id))return 'http://'.host().'/'.$id;//url($id)
else return 'action refused';}

//editor
static function realib($id){
$d=sql('txt',self::$db,'v',$id);
if(strpos($d,':id]'))$id=segment($d,'[',':id]');
return $id;}

static function editor($p){$ret='';
[$id,$ib,$idv,$rid,$to,$qo]=vals($p,['id','ib','idv','rid','to','qo']); if(!$rid)$rid=randid('ids');
if($qo)$msg='['.self::realib($qo).':id]'; //elseif($to)$msg='@'.$to.' ';
elseif($id)$msg=sql('txt',self::$db,'v',$id);
else $msg=val($p,'msg');
if($ib)$ret.=div(lang('in-reply to').' '.$to,'grey');
if($qo)$ret.=div(lang('repost'),'grey');
if(!$qo)$idv='dboard';
$js=atj('strcount2',$rid);
$jr=atj('autoResizeHeight',$rid);
$r=['contenteditable'=>'true','id'=>$rid,'placeholder'=>lang('message'),'class'=>'editarea scroll','onkeyup'=>$js.$jr,'onpaste'=>$js.$jr];
$ret.=tag('textarea',$r,$msg);
$asbt=bubble('ascii,call|rid='.$rid,ico('smile-o'),'btn');
$r2=[0=>'public',2=>'subscribers',3=>'mentions',4=>'private'];//,1=>'followed'
$prvbt=select('pv',$r2,0,0,1);//tlex::$privdsk
$count=span('','','strcnt'.$rid,'');
$prm['onclick']='closediv(\''.$idv.'\'); closediv(\'dboard\');';
$prm['id']='edtbt'.$rid; $prm['data-prmtm']='tm='.ses('user');//'current';
$ja='div,cbck,resetform|tlex,save|ibs='.$ib.',ids='.$rid.'|'.$rid.',lbl,pv';
$go=bj($ja,langp('publish'),'btsav',$prm);
$lb=hidden('lbl',0);
$ret.=span($asbt.' '.$prvbt.' '.$count.' '.$go,'right').' '.$lb.' ';//div(,'').' '.$cl
$ret.=div('','clear','asci');
return div($ret,'tlxapps','addpst'.$rid);}

//function publishbt($t,$v,$id){return btj($t,insert('['.$v.']',$id),'btok');}
static function publishbt($t,$v,$rid){
$ja='div,cbck,resetform,1|tlex,save|ids=tx'.$rid.'|tx'.$rid.'';
$prm['onclick']=atj('closediv','dboard').atj('cltg','');
$prm['id']='edtbt'.$rid; $prm['data-prmtm']='tm='.ses('user');
return bj($ja,langph('publish'),'btsav',$prm).hidden('tx'.$rid,'['.$v.']');}

}
?>