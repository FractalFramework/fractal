<?php
#Fractal GNU/GPL
//visit: /admin_lib

#autoload
function loadapp($d){$dr=ses('dev'); $r=sesf('scandir_b',$dr,0);
	if($r)foreach($r as $k=>$v){$f=$dr.'/'.$v.'/'.$d.'.php'; if(file_exists($f))require_once $f;}
	$f=$dr.'/'.$d.'.php'; if(file_exists($f))require_once $f;}
spl_autoload_register('loadapp');

#dev
function p($r){print_r($r);}
function pr($r,$o=''){$ret='<pre>'.print_r($r,true).'</pre>'; if($o)return $ret; else echo $ret;}
function vd($r){var_dump($r);}
function br(){return '<br />';}
function hr(){return '<hr />';}
function sp(){return '&nbsp;';}
//function sp(){return '&#160;';}
function ns(){return '&thinsp;';}
function n(){return "\n";}

#html
function atb($d,$v){if($v)return ' '.$d.'="'.$v.'"';}
function atc($d){if($d)return ' class="'.$d.'"';}
function atd($d){if($d)return ' id="'.$d.'"';}
function ats($d){if($d)return ' style="'.$d.'"';}
function atn($d){if($d)return ' name="'.$d.'"';}
function atv($d){if($d)return ' value="'.$d.'"';}
function atz($d){if($d)return ' size="'.$d.'"';}
function att($d){if($d)return ' title="'.$d.'"';}
function atj($d,$r){return $d.str_replace('\'this\'','this','(\''.(is_array($r)?implode('\',\'',$r):$r).'\');');}
function ath($d){return 'onFocus="if(this.value==\''.$d.'\')this.value=\'\'"; onBlur="if(this.value==\'\')this.value=\''.$d.'\'";';}
function atp($c,$id='',$s='',$w='',$h=''){
	if($c)$r['class']=$c; if($s)$r['style']=$s; if($id)$r['id']=$id;
	if($w)$r['width']=$w; if($h)$r['height']=$h; return $r;}
function ajx($d){return atj('ajx',$d);}

//tags
function tag($tag,$r,$t=false,$o=''){$p='';
	if(is_string($r))$r=prmr($r);
	if(is_array($r))foreach($r as $k=>$v)$p.=atb($k,$v);
	if($o && $t===false)return '<'.$tag.$p.'/>'; else return '<'.$tag.$p.'>'.$t.'</'.$tag.'>';}
function div($t,$c='',$id='',$s='',$r=[]){
	if($id)$r['id']=$id; if($c)$r['class']=$c; if($s)$r['style']=$s;
	return tag('div',$r,$t);}
function span($t,$c='',$id='',$s='',$r=[]){
	if($id)$r['id']=$id; if($c)$r['class']=$c; if($s)$r['style']=$s;
	return tag('span',$r,$t);}
function ul($t,$c='',$id='',$s=''){
	return tag('ul',['id'=>$id,'class'=>$c,'style'=>$s],$t);}
function li($t,$c='',$id='',$s=''){
	return tag('li',['id'=>$id,'class'=>$c,'style'=>$s],$t);}

function lk($u,$t='',$c='',$o='',$id=''){if(!$t)$t=domain($u);
	$r=['href'=>$u]; if($c)$r['class']=$c; if($o)$r['target']='_blank'; if($id)$r['id']=$id;
	return tag('a',$r,$t);}
function btj($t,$j,$c='',$id='',$ti=''){$r=['onclick'=>$j];
	if($c)$r['class']=$c; if($id)$r['id']=$id; if($ti)$r['title']=$ti; return tag('a',$r,$t);}
function bubjs($tx,$t,$c=''){$id=randid();
	$r=['onmouseover'=>atj('bubjs',[$tx,$id,1]),'onmouseout'=>atj('bubjs',[$tx,$id,0]),'id'=>$id]; 
	if($c)$r['class']=$c; return tag('a',$r,$t);}
function close($id,$t,$c=''){return btj($t,atj('closediv',$id),$c);}
function btn($r,$t){return tag('button',$r,$t);}
function small($t){return tag('small','',$t);}
function divh($d,$id){return div($d,'',$id,'display:none;');}
function iframe($f,$w='',$h=''){return tag('iframe',['width'=>$w,'height'=>$h,'frameborder'=>'0','scrolling'=>'no','marginheight'=>'0','marginwidth'=>'0','src'=>$f],'');}

function img($src,$w='',$h='',$c='',$s=''){
	if(is_numeric($w))$w.='px'; if(is_numeric($h))$h.='px'; $r['src']=$src;
	if($w)$r['width']=$w; if($h)$r['height']=$h; if($s)$r['style']=$s; if($c)$r['class']=$c;
	return tag('img',$r,'','1');}
function picture($f){
$ret=tag('source',['media'=>'min-width:0px','srcset'=>imgroot($f,'medium')],'',1);
$ret.=tag('source',['media'=>'min-width:1000px','srcset'=>imgroot($f,'full')],'',1);
$ret.=tag('img','','',1);
return $ret;}
function video($f,$w='',$h=''){if(!$w)$w='100%'; return '<video controls width="'.$w.'" height="'.$h.'"><source src="'.goodir($f).'" type="video/mp4"></video>';}
function audio($f,$id=''){return '<audio controls>
<source id="mp3'.$id.'" src="'.goodir($f).'" type="audio/mpeg"></audio>';}

//forms
function input($id,$v,$s='',$h='',$num='',$mx='',$j='',$no=''){$r=['id'=>$id];
	if($h==1)$r['placeholder']=$v; elseif($v!=='')$r['value']=$v;
	if($h && $h!=1){$r['placeholder']=$h; $r['title']=$h;}
	if($s)$r['size']=$s; if($mx)$r['maxlength']=$mx; if($no)$r['readonly']=true; if($num)$r['type']='number';
	if($num)$j='numonly(this);'.$j; if($j){$r['onkeyup']=$j; $r['onchange']=$j;}//$r['onclick']=$j; 
	return tag('input',$r,'',1);}
function inpdate($id,$v,$min='',$max='',$o=''){$ty=$o?'datetime-local':'date';//time//step=1
return tag('input',['type'=>$ty,'id'=>$id,'name'=>$id,'value'=>$v,'min'=>$min,'max'=>$max],'',1);}
function inpnb($id,$v,$min='',$max='',$st=1){
return tag('input',['type'=>'number','id'=>$id,'name'=>$id,'value'=>$v,'min'=>$min,'max'=>$max,'step'=>$st],'',1);}
function inpcolor($id,$v=''){return tag('input',['type'=>'color','id'=>$id,'name'=>$id,'value'=>$v],'',1);}
function inpmail($id){return tag('input',['type'=>'mail','id'=>$id,'name'=>$id],'',1);}
function inptel($id,$v,$pl='06-01-02-03'){return tag('input',['type'=>'tel','id'=>$id,'name'=>$id,'value'=>$v,'placeholder'=>$pl,'pattern'=>"[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}"],'',1);}
function goodinput($id,$v){if(strlen($v)<20)return input($id,$v); else return textarea($id,$v,40,4);}
function hidden($id,$v){return tag('input',['type'=>'hidden','id'=>$id,'value'=>$v],'',1);}
function password($id,$v,$sz='',$h=''){
	$r=['type'=>'password','id'=>$id,'value'=>$v,'size'=>$sz];
	if($h)$r['placeholder']=$h!=1?$h:$v;
	return tag('input',$r,'',1);}
function textarea($id,$v,$cols=64,$rows=8,$h='',$c='',$n='',$js=''){
	$r=['id'=>$id,'cols'=>$cols,'rows'=>$rows]; $op='';
	if($h)$r['placeholder']=$h!=1?$h:$v; if($c)$r['class']=$c;
	if($n){$js='strcount(\''.$id.'\','.$n.'); '.$js; $op=' '.span($n-mb_strlen($v),'small','strcnt'.$id.'');}
	if($js){$r['onclick']=ajx($js); $r['onkeyup']=ajx($js);}
	return tag('textarea',$r,$v).$op;}
function textareact($id,$v,$cl=64,$rw=8,$j='',$c=''){if($j)$j='ajxt(\''.$j.'\',this);';
	$r=['id'=>$id,'cols'=>$cl,'rows'=>$rw,'onclick'=>$j,'onkeyup'=>$j,'class'=>$c?$c:'console wd'];
	return tag('textarea',$r,$v);}
function divarea($id,$v,$c='article',$o=''){$bt=build::wsgbt($id,$o);
	return $bt.tag('div',['id'=>$id,'class'=>$c,'contenteditable'=>'true'],$v);}

function select($id,$r,$ck='',$o='',$lg='',$j='',$js=''){$ret='';
$ra=['id'=>$id]; if($j)$ra['onchange']=atj('ajx',$j.'\'+this.value+\''); if($js)$ra['onchange']=$js;
if($r)foreach($r as $k=>$v){$rb=[];
	if($o)$k=is_numeric($k)?$v:$k; if($lg)$v=lang($v);
	if($k==$ck)$rb['selected']='selected'; $rb['value']=$k;
	$ret.=tag('option',$rb,$v?$v:$k);}
	return tag('select',$ra,$ret);}
function radio($id,$r,$ck,$o=''){$ret='';
$rk=explode('-',$ck); $rk=array_flip($rk);
foreach($r as $k=>$v){$ka=$k;
	if($o)$k=is_numeric($k)?$v:$k; $kb=$id.normalize($k);
	$atb=['type'=>'radio','name'=>$id,'id'=>$kb,'value'=>$k];
	if(isset($rk[$k]))$atb['checked']='checked';
	$ret.=span(tag('input',$atb,'',1).label($kb,lang($v)),'btn').' ';}
	return $ret;}
function checkbox($id,$r,$ck='',$o=''){$ret='';
$rk=explode('-',$ck); $rk=array_flip($rk);
foreach($r as $k=>$v){$ka=$k;
	if($o)$k=is_numeric($k)?$v:$k; $kb=$id.normalize($k);
	$atb=['type'=>'checkbox','name'=>$id,'id'=>$kb,'value'=>$k];
	if(isset($rk[$ka]))$atb['checked']='checked';
	$ret.=span(tag('input',$atb,'',1).label($kb,lang($v)),'btn').' ';}
	return $ret;}
function datalist($id,$r,$v,$s=34,$t=''){$ret=''; $opt='';
	$ret=tag('input',['id'=>$id,'name'=>$id,'list'=>'dt'.$id,'size'=>$s,'value'=>$v,'placeholder'=>$t],'',1);
	foreach($r as $v)$opt.=tag('option','value='.$v,'',1);
	$ret.=tag('datalist',['id'=>'dt'.$id],$opt);
	return $ret;}
function bar($id,$v=1,$step=10,$min=0,$max=100,$in='',$j='',$c='',$s=''){if(!$v)$v=$step;
	$bt=$in?input('lbl'.$id,$v,8,'',1,'','val(this.value,\''.$id.'\');'.$j):span($v,'btn','lbl'.$id);
	return tag('input',['type'=>'range','id'=>$id,'name'=>$id,'min'=>$min,'max'=>$max,'step'=>$step,'value'=>$v,'onchange'=>($in?'val':'inn').'(this.value,\'lbl\'+id);'.$j,'class'=>$c,'style'=>'width:'.$s],'',1).$bt;}
function label($for,$v,$id='',$c=''){return tag('label',['for'=>$for,'id'=>$id,'class'=>$c],$v);}
function input_label($id,$v,$t,$s=12){return input($id,$v,$s,$t).label($id,$t);}//div()
function input_row($id,$bt,$t){return div(div(label($id,lang($t)),'cell2').div($bt,'cell'),'row');}
function input_pic($id,$v,$t,$p,$o=''){if($o)$o=label($id,$t); return span(pic($p).input($id,$v,30,$t).$o,'inpic');}
function progress($id,$n){return tag('progress',['id'=>$id,'value'=>$n,'max'=>100],'');}

function select2($id,$r,$ck='',$o='',$lg='',$j=''){$ret='';
$ra=['id'=>$id]; if($j)$ra['onchange']=atj('ajx',$j.'\'+this.value+\'');
	function values($k,$v,$ck,$o,$lg){$rb=[];
		if($o)$k=is_numeric($k)?$v:$k; if($lg)$v=lang($v);
		if($k==$ck)$rb['selected']='selected'; $rb['value']=$k;
		return tag('option',$rb,$v?$v:$k);}
	function options($r,$ck,$o,$lg){$ret=''; foreach($r as $k=>$v)$ret.=values($k,$v,$ck,$o,$lg); return $ret;}
	if($r)foreach($r as $k=>$v)
		if(is_array($v))$ret.=tag('optgroup',['label'=>$k],options($v,$ck,$o,$lg));
		else $ret.=values($k,$v,$ck,$o,$lg);
	return tag('select',$ra,$ret);}

function mkform($id,$j,$r){$d='';
$rp=['id'=>$id,'action'=>'ajbt(this);','data-j'=>$j,'onkeyup'=>atj('checkenter','this')];
foreach($r as $k=>$v)$d.=tag('input',$v,'',1).label($v['name'],$v['label']);
$d.=tag('submit',['class'=>'btsav'],langp('ok'));
return tag('form',$rp,$d);}

//formj
function inputcall($j,$id,$v,$s='',$h='',$p='',$r=[]){$r['id']=$id;
	if($h==1)$r['placeholder']=$v; elseif($h){$r['placeholder']=$h; $r['title']=$h;} if($h!=1)$r['value']=$v;
	if($s)$r['size']=$s; $r['data-j']=$j; $js='checkj(event,this);'; $onk=$r['onkeyup']??''; $r['onkeyup']=$js.$onk;
	$ret=tag('input',$r,'',1); if($p)return span(pic($p).$ret,'inpic'); else return $ret;}
function areacall($j,$id,$v,$c='',$h=''){
	$r['id']=$id; if($c)$r['class']=$c; $r['data-j']=$j; if($h)$r['placeholder']=$h;
	$r['onkeypress']='checkj(event,this);';//$r['onkeyup']='checkj(event,this)';
	return tag('textarea',$r,$v);}
function datalistcall($id,$r,$v,$j,$t='',$s=16){$opt='';
	$pr=['id'=>$id,'list'=>'dt'.$id,'size'=>$s,'value'=>$v,'placeholder'=>$t,'size'=>$s,'onkeyup'=>'checkj(event,this)','data-j'=>$j]; $ret=tag('input',$pr,'',1);
	foreach($r as $k=>$v)$opt.=tag('option','value='.$v,'',1);
	return $ret.tag('datalist',['id'=>'dt'.$id],$opt);}
function datalistj($id,$v,$j,$ja,$t='',$s=16){$ret=''; $opt=''; $ja='dt'.$id.'|'.$ja.',rid='.$id.'|'.$id;
	$r=['id'=>$id,'list'=>'dt'.$id,'size'=>$s,'value'=>$v,'placeholder'=>$t,'onkeyup'=>'callj(event,this); checkj(event,this);','onclick'=>'ajbt(this)','data-j'=>$j,'data-ja'=>$ja];
	return tag('input',$r,'',1).tag('datalist',['id'=>'dt'.$id],$opt);}

#bj
function aj($tg,$a,$p,$inp,$bt,$c){return bj($tg.'|'.$a.'|'.prm($p).'|'.implode(',',$inp),$bt,$c);}
function bj($call,$t,$c='',$r=[]){//wait for data-jb/-prmtm/-toggle
	$onc=$r['onclick']??''; $r['data-j']=$call; if($c)$r['class']=$c; $r['onclick']='ajbt(this);'.$onc;
	if(ses('dev')=='prog')$r['title']=vadd($r,'title',$call); return tag('a',$r,$t);}
function bjs($j){$p=explode('|',$j); if(isset($p[2]))$p[2]=_jr(explode(',',$p[2]));return atj('ajx',implode('|',$p));}
function bjt($call,$t,$c='',$ti=''){return bj($call,$t,$c,['title'=>$ti]);}
function bjlog($call,$t,$c='',$r=[]){if(auth(1))return bj($call,$t,$c,$r); else return popup('login,com',$t,$c);}
function bjk($call,$t,$c,$u){return bj($call,$t,$c,['data-u'=>$u]);}
function bjtab($call,$t,$c){static $i; $i++; return bj($call,$t,$c,['data-tab'=>'togtab']);}
function popup($call,$t,$c='',$r=[]){return bj('popup,,,1|'.$call,$t,$c,$r);}
function pagup($call,$t,$c='',$r=[]){return bj('pagup,,,1|'.$call,$t,$c,$r);}
function imgup($f,$t,$c=''){return bj('imgup|core,img|f='.$f,$t,$c);}
function bubble($call,$t,$c='',$r=[],$o=''){$id=randid('bb');//,['id'=>$id]
	return span(bj('bubble,'.$id.','.$o.'|'.$call,$t,$c),'',$id);}//.ico('caret-down')
function toggle($call,$t,$c='',$r=[],$o='',$ko=''){
	[$id,$j]=split_one('|',$call); $dv=''; $ob=''; if(strpos($id,','))[$id,$ob]=split_one(',',$id);
	if(!$id){$id=randid('tg'); $call=$id.','.$ob.'|'.$j; $dv=div('','tgbox',$id);}//$o?call($call):
	$r['id']=randid('tg'); $r['data-toggle']=$id; $r['rel']=$o; $r['data-ko']=$ko;
	return bj($call,$t,$c.($o?' active':''),$r).$dv;}
function togbt($j,$jb,$t,$c='',$o=0){
	$r=['data-j'=>$j,'data-jb'=>$jb,'rel'=>$o,'onclick'=>'togbt(this)','class'=>$c.act($o,1)];
	return tag('a',$r,$t);}
function call($d){$r=explode('|',$d); [$a,$m]=expl(',',$r[1],2); if(!$m)$m='content';
	$p=prmr($r[2]); if(method_exists($a,$m))return $a::$m($p);}
function ajtime($call,$prm,$t,$c=''){$id=randid('bbt');
	$r['onmouseover']='ajxt(\'bubble,'.$id.',1|'.$call.'|'.$prm.'\',this); zindex(\''.$id.'\');';
	$r['onmouseout']='clearTimeout(xc);'; $r['onmouseup']='clearTimeout(xc);';
	return span(bj('bubble,'.$id.',1|'.$call,$t,$c,$r),'',$id);}

#js
function insert($d,$rid,$o=''){return 'insert(\''.$d.'\',\''.$rid.'\',this);'.($o?' Close(\'popup\');':'');}

#php
function strprm($d,$s,$n){$r=explode($s,$d); return $r[$n]??'';}
function strto($d,$s){$p=strpos($d,$s); return $p!==false?substr($d,0,$p):$d;}
function struntil($d,$s){$p=strrpos($d,$s); return $p!==false?substr($d,0,$p):$d;}
function strend($d,$s){$p=strrpos($d,$s); return $p!==false?substr($d,$p+strlen($s)):$d;}
function strfrom($d,$s){$p=strpos($d,$s); return $p!==false?substr($d,$p+strlen($s)):$d;}
function segment($d,$s,$e){$pa=strpos($d,$s); $ret='';
	if($pa!==false){$pa+=strlen($s); $pb=strpos($d,$e,$pa);
		if($pb!==false)$ret=substr($d,$pa,$pb-$pa); else $ret=substr($d,$pa);} return $ret;}
function exclude($d,$s,$e){$pa=strpos($d,$s);
	if($pa!==false){$pb=strpos($d,$e,$pa);
		if($pb!==false)$d=substr($d,0,$pa).substr($d,$pb+=strlen($e));}
	if(strpos($d,$s)!==false && strpos($d,$e)!==false)$d=exclude($d,$s,$e); return $d;}
function portion($d,$a,$b,$na='',$nb=''){
	$pa=$na?strrpos($d,$a):strpos($d,$a); $pb=$nb?strrpos($d,$b):strpos($d,$b);
	return substr($d,$pa+1,($pb-$pa-1));}
function combine($a,$b){$n=count($a); $r=[];
	for($i=0;$i<$n;$i++)$r[$a[$i]]=$b[$i]??''; return $r;}//if(!empty($b[$i]))
function merge($r,$rb){if(is_array($r) && $rb)return array_merge($r,$rb); elseif($rb)return $rb; else return $r;}
function merger(...$r){$rt=[]; foreach($r[0] as $k=>$v)foreach($r as $ka=>$va)$rt[$k][]=$va[$k]; return $rt;}
function pushr($r,$rb){foreach($rb as $k=>$v)$r[]=$v; return $r;}
function pushv($ra,...$rb){return array_merge($ra,$rb);}
function split_one($s,$d,$o=''){$n=$o?strrpos($d,$s):strpos($d,$s);
if($n!==false)return [substr($d,0,$n),substr($d,$n+1)]; else return [$d,''];}
function split_one_mb($s,$d,$o=''){$n=$o?mb_strrpos($d,$s):mb_strpos($d,$s);
if($n!==false)return [mb_substr($d,0,$n),mb_substr($d,$n+1)]; else return [$d,''];}
function implode_b($s,$r){foreach($r as $k=>$v)if($v)$rb[]=$v; if(isset($rb))return implode($s,$rb);}
function implode_r($r,$l,$s){$rb=[]; foreach($r as $k=>$v)$rb[]=implode($s,$v); if($rb)return implode($l,$rb);}
function implode_k($r,$l,$s){$rb=[]; foreach($r as $k=>$v)$rb[]=$k.$s.$v; return implode($l,$rb);}
function implode_q($r){return '"'.implode('","',$r).'"';}
function explode_r($d,$l,$s){$r=explode($l,$d);
	if($r)foreach($r as $k=>$v)$rb[]=explode($s,$v); return $rb;}
function explode_p($d,$l,$s,$o=0){$r=explode($l,$d);
	if($r)foreach($r as $k=>$v)$rb[]=split_one($s,$v,$o); return $rb;}
function explode_k($d,$l,$s){$r=explode($l,$d); $rb=[];
	if($r)foreach($r as $k=>$v){[$ka,$va]=split_one($s,$v); if($ka)$rb[trim($ka)]=trim($va);} return $rb;}
function in_array_k($d,$r){foreach($r as $k=>$v)if($v==$d)return $k;}//array_search($d,$r);
function in_array_n($d,$r,$n){foreach($r as $k=>$v)if($v[$n]==$d)return $k;}//array_search($d,array_column($r,$n))
function in_array_like($d,$r){foreach($r as $k=>$v)if(strpos($v,$d)!==false)return $k;}
function in_array_in($d,$r){foreach($r as $k=>$v)if(strpos($d,$v)!==false)return $k;}
function array_keys_r($r,$n,$o=''){$rt=[]; foreach($r as $k=>$v)if(isset($v[$n]))$rt[$k]=$v[$n];
if(isset($rt))return $o?array_flip($rt):$rt;}
function array_kv($r,$a,$b){foreach($r as $k=>$v)$rb[$v[$a]]=$v[$b]; return $rb;}
function array2r($r){foreach($r as $k=>$v)$rt[]=[$k,$v]; return $rt;}
function maxk($r){$mx=0; $mk=0; foreach($r as $k=>$v)if($v>$mx){$mx=$v; $mk=$k;} return $mk;}
function maxr($r){$mx=0; foreach($r as $k=>$v){$m=max($v); if($m>$mx)$mx=$m;} return $mx;}
function is_file_b($f){$f=substr($f,0,1)=='/'?substr($f,1):$f; return is_file($f)?$f:'';}
function exc($d){if(auth(6))return shell_exec($d);}
function exe($d){return system(escapeshellcmd($d));}//preg_replace('/[^a-zA-Z0-9]/','',$d)
function excdir(){$dr=__DIR__; $r=explode('/',$dr); return '/'.$r[1].'/'.$r[2];}
function excget($u,$f){$e='wget -P '.excdir().'/'.$u.' '.$f; exc($e);}
function excunt($u,$f){$e='tar -zxvf  '.excdir().'/'.$u.' '.$f; exc($e);}
function array_substract($r){$n=count($r); for($i=0;$i<$n;$i++)$d=isset($r[$i-1])?$r[$i-1]-$r[$i]:$r[$i]; return $d;}
function padr($n){$r=[]; for($i=0;$i<$n;$i++)$r[]=$i; return $r;}

#mecanics
function strid($p,$n=6){return substr(md5($p),2,$n);}
function randid($p=''){return $p.base_convert(substr(microtime(),2,8),10,36);}
function random($p='',$n=10){return $p.strid(microtime(),$n);}
function http($d){return substr($d,0,4)!='http'?'http://'.$d:$d;}
function nohttp($d){return str_replace(['https','http','://','www.'],'',$d);}
function domain($d){$d=nohttp($d); return strto($d,'/');}
function nodomain($d){$d=nohttp($d); return strfrom($d,'/');}
function reload($u=''){echo tag('script','','window.location='.($u?$u:'document.URL'));}
function is_img($d){$n=strrpos($d,'.'); $xt=substr($d,$n);
if($xt && $xt!='.' && strpos('.jpg.png.gif.jpeg.webp',$xt)!==false)return true;}
function ex_img($d){if(substr($d,0,1)=='/')$d=substr($d,1);
if(is_file($d) and filesize($d)>1000)return true;}
function ext($d){$a=strrpos($d,'.'); if($a!==false)$d=strtolower(substr($d,$a));
$b=strrpos($d,'/'); if($b!==false)$d=substr($d,0,$b); if(strlen($d<6))return $d;}
function xt($d){return substr(ext($d),1);}
function b36($n,$o=''){return base_convert($n,$o?36:10,$o?10:36);}
function row($r){$ret=''; foreach($r as $v)$ret.=div($v,'cell'); return div($ret,'row');}
function etc($d,$n=200){$d=deln($d,' '); $d=strip_tags($d); 
	if(strlen($d)>$n){$e=strpos($d,' ',$n); $d=substr($d,0,$e?$e:$n).'...';} return $d;}
function shortnum($n){if($n>1000000)return ceil($n/1000000).'M';
if($n>1000)return ceil($n/1000).'K'; else return $n;}
function act($d,$v){return $d==$v?' active':'';}//===
function isnum($d){$r=str_split($d); foreach($r as $v)if(!is_numeric($v))return false; return true;}
function radd($r,$k,$v=1){return isset($r[$k])?$r[$k]+$v:$v;}
function vadd($r,$k,$v=''){return isset($r[$k])?$r[$k].$v:$v;}
function addslashes_b($d){return str_replace('"','&quot;',$d);}
function cat($r,$n,$o=0){$rb=[]; foreach($r as $k=>$v)$rb[$v[$n]??$v]=$k; return $o?array_flip($rb):$rb;}
function catr($r,$n){$rb=[]; foreach($r as $k=>$v)$rb[$v[$n]??$v][]=$k; return $rb;}
function tri($r,$n,$d){$rb=[]; foreach($r as $k=>$v)if($v[$n]==$d)$rb[$k]=$v; return $rb;}
function displace($r,$id,$to){if($id==$to)return $r; $rk=$r[$id]; unset($r[$id]);
foreach($r as $k=>$v){if($k==$to)$rb[$id]=$rk; $rb[$k]=$v;} return $rb;}
function mktime2($y,$m,$d,$h,$i,$s){return mktime($h,$i,$s,$m,$d,$y);}
function padleft($d,$n){return sprintf('%\'.0'.$n.'d',$d);}
function isdate($y,$m,$d){return sprintf("%04d-%02d-%02d",$y,$m,$d);}
function day($d='',$n=''){return date($d?$d:'ymdhis',$n?$n:time());}
function nbday($d,$n=''){return strtotime($d)+86400*$n;}
function scinb($n){return sprintf("%.3e",$n);}

#controls
function auth($n){if(ses('uid') && ses('auth')>=$n)return true;}
function val($r,$d,$b=''){if(!isset($r[$d]))return $b; return $r[$d]=='memtmp'?memtmp($d):$r[$d];}
function valb($r,$d,$b=''){if(empty($r[$d]))return $b; return $r[$d]=='memtmp'?memtmp($d):$r[$d];}
function valr($r,$k,$kb){$ra=isset($r[$k])?$r[$k]:''; return $ra?(isset($ra[$kb])?$ra[$kb]:''):'';}
function vals($p,$r,$o=''){foreach($r as $k=>$v)$rt[]=val($p,$v,$o); return $rt;}
function valsb($p,$r,$o=''){foreach($r as $k=>$v)$rt[]=valb($p,$v,$o); return $rt;}
function valk($p,$r,$b=''){foreach($r as $k=>$v)$rt[$v]=val($p,$v,$b); return $rt;}
function valkb($p,$r,$b=''){foreach($r as $k=>$v)$rt[$v]=valb($p,$v,$b); return $rt;}
function expl($s,$d,$n=2){$r=explode($s,$d);
for($i=0;$i<$n;$i++)$rb[]=isset($r[$i])?$r[$i]:''; return $rb;}
function repl($s,$b,$d){return str_replace($s,$b,$d);}
function arr($r,$n=''){$rb=[]; $n=$n?$n:count($r); for($i=0;$i<$n;$i++)$rb[]=$r[$i]??''; return $rb;}
function prm($p){$rt=[]; foreach($p as $k=>$v)if($k!='pagewidth' && $k!='appName' && $k!='appMethod')
	$rt[]=$k.'='.jurl($v); return implode(',',$rt);}
function prmb($p,$r){foreach($r as $k=>$v)$rt[]=$v.'='.$p[$v]; return implode(',',$rt);}//_jrb
function prmp($p,$r){$rb=[]; foreach($r as $k=>$v)$rb[$v]=$p['p'.($k+1)]??''; return $rb;}//_jrb
function prmr($d){$r=explode(',',$d); $rb=[];//explode_k
	foreach($r as $k=>$v){[$ka,$va]=split_one('=',$v); if($ka)$rb[$ka]=$va;} return $rb;}
function mkprm($p){foreach($p as $k=>$v)$rt[]=$k.'='.$v; if($rt)return implode('&',$rt);}
function trims($r){foreach($r as $k=>$v)$r[$k]=trim($v); return $r;}

//function get($d){return urldecode($_GET[$d]);}
function get($k){return filter_input(INPUT_GET,$k);}
function post($d,$v=false){if($v!==false)$_POST[$d]=$v; return $_POST[$d]??'';}
//function post($k){return filter_input_(INPUT_POST,$k);}
function cookie($d,$v=''){if($v)setcookie($d,$v,time()+(86400*90)); else $v=$_COOKIE[$d]??''; return $v;}
//function cookie($k){return filter_input_(INPUT_COOKIE,$k);}
//function server($k){return filter_input_(INPUT_SERVER,$k);}

function rmcookie($d){setcookie($d,0,time()-3600); return 'no';}
function srv($d,$v='',$n=''){if($v)$_SERVER[$d]=$v; return $_SERVER[$d]??$n;}
function ses($d,$v='',$n=''){if($v)$_SESSION[$d]=$v; return $_SESSION[$d]??$n;}
function sez($d,$v=''){return $_SESSION[$d]=$v;}
function sesif($d,$v){return $_SESSION[$d]??ses($d,$v,0);}
function sesifn($d,$v=''){return !empty($_SESSION[$d])?$_SESSION[$d]:ses($d,$v);}
function sesadd($d,$v){return $_SESSION[$d]=ses($d,0,0)+$v;}
function sesadr($d,$v,$o=''){if(!isset($_SESSION[$d]) or $o)$_SESSION[$d]=[];
	$_SESSION[$d][]=$v; return $_SESSION[$d];}
function sesr($d,$k,$v=''){
	if(!isset($_SESSION[$d]) || !array_key_exists($d,$_SESSION))$_SESSION[$d]=[];
	if(!isset($_SESSION[$d][$k]) || !array_key_exists($k,$_SESSION[$d]))$_SESSION[$d][$k]=$v;
	elseif($v)$_SESSION[$d][$k]=$v;
	return $_SESSION[$d][$k];}
function sesrif($d,$k,$v=''){$ret=sesr($d,$k); if(!$ret)$ret=sesr($d,$k,$v); return $ret;}
function sesrz($d,$k,$v=''){if($v)return $_SESSION[$d][$k]=$v; else unset($_SESSION[$d][$k]);}
function sesf($d,$v='',$z=''){$ret=ses($d); if((!$ret or $z) && function_exists($d))$ret=ses($d,$d($v)); return $ret;}
function sesm($d,$m,$p='',$z=''){$v=$d.$m.$p; if(!ses($v) or $z)sez($v,$d::$m($p)); return ses($v);}
function sestg($d){return ses($d)?sez($d,0):ses($d,1);}
function offon($d){return $d?0:1;}
function uns($r,$k){if(isset($r[$k]))unset($r[$k]); return $r;}
function unr($r,$rb){foreach($rb as $v)$r=uns($r,$v); return $r;}
function swap(&$a,&$b):void{[$a,$b]=[$b,$a];}

#embed_detect
function ecart($v,$a,$b){$min=$a+1; $max=$b-$a-1; return substr($v,$min,$max);}

function recursearch($v,$ab,$ba,$aa_balise){//réactualise le nombre de balises
$balise=ecart($v,$ab,$ba);
$nb_aa=substr_count($balise,'<'.$aa_balise);
$nb_bb=substr_count($balise,'</'.$aa_balise);
$nb=$nb_aa-$nb_bb;
if($nb>0){for($i=0;$i<$nb;$i++){$ba=strpos($v,'</'.$aa_balise,$ba+1);}
	$ba=recursearch($v,$ab,$ba,$aa_balise);}
return $ba;}

function embed_detect($v,$aa_inner){
$aa_end=strpos($aa_inner,' '); $ret=''; $ba=''; 
if($aa_end!==false)$aa_balise=substr($aa_inner,1,$aa_end-1);
else $aa_balise=str_replace(['<','>'],'',$aa_inner);
$aa=strpos($v,$aa_inner); 
if($aa===false){$vb=str_replace("\n",' ',$v); $aa=strpos($vb,$aa_inner);}
$ab=strpos($v,'>',$aa); 
if(strpos($v,'</'.$aa_balise.'>'))$ba=strpos($v,'</'.$aa_balise.'>',$ab); 
if($ba)$ret=ecart($v,$ab,$ba);
$aab=strpos($v,'<'.$aa_balise,$ab);
if($aab!==false && $ba){
	$ba=recursearch($v,$ab,$ba,$aa_balise);//!
	$ret=ecart($v,$ab,$ba);}
return $ret;}

#parse
function substrpos($v,$a,$b){return substr($v,$a+1,$b-$a-1);}
function lastagpos($v,$ab,$ba){$d=substrpos($v,$ab,$ba);
$nb_aa=substr_count($d,'{'); $nb_bb=substr_count($d,'}'); $nb=$nb_aa-$nb_bb;
if($nb>0){for($i=0;$i<$nb;$i++)$ba=strpos($v,'}',$ba+1); $ba=lastagpos($v,$ab,$ba);}
return $ba;}
function hooks($d,$o=''){$ra=['[',']','{','}']; $rb=['(hka)','(hkb)','(aca)','(acb)']; 
return $o?str_replace($rb,$ra,$d):str_replace($ra,$rb,$d);}

function accolades($d){
$pa=strpos($d,'{'); $d=substr($d,$pa+1);
$pb=strpos($d,'}'); $db=substr($d,0,$pb+1);
$na=substr_count($db,'{'); $nb=substr_count($db,'}');
if($na>$nb)$pb=lastagpos($d,$pa,$pb);
return substr($d,0,$pb);}

function innerfunc($d,$func){
$na=strpos($d,'function '.$func); $d=substr($d,$na);
$na=strpos($d,'('); $nb=strpos($d,')');
//$vars=substr($d,$na+1,$nb-$na-1);
$d=substr($d,$nb+1);
return accolades($d);}

#strings
function delp($d){return str_replace(['<p>','</p>'],"\n",$d);}
function delbr($d,$o=''){return str_replace(['<br />','<br/>','<br>','<br clear="left"/>'],$o,$d);}
function deln($d,$o=''){return str_replace("\n",$o,$d);}
function delt($d,$o=''){return str_replace("\t",$o,$d);}
function delsp($d){return str_replace("&nbsp;",' ',$d);}
function cleansp($d){return preg_replace('/( ){2,}/',' ',$d);}

function clean_lines($d){
$d=delbr($d,"\n");
$d=preg_replace('/(\n){2,}/',"\n\n",$d);
$r=explode("\n",$d);
foreach($r as $v)$rb[]=trim($v);
return implode("\n",$rb);}

function clean_mail($d){
$d=delbr($d,"\n");
$d=delsp($d);
$d=str_replace("M.\n",'M. ',$d);
$d=str_replace(".\n",'.µµ',$d);
$d=str_replace("\n",'µ',$d);
$d=str_replace('µµ',"\n\n",$d);
$d=str_replace('µ',' ',$d);
$d=clean_lines($d);
$d=preg_replace('/( ){2,}/',' ',$d);
return $d;}

function clean_n($d){
$d=str_replace("\r\n","\n",$d);
$d=str_replace("\r","\n",$d);
$d=str_replace('<br>'."\n","\n",$d);
//$d=str_replace('<br>',"\n",$d);
//$d=str_replace('<br />',"\n",$d);
$d=preg_replace('/( ){2,}/',' ',$d);
$d=preg_replace('/(\n){2,}/',"\n\n",$d);
return trim($d);}

function clean_br($d){
$d=delbr($d,' ');
$d=clean_lines($d);
$d=deln($d,' ');
$d=delsp($d,' ');
$d=cleansp($d);
return trim($d);}

function ptag($d){
$r=explode("\n\n",$d); $ret=''; $ex='<h1<h2<h3<h4<br<hr<bl<pr<di<sp<if<fi<ce<di';
foreach($r as $k=>$v){$v=trim($v); if($v){$cn=substr($v,0,3);
	if(strpos($ex,$cn)===false)$ret.='<p>'.$v.'</p>'; else $ret.=$v;}}
$ret=cleansp($ret); $ret=nl2br($ret); return $ret;}

function cleanconn($d){
$d=delt($d);
//$d=clean_mail($d);
if(strpos($d,'<br>')!==false && strpos($d,"\n")!==false)$d=deln($d);
if(strpos($d,'<br>')!==false && strpos($d,"\n")===false)$d=delbr($d,"\n");
$d=clean_lines($d);
//$d=conv::cleanhtml($d);
$d=conv::cleanconn($d);
//$d=delbr($d,"\n");
$d=clean_n($d);
$d=cleansp($d);
return $d;}

function nbsp($d){$n=sp();
$ra=['« ',' »',' !',' ?',' :',' ;'];
$rb=['«'.$n,$n.'»',$n.'!',$n.'?',$n.':',$n.';'];
return str_replace($ra,$rb,$d);}

function stripaccents($d){
	$a='ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ0ÙÚÛÜÝàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ';
	$b='AAAAAACEEEEIIIIDNOOOOO0UUUUYaaaaaaaceeeeiiiidnoooooouuuuyby';
	return strtr($d,$a,$b);}
function accents($d,$o=0){
	$a='àáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ';
	$b='ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞß';
	return $o?strtr($d,$a,$b):strtr($d,$b,$a);}
function accents_b($d){
	$a='àáâãäåæçèéêëìíîïòóôõöøùúûüý';
	$b='AAAAAAÆCEEEEIIIIOOOOOØUUUUY';
	return strtr($d,$a,$b);}
function strtoupper_b($d){return strtoupper(accents_b($d,0));}
function strtolower_b($d){return strtolower(accents($d,1));}
function ucfirst_b($d){return strtoupper_b(substr($d,0,1)).substr($d,1);}
function normalize($d,$o=''){$d=stripaccents(utf8_decode_b($d));
	if($o)$d=str_replace(' ','_',$d); else $d=str_replace('_','',$d);
	return str_replace(["'",'"','?','/','§',',',';',':','!','%','&','$','#','+','=','!',"\n","\r","\0","[\]",'~','(',')','[',']','{','}','«','»',"&nbsp;",'-','.'],'',$d);}

#conn
function connprm($d,$o=0){$s='Â§';
$s=strpos($d,'Â§')?'Â§':'§';//used when § come from code
//$d=str_replace(['*','Â§'],$s,$d);//patch//,'$'
return split_one_mb($s,$d,$o);}

function readconn($d){//p§o:c
[$da,$c]=split_one(':',$d,1);
[$p,$o]=connprm($da);
return [$p,$o,$c,$da];}

function readgen($d){//p*o:c
[$da,$c]=split_one(':',$d,1);
[$p,$o]=split_one('*',$da,1);
return [$p,$o,$c,$da];}

function atbr($d){$ret=''; $r=explode(',',$d);//make k="v" from k=v,
if($r)foreach($r as $v)if(strpos($v,'=')){[$ka,$va]=explode('=',$v); $ret.=atb($ka,$va);}
return $ret;}
function tagb($tag,$p,$t=''){//for vue
if(trim($t))return '<'.$tag.atbr($p).'>'.$t.'</'.$tag.'>';}

#db
function db_read($f,$k='',$kb='',$h=''){$r=db::read($f);
if($k && isset($r[$k])){$ret=$r[$k]; if($kb && isset($ret[$kb]))$ret=$ret[$kb];} else $ret=$r;
if($h && isset($ret['_']))unset($ret['_']); return $ret;}
function db_read_row($f,$k){$r=db::read($f);
if(isset($r[$k])){$rb=$r[$k]; if(isset($r['_']))$ra=$r['_'];
if(isset($ra))return array_combine($ra,$rb); else return $rb;}}
function db_write($f,$r,$k='',$kb=''){
if($k){$r=db::read($f); if(!isset($r[$k]))return; if($kb)$r[$k][$kb]=$r; else $r[$k]=$r;}
db::save($f,$r);}

#dir
function scandir_b($d){$r=scandir($d);
if($r[0]=='.')unset($r[0]); if($r[1]=='..')unset($r[1]); return $r;}
function scandir_r($d){static $ret; $dr=opendir($d); 
while($f=readdir($dr))if($f!='..' && $f!='.' && substr($f,0,1)!='_'){$df=$d.'/'.$f;
	if(is_dir($df))scandir_r($df); else $ret[]=$df;}
return $ret;}

function read_dir($dir){if(!is_dir($dir))return;
$r=scandir($dir); $ret=[];
foreach($r as $k=>$v){if(!in_array($v,['.','..','_notes'])){
	if(is_dir($dir.'/'.$v))$ret[$v]=read_dir($dir.'/'.$v);
	else $ret[]=$v;}}
return $ret;}

function scan_dir($dir){$ret=[];
if(is_dir($dir))$r=scandir($dir); if(!isset($r))return;
foreach($r as $k=>$v)if($v!='.' && $v!='..' && $v!='_notes'){
	if(is_dir($dir.'/'.$v))$ret[$v]=$v; else $ret[$k]=$v;}
return $ret;}

function explore($dr,$p='',$o=''){//unused
$r=scandir($dr,0); static $i; $ret=[];
foreach($r as $k=>$f){$drb=$dr.'/'.$f; $i++;
if(is_dir($drb) && $f!='..' && $f!='.' && $f!='_notes'){
	if($p=='dirs')$ret[$f]=$f; if(!$o)$ret+=explore($drb,$p,$o);}
if($p!='dirs')if(is_file($drb))$ret[$i]=$drb;}
return $ret;}

function mkdir_r($u){$ret='';
$nu=explode('/',$u); if(count($nu)>12)return;
if(strpos($u,'Warning')!==false)return;
foreach($nu as $k=>$v){$ret.=$v.'/'; if(strpos($v,'.'))$v='';
if($v && !is_dir($ret) && !mkdir($ret))echo '('.$v.':no)';}}

function rmdir_r($dr){
if(!auth(6))return; $dir=opendir($dr); $ret=$dr.br();
while($f=readdir($dir)){$drb=$dr.'/'.$f;
if(is_dir($drb) && $f!='..' && $f!='.'){rmdir_r($drb); if(is_dir($drb))rmdir($drb);}
elseif(is_file($drb)){unlink($drb); $ret.=$drb.br();}} if(is_dir($dr))rmdir($dr); return $ret;}

//walk
/*apply a function to the files of a dir
$res=walk('dir','walkMethod','db','',1);
$res=walk('','walkfunc','db',read_dir('db'),1);*/
function walkMethod($dir,$file){return $dir.'/'.$file;}
function walk($app,$method,$dir,$r='',$recursive=''){
if(!$r)$r=read_dir($dir);
$ret=[]; if(substr($dir,-1)=='/')$dir=substr($dir,0,-1);
if($r)foreach($r as $k=>$v){
	if(is_array($v)){
		$rb=walk($app,$method,$dir.'/'.$k,$v,$recursive);
		if($recursive)$ret[$k]=$rb; else $ret=array_merge($ret,$rb);}
	elseif(is_file($dir.'/'.$v)){
		if($app)$ret[$k]=$app::$method($dir,$v);
		elseif($method)$ret[$k]=$method($dir,$v);}}
return $ret;}

#files
function gz($f,$fb){$w=write_file($fb,implode('',gzfile($f))); if($w===false)return 'error';}
function gunz($f){return readgzfile($f);}
function writegz($f,$d){$gz=gzopen($f,'w9'); gzwrite($gz,$d); return gzclose($gz);}
function readgz($f,$o=0){$d=gzopen($f,'rb',$o); $ret='';
if($d)while(!gzeof($d))$ret.=gzread($d,1024); gzclose($d); return $ret;} 
function fdate($f,$v='ymd.His'){if(is_file($f))return date($v,filemtime($f));}
function fsize($f,$o=''){if(is_file($f))return round(filesize($f)/1024,2).($o?'':' Ko');}
function fdim($f,$w=0,$h=0){$r=[$w,$h,0,0,0,0,0]; if(is_file($f)){$rb=getimagesize($f); if($rb)$r=$rb;} return $r;}
//function fex($f){$fp=finfo_open(FILEINFO_MIME_TYPE); $d=finfo_file($fp,$f); finfo_close($fp); return $d;}
function fex1($f){return @fopen($f,'r');}
function fex2($f){$fp=curl_init($f); curl_setopt($fp,CURLOPT_NOBODY,true); curl_exec($fp);
$d=curl_getinfo($fp,CURLINFO_HTTP_CODE); curl_close($fp); return $d==200?1:0;}

function write_file($f,$d){
$h=fopen($f,'w+'); if(!$h)return 'error'; $w=fwrite($h,$d); fclose($h);
if(!ses('enc'))opcache_invalidate($f);//
if($w===false)return 'error';}

function read_file($f){
if(is_file($f))$fp=fopen($f,'r'); $ret='';
if(isset($fp)){while(!feof($fp))$ret.=fread($fp,8192); fclose($fp);}
return $ret;}

function bigcsv($f,$n=0,$l=10,$s='\t'){
$r=file($f); $r=array_slice($r,$l*$n,$l); $rc=[];
foreach($r as $k=>$v){$rb=explode($s,$v); foreach($rb as $kb=>$vb)$rc[$k][$kb]=trim($vb);}
return $rc;}

function info_file($f){
$fp=finfo_open(FILEINFO_MIME_TYPE);
foreach(glob('*') as $v)$rt[]=finfo_file($fp,$v);
finfo_close($fp); return $rt;}

function read_context($f){return read_file($f);
ini_set('user_agent','Mozilla/5.0');
$r=['http'=>['method'=>'GET','header'=>'User-agent: Mozilla/5.0','ignore_errors'=>1,'request_fulluri'=>true,'max_redirects'=>0]];
$context=stream_context_create($r);
$h=get_headers($f,false);//$http_response_header
if(strpos($h[0],'404'))return '404';
return file_get_contents($f,false,$context);}

function curl($f,$o='',$post=''){$ch=curl_init($f); //curl_setopt($ch,CURLOPT_URL,$f);
if($o=='json')$a='application/json'; elseif($o=='audio')$a='audio/wav';
elseif($o)$a=$o; else $a='application/x-www-form-urlencoded';
$r=['HTTP_ACCEPT: Something','HTTP_ACCEPT_LANGUAGE: fr, en, es','HTTP_CONNECTION: Something','Content-type: '.$a,'User-agent: Mozilla/5.0'];
curl_setopt($ch,CURLOPT_HTTPHEADER,$r);
if($post){curl_setopt($ch,CURLOPT_POST,TRUE); curl_setopt($ch,CURLOPT_POSTFIELDS,$post);}
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch,CURLOPT_REFERER,'');
$ret=curl_exec($ch); curl_close($ch); return $ret;}

function get_file($f){
$d=@file_get_contents($f);
if(!$d)$ret=curl($f);
if(!$d)$ret=read_context($f);
//if(!$d)$ret=read_file($f);
return $d;}

function get_file2($f){
if($d=sesr('gf',$f))return $d;
$d=get_file($f); sesr('gf',$f,$d);
return $d;}

function dom($d,$f=''){
if($f)$d=curl($f);
$dom=new DomDocument;
$dom->validateOnParse=true;
$dom->preserveWhiteSpace=false;
libxml_use_internal_errors(true);
if($d)$dom->loadHtml($d);
return $dom;}

function get_dom($f,$o=''){
if($o){$d=get_file($f); if($d)return dom($d);}
else{$dom=dom(''); $dom->loadHTMLFile($f); return $dom;}}

function domattr($v,$p){if($v->hasAttribute($p))return $v->getAttribute($p);}

function dom_extract($dom,$va){$ret='';//all-in-one
[$c,$t,$tg,$g]=expl($va,':',4); if(!$t)$t='class'; if(!$tg)$tg='div';//id,href,...
if(!$g){if($tg=='img')$g='src'; elseif($tg=='meta')$g='content';}//props
$r=$dom->getElementsByTagName($tg); $c=str_replace('(ddot)',':',$c);
foreach($r as $k=>$v){$attr=$v->getAttribute($t);
	if(!$ret && ($c==$attr or ($c && strpos($attr,$c)!==false) or !$c))
		$ret=$g?domattr($v,$g):$v->nodeValue;}
return ($ret);}//utf8_decode_b

function svgdim($f,$w=0,$h=0){$r=[$w,$h];
$d=read_file($f); if(!$d)return $r;
$w=segment($d,'width="','"');
$h=segment($d,'height="','"');
/*$dom=dom($d); if(!$dom)return $r;
$r=$dom->getElementsByTagName('svg'); //pr($r);
$w=$r[0]->getAttribute('width');
$h=$r[0]->getAttribute('height');*/
$w=str_replace('px','',$w);
$h=str_replace('px','',$h);
return [$w,$h];}

#csv
function csv($r){$d='';
foreach($r as $k=>$v)$d.=$k.'|'.implode('|',$v).n();
return $d;}

function writecsv($f,$r,$t=''){
file_put_contents($f,'');
if(($h=fopen($f,'r+'))!==false){
foreach($r as $k=>$v)fputcsv($h,$v); fclose($h);}
return lk('/'.$f,pictxt('file-data',$t?$t:$f));}

function readcsv($f,$s="\t"){$rb=[];
if(($h=fopen($f,'r'))!==false){$k=0;
while(($r=fgetcsv($h,'',$s))!==false){$nb=count($r);
for($i=0;$i<$nb;$i++)$rb[$k][]=$r[$i]; $k++;} fclose($h);}
return $rb;}

#amt
function memtmp($d){$r=$_SESSION['mem'][$d]??[]; $_SESSION['mem'][$d]=[];
if($r){ksort($r); $ret=implode('',$r); $_SESSION['mem']=[]; return jurl($ret,1);}}

//ajaxencode
function jurl($d,$o=''){//$d=unicode($d);
	$a=["\n","\t",'\'','|',"'",'"','*','#','+','=','&','?','.',':','§',',','%u',' '];//,'<','>','/'
	$b=['(-n)','(-t)','(-asl)','(-bar)','(-q)','(-dq)','(-star)','(-dz)','(-add)','(-eq)','(-and)','(-qm)','(-dot)','(-ddot)','(-par)','(-coma)','(-pu)','(-sp)'];//,','(-b1)','(-b2)'(-sl)'
	return str_replace($o?$b:$a,$o?$a:$b,$d);}
function _jr($r){$rt=[];//tostring
	if($r)foreach($r as $k=>$v)if($v)
		if(strpos($v,'=')){[$k,$v]=explode('=',$v); $rt[]=$k.'='.jurl($v);}
	if($rt)return implode(',',$rt);}
function _jrb($d,$s=':'){//mkarray
$r=explode(',',$d); $rt=[]; //$ra=['p','o','ob','oc','od'];
	if($r)foreach($r as $k=>$v){$rb=explode($s,$v);
		if(isset($rb[1])){
			if($rb[1]=='undefined' && $rb[0])$rt['p'.($k+1)]=jurl($rb[0],1);
			else $rt[$rb[0]]=jurl($rb[1],1);}
		elseif($rb[0])$rt['p'.($k+1)]=jurl($rb[0],1);}
	return $rt;}
function unicode($d){
	if(strpos($d,'%u')===false)return $d; $n=strlen($d); $ret='';
	for($i=0;$i<$n;$i++){$c=substr($d,$i,1);
	if($c=='%'){$i++; $cb=substr($d,$i,1);
		if($cb=='u'){$i++; $cc=substr($d,$i,4); $i+=3; $ret.='&#'.hexdec($cc).';';}
		else $ret.=$c.$cb;}
	else $ret.=substr($d,$i,1);}
return $ret;}
function unicode2($d){return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/',function($match){return mb_convert_encoding(pack('H*',$match[1]),'UTF-8','UCS-2BE');},$d);}
function ascii($v){if(is_numeric($v))$v='#'.$v; return '&'.$v.';';}
function protect($d,$o=''){$a='|'; $b='(bar)';
	return str_replace($o?$b:$a,$o?$a:$b,$d);}

function jurl2($v,$p=''){#dont edit!//ajx() on philum
$r=['*','_','(star)']; $a=$p?1:0; $b=$p?0:1;
if(!$p){$a=0; $b=1; $c=2; $d=0;} else{$a=1; $b=0; $c=0; $d=2;}//§=>&#167;
$ra=[$r[$a],$r[$b],'_',"\n","\r",'\'',"'",'§','#','µ','"','+','=','€','&','.',':','/','&#8617;'];
$rb=[$r[$c],$r[$d],'(und)','(nl)','(nl)','(aslash)','(quote)','(by)','(diez)','(mic)','(dquote)','(add)','(equal)','(euro)','(and)','(dot)','(ddot)','(slash)','(back)'];
return $p?str_replace($rb,$ra,$v):str_replace($ra,$rb,$v);}

//json
function json_er(){
switch(json_last_error()){
case JSON_ERROR_NONE:$ret='no error';break;
case JSON_ERROR_DEPTH:$ret='maximum depth reached';break;
case JSON_ERROR_STATE_MISMATCH:$ret='bad modes (underflow)';break;
case JSON_ERROR_CTRL_CHAR:$ret='error during character check';break;
case JSON_ERROR_SYNTAX:$ret='syntax error; malformed Json';break;
case JSON_ERROR_UTF8:$ret='malformed UTF-8 characters';break;
default:$ret='unknown error';break;}
return $ret;}

function json_r($r){
foreach($r as $k=>$v){
	if(is_array($v))$ret[]=json_r($v);
	elseif(is_numeric($k))$ret[]='"'.rawurlencode($v).'"';
	else $ret[]='"'.$k.'":"'.rawurlencode($v).'"';}
if(is_numeric($k))return '['.implode(',',$ret).']';
else return '{'.implode(',',$ret).'}';}

function utf_r($r,$o=''){
foreach($r as $k=>$v){
	if(is_array($v))$ret[$k]=utf_r($v,$o);
	else $ret[$k]=$o?utf8_decode($v):($v);}////_b
return $ret;}

function json_dec($d){return json_decode($d,true);}

function json_enc($r){
$ret=json_encode($r,JSON_HEX_TAG);
//,JSON_FORCE_OBJECT|JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP| JSON_UNESCAPED_UNICODE
if(!$ret)$ret=json_er();
return $ret;}

#clr
function diez($d,$o=''){return $o?substr($d,1):'#'.$d;}
function rgb($d){return [hexdec(substr($d,0,2)),hexdec(substr($d,2,2)),hexdec(substr($d,4,2))];}
function rgb2($r,$o=0){return [$r[0]+$o,$r[1]+$o,$r[2]+$o];}
function rgba($d,$a=1){$r=rgb($d); return 'rgba('.$r[0].','.$r[1].','.$r[2].','.$a.')';}
function rgb2clr($r){$ret=''; for($i=0;$i<3;$i++){$d=dechex($r[$i]>0?$r[$i]:0);
	if(strlen($d)==1)$d='0'.$d; $ret.=$d=='0'||!$d?'00':$d;}
	return $ret;}
function hexrgb_r($d){for($i=0;$i<3;$i++)$r[]=hexdec(substr($d,$i*2,2)); return $r;}
function hexrgb($d,$o=''){$r=hexrgb_r($d); return 'rgba('.$r[0].','.$r[1].','.$r[2].','.$o.')';}
function rgb2hex($r){$ret=''; for($i=0;$i<3;$i++){$d=dechex($r[$i]);
	if(strlen($d)==1)$d='0'.$d; $ret.=$d=='0'||!$d?'00':$d;}
return $ret;}
function hsl2rgb($h,$s,$l){
$h/=360; $s/=100; $l/=100; $r=$l;$g=$l;$b=$l;
$v=($l<=0.5)?($l*(1.0+$s)):($l+$s-$l*$s);
if($v>0){$m; $sv; $sextant; $fract; $vsf; $mid1; $mid2;
	$m=$l+$l-$v; $sv=($v-$m)/$v; $h*=6.0;
	$sextant=floor($h); $fract=$h-$sextant; $vsf=$v*$sv*$fract;
	$mid1=$m+$vsf; $mid2=$v-$vsf;
	switch($sextant){
	case 0:$r=$v; $g=$mid1; $b=$m; break;
	case 1:$r=$mid2; $g=$v; $b=$m; break;
	case 2:$r=$m; $g=$v; $b=$mid1; break;
	case 3:$r=$m; $g=$mid2; $b=$v; break;
	case 4:$r=$mid1; $g=$m; $b=$v; break;
	case 5:$r=$v; $g=$m; $b=$mid2; break;}}
$r=round($r*=255); $g=round($g*255); $b=round($b*255);
return [$r,$g,$b];}
function hsl2hex($h,$s,$l){$r=hsl2rgb($h,$s,$l);
return rgb2hex($r);}
function clrb($p,$o=0){$r=rgb($p); $r=rgb2($r,$o); return rgb2clr($r);}
function clrneg($p,$o){if(!$p)return '000000'; $p=str_pad(substr($p,-1),6,$p); if(!is_numeric($p))return;
	if($o)return hexdec($p)<6388607?'ffffff':'000000';
	for($i=0;$i<3;$i++){$d=dechex(255-hexdec(substr($p,$i*2,2))); $d=str_pad($d,2,'0',STR_PAD_LEFT);}
	return $ret;}

#img
//force LH, cut and center
function scale($w,$h,$wo,$ho,$s){$hx=$wo/$w; $hy=$ho/$h; $yb=0; $xb=0;
if($s==2){$xb=($wo/2)-($w/2); $yb=($ho/2)-($h/2); $wo=$w; $ho=$h;}
elseif($hy<$hx && $s){$xb=0; $yb=($ho-($h*$hx))/2; $ho=$ho/($hy/$hx);}//reduce_h
elseif($hy>$hx && $s){$xb=($wo-($w*$hy))/2; $wo=$wo/($hx/$hy);}//reduce_w
elseif($hy<$hx){$xb=($wo-($w*$hy))/2; $wo=$wo/($hx/$hy);}//adapt_h
elseif($hy && $hx){$xb=0; $ho=$ho/($hy/$hx);}//adapt_w
return [$wo,$ho,$xb,$yb];}

function mkthumb($in,$out,$w,$h,$s){$xa=0; $ya=0;
$w=$w?$w:170; $h=$h?$h:100; [$wo,$ho,$ty]=getimagesize($in); 
[$wo,$ho,$xb,$yb]=scale($w,$h,$wo,$ho,$s);
if(is_file($in))if(filesize($in)/1024 >5000)return;
$img=imagecreatetruecolor($w,$h);
if($ty==2){$im=imagecreatefromjpeg($in);
	imagecopyresampled($img,$im,$xa,$ya,$xb,$yb,$w,$h,$wo,$ho);
	imagejpeg($img,$out,100);}
elseif($ty==1){$im=imagecreatefromgif($in); imgalpha($img);
	imagecopyresampled($img,$im,$xa,$ya,$xb,$yb,$w,$h,$wo,$ho);
	imagegif($img,$out);}
elseif($ty==3){$im=imagecreatefrompng($in); imgalpha($img);
	imagecopyresampled($img,$im,$xa,$ya,$xb,$yb,$w,$h,$wo,$ho);
	imagepng($img,$out);}
return $out;}

function imgalpha($img){//imagefilledrectangle($im,0,0,$w,$h,$wh);
$c=imagecolorallocate($img,255,255,255); imagecolortransparent($img,$c);
imagealphablending($img,false); 
imagesavealpha($img,true);}

#utils
function url($d){return host(1).'/'.$d;}
function debug(){var_dump(debug_backtrace());}
function host($o=''){return ($o?'http://':'').$_SERVER['HTTP_HOST'];}
function serv(){return host()=='telex.ovh'?'http://logic.ovh':'http://telex.ovh';}
function utf8_decode_b($d){return mb_convert_encoding($d,'HTML-ENTITIES','UTF-8');}
function ip(){return gethostbyaddr($_SERVER['REMOTE_ADDR']);}
function eco($d,$o=''){if(is_object($d))return var_dump($d);
$d=is_array($d)?pr($d,1):$d; $ret=textarea('',utf8_decode($d),42,6);
if($o)return $ret; else echo utf8_encode($ret);}
function display($d,$o=''){global ${$d}; $d=is_array($d)?pr($d,1):$d; 
if($o)return $ret; else echo div($d.': '.${$d});}
function chrono($d='chrono',$n=5){static $s;
if(!$s)$s=$_SERVER['REQUEST_TIME_FLOAT']; $s1=microtime(1); $res=$s1-$s; $s=$s1;
return $d.':'.round($res,$n);}
?>