<?php
class gov extends appx{
static $private=0;
static $a='gov';
static $db='gov';
static $cb='mdl';
static $cols=['tit','txt','pub'];
static $typs=['var','bvar','int'];
static $conn=1;
static $gen=1;
static $db2='gov_vals';
static $open=0;
static $qb='db';

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'var'],1);//collect case
sql::create(self::$db2,['bid'=>'int','tit2'=>'var','txt2'=>'var'],1);//subcall case
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}

//injected javascript (in current page or in popups)
static function js(){return 'function barlabel(v,id){var d="";
	var r=["","broken","bad","works","good","new","","",""];
	inn(r[v],id);}';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
//collected datas from public forms
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){
$p['pub']=0;//default privacy
return parent::create($p);}

#subcall
//used in secondary database db2
static function subops($p){$p['t']='tit2'; return parent::subops($p);}//$p['bt']='';
static function subform($r){return parent::subform($r);}
static function subedit($p){$p['t']='tit2'; return parent::subedit($p);}//$p['data-jb']//2nd save
static function subcall($p){$p['t']='tit2'; return parent::subcall($p);}//$p['bt']='';

#form
//override appx field of form for col 'txt'
/*static function fc_txt($k,$val,$v){
return textarea($k,$val,40,strlen($val)>500?26:16,'','',$v=='var'?512:0);}*/

static function form($p){
//$p['html']='txt';//contenteditable for txt
//$p['fctxt']=1;//form col call fc_tit();
//$p['bttxt']=1;//label for txt;
//$p['barfunc']='barlabel';//js function for bar()
return parent::form($p);}

static function edit($p){//->form, ->call
//$p['collect']=self::$db2;//collected datas
$p['help']=1;//ref of help 'gov_edit'
$p['sub']=1;//active sub process (attached datas)
//$p['execcode']=1;//if edition is executable
//$p['bt']='';
return parent::edit($p);}

#build
static function build($p){
$ra=parent::build($p); $id=$p['id']??'';
$rb=sql('tit2,txt2',self::$db2,'phy',['bid'=>$id]);//'rr': assoc array, phy: prep for Phylo
return [$ra,$rb];}

static function template(){//return parent::template(); //use Genetics Template Motor
return '[[(tit)*class=tit:div][[txt:var]*class=txt:div]*class=paneb:div]';}//will nl2br()

#play (where to begin to code)
static function play($p){//->build, ->template
[$ra,$rb]=self::build($p); //pr($rb);
$ret=gen::com(self::template(),$ra);
//$ret.=gen::com2('[[(tit1)*class=tit:div][(txt2)*class=txt:div]*class=paneb:div]',$rb);
$ret.=phylo($rb,['stream'=>['tit'=>'tit2','txt'=>'txt2']]);//repeat all txt in div named 'list'
return $ret;}

static function stream($p){
//$p['t']=self::$cols[0];//used col as title
return parent::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];//used col as title
return parent::tit($p);}

static function call($p){//->play
return parent::call($p);}

#com (edit)
static function com($p){return parent::com($p);}//->content
static function uid($id){return parent::uid($id);}//author
static function own($id){return parent::own($id);}//owner (used to propose edition on apps)

#interface
static function content($p){//->stream, ->call
self::install();//hide this in prod
return parent::content($p);}

static function api($p){//callable datas
return parent::api($p);}
}