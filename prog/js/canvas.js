/*canvas*/

function cntx(id,w,h){
	var ob=document.getElementById(id);
	var ctx=ob.getContext("2d");
	//ob.width=w+"px"; ob.height=h+"px";
	ctx.width=w+"px"; ctx.height=h+"px";
	ctx.clearRect(0,0,w,h);
	return ctx;}

function props(ctx,clr,bdr,sz,o){
	if(clr)ctx.fillStyle=clr;
	if(bdr)ctx.strokeStyle=bdr;
	if(sz)ctx.lineWidth=sz;
	if(o)ctx.lineCap="round";}

function strpad(n){return n<10?"0"+n:n;}//n.padStart(2,"0");

function circle(ctx,w,a,b){
	ctx.beginPath();
	ctx.arc(0,0,w,0,Math.PI*2,true);
	if(a)ctx.stroke();
	if(b)ctx.fill();}

function square(ctx,x,y,w,h,o){
	if(o==1)ctx.strokeRect(x,y,w,h);
	else if(o==2)ctx.clearRect(x,y,w,h);
	else ctx.fillRect(x,y,w,h);}

function line(ctx,xa,ya,xb,yb){
	ctx.beginPath();
	ctx.moveTo(xa,ya);
	ctx.lineTo(xb,yb);
	ctx.stroke();}

function poly(ctx,r,o,b){
	ctx.beginPath();
	for(i=0;i<r.length;i++)if(i==0)ctx.moveTo(r[i][0],r[i][1]); else ctx.lineTo(r[i][0],r[i][1]);
	if(o)ctx.closePath();
	ctx.stroke();
	if(b)ctx.fill();}

function txt(ctx,txt,fnt,clr,x,y){
	ctx.save();
	ctx.translate(x,y); ctx.font=fnt; var tz=ctx.measureText(txt); if(!clr)clr="black";
	var tx=0-tz.width/2; var ty=0; var tw=tz.width; var th=40;
	ctx.strokeStyle=clr; ctx.fillStyle="rgba(200,200,200,0.8)";
	ctx.fillRect(tx-10,ty,tw+20,th); ctx.strokeRect(tx-10,ty,tw+20,th); //ctx.clearRect(tx,ty,tw,th);
	ctx.fillStyle=clr;
	ctx.fillText(txt,tx,30);
	ctx.restore();
	return ctx;}

function txt2(ctx,txt,clr){
	ctx.font="36px sans"; var tz=ctx.measureText(txt);
	var tx=0-tz.width/2; var ty=0; var tw=tz.width; var th=40;
	ctx.strokeStyle=clr; ctx.fillStyle="rgba(200,200,200,0.8)";
	ctx.fillRect(tx-10,ty,tw+20,th); ctx.strokeRect(tx-10,ty,tw+20,th);
	ctx.fillStyle="black"; ctx.fillText(txt,tx,30);
	return ctx;}

function animate(t1,t2,st,nd){var res;
var dt=t2-t1; var df=nd-st; var dd=df/dt;
for($i=0;i<dt;i++)res=t1+(dd*i);//...
}

function date(){
	var now=new Date();
	var ts=now.getTime();
	var hr=now.getHours();//hr=hr>=12?hr-12:hr;
	var mn=now.getMinutes();
	var sc=now.getSeconds();
	var ms=now.getMilliseconds();
	var hr0=(Math.PI/6)*hr+(Math.PI/360)*mn+(Math.PI/21600)*sc;
	var mn0=(Math.PI/30)*mn+(Math.PI/1800)*sc
	var sc0=(Math.PI/30)*(sc+ms/1000);
	var ms0=(Math.PI/30)*ms/3.6;
	return {"ts":ts,"hr":hr,"mn":mn,"sc":sc,"ms":Math.floor(ms/10),"hr0":hr0,"mn0":mn0,"sc0":sc0,"ms0":ms0,"mx":[12,60,60]};}

/*clock2*/
function udate(){
	//2003-07-26 from calculation
	//2003-07-09 from nr18
	var aeon4=1057716000;
	var date=new Date();
	var ts=date.getTime();
	var ms=date.getMilliseconds();
	var time=Math.floor(ts/1000);
	//oomo timestamp
	var oomoTimestamp=time-aeon4; //389140068 sec
	var nbMin=oomoTimestamp/60+ms/60000;
	//var nbDays=oomoTimestamp/60/60/24; //4503.9556018518515 days
	//var nbSecondsByXsi=111317770.6542;
	var nbMinutesByXee=111317.7706542;
	//var nbSecondsByXee=6679066239.252;
	var nbUiwByXsi=600.0117;
	//nb of xee
	var nbXee=nbMin/nbMinutesByXee;
	var xee=Math.floor(nbXee);
	var xeeLeft=nbXee-xee;
	//nb of xsi
	var nbXsi=xeeLeft*60;
	var xsi=Math.floor(nbXsi);
	var xsiLeft=nbXsi-xsi;
	//nb of uiw
	var nbUiw=xsiLeft*nbUiwByXsi;
	var uiw=Math.floor(nbUiw);
	var uiwLeft=nbUiw-uiw;
	//nb of uiwSec
	var nbUiwSec=uiwLeft*100;
	var uiwSec=Math.floor(nbUiwSec);
	var UiwMs=(xsiLeft*nbUiwByXsi)*100;
	//angles
	var hr0=(Math.PI/300)*xee+(Math.PI/3000)*xsi;//+(Math.PI/300000)*uiw;
	var mn0=(Math.PI/30)*xsi+(Math.PI/18000)*uiw
	var sc0=(Math.PI/300)*uiw;
	var ms0=(Math.PI/30)*UiwMs*0.6;
	return {"ts":time,"xee":xee,"hr":xee,"mn":xsi,"sc":uiw,"ms":uiwSec,"hr0":hr0,"mn0":mn0,"sc0":sc0,"ms0":ms0,"mx":[600,60,600]};}
	
/*brain*/
function needle(ctx,rot,x,w){
	ctx.rotate(rot); ctx.beginPath(); ctx.moveTo(x,0); ctx.lineTo(w,0); ctx.stroke();}

function mark(ctx,n,x,w){
	for(var i=0;i<n;i++){ctx.beginPath(); ctx.rotate(Math.PI/(n/2)); ctx.moveTo(x,0); ctx.lineTo(w,0); ctx.stroke();}}

function gauge(ctx,h,p1){
	//frame
	ctx.save(); ctx.lineWidth=4; ctx.beginPath(); ctx.arc(0,0,h,0,Math.PI,true); //ctx.closePath();
	ctx.stroke(); ctx.fill(); line(ctx,-h,0,h,0); ctx.restore();
	//marks
	ctx.save(); ctx.lineWidth=4; //ctx.rotate(Math.PI/2);
	for(var i=1;i<6;i++){ctx.beginPath(); ctx.rotate(-Math.PI/6); ctx.moveTo(h-10,0); ctx.lineTo(h,0); ctx.stroke();}
	ctx.restore();
	//needle
	ctx.save();
	var rot=p1*(Math.PI/12); ctx.lineWidth=10; ctx.rotate(Math.PI); needle(ctx,rot,0,h);
	ctx.restore();
	//center
	ctx.save(); ctx.lineWidth=8; ctx.fillStyle="white"; circle(ctx,10,1,1); ctx.restore();}

/*brain-dev*/
function bollards(d,mn,mx){if(d>mx)d=mx; if(d<mn)d=mn; return d;}
function limits(ob,mn,mx){var d=ob.value; var d2=bollards(d,mn,mx); if(d!=d2){ob.value=d2; reached=1;} return d2;}
function tyclr(ty){
	if(ty==0)return "#BC2356";
	if(ty==1)return "#3366CC";
	if(ty==2)return "#369C10";
	if(ty==3)return "#9323BC";}

function indicator(ctx,ob,x,y,h,ty){
	//var p1=ob.value; if(p1>12){p1=12; ob.value=p1;} if(p1<0){p1=0; ob.value=p1;}
	var p1=limits(ob,0,12); var clr=tyclr(ty);
	if(ty==0)var ti="goods";
	if(ty==1)var ti="work";
	if(ty==2)var ti="resouces";
	//
	ctx.save(); 
	ctx.strokeStyle=clr; ctx.fillStyle="rgba(255,255,255,0.6)"; ctx.translate(x,y); gauge(ctx,h,p1);
	ctx.restore();
	//ends
	if(p1<=0 || p1>=12){
		ctx.save();
		ctx.translate(x,y);
		if(p1<=0)var txt=ti+" depletion"; else var txt="no need more "+ti;
		ctx=txt2(ctx,txt,clr);
		ctx.restore();}}

function channels(ctx,x1,x2,x3,h,p1,p2,p3){
	//line1
	ctx.save();
	ctx.strokeStyle=tyclr(2); ctx.lineWidth=4; ctx.translate(x1,h*2);
	var r=[[0,0],[0,125],[x2-x1-50,125]];
	poly(ctx,r,0,0);
	ctx.restore();
	//line2
	ctx.save();
	ctx.strokeStyle=tyclr(1); ctx.lineWidth=4; ctx.translate(x2,h*2);
	var r=[[0,0],[0,100]];
	poly(ctx,r,0,0);
	ctx.restore();
	//line3
	ctx.save();
	ctx.strokeStyle=tyclr(0); ctx.lineWidth=4; ctx.translate(x3,h*2);
	var r=[[x2-x3+50,125],[0,125],[0,0]];
	poly(ctx,r,0,0);
	ctx.restore();
	//bub1
	ctx.save(); ctx.fillStyle=tyclr(2); ctx.translate(x1,h*2); var w1=125; var w2=x2-x1;
	p1=12-p1; var d1=Math.floor(p1); var t1a=(p1-d1)*(w1/3); var t1b=(p1-d1)*(w2/5); //distance
	for(i=0;i<3;i++){var a=(w1/3)*i; ctx.save(); ctx.translate(0,a+t1a); circle(ctx,10,0,1); ctx.restore();}
	for(i=0;i<5;i++){var a=(w2/5)*i; ctx.save(); ctx.translate(a+t1b,125); circle(ctx,10,0,1); ctx.restore();}
	ctx.restore();
	//bub2
	ctx.save(); ctx.fillStyle=tyclr(1); ctx.translate(x2,h*2); var w1=125;
	p2=12-p2; var d2=Math.floor(p2); var t2a=(p2-d2)*(w1/3);
	for(i=0;i<3;i++){var a=(w1/3)*i; ctx.save(); ctx.translate(0,a+t2a); circle(ctx,10,0,1); ctx.restore();}
	ctx.restore();
	//bub3
	ctx.save(); ctx.fillStyle=tyclr(0); ctx.translate(x3,h*2); var w1=125; var w2=x2-x3;
	p3=12-p3; var d3=Math.floor(p3); var t3a=(p3-d3)*(w1/3); var t3b=(p3-d3)*(w2/5);
	for(i=0;i<3;i++){var a=(w1/3)*i; ctx.save(); ctx.translate(0,a+t3a); circle(ctx,10,0,1); ctx.restore();}
	for(i=0;i<5;i++){var a=(w2/5)*i; ctx.save(); ctx.translate(a+t3b,125); circle(ctx,10,0,1); ctx.restore();}
	ctx.restore();
	//factory
	ctx.save(); var d1=30; var d2=d1*2; var d3=d1*3; 
	ctx.strokeStyle="black"; ctx.lineWidth=4; ctx.fillStyle="rgba(255,255,255,0.8)"; ctx.translate(x2,h+270);
	var r=[[0,0],[d2,0],[d2,-d2],[d1,-d2],[0,-d3],[0,-d2],[-d1,-d2],[-d1,-d3],[-d2,-d3],[-d2,0]];
	poly(ctx,r,1,1);
	ctx.restore();}

var tube={p1:12,x:200,y:0,w:125,h:200,n:5,clr:tyclr(0),
	draw:function(){
	ctx.save(); ctx.fillStyle=this.clr; ctx.translate(this.x,this.h*2); var w1=this.w;
	var d1=Math.floor(this.p1); var t1=(this.p1-d1)*(this.w/this.n);
	for(i=0;i<this.n;i++){var a=(w1/5)*i; ctx.save(); ctx.translate(a+t1,this.y); circle(ctx,10,0,1); ctx.restore();}
	ctx.restore();}};

/*function draw(){
	ctx.clearRect(0,0,canvas.width,canvas.height);
	ball.draw();
	ball.x += ball.vx;
	ball.y += ball.vy;
	raf = window.requestAnimationFrame(draw);}
canvas.addEventListener('mouseover', function(e){raf = window.requestAnimationFrame(draw);});
canvas.addEventListener("mouseout",function(e){window.cancelAnimationFrame(raf);});
tube.draw();*/

/**/
function brain_algo(p1,p2,p2){
	//var now=new Date(); var ts=now.getTime(); var sc=now.getSeconds();
	//var te=(ts-ts0)/100; var un=Math.floor(te); //pr(te+"-"+un+"-"+un0);
	//if(un>un0){ajx("p1;p2;p3|brain,datas|te="+te+",p1="+p1+",p2="+p2+",p3="+p3); un0=un;}
	/**/var t1=1; var t2=1; var t3=1; var tt=0.02;
	var d1=tt/2; var d2=tt/2; var d3=tt;
	//d1-=d3/2; d2-=d3/2; d3-=d1+d2;
	//d1+=1/sc; d2-=1/sc;
	return {"d1":d1,"d2":d2,"d3":d3};}

/*funky*/
//var rn=[1,4,9]; rn.map(function(n){return n*2;});