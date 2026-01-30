//tlex
var nbnew=0; var xa;
function lastelex(){var mnu=getbyid("cbck"); var firstchild=mnu.childNodes[0];
	if(firstchild)if(mnu){var first=firstchild.id; return id=first.substr(3);}}
function btaction(d,id,n=0){var bt=getbyid(id); if(bt==null)return;
	if(d>0){var sty="nbntf active";} else{var sty="nbntf"; d='';} if(n)d=n;
	bt.innerHTML=d; bt.className=sty;}
function recbt(nbnew){if(nbnew)var rn=nbnew.split('-');
	btaction(rn[0],'tlxrec');//new posts
	btaction(parseInt(rn[1])+parseInt(rn[2])+parseInt(rn[4]),'tlxact');//actions
	btaction(rn[1],'tlxntf');//notifs
	btaction(rn[2],'tlxsub',rn[3]);//follow
	btaction(rn[4],'tlxcht');}//messages
function refresh(){var id=lastelex();
	if(parseInt(id))ajx("returnVar,nbnew|tlex,refresh|since="+id+",tm="+usr);//+",ntf=1"
	if(nbnew)setTimeout("recbt(nbnew)",1000);}
function tlexlive(){switch(document.visibilityState){
	case "visible":refresh(); clearTimeout(xa); xa=setTimeout("tlexlive()",60000);break;
	case "hidden":refresh(); clearTimeout(xa); xa=setTimeout("tlexlive()",360000);break;}}

//autoflow
addEvent(document,"scroll",function(event){loadscroll("tlex,read","cbck")});

//search
function checkcases(id){//srchopt
var el=document.getElementsByName(id); var ret='';
for(var i=0;i<el.length;i++)
	if(el[i].checked)ret=ret+=i+'='+el[i].value+',';
return ret;}

function search2(id){
	var d=getbyid(id).value; var $chk=checkcases('srchopt');//id+'opt'
	if(d){getbyid('prmtm').value='srh='+d; ajaxcall("div,cbck|tlex,search_txt","",id);}}
function Search(old,id){
	var ob=getbyid(id); if(ob!=null)var src=ob.value;
	if(!src||src.length<2)return;
	if(src!=old){if(!old)return SearchT(id); else return;}
	if(src)search2(id);}
function SearchT(id){var ob=getbyid(id); 
	if(ob!=null)var old=ob.value; else var old='';
	setTimeout(function(){Search(old,id)},1000);}