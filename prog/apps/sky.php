<?php
class sky extends appx{
static $private=0;
static $a='sky';
static $db='sky';
static $cb='sk';
static $cols=['tit','css','pub'];
static $typs=['var','text','int'];
static $tags=0;
static $open=1;

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; $r=parent::admin($p);
$r[]=['','pop','sky,see','view','watch'];
return $r;}

static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','
	.skyframe{display:inline-block; width:378px; height:300px; border:1px solid black;}
	.skylist{display:inline-block; width:40px; height:30px; border:1px solid black;}');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){
//$p['db2']=self::$db2;
return parent::del($p);}

static function save($p){$p['css']=deln($p['css'],' '); return parent::save($p);}
static function modif($p){$p['css']=deln($p['css'],' '); return parent::modif($p);}
static function create($p){$p['help']=1;
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}
static function subform($r){return parent::subform($r);}

//skygen
static function mkclr($p){$a=val($p,'a',rand(0,10)); 
for($i=0;$i<3;$i++)$r[$i]=$p[$i]??rand(0,255);
return 'rgba('.$r[0].','.$r[1].','.$r[2].','.($a?$a/10:0).')';}

static function mkgrad($p){$ret='';
$ra=['linear-gradient','radial-gradient'];//repeating-linear-gradient
$raa=['bottom','left','right','top']; $rab=['ellipse','circle'];
$o=val($p,'o');//if($o==1)
$na=0; $nab=0; $n=3; $nc=2;
for($i=0;$i<$n;$i++)$rb[]=self::mkclr([]);
$s=$ra[$na].'('.($na==0?$raa[$nab]:$rab[$nab]).', '.implode(',',$rb).')';
return div($ret,'skyframe','','background-image:'.$s.';');}

static function dsgn($p){$ret='';
$ra=['linear-gradient','radial-gradient'];//repeating-linear-gradient
$rb=['bottom','left','right','top'];
$rb=['ellipse','circle'];
$o=val($p,'o');
if($o=='h2')$s='linear-gradient(to bottom,'.self::mkclr($p).','.self::mkclr($p).')';
if($o=='h3')$s='linear-gradient(to bottom,'.self::mkclr($p).','.self::mkclr($p).','.self::mkclr($p).')';
if($o=='v2')$s='linear-gradient(to right,'.self::mkclr($p).','.self::mkclr($p).')';
if($o=='v3')$s='linear-gradient(to right,'.self::mkclr($p).','.self::mkclr($p).','.self::mkclr($p).')';
elseif($o=='double')$s='linear-gradient(to bottom, '.self::mkclr($p).','.self::mkclr($p).'),
linear-gradient(to right, '.self::mkclr($p).','.self::mkclr($p).')';
elseif($o=='tripli')$s='linear-gradient(to bottom,'.self::mkclr($p).','.self::mkclr($p).'),
linear-gradient(to left,'.self::mkclr($p).','.self::mkclr($p).'),
linear-gradient(to right,'.self::mkclr($p).','.self::mkclr($p).')';
elseif($o=='quatro')$s='linear-gradient(to right, '.self::mkclr($p).','.self::mkclr($p).'), linear-gradient(to top, '.self::mkclr($p).','.self::mkclr($p).'), 
linear-gradient(to left, '.self::mkclr($p).','.self::mkclr($p).'), 
linear-gradient(to bottom, '.self::mkclr($p).','.self::mkclr($p).')';
elseif($o=='penta')$s='radial-gradient(circle at left, '.self::mkclr($p).','.self::mkclr($p).'), radial-gradient(circle at right, '.self::mkclr($p).','.self::mkclr($p).'), 
linear-gradient(to top, '.self::mkclr($p).','.self::mkclr($p).'), 
linear-gradient(45deg, '.self::mkclr($p).','.self::mkclr($p).')';
elseif($o=='sexa')$s='radial-gradient(circle at center, '.self::mkclr($p).','.self::mkclr($p).'), radial-gradient(circle at center, '.self::mkclr($p).','.self::mkclr($p).'), 
linear-gradient(to top, '.self::mkclr($p).','.self::mkclr($p).'), 
linear-gradient(45deg, '.self::mkclr($p).','.self::mkclr($p).')';
elseif($o=='frame'){$c=self::mkclr($p);  $b=self::mkclr($p);
$s='linear-gradient(to bottom, rgba(0,0,0,.9), '.$c.' 20%, '.$c.' 80%, rgba(0,0,0,.9)),
linear-gradient(to right, rgba(0,0,0,.9), '.$b.' 10%, '.$b.' 90%, rgba(0,0,0,.9))';}
elseif($o=='suns'){$a=self::mkclr($p); $b=self::mkclr($p); $c=self::mkclr($p); 
$s='linear-gradient(to top, '.$a.', '.$b.', '.$c.'),
radial-gradient(circle at center, rgba(255,255,0,.9), rgba(255,255,0,.9) 25%, rgba(255,255,0,0) 30%),
linear-gradient(to bottom, '.$c.','.$b.',rgba(0,0,0,0) 50%)';}
else $s='linear-gradient(to bottom,'.self::mkclr($p).','.self::mkclr($p).')';
$ret=hidden('skydefs',$s);
$bt=btj(lang('use'),atj('innfromval',['skydefs','css']),'btn').br();
return $bt.div($ret,'skyframe','','background-image:'.$s.';');}

static function gen($p){$ret=''; $bt='';$o=val($p,'o');//
$r=['h2','h3','v2','v3','double','tripli','quatro','penta','sexa','frame','suns'];
foreach($r as $v)$bt.=bj('skygen|sky,dsgn|o='.$v,$v,'btn');
return $bt.div(self::dsgn($p),'','skygen');}

//form
static function form($p){
return parent::form($p);}

static function edit($p){
$p['bt']=popup('sky,gen',langp('design'),'btn');
return parent::edit($p);}

static function see(){$ret='';
$r=sql('tit,css','sky','kv','where uid="'.ses('uid').'" or pub>2'); //p($rb);
foreach($r as $k=>$v)$ret.=div($k,'skyframe','','background-image:'.$v.';');
return $ret;}

#build
static function build($p){
if(is_numeric($p['id']))return sql('css',self::$db,'v',$p['id']);
else return sql('css',self::$db,'v','where tit="'.$p['id'].'"');}

static function play($p){
$ret=self::build($p);
return div(div('','skyframe','','background-image:'.$ret.';'));}

static function cover($id,$v=[]){
$sty='background-image:'.sql('css','sky','v',$id).';';
return span('','skyframe','',$sty).span($v['bt']);
return bj($v['j'],div(ico($v['ic']).$v['bt'],'skyframe','',$sty));}

static function listing($id,$v=[]){$a=self::$a;
$sty='background-image:'.sql('css','sky','v',$id).';';
return div($v['bt'],'skylist','',$sty);}

static function stream($p){$p['cover']=1; $p['listing']=1;
return parent::stream($p);}

#call (read)
static function slct($p){$ret=''; $rid=$p['rid']??'';
$r=sql('tit,css','sky','kv','where uid='.ses('uid').' or pub>2'); //p($r);
foreach($r as $k=>$v)$ret.=tag('a',['onclick'=>atj('val',['-'.$k,$rid]),'style'=>'height:40px; width:100px; display:inline-block; background-image:'.$v],$k);
$ret.=bj('popup|sky',langp('create'),'');
return div($ret,'');}

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

static function api($p){
return parent::api($p);}
}
?>