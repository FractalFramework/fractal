<?php

class conv{
static $conn=['b'=>'b','i'=>'i','u'=>'u','small'=>'s','em'=>'b','strike'=>'k','center'=>'c','sup'=>'e','sub'=>'n'];
static $conb=['h1'=>'h1','h2'=>'h2','h3'=>'h3','h4'=>'h4','h5'=>'h5','h6'=>'h6','big'=>'h','blockquote'=>'q','ul'=>'list','ol'=>'numlist'];
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
for($i=0;$i<$na;$i++)$bt.=bj('dtct|conv,call_table|url='.jurl($u).',n='.$i,$i,act($i,$n));
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
if(strpos($atb,'align="center"')!==false)$d='['.$d.':c]';
switch($tag){
case('a'):$u=segment($atb,'href="','"'); $ub=segment($atb,'data-j="','"');
	if($d==domain($u))$d='';
	if($u==$d && $u)return '['.trim($u).':url]';
	$pv=video::provider($u); if($pv)return '['.video::extractid($u,$pv).($d?'§'.$d:'').':video]';
	if(substr($u,0,3)=='#nh')return '['.substr($u,3).':nb]';
	if(substr($u,0,3)=='#nb')return '['.substr($u,3).':nh]';
	if($u)return '['.trim($u).($d?'§'.trim($d):'').':url]'; break;
case('img'):$u=segment($atb,'src="','"'); $b64='';
	$w=segment($atb,'width="','"'); $h=segment($atb,'height="','"');
	if(substr($u,0,10)=='data:image')$u=self::b64img($u);
	elseif(substr($u,0,4)=='http')$u=saveimg($u,'art',$w,$h='');
	elseif(substr($u,0,9)=='/img/mini')return '';
	//elseif(substr($u,0,9)=='/img/full')return $u;
	elseif(substr($u,0,4)!='http')$u=strend($u,'/');
	//if($w && $h)$u.='�'.$w.'-'.$h;
	return '['.$u.']'; break;//:img
case('table'):
	if(mb_substr($d,-1,1)=='�')$d=mb_substr($d,0,-1);
	if(post('th')){$o='�1'; self::$th='';} else $o='';
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
case('embed'):return video::mkconn(segment($atb,'src="','"'))."\n\n"; break;
case('iframe'):$u=segment($atb,'src="','"'); if($s=strpos($u,'?'))$u=substr($u,$s);
		if($pv=video::provider($u))return '['.video::extractid($u,$pv).':video]';
		else return '['.$u.':iframe]'."\n\n"; break;}
$r=self::$conn; if($d && isset($r[$tag]))return '['.$d.':'.$r[$tag].']';
$r=self::$conb; if($d && isset($r[$tag]))return "\n".'['.$d.':'.$r[$tag].']'."\n";
return $d;}

static function recursearch($v,$ab,$ba,$tag){//pousse si autre balise similaire
$bb=strpos($v,'>',$ba); $d=self::ecart($v,$ab,$ba); 
if(strpos($d,'<'.$tag)!==false){$bab=strpos($v,'</'.$tag,$ba+1);
	if($bab!==false)$ba=self::recursearch($v,$bb,$bab,$tag);}
return $ba;}

static function ecart($v,$a,$b){return substr($v,$a+1,$b-$a-1);}

static function cleanhtml($d){;
$r=['b','i','u','strike','ul','ol','li'];
foreach($r as $k=>$v){
	$d=str_replace('</'.$v.'><'.$v.'>','',$d);
	$d=str_replace('</'.$v.'> <'.$v.'>',' ',$d);}
return $d;}

static function cleanconn($d){
$d=str_replace('['."\n","\n".'[',$d);
$r=self::$conn+self::$conb;
foreach($r as $k=>$v){
	$d=str_replace("\n".':'.$v.']',':'.$v.']'."\n",$d);
	$d=str_replace(' :'.$v.']',':'.$v.'] ',$d);
	$d=str_replace(':'.$v.'].','.:'.$v.']',$d);
	$d=str_replace('[:'.$v.']','',$d);}
return $d;}

static function parse($v,$x=''){
$tag=''; $atb=''; $txt=''; $before='';
$aa=strpos($v,'<'); $ab=strpos($v,'>');//tag 
if($aa!==false && $ab!==false && $ab>$aa){
$before=substr($v,0,$aa);//...<
$atb=self::ecart($v,$aa,$ab);//<...>
	$aa_end=strpos($atb,' ');
	if($aa_end!==false)$tag=substr($atb,0,$aa_end);
	else $tag=$atb;}
$ba=strpos($v,'</'.$tag,$ab); $bb=strpos($v,'>',$ba);//end
if($ba!==false && $bb!==false && $tag && $bb>$ba){ 
	$ba=self::recursearch($v,$ab,$ba,$tag);
	$bb=strpos($v,'>',$ba);
	$tagend=self::ecart($v,$ba,$bb);
	$txt=self::ecart($v,$ab,$ba);}
elseif($ab!==false)$bb=$ab;
else{$bb=-1;}
$after=substr($v,$bb+1);//>...
$tag=strtolower($tag);
//it�ration
if(strpos($txt,'<')!==false)$txt=self::parse($txt,$x);
if(!$x)//interdit l'imbrication
	$txt=self::tags($tag,$atb,$txt);
//sequence
if(strpos($after,'<')!==false)$after=self::parse($after,$x);
$ret=$before.$txt.$after;
return $ret;}

/*static function getcnt($el,$ret=''){$attr=''; $at='class'; static $rtd=[]; static $rtr=[];
if(!isset($el->tagName)){$el0=$el->parentNode; $tg=$el0->tagName;
	if($tg=='a'){$attr=$el0->getAttribute('href'); return $ret.'['.$attr.'�'.$el->textContent.':url]';}
	elseif($tg=='img'){$attr=$el0->getAttribute('src'); return $ret.'['.$attr.':img]';}
	elseif($tg=='td'){$rtd[]=$el->textContent; return $ret;}
	elseif($tg=='tr'){$rtr[]=implode('|',$rtd); $rtd=[]; return $ret;}
	elseif($tg=='table'){$ret.='['.implode("\n",$rtr).':table]'; $rtr=[]; return $ret;}
	elseif($tg=='div'){$ret.=$ret.$el->textContent.n().n();}
	elseif($tg=='p'){$ret.=$ret.$el->textContent.n().n();}
	elseif($tg=='li'){$rtr[]=$el->textContent; return $ret;}
	elseif($tg=='ul'){$ret.='['.implode("\n",$rtr).':list]'; $rtr=[]; return $ret;}
	//if($el0->hasAttribute($at)!=null);
	else return $ret.$el->textContent;}
$el=$el->firstChild;
if($el!=null)$ret=self::getxt($el,$ret);
while(isset($el->nextSibling)){$el2=$el->nextSibling;
	$ret=self::getxt($el->nextSibling,$ret); $el=$el->nextSibling;}
return $ret;}

static function parsedom($dom){$rt=[];
foreach($dom->childNodes as $k=>$el){$rt[$k]=self::getxt($el);}
return $rt;}*/

static function call($p){
$d=val($p,'txt');
//$d=unicode($d);
//if(!$p['brut']??'')$d=deln($d);
$d=delt($d);
$d=deln($d,' ');
//$d=clean_mail($d);
if(strpos($d,'<br>')!==false && strpos($d,"\n")!==false)$d=deln($d);
if(strpos($d,'<br>')!==false && strpos($d,"\n")===false)$d=delbr($d,"\n");
$d=delp($d);
$d=clean_lines($d);
$d=self::cleanhtml($d);
$d=self::parse($d);
$d=self::cleanconn($d);
$d=delbr($d,"\n");
$d=clean_n($d);
$d=cleansp($d);
$d=nbsp($d);
//eco($d);
return $d;}

static function com($p){return self::call(['txt'=>$p]);}

static function import($p){
//list($ti,$tx,$im)=mercury::com($p['url']??'');
list($ti,$tx,$im)=vacuum::com($p['url']??'');
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