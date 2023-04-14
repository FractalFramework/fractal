<?php

class admin_tags{
static $private=6;
static $a='admin_tags';
static $db='tags';
static $db2='tags_r';
static $cb='adtg';

#install
static function install(){
sql::create(self::$db,['ref'=>'var','lg'=>'svar'],1);
sql::create(self::$db2,['bid'=>'int','aid'=>'int','app'=>'var'],1);}

//create lang
static function create($p){
$newlng=val($p,'newlng'); $lng='fr';
$ret=input('newlng',$newlng);
$ret.=bj('adtg|'.self::$a.',create||newlng',langp('add language'),'btn');
if($newlng){
	$r=sql('ref,txt',self::$db,'rr','where lg="'.$lng.'" limit 80,10'); //p($r);
	foreach($r as $k=>$v){
		$ex=sql('txt',self::$db,'v','where ref="'.$v['ref'].'" and lg="'.$newlng.'"');
		if(!$ex){
			$res=trans::com(['from'=>$lng,'to'=>$newlng,'txt'=>$v['txt']]);
			$v['txt']=($res); $v['lg']=$newlng;//utf8_decode
			sql::sav(self::$db,$v); $r[$k]=$v;}
		else $r[$k]['txt']=$ex;}
$ret.=tabler($r);}
return $ret;}

#tools
static function goodid($p){
return sql('id',self::$db,'v',['ref'=>$p['ref'],'lg'=>$p['lg']]);}

static function insertup($p){$id=self::goodid($p);
if($id)sql::up(self::$db,'txt',$p['txt'],$id);
else sql::sav(self::$db,[$p['ref'],$p['txt'],$p['lg']]);}

static function translate($p){$voc=''; $txt=''; $copy=val($p,'copy');
$r=sql('lg,ref',self::$db,'kv',['ref'=>$p['ref']]);
foreach($r as $k=>$v){
	if($p['lg']!='en' && isset($r['en'])){$from='en'; $txt=$r['en'];}
	if($p['lg']!='fr' && isset($r['fr'])){$from='fr'; $txt=$r['fr'];}}
if($copy)$voc=(($txt));//html_entity_decode//utf8_decode
elseif($txt)$voc=trans::com(['from'=>$from,'to'=>$p['lg'],'txt'=>$txt]);
return $voc;}

static function equalize($p){
$r=sql('ref,lg',self::$db,'kkv','');
$rb=array_keys($r);
foreach($rb as $k=>$v)
	if(!isset($r[$v][$p['lg']])){$txt=''; $voc='';
		if($p['lg']!='en' && isset($r[$v]['en'])){$from='en'; $txt=$r[$v]['en'];}
		if($p['lg']!='fr' && isset($r[$v]['fr'])){$from='fr'; $txt=$r[$v]['fr'];}
		//if($txt)$voc=trans::com(['from'=>$from,'to'=>$p['lg'],'txt'=>$txt]);
		self::insertup(['ref'=>$v,'txt'=>$voc,'lg'=>$p['lg']]);}
return self::com($p);}

#save
static function update($p){
$ref=val($p,$p['rid']); $old=val($p,'ref'); $ex='';
$ex=sql('id',self::$db,'v',['ref'=>$ref]);
if($old!=$ref && strtolower($old)==strtolower($ref))$ex=0;
if(!$ex)sql::up(self::$db,'ref',$ref,$p['id']);
else{
	$bid=sql('id',self::$db,'v',['ref'=>$old]);
	sql::up(self::$db2,'bid',$ex,['bid'=>$bid]);//trigger
	$n=sql('count(id)',self::$db2,'v',['bid'=>$bid]);
	if(!$n)self::del(['id'=>$bid]);}
return self::com($p);}

static function del($p){
$nid=sql::del(self::$db,$p['id']);
return self::com($p);}

static function save($p){
$nid=sql::savif(self::$db,['ref'=>$p['ref'],'lg'=>$p['lg']]);
return self::com($p);}

static function addfrom($p){
$p['voc']=trans::com(['from'=>$p['from'],'to'=>$p['lg'],'txt'=>$p['ref']]);
$p['id']=sql::sav(self::$db,[$p['ref'],$p['lg']]);
return self::edit($p);}

static function edit($p){$rid=randid('ref');
$to=val($p,'to')?'socket,,x':'adtg,,x';
$r=sql('ref,lg',self::$db,'ra','where id='.$p['id']);
$ret=label($rid,$r['ref'].' ('.$r['lg'].')');
$ret.=bj($to.'|'.self::$a.',update|id='.$p['id'].',rid='.$rid.',ref='.$r['ref'].',lg='.$r['lg'].'|'.$rid,lang('save'),'btsav');
$ret.=bj($to.'|'.self::$a.',del|id='.$p['id'].',lg='.$r['lg'],lang('del'),'btdel');
$ret.=bj('input,'.$rid.'|'.self::$a.',translate|ref='.$r['ref'].',lg='.$r['lg'].',copy='.$p['id'],pic('copy'),'btn');
$ret.=bj('input,'.$rid.'|'.self::$a.',translate|ref='.$r['ref'].',lg='.$r['lg'],pic('translate'),'btn');
$lgb=$r['lg']=='fr'?'en':'fr';
$ret.=bj('popup,,x|'.self::$a.'|lg='.$lgb,ico('window-maximize'),'btn');
foreach(lngs() as $v)if($v!=$r['lg']){
	$id=sql('id',self::$db,'v',['ref'=>$r['ref'],'lg'=>$v]);
	if($id)$ret.=bj('popup|'.self::$a.',edit|id='.$id,$v,'btn');
	else $ret.=bj('popup|'.self::$a.',addfrom|lg='.$v.',ref='.$r['ref'].',from='.$r['lg'],$v,'btsav');}
$ret.=br().textarea($rid,$r['ref'],40,4);
return $ret;}

static function open($p){$ref=val($p,'ref');
$p['id']=sql('id',self::$db,'v',['ref'=>$ref]);
if(!$p['id'])$p['id']=sql::sav(self::$db,[$ref,ses('lng')]);
if($p['id'])return self::edit($p);}

static function add($p){$ref=val($p,'ref');
$ret=input('ref',$ref,16,'ref').span($p['lg'],'nfo grey');
$ret.=bj('adtg,,x|'.self::$a.',save|lg='.$p['lg'].'|ref',lang('save'),'btsav');
return $ret;}

#edit
static function delusetag($p){
sql::del(self::$db2,$p['bid']);
return self::usetags($p);}

static function addentry($p){
[$ref,$id,$a,$lg]=vals($p,['adtg','id','a','lg']);
if($ref)$nid=sql::savif(self::$db,['ref'=>$ref,'lg'=>$lg]);
if($ref)$nid=sql::savif(self::$db2,['bid'=>$nid,'aid'=>$id,'app'=>$a]);
return self::usetags($p);}

#call
static function taglist($a,$pub){
//$w=$pub?'and pub='.$pub:''; '.$w.'
return sql::inner('tags.id as id,ref,count(bid) as nb',self::$db2,self::$db,'bid','','where lg="'.ses('lng').'" and app="'.$a.'" group by tags.id order by nb desc');}

static function build($p){
[$a,$id,$lg]=vals($p,['a','id','lg']);
//return sql::inner('tags_r.id as id,tags.id as bid,ref',self::$db2,self::$db,'bid','rr',['aid'=>$id,'app'=>$a]);
return sql::inr('tags_r.id as id,tags.id as bid,ref',[[self::$db,'id',self::$db2,'bid']],'rr',['aid'=>$id,'app'=>$a]);}

static function usetags($p){$ret=''; $edt=val($p,'editable');
$pr=valk($p,['id','a','lg','edt','rid','editable']); $prm=prm($pr);
$r=self::build($p);
if($r)foreach($r as $k=>$v)if($v['ref']){$del='';
	$bt=bj('popup|'.self::$a.',search|popwidth=640,bid='.$v['bid'].','.$prm,$v['ref'],''); 
	if($edt && $v['ref'])$del=bj($p['rid'].'|'.self::$a.',delusetag|bid='.$v['id'].','.prm($p),picto('del'),'');
	$ret.=span($bt.' '.$del,'nfo').' ';}
return $ret;}

static function searchapp($p){$ret='';
[$a,$bid,$dsp]=vals($p,['a','bid','dsp']);
$r=sql('aid',self::$db2,'rv',['bid'=>$bid,'app'=>$a]);
if($r)foreach($r as $k=>$v){
	$t=$a::tit(['id'=>$v,'a'=>$a]);
	$c=$dsp==1?'licon':'cicon';
	$ret.=bj('popup|'.$a.',call|id='.$v,div(pic($a).$t));}
return div($ret,$c);}

static function search($p){$ret='';
//$r=self::build($p);
$r=sql('app,aid',self::$db2,'',valk($p,['bid']));
if($r)foreach($r as $k=>$v){[$a,$id]=$v; $t='';
	if(class_exists($a))$t=$a::tit(['id'=>$id,'a'=>$a]);
	$ret.=bj('popup|'.$a.',call|id='.$id,div(pic($a)).$t);}
return div($ret,'cicon');}

static function call($p){$p['rid']=randid('tg');
[$a,$id,$lg,$edt]=vals($p,['a','id','lg','edt']);
$uid=sql('uid',$a::$db,'v',$id); $p['editable']=$a::permission($uid,$edt);
$r=sql('id,ref',self::$db,'kv',['lg'=>$lg,'_order'=>'ref']);
$j=$p['rid'].',,resetform|'.self::$a.',addentry|'.prm($p).'|adtg';
$ret=span(self::usetags($p),'',$p['rid']);
if($p['editable'])$ret.=datalistcall('adtg',$r,'',$j,'tags...',16);
return $ret;}

#play
static function selectlg($lg){$r=lngs();
//$r=sql('distinct(lg)',self::$db,'rv','');
foreach($r as $v){$c=$v==$lg?' active':'';
	$rc[]=bj('adtg|'.self::$a.',com|lg='.$v,$v,'btn'.$c);}
return div(implode('',$rc),'','tags').hidden('lg',$lg);}

static function com($p){$rb=[];
$lg=val($p,'lg');
$bt=self::selectlg($lg);
$r=sql('id,ref,lg',self::$db,'','where lg="'.$lg.'" order by ref');
if($r){
$n=count($r); $bt.=langnb('word',$n,'small');
//$rn=sql('bid,count(id)',self::$db2,'kv','group by bid',1); p($rn);
foreach($r as $k=>$v){
	$n=sql('count(id)',self::$db2,'v',['bid'=>$v[0]]);
	if(auth(6))$rb[]=bj('popup|'.self::$a.',edit|id='.$v[0],$v[1],'btn');
	else $rb[]=$v[1];}}
$bt.=popup(self::$a.',add|lg='.$lg,langp('add'),'btn');
return $bt.div(implode('',$rb),'list');}

#content
static function content($p){$ret='';
//self::install();
$lg=val($p,'lang',lng());
$ret=self::com(['lg'=>$lg]);
return div($ret,'board','adtg');}
}

?>