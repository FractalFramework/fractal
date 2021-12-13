<?php

class admin_htaccess{
static $private=6;
static $a='admin_htaccess';
static $cb='aht';

/*static function install(){
sqlcreate(self::$db,['tit'=>'var','txt'=>'bvar'],0);}*/

static function admin(){
$r[]=['','j','popup|admin_htaccess,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=admin_htaccess_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=admin_htaccess','code','Code'];
return $r;}

static function injectJs(){return;}
static function headers(){
add_head('csscode','');
add_head('jscode',self::injectJs());
return lang('ok');}

static function titles($p){
$d=$p['appMethod']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#build
/*static function build($p){$id=val($p,'id');
$r=sql('all',self::$db,'ra',$id);
return $r;}*/

#read
static function save($p){$t=val($p,'inp1');
if($t && auth(6))write_file('.htaccess',$t);
if($t && auth(6))write_file('htaccess.txt',$t);}

static function com($p){}

#content
static function content($p){
$t=read_file('.htaccess');
$ret=textarea('inp1',$t,60,20);
$ret.=bj('popup,,xx|admin_htaccess,save||inp1',langp('send'),'btn');
return div($ret,'pane','aht');}
}
?>