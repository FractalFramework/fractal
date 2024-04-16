<?php
//philum_app

class simbad{
static $a=__CLASS__;
static $default='wolf424';

static function url($p){$p=trim($p);
if(is_numeric($p))$p='hip'.$p;
//&NbIdent=1&Radius=2&Radius.unit=arcmin&submit=submit+id
return 'http://simbad.u-strasbg.fr/simbad/sim-id?Ident='.$p;}

//view-source:http://simbad.u-strasbg.fr/simbad/sim-id?Ident=hip+17265
static function build($u){//hip32578
$d=get_file2($u); $dom=dom($d);
$r=$dom->getElementsByTagName('table'); $n=count($r);
$rt=self::detect_table($r[3]);
$rt=self::cleanup($rt);
return $rt;}

static function cleanup($r){//pr($r);
$p=explode('--',$r[0][0]);
$rb[$p[0]]=$p[1];
foreach($r as $k=>$v){
if(isset($v[1]))$v[1]=deln($v[1],' ');
$t='Origin of the objects types';
if(strpos($v[0],$t)!==false)$rb[$t]=$v[1];
if($k==2){$p=explode(' ',$v[1]);
	if($k==2)$t='ICRS'; if($k==3)$t='FK4';
	$rb['ICRS AD']=$p[0].'h'.$p[1].'m'.$p[2].'s';
	$rb['ICRS DC']=$p[3].'°'.$p[4].'"'.$p[5]."'";}
if($k==3){$p=explode(' ',$v[1]);
	$rb['FK4 AD']=$p[0].'h'.$p[1].'m'.$p[2].'s';
	$rb['FK4 DC']=$p[3].'°'.$p[4].'"'.$p[5]."'";}
if($k==4){$p=explode(' ',$v[1]);
	$rb['degAD']=$p[0].'°';
	$rb['degDC']=$p[1].'°';}
$t='Proper motions mas/yr';
if(strpos($v[0],$t)!==false){
	$p=explode(' ',$v[1]);
	$rb[$t.' AD']=$p[0];
	$rb[$t.' DC']=$p[1];}
$t='Radial velocity';
if(strpos($v[0],$t)!==false){$p=explode(' ',$v[1]); $rb[$t.' '.$p[0]]=$p[1];}
$t='Parallaxes (mas)';
if(strpos($v[0],$t)!==false){$p=explode(' ',$v[1]); $rb[$t]=$p[0];
	$rb['Distance (LY)']=$p[0]?maths::mas2al((float)$p[0]):'';}
$t='Spectral type';
if(strpos($v[0],$t)!==false){$p=explode(' ',$v[1]);
	$rb['Spectral type']=$p[0].' '.$p[1];}}
return $rb;}

static function getxt($el,$ret=''){$attr=''; $at='class';
if(!isset($el->tagName)){$el0=$el->parentNode;
	//if($el0->hasAttribute($at)!=null)$attr=$el0->getAttribute($at); $tg=$el0->tagName;
	//if($tg!='div')//$attr!='info-tooltip' && 
	return $ret.$el->textContent;}
$el=$el->firstChild;
if($el!=null)$ret=self::getxt($el,$ret);
while(isset($el->nextSibling)){$el2=$el->nextSibling;
	$ret=self::getxt($el->nextSibling,$ret); $el=$el->nextSibling;}
return $ret;}

static function detect_table($dom){$rt=[];
$r=$dom->getElementsByTagName('tr');
foreach($r as $k=>$v){$rt[$k]=[];
	//if($v->childNodes)foreach($v->childNodes as $kb=>$el){}
	$rb=$v->getElementsByTagName('th'); if(!$rb['length'])$rb=$v->getElementsByTagName('td');
	if($rb)foreach($rb as $kb=>$el)$rt[$k][$kb]=str::clean_br(self::getxt($el));}//html2conn
return $rt;}

static function call($p){
$p=$p['star']??self::$default;
$u=self::url($p);
$bt=lk($u,icoxt('url',domain($u))).' ';
//for($i=0;$i<$n;$i++)$bt.=bj('smbd|simbad,call|star='.ajx($p).',n='.$i,$i,active($i,$o));
$r=self::build($u);
$ret=tabler($r,'',1);
return $bt.div($ret,'','smbd');}

static function callr($p){
$p=str_replace(' ','',$p);
if(!$p)return ['00h00m',"00°00'",'0'];
$u=self::url($p);
$r=self::build($u);
return [$r['ICRS AD'],$r['ICRS DC'],$r['Distance (LY)']];}

//interface
static function content($p){
$rid=randid('smb');
$j=$rid.'|simbad,call||star';
$bt=inputcall($j,'star',self::$default);
$bt.=bj($j,lang('ok'),'btn');
return $bt.div('','board',$rid);}

}
?>