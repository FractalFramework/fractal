<?php

class tests{
static $er=[];

static function test(){}

static function inverse($x) {
if(!$x)throw new Exception('Division par zéro.');
return 1/$x;}

static function error(){$ret='';
	try{$ret.=self::inverse(5)."\n";}
	catch(Exception $e){self::$errors[]=$e->getMessage();}
	finally{$ret.="Première fin.\n";}
	
	try{$ret.=self::inverse(0)."\n";}
	catch(Exception $e){self::$er[]=$e->getMessage();}
	finally{$ret.="Seconde fin.\n";}
	
	// On continue l'exécution
	$ret.="Bonjour le monde !\n";
	return $ret;}

//
public function exposeFunction(){return Closure::fromCallable([$this, 'privateFunction']);}
private function privateFunction($param){var_dump($param);}

}

//$privFunc=(new tests)->exposeFunction();
//$privFunc('some value');

?>