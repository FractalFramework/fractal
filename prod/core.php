<?php

class core{
static function injectJs($p){}
static function help($p){[$ref,$c,$cn,$b]=vals($p,['ref','css','conn','brut']);
return help($ref,$c,$cn,$b);}
static function val($p){return val($p,'p1');}
static function send($p){return val($p,$p['v']);}
static function mkbcp($p){return bktable($p['b'],$p['o']??'');}
static function rsbcp($p){return rbtable($p['b']);}
static function lang_set($p){return lang_set($p['lang']??'');}
static function boxhide($p){return boxhide($p);}
static function clrpick($p){return clrpick($p);}
static function clrpickset($p){return clrpickset($p);}
static function img($p){return img('/'.val($p,'f'),val($p,'w'));}
static function txt($p){return div(val($p,'txt'),val($p,'c'));}
static function voc($p){return voc(val($p,'txt'),val($p,'ref'));}
static function app($p){return app(val($p,'app'),val($p,'p'));}
static function com($p){return com($p['app'],$p['id']);}
static function ifr($p){return ifr(val($p,'app'),val($p,'id'));}
static function web($p){return web::play(http($p['u']??''));}
static function audio($p){return audio(http($p['u']??''));}
static function video($p){return video::com(http($p['u']??''));}
static function rplay($p){return play_r(json_decode($p['rj']??'',true));}
static function clean_mail($p){if($x=val($p,'x'))return clean_mail(val($p,$x));}}

class mem{static $r=[]; static $ret='';}

#sql
function connect(){require(ses('connect')); ses('dbq',$dbq);}
function qr($sql,$z=''){if($z==1)echo $sql; $rq=mysqli_query(ses('dbq'),$sql);
if($rq==null)echo mysqli_error(ses('dbq')).br().$sql.hr(); return $rq;}
function qfar($r){if($r)return mysqli_fetch_array($r);}
function qfas($r){if($r)return mysqli_fetch_assoc($r);}
function qfrw($r){if($r)return mysqli_fetch_row($r);}
function query($sql,$p,$z=''){if($z==1)echo $sql; $rq=qr($sql,$z);
if($rq){$rt=sqlformat($rq,$p); mysqli_free_result($rq); return $rt;}}
function escape($v){return mysqli_real_escape_string(ses('dbq'),stripslashes($v));}

function sqlformat($rq,$p){$rt=[];
if($p=='rq')return $rq;
if($p=='ry')return qfar($rq);
if($p=='ra')return qfas($rq);
if($p=='rw')return qfrw($rq);
if($p=='v'){$r=qfrw($rq); return $r[0];}
if($p=='rr'){while($r=mysqli_fetch_assoc($rq))$rt[]=$r; return $rt;}
if($p=='phy'){while($r=mysqli_fetch_assoc($rq))foreach($r as $k=>$v)$rt[$k][]=$v; return $rt;}
if($p=='rid'){while($r=mysqli_fetch_assoc($rq)){$k=array_shift($r); $rt[$k]=$r;} return $rt;}
while($r=mysqli_fetch_row($rq))if($r[0])switch($p){
	case('k'):$rt[$r[0]]=1; break;
	case('rv'):$rt[]=$r[0]; break;
	case('kv'):$rt[$r[0]]=$r[1]; break;
	case('kr'):$rt[$r[0]][]=$r[1]; break;
	case('kk'):$rt[$r[0]][$r[1]]=1; break;
	case('vv'):$rt[]=[$r[0],$r[1]]; break;
	case('kkv'):$rt[$r[0]][$r[1]]=$r[2]; break;
	case('kkr'):$rt[$r[0]][$r[1]][]=$r[2]; break;
	case('kkk'):$rt[$r[0]][$r[1]][$r[2]]=1; break;
	case('kvv'):$rt[$r[0]]=[$r[1],$r[2]]; break;
	case('id'):$k=array_shift($r); $rt[$k]=$r; break;
	case('kad'):if(isset($rt[$r[0]]))$rt[$r[0]]+=1; else $rt[$r[0]]=1; break;
	default:$rt[]=$r; break;}
return $rt;}

function rqcols($d,$b){
$r=explode(',',$d); $d='';
foreach($r as $k=>$v){
	if($v=='timeup')$r[$k]='unix_timestamp('.$b.'.up) as time';
	elseif($v=='dateup')$r[$k]='date_format('.$b.'.up,"%d/%m/%Y") as date';
	elseif($v=='numsec')$r[$k]='date_format('.$b.'.up,"%y%m%d.%H%i%s") as date';
	elseif($v=='numday')$r[$k]='date_format('.$b.'.up,"%y%m%d") as date';}
$d=implode(',',$r);
if($d=='all')$d=sqlcls($b,2,1);
if($d=='full')$d=sqlcls($b,1,1);
elseif($d=='used')$d=sqlcls($b,3,1);
if(!$d)$d='*';
return $d;}

function where($r,$o=''){
$rb=[]; $rc=[]; $w=''; $ret='';
foreach($r as $k=>$v)
	if($k=='_order')$w.=' order by '.$v;
	elseif($k=='_group')$w.=' group by '.$v;
	elseif($k=='_limit')$w.=' limit '.$v;
	elseif($k=='or')$rc=where($v,1);//'or'=>['!status'=>'3','!typ'=>'0']
	elseif(substr($k,0,1)=='|')$rc[]=substr($k,2).'="'.escape($v).'"';//or
	elseif(substr($k,0,1)=='!')$rb[]=substr($k,1).'!="'.escape($v).'"';
	elseif(substr($k,0,2)=='>=')$rb[]=substr($k,2).'>="'.escape($v).'"';
	elseif(substr($k,0,2)=='<=')$rb[]=substr($k,2).'<="'.escape($v).'"';
	elseif(substr($k,0,1)=='>')$rb[]=substr($k,1).'>"'.escape($v).'"';
	elseif(substr($k,0,1)=='<')$rb[]=substr($k,1).'<"'.escape($v).'"';
	elseif(substr($k,0,1)=='%')$rb[]=substr($k,1).' like "%'.escape($v).'%"';
	elseif($k=='numday')$rb[]='date_format(up,"%y%m%d")="'.$v.'"';
	elseif(is_array($v))$rb[]=$k.' between "'.$v[0].'" and "'.$v[1].'"';
	elseif($v==='not null')$rb[]=$k.' is not null';
	elseif($v==='is null')$rb[]=$k.' is null';
	else $rb[]=$k.'="'.escape($v).'"';
if($o)return $rb;
if($rc)$rb[]='('.implode(' or ',$rc).')';
if($rb)$ret=implode(' and ',$rb);
if($ret)return 'where '.$ret.$w;}

function setq($q,$b){
if(is_numeric($q))$q=[$b.'.id'=>$q];
if(is_array($q))return where($q);
else return $q;}

function escr($r){$rb=[];
foreach($r as $k=>$v)$rb[]=$v=='null'?$k.'=NULL':$k.'="'.escape($v).'"';
return $rb;}

function array2insert($r,$b='',$o='',$vd=''){
if($vd)$r=validcols($r,$b);//
foreach($r as $k=>$v){
	if(substr($v,0,8)=='PASSWORD')$rb[$k]=$v;
	elseif($v==='null')$rb[$k]='NULL';
	elseif($v===0)$rb[$k]=0;
	else $rb[$k]='"'.escape($v).'"';}
if($o==5)return '('.implode(',',$rb).')';
elseif($o)return '(NULL,'.implode(',',$rb).')';
else return '(NULL,'.implode(',',$rb).',"'.date('Y-m-d H:i:s',time()).'")';}

function array2insert2($r,$b='',$o='',$vd=''){//[[1,'hello'],[2,hey]]
foreach($r as $k=>$v)$rb[]=array2insert($v,$b,$o,$vd);
return implode(',',$rb);}

//sql('id','qda','rv',['id'=>$id]);
function sql($d,$b,$p='',$q='',$z=''){
$d=rqcols($d,$b); $q=setq($q,$b); $ret=[]; if($p=='v')$ret='';
if($b)$rq=qr('select '.$d.' from '.$b.' '.$q,$z);
if(!empty($rq->num_rows)){$ret=sqlformat($rq,$p); mysqli_free_result($rq);}
return $ret;}

//join b2 to b1, associating b2.$key to b1.id
function sqlin($d,$b1,$b2,$key,$p,$q='',$z=''){//b2 is on the right, let left empty
$q='inner join '.$b2.' on '.$b1.'.'.$key.'='.$b2.'.id '.setq($q,$b1);
return sql($d,$b1,$p,$q,$z);}

function sqljoin($d,$b1,$b2,$key,$p,$q='',$z=''){
$q='left join '.$b2.' on '.$b1.'.'.$key.'='.$b2.'.id '.setq($q,$b1);
return sql($d,$b1,$p,$q,$z);}

//[[$b1,$k1,$b2,$k2],[$b1,$k1,$b3,$k3]]
function sqlrin($d,$r,$p,$q='',$z=''){$w=''; $b='';
foreach($r as $k=>$v){$w.='join '.$v[2].' on '.$v[0].'.'.$v[1].'='.$v[2].'.'.$v[3].' '; if(!$b)$b=$v[0];}
return sql($d,$b,$p,$w.setq($q,$b),$z);}

function sqlrout($d,$r,$p,$q='',$z=''){$w=''; $b='';
foreach($r as $k=>$v){$w.='left outer join '.$v[2].' on '.$v[0].'.'.$v[1].'='.$v[2].'.'.$v[3].' '; if(!$b)$b=$v[0];}
return sql($d,$b,$p,$w.setq($q,$b),$z);}

function sqlsav2($b,$r,$o='',$x='',$z='',$vd=''){
if(auth(6) && $x){bktable($b); trunc($b);}
$sql='insert into '.$b.' values '.array2insert2($r,$b,$o,$vd);
$rq=qr($sql,$z); return mysqli_insert_id(ses('dbq'));}

function sqlsav($b,$r,$z='',$o='',$vd=''){
$sql='insert into '.$b.' values '.array2insert($r,$b,$o,$vd);
$rq=qr($sql,$z); return mysqli_insert_id(ses('dbq'));}
function sqlup($b,$d,$v,$q,$col='',$z=''){if($col)$q=[$col=>$q];
qr('update '.$b.' set '.$d.'='.($v=='null'?$v:'"'.escape($v).'"').' '.setq($q,$b),$z);}
function sqlups($b,$r,$q,$col='',$z=''){if($col)$q=[$col=>$q]; $rb=escr($r);
qr('update '.$b.' set '.implode(',',$rb).' '.setq($q,$b),$z);}
function sqlsavif($b,$r,$z=''){$ex=sql('id',$b,'v',$r);
if(!$ex)$ex=sqlsav($b,$r); return $ex;}
function sqlsavup($b,$r,$rb=[],$z=''){$ex=sql('id',$b,'v',$rb);
if(!$ex)return sqlsav($b,$r+$rb,$z); else return sqlups($b,$r,$ex,'',$z);}
function sqldel($b,$q,$col=''){if($col)$q=[$col=>$q];
qr('delete from '.$b.' '.setq($q,$b));}
function sqlclose(){mysqli_close(ses('dbq'));}

#sql_maintenance
function reflush($b,$o=''){
qr('alter table '.$b.' order by id');}
function reflush_ai($b){$id=lastid($b)+1;
qr('alter table '.$b.' auto_increment='.$id.'');}
function lastid($b){return sql('id',$b,'v','order by id desc limit 1');}
function trunc($b){qr('truncate '.$b);}
function drop($b){qr('drop table '.$b);}
function rntable($b,$bb){qr('rename table '.$b.' to '.$bb.';');}
function cptable($b,$bb){
qr('create table '.$bb.' like '.$b);
qr('insert into '.$bb.' select * from '.$b); return $bb;}
function bktable($b,$d=''){$bb='z_'.$b.'_'.$d;
if(sqlex($bb))drop($bb);
qr('create table '.$bb.' like '.$b);
//qr('alter table '.$bb.' add primary key (id)');
qr('insert into '.$bb.' select * from '.$b);
return $bb;}
function rbtable($b,$d=''){$bb='z_'.$b.'_'.$d;
if(!sqlex($bb))return; trunc($b);
qr('alter table '.$b.' auto_increment=1');
qr('insert into '.$b.' select * from '.$bb.'');
return $b;}
function sqlex($b){$rq=qr('show tables like "'.$b.'"');
return mysqli_num_rows($rq)>0;}

function tuples($b,$c){
return qfrw(qr('select count(*) as tuples, '.$c.' from '.$b.' group by '.$c.' having count(*)>1 order by tuples desc'));}
function doublons($b,$c){
return qfrw(qr('select count(*) as nbr_doublon, '.$c.' from '.$b.' group by '.$c.' having count(*)>1'));}
function killdoublons($b,$c){$b=$_SESSION[$b]; if(auth(6))
return qfrw(qr('delete t1 from '.$b.' as t1, '.$b.' as t2 where t1.id > t2.id and t1.'.$c.' = t2.'.$c.''));}

//update structure
function trigger($b,$ra){$rb=sqlcls($b); $rnew=[]; $rold=[];
if(isset($rb['id']))unset($rb['id']); if(isset($rb['up']))unset($rb['up']);
if($rb){$rnew=array_diff_assoc($ra,$rb); $rold=array_diff_assoc($rb,$ra);}//old
if($rnew or $rold){//pr([$rnew,$rold]);
	$bb=bktable($b,date('ymdHis')); drop($b);
	$rtwo=array_intersect_assoc($ra,$rb);//common
	$rak=array_keys($ra); $rav=array_values($ra);
	$rnk=array_keys($rnew); $rnv=array_values($rnew); $nn=count($rnk);
	$rok=array_keys($rold); $rov=array_values($rold); $no=count($rok);
	$na=count($rnew); $nb=count($rold); $ca=array_keys($rtwo); $cb=array_keys($rtwo);
	if($na==$nb)for($i=0;$i<$nn;$i++)if($rnv[$i]==$rov[$i] or $rnv[$i]!='int'){
		$ca[]=$rnk[$i]; $cb[]=$rok[$i];}
	return 'insert into '.$b.'(id,'.implode(',',$ca).',up) select id,'.implode(',',$cb).',up from '.$bb;}}

//columns
function dbtypes($b){$rb=[];
$rq=qr('select distinct(COLUMN_NAME),DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS where table_name="'.$b.'"');//16777215
while($r=mysqli_fetch_assoc($rq)){$type=$r['DATA_TYPE']; $sz=$r['CHARACTER_MAXIMUM_LENGTH'];
	//echo $r['COLUMN_NAME'].'-';
	if($type=='varchar'){
		if($sz<64)$type='svar';
		elseif($sz>1000)$type='bvar';
		else $type='var';}
	if($type=='longtext')$type='long';
	if($type=='mediumtext')$type='text';
	if($type=='tinytext')$type='tiny';
	if($type=='bigint')$type='bint';
	if($type=='decimal')$type='dec';
	if($type=='float')$type='float';
	if($type=='double')$type='double';
	if($type=='json')$type='json';
	$rb[$r['COLUMN_NAME']]=$type;}
return $rb;}

function sqlcols($b,$o=0){//old
$rb=dbtypes($b); if(!isset($rb))return; //pr($rb);
//0:[id,uid,xxx,up];1:id,uid,xxx,up;2:[uid,xxx];3:uid,xxx;4:[xxx];5:xxx,6:[0=>xxx],7:[0=>uid+xxx],8:[0=>xx]
if($o==2 or $o==4 or $o==5 or $o==6 or $o==8)array_shift($rb);//or $o==3
if($o==2 or $o==3 or $o==4 or $o==5 or $o==6 or $o==8)if(isset($rb['up']))unset($rb['up']);//az,a,vk,k,v
if($o==4 or $o==5 or $o==6)unset($rb['uid']); if($o==7)array_shift($rb);
if($o==1 or $o==3 or $o==5)return implode(',',array_keys($rb));
if($o==6 or $o==7)return array_keys($rb);
if($o==8){
	if(isset($rb['uid']))unset($rb['uid']);
	if(isset($rb['pub']))unset($rb['pub']);
	if(isset($rb['edt']))unset($rb['edt']);
	return array_keys($rb);}
return $rb;}

function sqlcls($db,$a=0,$b=0){//modes
//$a=0:[id],[uid],xxx,[up]; $a=1:[uid],xxx; $a=2:xxx; $a=3:[uid],xxx,[up]; $a=4:xx
//$b=0:array; $b=1:string; $b=2:array_values
$rb=dbtypes($db); if(!$rb)return []; //pr($rb);
if($a==4 or $a==3 or $a==2 or $a==1)unset($rb['id']);//full
if($a==4 or $a==3 or $a==2)unset($rb['up']);//all
if($a==4 or $a==3)unset($rb['uid']);//used
if($a==4)array_shift($rb);
if($b==1 or $b==2)$rb=array_keys($rb);
if($b==1)$rb=implode(',',$rb);
return $rb;}

function sqlcls0($db,$o=0){$a=0;$b=0;//patch
//0:[id,uid,xxx,up];1:id,uid,xxx,up;2:[uid,xxx];3:uid,xxx;4:[xxx];5:xxx,6:[0=>xxx],7:[0=>uid+xxx],8:[0=>xx]
if($o==1){$a=0; $b=1;}
if($o==2){$a=2; $b=0;}
if($o==3){$a=2; $b=1;}
if($o==4){$a=3; $b=0;}
if($o==5){$a=3; $b=1;}
if($o==6){$a=3; $b=2;}
if($o==7){$a=2; $b=2;}
if($o==8){$a=4; $b=2;}
return sqlcls($db,$a,$b);}

function sql_utf8($t){$r=sql('*',$t,'rr');//exec one time only on non-utf8 tables
foreach($r as $k=>$v){foreach($v as $ka=>$va)$rb[$k][$ka]=utf8_encode($va);
	sqlups($t,$rb[$k],$v['id']);}}

//create
function create_cols($r){$ret=''; $end='';
$collate='collate utf8mb4_general_ci';
foreach($r as $k=>$v)
if($v=='int')$ret.='`'.$k.'` int(11) default NULL,'."\n";
elseif($v=='bint')$ret.='`'.$k.'` bigint(36) NULL default NULL,'."\n";
elseif($v=='var')$ret.='`'.$k.'` varchar(255) NOT NULL default "",';//'.$collate.'
elseif($v=='bvar')$ret.='`'.$k.'` varchar(1020) NOT NULL default "",';
elseif($v=='svar')$ret.='`'.$k.'` varchar(60) NOT NULL default "",';
elseif($v=='tiny')$ret.='`'.$k.'` tinytext NOT NULL default "",';
elseif($v=='text')$ret.='`'.$k.'` mediumtext,';
elseif($v=='date')$ret.='`'.$k.'` date NOT NULL,';
elseif($v=='datetime')$ret.='`'.$k.'` datetime NOT NULL,';
elseif($v=='dec')$ret.='`'.$k.'` decimal(20,20) NULL default NULL,'."\n";
elseif($v=='float')$ret.='`'.$k.'` float(20,2) NULL default NULL,'."\n";
elseif($v=='double')$ret.='`'.$k.'` double NULL default NULL,'."\n";
elseif($v=='json')$ret.='`'.$k.'` json,'."\n";
//elseif($v=='json'){$ret.='`'.$k.'` mediumtext,'."\n"; $end='CHECK ('.$k.' IS NULL OR JSON_VALID('.$k.')),'."\n";}
//elseif($v=='enum')$ret.=''.$k.'` enum ("'.implode('","',$k).'") NOT NULL,';
return $ret.$end;}

function jsoncolfromattr($b,$c,$k){//add col from json attr k in new col c//attr_colour
qr('ALTER TABLE '.$b.' ADD '.$c.'_'.$k.' VARCHAR(32) AS (JSON_VALUE('.$c.', "$.'.$k.'"));');
qr('CREATE INDEX '.$b.'_'.$c.'_'.$k.'_ix ON '.$b.'('.$c.'_'.$k.');');}

function modifjsonvar($b,$c,$k,$v,$q=''){//impact colattr
qr('UPDATE '.$b.' SET '.$c.' = JSON_REPLACE('.$c.', "$.'.$k.'", "'.$v.'") '.setq($q,$b).';');}

//array('id'=>'int','ib'=>'int','val'=>'var');
function sqlcreate($b,$r,$up=''){
if(!is_array($r) or !$b)return; reset($r);
if($up=='z' && auth(6))drop($b);
if($up)$sql=trigger($b,$r); //echo $sql;
qr('create table if not exists `'.$b.'` (
  `id` int(11) NOT NULL auto_increment,'.create_cols($r).'
  `up` timestamp on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM collate utf8_general_ci;');
if(isset($sql))qr($sql,1);}

function dbapp($b){if(class_exists($b)){
if(isset($b::$cols))return array_combine($b::$cols,$b::$typs);}}

function validcols($r,$b,$rc=[]){
if(!$rc)$rc=dbapp($b); if(!$rc)$rc=sqlcls($b,2);
foreach($r as $k=>$v){$ty=$rc[$k]??''; if(!$v){
	if($k=='id')$r[$k]='NULL';
	elseif($ty=='int' or $ty=='bint' or $ty=='sint' or $ty=='dec' or $ty=='float' or $ty=='double')$r[$k]='0';//!
	elseif($ty=='date')$r[$k]=date('Y-m-d',time());}
elseif($ty=='int')$r[$k]=(int)$v;}
return $r;}

function pvalk($p,$db,$o=''){//public
$rc=sqlcls($db,3); if($o)$r=[ses('uid')];
foreach($rc as $k=>$v){
	$r[$k]=val($p,$k);
	if($k=='pub')$default=3;
	elseif($v=='int' or $v=='bint' or $v=='sint' or $v=='dec' or $v=='float' or $v=='double')$default=0;
	elseif($v=='date')$default=date('Y-m-d',time());
	else $default='';
	if(!$r[$k])$r[$k]=$default;}
return $r;}

#usr
function idusr($u){return sql('id','login','v',['name'=>$u]);}
function usrid($id){return sql('name','login','v',$id);}
function vrfusr($d){return sql('id','login','v','where name="'.$d.'" and auth>1');}
function vrfid($d,$db){return sqlin('name',$db,'login','uid','v','where '.$db.'.id="'.$d.'"');}
function isown($usr){return sql('name','login','v',['mail'=>ses('mail'),'name'=>$usr]);}

#app
function app($app,$p='',$mth=''){$ret='';
if(!is_array($p) && strpos($p,'{')!==false)$p=json_decode($p,true);//}//patch for admin_sys
if(isset($p['prm']))$p=_jrb($p['prm']);//when calling not by ajax
if(!$p)$p=[]; elseif(is_string($p))$p=_jrb($p);
$mth=$p['appMethod']??($mth?$mth:'content'); unset($p['appMethod']); unset($p['appName']);
if(method_exists($app,$mth)){
	$private=$app::$private??0; $auth=ses('auth'); if(!$auth)$auth=0;
	if($auth>=$private){$a=new $app; $ret=$a->$mth($p);
		if(method_exists($app,'headers'))
			if(!get('appName') or isset($p['headers']))
				if(!isset(storage::$alx[$app])){storage::$alx[$app]=1; $a->headers();}}
	else $ret=help('need auth '.$private,'paneb');}
elseif(!class_exists($app))return div(helpx('nothing').' : '.$app.'::'.$mth,'paneb');
else return div(helpx('no app loaded').' : '.$app.'::'.$mth,'paneb');
return $ret;}

function apj($call){
if(strpos($call,'|')){list($call,$prm)=explode('|',$call); $p=explode_k($prm,',','=');}
if(strpos($call,','))list($call,$mth)=explode(',',$call);
if(isset($mth))$p['appMethod']=$mth; $p['headers']=1;
return app($call,$p);}

function ifrapp($app,$id){
return iframe(host(1).'/frame/'.$app.'/'.$id);}

function com($app,$id){
$ret=span(langp('connectors'),'nfo');
$ret.=textarea('','['.$id.':'.$app.']','','1');
$ret.=lk('/frame/'.$app.'/'.$id,langp('iframe'),'nfo',1);
if(method_exists($app,'iframe'))
	$ret.=textarea('','<iframe src="'.host(1).'/frame/'.$app.'/'.$id.'"></iframe>','','1');
$ret.=lk(host(1).'/api/'.$app.'/'.$id,langp('api'),'nfo',1);
if(method_exists($app,'api')){//$a=new $app; $ret.=textarea('',$a::api(['id'=>$id]),'','');
	$ret.=textarea('',host(1).'/api/'.$app.'/id:'.$id.'','','1');}
return $ret;}

#icon
function icon_ex($d){$r=sesf('icon_com','',0);
if(is_array($r) && array_key_exists($d,$r))return $r[$d];}
function icon_com(){return sql('ref,icon','icons','kv','');}
function icolg($d,$o='',$no=''){$r=sesf('icon_com','',0);
if($r && !array_key_exists($d,$r) && $d && !is_numeric($d) && !$no){
	sqlsav('icons',[$d,'']); $r=sesf('icon_com','',1);}
$ret=!empty($r[$d])?$r[$d]:'';
if($o)$ret=ico($ret); return $ret;}
function ico($d,$s='',$c='',$t='',$ti='',$tb='',$id=''){$r=[];
	if(is_numeric($s))$s='font-size:'.$s.'px'; if($s)$r['style']=$s;
	if($c)$r['class']=$c; if($id)$r['id']=$id; if($ti)$r['title']=($ti); if($tb)$t=' '.$tb;
	$ret=span('','pic fa fa-'.$d).$t; if($r)$ret=tag('span',$r,$ret); return $ret;}
function icoxt($d,$t,$c='',$s=''){return span(ico($d,$s).$t,$c);}
function icoit($d,$t,$c='',$s=''){return ico($d,$s,$c,'',$t);}
//function icid($d,$t,$id){return ico($d,'','',$t,'','',$id);}
function pic($d,$s='',$c=''){return ico(icolg($d),$s,$c);}
function picxt($d,$t=''){return ico(icolg($d),'','','','',$t);}
function picto($d,$s='',$c=''){if($c)$c=' '.$c; if(is_numeric($s))$s='font-size:'.$s.'px;';
	return span('','philum ic-'.$d.$c,'',$s);}
function pictxt($d,$t=''){return span('','philum ic-'.$d).$t;}

#lang
function setlng(){$lng=ses('lng'); if($lng=='en')$lngb='US'; else $lngb=strtoupper($lng);
setlocale(LC_ALL,$lng.'_'.$lngb);}
function lng(){return ses('lng')?ses('lng'):'fr';}//sesif('lng','fr')
function lngs(){return ['fr','en','es'];}//,'it','de','zn','ru','ja','ar','zw'
function lang_set($lg){$v=$lg?$lg:sesif('lng','fr');
sez('lng',$v); cookie('lng',$v); sesf('lang_com',$v,1); return $v;}
function lang_com($lang){return sql('ref,voc','lang','kv',['lang'=>$lang]);}
function lang_ex($d){$lang=sesif('lng','fr');
$r=sesf('lang_com',$lang); if(is_array($r) && array_key_exists($d,$r))return 1;}

function lang($d,$o='',$no=''){
$lang=sesif('lng','fr'); $applng=sesifn('applng',$lang); $r=sesf('lang_com',$lang,0);
if(!$no && $r && $d && !array_key_exists($d,$r) && !is_numeric($d)){//strpos($d,',')===false &&
	//$db=yandex::com(['from'=>'en','to'=>$lang,'txt'=>$d]); if($db)$d=$db;//
	if(strpos($d,'"')===false)sqlsav('lang',[$d,'',$applng,$lang]);
	$r=sesf('lang_com',$lang,1);}
$ret=!empty($r[$d])?$r[$d]:$d;
if(!$o)$ret=ucfirst_b($ret);
return $ret;}

function langc($d,$c=''){return span(lang($d),$c);}
function langp($d,$s=''){return ico(icolg($d),$s,'ico').lang($d);}
function langpi($d,$s=''){return ico(icolg($d),$s,'ico','',lang($d));}
function langph($d,$s=''){return ico(icolg($d),$s,'ico').span(lang($d),'react');}
function langs($d,$n,$o=''){return lang($d.($n>1?'s':''),$o);}
function langnb($d,$n,$c=''){return span($n.' '.langs($d,$n,1),$c);}
function langnbp($d,$n,$c='',$s=''){return span(ico(icolg($d),$s,'ico').$n.' '.langs($d,$n,1),$c);}
function langx($d,$o=''){$rb=[]; $r=explode(' ',$d);
foreach($r as $k=>$v){$rb[]=lang($v,$o); $o=1;} return implode(' ',$rb);}

//helps
function help($ref,$css='',$conn='',$brut=''){$lg=sesif('lng','fr'); $bt='';//hlpxt
$r=sql('id,txt','help','rw',['ref'=>$ref,'lang'=>$lg]);//if(!$r)return $ref;
if(!isset($r[0]) && $ref)$r[0]=sqlsav('help',[$ref,'',$lg]);
if(auth(6))$bt=bj('popup|admin_help,edit|to=hlpxd,id='.$r[0].',headers=1',ico('edit')).' ';
if(isset($r[1]))$txt=$conn?conn::com($r[1]):nl2br($r[1]); else $txt=$ref;
if($brut)return $r[1]??''; elseif($txt)return div($bt.$txt,$css?$css:'helpxt','hlpxd');}
function helpx($d){return help($d,'','',1);}
function hlpbt($d,$t='',$c='btn'){return bubble('core,help|ref='.$d,ico('question-circle-o').$t,$c);}

#voc
function voc($d,$ref,$lg0=''){$lng=ses('lng');
list($db,$col,$id)=explode('-',$ref); $vrf=md5($d);
$lg=sql('lang','voc','v',['vrf'=>$vrf]);
if(!$lg){$ex=sql('id','voc','v',['ref'=>$ref,'lang'=>$lng]);//changes
	if($ex)sqldel('voc',$ref,'ref');}
if(!$lg){if($lg0)$lg=$lg0; else $lg=yandex::detect(['txt'=>$d]);
	if($lg)$id=sqlsav('voc',[$ref,$lg,$d,$vrf]);}
if($lg && $lg!=$lng){
	$b=sql('trad','voc','v',['ref'=>$ref,'lang'=>$lng]);
	if(!$b){$c=yandex::com(['from'=>$lg,'to'=>$lng,'txt'=>$d]);
		if($c)sqlsav('voc',[$ref,$lng,$c,md5($c)]); $d=$c;}
	else $d=$b;}
return $d;}

#batch
function batch($r,$j,$vrf=''){$ret='';
if($r)foreach($r as $k=>$v)if($v)$ret.=bj(str_replace(['$k','$v'],[$k,$v],$j),$v,act($v,$vrf));
return div($ret,'lisb');}

function iter_r($r,$a){$ret=[];
if($r)foreach($r as $k=>$v)$ret[]=is_array($v)?iter_r($v,$a):$a($k,$v);
return $ret;}

function iter($r,$a){$ret=[];
if($r)foreach($r as $k=>$v)$ret[]=$a($k,$v);
return $ret;}

function loop($fc,$n,$a='',$b=''){$r=[];
for($i=0;$i<$n;$i++)$r[]=$fc($i,$a,$b,$r);
return $r;}

function loopr($r,$fc,$a='',$b=''){$rb=[];
foreach($r as $k=>$v)$rb[$k]=$fc($v,$a,$b);
return $rb;}

function proportions($r,$n=1){$rb=[];
$a=array_sum($r); $t=$n/$a;
foreach($r as $k=>$v)$rb[$k]=$v*$t;
return $rb;}

#pages
function btpages_nb($nbp,$pg){
$cases=5; $left=$pg-1; $right=$nbp-$pg; $r[1]=1; $r[$nbp]=1;
for($i=0;$i<$left;$i++){$r[$pg-$i]=1; $i*=2;}
for($i=0;$i<$right;$i++){$r[$pg+$i]=1; $i*=2;}
if($r)ksort($r);
return $r;}

function btpages($nbyp,$pg,$nbarts,$j){$ret=''; $nbp=''; $rp=[];
if($nbarts>$nbyp)$nbp=ceil($nbarts/$nbyp);
if($nbp)$rp=btpages_nb($nbp,$pg);
if($rp)foreach($rp as $k=>$v)$ret.=bj($j.',pg='.$k,$k,act($k,$pg));
if($ret)return div($ret,'nbp sticky');}

function batch_pages($r,$p,$j,$a,$fc){
$id=$p['id']??''; $pg=$p['pg']??1; $nbp=20; $ret=''; $i=0;
$min=($pg-1)*$nbp; $max=$pg*$nbp; $tot=count($r);
$bt=btpages($nbp,$pg,$tot,$a.'pg,,z|'.$j);
if($r)foreach($r as $k=>$v){if($i>=$min && $i<$max)$ret.=$a::$fc($v); $i++;}
return div($bt.$ret,'',$a.'pg');}

#clr
function clrs(){return json::read('json/system/colors');}//get
function clrget($d){$r=sesf('clrs','',0); if(isset($r[$d]))return $r[$d];}//read
function clrand(){$r=sesf('clrs'); if(is_array($r))$r=array_values($r); return $r[rand(0,139)];}
function btclr($k,$v){return span('','clr','','background-color:#'.$v.';');}//,['title'=>$k]

function clrpick($p){$id=$p['id'];
$r=json::read('json/system/colors'); $ret='';
foreach($r as $k=>$v)$ret.=tag('a',['class'=>'clr','onclick'=>atj('affectclr',[$v,$id,0]),'style'=>'background-color:#'.$v.'; padding:0 4px;','title'=>$k],'');
return div($ret);}

function inpclr($id,$clr,$sz='',$sky='',$bkg=''){$cb=randid('cklr');
if(substr($clr,0,1)=='-' or strpos($clr,',') or is_img($clr))$clrb='black';
else $clrb=clrneg($clr,1);
$inp=tag('input',['type'=>'color','id'=>$id,'value'=>$clr,'size'=>$sz,'placeholder'=>lang($id,1),'onclick'=>'applyclr(this,'.$bkg.')','onkeyup'=>'applyclr(this,'.$bkg.')','style'=>'background-color:#'.$clr.'; color:#'.$clrb],'',1);
$ret=span(pic('color').$inp,'inpic');
$ret.=toggle($cb.'|core,clrpick|id='.$id.''.$bkg,pic('clr'),'btn');
if($sky)$ret.=toggle($cb.'|sky,slct|rid='.$id,ico('snowflake-o'),'btn');
if($bkg)$ret.=upload::img($id,'',$cb);
return $ret.=span('','',$cb);
return div($ret);}

function inpimg($k,$val,$sz,$o=''){
if($o)$bt=pickim($k); else $bt=upload::call($k);//upload::img($k)
$bt.=build::import_img(['tg'=>$k,'html'=>0,'hk'=>0]);
if($val)$bt.=imgup(imgroot($val),pic('view'),'btn');
return input($k,$val,$sz).$bt;}

function theme($clr){
if($clr=='no')$ret='';
elseif(substr($clr,0,1)=='-'){$c=substr($clr,1); //$sky=ses('sky'.$c);
	//if(!$sky){ses('sky'.$c,$sky);}
	$sky=sql('css','sky','v',['tit'=>$c]);
	$clr=segment($sky,'#',','); $clr0=clrneg($clr,1);
	$ret='background-image:'.$sky.';';}// color:#'.$clr0.';
	//$ret.='} .bicon, .bicon .pic{color:white;} .bicon:hover, .bicon:hover .pic{color:black;';
elseif(strpos($clr,':'))$ret='background-image:'.$clr.';';
elseif(strpos($clr,'.'))$ret='background-image:url(/'.imgroot($clr).'); background-size:cover;';
elseif(strpos($clr,',')){[$clr1,$clr2]=explode(',',$clr); $clr0=clrneg($clr,1);
	$ret='background-image:linear-gradient(to bottom,#'.$clr1.',#'.$clr2.'); color:#'.$clr0.';';}
elseif($clr){$clr0=clrneg($clr,1);
	$hex='rgba(119,1119,119,0.0)';//$clr?hexrgb($clr,0.9): $clr2=clrb($clr,-20);
	$hex2='rgba(119,99,119,0.4)';//$clr2?hexrgb($clr2,0.3):
	$ret='background-color:#'.$clr.'; ';// color:#'.$clr0.';
	$ret.='background-image:linear-gradient(to bottom,'.$hex.','.$hex2.'); ';
	//$ret.='color:#'.$clr0.';';
	//add_head('csscode','.bicon, .bicon .pic, .bicon .pic:hover{color:#'.$clr0.';}');
	}//h1,h2,h3,h4{color:#'.$clr0.';}
else $ret='';
//.pane,.paneb,.panec,.paned{color:#'.$clr0.' background-color:'.$hex0.';}
//.lisb a, .lisb a:hover,.lisb .pic{color:#'.$clr0.';}
return $ret;}

function bootheme($usr){$cusr=$usr?$usr:ses('user');
$clr=sesr('clr',$cusr,'');//echo $usr.'-'.$own;
if(!$clr)$clr=profile::init_clr(['usr'=>$cusr]);
$sty=ses('sty'.$clr); if(!$sty){$sty=theme($clr); ses('sty',$sty);}
//if(get('popup'))add_head('csscode','.container{'.$sty.'}'); else
add_head('csscode','body{'.$sty.'}');}

#img
function imgthumb($f){
$fa='img/full/'.$f; $fc='img/mini/'.$f;
if(!is_file($fc) && is_file($fa))mkthumb($fa,$fc,170,170,0);
//elseif(is_file($fc))unlink($fc);//maintenance
return $fc;}

function imgroot($f,$dim=''){
if(substr($f,0,4)=='http')return $f;
$fa='img/full/'.$f; $fb='img/medium/'.$f; $fc='img/mini/'.$f;
$med=is_file($fb); if(!$dim)$dim='full'; if(!is_file($fc))imgthumb($f);
if($dim=='mini' or $dim=='micro')$im=$fc;
elseif($dim=='medium')$im=$med?$fb:$fa; else $im=$fa;
return $im;}

function goodir($f){
if(substr($f,0,4)=='http')return $f;
elseif(substr($f,0,4)=='/usr')return $f; 
elseif(substr($f,0,4)=='disk')return $f;
elseif(substr($f,0,3)=='usr')return '/disk/'.$f; 
else return '/disk/usr/'.$f;}

function img2($f,$dim='',$o=''){
$ret=imgroot($f,$dim); $w=$dim=='micro'?100:''; $w=$dim=='avt'?60:'';
if(ex_img($ret))return img('/'.$ret,$w);
elseif($o)return pic('img');}

function playimg($f,$dim,$o='',$sz=''){if($dim=='micro')$sz=64;
if(substr($f,0,4)=='http')$f=saveimg($f,'tlx',$sz,'');
$u=imgroot($f,$dim); $im=img('/'.$u,$sz); $ua='img/full/'.$f;
if(!is_file($ua))return pic('img');
if(!$u)return; if($o==2)return $u; elseif($o)return $im;
[$w,$h]=@getimagesize($ua);
if($w>800 or $dim=='micro')return imgup($ua,$im);
else return $im;}

function saveimg($f,$prf,$w,$h=''){$er=1;
if(substr($f,0,4)!='http')return;
if(strpos($f,'?'))$f=struntil($f,'?');
$xt=ext($f); if(!$xt)$xt='.jpg';
$nm=$prf.strid($f,10); $h=$h?$h:$w;
$fa='img/full/'.$nm.$xt; mkdir_r($fa);
$fb='img/mini/'.$nm.$xt; //mkdir_r($fb);
$fc='img/medium/'.$nm.$xt; //mkdir_r($fc);
if(is_file($fa))return $nm.$xt;
$ok=@copy($f,$fa);
if(!$ok){$d=@file_get_contents($f); if($d)$er=write_file($fa,$d);}
if($ok or !$er)if(filesize($fa)){mkthumb($fa,$fb,170,170,0);
	upload::add_img_catalog($nm.$xt,$prf);
	[$wa,$ha]=getimagesize($fa); if($wa>$w or $ha>$h)mkthumb($fa,$fc,$w,$h,0);
	return $nm.$xt;}}

function pickim($id,$o='',$cb=''){//$o:insert,$cb:pop/bub/tog//see desktop::pickim
if($cb==1)return bubble('upload,pick|id='.$id.',o='.$o,ico('image'),'btn',[],'z');
elseif($cb)return toggle($cb.',,z|upload,pick|id='.$id.',o='.$o,ico('image'),'btn',[],'z');
else return popup('upload,pick|id='.$id.',o='.$o,ico('image'),'btn');}

#head
class storage{static $r; static $ret; static $alx; static $head;}
function headerhtml(){return '<!DOCTYPE html>
<html lang="fr" xml:lang="fr">'.n();}
function meta($attr,$prop,$d=''){
return '<meta '.$attr.'="'.$prop.'"'.($d?' content="'.$d.'"':'').'/>';}
function csslink($u){//if(strrchr($u,'.')=='.css')
return '<link href="/'.ses('dev').$u.'" rel="stylesheet" type="text/css">';}
function jslink($u){if(substr($u,0,4)!='http')$root='/'.ses('dev'); else $root='';
return '<script src="'.$root.$u.'"></script>';}
function csscode($d){return '<style type="text/css">'.$d.'</style>';}
function jscode($d,$id=''){return '<script type="text/javascript"'.atd($id).'>'.$d.'</script>';}
function add_head($action,$r){storage::$head[][$action]=$r;}//add
function add_prop($p,$v){storage::$head[]['meta']=['attr'=>'property','prop'=>$p,'content'=>$v];}
function add_name($p,$v){storage::$head[]['meta']=['attr'=>'name','prop'=>$p,'content'=>$v];}
function build_head(){$ret=''; $r=storage::$head;
if($r)foreach($r as $k=>$v){if(is_array($v))$va=current($v);
	switch(key($v)){
		case('code'):$ret.=$va."\n"; break;
		case('charset'):$ret.='<meta charset="'.$va.'">'."\n"; break;
		case('csslink'):if($va)$ret.=csslink($va)."\n"; break;
		case('jslink'):if($va)$ret.=jslink($va)."\n"; break;
		case('csscode'):if($va)$ret.=csscode($va)."\n"; break;
		case('jscode'):if($va)$ret.=jscode($va)."\n"; break;
		case('rel'):$ret.='<link rel="'.$v['rel']['name'].'" href="'.$v['rel']['value'].'">'."\n"; break;
		case('meta'):$v=$v['meta']; $ret.=meta($v['attr'],$v['prop'],$v['content'])."\n"; break;
		case('tag'):$v=$v['tag']; $ret.=tag($v[0],$v[1],$v[2])."\n"; break;}}
	return $ret;}
function generate(){return headerhtml().tag('head','',build_head()).n();}

#popup
function mkpopup($d,$p){
//$pw=$p['pagewidth']??640; $w=$p['popwidth']??post('pw'); //$pw=640;
//$s='width:'.($pw<640?$pw:($w?$w:$pw)).'px;';
$ret=btj(picto('close',20),atj('Close','popup'),'imbtn');
$ret.=btj(picto('ktop',20),atj('repos',''),'imbtn');
$ret.=btj(picto('less',20),atj('reduc','popup'),'imbtn');
$app=$p['appName']??''; $mth=$p['appMethod']??'';
$title=':: '.$app.' :: ';//lk('/'.$app,ico('link'),'',1).
if(method_exists($app,'titles'))$title.=$app::titles($p); else $title.=$mth.' ';
if($app && method_exists($app,'admin') && !$mth)//
	$title.=menu::call(['app'=>$app,'mth'=>'admin']);
$ret.=tag('span',['class'=>'imbtn'],$title);
$head=tag('div',['id'=>'popa','class'=>'popa','onmouseup'=>'stop_drag(event); noslct(1);','onmousedown'=>'noslct(0);'],$ret);
$ret=tag('div',['id'=>'popu','class'=>'popu'],$d);
if($d)return tag('div',['class'=>'popup'],$head.$ret);}//,'style'=>$s

function mkpagup($d,$p){if(!$d)return;
if($w=$p['popwidth']??'')$d=div($d,'','','max-width:'.$w.'px');
//$bt=span(btj(ico('close'),'Close(\'popup\');','btn'),'left');$bt.
$d=tag('div',['id'=>'popu','class'=>'pagu'],div($d,'pgu'));
return tag('div',['class'=>'pagup'],$d);}

function mkimgup($d){
$ret=tag('div',['id'=>'popu','class'=>'imgu'],div($d,'imu'));
//$ret=tag('a',['onclick'=>'Close(\'popup\');'],$ret);
return tag('div',['class'=>'pagup'],$ret);}

function mkbubble($d){
$d=tag('div',['id'=>'popu','class'=>'bubu'],$d);
return tag('div',['class'=>'bubble'],$d);}//,'style'=>'max-width:320px'

function mkmenu($d){
$d=tag('div',['id'=>'popu','class'=>'bubu'],$d);
return tag('div',['class'=>'bubble','style'=>''],$d);}

#builders
function scroll($r,$n,$h=''){$max=count($r); $ret=implode('',$r);
$s='overflow-y:scroll; max-height:'.($h?$h.'px':400).';';
if($max>$n)return tag('div',['id'=>'scroll','style'=>$s],$ret);
else return $ret;}

function boxhide($p,$xid=''){$r=explode('-',$p['s']);
if($xid)$p['rid']=$xid; $rid=$p['rid']; $id=$p['id']; $ka=valb($p,'ka',0); $ret='';
foreach($r as $k=>$v){$c='btn'; if($k==$ka)$c.=' active'; $p['ka']=$k;
	$ret.=bj($rid.'|core,boxhide|'.prm($p),langp($v),$c);}
$ret.=hidden($id,$ka); if($xid)$ret=div($ret,'',$xid); return $ret;}

function trace($r){
return popup('core,rplay||rj',ico('pied-piper'),'btn').divh(json_enc($r),'rj');}

function dragline($t,$id,$j=''){return tag('div',['id'=>$id,'class'=>'dragme','draggable'=>'true','ondragstart'=>'drag_start(event)','ondragover'=>'drag_over(event)','ondragleave'=>'drag_leave(event)','ondrop'=>'drag_drop(event,\''.$j.'\')','ondragend'=>'drag_end(event)'],$t);}

//phylo
//['class'=>['class1'=>'col_1','class2'=>'col_2']];
function phylo($r,$struct){$rt=[]; $rb=[];
foreach($struct as $k=>$v){
	if(is_array($v))$rt[]=div(phylo($r,$v),'',is_numeric($k)?'':$k);
	elseif(array_key_exists($v,$r)){$vr=$r[$v];
		if(is_array($vr)){
			foreach($vr as $kb=>$vb){
				if(!is_numeric($k))$rb[$kb][$v]=div($vb,$k);
				else $rb[$kb][$v]=$vb;}}
		elseif(!is_numeric($k))$rt[]=div($vr,$k);
		else $rt[]=$vr;}}
if($rb)foreach($rb as $k=>$v)$rt[]=implode('',$v);
if($rt)return implode('',$rt);}

//tabler
function tabler($r,$head='',$keys='',$sums=''){$i=0; $tr=[];
if(is_array($head))array_unshift($r,$head);
if($sums)foreach(next($r) as $k=>$v)$r['='][]=array_sum(array_column($r,$k));
if(is_array($r))foreach($r as $k=>$v){$td=[]; $i++;
	if(($head && $i==1) or $k==='_' or $k==='=')$tag='th'; else $tag='td';
	if($keys)$td[]=tag($tag,'',$k?$k:'_');
	if(is_array($v))foreach($v as $ka=>$va)
		$td[]=tag($tag,['id'=>$k.'-'.$ka],$va);
	else $td[]=tag($tag,'',$v);
	if($td)$tr[]=tag('tr',['id'=>'k'.$k],join('',$td));}
$ret=tag('tbody','',join('',$tr));
return div(tag('table','',$ret),'scroll','','');}//overflow:auto;

function play_r($r){$ret='';//expl
if(is_array($r))foreach($r as $k=>$v)
	if(is_array($v))$ret.=li($k).play_r($v);
	else $ret.=li($k.':'.$v);
return ul($ret);}

function taxo_clean(&$r,$rb){
if($rb)foreach($rb as $k=>$v)if(isset($r[$v]))unset($r[$v]);}

function taxo_find($rb,$ra,&$rx){$rt=[];
foreach($rb as $k=>$v){
	if(isset($ra[$k])){
		if(is_array($ra[$k]))$rt[$k]=taxo_find($ra[$k],$ra,$rx);
		else $rt[$k]=$ra[$k];
		$rx[]=$k;}
	else $rt[$k]=$v;}
return $rt;}

//$rb[$v['idp']][$v['idn']]=1;
function taxonomy($r){$ra=$r; $rx=[]; $rt=[];
foreach($r as $k=>$v){
	if(is_array($v))$rt[$k]=taxo_find($v,$ra,$rx);
	else $rt[$k]=$v;}
taxo_clean($rt,$rx);
return $rt;}

//[1=>0,2=>1,3=>2,4=>0]
function taxo($r){$rb=[];
foreach($r as $k=>$v)$rb[$v][$k]=1;
return taxonomy($rb);}

#time
function readtime($d,$o=0){$n=$d/1200; $b='';
if($n>60){$b=round($n/60).'h '; $n=$n%60;}
if($n>1)$b.=str_pad(round($n),2,'0',STR_PAD_LEFT).' min ';
return ico('clock-o','','bton',$b,lang('time_reading'));}
function numday($d=''){return date('ymd',$d);}
function numday2time($d){if(is_numeric($d) && strlen($d)==6)$d='20'.$d; return strtotime($d);}

function calendar($p){
$d=$p['day']??''; $fc=$p['fc']??''; $rid=randid();
return div(build::calendar($d,$fc),'',$rid);}

#pop
function alert($d){
add_head('jscode','ajx("popup|core,txt|txt='.$d.'");');}

?>
