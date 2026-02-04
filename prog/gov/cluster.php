<?php
class cluster extends appx{
static $private=2;
static $a='cluster';
static $db='cluster';
static $cb='cls';
static $cols=['tit','typ','status','value','hash','img'];
static $typs=['var','int','int','int','svar','svar'];
static $conn=0;
static $db2='cluster_prop';
static $db3='cluster_attr';
static $db4='cluster_unit';
static $db5='cluster_parents';
//static $db5='cluster_parent_contracts';
//static $db6='cluster_parent_usages';//see contracts (usage is used to evaluate object)
static $credits=['red','blue','green'];
static $usage=['product','work','resource'];
static $status=['free','used','not free','destroyed'];
static $open=0;
static $tags=0;
static $rc=[];

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
//props: actions(r)/job(b)/maintenance(g), atid: attr id, unid: unit id
sql::create('cluster_prop',['bid'=>'int','atid'=>'int','unid'=>'int','prop'=>'var'],1);
sql::create('cluster_attr',['attr'=>'var','typ'=>'int'],1);//attr of prop
sql::create('cluster_unit',['atid'=>'int','unit'=>'var'],1);//scale of prop
//pid: parent id, ct: contract id
//not 'child' because clusters are built from other clusters
sql::create('cluster_parents',['bid'=>'int','pid'=>'int','ct'=>'int'],1);}

static function admin($p){$p['o']=1; $p['ob']=1;
return parent::admin($p);}

static function titles($p){return parent::titles($p);}
static function js(){return '';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){
return parent::collect($p);}

static function del($p){
//$p['db2']='cluster_prop';
return parent::del($p);}

static function save($p){
return parent::save($p);}

static function modif($p){
return parent::modif($p);}

#form
/*static function subcall0($p){$id=$p['id']; $cb=self::$cb; $tg=$cb.'tab';
$bt=bjtab($tg.'|cluster,properties|id='.$id,langp('properties'),'active');
$bt.=bjtab($tg.'|cluster,parents|id='.$id,langp('dependancies'),'');
$ret=self::properties($p);
return div($bt,'tabs').div($ret,'',$tg);}*/

static function subcall($p){
$cb=self::$cb; $tg=$cb.'tab'; $id=$p['id']??'';
$ret=bj($tg.'1|cluster,properties|id='.$id,langp('properties'),'tit');
$ret.=div(self::properties($p),'',$cb.'tab1').hr();
$ret.=bj($tg.'2|cluster,parents|id='.$id,langp('dependancies'),'tit');
$ret.=div(self::parents($p),'',$cb.'tab2');
return div($ret);}

static function boxhide($p,$xid=''){$r=self::$usage;
if($xid)$p['rid']=$xid; $rid=$p['rid']; $id=$p['id']; $ka=valb($p,'ka',0); $ret='';
foreach($r as $k=>$v){$c='btn'; if($k==$ka)$c.=' bk'.self::$credits[$k].' active'; $p['ka']=$k;
	$ret.=bj($rid.'|cluster,boxhide|'.prm($p),langp($v),$c);}
$ret.=hidden($id,$ka); if($xid)$ret=div($ret,'',$xid); return $ret;}

static function status($d){$r=self::$status; $ret='';
foreach($r as $k=>$v)$ret.=span(langp($v),'btn'.active($k,$d));
return $ret;}

static function form($p){$ret=''; $bt=''; $cb=self::$cb; $pr=pushr(['id','uid'],self::$cols);
[$id,$uid,$tit,$typ,$status,$value,$hash,$img]=vals($p,$pr);//['id','uid',...self::$cols]
$ret=hidden('id',$id);
$ret.=input('tit',$tit,32,lang('title'));
$ret.=self::boxhide(['id'=>'typ','ka'=>$typ],'clstyp');
if(auth(6))$ret.=boxhide(['id'=>'status','ka'=>$status,'s'=>implode('-',self::$status)],'clstus');
else $ret.=self::status($status);
if($typ==0)$bt=bj('value|cluster,calculate|id='.$id,langp('calculate'),'btn').hlpbt('cluster_calculate');
elseif($typ==1)$bt=bj('value|cluster,income|id='.$id,langp('calculate'),'btn').hlpbt('cluster_income');
elseif($typ==2)$bt=bj('value|cluster,crscr|id='.$id,langp('calculate'),'btn').hlpbt('cluster_rscr');
$ret.=div(input('value',$value,12,lang('value'),1,'','',$typ).$bt.' '.label('value',lang('value')));
$bt=bj('hash|cluster,genhash|id='.$id,langp('generate'),'btn');
$ret.=div(input('hash',$hash,12,lang('hash'),0,'','',1).$bt.' '.label('hash',lang('hash')));
$ret.=div(inpimg('img',$img,''));
$ret.=hr().div(self::subcall($p),'',$cb.$id.'sub');
return $ret;}

#edit
static function edit($p){
$p['help']='cluster_edit'; $id=$p['id'];
$p['sub']=1;//mean local subcall
$bid=sql('bid',self::$db5,'v',['pid'=>$id]);
if($bid)$p['bt']=bjk(self::$cb.$id.'edit|cluster,edit|id='.$bid,langp('parent cluster'),'btko',self::$a.'/'.$bid);
return parent::edit($p);}

static function add($p){
return self::form($p);}

static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//copy
/*static function copyfrom($p){//p($p);
$id=$p['id']??''; $bid=$p['bid']; $typ=$p['typ']??0; $ret='';
if(!$bid){$r=sql('id,tit',self::$db,'kv',['typ'=>$typ]); //p($r);
	if($r)foreach($r as $k=>$v)
		$ret.=bj(self::$cb.'tab|cluster,copyfrom|id='.$id.',bid='.$k,$v);}
else{$r=$r=sql('atid,unid,prop','cluster_prop','rr',['bid'=>$id],1); //p($r);
	if($r)foreach($r as $k=>$v)
		$rb[]=[$id,$v['atid'],$v['unid'],$v['prop']]; //pr($rb);
	sql::sav2('cluster_prop',$rb);}
return div($ret,'list');}*/

#bank
static function bank_condition($p){//needed by bank
[$uid,$id,$cnd]=vals($p,['at','aid','cnd']); //pr($p);
$cuid=sql('uid',self::$db,'v',$id);//at is owner of cluster
if($cuid==$uid)return 1;}

static function bank_trigger($p){//needed by bank
[$bid,$cid,$ct,$ty,$cuid]=valsb($p,['aid','cid','ct','type','from'],0); //pr($p);
$ra=['tit','typ','status','value','hash','img'];
$r=sql(implode(',',$ra),self::$db,'ra',$bid);
$r['hash']=0; $r['status']=0; //$r['uid']=$cuid; //ses('uid')
$ra=sql::pvalk($r,self::$db,1); //pr($ra);
$nid=sql::sav(self::$db,$ra,0);//create new cluster
bank::$rf['new_cluster']=$nid;
$rb=['bid'=>$nid,'cid'=>$bid,'ct'=>$ct];
$kid=sql::sav(self::$db5,$rb);//associate parent
bank::$rf['new_parent']=$kid;
sql::upd(self::$db,['status'=>'2'],$bid);//disactivate old cluster
bank::$rf['disactivate']=$bid;
if($nid && $kid)return 'ok';}

static function bank_finalization($p){$ret='';//needed by bank
[$bid,$cid,$va,$ty,$rf,$cnd]=vals($p,['aid','cid','value','type','rf','cnd'],0);
$ret.=help('New cluster created').' ';
if($rf)$ret.=rplay(bank::$rf);
return $ret;}

#parents
static function del_parent($p){
$id=$p['id']; $pid=$p['pid']; $cid=$p['cid'];
$ct=sql('ct','cluster_parents','v',$pid);
if($id)sql::del('cluster_parents',$pid);
//$ct=self::contract($p+['ok'=>0]);
sql::upd(bank::$db3,['ok'=>'0'],$ct);//resolve contract
sql::upd(self::$db,['status'=>'0'],$cid);//if($typ==0)
return self::parents($p);}

static function contract($p){$cid='';
[$id,$pid,$ok2]=vals($p,['id','pid','ok']);
$uid=ses('uid'); $cuid=sql('uid',self::$db,'v',$id);
[$ty,$va]=sql('typ,value',self::$db,'rw',$id);//if!$va)...calculate
$r=['uid'=>$uid,'uid2'=>$cuid,'value'=>$va?$va:0,'type'=>$ty?$ty:0,'app'=>self::$a,'aid'=>$pid];
$rb=sql('id,ok',bank::$db3,'rw',$r); if($rb)[$cid,$ok]=$rb;
if($cid && $ok!=$ok2)sql::upd(bank::$db3,['ok'=>$ok2],$cid);//surely better to not update
elseif(!$cid && $ok2)$cid=sql::sav(bank::$db3,$r+['ok'=>1]);
sql::upd(self::$db,['status'=>1],$cid);
return $cid;}

static function sav_parent($p){
[$id,$pid]=vals($p,['id','pid']);
if($id==$pid or !$pid)return self::parents($p);
$ex=sql('id','cluster_parents','v',['bid'=>$id,'pid'=>$pid]);
$ct=self::contract($p+['pid'=>$pid,'ok'=>1]);
$r=['bid'=>$id,'pid'=>$pid,'ct'=>$ct];
if(!$ex)$p['pid']=sql::sav('cluster_parents',$r);
if($p['pid']??'')sql::upd(self::$db,['status'=>'1'],$pid);
return self::parents($p);}

static function open_parent($p){$ret=''; $id=$p['id'];
$r=sql('id,tit',self::$db,'kv',['uid'=>ses('uid'),'status'=>'0']);
$j=self::$cb.'tab2|cluster,sav_parent|';
if($r)foreach($r as $k=>$v)if($k!=$id)$ret.=bj($j.'id='.$id.',pid='.$k,$v);//btj($v,atj('val',[$k,'pid']));
if(!$ret)$ret=help('no free cluster');
return div($ret,'list');}

static function addnew_sav($p){
[$ti,$ty]=valsb($p,['ntit','ntyp'],0);
if(!$ti)return self::add_new($p);
$r=array_combine(self::$cols,[$ti,$ty,0,0,0,'']);
$p['pid']=parent::addentry($r); //return $id;
return self::sav_parent($p);
return btj(langp(''),atj('val',[$p['pid'],'pid']).atj('closediv',['adn','this']));}

static function add_new($p){
$r=sql('distinct(tit)',self::$db,'rv','');
//$ret=datalist('ntit',$r,'',32,lang('titled'));
$j=self::$cb.'tab2|cluster,addnew_sav|id='.$p['id'].'|ntit,ntyp';
$ret=datalistcall('ntit',$r,'',$j,lang('titled'),32);
$ret.=self::boxhide(['id'=>'ntyp','ka'=>0],'ntp');
$ret.=bj($j,langp('save'),'btsav');
return div($ret,'board');}

static function add_parent($p){$id=$p['id'];
$ret=input('pid','',12,lang('cluster_id'));
$ret.=bj(self::$cb.'tab2|cluster,sav_parent|id='.$id.'|pid',langp('attach'),'btsav');
$ret.=bubble('cluster,open_parent|id='.$id,langp('free clusters'),'btn','',0);
$ret.=toggle('adn|cluster,add_new|id='.$id,langp('add new'),'btn').span('','','adn');
//$ret.=bj(self::$cb.'|cluster,edit|id='.$id,langp('cancel'),'btn');
return $ret;}

static function parents($p){$id=$p['id']??''; $cb=self::$cb; $clr=self::$credits;
$r=sql::inner('cluster_parents.id,pid,tit,ct,value,typ','cluster_parents',self::$db,'pid','id',['bid'=>$id]);
$rb[]=['id',lang('title'),lang('value'),lang('contract'),lang('disconnect'),lang('browse')];
if($r)foreach($r as $k=>$v){$coin=bank::coin($v[3],$v[4]);
	$edt=bj($cb.'tab2|cluster,del_parent|id='.$id.',pid='.$k.',cid='.$v[0],langpi('disconnect'),'btdel');
	$see=bubble('cluster,menu|id='.$v[0],langpi('browse'),'btn',[],1);
	$parent=bjk($cb.$id.'edit|cluster,edit|id='.$v[0],$v[1],'btn',self::$a.'/'.$v[0]);
	$rb[]=[$v[0],$parent,$coin,$v[2],$edt,$see];}
$ret=tabler($rb,1);
$edt=bj(self::$cb.'edt|cluster,add_parent|id='.$id,langp('add dependance'),'btsav').hlpbt('cluster_parents');
return div($edt,'',$cb.'edt').$ret;}

#props

//edit prop (forbidden action)
static function mdf_prop($p){$id=$p['ida'];
if($p['del']??'')sql::del(self::$db2,$id);
else sql::upd(self::$db2,['prop'=>$p['mdfprop']],$id);
return self::properties($p);}

static function edt_prop($p){$id=$p['id']; $ida=$p['ida'];
$d=sql('prop',self::$db2,'v',$ida);
$ret=input('mdfprop',$d,32,lang('property'));
$ret.=bj(self::$cb.'tab1|cluster,mdf_prop|,id='.$id.',ida='.$ida.'|mdfprop',langp('modif'),'btok');
if(auth(6))$ret.=popup('admin_sql,call|b=cluster_prop,id='.$ida,langp('edit'),'btn');
return $ret;}

//prop
static function sav_prop($p){$id=$p['id']??'';
$atid=$p['atid']; $unid=$p['unid']; $prop=$p['addprop'];
$r=['bid'=>$id,'atid'=>$atid,'unid'=>$unid,'prop'=>$prop];
$ex=sql('id','cluster_prop','v',$r);
if(!$ex)sql::sav('cluster_prop',$r);
else sql::upd('cluster_prop',$r,$ex);
return self::properties($p);}

static function add_prop($p){$id=$p['id']??''; $atid=$p['atid']; $attr=$p['addattr'];
$rb=sql('distinct(prop)','cluster_prop','rv','');
$u0=sql('prop','cluster_prop','v',['atid'=>$atid]);
//$ret=datalist('addprop',$rb,'',20,$u0);
$j=self::$cb.'tab1|cluster,sav_prop|'.prm($p).',|addprop';
$ret=datalistcall('addprop',$rb,$u0,$j,lang('property'),20);
$ret.=bj($j,langp('add property'),'btsav');
$ret.=bj(self::$cb.'|cluster,edit|id='.$id,langp('cancel'),'btn');
return div($ret);}

//unit
static function sav_unit($p){$id=$p['id']??''; $atid=$p['atid']; $unit=$p['addunit'];
$ex=sql('id','cluster_unit','v',['unit'=>$unit,'atid'=>$atid]);
if($ex)$p['unid']=$ex; else $p['unid']=sql::sav('cluster_unit',['atid'=>$atid,'unit'=>$unit]);
return self::add_prop($p);}

static function add_unit($p){$id=$p['id']??''; $atid=$p['atid'];
$r=sql('distinct(unit)','cluster_unit','rv','');
$u0=sql('unit','cluster_unit','v',['atid'=>$atid]);
//$ret=datalist('addunit',$r,$u0,20,lang('referential'));
$j=self::$cb.'edt|cluster,sav_unit|'.prm($p).'|addunit';
$ret=datalistcall('addunit',$r,$u0,$j,lang('referential'),20);
$ret.=bj($j,langp('add unit'),'btsav');
$ret.=bj(self::$cb.'|cluster,edit|id='.$id,langp('cancel'),'btn');
return div($ret);}

//attr
static function sav_attr($p){$id=$p['id']??''; $attr=$p['addattr'];
if(!$attr)return self::add_attr($p);
$ex=sql('id',self::$db3,'v',['attr'=>$attr]);
$ex_prop=sql('id','cluster_prop','v',['bid'=>$id,'atid'=>$ex]);
if($ex)$p['atid']=$ex; elseif(!$ex_prop)$p['atid']=sql::sav(self::$db3,['attr'=>$attr,'typ'=>$p['typ']]);
return self::add_unit($p);}

static function add_attr($p){$id=$p['id']??''; $typ=$p['typ'];
$r=sql('distinct(attr)',self::$db3,'rv',['typ'=>$typ]);
//$ret=datalist('addattr',$r,'',20,lang('attribut'));
$j=self::$cb.'edt|cluster,sav_attr|id='.$id.',typ='.$typ.'|addattr';
$ret=datalistcall('addattr',$r,'',$j,lang('attribut'),20);
$ret.=bj($j,langp('add attribut'),'btsav');
//$ret.=bj(self::$cb.'|cluster,edit|id='.$id,langp('cancel'),'btn');
return $ret;}

//read
static function props($id){
return sql::inr('cluster_prop.id,attr,prop,unit',[['cluster_attr','id','cluster_prop','atid'],['cluster_prop','unid','cluster_unit','id']],'id',['bid'=>$id]);}

static function properties($p){
$id=$p['id']??''; $cb=self::$cb; $typ=valb($p,'typ',0); 
$r=self::props($id); //pr($r);
$rb[]=[lang('attribut'),lang('unit'),lang('property'),lang('edit'),lang('del')];
foreach($r as $k=>$v){
	$edt=bubble('cluster,edt_prop|id='.$id.',ida='.$k,langpi('edit'),'btn');
	$del=bj($cb.'tab1|cluster,mdf_prop|id='.$id.',ida='.$k.',del=1',langpi('del'),'btdel');
	$rb[]=[lang($v[0]),lang($v[2]),($v[1]),$edt,$del];}
$ret=tabler($rb,1);
$edt=bj($cb.'edt|cluster,add_attr|id='.$id.',typ='.$typ,langp('add attribut'),'btsav').hlpbt('cluster_properties');
//$edt.=bj($cb.'edt|cluster,copyfrom|id='.$id.',typ='.$typ,langp('copy from'),'btn');
return div($edt,'',$cb.'edt').$ret;}

#build
static function build($p){
return parent::build($p);}

static function read_r($ra,$rc){$ret='';
foreach($rc as $k=>$v){$cn=is_array($v);
	if(isset($ra[$k]))[$t,$ty,$va]=$ra[$k];
	else [$t,$ty,$va]=sql('tit,typ,value',self::$db,'rw',$k);
	$c=self::$credits[$ty?$ty:0]; $n=$cn?count($v):'0';
	$op=popup('cluster,edit|opn=1,id='.$k,pic('popup'),'');
	$bt=$t.' ['.langnb('credit',$va).'] ('.langnb('node',$n).') ';
	$ret.=li('#'.$k.' '.toggle('u-'.$k.'|cluster,menu|id='.$k,$bt,$c,[],$cn).$op);
	if($cn)$ret.=div(self::read_r($ra,$v),'','u-'.$k);
	else $ret.=ul('','','u-'.$k);}
if($ret)return ul($ret);}

static function taxo($p){$ret=''; $db=self::$db; $id=$p['id']; $uid=ses('uid'); $ra=[]; $rb=[];
$r=sql::inr($db.'.id,tit,typ,value,pid',[[$db,'id','cluster_parents','bid']],'',[]);//'uid'=>$uid,'_group'=>$db.'.id'
if($r)foreach($r as $k=>$v){$ra[$v[0]]=[$v[1],$v[2]?$v[2]:0,$v[3]]; $rb[$v[0]][$v[4]]=1;} //pr($r);
$rc=taxonomy($rb); //pr($rc);
return [$ra,$rc];}

static function menu($p){$id=$p['id'];
[$ra,$rc]=self::taxo($p); //pr($ra);
if(isset($rc[$id]))return self::read_r($ra,$rc[$id]);
else return tabler(self::props($id));}

//calc
static function calc_r($ra,$rc){
foreach($rc as $k=>$v){$cn=is_array($v);
	if(isset($ra[$k]))$rb=$ra[$k]; else $rb=sql('tit,typ,value',self::$db,'rw',$k);
	if($cn)self::calc_r($ra,$v); elseif(isset($rb))self::$rc[$k]=$rb;}}

static function calculate($p){$id=$p['id']; self::$rc=[]; $n=0;
[$ra,$rc]=self::taxo($p); if(isset($rc[$id]))self::calc_r($ra,$rc[$id]); //pr(self::$rc);
foreach(self::$rc as $k=>$v)if($v[1]!=0)$n+=$v[2];//only b/g are counted
return $n;}

static function income($p){$id=$p['id']; $ty=sql('typ',self::$db,'v',$id); $n=0;
$r=sql::inr('attr,unit,prop',[[self::$db2,'atid',self::$db3,'id'],[self::$db2,'unid',self::$db4,'id']],'id',['bid'=>$id]);
if($r['time'][0]=='week')$l=7; elseif($r['time'][0]=='month')$l=30; else $l=1;
return ($r['time'][1]??1)*$l*($r['qualification'][1]??1);}

static function crscr($p){$id=$p['id'];//return value of props//todo...
$r=sql::inr('attr,unit,prop',[[self::$db2,'atid',self::$db3,'id'],[self::$db2,'unid',self::$db4,'id']],'id',['bid'=>$id]);
return 1;}

static function genhash($p){$id=$p['id'];
$r=sql('id,atid,unid,prop',self::$db2,'',['bid'=>$id]);
if($r)$d=implode_r($r,'',''); else $d=time();
return md5($d);}

#play
static function play($p){$r=self::build($p);
[$id,$uid,$tit,$ty,$st,$va]=valsb($r,['id','uid','tit','typ','status','value'],0);
$clr=self::$credits[$ty]; $obj=self::$usage[$ty]; $stt=self::$status[$st];
$ret=div(span(langp($obj),$clr).' '.span($tit,''),'tit bksilver');
$ret.=playimg($r['img'],'micro');
$r=self::props($id); $rt[]=[lang('value'),bank::coin($va?$va:'0',$ty)];
if($r)foreach($r as $k=>$v)$rt[]=[lang($v[0]),($v[1]).($v[2]?' ('.lang($v[2]).')':'')];
$ret.=tabler($rt);
$n=sql::inner('count(pid)','cluster_parents',self::$db,'pid','v',['bid'=>$id]);
$bt=langnb('dependancy',$n);
$ret.=toggle('|cluster,menu|id='.$id,$bt,'btn'); $bt='';
//if($r)foreach($r as $k=>$v)$bt.=toggle('|cluster,call|id='.$k,$v,'licon'); $ret.=div($bt,'sublock');
$pr=['from'=>ses('uid'),'value'=>$va,'type'=>$ty,'at'=>$uid,'label'=>$id.':cluster','app'=>'cluster','aid'=>$id,'cnd'=>1,'vb'=>0,'ok'=>1,'rf'=>1,'cbk'=>0];//,'rid'=>'bnk'.$id
if(!$st)$ret.=bank::bt($pr); else $ret.=help('not available');
return $ret;}

#stream
static function cover($k,$v=[]){$ty=valb($v,'typ',0); //pr($v);
$bt=ico('code-fork'); $c='bkg'.self::$credits[$ty];
return span($bt,$c).span($v['tit']);
return bj(self::$cb.'|cluster,edit|id='.$v['id'],$bt,$c);}

static function stream($p){$ret='';
$a=self::$a; $db=self::$db; $md=$p['mod']??'1'; $uid=ses('uid'); $clr=cluster::$credits;
if($md==2)$r=sql('id,tit,typ',self::$db,'rr',['uid'=>$uid,'!status'=>'3']);
else $r=sql::outr($db.'.id,tit,typ',[['cluster','id','cluster_parents','pid']],'rr',['uid'=>$uid,'!status'=>'3','or'=>['pid'=>'is null'],'_order'=>'id']);//,'>typ'=>0
$bt=bj(self::$cb.'|cluster,stream|mod=1',langp('masters clusters'),'btn'.active($md,'1'));
$bt.=bj(self::$cb.'|cluster,stream|mod=2',langp('all clusters'),'btn'.active($md,'2'));
$bt.=hlpbt('cluster_categories');
if($r)foreach($r as $k=>$v){$ty=$v['typ']; $c='ic'.$clr[$ty];
	$btn=span(ico('code-fork'),$c).span($v['tit']);
	$ret.=bjk(self::$cb.'|cluster,edit|id='.$v['id'],div($btn),$c,$a.'/'.$v['id']);}
return $bt.div($ret,'bicon');}

#call
static function tit($p){
$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
return parent::call($p);}

static function com($p){
return parent::com($p);}

#interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>