<?php

class replace{
static $private=0;
static $a=__CLASS__;
static $cb='rpl';

static function headers(){
head::add('csscode','');
head::add('meta',['attr'=>'property','prop'=>'description','content'=>'replace characters']);}

static function admin(){
$r=admin::app(['a'=>self::$a]);
$r[]=['editors','pop','txt','','txt'];
$r[]=['editors','pop','pad','','pad'];
$r[]=['editors','pop','convert','','convert'];
return $r;}

static function call($p){$ret='';
$txt=$p['inp0']; $from=$p['inp1']; $to=$p['inp2'];
return str_replace($from,$to,$txt);}

#content
static function content($p){
$r['p1']=['textarea','',20,1,'',''];
$r['p2']=['textarea','',20,1,'',''];
$r['p2']=['textarea','',40,12,'',''];
$r['p2']=['submit','replace',''];
//$ret=form::com();
$ret=textarea('inp1','','16','1','','','');
$ret.=textarea('inp2','','16','1','','','');
$ret.=div(bj('inp0|replace,call||inp0,inp1,inp2',langp('replace'),'btn'));
$ret.=textarea('inp0','','40','12','','','');
return $ret;}
}

?>