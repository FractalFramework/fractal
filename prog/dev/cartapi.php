<?php

/*
* api of cart, standalone application
* excepted things from the framework:
* (see below)
*/

assert_options(ASSERT_ACTIVE,1);//in case of
assert_options(ASSERT_CALLBACK,'see_assert');
assert_options(ASSERT_BAIL,0);
function see_assert($script,$line,$error){
echo 'error in '.$script.' at line '.$line.': '. $error;}

class cartapi{
static $private=0;
static $a=__CLASS__;
static $cb='mdb';
static $process=[];
static $errors=[];

#api/client

/*
* unuseful here
* it's the client side
* with an example
*/

static function getkey(){return 1234;}

static function apicall($r,$mode){
	$k=self::getkey();
	if(!$mode)$mode='get';//post,put,get,del
	$ubase='';//https://logic.ovh///unused
	$u=$ubase.'api/cartapi/'.$mode.'?'.mkprm($r).'&token='.$k;
	$d=@file_get_contents($u);
	return json_decode($d,true);}

/*
* here is the job
* begin to read from the bottom to the top
* api() is called by api.php
* core() if the main activity of this app
* first we verify types of datas
* secondly we verify validity of datas (was not necessary here, i know...)
* global_result() call calculate_price()
*/

#api/server
static private $status=[200=>'ok',201=>'created',204=>'no_content',206=>'partial_content',304=>'not_modified',400=>'bad_request',401=>'Unauthorized',403=>'Forbidden',404=>'Not Found',500=>'Internal Server Error'];

/*
* method render_results
* returns json
*/

static function render_results($r,$n=404){
	$ret=['status'=>$n,'results'=>$r];
	header('HTTP/1.1 '.$n.' '.(self::$status[$n]??404));
	header('Content-Type: text/json; charset=UTF-8');
	return json_encode($ret);}

/*
* method calculate_prise
* contains definitions of remittances and costs (should be in an other place)
* returns array containing: Amount, Vat, Total
*/

static function scaled_price($item){
	$remittance_quantity=0; $cost_deadline=0;
	//definitions
	$cost_by_type=['pdf'=>15,'psd'=>35,'ai'=>25];
	$cost_by_quantity=[100=>5,250=>10,500=>15,1000=>20];//remittance by quantity
	$cost_by_deadline=[0=>30,86400=>20,172800=>10,259200=>0];//nb days in seconds
	$deadline=strtotime($item['delivery_date'])-time();//time left at this moment
	//calculation
	$cost_filetype=$cost_by_type[$item['filetype']];
	foreach($cost_by_quantity as $k=>$v)if($item['quantity']>=$k)$remittance_quantity=$v;
	foreach($cost_by_deadline as $k=>$v)if($deadline>=$k)$cost_deadline=$v;
	return [$cost_filetype,$remittance_quantity,$cost_deadline];}

static function algo_price($item){
	$remittance_quantity=0; $cost_deadline=0;
	//definitions
	$cost_by_type=['pdf'=>15,'psd'=>35,'ai'=>25];
	$qte=$item['quantity']; if($qte>1000)$qte=1000;//max remittance
	$ratio_deadline=0.2;
	$deadline=strtotime($item['delivery_date'])-time();
	$daysleft=round($deadline/86400,1); if($daysleft>3)$daysleft==3;//calculate deadline by 0.1 day
	//calculation
	$cost_filetype=$cost_by_type[$item['filetype']];
	$remittance_quantity=($res=log($qte/50)*6.7)<0?0:$res;//logarithm
	$cost_deadline=($res=(3-$daysleft)*12)<0?0:$res;//(3-n)*10;//linear
	return [$cost_filetype,$remittance_quantity,$cost_deadline];}

static function calculate_price($item,$algo=0){//pr($item);
	$price_of_product=1;//all products costs 1
	$cost_deadline=0;
	$remittance_quantity=0;
	//calculation
	$amount=$price_of_product*$item['quantity'];
	if($algo==1)[$cost_filetype,$remittance_quantity,$cost_deadline]=self::algo_price($item);
	else [$cost_filetype,$remittance_quantity,$cost_deadline]=self::scaled_price($item);
	//add all remittances/costs
	$vat=$cost_filetype+$cost_deadline-$remittance_quantity;
	//apply remittances/costs
	$total=$amount+($amount*($vat/100));
	$ret=['amount'=>$amount,'vat'=>$vat,'total'=>$total];
	//we keep that 
	self::$process[$item['product_sku']]=['product'=>$item['product_name'],'net_price'=>$amount,'cost_filetype'=>$cost_filetype,'remittance_quantity'=>$remittance_quantity,'cost_deadline'=>$cost_deadline]+$ret;
	return $ret;}

/*
* method global_result()
* do the calculation for each item of the list
* returns array
*/

static function global_result($items,$algo=0){
	$additions=[];
	foreach($items as $k=>$item)
		$additions[]=self::calculate_price($item,$algo);
	$price=array_sum(array_column($additions,'amount'));
	$vat=array_sum(array_column($additions,'vat'))/count($additions);//average
	$total=array_sum(array_column($additions,'total'));
	$ret=['price'=>round($price,2),'vat'=>round($vat,2),'total'=>round($total,2)];
	self::$process['global_result']=$ret;//keep that
	return $ret;}

/*
* method verif_types
* do not verify validity, only types
* accepted types are int/var/list given by "-", and time.
* return boolean $res, 1 if true
*/

static $types=['ecommerce_id'=>'int','customer_id'=>'int','item_list'=>'json','product_sku'=>'int','product_name'=>'var','filetype'=>'pdf-psd-ai','quantity'=>'int','delivery_date'=>'date'];

static function validation($k,$v){//type,value
	$expected_type=self::$types[$k]??'';
	$detected_type='';
	$res='';
	switch($k){
		case('product_sku'):$res=is_numeric($v) && $v>0?1:0; break;
		case('product_name'):$res=!is_numeric($v)?1:0; break;
		case('filetype'):$ra=explode('-',$expected_type); $res=in_array($v,$ra)?1:0; break;
		case('quantity'):$res=is_numeric($v) && $v>0?1:0; break;
		case('delivery_date'):$res=DateTime::createFromFormat('Y-m-d',$v)!==false?1:0; break;}
	if(!$res)self::$errors[]='error on '.$k.': expected '.$expected_type.' for value '.$v;
	return $res;}

/*
* here are the methods for each called status
*/

//returns: ['created_at','updated_at'];
static function create($p){
	[$ecommerce_id,$customer_id]=vals($p,['ecommerce_id','customer_id']);
	$id=$ecommerce_id.'-'.$customer_id;
	$r1=['ecommerce_id'=>$ecommerce_id,'customer_id'=>$customer_id];
	$r2=['created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')];
	if(!isset($_SESSION[$id]))$_SESSION[$id]=$r1+$r2;
	else return ['status'=>'cart already exists'];
	$ret=['created_at'=>$_SESSION[$id]['created_at'],'updated_at'=>$_SESSION[$id]['updated_at']];
	return $ret;}

static function delete($p){
	[$ecommerce_id,$customer_id]=vals($p,['ecommerce_id','customer_id']);
	$id=$ecommerce_id.'-'.$customer_id;
	if(!isset($_SESSION[$id]))return ['status'=>'cart not exists'];
	unset($_SESSION[$id]);//remove session
	return ['status'=>'cart have been removed'];}

//returns: ['updated_at'];
static function add($p){
	[$ecommerce_id,$customer_id,$item_list]=vals($p,['ecommerce_id','customer_id','item_list']);
	//if(!$item_list)return ['status'=>'item_list is not specified'];
	$id=$ecommerce_id.'-'.$customer_id;
	if(!isset($_SESSION[$id]))return ['status'=>'cart not exists'];
	$item=json_decode($item_list,true); //pr($item);
	$sku=$item['product_sku']??'';
	if(!$sku)return ['status'=>'sku is not specified'];
	$rb=[];
	foreach($item as $k=>$v)
		$rb[$k]=self::validation($k,$v); //pr($rb);
	//validation means n points for n entries
	$ok=count($rb)==array_sum($rb);//all is okay
	if(!$ok)//return errors
		return self::$errors;
	else self::$process[]='no error for sku: '.$sku;
	$item['updated_at']=date('Y-m-d H:i:s');
	$items=$_SESSION[$id]['item_list']??[];
	$ex=0;//search in array if sku exists
	foreach($items as $k=>$v)if($v['product_sku']==$sku)$ex=1;
	if(!$ex)$_SESSION[$id]['item_list'][]=$item;
	else return ['status'=>'this article is already in the basket'];//could add quantity
	return ['updated_at'=>$item['updated_at']];}

//returns: ['ecommerce_id','customer_id','created_at'=>'','price'=>'','item_list'=>[]];
static function view($p){
	[$ecommerce_id,$customer_id,$verbose,$algo]=vals($p,['ecommerce_id','customer_id','verbose','algo']);
	$id=$ecommerce_id.'-'.$customer_id;
	$ra=$_SESSION[$id]??[]; //pr($ra);
	if(!$ra)return ['status'=>'cart not exists'];
	$items=$ra['item_list']??[];
	if(!$items)return ['status'=>'cart is empty'];
	$res=self::global_result($items,$algo);
	$ret=['ecommerce_id'=>$ra['ecommerce_id'],'customer_id'=>$ra['customer_id'],'created_at'=>$ra['created_at'],'price'=>$res['total'],'item_list'=>$items];
	if($verbose)$ret['verbose']=self::$process;
	return $ret;}

//returns: ['date_checkout','price'=>'','vat'=>'','total'=>''];
static function checkout($p){
	[$ecommerce_id,$customer_id,$verbose,$algo]=vals($p,['ecommerce_id','customer_id','verbose','algo']);
	$id=$ecommerce_id.'-'.$customer_id;
	$items=$_SESSION[$id]['item_list']??[];
	if(!$items)return ['status'=>'cart is empty'];
	$ret=self::global_result($items,$algo);
	if($verbose)$ret['verbose']=self::$process;
	return $ret;}

/*
* this section need the frameweork
* it's used to display an interface
* that let test things
*/

#call from ajax
static function call($p){$ret='';
	[$act,$p1]=vals($p,['act','p1']);
	$prm=explode_k($p1,'&','='); //pr($prm);
	if(!$prm)return self::render_results(['status'=>'no datas received'],200);
	if(empty($prm['ecommerce_id']))return self::render_results(['status'=>'ecommerce_id is not specified'],200);
	if(empty($prm['customer_id']))return self::render_results(['status'=>'customer_id is not specified'],200);
	if($act=='create')$ret=self::create($prm);
	if($act=='del')$ret=self::delete($prm);
	if($act=='add')$ret=self::add($prm);
	if($act=='view')$ret=self::view($prm);
	if($act=='checkout')$ret=self::checkout($prm);
	if($ret)return play_r($ret);//play array
	return help('nothing');}

#call from url api/
static function api($p){$ret='';
	//pr($p);//framework usually accept pseudo-json urls
	//pr($_GET);//we obtain here : [app] => cartapi, [p] => create, [ecommerce_id] => 1, [item_list] => {"1":...
	//ok, go : we build the array
	$status=get('p');//create,add,view,checkout
	$prm['ecommerce_id']=get('ecommerce_id');
	$prm['customer_id']=get('customer_id');
	$prm['item_list']=get('item_list');
	$prm['verbose']=get('verbose');
	$prm['algo']=get('algo');
	if(!$prm['ecommerce_id'])return self::render_results(['status'=>'ecommerce_id is not specified'],200);
	if(!$prm['customer_id'])return self::render_results(['status'=>'customer_id is not specified'],200);
	if($status=='create')$ret=self::create($prm);
	if($status=='add')$ret=self::add($prm);
	if($status=='view')$ret=self::view($prm);
	if($status=='checkout')$ret=self::checkout($prm);
	if($ret)return self::render_results($ret,200);
	return self::render_results(['bad request'=>'400'],200);}//let to 200

#content (web interface)
static function ex(){
return ['call'=>'ecommerce_id=1&customer_id=1',
'ex1'=>'ecommerce_id=1&customer_id=1&item_list={"product_sku":1,"product_name":"prod1","filetype":"pdf","quantity":"101","delivery_date":"'.date('Y-m-d',time()+86400).'"}&verbose=0&algo=0',
'ex2'=>'ecommerce_id=1&customer_id=1&item_list={"product_sku":2,"product_name":"prod2","filetype":"ai","quantity":"499","delivery_date":"'.date('Y-m-d',time()+172800).'"}&verbose=0&algo=1'];}

static function menu($p){$p1=$p['p1']??'';
	$ret=build::sample(['a'=>'cartapi','b'=>'p1']);
	$ret.=textarea('p1','',44,4);
	$ret.=bj(self::$cb.',,z|'.self::$a.',call|act=create|p1',langp('create'),'btsav');
	$ret.=bj(self::$cb.',,z|'.self::$a.',call|act=del|p1',langp('delete'),'btsav');
	$ret.=bj(self::$cb.',,z|'.self::$a.',call|act=add|p1',langp('add'),'btsav');
	$ret.=bj(self::$cb.',,z|'.self::$a.',call|act=view|p1',langp('view'),'btsav');
	$ret.=bj(self::$cb.',,z|'.self::$a.',call|act=checkout|p1',langp('checkout'),'btsav');
	$ret.=lk('http://logic.ovh/api/cartapi/view&',langp('api'),'btn');
	$ret.=hlpbt('cartapi_app');
	return $ret;}

static function content($p){
	//self::install();
	$p['p1']=$p['p1']??'';
	$bt=self::menu($p);
	return $bt.div('','board',self::$cb);}

}

#samples
/*static function test($p){
	$k=$p['k']??'create';//status
	$r['create']=['ecommerce_id'=>1,'customer_id'=>1];
	//returns: ['created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')];
	$r['add']=['ecommerce_id'=>1,'customer_id'=>1];
	$rb=['product_sku'=>rand(1,100),'product_name'=>'prod'.rand(1,100),'filetype'=>'pdf','quantity'=>rand(1,1001),'delivery_date'=>date('Y-m-d H:i:s',time()+rand(0,259200))];
	$r['add']['item_list']=json_encode($rb);
	//returns: ['updated_at'=>date('Y-m-d H:i:s')];
	$r['view']=['ecommerce_id'=>1,'customer_id'=>1];
	//returns: ['ecommerce_id'=>1,'customer_id'=>1,'created_at'=>'','price'=>'','item_list'=>''];
	$r['checkout']=[''];
	//returns: ['date_checkout'=>date('Y-m-d H:i:s'),'price'=>'','vat'=>'','total'=>''];
	return $r[$k];}*/

#things of framework (don't be afraid)
//function get($d){if(isset($_GET[$d]))return urldecode($_GET[$d]);}
//function mkprm($p){foreach($p as $k=>$v)$rt[]=$k.'='.$v; if($rt)return implode('&',$rt);}
//function val($r,$d,$b=''){if(!isset($r[$d]))return $b; return $r[$d]=='memtmp'?memtmp($d):$r[$d];}
//function vals($p,$r,$o=''){foreach($r as $k=>$v)$rt[]=val($p,$v,$o); return $rt;}
//function pr($r,$o=''){$ret='<pre>'.print_r($r,true).'</pre>'; if($o)return $ret; else echo $ret;}
/*
function json_er(){
switch(json_last_error()){
case JSON_ERROR_NONE:$ret='no error';break;
case JSON_ERROR_DEPTH:$ret='maximum depth reached';break;
case JSON_ERROR_STATE_MISMATCH:$ret='bad modes (underflow)';break;
case JSON_ERROR_CTRL_CHAR:$ret='error during character check';break;
case JSON_ERROR_SYNTAX:$ret='syntax error; malformed Json';break;
case JSON_ERROR_UTF8:$ret='malformed UTF-8 characters';break;
default:$ret='unknown error';break;}
return $ret;}*/

?>