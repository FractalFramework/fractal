<?php
//show subrings
class spitable8{
static $private=0;
static $a=__CLASS__;
static $db='';
static $cb='spt';
static $max=36;
static $unit=40;
static $src='img/svg/spihelix.svg';
static $rsub=[];
static $rhid=[];
static $rx=[];
static $nt='8';

static $sz='1000/260';
static $ratio=2;
static $base=240;

static function css(){$ret='';
for($i=1;$i<self::$max;$i++)$ret.='#id'.$i.':hover{background:rgba(255,255,255,0.4);}'."\n";
return $ret;}

static function js($j,$n=1){
if(!$j)$j='spg|spitable8,call';
return 'var n='.($n?$n:1).';
addEvent(document,"DOMMouseScroll",function(){wheelcount(event,"'.$j.'")});
';}

//clr
static function clr2(){
$r=db_read('db/public/atoms','','',1);
return array_keys_r($r,4);}

static function mkclr($v){
$d=dechex(255-round($v));
return 'ff'.$d.$d;}

static function find_fam($r){
foreach($r as $k=>$v){$fam=$v[2]; $level=$v[4];
	$rb=spilib::findpos($level,$k); $kb=$rb[0].'-'.$rb[1];
	$clr=spilib::findclr($fam);
	$rt[$kb]=$clr;}
return $rt;}

#find
static function grow($n){
return $n>1?range(2,$n*4,4):[2];}

static function ranges(){$n=2;
$grow=fn($n)=>$n>1?range(2,$n*4,4):[2];
for($i=1;$i<10;$i++)$r[]=$grow($i);//push to atom 280
return $r;}

static function findrg($p){
$ra=self::grow(9); $na=0;
[$rb,$n]=self::subring(36,[],[],1,1,1); //pr($rb);
foreach($rb as $k=>[$rg,$sb]){$n=$ra[$sb-1];
	for($i=0;$i<$n;$i++){$na++;
		if($na==$p)return [$rg,$sb,$i+1,$n];}}}

static function findn($a,$b){
$ra=self::grow(9); $na=0;
[$rb,$n]=self::subring(36,[],[],1,1,1); //pr($rb);
foreach($rb as $k=>[$rg,$sb]){$n=$ra[$sb-1];
	for($i=0;$i<$n;$i++)$na++;
	if($rg==$a & $sb==$b)return $na;}}

static function tableofn(){
$ra=self::grow(9); $na=0;
[$rb,$n]=self::subring(36,[],[],1,1,1); //pr($rb);
foreach($rb as $k=>[$rg,$sb]){$n=$ra[$sb-1];
	for($i=0;$i<$n;$i++)$na++;
	$rt[$rg.'-'.$sb]=$na;}
return $rt;}

#coordinates from atomic number
static function block($k,$v,$rc,$t){
$ret=''; $u=self::$unit; $w=$u; $h=$u/2; $b=self::$base; $x=$v[0]*$u; $y=$b-$v[1]*$u+$u;
$ratio=255/42; $sz=14; //$t=join('-',$v);
$xc=spilib::center_text($t,$w,$sz);
$clr=self::mkclr($k*$ratio);
//$clr=$rc[$t]??$clr;
$clrn=clrneg($clr,1);
$ret.='[#'.$clr.',gray,,,1:attr]['.$x.','.$y.','.$w.','.$h.',,id'.$k.':rect]';
$ret.='[#'.$clrn.',:attr]['.($x+$xc).','.($y+15).','.$sz.'*'.$t.':text]';
return $ret;}

//fonction qui détermine la prochaine sous-couronne à remplir
static function nextsubrg($rb,$rg,$subrg){
if($rg==1)return [$rg+1,$subrg];
elseif($subrg==1){
	for($i=1;$i<9;$i++)if(isset($rb[$i]) && $rb[$i]<$i && $rb[$i])return [$i,$rb[$i]+1];
	return [$rg,$subrg+1];}
else return [$rg+1,$subrg-1];}

static function subring($max,$ra,$rb,$rg,$subrg,$n){//echo $rg.':'.$subrg.' ';
$ra[$n]=[$rg,$subrg]; $n++;
$rb[$rg]=$subrg;//known filled
if($n<=$max){
	[$rg,$subrg]=self::nextsubrg($rb,$rg,$subrg);
	[$ra,$n]=self::subring($max,$ra,$rb,$rg,$subrg,$n);}
return [$ra,$n];}

/*static function find_subring($n){
	$rc[1][1]=1; $rg=1; $rs=1;
	for($i=2;$i<$n;$i++){
		if(count($rc[$i-1])<=$rs){$rg=$i-1; $rs+=1; $cnd=1;}
		//if(isset($rc[$i-1]) && $i-1>$rs)[$rg,$rs]=self::find_subring($n,[$rg,$rs]);
		elseif($rs>1){$rg+=1; $rs-=1; $cnd=2;}
		else{$rg=$i; $rs+=1; $cnd=3;}
		$rc[$rg][$rs]=1; echo $i.':'.$cnd.' ';}
return $rc;}*/

static function build_layer($p,$rc){$ret='';//42
//définition des emplacements ; résultat par imbrication : sous-couronne 3 = $r[0]+$r[1]+$r[2]
//$r[0]=[1,2]; $o=2; for($i=1;$i<6;$i++)for($ia=0;$ia<4;$ia++){$o+=1; $r[$i][]=$o;} //pr($r);
//difinition des sous-couronnes possibles pour chaque couronne
//for($i=1;$i<9;$i++)for($ia=1;$ia<=$i;$ia++)$rb[$i][]=$ia; //pr($rb);
//$rc[1]=[1,1]; //$rc=self::find_subring(42,$rc); //pr($rc);
[$r,$n]=self::subring($p,[],[],1,1,1); //pr($rc);
$rt=self::tableofn();
if($r)foreach($r as $k=>$v)if(is_array($v))$ret.=self::block($k,$v,$rc,$rt[join('-',$v)]);
return $ret;}

//build
static function build($p,$o,$c,$hl){//$o=0;
$r=db_read('db/public/atomic','','',1);
$u=self::$unit;//unit
if($o=='linear'){$u=self::$unit; $w=25*$u; $h=18*$u;}
else{$o='classic'; $u=self::$unit; $w=25*$u; $h=18*$u;}
$max=self::$max;
$rc=[]; if($c==2)$rc=spilib::clr2(); elseif($c!=1)$rc=spilib::clr3($r,$c);
//spilib::attempt_subrings($r,$p);
//for($i=1;$i<=$max;$i++)$rb[]=self::atom($r,$i,$p,$o,$rc,$hl);
$rc=self::find_fam($r);
$ret=self::build_layer($p,$rc); //pr($rb);
//$ret='[white,black,1:attr][0,0,'.$w.','.$h.':rect]';
//$ret=self::subring_limits($o);
//$ret.=implode("\n",$rb);
$bt=self::nav($p,$o,$c,$hl);
//$bt.=spilib::legend($c);
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
return spilib::nav($a,$o,$c,$h,self::$nt,1);}

static function menu($p,$o,$rid){
return spilib::menu($p,$o,$rid,self::$nt);}

static function content($p){
$a=$p['lbar']??self::$max; $o=$p['mode']??'';
$rid=self::$cb; //$o=1;
$bt=self::menu($a,$o,$rid);
head::add('jscode',self::js($a,$o,self::$nt));
$ret=self::call($p);
return div($bt.div($ret,'',$rid),'board','sptcb');}

}
?>