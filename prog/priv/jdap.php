<?php
class jdap{	
static $private=2;
static $db='jdap';
static $cols=['word','nb'];
static $typs=['svar','int'];
static $a='jdap';
static $cb='dbs';
static $db2='jdap_200425';

static function install(){
sqlcreate(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){
return admin::app(['a'=>self::$a,'db'=>self::$db]);}

static function injectJs(){return;}
static function headers(){
add_head('csscode','');
add_head('jscode',self::injectJs());}

#build
static function build($p){$id=val($p,'id');
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p){
$ret=lang('result').': '.val($p,'v1').' '.val($p,'inp1');
return $ret;}

static function dl($p){$ret='';
$u='https://lejourdapres.parlement-ouvert.fr/open-data/download';
$e='wget -P '.__DIR__.'/usr/dl/ '.$u; exc($e);
//$fa=val($p,'inp1'); $dba=normalize(strto($fa,'.')); $db=self::$db.'_'.$dba;
$ret=$u;
return $ret;}

static function extract_words($d){
//$ra=[',','.',';',':','!','?','(',')','[',']','{','}','"','\'',' '];
//$d=str_replace($ra,' ',$d); $m="#[ ]+#";
$d=strtolower($d); $d=html_entity_decode($d);
$m="/[\s,]+/";
$r=preg_split($m,$d);//PREG_OFFSET_CAPTURE
//preg_match_all($msk,($msg),$r,);
$rb=[];
$rx=[1,2,3,4,5,6,7,8,9,'0','de','et','les','des','la','le','pour','à','que',':','en','-','qui','sur','il','dans','un','est','plus','par','du','une','faut','au','ou','avec','aux','pas','le','a','ne','d\'','cette','l\'','ce','à','on','ces','nos','son','ses',':','...','«','»','I','là','"','ni','-là','?','se','mais','leur','!','d\'une','n\'est','où','.',';',':','','','','','','','',''];
foreach($r as $k=>$v)
	if(!in_array($v,$rx))
	$rb[$v]=radd($rb,$v);
arsort($rb);
pr($rb);
}

#call
static function call($p){$ret='';
$fa=val($p,'inp1'); $db='csv2sql_'.$fa;
$r=sql('body',$db,'rv','limit 5000'); $n=count($r); $ret=$n;
$d=implode(' ',$r);
self::extract_words($d);
return $ret;}

#content
static function content($p){
self::install();
$p['p1']=$p['p1']??''; $p1='1587983145215';
$j=self::$cb.',,z|'.self::$a.',call||inp1';
$bt=inputcall($j,'inp1',$p1,'',0);
$bt.=bj($j,pic('go'),'btn');
$bt.=lk('https://lejourdapres.parlement-ouvert.fr/open-data/download',pic('url'),'btn');
//$bt.=upload::call('inp1');
return $bt.div('','pane',self::$cb);}
}
?>