<?php
//abstract app
class appx{
static $private=1;
static $a;//app name
static $db;//data base
static $cb;//callback name
static $cols=[];//cols of db
static $typs=[];//types of cols
static $conn;//connectors
static $tags;//tags
static $gen;//motor
static $db2;//db2
static $title;
static $descr;
static $image;
static $boot;//dislay in tlex
static $qb;//db type
static $db3;
static $db4;
static $db5;

function __construct(){self::$cb=randid('appx');}

static function boot(){//$a=self::$a;
if(self::boot==null)self::$boot=new $a;}

static function install($p){$r['uid']='int';
sqlcreate(self::$db,merge($r,$p),1);}

static function dblist(){$r['db']=self::$db;
foreach(['db2','db3','db4','db5'] as $k=>$v)if(isset(self::$$v))$r[$v]=self::$$v;
return $r;}

#admin menus
static function admin($p){//pr($p);
$a=self::$a; $db=self::$db; $cb=self::$cb; $rid=$p['rid']??''; $id=$p['id']??''; $nm='app';
//if($rid)$r[]=['','j',$cb.'|tlxf,apps|rid='.$rid,'',$a];
//if($rid)$r[]=['','j',$cb.'|'.$a.',stream|rid='.$rid,'',$a];
//if($p['ob']??'')$r[]=['','j',$cb.'|'.$a.',menu|display=2,rid='.$rid,'folder-open-o','files'];
//$r[]=['','j',$cb.'|tlxf,apps|rid='.$rid,'back','apps'];
$r[]=['','jk',$cb.'|'.$a.',stream|rid='.$rid,$a,$a];//menu//tri=x,tru=1,
if(ses('uid'))$r[]=['','j',$cb.'|'.$a.',create|rid='.$rid,'plus-circle','new'];
$r[]=['','bub','core,help|ref='.$a.'_app','question-circle-o','-'];
$r[]=['','lk','/'.$a.($id?'/'.$id:''),'url','-'];
$r[]=['','pop',$a.',search','search','-'];
//if($id)$r[]=['','','core,com|app='.self::$a.',id='.$id,'api','api'];
//$r[]=['','j',$cb.'|core,help|ref='.$a.'_app','question-circle-o','-'];
if(auth(4)){$r[]=[$nm,'j','pagup|dev,seeCode|f='.$a,'code','Code'];
	$r[]=[$nm,'j','popup|dev,com|f='.$a,'terminal','dev'];
	$r[]=[$nm,'j','popup|admin_sys,call|app='.$a,'file-code-o','code-comment'];}
if(auth(6)){$rb=self::dblist();
	foreach($rb as $k=>$v)$r[]=[$nm.'/db','j','popup|admin_sql|b='.$v,'db',$v];
	$r[]=[$nm.'/identity','pop','admin_lang,open|ref='.$a.',app='.$a,'lang','name'];
	$r[]=[$nm.'/identity','pop','admin_help,open|ref='.$a,'help','identity'];
	$r[]=[$nm.'/identity','pop','admin_help,open|ref='.$a.'_app','help','help'];
	$r[]=[$nm.'/identity','pop','admin_icons,open|ref='.$a,'picto','pictos'];
	$r[]=[$nm.'/identity','pop','admin_labels,open|ref='.$a,'tag','label'];
	$r[]=[$nm.'/identity','pop','desktop,tlex_app|app='.$a,'desktop','publish App'];}
return $r;}

static function find($p){$ret='';
$a=self::$a; $db=$p['db']; $b=$a::$$db; $c=$p['col']??self::$cols[0]; $d=$p['search'];
$r=sql('id',$b,'rv',['uid'=>ses('uid'),'%'.$c=>$d],0); if(!$r)return help('no_result');
foreach($r as $k=>$v)$ret.=bj('popup|'.$a.'|id='.$v,$a::tit(['id'=>$v]));
return div($ret,'list');}

static function search($p){
$a=self::$a; $r=sqlcls($a::$db,4,2);
$ret=select('db',self::dblist(),'',0);
$ret.=select('col',$r,'',1);
$j='srch|'.$a.',find||search,col,db';
$ret.=inputcall($j,'search','');
$ret.=bj($j,langp('search'),'btn');
return div($ret).div('','pane','srch');}

#titles to display in popup for each method
static function titles($p){
$d=$p['appMethod']??'';
$r=['content'=>'welcome','collect'=>'collected datas','call'=>self::$a];
if(isset($r[$d]))return lang($r[$d]);
return $d;}

static function injectJs(){}
static function headers(){
add_prop('og:title',addslashes_b(self::$title));
add_prop('og:description',addslashes_b(self::$descr));
add_prop('og:image',self::$image);}

#edit
static function del($p){$id=$p['id']??'';
$a=self::$a; $db=self::$db; $cb=self::$cb; $ok=$p['ok']??'';
$own=sql('id',$db,'v',['uid'=>ses('uid'),'id'=>$id]);
if($own!=$id)return help('operation not permited','alert');
if($ok){$p['noab']=1;//return in posts
	if($ok==1)sqldel($db,$id); 
	if($p['db2']??'')sqldel($p['db2'],$id,'bid');
	if($p['db3']??'')sqldel($p['db3'],$id,'bid');
	sqldel('tags_r',['app'=>$a,'aid'=>$id]);//tags
	if($ok==1)return $a::stream($p); else return $a::edit($p);}
$ret=bj($cb.'|'.$a.',del|ok=1,id='.$id,lang('confirm deleting'),'btdel');
$ret.=bj($cb.'|'.$a.',del|ok=2,id='.$id,lang('truncate'),'btdel');
$ret.=bj($cb.'|'.$a.',edit|id='.$id,lang('cancel'),'btn');
return $ret;}

static function addentry($p){
$a=self::$a; $db=self::$db;
$r=pvalk($p,$db,1);
return sqlsav($db,$r);}

static function save($p){$a=self::$a;
$p['id']=$a::addentry($p);
return $a::edit($p);}

static function modif($p){
$id=$p['id']??''; $a=self::$a; $db=self::$db;
if($a::$conn==1 && isset($p['txt']))$p['txt']=cleanconn(val($p,'txt'));
$r=pvalk($p,$db,0); $r=trims($r);
$ok=self::perms($db,$id,'pub');
if(!$ok)return lang('permission denied');
sqlups($db,$r,$id,'',0);
return $a::edit($p);}

//privacy
static $privacy=[0=>'privread',1=>'clan-visible',2=>'usr-visible',3=>'net-visible',4=>''];//
static $privedt=[0=>'privedit',1=>'clan-editable',2=>'usr-editable',3=>'net-editable'];
static $privdsk=[0=>'internet',1=>'network',2=>'clan',3=>'mentions',4=>'private',5=>'dev',6=>'admin'];
static $app2dsk=[0=>3,1=>2,2=>1,3=>0,4=>0,5=>0,6=>0];//translation systems of privacy

static function subscriptor($uid){//if usr ab to name
$ex=sql('id','tlex_ab','v',['usr'=>ses('uid'),'ab'=>$uid]);
if($ex)return 3;}

//0:private,1:clan-visible,2:usr-visible,3:net-visible
//output=0:no,1:net,2:clan,3:clan,4:owner
static function permission($uid,$pub){
if($uid==ses('uid'))return 4;//!
elseif($pub==1)return self::subscriptor($uid);
if((ses('uid') && $pub==2))return 2;
elseif($pub==3)return 1;
else return 0;}

static function perms($db,$id,$typ='pub'){//edt
if(!in_array($typ,self::$cols)){if($typ=='edt')return false;//default from app
	//visitors can publicated object of apps without col 'pub' following app's privacy 
	else{$a=self::$a; $b=$a::$private; $c=self::$app2dsk[$b]; return appx::$privacy[$c];}}
list($uid,$pub)=sql('uid,'.$typ,$db,'rw',$id); if(!$pub)$pub=0;//supplante pub
if($typ=='pub')$ret=appx::$privacy[$pub]; else $ret=appx::$privedt[$pub];
if(appx::permission($uid,$pub))return $ret;}//call appx means no abstraction

//works for pub and edt
static function mkpub($p){
if(isset($p['pub'])){$v=$p['pub']??0; sqlup(self::$db,'pub',$v,$p['id']??''); desktop::renove(self::$a);}
elseif(isset($p['edt'])){$v=$p['edt']??0; sqlup(self::$db,'edt',$v,$p['id']??'');}
return self::edit($p);}

static function privacy($p){$ret=''; $typ=$id=$p['typ']??'pub'; $edtlimit=$p['pub']??'';
$a=self::$a; $cb=self::$cb; $id=$p['id']??''; $rid=$p['rid']??''; //$pub=$p['pub']??'';
if($typ=='pub')$pub=sql('pub',self::$db,'v',$id); else $pub=sql('edt',self::$db,'v',$id);
if($typ=='pub')$r=appx::$privacy; else $r=appx::$privedt;
foreach($r as $k=>$v){$bt=$k==$pub?ico('check'):''; $c=$k==$pub?'active':'';
	//call app and not appx means use extends appx, to know the db
	//corréler niveau d'édition et de publication
	if(($typ=='edt' && $edtlimit>=$k) or $typ=='pub')
	if($v)$ret.=bj($cb.'|'.$a.',mkpub|opn=1,id='.$id.',rid='.$rid.','.$typ.'='.$k,langp($v).$bt,$c);}
return div($ret,'list');}

static function privedt($p){$ret=''; $r=appx::$privedt; //echo $a=self::$a;
$a=self::$a; $cb=self::$cb; $id=$p['id']??''; $rid=$p['rid']??''; //$pub=$p['pub']??'';
$pub=sql('edt',self::$db,'v',$id);
foreach($r as $k=>$v){$bt=$k==$pub?ico('check'):'';
	//call app and not appx means use extends appx, to know the db
	$ret.=bj($cb.'|'.$a.',mkpub|id='.$id.',rid='.$rid.',edt='.$k,lang($v).$bt);}
return div($ret,'list');}

//desktop
static function mkdsk($p){
$ex=$p['ex']??''; $com=$p['com']??''; $t=$p['tit']??''; $pub=$p['pub']??''; $del=$p['del']??'';
//
if($del)return tlxf::dskdel($p);
else{sqlup('desktop','auth',$pub,$ex); if(!$ex)return tlxf::dsksav($p);}
return desktop::content(['dir'=>'/documents/'.$com]);}

static function dsk($p){$ret='';
$a=self::$a; $id=$p['id']??''; $uid=$p['uid']??''; $t=$p['t']??''; $pub=$p['pub']??'';
//$pub=sql('pub',self::$db,'v',$id); 
$t=sql($t,self::$db,'v',$id); $r=appx::$privacy;
$ra=sql('id,auth','desktop','rw',['uid'=>$uid,'dir'=>'/documents/'.$a,'bt'=>$t]);
list($ex,$ath)=$ra?$ra:['',''];
$rb=self::$app2dsk;
//$bt=$ex?ico('check').lang('del2dsk'):;
$j='popup|'.$a.',mkdsk|ex='.$ex.',com='.$a.',p1='.$id.',tit='.$t.',pub=';
if($ex)$ret=bj($j.',del=1',langp('del2dsk'),'btdel');
foreach($rb as $k=>$v){
	if($k<4)$bt=($v==$ath?ico('check'):pic('add2dsk')).lang($r[$k]);
	if($pub>=$k)$ret.=bj($j.$v,$bt);}
return div($ret,'list');}

//subcall
static function subops($p){
$id=$p['id']??''; $idb=$p['idb']??''; $op=$p['op']??'';
$a=self::$a; $cb=self::$cb; $db2=self::$db2; $rb='';
if($op=='add'){$cols=sqlcls($db2,2);
	foreach($cols as $k=>$v){$idn=$p[$k]??'';
		if($k=='bid')$rc[$k]=$id; elseif($k=='uid')$rc[$k]=ses('uid');
		elseif($k=='idn')$rc[$k]=is_numeric($idn)?$idn+1:1; else $rc[$k]=$v=='int'?0:'';}//$p[$k]??'';
	$idb=sqlsav($db2,$rc);}
elseif($op=='imp'){$nm=$p['impdb']??''; $cols=sqlcls($db2,2); $r=explorer::read($nm);//explorer::fext($nm,1)
	if($r && isset($r['_'])){$ra=$r['_']; unset($r['_']);} else $ra=$cols;
	if($r)foreach($r as $k=>$v){$v=array_combine($ra,$v);
		foreach($cols as $ka=>$va){
			if($ka=='bid')$rb[$k]['bid']=$id; elseif($ka=='uid')$rb[$k]['uid']=ses('uid');
			elseif($ka=='idn')$rb[$k]['idn']=$k; elseif(isset($v[$ka]))$rb[$k][$ka]=$v[$ka];
			else $rb[$k][$ka]='';}} if($rb)ksort($rb);
	if($rb)sqlsav2($db2,$rb); return self::subcall($p);}
elseif($op=='sav'){$cols=sqlcls($db2,3,2); $r=valk($p,$cols); $r=trims($r);
	if($a::$conn==1 && isset($r['txt']))$r['txt']=conv::com($r['txt']); sqlups($db2,$r,$idb);}
elseif($op=='del'){
	if($p['ok']??'')sqldel($db2,$idb);
	else return bj($cb.$id.'sub,,x|'.$a.',subops|id='.$id.',idb='.$idb.',op=del,ok=1',langp('really?'),'btdel');
	return self::subcall($p);}
return $a::subedit(['id'=>$id,'idb'=>$idb]);}

static function subform($p){$ret=''; $a=self::$a;
$ret=hidden('bid',$p['bid']); array_shift($p);//not edit bid
$rc=sqlcls(self::$db2,3); $html=$p['html']??'';//!
foreach($rc as $k=>$v)if($k!='bid'){$val=$p[$k]??''; $bt=''; $lbl=lang($k);
	if($p['fc'.$k]??''){$f='fc_'.$k; $bt=$a::$f($k,$val,$v,$p['bid']);}
	elseif($k==$html)$bt=divarea($k,conn::mincom($val,1),'article');
	elseif($k=='txt' or $k=='txt2' or $v=='text')$bt=build::connbt($k).textarea($k,$val,64,22,$lbl);
	elseif($k=='bkg')$bt=inpclr($k,$val,'',1,1).$lbl;
	elseif($k=='img')$bt=inpimg($k,$val,'').$lbl;
	elseif($k=='clr')$bt=inpclr($k,$val,'').$lbl;
	else $bt=input($k,$val,63,$lbl,'',512).$lbl;
	$ret.=div($bt);}
return $ret;}

static function prevnext($id,$idb,$w){$ka=0;// order by idn
$r=sql('id',self::$db2,'rv','where bid="'.$id.'"'.$w); $a=''; $b='';
if($r)foreach($r as $k=>$v){$rb[$k]=$v; if($v==$idb)$ka=$k;}
if(isset($rb[$ka-1]))$a=$rb[$ka-1];
if(isset($rb[$ka+1]))$b=$rb[$ka+1];
return [$a,$b];}

static function subedit($p){
$id=$p['id']??''; $idb=$p['idb']??'';
$a=self::$a; $cb=self::$cb.$id; $cbb=$cb.'edit';
//$j=$p['data-jb']??''; if($j)$rj['data-jb']=$j;
$j='id='.$id.',idb='.$idb; $ret='';
$cls=sqlcls(self::$db2,3,2); $cols=implode(',',$cls);
if(in_array('idn',$cls))$w=' order by idn+0'; else $w=' order by id asc';
$r=sql($cols,self::$db2,'ra',$idb);
$j=$cb.'|'.$a.',subedit|id='.$id.',idb=';
$urf=$p['urf']??''; if($urf && in_array($urf,$cls))$uk=$r[$urf]; else $uk=$idb; $lk=$a.'/'.$id.'/'.$uk;
$ret=bjk($cb.'|'.$a.',edit|id='.$id,langp('back'),'btn',$a.'/'.$id,$a.'/'.$id);//cbb
list($prv,$nxt)=self::prevnext($id,$idb,$w);//prev-next
$ret.=bj($prv?$j.$prv:'',langp('previous'),'btn'.($prv?'':' grey'));
$ret.=bj($nxt?$j.$nxt:'',langp('next'),'btn'.($nxt?'':' grey'));
$ret.=bj($cb.',,z|'.$a.',subops|id='.$id.',op=add|'.$cols,langp('add'),'btn');
$ret.=bj(''.$cb.',,z,scrollBottom,txt|'.$a.',subops|id='.$id.',idb='.$idb.',op=sav|'.$cols,langp('save'),'btsav');
$ret.=bj('popup|'.$a.',subops|id='.$id.',idb='.$idb.',op=del',langp('delete'),'btdel');
$ret.=bj($j.$idb,'#'.$idb,'btn');
$ret.=lk('/'.$lk,ico('link'),'btn');
if($idb)$ret.=bj('popup|'.$a.',datasdb2|idb='.$idb,langpi('preview'),'btn');
//if($idb && auth(6))$ret.=bj('popup|'.$a.',datasdb2|epub=1,idb='.$idb,langpi('epub'),'btn');
if($bt=$p['bt']??'')$ret.=$bt;
$t=$p['t']??$cls[1];
$ra=sql('id,'.$t,self::$db2,'kv','where bid="'.$id.'"'.$w);
$ret.=select('slctsub'.$id,$ra,$idb,0,0,$j);
if(in_array('txt',$cls) && $a::$conn=1)
	$ret.=bj('popup|'.$a.',subeditconn|id='.$id.',idb='.$idb.',cbk=subedit',ico('edit'),'btn');
//$ret.=div(input($cls[0],$r[$cls[0]],63,lang($cls[0]),'',512));
$ret.=div($a::subform($r),'',$cb.'sub');
//$ret.=div($a::play(['id'=>$id,'idb'=>$idb]),'',$cb.'prw'); //need idn system for db2 apps
return $ret;}

//substream
static function subcall($p){$id=$p['id']??'';
$a=self::$a; $cb=self::$cb; $db=self::$db; $db2=self::$db2; $mode=$p['collect']??'';
$rc=sqlcls($db2,2); $cls=array_keys($rc); $cols=implode(',',$cls);
if(in_array('idn',$cls))$w=' order by idn+0';
elseif(in_array('twid',$cls))$w=' order by twid desc';
else $w=' order by id asc';
$r=sql('id,'.$cols,$db2,'rr','where bid="'.$id.'"'.$w);
$ret=tag('h3','',lang('attached datas'));
if($r)$n=count($r); else $n=0;
$ret.=span($n.' '.lang('objects',1),'btn');
if(!$mode){
	$ret.=bj($cb.'sub|'.$a.',subops|op=add,id='.$id,langp('add'),'btsav');
	$ret.=build::import_db(['a'=>$a,'cb'=>$cb,'id'=>$id]);}
if($bt=$p['bt']??'')$ret.=$bt;
$col=$p['t']??$cls[1];//used col
array_unshift($cls,'id'); $rb['_']=$cls;
if($r){$t=$p['t']??$cls[1]; if(in_array('idn',$cls))$w=' order by idn';
	$ra=sql('id,'.$t,self::$db2,'kv','where bid="'.$id.'"'.$w); $ra=['subarts']+$ra;;
	if(!$mode)$ret.=select('slctsub'.$id,$ra,'',0,0,$cb.$id.'|'.$a.',subedit|id='.$id.',idb=');
	if($b=$p['player']??'')$ret.=$a::$b($p,$r);
	//elseif(in_array('root',$cls))$ret.=root($r);
	else foreach($r as $k=>$v){$t=$v[$col]?$v[$col]:$v['id'];
		$lk=$a.'/'.$id.'/'.$v[$p['urf']??'id'];
		if(!$mode)$v['_edt']=bjk($cb.$id.'|'.$a.',subedit|id='.$id.',idb='.$v['id'],ico('edit'),'',$lk);
		$rb[$v['id']]=$v;}
	if($mode)$ret.=tabler($rb);
	else $ret.=div(build::editable($rb,'admin_sql',['b'=>$db2,'w'=>'bid','bid'=>$id],1),'','asl');}
return div($ret,'',$cb.'sub');}

static function submdftxt($p){
$id=$p['id']??''; $idb=$p['idb']??''; $a=self::$a;
$d=val($p,$p['rid']??''); $d=cleanconn($d);
sqlup(self::$db2,'txt',$d,$idb);
return $a::subedit(['id'=>$id,'idb'=>$idb]);}

static function subeditconn($p){$id=$p['id']??''; $idb=$p['idb']??'';
$txt=sql('txt',self::$db2,'v',$idb); $rid=randid('art'); $a=self::$a; $cb=self::$cb;
$ret=bj($cb.$id.',,x|'.self::$a.',submdftxt|id='.$id.',idb='.$idb.',rid='.$rid.'|'.$rid,langp('save'),'btsav');
$ret.=build::connbt($rid).textarea($rid,$txt,'64','22','','console');
return $ret;}

static function langs(){
$r=sql('distinct(lang)','voc','rv',''); if(!$r)$r=['fr','en','es','it','de']; return $r;}

static function lglist($p){$r=self::langs(); $ret='';
foreach($r as $k=>$v)$ret.=btj(lang($v),atj('val',[$v,$p['id']]));
return div($ret,'list');}

//form
static function formcom($p){$ret=''; $ty=$p['ty']??''; $answ=$p[$ty]??'';
if($answ){$rv=explode('|',$answ); $nb=count($rv); unset($p[$ty]);}
else $nb=$p['nb']??2;
for($i=1;$i<=$nb;$i++){$inp[]=$ty.$i; $j=atj('multhidden',[$nb,$ty]);
	if(isset($rv[$i-1]))$v=$rv[$i-1]; else $v=$p[$ty.$i]??'';
	$prm=['id'=>$ty.$i,'value'=>$v,'size'=>40,'onkeyup'=>$j,'onclick'=>$j,'onchange'=>$j];
	$prm['placeholder']=lang('option').' '.$i;
	$ret.=div(tag('input',$prm,'',1));}
$inps=implode(',',$inp); $_POST[$ty]=$inps;
if($nb<20)$ret.=bj('choices|appx,formcom|ty='.$ty.',nb='.($nb+1).'|'.$inps,langp('add option'),'btn');
if(!($p['nb']??''))$ret=div($ret,'','choices').hidden($ty,$answ);
return $ret;}

static function form($p){$a=self::$a; $cb=self::$cb; $ret=''; $sz='34';//63
$cols=sqlcls(self::$db,3); $id=$p['id']??''; $uid=$p['uid']??''; $html=$p['html']??'';
foreach($cols as $k=>$v){$val=$p[$k]??''; $bt=''; $wsg=$p['wsg']??''; $lbl=lang($p['label'.$k]??$k);
	if($p['fc'.$k]??''){$f='fc_'.$k; $bt=$a::$f($k,$val,$v,$id,$lbl);}
	elseif($k==$html)$bt=divarea($k,conn::com($val,1),'article',1);
	elseif($k=='txt'){
		if($a::$conn)$wsg=build::connbt($k,1);
		if($a::$gen)$wsg=build::genbt($k,1);
		$h=strlen($val)>1000||substr_count($val,"\n")>10?26:8;
		$bt=$wsg.textarea($k,$val,80,$h,$lbl,'',$v=='bvar'?1000:'');}
	//elseif($k=='pub')$bt=self::pub($k,$val,$uid);
	elseif($k=='pub' or $k=='edt')$ret.=hidden($k,$val);
	elseif($k=='hid')$ret.=hidden($k,$val).span($val,'nfo');
	elseif($k=='com')$bt=self::formcom($p+['ty'=>$k]);
	elseif($k=='cl')$bt=build::toggle(['id'=>$k,'v'=>$val,'yes'=>'closed','no'=>'opened']);
	elseif($k=='nb')$bt=bar($k,$val,1,1,10,'',$p['barfunc']??'','inp').$lbl;
	elseif($k=='nb1')$bt=bar($k,$val,1,1,100,'',$p['barfunc']??'','inp').$lbl;
	elseif($k=='bkg')$bt=inpclr($k,$val,'',1);
	elseif($k=='clr')$bt=inpclr($k,$val,'');
	elseif($k=='img')$bt=inpimg($k,$val,'');
	elseif($k=='code')$bt=$wsg.textarea($k,$val,80,26,$lbl,'console');
	elseif($k=='lang'){$bt=input($k,$val,'8',$lbl,'2');
		$bt.=bubble($a.',lglist|id='.$k,pic('flag'),'',1);}
	elseif($k=='md5')$bt=input($k,$val?$val:random(),8,$lbl,'',10);
	elseif($v=='svar')$bt=input($k,$val,$sz,$lbl,'',50);
	elseif($v=='bvar')$bt=input($k,$val,$sz,$lbl,'',1000);
	elseif($v=='var')$bt=input($k,$val,$sz,$lbl,'',255);
	elseif($v=='text')$bt=textarea($k,$val,80,26,$lbl);
	elseif($v=='date')$bt=input($k,$val && $val!='0000-00-00'?$val:date('Y-m-d',time()),8,$lbl);
	elseif($v=='bint')$bt=input($k,$val,'32',$lbl);
	elseif($v=='double')$bt=input($k,$val,'16',$lbl,1);
	elseif($v=='int')$bt=input($k,$val,'8',$lbl,1);
	else $bt=input($k,$val,'8',$lbl);
	//extras
	if($p['exec'.$k]??'')$bt.=popup('exec,edit|rid='.$k.',ind='.$a.$id,langp('exec'),'btn');
	if($btb=$p['bt'.$k]??'')$bt.=$btb;
	if($bt)$ret.=div($bt);}
if($p['sub']??'')$ret.=hr().div($a::subcall($p),'',$cb.$id.'sub');
return $ret;}

//admin	
static function create($p){
$id=$p['id']??''; $rid=$p['rid']??'';
$a=self::$a; $cb=self::$cb; $cls=implode(',',self::$cols);
$ret=bj($cb.'|'.$a.',stream|rid='.$rid,pic('back'),'btn');
$ret.=bj($cb.'|'.$a.',save|rid='.$rid.'|'.$cls,lang('save'),'btsav');
if($p['help']??'')$ret.=hlpbt($a.'_help');
$ret.=$a::form($p).br();
return div($ret,'',$cb);}

static function edit($p){
$id=$p['id']??$p['edit']??''; $rid=$p['rid']??''; $opn=$p['opn']??''; 
$db2=$p['collect']??''; $uid=ses('uid'); $own=0; $sav=''; $qb=self::$qb;
$a=self::$a; $cb=self::$cb; $db=self::$db; $cls=implode(',',self::$cols);
$t=$p['t']??self::$cols[0]; $cc=$rid?$rid:$cb.$id.'edit'; //$cd=$cb.$id;
$r=sql('id,uid,'.$cls,$db,'ra',$id); if(!$r)return; //pr($r); return;
$pub=$p['pub']??''; $edt=$p['edt']??0; $r['sub']=$p['sub']??'';
//if($r['edt']==null)pr(self::$cols);
$readable=self::permission($r['uid'],$pub);
$editable=self::permission($r['uid'],$edt);
//$readable=self::perms($db,$id,$pub,'pub');//more secure
if($r['uid']==$uid)$own=1;// or auth(6)
$ret=bjk($cc.'|'.$a.',stream|rid='.$rid,pic('back'),'btn',$a);
if(substr($rid,0,3)=='edt')$ret=bj($cb.$id.',,x|'.$a.',call|id='.$id,pic('ok'),'btsav');
elseif($rid)$ret.=tlxf::publishbt(lang('use'),$id.':'.$a.'',$rid);
elseif($opn)$ret.=bj('cbck|tlex,save|ids='.$id.',a='.$a,langp('publish'),'btsav');
else $ret.=bj('cbck|tlex,save|ids='.$id.',a='.$a,langp('publish'),'btsav');
if($editable)$ret.=bj($cc.',,1|'.$a.',edit|opn=1,headers=1,id='.$id.',rid='.$rid,langp('view'),'btn'.($opn?' active':''));
if($own or $editable){$r['own']=1;
	$ret.=bj($cc.'|'.$a.',edit|id='.$id.',rid='.$rid,langp('edit'),'btn'.($opn?'':' active'));
	if($p['help']??'')$ret.=hlpbt($a.'_edit');//$help
	if(isset($p['json']))$cls.=','.implode(',',$p['json']);//add json cols, need special treat in modif()
	$sav=bj($cc.'|'.$a.',modif|id='.$id.',rid='.$rid.'|'.$cls,langp('save'),'btsav').br();}
if($own){
	if(isset($r['pub'])){$bt=langp(appx::$privacy[$r['pub']]);
		$ret.=bubble($a.',privacy|id='.$id.',rid='.$rid,$bt,'btn');}
	if(isset($r['edt'])){$bt=langp(appx::$privedt[$r['edt']]);
		$ret.=bubble($a.',privacy|typ=edt,id='.$id.',rid='.$rid.',pub='.$r['pub'],$bt,'btn');}
	//$ret.=bubble($a.',dsk|id='.$id.',uid='.$uid.',t='.$t.',pub='.$pub,langp('desktop'),'btn');//pub?
	if($db2)$ret.=bj($cb.$id.'|'.$a.',collect|id='.$id.',db='.$db2.',cb='.$cb.$id,langpi('datas'),'btn');
	if($qb)$ret.=$qb::bt('usr/'.usrid($r['uid']).'/'.$a.'/'.$id);
	$ret.=bj($cb.$id.'|'.$a.',del|id='.$id.',rid='.$rid,langpi('delete'),'btdel');}
else $ret.=span(lang('by').' '.usrid($r['uid']),'btn');
$ret.=bj('popup|core,com|app='.$a.',id='.$id,pic('api'),'btn');
if($p['lang']??'' && $md5=$p['md5']??'')$ret.=lk('/'.$a.'/'.$md5,'#'.$id,'btn');
elseif(in_array('tit',self::$cols))$ret.=lk('/'.$a.'/'.$id,'#'.$id,'btn');
else $ret.=lk('/'.$a.'/'.$id,pic('id').$id,'btn');
if($a::$tags??'')$ret.=admin_tags::call(['id'=>$id,'a'=>$a,'lg'=>lng(),'edt'=>$edt]);
if($bt=$p['bt']??'')$ret.=$bt;
if($opn)$ret.=div($a::play($p),'',$cb.$id);
elseif($own or $editable)$ret.=div($sav.$a::form($r),'',$cb.$id);
else $ret.=$a::play($p);//arrived there by krack
return div($ret,'',$cc);}

#collected datas
static function delb($p){
if(auth(4))sqldel($p['db'],$p['idb']);
return self::collect($p);}

static function collect($p){$id=$p['id'];
return admin_sql::call(['b'=>self::$db2,'id'=>$id,'w'=>'bid']);}

#stream
static function stream_tags($p){
$a=$p['a']??''; $cb=$p['cb']??''; $dsp=$p['dsp']??''; $tri=$p['tri']??'';
$r=admin_tags::taglist($a,$tri); $ret='';
if($r)foreach($r as $k=>$v)if($v[1])
	$ret.=bj($a.'stream|admin_tags,searchapp|dsp='.$dsp.',a='.$a.',bid='.$v[0],$v[1].' ('.$v[2].')','').' ';
return span($ret,'lisb');}

static function appmenu($a,$cb,$rid,$pub,$dsp,$spd,$r){$ret=''; $bt=''; $tri='';
$ret=bjk('page|home|',langpi('home'),'btn','/');
if($dsp==2)$ret.=bj($cb.'|'.$a.',stream|display=1,rid='.$rid,langpi('icons'),'btn');
else $ret.=bj($cb.'|'.$a.',stream|display=2,rid='.$rid,langpi('list'),'btn');
if($pub){/**/
	if($spd==2)$ret.=bj($cb.'|'.$a.',stream|spread=1,tru=1,rid='.$rid,langp('dsk_private'),'btn');
	else $ret.=bj($cb.'|'.$a.',stream|spread=2,tri=x,rid='.$rid,langp('dskpublic'),'btn');}
if($pub){
	if($spd==2){$tri=ses('apptri');
		//0:private,1:clan-visible,2:usr-visible,3:net-visible
		$ra=[1=>'closed','clan','logeds','opened'];//$r=appx::$privacy;
		//if($r && ses('uid'))foreach($r as $k=>$v)$rb[$v['pub']]=$ra[$v['pub']+1];
		if(isset($ra))foreach($ra as $k=>$v){$c=$tri==$k?'active':'';
			$bt.=bj($cb.'|'.$a.',stream|spread=2,tri='.($tri==$k?'x':$k).',rid='.$rid,lang($v),$c);}}
	else{$tru=ses('apptru');
		if($r)foreach($r as $k=>$v)$rc[$v['name']]=1; //pr($rc);
		if($r)foreach($rc as $k=>$v){$c=$tru==$k?'active':'';
			$bt.=bj($cb.'|'.$a.',stream|spread=1,tru='.($c?1:$k).',rid='.$rid,$k,$c);}}}
//tags
if($a::$tags??'')$bt.=bj($a.'tags|'.$a.',stream_tags|dsp='.$dsp.',tri='.$tri.',a='.$a.',cb='.$cb,langp('tags'),'');
if($bt)$ret.=span($bt,'tabs');
$ret.=div('','',$a.'tags');
//$ret.=toggle($a.'tags|'.$a.',stream_tags|a='.$a.',cb='.$cb,langp('tags'),'btn').div('','',$a.'tags');
return $ret;}

static function stream_r($p,$t,$pub,$edt,$spd,$lng,$cv){$ret=''; $w='';
$a=self::$a; $db=self::$db; $uid=ses('uid');
$tri=ses('apptri',$p['tri']??''); $tru=ses('apptru',$p['tru']??'');
$cls='id,uid,'; if($cv)$cls.=implode(',',self::$cols);//sqlcls($db,3,1); 
else $cls.=$t;
if($pub)$cls.=',pub'; if($edt)$cls.=',edt';
if($spd==2){$w='where uid="'.($uid?$uid:'').'" ';
	if($tri && $tri!='x' && $pub)$w.=' and pub="'.($tri-1).'" ';}
else{$w='where uid!="'.($uid?$uid:'').'" ';//need nicer req, slct arts by auth
	if($pub){if(!$uid)$w.='and pub=3 '; else $w.='and (pub>1 or (pub=1 and (select usr from tlex_ab where usr="'.$uid.'" and ab=uid)))';}
	if($tru && $tru!=1)$w='where name="'.$tru.'"';}
if($lng)$w.=' and lang="'.ses('lng').'"';
$w.=' order by uid asc, id desc limit 1000';
$r=sqlin($db.'.'.$cls.',name,dateup',$db,'login','uid','rr',$w,0);
return $r;}

//0:private,1:clan-visible,2:usr-visible,3:net-visible
static function stream_build($p){$rid=$p['rid']??''; $ret=[]; $w='';
$a=self::$a; $cb=self::$cb; $cols=self::$cols;
$t=$p['t']??$cols[0]; $uid=ses('uid');
$dsp=ses('appdsp',$p['display']??''); if(!$dsp)$dsp=ses('appdsp',2);
$spd=ses('appspd',$p['spread']??''); if(!$spd)$spd=ses('appspd',2); if(!$uid)$spd=ses('appspd',1);
$rb['c']=$dsp==1?'licon':'bicon'; $cv=$p['cover']??'';
$pbb=in_array('pub',$cols)?1:0;
$edd=in_array('edt',$cols)?1:0;
$lng=in_array('lang',$cols)?1:0;
$r=self::stream_r($p,$t,$pbb,$edd,$spd,$lng,$cv);
$rb['head']=div(self::appmenu($a,$cb,$rid,$pbb,$dsp,$spd,$r),'');
if($r)foreach($r as $k=>$v){$id=$v['id']; $btn=''; $ttl=''; $ico=''; $com='call';//open in editor
	$tit=$v[$t]?$v[$t]:'#'.$v['id'];
	$pub=$v['pub']??0; $edt=$v['edt']??'';
	$readable=self::permission($v['uid'],$pub);
	$editable=self::permission($v['uid'],$edt);
	if($v['uid']==$uid)$ic='file-o'; elseif($editable)$ic='file-text-o'; else $ic='file';
	if(is_img($v[$t]))$ico=img2($v[$t],'micro');
	//if($pbb){if($v['uid']==$uid)$com='edit'; elseif($editable && $uid)$com='edit';} else $com='edit';
	if($readable){
		if(!$ico)$ico=ico($ic);
		if($dsp==2 && !$uid)$cb='pagup';
		$com=$editable?'edit':'call'; $opn=$editable?1:0; //$com='call';
		$j=$cb.'|'.$a.','.$com.'|headers=1,id='.$v['id'].',rid='.$rid.',opn='.$opn;
		$pop=bj('popup,,y|'.$a.',call|headers=1,id='.$v['id'],pic('popup',12)); if($dsp==2)$pop='';
		if($dsp==1){$btn.=div('#'.$v['id'],'cell').div(bjk($j,$tit,'',$a.'/'.$id),'cell ltit');
			$btn.=div($v['date'],'cell date');
			$btn.=div($v['name'],'cell small');//,'','',['title'=>$ttl]//lang('by').' '.
			if($pbb && $readable)$btn.=div(langpi(appx::$privacy[$pub],14),'cell');
			if(($v['uid']==$uid && $edt>0) or ($spd==1 && $edd && $editable))
				$btn.=div(langpi(appx::$privedt[$v['edt']],14),'cell');}
		else $btn=$tit;
		//$ret[$id]=bj($j,$ico.$btn).$pop;
		$ret[$id]=['j'=>$j,'ic'=>$ic,'bt'=>$btn,'op'=>$pop,'id'=>$id];//,'r'=>$v
		if($cv)$ret[$id]+=$v;}}
//if(!$ret)$ret['null']=$rb['head'].help('no element','txt');
return [$ret,$rb];}

static function listing($id,$v=[]){return span($v['bt'],'licon');}
static function cover($id,$v=[]){return span($v['bt'],'bicon');}

static function stream($p){
$cv=$p['cover']??''; $ls=$p['listing']??'';
[$r,$rb]=self::stream_build($p); $ret='';
$a=self::$a; $dsp=ses('appdsp');
if(isset($r['null']))return $r['null'];
if($r)foreach($r as $k=>$v){//if(isset($v['j']))
	if($dsp==2 && $cv)$ret.=bjk($v['j'],$a::cover($k,$v),'',$a.'/'.$v['id']);
	elseif($dsp==1 && $ls)$ret.=bjk($v['j'],$a::listing($k,$v),'',$a.'/'.$v['id']);
	elseif($dsp==1)$ret.=div($v['bt'],'row');
	else $ret.=span(bjk($v['j'],ico($v['ic']).$v['bt'].$v['op'],'',$a.'/'.$v['id']),$rb['c']);}//
	//$ret.=$v['ico'].' '.$v['id'].' '.$v['tit'].' '.bj($v['j'],pic('see'));
if(!$ret)return $rb['head'].help('no element','txt');
if($dsp==2 && $cv)$ret.=div('','clear');
if($dsp==1 && !$ls)$ret=div($ret,'table');
return div($rb['head'].div($ret,'',self::$cb.'stream'),'');}

#build
static function build($p){$id=$p['id']??'';
$a=self::$a; $cols=implode(',',self::$cols); //$cols=sqlcls($db,2);
$r=sql('id,uid,'.$cols,self::$db,'ra',$id);
//tlex will use $conn; this var is sent by tlex::reader
if(isset($r['txt']) && $a::$conn)$r['txt']=conn::com($r['txt'],1);
return $r;}

static function build2($p){$id=$p['id']??'';
$db2=self::$db2; $cols=sqlcls($db2,2);
return sql($cols,$db2,'rr',['bid'=>$id]);}

static function datasdb2($p){
$r=sql('all',self::$db2,'ra',$p['idb']);
return tabler($r);}

#play
static function template(){
//return div(div('[tit:var]','tit').div('[txt:var]','txt'),'article');
//return '[[{tit}*class=tit:div][{txt}*class=txt:div]*class=article:div]';
return '[[[tit:var]*[tit:class]:div][[txt:conn]*[txt:class],[cbck:id]:div]*[article:class]:div]';}

static function play($p){$ret='';
$r=self::build($p); $a=self::$a;
$ret=gen::com($a::template(),$r);//gen by default
//if($qb=self::$qb && auth(6))$ret.=$qb::bt('datas/'.$a.$id);
return $ret;}

static function preview($p){
$id=$p['id']??''; $a=self::$a; $ret=''; $txt=''; $dots=''; $max=440;
$r=sql('all',self::$db,'ra',$id); //pr($r);
$t=$p['t']??($r['tit']??($r[self::$cols[0]]??''));
$bt=pagup($a.',call|id='.$id,span(pic($a,32).' '.$t),'apptit');
//$bt2=pagup($a.',call|id='.$id,lang('read more'),'bold');
//$t.=lk('/'.$a.'/'.$id,pic('url'),'btxt');
if(!empty($r['txt'])){
	$txt=conn::call(['msg'=>$r['txt'],'app'=>'conn','mth'=>'noconn','ptag'=>$p['ptag']??'no']);//
	$nb=strlen($txt); if($nb>$max)$nb=strpos($txt,'.',$max);
	if($nb>$max){$nb=strpos($txt,' ',$max); $dots='...';}
	$txt=substr($txt,0,$nb).$dots;}//.$p['opt']
if(!empty($r['img']))$txt=playimg($r['img'],'mini','','').$txt;
$ret=div($bt,'appicon').div($txt,'message').div('','clear');
return div($ret,'');}

#interfaces
//title (used by desktop and shares)
static function tit($p){
$id=$p['id']??''; $a=$p['a']??self::$a;
if(!isnum($id))$id=self::idmd5($id);
$t=$p['t']??$a::$cols[0];
if($id)return sql($t,$a::$db,'v',$id);}

static function usrnfo($n,$d){
$date=span(date('Y-m-d',strtotime($d)),'small');
return small($n).' '.span($date,'date');}//lang('by').' '.

static function idsuj($d){return sql('id',self::$db,'v',['tit'=>$d]);}
static function idlng($d){return sql('id',self::$db,'v',['md5'=>$d,'lang'=>ses('lng')]);}
static function idmd5($d){return sql('id',self::$db,'v','where substring(md5(id),8,7)="'.$d.'"');}
static function uid($id){return sql('uid',self::$db,'v',['id'=>$id]);}
static function usr($id){return sqlin('name',self::$db,'login','uid','v',$id);}
static function own($id){if(self::uid($id)==ses('uid'))return true;}

static function getid($id){
if(is_numeric($id))return sql('id',self::$db,'v',['id'=>$id]);
elseif(in_array('md5',self::$cols) && in_array('lang',self::$cols))return self::idlng($id);
else return self::idmd5($id);}

static function apphead($uid,$id,$a,$readable,$cb){
//$nm=usrid($uid); $bt=lk('/@'.$nm,'@'.$nm,'btn');
//if($readable)$nm.=span(langp($readable),'grey');
//$ref=self::tit(['id'=>$id]); if(strlen($ref)>12)$ref=$id; else $ref=urlencode($ref);
$bt=lk('/'.$a.'/'.$id,ico('link'),'btn');//substr(md5($id),7,7)
//$editable=self::perms(self::$db,$id,'edt');
//if($editable)$bt.=bj($cb.$id.'|'.$a.',edit|edit=1,id='.$id,ico('edit'),'btn');//substr(md5($id),7,7)
if($uid!=ses('uid') && ses('uid'))$bt.=popup('tlxf,dsksav|com='.$a.',p1='.$id.',tit='.$a::tit(['id'=>$id]),langp('keep'),'btn');
return div($bt,'');}

//call
static function call($p){
$a=self::$a; $cb=self::$cb; $id0=$p['id']??($p['p1']??''); $opn=$p['opn']??''; $bt='';
$id=self::getid($id0); $p['id']=$id; $readable='';
$uid=self::uid($id);
if($id)$readable=self::perms(self::$db,$id,'pub');
//if($id)$bt=self::apphead($uid,$id,$a,$readable,$cb);
if($id0 && !$id)$ret=help('id not exists','board');// or !$uid
elseif($id && !$readable)$ret=help('access not granted','board');
elseif($opn)$ret=div(self::edit($p),'',$cb.'edt');
else $ret=$a::play($p);
return div($ret.$bt,'',$cb.$id);}

//com (write)
static function com($p){$rid=$p['rid']??''; $a=self::$a; $ret='';//rid (will focus on tlex editor)
if(method_exists($a,'admin'))$ret=menu::call(['app'=>$a,'mth'=>'admin','rid'=>$rid]);
$ret.=$a::content($p);
return $ret;}

static function iframe($p){
$id=$p['p1']??''; $a=self::$a; $cb=self::$cb;
$id=self::getid($id); $id=$p['id']??$id; $p['id']=$id;
if(method_exists($a,'headers'))$a::headers();
$readable=self::perms(self::$db,$id,'pub');
//$ret=app('admin',['app'=>$a,'id'=>$id]);
if($readable)$ret=div($a::play($p),'',$cb.$id);
else $ret=help('access not granted','board');
add_head('csslink','/css/global.css');
add_head('csslink','/css/apps.css');
add_head('csslink','/css/pictos.css');
add_head('csslink','/css/fa.css');
add_head('jslink','/js/ajax.js');
add_head('jslink','/js/utils.js');
$ret=div($ret,'board',$cb);
$ret=tag('body',['onmousemove'=>'popslide(event)','onmouseup'=>'closebub(event)'],$ret);
$ret.=tag('div',['id'=>'closebub','onclick'=>'bubClose()'],'');
$ret.=tag('div',['id'=>'popup'],'');
return generate().$ret;}

//interface
static function content($p){$ret='';
$a=self::$a; $cb=self::$cb;
$ida=$p['p1']??'';
if($ida=='install')$a::install();
$ida=$p['id']??$ida;
$p['id']=self::getid($ida);
$own=self::own($p['id']);
if($p['edit']??'')$ret=$a::edit($p);
elseif($p['id'] && $own && empty($p['o'])){$p['opn']=1; $ret=$a::edit($p);}//p2??
elseif($p['add']??'')$ret=$a::create($p);
elseif($p['id'])$ret=$a::call($p);
elseif($a)$ret=$a::stream($p);
return div($ret,'container board',$cb);}

//api
static function api($p){
$r=self::build($p);
if($r)return json_enc($r,true);}
}
?>