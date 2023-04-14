<?php

class mem{//bdd infinie
//itération par l'usage d'un col=row-col
static function install($p=''){
	sql::create('mem',['bid'=>'int','row'=>'int','col'=>'int','txt'=>'bvar'],1);
	sql::create('mem_b',['cid'=>'int','num'=>'int','tit'=>'var'],1);
	sql::create('mem_c',['tab'=>'var'],1);}

static function write($u,$r){
//echo $n=key($r);
$id=sql::savif('mem_c',[$u]);
$ra=self::cols($id); //pr($ra);
if(!$ra && $r){
	foreach(current($r) as $k=>$v)$rb[]=[$id,$k,'col'.$k];
	sql::sav2('mem_b',$rb);
	$ra=self::cols($id);}
if($r){
	$ra=self::valk($id); //pr($ra);
	foreach($r as $k=>$rb){
		if(!$ra[$k])foreach($rb as $kb=>$vb)$rc[]=[$id,$k,$kb,$vb];//
		//elseif($k=='mdf')foreach($rb as $kb=>$vb)$rd[]=[$id,$k,$kb,$vb];
	}
	if(isset($rc))sql::sav2('mem',$rc);
	//if(isset($rd))sql::up2('mem',$rd);
	}
}

static function where($id,$row='',$col=''){
$r['id']=$id; if($row)$r['row']=$row; if($col)$r['col']=$col;
return sql('row,col,txt','mem','kkv',$r);}

static function read_assoc($u){
return sql('row,col,txt','mem','kkv','
inner join mem_b on cid=mem_c.id and num=col
inner join mem_c on bid=mem_c.id where tab="'.$u.'"');}
static function read($u){
return sql('row,col,txt','mem','kkv','inner join mem_c on bid=mem_c.id where tab="'.$u.'"');}

static function valk($id){return sql('row,col,txt','mem','kkv',['bid'=>$id]);}
static function cols($id){return sql('id,num,tit','mem_b','rr',['cid'=>$id]);}
static function id($u){return sql('id','mem_c','v',['tab'=>$u]);}

static function add($u,$row){}

static function call($p){}

static function content($p){$r='';
//self::install();
$u='hello';
$r=[1=>['yes','no'],2=>['oui','non']];
//self::write($u,$r);
$r=self::read($u);
return tabler($r,0,1);}

}