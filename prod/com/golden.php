<?php

class golden{	
static $private=0;
static $db='_model';
static $a='_model';

/*static function install(){
	sql::create(self::$db,['tit'=>'var','txt'=>'bvar'],0);}*/

static function admin(){
$r[]=['','j','popup|golden,content','plus',lang('open')];
$r[]=['','pop','core,help|ref=_model_app','help','-'];
if(auth(4))$r[]=['admin','j','pagup|dev,seeCode|f=_model','code','Code'];
return $r;}

static function js(){return "var options = {bullion: 'gold'};
	var chartBV = new BullionVaultChart(options, 'chartContainer');
		var options = {
			bullion: 'gold',
			currency: 'USD',
			timeframe: '1w',
			chartType: 'line',
			miniChartModeAxis : 'oz',
			referrerID: 'MYUSERNAME',
			containerDefinedSize: true,
			miniChartMode: false,
			displayLatestPriceLine: true,
			switchBullion: true,
			switchCurrency: true,
			switchTimeframe: true,
			switchChartType: true,
			exportButton: true
		};
		var chartBV = new BullionVaultChart(options, 'embed');";}
static function headers(){
head::add('jslink','https://or.bullionvault.fr/chart/bullionvaultchart.js?v=1');
head::add('jscode',self::js());}

static function titles($p){
$d=$p['_m']??'';
$r['content']='welcome';
$r['build']=self::$a;
if(isset($r[$d]))return lang($r[$d]);
return $d;}

#build
/*static function build($p){$id=$p['id']??'';
	$r=sql('all',self::$db,'ra',$id);
	return $r;}*/

#read
static function call($p){
return $p['msg'].': '.$p['inp1'];}

static function com($p){
return self::content($p);}

#content
static function content($p){
//self::install();
$p['p1']=$p['p1']??'';
$ret=input('inp1','value1','','1');
$ret.=bj('popup|golden,call|msg=text|inp1',lang('send'),'btn');
$ret.=div('','','embed','height: 400px; width: 660px;');
return div($ret,'pane');}
}
?>