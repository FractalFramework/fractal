<?php
class iter implements Iterator{private $r=[]; private $a=''; private $m='';
public function __construct($r,$a,$m){$this->r=$r; $this->a=$a; $this->m=$m;}
public function rewind(){reset($this->r);}
public function current(){$a=$this->a; $m=$this->m; return $a::$m(current($this->r));}
public function key(){return key($this->r);}
public function next(){return next($this->r);}
public function valid(){$k=key($this->r); return ($k!==NULL && $k!==FALSE);}}
//$r=[1,2,3]; $q=new iter($r); foreach($q as $k=>$v){}
?>