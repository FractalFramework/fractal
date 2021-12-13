<?php

class midi{
	//used by system
	static $private=1;
	
	static function injectJs(){
		return '';
	}
	static function headers(){
		add_head('csscode','');
		add_head('jscode',self::injectJs());
	}
	
	static function admin(){
		$r[]=array('','j','popup|midi,content','plus',lang('open'));
		return $r;
	}
	//builder
	static function build($p){
		$f='usr/'.val($p,'fil');
		$ret=read_file($f);
		//$ret=file_get_contents($f);
		//pr(file($f));
		//$ret=tag('audio',array('src'=>$f,'controls'=>1),lk($f,lang('download')));
		return $ret;
	}
	
	//interface
	static function content($p){
		$p['rid']=randid('md');
		$p['p1']=$p['p1']??'Toccata.mid';//unamed param before
		$ret=hlpbt('midi');
		$ret.=input('fil','value1',$p['p1'],'1');
		$ret.=bj('popup|midi,build||fil',lang('send'),'btn');
		return div($ret,'',$p['rid']);
	}
}
?>
