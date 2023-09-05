<?php
class batchurl{	
static $private=6;
static $a='batchurl';
static $db='batchurl';
static $cols=['dom'];
static $typs=['var'];

static function install($p=''){
appx::install(array_combine(self::$cols,self::$typs));}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db]);}

static function js(){return '
function batchtime(){
var n=getbyid("step").value; //alert(n);
if(n<10000)ajx("div,batchurl|batchurl,call|p1="+n);//
setTimeout("batchtime()",3000);}
setTimeout("batchtime()",10);//
';}
static function headers(){
head::add('csscode','');
head::add('jscode',self::js());}

static function titles($p){
$d=$p['_m']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#build
/*static function build($p){$id=$p['id']??'';
$r=sql('all',self::$db,'ra',$id);
return $r;}*/

#dl
//56 columns : https://www.rave-survey.org/project/documentation/dr5/rave_tgas/
//astrometric_weight_al = 34
//phot_g_mean_flux = 47
//phot_g_mean_mag = 50 (mag)
//radius_val = 88
//lum = 91 //tot=93
static function fscan($f){$h=fopen($f,'r');
while($r=fgets($h,4096))if($r){$ra=explode(',',$r);//each line with parallax
//[$gid,$ad,$dec,$par,$mag,$radius,$lum]=vals($ra,[1,5,7,9,50,88,91]);
//echo $gid.'-'.$ad.'-'.$dec.'-'.$par.'-'.$tad.'-'.$tdc.'-'.$lum;
$gid=str_replace('batchurl DR2 ','',$ra[1]);//$ra[12],$ra[14],
if($ra[9])$rb[]=[$gid,$ra[5],$ra[7],$ra[9],$ra[50],$ra[88],$ra[91]];}//>0.01 or $ra[9]<-0.01
fclose($h); array_shift($rb);
return $rb;}

static function ff(){
$f='usr/batchurl_files.txt';
$u='https://www.ovh.com/fr/order/domain/#/legacy/domain/search?domain=';
$ua='usr/batchurl.txt';
$d=file_get_contents($ua);
$r=explode("\n",$d);
if($r)foreach($r as $k=>$v){$vb=between($v,'href="','"');
$vb=str_replace($u,'',$vb);}
if($r)file_put_contents($f,implode("\n",$r));
//$r=self::emulate(); //pr($r);
return $r;}

/*static function dlgz($f,$id){$er='';
$u='https://www.ovh.com/fr/order/domain/#/legacy/domain/search?domain=';
$d=curl($u);
$ret=between($u,$s,$e);
sql::sav(self::$db,[$ret],'',1);
return $f.' '.count($r).' objects added';}*/

/*static function batch($r){$i=0; $w=10; $n=12; //5
foreach($r as $k=>$v){$i++; if($i>=$n*$w && $i<($n+1)*$w)$rb[]=self::dlgz($v);}// sleep(20);
return $rb;}*/

#read
/*static function call($p){
$id=$p['p1']??''; $ret='';
//if(!$d)$r=self::ff();
//$ret=self::batch($r);
if(auth(6))$ret=self::dlgz($d,$id); $n=$id+1;
return lk('/batchurl/'.$n,$n,'btsav').' '.span($ret,'small').hidden('step',$n);}*/

static function com($p){
return self::content($p);}

#content
static function content($p){//p($p);
//self::install();
$p['p1']=$p['p1']??''; $n=0;
if($p['p1'])$ret=self::call($p);
//$dr='usr/batchurl/batchurlSource_000-000-';
//for($i=0;$i<256;$i++)if(file_exists($dr.str_pad($i,3,'0',STR_PAD_LEFT).'.txt'.$i.''))$n=$i;
else $ret=input('inp1',1).bj('batchurl|batchurl,call||inp1',lang('send'),'btn');
//else $ret=self::call($p);
return div($ret,'pane','batchurl');}
}
?>