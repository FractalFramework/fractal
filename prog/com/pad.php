<?php
class pad{
static $a=__CLASS__;

static function js($p=[]){
return 'memStorage("'.($p['rid']??'').'_m'.($p['m']??'1').'_res_1");';}

static function headers(){}

static function admin(){
$r=admin::app(['a'=>self::$a]);
$r[]=['editors','pop','txt','','txt'];
$r[]=['editors','pop','pad','','pad'];
$r[]=['editors','pop','convert','','convert'];
return $r;}

static function popup(){
$bt=bj('pagup|pad',ico('window-maximize'));
$ret['title']='NotePad';
$ret['width']=640;
return $ret;}

static function call($p){$bt=''; $m=$p['m']??'1'; $rid=$p['rid'];
for($i=1;$i<10;$i++)
$bt.=bj($rid.'cb|pad,call|rid='.$rid.',m='.$i,$i,active($m,$i),['onclick'=>atj('memStorage',$rid.'_m'.$i.'_res_1')]);
//$bt.=btj($i,atj('memStorage',$p['rid'].'_m'.$i.'_res_1'),active($m,$i),'ckb'.$i);
$ret=span($bt,'nbp');
$ret.=btj(langp('save'),atj('memStorage',$p['rid'].'_m'.$m.'_sav_1'),'btsav','ckc');
$ret.=hlpbt('pad_app');
return $ret;}

#content
static function content($p){
$p['rid']=randid('pad');
$ret=div(self::call($p),'',$p['rid'].'cb');
//$ret.=div(textarea($p['rid'],'',34,8,'','article'));
$s='width:calc(100% - 10px); min-height:440px; max-width:720px;';
$ret.=divarea($p['rid'],'<p></p>','padarea scroll','');
$ret.=head::jscode(self::js($p));
return $ret;}
}
?>