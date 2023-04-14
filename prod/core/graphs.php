<?php

class graphs{	
static $private=2;
static $a=__CLASS__;
static $cb='grp';
static $ratios=[];
static $incs=[];
static $mins=[];
static $w=720;
static $h=400;

#build
static function valtime($r0){
$rt=[]; foreach($r0 as $k=>$v)foreach($v as $ka=>$va)$rt[$k][$ka]=numday2time($va); ksort($rt); //pr($rt);
return $rt;}

static function keytime($rk){
$rt=[]; foreach($rk as $k=>$v)$rt[]=numday2time($v); //pr($rt);
return $rt;}

#uniscale
static function datas_dim($r0,$ad){$ra=[];
foreach($r0 as $k=>$v)foreach($v as $ka=>$va)$ra[]=$va; //pr($ra);
$min=min($ra); $max=max($ra); $diff=$max-$min;
if($ad && $diff)$ratio=1/$diff; elseif($max)$ratio=1/$max; else $ratio=1;
return [$ratio,$diff,$min,$max];}

static function datas_dim_histos($r0,$ad){$ra=[];//add values
foreach($r0 as $k=>$v)foreach($v as $ka=>$va)$ra[$k]=radd($ra,$k,$va);
$min=0; $max=max($ra); $diff=($max-$min);
if($ad)$ratio=1/$diff; else $ratio=1/$max;
return [$ratio,$diff,$min,$max];}

static function datas_ry($r0,$ad,$min,$ratio){$ry=[];
foreach($r0 as $k=>$v)foreach($v as $ka=>$va)
if($ad)$ry[$k][$ka]=($va-$min)*$ratio;
else $ry[$k][$ka]=$va*$ratio; //pr($ry);
return $ry;}

static function datas_rp($r0,$wb,$rx,$ry,$bdr,$hb,$h2,$ty3){$rp=[]; $i=0;
foreach($r0 as $k=>$v){foreach($v as $ka=>$va){
$rp[$k][$ka]['x']=$wb*($rx[$i]??1)+$bdr[0];
if($ty3)$rp[$k][$ka]['y']=($hb)*$ry[$k][$ka];
else $rp[$k][$ka]['y']=($hb)*$ry[$k][$ka]-$bdr[1];}
$i++;}
return $rp;}

static function datas_rky($r0,$n,$bdr,$hb,$h2,$ad,$min,$inc,$ty3){$rky=[]; $rkty=[]; $i=0;
$ny=$ty3?$n:$n-1; //$ny=10;//nb of y-increments
//$ratio=($hb+($o?0:$bdr[1]))/$ny;
$ratio=$hb/$ny;
$incb=100/($ny);
foreach($r0 as $k=>$v){
if($ad)$rkty[]=$min+($inc*$i);//adapt
else $rkty[]=$incb*$i;
$rky[]=$i*$ratio;
$i++;}
return [$rky,$rkty];}

#multiscales
static function datas_ry2($r0,$n){$ry=[];
$n=count(current($r0)); $ratios=[];
for($i=0;$i<$n;$i++){$ra=array_column($r0,$i);
$min=min($ra); $max=max($ra); if(!$min)$min=0; if(!$max)$max=0; $diff=$max-$min; $ratio=$diff?1/$diff:1;
self::$ratios[$i]=$ratio; self::$incs[$i]=$diff/($n); self::$mins[$i]=$min;
foreach($r0 as $k=>$v)$ry[$k][$i]=((int)$v[$i]-$min)*$ratio;}
return $ry;}

static function datas_rp2($r0,$wb,$rx,$ry,$bdr,$hb,$h2){$rp=[]; $i=0;
foreach($r0 as $k=>$v){foreach($v as $ka=>$va){
$rp[$k][$ka]['x']=$wb*$rx[$i]+$bdr[0];
$rp[$k][$ka]['y']=($hb)*$ry[$k][$ka];}
$i++;}
return $rp;}

static function datas_rky2($r0,$n,$hb,$h2){$rky=[]; $rkty=[]; $i=0;
$ny=$n;//-1 //$ny=10;//nb of y-increments
$ratio=($hb)/($ny);
$incb=100/($ny);
foreach($r0 as $k=>$v){foreach($v as $ka=>$va){
$rkty[$i][$ka]=self::$mins[$ka]+(self::$incs[$ka]*$i);
//$rky[$k][$ka]=$i*self::$ratios[$ka];
$rky[]=$i*$ratio;}
$i++;}//pr($rkty);
return [$rky,$rkty];}

#labels
static function lbly($rk,$rky,$rkty,$n,$bdr,$hb,$h2,$w,$dc,$dv){$ret='';
if($n>16)$nx=ceil($n/16); else $nx=1; $ni=count($rk);//not good
$hc=$hb+$h2+$bdr[1];
for($i=0;$i<$ni;$i+=$nx){
$y=$hc-$rky[$i];
$t=$rkty[$i]; if($dv)$t=numday($t);
$ret.='[,silver:attr]['.($bdr[0]).','.($y).','.($w-$bdr[2]).','.($y).':line]';
$t=round($rkty[$i],$dc?2:0); if($dv)$t=numday($t);
$ret.='[black,:attr][10,'.($y+5).',14*'.($t?$t:'0').':text]';}
return $ret;}

static function lblx($rk,$rx,$rktx,$n,$bdr,$hb,$h2,$wb){$ret='';
if($n>16)$nx=ceil($n/24); else $nx=1; $ni=count($rk);//not good
$hc=$h2+$bdr[1]; $y=$hb+$hc+32;
for($i=0;$i<$ni;$i+=$nx){
//$t=round($rk[$i]??$rk[$i-1]);
$t=$rktx[$i]; //if($dk)$t=numday($t); //echo $t.'-';
$x=($rx[$i]??($rx[$i-1]??1))*$wb+$bdr[0]; $x=round($x,2); $y=round($y,2);
$rot=strlen($t)>2?',,,,,rotate(300 '.($x-2).'/'.($bdr[1]+$h2-10).')':'';
if(isset($rc))$ret.='[black,'.$rot.':attr]['.($x-8).','.($hc).',,*'.$rc[$i].':text]';
$rot=strlen($t)>2?',,,,,rotate(300 '.($x+8).'/'.($y+10).')':'';
$ret.='[black,'.$rot.':attr]['.($x-8).','.$y.',,*'.($t?$t:'0').':text]';
$ret.='[,silver:attr]['.($x).','.($hb+$hc).','.($x).','.($hc).':line]';}
return $ret;}

static function legends($rn,$bdr,$wb,$clr){$ret='';
$nm=count($rn); $xba=$bdr[0]; $yba=$bdr[1]; $wba=$wb/$nm; $hba=30;
foreach($rn as $k=>$v){$xn=$xba+($k*$wba); $yn=$yba; 
$ret.='['.$clr[$k].':attr]['.($xn).','.$yn.','.($wba).','.($hba).':rect]';
$ret.='[white:attr]['.($xn+10).','.($yn+20).',12*'.$v.':text]';}
return $ret;}

#graphs
static function lines($rp,$r0,$bdr,$hb,$h2,$dv,$lb,$clr){
$ret=''; $txt=''; $dots=''; $old=false;
foreach($rp as $k=>$v){foreach($v as $ka=>$va){
$x=$va['x']; $y=$hb-$va['y']+$h2;
if($old!==false){$x2=$rp[$old][$ka]['x']; $y2=$hb-$rp[$old][$ka]['y']+$h2;
	$ret.='[,'.$clr[$ka].',2:attr]['.$x.','.$y.','.$x2.','.$y2.':line]';}
$t=$r0[$k][$ka]; if($dv)$t=numday($t);
$dots.='['.$clr[$ka].',black:attr]['.$t.'*['.$x.','.$y.',2:circle]:bub]';//
if($t)$txt.='[black,:attr]['.($x+8).','.($y+4).',12*'.$t.':text]';} $old=$k;}
$ret.=$dots; if($lb)$ret.=$txt;
return $ret;}

static function histo($rp,$r0,$bdr,$hb,$h2,$dv,$lb,$clr,$wb){$ret=''; $txt=''; $wd=0; $ln=0;//distance x
$na=count($rp); $w=round($wb/$na,2); if($w>20)$w=20; $mxy=[]; $w2=$w/2; $hb=$hb+$h2+$bdr[1];//frame
foreach($rp as $k=>$v){$n=count($v); foreach($v as $ka=>$va){$x=$va['x']; if($x-$wd<5)$ln=1; $wd=$x;}}
foreach($rp as $k=>$v){$n=count($v); $old=0; //arsort($v); //pr($v);
	foreach($v as $ka=>$va){
	$x=$va['x']; $hc=$va['y']; $y=$hb-$hc; $klr=$n==1?'black':$clr[$ka]; $x=round($x,2); $y=round($y,2);
	if(!$ln)$ret.='['.$klr.',black:attr]'; else $ret.='['.$klr.':attr]';
	$ret.='['.($x-$w2).','.($y-$old).','.($w).','.($hc).':rect]';
	$t=$r0[$k][$ka]; if($dv)$t=numday($t);
	$l=strlen($t); if($t)$txt.='[white,:attr]['.($x+9-$l*3-$w2).','.($y-$old+10).',12*'.$t.':text]';
	$old+=$hc;}}
if($lb)$ret.=$txt;
return $ret;}

static function boxes($rp,$r0,$bdr,$hb,$dv,$lb,$wb){$ret=''; $txt='';
$na=count($rp); $w=$wb/$na; if($w>10)$w=10; $w2=$w/2;
//foreach($rp as $k=>$v){foreach($v as $ka=>$va){}}//build median
foreach($rp as $k=>$v){foreach($v as $ka=>$va){
$x=$rp[$k][$ka]['x']; //echo $x.' ';
$y=$hb-$rp[$k][$ka]['y']; //echo $y.' ';
if($ka){$x2=$rp[$k][$ka-1]['x']; $y2=$hb-$rp[$k][$ka-1]['y'];}
if($ka==3 or $ka==1)$ret.='[,black:attr]['.$x.','.$y.','.$x.','.$y2.':line]';
if($ka==2)$ret.='[black,white:attr]['.($x-5).','.$y.','.$w.','.($y2-$y).':rect]';
//$ret.='[red,black:attr]['.$x.','.$y.',2:circle]';
if($ka==0 or $ka==3)$ret.='[,black,2:attr]['.($x-$w2).','.$y.','.($x+$w2).','.$y.':line]';
$t=$r0[$k][$ka]; if($dv)$t=numday($t);
$txt.='[black:attr]['.($x+8).','.($y+4).',12*'.($t?$t:'0').':text]';}}
if($lb)$ret.=$txt;
return $ret;}

static function draw($r0,$p,$w,$h){//pr($r0);
[$typ,$dk,$dv,$ad,$dc,$pr,$lb,$ti]=vals($p,['typ','dk','dv','ad','dc','pr','lb','t']); //pr($rp);
$bdr=[50,20,20,80]; $h2=0;//pr($bdr);
//colnames
if(isset($r0['_'])){$rn=$r0['_']; unset($r0['_']); $h2=50;} //pr($r0);
//bkg
$ret='[0,black:attr][0,0,'.($w).','.($h+$h2).':rect]';
$wb=$w-$bdr[0]-$bdr[2]; $hb=$h-$bdr[1]-$bdr[3];
if($ti)$ret.='[black,0:attr][10,'.($h+$h2-8).',12*'.$ti.':text]';
//convert values to time
if($dv)$r0=self::valtime($r0); //pr($r0);
//keys
$rktx=array_keys($r0); //pr($rktx);//sort($rk);
if(!$rktx)return;
$n=count($rktx); if(isset($rn))$n-=1;
//convert keys to time
if($dk)$rk=self::keytime($rktx); else $rk=array_keys($rktx); //pr($rk);
//vals
$min=min($rk); $max=max($rk); $diff=$max-$min; //echo $min.'-'.$max;
//col y (repartition)
$rx=[]; if($diff)foreach($rk as $k=>$v)$rx[]=($v-$min)/$diff; //pr($rx);
if(!$pr){
	//dimensions
	$ty3=$typ=='histo'?1:0;
	if($ty3)[$ratio,$diff,$min,$max]=self::datas_dim_histos($r0,$ad);
	else [$ratio,$diff,$min,$max]=self::datas_dim($r0,$ad);
	$inc=$diff/($n);
	//y ratios of values (0-1)
	$ry=self::datas_ry($r0,$ad,$min,$ratio); //pr($ry);
	//y pos of values
	$rp=self::datas_rp($r0,$wb,$rx,$ry,$bdr,$hb,$h2,$ty3); //pr($rp);
	//values of y labels
	[$rky,$rkty]=self::datas_rky($r0,$n,$bdr,$hb,$h2,$ad,$min,$inc,$ty3); //pr($rky);//pr($rkty);
	//labels-y
	$ret.=self::lbly($rk,$rky,$rkty,$n,$bdr,$hb,$h2,$w,$dc,$dv);
}
else{//multiscales
	$ry=self::datas_ry2($r0,$n); //pr($ry);//echo tabler($rys);
	$rp=self::datas_rp2($r0,$wb,$rx,$ry,$bdr,$hb,$h2); //pr($rp);
	[$rky,$rkty]=self::datas_rky2($r0,$n,$hb,$h2);
	//$ret.=self::lbly2($rk,$rky,$rkty,$n,$h,$h2,$bdr,$w,$dc,$dv);//todo
}
//cumuls
//if(isset($rn)){$rc=[]; foreach($r0 as $k=>$v)$rc[]=array_sum($v);}//pr($rc);
//labels-x
//$ret.='[black,:attr][10,'.($h-$bdr[3]+30).',*date:text]';
//$ret.=self::lblx_old($rk,$rx,$dk,$wb,$h,$h2,$bdr,$n);
$ret.=self::lblx($rk,$rx,$rktx,$n,$bdr,$hb,$h2,$wb);
$clr=svg::$clr_graph; //$clr=svg::clrs($n);
//legend
if(isset($rn))$ret.=self::legends($rn,$bdr,$wb,$clr);
//draw
if($typ=='lines')$ret.=self::lines($rp,$r0,$bdr,$hb,$h2,$dv,$lb,$clr);
elseif($typ=='histo')$ret.=self::histo($rp,$r0,$bdr,$hb,$h2,$dv,$lb,$clr,$wb);
elseif($typ=='boxes')$ret.=self::boxes($rp,$r0,$bdr,$hb,$dv,$lb,$wb);
return $ret;}

#datas
static function build($d){$r=[]; if(!$d)return []; 
if(strpos($d,'{'))return json_encode($d,true); $ra=explode("\n",str_replace(';',"\n",$d));
foreach($ra as $k=>$v){$rb=explode(',',$v); $k=$rb[0]; array_shift($rb); $r[$k]=$rb?$rb:$k;}
return $r;}

#play
static function play($r,$p){
$w=self::$w; $h=self::$h;
$d=self::draw($r,$p,$w,$h);
if(isset($r['_']))$h+=50;
//svg::init($w,$h); //svg::$r=[$d];
$ret=svg::call(['code'=>$d,'w'=>$w,'h'=>$h,'t'=>$p['t'],'img'=>$p['im']??0]);
//$sav=svg::save($p);
//if(auth(4))$ret.=div($sav);
return $ret;}

#call
//1,12,13\n2,23,24\n...
static function call($p){
$d=$p['com']??''; $ret='';
if($d)$r=self::build($d);
else $r=$p['r']??[]; //pr($r);
if($r)$ret=self::play($r,$p);
if(!$ret)return help('no element','txt');
return $ret;}

//$rp=vals($p,['typ','dk','dv','ad','dc','pr','lb','t','im']);
static function com($r,$ra,$w=640,$h=320){self::$w=$w; self::$h=$h;
$rb=['typ'=>'lines','dk'=>0,'dv'=>0,'ad'=>1,'dc'=>0,'pr'=>0,'t'=>'graph'];
foreach($rb as $k=>$v)$rb[$k]=val($ra,$k,$v);
if(!is_array($r))$r=self::build($r);
foreach($r as $k=>$v)if(!is_array($v))$r[$k]=[$v]; $rb['r']=$r;
return self::call($rb);}

#content
static function content($p){
$bt=textarea('com','','','','','console');
$bt.=bj(self::$cb.'|graphs,call||com',langp('ok'),'btn');
return $bt.div('','board',self::$cb);}
}
?>