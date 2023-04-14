<?php
class product extends appx{
static $private=0;
static $a='product';
static $db='product';
static $cb='bnk';
static $cols=['typ','tit','descr','img','price','rate','state','status'];
static $typs=['int','var','text','var','int','int','int','int'];
static $db2='product_stock';
static $t='tit';
static $tags=0;
static $open=0;
static $credits=['red','blue','green'];//mass,time,space
static $status=['used','for lent','for rent','for sale','loan','rented','sold','destroyed'];
static $roles=['man','corp','rsrc','cmd'];

function __construct(){
$r=['a','db','cb','cols'];
foreach($r as $v)parent::$$v=self::$$v;}

static function install($p=''){
parent::install(array_combine(self::$cols,self::$typs));
//red,blue,green credits//role: profile status
sql::create('product_stock',['uid'=>'int','role'=>'int','red'=>'int','blue'=>'int','green'=>'int'],1);
sql::create('product_moves',['aid'=>'int','val'=>'int','to'=>'int'],1);
sql::create('product_criters',['aid'=>'int','val'=>'int'],1);
sql::create('product_featurs',['cid'=>'int','val'=>'int'],1);
//sql::create('product_crons',['aid'=>'int','val'=>'int','to'=>'int','at'=>'int'],1);
sql::create('product_contr',['cid'=>'int','if'=>'var'],1);}

static function admin($p){$p['o']='1';
return parent::admin($p);}

static function titles($p){return parent::titles($p);}
static function js(){return '';}
static function headers(){
head::add('csscode','
.coin{background:#ffffff; border:1px solid #fff; display:inline-block; padding:4px 6px;}
.coin:hover{box-shadow:0,0,6px,rgba(0,0,0,0.4);}
.red,.blue,.green{text-align:center;}
.red{background:#ff4444; color:white;} .red:hover{background:#ff4444;}
.blue{background:#4444ff; color:white;} .blue:hover{background:#4444ff;}
.green{background:#44ff44; color:black;} .green:hover{background:#44ff44;}
.instit{background:#f4f4f4; color:black;}
.money{background:#f4f4f4; margin:6px;}');
head::add('jscode',self::js());}

#edit
static function collect($p){
return parent::collect($p);}

static function del($p){
return parent::del($p);}

static function save($p){
return parent::save($p);}

static function modif($p){//pr($p);
return parent::modif($p);}

static function form($p){$ret='';
$cols=sql::cols(self::$db,3,0); $cls=implode(',',array_keys($cols));
foreach(self::$credits as $k=>$v)$rty[$k]=lang($v.'_descr');
foreach($cols as $k=>$v){$val=$p[$k]??'';
	if($k=='typ')$bt=radio($k,$rty,$val,'');
	elseif($k=='img')$bt=input($k,$val).upload::img($k);
	elseif($k=='rate'){
		if($val>0)$bt=bar($k,$val,10,0,100,'1','');
		else $bt=hidden($k,$val);}
	elseif($k=='state')$bt=bar($k,$val,10,0,100,'1','');
	elseif($k=='status')$bt=select($k,self::$status,$val,0,1);
	elseif($v=='int')$bt=input($k,$val,'','','1',5);
	else $bt=input($k,$val,63,'','',255);
	$ret.=input_row($k,$bt,$k);}
$ret=div($ret,'table');
return $ret;}

static function edit($p){
return parent::edit($p);}

static function add($p){
$typ=$p['typ'];
$ret=self::form($p);
return $ret;}

static function create($p){$ret='';
$ret=div(bj(self::$cb.'|product,add|typ=red',langp('add'),'btn red'),'coin');
$ret.=div(bj(self::$cb.'|product,add|typ=blue',langp('add'),'btn blue'),'coin');
$ret.=div(bj(self::$cb.'|product,add|typ=green',langp('add'),'btn green'),'coin');
return div($ret,'');}

#role
static function savrole($p){$set=val($p,'set',0);
if($set)sql::up(self::$db2,'role',$set,ses('uid'),'uid');
return lang(self::$roles[$set]);}

static function setrole($p){$ret='';
$role=sql('role',self::$db2,'v',['uid'=>ses('uid')]);
foreach(self::$roles as $k=>$v){$c=$k==$role?'active':'';
	$ret.=bj('role|product,savrole|set='.$k,lang($v),$c);}
return div($ret,'list');}

static function playrole($r){$d=$r['role']; //p($r);
$ra=self::$roles; $role=$ra[$d];
$ret=div(lang($role),'','role');
$ret.=bubble('product,setrole',lang('set role'),'small');
return div($ret,'coin');}

#build
static function build($p){
//$cols=sql::cols(self::$db,0,1);
$cols=implode(',',self::$cols);
return sql('id,'.$cols,self::$db,'ra',$p['id']);}

#account
static function create_stock(){
$r=[ses('uid'),'0','0','0','0'];
sql::sav(self::$db2,$r);
return $r;}

static function accounts($p){$ret=''; $typ=$p['typ'];
$r=sql('all',self::$db,'rr',['uid'=>ses('uid'),'typ'=>$typ]); //pr($r);
return $ret;}

static function coin($typ,$n){
$ret=div(lang($typ.'_money'),'btn');
$ret.=div($n,'btit');
//$ret.=bj(self::$cb.'|product,add|typ='.$typ,langpi('add'),'btn');
//$ret.=bj('account|product,account|typ='.$typ,langpi('view'),'btn');
return div(div($ret,'money'),'coin '.$typ);}

static function account(){
$r=sql('all',self::$db2,'ra',['uid'=>ses('uid')]); //pr($r);
if(!$r)$r=self::create_stock();
$ret=self::playrole($r);
foreach(self::$credits as $v)$ret.=self::coin($v,$r[$v]);
$ret.=div('','','account');
return $ret;}

#play
static function play($p){
$r=self::build($p); $a=self::$a; //pr($r);
$typ=self::$credits[$r['typ']];
$ret=div($r['tit'],'tit');
$ret.=div($r['descr'],'txt');
$rt[]=[lang('type'),div(lang($typ.'_descr'),'coin '.$typ)];
$rt[]=[lang('photo'),build::mini($r['img'])];
$rt[]=[lang('price'),$r['price']];
if($r['typ']>0)$rt[]=$rt[]=[lang('rate'),$r['rate']];
$rt[]=[lang('state'),$r['state'].'/100'];
$rt[]=[lang('status'),lang(self::$status[$r['status']])];
$ret.=tabler($rt);
return $ret;}

static function stream($p){
$p['t']='tit';
//$ret=self::account();
$ret=bj(self::$cb.'|product,account',langpi('stock'),'btn');
$ret.=parent::stream($p);
//$ret=self::play($p);
return $ret;}

#call (read)
static function tit($p){
$p['t']='tit';
return parent::tit($p);}

static function call($p){
return parent::call($p);}

#com (edit)
static function com($p){
return parent::com($p);}

#interface
static function content($p){
//self::install();
return parent::content($p);}
}
?>
