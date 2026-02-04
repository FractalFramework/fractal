<?php
class sqb{
static $lc;
static $db;
static $qr;
static $er=[];
static private $r=[];

function __construct(){if(!self::$qr)self::dbq();}

static function dbq(){[$h,$n,$p,$b]=sql::r(); self::$db=$b;
$dsn='mysql:host='.$h.';dbname='.$b.';charset=utf8mb4';
$ro=[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_PERSISTENT=>true,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_NUM];//Pdo\Mysql::
self::$qr=new PDO($dsn,$n,$p,$ro);}

#render
static function rq(){if(!self::$qr)self::dbq(); return self::$qr;}
static function qfar($r){return $r->fetchAll(PDO::FETCH_BOTH);}
static function qfas($r){return $r->fetchAll(PDO::FETCH_ASSOC);}
static function qfrw($r){return $r->fetchAll(PDO::FETCH_NUM);}
static function nid(){return self::$qr->lastInsertId();}
static function qr($sql,$z=''){$qr=self::rq(); if($z)self::$er[]=$sql;
try{return $qr->query($sql);}catch(Exception $e){self::$er=$e->getMessage();}}

static function format($r,$p){
$rt=[];  if($p=='v')$rt='';
if($p=='rq')return $r;
if($p=='ry' or $p=='ra' or $p=='rw')return array_first($r);
if($p=='phy' or $p=='rid')$p='krr';
foreach($r as $k=>$v)switch($p){
	case('v'):$rt=$v[0]; break;
	case('k'):$rt[$v[0]]=1; break;
	case('ar'):$rt[]=$v; break;
	case('rr'):$rt[]=$v; break;
	case('rv'):$rt[]=$v[0]; break;
	case('kv'):$rt[$v[0]]=$v[1]; break;
	case('kk'):$rt[$v[0]][$v[1]]=1; break;
	case('vv'):$rt[]=[$v[0],$v[1]]; break;
	case('kr'):$rt[$v[0]][]=$v[1]; break;
	case('kkv'):$rt[$v[0]][$v[1]]=$v[2]; break;
	case('kkk'):$rt[$v[0]][$v[1]][$v[2]]=1; break;
	case('kkkv'):$rt[$v[0]][$v[1]][$v[2]]=$v[3]; break;
	case('kvv'):$rt[$v[0]]=[$v[1],$v[2]]; break;
	case('kkr'):$rt[$v[0]][$v[1]][]=$v[2]; break;
	case('krr'):$rt[$v[0]][]=$v; break;
	case('kx'):$rt[$v[0]]=explode('/',$v[1]); break;
	case('ks'):$rt[$v[0]]=explode(' ',$v[1]); break;
	case('kad'):if(isset($rt[$v[0]]))$rt[$v[0]]+=1; else $rt[$v[0]]=1; break;
	case('id'):$k=array_shift($v); $rt[$k]=$v; break;
	case('index'):$rt[$v[0]]=$v; break;
	default:$rt[]=$v; break;}
return $rt;}

#build
static function where($r){
$rb=[]; $rc=[]; $rt=[]; $w='';
if(is_numeric($r))$r=['id'=>$r]; $i=0;
if(is_array($r))foreach($r as $k=>$v){$i++;
	$c=substr($k,0,1); $kb=substr($k,1); $kc=$kb.$i;
	if($k=='_order')$w=' order by '.$v;
	elseif($k=='_group')$w.=' group by '.$v;
	elseif($k=='_limit')$w.=' limit '.$v;
	elseif($c=='<'){$rb[]=$kb.'<:'.$kc; $rt[$kc]=$v;}
	elseif($c=='>'){$rb[]=$kb.'>:'.$kc; $rt[$kc]=$v;}
	elseif($c=='{'){$rb[]=$kb.'<=:'.$kc; $rt[$kc]=$v;}
	elseif($c=='}'){$rb[]=$kb.'>=:'.$kc; $rt[$kc]=$v;}
	elseif($c=='!'){$rb[]=$kb.'!=:'.$kc; $rt[$kc]=$v;}
	elseif($c=='%'){$rb[]=$kb.' like :'.$kc; $rt[$kc]='%'.$v.'%';}
	elseif($c=='['){$rb[]=$kb.' like :'.$kc; $rt[$kc]=''.$v.'%';}
	elseif($c==']'){$rb[]=$kb.' like :'.$kc; $rt[$kc]='%'.$v.'';}
	elseif($c=='~'){$rb[]=$kb.' like :'.$kc; $rt[$kc]=''.$v.'';}
	elseif($c=='|'){$rc[]=$kb.'=:'.$kc; $rt[$kc]=$v;}//or
/**/elseif($k=='or'){//'or'=>['!status'=>'3','!typ'=>'0']
		[$rd,$q2]=self::where($v,$i); $q2=str_replace('where ','',$q2);
		foreach($rd as $ka=>$va){$rc[]=$ka.'=:'.$ka.$i; $rt[$ka.$i]=$va; $i++;}}
	elseif($c=='-'){$rb[]='substring('.$kb.',1,'.strlen($v).')!=:'.$kc; $rt[$kc]=$v;}
	elseif($c=='+'){$rb[]='substring('.$kb.',1,'.strlen($v).')=:'.$kc; $rt[$kc]=$v;}
	elseif($c=='&'){$rb[]=$kb.' between :'.$kc.'1 and :'.$kc.'2'; $rt[$kc.'1']=$v[0]; $rt[$kc.'2']=$v[1];}
	elseif($c=='('){foreach($v as $ka=>$va)$rta['in'.$ka]=$va; $rt+=$rta;
		$rb[]=$kb.' in (:'.implode(',:',array_keys($rta)).')';}
	elseif($c==')'){foreach($v as $ka=>$va)$rta['nin'.$ka]=$va; $rt+=$rta;
		$rb[]=$kb.' not in (:'.implode(',:',array_keys($rta)).')';}
	elseif($k==='not null'){$rb[]=$kb.' is not null';}//?
	elseif($k==='is null'){$rb[]=$kb.' is null';}
	elseif($k==='null'){$rb[]=$kb.'=NULL';}
	else{$rb[]=$k.'=:'.$k; $rt[$k]=$v;}}
if($rc)$rb[]='('.implode(' or ',$rc).')';
$q=implode(' and ',$rb); if($q)$q='where '.$q; if($w)$q.=$w;
return [$rt,$q];}

static function setq($q,$b){
if(is_numeric($q))$q=[$b.'.id'=>$q];
if(is_array($q))return self::where($q);
else return ['',$q];}

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

#req
static function fetch($stmt,$p){$rt=[];
if($p=='a' or $p=='ar' or $p=='r' or $p=='rr')$rt=$stmt->fetchAll(PDO::FETCH_ASSOC);
else $rt=$stmt->fetchAll(PDO::FETCH_NUM);
return $rt;}

static function bind($stmt,$r){
foreach($r as $k=>$v)$stmt->bindValue(':'.$k,$v,is_numeric($v)?PDO::PARAM_INT:PDO::PARAM_STR);}

static function prep($sql,$r,$p,$z=''){
$qr=self::rq(); if($z)echo self::see($sql,$r);
//try{}catch(Exception $e){er($e->getMessage());}
$stmt=$qr->prepare($sql);
self::bind($stmt,$r);
$stmt->execute();
//$stmt->closeCursor();
return self::fetch($stmt,$p);}

static function query($sql,$r,$p,$z=''){
$rq=self::prep($sql,$r,$p,$z);
if($rq)$rt=sqb::format($rq,$p); else $rt=$p=='v'?'':[];
return $rt;}

static function see($sql,$r){
foreach($r as $k=>$v)$sql=str_replace(':'.$k,'"'.$v.'"',$sql);
return $sql;}

#req
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
return $d;}

#read
static function call($sql,$p,$z=''){
return self::format(self::fetch(self::qr($sql,$z),$p),$p);}

//self::read('id','table','rv',['id'=>$id]);
static function read($d,$b,$p,$q,$z=''){
$d=self::rqcols($d,$b); [$r,$sql]=self::where($q);
$sql='select '.$d.' from '.$b.' '.$sql;
return self::query($sql,$r,$p,$z);}

//join b2 to b1, associating b2.$key to b1.id //b2 is on the right, let left empty
static function inner($d,$b1,$b2,$k2,$p,$q='',$z=''){
if($d==$k2)$d=$b2.'.'.$d; [$r,$sql]=self::setq($q,$b1); 
$sql='select '.$d.' from '.$b1.' b1 inner join '.$b2.' b2 on b1.id=b2.'.$k2.' '.$sql;
return self::query($sql,$r,$p,$z);}

static function join($d,$b1,$b2,$k2,$p,$q='',$z=''){
if($d==$k2)$d=$b2.'.'.$d; [$r,$sql]=self::setq($q,$b1); 
$sql='select '.$d.' from '.$b1.' b1 left join '.$b2.' b2 on b1.id=b2.'.$k2.' '.$sql;
return self::query($sql,$r,$p,$z);}

//[[$b1,$k1,$b2,$k2],[$b1,$k1,$b3,$k3]]
static function inr($d,$r,$p,$q='',$z=''){$w=''; $b=''; [$rb,$ql]=self::setq($q,$r[0][0]);
foreach($r as $k=>$v){$w.='join '.$v[2].' on '.$v[0].'.'.$v[1].'='.$v[2].'.'.$v[3].' '; if(!$b)$b=$v[0];}
$sql='select '.$d.' from '.$b.' '.$w.' '.$ql;
return self::query($sql,$rb,$p,$z);}

static function outr($d,$r,$p,$q='',$z=''){$w=''; $b='';
foreach($r as $k=>$v){$w.='left outer join '.$v[2].' on '.$v[0].'.'.$v[1].'='.$v[2].'.'.$v[3].' '; if(!$b)$b=$v[0];}
$sql='select '.$d.' from '.$b.' '.$w;
return self::query($sql,$r,$p,$z);}

#write
static function mkv($r){$rt=[]; foreach($r as $k=>$v)$rt[]=':'.$k; return implode(',',$rt);}
static function mkvk($r){$rt=[]; foreach($r as $k=>$v)$rt[]=$k.'=:'.$k; return implode(',',$rt);}

static function insertions($r,$b='',$o=''){
if($b)$r=self::vrf($r,$b);
$rk=array_keys($r);
$q=self::mkv($rk);
if($o==5)$sql=$q;
elseif($o){$sql='NULL,'.$q;}
else{$sql='NULL,'. $q.',:up'; $r['up']=datz('Y-m-d H:i:s',time());}
$cols=join(',',$rk);
return [$r,$sql,$cols];}

static function sav($b,$q,$z='',$o='',$vd=''){
[$r,$q,$c]=self::insertions($q,$vd?$b:'',$o);
$sql='insert into '.$b.' ('.$c.') values ('.$q.')';
$stmt=self::prep($sql,$r,$p,$z);
return self::nid();}

static function savup($b,$q,$z='',$o='',$vd=''){
[$r,$q,$c]=self::insertions($q,$vd?$b:'',$o);
$sql='insert ignore into '.$b.' ('.$c.') values ('.$q.')';
$stmt=self::prep($sql,$r,$p,$z);
return self::nid();}

static function savif($b,$r,$z=''){$ex=self::read('id',$b,'v',$r,$z);
if(!$ex)$ex=self::sav($b,$r,$z); return $ex;}

static function savr($b,$q,$z=''){
$ra=self::cols($b); $rt=[]; $sq=[];
foreach($q as $k=>$v){$rb=[];
	foreach($v as $ka=>$va){$rb[]=':'.$ka.$k; $rt[$ka.$k]=$va;}
	$sq[]='('.join(',',$rb).')';}
$sql='insert into '.$b.' ('.join(',',$ra).') value '.join(',',$sq).' on duplicate key update id=id';
self::prep($sql,$rt,$p,$z);
return self::nid();}

//[[1,'hello'],[2,hey]]//usused
static function sav2($b,$r,$o='',$x='',$z='',$vd=''){
if(auth(6) && $x){self::backup($b); self::trunc($b);}
$qr=self::rq();
try{$qr->beginTransaction();
	foreach($r as $k=>$q){
		if($z)echo self::see($sql,$r);
		[$r,$q,$c]=self::insertions($q,$vd?$b:'',$o);
		$sql='insert into '.$b.' ('.$c.') values ('.$q.')';
		$stmt=$qr->prepare($sql); self::bind($stmt,$r); $stmt->execute();}
	$qr->commit();}
catch(Exception $e){$qr->rollback(); er($e->getMessage());}
return self::nid();}

static function upd($b,$r,$q,$z=''){$rt=[];
$vals=self::mkvk($r); [$ra,$sql]=self::where($q);
$sql='update '.$b.' set '.$vals.' '.$sql;
$stmt=self::prep($sql,$r+$ra,$p,$z);
return $stmt?1:0;}

static function del($b,$q,$z=''){
[$ra,$sql]=self::where($q);
$sql='delete from '.$b.' '.$sql.' limit 1';
$stmt=self::prep($sql,$ra,$p,$z);
return $stmt?1:0;}

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
$r=self::query($sql,'rv','',[]); pr($r);
foreach($r as $k=>$v)self::qr($v);}

static function utf8($t){$r=self::read('*',$t,'rr',[]);//exec one time only on non-utf8 tables
foreach($r as $k=>$v){foreach($v as $ka=>$va)$rb[$k][$ka]=str::utf8enc($va);
	self::upd($t,$rb[$k],$v['id']);}}

}
?>
