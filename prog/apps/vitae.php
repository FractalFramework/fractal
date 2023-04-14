<?php
class vitae extends appx{
static $private=0;
static $a='vitae';
static $db='vitae';
static $cb='mdl';
static $cols=['fullname','profession','idcard','pub'];
static $typs=['var','var','int','int'];
static $conn=0;
static $gen=0;
static $db2='vitae_vals';
static $tags=1;
static $open=0;
static $qb='db';

function __construct(){
$r=['a','db','cb','cols','db2','conn'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
$r=['bid'=>'int','year'=>'date','job'=>'var','company'=>'var','description'=>'var','tech'=>'var'];
sql::create(self::$db2,$r,1);
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
static function subops($p){$p['t']='company'; return parent::subops($p);}//$p['bt']='';
static function subform($r){return parent::subform($r);}
static function subedit($p){$p['t']='company'; return parent::subedit($p);}//$p['data-jb']//2nd save
static function subcall($p){$p['t']='company'; return parent::subcall($p);}//$p['bt']='';

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
$p['help']=1;//ref of help 'vitae_edit'
$p['sub']=1;//active sub process (attached datas)
//$p['execcode']=1;//if edition is executable
return parent::edit($p);}

#build
static function build($p){
$ra=parent::build($p); $id=$p['id']??'';
//$rb=parent::build2($p);
$rb=sql('job,description',self::$db2,'phy',['bid'=>$id]);//'rr': assoc array, phy: prep for Phylo
return [$ra,$rb];}

static function template(){//return parent::template(); //use Genetics Template Motor
return '[[(fullname)*class=tit:div][[profession:var]*class=txt:div]*class=paneb:div]';}//will nl2br()

#play (where to begin to code)
static function play($p){//->build, ->template
[$ra,$rb]=self::build($p); //pr($ra);
$ret=gen::com(self::template(),$ra);
$ret.=phylo($rb,['stream'=>['tit'=>'job','txt'=>'description']]);//repeat all txt in div named 'list'
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
?>