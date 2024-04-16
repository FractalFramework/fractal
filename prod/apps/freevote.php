<?php

class freevote extends appx{
static $private=0;
static $a='freevote';
static $db='freevote';
static $cb='dcwrp';
static $cols=['tit','txt','cl','pub'];
static $typs=['var','bvar','int','int'];
static $db2='freevote_args';
static $open=0;
static $tags=1;
static $qb='db';

//install
static function install($p=''){
sql::create('freevote_args',['bid'=>'int','uid'=>'int','txb'=>'var'],1);
sql::create('freevote_valid',['cid'=>'int','uid'=>'int','val'=>'int'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1'; return parent::admin($p);}
static function headers(){head::add('csscode','');}

#sys
static function del($p){return parent::del($p);}
static function modif($p){return parent::modif($p);}
static function save($p){return parent::save($p);}
static function create($p){return parent::create($p);}

#editor
static function form($p){return parent::form($p);}
static function edit($p){$p['collect']=self::$db2; return parent::edit($p);}

//generics
private static function security($table,$id){
$uid=sql('uid',$table,'v',$id);
if($uid==ses('uid'))return 1;}

//algo
static function algo($r){$ret=[];
$nb_props=count($r); $tot_votes=0; $rt=[]; $rs=[]; $rq=[]; $rw=[];
foreach($r as $k=>$rb){$nb_votes=count($rb); $tot_votes+=$nb_votes; $agree=0; $disagree=0;
	foreach($rb as $kb=>$vb){
		$agree+=$vb==1?1:0; $disagree+=$vb==2?1:0;}
	$rt[$k]=$agree; $rs[$k]=$disagree; $rq[$k]=$tot_votes; $rw[$k]=round(($agree-$disagree)/$tot_votes,2);}
asort($rt); asort($rs); asort($rq); asort($rw); //pr($rt);
//score by mass
$i=0; if($rt)foreach($rt as $k=>$v){$i++; $rt_score[$k]=$i;} //pr($rt_score);
//score by ratio
$i=0; if($rw)foreach($rw as $k=>$v){$i++; $rw_score[$k]=$i;} //pr($rw_score);
if($rt_score)foreach($rt_score as $k=>$v)$score[$k]=$v+$rw_score[$k]; //pr($score);
if($score)arsort($score); //pr($score);
if($score)foreach($score as $k=>$v)$ret[$k]=[$v,$rs[$k],$rq[$k],$rw[$k]*100]; //pr($ret);
//if($ret)$ret=array_slice($ret,0,5); //pr($ret); /kill keys
return ['nb'=>$nb_props,'tot'=>$tot_votes,'res'=>$ret];}

static function argcalc($p){$id=$p['id']; $ret='';
$ra=sql('id','freevote_args','rv','where bid='.$id); //pr($ra);
if($ra)foreach($ra as $k=>$v)
	$rb[$v]=sql('val','freevote_valid','rv','where val>0 and cid='.$v); //pr($rb);
if($rb)$res=self::algo($rb); //pr($res);
//$help=' '.hlpbt('freevote_algo');
if(isset($res['res']))foreach($res['res'] as $k=>$v){$p['bid']=$k; $p['score']=$v[3];
	$ret.=self::panearg($p);}
//$css=isset($res['global'])?'resyes':'resno';
if(isset($res))$bt=div($res['nb'].' '.lang('propositions').' - '.$res['tot'].' '.lang('votes'),'nfo');
if($ret)return div($bt.' '.$ret);}

#argument
static function delarg($p){$bid=$p['bid']; $cc=self::$cb.$p['id'];
if(!self::security('freevote_args',$bid))return;
if(!val($p,'ok'))return bj($cc.'|freevote,delarg|ok=1,'.prm($p),langp('really?'),'btdel');//.bj($cc.'|freevote,arguments|'.prm($p),langp('cancel'),'btn')
sql::del('freevote_args',$bid);
sql::del('freevote_valid',$bid,'cid');
return self::play($p);}

static function savearg($p){
$r=[$p['id'],ses('uid'),$p['addarg']];//p($r);
$p['cid']=sql::sav('freevote_args',$r);
return self::play($p);}

static function argumentedit($p){$id=$p['id'];
$ret=textarea('addarg','',40,4,lang('add proposition')).br();
$ret.=bj(self::$cb.$id.'|freevote,savearg|id='.$id.'|addarg',langp('save'),'btsav');
$ret.=bj(self::$cb.$id.'|freevote,play|id='.$id.'|addarg',langp('cancel'),'btn');
return $ret;}

#vote
static function savevote($p){$bid=$p['bid'];
$id=sql('id','freevote_valid','v','where cid="'.$bid.'" and uid="'.ses('uid').'"');
if(!$id)sql::sav('freevote_valid',[$bid,ses('uid'),$p['val']]);
elseif($id){$v=$p['val']==$p['current']?0:$p['val']; sql::up('freevote_valid','val',$v,$id);}
return self::vote($p);}

static function vote($p){
$id=$p['id']??''; $bid=$p['bid']??''; $cl=$p['cl']??''; $nb=$p['nbvotes']??''; $kid=randid('freevote');
[$tot,$median,$medp,$bmed]=vals($p,['tot','median','medp','bmed']); $n='';
$vote=sql('val','freevote_valid','v','where cid="'.$bid.'" and uid="'.ses('uid').'"');
if(!$median){$r=sql('val','freevote_valid','rv','where cid="'.$bid.'" and val>0');
	$tot=count($r); $score=array_sum($r); $median=$tot?$score/$tot:0;}
//echo $bid.' - '.$median.' - '.$medp.br();//
$ret=span($tot,'nfo');
$j=$kid.'|freevote,savevote|id='.$id.',bid='.$bid.',current='.$vote;
for($i=1;$i<=5;$i++){$c=$vote==$i?'voted':'';
	$ico=$i<=$median?ico('star'):ico('star-o');
	if(ses('uid') && !$cl)$ret.=bj($j.',val='.$i,$ico,$c);
	else $ret.=$ico.($n?' ('.$n.')':'');}
return div($ret,'',$kid);}

static function panearg($p){$ret=''; $bt='';
$id=$p['id']??''; $bid=$p['bid']??''; $cl=$p['cl']??''; //$tot=$p['tot']??'';
if(!isset($id) && $bid)$id=sql('bid','freevote_args','v',$bid);
$cols='name,txb,freevote_args.up as date,count(freevote_valid.id) as nb';
$w='left join freevote_valid on freevote_valid.cid=freevote_args.id where freevote_args.id='.$bid;
$r=sql::inner($cols,'freevote_args','login','uid','ra',$w.' order by nb desc');
//$bt=self::usrnfo($r['name'],$r['date']);
$bt=bubble('profile,call|usr='.$r['name'].',sz=small','#'.$bid,'small',1);
if($r['name']==ses('usr') && !$cl)
	$bt.=bubble('freevote,delarg|bid='.$bid.',id='.$id,pic('delete'),'small');
$txb=$r['txb']; //$txb=bj('page|decide|assoc='.$txb,$txb);
$ret.=div($bt,'argcell').div($txb,'argcell');//.div($tot,'argcell')
if(!$cl)$ret.=div(self::vote($p),'argcell','','white-space:nowrap;');//vote
else $ret.=span($p['score'].'%','btn argcell');//($p['score']>0?'resyes':'resno')
return div($ret,'argrow');}

//read
static function ordered($r,$p){
$id=$p['id']; $na=count($r);
foreach($r as $k=>$v){$r1=0; $r2=0;
	$rb=sql('val','freevote_valid','rv','where cid="'.$v.'" and val>0');
	$tot=count($rb); $score=array_sum($rb); $median=$tot?$score/$tot:0;
	$rc[$v]=$median; $rt[$v]=$tot; $rs[$v]=$score;}
$btot=array_sum($rt); $bscore=array_sum($rs); $bmed=$btot?$bscore/$btot:0;//ponderation//!
foreach($rc as $k=>$v)$rp[$k]=$v*($rt[$k]/$btot*$na);
if(val($p,'ord'))arsort($rc);//order by score
foreach($rc as $k=>$v)$rd[$k]=['bid'=>$k,'tot'=>$rt[$k],'median'=>$v,'medp'=>$rp[$k],'bmed'=>$bmed,'na'=>$na];
return $rd;}

static function arguments($p){$id=$p['id']; $ret='';
unset($p['_pw']); unset($p['_a']); unset($p['_m']);
$r=sql('id','freevote_args','rv','where bid='.$id.' order by id');
$r=self::ordered($r,$p);
if($r)foreach($r as $k=>$v)$ret.=self::panearg(array_merge($p,$v));
return div($ret,'argtable');}

#stream
static function play($p){
$id=$p['id']??''; $p['cid']=val($p,'o'); $add=''; if(!$id)return;
$cols='name,txt,cl,'.self::$db.'.up as date';
$where='where '.self::$db.'.id='.$id;
$r=sql::inner($cols,self::$db,'login','uid','ra',$where);
$p['cl']=$r['cl'];
$txt=div(nl2br($r['txt']),'tit');
if($r['cl']==1)return $txt.self::argcalc($p).help('form closed','alert');
$by=self::usrnfo($r['name'],$r['date']);
//$by=bubble('profile,call|usr='.$r['name'].',sz=small',$r['name'],'grey small',1);
//$bt=div(lk('/freevote/'.$id,ico('link'),'btn'),'right');
$n=sql('count(id)','freevote_args','v','where bid="'.$id.'"');
$nv=sql::join('count(freevote_valid.id)','freevote_valid','freevote_args','cid','v','where bid="'.$id.'"');
$p['nbvotes']=$n;
$ret=div($by.$txt,'');//$bt.br().
$ret.=bj('arg'.$id.'|freevote,arguments|id='.$id,langnb('proposition',$n),'nfo');
$ret.=langnb('vote',$nv,'nfo');
$ret.=bj('arg'.$id.'|freevote,arguments|ord=1,id='.$id,langp('ordered'),'btn');
$ret.=hlpbt('freevote_algo');
if(!$r['cl'])$ret.=bjlog('arg'.$id.'|freevote,argumentedit|id='.$id,langp('add proposition'),'btok');
//if(!ses('uid'))$ret.=popup('login,com',langp('login'),'btn');
$ret.=div(self::arguments($p),'','arg'.$id);
return $ret;}

static function stream($p){
$p['privacy']='0';
return parent::stream($p);}

#interfaces
static function tit($p){
$p['t']='tit';
return parent::tit($p);}

//call (read)
static function call($p){
return parent::call($p);}

//com (edit)
static function com($p){
return parent::com($p);}

//interface
static function content($p){
//self::install();
return parent::content($p);}
}

?>