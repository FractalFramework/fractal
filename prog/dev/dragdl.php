<?php
class dragdl{
static $private=6;

static function js(){return '
function reactarea(id){
var t=getbyid(id).innerHTML;
ajx(id+\'|dragdl,react|txt=\'+t);
}';}

static function headers(){
head::add('jscode',self::js());}

static function react($p){
$d=conv::call($p);//txt
$ret=conn::call(['msg'=>$d]);//msg
return $ret;}

static function upload($p){pr($p); pr($_FILES);
$rid='upfile'.($p['rid']??''); $ty=$p['ty']; 
$f=$_FILES[$rid]['name']; $f_tmp=$_FILES[$rid]['tmp_name']; //pr($_FILES);
if(!$f)return 'no file uploaded ';
}

//builder
static function build($p){$id=$p['rid']; //$j=atj('reactarea',$id);
$rp=['id'=>'upl'.$id,'class'=>'console','style'=>'min-height:200px;','contenteditable'=>'true','onclick'=>'','ondragenter'=>'dropenter(event)','dropover'=>'dropover(event)','ondrop'=>'dropok(event,\''.$id.'\')'];
$fl='<input type="file" id="upfile'.$id.'" name="upfile'.$id.'" multiple />';
return tag('div',$rp,'here').$fl.span('','',$id.'up');}

//interface
static function content($p){
$p['rid']=randid('md');
$ret=self::build($p);
return div($ret,'');}
}
?>
