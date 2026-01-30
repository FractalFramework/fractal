<?php

class judgment extends appx{
static $private=1;
static $a='judgment';
static $db='judgment';
static $db2='judgment_r';
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

//install
static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create(self::$db2,['bid'=>'int','uid'=>'int','choice'=>'int','val'=>'int'],1);}

static function admin($p){$p['o']='0'; return parent::admin($p);}
static function headers(){head::add('csscode','');}

#edit
static function form($p){
return parent::form($p);}

static function edit($p){
$p['collect']=self::$db2;
return parent::edit($p);}

static function collect($p){
$p['bt']=bj('popup|judgment,editcsv|'.prm($p),langpi('edit'),'btsav');
$p['bt'].=bj('popup|judgment,editpaq|'.prm($p),langpi('import'),'btsav');
return parent::collect($p);}

#sav
static function modif($p){
return parent::modif($p);}

static function del($p){
$p['db2']=self::$db2;
return parent::del($p);}

static function save($p){return parent::save($p);}
static function create($p){return parent::create($p);}

#redit
static function savcsv($p){
$id=$p['id']??''; $d=$p['datas']??''; $b=self::$db2;
$r=explode_r($d,"\n",','); $com=array_shift($r);
<<<<<<< HEAD
if(auth(6))sql::upd(self::$db,['com'=>implode('|',$com)],$id);
=======
if(auth(6))sql::up(self::$db,'com',implode('|',$com),$id);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
$cl=array_keys(sql::cols($b,3,0)); if(auth(6))sql::del($b,$id,'bid');
if(auth(6))foreach($r as $k=>$v){array_unshift($v,$id,ses('uid'));sql::sav($b,$v);}
return self::collect($p);}

static function editcsv($p){
$id=$p['id']??''; $cb=$p['cb']??''; $b=self::$db2;
$h=sql('com',self::$db,'v',$id); $rh=explode('|',$h);
$r=sql('choice,val',$b,'',['bid'=>$id]);
array_unshift($r,$rh);
$d=implode_r($r,"\n",',');
$ret=bj($cb.'|judgment,savcsv|id='.$id.',db='.$p['db'].',cb='.$cb.'|datas',langp('save'),'btsav');
$ret.=div(textarea('datas',$d,84,24,'','console'));
return $ret;}

static function paqsav($p){
$id=$p['id']??''; $d=$p['datas']??''; $b=self::$db2;
$d=str::clean_separator($d,';',"\n"); $r=explode_r($d,';',','); $com=array_shift($r);
<<<<<<< HEAD
if(auth(6))sql::upd(self::$db,['com'=>implode('|',$com)],$id);
=======
if(auth(6))sql::up(self::$db,'com',implode('|',$com),$id);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
if(auth(6))sql::del($b,$id,'bid');
$rb=referendum::onpaq($r); $rc=[];
foreach($rb as $k=>$v)foreach($v as $ka=>$va)$rc[]=[$id,$ka+1,$k,$va];
if($rc && auth(6))sql::sav2($b,$rc);
return self::collect($p);}

static function editpaq($p){$d='';
$id=$p['id']??''; $cb=$p['cb']??''; $b=self::$db2;
$h=sql('com',self::$db,'v',$id); $rh=explode('|',$h); $d=join(',',$rh).n();
$r=sql('choice,val',$b,'kr',['bid'=>$id,'_order'=>'choice']);
$d=referendum::buildatas($r,$rh);
$ret=bj($cb.'|judgment,paqsav|id='.$id.',db='.$p['db'].',cb='.$cb.'|datas',langp('save'),'btsav');
$ret.=div(textarea('datas',$d,84,24,'','console'));
return $ret;}

#note
static function votants($p){$id=$p['id']; $rt=[];
<<<<<<< HEAD
$r=sql::inner('distinct(uid),name',self::$db2,'login','uid','kv',['bid'=>$id]);
=======
$r=sql::inner('distinct(uid),name',self::$db2,'login','uid','kv','where bid="'.$id.'"');
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
if($r)foreach($r as $k=>$v)$rt[]=profile::call(['usr'=>$v,'sz'=>'small']);
return div(implode('',$rt));}

static function note($p){$id=$p['id'];
$w=['bid'=>$id,'uid'=>ses('uid'),'choice'=>$p['choice']];
$idnote=sql('id',self::$db2,'v',$w,0);
if(!$idnote)$p['idnote']=sql::sav(self::$db2,[$id,ses('uid'),$p['choice'],$p['val']]);
<<<<<<< HEAD
else sql::upd(self::$db2,['val'=>$p['val']],$idnote);
=======
else sql::up(self::$db2,'val',$p['val'],$idnote);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
return self::build($p);}

#lib
static function average($r){
return round(array_sum($r)/count($r));}

static function middle($r){sort($r);
$n=count($r); $n2=ceil($n/2); return $r[$n2];}

/*static function mediane($r){asort($r); $ns=0; $kb=0;
$n=count($r); $sum=array_sum($r); $n2=ceil($sum/2);//middle value
foreach($r as $k=>$v){$ns+=$v; if($ns<$n2)$kb=$k;}//search range
return $r[$kb];}*/

static function reached($r,$m=3){$ns=0; $kb=1;
foreach($r as $k=>$v){$ns+=$v; if($k==$m)return $ns;}//reached score at range
return $kb;}

static function duplicates($r){$rb=self::countr($r); //$rb=array_count_values($r);
foreach($rb as $k=>$v)if($v>1)return true;}

static function solution($rm){$rb=[]; $rt=[];
foreach($rm as $k=>$v)$rb[(string)$v][]=$k; $i=0;
foreach($rb as $k=>$v)if(count($v)>1)$rt[]=$v; else $rt[]=current($v);
return $rt;}

/*static function scores2($rt,$m,$n){
$mn=self::$mnt; $rt=[];
while($mn>0){$min=0; $max=0;
	foreach($rt as $k=>$v){$ka=$m-$n; $kb=$m+$n;
		if($ka>0 && $kb<=$mnt){
		if($k<=$ka)$min+=$v; if($k>=$kb)$max+=$v;}
		$rt[]=[$min,$max]; $n++;}}
return $rt;}*/

static function maxk($r){$mv=0; $mk=0;
//foreach($r as $k=>$v)if($v>$mv){$mv=$v; $mk=$k;}
$mv=max($r); $mk=array_search($mv,$r);
return [$mk,$mv];}

static function isuniq($r,$d){$n=0;
foreach($r as $k=>$v)if($v==$d)$n++; return $n;}

/*static function pushr($r,$rb){//array_merge without collision of keys (lib)
foreach($rb as $k=>$v)$r[]=$v; return $r;}*/

static function randr($r){$rt=[];
while($r){$k=array_rand($r); $rt[]=$r[$k]; unset($r[$k]);}
return $rt;}

static function countr($r){$rb=[];
foreach($r as $k=>$v)$rb[(string)$v][$k]=1;
return $rb;}

static function rank($r){$rt=[];
foreach($r as $k=>$v)if(is_array($v))$rt=pushr($rt,self::rank($v)); else $rt[]=$v;
return $rt;}

//method6x: average order (Condorcet)
static function best6x($rb,$rm,$rt,$m,$n,$o,$u=0){$rc=[]; $rd=[]; $mn=self::$mnt;
for($i=1;$i<=$mn;$i++){$rd[$i]=array_column($rt,$i); if($i<$mn/2)asort($rd[$i]); else arsort($rd[$i]);} //pr($rc);
//foreach($rb as $k=>$v)$rc[$v]=array_sum($rt[$k]);
self::$rf['process'][]['tie-break'.$n.'.'.$o.'.'.$u]=['rc'=>$rc];
return $rm;}

//method6: chips
static function averages($r){$rc=[]; $rm=[];
foreach($r as $k=>$v){$m=self::majoritary($k,$v,0.25); $rc[$k][0]=self::$rc[$k];}//*$m
foreach($r as $k=>$v){$m=self::majoritary($k,$v,0.75); $rc[$k][1]=1-self::$rc[$k];}//*$m
foreach($rc as $k=>$v)$rm[$k]=array_sum($v)/count($v); //pr($rc);
return $rm;}

static function best6($rb,$rm,$rt,$m,$n,$o,$u=0){$rc=[]; //$rt=self::$ra;
foreach($rb as $k=>$v){$na=0;
	if($u==0)foreach($rt[$v] as $ka=>$va)$na+=$va**$va;//bad quality for rt//1/3|2/3|0<2/3|0|1/3 (bad)
	if($u==1)foreach($rt[$v] as $ka=>$va)$na+=$va-self::$mnt/2;
	$rc[$v]=$na;}
self::$rf['process'][]['tie-break'.$n.'.'.$o.'.'.$u]=['rc'=>$rc];
//$rcb=self::countr($rc);
//if(count($rcb)==1 && $u==0)$rc=self::best6($rb,$rm,$rt,$m,$n,$o,1);
return $rc;}

//method5: reached percent //4b
static function best5($rb,$rm,$rt,$m,$n,$o,$u=0){
foreach($rb as $k=>$v)$rc[$v]=self::$rc[$v];
self::$rf['process'][]['tie-break'.$n.'.'.$o.'.'.$u]=['rc'=>$rc];
return $rc;}

//method4a: floating median
static function best9($rb,$rm,$rt,$m,$n,$o,$u=1){$rc=[]; $mb=$u/100; if($u%2==0)$mb*=-1; $mb+=$m;
foreach($rb as $k=>$v)$rc[$v]=self::majoritary($v,$rt[$v],$mb);
$rcb=array_count_values($rc); self::$rf['process'][]['tie-break'.$n.'.'.$o.'.'.$u]=['rc'=>$rc];
if(count($rcb)==1 && ($mb>0 && $mb<1))$rc=self::best9($rb,$rm,$rt,$mb,$n,$o,$u+1);
return $rc;}

//method4b: floating median with rule of groups of dissatisfied
static $ma=100;//% around middle
static function best4($rb,$rm,$rt,$m,$n,$o,$u=1){if(!$m)$m=0.5;
$rc=[]; $ma=self::$ma; $mb=$u/$ma; if($u%2==0)$mb*=-1; $mb+=$m;
foreach($rb as $k=>$v)$rc[$v]=self::majoritary($v,$rt[$v],$mb);
$rcb=array_count_values($rc); self::$rf['process'][]['tie-break'.$n.'.'.$o.'.'.$u]=['rc'=>$rc];
if(count($rcb)==1){
	if($mb>0 && $mb<1)$rc=self::best4($rb,$rm,$rt,$m,$n,$o,$u+1);
	elseif($ma<100){self::$ma*=10; self::$rf['process'][]['steps']=self::$ma.'-'.$mb;
		$rc=self::best4($rb,$rm,$rt,$m,$n,$o,1);}
	else self::$rf['process'][]='max limit of 100 iterations of tie-break 2 is reached';}
else self::$ma=100;
return $rc;}

//method3: usual (evaluation)
static function best3($rb,$rm,$rt,$m,$n,$o,$u=0){$rc=[];
$mnt=self::$mnt; $end=($mnt-$mnt%2)/2;
foreach($rb as $k=>$v){$me=$rm[$v]; $ca=$rt[$v][$me];
	[$cq,$cp]=self::scores($rt[$v],$me,$u); //pr($rt[$v]);
	$rc[$v]=($cp-$cq)/$ca/2;}//((($cp-$cq)/(1-$cp-$cq))/2) //pr($rc);
self::$rf['process'][]['tie-break'.$n.'.'.$o.'.'.$u]=['rc'=>$rc];
$rcb=self::countr($rc);
if(count($rcb)==1 && $u<=$end)$rd=self::best3($rb,$rm,$rt,$m,$n,$o,$u+1);
return $rc;}

//method2: iterate mentions
static function scores($rt,$m,$n){$min=0; $max=0;
foreach($rt as $k=>$v){if($k<$m-$n)$min+=$v; if($k>$m+$n)$max+=$v;}
return [$min,$max];}

static function best2($rb,$rm,$rt,$m,$n,$o,$u=0){
$winner=''; $looser=''; $rd1=[]; $rd=[]; $rd2=[]; $exq=0; $mnt=self::$mnt; $end=($mnt-$mnt%2)/2;
foreach($rb as $k=>$v)$rc[$v]=self::scores($rt[$v],$rm[$v],$u);//[0=>opponents,1=>supporters]
foreach($rc as $k=>$v){$rd1[$k]=$v[0]; $rd2[$k]=$v[1];} arsort($rd1); arsort($rd2);
[$k1,$v1]=self::maxk($rd1); [$k2,$v2]=self::maxk($rd2);
$isu1=self::isuniq($rd1,$v1); $isu2=self::isuniq($rd2,$v2);//if best result is single - not significant if not unique
if($v1<$v2 && $isu2==1)$winner=$k2; elseif($v1>$v2 && $isu1==1)$looser=$k1; else $exq=1;
if($exq && $isu1==1 && $u==$end){$looser=$k1; $exq=0;}//if end of loops, reason is for exaequos loosers
//in case of exaequo winner or looser in a range rd1 or rd2, the script will iterate to determine an unique solution and let the others candidates to be tied in an other serie of iterations, starting from n=0
if($winner){unset($rd2[$k2]); $rd0=$rd2;} elseif($looser){unset($rd1[$k1]); $rd0=$rd1;} else $rd0=$rd1;
if($winner)$rd[$k2]=20-$u;//we reorder the solutions for resolve 
if($exq && $u<=$end)$rd=self::best2($rb,$rm,$rt,$m,$n,$o,$u+1);
else foreach($rd0 as $k=>$v)$rd[$k]=$u+1;
if($looser)$rd[$k1]=$u;
if($winner)$d='winner: '.$k2; elseif($looser)$d='looser: '.$k1; else $d='ex aequo';
self::$rf['process'][]['tie-break'.$n.'.'.$o.'.'.$u]=[$d,'opponents/supporters'=>$rc,'given notes'=>$rd];
return $rd;}

//method1: iterate votes
static function best1($rb,$rm,$rt,$m,$n,$o){$rq=[]; $rc=[]; $na=0;
foreach($rb as $k=>$v){$rq[$v]=$rt[$v]; $rc[$v]=$rm[$v]; $nt=count($rq[$v]);}//select
while(count(array_count_values($rc))==1 && $rq && $na<$nt){$na++;
	foreach($rq as $k=>$v){
		$k1=array_search($rm[$k],$v); unset($rq[$k][$k1]);//del first voter at mention
		$rc[$k]=self::majoritary($k,$rq[$k],$m); $rm=$rc;}}
self::$rf['process'][]['tie-break'.$n.'.'.$o]=['iterations'=>$na,'rc'=>$rc];
if($na>=1000)self::$rf['process'][]='max limit of 1000 iterations is reached';
return $rc;}

#generic iteration
static function resolve($a,$rb,$rm,$rt,$m,$n,$o=0){
$fc='best'.$a; if(method_exists('judgment',$fc))$rc=self::$fc($rb,$rm,$rt,$m,$n,$o);
elseif(method_exists('referendum',$fc))$rc=referendum::$fc($rb,$rm,$rt,$m,$n,$o);
arsort($rc); $rcb=self::countr($rc); $rk=[];//$rcb=array_count_values($rc);
if(count($rcb)==1){$rk=self::randr(array_keys($rc)); self::$rf['random'][]=$rc; $o='random'; $rc=[];}
if($rc && $n<100){$rb=self::solution($rc); $rk=self::resolution($a,$rb,$rm,$rt,$m,$n+1);}
self::$rf['process'][]['resolve'.$n.'.'.$o]=['rk'=>$rk];
if($n>=10)self::$rf['process'][]='max limit of 100 iterations of resolve is reached';
return $rk;}

//groups of equalities
static function resolution($a,$rb,$rm,$rt,$m,$n=0){$rd=[];
foreach($rb as $k=>$v){//order[][candidate]
	if(is_array($v))$rd[]=self::resolve($a,$v,$rm,$rt,$m,$n);//for each exeaquo
	else $rd[]=$v;}
self::$rf['process'][]['resolution'.$n]=['rd'=>$rd];
return $rd;}

/*
$r=[voter][candidate]vote
$rt=[candidate][mention]percent
$rb=[order]candidate
$rc=[candidate]score
$rd=[order]candidate ($rk[]=$rd)
$rk=[order][][candidate]
*/

#exeaquo
static function exaequo($rm,$rt,$a=2,$m=0.5){//$a=2;
arsort($rm); if($a==1 or $a==4 or $a==8 or $a==9)$rt=self::$ra; chrono(); //pr($rt);
self::$rf['nomenclature']=['r'=>'[voter][candidate]vote','rt'=>'[candidate][mention]percent','rb'=>'[order]candidate','rc'=>'[candidate]score','rd'=>'[order]candidate ($rk[]=$rd)','rk'=>'[order][][candidate]','sequence'=>'solution, resolution, resolve, tie-break (following method), ranks.'];
self::$rf['exaequo']=['method'=>$a,'mentions'=>$rm,'reached'=>self::$rc];
if(!self::duplicates($rm))return array_keys($rm);
$rb=self::solution($rm);//order[][candidate]
self::$rf['solution']=$rb;
if($a==7)$rd=referendum::resolution2($rb,$rm,$rt,$m);//insatisfaits
else $rd=self::resolution($a,$rb,$rm,$rt,$m);//all methods
self::$rf['final']=$rd;
$rk=self::rank($rd);//order[candidate]
self::$rf['ranks']=$rk;
self::$rf['chrono']=chrono('process');
return $rk;}

//equalize non-votes
static function normalize($r){$rt=[]; $rx=[]; $n=0;//candidate[mention]
foreach($r as $k=>$v)if(($mx=count($v))>$n)$n=$mx;//max nb of voters
foreach($r as $k=>$v){$na=count($v); $rx[$k]=0;
	if($na<$n)for($i=0;$i<$n-$na;$i++){$v[]=1; $rx[$k]+=1;}//missing votes to 1
	$rt[$k]=$v;}
if($rx)self::$rf['normalize votes']=$rx;
return $rt;}

static function mention($c,$r,$m=0.5){$ns=0; $kb=1; $nc=0;
foreach($r as $k=>$v){$ns+=$v; if($ns<=$m){$kb=$k+1; $nc=$ns;}}//search range
self::$rc[$c]=$nc;
return $kb;}

static function majoritary($c,$r,$m=0.5){
$n=count($r); $rs=[]; $rb=[];//$r:candidate[note][nb]
foreach($r as $k=>$v)$rs[$v][]=1;//nb of each notes
foreach($rs as $k=>$v)$rb[$k]=count($v)/$n;//ratio
for($i=1;$i<=self::$mnt;$i++)if(!isset($rb[$i]))$rb[$i]=0;//zero votes for unused mentions
ksort($rb); self::$rb=$rb;//usefull one time
$ret=self::mention($c,$rb,$m);//reached mention at 50%
return $ret;}

//$rm=mentions, $rk=ranks
static function algo($r,$m=0.5,$a=3){
$rm=[]; $rt=[]; $rx=[]; //pr($r);
$r=self::normalize($r);//equalizer
self::$ra=$r; //pr($r);
$n=1; foreach($r as $k=>$v){$na=max($v); if($na>$n)$n=$na;} self::$mnt=$n;//nb of mentions
foreach($r as $k=>$v){
	$rm[$k]=self::majoritary($k,$v,$m);
	//if($a==5)$rm[$k]=1-self::$rc[$k];
	$rt[$k]=self::$rb;} //pr($rm);
if($a==6)$rm=self::averages($r);
self::$rm=$rm;//canddate[mention]
$rk=self::exaequo($rm,$rt,$a,$m); //pr($rk);
foreach($rk as $k=>$v)$rx[$v]=$rt[$v];//ranks
return $rx;}

#render
static function winner($rb,$rc,$ka,$a,$rid,$id){
$win=$rb[$ka]??''; $mnt=self::$mnt; $ra=self::$ra; $rx=[]; $rd=[]; $ret=''; $score=0;
if($rc[$ka]??'')$score=array_sum(array_slice($rc[$ka],round($mnt/2))); $sc=round($score*100,2);
$rn=self::$rf['random']??''; $status=lang('supporters').': '.tag('b','',$sc.'%').' ';
if($rn)foreach($rn as $k=>$v)foreach($v as $ka=>$va)$rd[$k][]=$rb[$ka]??'';
if($rd)foreach($rd as $k=>$v)$status.=langx('raffle between').': '.tag('b','',implode(' | ',$v)).' ; ';
//$rp=['cod','ex','pr','inp3','inpc','inpm','inp2','codb','inp4','a','rid','inp1'];
$j=$rid.'|referendum,com|pr=0-0,inpm='.$mnt.',inp2='.$id.',a='.($id?1:3).',rid='.$rid;
for($i=1;$i<5;$i++)$ret.=bj($j.',ex='.$i.'|codb','m'.$i,$i==$a?'bton':'btno');
$ret.=' '.lang('winner').': '.tag('b','',$win).' ';
$ret.=lang('mention').': '.tag('b','',self::$rm[$ka]??'0').'; '.$status.' ';
if($ra)$ret.=lang('among').' '.tag('b','',count(array_shift($ra))).' '.lang('voters',1).' ';
<<<<<<< HEAD
$ret.=rplay(self::$rf['process']??[]);
=======
$ret.=trace(self::$rf['process']??[]);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
return div($ret,'valid');}

static function results($rb,$rc,$a,$rid,$id=''){//pr($rb);
$ret=''; $n=0; $b=0; $winner=key($rc); $sz=400; $mn=self::$mnt;//max width
$rt[0][0]=lang('candidate'); if(!$rid)$rid=randid('mj');
$rt[0][1]=lang('rank'); $rt[0][2]='';
for($i=1;$i<=$mn;$i++)//headers
	$rt[0][2].=span(str_pad('',$i,'*'),'ansprop score'.$i,'','width:'.($sz/$mn+$b).'px;');
$rt[0][2]=div($rt[0][2],'pllcnt');
if($rc)foreach($rc as $k=>$v){//$ka=$k;//+1;
	$stot=0; $sum=0; $rts=''; $rd=[];
	$rd[0]=$rb[$k]??$k; $n++; $rd[1]=$n;
	for($i=1;$i<=$mn;$i++){$vi=$v[$i]??0; $nb=round($vi*100,2); //$nb=$r[$k][$i]??0;
		$wd=$vi*$sz+$b; $t=$wd>16?$nb:tag('span',['title'=>$nb],'*');
		$rts.=span($nb,'ansprop score'.$i,'','width:'.$wd.'px;');}
	$rd[2]=div($rts,'pllcnt');
	$rt[]=$rd;}
$ret=tabler($rt,'','1');
if($winner)$ret.=self::winner($rb,$rc,$winner,$a,$rid,$id);
$ret.=hidden('codb',referendum::buildatas(self::$ra,$rb,';'));
return $ret;}

#read
static function cancel($p){
qr('delete from judgment_r where bid="'.$p['id'].'" and uid="'.ses('uid').'"');
return self::build($p);}

static function votes($id){$rb=[];
return sql('choice,val',self::$db2,'kr',['bid'=>$id,'_order'=>'choice']);}

static function pane($rb,$closed,$note,$nb,$id){$ret=[];
if(!ses('uid'))$com='popup|core,help|ref=loged_needed';
else $com='pb'.$id.'|judgment,note|id='.$id;//note button
for($i=1;$i<=$nb;$i++){$rt='';
	$answer=ico('square-o').' '.val($rb,$i);
	$noted=count($note)==$nb?1:0; //$noted=$closed;
	//if(auth(6))$noted=0;
	$notedcase=$note[$i]??'';
	for($k=1;$k<=self::$mnt;$k++){
		$ico=$k<=$notedcase?ico('star'):ico('star-o');
		if($closed)$rt.=span($ico);
		else $rt.=bj($com.',choice='.$i.',val='.$k,$ico);}
	$ret[]=[$answer,$rt];}
return tabler($ret);}

static function build($p){$id=$p['id']; $ret=''; $rc=[];
[$answers,$end]=sql('com,day',self::$db,'rw',$id); $m=0.5; $a=3;//mode of exaequo
if(ses('uid'))$note=sql('choice,val',self::$db2,'kv',['bid'=>$id,'uid'=>ses('uid')]); else $note=[];
$rs=sql('distinct(uid)',self::$db2,'k',['bid'=>$id,'_order'=>'val']);
$rb=explode('|',$answers); $nb=count($rb); array_unshift($rb,'null'); $sum=array_sum($rs);
$endtime=strtotime($end);
$leftime=ses('time')-$endtime;
if($leftime>0)$closed=1; else $closed=0;
if($p['adm']??'')$closed=1; //if(auth(6))$closed=1;
$r=self::votes($id);//[choice][mention]=nb
if($r)$rc=self::algo($r,$m,$a);//[choice][mention]=percent
if(count($rc)<$nb)for($i=1;$i<=$nb;$i++)$rc[$i]=$rc[$i]??0;//complete
if($closed)$ret=div(self::results($rb,$rc,$a,'jm'.$id,$id),'','jm'.$id);
else $ret=self::pane($rb,$closed,$note,$nb,$id);
$ret.=bj('popup|judgment,votants|id='.$id,langnb('voter',$sum),'tot').' ';//footer
if($closed)$state=lang('vote closed').' '.lang('the',1).' '.$end;
else $state=lang('time left').' : '.build::leftime($endtime);
$ret.=span($state,'grey');
if(ses('uid') && $note && !$closed)$ret.=bj('pb'.$id.'|judgment,cancel|id='.$id,lang('cancel'),'btdel');
if(!$closed)$ret.=bj('pb'.$id.'|judgment,build|headers=1,adm=1,id='.$id,lang('see'),'btn');
return div($ret,'','pb'.$id);}

#stream
static function play($p){$bt=''; $go='';
$id=$p['id']; $rid=$p['rid']??''; $tid=randid('txt');
$cols=self::$db.'.id,name,txt,com,day';
$where='where '.self::$db.'.id='.$id.' order by '.self::$db.'.id desc';
$r=sql::inner($cols,self::$db,'login','uid','ra',$where);
if(!$r)return help('id not exists','board');
//admin
if($rid){$go=bj('blcbk|judgment,stream|rid='.$rid,'#'.$id,'btn');
	$go.=tlxf::publishbt(lang('use'),$id.':judgment',$rid);}
$go.=lk('/judgment/'.$id,ico('link'),'btn').' ';
$by=small($r['day'].' '.lang('by').' '.$r['name']).' ';
//$bt=div($bt,'right');
$txt=div(nl2br(voc($r['txt'],self::$db.'-txt-'.$id)),'txt',$tid);
//results
$results=self::build($p);
//render
$ret=div($txt.$results,'');//$go.' '.$by.$bt.br().
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
//self::install();
//sql::rn('vote','judgment');
//sql::rn('vote_note',self::$db2);
return parent::content($p);}
}

?>