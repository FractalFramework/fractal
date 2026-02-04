<?php
//classical
class spitable{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='spt';
static $max=120;
static $unit=40;
static $src='img/svg/spihelix.svg';
static $nt='';

static function admin(){
$r=admin::app(['a'=>self::$a,'db'=>self::$db]);
//$r[]=['','lk','/spitable','','spitable2'];
return $r;}

static function js($n=1,$o=''){
return spilib::js($n,$o,self::$nt);}

static function css(){return spilib::css();}
static function headers(){
//head::add('jscode',self::js($p,'',''));
head::add('csscode',self::css());}

//sockets
static function helicoid(){
$rx[1]=[1=>-1,0];
$ry[1]=[1=>0,0];
$rx[2]=[1=>0,-2,-2,-2,0,1];
$ry[2]=[1=>-1,-1,0,2,2,0];
$rx[3]=[1=>2,2=>0,3=>-2,4=>-3,5=>-3,6=>-3,7=>-2,8=>0,9=>2,10=>2];
$ry[3]=[1=>-2,2=>-2,3=>-2,4=>-2,5=>0,6=>2,7=>3,8=>3,9=>2,10=>0];
$rx[4]=[1=>3,2=>2,3=>0,4=>-2,5=>-4,6=>-4,7=>-4,8=>-4,9=>-4,10=>-2,11=>0,12=>2,13=>3,14=>3];
$ry[4]=[1=>-2,2=>-3,3=>-3,4=>-3,5=>-3,6=>-2,7=>0,8=>2,9=>4,10=>4,11=>4,12=>4,13=>2,14=>0];
//atom angle
$rg[1]=str_split('011');
$rg[2]=str_split('0001001');
$rg[3]=str_split('01001110011');
$rg[4]=str_split('010000111000011');
return [$rx,$ry,$rg];}

static function helicoid2(){
$rx[1]=[1=>-1,0];
$ry[1]=[1=>0,0];
$rx[2]=[1=>-1,-1,1,-2,1,-2];
$ry[2]=[1=>2,-1,1,-1,-1,1];
$rx[3]=[1=>0,2=>-2,3=>-2,4=>0,5=>-3,6=>2,7=>-3,8=>2,9=>-3,10=>2];
$ry[3]=[1=>-2,2=>3,3=>-2,4=>3,5=>-2,6=>2,7=>0,8=>0,9=>2,10=>-2];
$rx[4]=[1=>-3,2=>1,3=>-1,4=>-1,5=>1,6=>-3,7=>3,8=>-4,9=>3,10=>-4,11=>3,12=>-4,13=>3,14=>-4];
$ry[4]=[1=>4,2=>-3,3=>4,4=>-3,5=>4,6=>-3,7=>3,8=>-3,9=>1,10=>-1,11=>-1,12=>1,13=>-3,14=>3];
//atom angle
$rg[1]=str_split('011');
$rg[2]=str_split('0001111');
$rg[3]=str_split('00000111111');
$rg[4]=str_split('000000011111111');
return [$rx,$ry,$rg];}

//atom
static function atompos($ring,$sub,$pos,$i,$v,$nm,$o,$h,$iso){
$u=self::$unit;//unit
[$mode,$clr,$size]=expl('-',$o,3);
$zerox=0; $zeroy=4;
//position on rings
if($h==2)[$rx,$ry,$rg]=self::helicoid2();
else [$rx,$ry,$rg]=self::helicoid();
//ring_distance//if radial2
$rd=[1=>1,2=>5,3=>11,4=>19,5=>28,6=>36,7=>42,8=>46];
//ring distant//vertical
$rdx=[1=>2,2=>6,3=>12,4=>20,5=>4,6=>12,7=>18,8=>22,9=>26];
//$rdy=[1=>1,2=>1,3=>1,4=>1,5=>8,6=>8,7=>8,8=>8,9=>8];
$rdy=[1=>0,2=>0,3=>0,4=>0,5=>7,6=>7,7=>7,8=>7,9=>7];
//mode
if($mode=='linear'){$addx=($rd[$ring]*$u); $addy=20;}
else{$addx=($rdx[$ring]*$u); $addy=($rdy[$ring]*$u);}
//x,y,w,h
$x=($rx[$sub][$pos]+$zerox)*$u+$addx;
$y=($ry[$sub][$pos]+$zeroy)*$u+$addy;
//t, vertical-horizontal
$dcx=round($u/2); $ub=$u*2;
$l1=strlen($i); $dcx1=$dcx-$l1*4.5;
$l2=strlen($v); $dcx2=$dcx-$l2*6;
$l3=strlen($nm); $dcx3=$dcx-$l3*2;
$dcy=$dcx+6; $sz=10+($u/10); //$s='font-size:'.$sz.'px';
$ret='[black:attr]';//,,,14px
$j='spt|spitable,call|p1='.$i.',|mode,clr,helico';
//$ret.='['.$j.'*['.($x+$dcx1).','.($y+$dcy).',,atom_num*'.$i.':text]:bj]';
//$ret.='['.$j.'*['.($x+$dcx1).','.($y+$dcy).',,atom_num*'.$i.':text]:bjo]';
$rp=['onclick'=>'ajbt(this);'.atj('val',[$i,'lbar']).atj('val',[$i,'lbllbar'])];//onmouseover
$ret.=bj($j,'['.($x+$dcx1).','.($y+$dcy).',,atom_num*'.$i.':text]','',$rp);
$ret.='['.($x+2).','.($y+10).',9*'.$pos.':text]';
$j='popup|spilib;infos|p1='.$i.'';
if($rg[$sub][$pos]){$h=$ub; $w=$u;//vertical
	//$ret.='['.($x+$w-12).','.($y+10).',9*'.$iso.':text]';
	//$ret.='['.$j.'*['.($x+$dcx2).','.($y+$ub-$dcy+2).',,atom_sym*'.$v.':text]:bj]';
	$ret.='['.$j.'*['.($x+$dcx2).','.($y+$dcy+$u).',,atom_sym*'.$v.':text]:bubj]';}
else{$h=$u; $w=$ub;
	//$ret.='['.($x+$w-12).','.($y+10).',9*'.$iso.':text]';
	//$ret.='['.$j.'*['.($x+$u+$dcx2).','.($y+$dcy+2).',,atom_sym*'.$v.':text]:bj]';
	$ret.='['.$j.'*['.($x+$u+$dcx2).','.($y+$dcy).',,atom_sym*'.$v.':text]:bubj]';}
return [$x,$y,$w,$h,$ret];}

static function atom($r,$i,$p,$o,$rc,$hl){
[$name,$sym,$fam,$layer,$level]=arr($r[$i],5); //echo $name.'-'.$sym.'-'.$fam.'-'.$level.br();
[$ring,$subring,$pos]=spilib::findpos($level,$i); //echo $ring.'-'.$subring.'-'.$pos.br();
//[$mode,$clr,$size]=expl('-',$o,3);
[$x,$y,$w,$h,$t]=self::atompos($ring,$subring,$pos,$i,$sym,$name,$o,$hl,$r[$i][10]); //echo $x.'-'.$y.'-'.$w.'-'.$h;
$clr=spilib::findclr($fam); if($rc)$clr=$rc[$i];
$hide=$i<=$p?0:1; $hide=spilib::atom_toggle($r[$p]??'',$ring);//anomalies
$bdr=$p==$i?'white':($hide?'gray':'black'); $sz=$p==$i?'2':'1'; $alpha=$hide?'0.3':'1';
$atr='[#'.$clr.','.$bdr.','.$sz.',,'.$alpha.':attr]';
$rect='['.$x.','.$y.','.$w.','.$h.',,id'.$i.':rect]';
return $atr.$rect.$t;}

//build
static function build($p,$o,$c,$hl){//$o=0;
$r=db_read('db/public/atomic','','',1);
$u=self::$unit;//unit
if($o=='linear'){$u=self::$unit=30; $w=49*$u; $h=10*$u;}
elseif($o=='radial2'){$u=self::$unit=60; $w=25*$u; $h=18*$u;}
else{$o='radial'; $u=self::$unit; $w=25*$u; $h=18*$u;}
$max=self::$max; //p($r[$p]);
$rc=[]; if($c==2)$rc=spilib::clr2(); elseif($c!=1)$rc=spilib::clr3($r,$c);
for($i=1;$i<=$max;$i++)$rb[]=self::atom($r,$i,$p,$o,$rc,$hl);
//$ret='[white,black,1:attr][0,0,'.$w.','.$h.':rect]';
$ret=implode("\n",$rb);
$bt=self::nav($p,$o,$c,$hl);
$bt.=spilib::legend($c);
if($ret)$ret=svg::call(['code'=>$ret,'w'=>$w,'h'=>$h,'fit'=>1]);
return $bt.$ret;}

static function call($p){//pr($p);
$inpspi=$p['inpspi']??self::$max;
$a=$p['p1']??($p['lbar']??$inpspi);
$o=$p['p2']??($p['mode']??'radial');
$c=$p['p3']??($p['clr']??1);
$h=$p['p4']??($p['helico']??1);
$bt=hidden('mode',$o);
$bt.=hidden('clr',$c);
$bt.=hidden('helico',$h);
if($a>self::$max)$a=self::$max;
return $bt.self::build($a,$o,$c,$h);}

static function navb($a,$o,$c,$h){$rid=self::$cb; $ret='';
$ret.=bj($rid.'|spitable,call|mode=radial|lbar,clr,helico',lang('radial'),'btn'.active($o,'radial'));
$ret.=bj($rid.'|spitable,call|mode=radial2|lbar,clr,helico',lang('big'),'btn'.active($o,'radial2'));
$ret.=bj($rid.'|spitable,call|mode=linear|lbar,clr,helico',lang('linear'),'btn'.active($o,'linear')).' | ';
$ret.=bj($rid.'|spitable,call|helico=1|lbar,mode,clr',lang('propeller'),'btn'.active($h,'1')).' ';
$ret.=bj($rid.'|spitable,call|helico=2|lbar,mode,clr',lang('double-helix'),'btn'.active($h,'2'));
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