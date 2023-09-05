<?php
class contacts extends appx{
static $private=0;
static $a='contacts';
static $db='contacts';
static $cb='mdl';
static $cols=['fullname','project','mail','phone','web','address','tags','txt','pub'];
static $typs=['var','var','var','var','var','var','var','var','int'];
static $conn=0;
static $db2='contacts_vals';
static $open=0;
static $tags=0;
static $qb='db';

static function install($p=''){
sql::create(self::$db2,['bid'=>'int','fullname'=>'svar','organisation'=>'svar','mail'=>'svar','phone'=>'svar','web'=>'svar','address'=>'svar','tags'=>'svar','txt'=>'bvar'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}
static function js(){return '';}
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
//$p['help']='contacts_edit';
//$p['sub']=1;
return parent::edit($p);}

#build
static function build($p){
return parent::build($p);}

static function template(){
//return parent::template();
return '[
	[(fullname)*class=cstitle:div]
	[[(project)*class=csfunction:div]
	[(mail)*class=csname:span]:div]
	[[(phone):a]*class=cssite:div]
	[[(web):a]*class=cssite:div]
	[[(address):a]*class=cssite:div]
	[[(tags):a]*class=cssite:div]
	[(txt)*class=csinfos:div]
*class=paneb cscard:div]';}

static function play($p){
$r=self::build($p);
$r['web']=http($r['web']);
$template=self::template();
$ret=gen::com($template,$r);
return $ret;}

static function listing($id,$v=''){
//=vals($v['r'],['fullname','project','mail']); pr($v['r']);
[$nm,$pj,$mail]=sql('fullname,project,mail',self::$db,'rw',$v['id']);
return span($nm.' '.$pj.' '.$mail,'licon');}

static function stream($p){
//$p['t']=self::$cols[0];
$p['listing']=1;
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