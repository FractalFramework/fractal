<<<<<<< HEAD
<?php

//application not based on appx
class mkbook{	
static $private=2;
static $a=__CLASS__;
static $db=__CLASS__;
static $cols=['tit','txt'];
static $typs=['var','text'];
static $cb='mdb';

static function install(){
sql::create(self::$db,[array_combine(self::$cols,self::$typs)],0);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p){$ret=''; //pr($r);
$r=self::build($p);
//$f=val($p,'inp2');
$nm=$p['inp2']??'ebook';
$f='disk/usr/'.ses('usr').'/'.$nm.'.epub';
$rb=[];
$ret=epub::build($r,$rb,$nm);
return $ret;}

#call
static function call($p){
$ret=self::play($p,);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){
$j=self::$cb.'|'.self::$a.',call||inp2';
$ret=inputcall($j,'inp2',$p['p1']??'',32);
$ret.=upload::call('inp2');
$ret.=bj($j,langp('send'),'btn');
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=self::com($p);
//$ret=self::call($p);
return $bt.div('','pane',self::$cb);}
}
?>
=======
<?php

//application not based on appx
class mkbook{	
static $private=2;
static $a=__CLASS__;
static $db=__CLASS__;
static $cols=['tit','txt'];
static $typs=['var','text'];
static $cb='mdb';

static function install(){
sql::create(self::$db,[array_combine(self::$cols,self::$typs)],0);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

#build
static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'rr',['uid'=>ses('uid')]);
return $r;}

#play
static function play($p){$ret=''; //pr($r);
$r=self::build($p);
//$f=val($p,'inp2');
$nm=$p['inp2']??'ebook';
$f='disk/usr/'.ses('usr').'/'.$nm.'.epub';
$rb=[];
$ret=epub::build($r,$rb,$nm);
return $ret;}

#call
static function call($p){
$ret=self::play($p,);
if(!$ret)return help('no element','txt');
return $ret;}

static function com($p){
$j=self::$cb.'|'.self::$a.',call||inp2';
$ret=inputcall($j,'inp2',$p['p1']??'',32);
$ret.=upload::call('inp2');
$ret.=bj($j,langp('send'),'btn');
return $ret;}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$bt=self::com($p);
//$ret=self::call($p);
return $bt.div('','pane',self::$cb);}
}
?>
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
