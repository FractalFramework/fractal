<?php
class web{
static $db='tlex_web';
static $cols=['url','tit','txt','img'];
static $typs=['var','var','var','var'];

static function install(){$n=0;
//appx::install(array_combine(self::$cols,self::$typs));
sql::create(self::$db,array_combine(self::$cols,self::$typs),$n);}

static function metas($f,$d=''){
if(!$d)$d=get_file($f); if(!$d)return; $dom=dom($d);//eco($d); 
//$dom=get_dom($f,0); //pr($dom);
$ti=dom::extract($dom,'::h1');
if(!$ti)$ti=dom::extract($dom,'title:property:meta');
if(!$ti)$ti=dom::extract($dom,'og(ddot)title:property:meta');
if(!$ti)$ti=dom::extract($dom,'name:itemprop:meta');
if(!$ti)$ti=dom::extract($dom,'::title');
if(!$ti)$ti=dom::extract($dom,'::h1');
$tx=dom::extract($dom,'description:name:meta'); //$x=''; if($tx)$x.=1;
if(!$tx)$tx=dom::extract($dom,'og(ddot)description:property:meta');
if(!$tx)$tx=dom::extract($dom,'description:itemprop:meta');
//if(!$tx)$tx=between($d,'"description":"','",');//bad res
$im=dom::extract($dom,'image:name:meta');
if(!$im)$im=dom::extract($dom,'og(ddot)image:property:meta');
if(!$im)$im=dom::extract($dom,'og(ddot)image:itemprop:meta');
if(!$im)$im=dom::extract($dom,'thumbnailUrl:itemprop:link:href');
if(strpos($f,'youtube.com')!==false)$im='https://img.youtube.com/vi/'.strend($f,'=').'/hqdefault.jpg';
return [$ti,$tx,$im];}

static function build($d,$x=''){
$r=sql('tit,txt,img,id','tlex_web','rw','where url="'.$d.'"');
if(!$r or $x){$ra=$r;// or !$r[0]
	if(strpos($d,'newsnet.fr')!==false)$r=vacuum::com(http($d),1);
	else $r=self::metas($d); //pr($r); echo $d;
	if(!$r[0])$r=self::kit($d)??$r;
	//if(!$r[0])$r=self::headers($d);
	if($r[1])$r[1]=str_replace(['“','”'],'',$r[1]);//html_entity_decode
	if($r[2])$r[2]=saveimg($r[2],'web','590','400');
	$r=sql::vrf(array_combine(['tit','txt','img'],$r),'tlex_web'); $r=array_values($r);
	//if($ra)pr($ra);
	if($ra)sql::up2('tlex_web',['tit'=>trim($r[0]),'txt'=>trim($r[1]),'img'=>$r[2]],$ra[3]);
	elseif($r[0])sql::sav('tlex_web',[$d,trim($r[0]),trim($r[1]),$r[2]]);}
return $r?$r:['','',''];}

static function kit($f){
if(substr($f,0,7)=='youtube')$f=strend($f,'=');
$u='http://newsnet.ovh/call/yt,build/'.str_replace('/','|',$f);
//if(auth(6))echo $u.' ';
$d=file_get_contents($u);
$r=json_decode($d,true);
$r[0]=!empty($r[0])?etc($r[0]):'';
$r[1]=!empty($r[1])?etc($r[1]):'';
$r[2]=!empty($r[2])?etc($r[2]):'';
return $r;}

static function headers($u){
$r=@get_headers(http($u)); pr($r);
$ti='';
$tx='';
$im='';
return [$ti,$tx,$im];}

static function playnet($p){
$ret=vacuum::play($p);//$p['url']
return div($ret,'article');}

static function redit($p){$u=$p['u'];
$ret=bj('re'.$p['rid'].'|web,call|u='.$u.',x=1',langp('refresh'),'btn');
$id=sql('id','tlex_web','v',['url'=>$u]);
if($id)$ret.=admin_sql::call(['b'=>'tlex_web','id'=>$id]);
return $ret;}

static function play($d,$x=''){$rid=randid();
$ua=http($d); $ub=nohttp($d); $r=self::build($ub,$x); $bt='';
$dom=domain($ua); $t=$r[0]?$r[0]:$dom;
if(!$r)return lk($ua,$t,'btlk',1);;
if(substr($r[2],0,4)=='http')$f=$r[2];
elseif($r[2])$f=img2($r[2],'mini',1); else $f='';//imgroot($r[2],'')
if(is_file($f))$imx=getimagesize($f); else $imx=[];
//if(substr($r[2],0,4)=='http'){if($imx[0]>590)$img=img($r[2],'590'); else $img='';}
if($imx){
	//if($imx[0]>590)$img=img('/'.imgroot($r[2],'medium'));//img('/'.$f,'590');else
	if($imx[0])$img=img('/'.imgroot($r[2],'mini'),100,100,'artim');} else $img='';
$j='web,playnet|url='.$ub;
if($img){
	if(substr($r[2],0,4)=='http')$r[2]=nodomain($r[2]);
	$im=imgroot($r[2],'full'); $ban=imgup($im,$img,'');}
else $ban=$img;
//$tit=lk($ua,$t,'btit');
if(strpos($ua,'newsnet.fr')!==false)$pic=ico('nn',32);
elseif(strpos($ua,'oumo.fr')!==false)$pic=picto('oomo');
else $pic=ico('share-square-o');
if(conn::$opn)$tit=bj('pagup|'.$j,span($pic.' '.$t,'apptit'),'appicon');
else $tit=toggle($rid.',,z|'.$j,span($pic.' '.$t,'apptit'),'appicon');
$url=lk($ua,pic('url').$dom,'btxt',1);
//$bt=toggle($rid.',,z|'.$j,langp('read'),'btxt');//if($r[2])
if(auth(6))$bt=' '.popup('web,redit|rid='.$rid.',u='.$ub,pic('redit'),'');
$txt=($r[1]);//utf8_encode
$ret=$tit.div($txt,'stxt').div($url.$bt,'').div('','',$rid).div('','clear');
return div($ban.div($ret,'pncxt'),'panec','re'.$rid);}

static function tit($p){
return sql('tit','tlex_web','v','where url="'.$p['id'].'"');}

static function api($p){
$u='youtube.com/watch?v='.$p['p1'];
$r=self::build($u);
return json_encode($r,true);}

static function call($p){
$d=$p['u']??($p['p1']??'');
return self::play($d,$p['x']??'');}

static function content($p){
$u=$p['p1']??''; $ret='';
$j='wb|web,call||u';
$bt=inputcall($j,'u',$u,32);
$bt.=bj($j,langp('ok'),'btn');
if($u)$ret=self::call($p);
return $bt.div($ret,'pane','wb');}

}
?>