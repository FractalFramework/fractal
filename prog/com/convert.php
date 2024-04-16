<?php

class convert{
static $private=0;
static $a=__CLASS__;
static $db='';

static function headers(){
head::add('csscode','');
head::add('meta',['attr'=>'property','prop'=>'description','content'=>'conversions encode decode characters']);}

static function admin(){
$r=admin::app(['a'=>self::$a,'db'=>self::$db]);
$r[]=['editors','pop','txt','','txt'];
$r[]=['editors','pop','pad','','pad'];
$r[]=['editors','pop','convert','','convert'];
return $r;}

static function clean_mail($ret){
$ret=str_replace(".\n",'.��',$ret);
$ret=str_replace("\n",'�',$ret);
$ret=str_replace('��',"\n\n",$ret);
$ret=str_replace('�',' ',$ret);
return $ret;}

static function ascii2utf8($d){$ret='';
$r=explode(';',$d);
foreach($r as $v){
	if(substr($v,0,2)=='&#'){$n=substr($v,2);
		//$va='%u'.str::utf8enc(unicode(dechex($n)));
		$va=mb_convert_encoding('&#'.intval($n).';','UTF-8','HTML-ENTITIES');}
		else $va=$v;
	$ret.=$va;}
return $ret;}

static function parser($d,$m){$d=str_replace("\n",' ',$d);
$r=explode(' ',$d); foreach($r as $v)if($v)$ret[]=$m($v);
return implode(' ',$ret);}

static function bin2ascii($d){$ret='';
$d=str_replace("\n",'',$d); $d=str_replace(' ','',$d);
$n=strlen($d); $nb=ceil($n/8);
for($i=0;$i<$nb;$i++)$r[]=substr($d,$i*8,8);
foreach($r as $v)$ret.=chr(bindec($v)).' ';
return $ret;}

static function ascii2bin($d){$ret='';
$r=str_split($d);
foreach($r as $v)$ret.=str_pad(decbin(ord($v)),8,'0',STR_PAD_LEFT).' ';
return $ret;}

static function php($d){
$r=['=','(',')','{','}',',','.','[',']'];
foreach($r as $k=>$v)$d=str_replace([' '.$v,$v.' '],$v,$d);
$d=str_replace("\t",'',$d);
return $d;}

static function rgb2hexa($d){$ret='';
$d=str_replace(['rgba(','rgb(',')',';'],'',$d);
$r=explode(',',$d);
for($i=0;$i<3;$i++)$ret.=str_pad(dechex(val($r,$i)),2,'0');
return $ret;}

static function morse($d,$o=0){$ret='';
if($o)$ra=explode(' ',$d); else $ra=str_split($d);
$r=['a'=>'-.-','b'=>'-...','c'=>'-.-.','d'=>'-..','e'=>'.','f'=>'..-.','g'=>'--.','h'=>'....','i'=>'..','j'=>'.---','k'=>'-.-','l'=>'.-..','m'=>'--','n'=>'-.','o'=>'---','p'=>'.--.','q'=>'--.-','r'=>'.-.','s'=>'...','t'=>'-','u'=>'..-','v'=>'...-','w'=>'.--','x'=>'-..-','y'=>'-.--','z'=>'--..','0'=>'-----','1'=>'.----','2'=>'..---','3'=>'...--','4'=>'....-','5'=>'.....','6'=>'-....','7'=>'--...','8'=>'---..','9'=>'----.'];
if($o)$r=array_flip($r);
if($ra)foreach($ra as $v)$ret.=$r[trim($v)].'';
return $ret;}

/*match (8.0) {  
  '8.0' => "Oh no!",  
  8.0 => "This is what I expected",  
};*/

static function exe($p,$d){$n=str_replace(' ','',$d);
if($p)switch($p){
	case('html2conn'):$d=conv::com($d); break;
	case('clean_mail'):$d=self::str::clean_mail($d); break;
	case('url-decode'):$d=rawurldecode($d); break;
	case('url-encode'):$d=rawurlencode($d); break;
	case('utf8-decode'):$d=str::utf8dec($d); break;
	//case('utf8-decode'):$d=mb_convert_encoding($d,'HTML-ENTITIES','UTF-8'); break;
	case('utf8-encode'):$d=str::utf8enc($d); break;
	case('base64-decode'):$d=base64_decode($d); break;
	case('base64-encode'):$d=base64_encode($d); break;
	case('htmlentities-encode'):$d=htmlentities($d,ENT_COMPAT,'UTF-8'); break;
	case('htmlentities-decode'):$d=html_entity_decode($d); break;
	case('timestamp-decode'):$d=date('d/m/Y H:i:s',$d); break;
	case('timestamp-encode'):$d=strtotime($d); break;
	case('hex-ascii'):$d=base_convert($n,16,2); $d.='=>'.self::bin2ascii($d); break;
	case('json-decode'):$d=print_r(json_decode($d,true),true); break;
	case('unicode (\u)'):$d=preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/',function($match){return mb_convert_encoding(pack('H*',$match[1]),'UTF-8','UCS-2BE');},$d); break;
	case('unicode (%u)'):$d=unicode($d); break;
	case('reverse'):$d=implode('',array_reverse(str_split($d))); break;
	case('iconv'):setlocale(LC_ALL,'fr_FR.utf8'); $d=iconv('UTF-8','ASCII//TRANSLIT',$d); break;
	case('ascii_encode'):$d=mb_convert_encoding($d,'US-ASCII','UTF-8'); break;
	case('ascii_decode'):$d=mb_convert_encoding($d,'ASCII'); break;
	case('ascii2utf8'):$d=self::ascii2utf8($d); break;
	case('bin2ascii'):$d=self::bin2ascii($d); break;
	case('ascii2bin'):$d=self::ascii2bin($d); break;
	case('sha256'):$d=hash('sha256',$d); break;
	case('ripemd160'):$d=hash('ripemd160',$d); break;
	case('translate'):$d=trans::com(['to'=>ses('lng'),'txt'=>$d,'dtc'=>1]); break;
	case('table2array'):$r=explode_r($d,"\n",'|'); $d=db::dump($r,''); break;
	case('morse-enc'):$d=self::morse($d); break;
	case('morse-dec'):$d=self::morse($d,1); break;
	case('soundex'):$d=soundex($d); break;
	case('php'):$d=self::php($d); break;
	case('ord'):$d=ord($d); break;
	case('md5'):$d=md5($d); break;
	case('bin-dec'):$d=self::parser($n,'bindec'); break;
	case('dec-bin'):$d=self::parser($n,'decbin'); break;//decbin()
	case('bin2hex'):$d=self::parser($n,'bin2hex'); break;
	case('hex2bin'):$d=self::parser($n,'hex2bin'); break;
	case('dec-hex'):$d=self::parser($n,'dechex'); break;
	case('hex-dec'):$d=self::parser($n,'hexdec'); break;
	case('dec-b36'):$d=b36($n); break;
	case('b36-dec'):$d=b36($n,1); break;
	case('deg2rad'):$d=deg2rad($n);break;
	case('rad2deg'):$d=rad2deg($n);break;
	case('rgb2hexa'):$d=self::rgb2hexa($n); break;
	case('hexa2rgb'):$d=hexrgb($n); break;
	case('sex2dec'):$d=maths::base2dec($n,60); break;
	case('dec2sex'):$d=maths::dec2base($n,60); break;
	case('time_distorsion'):$d=maths::spacetime($n,1); break;
	//case('test'):$d=maths::deg2ra(49.27).'//'.maths::dec2deg(11.5); break;
	case('powers'):$d=html_entity_decode(maths::powers($n)); break;
	case('measures'):$d=maths::measures($n); break;
	case('magnitude'):$d=maths::magnitude($n); break;
	case('numerology'):$d=maths::numerology($d); break;
	case('xyz'):[$ad,$dc,$ds]=expl(',',$d,3);
		$r=maths::xyz((float)$ad,(float)$dc,(float)$ds); $d=implode(n(),$r); break;
	default:if(method_exists('maths',$p))$d=maths::$p($n); elseif(function_exists($p))$d=$p($n); break;}
return $d;}

static function call($p){$ret='';
$conv=$p['mode']; $txt=$p['code'];
return self::exe($conv,$txt);}

static function r(){return ['filters'=>['translate','table2array','html2conn','clean_mail','php','reverse'],'codage'=>['url-decode','url-encode','utf8-decode','utf8-encode','htmlentities-decode','htmlentities-encode','base64-decode','base64-encode','json-decode','unicode (%u)','unicode (\\u)','ord','iconv'],
'cryptography'=>['md5','ripemd160','sha256','soundex','morse-enc','morse-dec'],
'time'=>['timestamp-decode','timestamp-encode','sec2time','time2sec'],
'ascii/clr'=>['rgb2hexa','hexa2rgb'],
'ascii'=>['ascii_encode','ascii_decode','ascii2utf8','hex-ascii','bin2ascii','ascii2bin'],
'math/numbers'=>['powers','magnitude','numerology'],
'math'=>['bin-dec','dec-bin','bin2hex','hex2bin','dec-hex','hex-dec','dec-b36','b36-dec','deg2rad','rad2deg','sex2dec','dec2sex'],
'astro/numbers'=>['sunsz','lightspeed','soundspeed','time_distorsion'],
'astro'=>['al2km','km2al','au2km','km2au','al2pc','pc2al','km2pc','pc2km','pc2mas','mas2pc','al2mas','mas2al','deg2mas','mas2deg','mas2rad','ra2deg','deg2ra','dec2deg','deg2dec','nm2thz','cm2hz'],
'3d'=>['xyz','test']];}

static function menu(){$r=self::r(); $rb=[];
foreach($r as $k=>$v)foreach($v as $vb)
	$rb[]=[''.$k,'j','input,res|convert,call|mode='.$vb.'|code','',$vb];
return $rb;}

#content
static function content($p){$ret='';
$ret=div(menu::call(['app'=>'convert','mth'=>'menu','mode'=>1]));
$ret.=textarea('code','','40','10','','','');
$ret.=textarea('res','','40','10','','','');
$ret.div('','clear');
return $ret;}
}

?>