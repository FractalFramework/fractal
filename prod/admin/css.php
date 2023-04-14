<?php

class css{	
static $private=6;
static $db='css';
static $a='css';
static $cb='css';

static function install(){
sql::create(self::$db,['tit'=>'svar','css'=>'json'],1);}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){}

static function editor(){
$ret=textarea('csd','',64,10);
return $ret;}

//associate
static function pictos($p){$ret='';
$ra=self::build(['css'=>'fa']);
$rb=self::build(['css'=>'pictos']); //pr($rb);
$ra=self::nm($ra,'.fa-'); //pr($ra);
$rb=self::nm($rb,'.ic-'); //pr($rb);
$r=array_diff($ra,$rb); //pr($r);
echo count($r);
return $ret;}

static function nm($r,$a){//.fa-th-large:before
foreach($r as $k=>$v){$d=between($v[0],$a,':before'); if($d)$ret[]=$d;}
return $ret;}

#edit
static function save($p){$ret=''; $css=$p['css'];
$r=self::build($p); $k=$p['k']; $rb=$r[$k][2];
$r[$k][1]=$p['css'.$k];
foreach($r as $k=>$v)$ret.=$v[0].'{'.$v[1].'}'.n(); //eco($ret);
$d=file_put_contents(ses('dev').'/css/'.$css.'.css',$ret);
return self::edit($p);}

static function edit($p){$ret=''; $css=$p['css'];
$r=self::build($p); $k=$p['k']; $rb=$r[$k][2]; //pr($rb);
$ret=bj('cscb'.$k.'|css,save|css='.$css.',k='.$k.'|css'.$k,langp('save'),'btn').br();
$ret.=textarea('css'.$k,$r[$k][1],82,6);
return div($ret,'','cscb'.$k);}

static function stream($r,$css){$ret=''; //pr($r);
foreach($r as $k=>$v)$ret.=toggle('|css,edit|css='.$css.',k='.$k,$v[0]);
return div($ret,'nbp');}

#build
static function build($p){$css=$p['css'];
$d=file_get_contents(ses('dev').'/css/'.$css.'.css');
$n=strlen($d);
for($i=0;$i<$n;$i++){}
$r=explode('}',"\n".$d); //pr($r);
foreach($r as $k=>$v){
	$tit=between($v,"\n",'{'); $tit=exclude($tit,'/*','*/');
	$txt=strfrom($v,'{'); $txt=exclude($txt,'/*','*/'); $txt=deln($txt);
	$rb=explode_k($txt,';',':');
	$ret[]=[trim($tit),trim($txt),$rb];}
	//sql::del(self::$db,$css,'css');
	//sql::sav2(self::$db,$ret);
	//$r=sql('all',self::$db,'ra',$id);
return $ret;}

#read
static function call($p){
$css=$p['css'];
$r=self::build($p);
$ret=self::stream($r,$css);
return $ret;}

static function com(){
return self::content($p);}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$r=['global','apps','fa','pictos'];
$ret=batch($r,self::$a.'|css,call|css=$v');
$ret.=bj(self::$cb.'|css,editor|',langp('edit'),'btn');
return div($ret,'sticky').div('','pane',self::$cb);}
}
?>