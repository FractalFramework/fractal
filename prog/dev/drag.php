<?php
//https://developer.mozilla.org/en-US/docs/Web/API/HTML_Drag_and_Drop_API
class drag{
static $private=1;

static function js(){return '';}
static function headers(){head::add('csscode','
.drags div{width:64px; height:64px; margin:0.25em; padding:0.25em; background-color:#eee; display:inline-block;}');
head::add('jscode',self::js());}

static function dragline($t,$id,$j=''){return tag('div',['id'=>$id,'class'=>'dragme','draggable'=>'true','ondragstart'=>'drag_start(event)','ondragover'=>'drag_over(event)','ondrop'=>'drag_sql::drop(event,\''.$j.'\')','ondragend'=>'drag_end(event)','ondragleave'=>'drag_leave(event)'],$t);}

static function build($p){
$r=['d1'=>'hello1','d2'=>'hello2','d3'=>'hello3'];
return $r;}

//builder
static function play($p){$ret='';
$r=self::build($p);
foreach($r as $k=>$v)$ret.=self::dragline($v,$k);
return $ret;}

static function call($p){
return div(self::play($p),'drags','drg');}

//interface
static function content($p){
$p['rid']=randid('md');
$ret=self::call($p);
return div($ret,'',$p['rid']);}
}
?>
