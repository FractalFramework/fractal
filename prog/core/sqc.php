<?php
class sqc{
static $db;
static $qr;
static $r;
static $er;

//where
static function oper($q,$o=''){$ra=[]; $rb=[]; $rc=[]; $w='';
if($q)foreach($q as $k=>$v){
	$s=substr($k,0,1); $c=substr($k,1);
	if($k=='_order')$ra[]='order by'.$v;
	elseif($k=='_group')$ra[]='group by '.$v;
	elseif($k=='_limit')$ra[]='limit '.$v;
	elseif($k=='_code')$ra[]=$v;
	elseif($k=='or')$rc+=self::oper($v);//'or'=>['!status'=>'3','!typ'=>'0']
	elseif($k=='and')$rb+=self::oper($v);//second iteration
	elseif($k=='not null')$rb[]=[$c,'is not null',''];
	elseif($k=='is null')$rb[]=[$c,'is null',''];
	elseif($s=='|')$rc[]=[$c,'=',$v];//or
	elseif($s=='!')$rb[]=[$c,'!=',$v];
	elseif($s=='>')$rb[]=[$c,'>',$v];
	elseif($s=='<')$rb[]=[$c,'<',$v];
	elseif($s=='}')$rb[]=[$c,'>=',$v];
	elseif($s=='{')$rb[]=[$c,'<=',$v];
	elseif($s=='%')$rb[]=[$c,'like','%'.$v.'%'];
	elseif($s=='[')$rb[]=[$c,'like',$v.'%'];
	elseif($s==']')$rb[]=[$c,'like','%'.$v];
	elseif($s=='~')$rb[]=[$c,'like',$v];
	elseif($s=='&')$rb[]=[$c,'between','("'.$v[0].'" and "'.$v[1].'")'];
	elseif($s=='(')$rb[]=[$c,'in',$v];
	elseif($s==')')$rb[]=[$c,'not in',$v];
	elseif($s=='#')$rb[]=['','date_format('.$c.',"%y%m%d")=',$v];
	elseif($s=='-')$rb[]=['','substring('.$c.',1,'.strlen($v).')!=',$v];
	elseif($s=='+')$rb[]=['','substring('.$c.',1,'.strlen($v).')=',$v];
	elseif(is_array($v))$rb[]=[$c,'',$v];
	elseif(is_numeric($k))$rb[]=['','',$v];
	else $rb[]=[$c,'=',$v];}
return [$ra,$rb,$rc];}

static function operq($q){
if(is_numeric($q))return [[],['id','=',$q],[]];
elseif(is_string($q))return [[],['',$q,''],[]];
elseif(!$q)return [[],[],[]]; 
else return self::oper($q);}

//proc
static function buildreq($r){$rt=[];
foreach($r as $k=>[$c,$p,$v]){
	if(is_array($v))$va=self::atmra($v); elseif($v)$va=sql::escape($v); else $va='';
	$rt[]=$c.' '.$p.' '.$va;}
return $rt;}

static function buildsql($q){
[$ra,$rb,$rc]=$q; $a=implode(' ',$ra); $ret='';
if($rc)$rb[]='('.implode(' or ',self::buildreq($rc)).')';
if($rb)$ret=implode(' and ',self::buildreq($rb)); else return $a;
if($ret)return 'where '.$ret.' '.$a;}

static function where($q){
$rq=self::operq($q);
return self::buildsql($rq);}

static function read($d,$b,$p,$q,$z=''){//sql
$sql='select '.$d.' from '.$b.' '.self::where($q);
$rq=sql::qr($sql,$z); $ret=$p=='v'?'':[];
if($rq){$ret=sql::format($rq,$p); sql::qfc($rq);}
return $ret;}

//prep
static function ptype($p){
if(ctype_digit((string)$p))return $p<=PHP_INT_MAX?'i':'s';
if(is_numeric($p))return 'd';
return 's';}

static function mkt($r){$rt=[];
foreach($r as $k=>$v)$rt[]=self::ptype($v);
return implode('',$rt);}

static function prep($sql,$q,$p){
$qr=sqb::rq();
$stmt=$qr->prepare($sql);
//$ty=self::mkt($q); //pr($q);
//$stmt->bind_param($ty,...$q);
sqb::bind($stmt,$q); $stmt->execute();
return sqb::fetch($stmt,$p);}

//oo
static function buildreq_oo($r,$rs=[],$rt=[],$i=0){
foreach($r as $k=>[$c,$p,$v]){$i++;
	if(is_array($v)){[$at,$as]=self::buildreq_oo($v,$rs,$rt,$i); $rs+=$as; $rt+=$at;}
	else{$rs[]=$c.' '.$p.' :'.$c.$i; $rt[$c.$i]=$v;}}
return [$rt,$rs];}

static function buildsql_oo($q){
[$ra,$rb,$rc]=$q; $ret=''; $a=implode(' ',$ra);
[$rbt,$rbs]=self::buildreq_oo($rb);
[$rct,$rcs]=self::buildreq_oo($rc);
$rt=$rbt+$rct;
if($rc)$rb[]='('.implode(' or ',$rcs).')';
if($rb)$ret=implode(' and ',$rbs); else return [$a,$rt];
if($ret)return ['where '.$ret.' '.$a,$rt];}

static function where_oo($q){
$rq=self::operq($q);
return self::buildsql_oo($rq);}

//[['day','>','1769442890'],['suj','like','trump']]
static function read_oo($d,$b,$p,$q,$z=''){
[$ql,$r]=self::where_oo($q);
$sql='select '.$d.' from '.$b.' '.$ql; if($z)echo $sql;
//return sqb::query($sql,$r,$p,$z);
$rq=self::prep($sql,$r,$p);
if($rq)$ret=sqb::format($rq,$p); else $ret=$p=='v'?'':[];
return $ret;}

}
?>