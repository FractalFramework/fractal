<?php
class subt{
static $rt=[];
static $p=[];
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

}
?>