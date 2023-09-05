<?php
class comic extends appx{
static $private=0;
static $a=__CLASS__;
static $db=__CLASS__;
static $cb='mdl';
static $cols=['tit','txt','clr','pub'];
static $typs=['var','bvar','svar','int'];
static $conn=1;
static $gen=1;
static $db2='comic_cases';
static $open=0;
static $tags=0;
static $qb='';//db

static function install($p=''){
sql::create(self::$db2,['bid'=>'int','row'=>'int','col'=>'int','colspan'=>'int','rowspan'=>'int','situation'=>'var','dialog'=>'var','img'=>'svar','clr'=>'svar'],1);
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
static function create($p){$p['help']=1; $p['pub']=0;//default privacy
return parent::create($p);}

#subcall
//used in secondary database db2
static function subops($p){$p['t']='situation'; return parent::subops($p);}//$p['bt']='';
static function subform($r){return parent::subform($r);}//$r['html']='txt2';
static function subedit($p){$p['t']='situation'; return parent::subedit($p);}//$p['data-jb']//2nd save
static function subplay($r){return div(build::editable($r,'admin_sql',['b'=>self::$db2],1),'','asl');}
static function subcall($p){$p['t']='situation';//$p['bt']=''; $p['collect']=self::$db2; $p['player']='subplay';
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
return parent::form($p);}

static function edit($p){//->form, ->call
//$p['collect']=self::$db2;//collected datas
$p['help']=1;//ref of help 'comic_edit'
$p['sub']=1;//active sub process (attached datas)
//$p['execcode']=1;//if edition is executable
//$p['bt']='';
return parent::edit($p);}

#build
static function build($p){
$ra=parent::build($p); $id=$p['id']??'';
$rb=sql('all',self::$db2,'rr',['bid'=>$id]);//'rr': for Gen or Vue, phy: for Phylo
return [$ra,$rb];}

static function template(){//return parent::template(); //use Genetics Template Motor
//return '[[[tit:var]*class=tit:div][[txt:gen]*class=txt:div]*class=paneb:div]';//use gen for txt
//return '[[[tit:var]*class=tit:div][[txt:html]*class=txt:div]*class=paneb:div]';//html content
return '[[(tit)*class=tit:div][[txt:var]*class=txt:div]*class=paneb:div]';}//will nl2br()

#play (where to begin to code)
static function play($p){//->build, ->template
[$ra,$rb]=self::build($p); //pr($rb);
$ret=''; $hd=200; $wd=600;
$rh=array_keys_r($rb,'row'); $h=max($rh); if($rb[$h]['rowspan'])echo $h+=$ra['rowspan']-1;
$rc=[]; foreach($rb as $k=>$v)$rc[$v['row']][$v['col']]=1;
$rd=[]; foreach($rc as $k=>$v)$rd[]=count($v);//nb of cols by row //pr($rd);
foreach($rb as $k=>$v){$txt='';
	if($v['situation'])$txt.=div($v['situation'],'big bkg');
	if($v['dialog'])$txt.=div(conn::com($v['dialog']),'bkg');
	$sz='grid-row:'.$v['row'].'; grid-column:'.$v['col'].'; ';
	//$sz.='width:'.($wd/$rd[$v['row']]).'%; ';
	//$sz.='height:'.($hd*val($v,'rowspan',1)).'px; ';
	if($v['colspan'])$sz.='grid-column-start:'.$v['col'].'; grid-column-end:'.($v['colspan']+1).'; ';
	if($v['rowspan'])$sz.='grid-row-start:'.$v['row'].'; grid-row-end:'.($v['colspan']+1).'; ';
	if($v['clr'])$sz.='background-color:#'.($v['clr']).'; color:#'.clrneg($v['clr'],1).'; ';
	if($v['img'])$sz.='background-image:url('.imgroot($v['img']).'); background-size:cover; ';
	$ret.=div($txt,'frame','',$sz);}
$ret=div($ret,'','','display:grid; grid-gap:8px;');
return $ret;}

//static function cover($id){}

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