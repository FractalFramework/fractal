<?php

class css{	
static $private=6;
static $db='css';
static $a='css';
static $cb='ecs';

static function install(){
sql::create(self::$db,['pag'=>'svar','tit'=>'var','cod'=>'text'],1);}

//static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function admin(){return menu::call(['app'=>'admin','mth'=>'app','drop'=>1,'a'=>self::$a]);}
static function js(){return;}
static function headers(){}

static function editor(){
$ret=textarea('csd','',64,10);
return $ret;}

//associate
static function pictos($p){$ret='';
$ra=self::build(['css'=>'fa']);
$rb=self::build(['css'=>'pictos']); //pr($rb);
$ra=self::nm($ra,'.fa-'); //pr($ra);
$rb=self::nm($rb,'.ic-'); //pr($rb);
$r=array_diff($ra,$rb); //pr($r);
echo count($r);
return $ret;}

static function nm($r,$a){//.fa-th-large:before
foreach($r as $k=>$v){$d=between($v[0],$a,':before'); if($d)$ret[]=$d;} return $ret;}
static function f($d){return 'prog/css/'.$d.'.css';}

#buildcss
static function buildcss($r,$o=''){
$a=$o==1?' ':"\n";
if($r)return implode_kr($r,';'.$a,':').';';}

static function readjson($d,$o=''){
$r=json_decode($d,true);
return self::buildcss($r,$o);}

static function buildpage($p){
$id=$p['id']; $pag=$p['pag']; $ret='';
$r=sql::read('tit,cod',self::$db,'vv',['pag'=>$pag]); //eco($r);
foreach($r as $k=>[$t,$d]){$b=self::readjson($d,1);
	$rb[$k]=[$t,$b]; $rc[$t]=json_decode($d,true);
	$ret.=$t.'{'.$b.'}'.n();}
if(auth(6)){
$d=file_put_contents(self::f($pag),$ret);
json::write('json/system/'.$pag.'.json',$rc);
db::save('db/system/'.$pag,$rb);}
return lang('saved');}

#edit
static function buildjson($d){
$r=explode_k($d,';',':');
return json_enc($r,1);}

static function save($p){
$id=$p['id']; $cod=$p['css'.$id]; $d=self::buildjson($cod);
if(auth(6))sql::upd(self::$db,['cod'=>$d],$id);
return self::play($p);}

static function edit($p){$id=$p['id']; $pag=$p['pag'];
[$t,$d]=sql('tit,cod',self::$db,'rw',['id'=>$id]);
$b=self::readjson($d);
$ret=textarea('css'.$id,$b,82,6);
$ret.=bj('cod'.$id.'|css,play|pag='.$pag.',id='.$id,langp('cancel'),'btn');
$ret.=bj('cod'.$id.'|css,save|pag='.$pag.',id='.$id.'|css'.$id,langp('save'),'btsav');
return div($ret,'',self::$cb.$id);}

#build
static function findclasses($d){
$r=explode('}',$d); $rt=[];
foreach($r as $k=>$v){
	$tit=strto($v,'{');
	$txt=strfrom($v,'{'); $txt=trim($txt);
	if($txt)$rt[trim($tit)]=explode_k($txt,';',':');}
return $rt;}

static function findblocks($d){
$n=substr_count($d,'@media'); $ret=''; $rt=[];
for($i=0;$i<$n;$i++){
	$po=strpos($d,'@media'); if($po!==false){$ret.=substr($d,0,$po); $d=substr($d,$po);}
	$pb=strpos($d,'}}'); if($pb!==false){$in=substr($d,0,$pb); $d=substr($d,$pb+2);} else $in=$d;
	$pa=strpos($in,'{'); if($pa!==false){$t=substr($in,0,$pa); $tx=substr($in,$pa+1);} else $tx=$in;
	$rb=self::findclasses($tx);
	if($rb)$rt[$t]=$rb;}
$ra=self::findclasses($ret.$d);
return $ra+$rt;}

static function build($p){$pag=$p['pag']; $rt=[]; $rd=[];
$d=file_get_contents(self::f($pag));
$d=exclude($d,'/*','*/'); $d=deln($d); $d=delt($d); //eco($d);
$rt=self::findblocks($d);
sql::del(self::$db,$pag,'pag');
foreach($rt as $k=>$v)$rb[]=['tit'=>$k,'pag'=>$pag,'cod'=>json_enc($v,1)];
if(auth(6))sql::sav2(self::$db,$rb);
$r=sql('id,tit,cod',self::$db,'kvv',['pag'=>$pag]); //pr($r);
return $r;}

#play
static function playone($d,$id,$pag){$d=self::readjson($d);
$ret=div(build::code($d),'');
if(auth(6))$ret.=bj('cod'.$id.'|css,edit|pag='.$pag.',id='.$id,langp('edit'),'btn');
return $ret; }

static function play($p){$id=$p['id']; $pag=$p['pag'];
[$t,$d]=sql('tit,cod',self::$db,'rw',['id'=>$id]);
return self::playone($d,$id,$pag);}

static function read($r,$pag){$rt=[];
foreach($r as $k=>[$t,$v]){
//$ret.=toggle('|css,edit|pag='.$pag.',id='.$k,$t);
//$bt=textarea('cod'.$k,self::readjson($v),82,6);
//$bt=bj('cod'.$k.',,z|css,save|pag='.$pag.',id='.$k.'|cod'.$k,langp('save'),'btsav');
$ret=self::playone($v,$k,$pag);
$rt[]=[$t,div($ret,'','cod'.$k)];}
return tabler($rt,['name','edit']);}

#read
static function call($p){
$pag=$p['pag']; $bt=db::bt('db/system/global');
$r=self::build($p); //pr($r);
$ret=self::read($r,$pag);
return $bt.$ret;}

static function com($p){
return self::content($p);}

#content
static function content($p){
self::install();
$bt=self::admin();
$p['p1']=$p['p1']??'';
$r=['global','apps','day','night'];//,'fa','pictos'
$ret=batch($r,self::$cb.'|css,call|pag=$v');
//$ret.=bj(self::$cb.'|css,editor|',langp('edit'),'btn');
return $bt.div($ret,'sticky-edt').div('','pane',self::$cb);}
}
?>