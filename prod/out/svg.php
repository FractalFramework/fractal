<?php
class svg{
static $r=[],$w=600,$h=440,$t='',$fit=0,$ret=[],$vb=[];
static $clr=['#ffffff','#000000','#ff0000','#00ff00','#0000ff','#ffff00','#00ffff','#ff9900','#cccccc','#666666'];
static $clr_graph=['deepskyblue','yellowgreen','orange','blueviolet','palevioletred','limegreen','tomato','mediumpurple','magenta','slategray','mediumaquamarine','darkviolet','firebrick','chocolate'];

function __construct($w='',$h='',$t='',$fit=''){self::$r=[];
if($w)self::$w=$w; if($h)self::$h=$h; if($t)self::$t=$t; if($fit)self::$fit=$fit;}
function __destruct(){self::$r=[];}
static function init($w='',$h='',$t='',$fit=''){self::$r=[];
if($w)self::$w=$w; if($h)self::$h=$h; if($t)self::$t=$t; if($fit)self::$fit=$fit;}

static function clrs($n){$rb=[]; $r=array_keys(clrs()); $na=count($r)-1;
for($i=0;$i<$n;$i++)$rb[]=$r[rand(0,$na)];
return $rb;}

static function clr($d=''){$r=clrs();
$rb=array_keys($r); if($d=='rand')$d=rand(0,count($rb)-1);
return is_numeric($d)?$rb[$d]:$d;}

static function center_text($t,$sz=8){
return mb_strlen($t)*$sz*-1;}

static function len($d){$r=str_split($d); $n=0;
foreach($r as $k=>$v)if(strstr('itl ',$v))$n+=3; else $n+=8;
return $n;}

static function text($sz,$x,$y,$t,$clr){
self::$ret[]='['.$clr.':attr]['.$x.','.$y.','.$sz.'*'.$t.':text]';}
static function rect($x,$y,$w,$h,$clr,$clr2='',$wb='',$o='',$id=''){if(!$clr)$clr='none';
self::$ret[]='['.$clr.','.$clr2.','.$wb.':attr]['.$x.','.$y.','.$w.','.$h.','.$o.','.$id.':rect]';}
static function line($x,$y,$x2,$y2,$clr,$wb='',$o='',$ob=''){
self::$ret[]='[none,'.$clr.','.$wb.',,,'.$ob.':attr]['.$x.','.$y.','.$x2.','.$y2.','.$o.':line]';}
static function ellipse($x,$y,$w,$h,$clr,$clr2='',$wb='',$o=''){if(!$clr)$clr='none';
self::$ret[]='['.$clr.','.$clr2.','.$wb.':attr]['.$x.','.$y.','.$w.','.$h.','.$o.':ellipse]';}
static function circle($x,$y,$w,$clr,$clr2='',$wb='',$o=''){$w/=2; if(!$clr)$clr='none';
self::$ret[]='['.$clr.','.$clr2.','.$wb.':attr]['.$x.','.$y.','.$w.','.$o.':circle]';}
static function poly($r,$clr,$clr2='',$wb=''){if(!$clr)$clr='none';
self::$ret[]='['.$clr.','.$clr2.','.$wb.':attr]['.implode(' ',$r).':polygon]';}
static function polyline($r,$clr,$clr2='',$wb='',$op=''){if(!$clr)$clr='none';
self::$ret[]='['.$clr.','.$clr2.','.$wb.',,'.$op.':attr]['.implode(' ',$r).':polyline]';}
static function path($r,$clr,$clr2='',$wb=''){if(!$clr)$clr='none';
self::$ret[]='['.$clr.','.$clr2.','.$wb.':attr]['.implode(' ',$r).':path]';}
static function lk($x,$y,$lk,$clr,$tx='',$onc=''){if(!$clr)$clr='black';
self::$ret[]='['.$clr.':attr]['.$lk.','.$onc.'*['.$x.','.$y.'*'.$tx.':text]:a]';}
static function bj($x,$y,$sz,$clr,$j='',$tx=''){if(!$clr)$clr='black'; if(!$sz)$sz=12;
self::$ret[]='['.$clr.':attr]['.str_replace(',',';',$j).'*['.$x.','.$y.','.$sz.'*'.$tx.':text]:bj]';}
static function bub($x,$y,$sz,$clr,$ti='',$tx=''){if(!$clr)$clr='black'; if(!$sz)$sz=12;
self::$ret[]='['.$clr.':attr][['.$x.','.$y.','.$sz.'*'.$tx.':text]*'.$ti.':bub]';}
static function bubj($x,$y,$sz,$clr,$j,$bt){if(!$clr)$clr='black'; if(!$sz)$sz=12;
self::$ret[]='['.$clr.':attr]['.$j.'*['.$x.','.$y.','.$sz.'*'.$bt.':text]:bubj]';}
static function bubj2($x,$y,$sz,$clr,$j,$tx,$bt){if(!$clr)$clr='black'; if(!$sz)$sz=12;
self::$ret[]='['.$clr.':attr]['.$tx.','.$j.'*['.$x.','.$y.','.$sz.'*'.$bt.':text]:bubj2]';}
static function img($im,$w='',$h=''){self::$ret[]='['.$im.','.$w.','.$h.':image]';}

static function ex(){
$r['empty']='[60:grid][0,black:attr][0,0,[5:g],[5:g]:rect]';
$r['basics']='[red,black,1:attr]';
$r['stroke']='[red,10,0.5,round,2/4/5/4/2,miter:stroke]';
$r['rect']='[100,200,30,20:rect]';
$r['circle']='[300,220,200:circle]';
$r['line']='[100,100,40,80:line]';
$r['ellipse']='[100,100,40,80:ellipse]';
$r['polygon']='[200/10 250/190 160/210:polygon]';
$r['polyline']='[20/20 40/20 60/60 20/40 20/20:polyline]';
$r['arc']='[200,200,100,0,90:arc]';
$r['bj']='[popup|core/txt|txt=hello*[20,20*hello:text]:bj]';
$r['path']='[rand,red,2:attr][M150 0 L75 200 L225 200 Z:path][M10,50 A 50 20 0 1 1 110,50:path]';
$r['text']='[purple,,,,,,rotate(330 40/20):attr][10,100*hello:text]';
$r['a']='[blue:attr][280,140,http://philum.fr*[80,20,,1*philum:text]:a]';
$r['tspan']='[480,20*[80,20*hello1:tspan][green:attr][80,40*hello2:tspan]:text]';
$r['feGaussianBlur']='[*[f1,0,0*[SourceGraphic,15:feGaussianBlur]:filter]:defs]
[rand,,,,0.4:attr][300,120,100,1:circle]';
$r['defs']='[*[grad1,0%,0%,0%,100%*[0%,rand:stop][100%,rand:stop]:linearGradient]:defs]
[,,,,,,,grad1:attr][0,0,600,400:rect]
[*[grad2,0%,0%,0%,100%*[0%,red,0:stop][100%,yellow:stop]:linearGradient]:defs]
[,,,,,,,grad2:attr][300,120,100:circle]
[*[grad3,0%,0%,0%,100%*[0%,rand,0:stop][100%,rand:stop]:linearGradient]:defs]
[,,,,,,,grad3:attr][0,200,600,200:rect]
[*[f1,0,0*[SourceGraphic,15:feGaussianBlur]:filter]:defs]
[rand,,,,0.4:attr][300,120,100,1:circle]
[*[f2*
[SourceGraphic,offOut,10,10:feOffset]
[offOut,matrixOut,matrix,0.2 0 0 0 0 0 0 2 0 0 0 0 0 0 2 0 0 0 0 0 1 0:feColorMatrix]
[matrixOut,10,blurOut:feGaussianBlur]
[SourceGraphic,blurOut,normal:feBlend]
:filter]:defs]
[rand,,,,0.4:attr][300,120,100,f2:circle]';
$r['path']='[#1A171B:attr]
[M189.864 122.19c 0.161 0.173 0.373 0.269 0.617 0.269h 68.182c 0.236,0 0.454,0.096 0.614,0.269 c 0.149,0.191 0.208,0.426 0.171,0.645l11.457,69.282c0.063,0.383,0.396,0.67,0.784,0.67h14.06l0.962,11.283l 5.915,0.006 13.766,13.766l13.799 13.797l 5.914,0.008l0.96 11.266h14.306c0.396,0,0.725 0.285,0.783 0.681l10.717 69.271 C190.08 121.782,190.01 122.01,189.864 122.19z M163.211 65.229c0,0 3.1,3.19 8.13,2.84c 5.05,0.35 8.149 2.84 8.149 2.84 c 5.11 5.101 3.28 11.45 1.511 14.729c1.101 2.051,8.95 13.841,9.631 14.841v 0.08c0,0,0.01,0.01,0.029,0.04l0.021 0.04v0.08 c0.66,1,8.52,12.79,9.62,14.841C166.501 76.679,168.322 70.33,163.211 65.229z M185.142 107.614 c 1.849 0.814 4.191 0.52 6.441,0.392c 8.46,3.429 14.216,4.661 23.114,1.875c 5.604 1.754 10.848 5.427 17.046 3.19 c 4.112,1.479 7.944,2.89 12.595,2.854l 2.686 13.77h63.812L185.142 107.614z:path]';
$r['animate']='[100,100,10*
[r,XML,1;100;1,0s,10s,3:animate]
[fill-opacity,css,0.1;1;0.1,0s,10s,indefinite:animate]
:circle]';
$r['textpath']='[0,rand:attr][400,200:rect][M 100/100 c100/_150 200/150 300/0,chemin:path]
[rand:attr][*[#chemin,font-size:20px*Le SVG est un langage très amusant:textpath]:text]';
return $r;}

static function spe(){
$r['polygon']=['200/10 250/190 160/210'];
$r['polyline']=['20/20 40/25 60/40 80/120 120/140 200/180'];
$r['path']=['A rx ry rotation large-arc-flag sweep-flag x y','id'];
$r['path_type']=['M'=>'moveto','L'=>'lineto','H'=>'horizontal lineto','V'=>'vertical lineto','C'=>'curveto','S'=>'smooth curveto','Q'=>'quadratic Bézier curve','T'=>'smooth quadratic Bézier curveto','A'=>'elliptical Arc','Z'=>'closepath'];
$r['filters']=['feBlend','feColorMatrix','feComponenttransfer','feComposite','feConvolveMatrix','feDiffuseLighting','feDisplacementMap','feFlood','feGaussianBlur','feImage','feMerge','feMorphology','feOffset','feSpecularLighting','feTile','feTurbulence','feDistantLight','fePointLight','feSpotLight'];
$r['transform']=['rotate(30 20/40)','skewY(30)','skewY(30)'];
$r['animate-transform']=['translate','scale','skewX','skewY'];
$r['stroke-linecap']=['butt','round','square'];
$r['stroke-linejoin']=['arcs','bevel','miter','miter-clip','round'];
return $r;}

static function motor(){return [
'attr'=>['fill','stroke','stroke-width','size','fill-opacity','stroke-dasharray','transform','fillurl'],
'stroke'=>['stroke','stroke-width','stroke-opacity','stroke-linecap','stroke-dasharray','stroke-linejoin'],
'circle'=>['cx','cy','r','filter'],
'rect'=>['x','y','width','height','filter','id'],
'ellipse'=>['cx','cy','rx','ry','filter'],
'line'=>['x1','y1','x2','y2','filter','id','style'],
'polygon'=>['points'],
'polyline'=>['points'],
'path'=>['d','id'],
'arc'=>['x','y','radius','startAngle','endAngle'],
'text'=>['x','y','style','class','filter'],
'tspan'=>['x','y','dx','dy'],
'textpath'=>['xlink:href','style'],
'image'=>['xlink:href','width','height'],
'a'=>['xlink:href','onclick','title','id','target'],
//'bj'=>['x','y','onclick','onmouseover'],
'bj'=>['j'],//use ; instead of ,
'bjo'=>['j'],
'btj'=>['onclick','id'],
'bub'=>['txt'],
'bubj'=>['j'],
'bubj2'=>['j','txt'],
'js'=>['code'],
'animate'=>['attributeName','values','begin','dur','fill','repeatCount'],//,'from','to'
'animateTransform'=>['attributeName','values','begin','dur','type','repeatCount'],//,'from','to'
'animateMotion'=>['path','begin','dur','repeatCount'],
//'animate'=>['attributeName','attributeType','values','begin','dur','repeatCount'],
//[r,XML,1;200;1,0s,10s,10:animate][fill-opacity,css,0.1;1;0.1,0s,30s,indefinite:animate]
'filter'=>['id','x','y'],//,'filter','value'
'feOffset'=>['in','result','dx','dy'],
'feColorMatrix'=>['in','result','type','values'],
'feGaussianBlur'=>['in','stdDeviation','result'],
'feBlend'=>['in','in2','mode'],
'linearGradient'=>['id','x1','y1','x2','y2'],
'stop'=>['offset','style','opac'],
'group'=>['id'],
'use'=>['xlink:href','x','y'],
'defs'=>['value'],
'dim'=>['w','h']];}

static function sz($pr){[$x0,$y0]=self::$r['sz'];
$p=['x','y','width','height','cx','cy','r','rx','ry','x2','y2','points']; $r=vals($pr,$p,0);
foreach($r as $k=>$v)if(!is_numeric($v) && $k!=11)$r[$k]=0;
[$x,$y,$w,$h,$cx,$cy,$ray,$rx,$ry,$x2,$y2,$pt]=$r;
if($x+$w>$x0)$x0=$x+$w; if($y+$h>$y0)$y0=$y+$h;
if($cx+$ray>$x0)$x0=$cx+$ray; if($cy+$ray>$y0)$y0=$cy+$ray;
if($cx+$rx>$x0)$x0=$cx+$rx; if($cy+$ry>$y0)$y0=$cy+$ry;
if($x2>$x0)$x0=$x2; if($y2>$y0)$y0=$y2;
if($pt){$ptr=explode_r($pt,' ','/');//$pt=str_replace('-',' ',$pt);
	foreach($ptr as $k=>$v){if($v[0]>$x0)$x0=$v[0]; if($v[1]>$y0)$y0=$v[1];}}
if($x0>self::$r['sz'][0])self::$r['sz'][0]=$x0;
if($y0>self::$r['sz'][1])self::$r['sz'][1]=$y0;}

static function arc($pr){extract($pr);
return maths::describeArc($x,$y,$radius,$startAngle,$endAngle);}

static function prop($d){return str_replace(['/'],[','],$d);}//,'-','_'//,' ','-'

static function gridpoints($p){$r=explode(' ',$p); $rb=[];//'x1/y1-x2/y2 //$p=str_replace('-',' ',$p);
foreach($r as $k=>$v){[$x,$y]=explode('/',$v); $x*=self::$r['grid']; $y*=self::$r['grid']; $rb[]=$x.'/'.$y;}
return implode(' ',$rb);}

static function grid($w,$h){$ret='';
$g=self::$r['grid']; $nx=round($w/$g); $ny=round($h/$g);
for($i=0;$i<$nx;$i++)$ret.='[red:attr]['.($i*$g).',10*'.$i.':text][,silver:attr][0,'.($i*$g).','.($w).','.($i*$g).':line][red:attr][2,'.($i*$g).'*'.$i.':text][,silver:attr]['.($i*$g).',0,'.($i*$g).','.($h).':line]';
return $ret;}

static function conn($d){
$ra=self::motor(); //$d=str_replace('*','|',$d);
[$p,$o,$c]=readgen($d); $pr=[]; $vb=''; $noattr=0;
if(!$c)return; if(substr($p,0,2)=='//')return;
//echo $p.'--'.$o.'--'.$c.br();
//if($c=='path')$d=str_replace(',','/',$d);
if($c=='svg')return $p; if($c=='verbose' && $p)self::$r['verbose']=1;
if($c=='grid')self::$r['grid']=$p?$p:20;
if($c=='g')return $p*(self::$r['grid']??20); if($c=='gp')return self::gridpoints($p);
$rb=explode(',',$p); if(isset($rb[5]))$rb[5]=join(',',explode('-',$rb[5]));
if(isset($ra[$c]))$pr=combine($ra[$c],$rb); self::sz($pr);
if($c=='animate' or $c=='animateTransform' or $c=='animateMotion')$noattr=1;
if($c=='stroke')$c='attr';
if($c=='attr')self::$r['attr']=$pr;
elseif(self::$r['attr'] && !$noattr)$pr=merge($pr,self::$r['attr']);
if($c=='setvar' && strpos($p,'='))[$p,$o]=explode('=',$p);//[v=10:setvar][v:var]//echo $c.'-'.$p.'-'.$o;
if($c=='setvar')self::$r[$p]=$o; elseif($c=='var')return self::$r[$p]??'';//[v*10:setvar]
if(!empty($pr['points']))$pr['points']=self::prop($pr['points']);
if(!empty($pr['stroke-dasharray']))$pr['stroke-dasharray']=self::prop($pr['stroke-dasharray']);
if(!empty($pr['d']))$pr['d']=self::prop($pr['d']);
if(!empty($pr['transform']))$pr['transform']=self::prop($pr['transform']);
if(!empty($pr['style']))$pr['style']=is_numeric($pr['style'])?'font-size:'.$pr['style'].'px;':$pr['style'];
if(isset($pr['fill']))$pr['fill']=self::clr($pr['fill']);
if(!empty($pr['stroke']))$pr['stroke']=self::clr($pr['stroke']);
//if(isset($pr['onclick']) && $c=='bj'){//pr($pr);
//	$pr['onclick']=bjs($pr['onclick']['com'].'|'.$pr['onclick']['app']);}
if(!empty($pr['fillurl'])){$pr['fill']='url(#'.$pr['fillurl'].')';$pr['fillurl']='';}
if(!empty($pr['filter']))$pr['filter']='url(#'.$pr['filter'].')';
if($c=='feColorMatrix')$pr['values']=self::prop($pr['values']);
if($c=='stop')$pr['style']='stop-color:'.self::clr($pr['style']).'; stop-opacity:'.($pr['opac']??'0%').';';
if($c=='bj')return bj(str_replace(';',',',$pr['j']),$o,'');
if($c=='bjo')return bjo(str_replace(';',',',$pr['j']),$o,'');
if($c=='bub')return bubjs($pr['txt'],$o);
if($c=='bubj')return bubj(str_replace(';',',',$pr['j']),$o);
if($c=='bubj2')return bubj2($pr['txt'],str_replace(';',',',$pr['j']),$o);
if($c=='js'){head::add('jscode',$pr['code']); return;}
if($c=='a'){unset($pr['fill']); unset($pr['stroke']);}
if($c=='btj')return btj($o,$pr['onclick'],'',$pr['id']);
if($c=='arc'){$pr['d']=self::arc($pr); $c='path';}
if($c=='setvar'){unset($pr['fill']); unset($pr['stroke']);}
if($c=='dim')self::$r['sz']=[$pr['w'],$pr['h']];
if($c=='group')$c='g';
if(isset(self::$r['verbose']))self::$vb[]=tabler([$c]+$pr,1,1); //echo $p.'-'.$o.'-'.$c.br();
if($c!='attr' && $c!='verbose' && $c!='grid' && $c!='attr' && $c!='setvar' && $c!='var' && $c)
return tag($c,$pr,$o,0);}

#save
static function buildsave($d,$w='',$h='',$t=''){
$w=$w?$w:self::$w; $h=$h?$h:self::$h; $t=$t?$t:self::$t;
$u='img/svg/'.$t.'.svg'; mkdir_r($u);
$ret='<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="'.$w.'px" height="'.$h.'px" >'.$d.'</svg>';//viewBox="120.269 -122.459 69.784 95.92"
write_file($u,$ret); //if(auth(2))echo 'saved:'.$t;
return lk('/'.$u,icoxt('download',$t),'btn',1);}

static function save($p){self::$r['attr']=[]; self::$r['sz']=[0,0];
$t=$p['t']??date('ymd'); $code=$p['code']??''; $w=$p['w']??''; $h=$p['h']??'';
$d=conn::com2($code,'svg','conn'); [$w,$h]=self::$r['sz'];
return self::buildsave($d,$w,$h,$t);}

#call
static function cache($f){$u='img/svg/'.$f.'.svg'; //$ft=fdate($u);
if(file_exists($u))return tag('img',['src'=>'/'.$u,'width'=>'','height'=>'']);}

static function call($p){$rid=$p['rid']??''; ini_set('xdebug.max_nesting_level',10000);
$code=$p['code'.$rid]??''; $w=$p['w']??600; $h=$p['h']??440; $t=$p['t']??'graph'.strid($code);
$code=deln($code); $code=delnbsp($code);
self::$r['attr']=[]; self::$r['sz']=[0,0];
$ret=conn::com2($code,'svg','conn'); //$ret=cleannl($ret);
if($p['fit']??0){[$wb,$hb]=self::$r['sz']; if($wb>1 && $hb>1){$w=$wb; $h=$hb;}} //pr(self::$r['sz']);
$atr=['version'=>'1.1','width'=>$w+1,'height'=>$h+1];
if(isset(self::$r['grid'])){$code=self::grid($w,$h); $ret.=conn::com2($code,'svg','conn');} //eco($ret);
if($p['img']??''){self::buildsave($ret,$w,$h,$t); $ret=self::cache($t);}
else $ret=tag('svg',$atr,$ret);
if(isset(self::$r['verbose'])){eco($ret,'',80,16); $ret.=join('',self::$vb);}
self::$ret=[];
return $ret;}

static function com($d='',$w='',$h='',$t='',$fit=''){$d=$d?$d:implode('',self::$ret);
$w=self::$w; $h=self::$h; $t=self::$t; $fit=self::$fit;
return self::call(['code'=>$d,'w'=>$w,'h'=>$h,'t'=>$t,'fit'=>$fit]);}

/*static function render($sav=''){
$d=implode('',self::$ret); $w=self::$w; $h=self::$h; $t=self::$t; $fit=self::$fit;
$ret=self::call(['code'=>$d,'w'=>$w,'h'=>$h,'t'=>$t,'fit'=>$fit]);
if($sav){self::buildsave($ret); $ret=tag('img',['src'=>'img/svg/'.self::$t.'.svg','width'=>'','height'=>'']);}
self::$ret=[];
return $ret;}*/

static function content($p){
$p['code']=build::sample(['a'=>'svg','k'=>'basics']);
$bt=build::sample(['a'=>'svg','b'=>'codea']);
$bt.=div(menu::call(['app'=>'vector','mth'=>'menu','bid'=>'codea','drop'=>0]));
$bt.=textarea('codea',$p['code'],64,12,'','console');
$bt.=bj('svgcb|svg,call|rid=a|codea',langp('ok'),'btn');
return $bt.div(self::call($p),'board','svgcb');}

}
?>