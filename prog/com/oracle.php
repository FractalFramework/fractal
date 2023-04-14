<?php

//application not based on appx
class oracle{	
static $private=2;
static $a=__CLASS__;
static $db=__CLASS__;
static $cols=['tit'];//'card',
static $typs=['var'];//'int',
static $cb='orc';

static function install(){
sql::create(self::$db,array_combine(self::$cols,self::$typs),'z');}

static function admin(){return admin::app(['a'=>self::$a,'db'=>self::$db,'cb'=>self::$cb]);}
static function js(){return;}
static function headers(){
head::add('csscode','.orc{position:relative; width:198px; height:330px; font-size:24px; color:yellow; text-shadow:1px 1px 1px rgb(0,0,0,0.7); text-align:center; background-position:center center;
background-size:cover;}');
head::add('jscode',self::js());}

static function fill(){
$d='1 alpha
2 isolation
3 delta
4 lie
5 water
6 root
7 door
8 nadir
9 wisdom
10 success storie
11 temptation
12 seal
13 penance
14 man
15 love
16 mutation
17 energy
18 science
19 prayer
20 error
21 blessing
22 choice
23 country
24 wealth
25 woman
26 event
27 doubt
28 protection
29 elevation
30 pardon
31 order
32 illusion
33 birth
34 initiation
35 necessity
36 trip
37 light
38 weapon
39 message
40 woe
41 equity
42 desert
43 key
44 lightning
45 beat
46 sacrifice
47 eternitie
48 soul
49 opponent
50 papyrus
51 return
52 silence
53 meditation
54 dead
55 merger
56 brother
57 omega';
$r=explode("\n",$d);
foreach($r as $k=>$v){$rb=explode(' ',$v); sql::sav(self::$db,[utf8enc($rb[1])]);}}

#build
static function build($p){$id=$p['id']??'';
$r=sql('id,tit',self::$db,'kv','');
return $r;}

#play
static function card($p){
$n=$p['n']; $i=$p['i'];
$rb=['','origin','trend','path','issue','synthesis',''];
$v=sql('tit',self::$db,'v',$n); //$t=lang($rb[$i]).': '.lang($v);
$in=str_pad($n,2,'0',STR_PAD_LEFT);
return img('usr/oracle/'.$in.'.jpg',238,400);}

static function play($n,$i){
$im=img('usr/_/oracle/back.jpg',238,400);
return bj(self::$cb.$i.'|oracle,card|n='.$n.',i='.$i,$im);}

static function template($r){
$rb=[['',$r[3],''],[$r[1],$r[5],$r[2]],['',$r[4],'']];
return tabler($rb);}

#call
static function call($p){
$r=self::build($p); $ra=[]; $rc=[]; $rd=[]; $ret=''; $rt='';
for($i=1;$i<=57;$i++)$ra[$i]=$i;
$rb=['','origin','trend','path','issue','synthesis',''];
//for($i=1;$i<58;$i++)lang($r[$i]);//init
for($i=1;$i<=5;$i++){$n=array_rand($ra); unset($ra[$n]);
	$t=lang($rb[$i]);
	//$im='usr/oracle/00.jpg';
	//$s='background-image:url('.$im.'); display:inline-block;';
	//$bt=div($i.'. '.$t,'orc','orc'.$i,$s);
	//$rc[$i]=bj($j,$bt);
	$im=img('usr/oracle/00.jpg',238,400);
	$j=self::$cb.$i.'|oracle,card|n='.$n.',i='.$i;
	$ret.=div(div($t,'').div(bj($j,$im),'','orc'.$i,''),'','','display:inline-block;');}
//$ret=self::template($rc);
return $ret;}

static function menu($p){
$j=self::$cb.'|'.self::$a.',call||';
return bj($j,langp('load'),'btn');
return inputcall($j,'inp2',$p['p1']??'',32);}

#content
static function content($p){
//self::install(); self::fill();
$p['p1']=$p['param']??$p['p1']??'';
$bt=self::menu($p);
$ret=self::call($p);
return $bt.div($ret,'',self::$cb);}
}
?>