<?php

class condorcet extends appx{
static $private=1;
static $a='condorcet';
static $db='condorcet';
static $db2='condorcet_r';
static $cb='vte';
static $cols=['tit','txt','com','day','pub'];
static $typs=['var','bvar','var','date','int'];
static $tags=1;
static $open=1;
static $mnt=5;
static $ra=[];//votes
static $rb=[];//results
static $rc=[];//reached
static $rm=[];//mentions
static $rf=[];//verbose

function __construct(){
$r=['a','db','db2','cb','cols'];
foreach($r as $v)parent::$$v=self::$$v;}

//install
static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sqlcreate(self::$db2,['bid'=>'int','uid'=>'int','choice'=>'int','val'=>'int'],1);}

static function admin($p){$p['o']='0'; return parent::admin($p);}
static function headers(){}

#edit
static function form($p){
return parent::form($p);}

static function edit($p){
$p['collect']=self::$db2;
return parent::edit($p);}

static function collect($p){
return parent::collect($p);}

#sav
static function modif($p){
return parent::modif($p);}

static function del($p){
$p['db2']=self::$db2;
return parent::del($p);}

static function save($p){return parent::save($p);}
static function create($p){return parent::create($p);}

#note
static function votants($p){$id=$p['id'];
$r=sqlin('distinct(uid),name',self::$db2,'login','uid','kv',['bid'=>$id]);
if($r)foreach($r as $k=>$v)$ret[]=profile::call(['usr'=>$v,'sz'=>'small']);
return div(implode('',$ret));}

static function redefine($id,$p){$rt=[]; $ka=$p['choice']; $va=$p['val']; $rt[$ka]=$va; $ex='';
$answers=sql('com',self::$db,'v',$id); $rb=explode('|',$answers); $nb=count($rb);
$rn=sql('choice,val',self::$db2,'kv',['bid'=>$id,'uid'=>ses('uid')]);
foreach($rn as $k=>$v)if($k!=$ka && $v==$va)$ex=$k;
if($ex){for($i=1;$i<=$nb;$i++)$ri[]=$i; $rd=array_diff($ri,$rn);
$rn[$ex]=current($rd); sqlup(self::$db2,'val',$rn[$ex],['choice'=>$ex]);}}
/*for($i=1;$i<=$nb;$i++){$note=$rn[$i]??''; $ex='';
	foreach($rt as $k=>$v)if($k!=$i && $v==$note)$ex=$k;
	if($note){if(!$ex)$rt[$i]=$note; else{
		for($ia=1;$ia<=$nb;$ia++)if(!in_array($ia,$rt))$rt[$i]=$ia;}}}
foreach($rt as $k=>$v)if($k!=$ka)sqlup(self::$db2,'val',$v,['choice'=>$k]);*/

static function note0($p){$id=$p['id']; $uid=ses('uid');
$w=['bid'=>$id,'uid'=>$uid,'choice'=>$p['choice']];
$idnote=sql('id',self::$db2,'v',$w,0);
if(!$idnote)$p['idnote']=sqlsav(self::$db2,[$id,$uid,$p['choice'],$p['val']]);
else sqlup(self::$db2,'val',$p['val'],$idnote);
if($uid)self::redefine($id,$p);
return self::build($p);}

static function pane0($rb,$closed,$rn,$nb,$id){$ret=[];
if(!ses('uid'))$com='popup|core,help|ref=loged_needed';
else $com='pb'.$id.'|condorcet,note|id='.$id;//note button
$ret['_'][]=''; for($i=1;$i<=self::$mnt;$i++)$ret['_'][]=$i;
for($i=1;$i<=$nb;$i++){
	$answer=ico('plus-square-o').' '.$rb[$i]??''; $rt=[$answer];
	$noted=count($rn)==$nb?1:0; //$noted=$closed;
	//if(auth(6))$noted=0;
	$notedcase=$rn[$i]??'';
	for($k=1;$k<=self::$mnt;$k++){
		$ico=$k==$notedcase?ico('square'):ico('square-o');
		if($closed)$rt[]=span($ico);
		else $rt[]=bj($com.',choice='.$i.',val='.$k,$ico);}
	$ret[]=$rt;}
return tabler($ret);}

//drag
static function note($p){$id=$p['id']; $rn=[]; $uid=ses('uid');
$rv=explode(';',$p['vals']??''); //pr($rv);
$r=sql('choice,val,id',self::$db2,'id',['bid'=>$id,'uid'=>$uid],0);
foreach($rv as $k=>$v){$va=substr($v,2); $ka=$k+1; [$old,$bid]=$r[$ka]??[0,0];
	if(!isset($r[$ka]))sqlsav(self::$db2,[$id,$uid,$ka,$va]);
	else sqlup(self::$db2,'val',$va,$bid,'',0);}
return self::build($p);}

static function pane($rb,$closed,$rn,$nb,$id){$ret=''; //pr($rn);
if(!ses('uid'))$com='popup|core,help|ref=loged_needed,vals=';
else $com='pb'.$id.'|condorcet,note|id='.$id.',vals=';//note button
for($i=1;$i<=$nb;$i++){$rni=$rn[$i]??$i;
	$ret.=dragline(ico('arrows-v').$rni.' '.$rb[$rni],'cd'.$rni,$com);}
return div($ret,'drags dlist');}

#lib
static function algo($r,$m=0.5,$a=3){
return judgment::algo($r,$m,$a);}

#render
static function results($rb,$rc){//pr($rc);
$ret=''; $n=0; $winner=key($rc); $sz=200; $mn=self::$mnt;//max width
$rt[0][0]=lang('candidate');
$rt[0][1]=lang('rank'); $rt[0][2]='';
for($i=1;$i<=$mn;$i++)//headers
	$rt[0][2].=span(str_pad('',$i,'*'),'ansprop score'.$i,'','width:'.($sz/$mn+16).'px;');
	$rt[0][2]=div($rt[0][2],'pllcnt');
if(isset($rc))foreach($rc as $k=>$v){$ka=$k;//+1;
	$stot=0; $sum=0; $n++;
	$rt[$ka][0]=$rb[$k]??$k;
	$rt[$ka][1]=$n; $rts='';
	for($i=1;$i<=$mn;$i++){$vi=$v[$i]??0; $nb=round($vi*100,2); //$nb=$r[$k][$i]??0;
		$rts.=span($nb,'ansprop score'.$i,'','width:'.($vi*$sz+16).'px;');}
	$rt[$ka][2]=div($rts,'pllcnt');}
$ret=tabler($rt,'','1');
if($winner)$ret.=self::winner($rb,$rc,$winner);
return $ret;}

static function winner($rb,$rc,$winner){$ka=key($rc);
$win=$rb[$ka]??''; $mnt=self::$mnt/2; $rx=[]; $rd=[];
$score=array_sum(array_slice($rc[$winner],$mnt)); $sc=round($score*100,2);
$rn=self::$rf['random']??'';
$status='('.lang('supporters').' = '.$sc.'%) ';
if($rn)foreach($rn as $k=>$v)foreach($v as $ka=>$va)$rd[$k][]=$rb[$ka]??'';
if($rd)foreach($rd as $k=>$v)$status.=langx('raffle between').': '.tag('b','',implode(' ',$v)).' ; ';
$t=lang('the winner is').' : '.tag('b','',$win).' '.langx('with mention',1).': '.$mnt.' '.$status;
return div($t,'valid');}

#read
static function cancel($p){
sqldel(self::$db2,['bid'=>$p['id'],'uid'=>ses('uid')]);
return self::build($p);}

static function votes($id){
return sql('choice,val',self::$db2,'kr',['bid'=>$id]);}

static function build($p){$id=$p['id']; $ret='';
list($answers,$end)=sql('com,day',self::$db,'rw',$id);
if(ses('uid'))$rn=sql('choice,val',self::$db2,'kv',['bid'=>$id,'uid'=>ses('uid')]); else $rn=[];
$rs=sql('distinct(uid)',self::$db2,'k',['bid'=>$id,'_order'=>'val']);
$rb=explode('|',$answers); $nb=count($rb); array_unshift($rb,'null'); $sum=array_sum($rs);
$endtime=strtotime($end); $leftime=ses('time')-$endtime;
if($leftime>0)$closed=1; else $closed=0; if($p['cl']??'')$closed=1;
$r=self::votes($id);//[choice][mention]=nb
if($r)$rc=self::algo($r);//[choice][mention]=percent
if($closed)$ret=self::results($rb,$rc);
else $ret=self::pane($rb,$closed,$rn,$nb,$id);
$ret.=br().bj('popup|condorcet,votants|id='.$id,langnbp('noter',$sum),'tot').' ';//footer
if($closed)$state=langp('vote closed').' '.lang('the',1).' '.$end;
else $state=langp('time left').' : '.build::leftime($endtime);
$ret.=span($state,'tot').' ';
if(ses('uid') && $rn && !$closed)$ret.=bj('pb'.$id.'|condorcet,cancel|id='.$id,langp('cancel_vote'),'btdel');
if(!$closed)$ret.=bj('pb'.$id.'|condorcet,build|cl=1,id='.$id,langp('see_results'),'btok');
elseif($leftime<0)$ret.=bj('pb'.$id.'|condorcet,build|id='.$id,langp('back'),'btn');
return $ret;}

#stream
static function play($p){$bt=''; $go='';
$id=$p['id']; $rid=$p['rid']??''; $tid=randid('txt');
$cols=self::$db.'.id,name,txt,com,day';
$where='where '.self::$db.'.id='.$id.' order by '.self::$db.'.id desc';
$r=sqlin($cols,self::$db,'login','uid','ra',$where);
if(!$r)return help('id not exists','board');
//admin
if($rid){$go=bj('blcbk|condorcet,stream|rid='.$rid,'#'.$id,'btn');
	$go.=tlxf::publishbt(lang('use'),$id.':condorcet',$rid);}
$go.=lk('/condorcet/'.$id,ico('link'),'btn').' ';
$by=small($r['day'].' '.lang('by').' '.$r['name']).' ';
//$bt=div($bt,'right');
$txt=div(nl2br(voc($r['txt'],self::$db.'-txt-'.$id)),'txt',$tid);
//results
$results=div(self::build($p),'','pb'.$id);
//render
$ret=$txt.$results;//$go.' '.$by.$bt.br().
return div($ret,'','pol'.$id);}

static function stream($p){
return parent::stream($p);}

#interface
static function tit($p){
return parent::tit($p);}

static function call($p){
return parent::call($p);}

static function com($p){
return parent::com($p);}

static function api($p){
return parent::api($p);}

#content
static function content($p){
self::install();
//rntable('vote','condorcet');
//rntable('vote_note',self::$db2);
return parent::content($p);}
}

?>