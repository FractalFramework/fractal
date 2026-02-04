<?php

class conv{
static $conn=['b'=>'b','i'=>'i','u'=>'u','small'=>'s','em'=>'b','strike'=>'k','center'=>'c','sup'=>'e','sub'=>'n'];
static $conb=['h1'=>'h1','h2'=>'h2','h3'=>'h3','h4'=>'h4','h5'=>'h5','h6'=>'h6','big'=>'h','blockquote'=>'q','ul'=>'list','ol'=>'numlist','table'=>'table'];
static $th='';

static function getxt($el,$ret=''){$attr='';
if(!isset($el->tagName))return $ret.$el->textContent;
$el=$el->firstChild;
if($el!=null)$ret=self::getxt($el,$ret);
while(isset($el->nextSibling)){$ret=self::getxt($el->nextSibling,$ret); $el=$el->nextSibling;}
return $ret;}

static function detect_table($dom){$rt=[];
$r=$dom->getElementsByTagName('tr');
foreach($r as $k=>$v){$rt[$k]=[];
	$rb=$v->getElementsByTagName('th'); if(!$rb['length'])$rb=$v->getElementsByTagName('td');
	if($rb)foreach($rb as $kb=>$el)$rt[$k][$kb]=self::com(self::getxt($el));}
return $rt;}

static function get_table($u){
$d=get_file2($u); $dom=dom($d);
return $dom->getElementsByTagName('table');}

static function select_table($p){
$u=$p['url']; $n=$p['n']??0;
$r=self::get_table($u);
$rt=self::detect_table($r[$n]);
return tabler($rt);}

static function call_table($p){
$u=$p['url']; $n=$p['n']??0; $bt='';
$r=self::get_table($u); $na=count($r);
if(!$r[$n])return help('no_result');
for($i=0;$i<$na;$i++)$bt.=bj('dtct|conv,call_table|url='.jurl($u).',n='.$i,$i,active($i,$n));
$rt=self::detect_table($r[$n]); $ret=tabler($rt);
//$bt2=bj('txt,,resetdiv|core,send|v=res|res',lang('keep'),'btsav');
$bt2=bj('txt,,reset:dtct|conv,select_table|url='.jurl($u).',n='.$n,lang('keep'),'btsav');
return div(div($bt,'nbp').div($ret.$bt2,'','res'),'','dtct');}

//im
static function b64img($u){
if(substr($u,0,21)=='data:image/png;base64'){$d=substr($u,22); $xt='.png';}
elseif(substr($u,0,22)=='data:image/jpeg;base64'){$d=substr($u,23); $xt='.jpg';}
$f=strid($d).$xt;
write_file('img/full/'.$f,base64_decode($d));
upload::add_img_catalog($f,'b64');
return $f;}

//tags
static function tags($tag,$atb,$d){
//if(mb_strpos($atb,'align="center"')!==false)$d='['.$d.':c]';
switch($tag){
case('a'):$u=between($atb,'href="','"'); //$ub=between($atb,'data-j="','"');
	if($d==domain($u))$d='';
	if($u==$d && $u)return '['.trim($u).':url]';
	$pv=video::provider($u); if($pv)return '['.video::extractid($u,$pv).($d?'|'.$d:'').':video]';
	if($u && substr($u,0,3)=='#nh')return '['.substr($u,3).':nb]';
	if($u && substr($u,0,3)=='#nb')return '['.substr($u,3).':nh]';
	if($u)return '['.trim($u).($d?'|'.trim($d):'').':url]'; break;
case('img'):$u=between($atb,'src="','"'); $alt=between($atb,'alt="','"'); $b64='';
	$w=between($atb,'width="','"'); $h=between($atb,'height="','"');
	if(substr($u,0,10)=='data:image')$u=self::b64img($u);
	elseif(substr($u,0,4)=='http')$u=saveimg($u,'art',$w);
	elseif(substr($u,0,9)=='/img/mini')return '';
	//elseif(substr($u,0,9)=='/img/full')$d=strend($u,'/');
	elseif(substr($u,0,4)!='http')$u=strend($u,'/');
	//if($w && $h)$u.='|'.$w.'-'.$h;
	return '['.$u.''.($alt?'|'.$alt:'').']'; break;//:img
case('table'):
	if(mb_substr($d,-1,1)=='¬')$d=mb_substr($d,0,-1);
	if(post('th')){$o='|1'; self::$th='';} else $o='';
	return '['.$d.$o.':table]';break;//.$o
case('big'):return '['.$d.':big]'; break;
case('center'):return '['.$d.':c]'; break;
case('aside'):return '['.$d.':aside]'; break;
case('tr'):if(mb_substr($d,-1,1)=='|')$d=trim(mb_substr($d,0,-1));
	$d=strip_tags($d); return $d?str_replace("\n",' ',$d)."\n":''; break;
case('th'):self::$th=1; $d=trim($d); return $d?str_replace('|','',trim($d)).'|':''; break;
case('td'):$d=trim($d); return $d?str_replace('|','',$d).'|':''; break;
case('font'):return $d; break;//font-size
case('li'):return trim($d)."\n"; break;
case('ul'):return '['.$d.':list]'."\n"; break;
case('div'):return $d."\n\n"; break;
case('hr'):return '[--]'; break;
case('p'):return $d."\n\n"; break;
case('embed'):return video::mkconn(between($atb,'src="','"'))."\n\n"; break;
case('iframe'):$u=between($atb,'src="','"'); if($s=strpos($u,'?'))$u=substr($u,$s);
		if($pv=video::provider($u))return '['.video::extractid($u,$pv).':video]';
		else return '['.$u.':iframe]'."\n\n"; break;}
$r=self::$conn; if($d && isset($r[$tag]))return '['.$d.':'.$r[$tag].']';
$r=self::$conb; if($d && isset($r[$tag]))return "\n\n".'['.$d.':'.$r[$tag].']'."\n\n";
return $d;}

//pousse si autre balise similaire
static function recursearch($v,$ab,$ba,$tag){static $i; $i++;
$bb=mb_strpos($v,'>',$ba); $d=self::ecart($v,$ab,$ba);
$nab=mb_strpos($d,'<'.$tag); $nba=mb_strpos($d,'</'.$tag);
if($nab!==false && $nba===false){$bab=mb_strpos($v,'</'.$tag,$ba+1);
	if($bab!==false && $i<100)$ba=self::recursearch($v,$bb,$bab,$tag);}
return $ba;}

static function recursearch_a($v,$ab,$ba,$tag){static $i; $i++;
$bb=strpos($v,'>',$ba); $d=self::ecart($v,$ab,$ba);
$nab=strpos($d,'<'.$tag); $nba=strpos($d,'</'.$tag);
if($nab!==false && $nba===false){$bab=strpos($v,'</'.$tag,$ba+1);
	if($bab!==false && $i<100)$ba=self::recursearch($v,$bb,$bab,$tag);}
return $ba;}

static function recursearch_0($v,$ab,$ba,$aa_bal){//pousse si autre balise similaire
$bb=strpos($v,'>',$ba); $bal=self::ecart($v,$ab,$ba);
if(strpos($bal,'<'.$aa_bal)!==false){$bab=strpos($v,'</'.$aa_bal,$ba+1);
	if($bab!==false)$ba=self::recursearch($v,$bb,$bab,$aa_bal);}
return $ba;}

static function ecart($v,$a,$b){
//if($b<$a+2)return $v;
return mb_substr($v,$a+1,$b-$a-1);}

static function cleanhtml($d){;
$r=['b','i','u','em','strong','strike','ul','ol','blockquote'];
foreach($r as $k=>$v){
	$d=str_replace('<'.$v.'> ',' <'.$v.'>',$d);
	$d=str_replace(' </'.$v.'>','</'.$v.'> ',$d);
	$d=str_replace('<'.$v.'></'.$v.'>','',$d);
	$d=str_replace('<'.$v.'> </'.$v.'>','',$d);
	$d=str_replace('<'.$v.'>."\n".</'.$v.'>',"\n",$d);
	$d=str_replace('</'.$v.'><'.$v.'>','',$d);
	$d=str_replace('</'.$v.'> <'.$v.'>',' ',$d);
	$d=str_replace('</'.$v.'>."\n"<'.$v.'>',"\n",$d);}
return $d;}

static function cleanconn($d){
$d=str_replace('['."\n","\n".'[',$d);
$r=self::$conn+self::$conb;
foreach($r as $k=>$v){
	$d=str_replace("\n".':'.$v.']',':'.$v.']'."\n",$d);
	$d=str_replace(' :'.$v.']',':'.$v.'] ',$d);
	//$d=str_replace(':'.$v.'].','.:'.$v.']',$d);
	$d=str_replace('[:'.$v.']','',$d);}
return $d;}

static function parse($v,$x=''){
$tag=''; $atb=''; $txt=''; $before='';
$aa=mb_strpos($v,'<'); $ab=mb_strpos($v,'>');//tag
if($aa!==false && $ab!==false && $ab>$aa){
$before=mb_substr($v,0,$aa);//...<
$atb=self::ecart($v,$aa,$ab);//<...>
	$aa_end=mb_strpos($atb,' ');
	if($aa_end!==false)$tag=mb_substr($atb,0,$aa_end);
	else $tag=$atb;}
$ba=mb_strpos($v,'</'.$tag,$ab); $bb=mb_strpos($v,'>',$ba);//end
if($ba!==false && $bb!==false && $tag && $bb>$ba){
	$ba=self::recursearch($v,$ab,$ba,$tag);
	$bb=mb_strpos($v,'>',$ba);
	$tagend=self::ecart($v,$ba,$bb);
	$txt=self::ecart($v,$ab,$ba);}
elseif($ab!==false)$bb=$ab;
else{$bb=-1;}
$after=mb_substr($v,$bb+1);//>...
$tag=strtolower($tag);
//itération
if(mb_strpos($txt,'<')!==false)$txt=self::parse($txt,$x);
if(!$x)//interdit l'imbrication
	$txt=self::tags($tag,$atb,$txt);
//sequence
if(mb_strpos($after,'<')!==false)$after=self::parse($after,$x);
$ret=$before.$txt.$after;
return $ret;}

/**/
static function getcnt($el,$ret=''){static $td=[]; static $tr=[];
//pr($el);
//$tg=$el->tagName;
if($el->childNodes)return self::parsedom($el);
if(!isset($el->tagName)){$el0=$el->parentNode; $tg=$el0->tagName; echo $tg.'-';
	switch($tg){
	case('img'):return '['.$el0->getAttribute('src').':img]'; break;
	case('a'):return '['.$el0->getAttribute('href').'|'.$el->nodeValue.':url]'; break;
	case('b'):return '['.$el->nodeValue.':b]'; break;
	case('td'):$td[]=$el->nodeValue; return; break;
	case('tr'):$tr[]=implode('|',$td); $td=[]; return; break;
	case('table'):$ret='['.implode("\n",$tr).':table]'; $tr=[]; return $ret; break;
	case('div'):return $el->nodeValue.n().n(); break;
	case('p'):return $el->nodeValue.n().n(); break;
	case('li'):$tr[]=$el->nodeValue; return; break;
	case('ul'):$ret='['.implode("\n",$tr).':list]'; $tr=[]; return $ret; break;
	case('ol'):$ret='['.implode("\n",$tr).':numlist]'; $tr=[]; return $ret; break;
	default:return $el->nodeValue; break;}}
/*while(isset($el->nextSibling)){$el2=$el->nextSibling;
	$ret=self::getxt($el->nextSibling,$ret); $el=$el->nextSibling;}*/
return $ret;}

static function parsedom($dom){$rt=[]; //pr($dom);
foreach($dom->childNodes as $k=>$el)$rt[]=self::getcnt($el);
return join('',$rt);}

static function call($p){
$d=$p['txt']??''; //eco($d);
//$d=unicode($d);
//if(!$p['brut']??'')$d=str::deln($d);
$d=str::delt($d);
$d=str::delnbsp($d);
$d=str::delr($d);
$d=str::deln($d,' ');
//$d=str::clean_mail($d);
if(strpos($d,'<br>')!==false && strpos($d,"\n")!==false)$d=str::deln($d);
if(strpos($d,'<br>')!==false && strpos($d,"\n")===false)$d=str::delbr($d,"\n");
//$d=str::delp($d);
$d=str::clean_lines($d);
$d=self::cleanhtml($d);
$d=self::parse($d);
//$d=self::parsedom(dom($d));
$d=self::cleanconn($d);
$d=str::delbr($d,"\n");
$d=str::clean_n($d);
$d=str::repair_punct($d);
//eco($d);
return $d;}

static function com($p){return self::call(['txt'=>$p]);}

static function import($p){
[$ti,$tx,$im]=vacuum::com($p['url']??'');
$ret=self::call(['txt'=>$tx]);
if($ti)$ret='['.$ti.':h]'.n().$ret;
if($p['html']??'')$ret=conn::call(['msg'=>$ret,'ptag'=>'1']);
return $ret;}

static function content($p){
$rid=randid('cnv');
$j=$rid.'|conv,call||txt';
$bt=textarea('txt','',34,6,'html code','console');
$bt.=bj($j,lang('ok'),'btn');
return $bt.div('','board',$rid);}

}
?>