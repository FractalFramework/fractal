<?php
class friends{
static $private=2;
static $db='tlex_ab';
static $cols=['usr','ab','list','wait','block'];
static $typs=['int','int','var','int','int'];

//install
static function install(){$n=0;
//appx::install(array_combine(self::$cols,self::$typs));
sql::create(self::$db,array_combine(self::$cols,self::$typs),$n);}

static function admin($p){}

#follow
static function follow($p){$uid=ses('uid');
$rp=['rid','usr','subslist','follow','block','refuse','approve','unfollow','list'];
[$rid,$usr,$list,$flw,$block,$refus,$apr,$unf,$lst]=vals($p,$rp); if(!$list)$list=$flw; $cuid=idusr($usr);
if($list){//save
	$id=sql('id','tlex_ab','v',['usr'=>$uid,'ab'=>$cuid]);
	if($id)sql::upd('tlex_ab',['list'=>$list],$id);
	else{$private=sql('privacy','profile','v',['pusr'=>$usr]);
		sql::sav('tlex_ab',[$uid,$cuid,$list,$private,0]);
		tlxf::saventf1($usr,ses('usr'),4);}
	return tlex::followbt($p);}
elseif($block){
	$id=sql('id','tlex_ab','v',['usr'=>$uid,'ab'=>$cuid]);
	if($block==2)sql::upd('tlex_ab',['block'=>0],$id);
	elseif($id)sql::upd('tlex_ab',['block'=>1],$id);
	else sql::sav('tlex_ab',[$uid,$cuid,'','',1]);
	return tlex::followbt($p);}
elseif($refus){$apr=idusr($apr);
	qr('delete from tlex_ab where usr="'.$refus.'" and ab="'.$uid.'"');
	return self::subscriptions(['usr'=>$uid]);}
elseif($apr=val($p,'approve')){$apr=idusr($apr);
	qr('update tlex_ab set wait=0 where usr="'.$apr.'" and ab="'.$uid.'"');
	tlxf::saventf1($apr,ses('usr'),6);
	return self::subscribers(['usr'=>ses('usr')]);}
elseif($unf){sql::del('tlex_ab',$unf);//unfollow
	$ntf=sql('id','tlex_ntf','v',['4usr'=>$usr,'typntf'=>4]);
	sql::del('tlex_ntf',$ntf); return tlex::followbt($p);}
elseif($lst){$bt='';//display
	$r=sql('distinct(list)','tlex_ab','k',['usr'=>$uid,'block'=>0]);
	$ra=sql('list,block','tlex_ab','rw',['usr'=>$uid,'ab'=>$cuid]); if(!$ra)$ra=[0,0];
	$r=merge($r,['mainstream'=>1,'local'=>1,'global'=>1,'passion'=>1,'extra'=>1]);
	//$ret=div(lang('subscribe_list'),'btit');
	$ret=input('subslist',lang('new group'),18,1).' ';
	$ret.=bj($rid.'|friends,follow|usr='.$usr.',rid='.$rid.'|subslist',lang('ok',1),'btsav');
	foreach($r as $k=>$v){$c=active($k,$ra[0]);
		$bt.=bj($rid.'|friends,follow|usr='.$usr.',rid='.$rid.',follow='.$k,$k,$c);}
	if($ra[1])$bt.=bj($rid.'|friends,follow|usr='.$usr.',rid='.$rid.',block=2',lang('blocked'),'active');
	else $bt.=bj($rid.'|friends,follow|usr='.$usr.',rid='.$rid.',block=1',lang('block'),'del');
	return div($ret.div($bt,'list'),'pane','','');}}

#render
static function render($r,$rc=[],$t=''){
$n=count($r); $ret='';
$tit=div($n.' '.langs($t,$n),'btit');
if($r)foreach($r as $k=>$v){
	//if(isset($rc[$k]))$wait=1; else $wait=0;
	$ret.=profile::standard(['uid'=>$k]);}
return $tit.div($ret,'board');}

#connexions
static function subscriptions($p){
$usr=$p['usr']; $cuid=idusr($usr); $ret='';
$r=sql('ab','tlex_ab','k',['usr'=>$cuid,'_order'=>'up desc']);
[$r1,$r2,$r3]=self::build($p); $r=$r1;
$rc=sql('ab','tlex_ab','k',['usr'=>$cuid,'wait'=>'1']);
return self::render($r,$rc,'subscription');}

static function subscribers($p){
$usr=$p['usr']; $cuid=idusr($usr);
$r=sql('usr','tlex_ab','k',['ab'=>$cuid,'_order'=>'up desc']);
[$r1,$r2,$r3]=self::build($p); $r=$r2;
$rc=sql('usr','tlex_ab','k',['ab'=>$cuid,'wait'=>'1']);
if($n=count($rc))foreach($rc as $k=>$v)unset($r[$k]);
$ret=tlxf::notifs($p);
if($rc)$ret.=self::render($rc,[],'pending subscriber');
$ret.=self::render($r,$rc,'subscriber');
return $ret;}

static function reciproques($p){
$usr=$p['usr']; $cuid=idusr($usr);
[$r1,$r2,$r3]=self::build($p);
return self::render($r3,[],'friend');}

static function build($p){
$usr=$p['usr']??ses('usr'); $uid=$p['uid']??ses('uid');
$r1=sql('ab','tlex_ab','k',['usr'=>$uid]);
$r2=sql('usr','tlex_ab','k',['ab'=>$uid]); $r3=[];
foreach($r1 as $k=>$v)if($r2[$k]??''){$r3[]=$k; unset($r1[$k]); unset($r2[$k]);}
$r3=array_flip($r3);
return [$r1,$r2,$r3];}

static function menu($p){
$rp=['data-prmtm'=>'no'];//stop continuous scrolling!
$usr=$p['usr']??ses('usr'); $uid=$p['uid']??ses('uid'); $c=''; $ret='';
$op=$p['op']??''; $rm=['reciproques','subscriptions','subscribers'];
foreach($rm as $k=>$v)$rc[]=$v==$op?1:0; $ko=1;
//if(!$uid)return help('must_be_loged');
//[$r1,$r2,$r3]=self::build($p);
//$n1=count($r1); $n2=count($r2); $n3=count($r3);
//$n4=sql('count(id)','tlex_ntf','v',['4usr'=>$usr,'state'=>1,'typntf'=>5]);
//$n5=sql('count(id)','tlex_ntf','v',['4usr'=>$usr,'state'=>1,'typntf'=>4]);
$bt=langph('friends');//.' '.span($n3?$n3:'','nbntf react')
$rt[]=toggle('cbck|friends,reciproques|usr='.$usr,$bt,$c,[],$rc[0],$ko).' ';
$bt=langph('subscriptions');//.' '.span($n1?$n1:'','nbntf react')
$rt[]=toggle('cbck|friends,subscriptions|usr='.$usr,$bt,$c,[],$rc[1],$ko).' ';
$role=sql('role','profile','v',['pusr'=>$usr]);
$bt=langph($role==3?'members':'subscribers');//.' '.span($n2,'nbntf react','tlxsub').($n5?' active':'')
$rt[]=toggle('cbck|friends,subscribers|usr='.$usr,$bt,$c,[],$rc[2],$ko);
//$rt[]=hidden('tlxabsnb',$n2);//.hidden('tlxsubnb',$n1)
//$rt[]=toggle('cbck|mob,showusrs|usr='.$usr,langpi('other accounts'),$c);
return implode(' ',$rt);}

/*static function menu0($p){
$rt[]=toggle('cbck|friends,call|',langp('friends'),'',[],1);
$rt[]=toggle('cbck|friends,call|',langp('followed'),'',[],1);
$rt[]=toggle('cbck|friends,call|',langp('followers'),'',[],1);
return implode(' ',$rt);}*/

static function call($p){$p['usr']=ses('usr');
return ['nav2'=>self::menu($p),'cbck'=>self::reciproques($p)];}

static function com($p){
//self::install();
return self::call($p);}

static function content($p){
//self::install();
return self::call($p);}

}
?>