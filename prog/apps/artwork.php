<?php

class artwork extends appx{
static $private=0;
static $a='artwork';
static $db='artwork';
static $cb='bok';
static $cols=['tit','pub','edt'];
static $typs=['var','int','int'];
static $db2='artwork_arts';
static $tags=0;
static $conn=0;

function __construct(){
$r=['a','db','cb','cols','db2'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
sql::create(self::$db2,['bid'=>'int','conn'=>'var'],1);}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function js(){
$d='function format(p,o){document.execCommand(p,false,o?o:null);}';
return ;}

static function headers(){
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

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}

static function dskslct($p){$a=self::$a; $cb=self::$cb; $dr=val($p,'dir'); $bck=struntil($dr,'/');
$r=sql('type,com,picto,bt','desktop','','where uid="'.ses('uid').'" and dir like "/documents%" order by dir asc'); $ret='';
if($r)foreach($r as $k=>$v){[$typ,$com,$ic,$bt]=$v;
$app=struntil($com,','); $aid=strend($com,'=');
if($typ=='img')$app=$typ; if($aid=='')$conn=$app.':url'; else $conn=$aid.'/'.$app;
$ret.=bj($cb.'subedt|'.$a.',subform|bid='.$p['bid'].',conn='.$conn,ico($ic).$bt,'');}
return div($ret,'list');}

/*static function dskslct0($p){$a=self::$a; $cb=self::$cb; $dr=val($p,'dir'); $bck=struntil($dr,'/');
$r=sql('type,com,picto,bt','desktop','','where uid="'.ses('uid').'" and dir like "/documents%" order by dir asc'); $ret='';
//$ret=bj($cb.'edit|'.$a.',subedit|bid='.$p['bid'].',conn='.$p['conn'],langp('back'),'btn');
if($r)foreach($r as $k=>$v)$rb[$dir][$k]=$v;
if($r)foreach($rb as $dr=>$vr)foreach($vr as $k=>$v){
[$typ,$com,$ic,$bt]=$v; $app=struntil($com,','); $aid=strend($com,'=');
if($typ=='img')$app=$typ; elseif($typ=='')$app='url'; $conn=$aid.'/'.$app;
$ret.=bj($cb.'subedt|'.$a.',subform|bid='.$p['bid'].',conn='.$conn,ico($ic).$bt,'');}
return div($ret,'list');}*/

static function subform($r){$cls=sql::cols(self::$db2,3,2); $a=self::$a; $cb=self::$cb;
$ret=bj($cb.'subedt|'.$a.',dskslct|bid='.$r['bid'].',conn='.$r['conn'],langp('select'),'btsav');
$ret.=hidden('bid',$r['bid']); $conn=str_replace('/',':',$r['conn']);
$ret.=div(input('conn',$conn,63,lang('connector')));
if($conn)$ret.=conn::reader($conn);
return $ret;}

//appx
static function edit($p){$p['sub']=1;
return parent::edit($p);}

//play
static function build($p){$id=$p['id']??'';
$ra=sql::inner('name,tit',self::$db,'login','uid','ra',$id);
$rb=sql('conn',self::$db2,'rr',['bid'=>$id]);
return [$ra,$rb];}

static function reader($p){$id=$p['id']??'';
$r=sql::inner('name,tit',self::$db,'login','uid','ra',$id);
$rb=sql('id,conn',self::$db2,'kv',['bid'=>$id]);
$ret=tag('h1','',$r['tit']);
$ret.=div(lk('/@'.$r['name'],ico('user').$r['name'],'btn'));
foreach($rb as $k=>$v)$ret.=conn::reader($v);
return $ret;}

static function play($p){
return div(self::reader($p));}

//stream
static function stream($p){
return div(parent::stream($p),'');}

//call
static function txt($p){$id=$p['id']??'';
if($id)$txt=sql('conn',self::$db,'v',$id);
if($txt)return conn::call(['msg'=>$txt,'ptag'=>1]);}

static function tit($p){$id=$p['id']??'';
if($id)return sql('tit',self::$db,'v',$id);}

static function call($p){
return parent::call($p);}

static function com($p){
return parent::com($p);}

#content
static function content($p){
//self::install();
return parent::content($p);}
}
?>
