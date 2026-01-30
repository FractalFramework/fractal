<?php
//biface
class spitable7{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='spt';
static $max=120;
static $unit=40;
static $src='img/svg/spihelix.svg';
static $rsub=[];
static $rhid=[];
static $rp=[];
static $nt='7';

static function admin(){
$r=admin::app(['a'=>self::$a,'db'=>self::$db]);
//$r[]=['','lk','/spitable7','','spitable7'];
return $r;}

static function js($n=1,$o=''){
return spilib::js($n,$o,self::$nt);}

static function css(){return spilib::css();}
static function headers(){
//head::add('jscode',self::js($p,$o));
head::add('csscode',self::css());}

static function mkclr($v){
$d=dechex(255-round($v));
return 'ff'.$d.$d;}

static function ring_coord(){
$rdx=[1=>1,2=>5,3=>11,4=>19,5=>4,6=>12,7=>18,8=>22,9=>26];
$rdy=[1=>4,2=>4,3=>4,4=>4,5=>12,6=>12,7=>12,8=>12,9=>12];
$rdn=[1=>1,2=>2,3=>3,4=>4,5=>4,6=>3,7=>2,8=>1,9=>1];
return [$rdx,$rdy,$rdn];}

static function background($o){
$u=self::$unit;
[$rdx,$rdy,$rdn]=self::ring_coord();
$ret='[none,grey,1,,,3-4:attr]'; $mw=23; if($o=='linear')$mw=47;
$ln=fn($x,$y,$h)=>'['.($x+20).','.($y+20).','.($h).':circle]';
for($i=1;$i<9;$i++){$x=$rdx[$i]; $y=$rdy[$i]; $n=$rdn[$i];
	for($o=1;$o<=$n;$o++)$ret.=$ln($u*$x,$u*$y,$u*$o);
}
//if($o=='classic')
return $ret;}

//sockets
static function matrix($n=5){$r=[]; $b=-2;
for($i=1;$i<$n;$i++)$r[$i]=$b+=4;
return $r;}

static function polar($n){
$b=deg2rad(360/$n);
for($i=1;$i<=$n;$i++)$r[$i]=$b*$i;
return $r;}

static function coord($m){
$u=2; $ray=$m;
$ra=self::matrix(); //pr($ra);
$n=$ra[$m];
$rb=self::polar($n); //pr($rb);
foreach($rb as $k=>$v){
$a=$ray*cos($v); $b=$ray*sin($v);
$r[$k]=[round($a,2),round($b,2),$k];}
return $r;}

static function helicoid(){//pyr
$r=self::coord(1); //pr($r);
$rx[1]=array_column($r,0,2);
$ry[1]=array_column($r,1,2);
$r=self::coord(2);
$rx[2]=array_column($r,0,2);
$ry[2]=array_column($r,1,2);
$r=self::coord(3);
$rx[3]=array_column($r,0,2);
$ry[3]=array_column($r,1,2);
$r=self::coord(4);
$rx[4]=array_column($r,0,2);
$ry[4]=array_column($r,1,2);
//atom angle
$rg[1]=str_split('000');
$rg[2]=str_split('0000000');
$rg[3]=str_split('00000000000');
$rg[4]=str_split('000000000000000');
self::$rp=[$rx,$ry,$rg];}

//atom
static function atompos($p,$ring,$sub,$pos,$i,$v,$nm,$o,$h,$iso){
$u=self::$unit;//unit
[$mode,$clr,$size]=expl('-',$o,3);
$zerox=0; $zeroy=0;
//position on rings
[$rx,$ry,$rg]=self::$rp; //pr(self::$rp);
//ring_distance//if circular2
$rd=[1=>1,2=>5,3=>11,4=>19,5=>28,6=>36,7=>42,8=>46];
//ring distant//vertical
[$rdx,$rdy,$rdn]=self::ring_coord();
//mode
if($mode=='linear'){$addx=($rd[$ring]*$u); $addy=0;}
else{$addx=($rdx[$ring]*$u); $addy=($rdy[$ring]*$u);}
//x,y,w,h
if(!isset($rx[$sub][$pos]))return [0,0,0,0,''];
$x=($rx[$sub][$pos]+$zerox)*$u+$addx;
$y=($ry[$sub][$pos]+$zeroy)*$u+$addy;
//t, vertical-horizontal
$dcx=round($u/2); $ub=$u*2;
$l1=strlen($i); $dcx1=$dcx-$l1*4.5;
$l2=strlen($v); $dcx2=$dcx-$l2*6;
$l3=strlen($nm); $dcx3=$dcx-$l3*2;
$dcy=$dcx+4; $sz=10+($u/10); //$s='font-size:'.$sz.'px';
$ret='[black:attr]';//,,,14px
$j='spt|spitable7,call|p1='.$i.',|mode,clr,helico';
$rp=['onclick'=>'ajbt(this);'.atj('val',[$i,'lbar']).atj('val',[$i,'lbllbar'])];//onmouseover
$ret.=bj($j,'['.($x+$dcx1).','.($y+$dcy-8).',,atom_num*'.$i.':text]','',$rp);//atomic number
//$ret.='['.($x+6).','.($y+$dcy-10).',9*'.$pos.':text]';//atomc position
$j='popup|spilib;infos|p1='.$i.'';
//if($rg[$sub][$pos]){$h=$ub; $w=$u;//vertical//unused
$h=$u; $w=$u;
$ret.='['.$j.'*['.($x+$dcx2).','.($y+$dcy+8).',,atom_sym*'.$v.':text]:bubj]';//atomic element
return [$x,$y,$w,$h,$ret];}

static function atom($r,$i,$p,$o,$rc,$hl){
[$name,$sym,$fam,$layer,$level]=arr($r[$i],5); //echo $name.'-'.$sym.'-'.$fam.'-'.$level.br();
[$ring,$subring,$pos]=spilib::findpos($level,$i); //echo $ring.'-'.$subring.'-'.$pos.br();
//[$mode,$c,$size]=expl('-',$o,3);
[$x,$y,$w,$h,$t]=self::atompos($p,$ring,$subring,$pos,$i,$sym,$name,$o,$hl,$r[$i][10]);//$x.'-'.$y.'-'.$w.'-'.$h;
if(!$x && !$y)return;
$clr=spilib::findclr($fam); if($rc)$clr=$rc[$i];
//$hide=$i<=$p?0:1; 
//$hide=spilib::atom_toggle($r[$p]??'',$ring);//anomalies
$hide=spilib::$rhid[$i];
$bdr=$p==$i?'white':($hide?'gray':'black'); $sz=$p==$i?'2':'1'; $alpha=$hide?'0.3':'1';
$atr='[#'.$clr.','.$bdr.','.$sz.',,'.$alpha.':attr]';
//$ret='['.$x.','.$y.','.$w.','.$h.',,id'.$i.':rect]';
$ret='['.($x+20).','.($y+20).',20,,id'.$i.':circle]';
return $atr.$ret.$t;}

//build
static function build($p,$o,$c,$hl){//$o=0;
$r=db_read('db/public/atomic','','',1);
$u=self::$unit;//unit
if($o=='linear'){$u=self::$unit; $w=25*$u; $h=18*$u;}
else{$o='classic'; $u=self::$unit; $w=25*$u; $h=18*$u;}
$max=self::$max;
$rc=[]; if($c==2)$rc=spilib::clr2(); elseif($c!=1)$rc=spilib::clr3($r,$c);
spilib::attempt_subrings($r,$p);
self::helicoid();//position on rings
for($i=1;$i<=$max;$i++)$rb[]=self::atom($r,$i,$p,$o,$rc,$hl);
//$ret='[white,black,1:attr][0,0,'.$w.','.$h.':rect]';
$ret=self::background($o);
$ret.=implode("\n",$rb);
$bt=self::nav($p,$o,$c,$hl);
$bt.=spilib::legend($c);
if($ret)$ret=svg::call(['code'=>$ret,'w'=>$w,'h'=>$h,'fit'=>1]);
return $bt.$ret;}

static function call($p){//pr($p);
$inpspi=$p['inpspi']??self::$max;
$a=$p['p1']??($p['lbar']??$inpspi);
$o=$p['p2']??($p['mode']??'square');
$c=$p['p3']??($p['clr']??1);
$h=$p['p4']??($p['helico']??1);
$bt=hidden('mode',$o);
$bt.=hidden('clr',$c);
$bt.=hidden('helico',$h);
if($a>self::$max)$a=self::$max;
return $bt.self::build($a,$o,$c,$h);}

static function navb($a,$o,$c,$h){$rid=self::$cb; $ret='';
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
return div($bt.div($ret,'',$rid),'board','sptcb');}

static function iframe($p){
return spilib::iframe($p);}

}
?>