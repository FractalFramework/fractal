<?php
class api{
static $private=0;

//false api
static function content($p){
$app=$p['p1']??''; $mth=$p['p2']??'';
if(method_exists($app,'api')){$a=new $app(); $ret=$a::api($p);}
return $ret;}

}
?>