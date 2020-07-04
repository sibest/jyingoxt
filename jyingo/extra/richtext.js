
var isRichText = false;
var rng;
var currentRTE;
var allRTEs = "";

var isIE;
var isGecko;
var isSafari;
var isKonqueror;

var imagesPath;
var includesPath;
var cssFile;


function initRTE(imgPath, incPath, css) {
	//set browser vars
	var ua = navigator.userAgent.toLowerCase();
	isIE = ((ua.indexOf("msie") != -1) && (ua.indexOf("opera") == -1) && (ua.indexOf("webtv") == -1)); 
	isGecko = (ua.indexOf("gecko") != -1);
	isSafari = (ua.indexOf("safari") != -1);
	isKonqueror = (ua.indexOf("konqueror") != -1);
	
	//check to see if designMode mode is available
	if (document.getElementById && document.designMode && !isKonqueror) {
		isRichText = true;
	}
	
	if (isIE) {
		document.onmouseover = raiseButton;
		document.onmouseout  = normalButton;
		document.onmousedown = lowerButton;
		document.onmouseup   = raiseButton;
	}
	
	//set paths vars
	imagesPath = imgPath;
	includesPath = incPath;
	cssFile = css;
	
	//if (isRichText) document.writeln('<style type="text/css">@import "' + includesPath + 'rte.css";</style>');
	

}

function writeRichText(where, rte, html, width, height, buttons, readOnly, ilast) {
	if (isRichText) {
		
		if (allRTEs.indexOf(where + ';') == -1)
		{
		 if (allRTEs.length > 0) allRTEs += ";";
		
		
	  	allRTEs += rte;
		}
		if (readOnly) buttons = false;
		
		//adjust minimum table widths
		if (isIE) {
			if (buttons && (width < 540)) width = 540;
			var tablewidth = width;
		} else {
			if (buttons && (width < 540)) width = 540;
			var tablewidth = width + 4;
		}
		
		var code = '';
		code+= ('<div class="rteDiv">');
		
			code+= ('<table class="rteBack" cellpadding="0" cellspacing="0" id="Buttons2_' + rte + '" width="' + tablewidth + '">');
			code+= ('	<tr>');
			code+= ('		<td><img id="'+rte+'_bold" class="rteImage2" style="cursor:pointer"  src="' + imagesPath + 'butt_fmt_bold.gif" alt="Bold" title="Grassetto" onClick="rteCommand(\'' + rte + '\', \'bold\', \'\')"></td>');
			code+= ('		<td><img id="'+rte+'_italic" class="rteImage2" style="cursor:pointer"  src="' + imagesPath + 'butt_fmt_italic.gif" alt="Italic" title="Corsivo" onClick="rteCommand(\'' + rte + '\', \'italic\', \'\')"></td>');
			code+= ('		<td><img id="'+rte+'_underline" class="rteImage2" style="cursor:pointer" src="' + imagesPath + 'butt_fmt_und.gif" alt="Sottolineato" title="Underline" onClick="rteCommand(\'' + rte + '\', \'underline\', \'\')"></td>');
     // code +=('		<td><img class="rteImage2" style="cursor:pointer"  src="' + imagesPath + 'hyperlink.gif" width="25" height="24" alt="Inserisci Link" title="Insert Link" onClick="insertLink(\'' + rte + '\')"></td>');
			code+= ('		<td width="100%" align="right">');
			code += ' <a href="#" onclick="rimuovi_passo('+ilast+'); return false;" class="rte_elimina"><font size="2" style="font-size:11px"><img src="/images/ico_elimina.gif" /> Elimina passo</font></a>';
	    code += ' | <a href="#" onclick="aggiunti_passo('+ilast+'); return false;" class="rte_aggiungi" style="text-decoration:none" ><font size="2" style="font-size:11px; color:#666" >Aggiungi un altro passo dopo di questo</font></a>';
	    code += '</td>';
			code+= ('	</tr>');
			code+= ('</table>');
		
		}
		code+= ('<iframe id="' + rte + '" name="' + rte + '" width="' + width + 'px" height="' + height + 'px" src="' + includesPath + 'blank.htm" style="border:1px solid #8DB9CE;" frameborder="0"></iframe>');
		code+= ('<input type="hidden" id="hdn' + rte + '" name="' + rte + '" value="">');
		code+= ('</div>');
		
		document.getElementById(where).innerHTML = code;
		
		document.getElementById('hdn' + rte).value = html;
		enableDesignMode(rte, html, readOnly);
	
}
function getCaret(el) {  
  if (el.selectionStart) {  
    return el.selectionStart;  
  } else if (document.selection) {  
    el.focus();  
 
    var r = document.selection.createRange();  
    if (r == null) {  
      return 0;  
    }  
 
    var re = el.createTextRange(),  
        rc = re.duplicate();  
    re.moveToBookmark(r.getBookmark());  
    rc.setEndPoint('EndToStart', re);  
 
    return rc.text.length;  
  }   
  return 0;  
} 
function rte_replace(bodyText, searchTerm, highlightStartTag, highlightEndTag) 
{

  

  var newText = "";
  var i = -1;
  var lcSearchTerm = searchTerm.toLowerCase();
  var lcBodyText = bodyText.toLowerCase();
    
  while (bodyText.length > 0) {
    i = lcBodyText.indexOf(lcSearchTerm, i+1);
    if (i < 0) {
      newText += bodyText;
      bodyText = "";
    } else {

      if (bodyText.lastIndexOf(">", i) >= bodyText.lastIndexOf("<", i)) {

        if (lcBodyText.lastIndexOf("/script>", i) >= lcBodyText.lastIndexOf("<script", i) && lcBodyText.lastIndexOf("/span>", i) >= lcBodyText.lastIndexOf("<span", i)) {
          newText += bodyText.substring(0, i) + highlightStartTag + bodyText.substr(i, searchTerm.length) + highlightEndTag;
          bodyText = bodyText.substr(i + searchTerm.length);
          lcBodyText = bodyText.toLowerCase();
          i = -1;
        }
     
        
      }
    }
  }
  
  return newText;
}
function rte_trim(str, chars) {
	return rte_ltrim(rte_rtrim(str, chars), chars);
}
 
function rte_ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}
 
function rte_rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

var _savedCurrentWindow = null;

function rteOnKeyDown(el, event) {

  var obj = new Object();
  var root = el.el.body;  
  obj.o = root;
  obj.main = el.main;
  
  obj.func = function() {


    var range = this.main.getSelection().getRangeAt(0);
    this.o.savedRange = range.startOffset;
    _savedCurrentWindow = this.main;
  	this.o.newCode = this.o.innerHTML;
  	
  }

 
  setTimeout(Function.createDelegate(obj,obj.func), 0);
  
  
  
}

_rteSpellcheckerTimer = null;

function rteOnKeyUp(el, event) {


  var root = el.el.body;
  
  
  root.innerHTML = global_replace('é','eGrave1',root.innerHTML);  
  root.innerHTML = global_replace('è','eAcute1',root.innerHTML);   
  root.innerHTML = global_replace('à','aAcute1',root.innerHTML);  
  root.innerHTML = global_replace('ì','iAcute1',root.innerHTML);  
  root.innerHTML = global_replace('ù','uAcute1',root.innerHTML);  
  root.innerHTML = global_replace('ò','oAcute1',root.innerHTML);  
  
  var code = el.el.body.innerHTML;
  var originalcode = code;
  
  code = global_replace('<span class="schk">','', code);
  code = global_replace('</span>','', code);
  code = global_replace('<SPAN CLASS="schk">','', code);
  code = global_replace('</SPAN>','', code);
  code = global_replace('<span class=\'schk\'>','', code);
  code = global_replace('<SPAN CLASS=\'schk\'>','', code);
  code = global_replace('<span class=\'schk2\'>','', code);
  code = global_replace('<SPAN CLASS=\'schk2\'>','', code);
  code = global_replace('<SPAN CLASS="schk2">','', code);
  code = global_replace('<span class="schk2">','', code);  
  code = global_replace('<SPAN class=schk2>','', code);
  code = global_replace('<span class=schk>','', code);  

  var txt = (root.innerText ? root.innerText : root.textContent);

  
  var words = txt.split(/\W+/);
  words.sort(rte_sortfunc);
  
  for (var i = 0; i < words.length; i++)
  {
   
   if (!rte_trim(words[i])) continue;
   
   code = rte_replace(code, words[i],'<span class="schk">','</span>');   
  }
 
  code = global_replace('eGrave1','é',code);  
  code = global_replace('eAcute1','è',code);   
  code = global_replace('aAcute1','à',code);  
  code = global_replace('iAcute1','ì',code);  
  code = global_replace('uAcute1','ù',code);  
  code = global_replace('oAcute1','ò',code);  
  
  root.innerHTML = code;
  var compiledarray = new Array();
  var skipwrds = new Array();
  
  var els = root.getElementsByTagName('span');
  for (var i = 0; i < els.length; i++)
  {
  	
  	var t = els[i];
  	if (t.className != 'schk')
  	 continue;

    if (skipwrds[(t.innerText ? t.innerText : t.textContent)] != undefined) continue;
    if (_rteCorrectionIgnored[t.innerText ? t.innerText : t.textContent] != undefined) continue;
    
    skipwrds[t.innerText] = 1;
    compiledarray.push(t.innerText ? t.innerText : t.textContent);
   
  }
  
  var code = JSON.stringify(compiledarray);
  
  
  var script = document.createElement('script');
  script.src = '/sp.php?code='+code+'&num=0&rnd='+Math.random();
  script.type='text/javascript';
  
  var head = document.getElementsByTagName('head')[0];   
  head.appendChild(script);
  
  root.newCode = root.innerHTML;
  root.innerHTML = originalcode;

}
function rte_sortfunc(a, b)
{
    return b.length-a.length;
}

function rte_spellcheckResults(arr, rteNum) {
	 
	var oRTE,oRTE2;
	if (document.all) {
		oRTE = frames['rte_dati_'+rteNum].document;
		oRTE2 = document.getElementById('rte_dati_'+rteNum);
	} else {
		oRTE = document.getElementById('rte_dati_'+rteNum).contentWindow.document;
		oRTE2 = document.getElementById('rte_dati_'+rteNum);
	}

 
	
	
	var rte = oRTE.body;
	rte.innerHTML = rte.newCode;
	rte.focus();	


	
	for (var txt in arr)
	{

   var els = rte.getElementsByTagName('span');
   for (var i = 0; i < els.length; i++)
   {
  	
  	var t = els[i];
  	if (t.className != 'schk' && t.className != 'schk2')
  	 continue;	
	  
	  if ((t.innerText ? t.innerText : t.textContent) == txt)
	  {
	  	
	  	t.className = 'schk2';
	  	sugg = new suggerimentiView(t, arr[txt],getX(oRTE2),getY(oRTE2),oRTE); 
	  } 	
		
	}
	}


	
/*
 sel=_savedCurrentWindow.getSelection();
 
//we are saving the current selected range obj
 range2=sel.getRangeAt(0);
 
//and we need to create a new range object to set the caret position to 5
 var range = document.createRange();
 
range.setStart(rte,rte.savedRange);
 
range.setEnd(rte,1);
 
//remove the old range and add the newly created range
 sel.removeRange(range2);
 
sel.addRange(range);*/
/*
var p = document.createElement('input');
p.type='text';
document.body.appendChild(p);
p.focus();
_savedCurrentWindow.focus();
rte.focus();
*/
/*
rte.focus();
rte.value += 'x';
rte.value = rte.value.replace(/x$/,"");
  document.body.focus();
rte.focus();*/
}

var _rteCorrectionIgnored = Array();

suggerimentiView = function(element, suggestions, x,y,cont) {
	
	var el = document.createElement('div');
  el.className = 'rteContextMenu';
  this.x = x;
  this.y = y;

	for (var i = 0; i < suggestions.length; i++)
	{
	   var sugg = document.createElement('a');
	   sugg.onclick = Function.createDelegate(this, this.itemClicked);
	   sugg.href = '#';
	   sugg.suggestion = suggestions[i];
	   sugg.innerHTML = suggestions[i];
	   el.appendChild(sugg);
	   
	}
	
	var sep = document.createElement('div');
	sep.className = 'sep';
	el.appendChild(sep);
	
   var ignora = document.createElement('a');
	 ignora.onclick = Function.createDelegate(this, this.itemClicked);
	 ignora.href = '#';
   ignora.ignora = 1;
	 ignora.innerHTML = 'Ignora';
   el.appendChild(ignora);
	
	
	document.body.appendChild(el);
	
	this.element = el;
	this.attach = element;
	this.attach.onclick = Function.createDelegate(this, this.show);
	if(navigator.userAgent.indexOf('MSIE') == -1)
	 this.attach.addEventListener("click", Function.createDelegate(this, this.show), true);
	
	if(navigator.userAgent.indexOf('MSIE') == -1)
	{
		document.body.addEventListener('click', Function.createDelegate(this, this.hide), true);
		cont.body.addEventListener('click', Function.createDelegate(this, this.hide), true);
		
	} else {
	jyingo.addEvent(document.body,'click',Function.createDelegate(this, this.hide));
	jyingo.addEvent(cont.body,'click',Function.createDelegate(this, this.hide));		
	}
	

}

suggerimentiView.prototype = {
	
	hide : function(event) {
		var ev = event;
		if (!ev) ev = window.event;
		
		var tgt = ev.target ? ev.target : ev.srcElement;

		while (true)
		{
			if (!tgt) break;
			if (tgt == undefined) break;
			
			if (tgt == this.attach) return;
			
			if (tgt == this.element) return;
		
			tgt = tgt.parentNode;
			
			
		}
		
		

	 this.element.style.display='none';		
	},
	
	show : function(event) {

		var ev = event;
		if (!ev) ev = window.event;
		
	   var x,y;
	 
	 	 if (ev.pageX && ev.pageY)
	 	 {
	 	   x = ev.pageX;
	 	   y = ev.pageY;	
	 	 } else {
	 	   x = ev.clientX + document.documentElement.scrollLeft;
	 	   y = ev.clientY + document.documentElement.scrollTop;   	
	 	 }
	 	 
	 	 x = x + this.x;
	 	 y = y + this.y;
	 	 
	 	 this.element.style.left = x+'px';
	 	 this.element.style.top = y+'px';
	 	 this.element.style.display='block';		
		
	},
	
	itemClicked : function(event) {
		var ev = event;
		if (!ev) ev = window.event;
		
		var tgt = ev.target ? ev.target : ev.srcElement;
		
		if (tgt.ignora != undefined)
		{
			
			_rteCorrectionIgnored[(this.attach.innerText ? this.attach.innerText : this.attach.textContent)]= 1;
			
			
		} else {
			
			this.attach.innerHTML = tgt.suggestion;
			
			
		}
		
		this.attach.className = 'schk';
		this.element.style.display='none';		
	}
	
}




function enableDesignMode(rte, html, readOnly) {
	var frameHtml = "<html id=\"" + rte + "\">\n";
	frameHtml += "<head>\n";
	//to reference your stylesheet, set href property below to your stylesheet path and uncomment
	if (cssFile.length > 0) {
		frameHtml += "<link media=\"all\" type=\"text/css\" href=\"" + cssFile + "\" rel=\"stylesheet\">\n";
	} else {
		frameHtml += "<style>\n";
		
		frameHtml += "span.schk2 { background:url(/images/err.png) left bottom repeat-x; color:red; cursor:pointer; } body {\n";
		frameHtml += "	background-color: #fefefe;;\n";
		frameHtml += "	margin: 3px;\nfont-family:arial;font-size:12px;\r\n";
		frameHtml += "	padding: 0px; border:0\n";
		frameHtml += "}\n";
		frameHtml += "img {\n";		
			frameHtml += "	padding: 0px;\n";
			frameHtml += "	margin: 0px; display:block; text-align:center; margin:0 auto;\n";			
		
			frameHtml += "}\n";	
		frameHtml += "br {\n";		
			frameHtml += "	padding: 0px;\n";
			frameHtml += "	margin: 0px;\n";			
		
			frameHtml += "}\n";	
		frameHtml += "p {\n";		
			frameHtml += "	padding: 0px;\n";
			frameHtml += "	margin: 0px;\n";			
		
			frameHtml += "}\n";				
		frameHtml += "</style>\n";
	}
	frameHtml += "</head>\n";
	frameHtml += "<body rtename=\""+rte+"\"  onpaste=\"window.parent.rteOnPaste('"+rte+"', event);\">\n";
	frameHtml += html + "\n";
	frameHtml += "</body>\n";
	frameHtml += "</html>";
	
	var oRTE,oRTE2;
	if (document.all) {
		 oRTE = frames[rte].document;
		oRTE.open();
		oRTE.write(frameHtml);
		oRTE.close();
		oRTE2 = frames[rte];
		if (!readOnly) oRTE.designMode = "On";
	} else {
		try {
			if (!readOnly) document.getElementById(rte).contentDocument.designMode = "on";
			try {
				 oRTE = document.getElementById(rte).contentWindow.document;
				 oRTE2 = document.getElementById(rte).contentWindow;
				oRTE.open();
				oRTE.write(frameHtml);
				oRTE.close();
				if (isGecko && !readOnly) {
					//attach a keyboard handler for gecko browsers to make keyboard shortcuts work
					oRTE.addEventListener("keypress", kb_handler, true);
				}
			} catch (e) {
				alert("Error preloading content.");
			}
		} catch (e) {
			//gecko may take some time to enable design mode.
			//Keep looping until able to set.
			if (isGecko) {
				setTimeout("enableDesignMode('" + rte + "', '" + html + "', " + readOnly + ");", 10);
			} else {
				return false;
			}
		}
	}
	/*
	jyingo.addEvent(oRTE, "keyup", aggiornaLenRTE, true);
	jyingo.addEvent(oRTE, "keydown", aggiornaLenRTE, true);
	*/
	

	
	obj = new Object();
	obj.rte = rte;
	obj.el = oRTE;
	obj.main = oRTE2;
	obj.func2 = function() { clearInterval(_rteSpellcheckerTimer); _rteSpellcheckerTimer = null;  rteOnKeyUp(this, null); }
	obj.func = function(ev) { if (_rteSpellcheckerTimer != null) clearInterval(_rteSpellcheckerTimer); _rteSpellcheckerTimer = setInterval(Function.createDelegate(this, this.func2), 1000);  }
	
	jyingo.addEvent(oRTE, "keyup", Function.createDelegate(obj, obj.func), true);
	if(navigator.userAgent.indexOf('MSIE') == -1)
	 oRTE.addEventListener("blur", Function.createDelegate(obj, obj.func), true);
	obj = new Object();
	obj.rte = rte;
	obj.el = oRTE;
	obj.main = oRTE2;
	obj.func = function(ev) { rteOnKeyDown(this, ev); }
	
	jyingo.addEvent(oRTE, "keydown", Function.createDelegate(obj, obj.func), true);
	if(navigator.userAgent.indexOf('MSIE') == -1)
	 oRTE.addEventListener("keydown", Function.createDelegate(obj, obj.func), true);
	jyingo.addEvent(oRTE, "mouseup", Function("checkRange('"+rte+"');"), true); 

	
	if(navigator.userAgent.indexOf('MSIE') == -1)
  {
  
  	oRTE.addEventListener("keyup", aggiornaLenRTE, true);
  	oRTE.addEventListener("keydown", aggiornaLenRTE, true);
  	oRTE.addEventListener("mouseup", Function("checkRange('"+rte+"');"), true);
		oRTE.addEventListener("paste", Function("rteOnPaste('"+rte+"');"), true);
  }
}


function rteOnPaste(rte, ev)
{
	 if (!ev)
	  ev = window.event;
	 


	 if(navigator.userAgent.indexOf('Firefox') != -1)
	 {
 
    // firefox demmerd
    return;
	 	
	 }
	 else
	 {
	  
	 	 var txt = window.clipboardData.getData("Text");
   	 insertHTMLRte(rte, txt.split('<').join('&lt;'));
	 
	 
		 ev.returnValue= false;
	 
	}
}

function updateRTEs() {
	var vRTEs = allRTEs.split(";");
	for (var i = 0; i < vRTEs.length; i++) {
		updateRTE(vRTEs[i]);
	}
}

function updateRTE(rte) {
	if (!isRichText) return;
	
	//set message value
	var oHdnMessage = document.getElementById('hdn' + rte);
	if (!oHdnMessage) return;
	
	var oRTE = document.getElementById(rte);
	var readOnly = false;

	

	if (isRichText) {

	
		if (oHdnMessage.value == null) oHdnMessage.value = "";
		if (document.all) {
			

			oHdnMessage.value = frames[rte].document.body.innerHTML;
		} else {
			
			
			oHdnMessage.value = oRTE.contentWindow.document.body.innerHTML;
		}
		
		//if there is no content (other than formatting) set value to nothing
		if (stripHTML(oHdnMessage.value.replace("&nbsp;", " ")) == "" 
			&& oHdnMessage.value.toLowerCase().search("<hr") == -1
			&& oHdnMessage.value.toLowerCase().search("<img") == -1) oHdnMessage.value = "";
		//fix for gecko
		if (escape(oHdnMessage.value) == "%3Cbr%3E%0D%0A%0D%0A%0D%0A") oHdnMessage.value = "";
	}
//	oHdnMessage.value = oHdnMessage.value.split('+').join('%2B');
}

function rteCommand(rte, command, option) {
	

	//function to perform command
	var oRTE;
	if (document.all) {
		oRTE = frames[rte];
	} else {
		oRTE = document.getElementById(rte).contentWindow;
	}
	
	try {
		oRTE.focus();
		if (navigator.userAgent.indexOf('MSIE') == -1)
	oRTE.document.execCommand ('useCSS',false,true);
	  	oRTE.document.execCommand(command, false, option);
		oRTE.focus();
		
		if (command == 'bold' || command == 'underline' || command == 'italic')
		{
		  var el = $get(rte+'_'+command);
		  
		  setRange(rte);
  	  var res;
   		if (rng.queryCommandState)
       res = rng.queryCommandState(command);
      else
       res = document.getElementById(rte).contentWindow.document.queryCommandState(command);		  
		  
		   var isIE = (navigator.userAgent.indexOf('MSIE') != -1);
		  
		  /*
		  if (isSafari && command != 'underline')
		  if ( (isIE && ( (rng.text.length && res == true) || (!rng.text.length && res == false) ))  || (!isIE && (res)))
		  {
		  	
		   el.className = 'rteImage2_sel';
		   if (el.src.indexOf('_on.gif') == -1)
		   el.src = el.src.replace('.gif','_on.gif');
		  }
		  else
		  {
		     el.style.border = '';
		  	 el.src = el.src.replace('_on.gif','.gif');	 
		  }
*/


		  if (el.className == 'rteImage2')
		  {
		  
		   el.className = 'rteImage2_sel';
		   if (el.src.indexOf('_on.gif') == -1)
		    el.src = el.src.replace('.gif','_on.gif');
		  }
		  else
		  {
		     el.className = 'rteImage2';
		  	  el.src = el.src.replace('_on.gif','.gif');	 
		  }		  	
		  
		  
		}
		
	} catch (e) {
//		alert(e);
//		setTimeout("rteCommand('" + rte + "', '" + command + "', '" + option + "');", 10);
	}
	aggiornaLenRTE();

}

function checkRange(rte)
{

	setRange(rte);

	var arrCmds = Array();
	
	arrCmds.push('bold');
	arrCmds.push('italic');
	arrCmds.push('underline');
	
	for (var i = 0; i < arrCmds.length; i++)
	{
	 var command = arrCmds[i];
	 var el = $get(rte+'_'+command);
   
   var res;
   if (rng.queryCommandState)
    res = rng.queryCommandState(command);
   else
   	res = document.getElementById(rte).contentWindow.document.queryCommandState(command);
    
	 if ( res == true )
	 {
		 el.className = 'rteImage2_sel';
		 
		 if (el.src.indexOf('_on.gif') == -1)
		  el.src = el.src.replace('.gif','_on.gif');	 
	 }
	 else
   {
		  el.src = el.src.replace('_on.gif','.gif');	 
     el.className = 'rteImage2';	 
 	 }
 	} 	
}

function toggleHTMLSrc(rte) {
	//contributed by Bob Hutzel (thanks Bob!)
	var oRTE;
	if (document.all) {
		oRTE = frames[rte].document;
	} else {
		oRTE = document.getElementById(rte).contentWindow.document;
	}
	
	if (document.getElementById("chkSrc" + rte).checked) {
		showHideElement("Buttons1_" + rte, "hide");
		showHideElement("Buttons2_" + rte, "hide");
		if (document.all) {
			oRTE.body.innerText = oRTE.body.innerHTML;
		} else {
			var htmlSrc = oRTE.createTextNode(oRTE.body.innerHTML);
			oRTE.body.innerHTML = "";
			oRTE.body.appendChild(htmlSrc);
		}
	} else {
		showHideElement("Buttons1_" + rte, "show");
		showHideElement("Buttons2_" + rte, "show");
		if (document.all) {
			//fix for IE
			var output = escape(oRTE.body.innerText);
			output = output.replace("%3CP%3E%0D%0A%3CHR%3E", "%3CHR%3E");
			output = output.replace("%3CHR%3E%0D%0A%3C/P%3E", "%3CHR%3E");
			
			oRTE.body.innerHTML = unescape(output);
		} else {
			var htmlSrc = oRTE.body.ownerDocument.createRange();
			htmlSrc.selectNodeContents(oRTE.body);
			oRTE.body.innerHTML = htmlSrc.toString();
		}
	}
}

function dlgColorPalette(rte, command) {
	//function to display or hide color palettes
	setRange(rte);
	
	//get dialog position
	var oDialog = document.getElementById('cp' + rte);
	var buttonElement = document.getElementById(command + '_' + rte);
	var iLeftPos = getOffsetLeft(buttonElement);
	var iTopPos = getOffsetTop(buttonElement) + (buttonElement.offsetHeight + 4);
	oDialog.style.left = (iLeftPos) + "px";
	oDialog.style.top = (iTopPos) + "px";
	
	if ((command == parent.command) && (rte == currentRTE)) {
		//if current command dialog is currently open, close it
		if (oDialog.style.visibility == "hidden") {
			showHideElement(oDialog, 'show');
		} else {
			showHideElement(oDialog, 'hide');
		}
	} else {
		//if opening a new dialog, close all others
		var vRTEs = allRTEs.split(";");
		for (var i = 0; i < vRTEs.length; i++) {
			showHideElement('cp' + vRTEs[i], 'hide');
		}
		showHideElement(oDialog, 'show');
	}
	
	//save current values
	parent.command = command;
	currentRTE = rte;
}

function dlgInsertTable(rte, command) {
	//function to open/close insert table dialog
	//save current values
	setRange(rte);
	parent.command = command;
	currentRTE = rte;
	var windowOptions = 'history=no,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=no,resizable=no,width=360,height=200';
	window.open(includesPath + 'insert_table.htm', 'InsertTable', windowOptions);
}

function insertLink(rte) {
	//function to insert link
	var szURL = prompt("Inserisci l'indirizzo:", "http://www.sito.com");
	
	if (!szURL) return;
	
	setRange(rte);
	if (rng.text.length)
	{
		//ignore error for blank urls
		rteCommand(rte, "Unlink", null);
		rteCommand(rte, "CreateLink", szURL);		
	} else { 
		
		var szTitle = prompt("Inserisci il titolo:", "Nome Sito");
	
	if (szURL && szTitle)
  insertHTMLRte(rte, '<a href="'+szURL+'">'+szTitle+'</a>');
 }
}

function setColor(color) {
	//function to set color
	var rte = currentRTE;
	var parentCommand = parent.command;
	
	if (document.all) {
		//retrieve selected range
		var sel = frames[rte].document.selection; 
		if (parentCommand == "hilitecolor") parentCommand = "backcolor";
		if (sel != null) {
			var newRng = sel.createRange();
			newRng = rng;
			newRng.select();
		}
	}
	
	rteCommand(rte, parentCommand, color);
	showHideElement('cp' + rte, "hide");
}

function addImage(rte) {
	//function to add image
	imagePath = prompt('Enter Image URL:', 'http://');				
	if ((imagePath != null) && (imagePath != "")) {
		rteCommand(rte, 'InsertImage', imagePath);
	}
}

// Ernst de Moor: Fix the amount of digging parents up, in case the RTE editor itself is displayed in a div.
// KJR 11/12/2004 Changed to position palette based on parent div, so palette will always appear in proper location regardless of nested divs
function getOffsetTop(elm) {
	var mOffsetTop = elm.offsetTop;
	var mOffsetParent = elm.offsetParent;
	var parents_up = 2; //the positioning div is 2 elements up the tree
	
	while(parents_up > 0) {
		mOffsetTop += mOffsetParent.offsetTop;
		mOffsetParent = mOffsetParent.offsetParent;
		parents_up--;
	}
	
	return mOffsetTop;
}

// Ernst de Moor: Fix the amount of digging parents up, in case the RTE editor itself is displayed in a div.
// KJR 11/12/2004 Changed to position palette based on parent div, so palette will always appear in proper location regardless of nested divs
function getOffsetLeft(elm) {
	var mOffsetLeft = elm.offsetLeft;
	var mOffsetParent = elm.offsetParent;
	var parents_up = 2;
	
	while(parents_up > 0) {
		mOffsetLeft += mOffsetParent.offsetLeft;
		mOffsetParent = mOffsetParent.offsetParent;
		parents_up--;
	}
	
	return mOffsetLeft;
}

function selectFont(rte, selectname) {
	//function to handle font changes
	var idx = document.getElementById(selectname).selectedIndex;
	// First one is always a label
	if (idx != 0) {
		var selected = document.getElementById(selectname).options[idx].value;
		var cmd = selectname.replace('_' + rte, '');
		rteCommand(rte, cmd, selected);
		document.getElementById(selectname).selectedIndex = 0;
	}
}

function kb_handler(evt) {
	var rte = evt.target.id;
	
	//contributed by Anti Veeranna (thanks Anti!)
	if (evt.ctrlKey) {
		var key = String.fromCharCode(evt.charCode).toLowerCase();
		var cmd = '';
		switch (key) {
			case 'b': cmd = "bold"; break;
			case 'i': cmd = "italic"; break;
			case 'u': cmd = "underline"; break;
		};

		if (cmd) {
			rteCommand(rte, cmd, null);
			
			// stop the event bubble
			evt.preventDefault();
			evt.stopPropagation();
		}
 	}
}


function insertHTMLRte(rte, html) {
	//function to add HTML -- thanks dannyuk1982

	
	var oRTE;
	if (document.all) {
		oRTE = frames[rte];
	} else {
		oRTE = document.getElementById(rte).contentWindow;
	}
	
	oRTE.focus();
	if (document.all) {
		oRTE.document.selection.createRange().pasteHTML(html);
	} else {
		oRTE.document.execCommand('insertHTML', false, html);
	}
}


function insertHTML(html) {
	//function to add HTML -- thanks dannyuk1982
	var rte = currentRTE;
	
	var oRTE;
	if (document.all) {
		oRTE = frames[rte];
	} else {
		oRTE = document.getElementById(rte).contentWindow;
	}
	
	oRTE.focus();
	if (document.all) {
		oRTE.document.selection.createRange().pasteHTML(html);
	} else {
		oRTE.document.execCommand('insertHTML', false, html);
	}
}

function showHideElement(element, showHide) {
	//function to show or hide elements
	//element variable can be string or object
	if (document.getElementById(element)) {
		element = document.getElementById(element);
	}
	
	if (showHide == "show") {
		element.style.visibility = "visible";
	} else if (showHide == "hide") {
		element.style.visibility = "hidden";
	}
}

function setRange(rte) {
	//function to store range of current selection
	var oRTE;
	if (document.all) {
		oRTE = frames[rte];
		var selection = oRTE.document.selection; 
		if (selection != null) rng = selection.createRange();
	} else {
		oRTE = document.getElementById(rte).contentWindow;
		var selection = oRTE.getSelection();
		rng = selection.getRangeAt(0);
    rng.text = rng.toString();
			
		
	}
}

function stripHTML(oldString) {
	//function to strip all html
	var newString = oldString.replace(/(<([^>]+)>)/ig,"");
	
	//replace carriage returns and line feeds
   newString = newString.replace(/\r\n/g," ");
   newString = newString.replace(/\n/g," ");
   newString = newString.replace(/\r/g," ");
	
	//trim string
	newString = trim(newString);
	
	return newString;
}

function trim(inputString) {
   // Removes leading and trailing spaces from the passed string. Also removes
   // consecutive spaces and replaces it with one space. If something besides
   // a string is passed in (null, custom object, etc.) then return the input.
   if (typeof inputString != "string") return inputString;
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
	
   while (ch == " ") { // Check for spaces at the beginning of the string
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length - 1, retValue.length);
	
   while (ch == " ") { // Check for spaces at the end of the string
      retValue = retValue.substring(0, retValue.length - 1);
      ch = retValue.substring(retValue.length - 1, retValue.length);
   }
	
	// Note that there are two spaces in the string - look for multiple spaces within the string
   while (retValue.indexOf("  ") != -1) {
		// Again, there are two spaces in each of the strings
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ") + 1, retValue.length);
   }
   return retValue; // Return the trimmed string back to the user
}

//*****************
//IE-Only Functions
//*****************
function checkspell() {
	//function to perform spell check
	try {
		var tmpis = new ActiveXObject("ieSpell.ieSpellExtension");
		tmpis.CheckAllLinkedDocuments(document);
	}
	catch(exception) {
		if(exception.number==-2146827859) {
			if (confirm("ieSpell not detected.  Click Ok to go to download page."))
				window.open("http://www.iespell.com/download.php","DownLoad");
		} else {
			alert("Error Loading ieSpell: Exception " + exception.number);
		}
	}
}

function raiseButton(e) {
	//IE-Only Function
	var el = window.event.srcElement;
	
	className = el.className;
	if (className == 'rteImage' || className == 'rteImageLowered') {
		el.className = 'rteImageRaised';
	}
}

function normalButton(e) {
	//IE-Only Function
	var el = window.event.srcElement;
	
	className = el.className;
	if (className == 'rteImageRaised' || className == 'rteImageLowered') {
		el.className = 'rteImage';
	}
}

function lowerButton(e) {
	//IE-Only Function
	var el = window.event.srcElement;
	
	className = el.className;
	if (className == 'rteImage' || className == 'rteImageRaised') {
		el.className = 'rteImageLowered';
	}
}
