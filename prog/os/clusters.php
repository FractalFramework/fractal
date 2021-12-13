<?php

class clusters{	
static $private=2;
static $db='clusters_chain';
static $a='clstr';

static function install($p){
//bid:cluster id, usage:buy/use/lend/rent, cuid: user id (comity)), eval: score given by system
//chaque chaîne de ce bloc est écrite une seule fois et jamais modifiée
sqlcreate(self::$db,['bid'=>'int','cuid'=>'int','usage'=>'var','eval'=>'int'],1);
//generator of clusters
sqlcreate('clusters_usage',['bid'=>'int','cuid'=>'int','type'=>'var','eval'=>'int'],1);
sqlcreate('clusters_rules',['bid'=>'int','cuid'=>'int','type'=>'var','eval'=>'int'],1);}

static function admin($p){//return parent::admin($p);
$r[]=['','j','popup|clusters,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=clusters_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=clusters','code','Code'];
return $r;}

static function injectJs(){return;}
static function headers(){
add_head('csscode','');
add_head('jscode',self::injectJs());}

static function titles($p){
$d=$p['appMethod']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#build
static function build($p){$id=val($p,'id');//
//$r=sqlin('pid,tit,typ','cluster_parents','cluster','bid','rr','where uid='.ses('uid').' and cluster.id not equals cluster_parents.pid',1);
//$r=sql('tit,typ','cluster b1','rr','inner join cluster_parents b2 on b1.id=b2.bid where uid='.ses('uid').' and b2.pid != b1.id');
$r=sql('id,tit,typ','cluster','rr','where uid='.ses('uid').' and id not in (select pid from cluster_parents)');
return $r;}

#read
static function call($p){
$id=val($p,'id'); $ret='';
$r=self::build($p); //pr($r);
$clr=cluster::$credits;
//$rb=cluster::props($id);
if($r)foreach($r as $k=>$v){
	$typ=$v['typ']?$v['typ']:0;
	$bt=ico('code-fork').span($v['tit']); $c='bicon bkg'.$clr[$typ];
	$ret.=bj('popup|clusters,edit|id='.$v['id'],$bt,$c);}
return $ret;}

static function com($p){
return;}

#content
static function content($p){
//self::install();
//$id=val($p,'param');
$ret=self::call($p);
return div($ret,'pane');}
}
?>