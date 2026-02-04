<?php
class randomizer extends appx{
static $private=0;
static $a=__CLASS__;
static $db='randomizer';
static $cb='rnd';
static $cols=['tit','max'];
static $typs=['svar','int'];
static $conn=0;
static $gen=0;
static $tags=0;
static $db2='randomizer_r';
static $open=1;
static $qb='db';

static function install($p=''){
//sql::create(self::$db2,['bid'=>'int','uid'=>'int','val'=>'var'],1);//collect case
sql::create(self::$db2,['bid'=>'int','nb'=>'int'],1);//subcall case
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}

static function js(){$id='';
return '
function batchtime(){
	ajx("div,'.self::$cb.'|randomizer,addrand|id='.$id.'");
	x=setTimeout("batchtime()",3000);}
//setTimeout("batchtime()",10);
function playstop(){if(typeof x!="undefined")clearTimeout(x);}
';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

//$bt=btj(pic('play'),atj('setTimeout',['batchtime()',10]),'btn').' ';
//$bt.=btj(pic('stop'),'playstop()','btn').' ';

#edit
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){$ret=parent::modif($p); self::play($p,1); return $ret;}
static function create($p){$p['pub']=0; $p['tit']=date('ymdHi'); return parent::create($p);}

#subcall
static function subops($p){$p['t']='nb'; return parent::subops($p);}//$p['bt']='';
static function subform($p){$p['fcnb']=1; return parent::subform($p);}
static function subedit($p){$p['t']='nb';
$cb=self::$cb.$p['id'].'edit';
$p['bt']=bj($cb.'|randomizer,addrand1|id='.$p['id'].',max='.$p['max'],langp('generate_number'),'btn');
return parent::subedit($p);}//$p['data-jb']
static function subcall($p){$p['t']='nb'; return parent::subcall($p);}//$p['bt']

#form
static function fc_nb($k,$val,$v){
$bt=bj($k.'|randomizer,generate1',langp('generate_number'),'btn').br();
return $bt.input($k,$val,9,$k,lang($k),12);}

static function form($p){
//$p['html']='txt';
//$p['fctxt']=1;
//$p['bttxt']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
//$p['collect']=self::$db2;
$p['max']=sql('max',self::$db,'v',$p['id']);
$p['help']=1;
$p['sub']=1;
//$p['execcode']=1;
$cb=self::$cb.$p['id'];
$p['bt']=bj($cb.'edit|randomizer,addrand2|id='.$p['id'].',max='.$p['max'],langp('generate_numbers'),'btn');
return parent::edit($p);}

#generate
static function generate1($p){$n=$p['max']??100; return rand(0,$n);}
static function addrand1($p){sql::sav(self::$db2,[$p['id'],self::generate1($p)]); return self::edit($p);}
static function generate2($p){$ret=''; $n=100; for($i=0;$i<$n;$i++)$r[]=[$p['id'],self::generate1($p)]; return $r;}
static function addrand2($p){sql::sav2(self::$db2,self::generate2($p)); return self::edit($p);}

#build
static function build($p){
$ra=parent::build($p); $id=$p['id']??'';
$rb=sql('id,nb',self::$db2,'rr',['bid'=>$id]);
return [$ra,$rb];}

static function draw($p,$ra,$rb){$rc=[]; $ret='';
$rp=['typ'=>'lines','dk'=>0,'dv'=>0,'ad'=>1,'lb'=>0,'fit'=>1]; //p($rb);
if($rb)foreach($rb as $k=>$v)$rc[]=[$k,$v['nb']?$v['nb']:0];
$rt=implode_r($rc,"\n",','); $rp['com']=$rt; $rp['t']='randomizer'.$p['id']; //p($rp);
if($rt)$ret=graphs::call($rp); //echo svg::save($rp);//saved by graph, need $rp['cod']=graph::build($rt);
return $ret;}

static function cache($p){
$f='img/svg/randomizer'.$p['id'].'.svg';
if(file_exists($f))return $f;}

#play
static function play($p,$x=''){
$f=self::cache($p); if($f && !$x)return img('/'.$f.'?'.randid());
[$ra,$rb]=self::build($p);
return self::draw($p,$ra,$rb);}

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