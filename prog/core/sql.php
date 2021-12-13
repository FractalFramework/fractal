<?php
class sql{
static private $dbhost='localhost';
static private $dbuser='root';
static private $dbname='wos';
static private $dbpass='';
static $dbq=Object;
function __construct(){self::$dbq=new mysqli(self::$dbhost,self::$dbuser,self::$dbname,self::$dbpass);}
//static function connect(){self::$dbq=new mysqli(self::$dbhost,self::$dbuser,self::$dbname,self::$dbpass);}
//static function connect(){require('cnfg/connect.php'); self::$dbq=$dbq;}
static function qr($sql,$z=''){if($z==1)echo $sql; $rq=mysqli_self::query(self::$dbq,$sql);
if($rq==null)echo mysqli_error(ses('dbq')).br().$sql.hr(); return $rq;}
static function qfar($r){if($r)return mysqli_fetch_array($r);}
static function qfas($r){if($r)return mysqli_fetch_assoc($r);}
static function qfrw($r){if($r)return mysqli_fetch_row($r);}
static function query($sql,$p,$z=''){if($z==1)echo $sql; $rq=self::qr($sql,$z);
if($rq){$rt=self::sqlformat($rq,$p); mysqli_free_result($rq); return $rt;}}
static function escape($v){return mysqli_real_escape_string(ses('dbq'),stripslashes($v));}

static function sqlformat($rq,$p){$rt=[];
if($p=='rq')return $rq;
if($p=='ry')return self::($rq);
if($p=='ra')return self::qfas($rq);
if($p=='rw')return self::qfrw($rq);
if($p=='v'){$r=self::qfrw($rq); return $r[0];}
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

static function rqcols($d,$b){
$r=explode(',',$d); $d='';
foreach($r as $k=>$v){
	if($v=='timeup')$r[$k]='unix_timestamp('.$b.'.up) as time';
	elseif($v=='dateup')$r[$k]='date_format('.$b.'.up,"%d/%m/%Y") as date';
	elseif($v=='numsec')$r[$k]='date_format('.$b.'.up,"%y%m%d.%H%i%s") as date';
	elseif($v=='numday')$r[$k]='date_format('.$b.'.up,"%y%m%d") as date';}
$d=implode(',',$r);
if($d=='all')$d=self::sqlcls($b,2,1);
if($d=='full')$d=self::sqlcls($b,1,1);
elseif($d=='used')$d=self::sqlcls($b,3,1);
if(!$d)$d='*';
return $d;}

static function where($r,$o=''){
$rb=[]; $rc=[]; $w=''; $ret='';
foreach($r as $k=>$v)
	if($k=='_order')$w.=' order by '.$v;
	elseif($k=='_group')$w.=' group by '.$v;
	elseif($k=='_limit')$w.=' limit '.$v;
	elseif($k=='or')$rc=self::where($v,1);//'or'=>['!status'=>'3','!typ'=>'0']
	elseif(substr($k,0,1)=='|')$rc[]=substr($k,2).'="'.self::escape($v).'"';//or
	elseif(substr($k,0,1)=='!')$rb[]=substr($k,1).'!="'.self::escape($v).'"';
	elseif(substr($k,0,2)=='>=')$rb[]=substr($k,2).'>="'.self::escape($v).'"';
	elseif(substr($k,0,2)=='<=')$rb[]=substr($k,2).'<="'.self::escape($v).'"';
	elseif(substr($k,0,1)=='>')$rb[]=substr($k,1).'>"'.self::escape($v).'"';
	elseif(substr($k,0,1)=='<')$rb[]=substr($k,1).'<"'.self::escape($v).'"';
	elseif(substr($k,0,1)=='%')$rb[]=substr($k,1).' like "%'.self::escape($v).'%"';
	elseif($k=='numday')$rb[]='date_format(up,"%y%m%d")="'.$v.'"';
	elseif(is_array($v))$rb[]=$k.' between "'.$v[0].'" and "'.$v[1].'"';
	elseif($v==='not null')$rb[]=$k.' is not null';
	elseif($v==='is null')$rb[]=$k.' is null';
	else $rb[]=$k.'="'.self::escape($v).'"';
if($o)return $rb;
if($rc)$rb[]='('.implode(' or ',$rc).')';
if($rb)$ret=implode(' and ',$rb);
if($ret)return 'where '.$ret.$w;}

static function setq($q,$b){
if(is_numeric($q))$q=[$b.'.id'=>$q];
if(is_array($q))return self::where($q);
else return $q;}

static function escr($r){$rb=[];
foreach($r as $k=>$v)$rb[]=$v=='null'?$k.'=NULL':$k.'="'.self::escape($v).'"';
return $rb;}

static function array2insert($r,$b='',$o='',$vd=''){
if($vd)$r=self::validcols($r,$b);//
foreach($r as $k=>$v){
	if(substr($v,0,8)=='PASSWORD')$rb[$k]=$v;
	elseif($v==='null')$rb[$k]='NULL';
	elseif($v===0)$rb[$k]=0;
	else $rb[$k]='"'.self::escape($v).'"';}
if($o==5)return '('.implode(',',$rb).')';
elseif($o)return '(NULL,'.implode(',',$rb).')';
else return '(NULL,'.implode(',',$rb).',"'.date('Y-m-d H:i:s',time()).'")';}

static function array2insert2($r,$b='',$o='',$vd=''){//[[1,'hello'],[2,hey]]
foreach($r as $k=>$v)$rb[]=self::array2insert($v,$b,$o,$vd);
return implode(',',$rb);}

//self::read('id','qda','rv',['id'=>$id]);
static function read($d,$b,$p='',$q='',$z=''){//sql()
$d=self::rqcols($d,$b); $q=self::setq($q,$b); $ret=[]; if($p=='v')$ret='';
if($b)$rq=self::qr('select '.$d.' from '.$b.' '.$q,$z);
if(!empty($rq->num_rows)){$ret=self::sqlformat($rq,$p); mysqli_free_result($rq);}
return $ret;}

//join b2 to b1, associating b2.$key to b1.id
static function sqlin($d,$b1,$b2,$key,$p,$q='',$z=''){//b2 is on the right, let left empty
$q='inner join '.$b2.' on '.$b1.'.'.$key.'='.$b2.'.id '.self::setq($q,$b1);
return self::read($d,$b1,$p,$q,$z);}

static function sqljoin($d,$b1,$b2,$key,$p,$q='',$z=''){
$q='left join '.$b2.' on '.$b1.'.'.$key.'='.$b2.'.id '.self::setq($q,$b1);
return self::read($d,$b1,$p,$q,$z);}

//[[$b1,$k1,$b2,$k2],[$b1,$k1,$b3,$k3]]
static function sqlrin($d,$r,$p,$q='',$z=''){$w=''; $b='';
foreach($r as $k=>$v){$w.='join '.$v[2].' on '.$v[0].'.'.$v[1].'='.$v[2].'.'.$v[3].' '; if(!$b)$b=$v[0];}
return self::read($d,$b,$p,$w.self::setq($q,$b),$z);}

static function sqlrout($d,$r,$p,$q='',$z=''){$w=''; $b='';
foreach($r as $k=>$v){$w.='left outer join '.$v[2].' on '.$v[0].'.'.$v[1].'='.$v[2].'.'.$v[3].' '; if(!$b)$b=$v[0];}
return self::read($d,$b,$p,$w.self::setq($q,$b),$z);}

static function sqlsav2($b,$r,$o='',$x='',$z='',$vd=''){
if(auth(6) && $x){self::bktable($b); self::trunc($b);}
$sql='insert into '.$b.' values '.self::array2insert2($r,$b,$o,$vd);
$rq=self::qr($sql,$z); return mysqli_insert_id(ses('dbq'));}

static function sqlsav($b,$r,$z='',$o='',$vd=''){
$sql='insert into '.$b.' values '.self::array2insert($r,$b,$o,$vd);
$rq=self::qr($sql,$z); return mysqli_insert_id(ses('dbq'));}
static function sqlup($b,$d,$v,$q,$col='',$z=''){if($col)$q=[$col=>$q];
self::qr('update '.$b.' set '.$d.'='.($v=='null'?$v:'"'.self::escape($v).'"').' '.self::setq($q,$b),$z);}
static function sqlups($b,$r,$q,$col='',$z=''){if($col)$q=[$col=>$q]; $rb=self::escr($r);
self::qr('update '.$b.' set '.implode(',',$rb).' '.self::setq($q,$b),$z);}
static function sqlsavif($b,$r,$z=''){$ex=self::read('id',$b,'v',$r);
if(!$ex)$ex=self::sqlsav($b,$r); return $ex;}
static function sqlsavup($b,$r,$rb=[],$z=''){$ex=self::read('id',$b,'v',$rb);
if(!$ex)return self::sqlsav($b,$r+$rb,$z); else return self::sqlups($b,$r,$ex,'',$z);}
static function sqldel($b,$q,$col=''){if($col)$q=[$col=>$q];
self::qr('delete from '.$b.' '.self::setq($q,$b));}
static function sqlclose(){mysqli_close(ses('dbq'));}

#sql_maintenance
static function reflush($b,$o=''){
self::qr('alter table '.$b.' order by id');}
static function reflush_ai($b){$id=self::lastid($b)+1;
self::qr('alter table '.$b.' auto_increment='.$id.'');}
static function lastid($b){return self::read('id',$b,'v','order by id desc limit 1');}
static function trunc($b){self::qr('truncate '.$b);}
static function drop($b){self::qr('drop table '.$b);}
static function rntable($b,$bb){self::qr('rename table '.$b.' to '.$bb.';');}
static function cptable($b,$bb){
self::qr('create table '.$bb.' like '.$b);
self::qr('insert into '.$bb.' select * from '.$b); return $bb;}
static function bktable($b,$d=''){$bb='z_'.$b.'_'.$d;
if(self::sqlex($bb))self::drop($bb);
self::qr('create table '.$bb.' like '.$b);
//self::qr('alter table '.$bb.' add primary key (id)');
self::qr('insert into '.$bb.' select * from '.$b);
return $bb;}
static function rbtable($b,$d=''){$bb='z_'.$b.'_'.$d;
if(!self::sqlex($bb))return; self::trunc($b);
self::qr('alter table '.$b.' auto_increment=1');
self::qr('insert into '.$b.' select * from '.$bb.'');
return $b;}
static function sqlex($b){$rq=self::qr('show tables like "'.$b.'"');
return mysqli_num_rows($rq)>0;}

static function tuples($b,$c){
return self::qfrw(self::qr('select count(*) as tuples, '.$c.' from '.$b.' group by '.$c.' having count(*)>1 order by tuples desc'));}
static function doublons($b,$c){
return self::qfrw(self::qr('select count(*) as nbr_doublon, '.$c.' from '.$b.' group by '.$c.' having count(*)>1'));}
static function killdoublons($b,$c){$b=$_SESSION[$b]; if(auth(6))
return self::qfrw(self::qr('delete t1 from '.$b.' as t1, '.$b.' as t2 where t1.id > t2.id and t1.'.$c.' = t2.'.$c.''));}

//update structure
static function trigger($b,$ra){$rb=self::sqlcls($b); $rnew=[]; $rold=[];
if(isset($rb['id']))unset($rb['id']); if(isset($rb['up']))unset($rb['up']);
if($rb){$rnew=array_diff_assoc($ra,$rb); $rold=array_diff_assoc($rb,$ra);}//old
if($rnew or $rold){//pr([$rnew,$rold]);
	$bb=self::bktable($b,date('ymdHis')); self::drop($b);
	$rtwo=array_intersect_assoc($ra,$rb);//common
	$rak=array_keys($ra); $rav=array_values($ra);
	$rnk=array_keys($rnew); $rnv=array_values($rnew); $nn=count($rnk);
	$rok=array_keys($rold); $rov=array_values($rold); $no=count($rok);
	$na=count($rnew); $nb=count($rold); $ca=array_keys($rtwo); $cb=array_keys($rtwo);
	if($na==$nb)for($i=0;$i<$nn;$i++)if($rnv[$i]==$rov[$i] or $rnv[$i]!='int'){
		$ca[]=$rnk[$i]; $cb[]=$rok[$i];}
	return 'insert into '.$b.'(id,'.implode(',',$ca).',up) select id,'.implode(',',$cb).',up from '.$bb;}}

//columns
static function dbtypes($b){$rb=[];
$rq=self::qr('select distinct(COLUMN_NAME),DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS where table_name="'.$b.'"');//16777215
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

static function sqlcols($b,$o=0){//old
$rb=self::dbtypes($b); if(!isset($rb))return; //pr($rb);
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

static function sqlcls($db,$a=0,$b=0){//modes
//$a=0:[id],[uid],xxx,[up]; $a=1:[uid],xxx; $a=2:xxx; $a=3:[uid],xxx,[up]; $a=4:xx
//$b=0:array; $b=1:string; $b=2:array_values
$rb=self::dbtypes($db); if(!$rb)return []; //pr($rb);
if($a==4 or $a==3 or $a==2 or $a==1)unset($rb['id']);//full
if($a==4 or $a==3 or $a==2)unset($rb['up']);//all
if($a==4 or $a==3)unset($rb['uid']);//used
if($a==4)array_shift($rb);
if($b==1 or $b==2)$rb=array_keys($rb);
if($b==1)$rb=implode(',',$rb);
return $rb;}

static function sqlcls0($db,$o=0){$a=0;$b=0;//patch
//0:[id,uid,xxx,up];1:id,uid,xxx,up;2:[uid,xxx];3:uid,xxx;4:[xxx];5:xxx,6:[0=>xxx],7:[0=>uid+xxx],8:[0=>xx]
if($o==1){$a=0; $b=1;}
if($o==2){$a=2; $b=0;}
if($o==3){$a=2; $b=1;}
if($o==4){$a=3; $b=0;}
if($o==5){$a=3; $b=1;}
if($o==6){$a=3; $b=2;}
if($o==7){$a=2; $b=2;}
if($o==8){$a=4; $b=2;}
return self::sqlcls($db,$a,$b);}

static function sql_utf8($t){$r=self::read('*',$t,'rr');//exec one time only on non-utf8 tables
foreach($r as $k=>$v){foreach($v as $ka=>$va)$rb[$k][$ka]=utf8_encode($va);
	self::sqlups($t,$rb[$k],$v['id']);}}

//create
static function create_cols($r){$ret=''; $end='';
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
return $ret.$end;}

static function jsoncolfromattr($b,$c,$k){//add col from json attr k in new col c//attr_colour
self::qr('ALTER TABLE '.$b.' ADD '.$c.'_'.$k.' VARCHAR(32) AS (JSON_VALUE('.$c.', "$.'.$k.'"));');
self::qr('CREATE INDEX '.$b.'_'.$c.'_'.$k.'_ix ON '.$b.'('.$c.'_'.$k.');');}

static function modifjsonvar($b,$c,$k,$v,$q=''){//impact colattr
self::qr('UPDATE '.$b.' SET '.$c.' = JSON_REPLACE('.$c.', "$.'.$k.'", "'.$v.'") '.self::setq($q,$b).';');}

//array('id'=>'int','ib'=>'int','val'=>'var');
static function sqlcreate($b,$r,$up=''){
if(!is_array($r) or !$b)return; reset($r);
if($up=='z' && auth(6))self::drop($b);
if($up)$sql=self::trigger($b,$r); //echo $sql;
self::qr('create table if not exists `'.$b.'` (
  `id` int(11) NOT NULL auto_increment,'.self::create_cols($r).'
  `up` timestamp on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM collate utf8_general_ci;');
if(isset($sql))self::qr($sql,1);}

static function dbapp($b){if(class_exists($b)){
if(isset($b::$cols))return array_combine($b::$cols,$b::$typs);}}

static function validcols($r,$b,$rc=[]){
if(!$rc)$rc=self::dbapp($b); if(!$rc)$rc=self::sqlcls($b,2);
foreach($r as $k=>$v){$ty=$rc[$k]??''; if(!$v){
	if($k=='id')$r[$k]='NULL';
	elseif($ty=='int' or $ty=='bint' or $ty=='sint' or $ty=='dec' or $ty=='float' or $ty=='double')$r[$k]='0';//!
	elseif($ty=='date')$r[$k]=date('Y-m-d',time());}
elseif($ty=='int')$r[$k]=(int)$v;}
return $r;}

static function pvalk($p,$db,$o=''){//public
$rc=self::sqlcls($db,3); if($o)$r=[ses('uid')];
foreach($rc as $k=>$v){
	$r[$k]=val($p,$k);
	if($k=='pub')$default=3;
	elseif($v=='int' or $v=='bint' or $v=='sint' or $v=='dec' or $v=='float' or $v=='double')$default=0;
	elseif($v=='date')$default=date('Y-m-d',time());
	else $default='';
	if(!$r[$k])$r[$k]=$default;}
return $r;}

}