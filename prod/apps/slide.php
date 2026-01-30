<?php
class slide extends appx{
static $private=0;
static $a='slide';
static $db='slide';
static $db2='slide_r';
static $cb='sld';
static $cols=['tit','pub','edt'];
static $typs=['var','var','int'];
static $conn=1;
static $tags=1;
static $open=1;

//install
static function install($p=''){
$r=['bid'=>'int','idn'=>'int','idp'=>'int','t2'=>'var','bkg'=>'var','txt'=>'text','rel'=>'int'];
sql::create(self::$db2,$r,1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1'; return parent::admin($p);}
static function js(){return 'function sldbar(v,nid){
	var id=nid.substr(6);
	ajx(\'sld\'+id+\'|slide,play|id=\'+id+\',idn=\'+v);
	inn(v,nid);}
function mouscroll(e){var e=window.event||e;
	var delta=Math.max(-1,Math.min(1,(e.wheelDelta||-e.detail))); alert(delta);
	var id=getbyid(\'sldid\'); sldbar(delta,\'lblbar\'+id);}
addEventListener(\'mousewheel\',mouscroll,false);';}
static function css(){return '.slide{background:black; color:white; font-size:x-large; font-family:Ubuntu; align:center; padding:100px; margin:10px 0 0 0; white-space:pre-wrap; height:440px; overflow-y:auto; overflow-x:hidden;}';}
static function headers(){
head::add('csscode',self::css());
head::add('jscode',self::js());}

static function titles($p){
$d=$p['_m']??'';
$r['addslide']=lang('creating').' '.lang('slide');
$r['mdfslide']=lang('editing').' '.lang('slide');
$r['sldel']=lang('delete').' '.lang('slide');
if(isset($r[$d]))return $r[$d];
return parent::titles($p);}

#edit
static function save($p){$tit=$p['tit']??''; //return parent::save($p);
$p['id']=sql::sav(self::$db,['uid'=>ses('uid'),'tit'=>$tit,'pub'=>0,'edt'=>0]);
sql::sav(self::$db2,[$p['id'],'1','0',lang('first slide'),'',lang('first slide'),'0']);
return self::edit($p);}

static function connbt($id){
$ret=btj('[]',atj('embed_slct',['[',']',$id]),'btn');
$r=['h1','h2','h3','h4','h5','h6','h7','h8','b','i','u','q'];
foreach($r as $k=>$v)$ret.=btj(lang($v,1),atj('embed_slct',['[',':'.$v.']',$id]),'btn');
$ret.=hlpbt('connectors');
return div($ret);}

static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function modif($p){return parent::modif($p);}
static function create($p){$id=$p['id']??''; $rid=$p['rid']??''; $p['help']=1; return parent::create($p);}
/*$a=self::$a; $cb=self::$cb; $cls=implode(',',self::$cols);
$ret=bj($cb.'|'.$a.',stream|rid='.$rid,pic('back'),'btn');
if($hlp=$p['help']??'')$ret.=hlpbt($hlp);
$ret.=bj($cb.'|'.$a.',save|rid='.$rid.'|'.$cls.',imp',lang('save'),'btsav').br();
$ret.=$a::form($p).br();
$ret.=help('slide_taxo');
$ret.=self::connbt('imp').textarea('imp','',40,12).label('imp',lang('taxonomy'));
return $ret;}*/

#subedit
static function subbt($p){$id=$p['id']; $idb=$p['idb']??''; $ret='';
//if($idb)$ret.=bj('popup|slide,preview|idb='.$idb,langpi('preview'),'btn');
$ret.=bj('popup|slide,topo|edt=1,id='.$id,langpi('topology'),'btn');
$ret.=bj('popup|slide,book|id='.$id,langpi('export'),'btn');
return $ret;}

//repair
static function repair($ra,$id,$idb,$idn){$i=0; $rc=[];
$r=sql('id,idn,idp,rel',self::$db2,'rid','where bid="'.$id.'" order by idn');
foreach($r as $k=>$v)$rk[$v['idn']]=$k;//assoc old links
foreach($r as $k=>$v){//displace
	if($v['idn']==$idn)$rb[$idb]=$ra; if($k!=$idb)$rb[$k]=$v;}
foreach($rb as $k=>$v){$i++; $rb[$k]['idn']=$i; $rkb[$k]=$i;}//reorder
foreach($rb as $k=>$v){//new links
	if($v['idp'] && isset($rk[$v['idp']]))$rb[$k]['idp']=$rb[$rk[$v['idp']]]['idn'];
	if($v['rel'] && isset($rk[$v['rel']]))$rb[$k]['rel']=$rb[$rk[$v['rel']]]['idn'];}
foreach($rb as $k=>$v)sql::upd(self::$db2,$v,$k);//save
foreach($rb as $k=>$v)//rapport
	foreach($v as $ka=>$va)if($r[$k][$ka]??'' && $va!=$r[$k][$ka])$rc[$k][$ka]=$ka.':'.$r[$k][$ka].'->'.$va;
return $rc;}

static function collisions($p){$db2=self::$db2; $ret='';
$id=$p['id']??''; $idb=$p['idb']??''; $idn=$p['idn']??''; $idp=$p['idp']??''; $rel=$p['rel']??'';
[$_idn,$_idp,$_rel]=sql('idn,idp,rel',$db2,'rw',$idb);
$rc=[0,0,0]; if($idn!=$_idn)$rc[0]=1; if($idp!=$_idp)$rc[1]=1; if($rel!=$_rel)$rc[2]=1;
if(!$rc[0] && !$rc[1] && !$rc[2])return 0;
if(!$rc[0] && $idp==$idn)$idp=$rc[1]?$_idp:0;//collide parents
if($rc[0] && ($rel==$idn or $rel==$idp))$rel=$rc[2]&&$_rel!=$idn&&$_rel!=$idp?$_rel:0;//ref
$p['idn']=$idn; $p['idp']=$idp; $p['rel']=$rel;
//$ra=valk($p,['t2','bkg','txt']); sql::upd($db2,$ra,$idb);
$ra=valk($p,['idn','idp','rel']); if($ra['idn']!=$p['idn']) sql::upd($db2,$ra,$idb);
$rc=self::repair($ra,$id,$idb,$idn);
if($rc){$ret=help('slide_collision','board').bj('sldok|core,txt',langp('ok'),'btn').tabler($rc,0,1);}
return div($ret,'','sldok');}

//sav
static function subops($p){//supplante
$p['t']='t2'; $p['bt']=self::subbt($p); $ret='';
[$id,$idb,$idn,$op]=vals($p,['id','idb','idn','op']); if(!$idn)$idn=1;
$cb=self::$cb; $a=self::$a; $db2=self::$db2;
if($op=='sav'){$p['op']='';
	$r=valk($p,sql::cols($db2,3,2)); sql::upd($db2,$r,$idb);
	$ret=self::collisions($p);}//alternative save
elseif($op=='add')$p['idn']=$idn+1;
return ($ret?$ret:'').parent::subops($p);}

static function subform($r){return parent::subform($r);}
static function subedit($p){$p['bt']=self::subbt($p); $p['t']='t2';
return parent::subedit($p);}
static function subcall($p){
$p['t']='t2'; $p['bt']=self::subbt($p); return parent::subcall($p);
$p['edt']=1; return $p['bt'].self::topo($p);}
static function form($p){return parent::form($p);}
static function edit($p){$p['sub']=1; $p['help']=1; return parent::edit($p);}

//del
static function sldel($p){
$ok=$p['ok']??''; $delall=$p['delall']??''; $rid=$p['rid']??'';
$id=$p['id']??''; $idb=$p['idb']??''; $idn=$p['idn']??1; if($idn==1)$idn=0;//forbid del first slide
$prm='id='.$id.',idb='.$idb.',idn='.$idn.',rid='.$rid; $cb=self::$cb.$id;
if(!$ok){$prm=$cb.',,x|slide,sldel|'.$prm.',ok=1'; 
	if($delall)return bj($prm.',delall=1',langp('del all slides'),'btdel');
	else return bj($prm,langp('del').' '.lang('slide').': '.$idn,'btdel');}
elseif($id && $delall){sql::del(self::$db,$id); sql::del(self::$db2,$id,'bid'); 
	return self::stream($p);}
elseif($id && $idn){//reorder slides and parents after deleting
	qr('delete from '.self::$db2.' where bid="'.$id.'" and idb="'.$idb.'" and idn="'.$idn.'"');
	$r=sql('id,idn',self::$db2,'kv','where bid="'.$id.'" and idn>"'.$idn.'" order by idn');
	if($r)foreach($r as $k=>$v){$nidn=$v-1; sql::upd(self::$db2,['idn'=>$nidn],$k);}
	$r=sql('id,idp',self::$db2,'kv','where bid="'.$id.'" and idp>="'.$idn.'" order by idn');
	if($r)foreach($r as $k=>$v)if($v){$nidp=$v-1; sql::upd(self::$db2,['idp'=>$nidp],$k);}
	$p['idn']=$idn-1>0?$idn-1:1; return self::play($p);}}

//add slide
static function lasidn($bid){
$r=sql('idn',self::$db2,'rv','where bid="'.$bid.'" order by idn');
if($r)return max($r)+1; else return 1;}

static function addsav($p){
$id=$p['id']??''; $idb=$p['idb']??''; $mdf=$p['mdf']; //$idn1=$p['idn1'];
if($mdf)sql::upd(self::$db2,valk($p,['idn','idp','t2','bkg','txt','rel']),$idb);
else $id=sql::sav(self::$db2,valk($p,['bid','idn','idp','t2','bkg','txt','rel']));
//if($p['idn']!=$idn1 && $idn1)self::collisions($p);
return self::play($p);}

static function addslide($p){
$rid=$p['rid']??''; $id=$p['id']??1;
$idn=self::lasidn($id); $idp=$p['idp']??''; $idp='';//
$cols='idn,idp,t2,bkg,txt,rel'; $cb=self::$cb.$id;
$r=['idn'=>$idn,'idp'=>$idp,'t2'=>'','bkg'=>'','txt'=>'','rel'=>''];
$prm=$cb.',,x|slide,addsav|id='.$id.',bid='.$id.',idn1='.$idp.',rid='.$rid;
$ret=bj($prm.'|'.$cols,langp('save').' '.lang('slide').' '.$idn,'btsav');
if($r)foreach($r as $k=>$v){
	if($k=='txt')$ret.=div(textarea($k,$v,40,4).label($k,lang($k,1)));
	elseif($k=='idn')$ret.=div(input($k,$idn).label($k,lang($k,1)));
	else $ret.=div(input($k,$v).label($k,lang($k,1)));}
return $ret;}

static function mdfslide($p){
$rid=$p['rid']??''; $id=$p['id']??1;//id=bid
$idn=val($p,'idn',1); $idb=$p['idb']??'';//id slide
$cb=self::$cb.$id; $cols='idn,idp,t2,bkg,txt,rel';
if($idb)$w=$idb; else $w=['bid'=>$id,'idn'=>$idn];
$r=sql($cols,self::$db2,'ra',$w); //p($r);
$prm=$cb.',,x|slide,addsav|'.'id='.$id.',idb='.$idb.',idn1='.$r['idn'].',rid='.$rid.',mdf=1|'.$cols;
$ret=bj($prm,langp('modif').' '.$r['idn'],'btsav');
//$ret.=parent::subform($r):
if($r)foreach($r as $k=>$v){
	if($k=='txt')$ret.=div(build::connbt($k).textarea($k,$v,40,12).label($k,lang($k,1)));
	elseif($k=='bkg')$ret.=inpclr($k,$v,'',1);
	else $ret.=div(input($k,$v).label($k,lang($k,1)));}/**/
return $ret;}

//taxonomy
static function topo_r($id,$r,$rt,$edt){
$ret=''; $bt=''; $cb=self::$cb;
foreach($r as $k=>$v){$idb=$rt[$k][0]; $t=$k.'. '.$rt[$k][1];
	if($k && $edt)$bt=bj($cb.$id.'|slide,subedit|id='.$id.',idb='.$idb,$t,'');
	elseif($k)$bt=bj($cb.$id.'|slide,play|id='.$id.',idb='.$idb,$t,'');
	if(is_array($v))$bt.=self::topo_r($id,$v,$rt,$edt);
	$ret.=li($bt,'');}
if($ret)return ul($ret);}

static function topo($p){$id=$p['id']??''; $edt=$p['edt']??'';
$r=sql('id,idn,idp,t2,txt',self::$db2,'rr','where bid="'.$id.'" order by idn');
if($r)foreach($r as $k=>$v){$rb[$v['idp']][$v['idn']]=1;
	$t=$v['t2']?$v['t2']:$v['txt']; $rt[$v['idn']]=[$v['id'],$t];}
if($rb)$rc=taxonomy($rb);
//if($edt)$edt=bj(self::$cb.$id.'sub|slide,subcall|id='.$id,langp('back'),'btn');
$ret=self::topo_r($id,$rc[0],$rt,$edt);
return $ret;}

static function book_r($id,$r,$rt){
static $ret; static $h; $h++;
foreach($r as $k=>$v){
	if(is_array($v))self::book_r($id,$v,$rt);
	else $ret.=tag('h'.$h,'',$k.'. '.$rt[$k]['t2']).div(conn::com($rt[$k]['txt'],1));} $h--;
return $ret;}

static function book($p){$id=$p['id']??'';
$r=sql('id,idn,idp,t2,txt',self::$db2,'rr','where bid="'.$id.'" order by idn');
if($r)foreach($r as $k=>$v){$rb[$v['idp']][$v['idn']]=1; $rt[$v['idn']]=$v;}
if($rb)$rc=taxonomy($rb);
$ret=self::book_r($id,$rc[0],$rt);
return $ret;}

//organigram
static function organigram($p){$id=$p['id']??''; $ret='';
$r=sql('id,idn,idp,t2,txt,rel',self::$db2,'rr','where bid="'.$id.'" order by idn');
if($r)foreach($r as $k=>$v){$rb[$v['idp']][$v['idn']]=1;
	$t=$v['t2']?$v['t2']:$v['txt']; $rt[$v['idn']]=$v['idn'].'. '.$t;}
if($rb)$rc=taxonomy($rb);
//$ret=self::topo_r($id,$rc[0],$rt);
return $ret;}

static function subtit($id,$idn){$ret='';
$ret=bj(self::$cb.$id.'|slide,play|id='.$id.',idn='.$idn,pic('backward').$idn,'btn');
$idp=sql('idp',self::$db2,'v',['bid'=>$id,'idn'=>$idn]);
if($idp && $idp!=$idn)$ret=self::subtit($id,$idp).$ret;
return $ret;}

static function preview($p){
$r=sql('t2,txt,bkg',self::$db2,'ra',$p['idb']);
$txt=$r['txt']; $txt=conn::com($txt,1);
$ret=div($txt,'','tx','margin:auto;');
$sty=theme($r['bkg']);
return div($ret,'slide','',$sty);}

#pane
static function pane($ra,$p){$ret=''; $bt=''; $subtit=''; $btb='';
[$tit,$id,$rid,$own]=vals($p,['tit','id','rid','own']);
$cb=self::$cb.$id; $idb=$ra['id']; $idn=$ra['idn'];
$prm='id='.$id.',rid='.$rid; $j=$cb.'|slide,play|'.$prm;
$bt=bj($j.',idn=1',pic('slide').' '.$tit,'btn');
if($own){
	$btb.=bj('popup|slide,mdfslide|'.$prm.',idb='.$idb,langpi('edition'),'btn');//subedit
	$btb.=bj('popup|slide,addslide|'.$prm.',idn='.$idn,langpi('add'),'btsav');
	$btb.=bj('popup|slide,sldel|'.$prm.',idb='.$idb.',idn='.$idn,langpi('del'),'btdel');
	$btb.=bj($cb.'edit|slide,edit|'.$prm.',idb='.$idb,langpi('edit'),'btn');}
$btb.=lk('/slide/'.$id.'/'.$idn,pic('url'),'btn',1);
$bt.=span($btb,'right');
if($idn>1)$bt.=bj($j.',idb='.$p['prv'],pic('previous'),'btn');
else $bt.=span(pic('previous'),'btn grey');
//$bt.=bar('bar'.$id,$idn,1,1,$p['max'],'','sldbar').hidden('sldid',$id);
if($idn<$p['max'])$bt.=bj($j.',idb='.$p['nxt'],pic('next'),'btn');
else $bt.=span(pic('next'),'btn grey');
$p['id']=$ra['id']; $p['idn']=$idn; $tit=$ra['t2'];
if($ra['idp'])$bt.=self::subtit($id,$ra['idp']);
//if($ra['idp'])$bt.=bj($j.',idn='.$ra['idp'],pic('backward').$ra['idp'],'btn');
$bt.=bj($j.',idb='.$idb,'#'.$idn.' '.$tit,'btok');
if($ra['rel'])$bt.=bj($j.',idn='.$ra['rel'],pic('parent'),'btn');
foreach($p['rel'] as $k=>$v)$bt.=bj($j.',idb='.$v,pic('child'),'btn');
foreach($p['chd'] as $k=>$v)$bt.=bj($j.',idb='.$v,pic('forward').$k,'btn');
$bt.=bj('popup|slide,topo|id='.$id.',idb='.$idb,langpi('topology'),'btn');
$bt.=bj('popup|slide,book|id='.$id,langpi('export'),'btn');
$txt=$ra['txt'];
$txt=conn::com($txt,1);
//$txt=gen::com($txt,[]);
if(isset($ra))$ret=div($txt,'','tx'.$rid,'margin:auto;');//nl2br->white-space:pre-wrap;
$sty=theme($ra['bkg']);
return div($bt).div($ret,'slide','',$sty);}

static function build($p){
return sql('id,idn,idp,t2,bkg,txt,rel',self::$db2,'rr','where id="'.$p['idb'].'"');}

static function play($p){$ra='';
$id=$p['id']??''; $idb=$p['idb']??''; $idn=val($p,'idn',1);
$r=sql('uid,tit',self::$db,'ra',$id);
if(!$r)return help('id not exists','paneb');
if($r['uid']==ses('uid'))$p['own']=$id; $p['tit']=$r['tit'];
$r=sql('id,idn,idp,t2,bkg,txt,rel',self::$db2,'rr','where bid="'.$id.'" order by idn asc');
if(!$r)return help('nothing','paneb');
$p['rel']=[]; $p['chd']=[]; $n=count($r)-1;
foreach($r as $k=>$v){
	if($v['idn']==$idn or $v['id']==$idb){$ra=$v;
		$p['prv']=$k?$r[$k-1]['id']:$v['id'];
		$p['nxt']=$k<$n?$r[$k+1]['id']:$v['id'];}}
if(!$ra)return help('slide not exists','paneb');
foreach($r as $k=>$v){
	if($v['rel']==$ra['idn'])$p['rel'][]=$v['id'];
	if($v['idp']==$ra['idn'])$p['chd'][$v['idn']]=$v['id'];}
$p['max']=$v['idn'];
//return self::pane2($ra,$p);
return self::pane($ra,$p);}

static function stream($p){
return parent::stream($p);}

#interfaces
static function tit($p){
return parent::tit($p);}

//call (read)
static function call($p){return parent::call($p);
$id=$p['id']??''; return div(self::play($p),'',self::$cb.$id);}

//com (write)
static function com($p){
return parent::com($p);}

//interface
static function content($p){
$p['idn']=$p['o']??'';
//self::install();
return parent::content($p);}
}
?>