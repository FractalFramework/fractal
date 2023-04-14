<?php
class form{

static function ex(){return ['input'=>'label§title','textarea'=>'label2§text','select'=>'choice§a/b/c','checkbox'=>'options§a/b','radio'=>'choose one§a/b','bar'=>'evaluation§1-10','submit'=>'form0§'.ses('usr')];}

static function usave($p){//from conn
$u=$p['u']; $b=$p['b']; $h=$p['h']; unset($p['u']); unset($p['b']); unset($p['h']);
$f='usr/'.($u?$u:'public').'/forms/free/'.$b; $rh=pushr(['date','uid'],explode('-',$h));
$r=db::read($f,1); $bt=$u==ses('usr')?db::bt($f):''; $ex=in_array_n(ses('uid'),$r,1);
if($ex)return help('answered').tabler($r[$ex],'',1).$bt;
else db::add($f,[date('ymd:Hi'),ses('uid')]+$p,$rh); 
return help('form_filled').$bt;}

static function build($r){$rt=[]; //$rk=array_keys(uns($r,0));
foreach($r as $k=>$v){$k=normalize($k,1); $d='';
	['type'=>$ty,'value'=>$va,'label'=>$lbl,'opt'=>$o]=$v;
	switch($ty){
		case('input'):$d=input($k,$va,20,$lbl,$o); break;
		case('inputnb'):$d=input($k,$va,8,$lbl,1,'',$o); break;
		case('inputcall'):$d=inputcall($o,$k,$va,32,$lbl); break;
		case('textarea'):$d=textarea($k,$va,40,10,$lbl,$o); break;
		case('select'):$d=select($k,$o,$va,1); break;
		case('checkbox'):$d=checkbox($k,$o,$va,1); break;
		case('radio'):$d=radio($k,$o,$va,1); break;
		case('hidden'):$d=hidden($k,$va); break;
		case('bar'):$d=bar($k,$va); break;
		case('submit'):$d=bj($va,langp($lbl),'btsav'); break;
		//case('submit'):$d=input($k,langp($lbl),'','','submit'); break;
		}
	$rt[$k]['field']=$d;
	if($lbl && $ty!='submit')$rt[$k]['label']=label($k,$lbl);}
return $rt;}

static function pao($r,$j,$mode){
$frm=randid('frm'); $ret='';
if($mode=='rows')foreach($r as $k=>$v)$ret.=div(implode('',$v));
elseif($mode=='field')$ret=implode('',array_column($r,'field'));
else $ret=implode_r($r,' ','');
if($j){$rj=explode('|',$j); $jb=$rj[0].'|'.$rj[1].'|'.$rj[2].'|'.$frm;
	$ret.=bj($jb,langp('ok'),'btsav');
	$rp=['id'=>$frm,'name'=>$frm,'onsubmit'=>'return ajbt(this);','data-j'=>$jb];//'onkeyup'=>atj('checkenter','this')
	$ret=tag('form',$rp,$ret);}
return $ret;}

//['p1'=>['input','label','url',0],'p2'=>['radio','choice1','one',['one','two']],['submit','ok','popup|form||','idform']]
static function call($rp,$j='',$mode=''){
$ra=['type','value','label','opt'];
foreach($rp as $k=>$v)$rp[$k]=array_combine($ra,$v);
$r=self::build($rp);
if(!$j)return implode('',array_column($r,'field'));
else return self::pao($r,$j,$mode);}

static function com($r){$rb=self::build($r);
$tmp='[[(label)*class=cell:div][(field)*class=cell:div]*class=row:div]';
return gen::com2($tmp,$rb);}

}
?>