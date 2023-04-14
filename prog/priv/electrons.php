<?php

class electrons{
static $a='electrons';
static $private=1;
static $sz=300;
static $cb='clk2';

static function admin(){return admin::app(['a'=>self::$a]);}

static function js(){
$cb=self::$cb; $sz=self::$sz; $m=$sz/2; $t=$sz/3; $t2=$t*2; $s=0.5;
return '
var cb="'.$cb.'"; var m='.$m.'; var sz='.$sz.'; var t3='.$t.'; var t2='.$t2.'; var s='.$s.';
//r={cb:cb,sz:sz,sc:s};
function clock(){
	var ctx=cntx(cb,sz,sz);
	ctx.save();
	ctx.translate(m,m);
	ctx.scale(s,s);
	ctx.rotate(-Math.PI/2);
	
	if(world)var dt=udate();
	else var dt=date();
	
	//marks hours
	ctx.save();
	mark(ctx,12,t3-20,t3);
	ctx.restore();
	
	//marks min
	ctx.save();
	ctx.lineWidth=2;
	mark(ctx,24,t2-20,t2);
	ctx.restore();
	
	//marks sec
	ctx.save();
	ctx.lineWidth=2;
	mark(ctx,60,sz-20,sz-2);
	ctx.restore();
	
	//hours
	ctx.save();
	var rot=dt.hr0;
	ctx.lineWidth=14;
	ctx.strokeStyle="rgba(200,0,0,0.8)";
	needle(ctx,rot,0,t3);
	ctx.restore();
	
	//minutes
	ctx.save();
	var rot=dt.mn0;
	ctx.lineWidth=10;
	ctx.strokeStyle="rgba(0,0,200,0.8)";
	needle(ctx,rot,t3,t2);
	ctx.restore();
	
	ctx.save();
	//ctx.rotate(Math.PI/2); ctx.font="36px sans"; ctx.fillText(rot+"-"+(Math.PI/30)*dt.mn,0-80,'.($m+40).');
	ctx.restore();
	
	//secondes
	ctx.save();
	var rot=dt.sc0;
		//var ob=document.getElementById("p1"); var rot=ob.value*(Math.PI/300);
	ctx.lineWidth=6;
	ctx.strokeStyle="rgba(0,200,0,0.8)";
	needle(ctx,rot,t2,sz-2);
	ctx.restore();
	
	//ms
	ctx.save();
	var ms=dt.ms;
	ctx.lineWidth=4;
	ctx.strokeStyle="rgba(200,0,200,0.8)";
	var rot=dt.ms0;
	if(world==1)needle(ctx,rot,t2,sz-2);
	ctx.restore();
	
	//rond
	ctx.save();
	ctx.strokeStyle="rgba(200,0,0,0.8)";
	ctx.fillStyle="white";
	circle(ctx,10,1,1);
	ctx.restore();
	
	ctx.save();
	ctx.lineWidth=2;
	ctx.strokeStyle="#325FA2";
	ctx.fillStyle="rgba(200,200,200,0.2)";
	circle(ctx,sz-2,1,1);
	circle(ctx,t2,1,1);
	circle(ctx,t3,1,1);
	ctx.restore();
	
	//txt
	ctx.save();
	ctx.rotate(Math.PI/2);
	var hr=strpad(dt.hr);
	var mn=strpad(dt.mn);
	var sc=strpad(dt.sc);
	var ms=strpad(ms);
	ctx.font="36px sans";
	if(world)var hour="XEE:"+(hr)+" XSI:"+(mn)+" UIW:"+(sc)+"."+ms;
	else var hour=hr+":"+mn+":"+sc+"."+ms;
	var tz=ctx.measureText(hour);
	var tx=0-tz.width/2-10; var ty=m-32; var tw=tz.width+20; var th=40;
	ctx.strokeStyle="#325FA2";
	ctx.fillStyle="rgba(200,200,200,0.8)";
	ctx.fillRect(tx,ty,tw,th);
	ctx.strokeRect(tx,ty,tw,th);
	//ctx.clearRect(tx,ty,tw,th);
	
	ctx.fillStyle="black";
	ctx.fillText(hour,0-tz.width/2,m);
	ctx.restore();
	
	ctx.restore();
	//document.getElementById("p1").value=dt.ms0;
	window.requestAnimationFrame(clock);
	//setTimeout("clock()",100);
}
world=0;
window.requestAnimationFrame(clock);';}

static function headers(){
head::add('csscode','.hrclr{color:#990000;} .mnclr{color:#000099;} .scclr{color:#009900;} .msclr{color:#990099;}');
head::add('jslink','/js/canvas.js');
//head::add('jslink','/js/clock.js');
head::add('jscode',self::js());}

#content
static function content($p){
$bt=btj('o-clock','world=0','btn').btj('u-clock','world=1','btn');
//$bt.=input('p1','1','','',1);
$ret=tag('canvas',['id'=>self::$cb,'width'=>self::$sz.'px','height'=>self::$sz.'px'],'');
return $bt.div($ret,'board');}
}

?>