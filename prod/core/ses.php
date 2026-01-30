<?php
class ses{static $r=[]; static $s=[]; static $m=[]; static $er=[];
static $local=0; static $n=0; static $cnfg; static $tz; static $day; static $alx=[];
static function adm($k){return self::$adm[$k]??'';}
static function s($k,$v=''){return self::$s[$k]??(self::$s[$k]=$v);}//prms
static function r($k,$v=''){return self::$r[$k]??(self::$s[$k]=$v);}//art
static function m($k,$v=''){return self::$m[$k]??(self::$m[$k]=$v);}//metas
static function z($k){unset(self::$r[$k]);}
static function er($v){self::$er[]=$v;}}
?>