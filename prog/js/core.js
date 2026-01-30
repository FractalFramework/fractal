//Fractal GNU/GPL
//html
function atb(a,p){if(p)return ' '+a+'="'+p+'"';}
function tag(tag,r,t,o=''){
var p='';
for(var k in r)p+=atb(k,$r[k]);
return '<'+tag+p+'>'+(t || o?t+'</'+tag+'>':'');}

//localstorage
function memStorage(val){
var vn=val.split('_'); var ob=getbyid(vn[0]);
if(vn[2]=='sav')localStorage[vn[1]]=vn[3]==1?ob.innerHTML:ob.value;
if(vn[2]=='res'){
	if(vn[3]==1)ob.innerHTML=localStorage[vn[1]];
	else ob.value=localStorage[vn[1]];}}

//timer
function opac(op,id){getbyid(id).style.opacity=(op/100);}
function bkg(op,id){getbyid(id).style.backgroundColor='rgba(0,0,0,'+(op/100)+')';}
function resiz(op,id){getbyid(id).style.height=(op/100)+'px';}
function Timer(func,id,start,end,t){
var timer=10;
if(typeof id==='undefined' || id=='')return;
if(start>end){
	for(i=start;i>=end;i-=10){
		timer++; curi=i;
		x=setTimeout(func+"("+i+",'"+id+"')",timer * t);}}
else if(start < end){
	for(i=start;i<=end;i+=10){
		timer++;
		x=setTimeout(func+"("+i+",'"+id+"')",timer * t);}}}

function slowclose(id){
if(typeof x != 'undefined')clearTimeout(x);if(typeof xb != 'undefined')clearTimeout(xb);
Timer('opac',id,100,0,10); xb=setTimeout('Close('+id+')',1000);}

//buttons
function ajdel(call,prm,inp){
var ok=confirm('really?');
if(ok)ajaxcall(call,prm);}

//verif
function isEmail(myVar){
var regEmail=new RegExp("^[0-9a-z._-]+@{1}[0-9a-z.-]{2,}[.]{1}[a-z]{2,5}$","i");
return regEmail.test(myVar);}

function verifchars(e){
var va=e.value;
var arr=[',','?',';','.',':','/','!','|',' ','"',"'",'(',')','=','+','$','*','%','<','>',' ','|','-','~','&','^','�','�','�','�','�','�','�','@','{','}','[',']','`','^','�','�','^','�','#','\\'];//'_',
for(i=0;i < arr.length;i++)va=va.replace(arr[i],'');
if(Number(va.substr(0,1)))va=va.substr(1); //va=va.toLowerCase();
e.value=va;}//.toLowerCase()

//fixdiv
function fixdiv(ob){
var scrl=pageYOffset; var dim=innersizes();
var div=getbyid(ob); var pdiv=getPositionAbsolute(div);
if(typeof xtop==='undefined')xtop=pdiv.y;
if(typeof diff==='undefined')diff=pdiv.h - dim.h;if(diff < 0)diff=0;
if(scrl<=xtop+diff){
	div.style.top='';
	div.style.position='relative';}
else if(diff>0){
	div.style.top=(0 - diff)+'px';
	div.style.position='fixed';}
else {
	div.style.top='0';
	div.style.position='fixed';}}

function fixdiv_resize(ob){
var dim=innersizes();
var div=getbyid(ob); var pdiv=getPositionAbsolute(div);
diff=pdiv.h - dim.h;if(diff < 0)diff=0;}

function togglediv(id,o){
var div=getbyid(id);
if(o){div.style.display='block'; clbubob={esc: id,cl: id,bt: ''};}
else div.style.display='none';}

//states
function updateurl(u,j){
var n=u.indexOf('/'); //pr(u); //return;
var a=n?u.substring(0,n): u; var p=n?u.substring(n+1): '';if(j=='undefined')j='';
var r={u: u,'app': a,'p': p,'j': j};if(u != '/')u='/'+u; //pr(r);
window.history.pushState(r,a,u);}//window.location=u;

/*function restorestate(st){if(!st)return;
if(st.a=='menu')SaveBg(st.i,1);//abort update
else if(st.a=='module' && st.j)SaveJ(st.j);
else if(st.a=='art' && st.j){SaveJ(st.j); document.title=st.t;}//?st.t:recuptit()
else if(st.a=='context' && st.j){let r=st.u.split('/').splice(3);
for(var k in r)r[k]=ajx(r[k]); var g=r.join('_');//g=st.p;
SaveJ('page_mod,playcontext___'+g); document.title=st.t;}
else if(st.j)SaveJ(st.j);}*///unused

function startstate(index){//pr(index);
var u=document.URL; var ru=u.split('/'); var a=ru[3]; var p=ru[4];
if(!a)a='home';if(p==undefined)p='';
if(a=='apps' || a=='goodies'){p='app='+a; a='home';}
var j='main|'+index+',content|app:'+a+',p:'+p; //pr(j);
var r={u: u,'app': a,'p': p,'j': j}; document.title=a;
window.history.replaceState(r,a,u);}

//window.onpopstate=function(e){restorestate(e.state);}
window.onpopstate=function (e){var j=e.state.j; ajx(j?j:'page|root');}
window.onload=function (e){startstate(index);}

//fullscreen
function getFullscreenElement() {
	//|| document.webkitFullscreenElement || document.mozFullscreenElement || document.msFullscreenElement;
	return document.fullscreenElement;
}

function toggleFullscreen(id){
if(getFullscreenElement()){document.exitFullscreen();}
else{getbyid(id).requestFullscreen().catch(console.log);}}

//getbyid('btfs').addEventListener('click',()=>{toggleFullscreen('div');});

//scroll
function mouse(ev){
if(ev.pageX || ev.pageY){return {x:ev.pageX,y:ev.pageY};}
return{
	x:ev.clientX+document.body.scrollLeft-document.body.clientLeft,
	y:ev.clientY+document.body.scrollTop-document.body.clientTop};}

function scrolltopos(id){//var ob=getbyid(id);
var ob=document.querySelector('#'+id);
var sz=innersizes(); var h=sz.h/2; var w=sz.w/2;
var ox=ob.getAttribute('x'); var oy=ob.getAttribute('y');
var nx=ox-w>0?0:ox-w; var ny=oy-h>0?0:oy-h;
window.scroll(oy-h,ox-w);}//scrollslide(ny,nx);

function scrollslide(oy,ox){
var wy=window.scrollY; var wx=window.scrollX;
if(wy>oy)wy-=1; else wy+=1;
if(wx>ox)wx-=1; else wx+=1;
window.scrollTo(wy,wx);}
//x=setTimeout(function(){scrollslide(oy,ox)},100);
//if(wy-oy<10 && wx-ox<10)clearTimeout(x);

function scrollBottom(d){var div=getbyid(d); div.scrollTo(0,div.scrollHeight);}
function scrollTop(d){
	if(d=='pagup')d='pgu'+(popnb); if(d=='popup')d='popu'+(popnb);
	var div=getbyid(d); if(div)div.scrollTo(0,0);}
function randid(d){var n=Math.random()+''; return d+(n.substr(2,7));}
function active(bt,o){if(bt==undefined)return; var css=bt.className; if(css==undefined)css='';
	if(o)bt.className=css+' active'; else bt.className=css.split(' ')[0];}

//mb
function encode_utf8(s){return unescape(encodeURIComponent(s));}
function substr_utf8_bytes(str,startInBytes,lengthInBytes){
var resultStr=''; var startInChars=0;
for(bytePos=0; bytePos < startInBytes; startInChars++){
	ch=str.charCodeAt(startInChars);
	bytePos+=(ch < 128)? 1:encode_utf8(str[startInChars]).length;}
end=startInChars+lengthInBytes - 1;
for(n=startInChars; startInChars<=end; n++){
	ch=str.charCodeAt(n);
	end-=(ch < 128)? 1:encode_utf8(str[n]).length;
	resultStr+=str[n];}
return resultStr;}

function maxlength(id,limit){
var tx=getbyid(id).innerHTML;
if(tx.length>limit)tx.innerHTML=tx.substring(limit);}

function isNumeric(n){return !isNaN(parseFloat(n))&& isFinite(n);}

function numonly(e){
if(e.value && !isNumeric(e.value))e.className='error';
else e.className='';}

function closeditor(){
if(exb.indexOf(pid)== -1)exb.push(pid); var n=exb.length;
if(n>0)for(var i=0;i < n;i++)if(exb[i] && exb[i] != pid){
	var bt=getbyid(exb[i].substr(3));if(bt){bt.rel=''; bt.className='';}
	Close(exb[i]); exb[i]=0;}}

function closebt(f,rid){
Close('bt'+rid); //pr(rid);
var d=getbyid(rid); var ty=d.type;
if(d)var t=ty=='text'?d.value:d.innerHTML;//pr(t);
var tb=t.replace('['+f+']','');//:img
if(ty=='text')d.value=t; else d.innerHTML=t;}

//select
var slct=false;
var clientPC=navigator.userAgent.toLowerCase();
var clientVer=parseInt(navigator.appVersion);
var is_ie=((clientPC.indexOf('msie')!= -1)&& (clientPC.indexOf('opera')== -1));
var is_win=((clientPC.indexOf('win')!= -1)|| (clientPC.indexOf('16bit')!= -1));

function storeCaret(d) {//insert at Caret position
	if (d.createTextRange) d.caretPos = document.selection.createRange().duplicate();
}

function setSelectionRange(input,start,end){
if(input.setSelectionRange){
	input.focus();
	input.setSelectionRange(start,end);}
else if(input.createTextRange){
	var range=input.createTextRange();
	range.collapse(true);
	range.moveEnd('character',end);
	range.moveStart('character',start);
	range.select();}}

//editors
function embed_div(opn,clo,id){//use getrange
var ob=getbyid(id); var len=slct.length; //alert(len); alert(elStart+'-'+elEnd);
var s1=(ob.innerHTML).substring(0,elStart);
var s2=(ob.innerHTML).substring(elStart,elEnd);
var s3=(ob.innerHTML).substring(elEnd,len);
ob.innerHTML=s1+opn+s2+clo+s3;}

function embed_slct(debut,fin,id,act){//only value
//var e=getrangepos(id); alert(e);
var ob=getbyid(id); ob.focus(); //alert(ob.selectionStart);
donotinsert=false; slct=false;
if((clientVer>=4)&& is_ie && is_win){
	slct=document.selection.createRange().text;
	if(slct){
		while (slct.substring(slct.length - 1,slct.length)== ' '){
			slct=slct.substring(0,slct.length - 1);}
		document.selection.createRange().text=debut+slct+fin;
		ob.focus(); slct=''; return slct;}}
else if(ob.selectionEnd && (ob.selectionEnd - ob.selectionStart>0)){
	slct=mozWrap(debut,fin,id,act);
	return slct;}
else insert(debut+fin,id);}

function insert(text,id,el){
var ob=getbyid(id,el);
if(ob.createTextRange && ob.caretPos){
	var caretPos=ob.caretPos; var ct=caretPos.text;
	caretPos.text=ct.charAt(ct.length - 1)== ' '?ct+text+' ':ct+text;}
//else if(typeof id.value==='undefined')
else {mozWrap('',text,id,1); return;}}

//http://www.massless.org/mozedit/
function mozWrap(opn,clo,id,os){
var s1=''; var s2=''; var s3='';
var ob=getbyid(id); var vl=1;//
if(typeof ob.value==='undefined')var vl=0;
var len=ob.textLength;
var st=ob.selectionStart;
var nd=ob.selectionEnd;
var top=ob.scrollTop;
if(nd==1 || nd==2)nd=len;
if(vl)var truend=(ob.value).substring(nd - 1,nd);
if(nd - st>0 && truend==' ')nd=nd - 1;
if(nd - st>0 && truend=="\n")nd=nd - 1;
if(vl){
	var s1=(ob.value).substring(0,st);
	var s2=(ob.value).substring(st,nd);
	var s3=(ob.value).substring(nd,len);
	ob.value=s1+opn+s2+clo+s3;}
else ob.innerHTML=s1+opn+s2+clo+s3;
var end=nd+clo.length+opn.length;
window.setSelectionRange(ob,os?end:st,end);
ob.scrollTop=top;
ob.focus();
return s2;}

//stabilo
function getrange(id){
var ob=getbyid(id);
elStart=0; elEnd=0;
var doc=ob.ownerDocument || ob.document;
var win=doc.defaultView || doc.parentWindow;
var sel;
if(typeof win.getSelection != "undefined"){
	sel=win.getSelection(); //sel=encode_utf8(sel);
	if(sel.rangeCount>0){
		var range=win.getSelection().getRangeAt(0);
		var preCaretRange=range.cloneRange();
		preCaretRange.selectNodeContents(ob);
		preCaretRange.setEnd(range.endContainer,range.endOffset);
		elEnd=preCaretRange.toString().length;}}
else if((sel=doc.selection)&& sel.type != "Control"){
	var textRange=sel.createRange();
	var preCaretTextRange=doc.body.createTextRange();
	preCaretTextRange.moveToElementText(ob);
	preCaretTextRange.setEndPoint("EndToEnd",textRange);
	elEnd=preCaretTextRange.text.length;}
slct=sel.toString();
if(slct.substring(slct.length - 1,slct.length)== ' '){slct=slct.substring(0,slct.length - 1); elEnd-=1;}
var elStart=elEnd - slct.length;
return {start: elStart,end: elEnd,txt: slct};}

function useslct(e,id,aid){
var d=getrange(id);
//ajx('popup|stabilo,add_note|id='+aid+',start='+d.start+',end='+d.end+',txt='+jurl(d.txt));
var prm='id:'+aid+',start:'+d.start+',end:'+d.end+',txt:'+jurl(d.txt);
var url='/call.php?_a=stabilo&_m=add_note&_p='+prm+'&popup==';
if(d.txt)var ajax=new AJAX(url,'popup','','');}

function wygedt(e,id,aid){
var d=getrange(id);
var prm='id:'+aid+',start:'+d.start+',end:'+d.end+',txt:'+jurl(d.txt);
var url='/call.php?_a=build&_m=wygedt&_p='+prm+'&popup==';
if(d.txt)var ajax=new AJAX(url,'popup','','');}

function wygedt2(id){
var d=getrange(id);
if(d.txt)ajx('bubble|stabilo,add_note|id='+aid+',start='+d.start+',end='+d.end+',txt='+jurl(d.txt));}

//diveditable
function insertChar(t,id){
var ob=getbyid(id);
var range=window.getSelection().getRangeAt(0);
if(range.startContainer.nodeType===Node.TEXT_NODE){
	range.startContainer.insertData(range.startOffset,t);}}

function handleKeyUp(e,id){
//var e=e||window.event;
var char,key;
var key=e.keyCode; //console.log(key);
//if(key==34)char='"';//lol
if(key==40)char=')';//41
if(key==91)char=']';//93
if(key==123)char='}';//125
if(char)insertChar(char,id);}

function completion(id){
var ob=getbyid(id);
ob.addEventListener("keypress",handleKeyUp(event));}

function embedconn(t,id){
var ob=getbyid(id);
var d=ob.innerHTML;
var r=getrange(id); var p=r.end;
console.log(p);
ob.innerHTML=d+t;}

function divedit(e){
var charTyped=String.fromCharCode(e.which);
if(charTyped=="{" || charTyped=="("){
	//Handle this case ourselves
	e.preventDefault();
	var sel=window.getSelection();
	if(sel.rangeCount>0){
		//First,delete the existing selection
		var range=sel.getRangeAt(0);
		range.deleteContents();
		//Insert a text node with the braces/parens
		var text=(charTyped=="{")? "{}":"()";
		var textNode=document.createTextNode(text);
		range.insertNode(textNode);
		//Move the selection to the middle of the text node
		range.setStart(textNode,1);
		range.setEnd(textNode,1);
		sel.removeAllRanges();
		sel.addRange(range);}}}

//verifs
function strcount(id,limit=768){
var ob=getbyid(id); var n=ob.value.length;
if(n>=limit)ob.value=(ob.value).substr(0,limit);
var n=ob.value.length;
getbyid('strcnt'+id).innerHTML=limit - n;}

function strcount2(rid){
var limit=768; var on=500;
var id=rid?rid:idd; var that=rid?getbyid(rid): this;//used by local answer or global form
var tx=that.type=='textarea'?that.value:that.innerHTML;
var tn=getbyid('strcnt'+id); var tb=getbyid('edtbt'+id);
if(tx.length>limit - on)tn.innerHTML=limit - tx.length; else tn.innerHTML='';
if(tx.length>limit)tn.className='btko'; else if(tx.length>limit - on)tn.className='btok'; else tn.className='';
if(tx.length>limit)tb.style.display='none'; else tb.style.display='';}

function autoResizeHeight(rid){
var ob=getbyid(rid);
ob.style.height=0; ob.style.height=(ob.scrollHeight+2)+"px";}

function opedit(){getbyid('edtbt').style.display='block';}
function cledit(){getbyid('edtbt').style.display='none';}
function holder(){
var ty=this.type=='textarea'?1:0;
var tx=ty?this.value:this.innerHTML;
if(!tx)this.placeholder='hello';}

function reposfocus(){
var d=getrange(idd);
this.selectionStart=d.en;}

function myTrim(d) { return d.replace(/^\s+|\s+$/gm, ''); }

function dom_r(ob,o=0){
if(o==10)return;
var c=ob.childNodes,n=c.length,typ=0,tag='',res='';
console.log('--'+o);
for(i=0;i < n;i++)if(typeof c[i]=='object'){//nodeName//
	//parent.appendChild(c[i]);
	console.log('--------- '+i+' -------------');
	console.log("nodeName  => "+c[i].nodeName);
	console.log("nodeType  => "+c[i].nodeType);
	console.log("nodeValue => "+(c[i].nodeValue));//escape
	typ=c[i].nodeType;//3=value,1=node
	tag=c[i].nodeName;//div
	if(typ==1){
		res=res+=dom_r(c[i],o+1);}
	else if(typ==3){
			if(tag=='BR')res+='';
			else if(tag=='DIV')res+='<p>'+(c[i].nodeValue)+'</p>';
			else if(tag=='A')res+='<a href="'+(c[i].href)+'">'+(c[i].nodeValue)+'</a>';
			else if(tag=='#text')res+=(c[i].nodeValue);}}
return res;}

function cleanuphtml(){
var ob=getbyid(idd);}
//var ty=this.type=='textarea'?1:0;
//ajx(idd+';appedt|editor,actions|id='+idd+'|'+idd);
//var d=getrange(idd);
//alert(d.en);
//var res=dom_r(ob);
//console.log('=');
//console.log(res);

//editor
function editor(id,ev){
var ob=getbyid(id);idd=id; //var od=getbyid('addpst'+id);
ob.addEventListener('keyup',autoResizeHeight,0);
ob.addEventListener('paste',autoResizeHeight,0);
ob.addEventListener('keyup',strcount2,0);
ob.addEventListener('paste',strcount2,0);
//od.addEventListener('focus',opedit,0);
//od.addEventListener('blur',cledit,0);
//ob.addEventListener('keyup',holder,0);
//ob.addEventListener("keypress",divedit(e),false);
ob.addEventListener('keyup',cleanuphtml,0);
//	ob.addEventListener('paste',cleanuphtml,0);
//addEvent(ob,'focus',function(){var that=this;
//	setTimeout(function(){that.selectionStart=that.selectionEnd=10000;},0)});
//ob.addEventListener('keypress',divedit(event),false);
ob.addEventListener('keypress',function (event){handleKeyUp(event,id);},false);
ob.addEventListener('dblclick',wygedt2(id),false);}
//alert(e);

//upload
function upload(rid,usr){
var form=getbyid('upl'+rid);
var fileSelect=getbyid('upfile'+rid);
var files=fileSelect.files;
var div=getbyid(rid);
var fd=new FormData();
uploaded=0;
for(var i=0;i < 4;i++){//files.length
	var time=Date.now();//Math.floor(Date.now()/1000);
	var file=files[i]; //pr(files);
	if(!file)continue;
	var xtr=file.name.split('.'); var xt=xtr[xtr.length - 1];
	if(file.type.match('image.*'))var ty='img';
	else if(file.type.match('audio.*'))var ty='audio';//audio/mpeg /mid
	else if(file.type=='video/mp4')var ty='video';
	else if(xt=='xls' || xt=='csv' || xt=='txt')var ty='csv';
	else if(xt=='tar' || xt=='zip' || xt=='gz')var ty='archive';
	else if(xt=='xhtml' || xt=='html' || xt=='xml')var ty='xml';
	else continue;
	//console.log(file.type+'-'+xt);
	if(ty=='img')var filename=''+time+'.'+xt;
	else if(ty=='video')var filename=''+time+'.'+xt;
	else var filename=ty+'/'+time+'.'+xt;
	fd.append('upfile'+rid,file,filename);
	if(div.type=='text')div.value=filename;
	else if(ty=='img')insert('['+filename+':img]',rid);
	else if(ty=='csv')val(filename,rid);
	else if(ty=='video')insert('['+usr+'/video/'+filename+']',rid);
	else insert('['+filename+':'+ty+']',rid);//
	//var ridb=dedicated_div(rid,i);
	//if(ty!='img')
	if(filename)upload_progress(rid);
	var prm='rid:'+rid+',ty:'+ty;//getinp:1
	var url='/call.php?_a=upload&_m=save&_p='+prm;
	if(ty=='img')var ajax=new AJAX(url,'after','upl'+rid+'','z',fd,'');
	else var ajax=new AJAX(url,'div',''+rid+'up','xb',fd);}}

function cancelupload(rid){clearTimeout(xb); uploaded=0;inn('',rid+'up');}

function upload_progress(rid){
if(uploaded==100)var div=''; else
	var div='<progress value=\"'+uploaded+'\" max=\"100\"></progress><a onclick=\"cancelupload(\''+rid+'\')\" class=\"btdel\">x</a>';
inn(div,rid+'up');//getbyid(rid+'up').innerHTML=div;
xb=setTimeout(function (){upload_progress(rid)},100);}

function dedicated_div(tar,n){
var ob=getbyid(tar);if(ob==null)return;
var div=document.createElement('div'); div.id=tar+'_'+n; ob.appendChild(div);
return tar+'_'+n;}

//continuous scrolling
var exs=[]; var prmtm='';
function loadscroll(component,div){
var content=getbyid(div);
if(typeof content !== 'object')return;
var prmtm=String(getbyid('prmtm').value);
if(prmtm)prmtm+=','; else return;
var scrl=pageYOffset+innerHeight - 100;
var mnu=content.childNodes;
var last=mnu[mnu.length - 1];if(!last)return;
var id=last.id; pr(id);
var pos=getPositionAbsolute(last);
var idx=exs.indexOf(id);
if(idx==-1 && scrl>pos.y){
	exs.push(id);
	var call='after,'+id+'|'+component; var idn=id.substr(3); //alert(idn);
	if(parseInt(idn))ajaxcall(call,prmtm+'from='+idn);}}
//addEvent(document,'scroll',function(event){loadscroll('app,meth','div')});

//gps (fortlex editor)
var rid=0;
function gps_ko(error){
switch (error.code){
	case error.PERMISSION_DENIED: pr('gps: refus'); break;
	case error.POSITION_UNAVAILABLE: pr('gps: impossible'); break;
	case error.TIMEOUT: pr('gps: ne r�pond pas'); break;}}

//keyPressEnter
function checkenter(e,o){
if(e && e.which)char=e.which; else char=e.keyCode;
if(char==13){document.forms[o].submit(); return false;}
else return true;}

function checkj(e,o,a){
if(e && e.which)var char=e.which; else var char=e.keyCode; //pr(char);
if(char==13 || a){var d=o.dataset.j;if(d)ajx(d,o); return false;}
else return true;}

function callj(e,o){
if(e && e.which)var char=e.which; else var char=e.keyCode;
if(char != 37 && char != 38 && char != 39 && char != 40){var d=o.dataset.ja;if(d)ajx(d,o); return false;}
else return true;}

//getSelection
/*function focuspos(id){var ob=getbyid('txt'+id);
if(ob.setSelectionRange)return ob.value.substring(ob.selectionStart,ob.selectionEnd);
else if(document.selection){ob.focus(); return document.selection.createRange().text;}}*/

//art
function format(p){document.execCommand(p,false,'http');}
function format2(d){document.execCommand('formatBlock',false,'<'+d+'>'); getbyid('wygs').value='no';}
function fontsz(n){var txt=document.getSelection(); alert(txt);}
function savtim(id){
if(sok){
	xa=setTimeout("savtim("+id+")",10000);
	ajaxcall("socket|art,savetxt","id="+id,"txt"+id);}}
function restore_art(id){editbt(id,2); getbyid("txt"+id).innerHTML=localStorage["m3"];}
function backsav(e,id){//13=enter,46=dot pr(char);
if(e && e.which){char=e.which;} else {char=e.keyCode;}
if(char==13)ajaxcall("socket|art,savetxt","id="+id,"txt"+id);}

function editxt(div,id,o){
var ob=getbyid(div+id);
if(ob.className != "editon"){
	if(div=="txt" && o != 2)ajaxcall("div,txt"+id+"|art,playconn","id="+id,"");
	ob.contentEditable="true";
	ob.designMode="on"; void 0; //ob.focus();
	ob.className="editon";}}

function savtxt(div,id,opn=0){
var ob=getbyid(div+id);
if(!opn){ob.contentEditable="false"; ob.designMode="off"; ob.className="editoff";}
if(div=="tit")ajaxcall("div,tit"+id+"|art,savetxt","id="+id,"tit"+id);
if(div=="txt")ajaxcall("div,txt"+id+",z|art,savetxt","id="+id,"txt"+id);}

function editbt(id,o){
var bt=getbyid("bt"+id);
if(bt.rel==1 && !o){
	bt.rel=0; sok=0; //close
	ajaxcall("div,bt"+id+"|art,editbt","id="+id+",o=0","");
	savtxt("txt",id); //pr(bt.rel);
	getbyid("edt"+id).style.display="none";}
else {
	bt.rel=1; sok=1; editxt("txt",id,o);  //pr(bt.rel);//if(!o)savtim(id);//open
	ajaxcall("div,bt"+id+"|art,editbt","id="+id+",o=1","");
	//if(!o)ajaxcall("socket,"+id+",store|art,savetxt","id="+id,"txt"+id);//backup
	getbyid("edt"+id).style.display="inline-block";}}

//editable
function striptags(d) { return d.replace(/<\/?[^>]+(>|$)/g, ""); }

function savecell(id,j,e){
var prm=j.split('|'); var t=getbyid('d'+id,e).innerHTML;
getbyid('d'+id,e).innerHTML=(t);//striptags
ajaxcall('div,d'+id+',z|'+prm[0],prm[1]+',id='+id,'d'+id,e);}

//appx
function multhidden(n,id){
var r=[];
for(i=1;i<=n;i++){var v=getbyid(id+i).value;if(v)r.push(v);}
getbyid(id).value=r.join('|');}

//chat
chatliv=4000;
function chatlive(){
if(getbyid('chtbck')){
	var room=getbyid('chtroom').value;
	ajaxcall('div,chtbck|chat,read','vu=1,id='+room);}
setTimeout("chatlive()",chatliv);}
//if(chatliv)chatlive();

//json
function json(f){
var req=new XMLHttpRequest(); req.open("GET",f,true);
req.onreadystatechange=jsonread; req.send(null);}
function jsonread(){//var d=doc.menu.value;
if(req.readyState==4)var doc=eval('('+req.responseText+')');}

//map
rid=0;
function gps_paste(position){
var gpsav=position.coords.latitude+"/"+position.coords.longitude; val(gpsav,'coords');
var d=new Date; val(d.toDateString(),'address');}

function geo2(id){
rid=id;
if(navigator.geolocation)navigator.geolocation.getCurrentPosition(gps_paste,gps_ko,{enableHighAccuracy: true,timeout: 10000,maximumAge: 600000});}

//date
function date2ts(d){//26-02-2012
var r=d.split("-"); var dt=new Date(r[2],r[1] - 1,r[0]);
return dt.getTime();}

//profile
function geo(){
if(navigator.geolocation)navigator.geolocation.getCurrentPosition(gps_ok,gps_ko,{enableHighAccuracy: true,timeout: 10000,maximumAge: 600000});
else p("need html5");}
function gps_ok(position){
var gpsav=position.coords.latitude+"/"+position.coords.longitude;
ajaxcall("div,gpsloc|profile,gpsav","gps="+gpsav);}

//drag
function insertBefore(ob1,ob){ob.parentNode.insertBefore(ob1,ob);}
function insertAfter(ob1,ob){ob.parentNode.insertBefore(ob1,ob.nextSibling);}
function removeAfter(ob,ob1){ob.nextSibling.removeChild(ob1);}
function ev_dt(ev){var evd=ev.datatransfer;if(evd==undefined)evd=ev.dataTransfer; return evd;}
function ev_ob(evd){var id=evd.getData("text/plain"); return getbyid(id);}

function drag_start(ev){
var evd=ev_dt(ev);
evd.setData("text/plain",ev.target.id);
order=ordlist(ev.target);
evd.dropEffect="copy";
ev.dropEffect="move";
ev.target.className="dragover";}

function drag_over(ev){
ev.preventDefault();
var evd=ev_dt(ev);
evd.dropEffect="move"
ev.dropEffect="move";
var ob1=ev_ob(evd);
ev.target.className="dropper";}

function drag_leave(ev){
ev.preventDefault();
var evd=ev_dt(ev);
ev.target.className="dragme";}

function drag_drop(ev,j){
ev.preventDefault();
var evd=ev_dt(ev);
var ob1=ev_ob(evd);
var n1=ob1.id; var n2=ev.target.id;
if(order[n1] < order[n2])insertAfter(ob1,ev.target);//1
else insertBefore(ob1,ev.target);//1
var rt=idlist(ob1,ev.target);
if(j != undefined)ajx(j+rt);
ev.target.className="dragme";}

function drag_end(ev){
ev.preventDefault();
var evd=ev_dt(ev);
var ob1=ev_ob(evd);//moved
if(evd.dropEffect=='move')
	ev.target.parentNode.replaceChild(ob1,ev.target);//1
ev.target.className="dragme";}

function ordlist(ob){
var rt=[];
var r=ob.parentNode.getElementsByTagName('div');
for(i=0;i < r.length;i++){var k=(r[i].id); rt[k]=i;}
return rt;}

function idlist(ob){
var rt=[];
var r=ob.parentNode.getElementsByTagName('div');
for(i=0;i < r.length;i++)rt[i]=r[i].id;
return rt.join(';');}

/*drop img*/
function output(text,ob){ob.textContent+=text;}
function dropenter(ev){ev.target.textContent=''; ev.stopPropagation(); ev.preventDefault();}
function dropover(ev){ev.stopPropagation(); ev.preventDefault();}
function dropok(ev,rid){
ev.stopPropagation(); ev.preventDefault(); var fd=new FormData(); uploaded=0;
var dt=ev.dataTransfer; var rf=dt.files; var n=rf.length;
for(var i=0;i < n;i++)upim(rf[i],fd,rid);}

function upim(file,fd,rid){
var time=Date.now(); //.name,.size
var xtr=file.name.split('.'); var xt=xtr[xtr.length - 1];
if(file.type.match('image.*'))var ty='img'; else return;
var filename=''+time+'.'+xt;
fd.append('upl'+rid,file,filename);
if(filename)upload_progress(rid);
var url='/call.php?_a=dragdl&_m=upload&_p=rid:'+rid+',ty:'+ty;
var ajax=new AJAX(url,'after',rid+'up','z',fd);}

//unused
function decodeBase64(s){
var e={},i,b=0,c,x,l=0,a,r='',w=String.fromCharCode,L=s.length;
var A="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
for(i=0;i < 64;i++)e[A.charAt(i)]=i;
for(x=0; x < L; x++){
	c=e[s.charAt(x)]; b=(b << 6)+c; l+=6;
	while (l>=8)((a=(b >>> (l-=8))& 0xff)|| (x < (L - 2)))&& (r+=w(a));}
return r;}

//autorefresh
tim=0;
function getftime(f){ajaxcall("returnVar,ftres|file,fdate","fileRoot="+f); return res;}
function arload(f){var ftim=getftime(f);if(ftim>tim)window.location=document.URL; autorefresh(f);}
function autorefresh(f,x){if(x)clearTimeout(timr); else timr=setTimeout(function (){arload(f)},2000);}

//utils
function closediv(id,el){var d=getbyid(id,el);if(id && d)d.innerHTML='';}
/*function closediv0(id){var d=getbyid(id); var h=d.innerHeight; pr(id+'-'+h);//
d.style.height=h+'px'; d.innerHTML=''; Timer('resiz',id,h,0,10);}*/

function repaircode(id){
d=getbyid(id).innerHTML;
d=strreplace('=','=',d);
d=strreplace(' (','(',d);
d=strreplace(')',')',d);
d=strreplace(' {','{',d);
d=strreplace('  ',' ',d);
d=strreplace(',',',',d);
d=strreplace(' . ','.',d);
getbyid(id).innerHTML=d;}

function cleanmail(id) {
	d = getbyid(id).innerHTML;
	d = strreplace("\r", "\n", d);
	d = strreplace('<br>', "\n", d);
	d = strreplace('<br />', "\n", d);
	$d = strreplace("M.\n", 'M. ', d);
	$d = strreplace(".\n", '.��', d);
	$d = strreplace("\n", '�', d);
	$d = strreplace('��', "\n\n", d);
	$d = strreplace('�', ' ', d);
	//console.log(d);
	getbyid(id).innerHTML = d;
}

//li.ul
function act(ob,a){
var op=ob.className;
if(op.indexOf('active')== -1 && !a){ob.classList.add("active"); return 1;}
else {ob.classList.remove("active"); return 0;}}

function liul(el){
var a=act(el);
var ul=el.parentNode.getElementsByTagName("ul");
ul[0].className=a?'on':'off';}

function invertclr(clr){
var vclr=parseInt(clr,16);//var nclr=16777215-vclr;
if(vclr>6388607)return 'black'; else return 'white';}

function applyclr(e,o){
var d=e.value; var d1=''; var d2='';
if(d.indexOf('-')!= -1 || d.indexOf('.jpg')!= -1 || d.indexOf('.png')!= -1)d='';
if(d.indexOf(',')!= -1){var r=d.split(','); var n=r.length - 1; var d=r[n];}
//if(d && d.length<6){for(i=0;i<6;i++){var d2=d[i]?d[i]:d2; d1=d1+d2;} if(d1)d=d1;}
if(o && d)document.body.style.backgroundColor='#'+d;
if(d){e.style.backgroundColor='#'+d;if(d.length==6)var c2=invertclr(d);if(c2)e.style.color=c2;}
else {e.style.backgroundColor='#ffffff'; e.style.color='#000000';}}

function affectclr(c,id,o){
var e=getbyid(id); var d=e.value;
if(d.indexOf(',')!= -1){var r=d.split(','); var n=r.length - 1; r[n]=c; val(r.join(','),id);}
else val(c,id);
if(o)document.body.style.backgroundColor='#'+e.value;
e.style.backgroundColor='#'+c; e.style.color=invertclr(c);}

function hidediv(id){getbyid(id).style.display='none';}
function inn(v,id){getbyid(id).innerHTML=v;}
function val(v,id){getbyid(id).value=v;}
function innfromval(from,id){getbyid(id).innerHTML=getbyid(from).value;}
function valfromval(from,id){getbyid(id).value=getbyid(from).value;}
function dec2hex(n){var hex=n.toString(16);if(hex.length==1)hex="0"+hex; return hex;}//255
