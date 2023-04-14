<?php

class tree{
#sample
static function trees(){
//['folder','/j/lk/in/t','app,action','picto','text']//txt use lang
$r[]=['root','in','app,mth','text','textpad'];
return $r;}

#build button
static function bub($r){$ret=''; $attr='';
[$dir,$type,$call,$picto,$btn]=$r;
if($picto)$pic=icon_ex($picto)?icolg($picto):$picto;
elseif(icon_ex($btn))$pic=icolg($btn);
elseif($type=='lk')$pic='link'; 
elseif($type=='j')$pic='check';
else $pic='square-o';
if(lang_ex($btn))$btn=lang($btn);
elseif($btn=='-')$btn='';
$ico=$pic!='-'?ico($pic):'';//&&$dir
$btn=span($ico,$dir?'ico':'').' '.span($btn);
//$attr['onclick']='bubClose();';
//$attr['onmouseover']='bubCloseOthers(this.parentNode);';
//$call='sub'.$rid.'|tree,call';
//$p='app='.$app.',mth='.$mth.',dir='.$dir.',drop='.$drop.',bid='.$bid.',a='.$a;
//$ret=toggle($call.'|'.$p,strend($dir,'/'));
if($type=='')$ret.=popup($call.'|headers=1',$btn,'',$attr);
elseif($type=='j')$ret=bj($call,$btn,'',$attr);
elseif($type=='pag')$ret=pagup($call,$btn);
elseif($type=='pop')$ret=popup($call,$btn);
elseif($type=='bub')$ret=bubble($call,$btn);
elseif($type=='img')$ret=imgup('img/full/'.$call,$btn);
elseif($type=='in')$ret=div(apj($call));
elseif($type=='lk')$ret=lk($call,$btn);
elseif($type=='lkt')$ret=lk($call,$btn,'',1);
elseif($type=='t')$ret=tag('span','',$btn);
else $ret=popup($call,$btn,'',$attr);
return $ret;}

#build auto-button for sub-folders
static function subub($btn,$dir,$app,$mth,$drop,$bid,$a){$rid=randid('bub');
$mode=strpos($dir,'/')===false?'1':'';//vertical bubble for the first level
//if($btn===ses('usr'))$ico=ico('user-circle-o');//!
//elseif($ic=icon_ex($btn))$ico=ico($ic);//non-invasif
//else 
$ico=ico('caret-right');
//if(lang_ex($btn))$btn=lang($btn);
//if($mode)$btn=span($btn,'react');
if(!$mode)$ico=span($ico,'ico').' '; else $ico=$ico.' ';
//if(!$mode)$chevron=span(ico('chevron-right'),'grey right'); else $chevron='';$chevron.
$btn=$ico.span($btn);
$call='sub'.$rid.'|tree,call';//,,y
$p='app='.$app.',mth='.$mth.',dir='.$dir.',drop='.$drop.',bid='.$bid.',a='.$a;
//if($drop)$attr='';
//$attr['id']=$rid;
//$attr['onmousedown']='ajbt(this);'; $attr['data-j']=$call.'|'.$p;
//$ret=tag('a',$attr,$btn);
$ret=div(toggle($call.'|'.$p,$btn));
$ret.=div('','sub','sub'.$rid);
return $ret;}

#displayed part of the master array $r
//root begin without '/' mean first level, mean vertical drop
static function build($r,$dir,$app,$mth,$drop,$bid,$a){
$rdir=explode('/',$dir);
//$current_depht=count($rdir); 
$current_depht=substr_count($dir,'/');
$current_level=$rdir[$current_depht];
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
	if($v[0]==$dir)$ret[]=self::bub($v);
	//acceed to next level (second iteration)
	elseif($active_level==$current_level && $next_level)
		$ret[$next_level]=self::subub($next_level,$first_levels.'/'.$next_level,$app,$mth,$drop,$bid,$a);
	//display next level (first iteration)
	elseif(substr($v[0],0,strlen($dir))==$dir && $depht>=$current_depht){
		//$next=$next_level?$first_levels.'/'.$next_level:$active_level;
		$next=$active_level;
		$ret[$active_level]=self::subub($active_level,$next,$app,$mth,$drop,$bid,$a);}
}
if(isset($ret))return implode('',$ret);}

#call
static function call($p){
$dir=val($p,'dir'); $app=$p['app']??''; $mth=val($p,'mth'); $drop=val($p,'drop'); 
$bid=$p['bid']??''; $a=val($p,'a'); $css=val($p,'css'); $rid=$p['rid']??'';
if(method_exists($app,$mth)){$q=new $app;
	$r=$app::$mth(['dir'=>$dir,'drop'=>$drop,'bid'=>$bid,'rid'=>$rid]);}
if(isset($r))$ret=self::build($r,$dir,$app,$mth,$drop,$bid,$a); else $ret='';//no datas found
$attr['class']='lisb'; $attr['onclick']='popz++; this.style.zIndex=popz;';
if(!$dir && $css)$attr['class'].=' '.$css;
if($dir)$attr['class'].=' ablock';//css for sublevels
return tag('div',$attr,$ret);}

static function load($app,$mth,$dir='',$drop='',$bid='',$a=''){$rid=randid('dsk');
$p=['dir'=>$dir,'app'=>$app,'mth'=>$mth,'drop'=>$drop,'bid'=>$bid,'a'=>$a,'rid'=>$rid];
return div(self::call($p),'',$rid);}
}
?>