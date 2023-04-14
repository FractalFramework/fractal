<?php
class twlog{

static function call($p){
$twid=twitter::init();
$t=new twit($twid);
$u=host(1).'/twlog/oauth_token';
$q=$t->login($u); pr($q);
$ret='';
return $ret;}

static function content($p){
$do=$p['p1']??'';
$bt=bj('twlog|twlog,call',langp('login-tw'),'btsav');
return $bt.div('','board','twlog');}

}
?>