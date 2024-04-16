<?php
//twapi use a second db, here in the subcall mode (attached datas)
class twapi extends appx{
static $private=0;
static $a='twapi';
static $db='twapi';
static $cb='twp';
static $cols=['tit','command','mode','opt','pub'];
static $typs=['var','var','var','var','int'];
static $conn=0;
static $gen=0;
static $tags=0;
static $open=0;
static $db2='twapi_vals';
static $db2_cols=['bid'=>'int','aid'=>'bint'];

static function install($p=''){
sql::create(self::$db2,self::$db2_cols,1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}

//injected javascript (in current page or in popups)
static function js(){return '';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
//collected datas from public forms
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){
$p['pub']=0;//default privacy
return parent::create($p);}

#subcall
//used in secondary database db2
static function subops($p){$p['t']='aid'; return parent::subops($p);}//$p['bt']='';
static function subform($r){return parent::subform($r);}
static function subedit($p){$p['t']='aid'; return parent::subedit($p);}//$p['data-jb']//2nd save
static function subcall($p){$p['t']='aid'; $p['player']='subplay';
[$id,$command,$mode,$opt]=vals($p,['id','command','mode','opt']);
$j=self::$cb.'sub,,z|twapi,capture|id='.$p['id'].',command='.$command.',mode='.$mode.',opt='.$opt;
if($mode==5)$p['bt']=bj(self::$cb.'sub|twapi,addlist|id='.$id,langp('addfromlist'),'btsav');
else{$p['bt']=bj($j.',sens=0',langp('capture').pic('up'),'btn');
$p['bt'].=bj($j.',sens=1',langp('capture').pic('down'),'btn');}
$p['bt'].=bubble('twitter,twusr',langp('twapi'),'btn');
return div(parent::subcall($p),'',self::$cb.'subcall');}

static function subplay($p,$r){
$j=self::$cb.'sub,,z|twapi,capture|id='.$p['id'].',command='.$p['command'].',mode='.$p['mode'];
$r=self::build($p);
if($r)foreach($r as $k=>$v){
	$r[$k]['date']=is_numeric($v['date'])?date('d/m/Y H:i:s',$v['date']):$v['date'];}
if($r)$bt=download::mkcsv($r,$p['id']);
if($r)foreach($r as $k=>$v){
	$r[$k]['twid']=lk(twitter::twurl($v['screen_name'],$v['twid']),$v['twid'],'grey',1);
	//$r[$k]['date']=is_numeric($v['date'])?date('d/m/Y',$v['date']):$v['date'];
	array_unshift($r[$k],bj($j.',opt='.$v['twid'].',sens=0',pic('down'),'btn'));}
if($v){$rh=array_keys($v); array_unshift($rh,'add'); array_unshift($r,$rh);}
if($r)return $bt.tabler($r,1);}

static function savlist($p){
$d=val($p,'addlst'); $id=$p['id']??''; 
$d=str_replace("\n",' ',$d); $r=explode(' ',$d);
foreach($r as $k=>$v)if($v && is_numeric($v))$rb[]=['bid'=>$id,'aid'=>$v];
sql::sav2(self::$db2,$rb);
return self::subcall($p);}

static function addlist($p){
$ret=textarea('addlst','',32,4);
$ret.=bj(self::$cb.'subcall|twapi,savlist|'.prm($p).'|addlst',langp('add'),'btsav');
return div($ret,'','twlist');}

#capture
//save twit
static function twsave($r,$bid,$aid){
$ex=sql('id',self::$db2,'v','where aid='.$aid.' and bid='.$bid);
if(!$ex)sql::sav(self::$db2,[$bid,$aid]);
$ex=sql('id',twitter::$db2,'v','where twid='.$aid);
if(!$ex)sql::sav(twitter::$db2,$r);}

static function capture($p){$p['player']='subplay';
[$command,$mode,$opt,$sens]=vals($p,['command','mode','opt','sens']); $p['aid']='';
if($sens)$p['max']=sql('aid',self::$db2,'v','where bid='.$p['id'].' order by aid');
else $p['min']=sql('aid',self::$db2,'v','where bid='.$p['id'].' order by aid desc limit 1');
$r=self::capture_tl($p);
if($r)foreach($r as $rb)self::twsave($rb,$p['id'],$rb['twid']);
return self::subcall($p);}

/*static function capture_tw($p){$id=$p['id']??'';
$t=new twit(self::init());
if(is_numeric($id))return self::datas($t->read($id));}*/

static function capture_tl($p){//pr($p);
[$command,$mode,$max,$min,$opt,$sens]=vals($p,['command','mode','max','min','opt','sens']);
$t=new twit(twitter::init()); $rb=[]; $until=''; $nb=20;
//if(!$sens && $mode==1 && !$max && $until)$max=$until;
if(!$sens && $mode==1 && $opt){if(is_numeric($opt))$max=$opt; else $until=$opt;}
if($mode==1){$q=$t->search($command,$nb,$max,$min,$until); //pr($q);
	if(isset($q['statuses']))$q=$q['statuses'];}
elseif($mode==2)$q=$t->timeline($command,$nb,$max,0,$min);
elseif($mode==3)$qu=$t->retweeters($command,$nb);
elseif($mode==4)$q=$t->favorites($command,$max,$nb);
if(!empty($q))foreach($q as $r)if(isset($r['id']))$rb[]=twitter::datas($r);
//if(!empty($qu) && isset($qu['ids']))$rb=twitter::playusr($qu);
//pr($rb);
return $rb;}

/*static function player($r){$id=$r['aid'];
$ret=twitter::play($r,'');
return tag('section',['id'=>$id,'class'=>'paneb'],$ret);}*/

#form
static function fc_mode($k,$val,$v){
return select($k,[1=>'search','timeline','retweets','likes','list'],$val);}

static function form($p){
$p['fcmode']=1;
return parent::form($p);}

static function edit($p){
$p['collect']=self::$db2;
$p['help']=1;
$p['sub']=1;
//$p['bt']='';
return parent::edit($p);}

#build
static function build($p){$id=$p['id']??'';
$cols=implode(',',array_keys(twitter::$db2_cols)); $rb='';
$ra=sql('aid',self::$db2,'rv','where bid='.$id.' order by aid desc');
if($ra)$rb=sql('id,'.$cols,twitter::$db2,'rr','where twid in ('.implode(',',$ra).')');
//$rb=sql('aid',self::$db2,'rr','right join '.twitter::$db2.' on aid=twid where bid='.$id.' order by aid desc');
//if(count($ra)!=count($rb))foreach($ra as $k=>$v)$rb[]=twitter::read($v,1,2);
return $rb;}

static function template(){//return parent::template();
return '[[(tit)*class=tit:div][[txt:var]*class=txt:div]*class=paneb:div]';}//will nl2br()

#play
static function playtab($p){
$r=self::build($p);
$rh=twitter::$db2_cols;
$r=array_merge($rh,$r);
if($r)foreach($r as $k=>$v)
	$r[$k]['twid']=lk(twitter::twurl($v['screen_name'],$v['twid']),$v['twid'],'grey',1);
return tabler($r);}

static function play($p){$ret='';
$r=self::build($p);
//if($r)foreach($r as $k=>$v)$ret.=twitter::play($v);
$j='twapi,play|'.prm($p);
if($r)$ret=batch_pages($r,$p,$j,'twitter','play');//com to resav
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
//self::install();
return parent::content($p);}

static function api($p){//callable datas
return parent::api($p);}
}