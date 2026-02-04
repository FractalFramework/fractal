<?php
#Fractal GNU/GPL

#autoload
function loadapp($d){$dr=ses('dev'); $r=sesf('scandir_a',$dr,0);
	if($r)foreach($r as $k=>$v){$f=$dr.'/'.$v.'/'.$d.'.php'; if(file_exists($f))require_once $f;}
	$f=$dr.'/'.$d.'.php'; if(file_exists($f))require_once $f;}
spl_autoload_register('loadapp');

#dev
function p($r){print_r($r);}
function pr($r,$o=''){$ret='<pre>'.print_r($r,true).'</pre>'; if($o)return $ret; else echo $ret;}
function vd($r){var_dump($r,1);}
function br(){return '<br />';}
function hr(){return '<hr />';}
function sp(){return '&nbsp;';}
//function sp(){return '&#160;';}
function ns(){return '&thinsp;';}
function n(){return "\n";}

#html
function atb($d,$v){if($v!=='')return ' '.$d.'="'.$v.'"';}
function atc($d){if($d)return atb('class',$d);}
function atd($d){if($d)return atb('id',$d);}
function ats($d){if($d)return atb('style',$d);}
function atn($d){if($d)return atb('name',$d);}
function atv($d){if($d)return atb('value',$d);}
function atz($d){if($d)return atb('size',$d);}
function att($d){if($d)return atb('title',$d);}
function atr($r){foreach($r as $k=>$v)$rt[]=atb($k,$v); return join('',$rt??[]);}
function atj($d,$r){return $d.str_replace('\'this\'','this','(\''.(is_array($r)?implode('\',\'',$r):$r).'\');');}
function ath($d){return 'onFocus="if(this.value==\''.$d.'\')this.value=\'\'"; onBlur="if(this.value==\'\')this.value=\''.$d.'\'";';}
function atp($c,$id='',$s='',$w='',$h=''){
	if($c)$r['class']=$c; if($s)$r['style']=$s; if($id)$r['id']=$id;
	if($w)$r['width']=$w; if($h)$r['height']=$h; return $r;}
function ajx($d){return atj('ajx',$d);}

//tags
function tag($tag,$r,$t='',$o=''){$p=''; //if(is_string($r))trace();
<<<<<<< HEAD
if(!$r)$r=[]; elseif(is_string($r))$r=prmr($r);//
=======
if(!$r)$r=[]; elseif(is_string($r))$r=prmr($r);
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
return '<'.$tag.atr($r).(!$o?'>'.$t.'</'.$tag.'>':'/>');}
function tagb($tag,$r=[]){return '<'.$tag.atr($r).'/>';}
function div($t,$c='',$id='',$s='',$r=[]){
	if($id)$r['id']=$id; if($c)$r['class']=$c; if($s)$r['style']=$s;
	return tag('div',$r,$t);}
function span($t,$c='',$id='',$s='',$r=[]){
	if($id)$r['id']=$id; if($c)$r['class']=$c; if($s)$r['style']=$s;
	return tag('span',$r,$t);}
function ul($t,$c='',$id='',$s=''){return tag('ul',['id'=>$id,'class'=>$c,'style'=>$s],$t);}
function li($t,$c='',$id='',$s=''){return tag('li',['id'=>$id,'class'=>$c,'style'=>$s],$t);}
function h1($t,$c='',$id='',$s=''){return tag('h1',['id'=>$id,'class'=>$c,'style'=>$s],$t);}
function h2($t,$c='',$id='',$s=''){return tag('h2',['id'=>$id,'class'=>$c,'style'=>$s],$t);}
function h3($t,$c='',$id='',$s=''){return tag('h3',['id'=>$id,'class'=>$c,'style'=>$s],$t);}
function h4($t,$c='',$id='',$s=''){return tag('h4',['id'=>$id,'class'=>$c,'style'=>$s],$t);}

function lk($u,$t='',$c='',$o='',$id=''){if(!$t)$t=domain($u);
	$r=['href'=>$u,'class'=>$c,'id'=>$id]; if($o)$r['target']='_blank';
	return tag('a',$r,$t);}
function lku($u,$t,$c=''){return tag('a',['href'=>'/'.$u,'onclick'=>'aju(this)','class'=>$c],$t);}
function btj($t,$j,$c='',$id='',$ti=''){$r=['onclick'=>$j];
	if($c)$r['class']=$c; if($id)$r['id']=$id; if($ti)$r['title']=$ti; return tag('a',$r,$t);}
function close($id,$t,$c=''){return btj($t,atj('closediv',$id),$c);}
function btn($r,$t){return tag('button',$r,$t);}
function small($t){return tag('small','',$t);}
function divh($d,$id){return div($d,'',$id,'display:none;');}
function iframe($f,$w='',$h=''){return tag('iframe',['width'=>$w,'height'=>$h,'frameborder'=>'0','scrolling'=>'no','marginheight'=>'0','marginwidth'=>'0','src'=>$f],'');}

function img($src,$w='',$h='',$c='',$s=''){
	if(is_numeric($w))$w.='px'; if(is_numeric($h))$h.='px'; $r['src']=$src;
	if($w)$r['max-width']=$w; if($h)$r['height']=$h; if($s)$r['style']=$s; if($c)$r['class']=$c;
	return tagb('img',$r);}
function imgalt($f,$o){return tagb('img',['src'=>$f,'alt'=>$o]);}
function img64($d,$m=''){return tagb('img',['src'=>'data:image/'.($m?$m:'jpeg').';base64,'.base64_encode($d)]);}
function picture($f){
$ret=tagb('source',['media'=>'min-width:0px','srcset'=>imgroot($f,'medium')]);
$ret.=tagb('source',['media'=>'min-width:1000px','srcset'=>imgroot($f,'full')]);
$ret.=tagb('img',[]);
return $ret;}
function video($f,$w='',$h=''){if(!$w)$w='100%'; return '<video controls width="'.$w.'" height="'.$h.'"><source src="'.goodir($f).'" type="video/mp4"></video>';}
function audio($f,$id=''){return '<audio controls>
<source id="mp3'.$id.'" src="'.goodir($f).'" type="audio/mpeg"></audio>';}

//forms
function input($id,$v,$s='',$h='',$ty='',$mx='',$j='',$no=''){$ty=$ty==1?'number':$ty;
	$r=['type'=>$ty?$ty:'text','id'=>$id,'name'=>$id,'placeholder'=>$h==1?$v:$h,'value'=>$v,'size'=>$s,'maxlength'=>$mx,'title'=>$h,'readonly'=>$no?1:''];
	if($ty=='number'){$j='numonly(this);'.$j;}
	if($j)$r['onchange']=ajx($j); //if($j)$r['onkeyup']=ajx($j);
	return tagb('input',$r);}
function inpnb($id,$v,$s='',$min='',$max='',$j='',$js=''){
$rp=['type'=>'number','id'=>$id,'value'=>$v,'size'=>$s,'min'=>$min,'max'=>$max,'onchange'=>ajx($j).$js];
return tagb('input',$rp);}
function inpdate($id,$v,$min='',$max='',$o='',$j=''){$ty=$o?'datetime-local':'date';//time//step=1
$pr=['type'=>$ty,'id'=>$id,'name'=>$id,'value'=>$v,'min'=>$min,'max'=>$max,'onchange'=>ajx($j)];
return tagb('input',$pr);}
function goodinput($id,$v){if(strlen($v)<20)return input($id,$v); else return textarea($id,$v,40,4);}
function hidden($id,$v){return tagb('input',['type'=>'hidden','id'=>$id,'value'=>$v]);}
function password($id,$v,$sz='',$h=''){
	$r=['type'=>'password','id'=>$id,'value'=>$v,'size'=>$sz];
	if($h)$r['placeholder']=$h!=1?$h:$v;
	return tagb('input',$r);}
function textarea($id,$v,$cols=64,$rows=8,$h='',$c='',$n='',$j=''){
	$r=['id'=>$id,'cols'=>$cols,'rows'=>$rows]; $op='';
	if($h)$r['placeholder']=$h!=1?$h:$v; if($c)$r['class']=$c;
	if($j){$r['onclick']=ajx($j); $r['onkeyup']=ajx($j);}
	if($n){$j.=' strcount(\''.$id.'\','.$n.');'; $op=' '.span($n-mb_strlen($v),'small','strcnt'.$id.'');}
	return tag('textarea',$r,$v).$op;}
function textareact($id,$v,$cl=64,$rw=8,$j='',$c=''){if($j)$j='ajxt(\''.$j.'\',this);';
	$r=['id'=>$id,'cols'=>$cl,'rows'=>$rw,'onclick'=>$j,'onkeyup'=>$j,'class'=>$c?$c:'console wd'];
	return tag('textarea',$r,$v);}
function divarea($id,$v,$c='article',$o='',$pr=[]){$bt=build::wsgbt($id,$o);
	$pr=['id'=>$id,'class'=>$c,'contenteditable'=>'true']+$pr;
	return $bt.tag('div',$pr,$v);}

function select($id,$r,$ck='',$o='',$lg='',$j='',$js=''){$ret='';
$ra=['id'=>$id,'name'=>$id]; if($j)$ra['onchange']=ajx($j.'\'+this.value+\''); if($js)$ra['onchange']=$js;
if($r)foreach($r as $k=>$v){$rb=[];
	if($o)$k=is_numeric($k)?$v:$k; if($lg)$v=lang($v);
	if($k==$ck)$rb['selected']='selected'; $rb['value']=$k;
	$ret.=tag('option',$rb,$v?$v:$k);}
	return tag('select',$ra,$ret);}
function radio($id,$r,$ck,$o=''){$ret='';
$rk=explode('-',$ck); $rk=array_flip($rk);
foreach($r as $k=>$v){$ka=$k;
	if($o)$k=is_numeric($k)?$v:$k; $kb=$id.str::normalize($k);
	if($o)$k=is_numeric($k)?$v:$k; $kb=$id.str::normalize($k);
	$atb=['type'=>'radio','name'=>$id,'id'=>$kb,'value'=>$k];
	if(isset($rk[$k]))$atb['checked']='checked';
	$ret.=span(tagb('input',$atb).label($kb,lang($v)),'btn');}
	return $ret;}
function checkbox($id,$r,$ck='',$o=''){$ret='';
$rk=explode('-',$ck); $rk=array_flip($rk);
foreach($r as $k=>$v){$ka=$k;
	if($o)$k=is_numeric($k)?$v:$k; $kb=$id.str::normalize($k);
	if($o)$k=is_numeric($k)?$v:$k; $kb=$id.str::normalize($k);
	$atb=['type'=>'checkbox','name'=>$id,'id'=>$kb,'value'=>$k];
	if(isset($rk[$ka]))$atb['checked']='checked';
	$ret.=span(tagb('input',$atb).label($kb,lang($v)),'btn');}
	return $ret;}
function datalist($id,$r,$v,$s=34,$t=''){$ret=''; $opt='';
	$ret=tagb('input',['id'=>$id,'name'=>$id,'list'=>'dt'.$id,'size'=>$s,'value'=>$v,'placeholder'=>$t]);
	foreach($r as $v)$opt.=tagb('option','value='.$v);
	$ret.=tag('datalist',['id'=>'dt'.$id],$opt);
	return $ret;}
function bar($id,$v=1,$step=10,$min=0,$max=100,$in='',$j='',$c='',$s=''){if(!$v)$v=$step;
	$bt=$in?inpnb('lbl'.$id,$v,4,$min,$max,$j,'val(this.value,\''.$id.'\');'):span($v,'btn','lbl'.$id);
	return tagb('input',['type'=>'range','id'=>$id,'name'=>$id,'min'=>$min,'max'=>$max,'step'=>$step,'value'=>$v,'onchange'=>($in?'val':'inn').'(this.value,\'lbl\'+id);'.ajx($j),'class'=>$c,'style'=>'width:'.$s]).$bt;}
function label($for,$v,$id='',$c=''){return tag('label',['for'=>$for,'id'=>$id,'class'=>$c],$v);}
function input_label($id,$v,$t,$s=24){return input($id,$v,$s,$t).label($id,$t);}//div()
function input_row($id,$bt,$t){return div(div(label($id,lang($t)),'cell2').div($bt,'cell'),'row');}
function input_pic($id,$v,$t,$p,$o=''){if($o)$o=label($id,$t); return span(pic($p).input($id,$v,30,$t).$o,'inpic');}
function progress($id,$n){return tag('progress',['id'=>$id,'value'=>$n,'max'=>100],'');}

function select2($id,$r,$ck='',$o='',$lg='',$j=''){$ret='';
$ra=['id'=>$id]; if($j)$ra['onchange']=ajx($j.'\'+this.value+\'');
	function values($k,$v,$ck,$o,$lg){$rb=[];
		if($o)$k=is_numeric($k)?$v:$k; if($lg)$v=lang($v);
		if($k==$ck)$rb['selected']='selected'; $rb['value']=$k;
		return tag('option',$rb,$v?$v:$k);}
	function options($r,$ck,$o,$lg){$ret=''; foreach($r as $k=>$v)$ret.=values($k,$v,$ck,$o,$lg); return $ret;}
	if($r)foreach($r as $k=>$v)
		if(is_array($v))$ret.=tag('optgroup',['label'=>$k],options($v,$ck,$o,$lg));
		else $ret.=values($k,$v,$ck,$o,$lg);
	return tag('select',$ra,$ret);}

//[['type'=>'text','name'=>'tit','value'=>'title'],]
function mkform($r,$j){$frm=randid('frm'); $ret='';
$rj=explode('|',$j); $jb=$rj[0].'|'.$rj[1].'|'.$rj[2].'|'.$frm;
$rp=['id'=>$frm,'name'=>$frm,'onsubmit'=>'return ajbt(this);','data-j'=>$jb];
foreach($r as $k=>$v)$ret.=tagb('input',$v).label($v['id'],$v['label']);
$ret.=bj($jb,langp('ok'),'btsav');
return tag('form',$rp,$ret);}

//formj
function inputcall($j,$id,$v,$s='',$h='',$p='',$r=[]){$r['id']=$id;
	if($h==1)$r['placeholder']=$v; elseif($h){$r['placeholder']=$h; $r['title']=$h;} if($h!=1)$r['value']=$v;
	if($s)$r['size']=$s; $r['data-j']=$j; $r['onkeyup']='checkj(event,this);'.($r['onkeyup']??''); 
	$ret=tagb('input',$r); if($p)return span(pic($p).$ret,'inpic'); else return $ret;}
function areacall($j,$id,$v,){$js='checkj(event,this);'.atj('strcount',$id).atj('autoResizeHeight',$id);
	$r=['id'=>$id,'class'=>'resizearea scroll2','data-j'=>$j,'placeholder'=>lang('message'),'onkeyup'=>$js,'onpaste'=>$js]; 
	return tag('textarea',$r,$v);}
function datalistcall($id,$r,$v,$j,$t='',$s=16){$opt='';
	$pr=['id'=>$id,'list'=>'dt'.$id,'size'=>$s,'value'=>$v,'placeholder'=>$t,'size'=>$s,'onkeyup'=>'checkj(event,this)','data-j'=>$j]; $ret=tagb('input',$pr);
	foreach($r??[] as $k=>$v)$opt.=tagb('option',['value'=>$v]);
	return $ret.tag('datalist',['id'=>'dt'.$id],$opt);}
function datalistj($id,$v,$j,$ja,$t='',$s=16){$ret=''; $opt=''; $ja='dt'.$id.'|'.$ja.',rid='.$id.'|'.$id;
	$r=['id'=>$id,'list'=>'dt'.$id,'size'=>$s,'value'=>$v,'placeholder'=>$t,'onkeyup'=>'callj(event,this); checkj(event,this);','data-j'=>$j,'data-ja'=>$ja];//,'onclick'=>'ajbt(this)'
	return tagb('input',$r).tag('datalist',['id'=>'dt'.$id],$opt);}

#bj
function aj($tg,$a,$p,$inp,$bt,$c){return bj($tg.'|'.$a.'|'.prm($p).'|'.implode(',',$inp),$bt,$c);}
function bj($call,$t,$c='',$r=[]){//wait for data-jb/-prmtm/-toggle
	$onc=$r['onclick']??''; $r['data-j']=$call; if($c)$r['class']=$c; $r['onclick']='ajbt(this);'.$onc;
	if(ses('dev')=='prog')$r['title']=vadd($r,'title',$call,' - '); return tag('a',$r,$t);}
function bjs($j){$p=explode('|',$j); if(isset($p[2]))$p[2]=_jr(explode(',',$p[2])); return ajx(implode('|',$p));}
function bjt($call,$t,$c='',$ti=''){return bj($call,$t,$c,['title'=>$ti]);}
function bjlog($call,$t,$c='',$r=[]){
	if(auth(1))return bj($call,$t,$c,$r); else return popup('login,com',$t,$c);}
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
	$r=['data-j'=>$j,'data-jb'=>$jb,'rel'=>$o,'onclick'=>'togbt(this)','class'=>$c.active($o,1)];
	if(ses('dev')=='prog')$r['title']=$j.' / '.$jb;
	return tag('a',$r,$t);}
function call($d){$r=explode('|',$d); [$a,$m]=expl(',',$r[1],2); if(!$m)$m='content';
	$p=prmr($r[2]); if(method_exists($a,$m))return $a::$m($p);}
function ajtime($call,$prm,$t,$c=''){$id=randid('bbt');
	$r['onmouseover']='ajxt(\'bubble,'.$id.',1|'.$call.'|'.$prm.'\',this); zindex(\''.$id.'\');';
	$r['onmouseout']='clearTimeout(xc);'; $r['onmouseup']='clearTimeout(xc);';
	return span(bj('bubble,'.$id.',1|'.$call,$t,$c,$r),'',$id);}

function bjo($call,$t,$c='',$r=[]){$r['data-j']=$call; $r['onmouseover']='ajbt(this);'; if($c)$r['class']=$c;
return tag('a',$r,$t);}
function bubjs($tx,$t,$c=''){$id=randid(); $r=['id'=>$id,'onmouseover'=>'bubjs(this,1)','onmouseout'=>'bubjs(this,0)','data-tx'=>$tx,'class'=>$c]; return tag('a',$r,$t);}
function bubj($j,$t,$c=''){$r=['data-ja'=>strfrom($j,'|').',o=1','onclick'=>ajx($j),'onmouseover'=>'bubj(this,1)','onmouseout'=>'bubj(this,0)','class'=>$c]; return tag('a',$r,$t);}
function bubj2($tx,$j,$t,$c=''){$r=['data-j'=>$j,'onclick'=>'ajbt(this)','onmouseover'=>'bubjs(this,1)','onmouseout'=>'bubjs(this,0)','data-tx'=>$tx,'class'=>$c]; return tag('a',$r,$t);}

#php
function strsplit($v,$s){return [substr($v,0,$s),substr($v,$s)];}
function strprm($d,$s,$n){$r=explode($s,$d); return $r[$n]??'';}
function strto($d,$s){$p=strpos($d,$s); return $p!==false?substr($d,0,$p):$d;}
function struntil($d,$s){$p=strrpos($d,$s); return $p!==false?substr($d,0,$p):$d;}
function strend($d,$s){$p=strrpos($d,$s); return $p!==false?substr($d,$p+strlen($s)):$d;}
function strfrom($d,$s){$p=strpos($d,$s); return $p!==false?substr($d,$p+strlen($s)):$d;}
function strcut($d,$s){$p=strpos($d,$s); return $p!==false?[substr($d,0,$p),substr($d,$p)]:[$d,''];}
function popstr($d,$s){if(substr($d,-1,1)==$s)$d=substr($d,0,-1); return $d;}
function between($d,$a,$b,$na='',$nb=''){$pa=$na?strrpos($d,$a):strpos($d,$a);
if($pa!==false){$pa+=strlen($a); $pb=$nb?strrpos($d,$b,$pa):strpos($d,$b,$pa);
	if($pb!==false)return substr($d,$pa,$pb-$pa);}}
function exclude($d,$s,$e){$pa=strpos($d,$s);
	if($pa!==false){$pb=strpos($d,$e,$pa);
		if($pb!==false)$d=substr($d,0,$pa).substr($d,$pb+=strlen($e));}
	if(strpos($d,$s)!==false && strpos($d,$e)!==false)$d=exclude($d,$s,$e); return $d;}
function combine($a,$b){$n=count($a); $r=[]; for($i=0;$i<$n;$i++)$r[$a[$i]]=$b[$i]??''; return $r;}
function merge($r,$rb){if(is_array($r) && $rb)return array_merge($r,$rb); elseif($rb)return $rb; else return $r;}
function merger(...$r){$rt=[]; foreach($r as $k=>$v)foreach($v as $ka=>$va)$rt[$ka][$k]=$va; return $rt;}
function pushr($r,$rb){foreach($rb as $k=>$v)$r[]=$v; return $r;}
function pushv($ra,...$rb){return array_merge($ra,$rb);}
function split_one($s,$d,$o=''){if(!$d)return ['','']; $n=$o?strrpos($d,$s):strpos($d,$s);
if($n!==false)return [substr($d,0,$n),substr($d,$n+1)]; else return [$d,''];}
function split_one_mb($s,$d,$o=''){if(!$d)return ['',''];
$n=$o?mb_strrpos($d,$s,0,'UTF-8'):mb_strpos($d,$s,0,'UTF-8');
if($n!==false)return [mb_substr($d,0,$n),mb_substr($d,$n+1)]; else return [$d,''];}
function implode_b($s,$r){foreach($r as $k=>$v)if($v)$rb[]=$v; if(isset($rb))return implode($s,$rb);}
function implode_r($r,$l,$s){$rb=[]; foreach($r as $k=>$v)$rb[]=implode($s,$v); if($rb)return implode($l,$rb);}
function implode_k($r,$l,$s){$rb=[]; foreach($r as $k=>$v)$rb[]=$k.$s.$v; return implode($l,$rb);}
function implode_kr($r,$l,$s){$rb=[]; foreach($r as $k=>$v)$rb[]=$k.$s.(is_array($v)?implode_kr($v,$l,$s):$v); return implode($l,$rb);}
function implode_q($r){return '"'.implode('","',$r).'"';}
function explode_r($d,$l,$s){$r=explode($l,$d); if($r)foreach($r as $k=>$v)$rb[]=explode($s,$v); return $rb;}
function explode_p($d,$l,$s,$o=0){$r=explode($l,$d); if($r)foreach($r as $k=>$v)$rb[]=split_one($s,$v,$o); return $rb;}
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
function transverse($r){foreach($r as $k=>$v)foreach($v as $ka=>$va)$rt[$ka][$k]=$va; return $rt;}

#mecanics
function strid($p,$n=6){return substr(md5($p),2,$n);}
function randid($p=''){return $p.base_convert(substr(microtime(),2,8),10,36);}
function random($p='',$n=10){return $p.strid(microtime(),$n);}
function http($d){return substr($d,0,4)!='http'?'http://'.$d:$d;}
function nohttp($d){return str_replace(['https','http','://','www.'],'',$d??'');}
function domain($d){$d=nohttp($d); return strto($d,'/');}
function nodomain($d){$d=nohttp($d); return strfrom($d,'/');}
function reload($u=''){echo tag('script','','window.location='.($u?$u:'document.URL'));}
function is_img($d){$n=strrpos($d,'.'); $xt=substr($d,$n);
if($xt && $xt!='.' && strpos('.jpg.png.gif.jpeg.webp',$xt)!==false)return true;}
function ex_img($d){if(substr($d,0,1)=='/')$d=substr($d,1);
if(is_file($d) and filesize($d)>1000)return true;}
function ext($d){$a=strrpos($d,'.'); if($a!==false)$d=strtolower(substr($d,$a));
$b=struntil($d,'/'); $d=strto($d,'?'); if(strlen($d<6))return $d;}
function xt($d){return substr(ext($d),1);}
function b36($n,$o=''){return base_convert($n,$o?36:10,$o?10:36);}
function row($r){$ret=''; foreach($r as $v)$ret.=div($v,'cell'); return div($ret,'row');}
function etc($d,$n=200){$d=deln($d,' '); $d=strip_tags($d);
	if(strlen($d)>$n){$e=strpos($d,' ',$n); $d=substr($d,0,$e?$e:$n).'...';} return $d;}
function shortnum($n){if($n>1000000)return ceil($n/1000000).'M';
if($n>1000)return ceil($n/1000).'K'; else return $n;}
function active($d,$v){return $d==$v?' active':'';}//===
function isnum($d){$r=str_split($d); foreach($r as $v)if(!is_numeric($v))return false; return true;}
function radd($r,$k,$v=1){return isset($r[$k])?$r[$k]+$v:$v;}
function vadd($r,$k,$v='',$s=''){return isset($r[$k])?$r[$k].$s.$v:$v;}
function addslashes_b($d){return str_replace('"','&quot;',$d);}
function cat($r,$n,$o=0){$rb=[]; foreach($r as $k=>$v)$rb[$v[$n]??$v]=$k; return $o?array_flip($rb):$rb;}
function catr($r,$n){$rb=[]; foreach($r as $k=>$v)$rb[$v[$n]??$v][]=$k; return $rb;}
function tri($r,$n,$d){$rb=[]; foreach($r as $k=>$v)if($v[$n]==$d)$rb[$k]=$v; return $rb;}
function displace($r,$id,$to){if($id==$to)return $r; $rk=$r[$id]; unset($r[$id]);
foreach($r as $k=>$v){if($k==$to)$rb[$id]=$rk; $rb[$k]=$v;} return $rb;}
function padleft($d,$n){return sprintf('%\'.0'.$n.'d',$d);}

#dates
function mktime2($y=0,$m=0,$d=0,$h=0,$i=0,$s=0){return mktime($h,$i,$s,$m,$d,$y);}
<<<<<<< HEAD
function is_date($y,$m,$d){return sprintf("%04d-%02d-%02d",$y,$m,$d);}
=======
function isdate($y,$m,$d){return sprintf("%04d-%02d-%02d",$y,$m,$d);}
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
function day2date($d){$s=mktime2(date('Y'),1,1)+(86400*$d); return datz('Y-m-d',$s);}
function day($d='',$n=''){return date($d?$d:'ymdhis',$n?$n:time());}
function datz($d='',$n=''){$tz=new DateTimeZone(ses::$cnfg['tz']); $dt=new DateTime(!is_numeric($n)?$n:'',$tz);
if(is_numeric($n))$dt->setTimestamp($n); return $dt->format($d?$d:'ymd.his');}
function nbday($d,$n=''){return strtotime($d)+86400*$n;}
function scinb($n){return sprintf("%.3e",$n);}

#batch//array_map
function loop($fc,$n,$a='',$b=''){$r=[]; for($i=0;$i<$n;$i++)$r[]=$fc($i,$a,$b,$r); return $r;}
function walkr($r,$fc,$a='',$b=''){$rt=[]; foreach($r as $k=>$v)$rt[]=$fc($v,$a?$a:$k,$b); return $rt;}
function walkd($r,$fc,$a='',$b=''){$ret=''; foreach($r as $k=>$v)$ret.=$fc($v,$a?$a:$k,$b); return $ret;}

#controls
function auth($n){if(ses('uid') && ses('auth')>=$n)return true;}
function val($r,$k,$b=''){return empty($r[$k])?$b:$r[$k];}
function valb($r,$k,$b=''){return empty($r[$k])?$b:$r[$k];}
function valr($r,$k,$kb){$ra=$r[$k]??[]; return $ra[$kb]??'';}
function vals($p,$r,$o=''){foreach($r as $k=>$v)$rt[]=$p[$v]??$o; return $rt;}
function valsb($p,$r,$o=''){foreach($r as $k=>$v)$rt[]=valb($p,$v,$o); return $rt;}
function valk($p,$r,$o=''){foreach($r as $k=>$v)$rt[$v]=$p[$v]??$o; return $rt;}
function valkb($p,$r,$o=''){foreach($r as $k=>$v)$rt[$v]=valb($p,$v,$o); return $rt;}
function expl($s,$k,$n=2){$r=explode($s,$k); for($i=0;$i<$n;$i++)$rb[]=$r[$i]??''; return $rb;}
function arr($r,$n=''){$rb=[]; $n=$n?$n:count($r); for($i=0;$i<$n;$i++)$rb[]=$r[$i]??''; return $rb;}

function prm($p){$rt=[]; foreach($p as $k=>$v)if($k!='_pw' && $k!='_a' && $k!='_m')
	$rt[]=$k.'='.jurl($v); return implode(',',$rt);}
function prmb($p,$r){foreach($r as $k=>$v)$rt[]=$v.'='.$p[$v]; return implode(',',$rt);}//_jrb
function prmp($p,$r){$rb=[]; foreach($r as $k=>$v)$rb[$v]=$p['p'.($k+1)]??''; return $rb;}//_jrb
function prmr($d){$r=explode(',',$d); $rb=[];//explode_k
	foreach($r as $k=>$v){[$ka,$va]=split_one('=',$v); if($ka)$rb[$ka]=$va;} return $rb;}
function mkprm($p){foreach($p as $k=>$v)$rt[]=$k.'='.$v; if($rt)return implode('&',$rt);}

function trims($r){foreach($r as $k=>$v)$r[$k]=trim(str_replace("&nbsp;",' ',$v)); return $r;}
function trimv($d){return trim($d);}
function trimr($r){return array_filter($r,'trimv');}
function get($k,$v=''){$d=filter_input(INPUT_GET,$k); return $d?$d:$v;}
function post($k){return filter_input(INPUT_POST,$k);}
function cookie($d,$v=0){if($v)setcookie($d,$v,time()+(86400*90)); else $v=$_COOKIE[$d]??''; return $v;}
function rmcookie($d){setcookie($d,0,time()-3600); return 'no';}

function sesrv($d,$v='',$n=''){if($v)$_SERVER[$d]=$v; return $_SERVER[$d]??$n;}
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
function sesrif($d,$k,$v=''){$rt=sesr($d,$k); if(!$rt)$rt=sesr($d,$k,$v); return $rt;}
function sesrz($d,$k,$v=''){if($v)return $_SESSION[$d][$k]=$v; else unset($_SESSION[$d][$k]);}
function sesf($d,$p='',$z=''){$v=ses($d); if((!$v or $z) && function_exists($d))$v=ses($d,$d($p)); return $v;}
function sesm($d,$m,$p='',$z=''){$v=$d.$m.$p; if(!ses($v) or $z)sez($v,$d::$m($p)); return ses($v);}
function sestg($d){return ses($d)?sez($d,0):ses($d,1);}
function offon($d){return $d?0:1;}
function uns($r,$k){if(isset($r[$k]))unset($r[$k]); return $r;}
function unr($r,$rb){foreach($rb as $v)$r=uns($r,$v); return $r;}
function swap(&$a,&$b):void{[$a,$b]=[$b,$a];}

#head
class head{static $r=[];
static function html(){return '<!DOCTYPE html>'.n().'<html lang="fr" xml:lang="fr">'.n();}
static function meta($attr,$prop,$d=''){return tagb('meta',[$attr=>$prop,'content'=>$d]);}
static function csslink($u){//if(strrchr($u,'.')=='.css')
return '<link href="/'.ses('dev').$u.'" rel="stylesheet" type="text/css">';}
static function jslink($u){if(substr($u,0,4)!='http')$root='/'.ses('dev'); else $root='';
return '<script src="'.$root.$u.'"></script>';}
static function csscode($d){return '<style type="text/css">'.$d.'</style>';}
static function jscode($d,$id=''){return '<script type="text/javascript"'.atd($id).'>'.$d.'</script>';}
static function add($tag,$r){self::$r[][$tag]=$r;}//add
static function prop($p,$v){self::$r[]['meta']=['attr'=>'property','prop'=>$p,'content'=>$v];}
static function name($p,$v){self::$r[]['meta']=['attr'=>'name','prop'=>$p,'content'=>$v];}
static function build(){$ret=''; $r=self::$r;
if($r)foreach($r as $k=>$v){if(is_array($v))$va=current($v);
	switch(key($v)){
		case('code'):$ret.=$va."\n"; break;
		case('charset'):$ret.='<meta charset="'.$va.'">'."\n"; break;
		case('csslink'):if($va)$ret.=self::csslink($va)."\n"; break;
		case('jslink'):if($va)$ret.=self::jslink($va)."\n"; break;
		case('csscode'):if($va)$ret.=self::csscode($va)."\n"; break;
		case('jscode'):if($va)$ret.=self::jscode($va)."\n"; break;
		case('rel'):$ret.='<link rel="'.$v['rel']['name'].'" href="'.$v['rel']['value'].'">'."\n"; break;
		case('meta'):$v=$v['meta']; $ret.=self::meta($v['attr'],$v['prop'],$v['content'])."\n"; break;
		case('tag'):$v=$v['tag']; $ret.=tag($v[0],$v[1],$v[2])."\n"; break;}}
	return $ret;}
static function run(){$ret=self::build(); return self::html().tag('head',[],$ret).n();}}

#parsers
function delp($d){return str_replace(['<p>','</p>'],"\n",$d);}
function delbr($d,$o=''){return str_replace(['<br />','<br/>','<br>','<br clear="left"/>'],$o,$d);}
function deln($d,$o=''){return str_replace("\n",$o,$d);}
function delr($d,$o=''){return str_replace("\r",$o,$d);}
function delt($d,$o=''){return str_replace("\t",$o,$d);}
function delnbsp($d){return str_replace("&nbsp;",' ',$d);}
function cleansp($d){return preg_replace('/( ){2,}/',' ',$d);}
function cleannl($d){return preg_replace('/(\n){2,}/',"\n\n",$d);}
function cleanrl($d){return cleannl(delr($d,"\n"));}

#conn
function cprm($d,$o=0){$s='|';
function cprm($d,$o=0){$s='|';
//$d=str_replace(['$','|'],$s,$d);//patch//,'*'
return split_one_mb($s,$d,$o);}//

function readconn($d){//p|o:c
//return poc($d);
[$da,$c]=split_one(':',$d,1);
[$p,$o]=cprm($da);
[$p,$o]=cprm($da);
return [$p,$o,$c,$da];}

function readgen($d){//p*o:c
[$da,$c]=split_one(':',$d,1);
[$p,$o]=split_one('*',$da,1);
return [$p,$o,$c,$da];}

function poc($d){$p=''; $o=''; $c=''; $n=strrpos($d,'|'); $nb=strrpos($d,':');//p|o:c
if($n!==false && $nb>$n){$p=substr($d,0,$n); $o=substr($d,$n+1,$nb-$n-1); $c=substr($d,$nb);
if($o=='http'||$o=='https'){$o.=$c; $c='';}}
elseif($n!==false && $nb<$n){$p=substr($d,0,$n); $o=substr($d,$n+1); $c='';}
elseif($n!==false && $nb!==false){$p=substr($d,0,$nb); $o=substr($d,$nb+1,$n-$nb-1); $c=substr($d,$nb);
if($p=='http'||$p=='https'){$p=substr($d,0,$n); $o=substr($d,$n+1); $c='';}}
elseif($n===false && $nb!==false){$p=substr($d,0,$nb); $o=''; $c=substr($d,$nb);
if($p=='http'||$p=='https'){$p.=$c; $c='';}}
elseif($n===false && $nb===false){$p=$d; $o=''; $c='';}
return [$p,$o,$c,$p.($o?'|'.$o:'')];}

#db
function db_read($f,$k='',$kb='',$h=''){$r=db::read($f);
if($k && isset($r[$k])){$rt=$r[$k]; if($kb && isset($rt[$kb]))$rt=$rt[$kb];} else $rt=$r;
if($h && isset($rt['_']))unset($rt['_']); return $rt;}
function db_read_row($f,$k){$r=db::read($f);
if(isset($r[$k])){$rb=$r[$k]; if(isset($r['_']))$ra=$r['_'];
if(isset($ra))return array_combine($ra,$rb); else return $rb;}}
function db_write($f,$r,$k='',$kb=''){
if($k){$r=db::read($f); if(!isset($r[$k]))return; if($kb)$r[$k][$kb]=$r; else $r[$k]=$r;}
db::save($f,$r);}

#dir
//function scandir_a($dr){return array_slice(scandir($dr),2);}
function scandir_a($dr){$r=scandir($dr); $r=array_diff($r,['.','..','_notes','.php']); sort($r); return $r;}

function scan_dir($dr){$rt=[];//structured format
if(is_dir($dr))$r=scandir_a($dr); if(!isset($r))return;
foreach($r as $k=>$v){
	if(is_dir($dr.'/'.$v))$rt[$v]=$v; else $rt[$k]=$v;}
return $rt;}

//playdir()
function scandir_r($dr){//tree (structured format)
if(!is_dir($dr))return;
$r=scandir_a($dr); $rt=[];
foreach($r as $k=>$v){$drb=$dr.'/'.$v;
	if(is_dir($drb))$rt[$v]=scandir_r($drb);
	else $rt[]=$v;}
return $rt;}

function dirlist($dr,$nof='',$noit=''){//collated list
$dr=popstr($dr,'/'); $r=scandir_a($dr); static $i; $rt=[];
foreach($r as $k=>$f){$drb=$dr.'/'.$f; $i++;
if(is_dir($drb)){
	if($nof)$rt[$f]=$f; if(!$noit)$rt+=dirlist($drb,$nof,$noit);}
elseif(!$nof)$rt[$i]=$drb;}//is_file($drb) && 
return $rt;}

function mkdir_r($u){$ret='';
$nu=explode('/',$u); if(count($nu)>12)return;
if(strpos($u,'Warning')!==false)return;
foreach($nu as $k=>$v){$ret.=$v.'/'; if(strpos($v,'.'))$v='';
if($v && !is_dir($ret) && !mkdir($ret))echo '('.$v.':no)';}}

function rmdir_r($dr){
if(!auth(6))return; $dir=opendir($dr); $ret=$dr.br();
while($f=readdir($dir)){$drb=$dr.'/'.$f;
	if(is_dir($drb) && $f!='..' && $f!='.'){rmdir_r($drb); if(is_dir($drb))rmdir($drb);}
	elseif(is_file($drb)){unlink($drb); $ret.=$drb.br();}}
if(is_dir($dr))rmdir($dr); return $ret;}

//walk
//walkdir('walkMethod','db',scandir_r('db'),1);
function walkMethod($dr,$file){return $dr.'/'.$file;}
function walkdir($fc,$dr,$r=[],$rec=''){$rt=[];
if(!$r)$r=scandir_r($dr);
if(substr($dr,-1)=='/')$dr=substr($dr,0,-1);
if($r)foreach($r as $k=>$v){
	if(is_array($v)){
		$rb=walkdir($fc,$dr.'/'.$k,$v,$rec);
		if($rec)$rt[$k]=$rb; else $rt=array_merge($rt,$rb);}
	elseif(is_file($dr.'/'.$v))$rt[$k]=$fc($dr,$v);}
return $rt;}
#files
function gz($f,$fb){$w=write_file($fb,implode('',gzfile($f))); if($w===false)return 'error';}
function gunz($f){return readgzfile($f);}
function writegz($f,$d){$gz=gzopen($f,'w9'); gzwrite($gz,$d); return gzclose($gz);}
function readgz($f,$o=0){$d=gzopen($f,'rb',$o); $ret='';
if($d)while(!gzeof($d))$ret.=gzread($d,1024); gzclose($d); return $ret;}
function fdate($f,$v='ymd.His'){if(is_file($f))return date($v,filemtime($f));}
function fsize($f,$o=''){if(is_file($f))return round(filesize($f)/1024,2).($o?' Ko':'');}
function fdim($f,$w=0,$h=0,$ty=''){if(is_file($f))[$w,$h,$ty]=@getimagesize($f); return [$w,$h,$ty,0,0,0,0];}
//function fex($f){$fp=finfo_open(FILEINFO_MIME_TYPE); $d=finfo_file($fp,$f); finfo_close($fp); return $d;}
function fex1($f){return @fopen($f,'r');}
function fex2($f){$fp=curl_init($f); curl_setopt($fp,CURLOPT_NOBODY,true); curl_exec($fp);
$d=curl_getinfo($fp,CURLINFO_HTTP_CODE); return $d==200?1:0;}

function write_file($f,$d){
$h=fopen($f,'w+'); if(!$h)return 'error'; $w=fwrite($h,$d); fclose($h);
if(!sql::$lc)opcache_invalidate($f);
if($w===false)return 'error';}

function read_file($f){
if(is_file($f??''))$fp=fopen($f,'r'); $ret='';
if(isset($fp)){while(!feof($fp))$ret.=fread($fp,8192); fclose($fp);}
return $ret;}

function read_file2($f){if(fex2($f))return read_file($f);}
function read_try($f){try{$d=file_get_contents($f);}catch(Exception $ex){$d=$ex->getMessage();} return $d;}

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
return curl_exec($ch);}

function get_file($f){
$d=curl($f);//try{}catch(Exception $e){echo $e->message();}
$d=curl($f);//try{}catch(Exception $e){echo $e->message();}
//if(!$d)$ret=read_context($f);
//if(!$d)$ret=read_file($f);
return $d;}

function get_file2($f){
if($d=sesr('gf',$f))return $d;
$d=get_file($f); sesr('gf',$f,$d);
return $d;}

function dom($d,$f=''){
if($f)$d=curl($f);
//$d=str::utf8dec($d);
//$d=str::utf8dec($d);
$dom=new DomDocument('2.0');//,'UTF-8'
$dom->validateOnParse=true;
$dom->preserveWhiteSpace=false;
libxml_use_internal_errors(true);
if($d)$dom->loadHtml($d,LIBXML_HTML_NODEFDTD);//LIBXML_HTML_NOIMPLIED|
return $dom;}

function get_dom($f,$o=''){
if($o){$d=get_file($f); if($d)return dom($d);}
else{$dom=dom(''); $dom->loadHTMLFile($f); return $dom;}}

function domattr($v,$p){if($v->hasAttribute($p))return $v->getAttribute($p);}

function svgdim($f,$w=0,$h=0){$r=[$w,$h];
$d=read_file($f); if(!$d)return $r;
$w=between($d,'width="','"');
$h=between($d,'height="','"');
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
foreach($r as $k=>$v)fputcsv($h,$v,',','"','"'); fclose($h);}
return lk('/'.$f,pictxt('file-data',$t?$t:$f));}

function readcsv($f,$s="\t"){$rb=[];
if(($h=fopen($f,'r'))!==false){$k=0;
while(($r=fgetcsv($h,null,$s))!==false){$nb=count($r);
for($i=0;$i<$nb;$i++)$rb[$k][]=$r[$i]; $k++;} fclose($h);}
return $rb;}

//ascii
function ascii($v){if(is_numeric($v))$v='#'.$v; return '&'.$v.';';}
function asciinb($n){if(is_numeric($n))return ascii(65296+$n);}

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

function unquote_r($r,$o=''){$rt=[];
foreach($r as $k=>$v){
	if(is_array($v))$rt[$k]=unquote_r($v,$o);
	else $rt[$k]=$o?str_replace('\"','"',$v):str_replace('"','\"',$v);}
return $rt;}

function json_enc($r,$o=''){
if($o)$r=unquote_r($r);
$ret=json_encode($r,JSON_HEX_TAG|JSON_HEX_QUOT);
//|JSON_FORCE_OBJECT|JSON_HEX_TAG|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS
if(!$ret)$ret=json_er();
return $ret;}

function json_dec($d){
$r=json_encode($d,true);
$r=unquote_r($r,1);
if(!$r)$ret=json_er();
return $r;}

#clr
function diez($d,$o=''){return $o?substr($d,1):'#'.$d;}
function rgb($d){return [hexdec(substr($d,0,2)),hexdec(substr($d,2,2)),hexdec(substr($d,4,2))];}
function rgb2($r,$o=0){return [$r[0]+$o,$r[1]+$o,$r[2]+$o];}
function rgba($d,$a=1){$r=rgb($d); return 'rgba('.$r[0].','.$r[1].','.$r[2].','.$a.')';}
function rgb2clr($r){$ret=''; for($i=0;$i<3;$i++){$d=dechex($r[$i]>0?$r[$i]:0);
	if(strlen($d)==1)$d='0'.$d; $ret.=$d=='0'||!$d?'00':$d;}
	return $ret;}
function hexrgb_r($d){for($i=0;$i<3;$i++)$r[]=hexdec(substr($d,$i*2,2)); return $r;}
function hexrgb($d,$o=''){$r=hexrgb_r($d); return 'rgba('.$r[0].','.$r[1].','.$r[2].($o?','.$o:'').')';}
function rgb2hex($r){$ret=''; for($i=0;$i<3;$i++){$d=dechex($r[$i]);
	if(strlen($d)==1)$d='0'.$d; $ret.=$d=='0'||!$d?'00':$d;}
return $ret;}
function hsl2rgb($h,$s,$l){
$h/=360; $s/=100; $l/=100; $r=$l;$g=$l;$b=$l;
$v=($l<=0.5)?($l*(1.0+$s)):($l+$s-$l*$s);
if($v>0){$m=''; $sv=''; $sextant=''; $fract=''; $vsf=''; $mid1=''; $mid2='';
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

function clrneg($c,$o=0){if(!ctype_xdigit((string)$c))return; $r=array_map('hexdec',str_split($c,2));
if($o){$n=array_sum(array_map(fn($k,$v)=>match($k){0=>$v*0.3,1=>$v*0.5,default=>$v*0.1},array_keys($r),$r));
return $n>120?'000000':'ffffff';}
$r=array_map(fn($v)=>dechex(255-$v),$r);
$r=array_map(fn($v)=>str_pad($v,2,'0',STR_PAD_LEFT),$r);
return join('',$r);}
<<<<<<< HEAD

#ifis
function is_bin($d){return preg_match('/^[01]+$/',$d)===1;}
function is_hex($d){return ctype_xdigit((string)$d)?1:0;}
function isfloat(string $d){[$a,$b]=expl('.',$d); return is_numeric($a)&&is_numeric($b)?1:0;}

function find_type($d){
if(is_bin($d))$r[]='bin';
if(isfloat($d))$r[]='float';
if(is_int($d))$r[]='int';
if(is_numeric($d))$r[]='num';
if(is_hex($d))$r[]='hex';
if(is_string($d))$r[]='string';
return $r;}
=======
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235

#img
//force LH, cut and center
function scale($w,$h,$wo,$ho,$s){$hx=$wo/$w; $hy=$ho/$h; $yb=0; $xb=0;
if($s==2){$xb=($wo/2)-($w/2); $yb=($ho/2)-($h/2); $wo=$w; $ho=$h;}
elseif($hy<$hx && $s){$xb=0; $yb=($ho-($h*$hx))/2; $ho=$ho/($hy/$hx);}//reduce_h
elseif($hy>$hx && $s){$xb=($wo-($w*$hy))/2; $wo=$wo/($hx/$hy);}//reduce_w
elseif($hy<$hx){$xb=($wo-($w*$hy))/2; $wo=$wo/($hx/$hy);}//adapt_h
elseif($hy && $hx){$xb=0; $ho=$ho/($hy/$hx);}//adapt_w
return [round($wo??0),round($ho??0),round($xb),round($yb)];}

function mkthumb($in,$out,$w,$h,$s){$xa=0; $ya=0;
$w=$w?$w:170; $h=$h?$h:100; [$wo,$ho,$ty]=fdim($in);
[$wo,$ho,$xb,$yb]=scale($w,$h,$wo,$ho,$s);
if(is_file($in))if(fsize($in)>5000)return;
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

#stats
function proportions($r,$n=1){$rb=[];
$a=array_sum($r); $t=$n/$a;
foreach($r as $k=>$v)$rb[$k]=$v*$t;
return $rb;}

#meca
function dump($r,$o=''){$rb=[]; $i=0; if(is_array($r))foreach($r as $k=>$v){$ka='';
if(is_array($v))$va=dump($v,$o); else $va='\''.addslashes(stripslashes($v)).'\'';
if($k!=$i or $o)$ka=is_numeric($k)?$k:'\''.addslashes(stripslashes($k)).'\''; $rb[]=($ka?$ka.'=>':'').$va; $i++;}
return '['.implode(',',$rb).']';}

#utils
function url($d){return host(1).'/'.$d;}
function trace(){eco(debug_backtrace());}
function host($o=''){return ($o?'http://':'').$_SERVER['HTTP_HOST'];}
function srv($o=''){return ($o?'http://':'').ses::$cnfg['srv'];}
function ip(){return gethostbyaddr($_SERVER['REMOTE_ADDR']);}
function eco($d,$o='',$w=42,$h=6){if(is_object($d))return var_dump($d);
$d=is_array($d)?pr($d,1):$d; $ret=textarea('',str::utf8dec2($d),$w,$h);
if($o)return $ret; else echo str::utf8enc($ret);}
function display($d,$o=''){if(is_array($d))$d=pr($d,1);
if(!$o)return $d; else echo div($d.': '.${$d});}
function chrono($d='chrono',$n=5){static $s;
if(!$s)$s=$_SERVER['REQUEST_TIME_FLOAT']; $s1=microtime(1); $res=$s1-$s; $s=$s1;
return $d.':'.round($res,$n);}
?>

