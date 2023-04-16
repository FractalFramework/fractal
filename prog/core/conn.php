<?php
class conn{
static $one=1;
static $r=[];
static $obj=[];
static $opn=1;
static $usd=0;
static $imgs=[];

static function mklist($d,$o=''){
$r=explode("\n",$d); $b=$o?'ol':'ul';
foreach($r as $v)if($v){
	if(substr($v,0,2)=='- ')$v=substr($v,2);
	elseif(substr($v,0,1)=='-')$v=substr($v,1);
	$ret[]=tag('li','',$v);}
return tag($b,'',implode('',$ret));}

static function tabler($d,$o=''){
if(strpos($d,'¬')===false && strpos($d,"\n"))$d=str_replace("\n",'¬',$d);
$d=str_replace(['|¬',"¬\n",' ¬'],'¬',$d);
if(substr(trim($d),-1)=='¬')$d=substr(trim($d),0,-1);
$tr=explode('¬',$d);
foreach($tr as $k=>$row)$ret[]=explode('|',$row);
return tabler($ret,$o);}

static function url($d,$c='',$e=''){
[$p,$o]=connprm($d); //echo $p.'--'.$o.br();
if(is_img($p))return playimg($d,'full','',$o);
elseif(strpos($p,'.mp4'))return pagup('video,call|headers=1,id='.jurl($p),pic('movie',16).$p,'appicon');//
else return lk($p,$o,$c,$e);}

static function gallery($d){
$r=explode(',',$d); $ret=''; //self::$obj['gallery'][]=[$d];
foreach($r as $k=>$v)$ret.=playimg($v,$k==0?'full':'mini');
return $ret;}

static function img($d,$w='',$b=''){$h=''; self::$obj['img'][]=[$d,''];
[$p,$o]=connprm($d); if($o)return imgup($p,$o,'');
if(strpos($w,'-'))[$w,$h]=explode('-',$w);
if(strpos($d,','))return self::gallery($d);
if($b=='epub'){$f='usr/_epub/OEBPS/images/'.$d; $fa='img/full/'.$d;
	if(!is_file($f) && is_file($fa))copy($fa,$f); return img($f);}
elseif(is_numeric($b) && count(self::$obj['img'])>1)return playimg($d,'mini','','');//self::$one
if(strpos($d,'/')===false)return playimg($d,'full','');
else return img($d,$w,$h);}

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
$r=['b','i','u','e','n','h1','h2','h3','h4','span','div','small','big','table'];
if(in_array($c,$r))return $p;
switch($c){
	case('url'):return $o.' '; break;
	case('img'):return substr($p,0,4)=='http'?$p:host(1).'/img/full/'.$p; break;
	case('figure'):return; break;
	case('table'):return; break;
	case('web'):return sql('tit','tlex_web','v',['url'=>$p]); break;}
if($c && method_exists($c,'tit')){$q=new $c; $t=$q::tit(['id'=>$p]);
	return $t.' : '.host(1).'/'.$c.'/'.$p;}
if(substr($p,0,4)=='http')return $o?$o:$p.' ';
return $o?$o:$p;}

static function form($da,$b){
[$p,$o,$c,$d]=readconn($da);
$op=explode('/',$o); static $i=0; $i++; $k='q'.$i; if($c!='submit')self::$r['frm'][$p]=$k;
$ret=div(label($k,$p),'cell');
switch($c){
	case('input'):$d=input($k,$o,20,'',''); break;
	case('textarea'):$d=textarea($k,$o,40,10,'',''); break;
	case('select'):$d=select($k,$op,$p,1); break;
	case('checkbox'):$d=checkbox($k,$op,$p,1); break;
	case('radio'):$d=radio($k,$op,$p,1); break;
	case('hidden'):$d=hidden($k,$p); break;
	case('bar'):[$min,$max]=expl('-',$o); $d=bar($k,round(($max+$min)/2),1,$min,$max,$max>99?1:0); break;
	case('submit'):$v=implode(',',self::$r['frm']); $h=hidden('h',implode('-',array_keys(self::$r['frm'])));
		return toggle('|form,usave|u='.$o.',b='.$p.'|h,'.$v,langp('submit'),'btsav').$h; break;}
$ret.=div($d,'cell');
return div($ret,'row');}

static function math($da,$b){
[$p,$o,$c,$d]=readconn($da);
switch($c){
	case('frac'):return tag('mfrac','',tag('mi','',$p).tag('mi','',$o)); break;
	case('sup'):return tag('msup','',tag('mi','',$p).tag('mn','',$o)); break;
	case('sub'):return tag('msub','',tag('mi','',$p).tag('mn','',$o)); break;
	case('subsup'):$mo=is_numeric($p)?'&int;':'&dd;';
		return tag('msubsup','',tag('mo','',$mo).tag('mn','',$p).tag('mi','',$o)); break;
	case('mi'):return tag('mi','',$p); break;//x
	case('mo'):return tag('mo','','&'.($p=='+/-'?'PlusMinus':$p).';'); break;
	case('mrow'):return tag('mrow','',$p); break;
	case('matrix'):$rt='';
		$r=explode("\n",$p); foreach($r as $k=>$v){$rb=explode('|',$v); $d='';
			foreach($rb as $ka=>$va)$d.=tag('mtd','',$va); $rt.=tag('mtr','',$d);}
		return tag('mfenced',['open'=>'[','close'=>']'],$rt); break;}
return $p;}

static function minconn($da,$b){
[$p,$o,$c,$d]=readconn($da);//echo $p.'$'.$o.':'.$c.br();
$r=['b','i','u','h1','h2','h3','h4','small','big','span','div'];
if(in_array($c,$r))return tag($c,'',$p);
$r=['h'=>'big','k'=>'strike','q'=>'blockquote','s'=>'small','e'=>'sup','n'=>'sub','c'=>'center'];
if(isset($r[$c]))return tag($r[$c],'',$d);
switch($c){
	case('--'):return hr(); break;
	case('a'):return self::url($d,''); break;
	case('url'):return self::url($d,''); break;
	case('img'):return self::img($p,$o,$b); break;
	case('list'):return self::mklist($d); break;
	case('numlist'):return self::mklist($d,1); break;
	case('center'):return tag('center',$o,$p); break;
	case('table'):return self::tabler($p,$o); break;
	case('nh'):if($b=='epub')
		return '<sup id="nh'.$p.'"><a epub:type="noteref" href="#nb'.$p.'">['.$p.']</a></sup>';
		else return tag('a',['href'=>'#nb'.$p,'id'=>'nh'.$p],'['.$p.']'); break;
	case('nb'):if($b=='epub')return tag('a',['href'=>'#nh'.$p],'['.$p.']');
		return tag('a',['href'=>'#nh'.$p,'id'=>'nb'.$p],'['.$p.']'); break;
	case('aside'):$o=between($p,'#nh','"');
		return '<aside epub:type="footnote" id="nb'.$o.'">'.$p.'</aside>';
	//case('aside'):return tag('aside',['id'=>'nb'.$o],$p);
	//case('video'):return video::com2($p,$o); break;//send directly webpage
	case('video'):return video::lk($p,$o); break;//if($b=='epub')
	//case('audio'):return audio($p); break;
	//case('mp4'):return video($p); break;
	//case('mp3'):return audio($p); break;
	case('stabilo'):return span($d,'','','background-color:yellow; color:black;'); break;
	case('clr'):return span($p,'','','color:#'.$o.';'); break;
	case('bkg'):return span($p,'','','background-color:#'.$o.'; color:#'.clrneg($o,1).';'); break;
	case('code'):return div(tag('code','',$d),'console'); break;
	case('php'):return build::code($d); break;
	case('ascii'):return '&#'.$p.';'; break;
	case('var'):return self::$r[$p]??''; break;
	case('on'):return '['.$da.']'; break;
	case('no'):return; break;}
if(is_img($da))return $b=='epub'?self::img($da,'',$b):img2($da,'');
if(substr($p,0,4)=='http')return self::url($da,'');
return '['.$da.']';}

#composants
static function html($p,$o,$c){}

#read
static function reader($da,$b=''){
[$p,$o,$c,$d]=readconn($da); $atb=[];//[p|o:c]//d=p*o
if($p=='http'){$p.=':'.$c; $c='';}
$r=['b','i','u','h1','h2','h3','h4','sub','big','small','center'];
if(in_array($c,$r)){return tag($c,$atb,$d);}//if($o)$p=self::url($d,'');
$r=['h'=>'big','k'=>'strike','q'=>'blockquote','s'=>'small','e'=>'sup','n'=>'sub','c'=>'center'];
if(isset($r[$c]))return tag($r[$c],'',$d);
if($xt=strend($da,'.')){
	if($xt=='mp3')$c='audio'; elseif($xt=='mp4')$c='mp4';
	elseif($xt=='pdf')$c='pdf';}
switch($c){
	case('br'):return br(); break;
	case('--'):return hr(); break;
	case('a'):return self::url($d,''); break;
	case('tag'):return tag($c,$o,$p); break;
	case('url'):return self::url($d,''); break;
	case('lk'):return self::url($d,''); break;
	case('list'):return self::mklist($d); break;
	case('numlist'):return self::mklist($d,1); break;
	case('table'):return self::tabler($p,$o); break;
	//case('img'):self::$obj[$c][]=[$p,'']; return playimg($p,'full'); break;
	case('img'):return self::img($p,$o,$b); break;
	case('web'):self::$obj[$c][]=[$d,'']; return web::play($d); break;
	case('video'):return video::playbt($p,$o); break;
	case('video2'):return video::com2($p,$o); break;
	//case('mp4'):return video::playbt($p,$o); break;
	case('mp4'):return video($p); break;
	case('mp3'):return audio($p); break;
	case('twit'):if($o)return pagup('twitter,call|headers=1,id='.$p,pic('twitter',16).$p,'appicon');
		else return twitter::read($p,0); break;
	case('pdf'):$bt=span(ico('file-pdf-o').domain($da),'apptit');
		return toggle('|iframe,get|url='.nohttp($da),$bt,'appicon'); break;
	//case('img'):return images::com($p,'full'); break;//rename img
	case('mini'):return playimg($p,'mini'); break;
	case('gallery'):return self::gallery($d); break;
	case('figure'):return tag('figure','',playimg($p,'').tag('figcaption','',$o)); break;
	case('id'):if(is_numeric($p))return tlex::playquote($p); break;
	case('@'):return bubble('profile,call|sz=small,usr='.$p,'@'.$p,'btlk',1); break;
	case('#'):return bj('pagup|tlex,search_txt|srch='.$p,'#'.$p,'btlk'); break;
	case('stabilo'):return span($d,'stabilo'); break;
	case('sticky'):return stabilo::pad($p,$o); break;
	case('clr'):return span($p,'','','color:#'.$o.';'); break;
	case('bkg'):return span($p,'','','background-color:#'.$o.'; color:#'.clrneg($o,1).';'); break;
	case('code'):return div(tag('code','',$d),'console'); break;
	case('php'):return build::code($d); break;
	case('pub'):return lk('/art/'.$p,$o?$o:art::tit(['id'=>$p]),'btlk'); break;
	case('apj'):$js='ajx("div,cn'.$c.',,1|'.$p.','.$o.'|headers=1");';
		return div(head::csscode($js),'','cn'.$c); break;
	//case('app'):return app($p,_jrb($o)); break;//c|o
	case('app'):[$b,$a]=split_one(':',$d,1); return app($a,_jrb($b,'=')); break;//p:a|t
	case('com'):[$b,$a]=split_one(':',$d,1); return app($a,_jrb($b,'='),'com'); break;
	case('bt'):[$b,$a]=split_one(':',$p,1); $t=$a=='art'?art::tit(['id'=>$b]):($a);//p:a|t
		return pagup($a.',call|'.implode_k(_jrb($b,'='),',','='),pic($a).($o?$o:$t)); break;
	//case('open'):if(method_exists($p,$o))return $p::$o([]); break;
	case('popup'):return popup($p,$o?$o:pic('popup'),''); break;
	case('pagup'):return pagup($p,$o?$o:pic('pagup'),''); break;
	case('imgup'):return imgup($p,$o); break;
	case('artxt'):return art::call(['id'=>$p]); break;
	case('nh'):return tag('a',['href'=>'#nb'.$p,'name'=>'nh'.$p],'['.$p.']'); break;
	case('nb'):return tag('a',['href'=>'#nh'.$p,'name'=>'nb'.$p],'['.$p.']').' '.$o; break;
	case('ico'):return ico($p,$o?$o:24); break;
	case('pic'):return pic($p,$o); break;
	case('lang'):return lang($p,$o); break;
	case('help'):return helpx($p,$o); break;
	case('picto'):return picto($p,$o?$o:24); break;
	case('ascii'):return '&#'.$p.';'; break;
	case('aside'):return tag('aside',['id'=>'nb'.$o],$p);
	case('b64'):return img($p); break;
	case('var'):return self::$r[$p]??''; break;
	case('setvar'):self::$r[$o]=$p; return; break;
	case('gen'):$r=explode_k($o,',','='); return gen::com($p,$r,$b); break;
	case('svg'):[$w,$h,$t]=expl('/',$o,3); return svg::com($p,$w,$h,$t); break;
	case('math'):$v=self::read($d,'conn','math',$o);
		return tag('math',['xmlns'=>'http://www.w3.org/1998/Math/MathML'],$v); break;
	case('form'):return self::read($d,'conn','form',$o); break;
	case('loop'):return self::loop($p,$o); break;
	case('protect'):return jurl($p); break;
	case('auth'):return auth(6)?$p:$o; break;
	case('db'):return db::call(['f'=>'usr/'.$p]); break;
	//case('db'):return pagup($c.',call|f=usr/'.$p,span(pic($c).' '.$p,'apptit'),'appicon'); break;
	case('bj'):return bj($p,$o,''); break;
	case('no'):return '['.$d.']'; break;
	case('ko'):return; break;}
if(is_img($da))return playimg($da,'');//self::
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
$d=str_replace("<br />\n","\n",$d); $d=str_replace('<br />','',$d);
$app=$p['app']??'conn'; $mth=$p['mth']??'reader'; self::$one=0; self::$obj=[];
$ret=self::read($d,$app,$mth,$opt);
if($ptag==1)$ret=ptag($ret);
elseif($ptag!='no')$ret=nl2br($ret??'');
if($opt=='epub')$ret=str_replace("&nbsp;","&#160;",$ret); //self::$usd=0;
return $ret;}

static function com($p,$o=''){return self::call(['msg'=>$p,'ptag'=>$o]);}
static function com2($d,$a='conn',$m='reader',$r=[]){conn::$r=$r; return self::read($d,$a,$m);}

static function mincom($p,$o=''){return self::call(['msg'=>$p,'mth'=>'minconn','ptag'=>$o,'opt'=>'']);}

static function content($p){
$j='cnn|conn,call|ptag=1|msg';
$r=['id'=>'msg','rows'=>16,'cols'=>80,'class'=>'console','onkeyup'=>ajx($j),'onclick'=>ajx($j)];
$ret=build::connbt('msg').tag('textarea',$r,'');
//$ret.=bj($j,langp('ok'),'btsav');
$ret.=div('','board','cnn');
return $ret;}
}
?>