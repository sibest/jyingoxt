/**
 * Object FileReference
 * Unofficial Andrea Giammarchi JavaScript porting for original
 *      FileReference FLash 8 and ActionScript 2.0 class.
 * Original and official Macromedia documentation link ( thank you Macromedia ) :
 * http://livedocs.macromedia.com/flash/8/main/wwhelp/wwhimpl/common/html/wwhelp.htm?context=LiveDocs_Parts&file=00002204.html
 *
 * Demo test page:
 * http://www.devpro.it/FileReference/
 *
 * Special Thanks:
 * Albano Daniele Salvatore for its support and debug [ www.phpsoft.it ]
 * ---------------------------------------------
 * @dependenciesrequires Flash 8 plug in and FileReference_List.swf file
 * [ http://www.devpro.it/FileReference/ ]
 * @author              Andrea Giammarchi
 * @site		www.devpro.it
 * @date                2005/10/24
 * @lastmod             2005/11/08 11:00
 * @version             1.0b , tested with FF 1.0.7, IE 6, Opera 8
 */
function FileReference() {
	/** List of all PUBLIC methods */
	// init
		/** 
		 * Public unofficial and required method
		 * ( this method does not exist on original FileReference Flash 8 class )
		 * 
		 * Creates object that will recieve informations from JavaScript FileReference driver.
		 * You have to call this initializzator before use this object and after document (body) is loaded.
		 * The simplest way is to pass this method on onload body function.
		 *
		 *      this.init( HTMLElementID:String ):Void
		 *
		 * @param	String		unique id for an html element where flash object will be created
		 * NOTE: don't worry about your layout, it will be unvisible.
		 */
	
	// addListener
		/** 
		 * Public official method
		 * http://livedocs.macromedia.com/flash/8/main/wwhelp/wwhimpl/common/html/wwhelp.htm?context=LiveDocs_Parts&file=00002205.html
		 */
	
	// browse
		/** 
		 * Public official method
		 * http://livedocs.macromedia.com/flash/8/main/wwhelp/wwhimpl/common/html/wwhelp.htm?context=LiveDocs_Parts&file=00002206.html
		 */

	// cancel
		/**
		* Public official method
		* http://livedocs.macromedia.com/flash/8/main/wwhelp/wwhimpl/common/html/wwhelp.htm?context=LiveDocs_Parts&file=00002207.html
		*/

	// download
		/**
		 * Public official method
		 * http://livedocs.macromedia.com/flash/8/main/wwhelp/wwhimpl/common/html/wwhelp.htm?context=LiveDocs_Parts&file=00002210.html
		 */

	// removeListener
		/**
		 * Public official method
		 * http://livedocs.macromedia.com/flash/8/main/00002233.html
		 */
	
	// upload
		/**
		 * Public official method
		 * http://livedocs.macromedia.com/flash/8/main/wwhelp/wwhimpl/common/html/wwhelp.htm?context=LiveDocs_Parts&file=00002225.html
		 */
	
	return __commonFileReferenceConstructor(false);
}

/**
 * Object FileReferenceList
 * Unofficial Andrea Giammarchi JavaScript porting for original
 *      FileReferenceList FLash 8 and ActionScript 2.0 class.
 * Original and official Macromedia documentation link ( thank you Macromedia ) :
 * http://livedocs.macromedia.com/flash/8/main/00002226.html#242853
 *
 * Demo test page:
 * http://www.devpro.it/FileReference/
 * ---------------------------------------------
 * @dependenciesrequires Flash 8 plug in and FileReference_List.swf file
 * [ http://www.devpro.it/FileReference/ ]
 * @author              Andrea Giammarchi
 * @site		www.devpro.it
 * @date                2005/10/24
 * @lastmod             2005/11/08 11:00
 * @version             1.0b , tested with FF 1.0.7, IE 6, Opera 8
 */
function FileReferenceList() {
	/** List of all PUBLIC methods */
	// init
		/** 
		 * Public unofficial and required method
		 * ( this method does not exist on original FileReferenceList Flash 8 class )
		 * 
		 * Creates object that will recieve informations from JavaScript FileReferenceList driver.
		 * You have to call this initializzator before use this object and after document (body) is loaded.
		 * The simplest way is to pass this method on onload body function.
		 *
		 *      this.init( HTMLElementID:String ):Void
		 *
		 * @param	String		unique id for an html element where flash object will be created
		 * NOTE: don't worry about your layout, it will be unvisible.
		 */
	
	// addListener
		/** 
		 * Public official method
		 * http://livedocs.macromedia.com/flash/8/main/wwhelp/wwhimpl/common/html/wwhelp.htm?context=LiveDocs_Parts&file=00002205.html
		 */
	
	// browse
		/** 
		 * Public official method
		 * http://livedocs.macromedia.com/flash/8/main/wwhelp/wwhimpl/common/html/wwhelp.htm?context=LiveDocs_Parts&file=00002206.html
		 */

	// removeListener
		/**
		 * Public official method
		 * http://livedocs.macromedia.com/flash/8/main/00002233.html
		 */
	
	return __commonFileReferenceConstructor(true);
}

function __commonFileReferenceConstructor(isList) {
	var frName = 'fr' + Math.round((Math.random() * 1234567890));
	if(isList)
		document[frName] = new __FileReferenceList(frName);
	else
		document[frName] = new __FileReference(frName);
	return document[frName];
}
function __FileReference(frName) {
	this.init = function(divId) {

		var cientBrowser = navigator.userAgent.toLowerCase();
		if(cientBrowser.split('opera').length > 1 && !navigator.javaEnabled()) {
			__supported = false;
		}
		if(__supported && __fromList == false) {
		
			var dname = 'swf' + Math.round((Math.random() * 1234567890));
		
			
			var flash = '<object id="' + dname + '" '
			+ 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '
			+ 'codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" '
			+ 'width="180" height="40">'
			+ '<param name="movie" value="extra/FileReference_List.swf" />'
			+ '<embed swliveconnect="true" '
			+ 'name="' + dname + '" '
			+ 'src="extra/FileReference_List.swf" '
			+ 'type="application/x-shockwave-flash" '
			+ 'pluginspage="http://www.macromedia.com/go/getflashplayer" width="180" height="40" />'
			+ '</object>';
			if(document.getElementById)
				divId = document.getElementById(divId);
			else
				divId = document.all[divId];
			divId.style.display = 'inline';
			divId.style.position = 'absolute';
			divId.style.top = divId.style.left = '0px';
			divId.innerHTML += flash;
			arguments = new Array(arguments[0], dname);
		}
		else if(__supported && __fromList == true) {
			if(document.getElementById)
				divId = document.getElementById(divId);
			else
				divId = document.all[divId];
		}
		

		if(__supported) {
			__flash.name = arguments[1];
			__flash.__requires_change__ = true;
		}
		
	}
	this.addListener = function(listener) {
		if(__supported)
			__listener.push(listener);
	}
	this.browse = function() {
		var done = false;
		if(__supported) {
			this.__checkObject();
			var tmplist = '';
			if(arguments.length == 1) {
				var b = arguments[0].length;
				for(var a = 0; a < b; a++) {
					tmplist += arguments[0][a].description + '\n' + arguments[0][a].extension;
					if((a + 1) < b)
						tmplist += '\n\n';
				}
			}
			__flash.SetVariable('__typelist', tmplist);
			//__flash.TCallLabel('_root', '__callBrowse');
			done = __flash.GetVariable('__sentinel');
		}
		return done;
	}
	this.cancel = function() {
		if(__supported) {
			this.__checkObject();
			__flash.TCallLabel('_root', '__callCancel');
			this.__setVariables(Array(null, null, null, null, null, null));
		}
	}
	this.download = function(url) {
		var done = false;
		if(__supported) {
			this.__checkObject();
			if(arguments.length == 2)
				__flash.SetVariable('__fileName', arguments[1]);
			else
				__flash.SetVariable('__fileName', '');
			__flash.SetVariable('__serverFile', url);
			__flash.TCallLabel('_root', '__callDownload');
			done = __flash.GetVariable('__sentinel');
		}
		return done;
	}
	this.removeListener = function(listener) {
		var done = false;
		if(__supported) {
			var tmplst = Array();
			for(var a = 0; a < __listener.length; a++) {
				if(__listener[a] != listener)
					tmplst.push(__listener[a]);
				else
					done = true;
			}
			__listener = tmplst;
		}
		return done;
	}
	this.upload = function(url) {
		var done = false;
		if(__supported) {
			this.__checkObject();
			__flash.SetVariable('__serverFile', url);
			__flash.TCallLabel('_root', '__callUpload');
			done = __flash.GetVariable('__sentinel');
		}
		return done;
	}
	function __callBackManager(evt, file) {
		if(__supported) {
			var al = new Number(arguments.length - 2);
			if(file[0] != "undefined")
				this.__setVariables(file);
			for(var a = 0; a < __listener.length; a++) {
				if(__listener[a][evt]) {
					if(al == 0)
						__listener[a][evt](this);
					else if(al == 1)
						__listener[a][evt](this, arguments[2]);
					else
						__listener[a][evt](this, arguments[2], arguments[3]);
				}
			}
		}
	}
	function __checkObject() {
		if(__flash.__requires_change__) {
			__flash.__requires_change__ = false;
				__flash = document.getElementById(__flash.name);
			__flash.SetVariable('__JSObject', 'document.' + frName);
		}
		if(__fromList == true) {
			__flash.SetVariable('__realReference', this.name);
			__flash.TCallLabel('_root', '__setRealReference');
		}
	}
	function __setFromList(how) {
		__fromList = how;
	}
	function __setVariables(file) {
		this.name = file[0];
		this.creator = file[1];
		this.creationDate = file[2];
		this.modificationDate = file[3];
		this.size = file[4];
		this.type = file[5];
	}
	var __supported = true;
	var __fromList = false;
	var __flash = new Object();
	var __listener = new Array();
	this.__callBackManager = __callBackManager;
	this.__setFromList = __setFromList;
	this.__setVariables = __setVariables;
	this.__checkObject = __checkObject;
	this.name = 
	this.creator = 
	this.creationDate = 
	this.modificationDate = 
	this.size = 
	this.type = null;
}
function __FileReferenceList(frName) {
	this.init = function(divId) {

		var cientBrowser = navigator.userAgent.toLowerCase();
	//	if(cientBrowser.split('opera').length > 1 && !navigator.javaEnabled()) {
	//		__supported = false;
//		}
		if(__supported) {
		/*	var dname = 'swf' + Math.round((Math.random() * 1234567890));
			var fname = 'swf' + Math.round((Math.random() * 1234567890));
			
			*/
			
			__divId = divId;
			var flash = '<object id="' + dname + '" '
			+ 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '
			+ 'codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" '
			+ 'width="500" height="500">'
			+ '<param name="wmode" value="transparent">'
			+ '<param name="movie" value="extra/FileReference_List.swf?'+Math.random()+'" />'
			+ '<embed swliveconnect="true" '
			+ 'id="' + fname + '" '
			+ 'src="extra/FileReference_List.swf?'+Math.random()+'" '
			+ 'type="application/x-shockwave-flash" wmode="transparent" '
			+ 'pluginspage="http://www.macromedia.com/go/getflashplayer" width="280" height="60" />'
			+ '</object>';
		
		/*	if(document.getElementById)
				__currentDivId = document.getElementById(divId);
			else
				__currentDivId = document.all[divId];

			__currentDivId.style.display = 'inline';
			__currentDivId.style.position = 'absolute';
			__currentDivId.style.top = __currentDivId.style.left = '0px';
			__currentDivId.innerHTML += flash;*/
			
			if (navigator.userAgent.indexOf('MSIE') != -1)
			__flash.name = __swfId = dname;
			else
			__flash.name = __swfId = fname;
			__flash.__requires_change__ = true;
		}
	}
	this.addListener = function(listener) {
		if(__supported)
			__listener.push(listener);
	}
	this.browse = function() {
		var done = false;
		if(__supported) {
			__checkObject();
			var tmplist = '';
			if(arguments.length == 1) {
				var b = arguments[0].length;
				for(var a = 0; a < b; a++) {
					tmplist += arguments[0][a].description + '\n' + arguments[0][a].extension;
					if((a + 1) < b)
						tmplist += '\n\n';
				}
			}
			__flash.SetVariable('__typelist', tmplist);
			//__flash.TCallLabel('_root', '__callBrowseList');
			done = __flash.GetVariable('__sentinel');
		}
		return done;
	}
	this.removeListener = function(listener) {
		var done = false;
		if(__supported) {
			var tmplst = Array();
			for(var a = 0; a < __listener.length; a++) {
				if(__listener[a] != listener)
					tmplst.push(__listener[a]);
				else
					done = true;
			}
			__listener = tmplst;
		}
		return done;
	}
	function __callBackManager(evt, frList) {

		if(__supported) {
			
			
			if(frList.length > 0) {
				this.fileList = new Array();
				for(var a = 0; a < frList.length; a++) {
					this.fileList[a] = new FileReference();
					this.fileList[a].__setFromList(true);
					this.fileList[a].init(__divId, __swfId);
					this.fileList[a].__setVariables(frList[a]);
				}
			}
			for(var a = 0; a < __listener.length; a++) {
				if(__listener[a][evt])
					__listener[a][evt](this);
			}
		}
	}
	
	//this.checkObj() = function { __checkObject(); }
	
	function __checkObject() {
		
	//	if(__flash.__requires_change__) {
			__flash.__requires_change__ = false;
			
			__flash = document.getElementById(__flash.name);
			//__flash = document[__flash.name];
			globalVar1 = __flash;
	
			
			__flash.SetVariable('__JSObject', 'document.' + frName);
	//	}
	}
	var __supported = true;
	var __divId = '';
	var __swfId = '';
	var __currentDivId = '';
	var __flash = new Object();
	this.flashObj = 'aa';
	
	var __listener = new Array();
	
	this.__callBackManager = __callBackManager;
}