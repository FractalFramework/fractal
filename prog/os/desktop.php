<?php

class desktop{
static $private=0;
static $a='desktop';
static $db='desktop';

static function js(){return;}
static function headers(){//head::add('jscode','');
head::add('csscode','fieldset, legend{border:0; background:#ddd; width:44%; display:table-cell;}');}

static function admin(){
return admin::app(['a'=>self::$db,'db'=>self::$db]);
if(auth(4))$r[]=['','j','popup|desktop,manage','','manage'];
$r[]=['','bub','core,help|ref=desktop_app','help','-'];
return $r;}

static function install(){
sql::create(self::$db,['uid'=>'int','dir'=>'var','type'=>'var','com'=>'var','picto'=>'var','bt'=>'var','auth'=>'int']);}

//fill sql from existing apps
static function readapps(){
$dirs=scandir_r('app');
if(is_array($dirs))foreach($dirs as $dir=>$files){
	if(is_array($files) && $dir)foreach($files as $k=>$file){
		if(is_string($file)){$app=struntil($file,'.');
			if($app)$private=isset($app::$private)?$app::$private:0;
			$dr='/phi/'.$dir;
			if(!$private or ses('auth')>=$private)
				$r=['uid'=>'0','dir'=>$dr,'type'=>'','com'=>$app,'picto'=>icolg($app),'bt'=>$app];
				$nid=sql::sav(self::$db,$r);}}}}

static function reload(){
return bj('page|desktop',lang('reload'),'btn');}

static function applist(){
return applist::build('public');}

#admin
//displace
static function savemdfdr($p){
//$where=auth(6)?' or uid="0"':'';
$r=sql('id,dir',self::$db,'rr','where uid="'.ses('uid').'" and auth<="'.ses('auth').'"'); //pr($r);
if($p['mdfdr'])foreach($r as $k=>$v){$vb=str_replace($p['dir'],$p['mdfdr'],$v['dir']);
	if($vb!=$v['dir'])qr('update desktop set dir="'.$vb.'" where id="'.$v['id'].'"');}
return desk::load('desktop','build',struntil($p['mdfdr'],'/'));}

static function modifdir($p){$sz=$p['sz']??8; $rid=$p['rid']??'page';
//$j=bjs('page,2|desktop,savemdfdr','dir='.$p['dir'],'mdfdr');
$j=$rid.',,2|desktop,savemdfdr|dir='.$p['dir'].'|mdfdr';
$prm=['type'=>'text','id'=>'mdfdr','value'=>$p['dir'],'size'=>24,'onblur'=>'ajbt(this)','onkeypress'=>'checkj(event,this)','data-j'=>$j];
$ret=tag('input',$prm,'',1);
return $ret;}

//renove
static function renove($p){
$uid=ses('uid'); $rt=[];
$a=self::$a; $dr=$p['']??'';
//$r=self::applist(); //foreach($r as $a=>$v){}
$cols=$a::$cols??[];
	if($cols){$cl='id,'.$cols[0];
	if(in_array('pub',$cols))$cl.=',pub';
	if($a=='images')$cl.=',img';
	$dir='/documents/'.$a;
	$rb=sql($cl,$a::$db,'',['uid'=>ses('uid')]);
	if($rb)foreach($rb as $kb=>$vb){
		$t=val($vb,1); $pub=val($vb,2,0); $com=$a.',call|id='.$vb[0]; $pic=icon_ex($a); $ty='pop';
		//if($a=='images'){$im=val($vb,3); if(is_img($im)){$com=$im; $ty='img';}}
		$pub=appx::$app2dsk[$pub];
		$pr=['uid'=>$uid,'dir'=>$dir,'type'=>$ty,'com'=>$com,'picto'=>$pic,'bt'=>$t,'auth'=>$pub];
		if($t && $a!='images')sql::savup(self::$db,$pr,['uid'=>$uid,'dir'=>$dir,'com'=>$com]);//pr($pr);
		$rt[]=$pr;}}
//$ret=div('added','tit').tabler($rt);
$ret=self::delold($a);
return self::content(['dir'=>'/documents']).$ret;}

static function delold($a){$rb=[];
$r=sql('id,type,com',self::$db,'',['uid'=>ses('uid')]);
foreach($r as $k=>$v){$ok=1;
	if($v[1]=='img' && is_file('img/full/'.$v[2]))$ok=1;
	//$ra=explode('|',$v[2]); $rab=explode(',',$ra[0]); $a=$rab[0];
	if(strpos($v[2],','))$a=strto($v[2],',');
	elseif(strpos($v[2],'|'))$a=strto($v[2],'|');
	elseif($v[2] && strpos($v[2],'.')===false)$a=$v[2]; else $a='';
	if(strpos($v[2],'id='))$id=strfrom($v[2],'id='); else $id='';
	if($a && !class_exists($a))$ok=0;
	elseif($id && isset($a::$db) && !sql('id',$a::$db,'v',['id'=>$id]))$ok=0;
	if(!$ok)sql::del(self::$db,$v[0]);
	if(!$ok)$rb[]=[$v[1],$v[2],$a,$id,$ok];}
if($rb)return div('deleted','tit').tabler($rb);}

//rename
static function savemdfbt($p){
if(auth(6) && $p['id'])sql::up(self::$db,'bt',$p['mdfbt'],$p['id']);
return desk::load('desktop','build',$p['dir']);}

static function modifbt($p){
$rid=$p['rid']??'page';
$r=sql('bt,dir',self::$db,'ra','where id="'.$p['id'].'"');
//$j=bjs('page,,2|desktop,savemdfbt|id='.$p['id'].',dir='.$p['dir'].'|mdfbt');
$j=$rid.',,2|desktop,savemdfbt|id='.$p['id'].',dir='.$p['dir'].'|mdfbt';
$prm=['type'=>'text','id'=>'mdfbt','value'=>$r['bt'],'size'=>24,'onblur'=>'ajbt(this)','onkeypress'=>'checkj(event,this)','data-j'=>$j];
$ret=tag('input',$prm,'',1);
return $ret;}

//del
static function del($p){
sql::del(self::$db,$p['id']);
return self::manage($p);}
//update
static function update($p){
$keys='dir,type,com,picto,bt'; $r=explode(',',$keys);
foreach($r as $k=>$v)sql::up(self::$db,$v,$p[$v],$p['id']);
//return lang('updated').' '.self::reload();
return self::manage($p);}

static function edit($p){$ret='';
$keys='dir,type,com,picto,bt';
$r=sql($keys,self::$db,'ra','where id="'.$p['id'].'"');
foreach($r as $k=>$v)$ret.=goodinput($k,$v).' '.label($k,$k).br();
$ret.=bj('dskmg|desktop,update|id='.$p['id'].'|'.$keys,lang('save'),'btsav');
$ret.=bj('dskmg|desktop,del|id='.$p['id'],langp('del'),'btdel');
return div($ret,'','dskdt');}

static function save($p){
$r=sql::cols(self::$db,1,0);
foreach($r as $k=>$v)$rb[$k]=$p[$k]??'';
$nid=sql::sav(self::$db,$rb);
if($nid)self::manage($p);}

static function add($p){
$r=sql::cols(self::$db,1,0);
$keys=implode(',',array_keys($r)); unset($r['uid']);
$ret=hidden('uid',ses('uid'));
foreach($r as $k=>$v)$ret.=input($k,$k,16,1).br();
$ret.=bj('dskpop|desktop,save||'.$keys,lang('add'),'btn');
return div($ret,'','dskpop');}

static function tlex_app($p){$app=$p['app']??''; $ret='';
[$ex,$dir]=sql('id,dir',self::$db,'rw','where dir like "/apps/%" and com="'.$app.'"');
$rb=['uid'=>ses('uid'),'dir'=>'/apps','type'=>'','com'=>$app,'picto'=>icolg($app),'bt'=>$app,'auth'=>2];
if(!$ex)$nid=sql::sav(self::$db,$rb);
else $ret=bj('popup|desktop,del|id='.$ex,langp('delete'),'btdel');
return $ret.bj('popup|desktop|dir='.$dir,lang('desktop').$ex,'btn');}

//edit on place
static function mdfbt($p){
if($p['col']=='picto')$btn=ico($p['val']).' '; else $btn=$p['val'];
return bj($p['cbk'].'|desktop,modif|id='.$p['id'].',col='.$p['col'].',val='.jurl($p['val']).',cbk='.$p['cbk'],$btn,'btn');}

static function savemdf($p){$p['val']=$p[$p['idv']];
sql::up(self::$db,$p['col'],$p['val'],$p['id']);
return self::mdfbt($p);}

static function modif($p){
$idv='mdf'.$p['id'].$p['col'];
$js=bjs('div,'.$p['cbk'].'|desktop,savemdf|cbk='.$p['cbk'].',id='.$p['id'].',col='.$p['col'].',idv='.$idv.'|'.$idv); $v=$p['val'];
$r=['type'=>'text','id'=>$idv,'value'=>$v,'size'=>16,'onblur'=>$js];
$ret=tag('input',$r,'',1);
return $ret;}

//manage
static function manage($p){$ret=''; $ra=[]; $dir=$p['dir']??'';
if(isset($p['addrow'])){$r=sql::cols(self::$db,1,0);
	foreach($r as $k=>$v)$rb[$k]='';
	$rb['uid']=ses('uid'); $rb['dir']=$dir;
	$nid=sql::sav(self::$db,$rb);}
if(auth(2))$ret=bj('dskmg|desktop,manage|dir='.$dir.',addrow=1',langp('add'),'btn');
//if(auth(2))$ret=bj('popup|desktop,add|dir='.$dir,langp('add'),'btn');
//$ret.=bj('dskmg|desktop,manage|dir='.$dir,langp('refresh'),'btn');
//$ret.=bj('popup|desktop,readapps|'.lang('reflush apps'),'btn');
//table
if(auth(2))$keys='id,dir,type,com,picto,bt,auth'; else $keys='id,dir,picto,bt,auth';
$kr=explode(',',$keys); $n=count($kr);
if($dir)$wh=' and dir like "'.$dir.'%"'; else $wh='';// or auth<="'.ses('auth').'"
$r=sql($keys,self::$db,'','where uid="'.ses('uid').'" '.$wh.' order by id asc');
foreach($r as $k=>$v){
	//$ra[$k][0]=bj('popup|desktop,edit|id='.$v[0],$v[0],'btn');
	for($i=1;$i<$n;$i++){$cbk='inp'.$k.$i;//public can edit $v[6]
		if($kr[$i]=='picto')$ti=ico($v[$i]);
		else $ti=strlen($v[$i])>20?substr($v[$i],0,16).'...':$v[$i];
		if($kr[$i]=='com')$v[$i]=jurl($v[$i]);
		$bt=bj($cbk.'|desktop,modif|dir='.$dir.',id='.$v[0].',col='.$kr[$i].',val='.$v[$i].',cbk='.$cbk,$ti,'btn');
		$ra[$k][]=span($bt,'',$cbk);}
	$ra[$k][]=bj('dskmg|desktop,del|dir='.$dir.',id='.$v[0],pic('delete'),'btdel');}
$modes=hlpbt('desktop_modes','mode','btn');
$icons=bj('popup|icons','icon');
$auth=hlpbt('desktop_auth','auth','btn');
if(auth(2))$rk=['root',$modes,'app',$icons,'button',$auth];
else $rk=['root',$icons,'button',$auth];
if($ra)array_unshift($ra,$rk); else $ra[]=$rk;
$ret.=tabler($ra);
return div($ret,'','dskmg');}

#build
//$r[]=['dir','//j/in/lk','app','method','icon','bt'];
static function build($p){$wu=''; $wa='';
$dir=$p['dir']; $cuid=$p['cuid'];
$uid=ses('uid'); $usr=ses('usr'); $spd=ses('dskspd');//3=usr,2=public,1=private
$keys=self::$db.'.id,dir,type,com,picto,bt,auth,uid';
if(!$cuid)$cuid=$uid; if(!$spd)$spd=3;
if($spd==3){//cusr
	$wu='uid="'.$cuid.'"';
	if($uid!=$cuid){
		$ab=sql('id','tlex_ab','v',['usr'=>ses('uid'),'ab'=>$cuid]);
		if($ab)$wa='desktop.auth<=2'; elseif(!$uid)$wa='desktop.auth<1'; else $wa='desktop.auth<2';}
	else $wa='';}
elseif($spd==2){//public
	$rab=sql('ab','tlex_ab','rv',['usr'=>$uid]); $rab[]=$cuid; $wu='';
	//$w='left join tlex_ab on tlex_ab.ab=uid where uid="'.$uid.'" and tlex_ab.usr="'.$uid.'"';
	if($rab)$wa='(uid in ('.implode_q($rab).') or auth<2)';
	elseif(!$uid)$wa='auth<1'; else $wa='auth<2';}
elseif($spd==1){//private 
	$wu='uid="'.$uid.'"'; $wa='auth<="'.ses('auth').'"';}
if($wu && $wa)$w=$wu.' and '.$wa; elseif($wu)$w=$wu; elseif($wa)$w=$wa;
$w=$w?'where '.$w:''; if($dir)$w.=' and dir like "'.$dir.'%"';
return sql($keys,self::$db,'id',$w.' order by id desc');}//limit 100

static function pick($p){$css='bicon'; $cuid=ses('uid'); $id=$p['id']??''; $ret='';
$r=sql('id,dir,type,com,picto,bt,auth',self::$db,'id','where uid="'.$cuid.'" and dir like "/documents/img%" order by id asc');// limit 100//and auth<="'.ses('auth').'" 
if($r)foreach($r as $k=>$v)if($v[1]=='img'){$f='img/full/'.$v[2];
	$im=playimg($v[2],'micro',1); $bt=btj($im,insert($v[2],$id));
	if(is_file($f))$ret.=div($bt.span($v[4]),$css);}
return div($ret,'board');}

static function pickim($id){return popup('desktop,pick|id='.$id,pic('desktop'));}

//content
static function content($p){$ret='';
//self::install();
$uid=$p['cuid']??ses('cuid'); $dir=$p['dir']??''; if($dir && substr($dir,0,1)!='/')$dir='/'.$dir;
$ret=desk::load('desktop','build',$dir,$uid);
if($dir && !$ret)$ret=desk::load('desktop','build','',$uid);
return div($ret,'board');}//bloc_content
}

?>