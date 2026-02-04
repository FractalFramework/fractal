<?php
//philum_plugin_maths

class maths{
static $bsc=20;

function __construct($n=''){bcscale($n?$n:self::$bsc);}

#algebric
static function rsum($r){$d=0; foreach($r as $k=>$v)$d=bcadd($d,$v); return $d;}
static function rsub($r){$d=0; foreach($r as $k=>$v)$d=bcsub($d,$v); return $d;}
static function rmul($r){$d=0; foreach($r as $k=>$v)$d=bcmul($d,$v); return $d;}
static function rdiv($r){$d=0; foreach($r as $k=>$v)$d=bcdiv($d,$v); return $d;}
static function rrat($r){$d=0; foreach($r as $k=>$v)$rb[]=bcdiv(1,$v); return $rb;}

static function raddlast($r){$n=count($r); $rb=$r[0]; for($i=0;$i<$n;$i++)$rb[]=$r[$i]+$r[$i-1]; return $rb;}
static function rsublast($r){$n=count($r); $rb=$r[0]; for($i=1;$i<$n;$i++)$rb[]=$r[$i]-$r[$i-1]; return $rb;}

static function bcfact($n){$d=1; for($i=1;$i<=$n;$i++)$d=bcmul($d,$i); return $d;}
static function fact($n){$d=1; for($i=1;$i<=$n;$i++)$d*=$i; return $d;}

//pythagoria
static function powr($n){return pow($n,2);}
static function sqrt($v,$n){return pow((float)$v,bcdiv(1,$n));}//cube
static function hypothenuse($ca,$co){return sqrt(self::powr($ca)+self::powr($co));}
static function pytha_cote($hy,$c){return sqrt(self::powr($hy)-self::powr($c));}
static function cercle_longueur($rayon){return M_PI*($rayon*2);}
static function cercle_surface($diametre){return (M_PI/4)*self::powr($diametre);}
static function sphere_surface($diametre){return M_PI*self::powr($diametre);}
static function sphere_volume($diametre){return (pi()/6)*pow($diametre,3);}
static function volume_rayon($n){return bcdiv(4,3,99)*M_PI*bcpow($n,3,99);}
static function volum2ray($n){$a=bcdiv($n,M_PI); $b=bcdiv(3,4); $c=bcmul($a,$b); return bcpow($c,1/3);}
static function area2ray($n){$a=bcmul(4,M_PI); return self::sqrt($n,$a);}

//sinusoids
static function radian($a){return deg2rad($a);}
static function sinus($a){return sin(self::radian($a));}
static function cosinus($a){return cos(self::radian($a));}
static function tangente($a){return tan(self::radian($a));}
static function degres($radian){return rad2deg($radian);}
static function arcsin($a){return self::degres(asin($a));}
static function arccos($a){return self::degres(acos($a));}
static function arctan($a){return self::degres(atan($a));}
static function sin_rect($co,$hy){return $co/$hy;}//sinus = coté opposé / hypoténuse
static function cos_rect($ca,$hy){return $ca/$hy;}//cosinus = coté adjacent / hypoténuse
static function tan_rect($co,$ca){return $co/$ca;}//tangente = coté opposé / coté adjacent
static function cotan_rect($co,$ca){return $ca/$co;}//cotangente = inverse de tangente
static function ratan2($x,$y){return rad2deg(atan2($x,$y))+(($x<0)?180:0);}//compass

#astro
static function nm2thz($d){return self::lightspeed()/($d*pow(10,3));}//usable reciprocally
static function cm2hz($d){return self::soundspeed()/($d*pow(10,-2));}//w=c/f
static function parsec(){return 648000/M_PI;}
static function lightspeed(){return 299792458;}//m/s
static function soundspeed($d=1){return bcmul($d,345,2);}//m/s
static function sunsz($d){return bcmul($d,1392000,2);}//sun size
static function al2km($d){return bcmul($d,9460730472580,8);}
static function km2al($d){return bcdiv($d,9460730472580,8);}
static function au2km($d){return bcmul($d,149597900,8);}
static function km2au($d){return bcdiv($d,149597900,8);}
static function pc2km($d){return bcmul($d,30856780000000,8);}
static function km2pc($d){return bcdiv($d,30856780000000,8);}
static function pc2al($d){return bcmul($d,3.261563777,8);}
static function al2pc($d){return bcdiv($d,3.261563777,8);}
static function mas2deg($d){return bcmul($d,0.00027777777777778,8);}
static function al2time($d){return bcmul($d,0.00027777777777778,8);}
static function solar_ray_of_earth(){return [152.100527,147.105052];}
static function deg2mas($d){return $d*3600;}
static function mas2rad($d){return deg2rad(self::mas2deg($d));}
static function mas2pc($d){return bcdiv(1,$d,8);}
static function pc2mas($d){return bcdiv(1,$d,8);}//
static function al2mas($d){return 1/self::al2pc($d);}
static function mas2al($d){return self::pc2al(1/($d*1e-3));}//mas
static function ra2deg($d){//00h00m00s
	$d=str_replace(' ','',$d);
	$ad1=(float)substr($d,0,2); $ad2=(float)substr($d,3,2); $ad3=(float)substr($d,6,2);
	$a=($ad1*15); $b=$ad2*0.25; $c=$ad3*(0.25/60); //echo $a.'+'.$b.'+'.$c.'-  ';
	return $a+$b+$c;}//round,4
static function dec2deg($d){//+00°00'00"
	$d=str_replace(' ','',$d);
	$ad1=(float)mb_substr($d,0,3); $ad2=(float)mb_substr($d,4,2); $ad3=(float)mb_substr($d,8,2);
	$a=$ad1; $b=$ad2/60; $c=$ad3/600; //echo $a.'--'.$b.'--'.$c.'-- ';
	return $a+$b+$c;}
static function deg2ra($d){$ha=$d/15; $h=floor($ha);//if(!is_int($d))echo $d=floatval($d);
	$hab=$ha-$h; if($hab)$ma=round(60*$hab,4); else $ma=0; $m=floor($ma);
	$mab=$ma-$m; if($mab)$sa=round(10*$mab,4); else $sa=0; $s=floor($sa);
	$sf=round((10*$mab)-$s,2)*100;
	$h=str_pad($h,2,'0',STR_PAD_LEFT);
	$m=str_pad($m,2,'0',STR_PAD_LEFT);
	$s=str_pad($s,2,'0',STR_PAD_LEFT).'.'.$sf;
	return $h.'h'.$m.'m'.$s.'s';}
static function deg2dec($d){$deg=floor($d);//+00°00'00"
	$m1=$d-$deg; $m2=$m1/10*60*10; $m=floor($m2);
	$s1=$m2-$m; $s2=$s1/10*60*10; $s=floor($s2);
	$sf=round($s2-$s,2)*60; //echo $deg.'+'.$m.'+'.$s.'-'.$sf.' ';
	$deg=str_pad($deg,2,'0',STR_PAD_LEFT);
	$m=str_pad($m,2,'0',STR_PAD_LEFT);
	$s=str_pad($s,2,'0',STR_PAD_LEFT).'.'.$sf;
	if($deg>0)$deg='+'.$deg;
	return $deg.'d'.$m.'m'.$s.'s';}

static function elapsed_sec_from_year($d){
$t=strtotime($d); $nd=date('z',$t); $nh=date('H',$t)*60*60; $nm=date('i',$t)*60; $ns=date('s',$t);
return $nd*84600+$nh+$nm+$ns;}
static function nb_sec_in_year(){$j=365.2422; $aj=$j/360; return 86400*$j;}//official=30880800;
static function angle_from_date($d){$nd=self::elapsed_sec_from_year($d); $ns=30880800; return $nd/$ns;}
static function sec_from_angle($d){return (30880800/360)*$d;}

#bases
static function bcdec2hex($dec){$last=bcmod($dec,16); $remain=bcdiv(bcsub($dec,$last),16);
if($remain==0)return dechex($last); else return self::bcdec2hex($remain).dechex($last);}

static function dec2base($dec,$b,$d=false){
if($b<2 or $b>256)die('Invalid Base: '.$b); bcscale(0); $v=''; if(!$d)$d=self::digits($b);
while($dec>$b-1){$rest=bcmod($dec,$b); $dec=bcdiv($dec,$b); $v=$d[$rest].$v;}
$v=$d[intval($dec)].$v;
return (string)$v;}

static function base2dec($v,$b,$d=false){
if($b<2 or $b>256)die('Invalid base: '.$b); bcscale(0);
if(!$d)$d=self::digits($b);
if($b<37)$v=strtolower($v);
$size=strlen($v); $dec='0';
for($loop=0;$loop<$size;$loop++){
$element=strpos($d,$v[$loop]); $power=bcpow($b,$size-$loop-1);
$dec=bcadd($dec,bcmul($element,$power));}
return (string) $dec;}

static function digits($b){$d='';
if($b>64)for($loop=0;$loop<256;$loop++)$d.=chr($loop);
else $d='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
return (string)substr($d,0,$b);}

#polar/svg
static function polarToCartesian($centerX,$centerY,$radius,$angleInDegrees){
$angleInRadians=($angleInDegrees-180)*M_PI/180;
$x=$centerX+$radius*cos($angleInRadians);
$y=$centerY+$radius*sin($angleInRadians);
return ['x'=>$x,'y'=>$y];}

static function describeArc($x,$y,$radius,$startAngle,$endAngle){
$start=self::polarToCartesian($x,$y,$radius,$endAngle);
$end=self::polarToCartesian($x,$y,$radius,$startAngle);
$largeArcFlag=$endAngle-$startAngle<=180?0:1;
$rt=['M',$start['x'],$start['y'],'A',$radius,$radius,0,$largeArcFlag,0,$end['x'],$end['y']];
return join(' ',$rt);}

#numbers
static function nroot($d,$n){return pow($d,1/$n);}
static function numerology($d){$n=strlen($d); $ret=0;
	for($i=0;$i<$n;$i++){$s=substr($d,$i,1); if(is_numeric($s))$ret+=$s;}
	if(strlen($ret)>1)$ret=self::numerology($ret);
	return $ret;}
static function mkpowers($d){$n=strlen($d); $ret=''; 
	$r=["&#186;","&#185;","&#178;","&#179;","&#8308;","&#8309;","&#8310;","&#8311;","&#8312;","&#8313;",'.'=>'.'];
	for($i=0;$i<$n;$i++)$ret.=$r[substr($d,$i,1)]; return $ret;}
static function powers($d,$u=''){
	if($n=strpos($d,'.'))$d=substr($d,0,$n); $n=strlen($d);//1.10^2
	if(strpos($d,'E+')){$p=strend($d,'E+'); $d=substr($d,0,4);}
	else{$d=round(bcdiv($d,(bcpow(10,($n-1)))),2); $p=$n-1;}
	return $d." &bull; ".'10'.self::mkpowers($p).' '.$u;}
static function measures($d,$n=1,$o=0){//1
	if($n==='mega')$n=3; elseif($n==='giga')$n=6; elseif($n==='tera')$n=9; elseif($n==='peta')$n=12; 
	elseif($n==='milli')$n=-3; elseif($n==='micro')$n=-6; elseif($n==='nano')$n=-9;
	$ret=$d*pow(10,$n); if($o)$ret=round($ret,$o); return $ret;}
static function magnitude($d,$u=''){$n=0; (int)$d=$d; $m='';
	if($d<1000){$n=0; $m='';}
	elseif($d<1000000){$n=3; $m='K';}
	elseif($d<1000000000){$n=6; $m='M';}
	elseif($d<1000000000000){$n=9; $m='T';}
	elseif($d<1000000000000000){$n=12; $m='P';}
	$d=self::measures($d,-$n); $d=round($d,$d<100?2:0);
	return $d.' '.$m.$u;}
static function roman($n){$c='IVXLCDM';
for($a=5,$b=$s='';$n;$b++,$a^=7)for($o=$n%$a,$n=$n/$a^0;$o--;$s=($c[$o>2?$b+$n-($n&=-2)+$o=1:$b]??'I').$s);
return $s;}

//stats
static function mediane($r){asort($r); $ns=0; $kb=0;
$n=count($r); $sum=array_sum($r); $n2=ceil($sum/2);//middle value
foreach($r as $k=>$v){$ns+=$v; if($ns<$n2)$kb=$k;}//search range
return $r[$kb];}

static function min($r){$a=false;
foreach($r as $k=>$v)if($a===false or $v<$a)$a=$v;
return $a;}

static function max($r){$a=false;
foreach($r as $k=>$v)if($a===false or $v>$a)$a=$v;
return $a;}

static function lambda($r,$n=1){$rb=[];
$a=self::min($r); $b=self::max($r); $diff=$b-$a; if(!$diff)return; $t=$n/$diff;
foreach($r as $k=>$v)$rb[$k]=($v-$a)*$t;
return $rb;}

static function hyper($i,$n,$a=1){
$pi=M_PI/2; $p1=$n/$pi;
return sin($i/$p1)*$a;}

static function ease($i,$n){//ease in-out
$pi=M_PI/2; $nb=$n/2; $a=1; $b=0;
if($i<$nb){$a=0-$a; $i=$nb-$i; $b=0;} else $i=$i-$nb;
return sin($i/($nb/$pi))*$a+$b;}

static function easeInOut($r,$a){$rb=[]; $nb=max($r);
foreach($r as $k=>$v)$rb[]=maths::ease($v,$nb);
return $rb;}

static function ponderation($r,$a){
$ra=self::easeInOut($r,$a);
$rb=self::lambda($ra); if(!$rb)return $ra;
foreach($r as $k=>$v)$rc[]=$v*$rb[$k];
return $rc;}

//time
static function sec2time($d,$o=''){$ret=''; $ok=0; if(!$d or !is_numeric($d))return;
$d+=mktime2(0,1,1,0,0,0); $r=explode('-',date('Y-m-d-H-i-s',$d)); $r[0]-=2000; $r[1]-=1; $r[2]-=1;
if($o)$rb=['year','month','day','hour','minute','second']; else $rb=['yr','mt','dy','hr','min','sec'];
foreach($r as $k=>$v)$re[$k]=$k>4?str_pad($v,2,'0',STR_PAD_LEFT):$v;
foreach($re as $k=>$v)$rt[]=$o?langnb($rb[$k],$v):$v.''.$rb[$k];
return implode(' ',$rt);}

static function which($d,$rb){
foreach($rb as $k=>$v)if(strpos($d,$v))return (int)trim(str_replace($v,'',$d)); return 0;}

static function time2sec($d){$yr=$mt=$dy=$hr=$min=$sec=0; if(!$d)return;
$r=explode(' ',$d); $r=array_pad($r,-6,'0'); $rb=['yr','mt','dy','hr','min','sec'];
foreach($r as $k=>$v)$rc[$k]=self::which($v,$rb); 
foreach($rc as $k=>$v)if($v){$u=$rb[$k]; $$u=$v;}
$res=mktime2($yr,$mt+1,$dy+1,$hr,$min,$sec);
$mil=mktime2(0,1,1,0,0,0);//note the +1
return $res-$mil;}

static function rmn($d,$n){$a=$d/$n; $b=floor($a); $c=($a-$b)*$n; return [$b,$c];}
static function elapsed($d,$o=''){$yr=$mt=$dy=$hr=$mn=$sc=0; $rt=[]; $rd=[]; $ret='';
if($d>($n=86400*365.2422))[$mt,$d]=self::rmn($d,$n);
if($d>($n=2629743.84))[$mt,$d]=self::rmn($d,$n);
if($d>86400)[$dy,$d]=self::rmn($d,86400);
if($d>3600)[$hr,$d]=self::rmn($d,3600);
if($d>60)[$mn,$d]=self::rmn($d,60);
if($d>1)[$sc,$d]=self::rmn($d,1);
if($yr)$rt[]=[$yr,'year'];
if($mt)$rt[]=[$mt,'month'];
if($dy)$rt[]=[$dy,'day'];
if(!$yr && !$mt && !$dy)$o=1;
if($hr && $o)$rt[]=[$hr,'hour'];
if($mn && $o)$rt[]=[$mn,'minute'];
if($sc && $o)$rt[]=[$sc,'second'];
foreach($rt as $k=>$v)$rd[]=$v[0].' '.$v[1].($v[0]>1?'s':'');
return implode(', ',$rd);}

static function compute_time($d,$o=0){//maths::sec2time
if($d>86400*365.2422)$ret=bcdiv($d,86400*365,2422).' years';
elseif($d>86400)$ret=bcdiv($d,86400,12).' days';
elseif($d>3600)$ret=round($d/3600,8).' hours ';
elseif($d>60)$ret=round($d/60,8).' min ';
else $ret=$d.'sec';
return $ret;}

static function dec2sex($d){$yr=$mt=$dy=$hr=$mn=$sc=$am=$as=$mas=0;
if($d>21600)[$tau,$d]=self::rmn($d,21600);
if($d>360)[$hr,$d]=self::rmn($d,360);
if($d>60)[$mn,$d]=self::rmn($d,60);
if($d>1)[$dg,$d]=self::rmn($d,1);
if($d>1/60)[$am,$d]=self::rmn($d,1/60);
if($d>1/3600)[$as,$d]=self::rmn($d,1/3600);
if($d>1/21600)[$mas,$d]=self::rmn($d,1/21600);
return $tau.'Tau '.$hr.'hr '.$mn.'min '.$sc.'sec '.$dg.'dg '.$am.'am '.$as.'as '.$mas.'mas';}

static function sex2dec($d){}

#geometry
//longueur d'une hélice //long,nb_spires,diam,haut
static function helice($l,$n,$d,$h){return sqrt(self::powr($l)+self::powr($n)+self::powr($d)+self::powr($h));}
static function centrifuge($d,$t){return 4*pow(pi(),2)*$d/pow($t,2);}
static function oval_circ($a,$b){return 2*(1/2*($a**2+$b**2));}//P = 2 (1/2 (a² + b²))

//renvoie angle en degrès
static function missing_angle($r){//adj/opp/hyp 
if(!$r[0])return self::arcsin(self::sin_rect($r[1],$r[2]));
if(!$r[1])return self::arccos(self::cos_rect($r[0],$r[2]));
if(!$r[2])return self::arctan(self::tan_rect($r[1],$r[0]));}

//renvoie la longueur manquante dans un triangle rectangle
static function missing_length($r){//adj/opp/hyp 
$a=self::missing_angle($r);
if(!$r[0])$r[0]=$r[2]*self::cosinus($a);
if(!$r[1])$r[1]=$r[2]*self::sinus($a);
if(!$r[2])$r[2]=$r[0]/self::cosinus($a);
return $r;}

//renvoie la longueur manquante dans un triangle rectangle
static function pythagore($r){//adj/opp/hyp 
if(!$r[0])$r[0]=self::pytha_cote($r[2],$r[1]);
if(!$r[1])$r[1]=self::pytha_cote($r[2],$r[0]);
if(!$r[2])$r[2]=self::hypothenuse($r[0],$r[1]);
return $r;}

#dyn
static function grav($m,$d){//mass,ray
$g=6.67E-011;//gravitation//e=gm/r²
return ($g*$m)/($d*$d);}

#trigo
static function bcms1($d){if($d==1)return 1;
return bcmul($d,self::bcms1(bcsub($d,1)));}

static function bcsin($a){$or=$a;
$d=bcsub($a,bcdiv(bcpow($a,3),6)); $i=2;
while(bccomp($or,$d)){$or=$d; switch($i%2){
case 0:$d=bcadd($d,bcdiv(bcpow($a,$i*2+1),self::bcms1($i*2+1)));break;
default:$d=bcsub($d,bcdiv(bcpow($a,$i*2+1),self::bcms1($i*2+1)));break;}
$i++;}
return $d;}

static function bccos($a){$or=$a;
$d=bcsub(1,bcdiv(bcpow($a,2),2)); $i=2;
while(bccomp($or,$d)){$or=$d; switch($i%2){
case 0:$d=bcadd($d,bcdiv(bcpow($a,$i*2),self::bcms1($i*2)));break;
default:$d=bcsub($d,bcdiv(bcpow($a,$i*2),self::bcms1($i*2)));break;}
$i++;}
return $d;}

static function bcpi(){$d=2; $i=0; $or=0;
while(bccomp($or,$d)){$i++; $or=$d;
	$d=bcadd($d,bcdiv(bcmul(bcpow(self::bcms1($i),2),bcpow(2,$i+1)),self::bcms1(2*$i+1)));}
return $d;}

static function trigo($o,$a,$h){//3,4,5
if($o && $h){$sin=$o/$h; $o=$sin*$h; $h=$o/$sin; $d=asin($sin);}//0.6
if($a && $h){$cos=$a/$h; $a=$cos*$h; $h=$a/$cos; $d=acos($cos);}//0.8
if($o && $a){$tan=$o/$a; $o=$tan*$a; $a=$o/$tan; $d=atan($tan);}//0.75
if(!$o && $h)$o=sin($d)*$h; elseif(!$o && $a)$o=tan($d)*$h;
if(!$a && $h)$a=cos($d)*$h; elseif(!$a && $o)$a=$o/tan($d);
if(!$h && $o)$h=$o/sin($d); elseif(!$h && $a)$h=$a/cos($d);
return [$o,$a,$h];}

static function opposite_angle($tan){return atan(1/$tan);}//angle opposé
static function hypothenuse_from_oa($x,$y){return $x/cos(atan($y/$x));}
static function distance3d($r1,$r2){//v[(xA-xB)²+(yA-yB)²+(zA-zB)²]
return bcsqrt(bcpow($r1[0]-$r2[0],2)+bcpow($r1[1]-$r2[1],2)+bcpow($r1[2]-$r2[2],2));}

#constants
//phi
static function phi($n=10){$d=1; bcscale(self::$bsc); for($i=0;$i<10*$n;$i++)$d=bcadd(1,bcdiv(1,$d)); return $d;}//1e-40
//static function phi($n=10){$d=1; for($i=0;$i<10*$n;$i++)$d=1+(1/$d); return $d;}//1e-40
static function phi2($n){static $i; $i++; if($i==$n)return 1; return bcadd(1,bcdiv(1,self::phi2($n)));}
static function phibo(){$a=1; $b=1; $max=100;//phi by fibo
for($i=1;$i<$max;$i++){$c=bcadd($a,$b); $ret=bcdiv($c,$b); $a=$b; $b=$c;} return $ret;}
static function fibo($a=1,$b=1,$max=100){$r=[];//fibonacci
for($i=1;$i<$max;$i++){$c=$a+$b; $r[]=$c; $a=$b; $b=$c;} return $r;}
//φ=(φ+1)(φ-1)

//pi
static function pi(){return 3.14159265358979323846264338327950288419716939937510582097494459230781640628620899862803482534211706798214808651328230664709384460955058223172535940812848111745028410270193852110555964462294895493038196442881097566593344612847564823378678316527120190914;}
static function pi2(){return bcdiv(4,bcsqrt(self::phi()));}//bad
static function pi3($n=18000){$d=0; for($i=2*$n-1;$i>0;$i-=2)$d=$i*$i/(6+$d); return 3+$d;}//{3,1²;6,3²;6,5²}
static function pi3b($n=18000){$d=0; for($i=2*$n-1;$i>0;$i-=2)$d=bcmul($i,bcdiv($i,bcadd(6,$d))); return bcadd(3,$d);}
static function pi4($n=100000000){$d=0; for($i=2*$n-1;$i>0;$i-=2)$d=$i*$i/(2+$d); return 4/(1+$d);}//{1,1²;2,3²;2,5²}/4

//e
static function M_E($n=16){for($i=0;$i<$n;$i++)$r[]=1/self::fact($i); return array_sum($r);}
static function bcM_E($n=36){for($i=0;$i<$n;$i++)$r[]=bcdiv(1,self::bcfact($i)); return self::rsum($r);}//40
static function fcM_E($n=4){$r=[2,1]; for($i=2;$i<$n;$i+=2)array_push($r,$i,1,1); return self::fcr($r,0);}

//primes
static function primes($n){$r=[]; 
for($i=2;$i<$n;$i++)for($j=2;$j<$i;$j++){if(($i%$j)==0)break; if($j==($i-1))$r[]=$i;} return $r;}

#fc
static function fcadd($a,$q,$d){return bcadd($a,bcdiv($q,$d));}
static function fcsub($a,$q,$d){return bcsub($q,bcdiv($q,$d));}
static function fcalt($a,$q,$d,$i){if($i%2==0)return self::fcadd($a,$q,$d); else return self::fcsub($a,$q,$d);}

static function fcadd_v($a,$q,$d,$i){return '('.$a.'+'.$q.'/'.$d.')';}
static function fcsub_v($a,$q,$d,$i){return '('.$a.'-'.$q.'/'.$d.')';}
static function fcalt_v($a,$q,$d,$i){return '('.$a.($i%2?'+':'-').''.$q.'/'.$d.')';}

static function fc($fn,$a,$q=1,$n=10){$d=1; for($i=1;$i<$n;$i++)$d=$fn($a,$q,$d,$i); return $d;}
static function fcb($fn,$a,$q=1,$n=10){$d=1; if($n==1)return $d; $d=self::fcb($fn,$a,$q,$n-1); return $fn($a,$q,$d,$n);}
static function fcr($fn,$r,$q=1){$d=1; $r=array_reverse($r); foreach($r as $k=>$v)$d=$fn($v,$q,$d,$k); return $d;}
static function fcrb($fn,$r,$q=1,$i=0){echo $i.' '; $n=count($r)-1; $d=$r[$n]; if($i==$n)return $d;
$d=self::fcrb($fn,$r,$q,$i+1); return $fn($r[$i],$q,$d,$i+1);}

static function fract($ra,$q,$m='add',$n=10,$o=''){$d=1; if(is_array($q)){$q=array_reverse($q); $nq=count($q)-1;}
if(!is_array($ra))$ra=array_pad([],is_array($q)?count($q)-1:$n,$ra); $ra=array_reverse($ra); //pr($ra);
foreach($ra as $k=>$v){$qb=$q[$k]??$q;
	if($m=='add')$s=1; elseif($m=='sub')$s=0; elseif($m=='alt')$s=$k%2==0?1:0;
	if($o)$d='('.$v.($s?'+':'-').$qb.'/'.$d.')';
	else{$d1=bcdiv($qb,$d); $d=$s?bcadd($v,$d1):bcsub($v,$d1);}}
return $d;}

//ex
//$ret=maths::fc('maths::fcadd',1,1,60);//phi
//$ret=M_PI/maths::fcr('maths::fcadd',[2,6,10,14,18],M_PI**2);//Ramanujan: (e^pi-1)/(e^pi+1)
//$ret=maths::fract([1,2,2,2,2,2,2,2,2,2],$rc,'add',0,0); $ret=4/$ret;//pi(1)//!
//$ret=maths::fract(6,$rc,'add',0,0); $ret=3+1/$ret;//pi(2) //$rc=odd powers
//$ret=maths::fract($rd,$rn,'add',0,1); $ret=4/$ret; //pi(3) //$rd=odd serie, $rn=serie powers//!

//spi
static function sqrt2($n){return pow(sqrt(2)*$n,2);}//[1=>2,8,18;32,50,72,98,128]//spitable rings :)
static function rings($n){for($i=1;$i<=$n;$i++)$r[]=self::sqrt2($i); return $r;}//2,8,18,32,50
static function subrings($n){$r=self::rings($n); return self::rsublast($r);}//2,6,10,14,18
static function sqmul($n,$a,$b){return pow(pow($b,1/$a)*$n,$a);}//pow(sqrt($b)*$n,$a)

//array_substract($r)

#3d
static function xyz($ad,$dc,$ds,$o=2){$a=deg2rad($ad); $b=deg2rad($dc);
//$x=sin($a)*$ds; $y=sin($b)*$ds; $z=cos($a-$b)*$ds;
$x=(sin($a)*cos($b))*$ds; $y=(sin($a)*sin($b))*$ds; $z=cos($a)*$ds;
//$x=round($x,$o); $y=round($y,$o); $z=round($z,$o);
return [$x,$y,$z];}//,$a,$b

static function xyz2angles($x,$y,$z,$o=2){//o1,a,o2
$ad=atan(bcdiv($x,$z)); $dc=atan(bcdiv($y,$z));
//$ad=atan2($x,$z); $dc=atan2($y,$z);
//$ad=self::ratan2($x,$z); $dc=self::ratan2($y,$z);
$ds=sqrt(pow($x+$y,2)+pow($z,2));//$ds=$x/cos($ad);
//$ad=rad2deg($ad); $dc=rad2deg($dc);
return [$ad,$dc,$ds];}

#stars
//search opp/adj/hyp
static function triangle_lengths($a,$h){//alpha,hypotenuse
$a=deg2rad($a); $op=sin($a)*$h; $ad=cos($a)*$h;
return [$op,$ad,$h];}

static function star_xyz($r){
//if(is_numeric($r))return sql2('x,y,z','umm.hipparcos','w',['hip'=>$r]);
//if(is_numeric($r))$r=sql2('rarad,decrad,dist','umm.hipparcos','rv',['hip'=>$r]);
//if(is_numeric($r))$r=sql2('ra,dc,dist','umm.hipparcos','rv',['hip'=>$r]);
if(!is_array($r))$r=simbad::callr($r);
$ad=self::ra2deg($r[0]); $dc=self::dec2deg($r[1]); $ds=$r[2];
return self::xyz($ad,$dc,$ds,12);}

static function stars_distance($r1,$r2){
$rx1=self::star_xyz($r1); //pr($rx1);
$rx2=self::star_xyz($r2); //pr($rx2);
[$x1,$y1,$z1]=$rx1;
//$ra=self::xyz2angles($x1,$y1,$z1,12); pr($ra);
return self::distance3d($rx1,$rx2);}

static function test(){
//$ret=self::pythagore(['',1,2]);//p($rb);
//$ret=self::phi_calcul($n);
//$ret=self::fibo();
//$ret=bccomp($phi,$fibo);
//$r=$m->trigo(4,3,'');
$m=new maths(40);
//$ret=$m->test();
$r0=[14.6,107.4443,96.885];//soluce
$r1=['12h30m14s',"+09°01'15",14.3];
//$r1=['00h00m00s',"+00°00'00",0];
$r2=['23h03m57',"-04°47'41",99.43];//hip 113896//hd 217877
$r2=['03h39m27',"-10°41'52",99.05];//hip 17265//hd 23065
//$d2='17265';
//$d2='113896';
//$d2='32578';
$ret=$m->stars_distance($r1,$r2);
return $ret;}

//curve space
static function spacetime($d,$o=''){//datas from Pioneer
$distance=10000000000;//km
$distorsion=1.23;//sec
$distorsion_egdes=0.2;
$ratio=bcdiv(9460730472580,$distance,9);
$time=bcmul($distorsion,$ratio,11);
$time_edges=bcmul($distorsion_egdes,$ratio,10);
$t=bcmul($d,$time,12); $e=bcmul($d,$time_edges,12);
return $o?$d=self::compute_time($t).' +/- '.self::compute_time($e):[$t,$e];}

static function call($p){bcscale(self::$bsc); $ret='';
$fc=$p['fc']; $in1=$p['in1']; $in2=$p['in2']; $in3=$p['in3'];
if(method_exists('maths',$fc))$ret=self::$fc($in1,$in2,$in3);
return is_array($ret)?eco($ret):$ret;}

//interface
static function content($p){
$rid=randid('mth');
$j=$rid.'|maths,call||fc,in1,in2,in3';
$r=get_class_methods('maths');
array_shift($r); array_pop($r); array_pop($r);
$bt=select('fc',$r,'',1);
$bt.=bj($j,lang('ok'),'btn');
$bt.=inputcall($j,'in1','');
$bt.=inputcall($j,'in2','');
$bt.=inputcall($j,'in3','');
return $bt.div('','board',$rid);}

}
?>