<?php
class model2_collect extends appx{
static $private=0;
static $a=__CLASS__;
static $db='model';
static $cb='mdl';
static $cols=['tit','txt','pub'];
static $typs=['svar','bvar','int'];
static $conn=1;
static $gen=1;
static $tags=1;
static $db2='model_r';
static $open=1;
static $qb='db';

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'var'],1);//collect case
sql::create(self::$db2,['bid'=>'int','tit2'=>'var','txt2'=>'var'],0);//subcall case
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}

static function js(){return 'function barlabel(v,id){var d="";
	var r=["","broken","bad","works","good","new","","",""];
	inn(r[v],id);}';}
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
static function subform($p){return parent::subform($p);}
static function subedit($p){$p['t']='tit2'; return parent::subedit($p);}//$p['data-jb']
static function subcall($p){$p['t']='tit2'; return parent::subcall($p);}//$p['bt']='';

#form
/*static function fc_txt($k,$val,$v){
return textarea($k,$val,40,strlen($val)>500?26:16,'','',$v=='var'?512:0);}*/

static function form($p){
//$p['html']='txt';
//$p['fctxt']=1;
//$p['bttxt']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
$p['help']=1;
$p['sub']=1;
//$p['execcode']=1;
//$p['bt']='';
return parent::edit($p);}

#build
static function build($p){
$ra=parent::build($p); $id=$p['id']??'';
$rb=sql('all',self::$db2,'phy',['bid'=>$id]);
return [$ra,$rb];}

static function answer_sav($p){
$id=$p['id']??''; $txt=$p['txt2']??''; $opn=$p['opn']??'';
if($txt)$nid=sql::sav(self::$db2,[$id,ses('uid'),$txt]);
if($opn)return self::edit($p);
return self::play($p);}

static function answer_form($p){$id=$p['id']??''; $opn=$p['opn']??'';
$j=self::$cb.'|question,answer_sav|id='.$id.',opn='.$opn.'|txt2';
$ret=bj($j,langp('ok'),'btsav');
$ret.=divarea('txt2','','article');
return $ret;}

static function template(){
return '[[[txt:var]*class=txt:div]*class=paneb:div]';}//will nl2br()

static function template2(){
return '[[[txt2:var]*class=txt:div]*class=paneb:div]';}

#play
static function play($p){
[$ra,$rb]=self::build($p);
$ret=gen::com(self::template(),$ra);
$ret.=self::answer_form($p);
$ret.=gen::com2(self::template2(),$rb);
return $ret;}

//static function cover($id,$r=[]){}

static function stream($p){
//$p['t']=self::$cols[0];
//$p['cover']=1;
return parent::stream($p);}

#call
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
return parent::call($p);}

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