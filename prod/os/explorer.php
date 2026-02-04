<?php
class explorer{
static $private=2;
//f:short url without extension
//u:full url

static function js(){return '';}
static function ico($f,$o=''){$k=ext($f);
$r=['.jpg'=>'image','.png'=>'image','.gif'=>'image','.php'=>'file-code-o','.xls'=>'file-excel-o','.txt'=>'file-word-o','.pdf'=>'file-pdf-o','.odt'=>'file-word-o','.mp3'=>'file-audio-o','.mp4'=>'file-video-o','.gz'=>'file-archive-o','.json'=>'file-code-o','html'=>'file-word-o','xhtml'=>'file-word-o','xml'=>'file-word-o',''=>'file'];
if(!$f)$ret='folder'; elseif(isset($r[$k]))$ret=$r[$k]; else $ret='file';
return ico($ret,$o);}

static function a($f){$xt=ext($f); if($xt=='.json')return 'json'; else return 'db';}
static function read($f){$a=self::a($f); return $a::read($f);}
static function save($f,$r){$a=self::a($f); return $a::save($f,$r);}

static function nod($app,$id){
$usr=sql::inner('name',$app::$db,'login','uid','v',$id);
return 'usr/'.$usr.'/'.$app.'/'.$id;}

#editable
//cell
static function sav_cell($p){
$f=$p['f']??''; $id=$p['id']; [$k,$kb]=explode('-',$id); $kb-=1;//fix
$v=$p['d'.$id]; $v=trim(str_replace('<br>','',$v));
$r=self::read($f); if(isset($r[$k][$kb]))$r[$k][$kb]=$v; if(!$f)self::save($f,$r);
return $v;}

//row
static function sav_row($p){
$f=$p['f']; $ka=$p['k']??0; $kb=$p['kb']??''; $n=$p['n']; $rid=$p['rid'];
$r=self::read($f); if(isset($r[$ka]))$rk=$r[$ka]; else{$ka=self::nextk($r); $rk=current($r);}
if($kb!=$ka){unset($r[$ka]); $ka=$kb;}
if(isset($rk))foreach($rk as $k=>$v)$r[$ka][$k]=trim(stripslashes(str_replace('<br>','',$p[$rid.$k])));
self::save($f,$r);
return self::nav($p);}

static function edit_row($p){$f=$p['f']; $ka=$p['k']; $pg=$p['pg']??'';
$r=self::read($f); $ra=current($r); $n=count($ra); $ri=$r[$ka]??''; $rid=randid(); $i=0;
for($i=0;$i<$n;$i++)$rb[]=$rid.$i; $prm='kb'; if($n)$prm.=','.implode(',',$rb);
$bt=bj('fcb,,x|explorer,sav_row|f='.$f.',k='.$ka.',n='.$n.',pg='.$pg.',rid='.$rid.'|'.$prm,langp('save'),'btsav');
$bt.=bj('fcb,,x|explorer,opsav|op=del_row,f='.$f.',pg='.$pg.',nm='.$ka,langp('delete'),'btdel');
$bt.=bj('fcb,,x|explorer,opsav|op=dpl_row,f='.$f.',pg='.$pg.',nm='.$ka,langp('duplicate'),'btk');
$rk=array_keys($r); if(isset($r['_']))array_shift($rk); $bt.=select('mv',$rk,$ka,1);
$bt.=bj('fcb,,x|explorer,opsav|op=mov_row,f='.$f.',pg='.$pg.',nm='.$ka.'|mv',langp('displace'),'btk');
$ret[]=['key',input('kb',$ka,4)];
if($ra)foreach($ra as $k=>$v)$ret[]=[$v,goodinput($rb[$k],$ri[$k]??'')];
return $bt.tabler($ret);}

#datas
static function reset_header($r){
$k=key($r); $rk['_']=$r[$k]; unset($r[$k]);
return $rk+$r;}

static function nextk($r){static $i; $i++;
if(!isset($r[$i]))return $i; else return self::nextk($r);}

static function del_head($r){
foreach($r as $k=>$v){if($k=='_')$k=self::nextk($r); $rb[$k]=$v;} $r=$rb;
return $r;}

static function del_col($r,$n){
if($n=='_'){$rb=[]; foreach($r as $k=>$v){$k=array_shift($v); if($k)$rb[$k]=$v;} $r=$rb;}
else foreach($r as $k=>$v)unset($r[$k][$n-1]);//friendly
return $r;}

static function add_key($r){$rb=[]; $i=1;
foreach($r as $k=>$v){if($k)$rb[$i]=array_merge([$k],$v); $i++;}
return $rb;}

static function mkrow($r,$o=''){
$rb=$r?current($r):[]; $n=count($rb); if($n==0)$ret[]=''; $kn=self::nextk($r);
for($i=1;$i<=$n;$i++)if($o=='_')$ret['_'][]=''; elseif($o)$ret[]=$r[$o][$i-1]; else $ret[]='';
if($o=='_'){$rb=$ret+$r; $r=$rb;} 
elseif($o){foreach($r as $k=>$v){$rc[$k]=$v; if($k==$o)$rc[$kn]=$ret;} $r=$rc;}
else $r[$kn]=$ret;
return $r;}

static function mvrow($r,$ka,$kb){$rb=[]; if($ka==$kb)return $r; $va=$r[$ka]; unset($r[$ka]);
foreach($r as $k=>$v){if($k==$kb)$rb[$ka]=$va; $rb[$k]=$v;}
return $rb;}

#files
static function del($p){$f=$p['f']; $ok=$p['ok']??'';
if(!$ok)return bj('fcb|explorer,del|ok=1,f='.$f,langp('confirm deleting'),'btdel');
$u='disk/'.$f; if(is_dir($u))rmdir($u);//rmdir_r($u);//if(self::secu($u))
elseif(is_file($u)){unlink($u);}//$ub=self::burl($f); if(is_file($ub))unlink($ub);
return self::nav(['f'=>$f]);}

static function trunc($p){$f=$p['f']; $ok=$p['ok']??'';
if(!$ok)return bj('fcb|explorer,trunc|ok=1,f='.$f,langp('confirm trunc'),'btdel');
$r=self::read($f); $u='disk/'.$f; if($r)self::save($f,['_'=>array_shift($r)]);
return self::nav(['f'=>$f]);}

//edit
static function edit($f,$d,$m){
$ret=bj('fcb|explorer,opsav|op='.$m.',f='.$f.'|nm',langp('save'),'btsav');
$ret.=div(textarea('nm',$d,84,24,'','console'));
return div($ret,'');}

static function editxt($p){
$f=$p['f']; $u=self::furl($f); $d=read_file($u);
return self::edit($f,$d,'editxt');}

static function editcsv($p){
$f=$p['f']; $r=self::read($f); $d=implode_r($r,"\n",',');
return self::edit($f,$d,'editcsv');}

static function editjson($p){
$f=$p['f']; $r=self::read($f); $d=json_encode($r);
return self::edit($f,$d,'editjson');}

//dirs
static function add_dir($f){
$u='disk/'.$f; if(!is_dir($u))mkdir_r($u);}

//rename
static function rename_sav($f,$nm,$o=''){
if(strpos($nm,'.')===false && !$o)$nm.='.php';
$f='disk/'.$f; $fb='disk/'.$nm;
if(!is_dir($fb))mkdir_r($fb); if($fb!=$f)rename($f,$fb);
return $nm;}

//duplicate
static function duplicate($f,$nm){
if(strpos($nm,'.')===false)$nm.='.php';
$f=self::furl($f); $fb=self::furl($nm);
if(!is_dir($fb))mkdir_r($fb); if($fb!=$f)copy($f,$fb);
return $nm;}

static function reorder_col($r,$n){
if(isset($r['_'])){$rb['_']=$r['_']; unset($r['_']);}
if($n=='_')$rk=array_keys($r); else $rk=array_keys_r($r,$n); asort($rk);
foreach($rk as $k=>$v)if($n=='_')$rb[$v]=$r[$v]; else $rb[$k]=$r[$k];
return $rb;}

static function renumber($r){
if(isset($r['_'])){$rb['_']=$r['_']; unset($r['_']);} $i=0;
if($r)foreach($r as $k=>$v){$i++; $rb[$i]=$v;}
return $rb;}

//txt/html
static function txt($f){$u=self::furl($f);
return file_get_contents($u);}
//csv
static function csv2db($f){$d=self::txt($f); $u=self::furl($f);
if(strpos($d,"\t")!==false)$s="\t"; elseif(strpos($d,'|')!==false)$s='|'; else $s=';';
return readcsv($u,$s);}
//json
static function json2db($u){
$u=strpos($u,'http')!==false?$u:host(1).'/api/db/f:'.$u;
$d=file_get_contents($u); return json_decode($d,true);}

//repair
static function repair($r){
$er=self::errors($r); $ret=[];
if($er=='no')return [1=>['col1']];
if($er=='reord')return db::reorder($r);
if($er=='reset')return self::reset_header($r);
if($er)foreach($r as $k=>$v)$ret[$k]=array_pad($v,$er,''); return $ret;}

static function errors($r){if(!is_array($r))return;
if(isset($r[0]))return 'reord'; if(isset($r['_k']))return 'reset';
if($r)$na=count(current($r)); $nb=0; $er='';
if($r)foreach($r as $k=>$v){$n=count($v); if($n!=$na)$er=1; $nb=$n>$nb?$n:$nb;}
if($er)return $nb;}

//select (for tabler)
static function select($p){
$a=$p['a']; $tg=$p['tg']; $f=$p['f']; $u=self::url($f,1); $u=self::noxt($u);
$r=scan_dir('disk/'.$u); $bck=strpos($u,'/')?struntil($u,'/'):'';
$rb[]=bj('slctdb|explorer,select|a='.$a.',tg='.$tg.',f='.$bck,langpi('back').strend($bck,'/'),'grey');
if($r)foreach($r as $k=>$v){$vb=($u?$u.'/':'').struntil($v,'.'); $bt=ico('folder').$v;
	if(!is_numeric($k))$rb[]=bj('slctdb|explorer,select|a='.$a.',tg='.$tg.',f='.$vb.'.php',$bt,'');
	else $rb[]=bj($tg.'|explorer,readb|f='.$vb,ico('file-o').$v,'');}
if(isset($rb))$ret=implode('',$rb);
return div($ret,'list');}

static function readb($p){$u=self::url($p['f']); 
return tabler(db::read($u));}

//displace
static function displace($p){
$f=$p['f']; $nm=$p['nm']??'';
if(strpos($f,'.'))$fa=struntil($f,'/'); if(!$nm or $nm=='usr')$nm=$fa;
if(strpos($nm,'/'))$bck=struntil($nm,'/'); else $bck=$nm;
$u=self::furl($nm,1); $r=scan_dir($u);
if($f)$rb[]=bj('ftedit|explorer,displace|f='.$f.',nm='.$bck,div(langpi('back').strfrom($bck,'/')),'grey');
$rb[]=bj('fcb|explorer,opsav|op=rename_file,f='.$f.',nm='.$nm.'/'.strend($f,'/'),langp('save in').' '.strfrom($nm,'/'),'bicon');
if($r)foreach($r as $k=>$v)if(!is_numeric($k))$rb[]=bj('ftedit|explorer,displace|f='.$f.',nm='.$nm.'/'.$v,div(ico('folder').$v));
if(isset($rb))$ret=implode('',$rb);
return div($ret,'paneb cicon');}

//import
static function khead($r){
$rb=array_shift($r); $k=key($rb); //pr($rb);echo $k;
if(!is_numeric($k) or $k==0){$rb['_']=array_keys($rb); $rb[1]=array_values($rb);}
foreach($r as $k=>$v)$rb[]=$v;
return $rb;}

//swap
static function swap($r,$d){
[$a,$b]=explode('-',$d); //$a-=1; $b-=1;
foreach($r as $k=>$rb)foreach($rb as $kb=>$vb){
if($kb==$a)$rc[$k][$kb]=$rb[$b]; elseif($kb==$b)$rc[$k][$kb]=$rb[$a]; else $rc[$k][$kb]=$vb;}
return $rc;}

static function noxt($f){
return substr($f,-4)=='.php'?struntil($f,'.'):$f;}

//operations
static function opsav($p){
$f=$p['f']??''; $op=$p['op']??''; $nm=trim($p['nm']??''); $no=0; $xt=ext($f); $r=[];
if($xt && strpos('.mp3.csv.jpg.png.gif.gz.mp4.xhtml',$xt)===false)$r=self::read($f);
switch($op){
case('add_head'):$r=self::mkrow($r,'_'); break;
case('del_head'):$r=self::del_head($r); break;
case('add_col'):foreach($r as $k=>$v)$r[$k][]=''; break;
case('del_col'):$r=self::del_col($r,$nm); break;
case('add_key'):$r=self::add_key($r); break;
case('add_row'):$r=self::mkrow($r); break;
case('dpl_row'):$r=self::mkrow($r,$nm); break;
case('mov_row'):$r=self::mvrow($r,$nm,$p['mv']); break;
case('del_row'):if(isset($r[$nm]))unset($r[$nm]); break;
case('reorder'):$r=self::reorder_col($r,$nm); break;
case('renumber'):$r=self::renumber($r); break;
case('repair'):$r=self::repair($r); break;
case('rename_file'):$p['f']=self::rename_sav($f,$nm); $no=1; break;
case('rename_dir'):$p['f']=self::rename_sav($f,$nm,1); $no=1; break;
case('duplicate'):$p['f']=self::duplicate($f,$nm); $no=1; break;
case('add_file'):$r=[1=>['col1']]; $f=$nm.'.php'; $p['f']=$f; break;
case('add_dir'):self::add_dir($nm); $p['f']=$nm; $no=1; break;
case('export'):$r=self::read($f); $f=$nm; $p['f']=$f; break;
case('import_table'):$fa=self::noxt($nm,1); $r=self::read($fa); $f=self::noxt($f,1); break;
case('import_sql'):if($nm!='login')$r=sql('all',$nm,'rr',''); array_unshift($r,sql::cols($nm,3,2)); break;
case('import_conn'):$r=explode_r($nm,"\n",'|'); break;
case('import_html'):$d=tabler::trans($nm); $r=explode_r($d,"\n",'|'); break;
case('import_csv'):$r=self::csv2db($nm); break;
case('import_json'):$r=self::json2db($nm); break;
case('backup'):$u=self::furl($f); $ub=self::burl($f); mkdir_r($ub); copy($u,$ub); $no=1; break;
case('rollback'):$r=self::read('_bak/'.$f); break;
case('reset_header'):$r=self::reset_header($r); break;
case('request'):$ra=explode(';',$nm); $r=db::rq($ra[0],$f,$ra[1]); $no=1; break;
//case('trunc'):$rh['_']=array_shift($r); $r=$rh; break;
case('swap'):$r=self::swap($r,$nm); break;
case('editxt'):$u=self::furl($f); if(auth(6))write_file($u,$nm); $no=1; break;
case('editcsv'):$r=explode_r($nm,"\n",','); break;
case('editjson'):$r=json_decode($nm,true); break;}
if($r && !$no)self::save($f,$r);
return div(self::nav($p),'','fcb');}

static function opedt($p){
$f=$p['f']; $op=$p['op']; $x=$p['x']??''; $d=''; $inp=''; $opt='';
switch($op){
case('del_col'):$d='_'; break;
case('editxt'):return self::editxt($p); break;
case('editcsv'):return self::editcsv($p); break;
case('editjson'):return self::editjson($p); break;
case('displace'):return self::displace($p); break;
case('trunc'):return self::trunc($p); break;
case('del_file'):return self::del($p); break;
case('del_dir'):return self::del($p); break;
case('add_file'):$d=$f.'/'.lang('file',1); break;
case('add_dir'):$d=$f.'/'.lang('folder',1); $no=1; break;
case('reorder'):$d='_'; break;
case('import_sql'):$d=''; break;
case('import_conn'):$d=db::call($p); $inp=textarea('nm',tabler::trans($d),54,8,'','console'); break;
case('import_html'):$inp=divarea('nm',db::call($p),'article pane'); break;
case('import_csv'):$opt=upload::call('nm'); $d=''; break;
case('import_json'):$d=$f; break;
case('request'):$d='0,1;0=login,3=fr'; break;
case('swap'):$d='0-2'; break;
default:$d=self::noxt($f);}
$j='fcb|explorer,opsav|op='.$op.',x='.$x.',f='.$f.'|nm';
$ret=$inp?$inp:inputcall($j,'nm',$d,'44',$op,$op);
$ret.=bj($j,pic('ok'),'btsav').$opt;
$ret.=bj('ftedit|explorer,null|',langpi('cancel'),'btn');
$ret.=hlpbt($op);
return $ret;}

#actions
static function imtool($p){$f=$p['f']; $ret='';
$r=['import_table','import_html','import_conn','import_csv','import_json']; if(auth(6))$r[]='import_sql';
foreach($r as $k=>$v)$ret.=toggle('ftedit|explorer,opedt|op='.$v.',f='.$f,langph($v),'');
return div($ret,'nbp');}

static function dtool($f){$ret='';
if(strpos($f,'.')===false)$r=['add_dir','add_file','rename_dir','del_dir'];
else $r=['rename_file','del_file'];//used for non-php files
foreach($r as $k=>$v)$ret.=toggle('dtedit|explorer,opedt|op='.$v.',f='.$f,langpi($v),'');
return div($ret,'nbp').div('','','dtedit');}

static function ftool($f,$ra,$x=''){$ret=''; $c=''; //$xt=ext($f);
$er=self::errors($ra); //if(!is_array($ra))return;
$r[]='backup'; if(is_file(self::burl($f)))$r[]='rollback';
if(isset($ra['_']))$r[]='del_head'; else{$r[]='add_head'; $r[]='reset_header';}
array_push($r,'add_row','add_col','add_key','renumber');
if($er){$r=['repair']; $c='del';}
foreach($r as $k=>$v)$ret.=bj('fcb|explorer,opsav|op='.$v.',x='.$x.',f='.$f,langp($v),$c);
$r=['reorder','del_col','swap','editxt','editcsv','editjson','duplicate','displace','rename_file','trunc','del_file'];
if(!$er)foreach($r as $k=>$v)$ret.=toggle('ftedit|explorer,opedt|op='.$v.',x='.$x.',f='.$f,langp($v),$c);
$ret.=toggle('ftedit|explorer,imtool|f='.$f,langp('import'),'');
$ret.=lk('/api/db/f:'.substr($f,0,-4),langpi('api'),'',1);
$ret.=lk('/explorer/'.$f,pic('url'),'',1);
return div($ret.hlpbt('tabler_edit','',''),'nbp sticky').div('','','ftedit');}

#table
static function reader($f,$u,$d){
$ret=''; $xt=ext($u);
if($xt=='.php'){
	//if(!is_array($d))$d=db::read(self::burl($f));//restore
	if(is_array($d))$ret=build::editable($d,'explorer',['f'=>$f],1);//edit_row
	else $ret=self::editxt(['f'=>$f]);}
elseif($xt=='.json'){
	if(fsize($u)>10000)$ret=lk($f,ico('file-code-o').$f,'',1);
	else $ret=tree(json_decode($d,1));}
elseif($xt=='.jpg' or $xt=='.png' or $xt=='.gif' or $xt=='.webp')$ret=img('/'.$u);
elseif($xt=='.mp3' or $xt=='.mid')$ret=audio($u);
elseif($xt=='.mp4')$ret=video($u);
elseif($xt=='.txt')$ret=nl2br(self::txt($f));
elseif($xt=='.html')$ret=nl2br(self::txt($f));
elseif($xt=='.svg')$ret=svg::call(['code'=>$d]);
elseif($xt=='.gz')$ret=lk($f,ico('file-archive-o').$f,'',1);
elseif($xt=='.csv')$ret=lk($f,ico('file-excel-o').$f,'',1);
elseif($xt=='.xhtml')$ret=lk($f,ico('file-excel-o').$f,'',1);
else $ret=div($d,'pane scroll');
return div($ret,'board');}

static function editable($f){$d=self::read($f);
return build::editable($d,'explorer',['f'=>$f],1);}

#play
static function play($p){
$f=$p['f']; $ret=''; $d='';
$u=self::furl($f); $xt=ext($u);
if(!is_file_b($u))return 'no_file';
if(strpos('.txt.mp3.csv.jpg.png.gif.gz.mp4.html.xhtml.odt',$xt)===false && fsize($u)<10000){
	$u=self::furl($f); $d=read_file($u); if(strpos($d,'/db/'))$d=self::read($f);}
if($xt=='.php')$ret.=self::ftool($f,$d,$p['x']??''); else $ret.=self::dtool($f);
$bt=self::ico($f); //$ub=$f; $lk=lk('/explorer/'.$f,$ub,'',1);
$bt.=span(self::noxt($f),'btxt').'- ';
$bt.=span(fsize($u,1),'small').' - ';
if(is_array($d))$bt.=span(count($d).' '.lang('entries',1),'small').' - ';
$bt.=span(fdate($u,'Y-m-d H:i'),'small').' - ';
$ret.=div($bt,'stit');
$ret.=self::reader($f,$u,$d);
return $ret;}

static function replay($p){
$f=$p['f']; $u=self::furl($f);
if(!is_file_b($u))return; $r=self::read($f);
return build::editable($r,'explorer',$p,1);}

#nav
static function navf($ra,$rb,$ka,$fi){
$dr=array_shift($rb);//retire usr //if($dr=='usr')$dr='';
$fa=$dr.'/'.implode_b('/',$rb); $u=self::url($fa); $ret='';
$r=scan_dir('disk/'.$u); 
$ac=$ra[$ka+1]??''; //if($fi)$fa.='/'.$fi;
if($rb && $r)foreach($r as $k=>$v){$c=$ac==$v?'active':'';//if($k!='_bak')
	if(is_numeric($k))$rf[]=bj('fcb,,z|explorer,nav|f='.$fa.'/'.$v,self::ico($v,16).struntil($v,'.'),$c);
	else $rd[]=bj('fcb|explorer,nav|f='.$fa.'/'.$k.',fi='.$fi,ico('folder-o',16).$k,$c);}
if(isset($rd))$ret.=implode('',$rd);
if(isset($rf))$ret.=implode('',$rf);
return div($ret,'lisb');}

static function nav($p){
$f=$p['f']; $x=$p['x']??''; $fi=$p['fi']??'';
if(strpos($f,'.')===false)$ret=self::dtool($f); else {$ret=''; $fi=strend($f,'/');}
$r=explode('/',$f); //p($r);
if(!$x)foreach($r as $k=>$v){$rb[]=$v; $ret.=div(self::navf($r,$rb,$k,$fi));}
//$ret.=div('','','edit');
if(strpos($f,'.')!==false)$ret.=div(self::play($p),'margtop');
return $ret;}

static function navdr($f){
$usr=ses('usr'); $r=explode('/',$f); $ret='';
$dr0=['usr','db','json']; if(auth(6))$dr0[]='_';
if($r[0]=='usr')$dr1=sql('name','login','rv','where mail="'.ses('mail').'" and auth>1 order by name');
else $dr1=scan_dir('disk/'.$r[0]);
if(auth(6))$ret=div(batch($dr0,'drcb|explorer|f=$v',$r[0]),'');
if($r[1])$ret.=div(batch($dr1,'drcb|explorer|f='.$r[0].'/$v',$r[1]),'');
return $ret;}

#f
static function secu($r){$usr=ses('usr');
if($r[0]=='.')$r[0]='usr';
$dr0=['usr','db','json']; if(auth(6))$dr0[]='_';
if(!in_array($r[0],$dr0))$r[0]='usr';
if($r[0]=='usr'){
	$dr=sql('name','login','rv','where mail="'.ses('mail').'" and auth>1 order by name');
	$is=in_array($r[1],$dr)?1:0;
	if($r[1]!=$usr && !$is)$r[1]=$usr;}// && !auth(6)
return $r;}

static function url($f,$o=''){$usr=ses('usr');
if(substr($f,0,1)=='/')$f='usr/'.$f;//used by appx
$r=explode('/',$f); if(empty($r[1]))$r[1]=$usr;
if(strpos($f,'.csv') && count($r)==2)return 'usr/'.$usr.'/'.$f;
if($o)$r=self::secu($r);
return implode_b('/',$r);}

static function furl($f,$o=''){
$f=self::url($f,1); $dr='disk/';
if(strpos($f,'.')===false && !$o)$f.='.php';
return $dr.$f;}

static function burl($f){
$f=self::url($f,1); $dr='disk/_bak/';
if(strpos($f,'.')===false)$f.='.php';
return $dr.$f;}

//f=usr/dav/root/table
//f=/dav/root/table
static function content($p){
$f=$p['f']??'usr'; $f=self::url($f,1);
$bt=self::navdr($f); $ret=self::nav(['f'=>$f]);
return div($bt.div($ret,'pan','fcb'),'','drcb');}

}
?>