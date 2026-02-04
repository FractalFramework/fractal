<?php

class knownstars{	
static $private=0;
static $db='knownstars';
static $cb='clcl';
static $a='knownstars';

/*static function install(){
sql::create(self::$db,['tit'=>'var','txt'=>'bvar'],0);}*/

static function admin(){
$r[]=['','j','popup|knownstars,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=knownstars_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=knownstars','code','Code'];
return $r;}

static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function titles($p){
$d=$p['_m']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#build
/*static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'ra',$id);
return $r;}*/

static function draw($f,$r,$w){
$out='disk/_/'.$f.'.png'; $h=$w; $ration=10;
$im=imagecreate($w,$h);
[$white,$black,$red,$green,$blue,$yellow,$cyan]=stargraph::clr($im,'');
ImageFilledRectangle($im,0,0,$w,$h,$white);
foreach($r as $k=>$v)imageellipse($im,$k,$v+100,4,4,$red);
imagepng($im,$out);
return img('/'.$out.'?'.randid());}

#read
static function call0($p){
$ret=''; $pi=pi(); $piq=$pi/9; $ray=6384;
for($i=0;$i<=9;$i++)$ra[$i]=round(cos(deg2rad($i*10-5))*$ray*$pi*2/36,2); pr($ra);
foreach($ra as $k=>$v)if($k){
	if($k<4)$rb[]=$v*29*2; else $rb[]=$v*29;
}
echo array_sum($rb); //pr($rb);
//$ret=self::draw('graph',$ra,800);
return $ret;}

//2 * pi * (cos(L) * R) //R=6378, L=angle
static function call($p){
	$ret=''; $pi=pi(); $ray=6378; $long=$pi*$ray*2/36;//1/36� de la circonf�rence
	//circonf�rences entre deux latitudes
	for($i=0;$i<9;$i++)$ra[$i]=round(cos(deg2rad($i*10+5))*$ray*$pi*2/36,2); pr(array_reverse($ra));
	//sym�trie h�misph�re sud
	$ra=array_merge(array_reverse($ra),$ra); //pr($ra);
	//cases remplies par cat�gorie selon la latitude (green, orange, white)
	$rs=[[6,1,29],[21.5,15.5,0],[36,0,0]];
	//�tablit les surfaces de chaque cat�gorie d'aire
	foreach($ra as $k=>$v){
		if($k<13)$a=0; elseif($k==14)$a=1; else $a=2;
		$green=round($v*$rs[$a][0]*$long);
		$orange=round($v*$rs[$a][1]*$long);
		$white=round($v*$rs[$a][2]*$long); $rd[]=round($v);
		$rb[]=[$green,$orange,$white];} //pr($rb);
		pr($rd);
	//additions
	$gsum=$osum=$wsum=0;
	foreach($rb as $k=>$v){$gsum+=$v[0]; $osum+=$v[1]; $wsum+=$v[2];}
	//proportions
	$sum=$gsum+$osum+$wsum;
	//echo 'g: '.$gsum.' o:'.$osum.' w:'.$wsum.' = '.$sum;
	$ret=div('green : '.round($gsum/$sum*100,2).' %');
	$ret.=div('orange : '.round($osum/$sum*100,2).' %');
	$ret.=div('white : '.round($wsum/$sum*100,2).' %');
	$ret.=div('total : '.round($sum,2).'');
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
//$bt=inputcall('popup|knownstars,call||inp1','inp1',$p['p1']??'',32);
$bt=bj(self::$cb.'|knownstars,call',picto('ok'),'btn');
return div($bt.div('','',self::$cb),'pane');}
}
?>