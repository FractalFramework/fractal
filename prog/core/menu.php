<?php

class menu{	
#sample
static function menus(){
//['folder','/j/lk/in/t','app,action','picto','text']//txt use lang
$r[]=['menu1','j','popup|txt','text','textpad'];
return $r;}

#build button
static function bub($r){
[$dir,$ty,$call,$picto,$bt]=$r;
$c=strpos($dir,'/')===false?'react':'';
if($picto)$pic=icon_ex($picto)?icolg($picto):$picto;
elseif(icon_ex($bt))$pic=icolg($bt);
elseif($ty=='lk')$pic='link'; 
elseif($ty=='j')$pic='circle-o';//check
else $pic='square-o';
if(lang_ex($bt))$bt=lang($bt);
elseif($bt=='-')$bt='';
$ico=$pic!='-'?ico($pic):'';//&&$dir
$bt=span($ico,$dir?'ico':'').($bt?' '.span($bt,$c):'');
$attr['onclick']='bubClose();';
$attr['onmouseover']='bubCloseOthers(this.parentNode);';
if($ty=='')$ret=popup($call.'|headers=1',$bt,'',$attr);
elseif($ty=='j')$ret=bj($call,$bt,'',$attr);
elseif($ty=='jk')$ret=bjk($call,$bt,'',$r[4]);
elseif($ty=='js')$ret=btj($bt,$call,'','');
elseif($ty=='pag')$ret=pagup($call,$bt);
elseif($ty=='pop')$ret=popup($call,$bt);
elseif($ty=='bub')$ret=bubble($call,$bt);
elseif($ty=='img')$ret=imgup('img/full/'.$call,$bt);
elseif($ty=='cb')$ret=bj('cbck|'.$call,$bt,'',$attr);
elseif($ty=='in')$ret=div(apj($call));
elseif($ty=='lk')$ret=lk($call,$bt);
elseif($ty=='lkt')$ret=lk($call,$bt,'',1);
elseif($ty=='t')$ret=tag('span','',$bt);
else $ret=popup($call,$bt,'',$attr);
return $ret;}

#build auto-button for sub-folders
static function subub($bt,$dir,$app,$mth,$drop,$bid,$a){
$rid=randid('bub'); $attr=[]; $us=ses('usr'); $noc=0;
$mode=strpos($dir,'/')===false?'1':'';//vertical bubble for the first level
if($bt=='previous'){$mode=''; $noc=1;}//prevent adding pop
if($bt==$us)$ico=ico('user-circle-o');//
elseif($ic=icon_ex($bt))$ico=ico($ic);//non-invasif
else $ico=ico('square-o');
if($bt=='down')$bt=span('','',$bid);
if($bt!=$us && lang_ex($bt))$bt=lang($bt);
if($mode && $bt)$bt=span($bt,'');//react
if(!$mode)$ico=span($ico,'ico').' '; else $ico=$ico.' ';
if(!$mode && !$noc)$chevron=span(ico('chevron-right'),'grey right'); else $chevron='';
$bt=$chevron.$ico.span($bt);
$call=($drop?'drop':'menu').','.$rid.','.$mode.'|menu,call';
$p='app='.$app.',mth='.$mth.',dir='.$dir.',drop='.$drop.',bid='.$bid.',a='.$a;
//$attr['onmouseover']='bubCloseTimer();';
//$attr['onmouseover']='ajxt(\''.$call.'|'.$p.'\'); zindex(\''.$rid.'\');';
//$attr['onmouseout']='clearTimeout(xc);';//clearTimeout(xb);
if($drop)$attr=[];
$attr['id']=$rid;
$attr['onmousedown']='ajbt(this);';
$attr['data-j']=$call.'|'.$p;
$ret=tag('a',$attr,$bt);
return $ret;}

#displayed part of the master array $r
//root begin without '/' mean first level, mean vertical drop
static function build($r,$dir,$app,$mth,$drop,$bid,$a){
$rdir=explode('/',$dir);
//$current_depht=count($rdir); 
$current_depht=substr_count($dir,'/');
$current_level=$rdir[$current_depht];
$previous_level=$rdir[$current_depht-1]??'';
if($previous_level && $drop){$fsvl=array_slice($rdir,0,$current_depht);
	$ret[]=self::subub('previous',implode('/',$fsvl),$app,$mth,$drop,$bid,$a);}
foreach($r as $v){
	$level=explode('/',$v[0]); $depht=count($level)-1;
	//active_level: [1]/2/3
	if(array_key_exists($current_depht,$level))
		$active_level=$level[$current_depht];
	else $active_level='';
	//next_level: 1/[2]/3
	if(array_key_exists($current_depht+1,$level))
		$next_level=$level[$current_depht+1];
	else $next_level='';
	//first_levels: [1/2]/3
	$fsvl=array_slice($level,0,$current_depht+1);
	$first_levels=implode('/',$fsvl);
	//button
	$len=strlen($dir);
	if($v[0]==$dir)$ret[]=self::bub($v);
	//acceed to next level (second iteration)
	elseif($active_level==$current_level && $next_level)
		$ret[$next_level]=self::subub($next_level,$first_levels.'/'.$next_level,$app,$mth,$drop,$bid,$a);
	//display next level (first iteration)
	elseif(substr($v[0],0,$len)==$dir && $depht>=$current_depht){// && strpos(substr($v[0],$len),'/')
		//$next=$next_level?$first_levels.'/'.$next_level:$active_level;
		$next=$active_level;
		$ret[$active_level]=self::subub($active_level,$next,$app,$mth,$drop,$bid,$a);}
}
if(isset($ret))return implode('',$ret);}

#call
static function call($p){$bck='';
[$dir,$app,$drop,$bid,$a,$mth,$css,$rid]=vals($p,['dir','app','drop','bid','a','mth','css','rid']);
if(method_exists($app,$mth)){$q=new $app; $r=$app::$mth(['rid'=>$rid,'a'=>$a,'bid'=>$bid]);}//$rid
//if($adir=struntil($dir,'/') && substr_count($dir,'/')>1)$bck=self::subub('back',$adir,$app,$mth,$drop,$bid,$a);
if(isset($r))$ret=$bck.self::build($r,$dir,$app,$mth,$drop,$bid,$a); else $ret='';//no datas found
$attr['class']='bub'; $attr['onclick']='popz++; this.style.zIndex=popz;';
if(!$dir && $css)$attr['class'].=' '.$css;
if($dir)$attr['class'].=' ablock';//css for sublevels
return tag('div',$attr,$ret);}

static function load($app,$mth,$dir='',$drop='',$bid='',$a=''){$rid=randid('dsk');
$p=['dir'=>$dir,'app'=>$app,'mth'=>$mth,'drop'=>$drop,'bid'=>$bid,'a'=>$a,'rid'=>$rid];
return div(self::call($p),'',$rid);}
}
?>