<?php
class site extends appx{
static $private=0;
static $a='site';
static $db='site';
static $cb='ste';
static $cols=['tit','txt','pub'];
static $typs=['var','bvar','int'];
static $conn=0;
static $gen=1;
static $db2='site_r';
static $tags=0;
static $open=1;
static $qb='';

static function install($p=''){
sql::create(self::$db2,['bid'=>'int','tit2'=>'svar','app'=>'svar','ida'=>'int'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}

static function js(){return '';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){$p['pub']=0; return parent::create($p);}

#subcall
static function subops($p){$p['t']='tit2'; return parent::subops($p);}//$p['bt']='';
static function subform($r){return parent::subform($r);}
static function subedit($p){$p['t']='tit2'; return parent::subedit($p);}//$p['data-jb']
static function subcall($p){$p['t']='tit2'; return parent::subcall($p);}//$p['bt']='';

#form
static function fc_app($k,$val,$v){
$r=applist::pub();
return datalist($k,$r,$val,16,$k);}

static function form($p){
//$p['html']='txt';
$p['fcapp']=1;
//$p['bttxt']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
$p['collect']=self::$db2;
$p['help']=1;
$p['sub']=1;
//$p['execcode']=1;
//$p['bt']='';
return parent::edit($p);}

#usredit
static function sav($p){
$r=valk($p,['bid','tit2','app','ida']);
sql::sav(self::$db2,$r);
return self::call($p);}

static function add($p){$ret='';
$r=desktop::build(['bid'=>$p['bid'],'cuid'=>ses('uid'),'dir'=>'/documents','combine'=>2]); //p($r);
foreach($r as $k=>$v){
	$app=strto($v[2],strpos($v[2],',')?',':'|'); $ida=strend($v[2],'='); $t=ico($v[3]).$v[4];
	if(is_img($app)){$app='images'; $ida=sql('id','images','v',['img'=>$ida]);}
	$ret.=bj('cbck|site,sav|bid='.$p['bid'].',tit2='.$v[4].',app='.$app.',ida='.$ida,$t);}
return div($ret,'list');}

static function del2($p){
sql::del(self::$db2,$p['bid']);
return self::call($p);}

static function remove($p){$ret='';
[$ra,$rb]=self::build($p); //p($rb);
foreach($rb as $k=>$v)
	$ret.=bj('cbck|site,del2|bid='.$v['id'],$v['tit2']?$v['tit2']:'#'.$v['id'],'del');
return div($ret,'list');}

#build
static function build($p){
$ra=parent::build($p); $id=$p['id']??'';
$rb=sql('all',self::$db2,'rr',['bid'=>$id,'_order'=>'up desc','_limit'=>100]);
return [$ra,$rb];}

static function template(){
return '[[txt:var]*class=tit bloc_content:div]';}//will nl2br()

static function template2(){
return '[[[txt2:conn]*class=txt:div]*class=paneb:div]';}

#play
static function play($p){
[$ra,$rb]=self::build($p); $ret='';
//if(self::$conn && isset($ra['txt']))$ra['txt']=conn::com($ra['txt']);
//if(self::$conn && isset($rb['txt2']))$rb['txt2']=conn::com($rb['txt2']); p($rb);
if($ra['txt'])$ret=gen::com(self::template(),$ra); //p($rb);
//$ret.=gen::com2(self::template2(),$rb);
//$ret=$ra['txt']??'';
foreach($rb as $k=>$v){$a=$v['app'];
	if(class_exists($a))$q=new $a;
	//$ret.=conn::app($v['app'],$v['ida'],$v['tit2'],'');
	if($q)$ret.=div($q::preview(['id'=>$v['ida'],'t'=>$v['tit2']]),'bloc_content');}
return $ret;}

//static function cover($id){}
static function stream($p){
//$p['t']=self::$cols[0];
//$p['cover']=1;
return parent::stream($p);}

#call
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
$id=$p['id']??''; $uid=$p['uid']??ses('uid'); $bt='';
if(!$id)$p['id']=sql('id',self::$db,'v',['uid'=>$uid,'pub'=>3,'_limit'=>1]);
if($uid==ses('uid')){$bt=bubble('site,add|bid='.$p['id'],langp('add'),'btn');
	$bt.=bubble('site,remove|id='.$p['id'],langp('remove'),'btn');}
return div(div($bt,'right').parent::call($p),'','cbck');}

#com
static function com($p){return parent::com($p);}
static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
self::install();
//$bt=inputcall(self::$cb.'|'.self::$a.',,call||inp1','inp1','value1','','1');
//return $bt.div('','',self::$cb);
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>