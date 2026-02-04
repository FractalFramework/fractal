<?php
class desk{	
static $cb='dskcnt';

#sample
static function menus(){
//['folder','pop/lk','action','picto','bt','auth','uid']
$r[]=['/menu1','j','popup|txt','text','textpad',0,''];
return $r;}

static function bt($ico,$v4,$ok,$v6){
$bt=div($ico.span($v4));
if(!$ok)$bt.=span('('.usrid($v6).')','small');
return $bt;}

static function btmenu($ico,$v4){
return div($ico.span($v4));}//pic('edit',12)

static function build($r,$current_depht,$current_level,$auth,$uid,$dir,$prm,$cmb,$rid){$rt=[];
foreach($r as $k=>$v){
$level=explode('/',$v[0]); $depht=count($level)-1;
//next_level: 1/[2]/3
if(array_key_exists($current_depht+1,$level))
	$next_level=$level[$current_depht+1];
else $next_level='';
$private=class_exists($v[2]) && isset($v[2]::$private)?$v[2]::$private:0;
if($auth>=$private){
	$ok=$v[6]==$uid?1:0;
	if(icon_ex($v[4]))$ico=ico(icolg($v[4],'',1)); else $ico=ico($v[3]);
	if(lang_ex($v[4]))$v4=lang($v[4],'',1); else $v4=$v[4];
	if($v[0]==$dir or $cmb==2){
		if($v[1]=='img')$ico='';
		$bt=self::bt($ico,$v4,$ok,$v[6]);
		$j='kedt|desktop,modifbt|dir=/'.$current_level.',id='.$k.',rid='.$rid.'|mdfbt';
		//if($ok)$bt.=bj($j,picto('edit',12));//modifbt
		//if($v[1]=='' && class_exists($v[2]))$rt[]=popup($v[2].'|headers=1',$bt);
		if($v[1]=='' && class_exists($v[2]))$rt[]=bj(self::$cb.'|'.$v[2].'|headers=1',$bt);
		elseif($v[1]=='j')$rt[]=bj($v[2],$bt);
		elseif($v[1]=='ju')$rt[]=bjk($v[2],$bt,'','/');
		elseif($v[1]=='jk')$rt[]=bjk($v[2],$bt,'',$v[4]);
		elseif($v[1]=='pop')$rt[]=popup($v[2].',headers=1',$bt);
		elseif($v[1]=='pag')$rt[]=pagup($v[2].',headers=1',$bt);
		//elseif($v[1]=='img')$rt[]=imgup('img/full/'.$v[2],$ico);//span()
		elseif($v[1]=='img')$rt[]=div(span(playimg($v[2],'micro')).span($v4));
		elseif($v[1]=='audio')$rt[]=popup('core,audio|u='.nohttp($v[2]),$bt);
		elseif($v[1]=='video')$rt[]=pagup('core,video|u='.nohttp($v[2]),$bt);
		elseif($v[1]=='in')$rt[]=div(apj($v[2]));
		elseif($v[1]=='lk')$rt[]=lk('/app'.$v[2],div($ico),'',1);}
		elseif(substr($v[0],0,strlen($dir))==$dir && $depht>$current_depht){//dir
			//can use popup instead of div
			$ico=ico('folder'); if(strto($v[2],',')==$next_level)$ico=ico('folder-o');
			if(lang_ex($next_level))$v4=lang($next_level,'',1); else $v4=$next_level;
			$bt=self::btmenu($ico,$v4);
			//if($ok)$bt.=bj('kedt|desktop,modifdir|rid='.$rid.',dir='.$dir.'/'.$next_level,picto('edit',12));
			$rt[$next_level]=bj($rid.',,2|desk,call|dir='.$dir.'/'.$next_level.','.$prm,$bt);}}}
return $rt;}

#call //p:structure
static function call($p){$rt=[]; $uid=ses('uid');
$ra=['dir','app','mth','cuid','bid','rid','display','spread','combine'];
[$dir,$app,$mth,$cuid,$bid,$rid,$display,$spread,$combine]=vals($p,$ra);
$dsp=ses('dskdsp',$display); if(!$dsp)$dsp=ses('dskdsp',2);
$spd=ses('dskspd',$spread); if(!$spd or !$uid)$spd=ses('dskspd',3);
$cmb=ses('dskcmb',$combine);
$css=$dsp==1?'licon':'cicon'; //$sz=$dsp==1?24:32;
//$auth=auth(6);
$rdir=explode('/',$dir);
$current_depht=substr_count($dir,'/');
if(array_key_exists($current_depht,$rdir))
	$current_level=$rdir[$current_depht];
$auth=ses('auth')?ses('auth'):0;
$prm='app='.$app.',mth='.$mth.',cuid='.$cuid.',bid='.$bid.',rid='.$rid;
//load
if($app && $mth)$r=$app::$mth(['dir'=>$dir,'cuid'=>$cuid,'bid'=>$bid]); //pr($r);

if(isset($r))$rt=self::build($r,$current_depht,$current_level,$auth,$uid,$dir,$prm,$cmb,$rid);
//nav
$dr=''; $back=''; $edit=''; $n=count($rdir);
if($rdir)foreach($rdir as $k=>$v){if($v)$dr.='/'.$v; else $v='/';
	$back.=bj($rid.',,2|desk,call|dir='.$dr.','.$prm,langp($v),$k==$n-1?'btok':'btno');}
//edit
$prm.=',dir='.$dir; $j=$rid.',,y|desk,call|'.$prm; $edit='';
if($dsp==2)$edit.=bj($j.',display=1',langpi('icons'),$dsp==1?'active':'');//dsp
else $edit.=bj($j.',display=2',langpi('list'),$dsp==2?'active':'');
if($app=='desktop'){
	if($cmb==1)$edit.=bj($j.',combine=2',ico('folder-o'));//cmb
	else $edit.=bj($j.',combine=1',ico('folder-open-o'));
	if($spd==1 && $uid)$edit.=bj($j.',spread=2',langph('dsk_private'),$spd==1?'active':'');
	elseif($spd==2)$edit.=bj($j.',spread=3',langph('dskpublic'),$spd==2?'active':'');//spd
	elseif($spd==3)$edit.=bj($j.',spread=1',pic('user').usrid($cuid),$spd==3?'active':'');
	// && $uid!=$cuid
	if(ses('uid'))$edit.=bj('popup|desktop,manage|dir='.$dir,langpi('edit'));//edt
	if(ses('uid'))$edit.=bj(self::$cb.'|desktop,renove|dir='.$dir,langpi('update'));
	$edit.=lk('/'.$app.'/dir:'.substr($dir,1),ico('link'));}
$edit.=span('','','kedt');
$edit=span($edit,'nbp');
if($rt)return div($back.$edit).div(div(implode('',$rt),$css),'',self::$cb);
elseif($dir){
	if($rdir){array_pop($rdir); $p['dir']=implode('/',$rdir);} else $p['dir']='/';
	return self::call($p);}
else return div($back.$edit).help('destop empty');}
//elseif($dir){$p['dir']=struntil($dir,'/'); return self::call($p);}

static function load($app,$mth,$dir='',$cuid='',$bid=''){$rid=randid('dsk');
self::$cb=$rid;
$p=['dir'=>$dir,'app'=>$app,'mth'=>$mth,'cuid'=>$cuid,'bid'=>$bid,'rid'=>$rid];
return div(self::call($p),'',$rid);}
}
?>