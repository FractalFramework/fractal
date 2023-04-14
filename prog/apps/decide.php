<?php

class decide extends appx{
static $private=1;
static $a='decide';
static $db='decide';
static $cb='dcd';
static $cols=['tit','txt','cl'];
static $typs=['var','bvar','int'];
static $open=0;
static $tags=1;

function __construct(){$r=['a','db','cb','cols'];
foreach($r as $v)parent::$$v=self::$$v;}

//install
static function install($p=''){
sql::create('decide_args',['bid'=>'int','uid'=>'int','position'=>'int','txt'=>'bvar'],1);
sql::create('decide_chat',['cid'=>'int','uid'=>'int','txt'=>'bvar'],1);
sql::create('decide_valid',['cid'=>'int','uid'=>'int','val'=>'int'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1'; return parent::admin($p);}
static function headers(){head::add('csscode','');}

#edit
static function del($p){return parent::del($p);}
static function modif($p){return parent::modif($p);}
static function save($p){return parent::save($p);}
static function create($p){return parent::create($p);}
static function form($p){return parent::form($p);}
static function edit($p){return parent::edit($p);}

#generics
static function linktopoll($id,$cid){if($cid)$cid='/'.$cid;
return lk('/decide/'.$id.$cid,ico('link'),'small');}

static function usrlog($date,$name){
$date=span(date('Y-m-d',strtotime($date)),'small');
return small(lang('by').' '.$name).' '.span($date,'date').' ';}

static function textarea($id,$v){
return textarea($id,$v,60,4,lang('description'),'',216).br();}

private static function security($table,$id){
$uid=sql('uid',$table,'v',$id);
if($uid==ses('uid'))return 1;}

#algo
static function algo($ra,$r){$ret=0; $score=0;
if($r)foreach($r as $id=>$v){$tot=count($v); $pos=$ra[$id]; $r1=0; $r2=0;
	foreach($v as $ka=>$va){if($va==1)$r1+=1; else $r2+=1;}
	if($pos==1)$score+=($r1-$r2)*$tot; else $score-=($r1-$r2)*$tot;}
return $score;}

static function algo2($ra,$r){$ret=0; $score=0; $r1=0; $r2=0; $tot=0;//relative
if($r)foreach($r as $id=>$v){$tot+=count($v); $pos=$ra[$id];
	foreach($v as $ka=>$va){if($va+$pos==2 or $va+$pos==4)$r1+=1; else $r2+=1;}}
return round(($r1/$tot)*2-1,2);}

static function argcalc($p){$id=$p['id']; $ret=''; $score=0;
$ra=sql('id,position','decide_args','kv','where bid='.$id); //pr($ra);
$r=sql::inner('decide_args.id,decide_valid.id,val','decide_valid','decide_args','cid','kkv','where bid='.$id.' and (val=1 or val=2) order by cid');
if($ra)$score=self::algo2($ra,$r); //pr($res);
$css=isset($score) && $score>0?'resyes':'resno';
$ret=bj('score'.$id.'|decide,argcalc|id='.$id,langp('score').' : '.$score,'score '.$css);
$ret.=hlpbt('decide_algo2');
return div($ret,'right','score'.$id,'min-width:180px; text-align:right;');}

#vote
static function savevote($p){
$id=sql('id','decide_valid','v','where cid="'.$p['cid'].'" and uid="'.ses('uid').'"');
if(isset($id))$p['val']=$p['val']!=val($p,'current')?$p['val']:'0';
if(!isset($id))$p['idc']=sql::sav('decide_valid',[$p['cid'],ses('uid'),$p['val']]);
else sql::up('decide_valid','val',$p['val'],$id);
return self::vote($p);}

static function vote($p){
[$cid,$pos,$kid]=vals($p,['cid','position','dcd']);
$vote=sql('val','decide_valid','v','where cid="'.$cid.'" and uid="'.ses('uid').'"');
$yes=sql('count(val)','decide_valid','v','where cid="'.$cid.'" and val="1"');//tots
$no=sql('count(val)','decide_valid','v','where cid="'.$cid.'" and val="2"');
$pb=$kid.'|decide,savevote|cid='.$cid.',current='.$vote.',position='.$pos;
$cs1=$vote==1?' active':''; $cs2=$vote==2?' active':'';
if($pos==1){$cp1='argok'; $cp2='argko';} else{$cp1='argko'; $cp2='argok';}
$ck1=$vote==1?ico('check'):''; $ck2=$vote==2?ico('check'):'';
$bt1=$ck1.' '.lang('agree').' ('.$yes.')';
$bt2=$ck2.' '.lang('not agree').' ('.$no.')';
$buid=sql('uid','decide_args','v',$cid);
if(ses('uid') && $buid!=ses('uid')){//forbid author to vote//
	$ret=bj($pb.',val=1',$bt1,'btn'.$cs1.' '.$cp1);
	$ret.=bj($pb.',val=2',$bt2,'btn'.$cs2.' '.$cp2);}
else $ret=span($bt1,'btn').span($bt2,'btn');
//$score=round(($yes-$no)*($yes+$no),2);
$score=round(($yes-$no)*($pos==1?1:-1),2);
if(($yes-$no>0 && $pos==1) or ($yes-$no<0 && $pos==2))$c='argyes'; else $c='argno';
$ret.=span(pic('score').$score,'score '.$c);
return span($ret,'',$kid).' ';}

#chat
static function delchat($p){
if(!self::security('decide_chat',$p['idc']))return;
if(!val($p,'ok'))return bj('cht'.$p['cid'].'|decide,delchat|ok=1,'.prm($p),langp('really?'),'btdel');
if($p['idc'])sql::del('decide_chat',$p['idc']);
return self::panechat($p);}

static function savechat($p){
$p['idc']=sql::sav('decide_chat',[$p['cid'],ses('uid'),$p['txt'.$p['cid']]]);
return self::panechat($p);}

static function addchat($p){$id=$p['id']; $cid=$p['cid']; $inp='txt'.$cid;
$ret=self::textarea($inp,'');
$ret.=bj('cht'.$cid.'|decide,savechat|id='.$id.',cid='.$cid.'|'.$inp,lang('save'),'btsav');
return div($ret,'pane');}

static function panechat($p){$id=$p['id']; $cid=$p['cid']; $rid='cht'.$cid; $ret='';
$b='decide_chat'; $cols=$b.'.id as id,name,txt,'.$b.'.up as date';
$r=sql::inner($cols,$b,'login','uid','rr','where '.$b.'.cid='.$cid.' order by '.$b.'.id desc');
if(ses('uid'))$ret.=bj($rid.'|decide,addchat|id='.$id.',cid='.$cid,langp('add comment'),'btn');//add
if($r)foreach($r as $v){//read
	$by=span('#'.$v['id'],'btn').' '.self::usrlog($v['date'],$v['name']);
	if($v['name']==ses('usr'))$by.=span(bubble('decide,delchat|id='.$id.',cid='.$cid.',idc='.$v['id'].'',pic('del'),'btdel'),'right');
	$txt=div($v['txt'],'content');
	$ret.=div($by.$txt,'paneb');}
return div($ret,'');}//,$rid

#argument
static function delarg($p){$cid=$p['cid'];
if(!self::security('decide_args',$cid))return;
if(!val($p,'ok'))return bj('arg'.$p['id'].'|decide,delarg|ok=1,'.prm($p),langp('really?'),'btdel');
sql::del('decide_args',$cid);
sql::del('decide_valid',$cid,'cid');
return self::arguments($p);}

static function savarg($p){$id=$p['id'];
$r=[$id,ses('uid'),$p['pos'],$p['txt'.$id]];
$p['cid']=sql::sav('decide_args',$r);
return self::arguments($p);}

static function editarg($p){$id=$p['id']; $inp='txt'.$id;
$sens=$p['pos']==1?'argyes':'argno';
$ret=span(lang($sens),'btn '.$sens).br();
$ret.=self::textarea($inp,'');
$ret.=bj('arg'.$id.'|decide,savarg|id='.$id.',pos='.$p['pos'].'|'.$inp,lang('save'),'btsav');
return $ret;}

static function panearg($p){$ret=''; $bt='';
$id=$p['id']??''; $cid=val($p,'cid'); if(!$cid)return;
if(!isset($id) && $cid)$id=sql('bid','decide_args','v',$cid);
$cols='position,name,txt,decide_args.up as date'; $w='where decide_args.id='.$cid;
$r=sql::inner($cols,'decide_args','login','uid','ra',$w);
$c=$r['position']==1?'argyes':'argno';
//if($cid)$ret=bj('vt'.$id.'|decide,play|id='.$id,pic('back'),'btn');
if($r['position'])$ret.=span(lang($c).' #'.$cid,'btn '.$c).' ';
$ret.=self::usrlog($r['date'],$r['name']);
$bt=self::linktopoll($id,$cid);
if($r['name']==ses('usr'))$bt.=bubble('decide,delarg|cid='.$cid.',id='.$id,pic('delete'),'btdel');
$ret.=span($bt,'right');//header
$ret.=div($r['txt'],'content');//txt
$p['position']=$r['position'];
$ret.=self::vote($p);//vote
$n=sql('count(id)','decide_chat','v','where cid="'.$cid.'"');
$rid='cht'.$cid.'|decide,panechat|id='.$id.',cid='.$cid;
$ret.=toggle($rid,langp('comments').' ('.$n.')','btn');
$ret.=div('','panec','cht'.$cid);
$c=$r['position']==1?'argok':'argko';
$ret=div($ret,'paneb '.$c,'','');
return $ret;}

#stream
static function arguments($p){$id=$p['id']; $ret='';
if(ses('uid')){$ret=span(langp('add arg'),'btn');
	$ret.=bj('arg'.$id.'|decide,editarg|pos=1,id='.$id,langp('addargyes'),'btsav');
	$ret.=bj('arg'.$id.'|decide,editarg|pos=2,id='.$id,langp('addargno'),'btdel');}
else $ret=help('need login');
$ret.=hlpbt('decide_args');
$r=sql('id','decide_args','rv','where bid='.$id.' order by up desc');
if($r)foreach($r as $k=>$v)$ret.=self::panearg(['id'=>$id,'cid'=>$v]);
return $ret;}

#play
static function play($p){
$id=$p['id']??''; $p['cid']=val($p,'o'); $arg=''; if(!$id)return;
$cols='name,tit,txt,cl,'.self::$db.'.up as date';
$w='where '.self::$db.'.id='.$id;
$r=sql::inner($cols,self::$db,'login','uid','ra',$w);
if($r['cl']==1)return self::argcalc($p).div(nl2br($r['txt']),'txt').help('form closed','alert');
//$go=bj('vt'.$id.'|decide,play|id='.$id,ico('refresh').$r['tit'],'btn');//admin
$ret=self::usrlog($r['date'],$r['name']);
$ret.=self::linktopoll($id,'').br();
$ret.=div(nl2br($r['txt']),'content');
$n=sql('count(id)','decide_args','v','where bid="'.$id.'"');//args
//$ret.=toggle('arg'.$id.'|decide,arguments|id='.$id,lang('args').' ('.$n.')','btn');
//$ret.=bj('arg'.$id.'|decide,arguments|id='.$id,lang('args').' ('.$n.')','btn');
$ret.=self::argcalc($p);//calc
$ret=div($ret,'pane');//render
if($p['cid'])$arg=self::panearg($p);
else $arg=self::arguments(['id'=>$id]);
$ret.=div($arg,'panec','arg'.$id);
return $ret;}

static function stream($p){$p['privacy']='0'; return parent::stream($p);}
static function tit($p){$p['t']='txt';return parent::tit($p);}

//call
static function call($p){$id=$p['id'];
if(!is_numeric($id)){$iz=self::idsuj($p); if($iz)$p['id']=$id=$iz;}
return div(self::play($p),'','vt'.$id);}

static function com($p){return parent::com($p);}

static function content($p){
//self::install();
return parent::content($p);}
}

?>