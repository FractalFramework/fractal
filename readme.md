# PHP-AJAX FRAMEWORK

This is a platform to easily build web applications and services.
The architecture is based on the the Ajax process.

# Requirements

Server Apache PHP>=8+ MYSQL >5+ and mailing abilities

# Install

_(oldschool)_
- put install.php on your server and load it to receive all file, or put them directly via ftp.
- rename /cnfg/site.com.php -> [your domaine].php and fill the variables.
- put .htaccess in your virtualhost
- /prod/admin/install.php: temporaly change var $private=6 -> $private=0
- load /install to create all needed mysql tables (one or more by app)
- /upsql will import all needed datas (lang, help, icons, desktop) from mother server
- /update will import most recents files from server.
- register first account, it will have the column 'auth' of mysql table 'login' set to 7 (as superadmin). Others accounts will have auth=2.
- prod/admin/install.php: reset var $private=0 -> $private=6

# Dev

2 folders have the same content:
- /prod
- /prog

It's used to enter in Dev mode, to change things online before to push them in /Prod.
All changes will be saved in /_old (like on Git)

# How that works

The App named "tlex" is a Twitter-Like used to share Apps between users.
The App named "home" is the main interface of the site, but feel free to change it in cnfg/site.php

- /lib.php contains extensions of PHP
- /core contains common abilities

**Apps are unitaries services that contain PHP, CSS and JavaScript.**

We can load Apps in chains from any other App.
The application collect the specifics headers and JS of the App when it's loaded via ajax.

Ajax do that :
/app/appName 
	-> open popup/pagup/bubble/toggle/div/(etc.)
		-> use javascript (ajax.js)
			-> call another App (/call.php)
				-> return result to javascript 
					-> return result to page 
				-> second call for headers (option)
					-> return spectific javascript in headers of page.

# Structure of an App

An App can have no abstraction, or use the Appx abstraction.
In anyways, some methods are necessary :

- static $private='0';//public access
- static function js(){}//loadable js
- static function headers(){}//css and js called from url
- static function admin(){}//admin of the App (like databases, names, pictos, etc.)
- static function install(){}//definitions of the linked databases to this app (one or more)
- static function build($p){}//main access to the datas with or without an id
- static function call($p){}//called from inside
- static function com($p){}//called from inside
- static function content($p){}//called by default when opening url /app
- static function api($p){}//called from url /api/app

To create an App, use models from /x or reuse other Apps.

# Appx

Appx is an abstract App who let usable a lot of process common to each App :
- create an database and a secondary level of database
- create or edit the items
- presentation of the items
- privileges for each items
- collect datas of public forms

You can intercept any step of the process by your own function, specially the process 'play' and 'template'.

You can create databases by specifying the columns and their types.
Names of columns of database are associed with specific actions
You can change names or types of columns while developping your App.

# HTML subframework

An HTML Framework makes it (very) easy and fast to write html.
Constantly keep the lib.php on eyes to help you to write code.
(And it will be memorized fastly !)

//make tag div with class deco:
$ret=tag('div',['class'=>'deco'],'hello');
$ret=div('hello','class','id','style')

# Connectors

Connectors replace html in contents.
It assume to use XXL-Html like any tags you want.
Each App means a new tag.
So you can call in any text : [id:app]

//make tag div with class deco:
$ret='[hello:b]';//write hello in bold
$ret='[hello§class=deco:div]');

Connectors can assume Templating, Svg, and a few Maths.
Imported articles from the web are converted to Connectors to make lighter the code.

# Ajax motor

All commands for Ajax action uses these four sections :

"target|appName,appMethod|p1=1,p2=2|p3,p4"

- target can be div-id, popup, pagup, buble, toggle, etc...
- com is the app and method, ok
- vars are the sent variables
- vars captured on the page by the ID

# Menus

A very exciting feature is the Menus. 
We can build hierarchical ajax/html actions using a simple array and a loader.

//this will display a link to open pictos inside a submenus:
//Here, "j" means it's calling ajax process

static function menus(){
	$r[]=['menu1/submenu1','j','popup|pictos','map','pictos'];
	$r[]=['menu1/submenu2','j','popup|pictos','map','pictos'];
	return $r;}
static function content(){
	return Menu::call(array('app'=>'myApp','method'=>'menus'));}

# Desktop

Works like Menus, but using folders.

//this will display a link to open pictos inside a submenus:
static function structure(){
	$r[]=['menu1/menu2','','pictos','map','pictos'];
	return $r;}
static function content(){
	return Desk::load('desktop','structure');}

# Site

http://logic.ovh/

# Credits

FractalFramework 2015-2023
Free License GNU/GPL