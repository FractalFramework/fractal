<?php

class tlex{
static $private=0;
static $db='tlex';
static $cols=['uid','txt','lbl','pv','lg','ib','ko'];
static $typs=['int','bvar','int','int','tiny','int','int'];
static $title='Telex';
static $descr='';
static $image='';
static $usr='';
static $opn=0;
static $id=0;
static $privdsk=[0=>'internet',1=>'network',2=>'clan',3=>'mentions',4=>'private',5=>'dev',6=>'admin'];
static $rf=[];
static $lbl=[];
static $tag=[];
static $ntf=[];
static $ntr=[];

//install
static function install(){$n=0;
sqlcreate(self::$db,['uid'=>'int','txt'=>'bvar','lbl'=>'int','pv'=>'int','lg'=>'tiny','ib'=>'int','ko'=>'int'],$n);
sqlcreate('tlex_ab',['usr'=>'int','ab'=>'int','list'=>'var','wait'=>'int','block'=>'int'],$n);
sqlcreate('tlex_web',['url'=>'var','tit'=>'var','txt'=>'var','img'=>'var'],$n);
sqlcreate('tlex_lik',['luid'=>'int','lik'=>'int'],$n);
sqlcreate('tlex_rpt',['rpuid'=>'int','tlxid'=>'int'],$n);
sqlcreate('tlex_ntf',['4usr'=>'var','byusr'=>'var','typntf'=>'int','txid'=>'var','state'=>'int'],$n);
sqlcreate('tlex_mnt',['tlxid'=>'int','tousr'=>'var'],$n);
sqlcreate('tlex_tag',['tlxid'=>'int','tag'=>'var'],$n);
sqlcreate('tlex_app',['tlxid'=>'int','app'=>'var','p'=>'val'],$n);}

static function admin($p){}

#headers
static function injectJs(){}
static function headers(){
add_prop('og:title',addslashes(self::$title));
add_prop('og:description',addslashes(self::$descr));
add_prop('og:image',self::$image);
//add_head('csslink','/css/tlex.css');
add_head('jslink','/js/tlex.js');
add_head('jscode',self::injectJs());}

#lib
static function relativetime($sec){
$ret=lang('there_was').' '; $time=ses('time')-$sec;
if($time>86400*30)$ret=date('d-m-Y',$sec);//strftime('%a %d %b %Y'
elseif($time>86400)$ret=date('d-m-Y',$sec);
elseif($time>3600)$ret.=floor($time/3600).'h ';
elseif($time>60)$ret.=floor($time/60).'min ';
else $ret.=$time.'s';
return span(utf8_encode($ret),'small');}

#ajax
//1=quote,2=reply,3=like,4=subsc,5=chat,6=subsc-approve
static function refresh($p){//pr($p);
$p['count']=1; $n0=self::apisql($p); $w='where 4usr="'.ses('user').'" and state=1';
$n1=sql('count(id)','tlex_ntf','v',$w.' and typntf in (1,2,3)');//quote,reply,like
$n2=sql('count(id)','tlex_ntf','v',$w.' and typntf in (4,6)');//subscr
$n3=sql('count(id)','tlex_ab','v','where ab="'.ses('uid').'"');//subscr_nb
$n4=sql('count(id)','tlex_ntf','v',$w.' and typntf=5');//chat
return $n0.'-'.$n1.'-'.$n2.'-'.$n3.'-'.$n4;}

//load button
static function loadtm($p,$t,$rel='',$r=[]){
$c=$rel?' active':''; $tg='cbck';
//$r['onclick']='setTimeout(\'refresh()\',500);';
$r['data-prmtm']=$p; $r['onclick']='ajbt(this);'; $r['data-ko']=1;//atj('closediv','dboard');
$j='div,'.$tg.',,resetcs|tlex,read|'.($p=='current'?'':$p); //$rp['data-u']=$k;
$r['data-j']=$j; $r['data-toggle']=$tg; $r['id']=randid('tg'); $r['rel']=$rel; $r['class']=$c;
if(ses('dev')=='prog')$r['title']=$j;
return tag('a',$r,$t);}

#notifications
static function saventf($id,$type,$o){
$r=self::$o??[]; $us=ses('user');
if($r)foreach($r as $k=>$v)if($k!=$us){
	if($type)tlxf::saventf1($k,$id,$type);
	if($o=='ntf')sqlsavif('tlex_mnt',[$id,$k]);
	if($o=='tag')sqlsavif('tlex_tag',[$id,$k]);}
self::$o='';}

static function saveapps($id,$d){$r=explode_p($d,',','=');
if($r)foreach($r as $k=>$v)if(isset($v[1]) && method_exists($v[0],'call')){
	if($v[0]=='video')$v[1]=strend($v[1],'v='); //portion($v[1],'v=','&');
	sqlsavif('tlex_app',['tlxid'=>$id,'app'=>$v[0],'p'=>$v[1]]);} return $v[0]??'';}

static function readntf($p){$n=$p['typntf']; $by=$p['byusr']; $st=$p['state']; $ret='';
//$uname=sql('name','login','v','where usr="'.$p['byusr'].'"'); //if($n)$ret='@'.$by.' ';
if($p['state']==1)sqlup('tlex_ntf','state','0',$p['ntid']);
if($n==1 && $p['ib'])$ret.=lang('has_reply',1);
elseif($n==1)$ret.=lang('has_sent',1);
elseif($n==2)$ret.=lang('has_repost',1);
elseif($n==3)$ret.=lang('has_liked',1);
if($st)$c=' active'; else $c='';
return span($ret,'nfo'.$c).' ';}

#save
static function build_conn($d,$o=''){$ret=[];
$d=clean_n($d);
$d=str_replace("\n",' (nl) ',$d);
$r=explode(' ',$d);
foreach($r as $v){
	if(substr($v,0,1)=='@'){$v=substr($v,1); $ret[]='['.$v.':@]'; self::$ntf[$v]=1;}//mnt
	elseif(substr($v,0,1)=='#'){$ret[]='['.substr($v,1).':#]'; self::$tag[substr($v,1)]=1;}
	elseif(substr($v,0,4)=='http'){
		$v=strto($v,'?utm'); //$v=strto($v,'?utm');
		$xt=ext($v);
		if(is_img($v)){$f=saveimg($v,'tlx','590','400'); return '['.($f?$f:$v).':img]';}
		elseif($xt=='.mp3')return '['.$v.':audio]';//
		elseif($xt=='.mp4')return '['.$v.':mp4]';//
		elseif($xt=='.pdf')return '['.$v.':pdf]';//
		elseif(substr($v,0,20)=='https://twitter.com/')return '['.strend($v,'/').':twit]';
		else $metas=web::metas($v);
		if($pv=video::provider($v))$ret[]='['.video::extractid($v,$pv).':video]';
		elseif($metas)$ret[]='['.$v.':web]';
		else $ret[]='['.$v.']';}//:web
	elseif(is_img($v))$ret[]='['.$v.']';
	else $ret[]=$v;
	$conn=substr($v,0,1)=='['?1:0;
	if($conn && substr($v,-4)==':id]' && $id=substr($v,1,-4)){
		$usr=sqlin('name',self::$db,'login','uid','v','where '.self::$db.'.id='.$id);
		if($usr)self::$ntr[$usr]=1;}//quotes
	elseif($n=strrpos($v,':')){$cnn=substr($v,$n+1,-1); if($cnn)self::$lbl=$cnn;}}
if($ret)$d=implode(' ',$ret);
$d=str_replace(' (nl) ',"\n",$d);
return trim($d);}

static function save($p){$ids=$p['ids']; $txa=$p[$ids]??''; self::$ntf=[];
if($a=$p['a']??'')$txa='['.$ids.':'.$a.']'; $lg='';
if($oAuth=$p['oAuth']??''){$ok=sql('puid','profile','v','where oAuth="'.$oAuth.'"');
	if($ok)sez('uid',$ok); else return 'error';}
if($txa){$txt=self::build_conn($txa,1); $ib=$p['ibs']??0; $pv=$p['pv']??0;
	//$txb=conn::call(['msg'=>$txt,'mth'=>'realwords']);
	$aps=conn::call(['msg'=>$txt,'mth'=>'appreader']);
	$lbl=$p['lbl']??0;
	//if($lbl && !is_numeric($lbl))$lbl=sql('id','labels','v','where ref="'.$lbl.'"');
	//if(!$lbl && $lbl=post('lbl'))self::$lbl'=[];
	//if($txb)$lg=yandex::detect(['txt'=>$txb]); if(!$lg)$lg=ses('lng');
	if(!$lbl)$lbl=0; if(!$pv)$pv=0; if(!$ib)$ib=0;
	$id=sqlsav(self::$db,[ses('uid'),$txt,(int)$lbl,(int)$pv,$lg,(int)$ib,0]);
	if($aps)$lbl=self::saveapps($id,$aps);
	if(self::$ntf)self::saventf($id,1,'ntf');
	if(self::$tag)self::saventf($id,0,'tag');
	if(self::$ntr)self::saventf($id,2,'ntr');}
if($p['apicom']??'')return isset($id)?$id:'error';
return self::read(['tm'=>ses('user'),'noab'=>1]);}

static function modif($p){
$txt=$p[$p['ids']]??''; $id=$p['id'];
$txt=self::build_conn($txt);
if($id && $txt)sqlup(self::$db,'txt',$txt,$id);
$aps=conn::call(['msg'=>$txt,'mth'=>'appreader']);
if($aps)$lbl=self::saveapps($id,$aps);
return self::one(['id'=>$id,'noab'=>1]);}

static function modiflbl($p){
$id=$p['id']; $lbl=$p['lbl'.$id]??0;
if($id)sqlup(self::$db,'lbl',$lbl,$id);
return tlex::one(['id'=>$id,'noab'=>1]);}

static function modifpv($p){
$id=$p['id']; $pv=$p['pv'.$id]??0;
if($id)sqlup(self::$db,'pv',$pv,$id);
return tlex::one(['id'=>$id,'noab'=>1]);}

#editor
static function editor($p){}//tlxf::editor
static function opadd($p){return bj('dboard|tlxf,editor',langph('new_post'),'btn');}

#players
static function playlink($d){
$d=http($d); $r=web::metas($d); $t=domain($d);
$r=['href'=>$d,'title'=>$r[1],'class'=>'btlk','target'=>'_blank'];
return tag('a',$r,$t);}

static function playquote($id){
$ra=self::apisql(['id'=>$id]); $r=$ra[0];
if(!$r)return div(lang('post deleted'),'paneb');
$r['idv']='qlx'.$id; $r['fast']=1;
$ret=div(self::panehead($r,'popup'),'bloc_header');
$ret.=div(conn::call(['msg'=>$r['txt'],'app'=>'conn','mth'=>'reader','opt'=>'it2','ptag'=>'no']),'bloc_content');
return div($ret,'',$r['idv']);}

static function url($d,$c='',$e=''){
//if(strpos($d,'*'))$d=str_replace('*','�',$d);//patch
if(strpos($d,'*'))list($p,$o)=explode('*',$d); else{$p=$d; $o=domain($p);}
return lk($p,ico('external-link').' '.$o,$c,$e);}

#search
static function search_txt($p){
$srch=$p['srch']??''; $ret='';
$r=self::apisql(['srh'=>$srch]);
if($r)foreach($r as $k=>$v)$ret.=self::pane($v,'');
else $ret=help('no results','board');
return $ret;}

static function searchbt(){
$r=['onkeypress'=>'SearchT(\'srch\')','onclick'=>'SearchT(\'srch\')'];
$ret=inputcall('','srch','',32,lang('search'),'search',$r);
return div($ret,'tlxapps').div('','','cbksrch');}

#like
static function likebt($p){$rid=randid('lik'); $mylik=''; $sty='';
$id=$p['id']; $lid=$p['lid']; $n=''; $nlik1='';
if($lid){
	$nlik1=sql('count(id)','tlex_lik','v',['lik'=>$id]);
	//$nlik2=sql('count(id)','tlex_lik','v',['lik'=>$id,''=>2]);
	if(ses('uid')){$mylik=sql('id','tlex_lik','v','where lik='.$id.' and luid='.ses('uid'));
		if($mylik)$sty.='color:#e81c4f;';}}
$bt=ico('thumbs-o-up',$sty,'like','','',span($nlik1,'liknb'));//smile-o
$ret=bj($rid.'|tlxf,savelike|y=1,id='.$id.',lid='.$mylik.',name='.$p['name'],$bt);
//$bt=ico('thumbs-o-down',$sty,'like','','',span($nlik2,'liknb'));//smile-o
//$ret.=bj($rid.'|tlxf,savelike|y=-1,id='.$id.',lid='.$mylik.',name='.$p['name'],$bt);
return span($ret,'',$rid);}

#follow
static function followbt($p){$rid=$p['rid']??randid('flw');
$usr=$p['usr']; $sz=$p['sz']??''; $ret='';//$wait=$p['wait'];//vcu
$w='where usr="'.ses('uid').'" and ab="'.idusr($usr).'"';
$id=sql('id','tlex_ab','v',$w);
$rb=sql('wait,block','tlex_ab','ra',$w);//contexts:user see visitor (ucv),
if($id){
	if($rb['wait'])$flag='pending'; elseif($rb['block'])$flag='blocked'; else $flag='unfollow';
	$bt=$sz=='small'?pic($flag):langph($flag);
	$ret=bubble('tlxf,follow|list=1,usr='.$usr.',rid='.$rid,pic('menu'),'btn',1);
	$ret.=bj($rid.'|tlxf,follow|usr='.$usr.',unfollow='.$id.',rid='.$rid,$bt,'btdel');}
else{$bt=$sz=='small'?pic('follow'):langph('follow');
	$ret=bubble('tlxf,follow|list=1,usr='.$usr.',rid='.$rid,$bt,'btsav',1);}
$c=$p['rid']??'followbt';
return span($ret,$c,$rid);}

#subscriptions
static function subscribt($usr,$uid,$role){
if(!$uid)$uid=idusr($usr);
$n0=sql('count(id)',self::$db,'v','where uid="'.$uid.'"');
$n1=sql('count(id)','tlex_ab','v','where usr="'.$uid.'"');
$n2=sql('count(id)','tlex_ab','v','where ab="'.$uid.'"');
$bt=div(lang('published telex'),'subscrxt').div($n0,'subscrnb clr');
$ret=div(self::loadtm('tm='.$usr.',noab=1',$bt),'subscrbt');
$bt=div(lang('subscriptions'),'subscrxt').div(span($n1,'','tlxabs'),'subscrnb clr');//ab
$ret.=div(bj('cbck|tlxf,subscriptions|type=ption,usr='.$usr.'|tlxabs',$bt),'subscrbt');
$t=$role?'members':'subscribers';
$bt=div(lang($t),'subscrxt').div(span($n2,'','tlxsub'),'subscrnb clr');//sub
$ret.=div(bj('cbck|tlxf,subscriptions|type=ber,usr='.$usr.'|tlxsub',$bt),'subscrbt');
return div($ret,'subscrstats').div('','clear');}

#lists
static function listbt(){$ret=self::loadtm('tm='.ses('user'),lang('all'));
$r=sql('distinct(list)','tlex_ab','rv','where usr="'.ses('uid').'" and wait=0 and block=0');
if($r)foreach($r as $v)$ret.=self::loadtm('tm='.ses('user').',list='.$v,$v);
return div($ret,'lisb');}

#tris
static function labels(){$rb=[];
//$ra=[0=>'labels',284=>'free speech',283=>'fact',285=>'hypothesis',286=>'prospective',287=>'advertising'];
foreach($ra as $k=>$v)$rb[]=['down','js',atj('val',['lbl',$k]),'',$v];
$ra=sql('id,ref','labels','kv','');
foreach($ra as $k=>$v)$rb[]=['down/others','js',atj('val',['lbl',$k]),'',$v];
return $rb;}

static function lablbt(){$ret=''; $mode=$p['mode']??'public';//public,private
$r=self::apisql(['see'=>'labl','mode'=>$mode]);
//$r=sql('ref,icon','labels','kv','');
if($r)foreach($r as $k=>$v)$ret.=self::loadtm('labl='.$v[0].',mode='.$mode,ico($v[1]).lang($k));
return div($ret,'lisb');}

static function appsbt($p){$ret=''; $mode=$p['mode']??'';//public,private
//$r=self::apisql(['see'=>'app','mode'=>$mode]);
$r=sql('distinct app','tlex_app','k','');
if($r)foreach($r as $k=>$v)$ret.=self::loadtm('app='.$k.',mode='.$mode,langp($k));
//$ret.=loadapp::com(['tg'=>'cbck']);
return div($ret,'lisb');}

static function tagsbt($p){$ret=''; $mode=$p['mode']??'';//public,private
//$r=self::apisql(['see'=>'tag','mode'=>$mode]);
$r=sql('distinct tag','tlex_tag','k','');
if($r)foreach($r as $k=>$v)$ret.=self::loadtm('tag='.$k.',mode='.$mode,$k);
return div($ret,'lisb');}

static function mntsbt($p){$ret=''; $mode=$p['mode'];//public,private
$r=self::apisql(['see'=>'mnt','mode'=>$mode]);
if($r)foreach($r as $k=>$v)$ret.=self::loadtm('mnt='.$k.',mode='.$mode,ico('user').$k);
return div($ret,'lisb');}

static function tag2bt($p){$ret=''; $mode=$p['mode']??'';//public,private
//$r=sql('ref,id','tags','kv','');//['lg'=>ses('lng')]
$r=sqlrin('ref,tags.id',[['tags','id','tags_r','bid']],'kv','');//['lg'=>ses('lng')]
if($r)foreach($r as $k=>$v)if($v)$ret.=bj('cbck|tlxf,tagapp|bid='.$v,$k);
return div($ret,'lisb');}

#thread
static function thread_parents($id,$ret=[]){
$ib=sql('ib',self::$db,'v','where '.self::$db.'.id="'.$id.'"',0);
if($ib){$ret[$ib]=1; $ret=self::thread_parents($ib,$ret);}
return $ret;}

static function thread_childs($id,$ret=[]){
$r=sql('id',self::$db,'k','where ib='.$id,0); if($r)$ret+=$r;
if($r)foreach($r as $k=>$v)$ret=self::thread_childs($k,$ret);
return $ret;}

static function sql_thread($id,$o=''){
if($o!='parents')$ids=self::thread_childs($id); else $ids=[];
if($o!='childs')$ids=self::thread_parents($id,$ids);
if($o=='thread')$ids[$id]=1; ksort($ids);
if($ids)$r=array_keys($ids);
if(isset($r))return self::$db.'.id in ('.implode(',',$r).')';}

#pane
static function panehead($p){
[$id,$ib,$idv,$usr,$tousr,$pv,$us,$usid,$fast]=vals($p,['id','ib','idv','name','tousr','pv','us','usid','fast']);
if(!$pv)$pv=0; if(!$us)$us=ses('user'); if(!$usid)$usid=ses('uid'); $own=$usr==ses('user')?1:0;
$ret=bubble('profile,call|usr='.$usr.',sz=small','@'.$usr,'btxt',1).' ';
//$ret.=lk('/@'.$usr,$usr,'btxt" title="@'.$usr).' ';
if($p['typntf']??'')$ret.=self::readntf($p);
$time=self::relativetime($p['now']); $ret.=' &#8729; '.lk('/'.$id,$time,'grey').' &#8729; ';
if($fast)return $ret;
if($usid)$ret.=self::likebt($p).' ';
if($ib){$to=sqlin('name',self::$db,'login','uid','v','where '.self::$db.'.id='.$ib);
	$ret.=pagup('tlex,wrapper|ia='.$id,lang('in-reply to',1).' '.$to,'grey small').' ';}
if($nb=sql('count(id)',self::$db,'v',['ib'=>$id]))
	$ret.=pagup('tlex,wrapper|ib='.$id,$nb.' '.lang($nb>1?'replies':'reply',1),'grey small').' ';
if($usid)$ret.=bubble('tlxf,actions|id='.$id.',idv='.$idv.',uid='.$p['uid'].',lg='.$p['lg'].',pv='.$pv.',lbl='.$p['lbl'].',usr='.$usr,langpi('actions')).' ';//'pn'.$idv.
if($tousr==$us && $pv==3)$ret.=langpi('private message').' ';
elseif($pv)$ret.=langpi(self::$privdsk[$pv]).' ';
return $ret;}

static function pane($p,$current='',$op='tlex'){$head=''; $lb=''; //pr($p);
$id=$p['id']; $usr=$p['name']; $p['idv']='tlx'.$id; self::$id=$id;
$opt=get('id')==$id?1:0;
//if($p['privacy'])$ok=sql('wait','tlex_ab','v',[]);
if($p['ko']){$msg=div(help('telex_banned'),'alert');}
else{$head.=self::panehead($p);
	$msg=conn::call(['msg'=>$p['txt'],'app'=>'conn','mth'=>'reader','ptag'=>1,'opt'=>$id]);
	//if($lbxt=$p['ref'])$lb=span(lang($lbxt),'tx').icoit($p['icon'],$lbxt,'','21');}//$op
	if($app=$p['app'])$lb=span(langp($app),'tx');}//$op
$ret=div($head,'bloc_header').div($lb,'label');
$ret.=div('','bloc_redit','pn'.$p['idv']);
//$avt=div(img2($p['avatar'],'micro'),'avatarsmall bc_l','','');
$avt=profile::avatarsmall($usr);
$ret.=div($avt.div($msg,'message bc_r'),'bloc_content bc_grid');//.div('','','opn'.$id);
if($current==$id){self::$title=host(1).'/'.$id.' by @'.$usr;
	self::$descr=etc(strip_tags($msg));
	self::$image=host(1).'/img/mini/'.$p['avatar'];}
return div($ret,'telex','tlx'.$id,'');}

#rocketman
static function apisql($p,$z=0){//pr($p);
$ra=['tm','th','id','ib','ia','srh','ntf','from','since','list','noab','labl','count','app','tag','mnt','see','mode','likes'];
list($usr,$th,$id,$ib,$ia,$srh,$ntf,$from,$since,$list,$noab,$labl,$count,$app,$tag,$mnt,$see,$mode,$liks)=vals($p,$ra);
if($from=='wrp')return;
$vm='rr'; $gr=''; $ord=''; $db=self::$db; $sqin=[]; $us=ses('user'); $usid=ses('uid');
$cfg=['profile','like','labels'];
if(!$noab){$cfg[]='members'; $cfg[]='mentions';}
//if($labl or $see=='labl')$cfg[]='labels'; else
if($tag or $see=='tag')$cfg[]='tags';
//elseif($app or $see=='app')
$cfg[]='apps';
$sqcl[]=$db.'.id,uid,txt,lg,pv,ib,ko,unix_timestamp('.$db.'.up) as now,name';
$sqin[]='left join login on login.id=uid';
foreach($cfg as $k=>$v)//activations
	if($v=='profile'){$sqcl[]='pname,avatar,clr,privacy'; $sqin[]='left join profile on puid=uid';}
	elseif($v=='like'){$sqcl[]='tlex_lik.id as lid'; $sqin[]='left join tlex_lik on '.$db.'.id=lik';}
	elseif($v=='members'){$sqcl[]='list';//usr,ab
		if($mode=='public' or $tag or $app)$abs=''; else $abs=' and tlex_ab.usr="'.$usid.'"';
		$sqin[]='left join tlex_ab on '.$db.'.uid=ab and wait=0 and block=0'.$abs;}
	elseif($v=='labels'){$sqcl[]='ref,icon,lbl'; $sqin[]='left join labels on lbl=labels.id';}
	elseif($v=='mentions'){$sqcl[]='tousr'; $sqin[]='left join tlex_mnt on '.$db.'.id=tlex_mnt.tlxid';}
	elseif($v=='tags'){$sqcl[]='tag'; $sqin[]='left join tlex_tag on '.$db.'.id=tlex_tag.tlxid';}
	elseif($v=='apps'){$sqcl[]='app,p'; $sqin[]='left join tlex_app on '.$db.'.id=tlex_app.tlxid';}
if($count){$sqcl=['count('.$db.'.id)']; $vm='v';}
elseif($see=='labl'){$sqcl=['ref,labels.id,icon']; $vm='kvv';}
elseif($see=='app'){$sqcl=['app,tlex_app.id,p']; $vm='kvv';}
elseif($see=='tag'){$sqcl=['tag,tlex_tag.id']; $vm='kv';}
elseif($see=='mnt'){$sqcl=['tousr,tlex_mnt.id']; $vm='kv';}
if(is_numeric($from))$sqnd[]=$db.'.id<'.$from; elseif(is_numeric($since))$sqnd[]=$db.'.id>'.$since;
if($labl)$sqnd[]='labels.id="'.$labl.'"';
if($id)$sqnd[]=$db.'.id='.$id;
//elseif($ib)$sqnd[]='ib='.$ib;//childs
elseif($ib)$sqnd[]=self::sql_thread($ib,'childs');
elseif($ia)$sqnd[]=self::sql_thread($ia,'parents');
elseif($th)$sqnd[]=self::sql_thread($th,'thread');
//elseif($th)$sqnd[]='tlex.id in select id from tlex as t2 where ib=tlex.id;';
elseif($srh)$sqnd[]='((name="'.$srh.'" or txt like "%'.$srh.'%") and (privacy=0 or uid="'.ses('uid').'"))';
elseif($ntf){$sqcl[]='tlex_ntf.id as ntid,byusr,typntf,state';
	$sqin[]='left join tlex_ntf on txid='.$db.'.id and typntf in (1,2,3)'; $sqnd[]='4usr="'.$us.'"';}
elseif($mnt)$sqnd[]='tousr="'.$usr.'"';
elseif($tag)$sqnd[]='tag="'.$tag.'"';
elseif($liks)$sqnd[]='luid="'.$usid.'"';
elseif($list)$sqnd[]='list="'.$list.'"';
elseif($app)$sqnd[]='app="'.$app.'"';
elseif($noab)$sqnd[]='name="'.$usr.'"';
elseif($mode=='private')$sqnd[]='name="'.$usr.'"';
//elseif($answ)$sqnd[]='ib="'.$answ.'"';
//if($mode=='private')$sqnd[]='name="'.$usr.'"';
//elseif($mode=='public')$sqnd[]='name="'.$usr.'"';
else $sqnd[]='(privacy="0" or 0=(select wait from tlex_ab where usr="'.$usid.'" and tlex_ab.ab=tlex.uid))';//name!="'.$usr.'" and
if(!$noab && !$th)$sqnd[]='((pv=3 and tousr="'.$us.'") or (pv=2 and tlex_ab.usr="'.$usid.'") or pv<"'.($usid?2:1).'")';//(pv=4 and uid="'.$usid.'") or uid="'.$usid.'" or
//if(!$noab && !$th && !$app && !$tag && !$id && !$ntf)$sqnd[]='name!="'.$usr.'"';
if(!$id && !$see)$gr=' group by '.$db.'.id';//if(!$count)
if($ia or $ib or $th)$ord=' order by '.$db.'.id asc limit 40';
elseif(!$count && !$id && !$th && !$see && !$ntf)$ord=' order by '.$db.'.id desc limit 40';
elseif($ntf)$ord=' order by ntid desc limit 20';
//$sqcl=[$db.'.id'];
//pr(array_merge($sqcl,$sqin,$sqnd,[$gr],[$ord]));
$cols=implode(',',$sqcl); if($sqin)$in=implode("\n",$sqin); $w=implode(' and ',$sqnd);
return sql($cols,self::$db,$vm,$in.' where '.$w.$gr.$ord,$z);}

#read
static function read($p){//$id will be in popup
$ret=''; $id=''; $r=[]; $pm=''; $bt=''; $tm='tm'; $p['us']=ses('user'); $p['usid']=ses('uid');
list($last,$usr,$own,$id,$th,$ib,$ia,$lbl)=vals($p,['last','usr','own','id','th','ib','ia','labl']);
if($th && !$last){$id=$p['th']=$th; $pm='th='.$id;}//thread(parents+childs)
elseif($ia && !$last){$p['ia']=$ia;}//parents
elseif($ib && !$last)$p['ib']=$ib;//childs
elseif($id && !$last){$p['id']=$id;}//one
elseif($usr && $own){$p['tm']=$usr; $p['noab']=1; $pm='tm='.$usr.',noab=1';}//noab
else{$tm=$p['tm']??''; $usr=$usr?$usr:($tm?$tm:($own?$own:$p['us'])); $pm='tm='.$usr;//timeline
	$p['tm']=$tm?$tm:$usr;}//$p['from']=$last;
if($lbl)$pm='labl='.$lbl; if($p['noab']??'')$pm.=',noab=1';
if(isset($p))$r=self::apisql($p);
if($r){foreach($r as $k=>$v){$ret.=self::pane($v,$id);}}//self::$rf[]=chrono($v['id']);
//if($pm && isset($v['id']))$ret.=bj('after,tlx'.$v['id'].',2|tlex,read|'.$pm.',from='.$v['id'],pic('down'),'licon');
//$bt=trace(self::$rf);
if(!$ret && !$last)$ret=help('empty_home','board');
//else $ret.=hidden('prmtm',$pm); //echo $pm;
return $bt.$ret;}

static function readusr($p){//authorized to watch
$usr=$p['usr']; $uid=$p['cuid'];
$open=sql('auth','login','v','where id="'.$uid.'"');
if(!$open)return div(ico('lock').helpx('closed account'),'pane');
$prv=sql('privacy','profile','v','where puid="'.$uid.'"');//
//$cuid=idusr($usr);
if($prv){$id=sql('id','tlex_ab','v','where usr="'.ses('uid').'" and ab="'.$uid.'" and wait=0 and block=0');
	if(!$id)return div(ico('lock').helpx('private account'),'pane');}
return self::read(['tm'=>$usr,'noab'=>1]);}

static function wrapper($p){//$p['rs']=$p['id'];
return div(self::read($p),'bkg','wrapper');}

static function one($p){$r=self::apisql($p);
if($r)return div(self::pane(current($r),$p['id'],''),'','clbck');
else return help('empty_home','board');}

static function api($p){return self::call($p);}

static function iframe($p){
$ret=self::one($p);
add_head('csslink','/css/global.css');
add_head('csslink','/css/apps.css');
add_head('csslink','/css/fa.css');
add_head('jslink','/js/ajax.js');
add_head('jslink','/js/utils.js');
$ret=tag('body',['onmousemove'=>'popslide(event)','onmouseup'=>'closebub(event)'],$ret);
$ret.=tag('div',['id'=>'closebub','onclick'=>'bubClose()'],'');
$ret.=tag('div',['id'=>'popup'],'');
return generate().$ret;}

///api.php?app=tlex&mth=call&prm=tm:dav
static function call($p){$r=self::apisql($p);
if($r)foreach($r as $k=>$v){
	$r[$k]['avatar']=host(1).'/img/full/'.$v['avatar'];
	$r[$k]['txt']=conn::call(['msg'=>$v['txt'],'app'=>'conn','mth'=>'reader']);}
if($r)return json_enc($r);}

#content
static function content($p){
//self::install(); //profile::install();
//if(ses('dev')=='prog')self::$db='tlex';//alternative table
return home::content($p);}
}
?>
