<?php
class str{
#parsers
static function substrpos($v,$a,$b){return substr($v,$a+1,$b-$a-1);}

<<<<<<< HEAD
static function lastagpos($v,$ab,$ba){$d=self::substrpos($v,$ab,$ba);
$nb_aa=substr_count($d,'{'); $nb_bb=substr_count($d,'}'); $nb=$nb_aa-$nb_bb;
if($nb>0){for($i=0;$i<$nb;$i++)$ba=strpos($v,'}',$ba+1); $ba=self::lastagpos($v,$ab,$ba);}
=======
<<<<<<< HEAD
static function lastagpos($v,$ab,$ba){$d=self::substrpos($v,$ab,$ba);
$nb_aa=substr_count($d,'{'); $nb_bb=substr_count($d,'}'); $nb=$nb_aa-$nb_bb;
if($nb>0){for($i=0;$i<$nb;$i++)$ba=strpos($v,'}',$ba+1); $ba=self::lastagpos($v,$ab,$ba);}
=======
static function lastagpos($v,$ab,$ba){$d=str::substrpos($v,$ab,$ba);
$nb_aa=substr_count($d,'{'); $nb_bb=substr_count($d,'}'); $nb=$nb_aa-$nb_bb;
if($nb>0){for($i=0;$i<$nb;$i++)$ba=strpos($v,'}',$ba+1); $ba=str::lastagpos($v,$ab,$ba);}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
return $ba;}

/*static function goodend($d,$start,$end){
$pa=strpos($d,'<'); $d=substr($d,$pa+1);
$pb=strpos($d,'>'); $db=substr($d,0,$pb+1);
$na=substr_count($db,'<'); $nb=substr_count($db,'>');
<<<<<<< HEAD
if($na>$nb)$pb=self::lastagpos($d,$pa,$pb);
=======
<<<<<<< HEAD
if($na>$nb)$pb=self::lastagpos($d,$pa,$pb);
=======
if($na>$nb)$pb=str::lastagpos($d,$pa,$pb);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
return substr($d,0,$pb);}*/

/*static function hooks($d,$o=''){$ra=['[',']','{','}']; $rb=['(hka)','(hkb)','(aca)','(acb)'];
return $o?str_replace($rb,$ra,$d):str_replace($ra,$rb,$d);}*/

static function connslct($d,$c=':img'){
$r=explode('[',$d); $rt=[];
foreach($r as $k=>$v){
	$n=strpos($v,']'); $d=substr($v,0,$n);
	if(strpos($d,$c))$rt[]=substr($d,0,0-strlen($c));}
return $rt;}

static function accolades($d){
$pa=strpos($d,'{'); $d=substr($d,$pa+1);
$pb=strpos($d,'}'); $db=substr($d,0,$pb+1);
$na=substr_count($db,'{'); $nb=substr_count($db,'}');
<<<<<<< HEAD
if($na>$nb)$pb=self::lastagpos($d,$pa,$pb);
=======
<<<<<<< HEAD
if($na>$nb)$pb=self::lastagpos($d,$pa,$pb);
=======
if($na>$nb)$pb=str::lastagpos($d,$pa,$pb);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
return substr($d,0,$pb);}

static function innerfunc($d,$func){
$na=strpos($d,'static function '.$func); $d=substr($d,$na);
$na=strpos($d,'('); $nb=strpos($d,')');
//$vars=substr($d,$na+1,$nb-$na-1);
$d=substr($d,$nb+1);
<<<<<<< HEAD
return self::accolades($d);}
=======
<<<<<<< HEAD
return self::accolades($d);}
=======
return str::accolades($d);}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235

#strings
static function utfenc($d){return iconv('ISO-8859-1','UTF-8',$d);}
static function utfdec($d){return iconv('UTF-8','ISO-8859-1',$d);}
static function utf8enc($d){return mb_convert_encoding($d,'UTF-8','ISO-8859-1');}
static function utf8dec($d){return mb_convert_encoding($d,'ISO-8859-1','UTF-8');}
static function utf8dec2($d){return mb_encode_numericentity($d,[0x80,0x10FFFF,0,~0],'UTF-8');}
//static function utf8dec3($d){return mb_encode_numericentity($d,[0x0,0x2FFFF,0,0xFFFF],'UTF-8');}//all2he
//static function he2ascii($d){return mb_decode_numericentity($d,[0x0,0x2FFFF,0,0xFFFF],'UTF-8');}
//static function he2utf($d){return mb_convert_encoding($d,'UTF-8','HTML-ENTITIES');}
//static function utf2he($d){return mb_convert_encoding($d,'HTML-ENTITIES','UTF-8');}
//static function utfdecb($d){return html_entity_decode(utf8dec($d));}
<<<<<<< HEAD
static function utf2ascii($d){$d=$d??''; $d=htmlentities($d,ENT_QUOTES,'UTF-8'); $d=self::utf8dec2($d);
=======
<<<<<<< HEAD
static function utf2ascii($d){$d=$d??''; $d=htmlentities($d,ENT_QUOTES,'UTF-8'); $d=self::utf8dec2($d);
=======
static function utf2ascii($d){$d=$d??''; $d=htmlentities($d,ENT_QUOTES,'UTF-8'); $d=str::utf8dec2($d);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
return html_entity_decode($d);}

static function delp($d){return str_replace(['<p>','</p>'],"\n",$d);}
static function delbr($d,$o=''){return str_replace(['<br />','<br/>','<br>','<br clear="left"/>'],$o,$d);}
static function deln($d,$o=''){return str_replace("\n",$o,$d);}
static function delr($d,$o=''){return str_replace("\r",$o,$d);}
static function delt($d,$o=''){return str_replace("\t",$o,$d);}
<<<<<<< HEAD
//static function delnbsp($d){return str_replace("&nbsp;",' ',$d);}
static function delnbsp($d){return str_replace(" ",' ',$d);}
=======
<<<<<<< HEAD
//static function delnbsp($d){return str_replace("&nbsp;",' ',$d);}
static function delnbsp($d){return str_replace(" ",' ',$d);}
=======
static function delsp($d){return str_replace("&nbsp;",' ',$d);}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
static function cleansp($d){return preg_replace('/( ){2,}/',' ',$d);}
static function cleannl($d){return preg_replace('/(\n){2,}/',"\n\n",$d);}

static function clean_separator($d,$a,$b){
if(strpos($d,$a) && strpos($d,$b))$d=str_replace($b,'',$d);
elseif(strpos($d,$b))$d=str_replace($b,$a,$d);
return $d;}

static function clean_lines($d){
<<<<<<< HEAD
$d=self::delbr($d,"\n");
$d=self::cleannl($d);
=======
<<<<<<< HEAD
$d=self::delbr($d,"\n");
$d=self::cleannl($d);
=======
$d=delbr($d,"\n");
$d=cleannl($d);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
$r=explode("\n",$d);
foreach($r as $v)$rb[]=trim($v);
return implode("\n",$rb);}

static function clean_mail($d){
<<<<<<< HEAD
$d=self::delr($d);
$d=self::delbr($d,"\n");
$d=self::delnbsp($d);
=======
<<<<<<< HEAD
$d=self::delr($d);
$d=self::delbr($d,"\n");
$d=self::delnbsp($d);
=======
$d=delr($d);
$d=delbr($d,"\n");
$d=delsp($d);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
$d=str_replace("M.\n",'M. ',$d);
$d=str_replace(".\n",'.µµ',$d);
$d=str_replace("\n",'µ',$d);
$d=str_replace('µµ',"\n\n",$d);
$d=str_replace('µ',' ',$d);
<<<<<<< HEAD
$d=self::clean_lines($d);
$d=self::cleansp($d);
$d=self::repair_punct($d);
=======
<<<<<<< HEAD
$d=self::clean_lines($d);
$d=self::cleansp($d);
$d=self::repair_punct($d);
=======
$d=str::clean_lines($d);
$d=cleansp($d);
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
return $d;}

static function clean_n($d){
$d=str_replace("\r\n","\n",$d);
$d=str_replace("\r","\n",$d);
$d=str_replace('<br>'."\n","\n",$d);
//$d=str_replace('<br>',"\n",$d);
//$d=str_replace('<br />',"\n",$d);
<<<<<<< HEAD
$d=self::cleansp($d);
$d=self::cleannl($d);
=======
<<<<<<< HEAD
$d=self::cleansp($d);
$d=self::cleannl($d);
return trim($d);}

static function clean_br($d){
$d=self::delbr($d,' ');
$d=self::clean_lines($d);
$d=self::deln($d,' ');
$d=self::delnbsp($d,' ');
$d=self::cleansp($d);
return trim($d);}

static function cleanthreedots($d){
return str_replace('...','…',$d);}

static function cleandquotes($d){
$n=substr_count($d,'"');
if($n%2!=0 || $n==0)return $d;
$r=explode('"',$d);
foreach($r as $k=>$v)$r[$k]=$v.($k%2?' »':'« ');
$r[$k]=mb_substr($r[$k],0,-2);
return join('',$r);}

static function clean_nbsp($d){
$d=str_replace("&nbsp;&nbsp;","&nbsp;",$d);
$d=str_replace("&nbsp; ","&nbsp;",$d);
$d=str_replace(" &nbsp;","&nbsp;",$d);
$d=str_replace("&nbsp;&nbsp;","&nbsp;",$d);
$d=self::cleansp($d);
return $d;}

static function good_nbsp($d){$e="&nbsp;";
$a=['« ',' »',' !',' ?',' :',' ;'];
$b=['«'.$e,$e.'»',$e.'!',$e.'?',$e.':',$e.';'];
return str_replace($a,$b,$d);}

static function cleanpunct($d){
$d=self::delnbsp($d);
$d=self::cleansp($d);
$d=protect($d,'://',0);
$a=['«','»','!','?',' .',' ,'];//,'(',')',': ',';'
$b=['« ',' »',' !',' ?','. ',', '];//,' (',') ',' : ',' ; '
$d=protectr($d,$b,0);
$d=str_replace($a,$b,$d);
$d=protectr($d,$b,1);
$d=protect($d,'://',1);
$d=self::cleansp($d);
$d=self::good_nbsp($d);
return $d;}

static function repair_dots($d){
$a=['.com','.fr','.jpg','.png','.mp4','.'."\n",'.'."&nbsp;",'.)'];
$b=['(doccom)','(dotfr)','(dotjpg)','(dotpng)','(dotmp4)','(dotnl)','(dotnbsp)','(dotclp)'];
$d=str_replace($a,$b,$d);
$d=str_replace('.','. ',$d);
$d=str_replace($b,$a,$d);
return $d;}

static function repair_punct($d){
$d=self::clean_n($d);
$d=self::delnbsp($d);
$d=self::cleansp($d);
$d=self::cleandquotes($d);
$d=self::cleanpunct($d);
$d=self::clean_nbsp($d);
$d=self::cleanthreedots($d);
//$d=self::repair_dots($d);
//$d=self::cleansp($d);
return $d;}

=======
$d=cleansp($d);
$d=cleannl($d);
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
return trim($d);}

static function clean_br($d){
$d=self::delbr($d,' ');
$d=self::clean_lines($d);
$d=self::deln($d,' ');
$d=self::delnbsp($d,' ');
$d=self::cleansp($d);
return trim($d);}

static function cleanthreedots($d){
return str_replace('...','…',$d);}

static function cleandquotes($d){
$n=substr_count($d,'"');
if($n%2!=0 || $n==0)return $d;
$r=explode('"',$d);
foreach($r as $k=>$v)$r[$k]=$v.($k%2?' »':'« ');
$r[$k]=mb_substr($r[$k],0,-2);
return join('',$r);}

static function clean_nbsp($d){
$d=str_replace("&nbsp;&nbsp;","&nbsp;",$d);
$d=str_replace("&nbsp; ","&nbsp;",$d);
$d=str_replace(" &nbsp;","&nbsp;",$d);
$d=str_replace("&nbsp;&nbsp;","&nbsp;",$d);
$d=self::cleansp($d);
return $d;}

static function good_nbsp($d){$e="&nbsp;";
$a=['« ',' »',' !',' ?',' :',' ;'];
$b=['«'.$e,$e.'»',$e.'!',$e.'?',$e.':',$e.';'];
return str_replace($a,$b,$d);}

<<<<<<< HEAD
static function cleanpunct($d){
$d=self::delnbsp($d);
$d=self::cleansp($d);
$d=protect($d,'://',0);
$a=['«','»','!','?',' .',' ,'];//,'(',')',': ',';'
$b=['« ',' »',' !',' ?','. ',', '];//,' (',') ',' : ',' ; '
$d=protectr($d,$b,0);
$d=str_replace($a,$b,$d);
$d=protectr($d,$b,1);
$d=protect($d,'://',1);
$d=self::cleansp($d);
$d=self::good_nbsp($d);
return $d;}

static function repair_dots($d){
$a=['.com','.fr','.jpg','.png','.mp4','.'."\n",'.'."&nbsp;",'.)'];
$b=['(doccom)','(dotfr)','(dotjpg)','(dotpng)','(dotmp4)','(dotnl)','(dotnbsp)','(dotclp)'];
$d=str_replace($a,$b,$d);
$d=str_replace('.','. ',$d);
$d=str_replace($b,$a,$d);
return $d;}

static function repair_punct($d){
$d=self::clean_n($d);
$d=self::delnbsp($d);
$d=self::cleansp($d);
$d=self::cleandquotes($d);
$d=self::cleanpunct($d);
$d=self::clean_nbsp($d);
$d=self::cleanthreedots($d);
//$d=self::repair_dots($d);
//$d=self::cleansp($d);
return $d;}

=======
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
static function stripaccents($d){
$a=['À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ý','à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ð','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ÿ'];
$b=['A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','Y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y'];
return str_replace($a,$b,$d);}
static function accents($d,$o=0){
$a=['À','Á','Â','Ã','Ä','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ý'];
$b=['à','á','â','ã','ä','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ù','ú','û','ü','ý'];
if($o)[$b,$a]=[$a,$b];
return str_replace($a,$b,$d);}
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
static function strtolower_b($d){return strtolower(self::accents($d,0));}
static function strtoupper_b($d){return strtoupper(self::accents($d,1));}
static function ucfirst_b($d){return self::strtoupper_b(substr($d,0,1)).substr($d,1);}
static function normalize($d,$o=''){$d=self::stripaccents($d);
<<<<<<< HEAD
=======
if($o)$d=str_replace(' ','_',$d); else $d=str_replace('_','',$d);
return str_replace(["'",'"','?','/','§',',',';',':','!','%','&','$','#','+','=','!',"\n","\r","\0","[\]",'~','(',')','[',']','{','}','«','»',"&nbsp;",'-','.'],'',$d);}

static function ptag($d){$d=self::delr($d); $d=delbr($d,'');
$r=explode("\n\n","\n\n".$d."\n\n"); $ret=''; $ex='<h1<h2<h3<h4<h5<br<hr<bl<pr<di<sp<if<fi<ce<di';
foreach($r as $k=>$v){$v=trim($v); if($v){$cn=substr($v,0,3);
	if(strpos($ex,$cn)===false)$ret.='<p>'.$v.'</p>'; else $ret.=$v;}}
$ret=self::cleansp($ret); $ret=nl2br($ret); return $ret;}
=======
static function strtolower_b($d){return strtolower(str::accents($d,0));}
static function strtoupper_b($d){return strtoupper(str::accents($d,1));}
static function ucfirst_b($d){return str::strtoupper_b(substr($d,0,1)).substr($d,1);}
static function normalize($d,$o=''){$d=str::stripaccents($d);
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
if($o)$d=str_replace(' ','_',$d); else $d=str_replace('_','',$d);
return str_replace(["'",'"','?','/','§',',',';',':','!','%','&','$','#','+','=','!',"\n","\r","\0","[\]",'~','(',')','[',']','{','}','«','»',"&nbsp;",'-','.'],'',$d);}

static function ptag($d){$d=self::delr($d); $d=delbr($d,'');
$r=explode("\n\n","\n\n".$d."\n\n"); $ret=''; $ex='<h1<h2<h3<h4<h5<br<hr<bl<pr<di<sp<if<fi<ce<di';
foreach($r as $k=>$v){$v=trim($v); if($v){$cn=substr($v,0,3);
	if(strpos($ex,$cn)===false)$ret.='<p>'.$v.'</p>'; else $ret.=$v;}}
<<<<<<< HEAD
$ret=self::cleansp($ret); $ret=nl2br($ret); return $ret;}
=======
$ret=cleansp($ret); $ret=nl2br($ret); return $ret;}
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235

#conn
static function add_lines($d){
return $d;}

static function cleanconn($d){
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
$d=self::delt($d);
//$d=self::clean_mail($d);
if(strpos($d,'<br>')!==false && strpos($d,"\n")!==false)$d=self::deln($d);
if(strpos($d,'<br>')!==false && strpos($d,"\n")===false)$d=self::delbr($d,"\n");
$d=self::clean_lines($d);
<<<<<<< HEAD
=======
//$d=conv::cleanhtml($d);
$d=conv::cleanconn($d);
$d=self::good_nbsp($d);
//$d=self::delbr($d,"\n");
//$d=conn::read($d,'conn','cleanup','');
$d=self::cleannl($d);
$d=self::clean_n($d);
//$d=self::cleansp($d);
return $d;}

static function striptags($d,$r=[]){if(!$d)return;
=======
$d=delt($d);
//$d=str::clean_mail($d);
if(strpos($d,'<br>')!==false && strpos($d,"\n")!==false)$d=deln($d);
if(strpos($d,'<br>')!==false && strpos($d,"\n")===false)$d=delbr($d,"\n");
$d=str::clean_lines($d);
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
//$d=conv::cleanhtml($d);
$d=conv::cleanconn($d);
$d=self::good_nbsp($d);
//$d=self::delbr($d,"\n");
//$d=conn::read($d,'conn','cleanup','');
$d=self::cleannl($d);
$d=self::clean_n($d);
//$d=self::cleansp($d);
return $d;}

<<<<<<< HEAD
static function striptags($d,$r=[]){if(!$d)return;
=======
static function striptags($d,$r=[]){
>>>>>>> b79f9fbf5da408718315110e8a3db51ac9e121eb
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
$r=array_merge($r,['p','b','i','em','blockquote']);
foreach($r as $k=>$v)$d=str_replace(['<'.$v.'>','</'.$v.'>'],['',' '],$d);
return $d;}

}
?>