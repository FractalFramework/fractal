<?php
class headings{

function extract(DOMDocument $dom,int $max_level=6){
$xpath=new DOMXPath($dom);
$xpath->registerNamespace('html','http://www.w3.org/1999/xhtml');
$max_level=min(max($max_level,1),6);//Les titres en HTML vont de h1 à h6 maximum
$headings_queries=[];
//Les requêtes XPath partent du nœud courant,pour chercher les balises `hX`
foreach(range(1,$max_level) as $h){$headings_queries[]='self::h'.$h;}
$query_headings=implode(' or ',$headings_queries);//On sélectionne toutes les balise `h1` ou `h2` ou `h3`,etc.
$query='//*['.$query_headings.']';//On part de la racine pour chercher les balises en question
$headings=$xpath->query($query);
$ret='';
if(count($headings)){
	$current_level=0;
	$items=0;
	foreach($headings as $n_i=>$node){
		$level=(int) $node->tagName[1];
		$node_id=$node->getAttribute('id');
		//ID du titre
		if(empty($node_id)){
			$node_id='toc_'.$this->url_title(strip_tags($node->textContent),'-',TRUE);
			$node->setAttribute('id',$node_id);}
		//lien vers le titre
		$new_toc='<a href="#'.$node_id.'">'.$this->xss_clean($node->textContent).'</a>';
		if($level>$current_level){
			for($a=0;$a<$level-$current_level;$a++){
				$ret.='<ol class="toc-level-'.$level.'"><li>';}
			$ret.=$new_toc;
			$items=1;}
			elseif($level===$current_level){
				$ret.=($items?'</li>':'').'<li>'.$new_toc;
				$items++;}
		else{
			for($a=0;$a<$current_level-$level;$a++){$ret.='</li></ol>';}
			$ret.='</li><li>'.$new_toc;
			$items=0;}
		$current_level=$level;}
	
	for($a=$level-1;$a >=0;$a--){$ret.='</li></ol>';}
}
return $ret;}
	
function url_title($d,$s,$l){
$res=implode($s??'-',explode(' ',$d));
return $l?strtolower($res):$res;}

function xss_clean($d){
return htmlspecialchars($d);}

function com($d){
$dom=new DOMDocument('2.0','UTF-8');
$dom->loadHTML($d,LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
$toc=$this->extract($dom);
$d=$dom->saveHTML();//avec ancres
$ret=$toc;//sommaire
$ret.="\n".'<hr>'."\n";
$ret.=$d;//contenu
return $ret;}

}
?>