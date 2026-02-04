//Fractal GNU/GPL
var wait=0,popnb=0,curid=0,curh=0,cpop=0,cpop_difx=0,na=0,xt=0,cpop_dify=0,popz=1,fixpop=1,index='';

function AJAX(aUrl,aMethod,atarget,aOption,aPost,aEl){
if(aUrl!=undefined)this.mUrl=aUrl;
if(atarget!=undefined)this.targetId=atarget;
if(aOption!=undefined)this.ajaxOption=aOption;
if(aMethod!=undefined)this.method=aMethod; else this.method=0;
if(this.mRequest!=undefined){this.mRequest.abort(); delete this.mRequest;}
this.mRequest=this.createReqestObject(); var m_This=this;
this.mRequest.onreadystatechange=function(){m_This.handleResponse(aEl);}
this.mRequest.open('POST',this.mUrl,true);
var post=aPost instanceof FormData?aPost:null;
if(post)this.mRequest.upload.addEventListener('progress',progressHandler,false);
//if(post)this.mRequest.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
this.mRequest.send(post);}

AJAX.prototype.mUrl=undefined;
AJAX.prototype.targetId=undefined;
AJAX.prototype.mRequest=undefined;

AJAX.prototype.createReqestObject=function(){var req;
try{req=new XMLHttpRequest();}//all
catch(error){try{req=new ActiveXObject("Microsoft.XMLHTTP");}//IE6
	catch(error){try{req=new mUrliveXObject("Msxml2.XMLHTTP");}//IE4
		catch(error){req=false;}}}
return req;}

function progressHandler(ev){uploaded=Math.round((ev.loaded/ev.total)*100,2);}

//ajaxOption:x=close,y=repos,z=loading
AJAX.prototype.handleResponse=function(el){
var mth=this.method; var tg=this.targetId; var opt=this.ajaxOption; var ob='';
if(this.mRequest.readyState==4){wait=0;
//if(this.mRequest.readyState===XMLHttpRequest.DONE){
	//if(opt=='load')wait=0;
	if(this.mRequest.status=="200"){
		var res=this.mRequest.responseText;
		//var xres=httpRequest.responseXML; var root_node=xres.getElementsByTagName('root').item(0);
		//if(opt==3)opt='z'; else if(opt==2)opac(100,tg);
		if(opt=='z' || opt=='zx'){mpop('x'); opac(100,tg);}
		if(opt=='x' || opt=='xy' || opt=='zx')if(curid)Close('popup');
		if(opt=='xb')clearTimeout(xb);
		if(mth!='returnVar' && tg)ob=getbyid(tg,el);
		if(mth=='socket')ob='';
		//else if(res.indexOf('Fatal error')!=-1)popup(res);
		else if(mth=='div')ob.innerHTML=res;
		else if(mth=='popup' && res)popup(res);
		else if(mth=='pagup' && res)pagup(res);
		else if(mth=='imgup')pagup(res,1);
		else if(mth=='bubble')bubble(res,tg,opt,el);
		else if(mth=='menu')menu(res,tg,opt);
		else if(mth=='drop')drop(res,tg,opt);
		else if(mth=='input')ob.value=res;
		else if(mth=='after')addiv(tg,res,mth);
		else if(mth=='before')addiv(tg,res,mth);
		else if(mth=='begin')addiv(tg,res,mth);
		else if(mth=='atend')addiv(tg,res,mth);
		else if(mth=='injectJs')addjs(res);
		else if(mth=='injectCss')addcss(res);
		else if(mth=='returnVar')window[tg]=res;
		else if(mth=='loadjs')setTimeout(tg(res),100);
		else if(mth=='json')jsonput(tg,res);
		else if(mth=='reload'){//loged_ok,register_ok
			if(res==opt)setTimeout('window.location=document.URL',100);
			else ob.innerHTML=res;}
		//post-actions
		if(opt=='xy' || opt=='y')setTimeout("repos()",500);
		else if(opt=='xx')setTimeout("Close('popup')",2000);
		else if(opt=='xz')setTimeout("closediv('"+tg+"')",2000);
		else if(opt=='store')localStorage['m3']=res;
		else if(opt=='restore')ob.innerHTML=localStorage['m3'];
		else if(opt=='scrollTop')scrollTop(tg?tg:mth);
		else if(opt=='reposbub')reposbub(ob);}
	else if(this.onError!=undefined){
		this.onError({status:this.mRequest.status,
		statusText:this.mRequest.statusText});}
	delete this.mRequest;}
else if(wait==0){wait=1;
	if(this.method=='div'){//waitmsg(getbyid(tg));
		//var ob=getbyid(tg); curh=ob.offsetHeight;
		if(opt=='z' || opt=='zx')opac(50,tg);}
	//var percent=this.loaded/this.total*100;
	//if(mth=='after')mpop('Loading...');
}
//else if(opt=='z'){var d=getbyid('mpo');
	//if(d)ajaxcall('mpo|upload,progress','rid='+tg);}//progress
}

//let decide tg by keys of res
function jsonput(keys,res){var cb,k,typ,tg;
var obj=JSON.parse(res); var rk=keys.split(';'); var i=0; var rkx=rk.length>1?1:0; //pr(obj);
for(var k in obj){
	//tg=rk[k]?k:rk[i];//priotize asked
	tg=rkx?rk[i]:k;//priotize callback
	if(!tg)break;
	cb=getbyid(tg); i++;
	if(cb!=null)typ=cb.type;
	if(typ=='text'||typ=='textarea'||typ=='hidden'||typ=='number'||typ=='date')cb.value=obj[k];
	else if(cb!=null)cb.innerHTML=obj[k];}}

//composants
//function n(){return "\n";}
function nl(){return "\n";}
function pr(v){console.log(v);}
function addEvent(obj,event,func){if(obj!=undefined)obj.addEventListener(event,func,true);}
function delEvent(obj,event,func){if(obj!=undefined)obj.removeEventListener(event,func,true);}
function innersizes(){return {w:parseInt(window.innerWidth)-18,h:parseInt(window.innerHeight)};}
function waitmsg(div){div.innerHTML='Loading... <span id="mpo"></span>';}

//function getbyid0(id){return document.getElementById(id);}
function getbyid(id,tg){//tg.target.id
if(tg!=undefined){var pa=tg.parentNode;
	if(pa==undefined)return getbyid(id);
	else if(pa.id==id)return pa;
	else{var pc=pa.childNodes;
		for(i=0;i<pc.length;i++)if(pc[i].id==id)return pc[i];}
	return getbyid(id,pa);}
return document.getElementById(id);}

function mpop(res){
if(res=='x')return Close('mpop');
var content=getbyid('popup');
var pp=document.createElement('div');
pp.id='mpop'; pp.style.position='fixed'; pp.className='alert';
pp.innerHTML='<a onclick="Close(\'mpop\')">Loading... <span id="mpo"></span></a>';
content.appendChild(pp); zindex('mpop');
var pos=ppos(pp,0); pp.style.left=pos.x; pp.style.top=pos.y;}

function jra(val){var r=new Object; var ra=val.split(',');
for(i=0;i<ra.length;i++){var rb=ra[i].split('='); r[rb[0]]=rb[1];}//jurl()
return r;}
function jrb(ra){var rb=[];
for(var key in ra)rb.push(key+':'+ra[key]);
return rb.join(',');}

//ajaxCall //com{method,id,option,js}|app{app,mth}|
function ajaxcall(call,params,inputs,el){//na=0;
if(params){var prm=jra(params);} else var prm=new Object();
if(typeof xc!='undefined')clearTimeout(xc);//stop pending actions
var p=call.split('|');
var com=p[0].split(',');
var app=p[1].split(',');
//callbackMethod
var cbMethod=com[0];//div,popup,bubble,...
var cbId=com[1]!=undefined?com[1]:'';//id of callback
var cbOpt=com[2]!=undefined?com[2]:'';//x,y,xy,xx,z,store,...
var cbJs=com[3]!=undefined?com[3]:'';//id of code to retro-inject in headers
//component
var _a=app[0];
var _m=app[1]!=undefined?'&_m='+app[1]:'';
//aPost
var fd=new FormData();
//loading
if(cbOpt=='z')mpop('Loading...');
//if(cbOpt=='x' || cbOpt=='xy')if(curid)Close('popup');
//inputs
if(inputs)var inp=inputs.split(','); else inp='';
if(inp!=undefined && inp!=null){
	for(var i=0;i<inp.length;i++){
		if(inp[i]){var content=undefined;
			var ob=getbyid(inp[i],el);//content value
			if(ob!=null && ob.nodeName=='FORM')fd=formCaptures(ob,fd);
			else if(ob==null){var ob=document.getElementsByName(inp[i]); var rc=[];//checklists
				for(var io=0;io<ob.length;io++){if(ob[io].checked)rc.push(ob[io].value);}//rc.push(io+1)
				var content=rc.join('-');}
			else var content=ajaxCaptures(ob);
			if(content!=undefined){fd.append(inp[i],content);}}}}
//load operation
if(cbMethod=='popup' || cbOpt=='w')prm['_pw']=getbyid('page').offsetWidth-40;
//var 
var str=jrb(prm);
var url='/call.php?_a='+_a+_m+'&_p='+str+'&'+cbMethod+'='+(cbId?cbId:1);
//send
if(na){mem=[url,cbMethod,cbId,cbOpt]; return;}
else new AJAX(url,cbMethod,cbId,cbOpt,fd,el);
//post actions, before ajax finish
if(cbOpt=='reload')setTimeout('window.location=document.URL',100);//,,reload
else if(cbOpt=='resetform')for(i=0;i<inp.length;i++){
	var did=getbyid(inp[i],el); if(did.type=='textarea')did.value='';
	else if(did.type=='text')did.value='';}
else if(cbOpt=='resetdiv')getbyid(inp[0],el).innerHTML='';
else if(cbOpt.substr(0,6)=='reset:')getbyid(cbOpt.substr(6),el).innerHTML='';
else if(cbOpt=='js'){var ob=getbyid(com[4],el); addjs(ob.value?ob.value:ob.innerHTML);}
else if(cbOpt=='css'){var ob=getbyid(com[4],el); addcss(ob.value?ob.value:ob.innerHTML);}
//retro-injection in headers
if(cbJs){var url='/call.php?_a='+_a+'&_m=';
	if(cbJs=='resetcs')addjs('exs=[];');
	else if(cbJs=='scrollBottom')setTimeout('scrollBottom("'+(com[4]?com[4]:com[1])+'")',200);
	else if(cbJs=='scrollTop')scrollTop(com[1]?com[1]:com[0]);
	else if(cbJs=='scrollUp')window.scrollTo(0,0);
	else if(cbJs=='js'){new AJAX(url+'js','injectJs','','2','','');}
	else if(cbJs=='css'){new AJAX(url+'css','injectCss','','2','','');}
	else if(cbJs=='1'){
		new AJAX(url+'js','injectJs','','2','','');
		new AJAX(url+'css','injectCss','','2','','');}}}

function ajb(p,el){
	var com=p[0]; var app=p[1]; var prm=p[2]; var inp=p[3]; var cbk=p[0].split(','); var z=cbk[0];
	var r=['popup','pagup','imgup','bubble','menu','drop','input','before','after','begin','atend','reload','ses','socket','injectJs','injectCss','json']; if(!cbk[1] && r.indexOf(z)==-1){cbk[0]='div'; cbk[1]=z;}
	var cbk1=cbk.length>1?cbk[1]:''; 
	if(cbk1)var bt=getbyid(cbk1,el);
	if(z=='mul' || cbk1.indexOf(';')!=-1){cbk[0]='json';}
	if(cbk[0]=='div' && typeof bt=='object' && bt!=null){
		if(bt.tagName=='INPUT')cbk[0]='input';//bt!=null && 
		else if(bt.type=='textarea')cbk[0]='input';
		else if(bt.type=='input')cbk[0]='input';
		else if(bt==null)cbk[0]='socket';}
	var com=cbk.join(',');
	return ajaxcall(com+'|'+app,prm,inp,el);}

function ajbt(el){var p2='';
	if(el.dataset.prmtm){var ptm=getbyid('prmtm'); var pm=el.dataset.prmtm;
		if(pm=='no')ptm.value='';
		else if(pm!='current')ptm.value=pm;}//decodeBase64
	if(el.dataset.toggle){var ko=toggle(el); if(ko&&pm)ptm.value=''; return;}
	var da=el.dataset.j; var p=da.split('|'); if(p)ajb(p,el);//if(p2)p[2]=p[2]+','+p2;
	var db=el.dataset.jb; if(db){var p=db.split('|'); ajb(p,el);}
	var u=el.dataset.u; if(u)updateurl(u,da);
	var cl=el.dataset.cl; if(cl)cltg(cl);//close others bt 
	var tab=el.dataset.tab; if(tab)togtab(el); //pr(da);
	return false;}

function ajx(d,el){var p=d.split('|'); ajb(p,el);}//bridge ajaxCall
function ajxt(d,el){if(typeof xc!='undefined')clearTimeout(xc);
	xc=setTimeout(function(){ajx(d,el);},1000);}
//center of gravity of universe
function aju(el){var com=el.href; var r=com.split('/'); var call=r[3]+','+r[4]; var prm,u3;
	//if(r[5]!='undefined'){var r5=r[5]; if(r5.indexOf(':')!=-1)var prm=r5; else var u3=r5;}
	if(j)ajaxcall('cbk|'+call,r[5]!='undefined'?r[5]:'','',el); return false;}

//toggle
function togcl(ob){var p=ob.parentNode.childNodes; var bid=ob.dataset.bid;
	for(i=0;i<p.length;i++)if(p[i].id!=ob.id){p[i].rel=''; active(p[i],0);
		if(p[i].dataset!=undefined)p[i].dataset.bid=bid;}}

function cltg(did,bid){var p=document.querySelectorAll('[data-toggle]');
	for(i=0;i<p.length;i++)if(p[i].dataset.toggle==did && p[i].id!=bid && p[i].rel==1){
		p[i].rel=''; active(p[i],0); return p[i].id;}}

//bid is the associated bt to jb
//prepare to restore others activated bt to the same target
function toggle(ob){
	var res=0; var did=ob.dataset.toggle; var bid=ob.dataset.bid; var ko=ob.dataset.ko; if(ko==0)ko='';
	if(ob.rel==1 && !ko){closediv(did,ob); ob.rel=''; active(ob,0); res=1;//restored
		var jb=ob.dataset.jb; if(jb)ajx(jb,ob);
		if(bid){var oba=getbyid(bid); if(oba){active(oba,1); oba.rel=1;}}}
	else{ob.rel=1; active(ob,1); ajx(ob.dataset.j,ob); togcl(ob); var cid=cltg(did,ob.id); if(cid)bid=cid;
	if(bid){ob.dataset.bid=bid; togcl(ob);//propagation of bid
	var oba=getbyid(bid); if(oba){oba.rel=''; active(oba,0); ob.dataset.jb=oba.dataset.j;}}}
	return res;}

function togbt(ob){var n=ob.rel==1?0:1;
	var j=n?ob.dataset.j:ob.dataset.jb; ob.rel=n; active(ob,n); ajx(j);}

//capture inputs
function ajaxCaptures(el){var typ=el.type;
if(!typ)var type='div'; else var type=typ.split('-')[0]; //pr(typ);
if(type=='text' || type=='password' || type=='hidden')var content=el.value;
else if(type=='placeholder')var content=el.placeholder;
else if(type=='textarea'){var content=el.value;}// if(!content)var content=(el.innerHTML);
//else if(type=='checkbox')var content=el.checked?1:0;
//else if(type=='radio')var content=el.options[el.selectedIndex].value;
else if(type=='select')var content=el.options[el.selectedIndex].value;
else if(type=='range')var content=el.value;
else if(type=='div')var content=el.innerHTML;
else if(type=='td')var content=el.innerHTML;
else var content=el.value;
return content;}

function formCaptures(el,fd){//serialize
	var name=el.name; var form=document.forms[name];
	for(var i=0;i<form.length;i++){var content='';
		var ty=form[i].type; var ty=ty.split('-')[0]; var nm=form[i].name;
		if(ty=='select')content=form[i].options[form[i].selectedIndex].value;
		else if(ty=='checkbox' || ty=='radio'){//multiple calls the same but it's ok
			var ob=document.getElementsByName(nm); var rc=[];
			for(var io=0;io<ob.length;io++){if(ob[io].checked)rc.push(ob[io].value);}
			var content=rc.join('-');}
		else content=form[i].value;
		fd.append(nm,content);}
return fd;}

//login
function verifusr(e){//input,user//loadjs
ajaxcall("returnVar,usrxs|login,verifusr","user="+e.value,e);
setTimeout(function(){usrexist(e);},100);}
function usrexist(e){var bt=getbyid('usrexs',e);
if(usrxs){bt.style.display='inline-block'; e.style.bordeColor='red';}
else{bt.style.display='none'; e.style.bordeColor='silver';}}

//popup
function repos(){
if(!curid)return;
var pp=getbyid(curid);
var id='popu'+(curid.substring(3));
var popu=getbyid(id);
poph(popu,1); var pos=ppos(popu,0);//alert(pos.x+'-'+pos.y);
pp.style.left=pos.x; pp.style.top=pos.y;}

function reduc(){
var pp=getbyid(curid);
var id='popu'+(curid.substring(3));
var div=getbyid(id); var op=div.style.display;
if(op=='block' || !op)div.style.display='none'; 
else div.style.display='block';}

function poph(popu,o){
popu.style.maxHeight=''; popu.style.width=''; var adjust=80;
var sz=innersizes(); var pos=getPositionAbsolute(popu);
var ha=sz.h; var hb=pos.y+pos.h+adjust; //pr(ha+'-'+hb+'-'+pos.y);
if(o){if(pos.h+adjust>ha)popu.style.maxHeight=(ha-adjust)+'px';}
else if(hb>ha)popu.style.maxHeight=(ha-pos.y)+'px';
popu.style.overflowY='auto'; popu.style.overflowX='hidden';}

function ppos(popu,decal){var px=0; var sz=innersizes(); 
var sw=sz.w; var w=popu.offsetWidth; var l=(sw-w)/2+px; var py=-20; 
var sh=sz.h; var h=popu.offsetHeight; var t=(sh-h)/2+py; 
if(l+decal+w+0>sw)decal=0; var px=(l>0?l:0)+decal;
if(t+decal+h+0>sh)decal=0; var py=(t>0?t:0)+decal;
return {x:px+'px',y:py+'px'};}

function popup(res,method){popnb+=1; var nb=popnb; move=1;
var content=getbyid('popup');
var decal=(content.childNodes.length)*10;
var pp=document.createElement('div');
pp.id='pop'+nb; pp.style.position='fixed';
addEvent(pp,'mousedown',function(){zindex('pop'+nb)});
pp.innerHTML=res; 
content.appendChild(pp); zindex('pop'+nb);
var popa=getbyid('popa');
addEvent(popa,'mousedown',function(event){start_drag(event,nb)});
var popu=getbyid('popu');
poph(popu,1);//before ppos
var pos=ppos(popu,decal);
pp.style.left=pos.x; pp.style.top=pos.y;
popa.id='popa'+nb; popu.id='popu'+nb;}

var move=0;
function pagup(res,img){
if(popnb)Close('pop'+popnb);//used for second pagup
popnb+=1; var nb=popnb;
var content=getbyid('popup'); if(!res)return;
var pp=document.createElement('div');
pp.id='pop'+nb; pp.style.position='fixed';
addEvent(pp,'mousedown',function(){zindex('pop'+nb)});
pp.innerHTML=res;
content.appendChild(pp); zindex('pop'+nb); opac(1,'pop'+nb);
var popu=getbyid('popu');
if(!img){//closer
	var imu=popu.children[0]; var i=0;
	if(typeof imu!='undefined')while(imu.innerHTML==''){i++; imu=popu.children[i];}
	imu.id='pgu'+nb; zindex('pgu'+nb);
	clbubob={esc:'pgu'+nb,cl:'pop'+nb,bt:0};}
if(img)addEvent(popu,'mouseup',function(){Close('popup')});
//poph(popu,1);//before ppos
pp.style.left=0; pp.style.top=0;
pp.style.right=0; pp.style.bottom.top=0;
Timer('opac','pop'+nb,0,100,10);
popu.id='popu'+nb;}

//bubble position
function bpos(bt,pp,mode){//bubble
var pos=getPositionRelative(bt);//btn of reference
var pob=getPositionAbsolute(pp);//bubble
var px=pos.x+pos.w+6; var py=pos.y-((pob.h-pos.h)/2);
if(pob.h>300){var popu=getbyid('popu'); py=pos.y;}// popu.style.maxHeight='300px';
if(mode!=1){px=pos.x; py=pos.y+pos.h+6;}//as menu
if(py<20)py=20;
var sz=innersizes();
if(pos.x+pob.w>sz.w){px=pos.x+pos.w-pob.w; if(px<0)p=0;}//flip
if(curid)var parentpopu=getbyid('popu'+(curid.substring(3)));
if(parentpopu)var scr=parentpopu.scrollTop; if(scr)py-=scr;
//pob=getPositionAbsolute(pp);
return {x:px+'px',y:py+'px'};}

//bubble closers
clbubob={};
function clickoutside(bub,e){if(e)var m=mouse(e); var yoffset=0;
	var p=getPositionRelative(bub); var fix=infixed(bub);//if scroll
	if(fix){var p=getPositionAbsolute(bub); var yoffset=self.pageYOffset;} 
	var top=p.y+yoffset;
	if(m.x<p.x||m.x>(p.x+p.w)||m.y<top||m.y>(top+p.h))return 1;}

//clic on body
function closebub(e){var ob=getbyid(clbubob.esc);
	if(ob && clickoutside(ob,e)){
		if(clbubob.cl)hidediv(clbubob.cl);//Close
		/*if(clbubob.cl){//error open bubble:
			if(typeof x!='undefined')clearTimeout(x); if(typeof xb!='undefined')clearTimeout(xb);
			Timer('opac',clbubob.cl,100,0,10); xb=setTimeout('Close(clbubob.cl)',1000);}*/
		if(clbubob.bt)var obt=getbyid(clbubob.bt);
		if(obt){obt.rel=''; active(obt,0)}
		clbubob={};}}

function buboff(e){var ob=getbyid('pop'+id); if(ob){Close('popub'+id);}}
function closebubauto(id){var ob=getbyid(id); if(ob){Close('pop'+id); ob.rel='';}}
function attachclbub(popu,id){var r=popu.getElementsByTagName('a');
for(i=0;i<r.length;i++){addEvent(r[i],'click',function(){closebubauto(id)});}}

exb=[];
function bubCloseOthers2(pid){
if(exb.indexOf(pid)==-1)exb.push(pid); var n=exb.length;
if(n>0)for(var i=0;i<n;i++)if(exb[i] && exb[i]!=pid){
	var bt=getbyid(exb[i].substr(3)); if(bt){bt.rel=''; bt.className='';}
	Close(exb[i]); exb[i]=0;}}

//bubble
function bubble(res,id,mode,el){
var btn=getbyid(id); var pid='pop'+id; dropid=pid;
clbubob={esc:pid,cl:pid,bt:id};//params for close
if(btn.rel=='active'){Close(pid); btn.rel=''; active(btn,0); return;}
else{btn.rel='active'; active(btn,1);}
closebubauto(id);
bubCloseOthers2(pid);
bubClose();//close menus
var clbub=getbyid('closebub');
var pp=document.createElement('div');
pp.id=pid; pp.style.position='absolute';
addEvent(pp,'mousedown',function(){zindex(pid)});
pp.innerHTML=res;
btn.parentNode.appendChild(pp); zindex(pid)
var popu=getbyid('popu'); //attachclbub(popu,id);//dont works with href and menu
poph(popu);//before bpos
var pos=bpos(btn,pp,mode);
//var scr=(popu.childNodes[0]).scrollTop; alert(scr);
pp.style.left=pos.x; pp.style.top=pos.y;
popu.id='popu'+id;}//after bpos

function bubClose2(){bubBodyCloser(0);//
var bub=document.getElementsByClassName('bub');
for(var i=0;i<bub.length;i++)bubCloseOthers(bub[i]);}

function closeOthersmenus(pid){
var bub=document.getElementsByClassName('bub');
for(var i=0;i<bub.length;i++){var ex=0; var bid='';
	var el=bub[i].getElementsByTagName('a');
	for(var ib=0;ib<el.length;ib++)if(el[ib].id==pid)var ex=1;
	if(!ex)bubCloseOthers(bub[i]);}}

//inside div
function menu(res,id,mode){popnb+=1; var nb=popnb; var btn=getbyid(id);
bubBodyCloser(1);//prep future close
bubCloseOthers2('');//close bub
//closebubauto(id);//close pop generated after
//bubClose();//close menus
//bubBodyCloser(1);//usefull for multi drops but close too soon not fixed openable menu
//bubCloseOthers2('pop'+id);//add pid and close others
closeOthersmenus(id);
bubCloseOthers2('');//close bub
if(btn){bubCloseOthers(btn.parentNode); btn.className='active';}
var content=btn.parentNode;
var pp=document.createElement('div');
pp.id='pop'+id; pp.style.position='absolute';//'pop'+id
addEvent(pp,'mousedown',function(){zindex('pop'+id)});
//addEvent(document.body,'mousedown',function(){bubClose()});
pp.innerHTML=res; 
content.appendChild(pp); zindex('pop'+id);
var popu=getbyid('popu'); poph(popu);//before ppos
var posAbs=getPositionAbsolute(btn);
var posRel=getPositionRelative(btn);
var posbub=getPositionRelative(pp);
var bub=getPositionRelative(popu); var sz=innersizes();
if(mode){//vertical
	if(mode=='2')var posRel=getPositionRelative(btn.parentNode);//panup
	var px=posRel.x; var py=posRel.y+posRel.h; var pz='';
	if(posAbs.x+posbub.w>(sz.w/2))px=px+posRel.w-posbub.w+10;//flip
	if(mode=='2')pp.style.minWidth=(btn.parentNode.offsetWidth)+'px';
	//if(px+posbub.w>sz.w){px=''; pz=-10;}
}
else{//horizontal, second iteration
	var px=posRel.x+posRel.w; var py=posRel.y; var pz='';
	if(posAbs.x+posRel.w+bub.w+10>sz.w)px=posRel.x-bub.w;}
if(px)pp.style.left=px+'px';
if(pz)pp.style.right=pz+'px';
pp.style.top=py+'px';
popu.id='popu'+nb;}

function drop(res,id,mode){
if(mode=='1'){dropid=id; menu(res,id,2);}
else menu(res,dropid,2);}

function bubBodyCloser(op,id){var clbub=getbyid('closebub'); clbub.style.zIndex=1;
if(op){clbub.style.width='100%'; clbub.style.height='100%';}
else{clbub.style.width='0'; clbub.style.height='0';}}

function bubCloseOthers(e){//bubBodyCloser(0);
var btr=e.getElementsByTagName('div');
if(btr.length>0)for(var i=0;i<btr.length;i++)Close(btr[i].id);
var btr=e.getElementsByTagName('a');
if(btr.length>0)for(var i=0;i<btr.length;i++)btr[i].className='';}

function bubClose(){bubBodyCloser(0);//
var bub=document.getElementsByClassName('bub');
for(var i=0;i<bub.length;i++)bubCloseOthers(bub[i]);}

function bubCloseTimer(){//var id=e.id;
if(typeof xb!='undefined')clearTimeout(xb);
xb=setTimeout(function(){bubClose()},4000);}

//bubjs
function bubjs(ob,o){
var mp=mouse(event); var pp=getbyid('bubjs'); var res=ob.dataset.tx;
if(pp==null){var pp=document.createElement('div'); pp.id='bubjs'; 
pp.style='background:white; border;1px solid silver; padding:1px;'; getbyid('popup').appendChild(pp);}
if(o==1){pp.style.display='inline-block'; pp.innerHTML=res; pp.style.position='absolute';
	var py=(mp.y-32); var px=(mp.x-8);
	//var py=(mp.y-(pp.offsetHeight/2)); var px=(mp.x-(pp.offsetWidth/2));
	pp.style.top=py+'px'; pp.style.left=px+'px';}
else{pp.style.display='none'; pp.innerHTML='';}}

function bubj(ob,o){
var mp=mouse(event); var pp=getbyid('bubj'); var j=ob.dataset.ja;
if(pp==null){var pp=document.createElement('div'); pp.id='bubj';
pp.style='position:absolute; background:white; border;1px solid silver; padding:1px; font-size:12px;';
getbyid('popup').appendChild(pp);}
if(o==1){pp.style.display='block'; pp.style.zIndex+=1; ajx(pp.id+',,reposbub|'+j,'','',ob);
	//var pos=getPositionAbsolute(ob); pr(pos);
	var py=(mp.y+16); var px=(mp.x+16);
	pp.style.top=py+'px'; pp.style.left=px+'px';}
else{pp.style.display='none'; pp.innerHTML='';}}

function reposbub(ob){
var sz=innersizes();
//var pp=getbyid('bubj');//is ob
var pos=getPositionAbsolute(ob); //pr(pos);
//var py=(pos.y); var px=(pos.x);
//var mp=mouse(event); //give nothing?
//var ts=document.body.scrollTop; //give nothing?
var py=(pos.y); var px=(pos.x);
if(py>sz.h/2)py=pos.y-pos.h-26; //pr(mp);
if(px>sz.w/2)px=pos.x-pos.w;
ob.style.top=py+'px'; ob.style.left=px+'px';}

//tabs
function togtab(e){var pa=e.parentNode; var ob=pa.getElementsByTagName('a');
for(var i=0;i<ob.length;i++)if(ob[i]==e)ob[i].className='active'; else ob[i].className='';}

//composants
function strreplace(rep,by,val){return val.split(rep).join(by);}
function jurl(val,n){//encodeURIComponent
var arr=['|','|'];//"\n","\t",'\'',"'",'"','*','#','+','=','&','?','.',':',',',,' ','<','>','/','%u'
var arb=['(-bar)','(-par)'];//'(-n)','(-t)','(-asl)','(-q)','(-dq)','(-star)','(-dz)','(-add)','(-eq)','(-and)','(-qm)','(-dot)','(-ddot)','(-coma)',,'(-sp)','(-b1)','(-b2)','(-sl)','(-pu)'
if(n){var ra=arb; var rb=arr;}else{var ra=arr; var rb=arb;}
var rgx=new RegExp(/([^A-Za-z0-9\-])/);
if(rgx.test(val))for(var i=0;i<arr.length;i++)val=strreplace(ra[i],rb[i],val);
return val;}

<<<<<<< HEAD
//function nsf(){return false;} function nst(){return true;} 
function noslct(a){
if(window.sidebar){if(a)document.onmousedown=true; else document.onmousedown=false;}}
//document.onselectstart=new Function("return false");
=======
function nsf(){return false;} function nst(){return true;} 
function noslct(a){
if(window.sidebar){if(a)document.onmousedown=nst; else document.onmousedown=nsf;}}
//document.onselectstart = new Function("return false");
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235

function zindex(id){popz++; curid=id; var bub=getbyid(id);
if(bub!=null)bub.style.zIndex=popz;}

//ancestor of insertAdjacentHTML
/*function addiv_0(tar,res,st){var ob=getbyid(tar); if(ob==null)return; pr(tar); pr(st);
var div=document.createElement('div'); div.innerHTML=res; var parent=ob.parentNode; 
if(st=='before')parent.insertBefore(div,ob);
else if(st=='after')parent.appendChild(div);
else if(st=='begin'){var obd=ob.childNodes; ob.insertBefore(div,obd[0]);}
//else if(st=='atend')parent.insertBefore(div,ob.nextSibling);
else if(st=='atend'){var childs=div.childNodes, n=childs.length;
	for(i=0;i<n;i++)if(typeof childs[i]=='object')ob.appendChild(childs[i]);}}*/

function addiv(tar,res,st){var ob=getbyid(tar); if(ob==null)return;
if(st=='before')ob.insertAdjacentHTML('beforebegin',res);
else if(st=='begin')ob.insertAdjacentHTML('afterbegin',res);
else if(st=='atend')ob.insertAdjacentHTML('beforeend',res);
else if(st=='after')ob.insertAdjacentHTML('afterend',res);}

function addjs(d){var head=document.getElementsByTagName('head')[0]; if(xt)clearTimeout(xt);
var div=document.createElement('script'); div.type='text/javascript'; div.id='addjs'; 
var ob=getbyid('addjs'); if(ob!=null)head.removeChild(ob); div.innerHTML=d; head.appendChild(div);}

function addcss(d){var head=document.getElementsByTagName('head')[0]; if(xt)clearTimeout(xt);
var div=document.createElement('style'); div.type='text/css'; div.id='addcss';
var ob=getbyid('addjs'); if(ob!=null)head.removeChild(ob); div.innerHTML=d; head.appendChild(div);}

function popslide(ev){
if(move && cpop!=0){var mp=mouse(ev);
	cpop.style.left=(mp.x-cpop_difx)+'px';
	cpop.style.top=(mp.y-cpop_dify)+'px';}}

function start_drag(ev,z){
pp=getbyid('pop'+z); cpop=pp;
old_mousep=mouse(ev);
old_mousex=getPositionAbsolute(pp);
cpop_difx=old_mousep.x-old_mousex.x;
cpop_dify=old_mousep.y-old_mousex.y;}

function stop_drag(ev){cpop=0;}

function mouse(ev){if(ev.pageX || ev.pageY){return {x:ev.pageX,y:ev.pageY};}
return{x:ev.clientX+document.body.scrollLeft-document.body.clientLeft,
	y:ev.clientY+document.body.scrollTop-document.body.clientTop};}

function getPositionAbsolute(e){if(e==null)return {x:0,y:0,w:0,h:0};
var left=0; var top=0; var w=e.offsetWidth; var h=e.offsetHeight;
while(e.offsetParent){left+=e.offsetLeft; top+=e.offsetTop; e=e.offsetParent;}
left+=e.offsetLeft; top+=e.offsetTop; return {x:left,y:top,w:w,h:h};}

function getPositionRelative(e){if(e==null)return {x:0,y:0,w:0,h:0};
return {x:e.offsetLeft,y:e.offsetTop,w:e.offsetWidth,h:e.offsetHeight};}

function infixed(e){if(e==null)return 'no';
while(e.parentNode){if(e.style.position=='fixed')return e; e=e.parentNode;}
return 0;}

function Close(val){var pp=getbyid('popup');
if(val=='popup' && pp){
	if(curid)pp.removeChild(getbyid(curid)); else pp.innerHTML=''; curid=0;} //move=0;
else if(val=='pop' && pp)pp.innerHTML='';
else if(val){var div=getbyid(val); if(div)div.parentNode.removeChild(div);}}
