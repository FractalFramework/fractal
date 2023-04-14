<?php

class ideas extends appx{
static $private=0;
static $a='ideas';
static $db='ideas';
static $cb='dea';
static $cols=['tit','txt','cl'];
static $typs=['var','bvar','int'];
static $db2='ideas_args';
static $db3='ideas_valid';
static $tags=1;
static $open=1;

function __construct(){
$r=['a','db','cb','cols','db2'];
foreach($r as $v)parent::$$v=self::$$v;}

//install
static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create(self::$db2,['bid'=>'int','uid'=>'int','txb'=>'var'],1);
sql::create(self::$db3,['cid'=>'int','uid'=>'int','val'=>'int'],1);}

static function admin($p){$p['o']='1'; return parent::admin($p);}
static function js(){return;}
static function headers(){head::add('csscode','');}

#sys
static function del($p){return parent::del($p);}//$p['db2']=self::$db2;
static function modif($p){return parent::modif($p);}
static function save($p){return parent::save($p);}
static function create($p){$p['help']=1; return parent::create($p);}

#editor
static function form($p){return parent::form($p);}
static function edit($p){return parent::edit($p);}

//generics
private static function security($table,$id){
$uid=sql('uid',$table,'v',$id);
if($uid==ses('uid'))return 1;}

#argument
static function argumentDel($p){$bid=$p['bid']; $rid=self::$cb.$p['id'];
if(!self::security(self::$db2,$bid))return;
if(!val($p,'ok'))return bj($rid.'|ideas,argumentDel|ok=1,'.prm($p),langp('really?'),'btdel');
//.bj($rid.'|ideas,arguments|'.prm($p),langp('cancel'),'btn')
sql::del(self::$db2,$bid);
sql::del(self::$db3,$bid,'cid');
return self::play($p);}

static function argumentSave($p){
$r=[$p['id'],ses('uid'),$p['addarg']];//p($r);
$p['cid']=sql::sav(self::$db2,$r);
return self::play($p);}

static function argumentedit($p){$id=$p['id'];
$ret=input('addarg',lang('add proposition'),'52',1);
$ret.=bj(self::$cb.$id.'|ideas,argumentSave|id='.$id.'|addarg',langp('save'),'btsav');
return $ret;}

//algo
static function algo($r){
foreach($r as $k=>$rb)$ret[$k]=count($rb);
//arsort($ret);//pr($ret);
return $ret;}

//poll
static function lead_save($p){$v=val($p,'v');
$id=sql('id',self::$db3,'v','where uid='.ses('uid').' and cid='.$p['bid']);
if(!$id)sql::sav(self::$db3,[$p['bid'],ses('uid'),1]);
else sql::up(self::$db3,'val',$v==1?0:1,$id);
return self::play($p);}

static function barlevel($p){
[$sum,$score,$cl,$bid,$vot,$t,$bt,$btv]=vals($p,['sum','score','cl','bid','vot','txt','bt','btvot']);
$size=$sum&&$score?round($score/$sum*100):0;
$bar=div('','bartensor '.($vot?'active':''),'','width:'.$size.'%;";');
$bar.=div($t,'bartxt');
$bar.=div($size.'% '.$btv,'barscore');
$ret=div($bar,'barwrap');
$ret.=div($bt,'barbt');
return div($ret,'barline');}

#proposition
static function proposition($p){$ret=''; $bt=''; $vot='';//p($p);
$id=$p['id']??''; $bid=$p['bid']??''; $cl=val($p,'cl');
//if(!isset($id) && $bid)$id=sql('bid',self::$db2,'v',$bid);
$cols='name,txb'; $w='where ideas_args.id='.$bid;//ideas_args.up as date
$ra=sql::join($cols,self::$db2,'login','uid','ra',$w); //pr($ra);
//$cols='count(ideas_valid.id) as nb'; $w='where val=1 and cid='.$bid;
//$nb=sql($cols,self::$db3,'v',$w.''); //pr($rb);
if(ses('uid'))$vot=sql('val',self::$db3,'v','where uid='.ses('uid').' and cid='.$bid);
$p['voted']=$vot;
if($ra['name']==ses('usr') && !$cl)
	$p['bt']=bubble('ideas,argumentDel|bid='.$bid.',id='.$id,pic('delete'),'');
//if(!$cl)$p['score']=$nb;
$c=$vot?'active':''; $rid=self::$cb.$id;
if(!$cl && ses('uid'))$p['btvot']=bj($rid.'|ideas,lead_save|id='.$id.',bid='.$bid.',v='.$vot,pic('accept'),$c);//5*
//$usr=self::usrnfo($ra['name'],$ra['date']);
//$usr=small(lang('by').' '.$ra['name']).' ';
$usr=bubble('profile,call|usr='.$ra['name'].',sz=small',$ra['name'],'grey small',1);
static $i; $i++;
$by=$usr.' #'.$i;
$txt=voc($ra['txb'],'ideas_arg-txb-'.$bid); if($txt)$txt=nl2br($txt);
$p['txt']=$by.' - '.$txt;
$ret=self::barlevel($p);//pr($p);
return $ret;}

#play
static function play($p){$db=self::$db;
$id=$p['id']??''; $p['cid']=val($p,'o'); $content=''; $add=''; if(!$id)return;
$cols='name,txt,cl,'.$db.'.up as date';
$r=sql::inner($cols,$db,'login','uid','ra','where '.$db.'.id='.$id); //pr($r);
$p['cl']=$r['cl']; //$r=['name','txt','cl','date]
//$by=self::usrnfo($r['name'],$r['date']);
$by=bubble('profile,call|usr='.$r['name'].',sz=small',$r['name'],'grey small',1);
$txt=nl2br($r['txt']);
//entries
$ra=sql('id',self::$db2,'rv','where bid='.$id); //pr($ra);
if($ra)$p['nbargs']=count($ra); //else return;
if($ra)foreach($ra as $k=>$v)
	$rb[$v]=sql('id,val',self::$db3,'kv','where val>0 and cid='.$v); //pr($rb);
if(isset($rb))$rc=self::algo($rb); //pr($rc);
if(isset($rc)){$p['tot']=array_sum($rc); arsort($rc);} //pr($rc); //by ranks //if($r['cl'])
//render
if(isset($rc))foreach($rc as $k=>$v){$p['bid']=$k; $p['score']=$v;
	$content.=self::proposition($p);}
if(ses('uid') && !$r['cl'])//add argument
	$add=div(div(self::argumentedit($p),'barwrap','addarg'.$id),'barline');
elseif(!ses('uid'))$add=help('need auth 1','alert');
else $add=help('form closed','alert');
//$bt=div(lk('/ideas/'.$id,ico('link'),'btn'),'right');
//$args.=hlpbt(self::$db2);
$ret=div(voc($txt,$db.'-txt-'.$id),'txt');//$by.$bt.br().
if($ra)$ret.=div($p['nbargs'].' '.lang('propositions',1).' - '.$p['tot'].' '.lang('votes',1),'nfo');
$ret.=div($content.$add,'barlevels');
return $ret;}

#stream
static function stream($p){
$p['privacy']='0';
return parent::stream($p);}

#interfaces
static function tit($p){
$p['t']='txt';
return parent::tit($p);}

//call (read)
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