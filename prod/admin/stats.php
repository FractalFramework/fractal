<?php

//application not based on appx
class stats{
static $private=0;
static $a=__CLASS__;
static $db='stats';
static $cols=['ip','uid'];
static $typs=['var','int'];
static $db2='stats_r';
static $db3='stats_c';
static $qb='stats';
static $cb='mdb';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);
sql::create(self::$db2,['iq'=>'int','app'=>'svar','prm'=>'svar'],1);
sql::create(self::$db3,['day'=>'int','du'=>'int','vu'=>'int'],1);}

static function admin(){
return admin::app(['a'=>self::$a,'db'=>self::$db,'db2'=>self::$db2,'db3'=>self::$db3,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function iq(){$ip=sesf('ip'); $cuid=ses('uid',0);
$r=sql('id,uid',self::$db,'rw',['ip'=>$ip],0); [$iq,$uid]=arr($r,2);
if(!$iq)$iq=sql::sav(self::$db,['ip'=>$ip,'uid'=>$cuid?$cuid:0],0);
elseif(!$uid && $cuid)sql::up2(self::$db,['uid'=>$cuid],$iq,0);
return $iq;}

#operations
static function add($app,$prm){
$ip=sesf('ip');
$uid=ses('uid','',0);
$r['iq']=sesm('stats','iq');
$r['app']=$app;
if(isset($prm['id']))$r['prm']=$prm['id'];
elseif(isset($prm['usr']))$r['prm']=$prm['usr'];
else $r['prm']=get('_p');
sql::sav(self::$db2,$r,0);}

static function consolid($p){$day=date('ymd');
$last=sql('numday',self::$db3,'v','order by up desc limit 1');
$w='where date_format(up,"%y%m%d")>'.$last.' and date_format(up,"%y%m%d")<'.$day.' group by date';
$r=sql('numday,count(distinct iq),count(iq)',self::$db2,'',$w,0); //pr($r);
if($r)sql::sav2('stats_c',$r);
return tabler($r);}

#build
static function build($p){$id=$p['id']??''; $n=$p['n']??0; $db=self::$db; $db2=self::$db2; $na=500;
$nb=($n*$na); $l=$nb.','.($nb+$na);
$r=sql::inr('ip,uid,app,prm,'.$db.'.ip,numsec',[[$db2,'iq',$db,'id']],'','order by '.$db2.'.up desc limit '.$l);
return $r;}

#play
static function play($p,$r){$ret=''; //pr($r);
return $ret;}

static function uniqs($p){$ret='';
return sql('count(id)',self::$db,'v','');}

#call
static function call($p){
$n=$p['n']??0;
$r=self::build($p);
$ret=tabler($r);
if(!$ret)return help('no element','txt');
else $ret.=bj(self::$cb.'|stats,call|n='.($n+1),lang('next'),'btn');
return $ret;}

static function com($p){}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||p1'; $p1=$p['p1']??'';
//$bt=bj($j,langp('ok'),'btn');
//$ret=$bt.textarea('p1','',60,4);
//$ret=inputcall($j,'p1',$p['p1']??'',32).$bt;
$ret=form::call(['p1'=>['inputcall',$p1,'url',$j],['submit',$j,'ok','']]);
$ret.=bj(self::$cb.'|stats,consolid|',langp('consolidation'),'btn');
$ret.=bj(self::$cb.'|stats,uniqs|',langp('unique_visitors'),'btn');
return $ret;}

#content
static function content($p){
self::install();
$p['p1']=$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>