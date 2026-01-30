<?php

class calendav{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cols=[''];
static $typs=[''];
static $cb='cld';
static $rt=[];

/*static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}*/

static function admin(){return menu::call(['app'=>'admin','mth'=>'app','drop'=>1,'a'=>self::$a]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#vars
static $default='now';
//static $start='26-11-20';
static $start='21-12-20';
//static $daysfr=['seli','gamma','kali','alpha','jlimi','silio','dali'];
//static $daysen=['Dali','Seli','Gamma','Kali','Alpha','Jlimi','Silio'];
//static $ra=['d','e','g','k','a','j','s'];
static $chakradays=['Am','Lam','Vann','Ram','Miam','Jam','Sham'];//en
static $ra=['a','l','v','r','m','j','s'];//chakras
//static $chakradaysen=['root','thrideye','sacral','trhoat','solar','heart','crown'];//fr
static $weeks=['2','3','4','5','6','7','8','9','10','J','Q','K','A'];
static $rb=['2','3','4','5','6','7','8','9','10','j','q','k','a'];
static $season=['&spades;','&clubs;','&hearts;','&diams;','&#127183;'];//21/12
static $rc=['s','c','h','d','j'];//21/12
//static $season=['&spades;','&clubs;','&hearts;','&diams;','&#127183;'];//26/2
//static $rc=['s','c','h','d','j'];//26/2
//static $startday=364-353;//21 dec
//static $jokerday=3;//perihelion (4 jan)
//$aphelion=184 (4 jul);

#lib
static function dateref($y=''){
if(!$y)$y=date('y');
return new DateTime(self::$start.$y);}

static function startday(){
$dt=self::dateref();
$z=$dt->format('z')-1;
return 364-$z;}

//return IntlGregorianCalendar::isLeapYear($y);
static function is_leap_year($y){
$r=[400=>1,100=>0,4=>1];
foreach($r as $k=>$v)if($y%$k==0)return $v;
return 0;}

static function day($d){return self::$chakradays[$d];}
static function week($d){return self::$weeks[$d];}
static function seasonclr($d,$o=''){return $d==2||$d==3?'red':$o;}
static function season($d){$c=self::seasonclr($d); $t=self::$season[$d];
return span($t,'','','font-size:larger; color:'.$c.';');}
static function seasonsvg($d,$x,$y){$c=self::seasonclr($d);
return '['.$c.':attr]['.$x.','.$y.'*'.self::$season[$d].':text]';}

#graphic
static function labels($h,$l,$k,$na,$sp){
$n=($na/2); $a=90; $s=$a/$n; $m=$s/2; $nb=floor($n); $tn=self::$rt[$k];
$h3=$h-($l*$k)-$l/2-$sp*$k;
for($i=0;$i<$n;$i++){
	$x=$h3*sin(deg2rad(-$a+$s*$i+$m)); $y=$h3*cos(deg2rad(-$a+$s*$i+$m));
	$t=match($k){0=>self::day($i),1=>self::week($i),default=>self::$season[$i]};
	$tb=match($k){0=>self::day($na-1-$i),1=>self::week($na-1-$i),default=>self::$season[$na-1-$i]};
	$xt=$k==2?-4:svg::center_text($t,6); $sty='font-size:'.($k==2?32:16).'px;';
	$clr=$k==2?self::seasonclr($i,'white'):'white';
	$rt[]='['.$clr.':attr]['.($h+$x+$xt).','.($h-$y).','.$sty.'*'.$t.':text]';
	$clr=$k==2?self::seasonclr($na-$i-1,'white'):'white';
	$rt[]='['.$clr.':attr]['.($h-$x+$xt).','.($h-$y).','.$sty.'*'.$tb.':text]';}
return join('',$rt);}

static function radius($h,$l,$k,$n,$sp){
$n=($n/2); $s=90/$n; 
//$h1=$h-($l*$k); $h2=$h-($l*($k+1)); $h3=($h1+$h2)/2;
$h1=$h-($l*$k)-$k*$sp; $h2=$h-($l*($k+1))-$k*$sp; $h3=($h1+$h2)/2;
for($i=1;$i<=$n;$i++){
	$xa=$h1*sin(deg2rad(-90+$s*$i)); $ya=$h1*cos(deg2rad(-90+$s*$i));
	$xb=$h2*sin(deg2rad(-90+$s*$i)); $yb=$h2*cos(deg2rad(-90+$s*$i));
	$xc=$h3*sin(deg2rad(-90+$s*$i)); $yc=$h3*cos(deg2rad(-90+$s*$i));
	$rt[]='[white,1,.5:stroke]['.($h+$xa).','.($h-$ya).','.($h+$xb).','.($h-$yb).':line]';
	$rt[]='[white,1,.5:stroke]['.($h-$xa).','.($h-$ya).','.($h-$xb).','.($h-$yb).':line]';}
return join('',$rt);}

static function graph(){
$w=600; $h=$w/2; $l=80; $lb=$l/2; $sp=5;
$clr=['red'=>'#F54927','green'=>'#7CCF35','blue'=>'#2B7FFF','gold'=>'gold','automn'=>'#D0872E','winter'=>'#99A1AF','spring'=>'#7CCF35','summer'=>'#2B7FFF'];
//[$d,$w,$s,$y]=self::$rt; //pr(self::$rt);
//$sa=180/7; $sb=180/13; $sc=180/4; $a=($d+1)*$sa; $b=($w+1)*$sb; $c=($s+1)*$sc;//angles
$rn=[7,13,4]; foreach($rn as $k=>$v)$rb[$k]=(self::$rt[$k]+1)*(180/$v); $seas=(string) self::$rt[2];
$clr2=match($seas){'0'=>$clr['winter'],'1'=>$clr['spring'],'2'=>$clr['summer'],default=>$clr['automn']};
for($i=0;$i<3;$i++)$rh[]=$h-($l*$i)-$lb-$sp*$i; [$h1,$h2,$h3]=$rh;
$ret='[,black:attr]['.$h.','.$h.','.$h.',0,180:arc]
['.$clr['red'].','.$l.',.2:stroke]['.$h.','.$h.','.$h1.',0,180:arc]
['.$clr['red'].','.$l.',.9:stroke]['.$h.','.$h.','.$h1.',0,'.$rb[0].':arc]
['.$clr['gold'].','.$l.',.2:stroke]['.$h.','.$h.','.$h2.',0,180:arc]
['.$clr['gold'].','.$l.',.9:stroke]['.$h.','.$h.','.$h2.',0,'.$rb[1].':arc]
['.$clr2.','.$l.',.2:stroke]['.$h.','.$h.','.$h3.',0,180:arc]
['.$clr2.','.$l.',.9:stroke]['.$h.','.$h.','.$h3.',0,'.$rb[2].':arc]';
//$ret.=self::radius($h,$l,0,7); $ret.=self::radius($h,$l,1,13); $ret.=self::radius($h,$l,2,4);
foreach($rn as $k=>$v)$ret.=self::radius($h,$l,$k,$v,$sp);
foreach($rn as $k=>$v)$ret.=self::labels($h,$l,$k,$v,$sp);
return svg::com($ret,$w,$h);}

#decode
static function reverse($sd,$o=''){
if(!$sd)return 'no';
[$d,$y]=strsplit($sd,-2);
$g=self::is_leap_year($y);
$s=self::startday();
[$a,$b,$c]=str_split($d);//d5s
$a=array_flip(self::$ra)[$a];//in_array_b($ra,$a);
$b=array_flip(self::$rb)[$b];
$c=array_flip(self::$rc)[$c];
$n=$b*7+$c*91+$a; //-$s if 1/1/26
if($n>11)$y-=1;
$dt=self::dateref($y);
$t=$dt->getTimestamp();
$t+=$n*86400;
//pr([$a,$b,$c,$n,$y]);
return $o?date($o==1?'ymd':$o,$t):$t;}

static function call2($p){
[$a,$o,$rid,$p2]=vals($p,['a','o','rid','p2']); //$p['p4']
return self::reverse($p[$p2]??'','d-m-Y');}

#encode
static function compute($d,$g){$rt=[];
$rt=['day'=>0,'week'=>0,'season'=>0,'year'=>0];
$r=['year'=>$g?366:365,'season'=>91,'week'=>7,'day'=>1];
foreach($r as $k=>$v)if($d>=$v){$n=floor($d/$v); $d-=$v*$n; $rt[$k]=$n;}
return array_values($rt);}

static function convert($d){//21 dec
$s=self::startday();
return $d+$s;}

static function build_str(){[$d,$w,$s,$y]=self::$rt;
return self::$ra[$d].self::$rb[$w].self::$rc[$s].$y;}

static function build($z,$y=0,$dw=0,$o=''){
$g=self::is_leap_year($y);
$r=self::compute($z,$g); [$d,$w,$s]=$r; //pr($r);
self::$rt=[$d,$w,$s,$y];
//if($o)return self::$ra[$d].self::$rb[$w].self::$rc[$s].$y; else 
return self::day($d).' '.self::week($w).self::season($s).' '.$y;}

static function call($p){
[$a,$o,$rid,$p1]=vals($p,['a','o','rid','p1']); $a=$p[$p1]??$a;//$p['p4']; //pr($p);
$tz=new DateTimeZone(ses::$cnfg['tz']);
$dt=new DateTimeImmutable($a?$a:self::$default,$tz);
$z=$dt->format('z');//day of year [0-365]
$y=$dt->format('y');//year
$w=$dt->format('w');//day of week [0-6]
$z=self::convert($z);
$ret=self::build($z,$y,$w,$o);
return $ret;}

static function growdate($p1,$p4){//correct calendar change of month
$a=strtotime($p1); $b=mktime2(date('Y'),1,1)+(86400*$p4);
echo $p1.' ; '.$p4;
if($b-$a>86400)return datz('Y-m-d',$b+86400);//grow
if($a-$b>86400)return datz('Y-m-d',$b+86400);//down
return $p1;}

static function call0($p){
[$a,$o,$rid,$p1,$p2,$p3,$p4]=vals($p,['a','o','rid','p1','p2','p3','p4']);
$a=$p[$p1]??$a;
if(isset($p[$p4])){$a=day2date($p[$p4]); $p[$p1]=$a;}
//$p[$p1]=self::growdate($p[$p1]??'',$p[$p4]??'');
$rt[$rid]=self::call($p);
$rt[$p1]=$a;
$rt[$p2]=self::build_str();
$rt[$p3]=join('-',self::$rt);
$rt[$p4]=datz('z',$a);
$rt['cdgr']=self::graph();
return $rt;}

static function menu($p){
[$a,$o,$rid]=vals($p,['a','o','rid']);
for($i=1;$i<5;$i++)$ri['p'.$i]='p'.$i.$rid; $prm=prm($p+$ri);
$p1=$ri['p1']; $p2=$ri['p2']; $p3=$ri['p3']; $p4=$ri['p4'];
$j=$rid.';'.$p1.';'.$p2.';'.$p3.';'.$p4.';cdgr|calendav,call0|'.$prm.'|'.$p4;
$ret=inpnb($p4,datz('z'),'3','','',$j);
$j=$rid.';'.$p1.';'.$p2.';'.$p3.';'.$p4.';cdgr|calendav,call0|'.$prm.'|'.$p1;//.','.$p4
$ret.=inpdate($p1,$a,'','','',$j);
$ret.=bj($j,pic('ok')).' ';
$j=$rid.'|calendav,call2|'.prm(['a'=>$a,'o'=>'d-m-Y','rid'=>$rid,'p2'=>$p2]).'|'.$p2;
$str=self::build_str();
$ret.=input($p2,$str,'','','','',$j,'');
$ret.=bj($j,pic('ok')).' ';
$ret.=hlpbt('calendav_app');
$ret.=hidden($p3,'');
return div($ret);}

#interface
static function content($p){
$bt=self::admin();
[$a,$o]=vals($p,['a','o']);
if(!$a)$a=datz('Y-m-d');
$rid=randid(self::$cb); $ret='';
if($a)$ret=self::call($p);
$graph=self::graph();
$bt.=self::menu($p+['a'=>$a,'rid'=>$rid]);
return $bt.div($ret,'4stime',$rid).div($graph,'','cdgr');}
}
?>