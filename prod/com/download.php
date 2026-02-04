<?php
class download{

static function admin(){
$r[]=['','pop','core,help|ref=download_app','','help'];
return $r;}

static function createtar($f){$r=update::$dr; $r[]='prod';
return tar::buildFromList($f,$r);}

static function mkcsv($r,$nm){
$f='usr/dl/'.$nm.'.csv'; mkdir_r($f); writecsv($f,$r);
return lk('/'.$f,picxt('download',$nm),'btn',1);}

#content
static function content($prm){
$f=val($prm,'fileName','fractal');
$f.='.tar'; $fgz=$f.'.gz';
$url=self::createtar($f);
$ico=ico('download');
return lk('/'.$fgz,$ico.$url,'btn');}
}
?>