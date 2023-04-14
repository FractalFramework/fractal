<?php

//application not based on appx
class ajxtst{
static $private=0;
static $a=__CLASS__;
static $db='ajxtst';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='ajt';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??''; return [];//!
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p,$r){$ret=''; //pr($r);
if($r)foreach($r as $k=>$v){$rb=[];
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
return $ret;}

#call
static function call($p){$ret='';
//$r=self::build($p);
//$ret=self::play($p,$r);
pr($p);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){}

static function form1($p){
$j=self::$cb.'|'.self::$a.',call||p1,p2,p3,p4';
$ret=textarea('p1','',60,4);
$ret.=inputcall($j,'p2',$p['p1']??'',32);
$ret.=bj($j,langp('ok'),'btn');
return $ret;}

static function form2($p){$p1=$p['p1']??'';
$j=self::$cb.'|'.self::$a.',call||p1,p2,p3,p4';
return form::call(['p1'=>['inputcall',$p1,'url',$j],['submit',$j,'ok','']]);}

static function form3($p){
$j=self::$cb.'|ajxtst,call||';
$r=[['type'=>'text','name'=>'tit','id'=>'tit','value'=>'title','label'=>'tit'],
['type'=>'textarea','name'=>'txt','id'=>'txt','value'=>'text','label'=>'txt'],
['type'=>'checkbox','name'=>'check','id'=>'check1','value'=>'chk1','label'=>'chk1'],
['type'=>'checkbox','name'=>'check','id'=>'check2','value'=>'chk2','label'=>'chk2'],
['type'=>'radio','name'=>'radio','id'=>'radio1','value'=>'rad1','label'=>'rad1'],
['type'=>'radio','name'=>'radio','id'=>'radio2','value'=>'rad2','label'=>'rad2'],];
return mkform($r,$j);}

static function form4($p){
$j=self::$cb.'|ajxtst,call||p1,p2,p3,p4'; $p1=$p['p1']??'';
return form::call(['p1'=>['input',$p1,'url',''],'p2'=>['radio','one','choice1',[1=>'one',2=>'two']],'p3'=>['checkbox','one','choice2',['one','two']],'p4'=>['select','one','choice3',['one','two']],['submit',$j,'ok','']],$j,'rows');}

static function form5($p){
$j=self::$cb.'|ajxtst,call||';
$r=json_decode(self::ex(),true);
return mkform($r,$j);}

static function ex(){return '[
{"type":"text","name":"tit","id":"tit","value":"title","label":"tit"},
{"type":"textarea","name":"txt","id":"txt","value":"text","label":"txt"},
{"type":"checkbox","name":"check","id":"check1","value":"chk1","label":"chk1"},
{"type":"checkbox","name":"check","id":"check2","value":"chk2","label":"chk2"},
{"type":"radio","name":"radio","id":"radio1","value":"rad1","label":"rad1"},
{"type":"radio","name":"radio","id":"radio2","value":"rad2","label":"rad2"}
]';}

static function menu2($p){
$j=self::$cb.'|ajxtst,call||';
$rj=self::ex();
return textarea('rj',$rj,40);}

static function menu($p){
//$ret=self::form1($p);
//$ret=self::form2($p);
//$ret=self::form3($p);
$ret=self::form4($p);
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??''; $ret='';
$bt=self::menu($p);
//$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>