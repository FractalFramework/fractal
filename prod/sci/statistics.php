<<<<<<< HEAD
<?php
class statistics{

//correlation
static function Correlation($ra,$rb){
$correlation=0;
$k=self::SumProductMeanDeviation($ra,$rb);
$ssmd1=self::SumSquareMeanDeviation($ra);
$ssmd2=self::SumSquareMeanDeviation($rb);
$product=$ssmd1 * $ssmd2;
$res=sqrt($product);
$correlation=$k / $res;
return $correlation;}

static function SumProductMeanDeviation($ra,$rb){
$sum=0;$num=count($ra);
for($i=0;$i<$num;$i++)$sum=$sum + self::ProductMeanDeviation($ra,$rb,$i);
return $sum;}

static static function ProductMeanDeviation($ra,$rb,$item){
return(self::MeanDeviation($ra,$item)*self::MeanDeviation($rb,$item));}

static function SumSquareMeanDeviation($arr){
$sum=0;$num=count($arr);
for($i=0;$i<$num;$i++)$sum=$sum + self::SquareMeanDeviation($arr,$i);
return $sum;}

static function SquareMeanDeviation($arr,$item){
return self::MeanDeviation($arr,$item)*self::MeanDeviation($arr,$item);}

static function SumMeanDeviation($arr){
$sum=0;$num=count($arr);
for($i=0;$i<$num;$i++)$sum=$sum + self::MeanDeviation($arr,$i);
return $sum;}

static function MeanDeviation($arr,$item){
$average=self::Average($arr);
return $arr[$item]- $average;}

static function Average($arr){
$sum=self::Sum($arr);
$num=count($arr);
return $sum/$num;}

static function Sum($arr){
return array_sum($arr);}

}
?>
=======
<?php
class statistics{

//correlation
static function Correlation($ra,$rb){
$correlation=0;
$k=self::SumProductMeanDeviation($ra,$rb);
$ssmd1=self::SumSquareMeanDeviation($ra);
$ssmd2=self::SumSquareMeanDeviation($rb);
$product=$ssmd1 * $ssmd2;
$res=sqrt($product);
$correlation=$k / $res;
return $correlation;}

static function SumProductMeanDeviation($ra,$rb){
$sum=0;$num=count($ra);
for($i=0;$i<$num;$i++)$sum=$sum + self::ProductMeanDeviation($ra,$rb,$i);
for($i=0;$i<$num;$i++)$sum=$sum + self::ProductMeanDeviation($ra,$rb,$i);
return $sum;}

static static function ProductMeanDeviation($ra,$rb,$item){
return(self::MeanDeviation($ra,$item)*self::MeanDeviation($rb,$item));}

static function SumSquareMeanDeviation($arr){
$sum=0;$num=count($arr);
for($i=0;$i<$num;$i++)$sum=$sum + self::SquareMeanDeviation($arr,$i);
return $sum;}

static function SquareMeanDeviation($arr,$item){
return self::MeanDeviation($arr,$item)*self::MeanDeviation($arr,$item);}

static function SumMeanDeviation($arr){
$sum=0;$num=count($arr);
for($i=0;$i<$num;$i++)$sum=$sum + self::MeanDeviation($arr,$i);
for($i=0;$i<$num;$i++)$sum=$sum + self::MeanDeviation($arr,$i);
return $sum;}

static function MeanDeviation($arr,$item){
$average=self::Average($arr);
return $arr[$item]- $average;}

static function Average($arr){
$sum=self::Sum($arr);
$num=count($arr);
return $sum/$num;}

static function Sum($arr){
return array_sum($arr);}

}
?>
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
