<?php

//application not based on appx
class referendum{	
static $private=0;
static $a=__CLASS__;
static $db='referendum';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='mdb';
static $rn=[];
static $mnt=0;

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function ex(){
return ['wiki'=>'1,0,2,2,1,0;0,1,2,3,0,0',
'usual'=>'1484,1763,912,1971,2128,1532,210;2427,1158,1342,1295,1851,1705,222;2500,2500,1500,1500,1000,900,100',
'ep2012'=>',hollande,bayrou,sarkozy,�lenchon,dupont-aignan,joly,poutou,lepen,arthaud,cheminade;12,12,15,12,16,16,12;9,12,20,25,22,10,3;32,8,11,11,16,12,10;25,14,17,15,13,10,5;34,26,20,11,6,2,1;39,25,15,12,7,3,1;46,28,12,8,4,1,0;48,6,14,9,10,7,6;50,25,13,7,4,1,0;52,27,12,6,2,0,0',
'ep2017'=>',m�lenchon,macron,hamon,dupont-aignan,lepen,poutou,fillon,lassale,arthaud,asselineau,cheminade;16,11,9,29,22,13;20,11,11,26,23,9;21,15,17,29,12,6;24,20,18,24,10,4;34,14,7,16,14,15;26,23,17,23,8,2;34,18,11,18,12,7;29,27,19,20,4,1;29,28,16,19,6,2;32,31,17,17,3,0',
'ep2022'=>',E. Philippe,N. Hulot,R. Bachelot, X. Bertrand, N. Sarkozy, B. Le maire, JY Le Drian,O. V�ran, M. Le Pen, F. Baroin, M. Marechal, E. Zemmour, O. Besancenot, A. Montebourg,JL M�lenchon,M. Barnier,V. P�cresse,Y. Jadot,P. Juvin,F. Roussel,A. Hidalgo,N. D-Aignan,E. Ciotti,F. Philippot;18,17,19,32,14;22,18,19,32,9;22,20,25,29,4;26,22,21,25,6;15,31,23,24,7;29,20,21,25,5;35,17,18,24,6;24,25,22,24,5;11,45,16,17,11;37,17,19,21,6;21,36,17,17,9;16,45,14,16,9;30,30,17,19,4;30,21,27,20,2;15,43,21,15,6;42,15,18,20,5;28,20,24,22,6;35,21,22,18,4;61,15,15,7,2;54,19,15,9,3;21,37,24,15,3;26,35,19,16,4;38,30,19,10,3;29,40,17,12,2',
'ep2022'=>',E. Philippe,N. Hulot,R. Bachelot, X. Bertrand, N. Sarkozy, B. Le maire, JY Le Drian,O. V�ran, M. Le Pen, F. Baroin, M. Marechal, E. Zemmour, O. Besancenot, A. Montebourg,JL M�lenchon,M. Barnier,V. P�cresse,Y. Jadot,P. Juvin,F. Roussel,A. Hidalgo,N. D-Aignan,E. Ciotti,F. Philippot;45,19,32,14;40,19,32,9;42,25,29,4;48,21,25,6;46,23,24,7;49,21,25,5;52,18,24,6;49,22,24,5;56,16,17,11;54,19,21,6;57,17,17,9;61,14,16,9;60,17,19,4;51,27,20,2;58,21,15,6;57,18,20,5;48,24,22,6;56,22,18,4;76,15,7,2;73,15,9,3;58,24,15,3;61,19,16,4;68,19,10,3;69,17,12,2',
'ep2022/02'=>',em,vp,mlp,yf,fr,jl,ah,jlm,na,ct,nda,pp,ez;330,96,145,114,138,110,67;260,153,204,145,125,76,37;443,111,117,71,88,76,93;321,233,223,106,78,23,15;330,255,203,111,61,32,8;406,261,174,87,47,14,10;435,238,154,89,58,18,7;460,158,144,89,75,47,27;462,247,168,73,30,13,9;464,193,138,89,78,31,7;485,186,154,83,57,24,12;489,233,137,76,43,14,8;579,89,95,61,66,50,60'];}

static function edit($p){$rb=[]; $n=7; $nb=5; $j='';
$d=$p['codb']??''; $r=explode_r($d,"\n",','); if($r){$n=count($r); $nb=count(current($r));}
for($i=0;$i<$n;$i++)for($ib=0;$ib<$nb;$ib++){$rb[$i][$ib]=input('ed'.$i.'-'.$ib,$r[$i][$ib]??'',2,'',1,'',$j);}
return tabler($rb);}

#method2 (draft)
/**/static function resolve2($rq,$rm,$rt,$m,$n){arsort($rq);
if(!is_array($rq) && count($rq)>1)
if(!judgment::duplicates($rq))return $rq;
$rb=judgment::solution($rq); //$rs=[array_keys($rq)];
$rk=self::resolution2($rb,$rm,$rt,$m,$n);
return $rk;}

static function best2a($rc,$rb,$rm,$rt,$m,$n){
$looser=''; $winner=''; $rq=[]; $rk=[]; $rkb=[]; $kb=''; $exq=0;
foreach($rc as $k=>$v){$rd1[$k]=$v[0]; $rd2[$k]=$v[1];}
arsort($rd1); arsort($rd2); $na=count($rc);
[$k1,$v1]=judgment::maxk($rd1); [$k2,$v2]=judgment::maxk($rd2);
$isu1=judgment::isuniq($rd1,$v1); $isu2=judgment::isuniq($rd2,$v2);//if best result is single - not significant if not unique
if($v1<$v2)$winner=$k2; elseif($v1>$v2 or ($v1>=$v2 && $isu1==1))$looser=$k1; else $exq=1;//strict equality
$d='win:'.$winner.',looser:'.$looser.',eq:'.$exq;//echo $k1.':'.$v1.';'.$k2.':'.$v2.';';
if($looser){
	if($isu1==1){$kb=$k1; unset($rd1[$k1]); unset($rd2[$k1]);}
	if(count($rd1)==1){$ka=key($rd1); $rk[]=$ka; unset($rd1[$ka]); unset($rd2[$ka]);}
	elseif($rd1){//loosers at equality
		foreach($rd1 as $k=>$v)if($v==$v1){$rq[$k]=$v; unset($rd1[$k]); unset($rd2[$k]);}
		$rkb=self::resolve2($rq,$rm,$rt,$m,$n+1);}}
elseif($winner){
	if($isu2==1){$rk[]=$k2; unset($rd1[$k2]); unset($rd2[$k2]);}
	if(count($rd2)==1){$ka=key($rd2); $rk[]=$ka; unset($rd1[$ka]); unset($rd2[$ka]);}
	elseif($rd2){//winners at equality
		foreach($rd2 as $k=>$v)if($v==$v2){$rq[$k]=$v; unset($rd1[$k]); unset($rd2[$k]);}
		$rka=self::resolve2($rq,$rm,$rt,$m,$n+1); $rk=pushr($rk,$rka);}}
if(count($rd1)==1){$ka=key($rd1); $rk[]=$ka; unset($rd1[$ka]); unset($rd2[$ka]);}
elseif($rd1)$rk[]=self::resolve2($rd1,$rm,$rt,$m,$n+1);
if($rkb)$rk=pushr($rk,$rkb);
if($kb)$rk[]=$kb;
judgment::$rf['iteration'][$n]=[$d,'ranks'=>$rk,'scores (opposants-supporters)'=>$rc];
return $rk;}

static function resolution2($rb,$rm,$rt,$m,$n=0){$rd=[];
if($n>round(judgment::$mnt/2) && $rb){$rd=judgment::randr($rb[0]); judgment::$rf['random'][]=$rb[0];}//randomize strict equality
else foreach($rb as $k=>$v){//order[][candidate]
	if(is_array($v)){$rc=[];
		foreach($v as $ka=>$va){$mnt=$rm[$va];//for each exeaquo
		//scores opponents < mention < supporters (addition)
		$rc[$va]=judgment::scores($rt[$va],$mnt,$n);}
	//highest value determine the results
	$rd[]=self::best2a($rc,$rb,$rm,$rt,$m,$n);}
	else $rd[]=$v;}
return $rd;}

#method4: reached percent around middle//4b
static function best10($rb,$rm,$rt,$m,$n,$o,$u=0){$rc=[]; $rd=[]; $rk=[];
$mnt=judgment::$mnt; $ma=round($mnt/2); $mb=$u; if($u%2==0)$mb*=-1; $mc=$ma+$mb;
foreach($rb as $k=>$v){$rtb=array_count_values($rt[$v]); $sum=array_sum($rtb); //pr($rtb);
	$rc[$v]=isset($rtb[$mc])?$rtb[$mc]/$sum:0;} arsort($rc); //pr($rc);
$rb=judgment::solution($rc); //pr($rb);
if($u<10)foreach($rb as $k=>$v)if(is_array($v))$rd[]=self::best10($v,$rm,$rt,$m,$n,$o,$u+1); else $rd[]=$v; //pr($rd);
else $rd=$rb; //pr($rd);
//$rk=self::rank($rd); pr($rk);
judgment::$rf['process'][]['tie-break'.$n.'.'.$o.'.'.$u]=['rc'=>$rc];
return $rk;}

#method1: by calculation (dev) //1c
static function best11($rb,$rm,$rt,$m,$n,$o){$re=[]; $rc=[]; $k1=''; $na=0; $ok=1; $rt=judgment::$ra;
foreach($rb as $k=>$v){$re[$v]=array_count_values($rt[$v]); $nb=count($rt[$v])/2; $nc=0;}
foreach($re[$v] as $ka=>$va){$nc+=$va; if($nc<$nb)$rc[$v]=$nc;} //pr($rc);
judgment::$rf['process'][]['tie-break'.$n.'.'.$o]=['iterations'=>$na,'rc'=>$rc];
if($na>=1000)judgment::$rf['process'][]='max limit of 100 iterations is reached';
return $rc;}

//method1: iterate votes, del middle voter //1b
static function best8($rb,$rm,$rt,$m,$n,$o){$rq=[]; $rc=[]; $na=0;
foreach($rb as $k=>$v){$rq[$v]=$rt[$v]; $rc[$v]=$rm[$v]; $nt=count($rq[$v]);}//select
while(count(array_count_values($rc))==1 && $rq && $na<$nt){$na++;
	foreach($rq as $k=>$v){$i=0; $nb=floor(count($v)/2); 
		array_splice($rq[$k],$nb,1);//del middle//produce errors
		//foreach($v as $ka=>$va)if($i++==$nb)unset($rq[$k][$ka]);//del middle
		//foreach($v as $ka=>$va)if($va==$rm[$k])$k1=$ka; unset($rq[$k][$k1]);//del last voter at mention//ok
		//$k1=array_search($rm[$k],$v); unset($rq[$k][$k1]);//del first//ok
		//echo $nb.'-'; pr($rq[$k]);
		$rc[$k]=judgment::majoritary($k,$rq[$k],$m); $rm=$rc;}}//pr($rq);
judgment::$rf['process'][]['tie-break'.$n.'.'.$o]=['iterations'=>$na,'rc'=>$rc];
if($na>=1000)judgment::$rf['process'][]='max limit of 1000 iterations is reached';
return $rc;}

#build
static function build0($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

static function cod($d){//paq
$d=clean_separator($d,';','\n'); $r=explode_r($d,';',',');
if(!is_numeric($r[0][0]))$rb=array_shift($r);//isole candidates
if($rb[0])array_unshift($rb,''); self::$rn=$rb;//candidate 0 not exists
return self::onpaq($r);}

static function onpaq($r){$rt=[];//create array of voters
foreach($r as $k=>$v){//$vb is the nb of votes of range $kb+1 [candidat][voter]=note
	foreach($v as $kb=>$vb)for($i=0;$i<$vb;$i++)$rt[$k+1][]=$kb+1;} //pr($rt);
return $rt;}

static function votes($i,$n,$nb=5){$r=[];
for($i=1;$i<=$n;$i++)$r[]=rand(1,$nb);return $r;}

static function names($n){$ra=str_split('abcdefghijklmnopqrstuvwxyz');
for($i=1;$i<=$n;$i++)$rb[$i]=$ra[$i-1]??b36($i); return $rb;}
static function knames($id){$r=sql('com',judgment::$db,'v',$id);
$rb=explode('|',$r); array_unshift($rb,''); return $rb;}

static function buildatas($r,$ra,$s="\n"){
if(!isset($ra[0]))array_unshift($ra,'');
$rb=self::unpaq($r); array_unshift($rb,$ra);
return implode_r($rb,$s,',');}

static function unpaq($r){$n=0; $rc=[];//create synthesis of votes
foreach($r as $k=>$v){$ra=array_count_values($v); $rb[]=$ra; $na=max($v); if($na>$n)$n=$na;} //p($rb);
foreach($rb as $k=>$v){for($i=1;$i<=$n;$i++)$rc[$k][$i]=$v[$i]??0;} //ksort($rc[$k]);//ksort($rc);//pr($rc);
return $rc;}

static function unpaq0($r){$n=0; $rc=[];//create synthesis of votes
foreach($r as $k=>$v){$rc=array_count_values($v); ksort($rc); $rb[]=$rc; $na=count($rc); if($na>$n)$n=$na;}
foreach($rb as $k=>$v)if($k){for($i=1;$i<=$n;$i++)$rb[$k][$i]=$v[$i]??0; ksort($rb[$k]);} //pr($rb);
return $rc;}

#play
static function runs($p,$n){
$ex=$p['ex']??2; $rid=valb($p,'rid',randid('mj')); $re=[]; $ret='';
$rb=self::names($p['inpc']);
for($i=0;$i<$n;$i++){
judgment::$rf=[];
$r=self::buildvotes($p);
if(self::$mnt)judgment::$mnt=self::$mnt; else judgment::$mnt=maxr($r);
$rc=judgment::algo($r,0.5,$ex); //pr($rc);
$ret.=div(judgment::results($rb,$rc,$ex,$rid),'',$rid);
$re[]=count(judgment::$rf['random']??[]);}
$eq=array_sum($re); $rn=sesadr('ne',$eq,0); if(count($rn)>10)sez('ne',[]);
return $eq.' equalities / '.$n.' runs '.'('.implode(',',$rn).')'.br().$ret;}

static function play($p,$r){$ret=''; //pr($r);
$m=$p['inp1']??''; $a=$p['a']??0; $ex=$p['ex']??3; $rid=valb($p,'rid',randid('mj'));
$pr=array_flip(explode('-',$p['pr']??'')); //pr($pr);
$n=count($r); $rb=self::names($n); if($a==1)$rb=self::knames($p['inp2']);
if(self::$mnt)judgment::$mnt=self::$mnt; else judgment::$mnt=maxr($r);
$rc=judgment::algo($r,$m,$ex);
if(self::$rn)$rb=self::$rn;
$ret.=div(judgment::results($rb,$rc,$ex,$rid),'',$rid);
if(isset($pr[1]))$ret.=div(tree(judgment::$rf),'scroll');
if(isset($pr[2]))$ret.=textarea('codb',self::buildatas($r,$rb,';'));
//else $ret.=hidden('codb',self::buildatas($r,$rb,';'));
return $ret;}

static function buildvotes($p){$a=$p['a']??1;
if($a==0)$r=self::cod($p['cod']);
if($a==1)$r=judgment::votes($p['inp2']??11);
if($a==2)$r=loop('referendum::votes',$p['inpc']??4,$p['inp3']??10,$p['inpm']??5);
if($a==3)$r=self::cod($p['codb']); 
if($a!=1){array_unshift($r,''); unset($r[0]);}
return $r;}

#call
static function call($p){$ret='';
$nr=$p['inp4']??''; if($nr)return self::runs($p,$nr);
$r=self::buildvotes($p);
if($r)$ret=self::play($p,$r);
if(!$ret)return help('nothing','txt');
return $ret;}

static function com($p){$ret='';
$rp=['cod','ex','pr','inp3','inpc','inpm','inp2','codb','inp4','a','rid','inp1'];
if($p['p1']??'')$p=prmp($p,$rp); else $p=valk($p,$rp);
$p['inp1']=0.5; //judgment::$ma=100;
if($p['inpm'])self::$mnt=$p['inpm'];
return self::call($p);}

static function com2($r,$ex=4){$rb=[];//income
$rc=judgment::algo($r,0.5,$ex); $rid=randid('mj');
foreach($rc as $k=>$v)$rb[$k]=usrid($k);//kusers
$ret=judgment::results($rb,$rc,$ex,$rid);
return div($ret,'',$rid);}

static function menu($p){$j=self::$cb.'|'.self::$a.',call|';
$ret=build::sample(['a'=>'referendum','b'=>'cod']);
$ret.=textarea('cod','1,0,2,2,1,0'.n().'0,1,2,3,0,0',20,4,'','console');//1,2,3,1,3;2,3,1,1,3;3,0,1,4,2;1,2,3,1,3
$jb=$j.'a=0|inp1,pr,ex,cod'; $ret.=bj($jb,'code','btok');
$ret.=hlpbt('referendum_generator').' ';
$jb=$j.'a=1|inp1,pr,ex,inp2'; $ret.=input('inp2',11,4,'id').bj($jb,'judgment','btok');
$ret.=input('inpm',5,4,'mentions');
$ret.=input('inpc',10,4,'candidates');
$ret.=input('inp3',100,4,'voters');
$jb=$j.'a=2|inp1,pr,ex,inp3,inpc,inpm'; $ret.=bj($jb,'rand','btok');
$jb=$j.'a=3|inp1,pr,ex,inp3,inpc,inpm,codb'; $ret.=bj($jb,langpi('refresh'),'btok').'';
$jb='referendum,call|a=3,c=1|inp1,ex,inp3,inpc,inpm,codb'; $ret.=popup($jb,langpi('popup'),'btok').' ';
$ret.=input('inp4',10,4,'runs');
$jb=$j.'a=2|inp1,pr,ex,inp3,inp4,inpc,inpm'; $ret.=bj($jb,langpi('runs'),'btok').' ';
$ret.=checkbox('pr',[1=>'verbose','datas']);
$ret.=hlpbt('referendum_verbose').br();
//$ret.=popup('referendum,edit||codb',langpi('edit'),'btok').br();
$ret.=radio('ex',[1=>'iterate votes','iterate mentions','evaluation','floating median','percents','chips','2a','1b','3a','4b'],3);
$ret.=hlpbt('referendum_dev').' ';
$ret.=input('inp1',0.5,4,'mediane').hlpbt('referendum_mediane').' ';
//$ret.=hidden('inp1',0.5);
return $ret;}

#content
static function content($p){
//self::install();
$p['inp2']=$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
$lk=popup('art,call|id=2570ff6','article 1','btn');
$lk.=popup('art,call|id=ce68e51','article 2','btn');
return $bt.div($ret,'pane',self::$cb).$lk;}

static function api($p){
$r=self::buildvotes($p);
$rc=judgment::algo($r,0.5,4);
return json_encode($rc);}
}
?>