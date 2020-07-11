/*
 * jyingoXT, PHP Ajax Toolkit http://www.jyingo.com
 * Copyright 2011-2014 Andrea Pezzino (haxormail@gmail.com) 
 * 
 * WARNING: jyingoXT is not open source. You are allowed to use
 * this source code only under the following terms:
 *
 * 1) You must give appropriate credit, provide a link to the license, and
 * indicate if changes were made. You may do so in any reasonable manner,
 * but not in any way that suggests the licensor endorses you or your use.
 * 2) You may not use the material for commercial purposes.
 * 3) If you remix, transform, or build upon the material, you may
 *   not distribute the modified material.
 * 
 * Please refer to license.txt
 *  
 */ 
if (location.hash.substr(1,1) == '!')
{
 location.href = location.hash.substr(2);
}
var FB = 0;
Number.prototype.formatNumber = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };


if (typeof console == "undefined")
{
	
	var console = {};
	console.log = function(a) { };
	
}

var Utf8 = {
 
	encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
		
		if (escape(string).toUpperCase().indexOf('%C3') != -1)
		 return string;
 
		for (var n = 0; n < string.length; n++) {
 
			var c = string.charCodeAt(n);
 
			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
 
		}
 
		return utftext;
	},

 
	decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
 
		while ( i < utftext.length ) {
 
			c = utftext.charCodeAt(i);
 
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
 
		}
 
		return string;
	}
 
};

var jyingo = {};
(function() {
		
	var instance_id = null;
	var form = null;
	var object_tree = [];
	var __j_objects = [];
	var delegates = [];
	var mouse_events = [];
	var _evstatus = [];
	var _seq = 0;
	var __current_x = 0;
	var _mouse_is_down = false;
	var _parent_resolve_table = {};
	var _wmgr = null;
	var __current_y = 0;
	var _jyingoevents = [];
	var __debugging = false;
	var __windows = [];
	var __object_names = [];
	var _sound_manager = false;
  var _msgreceivers = {};
  var _wcshowing = null;
  var _override_transition_type = null;
  
  jyingo = {
	 extend : //function(m, b, a) { (a == undefined ? (a=b,b=m,m=this) : ''); var shared={}; this[b] = function(settings){  return new a(function(){}, settings, shared); }},
	  function(n, o, m) {
    var shared = {};
    this[n] = function(settings, sc) {
        var ctx = function(){}, su = {}, ct = {};
        (m && (ct = new m(settings, -99999999)) && (ctx.prototype = ct));
        function isg(obj, p) { return Object.getOwnPropertyDescriptor(obj, p) != undefined && (Object.getOwnPropertyDescriptor(obj, p).get  != undefined || Object.getOwnPropertyDescriptor(obj, p).set != undefined); };
        function isgo(obj, p) { var c = obj; while (c) { if (isg(c, p)) return true; c = Object.getPrototypeOf(c); }return false;};
        for (var k in ct) (isgo(ct, k) == false &&  ct[k] instanceof Function) && (su[k] = ct[k]);
        ((ctx.proto == undefined) && (ctx.proto = function(u) { for (var j in u) { 
        	
        	 if ( Object.getOwnPropertyDescriptor(u, j).get || Object.getOwnPropertyDescriptor(u, j).set )
        	  Object.defineProperty(ctx.prototype, j, Object.getOwnPropertyDescriptor(u, j))
        	 else
        	  ctx.prototype[j] = u[j]; 
        	 
        }}));
        var r = new o(ctx, settings, shared, su);
				for (var k in su) su[k] = jyingo.delegate(r, su[k]);
				if (sc !== -99999999 && r.construct) r.construct.apply(r, arguments);
        return r; 
    };
   },
   
	 createSwfObject : function(src, attributes, parameters) {
	  var i, html, div, obj, attr = attributes || {}, param = parameters || {};
	  attr.type = 'application/x-shockwave-flash';
	  if (window.ActiveXObject) {
	    attr.classid = 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000';
	    param.movie = src;
	  }
	  else {
	    attr.data = src;
	  }
	  html = '<object';
	  for (i in attr) {
	    html += ' ' + i + '="' + attr[i] + '"';
	  }
	  html += '>';
	  for (i in param) {
	    html += '<param name="' + i + '" value="' + param[i] + '" />';
	  }
	  html += '</object>';
	  div = document.createElement('div');
	  div.innerHTML = html;
	  obj = div.firstChild;
	  div.removeChild(obj);
	  return obj;
	},
  
  listen : function(listener, message)
	{
	  	if (!_msgreceivers[message])
	  		_msgreceivers[message] = new Array();
	  	
	  	_msgreceivers[message].push(listener);
	},
	set_override_transition_type : function(type)
	{
	 _override_transition_type = type;
	 _jyingo_popup_transition_style = type;
	},
	  
	get_override_transition_type : function()
	{
		return _override_transition_type;
	},
	
	build_children_list : function(obj, name)
	  {
				var children = [];
				
				for (var instance in __j_objects)
				{
					
					var j = __j_objects[instance];
					if (j.get_parent() == obj && j.get_name() == name)
					 return j;
					else if (j.get_parent() == obj)
					 children.push(j);
					
				}
				
				return children;	  	
	},	  
	  
	unbind : function(owner)
	{
	  	
	var _p = [];
	for (var i in _jyingoevents)
	  if (_jyingoevents[i][2] != owner)
	   _p.push(_jyingoevents[i]);
	  	  
	 
	 _jyingoevents = _p;
	 
	},
	  
	bind : function(evname, callback, owner)
	{
	 	 _jyingoevents.push([evname, callback, owner]);
	},
	  
	unset : function(inst)
	{
	 delete __j_objects[inst];
	},
	  
	unset_name : function(name)
	{
	  delete __object_names[name];
	},
	  
	get_window_info : function(instance)
	{
	 	return __windows[instance];
	},
	
	get_window_offsetW : function()
	{
		return 0;
	},
	
	get_window_offsetX : function()
	{
		return 0;
	},
	
	get_window_width : function()
	{
		return $(window).width();
	},
	
	get_window_height : function()
	{
		return $(window).height();
	},
	
	_wshowing : function(_wz)
	{
	  	
	 var obj = this.get_by_instance(_wz);
	 if (!obj)
	 	return;
	  	 
	 if (_wcshowing != obj)
	 {
	 	_wcshowing = obj;
	 	this._customcall('windowShow', obj);
	 		
	 }
	},
	  
	get_window : function()
	{
	 if (_wcshowing == null)
	  return null;
	 return __windows[_wcshowing._instance];
	}, 
	 
  _customcall : function(evname, params)
	{
	  for (var i = 0; i < _jyingoevents.length; i++)
	  {
	  	 if (_jyingoevents[i][0] == evname)
	   	 _jyingoevents[i][1](params); 	
	  } 
	},
  
  propagate_message : function(sender, message, data)
  {
  	 
	   var receivers = _msgreceivers[message];
  	 if (receivers instanceof Array)
  	 {
  	 	 
  	 	 for (var i = 0; i < receivers.length; i++)
  	 	 {
  	 	 	  
  	 	 	  var rcvr = receivers[i];
  	 	 	  rcvr.received_message(sender, message, data);
  	 	  	
  	 	 } 
  	  	
  	 } 
  	
  }, 
  
  unlisten : function(receiver, message)
  {  	 
	 	 for (var msg in _msgreceivers)
	 	 {
	 	   if ( message == undefined || msg == message )
	 	   {     
	  	    var idx = _msgreceivers[msg].indexOf(receiver);
	  	    if (idx != -1)
	  	     _msgreceivers[msg].splice(idx, 1);
	  	   	
	     }	
	 	 
	   }	
	},
	
	set_sound_manager : function(mgr)
	{
	 _sound_manager = mgr;
	},	
	
	sound : function()
	{
		return this._sound_manager;
	},
	
	is_empty : function(arr)
	{
	
	 if (arr.length != undefined && arr.length > 0)
	  return false;
		
	 for (var i in arr)
	  return false;
	 
	 return true;
	},
	
	
	count_object : function(o)
	{
		 var cnt = 0;
		 for (var i in o) cnt++;
		 return cnt;
	},
	
	init : function(id) {
		
		instance_id = id;
    form = document.forms.form1;
    
     
     

		if ('ontouchstart' in document.documentElement) { 

		 $(document).bind('touchstart', this.delegate(this, this._touch_down));
		 $(document).bind('touchend', this.delegate(this, this._touch_up));
	   //$(document).bind('touchmove', this.delegate(this, this._mouse_move));    
    
    } else {
    	

	  $(document).bind('mousedown', this.delegate(this, this._mouse_down));
	  $(document).bind('mouseup', this.delegate(this, this._mouse_up));  	
   
    } 

    
	  $(document).bind('mousemove', this.delegate(this, this._update_mouse_coords));
	  $(document).bind('mouseover', this.delegate(this, this._mouse_move));
	  $(document).bind('mouseout', this.delegate(this, this._mouse_move));   
    
    //$(window).bind('unload', this.delegate(this, this._unload));
    
    
    mouse_events['inside'] = [];
    mouse_events['inside']['mousedown'] = [];
    mouse_events['inside']['mouseup'] = [];
    mouse_events['inside']['mousemove'] = [];
    mouse_events['inside']['mouseover'] = [];
    mouse_events['inside']['mouseout'] = [];
    
    mouse_events['outside'] = [];
    mouse_events['outside']['mousedown'] = [];
    mouse_events['outside']['mouseup'] = [];
    mouse_events['outside']['mousemove'] = [];
    mouse_events['outside']['mouseover'] = [];
    mouse_events['outside']['mouseout'] = [];
    
	},
	
	set_window_manager : function(wmgr)
	{
		_wmgr = wmgr;
	},
	 
	get_window_manager : function()
	{
		return _wmgr;
	},
		
	_unload : function()
	{
		
		for (var instance in __j_objects)
		{
			__j_objects[instance]._dispose(false);
		}
		
	},
	
	_update_mouse_coords : function(event, __is_touch)
	{

		var ev = this.eventize(event);
		var x, y;
	  if (__is_touch === true && ev.targetTouches &&
	    ev.targetTouches.length)
	  { 
		  x = ev.targetTouches[0].pageX,
		  y = ev.targetTouches[0].pageY;	   	
	  } 
		else if (ev.pageX && ev.pageY)
		{
		  x = ev.pageX;
		  y = ev.pageY;	
		} else {
		  x = ev.clientX + $(document).scrollLeft();
		 	y = ev.clientY + $(document).scrollTop();
		} 
		__current_x = x;
 	  __current_y = y;	
	},
	
	_mouse_move : function(event)
	{
     this._update_mouse_coords(event);
	 	 this._catch_call('inside', 'mousemove');		
		 this._catch_call('outside', 'mousemove');	
	},

	
	get_mouse_coords : function(coordspace)
	{
			
			
		var tx = 0;
		var ty = 0;
			
		if (coordspace === 'window' || coordspace === window)
		{
			tx = $(document).scrollLeft();
			ty = $(document).scrollTop();
		}
	  else
		if (typeof coordspace==="object" && coordspace.tagName != undefined)
		{
		    
		  var pos = $(coordspace).offset();
		  tx = $(document).scrollLeft() + pos.left;
		  ty = $(document).scrollTop() + pos.top;
		   
		  var result = 0;
		   
		  if (__current_x -tx < 0 || __current_y-ty < 0 || __current_x -tx > $(coordspace).outerWidth() || __current_y-ty > $(coordspace).outerHeight())
		   result++;
	
	    pos = $(coordspace).offset();
		  tx = pos.left;
		  ty =  pos.top;
		    
		    
	    if (__current_x -tx < 0 || __current_y-ty < 0 || __current_x -tx > $(coordspace).outerWidth() || __current_y-ty > $(coordspace).outerHeight())
		   result++;	     
		    
		  if (result == 2) 
		   return false;
		     
		}
			
		return { x : __current_x - tx, y : __current_y-ty };
	},
	
	inside : function(obj)
	{
	  var l = new Date().valueOf();
	  var pos, w, h;  
		if (obj._offset == undefined || l - obj._lcache > 600 )
	  {
	        
	    if (obj._get_frame != undefined)
	    {  	
	      pos = obj._get_frame();
	      w = pos.width;
	      h = pos.height;	 
	    } else {
			  pos = $(obj).offset();
			  w = $(obj).outerWidth();
			  h = $(obj).outerHeight();
			}
		
		  obj._offset = pos;
		  obj._w = w;
			obj._h = h;
			  
			obj._lcache = l;
		 } else {
			   pos = obj._offset;
			   w = obj._w;
			   h = obj._h;
		 }
		 if (__current_x >= pos.left && __current_x <= pos.left+w &&
			   __current_y >= pos.top &&  __current_y <= pos.top+h)
		 {
			return true;
		 }
		 
		return false;
			 
  },
  
	is_mouse_down : function()
	{
		return _mouse_is_down;
	},	
	
	_mouse_up : function(event)
	{
		 _mouse_is_down = false;
	 	 this._catch_call('inside', 'mouseup');		
		 this._catch_call('outside', 'mouseup');		
	},

	_mouse_down : function(event)
	{
		 _mouse_is_down = true;
	 	 this._catch_call('inside', 'mousedown');		
		 this._catch_call('outside', 'mousedown');	
	},	
	_touch_down : function(event)
	{
    this._update_mouse_coords(event, true);
		_mouse_is_down = true;
		this._catch_call('inside', 'mousedown');		
		this._catch_call('outside', 'mousedown');	
	},
	
  _touch_up : function(event)
  {
		this._update_mouse_coords(event, true);
		_mouse_is_down = false;
		 this._catch_call('inside', 'mouseup');		
		this._catch_call('outside', 'mouseup');	
  },	
  
	_catch_call : function(type, evname, obj)
	{
		
		
		var arr = mouse_events[type][evname];

	
		for (var id in arr)
		{
			
			
			
			var objects = arr[id][1];
			var callback = arr[id][0];
			
			
			if (type == 'inside')
			{
			  var found = false;
			  
			  
			  if (_evstatus[id] === 1 && evname == 'mouseover')
			  {
			  	
			  } else {
			  	
				  for (var s = 0; s < objects.length; s++)
					{
				  
				  	if (this.inside(objects[s]) && $(objects[s]).css('display').toLowerCase() != 'none')
				  	{ 
		
		
				  		found = true;
				  		break;
				  	}
			    }
			  }
			  
			  if (found)
			  {
			   _evstatus[id] = 1;
			   callback();
				}
		  }
		  
		  if (type == 'outside')
		  {
		  	var found = true;
		  	

			  if (_evstatus[id] === 2 && evname == 'mouseout')
			  {
			  	found = false;
			  } else {
			  	
			  	var __skip = null;
			  	if ( obj && obj.id && obj.target ) __skip = obj.target;
			  	
			  	for (var s = 0; s < objects.length; s++)
				  {
	          
				  	if (__skip != objects[s] && this.inside(objects[s]))
				  	{ 
				  		found = false;
				  		break;
				  	}
				  	
				  }
				  
				  
			  }
		  	if (found)
			  {
			   _evstatus[id] = 2;
			   callback();		  	
		  	}
		  	
		  }
		  
		  
		}
		
	},
// id, event_name, callback,  object_1, object_2, object_3
  catch_outside : function()
  {
  	
  	var args = this.arguments_to_array(arguments);
  	
  	
  	var id = args[0];
  	var callback = args[2];
  	var name = args[1];
  	
  	var objects = args.slice(3);
  	
 // 	console.log('object count: '+objects.length);
  	
  	if (objects.length == 0)
  	{
  		delete mouse_events['outside'][name][id];
  		
  	} else {
  	  mouse_events['outside'][name][id] = new Array(callback, objects);
  	} 	
  	
  	if (name == 'mouseout')
  	{
  		for (var i = 0; i < objects.length; i++)
  		{
       var obj = { target: objects[i],  id : id, name : name, f : function(event) { jyingo._catch_call_outside_mouseout_object(event, this); } };
  		 $(objects[i]).bind('mouseleave', this.delegate(obj, obj.f));
  		}
  		_evstatus[id] = -1;
  		
  	}
 
  }, 
   
  // id, event_name, callback,  object_1, object_2, object_3
  catch_inside : function()
  {
  	
  	var args = this.arguments_to_array(arguments);
  	
  	var id = args[0];
  	var callback = args[2];
  	var name = args[1];
  	
  	var objects = args.slice(3);
  	
  	if (objects.length == 0)
  	{
  		
  		
  		
  		delete mouse_events['inside'][name][id];
  	} else {
  	  mouse_events['inside'][name][id] = new Array(callback, objects);

	  	if (name == 'mouseover')
	  	{
	  	
	  		
	  		for (var i = 0; i < objects.length; i++)
	  		{
	  		 $(objects[i]).bind('mouseover', this.delegate(this, Function("return jyingo._catch_call_inside_"+name+";")()));
	  		}
	  		
	  		_evstatus[id] = -1;
	  		
	  	}

  	}
  	


  	
  },	
  
  arguments_to_array : function(args)
  {
  	
  	var ret = [];
  	for (var i = 0; i < args.length; i++)
  	 ret.push(args[i]);
  	return ret;
  	
  },
  
  classof : function (obj) {
   if (typeof obj != "object" || obj === null) return false;
   return /(\w+)\(/.exec(obj.constructor.toString())[1];
  },
 
  
  
  _catch_call_outside_mouseover : function(event)
  {
  	this._mouse_move(event);
  	this._catch_call('outside', 'mouseover');
  },

  _catch_call_outside_mouseout_object : function(event, obj)
  {
    this._mouse_move(event);
  	this._catch_call('outside', 'mouseout', obj);
  },


  _catch_call_inside_mouseout: function(event)
  {
  	this._mouse_move(event);
  	this._catch_call('inside', 'mouseout');
  },

  _catch_call_inside_mouseover : function(event)
  {
  	this._mouse_move(event);
  	this._catch_call('inside', 'mouseover');
  },

  _catch_call_outside_mouseout: function(event)
  {
    this._mouse_move(event);
  	this._catch_call('outside', 'mouseout');
  },
	
	get : function(element)
	{
		if (__j_objects[element])
		 return __j_objects[element];
		 

		if (__object_names[element])
		 return __object_names[element];
		
		
		if (document.getElementById(element))
		 return document.getElementById(element);
		
		return null;
	},
	
	
	delegate : function(obj, func)
	{
		return function(){return func.apply(obj,arguments)}
	},
	
	replace : function(what, replace, where)
	{
		return where.split(what).join(replace);
	},
	
	event : function(caller, event)
	{
		
	 __debugging = true;
		
		var name = caller.get_instance();
		if (!name)
		 return;
		
		var data = this.get_form_data();
	  data = data.concat(this.get_object_data());
  
    var _instance = instance_id;
    if (!_instance)
     return;	  
	  
	  var out = new Array();
	  out.push( instance_id );
	  out.push('event');
	  out.push( caller.get_instance() );
	  out.push( event );
	  out.push( data );
	  
	  _seq++;

		var href = window.location.href.split("#")[0];
		var dest =  href + (href.indexOf('?') == -1 ? '?' : '&') + 'rand='+Math.random()+'&seq='+_seq; 
		  
	  $.ajax({url: dest, success:this.delegate(this, this.data), type:'POST', data:(JSON.stringify(out))});
	  
	  
	},
	
	call : function(caller, delegate, service, params)
	{
		

    var _instance = caller.get_instance();
    if (!_instance)
     return;
		
	  var out = new Array();
	  out.push( instance_id );
	  out.push('call');
	  out.push( _instance );


	  
	  var code = $.base64Encode(Math.random().toString());
	  
	  delegates[code] = delegate;
	  
		out.push( service );
		out.push( params );
		
    out.push( code );
	  	  

	  _seq++;
		var href = window.location.href.split("#")[0];
		var dest =  href + (href.indexOf('?') == -1 ? '?' : '&') + 'rand='+Math.random()+'&seq='+_seq; 

		
	  $.ajax({url:dest, success:this.delegate(this, this.data), type:'POST', data:(JSON.stringify(out))});

	},
	
	get_object_data : function()
	{
		
		var arr = new Array();
		
		for (var instance in __j_objects)
			 if (__j_objects[instance].get_data != undefined)
			  arr.push( new Array(instance, __j_objects[instance].get_data() ));
			 	
		
		return arr;
	},
	
	data : function(d)
	{
	   var _st_debugIndex = d.indexOf('[;--DEBUGINFO--:]');
		 if (_st_debugIndex != -1)
		 {
		 	var dd = d.substr(_st_debugIndex+17).replace('[;--DEBUGINFO--:]','');
		 	if (dd.length)
		 	{
		 		
		 		 var el = document.getElementById('pJyingoDebugDiv');
		 		 if (!el)
		 		 {
		 		 	 el = document.createElement('div');
		 		 	 el.id = 'pJyingoDebugDiv'
		 		 	 document.body.appendChild(el);
		 		 	 
 		 	  
		 		 	 
		 		 	 $(el).niceScroll();
		 		 }
		 		 
		 		 var pre = document.createElement('pre');
		 		 pre.innerHTML = dd.substr(2);
		 		 if (el.childNodes.length) 
		 		  el.insertBefore(pre, el.childNodes[0]);
		 		 else
		 		 	el.appendChild(pre);
		 		
		 		
		 	}
		 	
		  d = d.substr(	0, _st_debugIndex );
		 }
		 
		 if (d.indexOf('e0_refresh_page') != -1)
		 {
		  location.reload();
		  return;	
		 } 
 	 
 	 
 	 if (!d.length) 
   return;

	 var out = eval('('+d+')');
		
     if (out['clear'] != undefined)
     {
      
      
       for (var i = 0; i <  out['clear'].length; i++)
       if (__j_objects[out['clear'][i]] != undefined)
       {
        this._customcall('onClear', __j_objects[out['clear'][i]]);
        this.dispose_by_parent(__j_objects[out['clear'][i]]);
       }
     	
     }
		 if (out['load'] != undefined)
		 {
		 	
		 	 var _toload = out['load'];
		 	
		 	 var _cycles = 0;
		 	 while (1)
		 	 {
		 	   
		 	   var _continue = false;
		 	   
			   for (var instance in _toload)
			   {
			 
			   	 _continue = true;
			   	 
			   	 var parent = out['load'][instance]['parent'];
			   	 var data = (out['load'][instance]['data']);
			   	 var before = out['load'][instance]['before'];
			   
		
			   	 if (!data) {
			   	 	  _cycles = 0;
			   	  	delete _toload[instance];
			   	  	continue;
			   	 }
			   	 
			   	 var _element;
			   	 if (data.indexOf('<option') == 0)
			   	 {

            _element = document.createElement('option');
            _element.value = instance;
            
			   	   
			   	 } else {

				   	 var obj = document.createElement('div');
				   	 obj.innerHTML = data;
			   	   
			   	   _element = obj.childNodes[0];

			   	 }
			   	 
			   	 
			   	 
			   	 
			   	 
			   	 if (before == undefined)
			   	 {
			   	 
			   	  __j_objects[parent]._addChild(_element);
			   	 
			   	 } else if (before == 0) {
			   	 	
			   	 	
			   	 	
			   	 	__j_objects[parent]._insertBefore(_element, 0);

			   	 } else { 
			   	 	
			   	 	
			   	 	var _before = __j_objects[parent]._get_element(before);
			   	 	
			   	 	if (!_before) 
			   	 	 _before = __j_objects[before] ? __j_objects[before] : document.getElementById(before);
			   	 	
			   	 	if (__j_objects[before] == undefined && !document.getElementById(before) && !_before) continue;
			   	 	
			   	 	
			   	 	__j_objects[parent]._insertBefore(_element, _before);
			   	 		  	
			   	 	
			   	 }
			   	 
			   	 
			   	 delete _toload[instance];
			   	 _cycles = 0;
			   } 
			   
		   	 if (!_continue || _cycles++ == 100) break;
		 	 }
		 }
		 if (out['c'] != undefined)
		 {
		  
		  if (out['c']['vars'] != undefined)
		  for(var instance in out['c']['vars'])
		  {
		  	
		  	var changes = out['c']['vars'][instance];
		  	if (__j_objects[instance] != undefined)
		  	 __j_objects[instance]._changes(changes);
		  }	
		 
		  if (out['c']['styles'] != undefined)
		  for(var instance in out['c']['styles'])
		  {
		  	
		  	var changes = out['c']['styles'][instance];
		  	if (__j_objects[instance] != undefined)
		  	 __j_objects[instance]._stylize(changes);
		  	
		  
		  }	

		  if (out['c']['htmlprops'] != undefined)
		  for(var instance in out['c']['htmlprops'])
		  {
		  	
		  	var changes = out['c']['htmlprops'][instance];
		  	if (__j_objects[instance] != undefined)
		  	 __j_objects[instance]._htmlize(changes);
		  }	
		 	
		 }
		 

		 
		 if (out['s']) eval(out['s']);

 
 
     if (out['caller'] != undefined && __j_objects[out['caller']])
     {
		 	__j_objects[out['caller']]._set_disabled(false); 
		 	__j_objects[out['caller']]._called(); 
		 }
		 
     if (out['service'] != undefined)
     {
     	
     	var data = out['service_data'];
     	var service = out['service'];
     	
     	if (delegates[service] != undefined)
     	{
     		
     		if (delegates[service])
     		 delegates[service](data);
     		delete delegates[service];
     		
     	} 
     }
  	 this._customcall('eventRequest', this);
	},
	
	
	
	cancel_event : function(ev)
	{

 	   ev.returnValue = false;
 	   ev.cancelBubble = true;
 	   
 	   if (ev.stopPropagate)
 	    ev.stopPropagate();
 	   
     if (ev.preventDefault)
      ev.preventDefault();
      
     return false;	
	},
	
	get_form_data : function()
	{
	
		var arr = [];
		
		for (var i = 0; i < form.length; i++)
		{
			
			var el = form[i];
			if (el.type == undefined) continue;
			

			var name = el.name;
			if (__j_objects[name] != undefined && __j_objects[name].get_data != undefined)
			 continue;
			
		  if (el.type.toLowerCase() == 'checkbox')
		  {
		  
		 	 arr.push(new Array(el.name, el.checked));
		  }
		  else if (el.type.toLowerCase() == 'radio')
       arr.push(new Array(el.name, el.checked));	
			else	  	
			 arr.push(new Array(el.name, el.value));
			
		}
		
		return arr;
	},
	
	eventize : function(event)
	{
		 if (window.event) return window.event;
		 return event;
	},
	
	get_domain : function(str)
	{

		if (str.indexOf('://') != -1)
		{
			
			
			var s = str.split('://', 2);
			var t = s[1].split('/', 2);
			t = t[0];
			
			var url1 = (s[0]+'://'+t).toLowerCase();
			return url1;
			
			
		} else {
			 return '';
		}
	
	},
	
	get_by_instance : function(instance)
	{
		 
		 if (__j_objects[instance] != undefined)
		  return __j_objects[instance];
		 
		 return null;
		 
	},
	
  dispose_by_parent : function(parent, dont_remove)
	{
		var i = 0;
	  while (parent._children.length-i)
	   if (parent._children[0] != parent)
	   {
	    parent._children[0]._dispose(true, dont_remove);
	    parent._children.splice(0,1);
	   }
	   else
	   	i++;

		parent._clear();

	},
	
	get_by_parent_ordered : function(parent, type)
	{
		
		
		var ret = [];
		var cnt = 0;
		
		var el = parent.get_element();
		for (var i = 0; i < el.childNodes.length; i++)
		{
			
			var j = el.childNodes[i];
			if (j.jyingoObject == undefined)
			 continue;
			
			if (type != undefined && !(j.jyingoObject instanceof type))
			 continue;
			
			ret.push(j.jyingoObject);
			
			
		}
		
		
		return ret;
	},	
	get_by_parent : function(parent, type, recursive, _ret)
	{
		
		recursive = recursive ==  undefined ? false : true;
		
		var ret = _ret == undefined ? {} : _ret;
		var cnt = 0;
		

		
		for (var instance in __j_objects)
		{
			
			if (__j_objects[instance].get_parent() == parent)
			{
			 
			 if (type == undefined || __j_objects[instance] instanceof type)
			 {
			 	

			 	
			  ret[instance] = ( __j_objects[instance] );
			  cnt++;
			 }
			
	     if (recursive == true)
	     {
	     	
	      ret = this.get_by_parent(__j_objects[instance], type, recursive, ret);
	     }      
      
      }
      

      
		}
		
		return ret;
	},
	
  array_call : function(cb, parameters) {


    if (typeof cb === 'string') {

        func = (typeof this[cb] === 'function') ? this[cb] : func = (new Function('return ' + cb))();
    }
    else if (Object.prototype.toString.call(cb) === '[object Array]') {
        func = (typeof cb[0] == 'string') ? eval(cb[0] + "['" + cb[1] + "']") : func = cb[0][cb[1]];
    }
    else if (typeof cb === 'function') {
        func = cb;
    }

    if (typeof func !== 'function') {
        throw new Error(func + ' is not a valid function');
    }

    return (typeof cb[0] === 'string') ? func.apply(eval(cb[0]), parameters) : (typeof cb[0] !== 'object') ? func.apply(null, parameters) : func.apply(cb[0], parameters);
  },

	load_r : function(arr, debug)
	{
		
   
		
	 while (!this.is_empty(arr))
		for (var load_id in arr)
		{
			
			var obj = arr[load_id];
       
      var flag1 = this.get(obj['parent']) ? 'true' : 'false';

      

			if (obj['parent'] && !this.get(obj['parent']))
			 continue;
			
			
			
			this.load(eval(obj['class']), obj['name'], obj['instance'], obj['parent'], obj['data'], obj['visible'], obj['disabled'], obj['allow_postback']);
			
			delete arr[load_id];
			
			
		}
	},
	
	exscript : function(_url, _onload)
	{
		var script = document.createElement('script');
		script.src = _url+'&r='.Math.random();
		script.type = 'text/javascript';
		
		if (_onload != undefined)
		{
			script.onload = _onload;
		}
		
		document.getElementsByTagName('head')[0].appendChild(script);
		
	},
	
	ajax : function(data)
	{
		
		var _success = data['success'];
		var _url = data['url'];
		var _async = data['async'] ? data['async'] : true;
		var _post = data['data'] ? data['data'] : null;
		var _type = _post ? 'post' : 'get';
    
    var obj = {};
    obj.delegate = _success;
    
    obj.call = function()
    {
    	
    	var resp = this.xhr.responseText;
    	if (typeof resp === 'string')
    	 resp = eval('('+resp+')');
    	
    	this.delegate(resp);
    	
    }
    
    
	
		var xhr = new XMLHttpRequest(); 
    if ("withCredentials" in xhr || _async == false){ 
     xhr.open(_type, _url, _async); 
     xhr.onload = this.delegate(obj, obj.call);

     obj.xhr = xhr;
     
    } else if (typeof XDomainRequest != "undefined"){ 
     xhr = new XDomainRequest(); 
     xhr.open(_type, _url); 
     obj.xhr = xhr;
     
     xhr.onload  = this.delegate(obj, obj.call);
    } 
    
    xhr.timeout = 30;
    
    xhr.send(_post);
    return xhr;

		
	},
	
	loadwnd : function(name, instance, parent, transition, type)
	{
		
		var settings = {};
		settings._name = name;
		settings._instance = instance;
		settings._parent = parent;
		settings._transition = jyingo.get_override_transition_type() ? jyingo.get_override_transition_type() : transition;
		settings._type = type;			
		
		var obj = new jyingo.window(settings);

		__windows[instance] = obj;		
		obj._initialize();
		
    this._customcall('windowLoad', obj);
		
	},
	
	load : function(cls, name, instance, parent, data, visible, disabled, allow_postback)
	{

		if (instance == null)
		{
			
			instance = (Math.floor(Math.random()*8999)+1000)+'-'+(Math.floor(Math.random()*8999)+1000)+'-'+(Math.floor(Math.random()*8999)+1000);
			
		}
		
		
		if (allow_postback == undefined)
		 allow_postback = true;
		
		var obj = new cls();
		
		obj._children = [];
		obj._name = name;
		obj._instance = instance;
		obj._parent = parent;
    obj._disabled = false;
    obj._delegates = [];
    obj._timers = {};
    obj._visible = true;
    obj._allow_postback = allow_postback;
    obj._disabled = false;
    obj._catch_events = [];
    
    if (visible == undefined)
     visible = true;
    
    if (disabled == undefined)
     disabled = false;


		__object_names[name] = obj;

		obj.get_parent = function() {
			return jyingo.get_by_instance(this._parent);
		};
		
		if (!obj.received_message)
		obj.received_message = function(a, b, c)
		{
			
		};
		
		if (!obj.get_class)
		obj.get_class = function()
		{
			return Function("return "+jyingo.classof(this))();
		};
		obj.immediate = function(callback, params)
		{
			 var name = 'immediate_'+$.base64Encode(Math.random().toString());
			 if (typeof setImmediate == 'undefined')
			 {
			   
			   this.timer(name, callback, 0, params);
			 	
			 } else {
			 
			 
			   var obj = { func : this.create_delegate(callback), func2 : function() { this.func(this.params); }, params : params, obj : this };
			   setImmediate(jyingo.delegate(obj, obj.func2));
			 	
			 }
			 
		};
		
		obj.timer = function(name, callback, interval, params, repeats)
		{
			
			 if (this._timers[name])
			 {
			 	 
			 	 if (callback)
			 	  return;
			 	
			   clearInterval(obj._timers[name]);
			   obj._timers[name] = null;	
			 }
			 
			 if (interval == undefined)
			 {
			  interval = 0;
			 }
			 
			 if (interval == 0)
			  repeats = 1;
			 
			 if (params == undefined)
			  params = null;
			 
			 if (repeats == undefined)
			  repeats = -1;
			 
			 
			 
			 var timer = setInterval(this.create_delegate(function() {
			 	
			 if (this._timers[name].params)
			 	 this._timers[name].callback(this._timers[name].params);
			 else
			 	 this._timers[name].callback();
			 
			 
			 if (this._timers[name].repeats != -1)
			 {
			 	
			 	
			   this._timers[name].repeats--;
			   if (!this._timers[name].repeats)
			    this.clearTimer(name);
			    
			 }
			 	
			 }), interval);
			 
			 this._timers[name] = { timer : timer,
			 	                      callback : this.create_delegate(callback),
			 	                      repeats : repeats,
			 	                      params : params,
			 	                    interval : interval };
			 
			 return name;
		};
		
		obj.clearTimer = function(name)
		{
			if (this._timers[name])
			{
				 clearInterval(this._timers[name].timer);
				 delete this._timers[name];
			}
		};
		
		obj.listen = function(message)
		{
			 jyingo.listen(this, message);
		};
		if (!obj.get_position_element)
		obj.get_position_element = function() {
			return this.get_element();
		}		
		obj.message = function(message, data)
		{
			 jyingo.propagate_message(this, message, data);
		};

		obj.set_parent = function(parent)
		{
			this.move_to(parent);
		};
		
		obj.move_to = function(to)
		{
			
			if (this.get_parent())
				 this.get_parent()._children.splice(this.get_parent()._children.indexOf(this), 1);
			
			var el =  this.get_element();
			
			if (el.parentNode)
			{
				el.parentNode.removeChild(el);
			}				
			
			if (!to)
			 return;
			
			this._parent = to.get_instance();
			this.get_parent()._children.push(this);
				
			to.get_element().appendChild(el);

			if (this._ordered)
			 this.order_changed();
		};
		
		obj.order_changed = function()
		{
			

			var el = this._element;			
			
			if (!el.parentNode)
			 return;
			 
			 
			var parent = el.parentNode;			

			el.parentNode.removeChild(el);
			
			var bros = this.get_parent().get_children_ordered(this.get_class());
			var ins = false;
			for (var i = 0; i < bros.length; i++)
			{
				
				var obj = bros[i];
				
				if (obj.get_instance() == this.get_instance()) continue;
				

		  	if (obj.order_function && obj.order_function(this))
				{
					
					
					parent.insertBefore(el, obj.get_element());
					ins = true;
					break;
				}
				
			}
			
			if (!ins)
			 parent.appendChild(el);
			
		};
		
		if (!obj.show)
		obj.show = function()
		{
			$(this._element).css({display:''});
		}
		
		if (!obj.hide)
		obj.hide = function()
		{
			$(this._element).css({display:'none'});
		}		
		
		obj.get_mouse_position = function()
		{
			
			return jyingo.get_mouse_coords(this.get_element());
			
		};
		
		obj.load = function(cls, name, params, ordered, visible, disabled)
		{
			
			var _data = params;
			var _name = name;
			var _ordered = ordered ? ordered : false;
			
			
			
			if (params == undefined && typeof name !== 'string')
			{
			 	 _data = name;
				 _name = null; 
			}
			
			var obj = jyingo.load(cls, _name, null, this.get_instance(), _data, visible, disabled);
			
			
			if (!obj.get_element().parentNode)
			{
			 obj._ordered = _ordered;
			 this._addChild(obj.get_element());
			 
			 if (_ordered)
			  obj.order_changed();
			 
			}
			return obj;	
		};
		
		obj.get_name = function() {
			return this._name;
		};
		
		obj._clear = function() {
			
			if (this.clear != undefined)
			 this.clear();
			else
			 this._element.innerHTML = '';
			
		};
		
		obj.get = function(name)
		{
			 
			for (var i = 0; i < this._children.length; i++)
			 if (this._children[i].get_name() == name)
			  return this._children[i];
				
			
			for (var i = 0; i < this._children.length; i++)
		  {
			  var obj = (this._children[i].get(name));
			  if (obj) return obj;
			}
			
			return null;
		};
		
		obj.catch_inside = function()
		{
			
			
			arguments[0] = this.get_instance()+'_'+arguments[0];
			
			var id = arguments[0];
			var name = arguments[1];
			
			this._catch_events.push (new Array('inside', name, id));
			
			jyingo.array_call(jyingo.delegate(jyingo, jyingo.catch_inside), arguments);
			
		},

		obj.catch_outside = function()
		{
			
			arguments[0] = this.get_instance()+'_'+arguments[0];
			
			var id = arguments[0];
			var name = arguments[1];
			
			this._catch_events.push (new Array('outside', name, id));
			jyingo.array_call(jyingo.delegate(jyingo, jyingo.catch_outside), arguments);
			
		},
		
		obj._addChild = function(obj_add) {
			
	
			
			if (this.addChild)
			{
				this.addChild(obj_add);
			} else {
				this.get_element().appendChild(obj_add);
			}
			
		};
		
		obj.get_children = function(type, recursive)
		{
			
			return jyingo.get_by_parent(this, type, recursive);
			
		};
		
		obj.get_children_ordered = function(type)
		{
			
			return jyingo.get_by_parent_ordered(this, type);
			
		};
		
		obj._get_element = function(id)
		{
			 
			 if (this.get_element_id)
			  return this.get_element_id(id);
			 
			 
			 return null;
			 
		};
		
		obj._continue_function_load_wait = function(key, time)
		{
			
			var _t = { c : function() {
				this.p.call(function(){}, '_continue_function_load', this.k);
			 }, p : this, k : key };
			
			setTimeout(jyingo.delegate(_t, _t.c), time);
			
		};
		
		obj._continue_function_load = function(key)
		{
			 this.call(function(){}, '_continue_function_load', key);
			 
		};
		
		obj._insertBefore  = function(obj, before)
		{
			
			var el = before.get_element ? before.get_element() : before;
			
			if (before.get_element != undefined && before._superelement != undefined)
			 el = before._superelement; 
			
			if (this.insertBefore)
			{
				this.insertBefore(obj, before);
			} else if (before == 0)
			{
					
					if (this.get_element().childNodes.length == 0)
					 this._addChild(obj, before);
					else
					 this.get_element().insertBefore(obj, this.get_element().childNodes[0]);
					
			}	else {
				
				this.get_element().insertBefore(obj, el);
				
			}
					
			
		};

		obj.get_instance = function() {
		 	return this._instance;
		};
		
		obj.disabled = function() {
			
			 var current = this;
			 while (current)
			 {
			 	  
			 	  if (current._disabled)
			 	   return true;
			 	  
			 	  current = current.get_parent();
			  	
			 }
			  
			 
			 return false;
			 
			 
		};
		
		obj._called = function()
		{
			if (typeof this.called !== 'undefined')
			 this.called();
		};
		
		obj._set_disabled = function(disabled) {
			
			if (this._eat_disable)
			 this._eat_disable++;
			
			this._disabled = disabled;

			if (disabled)
			{
					this._element.disabled = true;
					this.$.addClass('disabled');
			} else {
					
					this._element.disabled = false;
					$(this._element).removeAttr('disabled');
					this.$.removeClass('disabled');
			}
			
			if (this.set_disabled)
			 this.set_disabled(disabled);
			else
			{
							
			}
			
		};

    obj.call = function(delegate, service)
    {

		  if (this.disabled() == true)
				 return;
				 
	 		var args = jyingo.arguments_to_array(arguments);
			var params = args.slice(2);   
			
			jyingo.call(this, (delegate ? this.create_delegate(delegate) : null), service, params);	
    	
    };
		
		obj.event = function(event, disable)
		{
			
			if (this._allow_postback == false)
			 return;
			 
			
			if (disable == undefined)
			 disable = true;
			
			if (disable == true)
			{	
				if (this.disabled() == true)
				 return;
				
				this._set_disabled(true);
		    this._eat_disable = 1;
		  }
		  
			jyingo.event(this, event);
		};
		
		obj.remove = function()
		{
			 this._dispose(true);
		};
		obj.get_window = function()
		{
			 var _obj = this;
			 while (_obj)
			 {
			 	
			 	 if (__windows[_obj.get_instance()])
			 	  return __windows[_obj.get_instance()];
			 	 
			 	 _obj = _obj.get_parent();
			 }
			 return null;
		};		
		obj.unlisten = function(message)
		{
			jyingo.unlisten(this, message);
		};

	  obj._dispose = function(do_parent, dont_remove)
	  {
	  	 
	  	 if (dont_remove == undefined) dont_remove = false;
	  	 if (this._disposed == true)
	  	  return;
	  	 
	  	 this._disposed = true;
	  	 
	  	 if (do_parent == undefined)
	  	  do_parent = true;
	  	
	  	 jyingo.unlisten(this);
	  	 
	  	 jyingo.unset_name(this.get_name());
	  	 
	  	 
	  	 for (var name in this._timers)
	  	 {
	  	   this.clearTimer(name);	
	  	 }
	  	 
	  	 for (var i = 0; i < this._delegates.length; i++)
	  	 {
	  	  
	  	   var el = this._delegates[i];
	  	   if (el[2] != null)
	  	    $(el[2]).unbind(el[0], el[1]);
	  	   else
	  	   	this.$.unbind(el[0], el[1]);
	  	 	
	  	 }
	  	 
	  	 
	     
	  	 for (var i = 0; i < this._catch_events.length; i++)
	  	 {
	  	   var arr = this._catch_events[i];
	  	   if (arr[0] == 'inside')
	  	   {
	  	     jyingo.catch_inside(arr[2], arr[1]);	
	  	   } else if (arr[0] == 'outside') {
	  	   	 jyingo.catch_outside(arr[2], arr[1]);	
	  	   }
	  	 }
	  	 
	  	 if (dont_remove == false && this.get_element() && this.get_element().parentNode)
	  	 {
	  	   	this.get_element().parentNode.removeChild(this.get_element());
	  	 }
	  	 
	  	 jyingo.unset(this.get_instance());
	  	 
	  	 
	  	 if (this.dispose)
	  	  this.dispose();
	  	 
	  	 this.set_parent(null);
	  	 if (do_parent == true)
	  	  jyingo.dispose_by_parent(this, true);
			 
			 this._instance = null;
	  };
		
		
		obj._stylize = function(s)
		{
			
		  for (var v in s)
		  {
		  	

		  	if (this.set_style)
		  	 this.set_style(v, s[v]);
		  	else
		  	 this.$.css(v, s[v]);
		  	
		  }
			 
		};
		
		obj._htmlize = function (s)
		{
			
		  for (var v in s)
		  {			
			 
			 if (this.set_html)
			  this.set_html(v, s[v]);
			 else
			 {
			 	 
			 	 if (v == 'class')
			 	   this.get_element().className = s[v];
			 	 

			 }
			 
			}
			
		},

    obj._changes = function(p)
    {
    	for (var k in p)
    	 this._change(k, p[k]);
    	
    	if (this.changes)
    	 this.changes(p);
    	else if (this.change)
    	{
	     	for (var k in p)
	     	{
	    	 this.change(k, p[k]);   			
    	  }
    	}	
    	 
    	 	 
    }
    
    obj._set_visible = function(v, s)
    {
    	
    	if (this.set_visible && s == undefined)
    	 this.set_visible(v);
    	else
    	{
    		
    		this._visible = v;
    		
    		
    		if (v)
    		 this.get_element().style.display='';
    		else
    		 this.get_element().style.display='none';
    		
    		
    	}
    	
    	/*
    	var children = this.get_children();
    	for (var id in children)
    	 children[id]._set_visible(v);
    	*/
    };
    

    obj._change = function(k, v) { 
    	
    	if (k == 'visible')
    	{
    		
    		this._set_visible(v);
    		
    	}
    	
    	if (k == 'classname')
    	{
    		
    		this.get_element().className = v;
    		
    	}
    	
    	if (k == 'disabled')
    	{ 
    		
    		this._set_disabled(v ? true : false);
    		
    	}
    	
    };
   
    
		if (!obj.get_element)
		obj.get_element = function()
		{
			return this._element;
		};
		
		if (!obj.get_position_element)
		obj.get_position_element = function() {
			return this.get_element();
		}
		
		obj.create_delegate = function(what)
		{
			return jyingo.delegate(this, what);
		};
		
		obj.delegate = function(evm, what, obj)
	  {
	  	
	  	var _object = null;
	  	if (obj != undefined)
	  	{
	  		 
	  		 if (typeof obj == 'string' || obj instanceof String)
          _object = this.get(obj).get_element();
         else if (obj.get_element)
         	_object = obj.get_element();
         else 
         	_object = obj;
	  		 
	  	}
	  	
	  	var $target = (obj == undefined ? this.$ : $(_object));
	  		
	  	if (evm == 'click' && ('ontouchstart' in document.documentElement) && $target.prop("tagName") != 'INPUT' && $target.prop("tagName") != 'SELECT')
	  	{ 
	  		var self = this;
	  		setTimeout(function() {
	  			
		  		var useTouchStart = ($target.outerWidth() < 80 || $target.outerHeight() < 80) && $target.outerWidth() > 0 && $target.outerHeight() > 0;
		  		 
		  		
	  	    var delegate = self.create_delegate(what);
	  	    self._delegates.push(new Array(useTouchStart ? 'touchstart' : 'click', delegate, _object));
		  		$target.bind(useTouchStart ? 'touchstart' : 'click', delegate);
		  	
		  		if (useTouchStart)
	        $target.click(function (event) {
				      		 
				      		 event.stopPropagation();
				      		 jyingo.cancel_event(event);
				      		  
				      	});
			  }, 0);
			  
			  return;		
	  		
	  	}
	  	
	  	var delegate = this.create_delegate(what);
	  	

	  	
	  	this._delegates.push(new Array(evm, delegate, _object));
	  	
	  	if (obj == undefined)
	  	 this.$.bind(evm, delegate);
	  	else
	  	 $(_object).bind(evm, delegate);
	  };
		
	  obj.t3d = new jyingo.css3animator(obj);
		if (object_tree[instance] == undefined)
		 object_tree[instance] = [];
		
		object_tree[instance].push(obj);
		__j_objects[instance] = (obj);

    if (document.getElementById(instance))
    {
		 obj._element = document.getElementById(instance);
		 obj.$ = $(obj._element);
		} else if (obj._element == undefined && obj.create_element)
		{
		 obj._element = obj.create_element();
		 obj.$ = $(obj._element);				
		 obj._element.id = instance;
		 obj._element.name = instance;
		} else if (obj._element == undefined) {
		 obj._element = document.createElement('div');
		 obj.$ = $(obj._element);						
		 obj.id = instance;	
		}
		
		if (obj.get_parent())
			 obj.get_parent()._children.push(obj);
		else if (obj._parent)
		{ 
			 if (!_parent_resolve_table[obj._parent])
			  _parent_resolve_table[obj._parent] = [];
			  
			 _parent_resolve_table[obj._parent].push(obj);
		}
		
    if (_parent_resolve_table[obj._instance])
    {
    	  for (var i = 0; i < _parent_resolve_table[obj._instance].length; i++)
    	     obj._children.push(_parent_resolve_table[obj._instance][i]);
    	  
    	  delete _parent_resolve_table[obj._instance];
    }
    
		var p = data == undefined ? {} : data;
		obj._eat_disable = 0;
		
		if (obj._element)
		 obj._element.jyingoObject = obj;
		obj.initialize(p);
		obj._element.jyingoObject = obj;
		
		if (p['visible'] === false)
		 obj.hide();
		 
		
		if (visible == false)
		 obj._set_visible(false);
		 
		if (disabled == true)
		 obj._set_disabled(true);		
		
		return obj;
		
	},
	
	d : function(d)
	{
		
		var f = d['p'];
		
		switch (d['do'])
		{
		  
		  case 'func':
		  
		   var obj = f['o'] ? window[f['o']] : window;
		   obj[f['f']].apply(obj, f['p']);
		  
		  break;
		  
		  case 'call':
		   
		   
		   
		   if (f['owner'] != null && (__j_objects[f['owner']] == undefined && f['owner'] != 'jyingo'))
		    return;
		   		   
		   
		   var x = f['x'].split('.');
		   var context = null;
		   var func = Function("return "+f['x'])();
		   if (f['x'].indexOf('.') != -1)
		   {
		     var p = x.slice(0, x.length-1);
		     context = Function("return "+p.join('.'))();
		     func.apply(context, f['p']);
		   } else {
		   	
		     if (f['p'].length == 0)
		      func();
		     else if (f['p'].length == 1)
		      func(f['p'][0]);
		     
		   }
		   
		   
		   
		   
		   
		   
		  break;
		  
		  case 'load_r':

		   this.load_r(f, __debugging);
		  break;
		  
		  case 'load':
		   this.load(eval(f['class']), f['name'], f['instance'], f['parent'], f['data'], f['visible'], f['disabled']);
		  break;
		  
		  case 'loadwnd':
			  this.loadwnd(f['name'], f['instance'], f['parent'], f['transition'], f['type']);
		  break;
			  
		  
		  case 'remove_by_parent':
		  
		  
		  if (__j_objects[f['parent']]  != undefined)
		   this.dispose_by_parent( __j_objects[f['parent']] );
		  break;
		  
		  case 'remove':
		   

		   
		   var el = __j_objects[f['what']];
		   
		   
		   if (__windows[f['what']])
		    __windows[f['what']]._dispose(true);
		   else if (el) el._dispose(true)
		   else if (document.getElementById(f['what'])) {
		   	 
		   	 if (document.getElementById(f['what']) && document.getElementById(f['what']).parentNode)
		   	   document.getElementById(f['what']).parentNode.removeChild(document.getElementById(f['what']));
		   	
		   }
		   
		  break;
		  
		  case 'redirect':
		   if (f['delay'] != 0)
		   {
		     
		     var p = {};
		     p.location = f['location'];
		     p.redirect = function() { location.href = this.location; }
		     
		     setTimeout(this.delegate(p, p.redirect), f['delay']);
		     
		     	
		   } else {
		     
		     location.href = f['location'];
		     	
		   }
		   
		   
		  break;
		  
		  case 'reload':
		   
		   if (f['delay'] != 0)
		   {
		    
		    	setTimeout(this.delegate(location, location.reload), f['delay']);
		   	
		   } else {
		    
		    location.reload();
		    	
		   }
		   
		  break;
		  
		  
		}
	}
 };
})();



jyingo.extend('css3animator', function(self, _element) {
	
	
	var _x = 0, _y = 0, _z = 0,
	    _rx = 0, _ry = 0, _rz = 0,
	    _sx = 1, _sy = 1, _sz = 1;
	 
	var _opacity = 1;
	var _eat_event = false;
	
	var _grad_type = 'deg';
  var _queue = [];
  var _running = false;
 
	
	function ob()
	{
		return navigator.appVersion.indexOf("MSIE 9") != -1;
	}
	
	function $el()
	{
		return (_element.get_element ? _element.$ : $(_element));
	}
	
	
	
	function get_transform_string()
	{
		 
		 var transforms = [];
		 
		 if (_x != 0 || _y != 0 || _z != 0)
		  transforms.push('translate3d('+_x.toFixed(2)+'px, '+_y.toFixed(2)+'px, '+_z.toFixed(2)+'px)');
		  
	   if (_sx != 1 || _sy != 1 || _sz != 1)
		  transforms.push('scale3d('+_sx.toFixed(2)+', '+_sy.toFixed(2)+', '+_sz.toFixed(2)+')');
		  
	   if (_rx != 0 || _ry != 0 || _rz != 0)
	   {
	   	
	   	if (_rx != 0)
	   	 transforms.push('rotate3d(1,0,0,'+_rx.toFixed(2)+_grad_type+')');
	   	if (_ry != 0)
	   	 transforms.push('rotate3d(0,1,0,'+_ry.toFixed(2)+_grad_type+')');
	   	if (_rz != 0)
	   	 transforms.push('rotate3d(0,0,1,'+_rz.toFixed(2)+_grad_type+')');
	   	
		 }
		 
		 if (!transforms.length)
		  return 'translate3d(0,0,0)';
		 		 
		 return transforms.join(' ');
		 
	}
	
	
	
  function transition_end(evt)
	{				 
		 var o = { f : function() {if (this.c != null) this.c(this.t); },
		    	      t : _element,
		    	    c : null };
		   
		  if (evt.data.cb != null && _eat_event == false)
		    	      o.c =  jyingo.delegate($el().get(0), evt.data.cb);
		    	        
		 $el().unbind('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd');   	    
		 $el().css({ '-webkit-transition' : '', 'transition' : '' });    
	   jyingo.delegate(o, o.f)();
	   
	   
	   if (_queue.length)
	    jyingo.delegate(this, run)();
	   else
	   	_running = false;
	} 
	
	function run()
	{
   	 
   	 var step = _queue.shift();
   	 if (!step) return;
   	 
   	 var params = step[0];
   	 var duration = step[1];
   	 var ease = step[2];
   	 var cb = step[3];
   	 
   	 if (ease == 'swing')
   	  ease = 'ease-in';
   	 
   	   
		   $el().css({ '-webkit-transition' : '-webkit-transform '+(duration/1000).toFixed(2)+'s '+ease+', opacity '+(duration/1000).toFixed(2)+'s '+ease, 
		   	   	 	     'transition': 'transform '+(duration/1000).toFixed(2)+'s '+ease+', opacity '+(duration/1000).toFixed(2)+'s '+ease });
        
      var delegate = jyingo.delegate(this, transition_end);
      var callback = { cb : cb, o : this };
     
	   $el().bind('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', callback, delegate);    	
	  	      	 
	   var o = { params : params,
	   	   	 	   c : this,
	   	   	 	   r : function() {  this.c.set(this.params) }};
	   	   	 
	    //if (!(typeof setImmediate == 'undefined'))
      //   setImmediate(jyingo.delegate(o, o.r)); 
	   	//else
	   	   setTimeout(jyingo.delegate(o,o.r), 1000/60);		
	}
	    	   	
	self.proto({
		
		  
		
	   	set : function(params)
	   	{ 
	   		
	   		 if (params === 'identity')
	   		 {
	   		 	 _opacity = 1;
	   		 	 _x = _y = _z = 0;
	   		 	 _sx = _sy = _sz = 1;
	   		 	 _rx = _ry = _rz = 0;
	   		 } else {
		   		 
		   		 if (params.opacity != undefined)
		   		  _opacity = params.opacity;
		   		 
		   		 if (params.x != undefined)
		   		  _x = params.x;
		   		 
		   		 if (params.scale != undefined)
		   		   _sx = _sy = params.scale;	
		   		 
		   		 
		   		 if (params.y != undefined)
		   		  _y = params.y;
		   		  
		   		 if (params.z != undefined)
		   		  _z = params.z;
	
		   		 if (params.scaleX != undefined)
		   		  _sx = params.scaleX;
		   		  
		   		 if (params.scaleY != undefined)
		   		  _sy = params.scaleY;
	
		   		 if (params.scaleZ != undefined)
		   		  _sz = params.scaleZ;	   		    
	
		   		 if (params.rotateX != undefined)
		   		  _rx = params.rotateX;
		   		  
		   		 if (params.rotateY != undefined)
		   		  _ry = params.rotateY;
	
		   		 if (params.rotateZ != undefined)
		   		  _rz = params.rotateZ;	   		
		   		  
	   		 }
	   		 
	   		 if (ob())
	   		 {
            
            $el().css( params );
            
	   		 } else {
	   	        $el().css({ '-webkit-transform' : get_transform_string(),
	   	        	      'transform'  :  	get_transform_string(),
	   	        	      'opacity' : _opacity});
	   	  
	   		 	    $el().addClass('trick3dbf');
	   		 }
	   	
	   	},
	   	
	   	finish : function(params, end)
	   	{
	   		
	   		 if (end == undefined) end = true;
	   		 if (_running && !_eat_event) _eat_event = !end;
	   		
	   		 _running = false;
	   		 _queue = [];
	   		 
	   		 if (params === 'current')
	   		 {
	   		 
	   		    $el().css({ '-webkit-transform' : get_transform_string(),
	   	        	      'transform'  :  	get_transform_string(),
	   	        	      'opacity' : _opacity});	
	   		 	
	   		 } else if (params != undefined)
	   		  this.set(params);
	   		 
	   	},
	   	
	    use_radians : function()
	    {
	    	_grad_type = 'rad';
	    },
	    
	    use_degrees : function()
	    {
	    	_grad_type = 'deg';
	    },
	    
	    
	    
	   	animate : function(params, duration, easeOrCallback, callback)
	   	{
	   	  
	   	   var ease  = 'ease-in';
	   	   var cb = null;
	   	   
	   	   if (easeOrCallback != undefined)
	   	   {
	   	     if (easeOrCallback instanceof String || typeof easeOrCallback == 'string')
	   	       ease = easeOrCallback;
	   	     else
	   	     	 callback = easeOrCallback;
	   	   }
	   	   
	   	   if (callback != undefined)
	   	    cb = callback;
	   	   
	   	   
	   	   if (ob())
	   	   {
	   	   	
	   	   	
	   	   	  if (easeOrCallback == 'ease-out')
	   	   	   easeOrCallback = 'easeOutQuad';
	   	   	   
	   	   	  if (easeOrCallback == 'ease-in')
	   	   	   easeOrCallback = 'easeInQuad';
	   	   	  
					  var o = { f : function() {if (this.c != null) this.c(this.t); },
					    	      t : _element,
					    	      c : null };
					   
					  if (cb != null)
					    	      o.c =  jyingo.delegate($el().get(0), cb);
	   	   	
            $el().animate( params, duration, easeOrCallback, jyingo.delegate(o, o.f));
	   	      return $el();
	   	      
	   	   	
	   	   } else {
	   	   	 
	   	   	 _queue.push([params, duration, ease, cb]);
	   	   	 if (!_running)
	   	   	 {
	   	   	 	_running = true;
	   	   	 	
						if (typeof setImmediate == 'undefined')
						   setTimeout(jyingo.delegate(this, run),0);
						else
						   setImmediate(jyingo.delegate(this, run));

	   	   	 }
	   	   }
	   	   
	   	   return this;
	   	}
	   	
	});
	
	return new self();
	
});

jyingo.frame = function() {
	
	this._superelement = null;
	
	
}

jyingo.frame.prototype = {
	
	initialize : function(params)
	{
		
		if (params.class)
		 this.$.addClass(params.class);
		
		if (params.class2n)
		{
			this._superelement = this._element;
			this._element = this.$.find('div').get(0);
			this.$ = $(this._element);
		} 
		
		this.clickable = params.clickable ? params.clickable : false;
		this.aligned = params.aligned ? params.aligned : false;
		
		if (this.aligned)
		{
			this.delegate('resize', this.create_delegate(this.resized), window);
			setTimeout(this.create_delegate(this.resized), 0);
		}
		
		if (this.clickable)
 	   this.delegate('click', this.click);
	},
	
	resized : function()
	{
		 if (this.$.children().size() == 0)
		  return;
		  
		 
		  var first = this.$.children().first();
		  var width = $(first).outerWidth()+parseInt($(first).css('marginLeft')) + parseInt($(first).css('marginRight'));
		  if (width == 0) return;
		  
		  this.$.removeAttr('style');
		  var maxWidth = this.$.width();
		  
		  var elements = Math.max(1, Math.floor(maxWidth/width));
		  var newMaxWidth = elements * width;
		  this.$.css({ 'max-width' : newMaxWidth+'px'});
		 
	},
	
	
	
	click : function()
	{
		this.event('click');
	},
	
	 
	dispose : function()
	{
		if (this._superelement && this._superelement.parentNode)
		 this._superelement.parentNode.removeChild(this._superelement);
	},
	
	collapse : function()
	{
		this.$.slideUp('fast');
	},
	
	expand : function()
	{
	  this.$.slideDown('fast');
	}

	
};



jyingo.formfield = function()
{
	
};

jyingo.formfield.prototype = {
	
	initialize : function(params)
	{
		
	},
	
	dispose : function()
	{
	}
	
};

jyingo.textbox = function() {
	
	this.placeholder = false;
	this.placeholder_color = null;
  this.valid_chars = 0;
  this.validate_mode = 0;
  this.filter_interval = 250;
  this.custom_chars = "";
  this.lowercase_letters = "abcdefghijklmnopqrstuvwxyz";
  this.uppercase_letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  this.numbers = "0123456789";
  this.validchar_string = "";
  this.interval_id = null;
  this.interval_handler = null;
  this.filtered = false;
  this.press_handler = [];
  this.locked = false;
}


jyingo.textbox.prototype = {

	get_text:function () {
		return this._element.value;
	},
	
	set_text:function (val) {
		this._element.value = val;
	},
	
	initialize : function(data)
	{
     
    
    this.locked = data['locked'] ? data['locked'] : false;
	  this.placeholder = data['placeholder'];
		this.placeholder_color = data['placeholder_color'] ? data['placeholder_color'] : '#999';
		
		this.datepicker = data['dp'] ? data['dp'] : false;
		this.active_class = data['active'] ?  data['active'] : 'active';
		
		this.onkeypress = (data['onkeypress'] ? data['onkeypress'] : null);
		this.onkeyup = (data['onkeyup'] ? data['onkeyup'] : null);
		this.onkeydown = (data['onkeydown'] ? data['onkeydown'] : null);

    this.auto_width = (data.autowidth && data.autowidth != undefined);
	  this.prefix = data['pr'] ? data['pr'] : null;
	  
    this.elastic = (data.elastic && data.elastic != undefined);
    this.wyg = data.wyg ? data.wyg : false;
       
    if (this.wyg)
    {
    	 var h = this.$.height();
			 CKEDITOR.replace(this.get_element().id, { height : h +'px' });	
			 this.$.addClass('no-display');
    }
    
    this._is_active = false;
    this._tags = data.tags;
    
    if (this._tags)
    {
    	this.$.tagsinput({
	/*	  confirmKeys: [44],*/
		  trimValue: true
		});
    }
    
    if (this.elastic)
    {
    
     
     var mw = this.$.css('min-height');
     this.$.css({'min-height' : 'auto'});
     this.elastic_min_height = mw;
		 this.min_height = this.$.height();
		 
		 
		}
		
		this.delegate('change', this.text_change, this.get_input_element());
		this.delegate('keypress', this.keypress, this.get_input_element());
		this.delegate('keydown', this.keydown, this.get_input_element());
		this.delegate('keyup', this.keyup, this.get_input_element());

		this.delegate('selectstart', this.selectstart);
		
		this.delegate('mousedown', this.mousedown);
		this.delegate('focus', this.focus);
		this.delegate('blur', this.blur);		
		this._eat_blur = false;
		
		if (data['filterdata'] != undefined)
		 this.setup_filter_data(data['filterdata']);
		
		
		this.filtered = data['filtered'] ? data['filtered'] : false;
		this.setup_filter();
		
		
		
		this._displaying_ph = false;
		
		this.delegate('resize', this.autoresize, window);
		
		this.autoresize();
		this.initial_autoresize = setInterval(this.create_delegate(this.autoresize), 200);
		this.set_placeholder();
		
		if ( this.elastic )
		{
		 this.$.autogrow();

		 
		}
		
		
		this.$.attr('autocomplete', 'off'); 
		this.$.css({outline : 'none'});
		
		if (this.locked)
		{
		 this.$.addClass('locked');
		 this.$.attr('readonly','readonly');
		}
		
		if (this.datepicker)
		{
			this.$.datepicker({format:'dd/mm/yyyy'}); 
			
		}
	},
	
	selectstart : function(event)
	{
		
		var ev = jyingo.eventize(event);
  	if (this._displaying_ph)
    {
    	   jyingo.cancel_event(ev);
  	  	 this.caret(0);
  	  	 return false;
  	  	 
  	}
  	return true;
	}, 
	
	destroy_tags : function()
	{
		if (!this._tags)
		 return;
		 
		this._tags = false;
		this.$.tagsinput('destroy');
		if (this.rehandle)
		 this.rehandle();
	},
	
	get_input_element : function()
	{
		if (this._tags) return this.$.tagsinput('input');
		return this.get_element();
	},
	
	mousedown : function(event)
	{
		var ev = jyingo.eventize(event);
		
		
  	if (this._displaying_ph)
    {
    	   jyingo.cancel_event(ev);
  	  	 this.caret(0);
  	  	 this._element.focus();
  	  	 return false;
  	  	 
  	}
  	
  	return true;		
	},
	
	autoresize : function()
	{
		
		if (this.auto_width == true)
		{
			var space = this.$.outerWidth() - this.$.width();
			
			
			
			if (this._element.parentNode)
			{
			
				
			 var _width = $(this._element.parentNode).width() - space - ( this.$.offset().left - $(this._element.parentNode).offset().left  ) - 1;
			 if (_width <= 0) return;
			 
			 if (this.initial_autoresize)
			 {
			  clearInterval(this.initial_autoresize);
			  this.initial_autoresize = null;	
			 }
			 
			 if (this._oldwidth != _width)
			 {
			 	
			   this._element.style.width = '10px';
			 
			   this._oldwidth = _width;
			   this.$.css({ width : _width });
		   }
		  }
		}
		
	},
	
	keyup : function(ev)
	{
		 

		  var ev = jyingo.eventize(ev);
		 if (this.onkeyup) this.onkeyup(ev);
		 
		 return true;
	},

	keydown : function(ev)
	{
		
		 var ev = jyingo.eventize(ev);
		 this.unset_placeholder();	
		 
		 
		 if (ev.keyCode == 13 || ev.which == 13)
		 {
		  
		   for (var i = 0; i < this.press_handler.length; i++)
		    this.press_handler[i]();
		    
		    
		   jyingo.cancel_event(ev);
		   return false;
		   	
		 }
		 
		 if (this.onkeydown) this.onkeydown(event);
	},
	
	keypress : function(event)
	{
		 if (this.onkeypress) this.onkeypress(event);
	},

	active : function()
	{
		
		if (this.active_class != null)
		 this.$.addClass(this.active_class);		
		 
		if (this.elastic)
		{
		//	this._element.changeMinHeight(this.elastic_min_height);
		}
		
	},
	
	set_press_handler : function(handler)
	{
		
		this.press_handler.push(handler);
		
	},
	
	inactive : function()
	{
		if (this.active_class != null)
		{
		 this.$.removeClass(this.active_class);
		}
		if (this.elastic && this.get_data().length == 0)
		{
		//	this._element.changeMinHeight(this.min_height);
		}
	},
	
	create_element : function()
	{
		var el = document.createElement('input');
		el.type = 'text';
		return el;
	},
	
	setup_filter : function()
	{
		if (this.filtered == false)
		{
		 if (this.interval_handler != null)
		 {
		 	 clearInterval(this.interval_handler);
		   this.interval_handler = null;	
		 }	
		} else {
			this.interval_handler = setInterval(this.create_delegate(this.check_filter_timed, 250));
		}
	},
	
	check_filter_timed : function()
	{

	    var m = document.createElement('span');
	    m.style.color = this.placeholder_color;
			
	  	if (this.get_element().style.color.toUpperCase() == m.style.color.toUpperCase() && 
	  	this.get_element().value == this.placeholder)
	  	 return;
	  	
	  	this.check_filter();		
		
	},
	
	setup_filter_data : function(d)
	{
		
		this.validate_mode = d['mode'];
		this.valid_chars = d['valid'];
		this.custom_chars = d['custom'];
  
  
		
		var v = "";
		
		if (this.valid_chars & 1)
		 v += this.numbers;

		if (this.valid_chars & 2)
		 v += this.lowercase_letters;		 
		 
		if (this.valid_chars & 4)
		 v += this.uppercase_letters;		 	
		 
		if (this.valid_chars & 8)
		 v += this.custom_chars;

    this.validchar_string = v;		
		
	},

	process_key : function(s)
	{
		
		var found = this.validchar_string.indexOf(s) != -1;
		
		if (this.validate_mode == 1)
		 found = !found;
		
		return found;
		
	},
	
	check_filter : function()
	{



		var txt = this.get_element().value;
		var result = "";
		
		for (var i = 0; i < txt.length; i++)
		{
		   if (this.process_key(txt.substr(i, 1)))
		    result += txt.substr(i,1);	
		}
		
		if (result != txt)
		 this.get_element().value = result;		
		
	},
	
	text_change : function(event) 
	{
	  this.unset_placeholder();	
		if (this.filtered == true)
		{

	    var m = document.createElement('span');
	    m.style.color = this.placeholder_color;
			
	  	if (this.get_element().style.color.toUpperCase() == m.style.color.toUpperCase() && 
	  	this.get_element().value == this.placeholder)
	  	 return;
			
		 this.check_filter();
	  }
	  
	  this.event('_change', false);
	  
	},
	
	keypress : function(event)
	{
		
		var ev = jyingo.eventize(event), scan;
	  

		if (this.filtered == true)
		{
		  
		  var code = (ev.charCode ? ev.charCode : ev.keyCode);

      if ((code == 33) ||
               (code == 34) ||
               (code == 38) ||
               (code == 40) ||
               (code == 37) ||
               (code == 39) ||
               (code == 36) ||
               (code == 35) ||
               (code == 8) ||
               (ev.ctrlKey)) {
            return;
      }
      
      if (ev.rawEvent && ev.rawEvent.keyIdentifier) {
       
       if (ev.rawEvent.ctrlKey || ev.rawEvent.altKey || ev.rawEvent.metaKey)
          return;

       if (ev.rawEvent.keyIdentifier.substring(0,2) != "U+")
          return;

            
       scan = ev.rawEvent.charCode; 
       
       if (scan == 63272)
           return;

      } else {
        scan = code;
      }       
		   
      if (scan && scan >= 0x20) {
        
        var c = String.fromCharCode(scan);

        
        if(!this.process_key(c))
          return jyingo.cancel_event(ev);
            
       }		  

		}
		return true;
		
		
	},
	
	dispose : function()
	{
		 if (this.interval_handler != null)
		 {
		 	 clearInterval(this.interval_handler);
		   this.interval_handler = null;	
		 }
	   if (this.initial_autoresize)
		 {
			  clearInterval(this.initial_autoresize);
			  this.initial_autoresize = null;	
		 }
	},
	
	get_view_object : function()
	{
		if (this._tags) return this.$.tagsinput('input').get(0).parentNode;
		return this.get_element();
	},
	
	add_tag : function(tag)
	{
		if (this._tags == false)
		{
			this.get_element().value = tag;
			return;
		}
		
		this.$.tagsinput('add', tag);
		this.$.tagsinput('input').get(0).value = '';
	  this.$.tagsinput('input').get(0).placeholder  = '';
	},
	
	get_input_data : function()
	{  
		if (this._tags) return this.$.tagsinput('input').get(0).value;
		return this.get_data();
	},
	
	get_data : function()
	{
		if (this.wyg)
		{
				 return CKEDITOR.instances[this.get_element().id].getData();
		}
		
    var m = document.createElement('span');
    m.style.color = this.placeholder_color;
		
  	if (this.get_element().style.color.toUpperCase() == m.style.color.toUpperCase() && 
  	this.get_element().value == this.placeholder)
  	{		
		  return '';
		} else {
			
			if (this.filtered)
			 this.check_filter();
			
			return this.get_element().value;
		}
		
	},
	
  focus : function(event)
  {
  	  this._is_active = true;
  	
  	  if (this.prefix && this._element.value.length == 0)
  	  {
  	   this._element.value=this.prefix;
  	   this.caret(this.prefix.length);
  	  }
  	   
  	  this.active();
  	  this._element.focus();
  	  
  	 /* if (this._displaying_ph)
  	  {
  	  	 this.caret(0);
  	  }*/
  },
  
  caret : function (pos) {
  	
  	var elem = this._element;
  	
    if(elem.selectionStart)
       elem.setSelectionRange(pos, pos);
  
  },
    
  unset_placeholder : function()
  {
    
    if (!this._displaying_ph)
     return;
    
    this._displaying_ph = false;
    var m = document.createElement('span');
    m.style.color = this.placeholder_color;
    

  	if (this.get_element().style.color.toUpperCase() == m.style.color.toUpperCase() && 
  	this.get_element().value == this.placeholder)
  	{
  		 
  		 this.get_element().style.color = '';
  		 this.get_element().value = '';
  		 
  	}
  	  	
  },
  
  set_placeholder : function()
  {

    if (this._displaying_ph)
     return;
  	
  	if (this.get_element().value == '' && this.placeholder != undefined && this.placeholder)
  	{  	
  		
  	  this._displaying_ph = true;
  	  this.get_element().style.color = this.placeholder_color;
  	  this.get_element().value = this.placeholder;  	  
    }
    
    if (!this._eat_blur)
    this.caret(0);
    
    this._eat_blur = false;
  },
  
  is_active : function()
  {
  	return this._is_active;
  },
  
  blur : function(event)
  {
  	
  	this._is_active = false;
  	this._eat_blur = true;
  	
  	this.set_placeholder();
  	this.inactive();
  },
  
  select : function()
  {
  	this._element.focus();
  	
  	setTimeout(jyingo.delegate(this._element, this._element.select), 10);
  },
  
  change : function(k, v)
  {
  	 
  	 if (k == 'locked')
  	 {
  	 	 this.locked = v;
  	 	 if (v) { this.$.attr('readonly','readonly'); this.$.addClass('locked'); }
  	 	 else { this.$.removeAttr('readonly'); this.$.removeClass('locked'); }
  	 	 	
  	 }
  	 
  	 if (k == 'text')
  	 {
  
    	if (this.wyg)
	  	{
	  	 		CKEDITOR.instances[this.get_element().id].setData(v);
	  	}
	  	 	
  	  this._element.value = v;	
  	  if (v == '')
  	   this.set_placeholder();
  	  else
  	  {
  	   this._element.style.color = '';
  	  }
  	 }
  	 
  	 if (k == 'filterdata')
  	 {
  	  this.setup_filter_data(v);	
  	 }
  	 
  	 if (k == 'filtered')
  	 {
  	  this.filtered = v;
  	  this.setup_filter();	
  	 }
  	 
  	 if (k == 'placeholder')
  	 {
  	 	  this.unset_placeholder();
  	    this.placeholder = v;
  	    this.set_placeholder();	
  	 }

  	 if (k == 'placeholder_color')
  	 {
  	 	  this.unset_placeholder();
  	    this.placeholder_color = v;
  	    this.set_placeholder();	
  	 }
  	 
  	 if (k == 'password')
  	 {
  	   
  	   if (v)
  	    this.get_element().type='password';
  	   else
  	   	this.get_element().type='text';	
  	 	
  	 } 
  	
  }
	
	
};


jyingo.link = function() {

		
}

jyingo.link.prototype = {
	
	initialize : function(data)
	{
	  
	  this.delegate('click', this.click);
	  this.href = data.href;
	  this.delegate('ontouchstart' in document.documentElement ? 'touchstart' : 'mousedown', this.mousedown);
	  this.delegate('ontouchend' in document.documentElement ? 'touchend' : 'mouseup', this.mouseup);
	  this.delegate('mouseout', this.mouseup);

    this.loader = data.loader ? data.loader : false;
	  this.paging = data.paging ? data.paging : false;
	  this.touchstart = data.touchstart ? data.touchstart : false;
	  
	  var el = $(this.$.find('span')[0]);
	  
	  if (el.html() == null || el.html().length == 0)
	   $(this.$.find('span')[0]).css({ display : 'none' });
	  
	},
	
	
	called : function()
	{
		 if (this.loader)
		   jyingo.get_window_manager().remove_loader();
		  
	},
	
	mousedown : function(event)
	{
		this.$.addClass('active');
	//	if (this.touchstart)
//		 this._click(event);
		
	},
	
	mouseup : function()
	{		
		this.$.removeClass('active');
	},
	
	anchorize : function(smooth)
	{
		
		if (smooth == true)
		{
			
			$('html,body').animate({scrollTop:this.$.offset().top  }, 500, 'easeOutCirc', function() { jyingo._anchorizing = false; });
			jyingo._anchorizing = true;
			
			
		} else {
		 location.hash = this._element.name;
		}
	},
	 
	_click : function(event)
	{

		if (window['hideAllDropdowns']) window['hideAllDropdowns'](); 
		
	  if (this.paging == true)
		{
			
			
			
			
			if (jyingo.get_window_manager())
			 jyingo.get_window_manager().set_hash_position(this.href);

		}
		
		
		else if (this.href == '#'/* || this.paging == true*/)
		{
			if (this.loader) jyingo.get_window_manager().set_loader();
		  this.event('click');
		 
		  var ev = jyingo.eventize(event);
		  jyingo.cancel_event(ev);
		  return false;
	  }		
	},
	 
	click : function(event)
	{
		//if (!this.touchstart)
		 this._click(event);
	/*	else
		{
		  var ev = jyingo.eventize(event);
		  jyingo.cancel_event(ev);
		  return false;
		}*/
	},
	
	
	change : function(key, value)
	{

		if (key == 'text')
		{
			
			var element = this.$.find('span')[0];
			if (value.length == 0) $(element).css({ display : 'none'});
			else $(element).css({ display : ''});
							
		  $(element).html(value);
		}
	}
	
};

/* -- */
jyingo.radio = function() {

		
}

jyingo.radio.prototype = {
	
	initialize : function(data)
	{
	  
	  var textline = jyingo.get('_' + this.get_instance());
	  if (!textline)
	  {
	  	
	  	var textline = document.createElement('label');
	  	textline.htmlFor = this.get_instance();
	  }
	  
	  this.textline = textline;
	  
	  
	  if (data.class)
	   this.get_element().className = data.class;
 	
 	  if (data.text)
 	  {
 	  	 this.set_text(data.text);
 	  }
 	   	   
 	   this.delegate('click', this.click);
 	   
	},
	
	get_data : function()
	{
		return this.is_checked();
	},
	
	click : function()
	{
		this.event('click');
	},
	
	create_element : function()
	{
	  var el = document.createElement('input');
	  el.type = 'checkbox';
	  
	  return el;
	},
	
	is_checked : function()
	{
		return this._element.checked;
	},

	dispose : function()
	{
		if (this.textline.parentNode)
		 this.textline.parentNode.removeChild(this.textline);
	},
	
	set_text : function(text)
	{
		this.textline.innerHTML = text;
	},
	
	set_visible : function(v)
	{
		if (v)
		{
			this.textline.style.display = '';
			this._element.style.display = '';
		} else {
			this.textline.style.display = 'none';
			this._element.style.display = 'none';
		}
	},


	change : function(key, value)
	{

		if (key == 'text')
		 this.get_element().innerHTML = value;
		

	}
	
};

/* -- */

jyingo.checkbox = function() {

		
}

jyingo.checkbox.prototype = {
	
	initialize : function(data)
	{
	  
	  var textline = jyingo.get('_' + this.get_instance());
	  if (!textline)
	  {
	  	
	  	var textline = document.createElement('label');
	  	textline.htmlFor = this.get_instance();
	  }
	  
	  this.textline = textline;
	  
	  
	  if (data.class)
	   this.get_element().className = data.class;
 	
 	  if (data.text)
 	  {
 	  	 this.set_text(data.text);
 	  }
 	  
 	  if (data.checked)
 	   this.set_checked(data.checked);
 	   
 	   this.delegate('click', this.click);
 	   
	},
	
	get_data : function()
	{
		return this.is_checked();
	},
	
	click : function()
	{
		this.event('click');
	},
	
	create_element : function()
	{
	  var el = document.createElement('input');
	  el.type = 'checkbox';
	  
	  return el;
	},
	
	is_checked : function()
	{
		return this._element.checked;
	},

	dispose : function()
	{
		if (this.textline.parentNode)
		 this.textline.parentNode.removeChild(this.textline);
	},
	
	set_text : function(text)
	{
		this.textline.innerHTML = text;
	},
	
	set_visible : function(v)
	{
		if (v)
		{
			this.textline.style.display = '';
			this._element.style.display = '';
		} else {
			this.textline.style.display = 'none';
			this._element.style.display = 'none';
		}
	},
	
	set_checked : function(value)
	{
		 this._element.checked = value;
	},

	change : function(key, value)
	{

		if (key == 'text')
		 this.get_element().innerHTML = value;
		
		if (key == 'checked')
		{
		 this.set_checked(value);
	  }
	}
	
};


jyingo.extend('page_title', function(self, settings, shared) {
	
	self.proto({
		
		initialize : function(params)
		{
		
		},
		
		change : function(key, value)
		{
	
			if (key == 'title')
			 document.title = value;
		}			
		
	});
	
	return new self();
	
});

/* --- */

jyingo.label = function() {

		
}

jyingo.label.prototype = {
	
	initialize : function(data)
	{
	  
	  if (data.class)
	   this.get_element().className = data.class;
 	
 	  if (data.text)
 	  {
 	  	 this.set_text(data.text);
 	  }
 	   
	},
	
	create_element : function()
	{
	  return document.createElement('span');
	},
	
	set_icon : function(icon)
	{
		
		var icon = $(this.$.find('i'));
		if (icon.size())
		{
			icon.get(0).className = icon; 
		}
		
	},	
	
	set_text : function(text)
	{
		
		var icon = $(this.$.find('i'));
		if (icon.size())
		{
			
			this.get_element().innerHTML = icon[0].outerHTML+text;
		} else {
			
			this.get_element().innerHTML = text;
		}
		
	},

	change : function(key, value)
	{
		if (key == 'icon')
		 this.set_icon(value);

		if (key == 'text')
		 this.set_text(value);
	}
	
};

/* --- */

jyingo.image = function() {

		this.width = null;
		this.height = null;
		this.scale = 1;
	  this._interval = null;	
	  this._autoresize = false;
}

jyingo.image.prototype = {
	
	get_src :function() {
		return this._image.src;
	},
	
	set_src :function(val) {
		this.set_image_src(val);
	},
	
	initialize : function(data)
	{
	 
	  this._image = $(this.get_element()).find('img');
	  this.scale = data.scale ? data.scale : 1;
	  
	  this.width = data.width ? data.width : null;
	  this.height = data.height ? data.height : null;
	  
	  if (this.width == null && this.height == null)
	   this._autoresize = true;
	  
	  if (this.width != null)
	   this.get_element().style.width = this.width + 'px';

	  if (this.height != null)
	   this.get_element().style.height = this.height + 'px';	   
	  
	  if (this._image.length)
	  {
	  	this._image = this._image.get(0);
	  } else {
	  	this._image = document.createElement('img');
	  	this._element.appendChild(this._image);
	  }
	  
	  
	  if (data.class)
	   this.get_element().className = data.class;
 	
 	  if (data.src)
 	  {
 	  	 this._image.src = data.src;
 	  }
 	  
 	  var mask = $(this.get_element()).find('div.imgblackmask');
 	  if (mask.get(0))
 	  {
 	  	
 	  	mask.css({ width : this.$.width(),
 	  		       height : this.$.height()});
 	  	
 	  }

 	  this.set_image_src(this._image.src);

 	  this.delegate('click', this.click);
	},
	
	click : function()
	{
		this.event('click');
	},
	
	set_image_src : function(src)
	{

    if (src == undefined || !src || !src.length)
     return;
     
		this._image.src = src;
		
	  var img_src = document.createElement('img');
	  img_src.style.visibility = 'hidden';
	//  img_src.style.position = 'absolute';
	  img_src.style.top = '0px';
	//  img_src.style.zIndex = -1;
	  img_src.style.left = '0px';  
 	  this.external_img = img_src;
 	  
 	  document.body.appendChild(img_src); 
 	  img_src.onload = this.create_delegate(this._external_loadup);	
 	  img_src.src = this._image.src;	
	},
	
	
	_external_loadup : function()
	{
		
		if (!this.external_img)
		 return;
		 
		if (this._autoresize)
		 this.width = this.height = null;
		 
		this._width = parseInt(this.external_img.width);
		this._height = parseInt(this.external_img.height);
		
		
		if (this.scale && this.scale != 1)
		{
			 this._width *= this.scale;
			 this._height *= this.scale;
		}
		
		if (!this.width)
		{
		 this.width = this._width;
	   this.get_element().style.width = this.width + 'px';
		}
		
		if (!this.height)
		{
		 this.height = this._height;
	   this.get_element().style.height = this.height + 'px';	    
		}
		
		
		this._render();
		
		document.body.removeChild(this.external_img);
		delete this.external_img;
	},
	
  set_size : function(w, h)
  {
  	this._autoresize = false;
 	  this.width = w ? w : null;
	  this.height = h ? h : null;
	  
	  if (this.width != null)
	   this.get_element().style.width = this.width + 'px';

	  if (this.height != null)
	   this.get_element().style.height = this.height + 'px';	    	
  	
  	this._render();
  	
  },
	
	_render : function()
	{
		
		var w = this._width,
		    h = this._height;
		 
		 
		var ow = $(this.get_element()).innerWidth(),
		    oh = $(this.get_element()).innerHeight();
		
		if ( w/h * oh > ow )
		{
			/*
			this._image.width = w/h * oh;
			this._image.height = oh;
			*/
			var of = (w/h * oh - ow) / 2;
			
			$(this._image).css({ top: 0, left: -of, width: (w/h * oh)+ 'px', height : oh +'px' });
			
		} else {
			/*
			this._image.width = ow;
			this._image.height = h/w * ow;
			 */
			var of = (h/w * ow - oh) / 2;

			
			$(this._image).css({ top: -of, left: 0, width: ow+'px', height: (h/w * ow) + 'px'});
						
		} 

	},
	
	create_element : function()
	{
	  var el = document.createElement('a');
	  el.className = 'jyingo_image';
	  
	  return el;
	},


	change : function(key, value)
	{
		
		if (key == 'width')
		{
			 this._autoresize = false;
		 this.width = parseInt(value);
	   this.get_element().style.width = this.width + 'px';
	   this._render();
	   
		} 
		if (key == 'height')
		{
			 
			 this._autoresize = false;
		 this.height = parseInt(value);
	   this.get_element().style.height = this.height + 'px';
	   this._render();
	   
		} 
		if (key == 'src')
		{
			
			
     this.set_image_src(value);
		
		}
	}
	
};

/* --- */

jyingo.imagebutton = function() {

		
}

jyingo.imagebutton.prototype = {
	
	initialize : function(data)
	{
	  
	  if (data.class)
	   this.get_element().className = data.class;
 	
 	  if (data.src)
 	  {
 	  	 this.get_element().src = params.src;
 	  }
 	  
 	  this.delegate('click', this.click);
 	   
	},
	
	
	click : function()
	{
		this.event('click');
	},
	
	create_element : function()
	{
	  return document.createElement('img');
	},


	change : function(key, value)
	{

		if (key == 'src')
		 this.get_element().src = value;
	}
	
};

/* --- */

jyingo.imagelink = function() {

		
}

jyingo.imagelink.prototype = {
	
	initialize : function(data)
	{
	  
	  if (data.class)
	   this.get_element().className = data.class;
 	
 	  this._img = document.getElementById(this.get_instance()+'_img');
 	  if (!this._img)
 	  {
 	  	this._img = document.createElement('img');
 	  	this.get_element().appendChild(this._img);
 	  }
 	
 	  if (data.src)
 	  {
 	  	 this._img.src = params.src;
 	  }
 	  
 	  if (!this.get_element().href)
 	  this.get_element().href = data.href ? data.href : '#';
 	   
	},

	
	create_element : function()
	{
	  return document.createElement('a');
	},


	change : function(key, value)
	{

		if (key == 'src')
		 this._img.src = value;
		
		if (key == 'href')
		 this.get_element().href = value;
	}
	
};


/* --- */

jyingo.generic = function() {

		
}

jyingo.generic.prototype = {
	
	initialize : function(data)
	{
	  
 	
	}
	
};

/* --- */

jyingo.extend('windowmanager', function(self, settings, shared) {
	
	
	var _hash_position = '';
	var _loader = null;
	var _use_state = false;
  self.proto({
	
		
		initialize : function(data)
		{
		  
	   	jyingo.set_window_manager(this);
	    
	    if (history.pushState)
	    {
	     
	    	window.addEventListener('load', (this.create_delegate(function() { setTimeout(this.create_delegate(this.init_defered), 0)})));
	    	_use_state = true;
	    	_hash_position = this.get_hash_position(location.href);
	    } else {
	    	
	   		 $(window).bind('hashchange', jyingo.delegate(this, this._state_changed));
	   		 _hash_position = location.hash.replace('#','');
	    }
	    jyingo.bind('windowAppears', this.create_delegate(this.remove_loader));
	    
	    
		},
		
		init_defered : function()
		{
			
	    	window.onpopstate = jyingo.delegate(this, this._state_changed);
		},
		
		get_hash_position : function(_h)
		{
	    if (_h[0] != '!')
		  {
				 _h = location.href.split('/');
				 _h.splice(0, 3);
				 _h = '/'+_h.join('/');
			}
				  
			if (_h[0] == '!')
			_h = _h.substr(1);		
			
			return _h;	
		},
		
		remove_loader : function()
		{
			if (_loader) {
				
	    	$(_loader).css({ opacity : 0});
				setTimeout(function() {
				
					_loader.parentNode.removeChild(_loader);
					_loader = null;
				}, 250);
				
			}
		},
		
		set_url : function(newUrl)
		{ 
			if (_use_state)
			{
				history.pushState(null, null, newUrl);
				_hash_position = newUrl;
				
				
			} else {
				
				location.hash='#!'+newUrl;
				_hash_position='!'+newUrl;
			
			}
			
			this._state_changed();
		},
		
		back : function()
		{
			history.back();
			
			return false;
		},
	   
	  set_loader : function()
	  {
      
      if (_loader != null)
       return;
      
	    _loader = document.createElement('div');
	    _loader.className = 'window_loader';
	    _loader.innerHTML = '<div class="loader"></div>';
	    setTimeout(function() {
	    	$(_loader).css({ opacity : 1});
	    	$(_loader).addClass('rotated');
	    }, 0);

	    $(document.body).append(_loader);	  	
	  },
	  
	  set_hash_position : function(p)
	  {
	  	this.set_url(p);
	    
		},	
	   	
		_state_changed : function(event)
		{
			if (_use_state)
			{
				var ev = jyingo.eventize(event);
				jyingo.cancel_event(ev);
			}
			
			var _h = location.hash.replace('#','');
			if (_h.indexOf(':') != -1) return;
			
			if(_h != _hash_position)
			{
				
				  if (_h[0] != '!')
				  {
				  	_h = location.href.split('/');
				  	_h.splice(0, 3);
				  	_h = '/'+_h.join('/');
				  }
				  
				  if (_h[0] == '!')
				   _h = _h.substr(1);
				  
				  this.set_loader();				  
				  this.call(function(){}, '_request_page', _h);
				  _hash_position = _h;
				 
			}
			
			return false;
		}
		
	});
	
	return new self();
	
});

/* --- */

jyingo.upload = function() {


 this._send_after = false;
 this._uploading = false;
 this._percent = 0;
 this._bytes_sent = 0;
 this._bytes_total = 0;
 this._status  -1;
 this.file_types = [
   
    {description : "Tutti i file (*.*)",
    extension : "*.*"}
   
 ];
   	
}

jyingo.upload.prototype = {
	
	
	
	initialize : function(data)
	{
	   

     this.text = data.text ? data.text : null;
	   this.progress = data.progress ? data.progress : null;
	   
	   this._send_after = data.send_after ? data.send_after : false;
	   this._element.innerHTML = '<iframe width="100" height="0" src="/jyingo/upload.api.php?text='+this.text+'&handle=' + data.handle + '" frameborder="0" border="0" scrolling="no"></iframe>';
 	   
 	   data.extension ? this.set_filetypes(data.extension) : null;
 	   
 	   
	},
	
	upload_end : function()
	{
		 this._status = 2;
		 this._uploading = false;
		 this.event('status_change', false);		
		 

		 
	},
	
	
	_hide_progress : function()
	{
    var progress =  jyingo.get_by_instance(this.progress);
		if (progress)
		{
		 	 progress.hide();
		}		
	},
	
 	push_error : function(error)
	{
		 this._uploading = false;
		 this._status = error;
		 this.event('status_change', false);
		 
	},
	
	upload_progress : function()
	{
	
		var progress =  jyingo.get_by_instance(this.progress);
		
		if (progress)
		{

			progress.set_value(this._bytes_sent);
			progress.set_max(this._filesize);
			progress.set_text(this._filename);
		
	  }
	},
	
	get_data : function()
	{
		return this._status;
	},
	
	
	_show_progress : function()
	{
		 var progress =  jyingo.get_by_instance(this.progress);
		 
		 if (progress)
		 {
		 	 progress.show();
		 } 		
	},
	
	upload_started : function()
	{
		
		 this._status = 1;
		 this._uploading = true;
		 this.event('status_change', false);
		 

		 
		 
	},
	
	set_filetypes : function(str)
	{
	
	  
	  this.file_types = [];
	  
		var arr = str.split('|');
		for (var i = 0; i < arr.length; i++)
		{
			
			var ext = arr[i], desc = arr[i], exts = ext.split('(', 2)[1];
			exts = exts.substr(0, exts.length-1).split(',').join(';');
		  
		  this.file_types.push({description:desc, extension:exts});
		  	
		}
	},
	
  get_filetypes : function()
  {
  	 return this.file_types;
  },
  
  set_container_size : function(w, h)
  {
  	 
  	 var el = $(this._element).find('iframe').get(0);
  	 
  	 el.width = w;
  	 el.height = h;
  	 
  },
  
  change : function(k, v)
  {
  	
  	if (k == 'extension')
  	{
  		this.set_filetypes(v);
  	}
  	
  	if (k == 'send_after')
  	{
  		this._send_after = v;
  	}
  	
  }
  
	
};

/* --- */


jyingo.button = function() {

		
}

jyingo.button.prototype = {
	
	initialize : function(data)
	{
	  this.delegate('mousedown', this.mousedown);
	  this.delegate('mouseout', this.mouseup);
	  this.delegate('mouseup', this.mouseup);
	  this.delegate('click', this.click);
	  	
	  this.onclick = null;
	},
	
	mousedown : function()
	{
		this.$.addClass('jyingo_button_active');
	},

	mouseup : function()
	{
		this.$.removeClass('jyingo_button_active');
	},
	
	click : function(event)
	{
		var ev = jyingo.eventize(event);
		jyingo.cancel_event(event);
		
	  this.event('click');
	  if (this.onclick != null)
	   this.onclick();
	  
	  return false;
	},
	
  
  text : function(value)
  {
  	
  	 if (value != undefined)
  	 {
  	   this.set_text(value);	
  	 }
  	
		 return this.get_element().innerHTML;
  },
  
  set_text : function(text)
  {
  	
  	this.change('text', text);
  	
  },

	change : function(key, value)
	{

		if (key == 'text')
		{

		 this.get_element().innerHTML = value;
	   
	  }
	 
	}
	
};

/* -- */
jyingo.linkbutton = function() {

		
}

jyingo.linkbutton.prototype = {
	
	initialize : function(data)
	{
		if(this.$.attr('href') != '#')
		 return;
		
	  this.delegate('mousedown', this.mousedown);
	  this.delegate('mouseout', this.mouseup);
	  this.delegate('mouseup', this.mouseup);
	  this.delegate('click', this.click);
	  	
	  this.onclick = null;
	},
	
	mousedown : function()
	{
		this.$.addClass('jyingo_linkbutton_active');
	},

	mouseup : function()
	{
		this.$.removeClass('jyingo_linkbutton_active');
	},
	
	click : function(event)
	{
		var ev = jyingo.eventize(event);
		jyingo.cancel_event(event);
		
	  this.event('click');
	  if (this.onclick != null)
	   this.onclick();
	  
	  return false;
	},
	
  
  text : function(value)
  {
  	
  	 if (value != undefined)
  	 {
  	   this.set_text(value);	
  	 }
  	
		 var span = this.$.find('span').get(0);
		 return span.innerHTML;
  },
  
  set_text : function(text)
  {
  	
  	this.change('text', text);
  	
  },

	change : function(key, value)
	{

		if (key == 'text')
		{
		 
		 var span = this.$.find('span').get(0);
		 span.innerHTML = value;

		 
		 //this.get_element().innerHTML = value;
	   
	  }
	  
	  if (key == 'icon')
	  {
	  	
	  	if (value)
	  	{
	  		this.$.find('img').get(0).src = value;
	  		this.$.find('img').get(0).style.display='';
	  	} else {
	  		this.$.find('img').get(0).style.display='none';
	  	}
	  	
	  }
	 
	}
	
};

jyingo.option = function()
{
	
}

jyingo.option.prototype = {
	
	initialize : function(params)
	{
		
   
    this.text = params.text ? params.text : null;
    this.value = params.value ? params.value : null;
    
    var parent = this.get_parent();
    
    var el = parent.get_element_id(this.get_instance());
    
    if (el == null)
    {
    	el = document.createElement('option');
    	el.value = this.get_instance();
    	el.innerHTML = this.text;
    	parent._addChild(el); 	  	
    } else {
    	el.innerHTML = this.text;
    } 
    
    this._element = el;
    
    if (params.selected == true)
     this.select();
		
	},
	
	is_selected : function()
	{
		return this._element.selected;
	},
	
	select : function()
	{
		 this._element.selected = true;
	},

	unselect : function()
	{
		 this._element.selected = false;
	},
	
	create_element : function()
	{
	  
	  return {};
	 
	}	
	
};

jyingo.select = function()
{
	
}

jyingo.select.prototype = {
	
	initialize : function(params)
	{
		
		this.auto_width = (params.autowidth && params.autowidth != undefined);
   
  
		this.delegate('resize', this.autoresize, window);
		this.delegate('change', this.change);
		
		this.delegate('mouseup', this.cancel_event);
		this.delegate('mousedown', this.cancel_event);
		
		this.autoresize();
				    
	},
	
	cancel_event : function(event)
	{
		var ev = jyingo.eventize(event);

 	   ev.cancelBubble = true;
 	   
 	   if (ev.stopPropagate)
 	    ev.stopPropagate();

 	   if (ev.stopPropagation)
 	    ev.stopPropagation();
 	    
 	    if (ev.originalEvent)
 	     ev.originalEvent.preventDefault();

 	    
		return true;
	}, 
	 
	change : function(event)
	{
		
		var ev = jyingo.eventize(event);
		jyingo.cancel_event(ev);
		
		this.event('change');
		
		return false;
	},
	
	get_data : function()
	{
		 
		 var sel = null;
		 
		 for (var i = 0; i < this.get_element().options.length; i++)
		 {
		   
		   var opt = this.get_element().options[i];
		   if (opt.selected)
		   {
		    sel = opt.value;
		    break;	
		   }
		 	
		 }
		 return sel;
		
	},
	
	autoresize : function()
	{
		
		if (this.auto_width == true)
		{
			var space = this.$.outerWidth() - this.$.width();
			
			if (this._element.parentNode)
			{
			 this._element.style.width = '10px';
			 this.$.css({ width : $(this._element.parentNode).width() - space - ( this.$.offset().left - $(this._element.parentNode).offset().left  ) - 1 });
		  }
		}
		
	},
	
	
	get_element_id : function(id)
	{
		 for (var i = 0; i < this.get_element().options.length; i++)
		 {
		  
		  
		   var opt = this.get_element().options[i];

		   if (opt.value == id)
		    return opt;	
		 	
		 }
		 
		 return null;
	},
	
	create_element : function()
	{
	 return document.createElement('select');	
	}	
	
};

jyingo.extend('window', function(self, settings) {
	
	var _is_appearing = false;
	var _instance = null;
	var _params = [];
	var _location = null;
	var _element = null;
	var _visible = true;
	var _currently_visible = true;
	var _appeared = false;
	var _cstack = 0;
  var _stack_state = [];
  var _appearing = true;
  var _timer = false;
  var _disposing = false;
  var _w = 0, _h = 0;
  var __parent = null;
  var _real_appeared = false;

  
  function resize()
  {

  	
  	 if (settings._type == 'popup')
  	     _element.$.css({ position : 'absolute', top : parseInt( jyingo.get_window_height()/2 -  _element.$.height()/2), left: parseInt((jyingo.get_window_width()-jyingo.get_window_offsetW())/2 - _element.$.width()/2) + jyingo.get_window_offsetX()});
  	 
  	 if (settings._type == 'wrapped')
  	     _element.$.css({ width : jyingo.get_window_width()- jyingo.get_window_offsetW() , height: jyingo.get_window_height(), top: 0, left: jyingo.get_window_offsetX() });
  	
    
  	jyingo._customcall('windowResized', __parent);
  }
  
  function change_visibility()
  {
  	
  	_cstack--;
  	if (_cstack) return;
  	
			 
		if( !_appeared && _appearing)
	  {
			 appears();
			 return;
	  }
	  
	  if (!_appeared)
	   return;
			  	
  	
  	if (_currently_visible == _visible)
  	 return;
  	
  	
  	
  	var _off = 16;
  	_currently_visible = _visible;
  	
  	 
  	if (_visible)
  	{
  		if (settings._transition == 'fade')
  		  _element.t3d.animate({ opacity : 1 }, 350, __changed_visibility);
  		else if (settings._transition == 'slide')
  		{
  			$('html,body').css({ 'overflow-x' : 'hidden' });
  		  _element.t3d.animate({ y : 0}, 350).animate({ y : -_off }, 50, 'swing').animate({ y :  0  }, 50, 'swing', __changed_visibility);
  		}
  		else
  		{
  			_element.$.css({ opacity : 1 });
  			__changed_visibility();
  		}
  	}
  	else
  	{
  		if (settings._transition == 'fade')
  		 _element.t3d.animate({opacity : 0}, 250);
  		else if (settings._transition == 'slide')
  		 _element.t3d.animate({ y : -(_element.$.outerHeight() + _element.$.offset().top)}, 500); 
  		else
  			_element.$.css({ display:'none' }); 
  	
  	
  	  __changed_visibility();
    }
  }
 
  function __changed_visibility()
  {
  	$('html,body').css({ 'overflow-x' : '' });
  	jyingo._customcall('windowChangedVisibility', __parent);
  }
 
  function appears()
  {
  	if (!_appeared && _appearing && !_is_appearing)
  	{
  		
  	 
  	 _is_appearing = true;
  	 _element.$.css({ display : 'block'});
  	 
  	 if (settings._transition == 'slide')
  	 {
  	 	  var element = _element.get_element();
  	    _element.t3d.set({ x : jyingo.get_window_width() - $(element).offset().left + jyingo.get_window_offsetX() });
        $('html,body').css({ 'overflow-x' : 'hidden' });
  	    _element.t3d.animate({ x : 0  }, 600, function(obj) {
					$('.__ns_scrolled').niceScroll().resize();
					_real_appeared = true;
					resize();
					__changed_visibility();
					
  	    });
  	    
  	 } else if (settings._transition == 'fade')
  	 {
  	   _element.t3d.set({ opacity : 0});
  	   _element.t3d.animate({ opacity : 1}, 350, function() { _real_appeared = true; resize(); __changed_visibility(); });
  	 } else {
  	  _real_appeared = true;	
			setTimeout(__changed_visibility, 10);
			
  	 }
  	  
  	 _appeared = true;
  	 jyingo._customcall('windowAppears', __parent);
  	 if (get_module().onAppears) get_module().onAppears();
  	 resize();
  	 $(document).scrollTop(0); 
  	 
  	}
  }
  
  function get_module()
  {
  	return jyingo.get_by_instance(_instance);
  }
 	
	self.proto({

    
		_initialize : function()
		{
			
			 __parent = this;
			 _instance = settings._instance;
			 _params = settings._params;
			 _location = settings._location;
			 _element = jyingo.get_by_instance(_instance);

	     resize();		   
		   $(window).bind('resize', jyingo.delegate(this, this.resize));
		   
		   appears();
		   /*
			 setTimeout(jyingo.delegate(this, function() {
			  
			   if (_appearing)
			    appears();
			  	
			  }), (settings._transition == 'slide' ? 50 : 0));*/
		},
		
		get_transition : function()
		{
			return settings._transition;
		},
		
		push_state : function()
		{
			 
			 _stack_state.push(_visible);
		},
		
		pop_state : function()
		{
			 var _state = _stack_state.pop();
			 if (_state) this.show();
			 else this.hide();
		},
		
		show : function()
		{

			 if (_visible && _appeared)
			  return;
			 
			 _cstack++;
			 _visible = true;
			 _appearing = true;
      
	    if (!(typeof setImmediate == 'undefined'))
         setImmediate(jyingo.delegate(this, change_visibility)); 
	   	else
	   	   setTimeout(jyingo.delegate(this,change_visibility), 0);		
			 
		},
		
		hide : function()
		{
			if (!_appeared)
			{
				_appearing = false;
				return;
			}
			
			if (!_visible)
			 return;
			
			_cstack++;
			_visible = false;
			
			change_visibility();
			/*
	    if (!(typeof setImmediate == 'undefined'))
         setImmediate(jyingo.delegate(this, change_visibility)); 
	   	else
	   	   setTimeout(jyingo.delegate(this,change_visibility), 0);		
			*/
		},
		
		resize : function()
		{
			resize();
		},
		
		is_visible : function()
		{
			return _currently_visible && _real_appeared;
		},
		
		get_dom : function()
		{
			var element = jyingo.get_by_instance(_instance) ? jyingo.get_by_instance(_instance).get_element() : document.getElementById(_instance);
			return element;
		},
		
		get_module : function()
		{
			return get_module();
		},
		
		_dispose : function()
		{
			
		  _disposing = true;
    	_currently_visible = false;
    	
    	__changed_visibility();
			var element = jyingo.get_by_instance(_instance) ? jyingo.get_by_instance(_instance).get_element() : document.getElementById(_instance);
			 
	    $(element).find('div.__ns_scrolled').niceScroll().remove();
			/*$(element).css({ width: $(element).width(),
				               height: $(element).height(),
				               position: 'absolute',
				               top: $(element).offset().top,
				              left: $(element).offset().left });*/
			
		  var animator = new jyingo.css3animator(element);
			if (settings._transition == 'slide')
			{
				   $('html,body').css({ 'overflow-x' : 'hidden' });
				   animator.animate({ x : - ($(element).width()+$(element).offset().left)  + jyingo.get_window_offsetX()/*jyingo.get_window_width() - $(element).offset().left + 250*/ },
				                    600, jyingo.delegate(this, this._finalize));
				   
				   
			} else if (settings._transition == 'fade') {
				 
				   animator.animate({ opacity : 0 },
				                    350, jyingo.delegate(this, this._finalize));
				   
				
			} else {
				this._finalize();
			}
		},
		
		_finalize : function()
		{
			$('html,body').css({ 'overflow-x' : '' });
			if (jyingo.get_by_instance(_instance) != null)
			{
		
				jyingo.get_by_instance(_instance)._dispose();
		
			} else {
				
				 var el = document.getElementById(_instance);
				 if (el && el.parentNode)
				 {
				 	 el.parentNode.removeChild(el);
				 }
		   		
			}
		},
		
	  _render : function()
	  {
	  	if (!_location)
	  	 return;
	  	
	    var hash = '#'+location;
	    
	    
	    if (jyingo.count_object(_params))
	    {
	      
	      hash += '?';
	      var p = new Array();
	      
	      for (var x in _params)
	       p.push(x + '=' + _params[x]);	
	    	
	    	hash += p.join('&');
	    	
	    }
	    
	    this.hash = hash;
	    
	  },
		
		set_location : function(location)
		{
			_location = location;
			this._render();
		},
		
		set_param : function(name, value)
		{
			_params[name] = value;
			this._render();
		}
				
		
	});
	
	return new self();
	
});



jyingo.soundmanager = function()
{
	
	this.sound_cache = {};
	this._si = null;
	this._si_ready = false;
	jyingo.set_sound_manager(this);
};

jyingo.soundmanager.prototype =  {
	
	initialize : function(params)
	{
		
		var obj = document.createElement('div'); 

    var swf = jyingo.createSwfObject(globals.static_url+'/jyingo/si.swf?v4', {id: '___jyingo_si', width: 1, height: 1}, {allowscriptaccess : 'always'});
    this._si = swf;
    
    $(obj).css({ position: 'absolute', top:-1000, left:-1000});
    document.body.appendChild(obj);
    obj.appendChild(swf);

		
		if (params.cache != undefined)
		{
			 for (var key in params.cache)
			 { 
			 	 var info = params.cache[key];
			   this.add_sound(key,  info.path, info.group, info.channels );
			 }
		} 
	},
	
	get_si : function()
	{
		 if (!this._si_ready)
		  return null;
		 
		 return this._si;
	},
	
	_si_loaded : function()
	{
		
		
		this._si_ready = true;
		for (var sound_id in this.sound_cache)
		 this.sound_cache[sound_id].ready();
	},
	
	add_sound : function(sound_id, path, group, channels)
	{
		if (this.sound_cache[sound_id] != undefined)
		 return;
		 

		if (channels == undefined)
		 channels = 1;
		
		if (group == undefined)
		 group = 'default';
		 

		this.sound_cache[sound_id] = { defered_status : null, loop : false, volume : 1.0, path : path, ccount : channels, group : group, channels : [], channels_index : 0, 
			
			stop : function() {  
			 
			 if (jyingo.sound().get_si() == null)
			 {
			   this.defered_status = null;
			   return;
			 }
			 
			 for (var i = 0; i < this.channels.length; i++)
			 {
			 	  jyingo.sound().get_si().stopSound(this.channels[i]._id);
			 }
			},
			
			ready : function()
			{
				if (!this.channels.length)
				{
					 for (var i = 0; i < this.ccount; i++)
					  this.channels.push({ _id : jyingo.sound().get_si().loadSound(path) });
				}
				
				if (this.defered_status == 'play')
				 this.play();
			},
			
			
			play : function()
			{
				if (jyingo.sound().get_si() == null)
				{
					 this.defered_status = 'play';
					 return;
				} 
				
				jyingo.sound().get_si().playSound(this.channels[this.channels_index]._id, this.loop, this.volume);
				this.channels_index++;
				
				if (this.channels_index >= this.channels.length)
				 this.channels_index = 0;
				
			}
	 };
	 
	 if (this.get_si() != null)
	  this.sound_cache[sound_id].ready();

	},
	
	stop : function(group_id)
	{
		 
		
		
		if (group_id == undefined || !group_id)
		{
			for (var key in this.sound_cache)
			{
				 var info = this.sound_cache[key];
				 info.stop();
			}
			
		} else {
			for (var key in this.sound_cache)
			{
				 var info = this.sound_cache[key];
				 if (info.group == group_id)
				  info.stop();
			}
		}
		
	},
	
	set_disabled : function(disabled)
	{
		if (disabled)
		{
			this.stop();
		} else {
			
		}
		 
	},
	
	play : function(sound_id, looped, volume)
	{
		 
		 if (this._disabled)
		  return;
		 
		 if (looped == undefined)
		  looped = false;
		 
		 if (volume == undefined)
		  volume = 1.0;
		 
		if (this.sound_cache[sound_id] == undefined)
		 return;
		
		var info = this.sound_cache[sound_id];
	//	if (info.group != 'default')
	//	 this.stop(info.group);
	
	//  console.log('Playing: '+sound_id);
		
		info.volume = volume;
		info.loop = looped; 
		info.play();
		
		 
	//	$('body').append('<embed class="jyingo_sound '+info.group+'" src="'+info.path+'" autostart="true" hidden="true" loop="'+(looped ? 'true' : 'false')+'">');
	}
	
	
	
};

module = { extend : jyingo.extend };// {extend: function(b, a) {  var shared={}; this[b] = function(settings){  return new a(function(){}, settings, shared); }}};
page = { extend : jyingo.extend };//{extend: function(b, a) {  var shared={}; this[b] = function(settings){  return new a(function(){}, settings, shared); }}};
generic = { extend : jyingo.extend };
globals = {};
resources = {};
components = {};
 