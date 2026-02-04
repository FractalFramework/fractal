<?php
//model2 use a second db, in a subcall (attached datas) ; see also apps/book or dev/chapter
//for an exemple using $db2 in collect mode, see apps/petition, doodle, freevote, pointer
class model2_full extends appx{
static $private=0;
static $a=__CLASS__;
static $db='model2';
static $cb='mdl';
static $cols=['tit','txt','pub'];
static $typs=['svar','bvar','int'];//var(100),var2(1000),var1(10),text,int,date
static $conn=1;//0,1(ptag),2(brut),no(br), while using 'txt'
static $gen=1;//use template motor, while using 'txt'
static $db2='model2_r';//second db, used in subcall or to collect datas
static $open=0;//open content, determine apps with db //1=open onplace,2=preview,3=iframe,4=link
static $home=0;//open in home
static $tags=1;//Specify if the App use Tags general system
static $qb='db';//associated nosql-table ; works with collected datas

//first col,txt,answ,com(settings),code,lang,day,clr,img,nb,cl,pub,edt
//$db2 must use col "bid" <-linked to-> id

/*known cols: (assume = logic devices)
- first col is actually used for title ['t']
- col "txt" (var2) will assume titles and md5 url
- col "txt" (var) will accept connectors ['conn'] or interpret templates ['gen']
- col "com" will assume settings
- col "day" is a date
- col "clr" is a color, with a colorpicker
- col "img" is a image, with a selector
- col "code" is for edit code
- col "nb" number 1-10
- col "nb1" number 1-100
- col "cl" mean close
- col "pub" will assume privacy
- col "edt" will assume editability
- col "lang" assume languages (need md5)
- col "md5" generate hash number
- col "idn" assume order
- col "num" assume order
- col "root" assume topology
- col "prm" will cause troubles
- col "hid" not editable*/

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
static function create($p){$p['pub']=0;//default privacy
return parent::create($p);}

#subcall
//used in secondary database db2
static function subops($p){$p['t']='tit2'; return parent::subops($p);}//$p['bt']='';
static function subform($p){return parent::subform($p);}//$r['html']='txt2';
static function subedit($p){$p['t']='tit2'; return parent::subedit($p);}//$p['data-jb']//2nd save
static function subplay($r){return div(build::editable($r,'admin_sql',['b'=>self::$db2],1),'','asl');}
static function subcall($p){$p['t']='tit2';//$p['bt']=''; $p['collect']=self::$db2; $p['player']='subplay';
return parent::subcall($p);}

#form
//override appx field of form for col 'txt'
/*static function fc_txt($k,$val,$v){
return textarea($k,$val,40,strlen($val)>500?26:16,'','',$v=='var'?512:0);}*/

static function form($p){
//$p['html']='txt';//contenteditable for txt
//$p['fctxt']=1;//form col call fc_tit();
//$p['bttxt']=1;//label for txt;
//$p['barfunc']='barlabel';//js function for bar()
//$p['labeltit']='title';//personalized label for col tit
//$p['jp']='preview|a=1';//'mth|prm to call for preview';
return parent::form($p);}

static function edit($p){//->form, ->call
//$p['collect']=self::$db2;//collected datas
$p['help']=1;//ref of help 'model2_edit'
$p['sub']=1;//active sub process (attached datas)
//$p['execcode']=1;//if edition is executable
//$p['bt']='';
return parent::edit($p);}

#build
static function build($p){
$ra=parent::build($p); $id=$p['id']??'';
$rb=sql('all',self::$db2,'phy',['bid'=>$id]);//'rr': for Gen or Vue, phy: for Phylo
return [$ra,$rb];}

static function template(){//return parent::template(); //use Genetics Template Motor
//return '[[[tit:var]*class=tit:div][[txt:gen]*class=txt:div]*class=paneb:div]';//use gen for txt
//return '[[[tit:var]*class=tit:div][[txt:html]*class=txt:div]*class=paneb:div]';//html content
return '[[(tit)*class=tit:div][[txt:var]*class=txt:div]*class=paneb:div]';}//will nl2br()

static function preview($p){return parent::preview($p);}

#play (where to begin to code)
static function play($p){//->build, ->template
[$ra,$rb]=self::build($p); //pr($rb);
$ret=gen::com(self::template(),$ra);
$ret.=phylo($rb,['stream'=>['tit'=>'tit2','txt'=>'txt2']]);//repeat all txt in div named 'list'
//$ret.=gen::com2(self::template2(),$rb);
return $ret;}

//static function cover($id,$r=[]){}

static function stream($p){
//$p['t']=self::$cols[0];//used col as title
//$p['cover']=1;//personalized icons
return parent::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];//used col as title
return parent::tit($p);}

static function call($p){//->play
return parent::call($p);}

#com (edit)
static function com($p){return parent::com($p);}//call from internal
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