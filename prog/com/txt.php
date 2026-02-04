<?php
class txt{
static $a=__CLASS__;

static function js($p=[]){
return 'memStorage("'.($p['rid']??'').'_m'.($p['m']??'1').'_res_1");';
return 'document.getElementById("'.($p['rid']??'').'").value=localStorage["m'.($p['m']??'1').'"];';}

static function headers(){}

static function admin(){
$r=admin::app(['a'=>self::$a]);
$r[]=['editors','pop','txt','','txt'];
$r[]=['editors','pop','pad','','pad'];
$r[]=['editors','pop','convert','','convert'];
return $r;}

static function popup(){
$bt=bj('pagup|txt',ico('window-maximize'));
$ret['title']='NotePad';
$ret['wifth']=640;
return $ret;}

static function call($p){$bt=''; $m=$p['m']??'1'; $rid=$p['rid'];
$j='';
for($i=1;$i<10;$i++)
$bt.=bj($rid.'cb|txt,call|rid='.$rid.',m='.$i,$i,active($m,$i),['onclick'=>atj('memStorage',$rid.'_m'.$i.'_res')]);
//$bt.=btj($i,atj('memStorage',$p['rid'].'_m'.$i.'_res'),active($m,$i),'ckb'.$i);
$ret=span($bt,'nbp');
$ret.=btj(langp('save'),atj('memStorage',$p['rid'].'_m'.$m.'_sav'),'btsav','ckc');
$ret.=hlpbt('txt_app');
return $ret;}

#content
static function content($p){
$p['rid']=randid('txt');
$ret=div(self::call($p),'',$p['rid'].'cb');
$ret.=div(textarea($p['rid'],'','',24,'','wd'),'','board');
$ret.=head::jscode(self::js($p));
return $ret;}
}
?>