<?php

class poll extends appx{
static $private=1;
static $a='poll';
static $db='poll';
static $cb='pll';
static $cols=['tit','txt','com','cl','day'];
static $typs=['var','bvar','var','int','date'];
static $tags=1;
static $open=1;
static $qb='db';

//install
static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create('poll_valid',['bid'=>'int','uid'=>'int','val'=>'int'],1);}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function headers(){
head::add('csscode','');}

#editor
static function del($p){$p['db2']='poll_valid'; return parent::del($p);}
static function modif($p){return parent::modif($p);}
static function save($p){return parent::save($p);}
static function form($p){$p['labelcl']='public_option'; return parent::form($p);}
static function create($p){return parent::create($p);}
static function edit($p){$p['collect']='poll_valid'; return parent::edit($p);}
static function collect($p){return parent::collect($p);}

#vote
static function vote($p){$id=$p['id'];//single
$idv=sql('id','poll_valid','v','where bid="'.$id.'" and uid="'.ses('uid').'"');
if(isset($idv))$p['val']=$p['val']!=$p['current']?$p['val']:'0';
if(!isset($idv))$idv=sql::sav('poll_valid',[$id,ses('uid'),$p['val']]);
else sql::upd('poll_valid',['val'=>$p['val']],$idv);
return self::play($p);}

static function vote2($p){$id=$p['id'];//multiple
$idv=sql('id','poll_valid','v','where bid="'.$id.'" and uid="'.ses('uid').'"');
if(isset($idv))$p['val']=$p['val']!=$p['current']?$p['val']:'0';
if(!isset($idv))$idv=sql::sav('poll_valid',[$id,ses('uid'),$p['val']]);
else sql::upd('poll_valid',['val'=>$p['val']],$idv);
return self::play($p);}

static function formcompub($p){
$id=$p['id']; $ok=$p['fcp'.$id]??'';
if($ok){$com=sql('com',self::$db,'v',$id); sql::upd(self::$db,['com'=>$com.'|'.$ok],$id);}
return self::play($p);}

static function pane($rb,$rs,$i,$sum,$closed,$vote,$com){$ret='';
$answ=$rb[$i]; $score=$rs[$i]??0;
$pic=$vote==$i?ico('square'):ico('square-o');
$size=$sum&&$score?round($score/$sum*100):0;
$answer=$pic.' '.$answ.' '.span('('.langnb('vote',$score).')','small',1);
$grad='linear-gradient(to right,rgba(34,122,217,0.7) '.$size.'%,rgba(34,122,217,0.1) '.$size.'%)';
$ret=div($answer,'pollbar','','background-image:'.$grad);
if(!$closed)$ret=bjlog($com.',val='.$i,$ret);//modif
return $ret;}

static function build($p){$id=$p['id']??'';
return sql('tit,txt,com,cl,day',self::$db,'rw',$id);}

static function play($p){$id=$p['id']; $ret='';
$r=self::build($p); if(!$r)return;
[$tit,$txt,$answers,$cl,$end]=$r;
$ret=div(nl2br($txt?$txt:$tit),'tit');
$vote=sql('val','poll_valid','v','where bid="'.$id.'" and uid="'.ses('uid').'"');
$rs=sql('val','poll_valid','kad','where bid="'.$id.'" order by val');//all votes
$sum=array_sum($rs); $rb=explode('|',$answers);
$nb=count($rb); array_unshift($rb,'null');
$endtime=strtotime($end);
$leftime=ses('time')-$endtime;
if($leftime>0)$closed=1; else $closed=0;
if(val($p,'adm'))$closed=1;//$closed=$vote?1:0;
//vote buttons
$com='poll'.$id.'|poll,vote|id='.$id.',current='.$vote;
for($i=1;$i<=$nb;$i++)$ret.=self::pane($rb,$rs,$i,$sum,$closed,$vote,$com);
//fcom
if($cl && !$closed && ses('uid')){
	$j='poll'.$id.'|poll,formcompub|id='.$id.'|fcp'.$id;
	$ret.=div(inputcall($j,'fcp'.$id,'','',lang('add_option'),'edit'),'pollinp');}
//footer
//$foot=span($sum.' '.langs('vote',$sum,1),'nfo',1).' ';
if($closed)$state=lang('poll closed');
else $state=lang('time left').' : '.build::leftime($endtime);
$foot=span($state,'grey');
return div(div($ret,'').div($foot),'','poll'.$id);}

#call
static function tit($p){
return parent::tit($p);}

static function stream($p){
return parent::stream($p);}

static function call($p){
return parent::call($p);}

//com (edit)
static function com($p){
return parent::com($p);}

//interface
static function content($p){
//self::install();
return parent::content($p);}
}

?>