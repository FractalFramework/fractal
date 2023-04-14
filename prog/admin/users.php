<?php

class users{
static $private=6;

#reader
static function read($p){$rid=$p['rid']??'';
	$r=sql::inner('name,pname,auth','profile','login','puid','','order by login.id desc limit 200');
	$bt=bj($rid.'|users,read|ip='.$rid,pic('refresh'));
	return $bt.tabler($r);}
	
//interface
static function content($p){
	$p['rid']=randid('md');
	$ret=self::read($p);
	return div($ret,'board',$p['rid']);}
}

?>