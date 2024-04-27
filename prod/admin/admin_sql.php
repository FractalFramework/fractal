<?php

class admin_sql{
static $private=2;
var $db='';

static function secu($b){
if(!auth(6))if($b=='login' or $b=='lang' or $b=='help' or $b=='icons')return 1;}

#edit
static function del($p){
$b=$p['b']??''; $id=$p['id']??'';
if(self::secu($b))return;
sql::del($b,$id);
return 'ok';}

static function modif($p){
$b=$p['b']??''; $cols=val($p,'cols'); $id=$p['id']??'';
if(self::secu($b))return;
$cl=explode(';',$cols);
foreach($cl as $v)$r[$v]=$p[$v];
sql::up2($b,$r,$id);
return 'ok';}

static function add($p){
$b=$p['b']??''; $cols=val($p,'cols'); $id=$p['id']??'';
if(self::secu($b))return;
$r=sql::cols($b,1,0); $cols=implode(',',array_keys($r));
if($r)foreach($r as $k=>$v)$ret[$k]=$p[$k]??'';
$nid=sql::sav($b,$ret);
return $nid;}

static function savcsv($p){
$b=$p['b']??''; $bid=$p['bid']??''; $d=$p['datas']??'';
$r=explode_r($d,"\n",','); $cl=array_keys(sql::cols($b,3,0));
foreach($r as $k=>$v){$id=array_shift($v); if(auth(6))sql::up2($b,array_combine($cl,$v),$id,0);}
return self::call($p);}

static function editcsv($p){
$b=$p['b']??''; $bid=$p['bid']??''; $cb=$p['cb']??($p['did']??'');
$r=sql('id,'.sql::cols($b,3,1),$b,'',['bid'=>$bid]);
$d=implode_r($r,"\n",',');
$ret=bj($cb.'|admin_sql,savcsv|'.prm($p).'|datas',langp('save'),'btsav');
$ret.=div(textarea('datas',$d,84,24,'','console'));
return $ret;}

/**/static function form($p){$ret='';
$b=$p['b']??''; $id=$p['id']??'';
if(self::secu($b))return;
$r=sql::cols($b,1,0); $cols=implode(',',array_keys($r));
if($r)foreach($r as $k=>$v){$val=$p[$k]; if(!$val && $v=='int')$val=0;
	if($k=='txt' or $v=='text')$ret.=build::connbt($k).textarea($k,$val,52,12);
	elseif($k=='clr')$ret.=inpclr($k,$val,'');
	else $ret.=input($k,$val,63,$k,'',512);}
return $ret;}

static function com($p){$ret='';
$rid=randid('txt'); $b=$p['b']??''; $cols=val($p,'cols');
if(self::secu($b))return;
$id=$p['id']??''; $act=val($p,'act'); $labs=val($p,'colslabels');
if($cols){$cl=explode(',',$cols);if($labs)$lb=array_combine($cl,explode(',',$labs));}
else{$cl=sql::cols($b,1,0); $cols=implode(',',array_keys($cl)); $p['cols']=$cols;}
if($id)$r=sql($cols,$b,'ra','where id='.$id);
$prm='id='.$id.',b='.$b.',cols='.str_replace(',',';',$cols);
if(isset($r))foreach($r as $k=>$v){
	$label=label($k,isset($lb[$k])?lang($lb[$k]):$k);
	$ret.=div(goodinput($k,$v).' '.$label);}
$ret.=bj($rid.'|edit,'.$act.'|'.$prm.'|'.$cols,langp($act),'btsav');
return div($ret,'',$rid);}

#edit2
static function del_row($p){
$b=$p['b']; $k=val($p,'k',0);
if(auth(6))sql::del($b,$k);
return self::read($p);}

static function sav_row($p){
$b=$p['b']; $id=$p['k']??0; $n=$p['n']; $rid=$p['rid'];
if(self::secu($b))return;
$ra=sql::cols($b,2,2);
foreach($ra as $k=>$v)$rb[$v]=valb($p,$rid.$k); //pr($rb);
$rb=sql::vrf($rb,$b);
if($id)sql::up2($b,$rb,$id);
return self::read($p);}

static function edit_row($p){
$b=$p['b']; $ka=$p['k']; $w=$p['w']??''; $bid=$p[$w]??''; $rid=randid(); $did=$p['did'];
$ra=sql::cols($b,2,2); $n=count($ra); $cls='id,'.implode(',',$ra); $cb=$did;
$r=sql($cls,$b,'id',$ka); if(isset($r[$ka]))$r=$r[$ka];
for($i=0;$i<$n;$i++)$rb[]=$rid.$i; if($n)$prm=implode(',',$rb); else $prm='';
$j='b='.$b.',k='.$ka.',n='.$n.',rid='.$rid.',w='.$w.',bid='.$bid.',did='.$did;
$bt=bj($cb.',,x|admin_sql,sav_row|'.$j.'|'.$prm,langp('save'),'btsav');
if(auth(6))$bt.=bj($cb.',,x|admin_sql,del_row|'.$j,langp('del'),'btdel');
foreach($ra as $k=>$v)$ret[]=[$v,goodinput($rb[$k],$r[$k])];//valb($r,$k,$ra[$v]=='int'?0:'')
return $bt.tabler($ret);}

static function sav_cell($p){
$b=$p['b']??''; $xy=$p['id']??''; $d=val($p,'d'.$xy);
if(self::secu($b))return;
[$id,$c]=explode('-',$xy);
$d=trim($d); $d=delbr($d,"\n");
$rc=sql::cols($b); $rd=array_keys($rc);
$col=is_numeric($c)?$rd[$c]:$c; $ty=$rc[$col];
if($ty=='int')$d=(int)trim($d);
sql::up($b,$col,$d,$id,'',0);
return $d;}

#build
static function build($p){//pr($p);
$b=$p['b']??''; $id=$p['id']??''; $w=$p['w']??'id'; $bid=$p[$w]??''; $pg=$p['pg']??1;
$cl=sql::cols($b,0,1);
$w=$bid?'where '.$w.'="'.$bid.'"':'';
$r['_']=sql::cols($b,1,2); //array_unshift($r,$cols);
$nbp=500; $min=($pg-1)*$nbp; $max=$pg*$nbp;
$rb=sql($cl,$b,'id',$w.' order by id desc');//w=id/bid// limit '.$min.','.$max
$rb=sql::vrf($rb,$b);
return $r+$rb;}

static function replay($p){//callback of btpages
return self::read($p);}

static function read($p){
$b=$p['b']??''; $id=$p['id']??''; $w=$p['w']??'id';
$r=self::build($p);
//foreach($r as $k=>$v)$r[$k]['_edt']=bj('popup|admin_sql,edit_row|b='.$b.',k='.$k.',pg=1,did=',pic('edit'));
$ret=build::editable($r,'admin_sql',$p,auth(6)?1:0);
return $ret;}

static function call($p){
$b=$p['b']??''; $id=$p['id']??''; $p['did']=randid('did');
$r=self::build($p);
$bt=bj('asq|admin_sql,menu|',pic('back'),'btn');
$bt.=bj('asq|admin_sql,call|b='.$b.',id='.$id,pic('refresh'),'btn');
$bt.=bj('popup,,xx|core,mkbcp|b='.$b.',o='.date('ymd'),pic('backup'),'btn');
$bt.=bj('popup,,xx|core,mkbcp|b='.$b,pic('backup'),'btsav');
$bt.=bj('popup,,xx|core,rsbcp|b='.$b,pic('restore'),'btdel');
$bt.=bj('popup,,xx|upsql,call|app='.$b,pic('renove'),'btn');
$bt.=bj('popup|admin_sql,editcsv|'.prm($p),pic('editcsv'),'btn');
if($p['bt']??'')$bt.=$p['bt'];
if(auth(6))$bt.=download::mkcsv($r,$b.($id?'_'.$id:''));
//core,mkbcp|b='.$db.',o='.date('ymd')
if($b)return $bt.div(self::read($p),'',$p['did']);}

static function menu($p){
$r=sql::query('show tables','rv'); $b=$p['b']??'';
foreach($r as $k=>$v)if(substr($v,0,2)=='z_')unset($r[$k]);
return batch($r,'asq|admin_sql,call|b=$v',$b);}

static function content($p){
$b=$p['b']??''; $id=$p['id']??'';
if($b)$ret=self::call($p);
else $ret=self::menu($p);
return div($ret,'board','asq');}

}
?>
