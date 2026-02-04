<?php

class loadapp{	
static $private=0;
static $a='loadapp';
static $cb='lda';

/*static function install(){
sql::create(self::$db,['tit'=>'var','txt'=>'bvar'],0);}*/
//static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db]);}

static function titles($p){
$d=$p['pla'];
$r['content']='welcome';
$r['call']='loadapp';
if(isset($r[$d]))return lang($r[$d]);
else return lk('/'.$d,lang($d));}

#read
static function call($p){$a=$p['pla']; $ret='';
$bt=lk('/'.$a,langp($a),'btn',0);
if(is_numeric($a))return tlex::one(['id'=>$a]);
if($a && method_exists($a,'admin'))
	$ret=menu::call(['a'=>$a,'mth'=>'admin','drop'=>1,'id'=>'']);
if($a && class_exists($a))$ret.=app($a);
if($ret)return $bt.$ret;}

static function search($p){$ret='';
$id=$p['rid']??'pla'; $d=$p[$id]??'';
//if(auth(6))$r=applist::allapps(); else $r=applist::folder('apps');
$r=applist::com();
foreach($r as $k=>$v)if(strpos($v,$d)!==false)$ret.=tag('option','value='.$v,'',1);
return $ret;}

static function com($p){
$tg=$p['tg']??'popup'; $bt=$p['bt']??'';
//if(auth(6))$r=applist::allapps(); else 
$r=applist::folder('apps');
$j=$tg.'|loadapp,call||pla'; if($bt)$bt=bj($j,pic('ok'),'btn');
//$ret=inputcall('popup|loadapp,call||pla','pla',lang('load app'),32,'','search');
//$ret=datalistcall('pla',$r,'',$j,lang('app'),32).$bt;
$ret=datalistj('pla','',$j,'loadapp,search|',lang('app'),32);
if(!$bt)return $ret;
$ret.=bj($j,langp('ok'),'btn',['data-cl'=>'cbck']).hlpbt('loadapp_app');
return div($ret,'tlxapps');}

#content
static function content($p){
$p['pla']=$p['pla']??'';
$ret=self::com(['tg'=>'popup']);
return div($ret,'board');}
}
?>