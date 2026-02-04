<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
<?php

//application not based on appx
class markdown{
static $private=0;
static $a=__CLASS__;
static $db='markdown';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='mkd';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){}
static function headers(){}

#build
static function build($p){$id=$p['id']??''; return [];//!
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

static function md($da){$ret='';
[$p,$o,$c,$d]=readconn($da);
//if($c=='h')echo $c.':'.$p.' - ';//[$p,$o,$c]=decompact_conn(
switch($c){
case(':h'):$ret='# '.$d;break;
case(':h1'):$ret='# '.$d;break;
case(':h2'):$ret='## '.$d;break;
case(':h3'):$ret='### '.$d;break;
case(':h4'):$ret='#### '.$d;break;
case(':h5'):$ret='##### '.$d;break;
case(':b'):$ret='**'.$d.'**';break;
case(':i'):$ret='_'.$d.'_';break;
case(':q'):$ret='> '.$d;break;
case(':list'):$ret=str_replace("\n",'- ',$d);break;
case(':numlist'):$r=explode("\n",$d); foreach($r as $k=>$v)$ret.=$k.'. '.$v.n(); break;
case(':php'):$r=explode("\n",$d); foreach($r as $k=>$v)$ret.="\t".$v.n(); break;
case(':code'):$ret='`'.$d.'`'; break;
case('--'):$ret='`---'; break;}
if(is_img($d)){$ret='![]('.($d).')';}
[$p,$o]=cprm($d);
if(substr($p,0,4)=='http' or substr($p,0,2)=='//')$ret=($o?'['.$o.']':'').'('.$p.')';
return $ret?$ret:$da;}

#play
static function play($p,$r){$ret=''; //pr($r);
if($r)foreach($r as $k=>$v){$rb=[];
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
return $ret;}

#call
static function call($p){
$r=self::build($p);
$ret=self::play($p,$r);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||p1'; $p1=$p['p1']??'';
//$bt=bj($j,langp('ok'),'btn');
//$ret=$bt.textarea('p1','',60,4);
//$ret=inputcall($j,'p1',$p['p1']??'',32).$bt;
$ret=form::call(['p1'=>['inputcall',$p1,'url',$j],['submit',$j,'ok','']]);
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>
<<<<<<< HEAD
=======
=======
<?php

//application not based on appx
class markdown{
static $private=0;
static $a=__CLASS__;
static $db='markdown';
static $cols=['tit','txt'];
static $typs=['svar','text'];
static $cb='mkd';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){}
static function headers(){}

#build
static function build($p){$id=$p['id']??''; return [];//!
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

static function md($da){$ret='';
[$p,$o,$c,$d]=readconn($da);
//if($c=='h')echo $c.':'.$p.' - ';//[$p,$o,$c]=decompact_conn(
switch($c){
case(':h'):$ret='# '.$d;break;
case(':h1'):$ret='# '.$d;break;
case(':h2'):$ret='## '.$d;break;
case(':h3'):$ret='### '.$d;break;
case(':h4'):$ret='#### '.$d;break;
case(':h5'):$ret='##### '.$d;break;
case(':b'):$ret='**'.$d.'**';break;
case(':i'):$ret='_'.$d.'_';break;
case(':q'):$ret='> '.$d;break;
case(':list'):$ret=str_replace("\n",'- ',$d);break;
case(':numlist'):$r=explode("\n",$d); foreach($r as $k=>$v)$ret.=$k.'. '.$v.n(); break;
case(':php'):$r=explode("\n",$d); foreach($r as $k=>$v)$ret.="\t".$v.n(); break;
case(':code'):$ret='`'.$d.'`'; break;
case('--'):$ret='`---'; break;}
if(is_img($d)){$ret='![]('.($d).')';}
[$p,$o]=cprm($d);
if(substr($p,0,4)=='http' or substr($p,0,2)=='//')$ret=($o?'['.$o.']':'').'('.$p.')';
return $ret?$ret:$da;}

#play
static function play($p,$r){$ret=''; //pr($r);
if($r)foreach($r as $k=>$v){$rb=[];
	foreach(self::$cols as $kb=>$vb)$rb[]=div($v[$vb]);
	$ret.=div(implode('',$rb));}
return $ret;}

#call
static function call($p){
$r=self::build($p);
$ret=self::play($p,$r);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||p1'; $p1=$p['p1']??'';
//$bt=bj($j,langp('ok'),'btn');
//$ret=$bt.textarea('p1','',60,4);
//$ret=inputcall($j,'p1',$p['p1']??'',32).$bt;
$ret=form::call(['p1'=>['inputcall',$p1,'url',$j],['submit',$j,'ok','']]);
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'pane',self::$cb);}
}
?>
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
