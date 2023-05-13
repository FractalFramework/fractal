<?php

class core{
static function js($p){}
static function help($p){[$ref,$c,$cn,$b]=vals($p,['ref','css','conn','brut']);
return help($ref,$c,$cn,$b);}
//static function no($p){return '';}
static function val($p){return $p['p1']??'';}
static function send($p){return $p[$p['v']]??'';}
static function com($p){return com($p['app'],$p['id']);}
static function app($p){return app($p['app']??'',$p['p']??'');}
static function mkbcp($p){return sql::backup($p['b'],$p['o']??'');}
static function rsbcp($p){return sql::rollback($p['b']);}
static function lang_set($p){return lang_set($p['lang']??'');}
static function boxhide($p){return boxhide($p);}
static function clrpick($p){return clrpick($p);}
static function img($p){return img('/'.$p['f']??'',$p['w']??'');}
static function txt($p){return div($p['txt']??'',$p['c']??'');}
static function voc($p){return voc($p['txt']??'',$p['ref']??'');}
static function web($p){return web::play(http($p['u']??''));}
static function audio($p){return audio(http($p['u']??''));}
static function video($p){return video::com(http($p['u']??''));}
static function rplay($p){return play_r(json_decode($p['rj']??'',true));}
static function clean_mail($p){$x=$p['x']??''; if($x)return clean_mail($p[$x]??'');}}

class mem{static $r=[]; static $ret='';}

function qr($d,$x=''){return sql::qr($d,$x);}
function sql($d,$b,$p='',$q='',$z=''){return sql::read($d,$b,$p,$q,$z);}

#usr
function idusr($u){return sql('id','login','v',['name'=>$u]);}
function usrid($id){return sql('name','login','v',$id);}
function vrfusr($d){return sql('id','login','v','where name="'.$d.'" and auth>1');}
function vrfid($d,$db){return sql::inner('name',$db,'login','uid','v','where '.$db.'.id="'.$d.'"');}
function isown($usr){return sql('name','login','v',['mail'=>ses('mail'),'name'=>$usr]);}

#app
function app($app,$p='',$mth=''){$ret='';
if(!is_array($p) && strpos($p,'{')!==false)$p=json_decode($p,true);//}//patch for admin_sys
if(isset($p['prm']))$p=_jrb($p['prm']);//when calling not by ajax
if(!$p)$p=[]; elseif(is_string($p))$p=_jrb($p);
$mth=!empty($p['_m'])?$p['_m']:($mth?$mth:'content'); unset($p['_m']); unset($p['_a']);
if(method_exists($app,$mth)){
	$private=$app::$private??0; $auth=ses('auth'); if(!$auth)$auth=0;
	if($auth>=$private){$a=new $app; $ret=$a->$mth($p);
		if(!isset(ses::$alx[$app])){ses::$alx[$app]=1;
			if(!get('_a') && isset($p['headers']) && method_exists($app,'headers'))$a->headers();}}
	else $ret=help('need auth '.$private,'paneb');}
elseif(!class_exists($app))return div(helpx('nothing').' : '.$app.'::'.$mth,'paneb');
else return div(helpx('no app loaded').' : '.$app.'::'.$mth,'paneb');
return $ret;}

function apj($call){
if(strpos($call,'|')){[$call,$prm]=explode('|',$call); $p=explode_k($prm,',','=');}
if(strpos($call,','))[$call,$mth]=explode(',',$call);
if(isset($mth))$p['_m']=$mth; $p['headers']=1;
return app($call,$p);}

#icon
function icon_ex($d){$r=sesf('icon_com','',0);
if(is_array($r) && array_key_exists($d,$r))return $r[$d];}
function icon_com(){return sql('ref,icon','icons','kv','');}
function icolg($d,$o='',$no=''){$r=sesf('icon_com','',0);
if($r && !array_key_exists($d,$r) && $d && !is_numeric($d) && !$no){
	sql::sav('icons',[$d,'']); $r=sesf('icon_com','',1);}
$ret=!empty($r[$d])?$r[$d]:'';
if($o)$ret=ico($ret); return $ret;}
function ico($d,$s='',$c='',$t='',$ti='',$tb='',$id=''){$r=[];
	if(is_numeric($s))$s='font-size:'.$s.'px'; if($s)$r['style']=$s;
	if($c)$r['class']=$c; if($id)$r['id']=$id; if($ti)$r['title']=($ti); if($tb)$t=' '.$tb;
	$ret=span('','pic fa fa-'.$d).$t; if($r)$ret=tag('span',$r,$ret); return $ret;}
function icoxt($d,$t,$c='',$s=''){return span(ico($d,$s).$t,$c);}
function icoit($d,$t,$c='',$s=''){return ico($d,$s,$c,'',$t);}
//function icid($d,$t,$id){return ico($d,'','',$t,'','',$id);}
function pic($d,$s='',$c=''){return ico(icolg($d),$s,$c);}
function picxt($d,$t=''){return ico(icolg($d),'','','','',$t);}
function picto($d,$s='',$c=''){if($c)$c=' '.$c; if(is_numeric($s))$s='font-size:'.$s.'px;';
	return span('','philum ic-'.$d.$c,'',$s);}
function pictxt($d,$t=''){return span('','philum ic-'.$d).$t;}

#lang
function setlng(){$lng=ses('lng'); if($lng=='en')$lngb='US'; else $lngb=strtoupper($lng);
setlocale(LC_ALL,$lng.'_'.$lngb);}
function lng(){return ses('lng')?ses('lng'):'fr';}//sesif('lng','fr')
function lngs(){return ['fr','en','es'];}//,'it','de','zn','ru','ja','ar','zw'
function lang_set($lg){$v=$lg?$lg:sesif('lng','fr');
sez('lng',$v); cookie('lng',$v); sesf('lang_com',$v,1); return $v;}
function lang_com($lang){return sql('ref,voc','lang','kv',['lang'=>$lang]);}
function lang_ex($d){$lang=sesif('lng','fr');
$r=sesf('lang_com',$lang); if(is_array($r) && array_key_exists($d,$r))return 1;}

function lang($d,$o='',$no=''){
$lang=sesif('lng','fr'); $applng=sesifn('applng',$lang); $r=sesf('lang_com',$lang,0); //$r=lang_com($lang);
if(!$no && $r && $d && !array_key_exists($d,$r) && !is_numeric($d)){//strpos($d,',')===false &&
	$db=trans::com(['from'=>'en','to'=>$lang,'txt'=>$d]); if($db)$d=$db;//
	if(strpos($d,'"')===false)sql::sav('lang',[$d,'',$applng,$lang]);
	$r=sesf('lang_com',$lang,1);}
$ret=!empty($r[$d])?$r[$d]:$d;
if(!$o)$ret=ucfirst_b($ret);
return $ret;}

function langc($d,$c=''){return span(lang($d),$c);}
function langp($d,$s=''){return ico(icolg($d),$s,'ico').lang($d);}
function langpi($d,$s=''){return ico(icolg($d),$s,'ico','',lang($d));}
function langph($d,$s=''){return ico(icolg($d),$s,'ico').span(lang($d),'react');}
function langs($d,$n,$o=''){return lang($d.($n>1?'s':''),$o);}
function langnb($d,$n,$o=''){return span($n.' '.langs($d,$n,1),$o);}
function langnbp($d,$n,$c='',$s=''){return span(ico(icolg($d),$s,'ico').$n.' '.langs($d,$n,1),$c);}
function langx($d,$o=''){$rb=[]; $r=explode(' ',$d);
foreach($r as $k=>$v){$rb[]=lang($v,$o); $o=1;} return implode(' ',$rb);}

//helps
function help($ref,$css='',$conn='',$brut=''){$lg=sesif('lng','fr'); $bt='';//hlpxt
$r=sql('id,txt','help','rw',['ref'=>$ref,'lang'=>$lg]); if(!$r)return $ref;
if(!isset($r[0]) && $ref)$r[0]=sql::sav('help',[$ref,'',$lg]);
if(auth(6))$bt=bj('popup|admin_help,edit|to=hlpxd,id='.$r[0].',headers=1',ico('edit')).' ';
if(isset($r[1]))$txt=$conn?conn::com($r[1],1):nl2br($r[1]); else $txt=$ref;
if($brut)return $r[1]??''; elseif($txt)return div($bt.$txt,$css?$css:'helpxt','hlpxd');}
function helpx($d){return help($d,'','',1);}
function hlpbt($d,$t='',$c='btn'){return bubble('core,help|ref='.$d,ico('question-circle-o').$t,$c);}

//voc
function voc($d,$ref,$lg0=''){$lng=ses('lng');
[$db,$col,$id]=explode('-',$ref); $vrf=md5($d);
$lg=sql('lang','voc','v',['vrf'=>$vrf]);
if(!$lg){$ex=sql('id','voc','v',['ref'=>$ref,'lang'=>$lng]);//changes
	if($ex)sql::del('voc',$ref,'ref');}
if(!$lg){if($lg0)$lg=$lg0; else $lg=trans::detect(['txt'=>$d]);
	if($lg)$id=sql::sav('voc',[$ref,$lg,$d,$vrf]);}
if($lg && $lg!=$lng){
	$b=sql('trad','voc','v',['ref'=>$ref,'lang'=>$lng]);
	if(!$b){$c=trans::com(['from'=>$lg,'to'=>$lng,'txt'=>$d]);
		if($c)sql::sav('voc',[$ref,$lng,$c,md5($c)]); $d=$c;}
	else $d=$b;}
return $d;}

#pages
function btpages_nb($nbp,$pg){
$cases=5; $left=$pg-1; $right=$nbp-$pg; $r[1]=1; $r[$nbp]=1;
for($i=0;$i<$left;$i++){$r[$pg-$i]=1; $i*=2;}
for($i=0;$i<$right;$i++){$r[$pg+$i]=1; $i*=2;}
if($r)ksort($r);
return $r;}

function btpages($nbyp,$pg,$nbarts,$j){$ret=''; $nbp=''; $rp=[];
if($nbarts>$nbyp)$nbp=ceil($nbarts/$nbyp);
if($nbp)$rp=btpages_nb($nbp,$pg);
if($rp)foreach($rp as $k=>$v)$ret.=bj($j.',pg='.$k,$k,active($k,$pg));
if($ret)return div($ret,'nbp sticky');}

function batch_pages($r,$p,$j,$a,$fc){
$id=$p['id']??''; $pg=$p['pg']??1; $nbp=20; $ret=''; $i=0;
$min=($pg-1)*$nbp; $max=$pg*$nbp; $tot=count($r);
$bt=btpages($nbp,$pg,$tot,$a.'pg,,z|'.$j);
if($r)foreach($r as $k=>$v){if($i>=$min && $i<$max)$ret.=$a::$fc($v); $i++;}
return div($bt.$ret,'',$a.'pg');}

#clr
function clrs(){return json::read('json/system/colors');}//get
function clrget($d){$r=sesf('clrs','',0); if(isset($r[$d]))return $r[$d];}//read
function clrand(){$r=sesf('clrs'); if(is_array($r))$r=array_values($r); return $r[rand(0,139)];}
function btclr($k,$v){return span('','clr','','background-color:#'.$v.';');}//,['title'=>$k]

function hsl($v,$ratio,$l=360,$m=50){
if(!is_numeric($v))$h=0; else $h=round($v*$ratio);
return rgb2hex(hsl2rgb($l-$h,$m,$m));}

function clrpick($p){$id=$p['id'];
$r=json::read('json/system/colors'); $ret='';
foreach($r as $k=>$v)$ret.=tag('a',['class'=>'clr','onclick'=>atj('affectclr',[$v,$id,'']),'style'=>'background-color:#'.$v.'; padding:0 4px;','title'=>$k],'');
return div($ret);}

function inpclr($id,$clr,$sz='',$sky='',$bkg=''){$cb=randid('cklr');
if(substr($clr,0,1)=='-' or strpos($clr,',') or is_img($clr))$clrb='black';
else $clrb=clrneg($clr,1);
$inp=tag('input',['type'=>'text','id'=>$id,'value'=>$clr,'size'=>$sz,'placeholder'=>lang($id,1),'onclick'=>'applyclr(this,'.$bkg.')','onkeyup'=>'applyclr(this,'.$bkg.')','style'=>'background-color:#'.$clr.'; color:#'.$clrb],'',1);//
$ret=span(pic('color').$inp,'inpic');
$ret.=toggle($cb.'|core,clrpick|id='.$id,pic('clr'),'btn');
if($sky)$ret.=toggle($cb.'|sky,slct|rid='.$id,ico('snowflake-o'),'btn');
if($bkg)$ret.=upload::img($id,'',$cb);
return $ret.=span('','',$cb);
return div($ret);}

function inpimg($k,$val,$sz,$o=''){
if($o)$bt=pickim($k); else $bt=upload::call($k);//upload::img($k)
$bt.=build::import_img(['tg'=>$k,'html'=>0,'hk'=>0]);
if($val)$bt.=imgup(imgroot($val),pic('view'),'btn');
return input($k,$val,$sz).$bt;}

function theme($clr){
if($clr=='no')$ret='';
elseif(substr($clr,0,1)=='-'){$c=substr($clr,1); //$sky=ses('sky'.$c);
	//if(!$sky){ses('sky'.$c,$sky);}
	$sky=sql('css','sky','v',['tit'=>$c]);
	$clr=between($sky,'#',','); $clr0=clrneg($clr,1);
	$ret='background-image:'.$sky.';';}// color:#'.$clr0.';
	//$ret.='} .bicon, .bicon .pic{color:white;} .bicon:hover, .bicon:hover .pic{color:black;';
elseif(strpos($clr,':'))$ret='background-image:'.$clr.';';
elseif(strpos($clr,'.'))$ret='background-image:url(/'.imgroot($clr).'); background-size:cover;';
elseif(strpos($clr,',')){[$clr1,$clr2]=explode(',',$clr); $clr0=clrneg($clr,1);
	$ret='background-image:linear-gradient(to bottom,#'.$clr1.',#'.$clr2.'); color:#'.$clr0.';';}
elseif($clr){$clr0=clrneg($clr,1);
	$hex='rgba(119,1119,119,0.0)';//$clr?hexrgb($clr,0.9): $clr2=clrb($clr,-20);
	$hex2='rgba(119,99,119,0.4)';//$clr2?hexrgb($clr2,0.3):
	$ret='background-color:#'.$clr.'; ';// color:#'.$clr0.';
	$ret.='background-image:linear-gradient(to bottom,'.$hex.','.$hex2.'); ';
	//$ret.='color:#'.$clr0.';';
	//head::add('csscode','.bicon, .bicon .pic, .bicon .pic:hover{color:#'.$clr0.';}');
	}//h1,h2,h3,h4{color:#'.$clr0.';}
else $ret='';
//.pane,.paneb,.panec,.paned{color:#'.$clr0.' background-color:'.$hex0.';}
//.lisb a, .lisb a:hover,.lisb .pic{color:#'.$clr0.';}
return $ret;}

function bootheme($usr){$cusr=$usr?$usr:ses('usr');
$clr=sesr('clr',$cusr,'');//echo $usr.'-'.$own;
if(!$clr)$clr=profile::init_clr(['usr'=>$cusr]);
$sty=ses('sty'.$clr); if(!$sty){$sty=theme($clr); ses('sty',$sty);}
//if(get('popup'))head::add('csscode','.container{'.$sty.'}'); else
head::add('csscode','body{'.$sty.'}');}

function night($dt){$dt=round($dt);
$r=ses::$r['night']??[]; if($r)return $r;//from meteo
$r=date_sun_info($dt,48.839,2.237);
[$h1,$h2]=vals($r,['sunrise','sunset']); //echo $h1.'-'.$dt.'-'.$h2;
if($dt>$h2 or $dt<$h1)$res='night'; else $res='day';
return '/css/'.$res.'.css';}

#img
function imgthumb($f){
$fa='img/full/'.$f; $fc='img/mini/'.$f;
if(!is_file($fc) && is_file($fa))mkthumb($fa,$fc,170,170,0);
//elseif(is_file($fc))unlink($fc);//maintenance
return $fc;}

function imgroot($f,$dim=''){
if(substr($f,0,4)=='http')return $f;
$fa='img/full/'.$f; $fb='img/medium/'.$f; $fc='img/mini/'.$f;
$med=is_file($fb); if(!$dim)$dim='full'; if(!is_file($fc))imgthumb($f);
if($dim=='mini' or $dim=='micro')$im=$fc;
elseif($dim=='medium')$im=$med?$fb:$fa; else $im=$fa;
return $im;}

function goodir($f){
if(substr($f,0,4)=='http')return $f;
elseif(substr($f,0,4)=='/usr')return $f;
elseif(substr($f,0,4)=='disk')return $f;
elseif(substr($f,0,3)=='usr')return '/disk/'.$f;
else return '/disk/usr/'.$f;}

function img2($f,$dim='',$o=''){
$ret=imgroot($f,$dim); $w=$dim=='micro'?100:''; $w=$dim=='avt'?60:'';
if(ex_img($ret))return img('/'.$ret,$w);
elseif($o)return pic('img');}

function playimg($f,$dim,$o='',$sz=''){if($dim=='micro')$sz=64;
if(substr($f,0,4)=='http')$f=saveimg($f,'tlx',$sz,'');
$u=imgroot($f,$dim); $im=img('/'.$u,$sz); $ua='img/full/'.$f;
if(!is_file($ua))return pic('img');
if(!$u)return; if($o==2)return $u; elseif($o)return $im;
[$w,$h]=@getimagesize($ua);
if($w>800 or $dim=='micro')return imgup($ua,$im);
else return $im;}

function saveimg($f,$prf,$w,$h=''){$er=1;
if(substr($f,0,4)!='http')return;
if(strpos($f,'?'))$f=struntil($f,'?');
$xt=ext($f); if(!$xt)$xt='.jpg';
$nm=$prf.strid($f,10); $h=$h?$h:$w;
$fa='img/full/'.$nm.$xt; mkdir_r($fa);
$fb='img/mini/'.$nm.$xt; //mkdir_r($fb);
$fc='img/medium/'.$nm.$xt; //mkdir_r($fc);
if(is_file($fa))return $nm.$xt;
$ok=@copy($f,$fa);
if(!$ok){$d=@file_get_contents($f); if($d)$er=write_file($fa,$d);}
if($ok or !$er)if(filesize($fa)){mkthumb($fa,$fb,170,170,0);
	upload::add_img_catalog($nm.$xt,$prf);
	[$wa,$ha]=getimagesize($fa); if($wa>$w or $ha>$h)mkthumb($fa,$fc,$w,$h,0);
	return $nm.$xt;}}

function pickim($id,$o='',$cb=''){//$o:insert,$cb:pop/bub/tog//see desktop::pickim
if($cb==1)return bubble('upload,pick|id='.$id.',o='.$o,ico('image'),'btn',[],'z');
elseif($cb)return toggle($cb.',,z|upload,pick|id='.$id.',o='.$o,ico('image'),'btn',[],'z');
else return popup('upload,pick|id='.$id.',o='.$o,ico('image'),'btn');}

#popup
function mkpopup($d,$p){
//$pw=$p['_pw']??640; $w=$p['popwidth']??640;
//$s='width:'.($pw<640?$pw:($w?$w:$pw)).'px;';
$ret=btj(picto('close',20),atj('Close','popup'),'imbtn');
$ret.=btj(picto('ktop',20),atj('repos',''),'imbtn');
$ret.=btj(picto('less',20),atj('reduc','popup'),'imbtn');
$app=$p['_a']??''; $mth=$p['_m']??'';
$title=':: '.$app.' :: ';//lk('/'.$app,ico('link'),'',1).
if(method_exists($app,'titles'))$title.=$app::titles($p); else $title.=$mth.' ';
if($app && method_exists($app,'admin') && !$mth)//
	$title.=menu::call(['app'=>$app,'mth'=>'admin']);
$ret.=tag('span',['class'=>'imbtn'],$title);
$head=tag('div',['id'=>'popa','class'=>'popa','onmouseup'=>'stop_drag(event); noslct(1);','onmousedown'=>'noslct(0);'],$ret);
$ret=tag('div',['id'=>'popu','class'=>'popu'],$d);
if($d)return tag('div',['class'=>'popup'],$head.$ret);}//,'style'=>$s

function mkpagup($d,$p){if(!$d)return;
//if($w=$p['popwidth']??'')$d=div($d,'','','max-width:'.$w.'px');
//$bt=span(btj(ico('close'),'Close(\'popup\');','btn'),'left');$bt.
$d=tag('div',['id'=>'popu','class'=>'pagu'],div($d,'pgu'));
return tag('div',['class'=>'pagup'],$d);}

function mkimgup($d){
$ret=tag('div',['id'=>'popu','class'=>'imgu'],div($d,'imu'));
//$ret=tag('a',['onclick'=>'Close(\'popup\');'],$ret);
return tag('div',['class'=>'pagup'],$ret);}

function mkbubble($d){
$d=tag('div',['id'=>'popu','class'=>'bubu'],$d);
return tag('div',['class'=>'bubble'],$d);}//,'style'=>'max-width:320px'

function mkmenu($d){
$d=tag('div',['id'=>'popu','class'=>'bubu'],$d);
return tag('div',['class'=>'bubble','style'=>''],$d);}

//ajaxencode
function jurl($d,$o=''){//$d=unicode($d);
	$a=['|','§'];//"\n","\t",'\'',"'",'"','*','#','+','=','&','?','.',':',',',,'<','>','/','%u',' '
	$b=['(-bar)','(-par)'];//'(-n)','(-t)','(-asl)','(-q)','(-dq)','(-star)','(-dz)','(-add)','(-eq)','(-and)','(-qm)','(-dot)','(-ddot)','(-coma)',,','(-b1)','(-b2)'(-sl)','(-pu)','(-sp)'
	return str_replace($o?$b:$a,$o?$a:$b,$d);}
function _jr($r){$rt=[];//tostring
	if($r)foreach($r as $k=>$v)if($v)
		if(strpos($v,'=')){[$k,$v]=explode('=',$v); $rt[]=$k.'='.jurl($v);}
	if($rt)return implode(',',$rt);}
function _jrb($d,$s=':'){if(!$d)return [];
	if($d)$r=explode(',',$d); $rt=[];
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
function protect($d,$o=''){$a='|'; $b='(bar)';
	return str_replace($o?$b:$a,$o?$a:$b,$d);}

function jurl2($v,$p=''){#dont edit!//ajx() on philum
$r=['*','_','(star)']; $a=$p?1:0; $b=$p?0:1;
if(!$p){$a=0; $b=1; $c=2; $d=0;} else{$a=1; $b=0; $c=0; $d=2;}//�=>&#167;
$ra=[$r[$a],$r[$b],'_',"\n","\r",'\'',"'",'�','#','�','"','+','=','�','&','.',':','/','&#8617;'];
$rb=[$r[$c],$r[$d],'(und)','(nl)','(nl)','(aslash)','(quote)','(by)','(diez)','(mic)','(dquote)','(add)','(equal)','(euro)','(and)','(dot)','(ddot)','(slash)','(back)'];
return $p?str_replace($rb,$ra,$v):str_replace($ra,$rb,$v);}

#builders
function insert($d,$rid,$o=''){return 'insert(\''.$d.'\',\''.$rid.'\',this);'.($o?' Close(\'popup\');':'');}

function scroll($r,$n,$h=''){$max=count($r); $ret=implode('',$r);
$s='overflow-y:scroll; max-height:'.($h?$h.'px':400).';';
if($max>$n)return tag('div',['id'=>'scroll','style'=>$s],$ret);
else return $ret;}

function boxhide($p,$xid=''){$r=explode('-',$p['s']);
if($xid)$p['rid']=$xid; $rid=$p['rid']; $id=$p['id']; $ka=valb($p,'ka',0); $ret='';
foreach($r as $k=>$v){$c='btn'; if($k==$ka)$c.=' active'; $p['ka']=$k;
	$ret.=bj($rid.'|core,boxhide|'.prm($p),langp($v),$c);}
$ret.=hidden($id,$ka); if($xid)$ret=div($ret,'',$xid); return $ret;}

function trace($r){
return popup('core,rplay||rj',ico('pied-piper'),'btn').divh(json_enc($r),'rj');}

function dragline($t,$id,$j=''){return tag('div',['id'=>$id,'class'=>'dragme','draggable'=>'true','ondragstart'=>'drag_start(event)','ondragover'=>'drag_over(event)','ondragleave'=>'drag_leave(event)','ondrop'=>'drag_sql::drop(event,\''.$j.'\')','ondragend'=>'drag_end(event)'],$t);}

function batch($r,$j,$vrf=''){$ret='';
if($r)foreach($r as $k=>$v)if($v)$ret.=bj(str_replace(['$k','$v'],[$k,$v],$j),$v,active($v,$vrf));
return div($ret,'lisb');}

#templaters
//['class'=>['class1'=>'col_1','class2'=>'col_2']];
function phylo($r,$struct){$rt=[]; $rb=[];
foreach($struct as $k=>$v){
	if(is_array($v))$rt[]=div(phylo($r,$v),'',is_numeric($k)?'':$k);
	elseif(array_key_exists($v,$r)){$vr=$r[$v];
		if(is_array($vr)){
			foreach($vr as $kb=>$vb){
				if(!is_numeric($k))$rb[$kb][$v]=div($vb,$k);
				else $rb[$kb][$v]=$vb;}}
		elseif(!is_numeric($k))$rt[]=div($vr,$k);
		else $rt[]=$vr;}}
if($rb)foreach($rb as $k=>$v)$rt[]=implode('',$v);
if($rt)return implode('',$rt);}

function mktags($r){$rt=[]; //pr($r);
foreach($r as $k=>$v){
	[$tag,$t,$c,$id,$s]=arr($v,5);
	if(is_array($t))$t=mktags($t); //pr($t);
	$rt[]=tag($tag,['class'=>$c,'id'=>$id,'style'=>$s],$t);}
return implode('',$rt);}

//tabler
function tabler($r,$head='',$keys='',$sums=''){$i=0; $tr=[];
if(is_array($head))array_unshift($r,$head);
if($sums)foreach(next($r) as $k=>$v)$r['='][]=array_sum(array_column($r,$k));
if(is_array($r))foreach($r as $k=>$v){$td=[]; $i++;
	if(($head && $i==1) or $k==='_' or $k==='=')$tag='th'; else $tag='td';
	if($keys)$td[]=tag($tag,'',$k?$k:'_');
	if(is_array($v))foreach($v as $ka=>$va)
		$td[]=tag($tag,['id'=>$k.'-'.$ka],$va);
	else $td[]=tag($tag,'',$v);
	if($td)$tr[]=tag('tr',['id'=>'k'.$k],join('',$td));}
$ret=tag('tbody','',join('',$tr));
return div(tag('table','',$ret),'scroll','','');}//overflow:auto;

function play_r($r){$ret='';//expl
if(is_array($r))foreach($r as $k=>$v)
	if(is_array($v))$ret.=li($k).play_r($v);
	else $ret.=li($k.':'.$v);
return ul($ret);}

//taxo
function taxo_clean(&$r,$rb){
if($rb)foreach($rb as $k=>$v)if(isset($r[$v]))unset($r[$v]);}

function taxo_find($rb,$ra,&$rx){$rt=[];
foreach($rb as $k=>$v){
	if(isset($ra[$k])){
		if(is_array($ra[$k]))$rt[$k]=taxo_find($ra[$k],$ra,$rx);
		else $rt[$k]=$ra[$k];
		$rx[]=$k;}
	else $rt[$k]=$v;}
return $rt;}

//$rb[$v['idp']][$v['idn']]=1;
function taxonomy($r){$ra=$r; $rx=[]; $rt=[];
foreach($r as $k=>$v){
	if(is_array($v))$rt[$k]=taxo_find($v,$ra,$rx);
	else $rt[$k]=$v;}
taxo_clean($rt,$rx);
return $rt;}

//[1=>0,2=>1,3=>2,4=>0]
function taxo($r){$rb=[];
foreach($r as $k=>$v)$rb[$v][$k]=1;
return taxonomy($rb);}

//from [$ra,$rb]=detect_headings($d); $r=taxonomy($ra);
function play_taxo($r,$rb,$i=1,$rt=[]){
foreach($r as $k=>$v){
$rt[$k]=tag('h'.$i,'',$rb[$k]).n();
if(is_array($v))$rt=play_taxo($v,$rb,$i+1,$rt);}
return $rt;}
//ksort($rt); $ret=join('',$rt);//reorder

//from build_headings($r);
function detect_headings($d){
$dom=dom($d); $r=[]; $rb=[]; $n=1; $i=0;
foreach($dom->firstChild->firstChild->childNodes as $k=>$v){$nod=$v->nodeName;
if(substr($nod,0,1)=='h'){$i++;
	$b=substr($nod,1,1); $rb[$i]=$v->textContent;
	$r[$b-1][$i]=1; $n=$b;}}
return [$r,$rb];}

//$r=['one'=>['two','three'=>['four'],'five'],'six'=>['seven'=>['height']]];
function build_headings($r,$i=1){$ret='';
foreach($r as $k=>$v)
if(is_array($v)){$ret.=tag('h'.$i,'',$k).n();
$ret.=build_headings($v,$i+1);}
else{$ret.=tag('h'.$i,'',$v).n();}
return $ret;}

#time
function readtime($d,$o=0){$n=round($d/1200); $b='';
if($n>60){$b=round($n/60).'h '; $n=$n%60;}
if($n>1)$b.=str_pad(round($n),2,'0',STR_PAD_LEFT).' min ';
return ico('clock-o','','bton',$b,lang('time_reading'));}
function numday($d=''){return date('ymd',$d);}
function numday2time($d){if(is_numeric($d) && strlen($d)==6)$d='20'.$d;
return is_numeric($d)?$d:strtotime($d);}
function isnew($d,$t=30){if($d<strtotime('-'.$t.' minutes'))return 1;}

function relativetime($sec){
$ret=lang('there_was').' '; $time=ses('time')-$sec;
if($time>86400*30)$ret=date('d/m/Y',$sec);
elseif($time>86400)$ret=date('d/m/Y',$sec);
elseif($time>3600)$ret.=floor($time/3600).'h ';
elseif($time>60)$ret.=floor($time/60).'min ';
else $ret.=$time.'s';
return span($ret,'small');}

function relativetime_full($sec){
$ret=lang('there_was').' '; $sec=time()-$sec;
if($sec>84600*365){$n=floor($sec/84600/365); $nm='year';}
if($sec>84600*30){$n=floor($sec/84600/30); $nm='month';}
elseif($sec>84600){$n=floor($sec/84600); $nm='day';}
elseif($sec>3600){$n=floor($sec/3600); $nm='hour';}
elseif($sec>60){$n=floor($sec/60); $nm='minute';}
else $nm='second';
return $ret.$n.' '.langs($nm,$n,1);}

function calendar($p){//old
$d=$p['day']??''; $fc=$p['fc']??''; $rid=randid();
return div(build::calendar($d,$fc),'',$rid);}

#pop
function alert($d){
head::add('jscode','ajx("popup|core,txt|txt='.$d.'");');}

?>