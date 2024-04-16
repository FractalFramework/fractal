<?php
class sticky extends appx{
static $private=0;
static $a='sticky';
static $db='sticky';
static $cb='stk';
static $cols=['tit','txt','pub'];
static $typs=['var','text','int'];
static $conn=0;
static $db2='sticky_vals';
static $open=0;
static $qb='';//db
static $obso='';

static function install($p=''){
sql::create(self::$db2,['uid'=>'int','bid'=>'int','start'=>'int','end'=>'int','pad'=>'var','txt'=>'var'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
//static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function titles($p){
$d=$p['_m']??'';
$r['add_note']='add note';
$r['play']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){return parent::create($p);}

//subform
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subform($p){return parent::subform($p);}
static function subedit_form($r){return parent::subedit_form($r);}

//form
static function form($p){return parent::form($p);}
static function edit($p){$p['collect']=self::$db2; return parent::edit($p);}

#edit
static function save_note($p){
$a=self::$a; $db=self::$db2; $cb=self::$cb; $cols=sql::cols($db,3);
$rid=$p['rid']; $p['txt']=$p[$rid];
$r=parent::batch_vals($p,$cols,1); //pr($r);
sql::sav($db,$r);
return self::play(['id'=>$p['bid']]);}

static function add_note($p){$rid=randid('stk');
$s=$p['start']; $e=$p['end']; $txt=$p['txt']; $id=$p['id'];
//$ret=help('save note');
$ret=div($txt,'helpxt','pad');
$ret.=textarea($rid,'');
$ret.=bj(self::$cb.$id.',,x|sticky,save_note|bid='.$id.',rid='.$rid.',start='.$s.',end='.$e.'|pad,'.$rid,langp('save'),'btsav');
return $ret;}

static function del_note($p){$del=val($p,'del');
if($del)sql::del(self::$db2,$del);
return self::stream_notes($p);}

static function modif_note($p){
$a=self::$a; $db=self::$db2; $cb=self::$cb; $cols=sql::cols($db,3);
$id=$p['id']; $txt=$p['pad'.$id];
sql::up(self::$db2,'txt',$txt,$id);
return;}

static function edit_note($p){$id=$p['id']??'';
$r=sql('pad,txt',self::$db2,'ra',$id);
$ret=div($r['pad'],'tit');
$ret.=textarea('pad'.$id,$r['txt']);
$ret.=bj('socket,,x|sticky,modif_note|id='.$id.'|pad'.$id,langp('modif'),'btsav');
$ret.=bj('socket,,x|sticky,del_notes|del='.$id,langp('del'),'btdel');
return $ret;}

static function stream_notes($p){
$txt=sql('txt',self::$db,'v',$p['id']);
$id=$p['id']??''; $rb[]=[lang('pad'),lang('edit'),lang('delete'),lang('error')];
if($id)$r=self::build2($p,1);
if($r)foreach($r as $k=>$v){$ok=1;
	$s=$v['start']; $e=$v['end'];
	$ok=self::intersections($r,$v,$txt);
	$ex=strpos($txt,$v['pad']); if($ex===false)$ok=0;
	if(!$ok)$er=picto('alert'); else $er='';
	$sav=bj('popup|sticky,edit_note|id='.$v['id'],picto('edit'),'');
	$del=bj('obso|sticky,del_note|id='.$id.',del='.$v['id'],picto('del'),'');
	$rb[]=[$v['pad'],$sav,$del,$er];}
if(!$rb)return lang('empty');
else return div(tabler($rb),'','obso');}

#build
static function build($p){
return parent::build($p);}

static function build2($p,$o=''){
if(!$o)$ra['uid']=ses('uid');
$r=sql('id,uid,start,end,pad',self::$db2,'rr','where bid='.$p['id'].' order by start');
return $r;}

static function pad_read($p){$id=$p['id']; $bt='';
$r=sql::inner('name,pad,txt',self::$db2,'login','uid','ra','where '.self::$db2.'.id='.$id); //pr($r);
if(!$r)return lang('empty');
$bt=bubble('profile,call|usr='.$r['name'].',sz=small',$r['name'],'grey small',1).' ';
$bt.=popup('sticky,edit_note|id='.$id,picto('edit'),'btn');//div($r['pad'],'helpxt').
return div(div($r['txt'],'txt').div($bt,''),'helpxt');}

static function pad($p,$id){
return bubble('sticky,pad_read|id='.$id,$p,'stabilo');}

static function intersections($r,$v,$txt){
$s=$v['start']; $e=$v['end']; $pad=$v['pad']; $id=$v['id']; $ok=1;
foreach($r as $k=>$v)if($v['id']!=$id){
	if($s>=$v['end'] or $e<=$v['start'])$ok=1; else return 0;}
if(strpos($txt,$pad)===false)return 0;
return $ok;}

static function detection($ret,$v){static $decal=0;
$s=$v['start']+$decal; $e=$v['end']+$decal; $pad=$v['pad']; $id=$v['id'];
$d1=mb_substr($ret,0,$s); $d2=mb_substr($ret,$s,$e-$s); $d3=mb_substr($ret,$e);
//pr([$s,$e,$pad,$d2,$decal]);
//if($d2!=$pad){$pos=strpos(substr($ret,$s),$pad); $diff=$e-$s;
//	$d1=mb_substr($ret,0,$pos); $d2=mb_substr($ret,$s,$pos-$diff); $d3=mb_substr($ret,$diff);}
$ret=$d1.'['.$d2.'|'.$id.':sticky]'.$d3; $decal+=strlen($id)+1;
//return str_replace($pad,'['.$pad.'|'.$id.':sticky]',$ret);
return $ret;}

static function stabilo($p){
$r=self::build2($p); $txt=$p['txt']; //pr($r);
$ret=$txt; $rb=[]; $rtb=str::utf8dec($ret);
if($r)foreach($r as $k=>$v){$ok=1;
	$ex=strpos($txt,$v['pad']);
	$ok=self::intersections($r,$v,$txt);
	//if($ex===false)$ok=0;
	//if($ok)$ret=self::detection($ret,$v);
	if($ok)$ret=str_replace($v['pad'],'['.$v['pad'].'|'.$v['id'].':sticky]',$ret);
	else self::$obso[]=$v['id'];}
return $ret;}

static function play($p){
$rid=randid('stk'); $id=$p['id'];
$r=self::build($p);
$txt=$r['txt']; $p['txt']=$txt;
$txt=self::stabilo($p);
$txt=conn::load(['msg'=>$txt,'ptag'=>1]);
$prm=['id'=>$rid,'class'=>'article padarea'];//,'contenteditable'=>'true'
$rp=['onmouseup','ondblclick']; $jb='useslct(this,\''.$rid.'\',\''.$id.'\');';//,'onmouseup'
foreach($rp as $v)$prm[$v]=$jb;
$ret=div($r['tit'],'tit');//if(self::$obso)
$ret.=span(popup('sticky,stream_notes|id='.$id,langp('my notes'),'btsav'),'right');
$ret.=tag('div',$prm,$txt);
$ret.=head::jscode('var xid='.$id.';');
return $ret;}

static function stream($p){
//$p['t']=self::$cols[0];
return parent::stream($p);}

#call (read)
static function tit($p){
//$p['t']=self::$cols[0];
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){return parent::com($p);}
static function uid($id){return parent::uid($id);}
static function own($id){return parent::own($id);}

#interface
static function content($p){
self::install();//
return parent::content($p);}

}
?>