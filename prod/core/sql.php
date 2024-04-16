<?php
class sql{
static $lc;
static $db;
static $qr;
static private $r;

function __construct($r){self::boot($r);}//if(!self::$qr)

private static function boot($r){self::$db=$r[3]; self::$r=$r;
self::$lc=$r[0]=='localhost'?1:0;
self::$qr=new mysqli($r[0],$r[1],$r[2],$r[3]) or die(pr($r));
self::$qr->query('set names utf8mb4');
self::$qr->query('set character set utf8mb4');}

static function switch($db){$r=self::$r; $r[3]=$db; self::boot($r);}

#sql
static function qr($sql,$z=''){if($z==1)echo $sql; $rq=mysqli_query(self::$qr,$sql);
if($rq==null)echo mysqli_error(self::$qr).br().$sql.hr(); return $rq;}
static function qfar($r){if($r)return mysqli_fetch_array($r);}
static function qfas($r){if($r)return mysqli_fetch_assoc($r);}
static function qfrw($r){if($r)return mysqli_fetch_row($r);}
static function qfc($r){mysqli_free_result($r);}
static function query($sql,$p,$z=''){if($z==1)echo $sql; $rq=self::qr($sql,$z);
if($rq){$rt=self::format($rq,$p); self::qfc($rq); return $rt;}}
static function escape($v){return mysqli_real_escape_string(self::$qr,stripslashes($v));}
static function escaper($r){foreach($r as $k=>$v)$rt[]=self::escape($v); return $rt;}
static function nid(){return mysqli_insert_id(self::$qr);}
static function close(){mysqli_close(self::$qr);}

static function format($rq,$p){$rt=[];
if($p=='rq')return $rq;
if($p=='ry')return self::qfar($rq);
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
if($d=='all')$d=self::cols($b,0,1);
elseif($d=='used')$d=self::cols($b,3,1);
if(!$d)$d='*';
return $d;}

static function where($r,$o=''){
$rb=[]; $rc=[]; $w=''; $ret='';
foreach($r as $k=>$v){
	$p1=substr($k,0,1); $p2=substr($k,1);
	if($k=='_order')$w.=' order by '.$v;
	elseif($k=='_group')$w.=' group by '.$v;
	elseif($k=='_limit')$w.=' limit '.$v;
	elseif($k=='or')$rc=self::where($v,1);//'or'=>['!status'=>'3','!typ'=>'0']
	elseif($p1=='|')$rc[]=$p2.'="'.self::escape($v).'"';//or
	elseif($p1=='!')$rb[]=$p2.'!="'.self::escape($v).'"';
	elseif(substr($k,0,2)=='>=')$rb[]=substr($k,2).'>="'.self::escape($v).'"';
	elseif(substr($k,0,2)=='<=')$rb[]=substr($k,2).'<="'.self::escape($v).'"';
	elseif($p1=='>')$rb[]=$p2.'>"'.self::escape($v).'"';
	elseif($p1=='<')$rb[]=$p2.'<"'.self::escape($v).'"';
	elseif($p1=='%')$rb[]=$p2.' like "%'.self::escape($v).'%"';
	elseif(substr($v,0,8)=='PASSWORD')$rb[]=$k.'='.$v;
	elseif(is_array($v))$rb[]=$k.' in ("'.implode('","',$v).'")';
	elseif($k=='numday')$rb[]='date_format(up,"%y%m%d")="'.$v.'"';
	elseif($v==='not null')$rb[]=$k.' is not null';
	elseif($v==='is null')$rb[]=$k.' is null';
	elseif(is_numeric($k))$rb[]=$v;
	elseif($v)$rb[]=$k.'="'.self::escape($v).'"';}
if($o)return $rb;
if($rc)$rb[]='('.implode(' or ',$rc).')';
if($rb)$ret=implode(' and ',$rb);
if($ret)return 'where '.$ret.$w;}

static function setq($q,$b){
if(is_numeric($q))$q=[$b.'.id'=>$q];
if(is_array($q))return self::where($q);
else return $q;}

static function escr($r,$b=''){$rb=[];
if($b)$r=self::vrf($r,$b);
foreach($r as $k=>$v)$rb[]=$v=='null'?$k.'=NULL':$k.'="'.self::escape($v).'"';
return implode(',',$rb);}

static function insertions($r,$b='',$o=''){
if($b)$r=self::vrf($r,$b);
foreach($r as $k=>$v){
	if($v===0)$rb[$k]='0';
	elseif($v==='0')$rb[$k]='0';
	elseif(!$v)$rb[$k]='""';
	elseif($v==='NULL')$rb[$k]='NULL';
	elseif(substr($v,0,8)=='PASSWORD')$rb[$k]=$v;
	else $rb[$k]='"'.self::escape($v).'"';}
if($o==5)return '('.implode(',',$rb).')';
elseif($o)return '(NULL,'.implode(',',$rb).')';
else return '(NULL,'.implode(',',$rb).',"'.date('Y-m-d H:i:s',time()).'")';}

static function insertions2($r,$b='',$o=''){$rb=[];//[[1,'hello'],[2,hey]]
foreach($r as $k=>$v)$rb[]=self::insertions($v,$b,$o);
return implode(',',$rb);}

//self::read('id','qda','rv',['id'=>$id]);
static function read($d,$b,$p='',$q='',$z=''){
$d=self::rqcols($d,$b); $q=self::setq($q,$b); $rt=[]; if($p=='v')$rt='';
if($b)$rq=self::qr('select '.$d.' from '.$b.' '.$q,$z);
if(!empty($rq->num_rows)){$rt=self::format($rq,$p); self::qfc($rq);}
return $rt;}

//join b2 to b1, associating b2.$key to b1.id
static function inner($d,$b1,$b2,$key,$p,$q='',$z=''){//b2 is on the right, let left empty
$q='inner join '.$b2.' on '.$b1.'.'.$key.'='.$b2.'.id '.self::setq($q,$b1);
return self::read($d,$b1,$p,$q,$z);}

static function join($d,$b1,$b2,$key,$p,$q='',$z=''){
$q='left join '.$b2.' on '.$b1.'.'.$key.'='.$b2.'.id '.self::setq($q,$b1);
return self::read($d,$b1,$p,$q,$z);}

//[[$b1,$k1,$b2,$k2],[$b1,$k1,$b3,$k3]]
static function inr($d,$r,$p,$q='',$z=''){$w=''; $b='';
foreach($r as $k=>$v){$w.='join '.$v[2].' on '.$v[0].'.'.$v[1].'='.$v[2].'.'.$v[3].' '; if(!$b)$b=$v[0];}
return self::read($d,$b,$p,$w.self::setq($q,$b),$z);}

static function outr($d,$r,$p,$q='',$z=''){$w=''; $b='';
foreach($r as $k=>$v){$w.='left outer join '.$v[2].' on '.$v[0].'.'.$v[1].'='.$v[2].'.'.$v[3].' '; if(!$b)$b=$v[0];}
return self::read($d,$b,$p,$w.self::setq($q,$b),$z);}

static function sav2($b,$r,$o='',$x='',$z='',$vd=''){
if(auth(6) && $x){self::backup($b); self::trunc($b);}
$sql='insert into '.$b.' values '.self::insertions2($r,$vd?$b:'',$o);
$rq=self::qr($sql,$z); return self::nid();}

static function sav($b,$r,$z='',$o='',$vd=''){
$sql='insert into '.$b.' values '.self::insertions($r,$vd?$b:'',$o);
$rq=self::qr($sql,$z); return self::nid();}
static function up($b,$d,$v,$q,$col='',$z=''){if($col)$q=[$col=>$q];
self::qr('update '.$b.' set '.self::escr([$d=>$v]).' '.self::setq($q,$b),$z);}
static function up2($b,$r,$q,$z='',$vd=''){
self::qr('update '.$b.' set '.self::escr($r,$vd?$b:'').' '.self::setq($q,$b),$z);}
static function savif($b,$r,$z=''){$ex=self::read('id',$b,'v',$r);
if(!$ex)$ex=self::sav($b,$r); return $ex;}
static function savup($b,$r,$rb=[],$z=''){$ex=self::read('id',$b,'v',$rb);
if(!$ex)return self::sav($b,$r+$rb,$z); else return self::up2($b,$r,$ex,$z);}
static function del($b,$q,$col=''){if($col)$q=[$col=>$q];
self::qr('delete from '.$b.' '.self::setq($q,$b));}

#sql_maintenance
static function reflush($b,$o=''){self::qr('alter table '.$b.' order by id');}
//static function reflush_ai($b){$id=self::lastid($b)+1; self::qr('alter table '.$b.' auto_increment='.$id.'');}
static function lastid($b){return self::read('id',$b,'v','order by id desc limit 1');}
static function trunc($b){self::qr('truncate '.$b);}
static function drop($b){self::qr('drop table '.$b);}
static function rn($b,$bb){self::qr('rename table '.$b.' to '.$bb.';');}
static function cp($b,$bb){
self::qr('create table '.$bb.' like '.$b);
self::qr('insert into '.$bb.' select * from '.$b); return $bb;}
static function backup($b,$d=''){$bb='z_'.$b.'_'.$d;
if(self::ex($bb))self::drop($bb);
self::qr('create table '.$bb.' like '.$b);
//self::qr('alter table '.$bb.' add primary key (id)');
self::qr('insert into '.$bb.' select * from '.$b);
return $bb;}
static function rollback($b,$d=''){$bb='z_'.$b.'_'.$d;
if(!self::ex($bb))return; self::trunc($b);
self::qr('alter table '.$b.' auto_increment=1');
self::qr('insert into '.$b.' select * from '.$bb.'');
return $b;}
static function ex($b){$rq=self::qr('show tables like "'.$b.'"');
return mysqli_num_rows($rq)>0;}

static function tuples($b,$c){
$sql='select count(*) as tuples, '.$c.' from '.$b.' group by '.$c.' having count(*)>1 order by tuples desc';
return self::qfrw(self::qr($sql));}
static function doublons($b,$c){
return self::qfrw(self::qr('select count(*) as nbr_doublon, '.$c.' from '.$b.' group by '.$c.' having count(*)>1'));}
static function killdoublons($b,$c){$b=$_SESSION[$b]; if(auth(6))
return self::qfrw(self::qr('delete t1 from '.$b.' as t1, '.$b.' as t2 where t1.id > t2.id and t1.'.$c.' = t2.'.$c.''));}

//update structure
static function trigger($b,$ra){
if(!self::ex($b))return;
$rb=self::cols($b); $rnew=[]; $rold=[];
if(isset($rb['id']))unset($rb['id']); if(isset($rb['up']))unset($rb['up']);
if($rb){$rnew=array_diff_assoc($ra,$rb); $rold=array_diff_assoc($rb,$ra);}//old
if($rnew or $rold){//pr([$rnew,$rold]);
	$bb=self::backup($b,date('ymdHis')); self::drop($b);
	$rtwo=array_intersect_assoc($ra,$rb);//common
	$rak=array_keys($ra); $rav=array_values($ra);
	$rnk=array_keys($rnew); $rnv=array_values($rnew); $nn=count($rnk);
	$rok=array_keys($rold); $rov=array_values($rold); $no=count($rok);
	$na=count($rnew); $nb=count($rold); $ca=array_keys($rtwo); $cb=array_keys($rtwo);
	if($na==$nb)for($i=0;$i<$nn;$i++)if($rnv[$i]==$rov[$i] or $rnv[$i]!='int'){
		$ca[]=$rnk[$i]; $cb[]=$rok[$i];}
	return 'insert into '.$b.'(id,'.implode(',',$ca).',up) select id,'.implode(',',$cb).',up from '.$bb;}}

//columns
static function types($b){$rb=[];
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
	if($type=='date')$type='date';
	$rb[$r['COLUMN_NAME']]=$type;}
return $rb;}

//$a=0:[id],[uid],xxx,[up]; $a=1:[uid],xxx; $a=2:xxx; $a=3:[uid],xxx,[up]; $a=4:xx
//$b=0:array; $b=1:string; $b=2:array_values
static function cols($db,$a=0,$b=0){//modes
$rb=self::types($db); if(!$rb)return []; //pr($rb);
if($a==4 or $a==3 or $a==2 or $a==1)unset($rb['id']);//full
if($a==4 or $a==3 or $a==2)unset($rb['up']);//all
if($a==4 or $a==3)unset($rb['uid']);//used
if($a==4)array_shift($rb);
if($b==1 or $b==2)$rb=array_keys($rb);
if($b==1)$rb=implode(',',$rb);
return $rb;}

static function utf8($t){$r=self::read('*',$t,'rr');//exec one time only on non-utf8 tables
foreach($r as $k=>$v){foreach($v as $ka=>$va)$rb[$k][$ka]=str::utf8enc($va);
	self::up2($t,$rb[$k],$v['id']);}}

//create
static function create_cols($r){$ret=''; $end='';
//$collate='collate utf8mb4_uniocode_ci'; $set='CHARACTER SET utf8mb4';
foreach($r as $k=>$v)
if($v=='int')$ret.='`'.$k.'` int(11) default NULL,'."\n";
elseif($v=='bint')$ret.='`'.$k.'` bigint(36) NULL default NULL,'."\n";
elseif($v=='dec')$ret.='`'.$k.'` decimal(20,20) NULL default NULL,'."\n";
elseif($v=='float')$ret.='`'.$k.'` float(20,2) NULL default NULL,'."\n";
elseif($v=='double')$ret.='`'.$k.'` double NULL default NULL,'."\n";
elseif($v=='var')$ret.='`'.$k.'` varchar(255) NOT NULL default "",';
elseif($v=='bvar')$ret.='`'.$k.'` varchar(1020) NOT NULL default "",';
elseif($v=='svar')$ret.='`'.$k.'` varchar(60) NOT NULL default "",';
elseif($v=='tiny')$ret.='`'.$k.'` tinytext,';
elseif($v=='text')$ret.='`'.$k.'` mediumtext,';//'.$set.'
elseif($v=='long')$ret.='`'.$k.'` longtext,';
elseif($v=='date')$ret.='`'.$k.'` date NOT NULL,';
elseif($v=='time')$ret.='`'.$k.'` datetime NOT NULL,';
elseif($v=='json')$ret.='`'.$k.'` json,';
//elseif($v=='json'){$ret.='`'.$k.'` mediumtext,'."\n"; $end='CHECK ('.$k.' IS NULL OR JSON_VALID('.$k.')),'."\n";}
//elseif($v=='enum')$ret.=''.$k.'` enum ("'.implode('","',$k).'") NOT NULL,';
return $ret.$end;}

static function jsoncolfromattr($b,$c,$k){//add col from json attr k in new col c//attr_colour
self::qr('ALTER TABLE '.$b.' ADD '.$c.'_'.$k.' VARCHAR(32) AS (JSON_VALUE('.$c.', "$.'.$k.'"));');
self::qr('CREATE INDEX '.$b.'_'.$c.'_'.$k.'_ix ON '.$b.'('.$c.'_'.$k.');');}

static function modifjsonvar($b,$c,$k,$v,$q=''){//impact colattr
self::qr('UPDATE '.$b.' SET '.$c.' = JSON_REPLACE('.$c.', "$.'.$k.'", "'.$v.'") '.self::setq($q,$b).';');}

//['id'=>'int','ib'=>'int','val'=>'var'];
static function create($b,$r,$up=''){
if(!is_array($r) or !$b)return; reset($r);
if($up=='z' && auth(6))self::drop($b);
//if($up)$sql=self::trigger($b,$r);
self::qr('create table if not exists `'.$b.'` (
  `id` int(11) NOT NULL auto_increment,'.self::create_cols($r).'
  `up` timestamp on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB collate utf8mb4_unicode_ci;',0);
if(isset($sql))self::qr($sql);}

static function dbapp($b){if(class_exists($b)){
if(isset($b::$cols))return array_combine($b::$cols,$b::$typs);}}

static function vrf($r,$b,$rc=[]){
if(!$rc)$rc=self::dbapp($b); if(!$rc)$rc=self::cols($b,2);
foreach($r as $k=>$v){$ty=$rc[$k]??'';
	if($k=='id')$r[$k]=is_numeric($v)&&$v<=2147483647?$v:'NULL';//if($o)
	switch($ty){
	case('int'):$r[$k]=is_numeric($v)&&$v<=2147483647?$v:0;break;
	case('bint'):$r[$k]=is_numeric($v)&&strlen($v)<27?$v:0;break;
	case('sint'):$r[$k]=is_numeric($v)&&$v<32767?$v:0;break;
	//case('dec'):$r[$k]=is_numeric($v)&&$v<=2147483647?$v:0;break;
	//case('float'):$r[$k]=is_numeric($v)&&$v<=2147483647?$v:0;break;
	//case('double'):$r[$k]=is_numeric($v)&&$v<=2147483647?$v:0;break;
	case('var'):$r[$k]=is_string($v)&&strlen($v)<256?$v:substr($v,0,255);break;
	case('bvar'):$r[$k]=is_string($v)&&strlen($v)<1020?$v:substr($v,0,1020);break;
	case('svar'):$r[$k]=is_string($v)&&strlen($v)<60?$v:substr($v,0,60);break;
	case('text'):$r[$k]=strlen($v)<=16777215?$v:substr($v,0,16777215);break;
	case('long'):$r[$k]=$v!=null?$v:'';break;
	case('json'):$r[$k]=$v!=null?$v:'';break;
	//case('enum(01)'):$r[$k]=$v===0||$v===1?$v:null;break;
	case('psw'):$r[$k]='PASSWORD('.$v.')';break;
	case('date'):$r[$k]='NOW()';break;
	case('time'):$r[$k]=is_numeric($v)?$v:0;break;}}
return $r;}

static function innodb(){
$sql="SELECT CONCAT('ALTER TABLE ',table_schema,'.',table_name,' ENGINE=InnoDB;')
FROM information_schema.tables
WHERE 1=1
    AND engine = 'MyISAM'
    AND table_schema NOT IN ('information_schema', 'mysql', 'performance_schema');";
$r=sql::query($sql,'rv'); pr($r);
foreach($r as $k=>$v)sql::qr($v);}

//assume integrity
static function pvalk($p,$db,$o=''){//public
$rc=self::cols($db,3); if($o)$r=[ses('uid')];
foreach($rc as $k=>$v){
	$r[$k]=$p[$k]??'';
	if($k=='pub')$default=3;
	elseif($v=='int' or $v=='bint' or $v=='sint' or $v=='dec' or $v=='float' or $v=='double')$default=0;
	elseif($v=='date')$default=date('Y-m-d',time());
	else $default='';
	if(!$r[$k])$r[$k]=$default;}
return $r;}

}
?>