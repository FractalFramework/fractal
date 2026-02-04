<?php

class twitter{
static $private=2;
static $rid='tw';
static $nb=20;
static $db='twitter';
static $db_cols=['owner'=>'var','consumer_key'=>'var','consumer_secret'=>'var','token_key'=>'var','token_secret'=>'var'];
static $db2='twits';
static $db2_cols=['twid'=>'bint','name'=>'var','screen_name'=>'var','date'=>'var','text'=>'bvar','media'=>'var','reply_id'=>'bint','reply_name'=>'var','favs'=>'var','retweets'=>'int','followers'=>'int','friends'=>'int','quote_id'=>'bint','quote_name'=>'var','lang'=>'svar','location'=>'svar','verified'=>'svar','protected'=>'svar','profile_img'=>'var'];//'banner'=>'var','tags'=>'var',

//static $db3='twits_users';
//static $db3_cols=['twusr'=>'bint','name'=>'var','screen_name'=>'var','location'=>'svar','verified'=>'svar','protected'=>'svar','profile_img'=>'var','banner'=>'var'];

static function install($p=''){
//sql::create(self::$db,self::$db_cols,1);
//sql::create(self::$db3,self::$db3_cols,1);
sql::create(self::$db2,self::$db2_cols,1);}

//js to append to the header of the parent page
static function js(){return 'exs=[]';}//reinit the continuous scrolling

static function headers(){
self::$rid=randid('plg');
head::add('jscode','	
var exs=[];
var call="atend,'.self::$rid.'|twitter,call";
var params=[];
//params.push("headers=1");
function twlive(e){
	var scrl=pageYOffset+innerHeight;
	var mnu=getbyid("'.self::$rid.'").getElementsByTagName("section");
	var load=mnu[mnu.length-4];
	var pos=getPositionRelative(load);
	var last=mnu[mnu.length-1];
	var id=last.id;
	var idx=exs.indexOf(id);
	if(idx==-1 && scrl>pos.y){exs.push(id); ajx(call+\'max=\'+id+);}}
//addEvent(document,"scroll",function(event){twlive(event)});');}

static function error($q){if(!is_array($q))return 'no';
if(array_key_exists('errors',$q))$er=$q['errors'][0]['message'];
if(isset($er))return div(helpx('error').' : '.$er,'alert');}

static function repair(){
$ra=sql('id,twid',self::$db2,'kv','');
foreach($ra as $k=>$v){if(isset($rb[$v]))sql::del(self::$db2,$k); $rb[$v]=1;}}

static function twsave($r){
$ex=sql('id',self::$db2,'v','where twid='.$r['twid']);
if(!$ex)sql::sav(twitter::$db2,$r);
else sql::upd(self::$db2,$r,$ex);}

#play-q
static function banner($p){
$t=new twit(self::init()); $q=$t->show($p['usr']);//lookup eco($q);
$ret=tag('h2','',val($q,'name'));
$ret.=self::twimg(val($q,'profile_image_url')).' ';
$ret.=lk(self::twurl(val($q,'screen_name')),'@'.val($q,'screen_name'),'grey',1);
$ret.=self::twimg(val($q,'profile_banner_url'));
$ret.=div(val($q,'description'),'txt');
return div($ret);}

static function from($q){
$name=($q['user']['name']);
$url=self::twurl($q['user']['screen_name'],$q['id']);
return lk($url,pic('user').$name,'btn');}

static function twdate($usr,$id,$d){
$url=self::twurl($usr,$id);
$time=is_numeric($d)?$d:strtotime($d);
$date=date('H:i - d/m/Y',$time);
return lk($url,$date,'grey small');}

#play-r
static function reply($r){
$id=$r['reply_id']; $u=$r['reply_name']; $ret='';
if($id)$ret=bj('popup|twitter,thread|id='.$id,picxt('parents',$u),'btn').' ';
$ret.=bj('popup|twitter,call|mode=rpl,id='.$r['twid'],pic('replies'),'btn').' ';
return $ret;}

static function embed_url($d){$ret='';
$d=str_replace("\n",' && ',$d); $r=explode(' ',$d);
foreach($r as $v){
	if(strncmp($v,'http',4)===0)$ret.=lk($v,$v).' '; 
	elseif(strncmp($v,'@',1)===0)$ret.=lk('https://twitter.com/'.substr($v,1),$v).' '; 
	else $ret.=$v.' ';}
return str_replace(' && ',br(),$ret);}

static function twimg($f,$o=''){
$dr='img/tw/'; $xt=ext($f); $fb=strid($f).$xt; $x=substr($xt,1);
if(file_exists($dr.$fb))return img(host(1).'/'.$dr.$fb);//
elseif($o){$d=get_file($f); if($d)write_file($dr.$fb,$d);
	if(file_exists($dr.$fb))return img(host(1).'/'.$dr.$fb);}
return img('data:image/'.$x.';base64,'.base64_encode(get_file($f)));}

static function twurl($u,$id=''){
$ret='https://twitter.com/'.$u; if($id)$ret.='/status/'.$id;
return $ret;}

//fav
static function mkfav($p){
$t=new twit(self::init());
$id=$p['id']??''; $is=val($p,'is',0); 
$q=$t->like($id,$is);
$n=isset($q['favorite_count'])?$q['favorite_count']:'0';
return self::btfav($p['id'],$n,$q['favorited']);}

static function btfav($id,$n,$ok){
$s=$ok?'color:#e0245e;':''; $bt=ico('heart',$s,'like','','',$n);
if(!auth(6))return $bt;
return bj('fav'.$id.'|twitter,mkfav|id='.$id.',is='.$ok,$bt,'');}

//retweet
static function mkrtw($p){
$t=new twit(self::init());
$id=$p['id']??''; $is=val($p,'is',0); 
$q=$t->retweet($id,$is);
$n=isset($q['retweet_count'])?$q['retweet_count']:'0';
return self::btrtw($p['id'],$n,$q['retweeted']);}

static function btrtw($id,$n,$ok){
$s=$ok?'color:#17bf63;':''; $bt=ico('retweet',$s,'like');
if(!auth(6))return $bt;
return bj('rtw'.$id.'|twitter,mkrtw|id='.$id.',is='.$ok,$bt,'');}

//thread
static function thread_up($t,$p){
$q=$t->read($p); $ret='';
if(isset($q['in_reply_to_status_id']))
	$ret=self::thread_up($t,$q['in_reply_to_status_id']);
if(isset($q['id']))$ret.=self::read($q);
return $ret;}

static function thread($p){
$t=new twit(ses('twid'));
return self::thread_up($t,$p['id']??'');}

//replies
static function replies($p,$id=''){$t=new twit;
$q=$t->read($p); $usr=$q['user']['screen_name'];
$q=$t->search($usr,100,$id); if($q)$r=$q['statuses'];
if($r)foreach($r as $k=>$v){
	$b=$v['in_reply_to_status_id'];
	if($b!=$p or !$b)unset($r[$k]);}
//if(!$r)$q=$t->search($usr,100,$b); pr($r);
return $r;}

//write
static function post($p){$txt=val($p,'inp2'); 
$t=new twit(self::init()); if($t)$res=$t->update($txt); //pr($res);
if(array_key_exists('errors',$res))$p['error']=$res['errors'][0]['message'];
if(isset($er))return help('error','alert');
else return self::call($p);}

#datas
static function clean0($d){eco($d); $d=strip_tags($d);
if($n=strpos($d,"&mdash;")!==false)$d=substr($d,0,$n);
if($n=strpos($d,'pic.twitter')!==false)$d=substr($d,0,$n);
if($n=strrpos($d,'https://t.co')!==false)$d=substr($d,0,$n-1);
return $d;}

static function clean1($d){$q=dom($d); //eco($d);
$r=$q->getElementsByTagName('p');//textContent
if($r)foreach($r as $k=>$v)if(domattr($v,'dir')=='ltr')$ret=str::utf8dec($v->nodeValue);
return $ret;}

static function clean($d){//eco($d);
if(strpos($d,'<p')!==false)$d=between($d,'<p','</p>'); 
$d=conv::com($d); $d=conn::call(['msg'=>$d,'ptag'=>'no','mth'=>'twits']); //eco($d); 
return str::clean_n($d);}

static function oembed($q){
$u='https://twitter.com/'.$q['user']['screen_name'].'/status/'.$q['id'];
//$f='https://publish.twitter.com/oembed?url='.$u; $d=get_file($f); $r=json_decode($d,true);
$t=new twit(self::init()); $r=$t->embed($u);
if(isset($r['error']))$ret=$q['text']; else $ret=$r['html']; 
return delbr($ret,"\n");}

static function urls($r,$id){$rb=[];
if($r)foreach($r as $k=>$v){$u=$v['expanded_url'];
	if(substr($u,0,20)=='https://twitter.com/'){$id_rtw=strend($u,'/'); 
		if(is_numeric($id_rtw) && $id_rtw!=$id && $id_rtw!=$id)$rb[]=$id_rtw;}
	//elseif(substr($u,0,16)=='https://youtu.be')$rb[]=strend($u,'/');
	//elseif(substr($u,0,23)=='https://www.youtube.com')$rb[]=between($u,'v=','&');
	elseif(substr($u,0,4)=='http')$rb[]=$u;}
return $rb;}

static function medias($q){$rb=[];
if(isset($q['entities']['media']))$r=$q['entities']['media'];
if(isset($r))foreach($r as $k=>$v)if($v['type']=='photo')$rb[]=$v['media_url_https'];
if(isset($q['extended_entities'])){$r=$q['extended_entities']['media'][0];
	if($r['type']=='photo' or $r['type']=='video')$rb[]=$r['media_url_https'];
	if(@$r['video_info']['variants'][1]['content_type']=='video/mp4')
		$rb[]=$r['video_info']['variants'][1]['url'];}
$rc=self::urls($q['entities']['urls'],$q['id']);
if($rc)$rb=array_merge($rb,$rc);
if($rb)$rb=array_flip(array_flip($rb));
return implode(' ',$rb);}

static function datas($q){//pr($q);
if(!isset($q['id']))return;
$ret['twid']=$q['id'];
$ret['name']=$q['user']['name'];
$ret['screen_name']=$q['user']['screen_name'];
//$ret['date']=$q['created_at'];
$ret['date']=strtotime($q['created_at']);
//$ret['date']=date('d/m/Y H:i:s',$time);
//$ret['hour']=date('',$time);
$txt=self::oembed($q); //eco($txt);
$ret['text']=self::clean($txt?$txt:$q['text']);
$ret['media']=self::medias($q);
//$ret['tags']=isset($q['entities']['hashtags'])?$q['entities']['hashtags']:'';
$ret['reply_id']=$q['in_reply_to_status_id'];
$ret['reply_name']=$q['in_reply_to_screen_name'];
$ret['favs']=isset($q['favorite_count'])?$q['favorite_count']:'0';
$ret['retweets']=$q['retweet_count'];
$ret['followers']=$q['user']['followers_count'];
$ret['friends']=$q['user']['friends_count'];
$ret['quote_id']=isset($q['quoted_status_id'])?$q['quoted_status_id']:'';
$ret['quote_name']=isset($q['quoted_status']['user']['screen_name'])?$q['quoted_status']['user']['screen_name']:'';
$ret['lang']=$q['lang'];
$ret['location']=$q['user']['location'];
$ret['verified']=$q['user']['verified'];
$ret['protected']=$q['user']['protected'];
//$ret['url']=$q['user']['url'];
$ret['profile_img']=$q['user']['profile_image_url'];
//$ret['banner']=$q['user']['profile_background_image_url'];
return $ret;}

//playusr
static function userlist($r){$t=new twit;
$n=count($r); $nb=ceil($n/100); $ia=0; $i=0; //$qu=$t->show($q['ids'][0]); pr($qu);
if($r)foreach($r as $v){if($i==99){$i=0; $ia++;} $rb[$ia][$i]=$v; $i++;} //pr($rb);
if($rb)foreach($rb as $v){$d=implode(',',$v); $qu=$t->lookup($d); //pr($qu);
	if($qu)foreach($qu as $k=>$vb)$rc[]=$vb;} //pr($rc);
return $rc;}

static function playusr($q){$ret='';
if($er=self::error($q))return $er;
//$r=self::userlist($q['ids']); //eco($r);
//foreach($r as $k=>$v)$ret.=div(self::banner(['usr'=>$v]));
foreach($q['ids'] as $k=>$v)$ret.=div(self::banner(['usr'=>$v]));
return $ret;}

static function see($p){$id=$p['id']??'';
$r=sql('all',self::$db2,'ra',['twid'=>$id]);
return eco($r,1);}

static function usr($p){$id=$p['id']??''; return;
return sql::inner('name',self::$db,'login','uid','v',$id);}

static function modif0($p){$id=$p['id']??'';
$r=sql('all',self::$db2,'ra',['twid'=>$id]);

}

//play
static function play($r,$q='',$b=''){
if(!$r && is_array($q))$r=self::datas($q); elseif(!$r)return; $id=$r['twid'];//pr($r);
$ret=bj('popup|twitter,banner|usr='.$r['screen_name'],picxt('user',$r['name']),'btn');
$ret.=self::twdate($r['screen_name'],$id,$r['date']).' ';
$ret.=self::reply($r).' ';
$ret.=span(self::btfav($id,$r['favs'],$q?$q['favorited']:''),'btn','fav'.$id).' ';
$ret.=span(self::btrtw($id,$r['retweets'],$q?$q['retweeted']:''),'btn','rtw'.$id).' ';
if(ses('twusr')==$r['screen_name'])
	$ret.=bj($id.',,z|twitter,call|id='.$id.',mode=del',pic('del'),'btn');
if(auth(6))$ret.=bj($id.',,z|twitter,call|id='.$id.',o=1,b=1',pic('refresh'),'btn');
//if(auth(6))$ret.=bj($id.',,z|twitter,call|id='.$id.',o=1,b=1',pic('save'),'btn');//detect prop
//if(auth(6))$ret.=bj($id.',,z|twitter,modif|id='.$id,pic('modif'),'btn');
if(auth(6))$ret.=popup('twitter,see|id='.$id,pic('code'),'btn');//detect prop
$txt=nl2br($r['text']);//!oldies have br
$ret.=div($txt,'txt');//self::embed_url
$rb=explode(' ',$r['media']);
foreach($rb as $v)
	if(is_img($v))$ret.=div(self::twimg($v,1));
	elseif(is_numeric($v))$ret.=self::call(['id'=>$v]);
	elseif(strpos($v,'youtu'))$ret.=div(video::com($v));
	else $ret.=div(web::play($v));
if($v=$r['quote_id'])$ret.=self::call(['id'=>$v]);
if($b)return $ret;
return tag('section',['id'=>$id,'class'=>'paneb'],$ret);}

//read
static function read($twid,$o='',$b=''){$q='';
$r=sql('all',self::$db2,'ra',['twid'=>$twid]); //p($r);
if(!$r or $o){$t=new twit(self::init()); $q=$t->read($twid); //p($q);
	if($er=self::error($q))return $er;
	else{$r=self::datas($q); self::twsave($r);}}
if($b=='2')return $r;
return self::play($r,$q,$b);}

static function com($p){
$twid=val($p,'twid');
return self::read($twid,2);}

static function batch($r,$mode,$req,$o){$ret='';
if($er=self::error($r))return $er;
if(is_array($r))foreach($r as $q)if(isset($q['id']))$ret.=self::play('',$q);
if(isset($q['id']) && !get('popup'))$ret.=bj('atend,'.self::$rid.',z|twitter,call|id='.$req.',mode='.$mode.',max='.$q['id'],div(lang('following'),'btok'));
return $ret;}

#call from ajax
static function call($p){
$req=$p['id']??'';//id or usr
$mode=val($p,'mode');//rpl//rtw
$max=val($p,'max');//continuous scrolling
$o=val($p,'o'); $b=$p['b']??'';
$ret=''; $q=''; $qr=''; $qu='';
if(substr($req,0,4)=='http')$req=strend($req,'/');
$t=new twit(self::init());
if(is_numeric($req)){
	if($mode=='rtw')$qu=$t->retweeters($req,self::$nb);
	elseif($mode=='rpl')$qr=self::replies($req);
	elseif($mode=='del')$qb=$t->delete($req);
	else $ret=self::read($req,$o,$b);}
else{
	if($mode=='mbd')$q=$t->embed($req);
	elseif($mode=='ban')$ret=self::banner(['usr'=>$req]);
	elseif($mode=='flw')$qu=$t->followers($req);
	elseif($mode=='src'){$q=$t->search($req,self::$nb,$max); if($q['statuses'])$q=$q['statuses'];}
	//elseif($mode=='fav')$q=$t->favorites($req,$max);
	else $qr=$t->timeline($req,self::$nb,$max,$mode);}//home/user
if($qr)$ret=self::batch($qr,$mode,$req,$o);
elseif($qu)$ret=self::playusr($qu);
//elseif($q)$ret=self::read($q,$o);
return $ret;}

//admin
static function edit($p){$usr=$p['usr']??'';
$ret=textarea('inp2','','','',lang('text to twit'));
$ret.=bj(self::$rid.',,1|twitter,post|usr='.$usr.'|inp2',langp('send'),'btsav');
return $ret;}

static function slct($p){$ret=''; $c=ses('twid');
$r=sql('id,owner',self::$db,'kv',['uid'=>ses('uid')]);
if($r)foreach($r as $k=>$v)$ret.=bj('page|twitter|user='.$v,pic('api').$v,$c==$k?'active':'');
if(ses('uid'))$ret.=bj('popup|admin_twitter',langp('params'));
return div($ret,'list');}

static function twusr($p){$ret=''; $c=ses('twid');
if($twusr=val($p,'twusr'))return twitter::init($twusr);
$r=sql('id,owner',self::$db,'kv',['uid'=>ses('uid')]);
if($r)foreach($r as $k=>$v)$ret.=bj('socket|twitter,twusr|twusr='.$v,pic('api').$v,active($c,$k));
if(ses('uid'))$ret.=bj('popup|admin_twitter',langp('params'));
return div($ret,'list');}

static function init($usr=''){$d=2;
$uid=$usr?usrid($usr):ses('uid');
if($usr)[$d,$u]=sql('id,owner',self::$db,'rw',['uid'=>ses('uid'),'owner'=>$usr]);
elseif(!$d=ses('twid'))[$d,$u]=sql('id,owner',self::$db,'rw','where uid="'.$uid.'" limit 1');
if(isset($u))ses('twusr',$u); return ses('twid',$d);}

#content
static function content($p){
//self::install();
$twid=self::init();
$bt=bubble('twitter,twusr',langp('account'),'btn');
if($twid){
	$bt.=bj(self::$rid.',,z|twitter,call|mode=tl',langpi('timeline'),'btn');
	$bt.=input('id','',24,1);
	$bt.=bj(self::$rid.',,z|twitter,call||id',langp('ok'),'btn');
	$bt.=bj(self::$rid.',,z|twitter,call|mode=src|id',langpi('search'),'btn');
	$bt.=bj(self::$rid.',,1|twitter,call|mode=rpl|id',langpi('replies'),'btn');
	$bt.=bj(self::$rid.',,z|twitter,call|mode=rtw|id',langpi('retweets'),'btn');
	$bt.=bj(self::$rid.',,z|twitter,call|mode=flw|id',langpi('followers'),'btn');
	if(auth(6))$bt.=bj(self::$rid.',,z|twitter,call|mode=mbd|id',langpi('embed'),'btn');
	//if(auth(6))$bt.=bj(self::$rid.',,1|twitter,post|usr='.$usr.'|id',langpi('send'),'btsav');
	$bt.=popup('twitter,edit',langpi('publish'),'btn');}
$ret=div('','',self::$rid);//self::call($p)
return div($bt.$ret,'board');}
}

?>