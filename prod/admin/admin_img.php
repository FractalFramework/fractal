<?php

class admin_img{
static $private=6;
static $a='admin_img';
static $db='admin_img';
static $cb='admim';

static function install(){
sql::create(self::$db,['uid'=>'int','im'=>'var','tit'=>'var'],1);}//,'app'=>'var'

static function admin(){
$r[]=['','j','popup|'.self::$a.',content','plus',lang('open')];
$r[]=['','j',self::$cb.'|'.self::$a.',stream|display=2','list','-'];
$r[]=['','j',self::$cb.'|'.self::$a.',stream|display=1','th-large','-'];
$r[]=['','pop','core,help|ref='.self::$a.'_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f='.self::$a,'code','Code'];
return $r;}

static function js(){
return '';}

static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function titles($p){
$d=$p['_m']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#build
static function build($p){
//$r=scandir_a('img/mini');
$r=sql('im',self::$db,'rv',['uid'=>ses('uid')]);
return $r;}

static function play($p){$ret='';
$r=self::build($p); //pr($r);
$dsp=ses(self::$a.'dsp',val($p,'display'));
if($r)foreach($r as $k=>$v){
	$im=img('/img/mini/'.$v);
	if(!is_file('img/mini/'.$v))$im=ico('image',36);
	$ret.=imgup('img/full/'.$v,$im.span($v),'bicon');}
return $ret;}

static function call($p){
return div(self::build($p),'',self::$cb);}

static function com(){
return self::content($p);}

static function feed($p){$ret='';
//$ra=scan_dir('img/full'); echo count($ra);
$rb=sql('uid,txt,id','tlex','','where txt like "%.jpg%" or like "%.png%" or txt like "%:img%"'); //pr($rb);
foreach($rb as $k=>$v){
	$r=str::connslct($v[1],':img');
	foreach($r as $vb){
		if(strpos($vb,'http')===false)
			if(strpos($vb,'.jpg') or strpos($vb,'.png') or strpos($vb,'.gof'))$rc[]=[$v[0],$vb,$v[2]];
	}
	//$im=img('/img/mini/'.$v);
	//$ret.=imgup('img/full/'.$v,$im.span($v),'bicon');
	}
//pr($rc);
//sql::sav2(self::$db,$rc);
#desk
$rc=sql('im',self::$db,'rv',''); //pr($ra);
$rb=sql('uid,com,bt','desktop','',['type'=>'img']); //pr($rb);
foreach($rb as $k=>$v){
	if(!in_array($v,$rc))$rd[]=$v;
}
//pr($rd);
//sql::sav2(self::$db,$rd);
#im
//pr($rc);
foreach($ra as $k=>$v){
	if(!in_array($v,$rc))$re[]=[1,$v,''];
}
pr($re);
//sql::sav2(self::$db,$re);
return $ret;}

static function duplicatas(){
$r=sql('id,im',self::$db,'kv',''); //pr($ra);
foreach($r as $k=>$v){
	unset($r[$k]);
	foreach($r as $kb=>$vb){
		if($vb==$v)$rb[$v][$kb]=1;
		if($vb==$v)$rc[$kb]=1;
	}
}
pr($rc);
}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??''; $ret='';
$ret=self::play($p);
//$ret=self::feed($p);
//$ret=self::duplicatas();
return div($ret,'pane');}

}
?>