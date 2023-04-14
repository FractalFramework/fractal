<?php
class statistics{

//correlation
function Correlation($ra,$rb){
$correlation=0;
$k=SumProductMeanDeviation($ra,$rb);
$ssmd1=SumSquareMeanDeviation($ra);
$ssmd2=SumSquareMeanDeviation($rb);
$product=$ssmd1 * $ssmd2;
$res=sqrt($product);
$correlation=$k / $res;
return $correlation;}

function SumProductMeanDeviation($ra,$rb){
$sum=0;$num=count($ra);
for($i=0;$i<$num;$i++)$sum=$sum + ProductMeanDeviation($ra,$rb,$i);
return $sum;}

function ProductMeanDeviation($ra,$rb,$item){
return(MeanDeviation($ra,$item)* MeanDeviation($rb,$item));}

function SumSquareMeanDeviation($arr){
$sum=0;$num=count($arr);
for($i=0;$i<$num;$i++)$sum=$sum + SquareMeanDeviation($arr,$i);
return $sum;}

function SquareMeanDeviation($arr,$item){
return MeanDeviation($arr,$item)* MeanDeviation($arr,$item);}

function SumMeanDeviation($arr){
$sum=0;$num=count($arr);
for($i=0;$i<$num;$i++)$sum=$sum + MeanDeviation($arr,$i);
return $sum;}

function MeanDeviation($arr,$item){
$average=Average($arr);
return $arr[$item]- $average;}

function Average($arr){
$sum=Sum($arr);
$num=count($arr);
return $sum/$num;}

function Sum($arr){
return array_sum($arr);}

}
?>