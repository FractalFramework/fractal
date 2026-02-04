<<<<<<< HEAD
/*drag*/

function ev_dt(ev) {
	var evd = ev.datatransfer;
	if (evd == undefined) evd = ev.dataTransfer;
	return evd;
}

function drag_start(ev) {//pr("dragStart");
	var evd = ev_dt(ev); //pr(ev.target.id);
	// Add the target element's id to the data transfer object
	evd.setData("text/plain", ev.target.id);
	//evd.setData("text/html","<p>Example paragraph</p>");
	//evd.setData("text/uri-list","http://developer.mozilla.org");
	//var img=new Image(); img.src='http://logic.ovh/img/full/webf4eafea934.jpg'; evd.setDragImage(img,10,10);
	//var data=evd.getData("text"); getbyid(data).className="dragme";
	ev.target.className = "dragme";
	evd.dropEffect = "copy";
	ev.dropEffect = "move";
}

function drag_over(ev) {
	ev.preventDefault();
	var evd = ev_dt(ev);
	//Set the dropEffect to move
	evd.dropEffect = "move"
	ev.dropEffect = "move";
	//var data=evd.getData("text"); getbyid(data).className="dragover";
	ev.target.className = "dragover";
}

function drag_leave(ev) {
	ev.preventDefault();
	var evd = ev_dt(ev);
	ev.target.className = "";
}

function drag_sqldrop(ev, j) {
	ev.preventDefault();
	var evd = ev_dt(ev);
	//Get the id of the target and add the moved element to the target's DOM
	var data = evd.getData("text"); //getbyid(data).className="dropper";
	ev.target.className = "dropper";
	//ev.target.appendChild(getbyid(data));//1
	//ajaxcall('div,divlist|drag,play','p1='+data+',p2='+ev.target.id,''); //pr(data);
	var rt = reorderdiv(data, ev.target.id);
	if (j != undefined) ajx(j + rt);
}

function drag_end(ev, j) {
	var evd = ev.datatransfer;
	//if(evd.dropEffect=='move')ev.target.parentNode.removeChild(ev.target);//1
	//ev.target.className="";
	cleandiv(ev.target);
}

function cleandiv(ob) {
	var div = ob.parentNode;
	var r = div.getElementsByTagName('div');
	for (i = 0; i < r.length; i++)r[i].className = "";
}

function reorderdiv(id, tg) {
	var div = getbyid(id).parentNode; var ida, idb, ia, ib;
	var r = div.getElementsByTagName('div'); var rt = new Array();
	for (i = 0; i < r.length; i++) {
		if (r[i].id == id) { var ida = r[i]; var ia = i; }
		if (r[i].id == tg) { var idb = r[i]; var ib = i; }
	}
	for (i = 0; i < r.length; i++) {
		if (ia > ib && r[i].id == id) { getbyid(id).after(idb); }
		if (ib > ia && r[i].id == tg) { getbyid(tg).after(ida); }
	}
	for (i = 0; i < r.length; i++)rt[i] = r[i].id;
	return rt.join(';');
}

/*drop img*/
function output(text, ob) { ob.textContent += text; }
function dropenter(ev) { ev.target.textContent = ''; ev.stopPropagation(); ev.preventDefault(); }
function dropover(ev) { ev.stopPropagation(); ev.preventDefault(); }
function dropok(ev) {
	ev.stopPropagation(); ev.preventDefault();
	var dt = ev.dataTransfer; var rf = dt.files; var n = rf.length; output("File Count: " + n + "\n", ev.target);
	for (var i = 0; i < rf.length; i++) {
		pr(rf[i]);
		var va = "File" + i + ":\n(" + (typeof rf[i]) + "):<" + rf[i] + ">" + rf[i].name + " " + rf[i].size + "\n";
		output(va, ev.target);
	}
}
=======
/*drag*/

function ev_dt(ev) {
	var evd = ev.datatransfer;
	if (evd == undefined) evd = ev.dataTransfer;
	return evd;
}
function ev_dt(ev) {
	var evd = ev.datatransfer;
	if (evd == undefined) evd = ev.dataTransfer;
	return evd;
}

function drag_start(ev) {//pr("dragStart");
	var evd = ev_dt(ev); //pr(ev.target.id);
function drag_start(ev) {//pr("dragStart");
	var evd = ev_dt(ev); //pr(ev.target.id);
	// Add the target element's id to the data transfer object
	evd.setData("text/plain", ev.target.id);
	evd.setData("text/plain", ev.target.id);
	//evd.setData("text/html","<p>Example paragraph</p>");
	//evd.setData("text/uri-list","http://developer.mozilla.org");
	//var img=new Image(); img.src='http://logic.ovh/img/full/webf4eafea934.jpg'; evd.setDragImage(img,10,10);
	//var data=evd.getData("text"); getbyid(data).className="dragme";
	ev.target.className = "dragme";
	evd.dropEffect = "copy";
	ev.dropEffect = "move";
}
	ev.target.className = "dragme";
	evd.dropEffect = "copy";
	ev.dropEffect = "move";
}

function drag_over(ev) {
function drag_over(ev) {
	ev.preventDefault();
	var evd = ev_dt(ev);
	var evd = ev_dt(ev);
	//Set the dropEffect to move
	evd.dropEffect = "move"
	ev.dropEffect = "move";
	evd.dropEffect = "move"
	ev.dropEffect = "move";
	//var data=evd.getData("text"); getbyid(data).className="dragover";
	ev.target.className = "dragover";
}
	ev.target.className = "dragover";
}

function drag_leave(ev) {
function drag_leave(ev) {
	ev.preventDefault();
	var evd = ev_dt(ev);
	ev.target.className = "";
}
	var evd = ev_dt(ev);
	ev.target.className = "";
}

function drag_sqldrop(ev, j) {
function drag_sqldrop(ev, j) {
	ev.preventDefault();
	var evd = ev_dt(ev);
	var evd = ev_dt(ev);
	//Get the id of the target and add the moved element to the target's DOM
	var data = evd.getData("text"); //getbyid(data).className="dropper";
	ev.target.className = "dropper";
	var data = evd.getData("text"); //getbyid(data).className="dropper";
	ev.target.className = "dropper";
	//ev.target.appendChild(getbyid(data));//1
	//ajaxcall('div,divlist|drag,play','p1='+data+',p2='+ev.target.id,''); //pr(data);
	var rt = reorderdiv(data, ev.target.id);
	if (j != undefined) ajx(j + rt);
}
	var rt = reorderdiv(data, ev.target.id);
	if (j != undefined) ajx(j + rt);
}

function drag_end(ev, j) {
	var evd = ev.datatransfer;
function drag_end(ev, j) {
	var evd = ev.datatransfer;
	//if(evd.dropEffect=='move')ev.target.parentNode.removeChild(ev.target);//1
	//ev.target.className="";
	cleandiv(ev.target);
}
	cleandiv(ev.target);
}

function cleandiv(ob) {
	var div = ob.parentNode;
	var r = div.getElementsByTagName('div');
	for (i = 0; i < r.length; i++)r[i].className = "";
}
function cleandiv(ob) {
	var div = ob.parentNode;
	var r = div.getElementsByTagName('div');
	for (i = 0; i < r.length; i++)r[i].className = "";
}

function reorderdiv(id, tg) {
	var div = getbyid(id).parentNode; var ida, idb, ia, ib;
	var r = div.getElementsByTagName('div'); var rt = new Array();
	for (i = 0; i < r.length; i++) {
		if (r[i].id == id) { var ida = r[i]; var ia = i; }
		if (r[i].id == tg) { var idb = r[i]; var ib = i; }
	}
	for (i = 0; i < r.length; i++) {
		if (ia > ib && r[i].id == id) { getbyid(id).after(idb); }
		if (ib > ia && r[i].id == tg) { getbyid(tg).after(ida); }
	}
	for (i = 0; i < r.length; i++)rt[i] = r[i].id;
	return rt.join(';');
}
function reorderdiv(id, tg) {
	var div = getbyid(id).parentNode; var ida, idb, ia, ib;
	var r = div.getElementsByTagName('div'); var rt = new Array();
	for (i = 0; i < r.length; i++) {
		if (r[i].id == id) { var ida = r[i]; var ia = i; }
		if (r[i].id == tg) { var idb = r[i]; var ib = i; }
	}
	for (i = 0; i < r.length; i++) {
		if (ia > ib && r[i].id == id) { getbyid(id).after(idb); }
		if (ib > ia && r[i].id == tg) { getbyid(tg).after(ida); }
	}
	for (i = 0; i < r.length; i++)rt[i] = r[i].id;
	return rt.join(';');
}

/*drop img*/
function output(text, ob) { ob.textContent += text; }
function dropenter(ev) { ev.target.textContent = ''; ev.stopPropagation(); ev.preventDefault(); }
function dropover(ev) { ev.stopPropagation(); ev.preventDefault(); }
function dropok(ev) {
	ev.stopPropagation(); ev.preventDefault();
	var dt = ev.dataTransfer; var rf = dt.files; var n = rf.length; output("File Count: " + n + "\n", ev.target);
	for (var i = 0; i < rf.length; i++) {
		pr(rf[i]);
		var va = "File" + i + ":\n(" + (typeof rf[i]) + "):<" + rf[i] + ">" + rf[i].name + " " + rf[i].size + "\n";
		output(va, ev.target);
	}
}
function output(text, ob) { ob.textContent += text; }
function dropenter(ev) { ev.target.textContent = ''; ev.stopPropagation(); ev.preventDefault(); }
function dropover(ev) { ev.stopPropagation(); ev.preventDefault(); }
function dropok(ev) {
	ev.stopPropagation(); ev.preventDefault();
	var dt = ev.dataTransfer; var rf = dt.files; var n = rf.length; output("File Count: " + n + "\n", ev.target);
	for (var i = 0; i < rf.length; i++) {
		pr(rf[i]);
		var va = "File" + i + ":\n(" + (typeof rf[i]) + "):<" + rf[i] + ">" + rf[i].name + " " + rf[i].size + "\n";
		output(va, ev.target);
	}
}
>>>>>>> 1e291934117955fdb0b0792ad329a68d5110b235
