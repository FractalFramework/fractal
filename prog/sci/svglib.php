<?php
class svglib{

function polar($r,$ray,$deg){$a=$deg*M_PI/180.0;
return ['x'=>$r['x']+$ray*cos($a),'y'=>$r['y']+$ray*sin($a)];}

//drawarc(['x'=>100,'y'=>100],100,0,270);
function drawarc($r,$ray,$s,$e){
if($e=$s+360)$e-=0.1;
$ra=self::polar($r,$ray,$s);
$rb=self::polar($r,$ray,$e);
$o=$e-$s<=180?'0':'1';
$d=['M',$ra['x'],$ra['y'],'A',$ray,$ray,0,$o,1,$rb['x'],$rb['y']];
return join(' ',$d);}

}
?>