<?php
//linear//old
class spitable3{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='spr';
static $max=120;
static $ratio=1;
static $mode=1;
static $nt='3';

static function admin(){
$r=admin::app(['a'=>self::$a,'db'=>self::$db]);
//$r[]=['','lk','/spitable','','spitable'];
return $r;}

static function js($n=1,$o=''){
$j=self::$cb.'|spitable3,call';
return 'var n='.$n.';
addEvent(document,"DOMMouseScroll",function(){wheelcount(event,"'.$j.'","'.$o.'")});';}

static function css(){$ret='';
for($i=1;$i<self::$max;$i++)$ret.='#id'.$i.':hover{background:rgba(255,255,255,0.4);}'."\n";
return $ret;}
static function headers(){
//head::add('jscode',self::js($p,$o));
head::add('csscode',self::css());}

static function legend(){$i=0; $ret='';
$r=spilib::basic_colors(); $w=100; $h=20; $y=20; $x=60; $n=count($r); $nc=16500000/$n;
$sz=[1=>70,2=>100,3=>90,4=>120,5=>70,6=>60,7=>50,8=>90,9=>80,10=>60,11=>70];
foreach($r as $k=>$v)if($k){$i++; 
	$w=strlen($k)*8; $w=$sz[$i];
	$ret.='[#'.$v.',gray:attr]['.$x.','.$y.','.$w.','.$h.':rect]';
	$ret.='[black,,,14px:attr]['.($x+4).','.($y+15).',font-size:12px*'.$k.':text]';
	$x+=$w;}
	$ret.='[50,640,9*@Davy 2003-2025:text]';
return $ret;}

static function layers(){
$u=20; $zeroy=3; $ret=''; $y=$zeroy*$u*self::$ratio;
$rd=['k'=>2,'l'=>5,'m'=>9,'n'=>14,'o'=>19,'p'=>23,'q'=>26,'r'=>28];
$rh=['k'=>1,'l'=>2,'m'=>3,'n'=>4,'o'=>4,'p'=>3,'q'=>2,'r'=>1];
foreach($rd as $k=>$v){$h=$rh[$k]*$u*self::$ratio;
	$ret.='[,black,1:attr][40,'.$y.',40,'.($y+$h).':line]';
	$ret.='[orange,,:attr][20,'.($y+($h/2)+6).',12px*'.$k.':text]';
	$y+=$h+$u*self::$ratio;}
return $ret;}

static function draft(){
$d='
[white,black,1:attr]
[0,0,400,400:rect]
[none,red,2:attr]
';

function cutpi($n){$r=[];
$rad=M_PI/180; $m=180/$n;
for($i=1;$i<=$n;$i++)$r[]=[round(cos($m*$i*$rad),2),round(sin($m*$i*$rad),2)];
return $r;}

//dev
function arc($r,$u,$ia){$d='';
foreach($r as $k=>$v){
$ray=$u*$ia; $x=$v[0]*$ray+$u; $y=$v[1]*$ray;
$d.='a '.$ray.'/'.$ray.' 0 0/1 '.$x.'/'.$y.' ';}
return $d;}

$m=200;
$u=20;
//$r[0]=[0,0];
$r[1]=[1,0];
$d2=arc($r,$u,1);
$r=cutpi(3); p($r);
$d2.=arc($r,$u,2);
$rb=cutpi(5);
//$d2.=arc($r,$u,1);
$rb=cutpi(7);
//$d2.=arc($r,$u,1);
echo $d2;
//pr($r);

//$d2='a 20/20 0 0/1 40/0';
$d2='a 20/20 0 0/1 33.2/-20 ';
$d2.='a 20/20 0 0/1 33.2/0 ';

//$d.='[M 200/200 '.$d2.':path]';
$d.='[none,red,2:attr][M 200/200 a 20/20 0 0/1 10/-8.7:path]';
$d.='[none,blue,2:attr][M 210/191.3 a 20/20 0 0/1 17.2/0:path]';
$d.='[none,green,2:attr][M 227.2/191.3 a 20/20 0 0/1 10/8.7:path]';
$d.='[none,red,2:attr][M 240/200 a 40/40 0 0/1 -20/17.2:path]';

$ret=svg::com($d,400,400);
return $ret;}

//positions
static function atompos($ring,$sub,$pos,$n,$v,$mode){
$u=20;//unit
$zerox=0; $zeroy=2; $ratio=self::$ratio;
//position on rings
$ry[1]=[1=>0,0];
$ry[2]=[1=>1,1,2,2,2,2];
$ry[3]=[1=>3,3,4,4,4,4,5,5,5,5];
$ry[4]=[1=>6,6,7,7,7,7,8,8,8,8,9,9,9,9];
$ry[5]=[1=>10,10,11,11,11,11,12,12,12,12,13,13,13,13,14,14,14,14];
$ry[6]=[1=>15,15,16,16,16,16,17,17,17,17,18,18,18,18,19,19,19,19,20,20,20,20];
$ry[7]=[1=>21,21,22,22,22,22,23,23,23,23,24,24,24,24,25,25,25,25,26,26,26,26,27,27,27,27];
//ring_height
$rd=[1=>0,2=>2,3=>5,4=>9,5=>14,6=>19,7=>23,8=>26,9=>29];
//angular pos
$rx[1]=[0=>0,14];
$ni=14/5; for($i=0;$i<6;$i++)$rx[2][$i]=$i*$ni;
$ni=14/9; for($i=0;$i<10;$i++)$rx[3][$i]=$i*$ni;
$ni=14/13; for($i=0;$i<14;$i++)$rx[4][$i]=$i*$ni;
//x,y,w,h
$valence=(count($ry[$sub])-$pos);
if($mode==4)$x=((14-$valence)*3+$zerox)*$u;//inverted on right
elseif($mode==2)$x=60+($valence*3+$zerox)*$u;//inverted on left
elseif($mode==3)$x=((15-$pos)*3+$zerox)*$u;//on right
elseif($mode==5)$x=(3+$rx[$sub][$pos-1]*3+$zerox)*$u;//angular
else $x=($pos*3+$zerox)*$u;//on left
//if($ring==1 && $sub==1 && $pos==2)$x=(($pos+4)*3+$zerox)*$u;
$y=($rd[$ring]+($sub)+$zeroy)*$u*$ratio;
//if($n==11)echo $n.'-'.$rd[$ring].'-'.$ry[$sub][$pos].br();
$w=$u*3; $h=$u*self::$ratio;
$ret='[black,,,12px:attr]';
//$ret.='[spl|spitable3;call|p1='.$n.'|mode*['.($x+6).','.($y+15).',font-size:12px*'.$n.':text]:bj]';
$j='spl|spitable3,call|p1='.$n.',|mode';
$rp=['onclick'=>'ajbt(this);'.atj('val',[$n,'lbar']).atj('val',[$n,'lbllbar'])];//onmouseover
$ret.=bj($j,'['.($x+6).','.($y+15).',font-size:12px*'.$n.':text]','',$rp);
if($ratio==2)$ret.='['.($x+6).','.($y+35).',12*'.$valence.':text]';
$j='popup|spilib;infos|p1='.$n;
//$ret.='['.$j.'*['.($x+$w/2).','.($y+(15*$ratio)).',font-size:'.(12*$ratio).'px*'.$v.':text]:bj]';
$ret.='['.$j.'*['.($x+$w/2).','.($y+(15*$ratio)).',font-size:'.(12*$ratio).'px*'.$v.':text]:bubj]';
//echo $x.'-'.$y.'-'.$w.'-'.$h.br();
return [$x,$y,$w,$h,$ret];}

//pos from coordinates
static function findpos2($i){
$r=[2,6,10,14]; $n=$i-self::$max;
foreach($r as $k=>$v)if($n-$v<0){$ring=8; $sub=$k; $pos=$n-$v;}
return [$ring,$sub,$pos];}

static function findpos($level,$i){
if($i>self::$max)return self::findpos2($i);
$ring=substr($level,0,1);
$subring=substr($level,1,1);
if($subring=='s')$sub=1;
elseif($subring=='p')$sub=2;
elseif($subring=='d')$sub=3;
elseif($subring=='f')$sub=4;
else $sub=5;
$pos=substr($level,2);
return [$ring,$sub,$pos];}

/*static function findpos_layer($level,$i){
$rg=[1=>2,8,18,32,32,18,8,2];
$r=explode('-',$level); $n=count($r);
$mx=$rg[$n]; $sub=$mx-$r[$n-1];
echo $i.';'.$level.';n:'.$n,';ring:'.$ring.';sub:'.$sub.br();
return [$ring,$sub,$pos];}*/

static function build_level($i){//7p6
$ra=['s','p','d','f','g',''];}

static function atom_toggle($r,$ring){static $rx;
[$name,$sym,$fam,$layer,$level]=$r;
$ra=explode('-','-'.$layer);
$max=val($ra,$ring); $rx[$ring][]=1;//count elemenrs in rings
return count($rx[$ring])<=$max?0:1;}

//atom
static function atom($r,$i,$mode,$p,$rc){
[$name,$sym,$fam,$layer,$level]=val($r,$i,['','','','','']);
[$ring,$subring,$pos]=self::findpos($level,$i);
//[$ring,$subring,$pos]=self::findpos_layer($layer,$i);
[$x,$y,$w,$h,$t]=self::atompos($ring,$subring,$pos,$i,$sym,$mode);
$clr=spilib::findclr($fam); if($rc)$clr=$rc[$i];
$hide=self::atom_toggle($r[$p]??'',$ring);//anomalies
$bdr=$p==$i?'white':($hide?'gray':'black'); $sz=$p==$i?'2':'1'; $alpha=$hide?'0.1':'1';
$atr='[#'.$clr.','.$bdr.','.$sz.',,'.$alpha.':attr]';
$rect='['.$x.','.$y.','.$w.','.$h.',,id'.$i.':rect]';
return $atr.$rect.$t;}

//build
static function build($p,$o,$c){//$o=0;
$r=db_read('db/public/atomic','','',1);
$w=1000; $h=680*self::$ratio; $sz=$w.'/'.$h;
$rb[0]=self::legend();
$rb[1]=self::layers();
$rc=[]; if($c==2)$rc=spilib::clr2(); elseif($c!=1)$rc=spilib::clr3($r,$c);
for($i=1;$i<=self::$max;$i++)$rb[]=self::atom($r,$i,$o,$p,$rc);
$ret=implode("\n",$rb);
if($ret)$ret=svg::call(['code'=>$ret,'w'=>$w,'h'=>$h,'fit'=>1]);//render
return $ret;}

static function call($p){//pr($p);
$inpspi=$p['inpspi']??self::$max;
$a=$p['p1']??($p['lbar']??$inpspi);
$o=$p['p2']??($p['mode']??self::$mode);
$c=$p['p3']??($p['clr']??1);
$bt=self::nav($a,$o,'','');
$bt.=hidden('mode',$o);
if($a>self::$max)$a=self::$max; if($a<1)$a=1;
return $bt.self::build($a,$o,$c);}

static function navb($a,$o,$c,$h){$rid=self::$cb; $ret='';
$ra=[1=>'left','valences/left','right','valences/right'];//,'angular'
foreach($ra as $k=>$v)$ret.=bj(self::$cb.'|spitable3,call|p1='.$a.',p2='.$k,$v,'btn'.active($o,$k)).'';
return div($ret);}

static function nav($a,$o,$c,$h){
return spilib::nav($a,$o,$c,$h,self::$nt);}

static function menu($p,$o,$rid){
return spilib::menu($p,$o,$rid,self::$nt);}

static function content($p){
$a=$p['lbar']??self::$max; $o=$p['mode']??'';
$rid=self::$cb; //$o=1;
$bt=self::menu($a,$o,$rid);
head::add('jscode',self::js($a,$o));
$ret=self::call($p);
return div($bt.div($ret,'',$rid),'board','splcb');}

static function iframe($p){
return spilib::iframe($p);}

}
?>