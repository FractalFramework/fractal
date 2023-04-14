<?php

class spiatom{
static $private=2;
static $a=__CLASS__;
static $db='spi';
static $cols=['tit','txt'];
static $typs=['var','text'];
static $cb='spi';

static function install(){
sql::create(self::$db,[array_combine(self::$cols,self::$typs)],0);}

static function admin(){return admin::app(['a'=>self::$a]);}
static function js(){return;}
static function headers(){head::add('jscode',self::js());}

#build
static function make($r,$type,$clr,$bdr){$rb=[];
foreach($r as $k=>$v)$rb[]=implode('/',$v);
return '['.$clr.','.$bdr.':attr]['.implode('-',$rb).':'.$type.']';}

static function repos($r,$x,$y,$sz){$rb=[];
foreach($r as $k=>$v)$rb[]=[$v[0]*$sz+$x,$v[1]*$sz+$y];
return $rb;}

static function hexa($n){$rb=[]; $angle=2*pi()/$n; //$p[0]=[1,1]; 
for($i=0;$i<=$n;$i++){
	$px=round(sin($angle*$i),2);
	$py=round(cos($angle*$i),2);
	$rb[$i]=[$px,$py];}
return $rb;}

static function label(){
}

static function poly(){
}

static function sections($r,$n,$w,$clr,$bdr,$sz){$ret='';
$origin=[$w,$w]; $o=implode('/',$origin); static $nb; $nb++; $seg=1/($n-1);
$ret='[*[grad'.$n.',0%,0%,0%,100%*[0%,'.$clr.':stop][100%,rand:stop]:linearGradient]:defs]';
for($i=0;$i<=$n;$i++){$id='poly'.$n.'-'.$i; $nb++;
	$r1=$r[$i]; $r2=isset($r[$i+1])?$r[$i+1]:$r[0];
	$a=implode('/',$r1); $b=implode('/',$r2); $rb=[$o,$a,$b,$o]; //p($rb);
	$ret.='['.$clr.','.$bdr.',,,,,,grad'.$n.':attr]'.'['.implode('-',$rb).','.$id.':polygon]';
	//
	$x0=$origin[0]; $y0=$origin[1]; $x1=$r1[0]; $y1=$r1[1]; $x2=$r2[0]; $y2=$r2[1];
	$xa=($x1+$x2)/2; $ya=($y1+$y2)/2;
	$xb=($x0+($xa*$n))/($n+1); $yb=($y0+($ya*$n))/($n+2);
	$a=$seg*$i*6; //$sz-=14;
	$x=$x0+sin($a)*$sz; $y=$y0+cos($a)*$sz;
	$ret.='[red:attr]['.$x.','.$y.'*'.$nb.':text]';}
return $ret;}

static function section1($r,$n,$w,$clr,$bdr,$sz){$ret='';
$origin=[$w,$w]; $o=implode('/',$origin);
$ret='[*[grad1,0%,0%,0%,100%*[0%,'.$clr.':stop][100%,rand:stop]:linearGradient]:defs]';//[,,,,,,,grad1:attr]
$a=implode('/',$r[0]); $b=implode('/',$r[1]); $c=implode('/',$r[2]); $rb=[$o,$a,$b,$c,$o];
$ret.='['.$clr.','.$bdr.',,,,,,grad1:attr]'.'['.implode('-',$rb).',poly1-1:polygon]';
$a=implode('/',$r[2]); $b=implode('/',$r[3]); $c=implode('/',$r[0]); $rb=[$o,$a,$b,$c,$o];
$ret.='['.$clr.','.$bdr.',,,,,,grad1:attr]'.'['.implode('-',$rb).',poly1-2:polygon]';
return $ret;}

static function ring($n,$sz,$w,$h,$clr,$bdr){$rb=[];
$r=self::hexa($n,$w,$h,$sz);
$r=self::repos($r,$w,$h,$sz); //pr($r);
if($n==4)$d=self::section1($r,$n,$w,$clr,$bdr,$sz);
else $d=self::sections($r,$n,$w,$clr,$bdr,$sz);
return $d;}

static function build($p){$o=val($p,'o');
//$r=msql_read_b('',nod('hexagon_1'));//p($r);
if(strpos($o,';'))[$n,$w,$h,$sz]=expl(';',$o,2);
$w=640; $h=$w; $n=10; $sz=300;
//$r=self::hexa($n,$w/2,$h/2,sz);
//$r=self::repos($r,$w/2,$h/2,$sz); //pr($r);
//$d=self::make($r,'polygon','white','red');
$d=self::ring(18,300,$w/2,$h/2,'#666698','red');
$d.=self::ring(14,240,$w/2,$h/2,'#9D6568','red');
$d.=self::ring(10,180,$w/2,$h/2,'#FF9900','red');
$d.=self::ring(6,120,$w/2,$h/2,'#F6E617','red');
$d.=self::ring(4,60,$w/2,$h/2,'#FF0008','red');
$ret=svg::call(['code'=>$d,'w'=>$w,'h'=>$h]);
return $ret;}

#call
static function call($p){
return self::build($p);}

static function com($p){
$j=self::$cb.'|spi,play|v1=hello|inp1';
$bt=bj($j,langp('send'),'btn');
return inputcall($j,'inp1',$p['p1']??'',32).$bt;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=self::com($p);
$ret=self::call($p);
return $bt.div($ret,'paneb',self::$cb);}
}
?>