<?php

class operations{	
static $private=6;
static $db='_model';
static $a='operations';
static $cb='opr';

#call
static function call($p){$ret='';
$db=$p['inp1'];
return $ret;}

static function headers(){}

//replace
static function call1($p){$ret='';
$a='1nfo.net'; $b='newsnet.fr';
//$r=sql('id,p','tlex_app','kv','');
$r=sql('id,txt','tlex','kv',''); //p($r);
foreach($r as $k=>$v)if(strpos($v,$a)){$v=str_replace($a,$b,$v); sql::up('tlex','txt',$v,$k); echo $k.' ';}
return $ret;}

//tlex_apps
static function call2($p){$ret='';
$r=sql('id,txt','tlex','kv','');
foreach($r as $k=>$v){
	$d=conn::call(['msg'=>$v,'mth'=>'appreader']); echo $d;
	tlex::saveapps($k,$d);}
return $ret;}

//
static function strainer($d,$a){$ret=[];
$d=str::clean_n($d); $d=str_replace("\n",' (nl) ',$d); $r=explode(' ',$d);
foreach($r as $v)if(substr($v,0,1)==$a)$ret[]=substr($d,1);
if($ret)$d=implode(' ',$ret); $d=str_replace(' (nl) ',"\n",$d);
return trim($d);}

static function captor($d,$a){$ret=[]; if(substr($d,0,1)==$a)echo $d; echo $d;
$d=str::clean_n($d); $d=str_replace("\n",' ',$d); $r=explode(' ',$d);
foreach($r as $v)if(substr($v,0,1)==$a)$ret[]=substr($d,1);
return $ret;}

static function savetags($id,$r){
if($r)foreach($r as $k=>$v)if($v)sql::savif('tlex_tag',['tlxid'=>$id,'tag'=>$v]);}

static function savemnt($id,$r){
if($r)foreach($r as $k=>$v)if($v)sql::savif('tlex_mnt',['tlxid'=>$id,'tousr'=>$v]);}

//tags/mnt
static function call3($p){$ret='';
$r=sql('id,txt','tlex','kv','');
foreach($r as $k=>$v){
	//$d=conn::call(['msg'=>$v,'mth'=>'connreader','opt'=>'#']); if($d)echo $d.br();
	//if($d)self::savetags($k,explode(',',$d));
	//$d=conn::call(['msg'=>$v,'mth'=>'connreader','opt'=>'@']); if($d)echo $d.br();
	//if($d)self::savemnt($k,explode(',',$d));
}
return $ret;}

//
static function call4($p){$ret='';
$r=sql('id,txt','tlex','kv','');
foreach($r as $k=>$v){
	$rb=self::captor($v,'#'); if($rb)pr($rb);
	//if($rb)self::savetags($k,$rb);
	//$rb=self::captor($v,'@');
	//if($rb)self::savemnt($k,$rb);
}
return $ret;}

//slide_r
static function idn($r,$ka){$ra=$r[$ka];
$id=$ra['id']; $bid=$ra['bid']; $idn=$ra['idn']; $idp=$ra['idp']; $rel=$ra['rel']; 
if($idp)foreach($r as $k=>$v)if($v['bid']==$bid && $v['idn']==$idp)$ra['idp']=$v['id'];
if($rel)foreach($r as $k=>$v)if($v['bid']==$bid && $v['idn']==$rel)$ra['rel']=$v['id'];
//pr($ra);
return $ra;}

static function call5($p){$ret='';
$r=sql('id,bid,idn,idp,rel','slide_r','rr','');
foreach($r as $k=>$v){
	$r[$k]=self::idn($r,$k);
	//sql::up2('slide_r',$r[$k],$id);
}
$ret=tabler($r);
return $ret;}

//css
static function call6($p){$ret='';
$r=sql('id,bid,idn,idp,rel','slide_r','rr','');
foreach($r as $k=>$v){
	$r[$k]=self::idn($r,$k);
	//sql::up2('slide_r',$r[$k],$id);
}
$ret=tabler($r);
return $ret;}

//http://www.efg2.com/Lab/ScienceAndEngineering/Spectra.htm">Spectra Lab Report
static function wavelength2rgb($p){$v=val($p,'inp1');
$min=384; $max=789; $g=0.8; $i=255;//gamma,intensity
if($v>=380 && $v<440){$red=-($v-440)/(440-380); $green=0.0; $blue=1.0;}
elseif($v>=440 && $v<490){$red=0.0; $green=($v-440)/(490-440); $blue=1.0;}
elseif($v>=490 && $v<510){$red=0.0; $green=1.0; $blue=-($v-510)/(510-490);}
elseif($v>=510 && $v<580){$red=($v-510)/(580-510); $green=1.0; $blue=0.0;}
elseif($v>=580 && $v<645){$red=1.0; $green=-($v-645)/(645-580); $blue=0.0;}
elseif($v>=645 && $v<781){$red=1.0; $green=0.0; $blue=0.0;}
else{$red=0.0; $green=0.0; $blue=0.0;}
//Let the intensity fall off near the vision limits
if($v>=380 && $v<420){$factor=0.3+0.7*($v-380)/(420-380);}
elseif($v>= 420 && $v<701){$factor=1.0;}
elseif($v>= 701 && $v<781){$factor=0.3 + 0.7*(780-$v)/(780-700);}
else{$factor=0.0;}
$rgb=[];
//Don't want 0^x=1 for x <> 0
$rgb[0]=$red==0.0?0:(int) round($i*pow($red*$factor,$g));
$rgb[1]=$green==0.0?0:(int) round($i*pow($green*$factor,$g));
$rgb[2]=$blue==0.0?0:(int) round($i*pow($blue*$factor,$g));
return implode(',',$rgb);}

static function dsk2($p){$ret=''; $rb=[];
$r=sql('id,uid,dir,type,com,picto,bt,auth','desktop','','');
foreach($r as $k=>$v){
	[$id,$uid,$dir,$type,$com,$picto,$bt,$auth]=$v;
	if(strpos($com,',')!==false)$app=strto($com,',');
	elseif(strpos($com,'|')!==false)$app=strto($com,'|');
	else $app=$com;
	if(strpos($com,'id=')!==false)$callid=strfrom($com,'id=');
	elseif(strpos($com,'id=')===false && strpos($com,'|')!==false)$callid=','.strfrom($com,'|');
	elseif(is_img($com)){$app=''; $callid=$com;}
	else $callid='';
	$rb[$k]=[$uid,$dir,$type,$app,$callid,$picto,$bt,$auth];
	//sql::up2('desktop2',$rb[$k],$id);
}
//sql::sav2('desktop2',$rb);
$ret=tabler($rb);
return $ret;}

static function renove($p){$ret='';
$r=sql('id,txt','tlex','kv','');
foreach($r as $k=>$v){$u=str_replace('1nfo.net','newsnet.fr',$v); $r[$k]=$u;
	//sql::up('tlex','txt',$u,$k);
	}
$ret=tabler($r);
return $ret;}

#content
static function content($p){$call='renove';
$p['p1']=$p['p1']??'';
$j=self::$cb.'|operations,'.$call.'|v1=hello|inp1';
$bt=inputcall($j,'inp1','value1','','1');
$bt.=bj($j,lang('send'),'btn');
return $bt.div('','pane',self::$cb);}
}