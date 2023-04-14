<?php

class forms extends appx{
static $private=1;
static $a='forms';
static $db='forms';
static $cols=['tit','txt','com','cl'];
static $typs=['var','bvar','var','int'];
static $db2='forms_vals';
static $cb='frm';
static $open=1;
static $tags=1;
static $qb='db';

function __construct(){
$r=['a','db','db2','cb','cols','qb'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create('forms_vals',['bid'=>'int','uid'=>'int','q1'=>'var','q2'=>'var','q3'=>'var','q4'=>'var','q5'=>'var','q6'=>'var','q7'=>'var','q8'=>'var','q9'=>'var'],1);}

static function admin($p){$p['o']='1'; return parent::admin($p);}
static function js(){return '';}
static function headers(){head::add('jscode',self::js());}

#sys
static function del($p){
$p['db2']='forms_vals';
return parent::del($p);}

static function save($p){
return parent::save($p);}

static function modif($p){
return parent::modif($p);}

#editor

static function fc_com($k,$val,$ty,$id){
return div(self::edit_form(['k'=>$k,'com'=>$val,'id'=>$id]));}

static function form($p){
$p['fccom']=1;
return parent::form($p);}

static function edit($p){
$p['collect']='forms_vals';
$p['help']='forms_com';
return parent::edit($p);}

static function collect($p){
return parent::collect($p);}

static function usave($p){$id=$p['id']??''; $rid=$p['rid']??''; $vrf='';
$ra=['q1','q2','q3','q4','q5','q6','q7','q8','q9']; $r=valk($p,$ra);
for($i=1;$i<=9;$i++)$vrf.=$r['q1'];
if(!$vrf)return bj(self::$cb.$id.'|forms,play|id='.$id,langp('registration failed'),'alert');
$rb=[$id,ses('uid'),$r['q1'],$r['q2'],$r['q3'],$r['q4'],$r['q5'],$r['q6'],$r['q7'],$r['q8'],$r['q9']];
$nid=sql::sav('forms_vals',$rb);
array_unshift($ra,'bid','uid');
$f=explorer::nod(self::$a,$id);
db::add($f,$rb,$ra);
return self::play($p);}

static function udel($p){$id=$p['id'];
sql::del('forms_vals',['bid'=>$id,'uid'=>ses('uid')]);
return self::play($p);}

static function sav_lead($p){$id=$p['id']??'';
$r=valk($p,['tit','txt','com']);
$r['com']=str_replace("\n",'',$r['com']);
if($id)sql::up2(self::$db,$r,$id);
else $bid=sql::sav(self::$db,[ses('uid'),$r['tit'],$r['txt'],$r['com'],'']);
return self::create($p);}

static function preview($p){$id=$p['id'];
return conn::com2($p['com'],'conn','form');}

static function edit_form($p){$com=str_replace("\n",'',val($p,'com')); $id=$p['id']??'';
if(!$com)$com=utf8enc('[label§title:input][label2§text:textarea]');//[form'.$id.'§'.ses('usr').':submit]
$ret=build::cbt('com',uns(form::ex(),'submit'));
$j='frmpw|forms,preview|id='.$id.'|com';
$ret.=div(textarea('com',$com,60,4,lang('fields'),'console','',$j));
$ret.=div(bj($j,langp('preview'),'btn'));//preview
$ret.=div(conn::com2($com,'conn','form'),'table','frmpw');
return $ret;}

static function create($p){$id=$p['id']??''; $rid=$p['rid']??''; $cb=self::$cb;
if($id)$r=sql('id,tit,txt,com,cl,dateup',self::$db,'ra',$id);
else $r=valk($p,['id','tit','txt','com','cl','date']);
$ret=bj($cb.'|forms,stream|id='.$id.',rid='.$rid,pic('back'),'btn');//back
$ret.=bj($cb.'|forms,sav_lead|id='.$id.',rid='.$rid.'|tit,txt,com',lang('save'),'btsav').br();
$ret.=input('tit',val($r,'tit'),28,lang('title')).br();
$ret.=textarea('txt',val($r,'txt'),60,4,lang('presentation')).br();//save
if($id)if(self::already($id))return $ret.br().br().help('form is not editable','alert');
$ret.=tag('h4','',lang('edit fields').' '.hlpbt('forms_com'));
$ret.=div(self::edit_form($r));
return $ret;}

/*static function template(){
return '[[(label)*class=cell:div][(field)*class=cell:div]*class=row:div]';}
static function vue($r){return gen::com2(self::template(),$r);}*/

static function ownanswer($p){$id=$p['id']??'';
$com=sql('com',self::$db,'v',$id);
conn::com2($com,'conn','form'); $rb=conn::$r['frm']??[]; $vars=implode(',',$rb);
$r=sql($vars,'forms_vals','ra',['uid'=>ses('uid'),'bid'=>$id]);
foreach($rb as $k=>$v)$rb[$k]=$r[$v];
return tabler($rb,0,1);}

static function already($id){
return sql('id','forms_vals','v','where uid="'.ses('uid').'" and bid='.$id);}

static function play($p){$id=$p['id']??''; $rid=$p['rid']??'';
if($id)$r=sql('id,tit,txt,com,cl,dateup',self::$db,'ra',$id);
if(!$r)return help('id not exists','paneb');
$ret=div($r['tit'],'tit').div($r['txt'],'txt');
if($r['cl'])$form=help('form closed','alert');
elseif(self::already($id)){
	$n=sql('count(id)',self::$db2,'v',['bid'=>$id]);
	$form=help('form_filled','valid');
	$form.=div(helpx('answered').' '.toggle('|forms,ownanswer|id='.$id,langp('answers'),'bold'));
	$form.=langnb('answer',$n,'btok').' ';
	$form.=bj(self::$cb.$id.'|forms,udel|id='.$id.',rid='.$rid,langp('remove'),'btdel');}
else{$form=div(conn::com2($r['com'],'conn','form'),'table');
$rb=conn::$r['frm']??[]; $n=count($rb); $vars=implode(',',$rb);
$form.=bj(self::$cb.$id.'|forms,usave|id='.$id.',rid='.$rid.'|'.$vars,langp('send'),'btsav');
}//send
return $ret.div($form);}

/*static function conn($p){//old version p:o:inp
$id=$p['id']??''; $rid=$p['rid']??''; $com=val($p,'com'); $cb=self::$cb.$id; if(!$com)return;
$r=explode('|',$com); foreach($r as $k=>$v)$r[$k]='['.$v.']';
$ret=self::vue($r);
$n=substr_count($com,','); for($i=1;$i<=$n;$i++)$vr[]='q'.$i; $vars=implode(',',$vr);
$ret.=div(bj($cb.'|forms,usave|id='.$id.',rid='.$rid.'|'.$vars,langp('send'),'btsav'));//send
return div($ret,'paneb',$cb);}*/

static function stream($p){
return parent::stream($p);}

#interfaces
static function tit($p){
$p['t']='tit';
return parent::tit($p);}

//call (read)
static function call($p){
return div(self::play($p),'paneb',self::$cb.$p['id']);}

//com (edit)
static function com($p){
return parent::com($p);}

//interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>