<?php
class canvas{
static $private=0;
private static $r=[];
private static $w=300;
private static $h=200;
private static $js='';
private static $cb='cnv';
private static $rid='';
private static $anim='';
static function js(){return '';}

#wsg
static function connbt($id,$o=''){$ns='';//ns();
$ret=btj('[]',atj('embed_slct',['[',']',$id]),'btn').$ns;
$ret=div(menu::call(['app'=>'bitmap','mth'=>'menu','bid'=>'code','drop'=>0]));
//$ret.=select('slct'.$id,$r,'',1,'','',$js);
//$ret.=bj($id.'|core,clean_mail|x='.$id.'|'.$id,pic('clean'),'btn',['title'=>helpx('eraser')]);
return div($ret);}

static function ex(){
return ['simple'=>'[:beginPath][25,25:moveTo][105,25:lineTo][25,105:lineTo][:fill]',
'anim'=>'[300:w][300:h][:date][:anim][0,0,150,150:clearRect][75,75:translate][0.5,0.5:scale][-Math.PI/2:rotate]
["black":strokeStyle]["white":fillStyle][2:lineWidth]["round":lineCap][:save]
[sec * Math.PI/30:rotate]["#D40000":strokeStyle]["#D40000":fillStyle]
[:beginPath][-30,0:moveTo][120,0:lineTo][:stroke][:restore]'];}

#read
static function reader($d,$b=''){//[p*o:c]
[$p,$o,$c,$da]=readgen($d); $ret='';
switch($c){
	case('w'):self::$w=$p; return; break;
	case('h'):self::$h=$p; return; break;
	case('dim'):[$w,$h]=expl('/',$p); self::$w=$w; self::$h=$h; return; break;
	//case('arc'):$ret=$c.'('.$p.','.($o?'false':'true').')'; break;//
	case('pi'):return 'Math.PI'; break;
	case('tau'):return 'Math.PI*2'; break;
	case('anim'):self::$anim='window.requestAnimationFrame(\''.self::$rid.'\');'.n(); return; break;
	case('date'):return 'var now=new Date(); var sec=now.getSeconds(); var min=now.getMinutes(); var hour=now.getHours();'.n(); break;
	case('var'):return self::$r[$p]??'['.$p.':var]'; break;
	case('setvar'):self::$r[$o]=$p; return; break;
	case('js'):return $p; break;
	case('x'):return ''; break;
	default:$ret=$c.'('.$p.')'; break;}
return 'ctx.'.$ret.';'.n();}

static function read($d,$p=''){
$st='['; $nd=']'; $deb=''; $mid=''; $end='';
$in=strpos($d,$st);
if($in!==false){
	$deb=substr($d,0,$in);
	$out=strpos(substr($d,$in+1),$nd);
	if($out!==false){
		$nb=substr_count(substr($d,$in+1,$out),$st);
		if($nb>=1){
			for($i=1;$i<=$nb;$i++){$out_tmp=$in+1+$out+1;
				$out+=strpos(substr($d,$out_tmp),$nd)+1;
				$nb=substr_count(substr($d,$in+1,$out),$st);}
			$mid=substr($d,$in+1,$out);
			$mid=self::read($mid,$p);}
		else $mid=substr($d,$in+1,$out);
		$mid=self::reader($mid,$p);
		$end=substr($d,$in+1+$out+1);
		$end=self::read($end,$p);}
	else $end=substr($d,$in+1);}
else $end=$d;
return $deb.$mid.$end;}

static function call($p){
$d=val($p,'code',val($p,'params')); $o=$p['opt']??''; $t=$p['t']??'current';
//$f='img/canvas/current.html'; mkdir_r($f); write_file($f,$ret); return iframe($f);
return self::com($d,$o,$t);}

static function com($d,$o='',$t=''){$rid=randid('cnv'); self::$rid=$rid;
[$w,$h,$t2]=expl(',',$o,3); if($w)self::$w=$w; if($h)self::$h=$h; if($t2)$t=$t2;
$js=self::read($d,$o);
$js='var ob=document.getElementById("'.$rid.'");
if(ob.getContext){} var ctx=ob.getContext("2d");
ctx.width="'.self::$w.'px"; ctx.height="'.self::$h.'px";
'.$js.'';
if(self::$anim)$js='function '.$rid.'(){'.$js.' '.self::$anim.'}'.n().self::$anim.n();
$ret=tag('canvas',['id'=>self::$rid,'width'=>self::$w.'px','height'=>self::$w.'px'],'');
$ret.=head::jscode($js,'jsc'); //$ret.=hidden('jsc',addslashes($js));
$f='img/canvas/'.$t.'.html'; mkdir_r($f); write_file($f,$ret); return iframe('/'.$f);
return $ret;}

static function content($p){
$j='cnn,,1|canvas,call|opt=0|code';//,,js,,jsc
$r=['id'=>'code','rows'=>16,'cols'=>80,'class'=>'console','onkeyup'=>ajx($j),'onclick'=>ajx($j)];
$rx=self::ex(); $d=$rx['anim'];
$ret=build::sample(['a'=>'canvas','b'=>'code']);
$ret.=self::connbt('code',1).tag('textarea',$r,$d);
$ret.=bj($j,langp('ok'),'btsav');
$ret.=div('','board','cnn');
return $ret;}

static function api($p){return self::call($p);}

}
?>