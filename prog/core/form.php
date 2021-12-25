<?php
class form{

static function ex(){return ['input'=>'label§title','textarea'=>'label2§text','select'=>'choice§a/b/c','checkbox'=>'options§a/b','radio'=>'choose one§a/b','bar'=>'evaluation§1-10','submit'=>'form0§'.ses('user')];}

static function usave($p){//from conn
$u=$p['u']; $b=$p['b']; $h=$p['h']; unset($p['u']); unset($p['b']); unset($p['h']);
$f='usr/'.($u?$u:'public').'/forms/free/'.$b; $rh=pushr(['date','uid'],explode('-',$h));
$r=db::read($f,1); $bt=$u==ses('user')?db::bt($f):''; $ex=in_array_n(ses('uid'),$r,1);
if($ex)return help('answered').tabler($r[$ex],'',1).$bt;
else db::add($f,[date('ymd:Hi'),ses('uid')]+$p,$rh); 
return help('form_filled').$bt;}

static function build($r){$rt=[]; //$rk=array_keys(uns($r,0));
foreach($r as $k=>$v){$val=$v['value']??''; $d=''; $k=normalize($k,1); $lbl=$v['label']??'';
	if($lbl)$rt[$k]['label']=label($k,$lbl); $o=$v['opt']??''; $dv=$v['div']??'';
	switch($v['type']){
		case('input'):$d=input($k,$val,20,$lbl,$o); break;
		case('inputnb'):$d=input($k,$val,8,$lbl,$o,1); break;
		case('inputcall'):$d=inputcall($o,$k,$val,32,$lbl); break;
		case('textarea'):$d=textarea($k,$val,40,10,$lbl,$o); break;
		case('select'):$d=select($k,$o,$val,1); break;
		case('checkbox'):$d=checkbox($k,$o,$val,1); break;
		case('radio'):$d=radio($k,$o,$val,1); break;
		case('hidden'):$d=hidden($k,$val); break;
		case('bar'):$d=bar($k,$val); break;
		case('submit'):$d=bj($val,langp($lbl),'btsav'); break;}
	$rt[$k]['field']=$dv?div($d):$d;}
return $rt;}

//['p1'=>['input','label','url',0],['submit','ok','popup|form']]
static function call($rp){
$ra=['type','label','value','opt'];
foreach($rp as $k=>$v)$rp[$k]=array_combine($ra,$v);
$r=self::build($rp);
$rt=array_column($r,'field');
return implode('',$rt);}

static function com($r){$rb=self::build($r);
$tmp='[[(label)*class=cell:div][(field)*class=cell:div]*class=row:div]';
return gen::com2($tmp,$rb);}

}
?>