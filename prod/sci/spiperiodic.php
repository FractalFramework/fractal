<?php

class spiperiodic{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='spl';
static $mx=40;

static function admin(){return admin::app(['a'=>self::$a]);}
static function js(){return;}
static function headers(){head::add('jscode',self::js());}

static function clr($d){$r=[''=>'ccc','Nonmetals'=>'5FA92E','Nobles Gasses'=>'00D0F9','Alkali Metals'=>'FF0008','Alkali Earth Metals'=>'FF00FF','Metalloids'=>'1672B1','Halogens'=>'F6E617','Metals'=>'999999','Transactinides'=>'FF9900','Lanthanides'=>'666698','Actinides'=>'9D6568','undefined'=>'ffffff'];
return val($r,$d,'ffffff');}

//give rgb from wavelength (nm)
//http://pierreontheweb.free.fr/RGB-wavelength/wavelength2RGB.htm
static function clrhz2(){//380->781
$r=db_read('db/system/clrwav2'); //p($r);
foreach($r as $k=>$v)$rb[($v[0])]=$v[1];//nm2thz
return $rb;}

#build
static function build($d,$s){$r=[];
if($d==2)for($i=0;$i<=4;$i++)$r[]=$i+1;//4 angles but 2 sockets
else for($i=0;$i<=$s;$i++)$r[]=$d-$s+$i;
return $r;}

static function angles($r){$rb=[];
$min=min($r); $max=max($r); $diff=$max-$min;//octave
if($diff)foreach($r as $k=>$v)if($v)$rb[]=round(360*($v-$min)/$diff,2);//radian
return $rb;}

static function spire($r,$ray,$h){$ray-=$h;
$min=min($r); $max=max($r); $diff=$max-$min; $rc=[];
if($diff)foreach($r as $k=>$v){$vb=$v-$min; $rc[]=$ray+(($vb/$diff)*$h);}
return $rc;}

/*static function findpos2($i){
$r=[2,6,10,14]; $n=$i-118;
foreach($r as $k=>$v)if($n-$v<0){$ring=8; $sub=$k; $pos=$n-$v;}
return [$ring,$sub,$pos];}

static function findpos($level,$i){
if($i>118)return self::findpos2($i);
$ring=substr($level,0,1);
$subring=substr($level,1,1);
if($subring=='s')$sub=1; elseif($subring=='p')$sub=2; elseif($subring=='d')$sub=3; elseif($subring=='f')$sub=4; else $sub=5;
$pos=substr($level,2);
return [$ring,$sub,$pos];}*/
//[$name,$sym,$fam,$layer,$level]=val($r,$i,['','','','','']);
//[$ring,$subring,$pos]=self::findpos($level,$i);

#graph
static function atom($d,$x,$y,$r,$n){
$fam=$r[$n][2]; $clr=self::clr($fam);//$r[$k][14];
$w=strlen($n)*4;
$ret='[#'.$clr.',black:attr]['.$x.','.$y.',20:circle]';
$ret.='[black,,,10:attr]['.($x-$w).','.($y+4).'*'.$n.':text]';
return $ret;}

static function graph_rings($ra,$mi,$ray,$h,$do,$d,$ratm,$ring,$subring,$atom){//echo $do.'-'.$d.' ';
$ret=''; $zxa=0; $zya=$mi-$ray;
$r=self::build($do,$d); $rb=self::angles($r); $rc=self::spire($r,$ray,$h); //pr($ra);
foreach($rb as $k=>$a){$v=0-deg2rad($a+180);
	$ray=$rc[$k]; $ray2=$ray-10; $ray3=$ray+10; 
	$xa=$mi+sin($v)*$ray; $ya=$mi+cos($v)*$ray;
	$xb=$mi+sin($v)*$ray2; $yb=$mi+cos($v)*$ray2;
	$xc=$mi+sin($v)*$ray3; $yc=$mi+cos($v)*$ray3;
	$ret.='[white,silver:attr]['.$xb.','.$yb.','.$xa.','.$ya.':line]';
	if($zxa)$ret.='[white,black:attr]['.$zxa.','.$zya.','.$xa.','.$ya.':line]';
	$zxa=$xa; $zya=$ya; $zxb=$xb; $zyb=$yb;}
foreach($rb as $k=>$a){$v=0-deg2rad($a+180); $ray=$rc[$k]; $nb=val($ra,$k); //echo $nb.'-';
	//$ra=$ratm[$nb]; //pr($ra); //echo $nb.'-';
	$xa=$mi+sin($v)*$ray; $ya=$mi+cos($v)*$ray;
	if($do==2){if($k==2)$hz=1; elseif($k==4)$hz=2; else $hz='';} else $hz=$r[$k]; //$hz=maths::powers($hz,'Hz');
	if($hz && $nb)$ret.=self::atom($hz,$xa,$ya,$ratm,$nb);}
return $ret;}

static function ring($r,$ring,$atom){
foreach($r as $k=>$v)if($v[12]==$ring && $k<=$atom)$rb[$v[13]][]=$k;
return $rb;}

static function graph($ro,$atom){
$w=$h=800; $mi=$w/2; $ray=$mi-50; $s=6; $h1=$ray/$s;
$ret='[white,black:attr]['.$mi.','.$mi.','.$ray.':circle]';
$min=1; $max=11; $n=$max-$min; $u=$n/$s; 
$ratm=db_read('db/public/atomic');
$ring=val($ratm[$atom],12);
$rg=self::ring($ratm,$ring,$atom); //pr($rg);
foreach($rg as $k=>$v){
	$ray=$h1*$k; $do=$ro[$k][1]; $a=$ro[$k][2];
	$ret.=self::graph_rings($v,$mi,$ray,$h1,$do,$a,$ratm,$ring,$k,$atom);}
return svg::call(['code'=>$ret,'w'=>$w,'h'=>$h]);}

static function octaves(){$r[]=['row','rings','subrings']; $a=0;
for($i=1;$i<18;$i+=2){$a=2*$i+$a; $b=$i*2; $r[]=[$i,$a,$b];}//false result for 1
return $r;}

#call
static function call($p){
$v=$p['inp2']??1;
$m=$p['mode']??'mul';
bcscale(40);
$ro=self::octaves();
$ret=self::graph($ro,$v);
//$ret.=tabler($ro,1);
return $ret;}

static function com($p){
$v=$p['p1']??118;
$j=self::$cb.'|'.self::$a.',call|';
$bt=bj($j.'mode=|inp2','ok','btn');
return inputcall($j.'mode=|inp2','inp2',$v,22,lang('iteration')).$bt;}

#content
static function content($p){
$p['p1']=$p['p1']??'';
$bt=self::com($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}//
}
?>