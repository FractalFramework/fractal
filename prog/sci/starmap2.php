<?php
class starmap2{
static $private=0;
static $a='starmap2';
static $cb='mdl';
static $default='17265,8102,88601,99461,81693,Yooma';
static $w=740;
static $subd=5;
static $p2=0;
static $rtx=[];

function __construct(){starlib::$w=self::$w;}
static function admin(){return admin::app(['a'=>self::$a,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){head::add('jscode',self::js());}

#map
static function legend($r,$p1,$p2,$p3){
$w=self::$w; $h=$w; $mid=$h/2; $sz=16; $p1b=jurl($p1); $n=self::$subd; $scale=$p2/$n;
[$white,$black,$red,$green,$blue,$yellow,$cyan,$orange,$silver,$gray]=starlib::$clr;
$rc=starlib::$clr2; $i=1; $l=($mid-40)/$n; $x=40; $y=$mid;
svg::text(11,$w-35,$mid,$p2.' Ly',$gray); svg::text(11,5,$mid,$p2.' Ly',$gray);//scale
svg::line($x,$y,$l+40,$y,$white); svg::line($x,$y-4,$x,$y+4,$white); svg::line($l+40,$y-4,$l+40,$y+4,$white);
svg::text(11,50,$y-6,$scale.' Ly',$silver);//unit
foreach($rc as $k=>$v){$i++;
	$clr=$v; $y=$i*$sz;
	svg::circle(20,$y-5,10,$clr);
	svg::text(11,20+$sz-2,$y,$k,$white);}
$x=60; $j='stm2|starmap2,call|p1='.$p1b.',p2='.$p2.',p3='; $jb='|p4';
$i=2; $y=$i*$sz; svg::bj($x,$y,11,$silver,$j.'0'.$jb,'0d');
$x+=18; svg::bj($x,$y,11,$silver,$j.'1'.$jb,'90d');
$x+=24; svg::bj($x,$y,11,$silver,$j.'2'.$jb,'180d');
$x+=32; svg::bj($x,$y,11,$silver,$j.'3'.$jb,'270d');
$x=60; $j='stm2|starmap2,call|p1='.$p1b.',p2=';
$i+=2; $y=$i*$sz; svg::bj($x,$y,11,$silver,$j.'20,p3='.$p3.$jb,'20 Ly');
$i++; $y=$i*$sz; svg::bj($x,$y,11,$silver,$j.'60,p3='.$p3.$jb,'60 Ly');
$i++; $y=$i*$sz; svg::bj($x,$y,11,$silver,$j.'200,p3='.$p3.$jb,'200 Ly');
$i++; $y=$i*$sz; svg::bj($x,$y,11,$silver,$j.'1000,p3='.$jb,'auto');}

static function repos($hip,$xb,$yb,$len){foreach(self::$rtx as $k=>$v)if(($v[0]>=$xb-$len or $v[0]<$xb+$len) && ($v[1]>=$yb-16 or $v[1]<$yb+16) && $k!=$hip)$yb+=16;
return $yb;}

static function dots($r,$p1,$p2){//pr($r);
$w=self::$w; $h=$w; $mid=$h/2; $mx=$mid; $my=$mid; $mx+=20; $my+=20; $sb=4; $rc=starlib::$clr2; $rz=starlib::sz($r);
[$white,$black,$red,$green,$blue,$yellow,$cyan,$orange,$silver,$gray]=starlib::$clr;
$ra=['hip','hd','x','y','star','planet','status','dist','radius','mag','spect','hs','rg'];
foreach($r as $k=>$v){
	[$hip,$hd,$x,$y,$st,$pl,$stt,$ds,$ray,$mg,$spc,$hs,[$xa1,$ya1,$xa2,$ya2]]=vals($v,$ra);
	$nm=$st?$st:($hd?'HD'.$hd:''); $spc=substr($v['spect'],0,1); $ds=$v['dist']??30; $ray=$v['radius']??1;
	$clr=$rc[$spc]??'#999999'; //$clr=starlib::sttclr($stt);
	//svg::line($mid,$mid,$x,$y,$silver);//radial
	$clin=$hs>0?$green:$orange; if($ds>$p2)$clin=$gray;
	$sz=$rz[$k]*2; if($sz<4)$sz=4;
	svg::line($xa1,$ya1,$xa2,$ya2,$clin,'');//mark
	svg::line($x,$y,$x,$y-$hs,$clin,'','','4');//link
	svg::circle($x,$y-$hs,$sz,$clr,$black,1);
	$tx=$nm.' ('.round($ds,2).' LY) ';//'HD'.$v['hd']
	$len=svg::len($nm);
	$xb=$x+$sb+4; if($xb+$len>$w)$xb=$x-$sb-$len;
	$yb=$y-$hs+4; self::$rtx[$hip]=[$xb,$yb];
	//$yb=self::repos($hip,$xb,$yb,$len);
	//svg::bub($xb,$yb,12,$white,$ds.' LY',$nm);
	//svg::$ret[]='[white:attr][['.$xb.','.$yb.',12*'.$nm.':text]*'.$ti.':tog]';
	svg::bj($xb,$yb,12,$ds>$p2?$gray:$white,'popup|starlib,info|com='.$hip,$nm);}}

static function ellipse($a,$n,$i,$o){
if($o==1 or $o==3)$i=$n-$i;
if($o==2 or $o==3)$a=0-$a;
return sin($i/($n/(M_PI/2)))*$a;}

static function arc($d,$a){
return [$x=$d*cos(deg2rad($a)),$y=$d*sin(deg2rad($a))];}

static function maxray($r){if(!$r)return;
$rd=array_keys_r($r,'dist'); $d=max($rd); $n=100000;
for($i=$n;$i>0;$i/=5)if($d<$i)$n=$i;
return ceil($d/$n)*$n;}

static function map($r,$p2,$p3){$n=self::$subd;
$w=self::$w; $h=$w; $wb=$w/2-40; $hb=$wb/4; $mid=$w/2; $mx=$mid; $my=$mid; $sz=11;
[$white,$black,$red,$green,$blue,$yellow,$cyan,$orange,$silver,$gray]=starlib::$clr; $clrg='#777';
for($i=1;$i<=$n;$i++){$w2=round($wb/$n*$i,2);
	svg::ellipse($mx,$my,$w2,$w2/4,'',$clrg);
	$t=round($p2/$n*$i,2); $x=$mid+$w2; $y=$mid-($w2/4);}
//svg::ellipse($mx,$my,$wb,$hb,'none',$white);
//if($p2){svg::text(11,$w-40,$my,$p2.' Ly',$gray); svg::text(11,5,$my,$p2.' Ly',$gray);}
$ax=$wb*cos(deg2rad(45)); $ay=$wb*sin(deg2rad(45))/4;
svg::line(40,$my,$w-40,$my,$clrg);
svg::line($mx,$my-$hb,$mx,$my+$hb,$clrg);
svg::line($mx-$ax,$my-$ay,$mx+$ax,$my+$ay,$clrg);
svg::line($mx-$ax,$my+$ay,$mx+$ax,$my-$ay,$clrg);
$rx=['0h','6h','12h','18h']; if($p3)$rx=array_merge(array_slice($rx,4-$p3),array_slice($rx,0,4-$p3));
svg::text($sz,$mx+$ax+5,$my+$ay+10,$rx[0],$gray);
svg::text($sz,$mx+$ax+2,$my-$ay-2,$rx[1],$gray);
svg::text($sz,$mx-$ax-20,$my-$ay-2,$rx[2],$gray);
svg::text($sz,$mx-$ax-16,$my+$ay+10,$rx[3],$gray);}

static function draw($r,$p,$p2,$p3){
$w=self::$w; $h=$w; //$im=new svg($w,$h);
[$white,$black]=starlib::$clr;
svg::rect(0,0,$w,$h,$black);
self::map($r,$p2,$p3);
self::dots($r,$p,$p2);//stars
self::legend($r,$p,$p2,$p3);
return svg::com('',$w,$h,'');}

static function positions($r,$p2,$p3){//spherical projection
$w=self::$w; $h=$w/2; $wi=$w/2; $hi=$h/2; $wb=$w/2-40; $hb=$wb/4; $hr=$wb/90;
$r1=starlib::scale(array_keys_r($r,'dist'),$wb,$p2); //pr($r1);
$as=45; if($p3)$as+=$p3*-90;
if($r)foreach($r as $k=>$v){
$ad=$v['ra']*15; $dc=$v['dc']; $ds=$r1[$k]; if($ds>$wb)$ds=$wb;
$a=deg2rad(360-$ad+$as); $b=deg2rad($dc-$as); $hs=round($dc*$hr,2);
$op=function($wi,$a,$ds,$n){return [$wi+round(cos($a)*($ds+$n),2),$wi+round((sin($a)*($ds+$n))/4,2)];};
[$xa,$ya]=$op($wi,$a,$ds,0); [$xa1,$ya1]=$op($wi,$a,$ds,-5); [$xa2,$ya2]=$op($wi,$a,$ds,5);
$r[$k]['x']=$xa; $r[$k]['y']=$ya; $r[$k]['hs']=$hs; $r[$k]['rg']=[$xa1,$ya1,$xa2,$ya2];} //pr($r);
return $r;}

#call
static function call($p){//pr($p);
[$p1,$p2,$p3,$p4]=vals($p,['p1','p2','p3','p4']);
if(strpos($p1,';'))$p1=str_replace(';',',',$p1);
$ra=db::read('db/public/stars/1',1); if(is_numeric($p4))self::$w=$p4;
if($p1=='knownstars' or $p1=='allstars'){
	if($p1=='allstars'){$rb=db::read('db/public/stars/2',1); $ra=array_merge($ra,$rb);}
	if(count(current($ra))>8)$p1=implode(',',array_keys_r($ra,8));}
$w=self::$w; $h=$w;
svg::init($w,$h,'starmap2');
$sq=starlib::sq($p1); //pr($sq);
$r=starlib::build($sq,1); //pr($r);
$rb=starlib::prep($r,$ra,$p1); //pr($rb);
$p2a=self::maxray($rb); $p2=$p2>$p2a||!$p2?$p2a:$p2;
$rb=self::positions($rb,$p2,$p3);
$ret=self::draw($rb,$p1,$p2,$p3);
$ret.=hidden('p4',$p4);
return div($ret,'','stm2');}

static function com($p){
[$p1,$p2,$p3,$p4]=vals($p,['p1','p2','p3','p4']);
return self::call(['p1'=>$p1,'p2'=>$p2,'p3'=>$p3,'p4'=>$p4]);}

#interface
static function menu($p1,$p2,$p3,$p4,$rid){
$j=$rid.'|starmap2,call||p1,p2,p4';
$ret=inputcall($j,'p1',$p2?$p1:self::$default,36);
$ret.=bj($j,langp('ok'),'btn').hlpbt('starmap1_app').' ';
$ret.=inputcall($j,'p2',$p2?$p2:30,4,'limit','',['type'=>'number']).' ';
$ret.=inputcall($j,'p4',$p4?$p4:740,4,'width','',['type'=>'number']).' ';
$df=jurl(self::$default);
$ret.=bj($rid.'|starmap2,call|p1='.$df.'|p2,p4','default','btn',['data-jb'=>'p1|core,val|'.$df]).' ';
$ret.=bj($rid.'|starmap2,call|p1=knownstars|p2,p4','knownstars','btn',['data-jb'=>'p1|core,val|knownstars']).' ';
return $ret;}

static function content($p){//pr($p);
[$p1,$p2,$p3,$p4]=vals($p,['p1','p2','p3','p4']); $rid=('stm2');
if(!$p1)$p1=self::$default;
$bt=self::menu($p1,$p2,$p3,$p4,$rid);
$bt.=db::bt('db/public/stars/1');
//$ret=self::call(['p1'=>$p1,'p2'=>$p2]);
return div($bt.div('','',$rid));}
}
?>