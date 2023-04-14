<?php

class trad{
static $a='trad';
static $db='trad';

static function headers(){}

static function admin(){
$r=admin::app([self::$a,self::$db]);
$r[]=['editors','pop','txt','','txt'];
$r[]=['editors','pop','pad','','pad'];
$r[]=['editors','pop','trad','','trad'];
return $r;}

static function clean_mail($ret){
$ret=str_replace(".\n",'.µµ',$ret);
$ret=str_replace("\n",'µ',$ret);
$ret=str_replace('µµ',"\n\n",$ret);
$ret=str_replace('µ',' ',$ret);
return $ret;}

static function com($prm){$d=val($prm,'inp1'); $d=self::clean_mail($d);
$ret=trans::com(['to'=>ses('lng'),'txt'=>$d,'dtc'=>1,'mode'=>'html']);
return $ret;}

#content
static function content($prm){
$ret=bj('trd,,z|trad,com||inp1',langp('translate'),'btsav').' ';
$ret.=divarea('inp1','');
$ret.=div('','board','trd');
return $ret;}
}

?>