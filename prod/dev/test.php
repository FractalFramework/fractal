<?php

class myException extends Exception{
public function __construct($msg){parent :: __construct($msg);}
public function getError($v){
switch($v){
case 999:break;//prev err
default:return $this->getMessage(); break;}}
public function __destruct(){}}


?>