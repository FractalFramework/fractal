<?php
//layers
class spitable4{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='spt';
static $max=120;
static $unit=40;
static $src='img/svg/spihelix.svg';
static $rsub=[];
static $rhid=[];
static $nt='4';

static function admin(){
$r=admin::app(['a'=>self::$a,'db'=>self::$db]);
//$r[]=['','lk','/spitable4','','spitable4'];
return $r;}

static function js($n=1,$o=''){
return spilib::js($n,$o,self::$nt);}

static function css(){return spilib::css();}
static function headers(){
//head::add('jscode',self::js($p,$o));
head::add('csscode',self::css());}

//sockets
static function helicoid_magical_error(){
$rx[1]=[1=>-1,1];
$ry[1]=[1=>0,0];
$rx[2]=[1=>-2,2,-2,2,-2,2];
$ry[2]=[1=>0,0,-1,-1,1,1];
$rx[3]=[1=>-3,2=>3,3=>-3,4=>3,5=>-3,6=>3,7=>-3,8=>3,9=>-3,10=>3];
$ry[3]=[1=>0,2=>0,3=>-1,4=>-1,5=>1,6=>1,7=>-2,8=>-2,9=>2,10=>2];
$rx[4]=[1=>-4,2=>4,3=>-4,4=>4,5=>-4,6=>4,7=>-4,8=>4,9=>-4,10=>4,11=>-4,12=>4,13=>-4,14=>4];
$ry[4]=[1=>0,2=>0,3=>-1,4=>-1,5=>1,6=>1,7=>-2,8=>-2,9=>2,10=>2,11=>-3,12=>-3,13=>3,14=>3];
//atom angle
$rg[1]=str_split('000');
$rg[2]=str_split('0000000');
$rg[3]=str_split('00000000000');
$rg[4]=str_split('000000000000000');
return [$rx,$ry,$rg];}

static function helicoid(){//layers
$rx[1]=[1=>-1,0];
$ry[1]=[1=>0,0];
$rx[2]=[1=>-2,1,-2,1,-2,1];
$ry[2]=[1=>0,0,-1,-1,1,1];
$rx[3]=[1=>-3,2=>2,3=>-3,4=>2,5=>-3,6=>2,7=>-3,8=>2,9=>-3,10=>2];
$ry[3]=[1=>0,2=>0,3=>-1,4=>-1,5=>1,6=>1,7=>-2,8=>-2,9=>2,10=>2];
$rx[4]=[1=>-4,2=>3,3=>-4,4=>3,5=>-4,6=>3,7=>-4,8=>3,9=>-4,10=>3,11=>-4,12=>3,13=>-4,14=>3];
$ry[4]=[1=>0,2=>0,3=>-1,4=>-1,5=>1,6=>1,7=>-2,8=>-2,9=>2,10=>2,11=>-3,12=>-3,13=>3,14=>3];
//atom angle
$rg[1]=str_split('000');
$rg[2]=str_split('0000000');
$rg[3]=str_split('00000000000');
$rg[4]=str_split('000000000000000');
return [$rx,$ry,$rg];}

static function helicoid2(){//circular
$rx[1]=[1=>-1,0];
$ry[1]=[1=>0,0];
$rx[2]=[1=>-2,1,-1,0,-1,0];
$ry[2]=[1=>0,0,-1,-1,1,1];
$rx[3]=[1=>-3,2=>2,3=>-1,4=>0,5=>-1,6=>0,7=>-2,8=>1,9=>-2,10=>1];
$ry[3]=[1=>0,2=>0,3=>-2,4=>-2,5=>2,6=>2,7=>-1,8=>-1,9=>1,10=>1];
$rx[4]=[1=>-4,2=>3,3=>-1,4=>0,5=>-1,6=>0,7=>-2,8=>1,9=>-2,10=>1,11=>-3,12=>2,13=>-3,14=>2];
$ry[4]=[1=>0,2=>0,3=>-3,4=>-3,5=>3,6=>3,7=>-2,8=>-2,9=>2,10=>2,11=>-1,12=>-1,13=>1,14=>1];
//atom angle
$rg[1]=str_split('000');
$rg[2]=str_split('0000000');
$rg[3]=str_split('00000000000');
$rg[4]=str_split('000000000000000');
return [$rx,$ry,$rg];}

static function helicoid3($p,$ring,$sub,$pos,$i){//diamond
if(spilib::$rhid[$i] && $p!=$i)return [0,0,0];
$n=spilib::$rsub[$ring];
if($n==1){
$rx[1]=[1=>-1,0];
$ry[1]=[1=>0,0];}
elseif($n==2){
$rx[1]=[1=>-2,1];
$ry[1]=[1=>0,0];
$rx[2]=[1=>-1,0,-1,0,-1,0];
$ry[2]=[1=>0,0,-1,-1,1,1];}
elseif($n==3){
$rx[1]=[1=>-3,2];
$ry[1]=[1=>0,0];
$rx[2]=[1=>-2,1,-2,1,-2,1];
$ry[2]=[1=>0,0,-1,-1,1,1];
$rx[3]=[1=>-1,2=>0,3=>-1,4=>0,5=>-1,6=>0,7=>-1,8=>0,9=>-1,10=>0];
$ry[3]=[1=>0,2=>0,3=>-1,4=>-1,5=>1,6=>1,7=>-2,8=>-2,9=>2,10=>2];}
elseif($n==4){
$rx[1]=[1=>-4,3];
$ry[1]=[1=>0,0];
$rx[2]=[1=>-3,2,-3,2,-3,2];
$ry[2]=[1=>0,0,-1,-1,1,1];
$rx[3]=[1=>-2,2=>1,3=>-2,4=>1,5=>-2,6=>1,7=>-2,8=>1,9=>-2,10=>1];
$ry[3]=[1=>0,2=>0,3=>-1,4=>-1,5=>1,6=>1,7=>-2,8=>-2,9=>2,10=>2];
$rx[4]=[1=>-1,2=>0,3=>-1,4=>0,5=>-1,6=>0,7=>-1,8=>0,9=>-1,10=>0,11=>-1,12=>0,13=>-1,14=>0];
$ry[4]=[1=>0,2=>0,3=>-1,4=>-1,5=>1,6=>1,7=>-2,8=>-2,9=>2,10=>2,11=>-3,12=>-3,13=>3,14=>3];}
//atom angle
$rg[1]=str_split('000');
$rg[2]=str_split('0000000');
$rg[3]=str_split('00000000000');
$rg[4]=str_split('000000000000000');
return [$rx,$ry,$rg];}

static function xwidth($d){
}

//atom
static function atompos($p,$ring,$sub,$pos,$i,$v,$nm,$o,$h,$iso){
$u=self::$unit;//unit
[$mode,$clr,$size]=expl('-',$o,3);
$zerox=0; $zeroy=4;
//position on rings
if($mode=='circular')[$rx,$ry,$rg]=self::helicoid2();
elseif($mode=='diamond')[$rx,$ry,$rg]=self::helicoid3($p,$ring,$sub,$pos,$i);
else [$rx,$ry,$rg]=self::helicoid();//layers
//ring_distance//if circular2
$rd=[1=>1,2=>5,3=>11,4=>19,5=>28,6=>36,7=>42,8=>46];
//ring distant//vertical
$rdx=[1=>2,2=>6,3=>12,4=>20,5=>4,6=>12,7=>18,8=>22,9=>26];
//$rdy=[1=>1,2=>1,3=>1,4=>1,5=>8,6=>8,7=>8,8=>8,9=>8];
$rdy=[1=>0,2=>0,3=>0,4=>0,5=>7,6=>7,7=>7,8=>7,9=>7];
//mode
//if($mode=='square'){$addx=($rd[$ring]*$u); $addy=20;}else{}
$addx=($rdx[$ring]*$u); $addy=($rdy[$ring]*$u);
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
$j='spt|spitable4,call|p1='.$i.',|mode,clr,helico';
//$ret.='['.$j.'*['.($x+$dcx1).','.($y+$dcy).',,atom_num*'.$i.':text]:bj]';
//$ret.='['.$j.'*['.($x+$dcx1).','.($y+$dcy).',,atom_num*'.$i.':text]:bjo]';
$rp=['onclick'=>'ajbt(this);'.atj('val',[$i,'lbar']).atj('val',[$i,'lbllbar'])];//onmouseover
$ret.=bj($j,'['.($x+$dcx1+4).','.($y+$dcy-8).',,atom_num*'.$i.':text]','',$rp);//atomic number
$ret.='['.($x+4).','.($y+$dcy-10).',9*'.$pos.':text]';//atomc position
$j='popup|spilib;infos|p1='.$i.'';
//if($rg[$sub][$pos]){$h=$ub; $w=$u;//vertical//unused
$h=$u; $w=$u;
//$ret.='['.($x+$w-12).','.($y+10).',9*'.$iso.':text]';
//$ret.='['.$j.'*['.($x+$u+$dcx2).','.($y+$dcy+2).',,atom_sym*'.$v.':text]:bj]';
$ret.='['.$j.'*['.($x+$dcx2).','.($y+$dcy+8).',,atom_sym*'.$v.':text]:bubj]';//atomic element
return [$x,$y,$w,$h,$ret];}

static function atom($r,$i,$p,$o,$rc,$hl){
[$name,$sym,$fam,$layer,$level]=arr($r[$i],5); //echo $name.'-'.$sym.'-'.$fam.'-'.$level.br();
[$ring,$subring,$pos]=spilib::findpos($level,$i); //echo $ring.'-'.$subring.'-'.$pos.br();
//[$mode,$clr,$size]=expl('-',$o,3);
[$x,$y,$w,$h,$t]=self::atompos($p,$ring,$subring,$pos,$i,$sym,$name,$o,$hl,$r[$i][10]);//$x.'-'.$y.'-'.$w.'-'.$h;
if(!$x && !$y)return;
$clr=spilib::findclr($fam); if($rc)$clr=$rc[$i];
//$hide=$i<=$p?0:1; 
//$hide=spilib::atom_toggle($r[$p]??'',$ring);//anomalies
$hide=spilib::$rhid[$i];
$bdr=$p==$i?'white':($hide?'gray':'black'); $sz=$p==$i?'2':'1'; $alpha=$hide?'0.3':'1';
$atr='[#'.$clr.','.$bdr.','.$sz.',,'.$alpha.':attr]';
$ret='['.$x.','.$y.','.$w.','.$h.',,id'.$i.':rect]';
//$ret='['.$x.','.$y.',20,,id'.$i.':circle]';
return $atr.$ret.$t;}

//build
static function build($p,$o,$c,$hl){//$o=0;
$r=db_read('db/public/atomic','','',1);
$u=self::$unit;//unit
//if($o=='circular'){$u=self::$unit=30; $w=49*$u; $h=10*$u;}
if($o=='circular'){$u=self::$unit; $w=25*$u; $h=18*$u;}
elseif($o=='diamond'){$u=self::$unit; $w=25*$u; $h=18*$u;}
else{$o='layers'; $u=self::$unit; $w=25*$u; $h=18*$u;}
$max=self::$max;
$rc=[]; if($c==2)$rc=spilib::clr2(); elseif($c!=1)$rc=spilib::clr3($r,$c);
spilib::attempt_subrings($r,$p);
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
$o=$p['p2']??($p['mode']??'square');
$c=$p['p3']??($p['clr']??1);
$h=$p['p4']??($p['helico']??1);
$bt=hidden('mode',$o);
$bt.=hidden('clr',$c);
$bt.=hidden('helico',$h);
if($a>self::$max)$a=self::$max;
return $bt.self::build($a,$o,$c,$h);}

static function navb($a,$o,$c,$h){$rid=self::$cb; $ret='';
$ret.=bj($rid.'|spitable4,call|mode=diamond|lbar,clr',lang('diamond'),'btn'.active($o,'diamond')).' ';
$ret.=bj($rid.'|spitable4,call|mode=circular|lbar,clr',lang('circular'),'btn'.active($o,'circular')).' ';
$ret.=bj($rid.'|spitable4,call|mode=layers|lbar,clr',lang('layers'),'btn'.active($o,'layers'));
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