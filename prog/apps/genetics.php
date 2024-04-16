<?php
class genetics extends appx{
static $private=0;
static $a='genetics';
static $db='genetics';
static $cb='gnx';
static $cols=['tit','txt','pub'];
static $typs=['var','bvar','int'];
static $conn=0;
static $gen=1;
static $db2='genetics_vals';
static $open=0;
static $tags=0;
static $qb='';//db

//first col,txt,answ,com(settings),code,day,clr,img,nb,cl,pub
//$db2 must use col "bid" <-linked to-> id

static function install($p=''){
sql::create(self::$db2,['bid'=>'int','t2'=>'var','v1'=>'var','v2'=>'var','v3'=>'var','v4'=>'var','v5'=>'var'],1);
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
return parent::del($p);}

static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){$p['t']='t2'; return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){$p['t']='t2'; return parent::subcall($p);}
static function subform($r){$p['t']='t2'; return parent::subform($r);}

//form
//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
$p['help']=1;
$p['sub']=1;
//$p['execcode']=1;
return parent::edit($p);}

#build
static function build($p){
return parent::build($p);}

static function template(){
//$t='[genetics,play2|id='.$id.'*|'.$t2.':popup]';
return '[[[tit:var]*class=tit:div][[bt:var]*class=txt:div]*class=paneb:div]';}

static function play2($p){$db2=self::$db2;
$txt=sql('txt',self::$db,'v',$p['id']);
$r=sql(sql::cols($db2,2,1),$db2,'ra',$p['bid']); //pr($r); echo $txt;
return gen::com($txt,$r);}

static function play($p){
$a=self::$a; $id=$p['id']; $ret='';
$r=parent::build($p);
//$rb=parent::build2($p);
$rb=sql('id,t2',self::$db2,'rr',['bid'=>$id]);
if($rb)foreach($rb as $k=>$v)$ret.=bj('popup|genetics,play2|id='.$id.',bid='.$v['id'],$v['t2'],'linec');
$r['bt']=$ret;
$ret=gen::com(self::template(),$r);
return $ret;}

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
self::install();
return parent::content($p);}

static function api($p){
return parent::api($p);}
}
?>