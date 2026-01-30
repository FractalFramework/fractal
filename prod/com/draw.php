<?php

//application not based on appx
class draw{	
static $private=2;
static $a=__CLASS__;
static $db='draw';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='mdb';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return '
$(document).ready(function(){
	var color="#000"; 
	var painting=false; 
	var started=false; 
	var width_brush=5; 
	var canvas=$("#canvas"); 
	var cursorX, cursorY; 
	var restoreCanvasArray=[]; 
	var restoreCanvasIndex=0; 
	var context=canvas[0].getContext("2d");
	context.lineJoin="round"; context.lineCap="round";
	canvas.mousedown(function(e){painting=true;
		cursorX=(e.pageX-this.offsetLeft);
		cursorY=(e.pageY-this.offsetTop);});
	$(this).mouseup(function(){painting=false; started=false;});
	canvas.mousemove(function(e){
		if (painting){
			cursorX=(e.pageX-this.offsetLeft); 
			cursorY=(e.pageY-this.offsetTop);
			drawLine();}});
	function drawLine(){
		if (!started){
			context.beginPath();
			context.moveTo(cursorX, cursorY);
			started=true;} 
		else{
			context.lineTo(cursorX, cursorY);
			context.strokeStyle=color;
			context.lineWidth=width_brush;
			context.stroke();}}
	function clear_canvas(){
		context.clearRect(0,0, canvas.width(), canvas.height());}
	$("#couleurs a").each(function(){
		$(this).css("background", $(this).attr("data-couleur"));
		$(this).click(function(){
			color=$(this).attr("data-couleur");
			$("#couleurs a").removeAttr("class","");
			$(this).attr("class", "actif");
			return false;});});
	$("#largeurs_pinceau input").change(function(){
		if (!isNaN($(this).val())){
			width_brush=$(this).val();
			$("#output").html($(this).val()+" pixels");}});
	$("#reset").click(function(){
		clear_canvas();
		$("#largeur_pinceau").attr("value",5);
		width_brush=5;
		$("#output").html("5 pixels");});
	$("#save").click(function(){
		var canvas_tmp=document.getElementById("canvas");
		window.location=canvas_tmp.toDataURL("image/png");
		//insert("["+canvas_tmp.toDataURL()+":img]","",this); Close("popup");
		//SaveJ("popup_plup___draw_draw*save_"+canvas_tmp.toDataURL());
		//alert(canvas_tmp.toDataURL());
		});});
';}
static function css(){
return '#canvas{border:1px solid #999; margin:0; display:block; background:#fff; cursor:crosshair;}
#couleurs{list-style:none; margin:0; padding:0;}
#couleurs li{display:inline-block; border-radius:50%;}
#couleurs a{display:inline-block; width:10px; height:10px; margin-right:10px; text-indent:-4000px; overflow:hidden;}
#couleurs a.actif{width:20px; height:20px;}';}

static function headers(){
head::add('csscode',self::css());
head::add('jscode',self::js());}


static function save($d){$f='img/draw_temp.png'; //$d.='=';
//echo textarea('',$d,20,10);//(substr($d,22));
write_file($f,base64_decode(substr($d,22)));
return img($f);}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p,$r){$ret=''; //pr($r);
if($r)foreach($r as $k=>$v){$rb=[];
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
return $ret;}

#call
static function call($p){
$w=640; $h=440; $cl='';
head::add('jslink','/js/jquery.js');
head::add('jscode',self::js());
head::add('csscode',self::css());
$ret=tag('canvas',['id'=>'canvas','width'=>$w.'px','height'=>$h.'px'],'');
$r=['black','white','blue','green','yellow','orange','brown','red','indigo','violet','pink','cyan']; $n=count($r);
for($i=0;$i<$n;$i++){
	$pr=['style'=>'background: none repeat scroll 0% 0% '.$r[$i].';','data-couleur'=>$r[$i]];
	$cl.=tag('li',$pr,lk($r[$i]));}
$ret.=tag('ul',['id'=>'couleurs'],$cl);
$inp=label('largeur_pinceau','width');
$inp.=bar('range','largeur_pinceau',atz(1).atb('min',2).atb('max',20));
//$inp.=tag('output','" id="output','pixels');
$inp.=tag('reset','reset','reset');
$inp.=btn('','save');
$ret.='<form id="largeurs_pinceau">'.$inp.'</form>';
return $ret;}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||inp2';
$bt=bj($j,langp('ok'),'btn');
//$ret=$bt.textarea('inp2','',60,4);
$ret=inputcall($j,'inp2',$p['p1']??'',32).$bt;
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['param']??$p['p1']??'';
$bt='';//self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>