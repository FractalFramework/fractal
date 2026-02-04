<?php
class conn{
static $one=1;
static $r=[];
static $obj=[];
static $opn=1;
static $usd=0;
static $imax=0;
static $imgs=[];

static function mklist($d,$o=''){
$r=explode("\n",$d); $b=$o?'ol':'ul';
foreach($r as $v)if($v){
	if(substr($v,0,2)=='- ')$v=substr($v,2);
	elseif(substr($v,0,1)=='-')$v=substr($v,1);
	$rt[]=tag('li',[],$v);}
return tag($b,[],implode('',$rt));}

static function tabler($d,$o=''){
if(strpos($d,'¬')===false && strpos($d,"\n"))$d=str_replace("\n",'¬',$d);
$d=str_replace(['|¬',"¬\n",' ¬'],'¬',$d);
if(substr(trim($d),-1)=='¬')$d=substr(trim($d),0,-1);
$tr=explode('¬',$d);
foreach($tr as $k=>$row)$rt[]=explode('|',$row);
return tabler($rt,$o);}

static function url($d,$c='',$e=''){[$p,$o]=cprm($d);
if(is_img($p))return playimg($d,'full','',$o);
elseif(strpos($p,'.mp4'))return pagup('video,call|headers=1,id='.jurl($p),pic('movie',16).$p,'appicon');//
else return lk($p,$o,$c,$e);}

static function gallery($d){
$r=explode(',',$d); $ret=''; //self::$obj['gallery'][]=[$d];
foreach($r as $k=>$v)$ret.=playimg($v,$k==0?'full':'mini');
return $ret;}

static function img($d,$o='',$b=''){
$h=''; self::$obj['img'][]=[$d,'']; //[$p,$o]=cprm($d); 
//if($o && $b!='epub')return imgup($p,$o,'');
//if(strpos($w,'-'))[$w,$h]=explode('-',$w); if(self::$imax)$w=720;
if(strpos($d,','))return self::gallery($d);
if($b=='epub'){$f='usr/_epub/OEBPS/images/'.$d; $fa='img/full/'.$d;
	if(!is_file($f) && is_file($fa))copy($fa,$f); return imgalt($f,$o);}//tag('p',[],)
elseif(is_numeric($b) && count(self::$obj['img'])>1)return playimg($d,'mini','','');//self::$one
elseif($b=='html')return img('/img/full/'.$d,'','','','max-width:640px;');
//elseif(strpos($d,'/')===false)return playimg($d,'full','');
elseif(strpos($d,'/')===false)return img2($d,'full',$o);
if(strpos($d,'/')===false)$d='/img/full/'.$d;
//return img($d,$w,$h);
return imgalt($d,$o);}

static function saveimg($d){
if(is_img($d) && substr($d,0,4)=='http')$d=saveimg($d,'art','');
return '['.$d.']';}

static function video($p,$o){
$pv=video::provider_from_id($p);
if($o)return pagup('video,call|p1='.$pv.',id='.$p,langp('read'),'');
else return video::player($p,$pv);}

static function twits($p,$o){
if(substr($o,0,1)=='@')$m='ban';
if(substr($p,0,4)=='http')$p=strend($p,'/');
if($o)return popup('twitter,call|id='.$p.',mode='.$m,pictxt('tw',$o));
else return twitter::read($p,1);}

static function loop($p,$o){$rb=[];
if(is_numeric($o))$r=padr($o); else $r=json_decode($o,true);
foreach($r as $k=>$v)$rb[]=str_replace('(var)',$v,$p);
return implode('',$rb);}

#readers
static function realwords($d){[$p,$o,$c]=readconn($d);
if(!method_exists($c,'call'))return $c?$p:$d;}

static function appreader($d,$b){[$p,$o,$c,$da]=readconn($d);
if($c=='img'){self::$obj[$c][]=[$p,$o]; return $c.'='.$p.',';}
if($c && method_exists($c,'call') && $p){self::$obj[$c][]=[$p,$o]; return $c.'='.$p.',';}}

static function connreader($d,$b){[$p,$o,$c,$da]=readconn($d);
if($c==$b && $p)return $p.',';}

static function repair($da,$b){//[$p,$o,$c,$d]=readconn($da);
//return '['.$p.($o?'|'.$o:'').($c?':'.$c:'').']'
$da=str_replace(':aj',':bj',$da);
return '['.str_replace('*','|',$da).']';}

static function noconn($d,$b){[$p,$o,$c]=readconn($d);
$r=['b','i','u','e','n','h1','h2','h3','h4','h5','span','div','small','big','table'];
if(in_array($c,$r))return $p;
$ret=match($c){
'url'=>$o.' ',
'img'=>substr($p,0,4)=='http'?$p:host(1).'/img/full/'.$p,
'figure'=>' ',
'table'=>' ',
'web'=>sql('tit','tlex_web','v',['url'=>$p]),
default=>''}; if($ret)return $ret;
if($c && method_exists($c,'tit')){$q=new $c; $t=$q::tit(['id'=>$p]);
	return $t.' : '.host(1).'/'.$c.'/'.$p;}
if(substr($p,0,4)=='http')return $o?$o:$p.' ';
return $o?$o:$p;}

static function cleanup($d,$b){[$p,$o,$c]=readconn($d); $n='';
$r=['h1','h2','h3','h4','h5','img','big','table'];
if(in_array($c,$r))$n="\n";
if(is_img($d))$n="\n\n";
return $n.'['.$d.']'.$n;}

static function form($da,$b){
[$p,$o,$c,$d]=readconn($da);
$op=explode('/',$o); static $i=0; $i++; $k='q'.$i; if($c!='submit')self::$r['frm'][$p]=$k;
$ret=div(label($k,$p),'cell');
$ret=match($c){
'input'=>input($k,$o,20,'',''),
'textarea'=>textarea($k,$o,40,10,'',''),
'select'=>select($k,$op,$p,1),
'checkbox'=>checkbox($k,$op,$p,1),
'radio'=>radio($k,$op,$p,1),
'hidden'=>hidden($k,$p),
default=>''};
if($c=='bar'){[$min,$max]=expl('-',$o); $d=bar($k,round(($max+$min)/2),1,$min,$max,$max>99?1:0);}
if($c=='submit'){$v=implode(',',self::$r['frm']); $h=hidden('h',implode('-',array_keys(self::$r['frm'])));
	return toggle('|form,usave|u='.$o.',b='.$p.'|h,'.$v,langp('submit'),'btsav').$h;}
$ret.=div($d,'cell');
return div($ret,'row');}

static function matrix($p){$ret=''; $r=explode("\n",$p);
foreach($r as $k=>$v){$rb=explode('|',$v); $d='';
	foreach($rb as $ka=>$va)$d.=tag('mtd','',$va); $ret.=tag('mtr','',$d);}
return tag('mfenced',['open'=>'[','close'=>']'],$ret);}

static function math($da,$b){
[$p,$o,$c,$d]=readconn($da);
$ret=match($c){
'frac'=>tag('mfrac','',tag('mi','',$p).tag('mi','',$o)),
'sup'=>tag('msup','',tag('mi','',$p).tag('mn','',$o)),
'sub'=>tag('msub','',tag('mi','',$p).tag('mn','',$o)),
'mi'=>tag('mi','',$p),
'subsup'=>tag('msubsup','',tag('mo','',is_numeric($p)?'&int;':'&dd;').tag('mn','',$p).tag('mi','',$o)),
'mo'=>tag('mo','','&'.($p=='+/-'?'PlusMinus':$p).';'),
'mrow'=>tag('mrow','',$p),
'matrix'=>self::matrix(),
default=>''}; if($ret)return $ret;
return $p;}

static function minconn($da,$b){
[$p,$o,$c,$d]=readconn($da);//echo $p.'|'.$o.':'.$c.br();
$r=['b','i','u','h1','h2','h3','h4','small','big','span','div'];
if(in_array($c,$r))return tag($c,[],$p);
$r=['h'=>'big','k'=>'strike','q'=>'blockquote','s'=>'small','e'=>'sup','n'=>'sub','c'=>'center'];
if(isset($r[$c]))return tag($r[$c],'',$d);
if($d=='--')return hr();
$ret=match($c){
'--'=>hr(),
'a'=>self::url($d,''),
'url'=>self::url($d,''),
'img'=>self::img($p,$o,$b),
'list'=>self::mklist($d),
'numlist'=>self::mklist($d,1),
'center'=>tag('center',$o,$p),
'table'=>self::tabler($d,$o),
'nh'=>$b=='epub'?'<sup id="nh'.$p.'"><a epub:type="noteref" href="#nb'.$p.'">'.$p.'</a></sup>':tag('a',['href'=>'#nb'.$p,'id'=>'nh'.$p],''.$p.''),
'nb'=>$b=='epub'?tag('a',['href'=>'#nh'.$p],'['.$p.']'):tag('a',['href'=>'#nh'.$p,'id'=>'nb'.$p],'['.$p.']'),
'aside'=>tag('aside',['epub:type'=>'footnote','id'=>'nb'.between($p,'#nh','"')],$p),
//'video'=>video::com2($p,$o),
'video'=>video::lk($p,$o),
//'audio'=>audio($p),
//'mp4'=>video($p),
//'mp3'=>audio($p),
'stabilo'=>span($d,'','','background-color:yellow; color:black;'),
'clr'=>span($p,'','','color:#'.$o.';'),
'bkg'=>span($p,'','','background-color:#'.$o.'; color:#'.clrneg($o,1).';'),
'code'=>div(tag('code','',$d),'console'),
'php'=>build::code($d),
'ascii'=>'&#'.$p.';',
'var'=>self::$r[$p]??'',
'on'=>'['.$da.']',
default=>''}; if($ret)return $ret;
if($p=='no')return;
//if(is_img($p))return $b=='epub'?self::img($p,$o,$b):img2($p,self::$imax?'med':'',$o);
if(is_img($p))return self::img($p,$o,$b);
if(substr($p,0,4)=='http')return self::url($da,'');
return '['.$da.']';}

#composants
static function html($p,$o,$c){}

#read
static function reader($da,$b=''){
[$p,$o,$c,$d]=readconn($da); $atb=[];//[p|o:c]//d=p|o
if($p=='http'){$p.=':'.$c; $c='';}
$r=['b','i','u','h1','h2','h3','h4','sub','big','small','center'];
if(in_array($c,$r)){return tag($c,$atb,$d);}//if($o)$p=self::url($d,'');
$r=['h'=>'big','k'=>'strike','q'=>'blockquote','s'=>'small','e'=>'sup','n'=>'sub','c'=>'center'];
if(isset($r[$c]))return tag($r[$c],[],$d);
if($d=='--')return hr();
if($xt=strend($da,'.')){
	if($xt=='mp3')$c='audio'; elseif($xt=='mp4')$c='mp4';
	elseif($xt=='pdf')$c='pdf';}
$ret=match($c){
'br'=>br(),
'hr'=>hr(),
'a'=>self::url($d,''),
'tag'=>tag($c,$o,$p),
'url'=>self::url($d,''),
'lk'=>self::url($d,''),
'list'=>self::mklist($d),
'numlist'=>self::mklist($d,1),
'table'=>self::tabler($d,$o),
'img'=>self::img($p,$o,$b),
'video'=>video::playbt($p,$o),
'video2'=>video::com2($p,$o),
'mp4'=>video($p),
'mp3'=>audio($p),
'mini'=>playimg($p,'mini'),
'gallery'=>self::gallery($d),
'figure'=>tag('figure','',playimg($p,'').tag('figcaption','',$o)),
'id'=>tlex::playquote($p),
'@'=>bubble('profile,call|sz=small,usr='.$p,'@'.$p,'btlk',1),
'#'=>bj('pagup|tlex,search_txt|srch='.$p,'#'.$p,'btlk'),
'stabilo'=>span($d,'stabilo'),
'sticky'=>stabilo::pad($p,$o),
'clr'=>span($p,'','','color:#'.$o.';'),
'bkg'=>span($p,'','','background-color:#'.$o.'; color:#'.clrneg($o,1).';'),
'code'=>div(tag('code','',$d),'console'),
'php'=>build::code($d),
'pub'=>lk('/art/'.$p,$o?$o:art::tit(['id'=>$p]),'btlk'),
'popup'=>popup($p,$o?$o:pic('popup'),''),
'pagup'=>pagup($p,$o?$o:pic('pagup'),''),
'imgup'=>imgup($p,$o),
'artxt'=>art::call(['id'=>$p]),
'nh'=>tag('a',['href'=>'#nb'.$p,'name'=>'nh'.$p],$p),
'nb'=>tag('a',['href'=>'#nh'.$p,'name'=>'nb'.$p],$p),
'ico'=>ico($p,$o?$o:24),
'pic'=>pic($p,$o),
//'lang'=>lang($p,$o),
//'help'=>helpx($p,$o),
'open'=>method_exists($p,$o)?$p::$o([]):'',
'picto'=>picto($p,$o?$o:24),
'ascii'=>'&#'.$p.';',
'aside'=>tag('aside',['id'=>'nb'.$o],$p),
'b64'=>img($p),
'calc'=>self::calc($d,$o),
'date'=>datz($d),
'var'=>self::$r[$p]??'',
'form'=>self::read($d,'conn','form',$o),
'loop'=>self::loop($p,$o),
'protect'=>jurl($p),
'auth'=>auth(6)?$p:$o,
'db'=>db::call(['f'=>'usr/'.$p]),
//'db'=>pagup($c.',call|f=usr/'.$p,span(pic($c).' '.$p,'apptit'),'appicon'),
'bj'=>bj($p,$o,''),
'no'=>'['.$d.']',
'bi'=>'<b><i>'.$d.'</i></b>',
'bu'=>'<b><u>'.$d.'</u></b>',
'iu'=>'<i><u>'.$d.'</u></i>',
'biu'=>'<b><i><u>'.$d.'</u></i></b>',
default=>''}; if($ret)return $ret;
switch($c){
	case('gen'):$r=explode_k($o,',','='); return gen::com($p,$r,$b); break;
	case('setvar'):self::$r[$o]=$p; return; break;
	case('svg'):[$w,$h,$t]=expl('/',$o,3); return svg::com($p,$w,$h,$t); break;
	case('math'):$v=self::read($d,'conn','math',$o);
		return tag('math',['xmlns'=>'http://www.w3.org/1998/Math/MathML'],$v); break;
	//case('img'):self::$obj[$c][]=[$p,'']; return playimg($p,'full'); break;
	//case('img'):return images::com($p,'full'); break;//rename img
	case('web'):self::$obj[$c][]=[$d,'']; return web::play($d); break;
	case('twit'):if($o)return pagup('twitter,call|headers=1,id='.$p,pic('twitter',16).$p,'appicon');
		else return twitter::read($p,0); break;
	case('pdf'):$bt=span(ico('file-pdf-o').domain($da),'apptit');
		return toggle('|iframe,get|url='.nohttp($da),$bt,'appicon'); break;
	case('apj'):$js=ajx('div,cn'.$c.',,1|'.$p.','.$o.'|headers=1');
		return div(head::csscode($js),'','cn'.$c); break;
	//case('app'):return app($p,_jrb($o)); break;//c|o
	case('app'):[$b,$a]=split_one(':',$d,1); return app($a,_jrb($b,'=')); break;//p:a|t
	case('com'):[$b,$a]=split_one(':',$d,1); return app($a,_jrb($b,'='),'com'); break;
	case('bt'):[$b,$a]=split_one(':',$p,1); $t=$a=='art'?art::tit(['id'=>$b]):($a);//p:a|t
		return pagup($a.',call|'.implode_k(_jrb($b,'='),',','='),pic($a).($o?$o:$t)); break;
}
if(is_img($p))return self::img($p,$o,$b);
if(substr($p,0,4)=='http')return self::url($da,'');
if(method_exists($c,'call'))return self::app($c,$p,$o,$b);
return '['.$da.']';}

static function app($c,$p,$t,$b){self::$obj[$c][]=[$p,$t];
	if(!class_exists($c))return; $q=new $c; $ret='';
	if(!$t && method_exists($c,'tit'))$t=$q::tit(['id'=>$p]); if(!$t)$t=lang($c);
	if(method_exists($c,'usr'))$tu=lang('by',1).' '.$q::usr($p); else $tu=$p;
	if(is_numeric($p))$t.=' '.span($tu,'small grey');
	if(strpos($p,'=') or strpos($p,','))$prm=_jrb($p,'='); else $prm['id']=$p; $pm=implode_k($prm,',','=');
	$bt=span(pic($c).' '.$t,'apptit');
	$op=$q::$open??'';//openability
	if($op){//conn::$one!=1 &&
		//if($op==2)$ret=app($c,['_m'=>'preview','id'=>$p,'ptag'=>'no','opt'=>$b]);
		if($op==2)$ret=$q::preview(['id'=>$p,'ptag'=>'no','opt'=>$b]);
		elseif($op==3)$ret=iframe(host(1).'/frame/'.$c.'/'.$p);
		//elseif($op==4)$ret=lk(host(1).'/frame/'.$c.'/'.$p,$bt,'app',1);
		elseif($op==4)$ret=lk(host(1).'/'.$c.'/'.$p,$bt,'app',1);
		//else $ret=app($c,['_m'=>'call','id'=>$p,'ptag'=>'no']);}//,'conn'=>'no'
		else $ret=$q::call(['id'=>$p,'ptag'=>'no']);}//,'conn'=>'no'
	elseif($t==1)$ret=$q::play($prm);
	//elseif($b==1)$ret=app($c,['_m'=>'call','id'=>$p,'ptag'=>'no']);
	elseif($b==1)$ret=$q::call($prm+['ptag'=>'no']);//forced
	elseif(self::$opn==1)$ret=pagup($c.',call|headers=1,'.$pm,$bt,'appicon');
	else $ret=toggle('|'.$c.',call|headers=1,'.$pm,$bt,'appicon');
return $ret;}

static function read($d,$app,$mth,$b=''){
$st='['; $nd=']'; $deb=''; $mid=''; $end='';
$in=strpos($d,$st);
if($in!==false){
	$deb=substr($d,0,$in);
	$out=strpos(substr($d,$in+1),$nd);
	if($out!==false){
		$nb=substr_count(substr($d,$in+1,$out),$st);
		if($nb>=1){
			for($i=1;$i<=$nb;$i++){$out_tmp=$in+1+$out+1;
				$out+=strpos(substr($d,$out_tmp),$nd)+1;
				$nb=substr_count(substr($d,$in+1,$out),$st);}
			$mid=substr($d,$in+1,$out);
			$mid=self::read($mid,$app,$mth,$b);}
		else $mid=substr($d,$in+1,$out);
		$mid=$app::$mth($mid,$b);
		$end=substr($d,$in+1+$out+1);
		$end=self::read($end,$app,$mth,$b);}
	else $end=substr($d,$in+1);}
else $end=$d;
if($mth=='connreader' or $mth=='appreader')return $mid;
/*if($b=='bchain'){
	if(is_array($deb) && is_array($mid))
		$ret=array_merge_recursive($deb,$mid); else $ret=$mid;
	if(is_array($end))$ret=array_merge_recursive($ret,$end);
	return $ret;}*/
return $deb.$mid.$end;}

static function call($p){//p($p);
$d=$p['msg']??''; if(!$d)$d=$p['p1']??''; //self::$usd=1;
$opt=$p['opt']??'';//1=open apps,epub=copy img
$ptag=$p['ptag']??''; self::$r=$p['r']??[]; //explode_k($p['vars']??'',',','=');
//$d=str_replace("<br />\n","\n",$d); $d=str_replace('<br />','',$d);
$app=$p['app']??'conn'; $mth=$p['mth']??'reader'; self::$one=0; self::$obj=[];
if($p['imax']??''){self::$imax=1; $mth='minconn';}
$d=self::read($d,$app,$mth,$opt);
if($d)$d=cleannl($d);
if($ptag==1)$d=str::ptag($d);
elseif($ptag!='no')$d=nl2br($d??'');
if($opt=='epub')$d=str_replace("&nbsp;","&#160;",$d); //self::$usd=0;
return $d;}

static function com($p,$o=''){return self::call(['msg'=>$p,'ptag'=>$o]);}
static function com2($d,$a='conn',$m='reader',$r=[]){conn::$r=$r;
$d=self::read($d,$a,$m); if($d)cleanrl($d,"\n"); return $d;}

static function mincom($p,$o=''){return self::call(['msg'=>$p,'mth'=>'minconn','ptag'=>$o,'opt'=>'']);}

static function content($p){
$j='cnn|conn,call|ptag=1|msg';
$r=['id'=>'msg','rows'=>16,'cols'=>80,'class'=>'console','onkeyup'=>ajx($j),'onclick'=>ajx($j)];
$d=build::connbt('msg').tag('textarea',$r,'');
//$d.=bj($j,langp('ok'),'btsav');
$d.=div('','board','cnn');
return $d;}
}
?>