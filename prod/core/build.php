<?php
class build{

#table
static function cell($id,$v,$j){
$j='savecell(\''.$id.'\',\''.$j.'\',this);';
if(strlen($v)>400)return tag('td',['class'=>''],etc($v));
$r=['id'=>'d'.$id,'contenteditable'=>'true','class'=>'editable scroll2','onblur'=>$j];
return tag('td',$r,$v);}

static function editable($r,$a,$p,$jb=''){$tr=''; $ny=0;
if(!$a)$a='admin_sql'; $pg=$p['pg']??1; $p['pg']=$pg; $i=0; $bt='';
if(!isset($p['did']))$p['did']=randid('dt'); else $ny=1; $did=$p['did'];
$prm=prm($p); if($jb)$jb=$a.',sav_cell|'.$prm;
$n=count($r); $nbp=500; $min=($pg-1)*$nbp; $max=$pg*$nbp;
if($r['_']??''){$rh=$r['_'];
	$bk=popup($a.',edit_row|'.$prm.',k=_','_'); $td=tag('th','',$bk);
	foreach($rh as $k=>$v){$kb=is_numeric($k)?$k+1:$k;
		$td.=tag('th',['id'=>'_-'.$kb],$v.' ('.$kb.')');}
	$tr=tag('tr',['id'=>$k.'-1','class'=>'sticktr'],$td); unset($r['_']);}
if(is_array($r))foreach($r as $k=>$v){if($i>=$min && $i<$max){$td='';
	$bk=popup($a.',edit_row|'.$prm.',k='.$k,$k); $td.=tag('td','',$bk);
	//if(isset($v['_edt'])){$td.=tag('td','',$v['_edt']); unset($v['_edt']);}
	if(is_array($v))foreach($v as $ka=>$va){
		$kb=is_numeric($ka)?$ka+1:$ka; $id=$k.'-'.$kb;
		$td.=self::cell($id,$va,$jb.',id='.$id.'|d'.$id);}
	else $td.=self::cell($k.'-1',$v,$jb.',id='.$k.'-1'.'|d'.$k.'-1');
	if($td)$tr.=tag('tr',['id'=>$k.'-1'],$td);} $i++;}
if($n>=$nbp)$bt=btpages($nbp,$pg,$n,$did.'|'.$a.',replay|'.$prm);
$ret=$bt.div(tag('table','',tag('tbody','',$tr)),'scroll');
return $ny?$ret:div($ret,'',$did,'');}

#apps
static function appids($a){
return sql('id',$a::$db,'rv',['uid'=>ses('uid')]);}

static function select_appid($p){$ret='';
$a=$p['app']??''; $id=$p['id']??'';
if(!class_exists($a))return ''; 
$app=new $a; $r=self::appids($a); $rb[]=lang('select');
if($r)foreach($r as $k=>$v){$bt=$app->tit(['id'=>$v]); $rb[$v]=$bt;}
$js=insert('[\'+this.value+\':'.$a.']',$id);
$ret=select('slct'.$id,$rb,'','','','',$js);
return $ret;}

static function appswsg($id){
$r=applist::com('public'); //pr($r);
array_unshift($r,lang('select'));
$j='slctapp|build,select_appid|id='.$id.',app=';
$ret=select('slct'.$id,$r,'',1,0,$j);
$ret.=span('','','slctapp');
//$ret.=hlpbt('genetics_app');
return $ret;}

#gen
static function genbt($id,$o=''){
$ret=btj('[]',atj('embed_slct',['[',']',$id]),'btn');
$r=['h','b','i','u','q','k','var','div','class'];
foreach($r as $k=>$v)$ret.=btj($v,atj('embed_slct',['[',':'.$v.']',$id]),'btn');
$ret.=hlpbt('genetics_conn');
if($o)$ret.=self::appswsg($id);
return div($ret);}

#wyg
static function wsgbt($id,$o=''){$ret=''; $ns='';
$r=['no'=>lang('titles'),'p'=>'normal','h1','h2','h3','h4','h5']; $rt='';//,'code'
foreach($r as $k=>$v)$rt.=tag('option',['value'=>!is_numeric($k)?$k:$v],$v).$ns;
$ret=tag('select',['id'=>'wygs','onchange'=>atj('format2','\'+this.value+\'')],$rt).$ns;//foreColor
//foreach($r as $k=>$v)$ret.=tag('button',['onclick'=>atj('format2',!is_numeric($k)?$k:$v)],$v);
$r=['bold'=>'bold','italic'=>'italic','underline'=>'underline','strikeThrough'=>'strikethrough','JustifyCenter'=>'align-center','insertUnorderedList'=>'list-ul','insertOrderedList'=>'list-ol','Indent'=>'indent','Outdent'=>'outdent','createLink'=>'link'];//'h'=>'font','JustifyLeft'=>'align-left','inserthorizontalrule'=>'minus',
foreach($r as $k=>$v)$ret.=btn(['onclick'=>atj('format',$k)],ico($v,14)).$ns;
if($o){
	//$ret.=upload::img($id,1);//can't insert
	$ret.=build::import_art(['id'=>$id,'html'=>1,'hk'=>1]).$ns;
	$ret.=build::import_img(['id'=>$id,'html'=>1,'hk'=>1]).$ns;}
$ret.=bj('popup|build,editconn|aid='.$id.'|'.$id,ico('pencil'),'');
return div($ret,'connbt sticky');}

static function wygedt($p){
$s=$p['start']; $e=$p['end']; $txt=$p['txt']??''; $id=$p['id'];
return div($s.'-'.$e,'wyg');}

static function editor($p){
$rid=$p['rid']??''; $ret=self::wsgbt($rid);
$j=ajx('div,'.$rid.'|build,areaconn|aid='.$rid.'|'.$rid);//strcount(\''.$rid.'\',768); 
$r=['contenteditable'=>'true','id'=>$rid,'class'=>'article scroll','onkeyup'=>$j,'onmousedown'=>$j,'placeholder'=>lang('message')];
$ret.=tag('div',$r,'');
$ret.=span('','btko','strcnt'.$rid,'display:none;').' ';
return $ret;}

static function areaconn($p){
$d=$p[$p['aid']??'']??'';
$d=tlex::build_conn($d);
$d=conv::call(['txt'=>$d]);
return conn::call(['msg'=>$d,'mth'=>'minconn','ptag'=>1]);}

#connbt
static function connbt($id,$o=''){$ns='';//ns();
$ret=btj('[]',atj('embed_slct',['[',']',$id]),'btn').$ns;
//$r=['h','b','i','u','q','k','video','web','twit'];//,'url','code'
$r=['url'=>'url','h'=>'big','b'=>'bold','i'=>'italic','u'=>'underline','k'=>'strike','list'=>'list','q'=>'indent','nh'=>'refnote','nb'=>'footnote','aside'=>'aside',];//'c'=>'center','url','code','video'=>'video','web'=>'web','twit'=>'twitter'
foreach($r as $k=>$v)$ret.=btj(langpi($v),atj('embed_slct',['[',':'.$k.']',$id]),'btn').$ns;
$ret.=bj($id.'|core,clean_mail|x='.$id.'|'.$id,pic('clean'),'btn',['title'=>helpx('eraser')]);
if($o==2)$ret.=bubble('images,pick|o=1,id='.$id,pic('img'),'btn');
//$ret.=btj(pic('clean'),atj('clean_mail',[$id]),'btn');
//$ret.=hlpbt('connectors');
if($o)$ret.=upload::call($id);//o:ins-val,cb:pop-bub-tog //pickim($id,'',1);
if($o)$ret.=build::import_img(['id'=>$id,'hk'=>1]);
//if($o)$ret.=build::import_mov(['id'=>$id,'hk'=>1]);
if($o)$ret.=build::import_art(['id'=>$id,'hk'=>1]);
$ret.=bubble('ascii,call|rid='.$id,ico('smile-o'),'btn');
//$ret.=bj('popup|build,editconn|render=1,aid='.$id.'|'.$id,pic('view'),'btn');
if($o==2)$ret.=self::appswsg($id);
return div($ret);}

static function cbt($rid,$r){
$ret=btj('[]',atj('embed_slct',['[',']',$rid]),'btn').' ';
foreach($r as $k=>$v)$ret.=btj($k,insert('['.str::utf8enc($v).':'.$k.']',$rid),'btn').' ';
return div($ret);}

static function editconn($p){$id=$p['aid']??''; $txt=$p[$id]??''; $rid=randid('edc');
if($p['render']??'')return conn::call(['msg'=>$txt,'mth'=>'minconn','ptag'=>1]);
$ret=bj($id.',,x|build,editconn|render=1,aid='.$rid.'|'.$rid,langp('apply'),'btsav').br();
$ret.=build::connbt($rid);
$ret.=textarea($rid,conv::call(['txt'=>$txt]),'64','22','','console');
return $ret;}

static function import_art($p){$rid=randid('mpa');
[$u,$id,$o,$b]=vals($p,['url','id','o','html']); $x=hlpbt('import_art');
if($o)return inputcall($id.'|conv,import|html='.$b.'|url','url',$u?$u:lang('url_article'),22,1).$x;
$bt=toggle($rid.'|build,import_art|o=1,id='.$id.',html='.$b.',url='.$u,langpi('import_web'),'btn');
return $bt.span('','',$rid);}

static function import_img($p){$rid=randid('mpa');
[$u,$o,$b,$tg,$hk]=vals($p,['url','o','html','tg','hk']); $tg=$tg?$tg:'urlim';
$j='html='.$b.',url='.$u.',tg='.$tg.',hk='.$hk; $x=hlpbt('import_img');
if($o)return inputcall($tg.'|upload,import_img|'.$j.'|urlim','urlim',$u?$u:lang('url_image'),22,1).$x;
$bt=toggle($rid.'|build,import_img|'.$j.',o=1',langpi('import_img'),'btn');
return $bt.span('','',$rid);}

static function import_mov($p){$rid=randid('mpa'); $hk=$p['hk']??'';
[$u,$o,$b,$tg,$hk]=vals($p,['url','o','html','tg','hk']); $tg=$tg?$tg:'urlmv';
$j='html='.$b.',url='.$u.',tg='.$tg.',hk='.$hk; $ja=$tg.'|upload,import_mov|'.$j.'|urlmv';
$x=bj($ja,langp('ok'),'btsav').hlpbt('import_mov');
if($o)return inputcall($ja,'urlmv',$u?$u:lang('url'),22,1).$x;
$bt=toggle($rid.'|build,import_mov|'.$j.',o=1',langpi('import_mov'),'btn');
return $bt.span('','',$rid);}

static function import_db($p){$rid=randid('mpb');
[$u,$id,$cb,$a,$o]=vals($p,['url','id','cb','a','o']);
$j=$cb.'sub|'.$a.',subops|op=imp,id='.$id.'|impdb';
$x=bj($j,langp('ok'),'btsav').hlpbt('import_db');
if($o)$ret=inputcall($j,'impdb',$u?$u:lang('root'),22).$x;
else $ret=toggle($rid.'|build,import_db|o=1,id='.$id.',cb='.$cb.',a='.$a,langpi('import_db'),'btn');
return span($ret,'',$rid);}

static function mini($d){
$fa='img/mini/'.$d; $fb='img/full/'.$d;
if(is_file($fb))return imgup($fb,img('/'.$fa));}

#mechanics
static function toggle($p){$v=$p['v']; $rid=randid('itg');
$yes=$p['yes']??'yes'; $no=$p['no']??'no';
if($v==1){$ic='on'; $t=$yes;}else{$ic='off'; $t=$no;}
$j=$rid.'|build,toggle|id='.$p['id'].',v='.($v==1?0:1); $j.=',yes='.$yes.',no='.$no;
return span(bj($j,ico('toggle-'.$ic,22).lang($v==1?$yes:$no)).hidden($p['id'],$v),'',$rid);}

static function leftime($end){$time=$end-ses('time');
if($time>=86400)$ret=langnb('day',floor($time/86400));
elseif($time>=3600)$ret=langnb('hour',floor($time/3600));
elseif($time>=60)$ret=langnb('minute',floor($time/60));
else $ret=langnb('second',$time);
return span($ret,'small');}

static function elapsed_time($d1,$d2=''){$rt=[]; if(!$d2)$d2=time();
$t1=new DateTime(); $t2=new DateTime(); $t1->setTimestamp($d1); $t2->setTimestamp($d2);
$diff=$t1->diff($t2); $n=$diff->format('%d');
$ra=$n>0?['year','month','day']:['hour','minute','second'];
$ty=$n>0?'%y-%m-%d':'%h-%i-%s'; $res=$diff->format($ty); $rb=explode('-',$res);
foreach($rb as $k=>$v)if($v)$rt[]=$v.' '.langs($ra[$k],$v,1);
return implode(', ',$rt);}

static function compute_timer($s){$rt=[];
$r=[31556925,2629744,604800,86400,3600,60,1];
foreach($r as $k=>$v)if($s>$v){$n=floor($s/$v); $s-=$v*$n; $rt[$k]=$n;}
return $rt;}

static function compute_time($s){$rt=[]; $r=self::compute_timer($s);
$ra=['year','month','week','day','h','min','s'];
foreach($r as $k=>$v)//$rt[]=$v.' '.$ra[$k].($v>1?'s':'');
if($s>86400){if($k<4)$rt[]=$v.' '.$ra[$k].($v>1?'s':'');}
else if($k>=4)$rt[]=$v.$ra[$k];
return join(' ',$rt);}

static function calendar($d='',$fc=''){$rt=[];
if(!$fc)$fc=function($mk){return date('d',$mk);};
$gd=getdate($d?$d:time()); $d0=date('d'); $mo=$gd['mon']; $y=$gd['year'];
$fd=date('w',mktime(1,1,1,$mo,1,$y)); if($fd==0)$fd=7;//first day
$nd=date('t',mktime(1,1,1,$mo,1,$y));//nb days in month
$dk=lang('MTWTFSS'); $rt['_']=str_split($dk); $k=1;
for($a=1;$a<$fd;$a++)$rt[$k][]='';
for($i=1;$i<=$nd;$i++){$mk=mktime(0,0,0,$mo,$i,$y); $dy=date('d',$mk);
	$rt[$k][]=span($fc($mk),active($dy,$d0));
	$a++; if($a==8){$a=1; $k++;}}
return tabler($rt,1);}

static function score($n=0,$o=6){$ret='';
for($i=1;$i<$o;$i++){
if($i<=$n+0.25)$ic='star'; elseif($i>$n+0.75)$ic='star-o'; else $ic='star-half-empty';
$ret.=ico($ic);}
return $ret;}

static function scorebt($p){$ret='';
[$id,$rid,$a,$v,$k,$lbl]=vals($p,['id','rid','a','v','k','lbl']);
if(!$a)$a='build'; $j=$rid.'|'.$a.',scorebt|'.prm($p);
for($i=1;$i<6;$i++)$ret.=bj($j.',v='.$i,ico($i<=$v?'star':'star-o'),'star');
return $ret.$lbl.hidden($k,$v);}

static function code($d){$d=trim($d);
ini_set('highlight.comment','gray');
ini_set('highlight.default','white');
ini_set('highlight.html','red');
ini_set('highlight.keyword','orange');
ini_set('highlight.string','lightblue');
$d=highlight_string('<?php'."\n".$d,true);
$d=str_replace(['&lt;?php'."\n",'?>','<br />'],'',$d);
return div(trim($d),'code','','');}

//iterable me,u (vector,bitmap)
static function iterbt($p,$ra,$r,$b,$a){$rb=[]; $rid=$p['bid'];
foreach($ra as $k=>$v){
	if(is_array($v)){
		//$rb[]=[$b.'/'.$k,'js',$a::el($k,$r[$k]??[],$rid),'',$k];
		foreach($v as $ka=>$va){
		if(is_array($va)){
			//$rb[]=[$b.'/'.$k.'/'.$ka,'js',$a::el($ka,$r[$ka]??[],$rid),'',$ka];
			foreach($va as $kb=>$vb){
			$rb[]=[$b.'/'.$k.'/'.$ka,'js',$a::el($vb,$r[$vb]??[],$rid),'',$vb];}}
		else $rb[]=[$b.'/'.$k,'js',$a::el($va,$r[$va]??[],$rid),'',$va];}}
	else $rb[]=[$b,'js',$a::el($v,$r[$v]??[],$rid),'',$v];}
return $rb;}

static function sample($p){//app,$rid,k
$a=$p['a']; $b=$p['b']??''; $k=$p['k']??''; $ret=''; $d=''; $r=$a::ex();
if($k)$d=$r[$k]??''; if($d)return str::utf8enc($d); elseif($k)return;
foreach($r as $k=>$v)$ret.=bj($b.'|build,sample|a='.$a.',k='.$k,$k,'');
return div($ret,'nbp');}

static function dcode($d){
$r=str_split($d); $n=count($r); $n2=$n**4; $rt=[];
for($i=0;$i<$n2;$i++){$r2=$r; $na=rand(1,$n);
for($o=0;$o<$na;$o++){sort($r2); $nr2=count($r2)-1; $nc=rand(0,$nr2); //echo $nc; pr($r2);
$rb[$i][$o]=$r2[$nc]; unset($r2[$nc]);}}
foreach($rb as $k=>$v)$rt[]=implode('',$v);
$rt=array_flip(array_flip($rt)); sort($rt);
return $rt;}
}
?>