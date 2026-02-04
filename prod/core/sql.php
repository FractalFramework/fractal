<?php
class sql{
static $lc;
static $db;
static $qr;
static $er=[];
static private $r=[];

function __construct($r){self::boot($r);}//if(!self::$qr)

private static function boot($r){self::$db=$r[3]; self::$r=$r;
self::$lc=$r[0]=='localhost'?1:0;
self::$qr=new mysqli($r[0],$r[1],$r[2],$r[3]) or die(pr($r));
self::$qr->query('set names utf8mb4');
self::$qr->query('set character set utf8mb4');}

static function switch($db){$r=self::$r; $r[3]=$db; self::boot($r);}
static function r(){return self::$r;}

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
if($p=='v'){$r=self::qfrw($rq); return $r[0]??'';}
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
	case('kvv'):$rt[$r[0]]=[$r[1],$r[2]]; break;
	case('kkr'):$rt[$r[0]][$r[1]][]=$r[2]; break;
	case('kkk'):$rt[$r[0]][$r[1]][$r[2]]=1; break;
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
	elseif($p1=='[')$rb[]=$p2.' like "'.self::escape($v).'%"';
	elseif($p1==']')$rb[]=$p2.' like "%'.self::escape($v).'"';
	elseif($p1=='(')$rb[]=$p2.' in ("'.implode('","',$v).'")';
	elseif(is_array($v))$rb[]=$k.' in ("'.implode('","',$v).'")';
	elseif($k=='numday')$rb[]='date_format(up,"%y%m%d")="'.$v.'"';
	elseif($v=='not empty')$rb[]=$k.' <> ""';
	elseif($v=='is empty')$rb[]=$k.' = ""';
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

#vrf
static function dbapp($b){if(class_exists($b)){
if(isset($b::$cols))return array_combine($b::$cols,$b::$typs);}}

static function vrf($r,$b,$rc=[]){
if(!$rc)$rc=self::dbapp($b); if(!$rc)$rc=self::cols($b,2);
foreach($r as $k=>$v){$ty=$rc[$k]??'';
if($k=='id')$r[$k]=is_numeric($v)&&$v<=2147483647?$v:'NULL';//if($o)
$r[$k]=match($ty){
'int'=>is_numeric($v)&&$v<=2147483647?$v:0,
'bint'=>is_numeric($v)&&strlen($v)<27?$v:0,
'sint'=>is_numeric($v)&&$v<32767?$v:0,
//'dec'=>is_numeric($v)&&$v<=2147483647?$v:0,
//'float'=>is_numeric($v)&&$v<=2147483647?$v:0,
//'double'=>is_numeric($v)&&$v<=2147483647?$v:0,
'var'=>is_string($v)&&strlen($v)<256?$v:substr($v,0,255),
'bvar'=>is_string($v)&&strlen($v)<1020?$v:substr($v,0,1020),
'svar'=>is_string($v)&&strlen($v)<60?$v:substr($v,0,60),
'text'=>strlen($v)<=16777215?$v:substr($v,0,16777215),
'long'=>$v!=null?$v:'',
'json'=>$v!=null?$v:'',
//'enum(01)'=>$v===0||$v===1?$v:null,
'date'=>'NOW()',
'time'=>is_numeric($v)?$v:0,
default=>$v!=null?$v:''};}
return $r;}

#read

static function call($sql,$p='',$z=''){$rq=self::qr($sql,$z);
if($rq){$rt=self::format($rq,$p); self::qfc($rq);}//!empty($rq->num_rows)
return $rt;}

//self::read('id','qda','rv',['id'=>$id]);
static function read($d,$b,$p='',$q='',$z=''){
$d=self::rqcols($d,$b); $q=self::setq($q,$b); $rt=[]; if($p=='v')$rt='';
if($b)$rq=self::qr('select '.$d.' from '.$b.' '.$q,$z);
if($rq){$rt=self::format($rq,$p); self::qfc($rq);}
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

#write
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
	else $rb[$k]='"'.self::escape($v).'"';}
if($o==5)return '('.implode(',',$rb).')';
elseif($o)return '(NULL,'.implode(',',$rb).')';
else return '(NULL,'.implode(',',$rb).',"'.date('Y-m-d H:i:s',time()).'")';}

static function insertions2($r,$b='',$o=''){$rb=[];//[[1,'hello'],[2,hey]]
foreach($r as $k=>$v)$rb[]=self::insertions($v,$b,$o);
return implode(',',$rb);}

static function sav2($b,$r,$o='',$x='',$z='',$vd=''){
if(auth(6) && $x){self::backup($b); self::trunc($b);}
$sql='insert into '.$b.' values '.self::insertions2($r,$vd?$b:'',$o);
$rq=self::qr($sql,$z); return self::nid();}

static function sav($b,$r,$z='',$o='',$vd=''){
$sql='insert into '.$b.' values '.self::insertions($r,$vd?$b:'',$o);
$rq=self::qr($sql,$z); return self::nid();}
static function upd($b,$r,$q,$z='',$vd=''){
self::qr('update '.$b.' set '.self::escr($r,$vd?$b:'').' '.self::setq($q,$b),$z);}
static function savif($b,$r,$z=''){$ex=self::read('id',$b,'v',$r,$z);
if(!$ex)$ex=self::sav($b,$r,$z); return $ex;}
static function savup($b,$r,$rb=[],$z=''){$ex=self::read('id',$b,'v',$rb);
if(!$ex)return self::sav($b,$r+$rb,$z); else return self::upd($b,$r,$ex,$z);}
static function del($b,$q,$col='',$z=''){if($col)$q=[$col=>$q];
self::qr('delete from '.$b.' '.self::setq($q,$b),$z);}

#usage

#columns
static function schema($b){return 'select distinct(COLUMN_NAME),DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS where table_name="'.$b.'"';}

static function sqcols($b){
return self::call(self::schema($b),'kvv');}

static function coltypes($b){$rt=[];
$r=self::sqcols($b);
if($r)foreach($r as $k=>[$ty,$sz]){
$rt[$k]=match($ty){
'int'=>$sz==36?'bint':'int',
'varchar'=>match($sz){'2'=>'var2','3'=>'var3','60'=>'svar','1000'=>'bvar',default=>'var'},
'longtext'=>'long',
'mediumtext'=>'text',
'tinytext'=>'tiny',
'bigint'=>'bint',
'decimal'=>'dec',
'float'=>'float',
'double'=>'double',
'json'=>'json',
'date'=>'date',
'timestamp'=>'date',
default=>$k};}
return $rt;}

//$a=0:[id],[uid],xxx,[up]; $a=1:[uid],xxx; $a=2:xxx; $a=3:[uid],xxx,[up]; $a=4:xx
//$b=0:array; $b=1:string; $b=2:array_values
static function cols($db,$a=0,$b=0){//modes
$rb=self::coltypes($db); if(!$rb)return []; //pr($rb);
if($a==4 or $a==3 or $a==2 or $a==1)unset($rb['id']);//full
if($a==4 or $a==3 or $a==2)unset($rb['up']);//all
if($a==4 or $a==3)unset($rb['uid']);//used
if($a==4)array_shift($rb);
if($b==1 or $b==2)$rb=array_keys($rb);
if($b==1)$rb=implode(',',$rb);
return $rb;}

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

#alter table
static function array_diff_order($ra,$rb){$n=count($ra);
$rak=array_keys($ra); $rbk=array_keys($rb); //rb in the good order//rv
for($i=0;$i<$n;$i++)if($rak[$i]!=$rbk[$i]??'')return true;}

static function reorder($b,$ra,$rb){
if(isset($ra['key']))unset($ra['key']); $ca=array_keys($ra); if(!$ca)return; $bb='z_'.$b;
self::backup($b,'reorder'); $bb=self::drop($b); self::create_table($b,$ra);
$sql='insert into '.$b.'('.implode(',',$ca).') select '.implode(',',$ca).' from '.$bb;
self::qr($sql,1); if(self::$er['qr']??'')self::rollback($b,'reorder');
self::$er+=['reorder_table:'=>$b]+self::$er??[];}//,'ra'=>$ra,'rb'=>$rb

static function findposition($d,$r){$n=count($r);
for($i=0;$i<$n;$i++)if($r[$i]==$d){
	if($r[$i-1]??'')return 'after '.$r[$i-1];
	elseif($r[$i+1]??'')return 'before '.$r[$i+1];}}

static function alter($b,$ra,$rb){if(!self::ex($b))return;
$rnew=[]; $rold=[]; $ca=[]; $rbb=[]; //pr([$ra,$rb]);
if(isset($ra['key']))unset($ra['key']); $na=count($ra); $nb=count($rb);
if($rb){$rnew=array_diff_assoc($ra,$rb); $rold=array_diff_assoc($rb,$ra);} //pr([$rnew,$rold]);
if($rnew or $rold){self::backup($b,'alter');
	$rnk=array_keys($rnew); $rok=array_keys($rold); $rak=array_keys($ra); //pr([$rnk,$rok,$rak]);
	if($na==$nb)foreach($rnk as $k=>$v){
		$ca[]='change `'.$rok[$k].'` `'.$v.'` '.self::assign_types($rnew[$v]);}
	elseif($na>$nb)foreach($rnk as $k=>$v){$fpos=self::findposition($v,$rak);
		$ca[]='add `'.$v.'` '.self::assign_types($rnew[$v]).' '.$fpos;}
	elseif($na<$nb)foreach($rold as $k=>$v)$ca[]='drop `'.$k.'`';
	$ca=array_flip(array_flip($ca)); //pr($ca);
	if($ca)self::qr('alter table `'.$b.'` '.implode(', ',$ca).';',1);
	self::$er+=['alter_table:'=>$b,'rnew'=>$rnew,'rold'=>$rold,'rnk'=>$rnk,'ca'=>$ca,'ra'=>$ra,'rb'=>$rb];}
$rb=self::cols($b,2); //pr($rb);
if($ra!=$rb && self::array_diff_order($ra,$rb))self::reorder($b,$ra,$rb);}

//create
static function assign_types($d){
return match($d){
'int'=>'int(11) default NULL',
'bint'=>'bigint(36) NULL default NULL',
'dec'=>'decimal(20,20) NULL default NULL',
'float'=>'float(20,2) NULL default NULL',
'double'=>'double NULL default NULL',
'var'=>'varchar(255) NOT NULL default ""',
'bvar'=>'varchar(1000) NOT NULL default ""',
'svar'=>'varchar(60) NOT NULL default ""',
'var3'=>'varchar(3) NOT NULL',
'var2'=>'varchar(2) NOT NULL',
'tiny'=>'tinytext',
'text'=>'mediumtext',//'.$set.'
'long'=>'longtext',
'date'=>'date NOT NULL',
'time'=>'datetime NOT NULL',
//'json'=>'mediumtext, CHECK ('.'IS NULL OR JSON_VALID('.$k.'))',
//'enum'=>'enum ("'.implode('","',$k).'") NOT NULL',
'json'=>'json',
default=>''};}

static function create_cols($r){$rt=[];
//$collate='collate utf8mb4_uniocode_ci'; $set='CHARACTER SET utf8mb4';
foreach($r as $k=>$v)$rt[]=$k.' '.self::assign_types($v);
return join(",\n",$rt);}

static function create_table($b,$r){
self::qr('create table if not exists `'.$b.'` (
  `id` int(11) NOT NULL auto_increment,'.self::create_cols($r).',
  `up` timestamp on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB collate utf8mb4_unicode_ci;',0);
self::$er['created:']=$b;}

//['id'=>'int','ib'=>'int','val'=>'var'];
static function create($b,$r,$up=''){
if(!is_array($r) or !$b)return; reset($r);
$rb=self::cols($b,2); //pr($rb);
if(!$rb)self::create_table($b,$r);
elseif($up==1)self::alter($b,$r,$rb);
elseif($up==2)self::rollback($b);
elseif($up=='z' && auth(6))self::drop($b);}

#sql_maintenance
static function reflush($b,$o=''){self::qr('alter table '.$b.' order by id');}
//static function reflush_ai($b){$id=self::lastid($b)+1; self::qr('alter table '.$b.' auto_increment='.$id.'');}
static function lastid($b){return self::read('id',$b,'v','order by id desc limit 1');}
static function trunc($b){self::qr('truncate '.$b);}
static function drop($b){if(auth(6))self::qr('drop table '.$b);}
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

//repair
static function jsoncolfromattr($b,$c,$k){//add col from json attr k in new col c//attr_colour
self::qr('ALTER TABLE '.$b.' ADD '.$c.'_'.$k.' VARCHAR(32) AS (JSON_VALUE('.$c.', "$.'.$k.'"));');
self::qr('CREATE INDEX '.$b.'_'.$c.'_'.$k.'_ix ON '.$b.'('.$c.'_'.$k.');');}

static function modifjsonvar($b,$c,$k,$v,$q=''){//impact colattr
self::qr('UPDATE '.$b.' SET '.$c.' = JSON_REPLACE('.$c.', "$.'.$k.'", "'.$v.'") '.self::setq($q,$b).';');}

static function innodb(){
$sql="SELECT CONCAT('ALTER TABLE ',table_schema,'.',table_name,' ENGINE=InnoDB;')
FROM information_schema.tables
WHERE 1=1
    AND engine = 'MyISAM'
    AND table_schema NOT IN ('information_schema', 'mysql', 'performance_schema');";
$r=self::query($sql,'rv'); pr($r);
foreach($r as $k=>$v)self::qr($v);}

static function utf8($t){$r=self::read('*',$t,'rr');//exec one time only on non-utf8 tables
foreach($r as $k=>$v){foreach($v as $ka=>$va)$rb[$k][$ka]=str::utf8enc($va);
	self::upd($t,$rb[$k],$v['id']);}}

}
?>