<?php
class petition extends appx{
static $private=1;
static $a='petition';
static $db='petition';
static $db2='petition_vals';
static $cb='ptwrp';
static $cols=['tit','txt','cl','pub'];
static $typs=['var','bvar','int','int'];
static $tags=1;
static $open=1;
static $qb='db';

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create(self::$db2,['bid'=>'int','uid'=>'int'],1);}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function js(){
return '';}

static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#editor
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function modif($p){return parent::modif($p);}
static function save($p){return parent::save($p);}
static function create($p){return parent::create($p);}
static function form($p){return parent::form($p);}
static function edit($p){$p['collect']=self::$db2; return parent::edit($p);}
static function collect($p){return parent::collect($p);}

static function sign($p){$id=$p['id']??'';
$r=[$id,ses('uid')];
$nid=sql::sav(self::$db2,$r);
$f=explorer::nod(self::$a,$id);
db::add($f,$r,['bid','uid']);
return self::play($p);}

static function unsign($p){$id=$p['id']??''; $vrf='';
sql::del(self::$db2,['bid'=>$id,'uid'=>ses('uid')],'',1);
return self::play($p);}

static function answers($p){$id=$p['id']??''; $ret='';
$r=sql::inner('name,dateup',self::$db2,'login','uid','rr','where bid='.$id);
if($r)$ret=div(count($r).' '.lang('signatures'),'valid');
if($r)array_unshift($r,[lang('user'),lang('date')]);
return $ret.tabler($r);}

static function already($id){
return sql('id',self::$db2,'v','where uid='.ses('uid').' and bid='.$id);}

static function play($p){$id=$p['id']??'';
$rid=$p['rid']??''; $nb=''; $cancel=''; $cc=self::$cb.$id;
if($id){$r=sql('id,tit,txt,cl,dateup',self::$db,'ra',$id);
	$n=sql('count(id)',self::$db2,'v','where bid='.$id);}
$ret=div($r['tit'],'tit').div($r['txt'],'txt');
if($n)$nb=' '.langnb('signature',$n,'btok');
if($r['cl'])$bt=help('petition closed','alert');
elseif(self::already($id)){
	$cancel=bj($cc.'|petition,unsign|id='.$id,langp('remove'),'btdel');
	$bt=div(ico('check').' '.helpx('petition_filled'),'valid');}
else $bt=bj($cc.'|petition,sign|id='.$id.',rid='.$rid,langp('sign'),'btsav');
$ret.=div($bt.$nb.$cancel);
return div($ret,'paneb');}

static function template(){
return '[[[_date*class=date:span] _tit _bt _insert _answ*class=tit:div][_txt*class=txt:div]*class=menu:div]';}

static function stream($p){
return parent::stream($p);}

#interfaces
static function tit($p){
return parent::tit($p);}

//call (read)
static function call($p){
return parent::call($p);
return div(self::play($p),'',self::$cb.$p['id']);}

//com (edit)
static function com($p){
return parent::com($p);}

//interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>