<?php

class clock{
static $private=2;
	
//js to append to the header of the parent page
static function js(){
	return '
function clock(){
	var date=new Date();
	var time=Math.round(date.getTime()/1000);
	var day=date.getDate();
	var hour=date.getHours();
	var min=date.getMinutes();
	var sec=date.getSeconds();
	document.getElementById("digit").innerHTML=hour+":"+min+":"+sec;
	needlePos(hour+(min/60)+(sec/60/60),"needleHour");
	needlePos(min+(sec/60),"needleMin");
	needlePos(sec,"needleSec");
}
function needlePos(val,needle){
	var el=document.getElementById(needle+"Frame");
	if(needle=="needleHour")var deg=val?val*30:360; 
	else var deg=val?val*6:360; 
	el.style.transform="rotate("+deg+"deg)";
}

xt=setInterval(function(){clock();},1000);';
}

private static function needle_css($id,$sz,$clr,$w,$h){return '
#'.$id.'{
	background:'.$clr.';
	width:'.$w.'px;
	height:'.$h.'px;
	margin:0px auto;
}
#'.$id.'Frame{
	width:'.$sz.'px;
	height:'.$sz.'px;
	position:absolute;
	left:calc(50% - '.($sz/2).'px);
	top:calc(50% - '.($sz/2).'px);
	transform:rotate(0deg);
	transition: all 0.2s linear;
}';}

static function headers(){
$unit=15;
$size1=$unit*10;//xee
$size2=$unit*15;//xsi
$size3=$unit*20;//uiw
$size4=$unit*18;//landmark
$hourclr='#990000';
$minclr='#000099';
$secclr='#009900';

head::add('csscode','
#clockFrame{
	background:#fff;
	width:'.($size3+40).'px;
	height:'.($size3+60).'px;
	position:relative;
}
#clock{
	background:#fff;
	border:2px solid grey;
	width:'.$size3.'px;
	height:'.$size3.'px;
	position:absolute;
	left:calc(50% - '.($size3/2).'px);
	top:calc(50% - '.($size3/2).'px);
	border-radius:'.($size3/2).'px;
	transform:rotate(0deg) translate(0px, 0px); 
}
#landmark{
	border:1px solid #555;
	background:#555;
	width:1px;
	height:10px;
	margin:0px auto;
}
#landmarkSub{
	border:1px solid #999;
	background:#999;
	width:1px;
	height:5px;
	margin:0px auto;
}
#landmarkFrame{
	width:'.$size3.'px;
	height:'.$size3.'px;
	position:absolute;
	left:calc(50% - '.($size3/2).'px);
	top:calc(50% - '.($size3/2).'px);
	transform:rotate(0deg); 
}
#landmarkText{
	text-align:center;
	margin:0px auto;
}
#landmarkTextFrame{
	width:'.$size4.'px;
	height:'.$size4.'px;
	position:absolute;
	left:calc(50% - '.($size4/2).'px);
	top:calc(50% - '.($size4/2).'px);
	transform:rotate(0deg); 
}
#clockCenter{
	border:2px solid grey;
	background:white;
	width:16px;
	height:16px;
	position:absolute;
	left:calc(50% - 9px);
	top:calc(50% - 9px);
	border-radius:8px;
}
'.self::needle_css('needleHour',$size1,$hourclr,8,$size1/2).'
'.self::needle_css('needleMin',$size2,$minclr,6,$size2/2).'
'.self::needle_css('needleSec',$size3,$secclr,4,$size3/2).'
');

head::add('jscode',self::js());
}

#content
static function content($prm){
	$landmark=''; $ret='';
	$ret=tag('div',['id'=>'clock'],'');
	//landmarkSub
	for($i=0;$i<60;$i++){
		$landmarkSub=tag('div',['id'=>'landmarkSub'],'');
		$ret.=tag('div',['id'=>'landmarkFrame','style'=>'transform: rotate('.($i*6).'deg);'],$landmarkSub);}
	//landmark
	for($i=0;$i<12;$i++){
		$landmark=tag('div',['id'=>'landmark'],'');
		$ret.=tag('div',['id'=>'landmarkFrame','style'=>'transform: rotate('.($i*30).'deg);'],$landmark);
		$hour=tag('div',['id'=>'landmarkText','style'=>'transform: rotate(-'.($i*30).'deg);'],($i?$i:12));
		$ret.=tag('div',['id'=>'landmarkTextFrame','style'=>'transform: rotate('.($i*30).'deg) translate(0px, 0px);'],$hour);}
	$needleMin=tag('div',['id'=>'needleHour'],' ');
	$ret.=tag('div',['id'=>'needleHourFrame'],$needleMin);
	$needleMin=tag('div',['id'=>'needleMin'],' ');
	$ret.=tag('div',['id'=>'needleMinFrame'],$needleMin);
	$needleSec=tag('div',['id'=>'needleSec'],' ');
	$ret.=tag('div',['id'=>'needleSecFrame'],$needleSec);
	$ret.=tag('div',['id'=>'clockCenter'],'');
	$ret.=tag('div',['id'=>'digit'],'');
	$ret.=head::csscode('clock();');
	$ret=tag('div',['id'=>'clockFrame'],$ret);
	return $ret;}
}

?>