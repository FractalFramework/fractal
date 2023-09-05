<?php
class doodle extends appx{
static $private=1;
static $a='doodle';
static $db='doodle';
static $cb='ddl';
static $cols=['tit','date','nbdays'];
static $typs=['var','date','int'];
static $conn=0;
static $db2='doodle_valid';
static $open=1;
static $tags=1;
static $qb='';

//first col,txt,answ,com(settings),code,day,clr,img,nb,cl,pub
//$db2 must use col "bid" <-linked to-> id

static function install($p=''){
sql::create(self::$db2,['bid'=>'int','uid'=>'int','day'=>'int','ok'=>'int'],1);
parent::install(array_combine(self::$cols,self::$typs));}

static function admin($p){$p['o']=1; return parent::admin($p);}
static function titles($p){return parent::titles($p);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#edit
static function collect($p){return parent::collect($p);}
static function del($p){$p['db2']=self::$db2; return parent::del($p);}
static function save($p){return parent::save($p);}
static function modif($p){return parent::modif($p);}
static function create($p){
//$p['pub']=0;//default privacy
return parent::create($p);}

//subcall
static function subops($p){return parent::subops($p);}
static function subedit($p){return parent::subedit($p);}
static function subcall($p){return parent::subcall($p);}
static function subform($r){return parent::subform($r);}

//form
//static function fc_tit($k,$v){}
static function form($p){
//$p['html']='txt';
//$p['fctit']=1;
//$p['barfunc']='barlabel';
return parent::form($p);}

static function edit($p){
$p['collect']=self::$db2;
//$p['help']=1;
//$p['sub']=1;
//$p['execcode']=1;
return parent::edit($p);}

#sav
static function register($p){$id=$p['id']??'';
$day=sql('date_format(date,"%y%m%d")',self::$db,'v',['id'=>$id]);
sql::sav(self::$db2,[$id,ses('uid'),$day,0]);
return self::play($p);}

static function remove($p){$id=$p['id']??''; $rid=self::$cb.$id;
if(!$p['ok']??''){
	$ret=bj($rid.'|doodle,remove|ok=1,id='.$id,lang('confirm deleting'),'btdel');
	$ret.=bj($rid.'|doodle,play|id='.$id,lang('cancel'),'btn');}
else{sql::del(self::$db2,['bid'=>$id,'uid'=>ses('uid')]); $ret=self::play($p);}
return $ret;}

static function check($p){$bid=$p['id']??''; $day=$p['day']??'';
$id=sql('id',self::$db2,'v',['bid'=>$bid,'uid'=>ses('uid'),'day'=>$day]);
if(!$id)sql::sav(self::$db2,[$bid,ses('uid'),$day,1]);
else sql::up(self::$db2,'ok',$p['go']??'',$id);
return self::play($p);}

#build
static function build($p){$id=$p['id']??'';
$r=sql('tit,date,nbdays',self::$db,'ra',$id); //pr($r);
return $r;}

static function usrbt($p){$usr=sql('name','login','v',$p['uid']);//ico('user').
return $bt=bubble('profile,call|usr='.$usr.',sz=small',$usr,'minicon',1);}

static function play($p){$id=$p['id']??''; $uid=ses('uid');
$ra=self::build($p); $cb=self::$cb;//pr($ra);
$ex=sql('uid',self::$db2,'v',['bid'=>$id,'uid'=>ses('uid')]);
$rb=sql('uid,day,ok',self::$db2,'kkv',['bid'=>$id]); //pr($rb);
$start=strtotime($ra['date']); $n=$ra['nbdays'];
setlng();//setlocale
//echo date('ymd',$start);
for($i=0;$i<$n;$i++)$re['_k'][]=utf8enc(date('Y-m-d',$start+86400*$i));//
array_unshift($re['_k'],''); //pr($re);
if($rb)foreach($rb as $k=>$v){//$re[$k][]=$k;
	for($i=0;$i<$n;$i++){
		$day=date('ymd',($start+86400*$i)); //echo $day.' ';
		if(isset($v[$day])){
			if($v[$day]==1){$c='active'; $bt=ico('check'); $go=2;}
			elseif($v[$day]==2){$c='disactive'; $bt=ico('close'); $go=0;}
			else{$c=''; $bt=ico('minus'); $go=1;}}
		else{$c=''; $bt=ico('minus'); $go=1;}
		if($k==$uid)$re[$k][]=bj($cb.$id.'|doodle,check|id='.$id.',day='.$day.',go='.$go,$bt,'minicon '.$c);
		else $re[$k][]=span($bt,'minicon '.$c);}
	array_unshift($re[$k],self::usrbt(['uid'=>$k]));} //pr($re);
$ret=div($ra['tit'],'txt');
$ret.=tabler($re);
if(!$ex)$ret.=bj(self::$cb.$id.'|doodle,register|id='.$id,langp('register'),'btsav');
else $ret.=bj(self::$cb.$id.'|doodle,remove|id='.$id,langp('remove'),'btdel');
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

static function api($p){
return parent::api($p);}
}
?>