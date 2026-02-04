<?php
class images extends appx{
static $private=0;
static $a='images';
static $db='images';
static $cb='mg';
static $cols=['tit','img','hid','pub'];
static $typs=['svar','svar','svar','int'];
static $conn=1;
static $gen=0;
//static $db2='images_vals';//sub
static $open=0;
static $tags=0;
static $qb='';//db

//first col,txt,answ,com(settings),code,lang,day,clr,images,nb,cl,pub,edt
//$db2 must use col "bid" <-linked to-> id

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
static function collect($p){return parent::collect($p);}
static function del($p){
//$p['db2']=self::$db2;
$nm=sql('img',self::$db,'v',$p['id']);
$f='img/full/'.$nm; if(is_file($f))unlink($f); //echo span($f,'alert');
$f='img/mini/'.$nm; if(is_file($f))unlink($f);
$f='img/medium/'.$nm; if(is_file($f))unlink($f);
sql::del('desktop',$nm,'com');
return parent::del($p);}

static function save($p){saveimg($p['img'],'img','',''); return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subform($r){return parent::subform($r);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}

//form
//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['bttxt']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
//$p['help']=1;
//$p['sub']=1;
//$p['execcode']=1;
return parent::edit($p);}

#build
static function build($p){
return parent::build($p);}

static function template(){
//return parent::template();
return '[[(img):img][[tit:var]*class=txt:div]*class=paneb:div]';}

#play
static function play($p){
//$r=self::build($p);
return parent::play($p);}//will use template

static function cover($id,$v=[]){
$nm=sql('img',self::$db,'v',$id); $f=imgroot($nm,'mini',1);
if(!is_file($f))$im=ico('image',36); else $im=img('/'.$f);
return $im;
return bj('mg|images,edit|id='.$id,span($im).span($v['bt']));}

static function stream($p){
//$p['t']=self::$cols[0];
$p['cover']=1;
return parent::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
return parent::call($p);}

static function com($p){
return parent::com($p);}

#com (edit)
//static function com($d){return playimg($d,'full');}
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