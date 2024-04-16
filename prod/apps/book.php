<?php

class book extends appx{
static $private=0;
static $a='book';
static $db='book';
static $cb='bok';
static $cols=['tit','subt','bkg','img','pub','edt'];
static $typs=['var','var','var','var','int','int'];
static $db2='book_chap';
static $conn=1;
static $open=1;
static $tags=1;
static $gen=1;

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create(self::$db2,['bid'=>'int','idn'=>'int','chapter'=>'var','txt'=>'long'],1);}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function js(){
$d='function format(p,o){document.execCommand(p,false,o?o:null);}';
return ;}

static function headers(){
//head::prop('og:title',self::$title);
//head::prop('og:description',self::$description);
//head::prop('og:image',self::$image);
head::add('csscode','');
head::add('jscode',self::js());}

static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}

//edit
static function del($p){
$p['db2']=self::$db2;
return parent::del($p);}

//save
static function form($p){return parent::form($p);}
static function create($p){return parent::create($p);}

static function reorder($p){
$r=sql('id',self::$db2,'rv',['bid'=>$p['id']]);
if($r)foreach($r as $k=>$v)sql::up(self::$db2,'idn',$k+1,$v);
return parent::subcall($p);}

static function subops($p){$p['t']='chapter'; return parent::subops($p);}
static function subedit($p){$p['t']='chapter'; $p['urf']='idn'; return parent::subedit($p);}
static function subcall($p){$p['t']='chapter'; $p['urf']='idn';
$p['bt']=bj(self::$cb.'sub|book,reorder|t=chapter,id='.$p['id'],lang('reorder'),'btn');
$p['bt'].=bj(self::$cb.'sub|book,dlop|op=imp,id='.$p['id'],langp('import_art'),'btdel');
$p['bt'].=bj(self::$cb.'sub|book,dlop|op=impnte,id='.$p['id'],langp('import_notes'),'btdel');
return parent::subcall($p);}

//static function subform0($r){$r['html']='txt'; return parent::subform($r);}

static function subform($r){$ret=hidden('bid',$r['bid']);
$idn=$r['idn']?$r['idn']:sql('count(id)',self::$db2,'v',['bid'=>$r['bid']]);
$ret.=div(input('idn',$idn,4,lang('idn'),'',25,1).label('idn',lang('idn')));
$ret.=div(input('chapter',$r['chapter'],63,lang('chapter'),'',255).label('chapter',lang('chapter')));
$ret.=divarea('txt',conn::mincom($r['txt'],1),'article booktxt',1);
return $ret;}

//edit
static function edit($p){$p['sub']=1;
return parent::edit($p);}

//dl
static function compile_html($p,$r,$rb,$f){$ret=''; $id=$p['id']??'';
$ret=head::meta('http-equiv','Content-Type','text/html; charset=utf8');
$ret.='<body style="white-space:pre-line;">';
$ret.=tag('h1','',$r['tit']);
$ret.=tag('h3','',$r['subt']);
if($rb)foreach($rb as $k=>$v){
	$ret.=tag('h2','',$v['chapter']);//maths::roman($v['idn']).' - '.
	$ret.=conn::com($v['txt'],1);}
$ret.='</body>';
mkdir_r($f); write_file($f,$ret);
return lk('/'.$f,langp('download'),'btn');}

static function dlop($p){
$id=$p['id']??''; $op=$p['op']??'';
[$r,$rb]=self::build($p); $ret='';
$fa=str::normalize(str::utf8dec2($r['tit']),1); //$fa='book'.$id;
switch($op){
case('html'):$f='usr/'.$r['name'].'/'.$fa.'.html';
	self::compile_html($p,$r,$rb,$f);//if(!is_file($f))
	$t=ico('download').' '.$fa.'.html ('.fsize($f,1).')';
	$ret.=lk('/'.$f,$t,'btn',1); break;
case('epub'):$f='usr/'.$r['name'].'/'.$fa.'.epub';
	epub::build($r,$rb,$fa);//if(!is_file($f))
	$t=ico('download').' '.$fa.'.epub ('.fsize($f,1).')';
	$ret.=lk('/'.$f,$t,'btn',1); break;
case('art'):$rt='';
	foreach($rb as $k=>$v)$rt.='['.$v['chapter'].':h1]'.n().n().$v['txt'].n().n();
	$ex=sql('id','arts','v',['tit'=>$r['tit']]);
	if($ex)sql::up('arts','txt',$rt,$ex);
	else $ex=sql::sav('arts',['uid'=>ses('uid'),'tit'=>$r['tit'],'txt'=>$rt,'pub'=>0,'edt'=>0]);
	$ret=popup('art,edit|opn=1,id='.$ex,pic('art'),'btn'); break;
	if($aid)$tx=sql('txt','arts','v',$aid);
	else $tx=sql('txt','arts','v',['tit'=>$r['tit']]);
	if(!$tx)return input('aid','',2).bj($id.'dwl|book,dlop|op=imp,id='.$id.'|aid',pic('ok'),'btn');
	$tx=str_replace(':h]',':h2]',$tx);
	if(strpos($tx,':h1]') && strpos($tx,':h2]'))$s=':h1]';
	elseif(strpos($tx,':h1]'))$s=':h1]';
	elseif(strpos($tx,':h2]'))$s=':h2]';
	$ra=explode("\n",$tx); $rt=[]; $re=[]; $rf=[]; $n=0;//find chapters
	foreach($ra as $k=>$v){if(substr($v,-4)==$s)$rt[]=[$n,substr($v,1,-4)]; $n=$k;} //pr($rt);
	foreach($rt as $k=>$v){$na=$v[0]+2; if(isset($rt[$k+1]))$nb=$rt[$k+1][0]-$na; else $nb=$n; $txt='';
		if($nb)$txt=implode("\n",array_slice($ra,$na,$nb)); $rf[]=[$v[1],trim($txt)];} //pr($rf);
	if($rf)foreach($rf as $k=>$v){$i=$k+1;//save chapters
		$ex=sql('id',self::$db2,'v',['bid'=>$id,'idn'=>$i]);
		$rd=['bid'=>$id,'idn'=>$i,'chapter'=>$v[0],'txt'=>$v[1]]; //pr($rd);
		if($ex)sql::up2(self::$db2,$rd,$ex); else $ex=sql::sav(self::$db2,$rd);}
	$ret=bj(self::$cb.($id).'|book,edit|opn=1,id='.$ex,langp('see'),'btn'); break;
case('impnte'):$aid=$p['aid']??''; break;}
return $ret;}

static function dlbt($p){$id=$p['id']??'';
$ret=bj($id.'dwl|book,dlop|op=epub,id='.$id,langp('epub'),'btn');
$ret.=bj($id.'dwl|book,dlop|op=html,id='.$id,langp('html'),'btn');
if(auth(2))$ret.=bj($id.'dwl|book,dlop|op=art,id='.$id,langp('art'),'btn');
return $ret;}

static function length($id){$n=0;
$r=sql('txt',self::$db2,'rv',['bid'=>$id]);
foreach($r as $v)$n+=strlen($v);
return readtime($n);
return langnb('word',$n,'bton');}

//play
static function build($p){$id=$p['id']??'';
$ra=sql::inner('name,tit,subt,bkg,img',self::$db,'login','uid','ra',$id);
$rb=sql('idn,chapter,txt',self::$db2,'rr','where bid="'.$id.'" and idn>0 order by idn');
return [$ra,$rb];}

static function chapitres($p){$id=$p['id']??''; $c=cookie('book'.$id); $bt='';
$rb=sql('id,chapter,idn',self::$db2,'','where bid="'.$id.'" and idn>0 order by idn');
$j=self::$cb.$id.'|book,play|id='.$id.',idb='; $lk='book/'.$id.'/';
//if($rb)foreach($rb as $k=>$v)$bt.=bjk($j.$v[0],$v[1],''.($c==$v[0]?' active':''),$lk.$v[2]);
if($rb)foreach($rb as $k=>$v)$bt.=lku($lk.$v[2],$v[1],$c==$v[0]?' active':'');
else $bt.=help('no chapter');
return div($bt,'list');}

static function menu($p){$id=$p['id']??'';
$r=sql::inner('name,tit,subt,bkg,img',self::$db,'login','uid','ra',$id);
$ret=self::cover($id,$r).div('','clear');
$ret.=bubble('profile,call|sz=small,usr='.$r['name'],'@'.$r['name'],'btn');
$ret.=toggle($id.'dwl|book,dlbt|id='.$id,langpi('export'),'btn').span('','',$id.'dwl');
$ret.=toggle('mnuchap'.$id.'|book,chapitres|id='.$id,langp('open'),'btn');//maths::roman($v[2]).'. '.
$ret.=self::length($id);
$ret.=div('','tgbox','mnuchap'.$id);
return div($ret,'bookmnu');}

static function mark($p){
$id=$p['id']; $idb=$p['idb']; $c=cookie('book'.$id);
if($c==$idb)$p['c']=rmcookie('book'.$id); else $p['c']=cookie('book'.$id,$idb);
return self::play($p);}

static function nav($p,$rb){
$id=$p['id']??''; $idb=$p['idb']??''; $idn=val($p,'idn',$rb['idn']); $c=$p['c']??'';
$cb=self::$cb; $ret=''; $prev=''; $next=''; $lk='book/'.$id.'/';
$r=sql('id',self::$db2,'rv','where bid='.$id.' order by idn asc');
foreach($r as $k=>$v)
	if($v==$idb){if(isset($r[$k-1]))$prev=$r[$k-1]; if(isset($r[$k+1]))$next=$r[$k+1];}
if($prev)$ret.=bjk($cb.$id.',,,scrollTop|book,play|id='.$id.',idb='.$prev,langp('previous'),'btn',$lk.($idn-1));
//if($prev)$ret.=lku($lk.($idn-1),langp('previous'),'btn');
$c=$c?$c:cookie('book'.$id); $c=$c==$idb?'btok':'btn';
$ret.=bj($cb.$id.'|book,mark|id='.$id.',idb='.$idb,langpi('markup'),$c);
if($next)$ret.=bjk($cb.$id.',,,scrollTop|book,play|id='.$id.',idb='.$next,langp('next'),'btn',$lk.($idn+1));
//if($prev)$ret.=lku($lk.($idn+1),langp('next'),'btn');
$ret.=lk('/book/'.$id.'/'.$idn,ico('link'),'btn');
if(self::own($id))$ret.=bj('bok'.$id.'|book,subedit|id='.$id.',idb='.$idb,langpi('edit'),'btn');
return $ret;}

static function reader($p){
$id=$p['id']??''; $idb=$p['idb']??''; $cb=self::$cb;
//$r=sql('tit',self::$db,'ra',$id);
$r=sql::inner('name,tit,subt',self::$db,'login','uid','ra',$id);
$rb=sql('idn,chapter,txt',self::$db2,'ra',$idb);
//$ret=div(bjk($cb.$id.'|book,play|mnu=1,id='.$id,pic('top').' '.$r['tit'],'','book/'.$id),'btit booknfo');
$ret=div(lku('book/'.$id,pic('top').' '.$r['tit']),'btit booknfo');
$nav=self::nav($p,$rb);
$ret.=div($nav,'booknfo');
$ret.=tag('h2','',$rb['chapter']);//maths::roman($rb['idn']).' - '.
$txt=conn::com($rb['txt'],1);
$ret.=div($txt,'booktxt','edt'.$id.'-'.$idb).' ';
//$ret.=tag('div',['contenteditable'=>'off','class'=>'booktxt','id'=>'edt'.$id.'-'.$idb],$txt);
$ret.=div($nav,'booknfo');
return div($ret,'');}

static function play($p){$id=$p['id']??''; $idb=$p['idb']??''; $idn=$p['p2']??'';
if(!$idb && $idn){$idb=sql('id',self::$db2,'v',['bid'=>$id,'idn'=>$idn]); if($idb)$p['idb']=$idb;}
if(!$idb)$ret=self::menu($p);
else $ret=self::reader($p);
return $ret;}

static function cover($id,$r=[]){$s='';
if(!isset($r['subt']))$r=sql::inner('name,tit,subt,bkg',self::$db,'login','uid','ra',$id);
$ret=tag('h1','',$r['tit']);
if($tb=$r['subt'])$ret.=tag('h3','',$tb);
//if($nm=$r['name'])$ret.=tag('h3','',$nm);
if($tb=$r['bkg'])$s=theme($r['bkg']);
return div($ret,'bookcov','',$s);}

static function preview($p){
$r=sql('all',self::$db2,'ra',$p['idb']);
$txt=$r['txt']; if(self::$conn==1)$txt=conn::com($txt);
if($p['epub']??'')$rt=conn::call(['msg'=>$r['txt'],'mth'=>'minconn','ptag'=>1,'opt'=>'epub']); else//
$rt=conn::call(['msg'=>$r['txt'],'mth'=>'reader','ptag'=>1,'opt'=>'']);
$ret=textarea('',$rt);
return div($rt,'txt').$ret;}

//stream
static function stream($p){$p['cover']=1;
return parent::stream($p);}

//call
static function txt($p){$id=$p['id']??'';
if($id)$txt=sql('txt',self::$db,'v',$id);
if($txt)return conn::call(['msg'=>$txt,'ptag'=>1]);}

static function tit($p){
return parent::tit($p);}

static function call($p){
//return parent::call($p);
return div(self::play($p),'book',self::$cb.$p['id']);}

static function com($p){
return parent::com($p);}

#content
static function content($p){
//self::install();
return parent::content($p);}
}

?>