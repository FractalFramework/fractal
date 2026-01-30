<?php

class ascii{
static $private=0;
static $a=__CLASS__;
static $cb='asc';

//static function admin(){return admin::app(['a'=>self::$a]);}

static function db(){$rt=[];
$r=db::read('db/system/ascii2',1);
foreach($r as $k=>$v)if(is_numeric($v[1]) && $v[2])$rt[$v[0]]=[hexdec($v[1]),hexdec($v[2])];
return $rt;}

#unicode
static function readsym($p){$ka=$p['k']; $id=$p['rid'];
$r=db::read('db/system/ascii2',1);
foreach($r as $k=>$v)if($k==$ka){$a=hexdec($v[1]); $b=hexdec($v[2]); $ret=div($v[0],'tit');
	for($i=$a;$i<=$b;$i++){$va='&#'.$i.'; '; $ret.=btj($va,insert($va,$id),'ascii','',$i).' ';}}
return $ret;}

static function allsym($p){
$ret=''; $id=$p['rid']??''; $b=$p['b']; $i=0; $da='';
$r=db::read('db/system/ascii2',1);
$ret=bj('ask|ascii,call|rid='.$id,pic('back'),'');
if($b)foreach($r as $k=>$v)if($v[3]!=$b)$r[$k]=[];
foreach($r as $k=>$v)if($v)$ret.=toggle('ask2|ascii,readsym|rid='.$id.',k='.$k,$v[0],'').' ';
<<<<<<< HEAD
return div($ret,'lisb').div('','','ask2');}
=======
return div($ret,'lisb').div('','lisb','ask2');}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb

#by groups
static function symbolsdb(){
$r=db::read('db/system/ascii'); foreach($r as $k=>$v)$rb[$v[1]][]=$v[0];
return $rb;}

static function display($v){
$v=str::utf8dec($v);
if(is_numeric($v))$v='&#'.$v.';';
elseif(mb_strlen($v)>1)$v='&'.$v.';';
else $v=str::utf8enc($v);
return $v;}

static function line($r,$id){$ret='';
foreach($r as $k=>$v)if($v){$va=self::display($v);
	$ret.=btj($va,insert($va,$id),'ascii','',$v).' ';}
return $ret;}

static function open($p){$k=$p['k']; $id=$p['rid']??''; 
$r=self::symbolsdb(); $v=$r[$k]??[];
if($v)return div(langx($k),'tit').self::line($v,$id);}

static function call($p){$id=$p['rid']??'';
//$ret=popup('ascii,call|rid='.$id,pic('popup'),'');
$r=db::read('db/system/ascii2',1); $ra=cat($r,3,1);
//$ra=['all','pictos','latin','maths','lang','CJC','numbers','old','empty'];
$bt=bj('ask|ascii,all|rid='.$id.',n=0',langpi('alphabet'),'');
foreach($ra as $k=>$v)$bt.=bj('ask|ascii,allsym|rid='.$id.',b='.$v,$v,'active').' ';
$r=self::symbolsdb(); //p($r);
foreach($r as $k=>$v)$bt.=toggle('ask2|ascii,open|rid='.$id.',k='.$k,$k,'',[],$k=='useful'?1:0).' ';
//if($k=='useful')$ret.=div(div(langx($k),'tit').self::line($v,$id));
$p['k']='useful'; $ret=self::open($p);
<<<<<<< HEAD
return div($bt,'lisb','ask').div($ret,'','ask2');}
=======
$ret=div($bt,'lisb').div($ret,'','ask2');
return div($ret,'','ask');}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb

//all
static function all($p){
$id=$p['rid']; $n=$p['n']??0; $ret='';
$ret=bj('ask|ascii,call|rid='.$id,pic('back'),'');
if($n>1024)$ret.=bj('ask|ascii,all|rid='.$id.',n='.($n-1024),pic('previous'),'');
$ret.=bj('ask|ascii,all|rid='.$id.',n='.($n+1024),pic('next'),'');
$r=[1,128,8208,11904,13312,40960,42128,43968,63744,64533,73728,77824,119040,126976,131072,178205];
foreach($r as $k=>$v)$ret.=bj('ask|ascii,all|rid='.$id.',n='.$v,'&#'.$v.';',$n>=$v?'active':'');
$ret.=span($n.'->'.($n+1024),'btok').br();
for($i=$n;$i<$n+1024;$i++){$va=self::display($i);
	$ret.=btj($va,insert($va,$id),'ascii','',$i).' ';}
return $ret;}

static function order($p){
$ty=$p['ty']??''; $ret='';
$r=self::symbolsdb(); $ra=array_keys($r);
foreach($ra as $k=>$v)$ret.=bj('ord|ascii,order|ty='.$v,$v,'licon');//pb closebubauto
if($ty){$rb=$r[$ty]; $rb=array_flip(array_flip($rb)); sort($rb);
	$ret.=str::utf8enc(implode(' ',$rb));}//explode(' ',$r[$ty]); 
return div($ret,'','ord');}

static function nav($p){$o=valb($p,'p1',1); $t='';
<<<<<<< HEAD
$r=sesm('ascii','db'); foreach($r as $k=>$v)if($o>=$v[0] && $o<$v[1])$t=$k;
=======
$r=sesm('ascii','db','',1); foreach($r as $k=>$v)if($o>=$v[0] && $o<$v[1])$t=$k;
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
$ret=bj('asc|ascii,nav|p1='.($o-1),pic('previous'));
$ret.=bj('asc|ascii,nav|p1='.($o+1),pic('next'));
$ret.=span($t,'nfo');
$ret.=div('&#'.$o.';','pane','','font-size:48px;');
//$ret.=self::order($p);
return $ret;}

static function search($p){$o=valb($p,'p1',1);
$ret=bar('p1',$o,$step=1,$min=0,$max=200000,'1','ajx(\'asc|ascii,nav||p1\')','','600px');
$ret.=div(self::nav($p),'','asc');
return div($ret,'board');}

static function content($p){$o=valb($p,'p1',1);
$rid=randid('asc'); $p['rid']=$rid;
$ret=input($rid,'',86,lang('inactive',1));
$ret.=bj('popup|ascii,search|p1=1',langp('search'),'btn');
$ret.=self::call($p);
return div($ret,'board');}
}
?>