<?php
class gaia extends appx{
static $private=0;
static $a='gaia';
static $db='gaia';
static $cb='mdl';
static $cols=['tit','req'];
static $typs=['var','var'];
static $conn=0;
static $open=0;
static $tags=1;
static $qb='';

function __construct(){
$r=['a','db','cb','cols'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'var'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){
return parent::collect($p);}

static function del($p){
//$p['db2']=self::$db2;
return parent::del($p);}

static function save($p){
return parent::save($p);}

static function modif($p){
return parent::modif($p);}

static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}
static function subform($r){
	$ret=hidden('bid',$r['bid']);
	//$ret.=div(input('chapter',$r['chapter'],63,lang('chapter'),'',512));
	return $ret;}

//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
//$p['help']=1;
//$p['sub']=1;
//$p['execcode']=1;
return parent::edit($p);}

#build
static function req($p){
$p=strtolower(str_replace(["&#nbsp;","\n","\t",','],',',$p));
$p=cleansp($p);
$pr=explode(',',$p); //p($pr);
if($pr)foreach($pr as $k=>$v){$t=''; $n=''; $v=trim($v);
	if(strpos($v,'<')){[$t,$n]=explode('<',$v); $s=0; $t=trim($t); $n=trim($n);}
	elseif(strpos($v,'>')){[$t,$n]=explode('>',$v); $s=1; $t=trim($t); $n=trim($n);}
	elseif(is_numeric($v))$n=trim($v);
	if($t=='ra' && $n)$sq['ra'][]=($s?'>':'<').$n;
	elseif($t=='dc' && $n)$sq['dc'][]=($s?'>':'<').$n;
	elseif($t=='dist' && $n)$sq['ds'][]=($s?'>':'<').(1/$n);
	elseif(is_numeric($n))$sq['gid'][]=$n;}//p($sq);
if(isset($sq['gid']))$wr['or'][]='gid in("'.implode('","',$sq['gid']).'")';
if(isset($sq['ra']))$wr['and'][]='ra'.implode(' and ra',$sq['ra']).'';
if(isset($sq['dc']))$wr['and'][]='dc'.implode(' and dc',$sq['dc']).'';
if(isset($sq['ds']))$wr['and'][]='parallax'.implode('and parallax',$sq['ds']).'';
if(isset($wr['and']))$w=implode(' and ',$wr['and']);
if(isset($wr['or']))$w=implode(' or ',$wr['or']);
return $w;}

static function build($p){$w=self::req($p['req']);
$r=sql('gid,ra,dc,parallax,mag','_gaia','rr','where '.$w.''); 
//$r=sql('gid,ra,dc,parallax,mag,radius,lum','_gaia2','rr','where '.$w.' limit 100'); 
return $r;}

/*Iouma
SELECT count(id) FROM `_gaia2` WHERE 
ra>189 and ra<190 and dc>9 and dc<10
and parallax 
*/

/*
parallax to dist
$p=$p/1000;
$p=1/$p;
1/p/1000/3.261564
0,582790372285251
3,261564
*/

static function play($p){
$ra=parent::build($p);
$r=self::build($ra);
if($r)foreach($r as $k=>$v){
	$rb[$k]['gid']=lk('http://simbad.u-strasbg.fr/simbad/sim-id?Ident=gaia+'.$v['gid'].'&NbIdent=1&Radius=2&Radius.unit=arcmin&submit=submit+id',$v['gid']);
	$rb[$k]['ra']=maths::deg2ra(($v['ra']));//rad2deg
	$rb[$k]['dc']=maths::deg2dec(($v['dc']));//rad2deg
	$rb[$k]['ds']=round(((1/$v['parallax'])*3.261564*1000),2);//
	$rb[$k]['mag']=round($v['mag'],2);
	}//pc2al
if(!isset($rb))return lang('no result');
array_unshift($rb,['gid','ra','dec','dist (al)','mag']); //pr($r);
return tabler($rb);}

static function stream($p){
//$p['t']=self::$cols[0];
return parent::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){return parent::com($p);}
static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
//self::install();
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>