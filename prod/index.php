<?php
#Fractal
//setlocale(LC_ALL,'fr_FR');//kill rgba ability?
$nc=ses('dev')?'?'.randid():'';
$index=ses::$cnfg['index'];
header('Content-Type: text/html; charset=UTF-8');
//head::add('code','<base href="'.$_SERVER['HTTP_HOST'].'" />');
head::add('charset','UTF-8');
head::add('tag',['title',[],ses::$cnfg['index']]);//setlng();//lang($app)
head::add('rel',['name'=>'shortcut icon','value'=>favicon()]);
head::name('viewport','user-scalable=no, initial-scale=1, width=device-width');
head::add('csslink',night(0).$nc);//$start
head::add('csslink','/css/global.css'.$nc);
head::add('csslink','/css/apps.css'.$nc);
head::add('csslink','/css/pictos.css');
head::add('csslink','/css/fa.css');
head::add('jslink','/js/ajax.js'.$nc);
head::add('jslink','/js/core.js'.$nc);
<<<<<<< HEAD
head::add('jscode','var index="'.($index=='home'?'root':$index).'";');
//head::add('jslink','/js/bab.js');
head::name('generator','Fractal');
head::name('version','26');
=======
head::add('jscode','var index="'.$index.'";');
//head::add('jslink','/js/bab.js');
head::name('generator','Fractal');
head::name('version','23');
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
#usr
$own=ses('usr'); $usr=$p['usr']??'';
ses('cusr',$own); ses('cuid',ses('uid'));
if($usr){if($okusr=vrfusr($usr)){ses('cusr',$usr); ses('cuid',$okusr);}}
#design
//if($usr)bootheme($usr);
#main
$main=root::content(['app'=>$app]+$p);
#content
//if(!ses('updated') && auth(6))upgrade::content();
if(isset($p['api']) or ses::$cnfg['noadmin'])$admin='';// && !auth(6)
else $admin=admin::content(['app'=>$app,'id'=>$p['p1']??'']);
stats::add($app,$p);
#render
$ret=head::run();
$ret.='<body onmousemove="popslide(event)" onclick="closebub(event)">'."\n";
$ret.=tag('div',['id'=>'closebub','onclick'=>'bubClose()'],'');
$ret.=tag('div',['id'=>'admin'],$admin);
$ret.=tag('div',['id'=>'page'],$main);
$ret.=tag('div',['id'=>'popup'],'');
if(ses('dev')=='prog')$ret.=div(round(microtime(1)-$start,5),'chrono');
$ret.='</body>
</html>';
echo $ret;
?>