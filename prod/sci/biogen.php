<?php

class biogen{
static $private=1;
static $a=__CLASS__;

static function admin(){return admin::app(['a'=>self::$a]);}

static function js(){return '';}
static function headers(){
head::add('csscode','
.rnd{display:inline-block; width:20px; height:20px; border-radius:10px; margin:2px;}
.gr{background:#00aa00;}
.yl{background:#aaaa00;}
');
head::add('jscode',self::js());}

static function cell($r){$ret='';
foreach($r as $v)$ret.=div('','rnd '.($v?'gr':'yl'));
return div($ret);}

/**/
static function algo_exec($p){
$p1=$p['p1']??''; $it=$p['it']??''; $rid=$p['rid']??'';
foreach($p as $k=>$v)if(is_numeric($k))$r[$k]=$v;
$nb=rand(0,count($r)-1);
$ret[]=$r[$nb];
//del used emplacement
unset($r[$nb]); sort($r);
$ret=bj($rid.'|biogen,algo_exec|'.prm($r),lang('next'),'btn');
return $ret;}

static function algo($p1,$n){
$na=$n*$p1; $r=[]; //echo $na.'/'.$n;
//emplacements
$r=array_pad($r,round($na),1); $r=array_pad($r,$n,0); //p($r);
for($i=0;$i<$n;$i++){
	$nb=rand(0,count($r)-1);
	$rt[]=$r[$nb];
	//del used emplacement
	unset($r[$nb]); sort($r);}
return self::cell($rt);}

static function build($p){$ret='';
$p1=$p['p1']??''; $it=$p['it']??1; $rid=val($p,'rid',0)+1;
$n=pow(2,$it);
$ret=self::algo($p1,$n);
$prm='p1='.$p1.',it='.($it+1).',rid='.$rid;
$ret.=bj($rid.'|biogen,build|'.$prm,lang('iteration').($it+1),'btn');
return div($ret).div('','',$rid);}

static function content($p){
$p['rid']=randid('gen');
$p1=$p['p1']??'0.90'; $it=$p['it']??1;
$bt=hlpbt('biogen_app');
$bt.=input_label('p1',$p1,'dominance').br();
$bt.=self::cell([1,0]);
$bt.=bj($p['rid'].'|biogen,build||p1',lang('iteration').$it,'btn');
return $bt.div('','',$p['rid']);}
}
?>