<?php
class install{
//while installation, set private=0;, and also to upsql.php
static $private=0;//!!!!!

static function menubt($dr,$f){
$app=struntil($f,'.');
if(method_exists($app,'install')){
	$q=new $app; $q::install('');
	return $f.br();}}

static function build(){
$r=walkdir('install::menubt','prod'); pr($r);
return implode('',$r);}

static function txt($p){
$d=file_get_contents('usr/_pub/install.txt');
return div(nl2br($d),'board');}

static function json($p){
$table=$p['inp1']??'';
$r=sql('*',$table,'rr','');
return json_encode($r);}

static function automate(){
$ret=tag('h2','','install databases');
$ret.=install::build();
$ret=tag('h2','','install datas');
$ret.=upsql::install(['local'=>0]);
return $ret;}

static function content($p){
$rid=randid('md');
//$ex=sql::ex('help'); if(!$ex)echo 'e';
$bt=hlpbt('install');
if(!sql::ex('profile')){ses('auth',6); self::automate();}
$bt.=bj($rid.'|install,build',langp('databases'),'btn');
$bt.=bj($rid.'|upsql,install|local=1',langp('datas'),'btn');
$bt.=bj($rid.'|install,txt|local=1',langp('installation'),'btn');
$bt.=lk('/update',langp('updates'),'btn');
return $bt.div('','',$rid);}
}

?>