<?php
class tlxf{
static $private=0;
static $db='tlex';
static $rid='rid';

//menu apps
static function apps($p){
$cuid=ses('cuid'); $b=$p['b']??'public'; $cat=$p['cat']??''; $auth=ses('auth'); if(!$auth)$auth=0; $ret='';
$dsp=ses('dskdsp',$p['display']??''); if(!$dsp)$dsp=ses('dskdsp',2); $c=$dsp==1?'licon':'cicon';
$ja='cbck,,1|tlxf,apps|b='.$b;
if($dsp==2)$bt=bj($ja.',display=1',langpi('icons'),'');
else $bt=bj($ja.',display=2',langpi('list'),'');
//$bt.=bj($ja,lang('all'),active($cat,''));
$r=applist::build($b);
if($r)foreach($r as $k=>$v){$dr=strend($v,'/'); $rb[$dr]='';
	if(method_exists($k,'com') && isset($k::$db))$call=$k.',com'; else $call=$k;
	$rp['title']=helpx($k.'_app'); $rp['data-u']=$k; $rp['data-cl']='cbck';
	$j='cbck,,,1|'.$call.'|headers=1'; if($k=='desktop')$j.=',cuid='.$cuid.',dir=/documents';
	$private=class_exists($k) && isset($k::$private)?$k::$private:0;
	if($cat==$dr or !$cat)$ok=1; else $ok=0;
	if($auth>=$private && $ok)$ret.=bj($j,div(span(pic($k)).span(helpx($k))),'',$rp,'');
	//if($auth>=$private && $ok)$ret.=lku($k,pic($k).span(helpx($k)),'',$rp,'');
	if($dsp==10 && $ok)$ret.=help($k.'_app');}
if(isset($rb))foreach($rb as $k=>$v)$bt.=bj($ja.',cat='.($k==$cat?'':$k),lang($k),active($cat,$k));
return div(div($bt,'tabs').div($ret,$c),'board');}

static function goodies($p){$ret='';
$r=applist::build('goodies'); p($r);
$r=['txt','pad','convert','keygen','clock','exif','oracle','pkr','randomizer','pi','phi','spectral','spitable','spitable2','spilog','biogen','weather2'];
foreach($r as $k=>$v)$ret.=bj('cbck,,,1|'.$v.(method_exists($v,'com')?',com':'').'|headers=1',pic($v).span($v));
return div($ret,'board cicon');}

static function tagapp($p){$ret=''; //return div(admin_tags::search($p),'board');
$r=sql('aid,app','tags_r','kv',['bid'=>$p['bid']]);
foreach($r as $k=>$v)if(class_exists($v)){
	$bt=pic($v).span($v::tit(['a'=>$v,'id'=>$k]));
	$ret.=pagup($v.',com|'.$k,div($bt));}
return div($ret,'licon');}

//keep
static function dsksav($p){//dir,type,com,picto,bt
[$id,$idv,$com,$d,$pub,$o,$ict,$tit]=vals($p,['id','idv','com','p1','pub','p2','ict','tit']);
$t=$p[$ict]??$tit; $uid=ses('uid'); if(!$pub)$pub=2; $ic='';
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
if(!$ex){$nid=sql::sav('desktop',[$uid,'/documents/'.$com,$ty,$ncom,$ic,$t,$pub]);
$bt=div(lang('added to desktop'),'valid').div(ico($ic).' '.$t,'tit');}
else $bt=help('already exists','alert');
return desktop::content(['dir'=>'/documents/'.$com]);
return div(bj('popup|desktop|dir=/documents/'.$com,$bt,'',['onclick'=>atj('Close','popup')]),'');}

static function dskdel($p){
$com=$p['com']??''; $t=$p['tit']??''; $uid=ses('uid');
$ex=sql('id','desktop','v',['uid'=>$uid,'dir'=>'/documents/'.$com,'bt'=>$t]);
if($ex)sql::del('desktop',$ex);
return desktop::content(['dir'=>'/documents/'.$com]);}

static function keep($p){$ret=''; $ex=''; $txt='';
[$id,$idv,$com,$dir,$pic,$bt,$auth]=vals($p,['id','idv','conn','dir','pic','bt','auth']);
$ret=div(help('keep'),'btit');
if(!$dir){
	if($id)$txt=sql('txt',self::$db,'v',$id);
	if($txt)conn::call(['msg'=>$txt,'app'=>'conn','mth'=>'reader']); $r=conn::$obj;
	if($r)foreach($r as $kr=>$vr)foreach($vr as $k=>$v){$im=''; $pic=''; $t=''; [$p,$o]=$v;
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
static function app_action($obj,$pr){$r=prmr($obj); $ret='';
if($r)foreach($r as $k=>$v)if(method_exists($k,'own')){$a=new $k;
	if($k::own($v))$ret.=toggle($pr.'|'.$k.'|edit=1,id='.$v.',rid='.randid('edt'),pic('edit').lang($k));}
return $ret;}

static function actions($p){$rt=[]; $sz=''; $us=ses('usr');
$id=$p['id']; $idv=$p['idv']; $pr='pn'.$idv; $usr=$p['usr']; $uid=$p['uid'];
$lg=$p['lg']; $pv=$p['pv']; $lbl=$p['lbl']; $own=$usr==$us?1:0;
if($id)$txt=sql('txt',self::$db,'v',$id);
if($txt)$obj=conn::call(['msg'=>$txt,'app'=>'conn','mth'=>'appreader']); //pr($r=conn::$obj);
$rt[]=toggle($pr.'|tlxf,editor|idv='.$idv.',to='.$usr.',ib='.$id,langp('reply',$sz));
$rt[]=toggle($pr.'|tlxf,editor|idv='.$idv.',qo='.$id,langp('relay',$sz));
$rt[]=toggle($pr.'|tlxf,share|id='.$id,langp('share',$sz));
if(conn::$obj)$rt[]=toggle($pr.'|tlxf,keep|idv='.$idv.',id='.$id,langp('keep',$sz));//
if($own or auth(6))$rt[]=toggle($pr.'|tlxf,redit|id='.$id,langp('modif'),'');
else $rt[]=toggle($pr.'|tlxf,report|idv='.$idv.',id='.$id.',cusr='.$usr,langp('report'),'');
$rt[]=self::app_action($obj,$pr);
//$rt[]=bj($pr.'|tlxf,translate|id='.$id,langp('translate'));
if($own)$rt[]=toggle($pr.'|tlxf,editpv|idv='.$idv.',id='.$id.',pv='.$pv,langp('privacy'),'');
//if($own)$rt[]=bj($pr.'|tlxf,editlbl|idv='.$idv.',id='.$id.',lbl='.$lbl,langp('label'),'');
$rt[]=toggle($pr.'|chat,discussion|id='.$id,langp('discussion'));//if($usr!=$us)
if($own or auth(6))$rt[]=toggle($pr.'|tlxf,del|idv='.$idv.',did='.$id,langp('delete'),'');//??own
return div(implode(' ',$rt),'lish');}//div(,'actions')

static function editlbl($p){
$idv=$p['idv']; $id=$p['id']; $lbl=$p['lbl']; $ret='';
//$lbl=sql('lbl','tlex','v',$id); return admin_labels::edit(['id'=>$lbl]);
$r=[0=>'none',284=>'free speech',283=>'fact',425=>'opinion',424=>'theory',287=>'advertising'];
$ret=radio('lbl'.$id,$r,$lbl,0,1);
$ret.=bj($idv.'|tlex,modiflbl|id='.$id.'|lbl'.$id,langp('modif'),'btsav');
//$ret.=close('pn'.$idv,langp('cancel'),'btn');
return $ret;}

static function editpv($p){
$idv=$p['idv']; $id=$p['id']; $pv=$p['pv']; $ret=''; $rt=[];
$r=[0=>'public',1=>'followers',2=>'friends',3=>'mentions',4=>'private'];//tlex::$prvdsk
//$ret=radio('pv'.$id,$r,$pv,0);
//$ret.=bj($idv.'|tlex,modifpv|id='.$id.'|pv'.$id,langp('modif'),'btsav');
$j=$idv.'|tlex,modifpv|id='.$id.',pv'.$id.'=';
foreach($r as $k=>$v)$rt[]=bj($j.$k,langp($v),active($pv,$k));
//$ret.=close('pn'.$idv,langp('cancel'),'btn');
return div(implode('',$rt),'lisb');}

static function redit($p){$id=$p['id']; $rid=randid('ids');
$msg=sql('txt',self::$db,'v',$id);
$count=span(768-mb_strlen(html_entity_decode($msg)),'btxt small','strcnt'.$rid).' ';
//$cancel=bj('tlx'.$id.'|tlex,one|id='.$id,langp('cancel'),'btn');
$j='tlx'.$id.'|tlex,modif|id='.$id.',ids='.$rid.'|'.$rid;
$ret=div(bj($j,langp('modif'),'btsav')).' ';//.$cancel
$js=atj('strcount',$rid);//.atj('autoResizeHeight',$rid)
$r=['class'=>'editarea','id'=>$rid,'onkeyup'=>$js,'onpaste'=>$js,'cols'=>84,'rows'=>8];
$ret.=build::connbt($rid).div(tag('textarea',$r,$msg).' '.$count);
return $ret;}

//share
static function sendmail($p){$id=$p['id']??'';
$ret=input('to','','20',lang('to'));
$ret.=hidden('subject',ses('usr').' '.lang('send you',1).' '.lang('a',1).' '.lang('telex',1));
$txt=sql('txt',self::$db,'v',$id);
$txt.="\n".host(1).'/'.$id;
$ret.=hidden('message',$txt);
$ret.=bj('sndml'.$id.'|sendmail,send|mode=text|subject,message,to',lang('send'),'btsav');
return $ret;}

static function twit_send($p){
$txt=$p['twitxt']??''; $twid=$p['twid']??''; $t=new twit($twid);
$txt=(($txt));//html_entity_decode(utf8dec
if($t)$q=$t->update($txt);
if(array_key_exists('errors',$q))$er=$q['errors'][0]['message'];
if(isset($er))return help('error','alert').$er;
return help('twit sent','valid');}

static function twit($p){$id=$p['id']??''; $twid=$p['twid'];
$txt=sql('txt',self::$db,'v',$id);
$txt=conn::call(['msg'=>$txt,'app'=>'conn','mth'=>'noconn','ptag'=>'no']);
$txt.="\n".'http://'.host().'/'.$id;
$ret=div(textarea('twitxt',$txt,60,4,'','',140));
$ret.=bj('sndtw'.$id.'|tlxf,twit_send|id='.$id.',twid='.$twid.'|twitxt',lang('tweet'),'btsav',['id'=>'edtbttwitxt']);
return $ret;}

static function share($p){$id=$p['id']??''; $root=host(1).'/'.$id;
$txt=sql('txt',self::$db,'v',$id);
$txt=conn::call(['msg'=>$txt,'app'=>'conn','mth'=>'noconn','ptag'=>0]);
$txt=(str::utf8enc(strip_tags($txt)));
//$obj=tlex::objects(); if($obj)$txt.=trim(strip_tags($obj));
$tw='http://twitter.com/intent/tweet?original_referer='.$root.'&url='.$root.'&text='.utf8enc($txt).' #tlex'.'&title=Tlex:'.$id; $fb='http://www.facebook.com/sharer.php?u='.$root;
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
static function del($p){$id=$p['did'];
$uid=sql('uid',self::$db,'v',$id);
if($uid!=ses('uid') && !auth(6))return lang('operation not permitted');
if(empty($p['confirm'])){
	//$cancel=bj('tlx'.$id.'|tlex,one|id='.$id,langp('cancel'),'btn');
	$ja='cbck|tlxf,del|did='.$id.',confirm=1';
	return bj($ja,langp('confirm deleting').' telex #'.$id,'btdel');}
else{sql::del(self::$db,$id); sql::del('tlex_ntf',$id,'txid'); sql::del('tlex_mnt',$id,'tousr'); sql::del('tlex_tag',$id,'tlxid'); sql::del('tlex_app',$id,'tlxid');}//del img
$p['id']='';
return tlex::read($p);}

//report
static function report($p){$id=$p['id']??''; //$idv=$p['idv'];
$uid=ses('uid'); $usr=$p['cusr']; $idp=''; $nb=0; $cuid=idusr($usr);
if($uid && $id)$idp=sql('id','tlex_rpt','v',['rpuid'=>$uid,'tlxid'=>$id]);
$nb=sql('count(id)','tlex_rpt','v',['tlxid'=>$id]);//nb reports
$max=sql('count(ab)','tlex_ab','v',['usr'=>$cuid]);//nb ab
$prm='id='.$id.',cusr='.$usr;//.',idv='.$idv
if($p['cancel']){sql::del('tlex_rpt',$idp);
	//echo $nb.'<='.ceil($max/20);
	if($max)if($nb<=ceil($max/20))sql::up(self::$db,'no','0',$id);}
elseif($idp){$and='';//already
	if($nb>1)$and=', '.lang('and',1).' '.$nb.' '.lang('others',1);
	$prb=['data-prmtm'=>'id='.$id];
	$ccl=bj('cbck|tlxf,report|'.$prm.',cancel=1',langp('cancel'),'btxt',$prb);
	return div(help('telex_reported').$and.' '.$ccl,'alert');}
elseif($p['confirm']){sql::sav('tlex_rpt',[$uid,$id]); $nb+=1;
	//echo $nb.'>='.ceil($max/20);
	if($max)if($nb>=ceil($max/20))sql::up(self::$db,'no','1',$id);}
else{$ja='cbck|tlxf,report|'.$prm.',confirm=1'; $prb=['data-prmtm'=>'id='.$id];
	$ret=lang('telex_max_reports').' : '.$nb.'/'.ceil($max/20);
	return bj($ja,langp('confirm reporting'),'btdel',$prb).' '.span($ret,'alert');}
return tlex::read($p);}

//translate
static function translate($p){$id=$p['id']??''; $lg=$p['lg'];
$txt=sql('txt',self::$db,'v',$id);
$txt=conn::call(['msg'=>$txt,'app'=>'conn','mth'=>'noconn','ptag'=>0]);
//return trans::read(['txt'=>$txt]);
return voc($txt,'tlex-text-'.$id,$lg);}

//labels		
static function labels_in($p){$id=$p['lbl']; if(!$id)return;
[$ico,$ref]=sql('icon,ref','labels','rw',$id);
return span(ico($ico).$ref,'','lblxt').hidden('lbl',$id);}

//ascii
/*static function ascii($p){
$id=$p['rid']??''; $all=$p['all']; $ret='';
$r=explode(' ',ascii::smileys());
foreach($r as $v)$ret.=btj('&#'.$v.';',insert('&#'.$v.';',$id),'btn').' ';
return $ret;}*/

#notifications
static function notifs($p){$ret='';$t='';
$r=sql('id,byusr,typntf,txid','tlex_ntf','rr',['4usr'=>ses('usr'),'state'=>1]);
if($r)foreach($r as $k=>$v){$st=$v['typntf']; $usr=$v['byusr']; 
	if($st==4)$t='has subscribe'; elseif($st==6)$t='has approved'; elseif($st==5)$t='sent you a message';
	$bt=bubble('profile,call|usr='.$usr.',sz=small','@'.$usr,'btit',1);
	if($st==5)$bt.=bj('cbck|chat,calltlx|headers=1',pic('chat'),'btn');
	if($st>3)$ret.=tag('h3','',$bt.' '.lang($t),'');
	sql::up('tlex_ntf','state','0',$v['id']);}
return div($ret,'');}

//notification (likes,follow)
static function saventf1($tousr,$id,$type){
$send=''; $usr=ses('usr'); if($tousr==$usr)return;
$r=['4usr'=>$tousr,'byusr'=>$usr,'typntf'=>$type,'txid'=>$id];
$ex=sql('id','tlex_ntf','v',$r);
if(!$ex)sql::sav('tlex_ntf',[$tousr,$usr,$type,$id,'1']);
else sql::up('tlex_ntf','state',1,$ex);
$send=sql('ntf','profile','v',['pusr'=>$tousr]);
if($send!=1){$subject=lang('tlex');
	$mail=sql('mail','login','v',['name'=>$tousr]);
	$rn=[1=>'quote',2=>'reply',3=>'like',4=>'follow',5=>'chat',6=>'subscr',7=>'bank'];
	$hlp='notif_'.$rn[$type]; $url=host(1).'/'.$id;
	$msg=$usr.' '.helpx($hlp).n().$url;
	mail::send($mail,$subject,$msg,'bot@'.host().'','text');}}

//likes
static function savelike($p){$id=$p['id']??''; $lid=$p['lid']??''; $nlik=$p['nlik']??'';
if($lid){sql::del('tlex_lik',$lid); $p['lid']='';
	$r=['4usr'=>$p['name'],'byusr'=>ses('usr'),'typntf'=>3,'txid'=>$id];
	$ex=sql('id','tlex_ntf','v',$r); if($ex)sql::del('tlex_ntf',$ex);}
elseif(ses('uid')){$p['lid']=sql::sav('tlex_lik',[ses('uid'),$id]); self::saventf1($p['name'],$id,3);}
return tlex::likebt($p);}

//new user
static function one($p){$d=tlex::api($p);
if($d)return self::pane($d,$p['id']);}

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
if(strpos($d,':id]'))$id=between($d,'[',':id]');
return $id;}

static function editor($p){$ret='';
[$id,$ib,$idv,$rid,$to,$qo]=vals($p,['id','ib','idv','rid','to','qo']);
if(!$rid)$rid=randid('ids'); self::$rid=$rid;
if($qo)$msg='['.self::realib($qo).':id]'; //elseif($to)$msg='@'.$to.' ';
elseif($id)$msg=sql('txt',self::$db,'v',$id);
else $msg=val($p,'msg');
if($ib)$ret.=div(lang('in-reply to').' '.$to,'nfo');
if($qo)$ret.=div(lang('repost'),'nfo');
if(!$qo)$idv='dboard';
$asbt=bubble('ascii,call|rid='.$rid,ico('smile-o'),'btn');
//$r2=[0=>'public',2=>'subscribers',3=>'mentions',4=>'private'];
$r2=[0=>'public',1=>'followers',2=>'friends',3=>'mentions',4=>'private'];//tlex::$prvdsk
$prvbt=select('pv',$r2,0,0,1);
$count=span('','','strcnt'.$rid,'');
//$prm['onclick']='closediv(\''.$idv.'\');';// closediv(\'dboard\');
$prm['id']='edtbt'.$rid; $prm['data-prmtm']='tm='.ses('usr');//'current';
$ja='div,cbck,resetform|tlex,save|ibs='.$ib.',ids='.$rid.'|'.$rid.',lbl,pv';
$go=bj($ja,langp('publish'),'btsav',$prm);
$lb=hidden('lbl',0);
$ret.=div($go.' '.$prvbt.' '.$count.' '.$asbt.' '.$lb.' ','');//div(,'').' '.$cl
$js=atj('strcount2',$rid).atj('autoResizeHeight',$rid);
$r=['id'=>$rid,'placeholder'=>lang('message'),'class'=>'resizearea scroll2','onkeyup'=>$js,'onpaste'=>$js];
$ret.=div(tag('textarea',$r,$msg));
$ret.=div('','clear','asci');
return div($ret,'tlxapps','addpst'.$rid);}

//menu
static function menu($p){$c='';
[$uid,$usr,$vu,$role,$op,$app,$tg,$noab]=vals($p,['uid','usr','vu','role','opn','app','tg','noab']);
$rp['data-u']='/'; if($vu)$rp['data-u'].='@'.$usr; $cusr=ses('usr');
$mode=',mode='.($vu?'private':'public'); $my=$vu?'my ':'';
$bt=ico('user').' '.$p['cusr']; //$bt=langph('myposts');
if($cusr){
	if($noab)$rt[]=toggle('main|root,com|opn=posts',$bt,'react',[],1,1);
	else $rt[]=toggle('main|root,com|opn=posts,usr='.$p['cusr'].',noab=1',$bt,'react',[],0,1);
	$rt[]=toggle('nav3|'.ses::$cnfg['index'].',call|op=new',langp('new'),$c,[],'',0);}
$rt[]=toggle('nav3|tlex,searchbt|usr='.$usr.$mode,langph('search'),$c,$rp,'',0);
$rt[]=toggle('nav3|tlex,tagsbt|usr='.$usr.$mode,langph($my.'hashtags'),$c,$rp,'',0);
//$rt[]=toggle('nav3|tlex,tag2bt|usr='.$usr.$mode,langph($my.'tags'),$c,$rp,'',0);
$rt[]=toggle('nav3|tlex,appsbt|usr='.$usr.$mode,langph($my.'apps'),$c,$rp,'',0);
if(!$noab && $cusr){
	//$rt[]=toggle('nav3|tlex,lablbt|usr='.$usr.$mode,langph($my.'labels'),$c,$rp,'',0);
	$rt[]=toggle('nav3|tlex,listbt|usr='.$usr.',list='.ses('list'),langph('lists'),$c,$rp,'',0);
	$n0=sql('count(id)','tlex_ntf','v','where 4usr="'.$usr.'" and state=1 and typntf in (1,2,3)');
	$bt=langph('notifications').' '.span($n0?$n0:'','nbntf','tlxntf');//notifs
	//$rt[]=toggle('|tlex,mntsbt|usr='.$usr,langph('mentions'),$c,[],'',0);
	$rt[]=tlex::loadtm('ntf=1',$bt,'');//,$rc
	$rt[]=tlex::loadtm('mnt=1',langph('mentions'),'',[]);//mention
	$rt[]=tlex::loadtm('likes=1',langph('likes'),'',[]);}//likes
return implode('',$rt);}

//function publishbt($t,$v,$id){return btj($t,insert('['.$v.']',$id),'btok');}
static function publishbt($t,$v,$rid){
$ja='div,cbck,resetform,1|tlex,save|ids=tx'.$rid.'|tx'.$rid.'';
$prm['onclick']=atj('cltg','');//atj('closediv','dboard').
$prm['id']='edtbt'.$rid; $prm['data-prmtm']='tm='.ses('usr');
return bj($ja,langph('publish'),'btsav',$prm).hidden('tx'.$rid,'['.$v.']');}

}
?>
