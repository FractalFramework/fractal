<?php
class gen{
private static $r=[];

#read
static function reader($d,$b=''){//[p*o:c]
[$p,$o,$c,$da]=readgen($d);
$r=['area','base','bgsound','embed','frame','input','link','meta','option'];//,'nextid'
if(in_array($c,$r)){$n=1; if(!$o)$o=$p;} else $n=0; $atb=$o?prmr($o):[]; 
//$r=['h'=>'big','k'=>'strike','e'=>'sup','n'=>'sub','s'=>'small','q'=>'blockquote'];
//if(isset($r[$c]))$c=$r[$c];
switch($c){
	case('style'):return 'style='.$p; break;
	case('class'):return 'class='.$p; break;
	case('id'):return 'id='.$p; break;
	//case('html'):return self::$r[$p]; break;
	case('img'):return conn::img($p,$o,$b); break;
	case('var'):return self::$r[$p]??'['.$p.':var]'; break;
	case('setvar'):self::$r[$o]=$p; return; break;
	case('x'):return '['.$p.':x]'; break;
	case('gen'):return self::read(self::$r[$p],$b); break;
	case('conn'):return conn::com2(self::$r[$p]??$p); break;}
return tag($c,$atb,$p,$n);}

static function read($d,$p=''){
$st='['; $nd=']'; $deb=''; $mid=''; $end='';
$in=strpos($d,$st);
if($in!==false){
	$deb=substr($d,0,$in);
	$out=strpos(substr($d,$in+1),$nd);
	if($out!==false){
		$nb=substr_count(substr($d,$in+1,$out),$st);
		if($nb>=1){
			for($i=1;$i<=$nb;$i++){$out_tmp=$in+1+$out+1;
				$out+=strpos(substr($d,$out_tmp),$nd)+1;
				$nb=substr_count(substr($d,$in+1,$out),$st);}
			$mid=substr($d,$in+1,$out);
			$mid=self::read($mid,$p);}
		else $mid=substr($d,$in+1,$out);
		$mid=self::reader($mid,$p);
		$end=substr($d,$in+1+$out+1);
		$end=self::read($end,$p);}
	else $end=substr($d,$in+1);}
else $end=$d;
return $deb.$mid.$end;}

static function call($p){
$d=val($p,'msg',val($p,'params'));
$r=$p['vars']??[];
$o=$p['opt']??'';
return self::read($d,$r,$o);}

static function com($d,$r=[],$o=''){self::$r=$r; $d=deln($d);
if($r)foreach($r as $k=>$v)$d=str_replace('('.$k.')',$v,$d);
$ret=self::read($d,$o);
//$ret=nl2br($ret);
return $ret;}

static function com2($d,$r){
if($r)foreach($r as $k=>$v)$ret[]=self::com($d,$v);
if(isset($ret))return implode('',$ret);}

static function content($p){
$j='cnn|gen,call|ptag=1|msg';
$r=['id'=>'msg','rows'=>16,'cols'=>80,'class'=>'console','onkeyup'=>ajx($j),'onclick'=>ajx($j)];
$ret=build::genbt('msg',2).tag('textarea',$r,'');
$ret.=bj($j,langp('ok'),'btsav');
$ret.=div('','board','cnn');
return $ret;}
}
?>