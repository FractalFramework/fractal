<?php
/*
 * Renname this file as yoursite.com.php
*/

#connexion to database
$s=['localhost','root','database','password'];

#Config
$r=[
'site'=>'mySite',//name of your site
'index'=>'home',//App to open
'noadmin'=>0,//not display admin to visitors
'usrboot'=>'',//name of default usr, in case you're using App "telex" and want to make an homepage with your own account
'srv'=>'ffw.ovh',//server of updates
'favicon'=>1,//favicon of your site
'tz'=>'Europe/Paris',//timezone
'lang'=>'fr'//default lang, given after get lang of navigator
];
?>
