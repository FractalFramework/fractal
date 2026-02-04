<?php
class bank extends appx{
static $private=2;
static $a='bank';
static $db='bank';
static $cb='bnk';
static $cols=['label','value','type','at','ct','cl'];//credit-rights
static $typs=['var','double','int','int','int','int'];
static $conn=0;
static $db2='bank_credits';
static $db3='bank_contracts';
static $bank_credits=['uid'=>'int','red'=>'int','blue'=>'int','green'=>'int','month'=>'int'];
static $bank_contracts=['uid'=>'int','uid2'=>'int','value'=>'int','type'=>'int','app'=>'var','aid'=>'int','ok'=>'int'];
static $open=1;
static $tags=0;
static $coins=['red','blue','green'];
static $coinb=['red'=>0,'blue'=>1,'green'=>2];
static $money=['mass','time','space'];
static $usage=['products','work','resources'];
static $clr=['#BC2356','#3366CC','#369C10'];//CC3366,,66CC33
static $pic=['coffee','plane','suitcase'];
static $css=['coin1','coin2','coin3'];
static $rf=[];
static $at=0;
static $er=0;

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create(self::$db2,self::$bank_credits,1);
sql::create(self::$db3,self::$bank_contracts,1);}

static function admin($p){$p['o']='0';
$r=parent::admin($p);
//$r[]=['','j',self::$cb.'|'.self::$a.',home|rid='.$p['rid'],'circle','accounts'];
return $r;}

static function titles($p){return parent::titles($p);}
static function js(){return '';}
static function headers(){head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){return parent::del($p);}//$p['db2']=self::$db2;
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}

static function fc_label($k,$v){return input($k,$v,'26',lang('label'),'','','',1).label($k,lang('label'));}
static function fc_value($k,$v){return input($k,$v,'16',lang('value'),'','','',1).label($k,lang('value'));}
static function fc_type($k,$v){return self::coin(lang(self::$usage[$v]),$v);}//radio($k,self::$usage,$v,'',1);
static function fc_at($k,$v){return input($k,$v,'8',lang('to'),'','','',1).label($k,lang('to'));}
static function fc_ct($k,$v){return input($k,$v,'8',lang('ct'),'','','',1).label($k,lang('ct'));}
static function fc_cl($k,$v){return input($k,$v,'8',lang('cl'),'','','',1).label($k,lang('cl'));}

static function form($p){
$p['fclabel']=1;
$p['fcvalue']=1;
$p['fctype']=1;
$p['fcat']=1;
$p['fcct']=1;
$p['fccl']=1;
return parent::form($p);}

static function edit($p){
return parent::edit($p);}

static function create($p){return help('bank_create');}//parent::create($p)

#coins
static function coin($n,$ty=0){
//$ret=ascii(8475).' '.$n;//$unit=unicode('%u211D');
$c=self::$css[$ty]??'';
$ret=ico('circle','',$c);//money
return span($ret.$n,'coin');}

static function stock($n,$ty){
$ret=div(lang(self::$usage[$ty]));
$ret.=div(self::coin($n,$ty),'coin_in');
return div($ret,'coin '.self::$money[$ty]);}

#account
static function init($uid){
$r=['uid'=>0,'label'=>'system.donation','value'=>100,'type'=>0,'at'=>$uid,'ct'=>0,'cl'=>1];
//$rp=['uid'=>0,'uid2'=>$uid,'value'=>100,'type'=>0,'app'=>'bank','aid'=>'0','ok'=>1];
//$r['ct']=bank::contract_id($rp);
$ok=sql::sav(self::$db,$r);
$r['value']=10; $r['type']=1; $ok=sql::sav(self::$db,$r);
$r['value']=1; $r['type']=2; $ok=sql::sav(self::$db,$r);
return ['red'=>100,'blue'=>10,'green'=>1];}

static function all_transactions($uid=0){
$w='where (uid='.$uid.' or at='.$uid.') and cl=1';
return sql('all',self::$db,'rr',$w);}

static function investigate($uid){
$r=self::all_transactions($uid); //pr($r);
if(!$r)return self::init($uid);
if($r)foreach($r as $k=>$v){
	if($v['at']==$uid)$rb[$v['type']][]=$v['value'];
	if($v['uid']==$uid)$rb[$v['type']][]=(0-$v['value']);}
if($rb)foreach($rb as $k=>$v){$coin=self::$coins[$k]; $va=array_sum($v); $rc[$coin]=$va;}
$rc['month']=date('ym');
self::$rf['investigate']=$rc;
return $rc;}

static function account_update($uid=0){
$r=self::investigate($uid); //pr($r);
$id=sql('id',self::$db2,'v',['uid'=>$uid]);
if($id)sql::upd(self::$db2,$r,['uid'=>$uid]);
else{$r=['uid'=>$uid]+$r; sql::sav(self::$db2,$r,0,0,1);}
self::$rf['account_update']='ok';
return array_values(array_slice($r,0,3));}

static function account($uid,$o=''){
if($o){$r=self::account_update($uid); if($r)return $r;}
return sql('red,blue,green',self::$db2,'rw',['uid'=>$uid]);}

static function available($uid,$ty=0,$o=0){
$r=self::account($uid,$o); return $r[$ty]??0;}

static function possibility($p){
[$uid,$amount,$ty]=valsb($p,['from','value','type'],0);
$stock=self::available($uid,$ty,0);
self::$rf['possibility']=$stock>$amount?'ok':'no';
if($stock<$amount)return $er=4;}

#transaction
static function transfert_exists($p){$id=''; $cl='';
$r=valkb($p,['uid','label','value','type','at'],0); //$r['cl']=1;
$r=sql('id,ct,cl',self::$db,'rw',$r); if($r)[$id,$ct,$cl]=$r;
return $id?($cl?10:11):'';}

static function contract_exists($p){$id=''; $ok='';
$r=valkb($p,['uid','uid2','value','type','app','aid'],0); //$r['ok']=1;
$r=sql('id,ok',self::$db3,'rw',$r); if($r)[$id,$ok]=$r;
return $id?($ok?8:9):'';}

static private function transfert($p){$id=''; $p['cl']=1;
$r=valk($p,['uid','label','value','type','at','ct','cl']);
if(!$r['at'])$tousr=0; else $tousr=usrid($r['at']);
if(!$r['uid'] && !auth(6))return $er=3;
$r=sql::vrf($r,self::$db3); //pr($r);
$nid=self::bank_trigger($p); self::$rf['trigger']=$nid;
if($nid)$id=sql::sav(self::$db,$r,'','',1); self::$rf['bank']=$r;
if($id)self::account_update($r['uid']); //pr($r);
if($id)tlxf::saventf1($tousr,$id,7);
self::$rf['transfert_id']=$id;
return $id?1:2;}

//bank_condition
static function contract_validation($p){$er='';
[$a,$id,$ct,$cnd]=vals($p,['app','aid','ct','cnd']);
$idc=sql('id',self::$db3,'v',$ct); if(!$idc)$er=6;
if(!$er){$ok=self::bank_condition($p); if(!$ok)$er=7;}
if(!$er)$er=sql::upd(self::$db3,['ok'=>1],$ct);//&&$id means contract are refs
self::$rf['contract_validation']=($er?'not ':'').'valided'; //returns actions
//if(!$er)$er=app($a,['id'=>$id,'ct'=>$ct],'actions');
return $er;}

static function contract_id($p){
$r=valkb($p,['uid','uid2','value','type','app','aid','ok'],0);
$id=sql('id',self::$db3,'v',$r);
if(!$id)$id=sql::sav(self::$db3,$r);
self::$rf['contract_id']=$id;//callback...
return $id;}

//callbacks
static function bank_condition($p){
[$a,$id,$cnd]=vals($p,['app','aid','cnd']);
if(method_exists($a,'bank_condition'))return $a::bank_condition($p);
return 1;}

static function bank_trigger($p){
[$a,$id,$ct]=vals($p,['app','aid','ct']);
if(method_exists($a,'bank_trigger'))return $a::bank_trigger($p);
return 'ok';}

static function bank_finalization($p){
[$a,$bid,$cid,$va,$ty,$rf]=vals($p,['app','aid','cid','value','type','rf'],0);
if(method_exists($a,'bank_finalization'))return $a::bank_finalization($p);
$ret=help('Thank you'); if($rf)$ret.=rplay(bank::$rf);
return $ret;}

//load
static function transaction($p){$er=0;
$ra=['from','value','type','at','label','app','aid','ct','cnd','vb','rid','ok','rf'];
[$from,$val,$ty,$at,$lbl,$app,$aid,$ct,$cnd,$vb,$rid,$ok,$rf]=valsb($p,$ra,0); if(!$from)$from=0;
if($at==$from)return self::verbose(3); //pr($p);
$er=self::transfert_exists($p); if($er)return self::verbose($er);//continue?
if(!$er)$er=self::possibility($p); if($er && $from==0)$er=0;//sys create money
if(!$er && !$ct){$rp=['uid'=>$from,'uid2'=>$at,'value'=>$val,'type'=>$ty,'app'=>$app,'aid'=>$aid,'ok'=>0];
	$ct=bank::contract_id($rp); $p['ct']=$ct; $p['vb']=1; if(!$ct)$er=6; 
	if($ct){$ex=self::contract_exists($p); if($ex==9)$er=9;}//if ex=8:terminate
	if($ct && !$ok)return self::verbose(5).self::bt($p);}
if(!$er && $ct)$er=self::contract_validation($p);
if(!$er)$ok=self::transfert($p+['uid'=>$from]);//transaction complete/fail/null
if(!$er)$er=1;
self::$rf['transaction']=$er;
if($er==1)return self::bank_finalization($p);
elseif($vb)return self::verbose($er);
//if($rf)return rplay(bank::$rf);
return $er;}

static function bt($p){$rid=randid(); $p['rid']=$rid;
[$val,$ty,$at,$a,$aid,$ct,$ok]=valsb($p,['value','type','at','app','aid','ct','ok'],0);
$bt=self::coin($val,$ty); $cusr=usrid($at); if($cusr)$bt.=' ('.$cusr.')'; //self::$rf['bt']=$bt;
$er=self::transfert_exists($p); if($er)return self::verbose($er).$bt;
if($ct or $ok){$ret=bj($rid.'|bank,transaction|'.prm($p),langp('payment').' '.$bt,'btok');}
else $ret=bj($rid.'|bank,transaction|'.prm($p),lang('to contract').' '.$bt,'btsav');
//if(!$ok)$ret.=bj($a::$cb.$aid.'|'.$a.',call|id='.$aid,langp('back'),'btno');
if(bank::$rf)$ret.=rplay(bank::$rf);
return span($ret,'',$rid);}

static function verbose($n){switch($n){
case 0:return help('nothing happened','alert'); break;
case 1:return help('transaction complete','valid'); break;
case 2:return help('transaction fail','alert'); break;
case 3:return help('transaction null','alert'); break;
case 4:return help('insufficient credit','alert'); break;
case 5:return help('contract valided','valid'); break;
case 6:return help('contract null','alert'); break;
case 7:return help('contract not valided','alert'); break;
case 8:return help('contract closed','alert'); break;
case 9:return help('contract opened','alert'); break;
case 10:return help('transaction closed','alert'); break;
case 11:return help('transaction opened','alert'); break;
default:return 'error:'.$n;}}

#play
static function build($p){$id=$p['id']??'';
return sql('all',self::$db,'ra',$id);}

static function play($p){
$r=self::build($p);
$ret=div($r['label'],'tit');
if($r['uid']!=ses('uid'))$ret.=langx('transaction from').' '.($r['uid']?usrid($r['uid']):lang('system'));
if($r['at']!=ses('uid'))$ret.=langx('transaction to').' '.($r['at']?usrid($r['at']):lang('system'));
$ret.=self::coin($r['value'],$r['type']);
if($r['cl'])$ret.=div(lang('transaction closed'),'alert');
return $ret;}

#stream
static function watch($p){$ret=''; $d=$p['amount']; $ty=$p['type'];
$r=sql('red,blue,green',self::$db2,'rw',['uid'=>ses('uid')]);
if($r)foreach($r as $k=>$v)$ret.=self::stock($v,$k);
$ret=div($ret,'','account');
if($d)$ret.=div(lang('you spend').' '.self::coin($d,$ty),'btn');
return $ret;}

static function presentation($r){$ret='';
if($r)foreach($r as $k=>$v)$ret.=div(self::stock($v,$k),'cell');
return div($ret,'','row account');}

static function home($p){$rid=$p['rid']??'';
$a=self::$a; $cb=self::$cb; $cols=self::$cols; 
$t=$cols[0]; $uid=ses('uid'); $me=lang('me'); $sys=lang('system');
$dsp=ses($a.'dsp',val($p,'display'));
$ra=self::account($uid,1); //pr($ra);
//$ret=bj(self::$cb.'|bank,stream',langp('back'),'btn');
$ret=self::presentation($ra);
$w='where (uid="'.$uid.'" or at='.$uid.') and cl=1 order by up desc';
$r=sql('id,uid,label,value,type,at,ct,dateup',self::$db,'rr',$w);
$ri=['id','label','credit','debit','type','from','to','cid','date'];
foreach($ri as $k=>$v)$rt[0][]=lang($v); $rt[0][0]='id';
if($r)foreach($r as $k=>$v){$ok=1; $credit=''; $debit=''; $from=''; $to='';
	[$id,$ty,$va,$vuid,$at,$ct,$dat]=valsb($v,['id','type','value','uid','at','ct','date'],0);
	$css=self::$money[$ty]; $usage=self::$usage[$ty];
	$cointy=ico('circle','',self::$css[$ty]).lang($usage);
	if($at==$uid)$credit=div($va,$css);
	if($vuid==$uid)$debit=div(0-$va,$css);
	$tit=bj($cb.'|'.$a.',edit|id='.$id.',rid='.$rid,$v[$t],''); //$tit=$v[$t];
	if($vuid==0)$from=$sys; elseif($vuid==$uid)$from=$me; else $from=usrid($vuid); 
	$to=$at==$uid?$me:usrid($at); if($at==0)$to=$sys;
	$rt[]=['#'.$id,$tit,$credit,$debit,$cointy,$from,$to,$ct,span($dat,'date')];}
$ret.=tabler($rt,1);
return $ret;}

static function stream($p){$rid=$p['rid']??''; $ret=''; return self::home($p); //return parent::stream($p);
$a=self::$a; $cb=self::$cb; $cols=self::$cols; 
$t=val($p,'t',$cols[0]); $uid=ses('uid'); $usr=ses('usr');
$dsp=ses($a.'dsp',val($p,'display'));//and cl=0 
$r=sql('id,uid,'.$t.',value,type,at,dateup',self::$db,'rr','where uid="'.$uid.'" order by up desc');
if($r)foreach($r as $k=>$v){$ok=1;
	$tit=$v[$t]?$v[$t]:'#'.$v['id'];
	$com='edit'; $ic=self::coin($v['value'],$v['type']);
	$btn=$ic.$tit.' '.span('#'.$v['id'].' '.$v['date'],'date');
	$c=$dsp==1?'bicon':'licon'; $cl=val($c,'cl');
	if($cl!=0)$com='call';
	$ret.=bj($cb.'|'.$a.','.$com.'|id='.$v['id'].',rid='.$rid,$btn,$c);}
if(!$ret)$ret=help('no element','txt');
return div($ret,'');}

#call
//static function uid($id){return parent::uid($id);}
//static function own($id){return parent::own($id);}
static function tit($p){$p['t']=self::$cols[0]; return parent::tit($p);}
static function call($p){return parent::call($p);}

#com (edit)
static function com($p){return parent::com($p);}

#interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>