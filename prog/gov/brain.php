<?php

class brain{
static $private=0;
static $a=__CLASS__;
static $db='brain';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='brn';
static $w=600;
static $h=200;

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){
$cb=self::$cb; $w=self::$w; $h=self::$h; $s=0.5;
$wb=$w*2; $hb=$h*2; $mw=$wb/2; $mh=$hb/2;
$g=$wb/8; $g1=$g*2; $gm=$mw; $g2=$wb-$g1; $gh=$h/2;
return '
function play(){stop(); reached=0; raf=window.requestAnimationFrame(run);}
function stop(){window.cancelAnimationFrame(raf);}
function playstop(){pp=pp==1?0:1; if(pause==1 && !pp){pause=0; pp=1;} if(pp)pause=0; if(pp)play(); else stop();}
function wait(){pause=pause==1?0:1;}

function resetp(p1,p2,p3){
	var ob1=getbyid("p1"); ob1.value=p1;
	var ob2=getbyid("p2"); ob2.value=p2;
	var ob3=getbyid("p3"); ob3.value=p3;
	pp=1; play();}

function set(ob){var p1=ob.value;
	if(p1>12){p1=12; ob.value=p1;} if(p1<0){p1=0; ob.value=p1;}}

function run(){ii++;
	var ctx=cntx("cnv",'.$w.','.$h.');
	ctx.save();//1
	//ctx.clearRect(0,0,'.$w.','.$h.');
	ctx.scale('.$s.','.$s.');
	//props(ctx,"white","black",2,1);
	
	var ob1=getbyid("p1"); var ob2=getbyid("p2"); var ob3=getbyid("p3");
	var p1=limits(ob1,0,12); var p2=limits(ob2,0,12); var p3=limits(ob3,0,12);
	
	channels(ctx,'.$g1.','.$gm.','.$g2.','.$gh.',p1,p2,p3,ii);
	indicator(ctx,ob1,'.$g1.','.$h.','.$gh.',2);
	indicator(ctx,ob2,'.$gm.','.$h.','.$gh.',1);
	indicator(ctx,ob3,'.$g2.','.$h.','.$gh.',0);

	//ctrl
	var res=brain_algo(p1,p2,p2);
	if(!pause){ob1.value=p1-res.d1; ob2.value=p2-res.d2; ob3.value=parseFloat(p3)+res.d3;}
	
	ctx.restore();
	if(reached)stop(); else play();}

var raf; var ta=0; var un0=0; var pause=0; var pp=1; var reached=0; var ii=0;
//var now=new Date(); var ts0=now.getTime();
var ob1=getbyid("p1"); var ob2=getbyid("p2"); var ob3=getbyid("p3");
window.requestAnimationFrame(run);
//setTimeout("run()",1000);
';}
static function headers(){head::add('csscode','');
head::add('jslink','/js/canvas.js');
head::add('jscode',self::js());}

#build
static function datas($p){
[$te,$p1,$p2,$p3]=vals($p,['te','p1','p2','p3']);
$d1=($te/100); $d2=($te/100); $d3=$d1+$d2;
$r['p1']=$p1-$d1;
$r['p2']=$p2-$d2;
$r['p3']=$p3+$d3; pr($r);
return ($r);}//json_enc

#build
static function build($p){$id=$p['id']??''; return [];
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
$r=self::build($p);
$ret=self::play($p,$r);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){
return tag('canvas',['id'=>'cnv','width'=>self::$w.'px','height'=>self::$h.'px'],'');}

static function menu($p){$a=self::$a; $cb=self::$cb; 
$p1=$p['p1']?$p['p1']:10; $p2=$p['p2']??10; $p3=$p['p3']??2;
$j=$cb.',,,1|'.$a.',com||p1,p2,p3';
$ret=form::call(['p1'=>['inputnb',$p1,'cl1',$j],'p2'=>['inputnb',$p2,'cl2',$j],'p3'=>['inputnb',$p3,'cl3',$j]]);//,['submit','ok',$j,'']
//$ret.=bj($cb.',,,1|'.$a.',com|p1=10,p2=10,p3=2',pic('reset'),'btn');
$ret.=btj(langp('play'),'playstop()','btn');
$ret.=btj(langp('pause'),'wait()','btn');
//$ret.=btj(langp('stop'),'stop()','btn');
$ret.=btj(langp('reset'),'resetp(4,4,4)','btn');
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=self::menu($p);
//$ret=self::call($p);
$ret=self::com($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>