<?php
class subt{
static $rt=[];
static $p=[];
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

}
?>