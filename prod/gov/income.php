<?php
class income extends appx{
static $private=2;
static $a='income';
static $db='income';
static $cb='inc';
static $cols=['tit','txt'];
static $typs=['var','bvar'];
static $db2='inc_donations';
static $db3='inc_members';
static $db4='inc_scores';
static $inc_donations=['bid'=>'int','uid'=>'int','cid'=>'int','don'=>'int','ratio'=>'int'];
static $inc_members=['uid'=>'int','needs'=>'int','ok'=>'int'];//'bid'=>'int'
static $inc_scores=['uid'=>'int','cid'=>'int','note'=>'int','month'=>'int'];
static $conn=1;
static $open=0;
static $tags=0;
static $qb='db';
static $ty='0';
static $at=0;
static $remain=0;
static $bkc=0;

function __construct(){
$r=['a','db','cb','cols','db2','conn','db3','db4'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
sql::create(self::$db2,self::$inc_donations,1);
sql::create(self::$db3,self::$inc_members,1);
sql::create(self::$db4,self::$inc_scores,1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; $r=parent::admin($p);
$r[]=['','j',self::$cb.'|'.self::$a.',home|rid='.$p['rid'],'money','redistributing'];
return $r;}

static function titles($p){return parent::titles($p);}

//injected javascript (in current page or in popups)
static function js(){return 'function barlabel(v,id){var d="";
	var r=["","broken","bad","works","good","new","","",""];
	inn(r[v],id);}';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
//collected datas from public forms
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){$p['pub']=0;//default privacy
return parent::create($p);}

#subcall
//used in secondary database db2
static function subops($p){$p['t']='uid'; return parent::subops($p);}//$p['bt']='';
static function subform($r){return parent::subform($r);}//$r['html']='txt2';
static function subedit($p){$p['t']='uid'; return parent::subedit($p);}//$p['data-jb']//2nd save
static function subplay($r){return div(build::editable($r,'admin_sql',['b'=>self::$db2],1),'','asl');}
static function subcall($p){$p['t']='uid'; $p['collect']=self::$db2;//$p['player']='subplay';
return parent::subcall($p);}

#form
//override appx field of form for col 'txt'
/*static function fc_txt($k,$val,$v){
return textarea($k,$val,40,strlen($val)>500?26:16,'','',$v=='var'?512:0);}*/

static function form($p){
//$p['html']='txt';//contenteditable for txt
//$p['fctxt']=1;//form col call fc_tit();
//$p['bttxt']=1;//label for txt;
//$p['barfunc']='barlabel';//js function for bar()
return parent::form($p);}

static function edit($p){//->form, ->call
//$p['collect']=self::$db2;//d�sactiv� pour emp�cher l'�dition
$p['help']=1;//ref of help 'income_edit'
$p['sub']=1;//active sub process (attached datas)
return parent::edit($p);}

//callbacks
static function bank_condition($p){//needed by bank
[$id,$cnd]=vals($p,['aid','cnd']);
if($id && $cnd)$id=sql('id',self::$db,'v',$id);
//if($cnd && !self::$bkc)$id=0;//operation in context
if(!$id && $cnd)$id=1;
return $id?1:0;}

static function bank_trigger($p){//needed by bank
$a=self::$a; $ty=self::$ty; $uid=ses('uid');
[$bid,$cid,$don,$rat]=vals($p,['aid','cid','value','rat'],0);
$r=[$bid,$uid,$cid,$don,$rat]; //pr($r);
return sql::sav(self::$db2,$r);}

static function bank_finalization($p){//needed by bank
[$bid,$cid,$don,$rat,$rf,$cnd]=vals($p,['aid','cid','value','rat','rf','cnd'],0);
$ret=div(lang('don').': '.bank::coin($don,0).' '.lang('at').' '.$rat.'% '.lang('for').': '.usrid($cid),'nfo');
$ret.=help('Thank You').' '; $ret.=bj(self::$cb.$bid.'|income,call|id='.$bid,langp('close'),'btno').' ';
if($cnd==2)$ret=help('done'); if($rf)$ret.=trace(bank::$rf);
return $ret;}

//operation
static function savdon($p){$bt='';
$a=self::$a; $ty=self::$ty; $at=self::$at;//to system
[$bid,$cid,$rid]=vals($p,['id','cid','rid']);
$don=$p[$rid.'don']; $rat=$p[$rid.'rat']; $uid=ses('uid');
$pr=['from'=>$uid,'value'=>$don,'type'=>$ty,'at'=>$at,'label'=>$bid.':'.$a,'app'=>$a,'aid'=>$bid,'cnd'=>1,'vb'=>0,'rid'=>$rid,'ok'=>0,'rf'=>1,'cbk'=>0,'rat'=>$rat,'cid'=>$cid]; self::$bkc=1;//context not works in two steps as ok=0
$er=bank::transaction($pr); if(is_numeric($er))$ret=bank::verbose($er); else $ret=$er;
return $ret;}

static function scorebt($p){
[$cid,$note]=vals($p,['id','v']); $uid=ses('uid'); $dt=date('ym');
$r=[$uid,(int)$cid,(int)$note,$dt];
$ex=sql('id',self::$db4,'v',['cid'=>$cid,'uid'=>$uid,'month'=>$dt]);//self::$w
if(!$ex)sql::sav(self::$db4,$r); else sql::up(self::$db4,'note',$note,$ex,'',0);
return build::scorebt($p);}

static function scorify($p){
$r=sql('uid,ok',self::$db3,'kr',['ok'=>1]);
$rb=sql('cid,note',self::$db4,'kv',['uid'=>ses('uid'),'month'=>$p['dt']]);
foreach($r as $k=>$v){$rp=['id'=>$k,'rid'=>'sc'.$k,'a'=>'income','v'=>$rb[$k]??0];
	$rt[]=div(span(build::scorebt($rp),'','sc'.$k).usrid($k));}
return implode('',$rt);}

#build
static function build($p){
$ra=parent::build($p); $id=$p['id']??'';
$rb=sql('all',self::$db2,'rr',['bid'=>$id]);
return [$ra,$rb];}

static function template(){
return '[[(tit)*class=tit:div][[txt:conn]*class=txt:div]*class=paneb:div]';}//will nl2br()
static function template2(){
return '[[(don)*class=tit:div][[score:var]*class=txt:div]*class=paneb:div]';}//will nl2br()

static function userform($id,$rid,$cid){
$uid=ses('uid'); $rp=['bid'=>$id]; if($uid)$rp['uid']=$uid; $dt=date('ym');
$ra=sql('don,ratio',self::$db2,'ra',$rp);
$max=bank::available($uid,self::$ty,0);
$bt=hlpbt('income_don',lang('donation')); $cusr=usrid($cid);
$ret=div(bar($rid.'don',valb($ra,'don',10),$step=1,$min=1,$max,1).$bt);
//$bt=hlpbt('income_rat',lang('affectation'));
//$ret.=div(bar($rid.'rat',val($ra,'ratio',50),$step=10,$min=0,$max=100).$bt);
$ret.=hlpbt('income_sco',lang('score'));//.' '.profile::com($cid)
$note=sql('note',self::$db4,'v',['cid'=>$cid,'uid'=>$uid,'month'=>$dt]); if(!$note)$note=0;
$pr=['id'=>$cid,'rid'=>$rid.'b','a'=>'income','v'=>$note,'lbl'=>$cusr];
$ret.=div(div(build::scorebt($pr),'',$rid.'b'));
$ja=$rid.'b|income,scorify|dt='.$dt; $jb=$rid.'b|build,scorebt|'.prm($pr);
$ret.=togbt($ja,$jb,langx('others members'),'btn');
if($ra){$amount=sql('sum(don) as don',self::$db2,'v',['bid'=>$id,'uid'=>$uid]);
	$ret.=div(helpx('donation_exists').' '.lang('in the amount of').': '.bank::coin($amount,0),'valid');}
$j=$rid.'c|income,savdon|id='.$id.',cid='.$cid.',rid='.$rid;
$bt=bj($j.','.$rid.'rat=0|'.$rid.'don',helpx('don_all'),'btsav');
$bt.=bj($j.','.$rid.'rat=50|'.$rid.'don',helpx('don_preference'),'btsav');
$bt.=bj($j.','.$rid.'rat=100|'.$rid.'don',helpx('don_exclusive'),'btsav');
$bt.=toggle('|income,uf_bar|id='.$id.',cid='.$cid.',rid='.$rid,lang('choose'),'btn');
$ret.=div($bt,'',$rid.'c');
return $ret;}

static function uf_bar($p){
$prm=prm($p); $rid=$p['rid'];
$bt=hlpbt('income_rat',lang('affectation'));
$ret=div(bar($rid.'rat',50,1,0,100,1).$bt);
$j=$rid.'c|income,savdon|'.$prm.'|'.$rid.'don,'.$rid.'rat';
$ret.=bj($j,langp('donation'),'btsav');
return $ret;}

#play (where to begin to code)
static function play($p){
$uid=ses('uid'); $rid=randid('nc'); $id=$p['id'];
[$ra,$rb]=self::build($p); //pr($rb);
$ret=gen::com(self::template(),$ra);
$bt=self::userform($id,$rid,$ra['uid']);
return $ret.div($bt,'',$rid);}

#subscription
/*static function savneeds($p){$n=$p['needs'];
if(!$n or !is_numeric($n))return help('value?','alert').self::edtneeds($p);
sql::up(self::$db3,$n,ses('uid'),'uid');
return self::edtneeds($p);}*/

static function register($p){$n=$p['needs'];
if(!$n or !is_numeric($n))return help('value?','alert').self::subscription($p);
sql::sav(self::$db3,[ses('uid'),$n,1]);
return self::stream($p);}

static function subscription($id){
$ret=help('income_needs','valid');
$ret.=input_pic('needs','100',lang('needs'),'needs');
$ret.=bj(self::$cb.'|income,register||needs',langp('register'),'btsav');
$ret.=hlpbt('income_app');
return $ret;}

#redistribution
static function details($p){
$m=$p['m']??date('n'); $y=$p['y']??date('y');
[$w,$y,$m,$dt]=self::currentmonth($y,$m);
$r=sql('cid,note',self::$db4,'kr',['month'=>$dt]);
$ret=referendum::com2($r,4);
$ret.=help('income_details');
return $ret;}

static function notations($dt){$rt=[];
$r=sql('cid,note',self::$db4,'kr',['month'=>$dt]);
//foreach($r as $k=>$v)$rt[$k]=array_sum($v)/count($v);
$rb=judgment::algo($r,0.5,4); //pr($rb);
$rt=self::mkscore($rb);//convert scores to notes
return $rt;}

static function affectation($r){$rb=[];
foreach($r as $k=>$v){
	//sql::up2(self::$db2,['don'=>$v['ratio'],'ratio'=>$v['don']],$v['id']);
	$don=$v['don']*round($v['ratio']/100); $remain=$v['don']-$don;
	$rb[$v['cid']][]=[$v['don'],$v['ratio'],$don,$remain];}
return $rb;}

static function redistribution($w){$rt=[];
$r=sql('all',self::$db2,'rr',$w); //pr($r);
$rb=self::affectation($r);
foreach($rb as $k=>$v){
	$tot1=array_sum(array_column($v,2));//don
	$tot2=array_sum(array_column($v,3));//sys
	$rt[$k]=[$tot1,$tot2];}
return $rt;}

static function reaffectation($ra,$rn,$rd){$rt=[];
$remain=array_sum(array_column($rd,1)); $mxn=array_sum($rn); if(!$mxn)$mxn=count($ra);
foreach($ra as $k=>$v){$usr=usrid($k); $needs=$v; $note=$rn[$k]??1; $score=build::score($note);
	$don1=$rd[$k][0]??0; $don2=floor($remain*($note/$mxn)); $tot=$don1+$don2; $miss=$needs-$tot; if($miss<0)$miss=0;
	$rt[$k]=[$usr,$score,round($note,2),$needs,$don1,$don2,$tot,$miss];}
$remain-=array_sum(array_column($rt,5)); self::$remain=$remain;
return $rt;}

static function mkscore($r){$rt=[];
foreach($r as $k=>$v){$n=0; foreach($v as $ka=>$va)$n+=$va*($ka+1); $rt[$k]=$n;}
return $rt;}

static function currentmonth($y,$m){if($m<1){$m+=12; $y-=1;}
$d1=mktime(0,0,0,$m,1,$y); $from=date('Y-m-d H:i:s',$d1);
$d2=mktime(0,0,0,$m+1,1,$y); $to=date('Y-m-d H:i:s',$d2);
$d='where up>="'.$from.'" and up<"'.$to.'"'; //self::$w=$d;
$dt=$y.($m<10?'0'.$m:$m);
return [$d,$y,$m,$dt];}

static function home($p){$ret='';
$m=$p['m']??date('n'); $y=$p['y']??date('y');
self::$remain=bank::available(0,0,0);//uid,ty,upd
[$w,$y,$m,$dt]=self::currentmonth($y,$m); $cdt=date('ym');
$ra=sql('uid,needs',self::$db3,'kv','');
$rn=self::notations($dt); //pr($rn);
$rd=self::redistribution($w); //pr($rd);
$re=self::reaffectation($ra,$rn,$rd); //pr($re);
$ret=div(helpx('income_lettosys').': '.self::$remain,'paneb');
$ri=['name','score','note','needs','direct donation','indirect donation','total','missing'];
foreach($ri as $k=>$v)$rt[0][]=lang($v); $rr=array_keys_r($re,6);
$ret.=tabler($rt+$re,1);
$ret.=toggle('|income,details|m='.$m.',y='.$y,lang('see details'),'btn');
if($dt==$cdt)$ret.=toggle('|income,scorify|dt='.$dt,lang('scores'),'btn');
$lbl='income.redistribution.'.$dt;
if(auth(6) && $dt<$cdt){//contract direct gestion
$rp=['from'=>0,'type'=>0,'label'=>$lbl,'app'=>self::$a,'aid'=>0,'cnd'=>2,'vb'=>1,'ok'=>1,'ct'=>0,'rf'=>1];
	foreach($rr as $k=>$v){$ex=sql('id',bank::$db,'v',['label'=>$lbl,'at'=>$k,'value'=>$v]);
		if(!$ex && $v)$ret.=div(bank::bt($rp+['value'=>$v,'at'=>$k,'rid'=>'d'.$k]),'','d'.$k);}}
$d1=mktime(0,0,0,$m-1,1,$y); $d2=mktime(0,0,0,$m+1,1,$y); $d0=mktime(0,0,0,$m,1,$y);
$r=[[-1,-1,0,1,1],[-1,0,0,0,1]]; for($i=0;$i<5;$i++){$m0=$m+$r[0][$i]; $y0=$y+$r[1][$i];
	$tm=mktime(0,0,0,$m0,1,$y0); $dtc=date('ym',$tm); if($tm<time())
	$ret.=span(bj('incm|income,home|m='.$m0.',y='.$y0,$dtc,'btn'.active($dtc,$dt)),'','pybt');}
return div($ret,'','incm');}

#call
static function stream($p){
//$p['cover']=1;//personalized icons
$uid=ses('uid'); $ex='';
if($uid)$ex=sql('id',self::$db3,'v',['uid'=>$uid]);
if($ex)$ret=parent::stream($p);
else $ret=self::subscription($p);
//$ret.=self::home($p);
return $ret;}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];//used col as title
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){return parent::com($p);}
static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
//self::install();//hide this in prod
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>