<?php
class chronology extends appx{
static $private=0;
static $a=__CLASS__;
static $db='chronology';
static $cb='chrn';
static $cols=['tit','com','pub'];
static $typs=['svar','svar','int'];
static $open=1;//1=open,2=preview,3=iframe,4=link
static $conn=1;
static $gen=0;
static $db2='';//chronology_r
static $tags=0;
static $qb='';//db
static $w=620;

//first col,txt,answ,com(settings),code,lang,day,clr,img,nb,cl,pub,edt
//$db2 must use col "bid" <-linked to-> id

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'var'],1);//id,...,day
parent::install(array_combine(self::$cols,self::$typs));}//id,uid,...,day

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){
//$p['db2']=self::$db2;
return parent::del($p);}

static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subform($p){return parent::subform($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}

//form
//static function fc_tit($k,$v){}
static function form0($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['bttxt']=1;
//$p['barfunc']='barlabel';
//$p['labeltit']='title';
return parent::form($p);}

static function form($p){$ret=''; $edt=''; $f='';//$cb=self::$cb;
$r=valk($p,self::$cols); $id=$p['id']??''; $uid=$p['uid']??ses('uid');
$ret=div(input('tit',$r['tit'],'44',lang('title')));
if($id)$f=self::nod($id,$uid);
if($f){$ex=db::ex($f,1); if(!$ex)db::save($f,[[date('Y-m-d'),'hello']],['date','text'],'');}
$edt=explorer::play(['f'=>$f,'x'=>1]);
$ret.=div($edt,'','fcb');
$ret.=hidden('pub',$r['pub']);
return div($ret);}

static function edit($p){
//$p['collect']=self::$db2;
//$p['help']=1;
//$p['sub']=1;
//$p['execcode']=1;
//$p['bt']='';
return parent::edit($p);}

#build
static function build($p){
return parent::build($p);}

static function template(){
//return parent::template();
return '[[{tit}*class=tit:div][{txt}*class=txt:div]*class=article:div]';}

#play
static function nod($id,$uid=''){//explorer::nod($app,$id)
if(!$uid && is_numeric($id))$uid=sql('uid',self::$db,'v',$id);
if($uid){$nm=usrid($uid); return 'usr/'.$nm.'/chronology/'.$id.'.php';}}

static function algo($p,$r,$rb){$rt=[]; $xa=0; $xb=0; //pr($rb);
$rd=[]; foreach($rb as $k=>$v)$rd[$k]=mktime(0,0,0,1,1,$v[0]); //pr($rd);
$min=min($rd); $max=max($rd); $diff=abs($max-$min); $w=self::$w; $ratio=$w/$diff;
$rp=[]; foreach($rd as $k=>$v)$rp[$k]=round($v*$ratio); //pr($rp);
$rc=[]; foreach($rb as $k=>$v)$rc[$k]=($rp[$k+1]??$rp[$k]+30)-$rp[$k]; //pr($rc);
foreach($rb as $k=>$v){$dt=$rd[$k]; $ti=$v[0]; $tx=$v[1]; $x=abs($rc[$k]); $xm=abs($x/2); $xb+=$x;
	$rt[]=[$xa,$x,$ti,$tx,$dt,$xm,$xb]; $xa=$x;}
return $rt;}

static function render0($r){$ret=''; $s='border:1px solid black; padding:6px; min-height:30px; ';
foreach($r as $k=>[$xa,$x,$ti,$tx,$dt,$xm])$ret.=div($dt.': '.$ti,'','',$s.'height:'.$x.'px;');
return div($ret,'');}

static function render($r,$id){$w=self::$w; $h=200; //pr($r);
$t=self::$a.'-'.$id; svg::init($w,$h,$t,1); $rc=svg::$clr_graph; //$n=count($r); $rc=svg::clrs($n); 
foreach($r as $k=>[$xa,$x,$ti,$tx,$dt,$xm,$xb]){$st=svg::len($tx);
svg::rect($xb-$x,0,$x,$h,$rc[$k]); $rot='';
$mid=$xb-$xm-($st/2); if($x<60)$rot=',,,,,rotate(270 '.($xb-$xm+2).'/'.($h/2).')';
//svg::text(12,$mid,$h/2,$tx,'white');//
svg::$ret[]='[white,'.$rot.':attr]['.$mid.','.($h/2).',,*'.($tx?$tx:'O').':text]';
svg::text(12,$xb-$x+2,$h-16,$ti?$ti:'O','black');}
return svg::com('',$w,$h,$t);}

static function play($p){
$r=self::build($p); $a=self::$a;
$f=self::nod($p['id'],$r['uid']);
$rb=db::read($f);
//self::$w=660;
$ra=self::algo($p,$r,$rb);
if($rb)return self::render($ra,$p['id']);}

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
self::install();//
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>