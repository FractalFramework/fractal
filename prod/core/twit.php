<?php
//https://github.com/tfairane/TwitterAPI
class twit{
private $_DST;
private $_method;
private $oauth_consumer_key;
private $oauth_consumer_secret;
private $oauth_nonce;
private $oauth_signature;
private $oauth_signature_method;
private $oauth_timestamp;
private $oauth_token;
private $oauth_token_secret;
private $oauth_version;
private $_prm;
private $_url;

public function __construct($id=''){
if(!$id)$id=ses('twid');//2;
$cols='consumer_key,consumer_secret,token_key,token_secret';
$r=sql($cols,'twitter','ra',$id);
if($r){
	$this->oauth_consumer_key=$r['consumer_key'];
	$this->oauth_consumer_secret=$r['consumer_secret'];
	$this->oauth_token=$r['token_key'];
	$this->oauth_token_secret=$r['token_secret'];}
$this->oauth_nonce=md5(rand());
$this->oauth_signature_method='HMAC-SHA1';
$this->oauth_timestamp=time();
$this->oauth_version='1.0';}

//build url
private function urlParams(){
return ['oauth_consumer_key'=>$this->oauth_consumer_key,
'oauth_nonce'=>$this->oauth_nonce,
'oauth_signature'=>$this->oauth_signature,
'oauth_signature_method'=>$this->oauth_signature_method,
'oauth_timestamp'=>$this->oauth_timestamp,
'oauth_token'=>$this->oauth_token,
'oauth_version'=>$this->oauth_version];}

private function mkprm($qr='',$sec=''){$ret='';
$r=$this->urlParams(); unset($r['oauth_signature']);
foreach($r as $k=>$v)$rt[]=$k.'='.rawurlencode($v);
$ret=implode('&',$rt);
if($qr)$ret=$qr.'&'.$ret;
if($sec)$ret.='&'.$sec;
$this->_prm=$ret;}

private function mkprm2(){$r=$this->urlParams();
foreach($r as $k=>$v)$rt[]=$k.'="'.rawurlencode($v).'"';
return implode(',',$rt);}

//webservice
private function send($url,$post){
$d=curl_init();//if(auth(6))pr($this->_DST);
curl_setopt($d,CURLOPT_URL,$url); //echo $url;
curl_setopt($d,CURLOPT_HTTPHEADER,$this->_DST);
if($post){
curl_setopt($d,CURLOPT_POST,TRUE);
curl_setopt($d,CURLOPT_POSTFIELDS,$post);}
curl_setopt($d,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($d,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($d,CURLOPT_RETURNTRANSFER,1);
$ret=json_decode(curl_exec($d),true);
return $ret;}

//publish
public function update($tweet){
$this->_url='https://api.twitter.com/1.1/statuses/update.json';
$this->_method='POST';
$qr='status='.rawurlencode($tweet);
$this->mkprm($qr);
$this->gen();
return $this->send($this->_url,$qr);}

public function like($id,$o=''){$mk=$o?'destroy':'create';
$this->_url='https://api.twitter.com/1.1/favorites/'.$mk.'.json';
$this->_method='POST';
$qr='id='.$id;
$this->mkprm($qr);
$this->gen();
return $this->send($this->_url,$qr);}

public function retweet($id,$o=''){$mk=$o?'unretweet':'retweet';
$this->_url='https://api.twitter.com/1.1/statuses/'.$mk.'.json';///'.$id.'
$this->_method='POST';
$qr='id='.$id;
$this->mkprm($qr);
$this->gen();
return $this->send($this->_url,$qr);}

public function login($d){
$this->_url='https://api.twitter.com/oauth/authenticate.json';
$this->_method='POST';
$qr='oauth_callback='.rawurlencode($d);
$this->mkprm($qr);
$this->gen();
return $this->send($this->_url.'?'.$qr,'');}

//follow
public function follow($id){
$this->_url='https://api.twitter.com/1.1/friendships/create.json';
$this->_method='POST';
$qr='user_id='.rawurlencode($id);
$sec='follow=true';
$this->mkprm($sec,$qr);
$this->gen();
return $this->send($this->_url,$qr.'&'.$sec);}

//tl
public function timeline($user,$count,$max='',$o=''){
$tl=$o?'home_timeline':'user_timeline';
$this->_url='https://api.twitter.com/1.1/statuses/'.$tl.'.json';
$this->_method='GET';
$qr='screen_name='.rawurlencode($user);
$sec='count='.rawurlencode($count).'&include_rts=1';
$sec.=$max?'&max_id='.rawurlencode($max):'';
$this->mkprm($sec,$qr);
$this->gen();
return $this->send($this->_url.'?'.$qr.'&'.$sec,'');}

//show
public function show($usr){
$this->_url='https://api.twitter.com/1.1/users/show.json';
$this->_method='GET';
if(is_numeric($usr))$qr='user_id='.$usr;
else $qr='screen_name='.rawurlencode($usr);
$this->mkprm('',$qr);
$this->gen();
return $this->send($this->_url.'?'.$qr,'');}

public function lookup($id){
$this->_url='https://api.twitter.com/1.1/users/lookup.json';
$this->_method='GET';
$qr='user_id='.rawurlencode($id);
$this->mkprm('',$qr);
$this->gen();
return $this->send($this->_url.'?'.$qr,'');}

//read
public function read($id){
$this->_url='https://api.twitter.com/1.1/statuses/show/'.$id.'.json';
$this->_method='GET';
$this->mkprm();
$this->gen();
return $this->send($this->_url,'');}

public function delete($id){
$this->_url='https://api.twitter.com/1.1/statuses/destroy/'.$id.'.json';
$this->_method='POST';
$qr='status='.rawurlencode($id);
$this->mkprm('',$qr);
$this->gen();
return $this->send($this->_url,$qr);}

//credentials
/*public function user($id){//obsolete
$this->_url='https://api.twitter.com/1.1/account/verify_credentials/'.$id.'.json';
$this->_method='GET';
$this->mkprm();
$this->gen();
return $this->send($this->_url,'');}

public function replies($id){//obsolete
$this->_url='https://api.twitter.com/1/related_results/show/'.$id.'.json';
$this->_method='GET';
$this->mkprm();
$this->gen();
return $this->send($this->_url,'');}*/

public function search($d,$nb='',$max='',$min='',$until=''){if(!$nb)$nb=40;
$this->_url='https://api.twitter.com/1.1/search/tweets.json';
$this->_method='GET';
$qr='q='.rawurlencode($d).'&result_type=popular';//;///recents/mixed
$sec='count='.rawurlencode($nb);
$sec.=$max?'&max_id='.($max):'';
//$sec.=$until?'&max_id='.($until):'';
//$sec.=$min?'&since_id='.($min):'';
$sec.=$until?'&until='.rawurlencode($until):'';
$this->mkprm($sec,$qr);
$this->gen(); //echo $this->_url.'?'.$qr.'&'.$sec;
return $this->send($this->_url.'?'.$qr.'&'.$sec,'');}

public function favorites($usr,$max='',$nb=40){
$this->_url='https://api.twitter.com/1.1/favorites/list.json';
$this->_method='GET';
$qr='q='.rawurlencode($usr);//;//popular//mixed
$sec='count='.rawurlencode($nb);
$sec.=$max?'&max_id='.rawurlencode($max):'';
$this->mkprm($sec,$qr);
$this->gen();
return $this->send($this->_url.'?'.$qr.'&'.$sec,'');}

public function retweets($id,$n=100){
$this->_url='https://api.twitter.com/1.1/statuses/retweets/'.$id.'.json';
$this->_method='GET';
$qr='count='.$n;//.'&trim_user=1'
$this->mkprm($qr);
$this->gen();
return $this->send($this->_url.'?'.$qr,'');}

public function retweeters($id,$n=''){if(!$n)$n=100;
$this->_url='https://api.twitter.com/1.1/statuses/retweeters/ids.json';
$this->_method='GET';
$qr='id='.$id;//.'&count=100&stringify_ids=true'
$this->mkprm($qr);
$this->gen();
return $this->send($this->_url.'?'.$qr,'');}

public function mentions($n=20,$id=''){
$this->_url='https://api.twitter.com/1.1/statuses/mentions_timeline.json';
$this->_method='GET';
$qr='count='.$n;
if($id)$qr.='&since_id='.$id;//'name='.rawurlencode($d).&
$this->gen();
return $this->send($this->_url.'?'.$qr,'');}

public function followers1($usr,$cursor=-1){//not works
$this->_url='https://api.twitter.com/1.1/followers/list.json';
$this->_method='GET';
$qr='screen_name='.$usr;
$sec='cursor='.$cursor;//.'&skip_status=true&include_user_entities=false'
$this->mkprm($sec,$qr);
$this->gen();
return $this->send($this->_url.'&'.$qr.'&'.$sec,'');}

public function followers($u){
$this->_url='https://api.twitter.com/1.1/followers/ids.json';
$this->_method='GET';
$qr='screen_name='.rawurlencode($u);
$sec='count=5000';//&cursor='.$cursor.'&skip_status=true&include_user_entities=false
$this->mkprm('',$qr);
$this->gen();
return $this->send($this->_url.'?'.$qr,'');}//.'&'.$sec,

public function embed($u){
$this->_url='https://publish.twitter.com/oembed';
$this->_method='GET';
$qr='url='.rawurlencode($u);
$this->mkprm($qr);
$this->gen();
return $this->send($this->_url.'?'.$qr,'');}

//signatures importantes pour l'OAuth
private function gen(){
$signature=rawurlencode($this->_method).'&'.rawurlencode($this->_url).'&'.rawurlencode($this->_prm);
$signing_key=rawurlencode($this->oauth_consumer_secret).'&'.rawurlencode($this->oauth_token_secret);
$this->oauth_signature=base64_encode(hash_hmac('SHA1',$signature,$signing_key,TRUE));
$this->_DST=['Authorization: OAuth '.$this->mkprm2()];}
}
?>