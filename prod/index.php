<?php
#Fractal
//setlocale(LC_ALL,'fr_FR');//kill rgba ability?
$enc='UTF-8';
$nc=ses('dev')?'?'.randid():'';//if()
header('Content-Type: text/html; charset=UTF-8');
//add_head('code','<base href="'.$_SERVER['HTTP_HOST'].'" />');
add_head('charset','UTF-8');
add_head('tag',['title','',$app?lang($app):$site]);//setlng();
add_head('rel',['name'=>'shortcut icon','value'=>'/favicon.ico']);
add_name('viewport','user-scalable=no, initial-scale=1, width=device-width');
add_head('csslink','/css/global.css'.$nc);
add_head('csslink','/css/apps.css'.$nc);
add_head('csslink','/css/pictos.css');
add_head('csslink','/css/fa.css');
add_head('jslink','/js/ajax.js'.$nc);
add_head('jslink','/js/utils.js'.$nc);
//add_head('jslink','/js/bab.js');
add_name('generator','Fractal');
add_name('version','21');
if(method_exists($app,'content')){$home=($app::$home)??'';
	if($home)$content=home::content($p+['app'=>$app]);//$index
	else{$a=new $app; $content=$a::content($p);
		if(method_exists($app,'headers'))$a::headers();}}
elseif(class_exists($app))$content=div(helpx('nothing').' : '.$app,'paneb');
else $content=div(helpx('no app').': '.$app,'paneb');
#usr
$own=ses('user'); $usr=$p['usr']??'';
ses('cusr',$own); ses('cuid',ses('uid'));
if($usr){if($okusr=vrfusr($usr)){ses('cusr',$usr); ses('cuid',$okusr);}}
#design
if($usr)bootheme($usr);
#content
//if(!ses('updated') && auth(6))upgrade::content();
if(isset($p['api']) or ($noadmin))$admin='';// && !auth(6)
else $admin=admin::content(['app'=>$app,'id'=>$p['p1']??'']);
stats::add($app,$p);
#render
$ret=generate();
//$s=svg::ex();
$ret.='<body onmousemove="popslide(event)" onclick="closebub(event)" onload="loadp()">'."\n";// '.$s.'
$ret.=tag('div',['id'=>'closebub','onclick'=>'bubClose()'],'');
$ret.=tag('div',['id'=>'admin'],$admin);
$ret.=tag('div',['id'=>'page'],$content);
$ret.=tag('div',['id'=>'popup'],'');
if(ses('dev')=='prog')$ret.=div(round(microtime(1)-$start,5),'chrono');
$ret.='</body>
</html>';
//if(ses('enc'))$ret=utf8_encode($ret);
echo $ret;
?>