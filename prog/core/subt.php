<?php
class subt{
static $rt=[];
static $p=[];
<<<<<<< HEAD
static $cb='sbt';

#sample
static function r(){return 
['a'=>
	['a1'=>
		['a11'=>['a111','a112'],
		'a12',
		'a13'],
	'a2',
	'a3'],
'b'=>
	['b1','b2'],
'c'
];}

static function read($p){
[$a,$m,$r,$cb,$n]=vals($p,['a','m','r','cb','n']);
return div($p['k'.$n]??'');}

static function cbk($p){
[$a,$m,$d,$n,$cb]=vals($p,['a','m','d','n','cb']); $r=$a::$d();
return ['mnu'=>self::menu($r,$p),'sbsock'=>$p['k'.$n]??'',...$a::$m($p)];}

static function menu($r,$p,$n=0){$n++; $rt=[]; $rk=[]; $ret=''; //pr($p);
$rp=['a','m','d','cb','inp']; [$a,$m,$d,$cb,$inp]=vals($p,$rp); $p2=valk($p,$rp); $s=$p['k'.$n]??'';
for($i=1;$i<=$n;$i++)$rk['k'.$i]=$p['k'.$i]??$n; //pr($rk);
foreach($r as $k=>$v){
	if(is_array($v)){$p2['k'.$n]=$k; $prm=prm($p2+$rk); 
		$rt[]=bj('mnu|subt,call|'.$prm,$k,active($k,$s));
		if($k===$s){$ret=self::menu($v,$p,$n);}}
	else{$p2['k'.$n]=$v; $p2['n']=$n; $prm=prm($p2+$rk);
		$rt[]=bj('mnu;sbsock;'.$cb.'|subt,cbk|'.$prm.'|sbsock,'.$inp,$v,'ok '.active($v,$s));}}//
return div(join(' ',$rt),'lisb').$ret;}

static function call($p){
[$a,$d]=vals($p,['a','d']); $r=$a::$d();
return self::menu($r,$p);}

static function load($p){
return div(self::call($p),'','mnu').hidden('sbsock','');}

static function content($p){
$p=['a'=>'subt','m'=>'read','d'=>'r','cb'=>self::$cb];
return self::load($p).div('','',self::$cb);}
=======
static $i=0;
static $l=['a','b','c','d','e','f','g','h'];

#sample
static function r(){
return ['one'=>['a'=>['a1','a2'],'b','c'],
'two'=>['2a','2b']
];}

static function bt($v){
[$a,$app,$rid]=vals(self::$p,['a','app','rid']);
for($i=0;$i<self::$i;$i++)$pr[]=self::$l[$i].'='.$v;
return bj('mnu|'.$app.'|'.join(',',$pr),$v);}

static function dig($r){self::$i++;
foreach($r as $k=>$v){
	if(is_array($v))self::dig($v);
	else self::$rt[self::$i][]=self::bt($v);}
self::$i--;}

static function menu($p){ $rt=[]; $rb=[];
[$a,$b]=vals($p,['a','mode']); $b=ses('cnvmode',$b);
foreach($r as $k=>$v){
	$rt[]=bj('mnu|convert,menu2|a='.$k,$k,active($k,$a));
	if($k==$a)foreach($v as $kb=>$vb)
		$rb[]=bj('mnu;res|convert,cbk|a='.$k.',mode='.$vb.'|code',$vb,active($vb,$b));}
return div(div(join(' ',$rt),'lisb').div(join(' ',$rb),'lisb'),'','mnu');}

static function call($p){
[$a,$app,$rid]=vals($p,['a','app','rid']);
self::$p=$p; $r=$a::r();
self::dig($r);
foreach(self::$rt as $k=>$v)$rt[]=div(implode(' ',$v),'lisb');
return join($rt);}

static function ex($p){
self::$p=['a'=>'subt','app'=>'','rid'=>''];}

static function content($p){
self::ex($p); $p=self::$p;//
return self::call($p);}
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235

}
?>