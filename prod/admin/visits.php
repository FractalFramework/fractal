<?php

class visits{
static $private=0;
static $a=__CLASS__;
static $db='visits';
	
static function install(){
sql::create(self::$db,['uid'=>'int','app'=>'var','prm'=>'var','day'=>'int','ip'=>'var'],1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db]);}

#logs
static function logs(){
system('cp -a /var/log/apache2 /home/tlex/usr');}

static function read(){
//return read_file('/pub/apache2/error.log');//access.log
}
static function tabler(){
return '';}

#operations
static function save($app,$prm){
$r['uid']=ses('uid','',0);
$r['app']=$app;
if(isset($prm['id']))$r['prm']=$prm['id'];
elseif(isset($prm['usr']))$r['prm']=$prm['usr'];
else $r['prm']=get('_p');
$r['day']=date('ymd');
$r['ip']=ip();
$id=sql::sav(self::$db,$r);}

#reader
static function pages_by_user($uid){
$r=sql('page',self::$db,'rw',['uid'=>$uid]);
return tabler($r);}

static function users_by_page($page){
$r=sql('uid',self::$db,'rw',['page'=>$page]);
return tabler($r);}

static function graph($page){
$r=sql('count(uid)',self::$db,'rv','group by day');
return tabler($r);}

static function live($p){$rid=$p['rid']??'';
$r=sql('uid,app,prm,ip,date_format(up,"%H:%i %d/%m")',self::$db,'','order by id desc limit 200');
//$r=sql::inner('name,count(stats.ip) as nb,app,prm,stats.ip,date_format(stats.up,"%H:%i %d/%m/%Y") as date',self::$db,'login','uid','','group by stats.ip order by stats.id desc');
$bt=bj($rid.'|visits,live|ip='.$rid,pic('refresh'));
return $bt.tabler($r);}

static function patch(){$db=self::$db; $db='z_visits_';
//sql::drop('stats');
//1
//$r=sql('distinct ip,uid',$db,'','');
//sql::sav2('stats',$r);
//$r=sql::doublons('stats','ip'); pr($r);
//2
/*$ra=sql('distinct ip,id','stats','kv',''); //echo count($ra); pr($ra);
$r=sql('ip,app,prm,up',$db,'','');
foreach($r as $k=>$v)$r[$k][0]=$ra[$v[0]];
sql::sav2('stats_r',$r,1);*/
//3
//$r=sql('date_format(up,"%y%m%d") as date,count(distinct ip),count(app)',$db,'','group by date');
//sql::sav2('stats_c',$r);
}

//interface
static function content($p){
//self::install(); //self::patch();
$p['rid']=randid('md'); $uid=$p['uid']??''; $page=$p['page']??''; $graph=$p['graph']??'';
if($uid)$ret=self::pages_by_user($uid);
elseif($page)$ret=self::users_by_page($p);
elseif($graph)$ret=self::graph($p);
else $ret=self::live($p);
//$ret=self::read();
return div($ret,'board',$p['rid']);}
}

?>