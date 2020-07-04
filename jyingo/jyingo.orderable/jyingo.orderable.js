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
jyingo.orderable = function(params)
{
	 this._timer = null;
};

jyingo.orderable.prototype = {
	
	initialize : function(params)
	{
		 var _p = this;
		 this.filter = params.filter;
		 this.$.sortable({ cursor:'move', 
		 	 distance : 16,
		   start: function(ev, ui) {
		    ui.placeholder.html('&nbsp;'); 	
		    _p._started();
		   }, 	  
		   sort : function(ev, ui) {
		 	   var obj = ui.item[0];
		 	   return _p._can_sort(obj);
		  
		   },
		   stop : function(ev, ui) { 
		  
		     _p._sorted();
		   
		   }
		  }); 
		
	},
	
	_started : function()
	{
		 if (this._timer)
		 {
		   clearInterval(this._timer);
		   this._timer = null;
		   	
		 }
	},
	
	_sorted : function()
	{
		  this._started();
		  this._timer = setInterval(this.create_delegate(this._send_data), 400);
		  
	},
	
	update : function()
	{
		this._send_data();
	},
	
	_send_data : function()
	{
		 this._started();
		 var children = this.get_children_ordered();
		 
		 var order = [];
		 
		 for (var i = 0; i < children.length; i++)
		 {
		 	
		 	 if (!this._can_sort(children[i].get_element()))
		 	  continue;
		 	 
		 	 order.push(children[i].get_instance());
		  	
		 }
		 
		 this.call(function(){}, '_sorted', order);
	},
	
	dispose : function()
	{
		 this._started();
	},
	
	addChild : function(child)
	{
		var nodes = this.get_element().childNodes;
		this.get_element().insertBefore(child, nodes[nodes.length-1]);
	},
	
	_can_sort : function(obj)
	{
		  var s = this.filter.split('.');
		  
	
		  if ((!s[0].length || s[0].toUpperCase() == obj.tagName.toUpperCase()) &&
		      (!s[1] || ($(obj).hasClass(s[1]))))
		        return true;
		  
		  setTimeout(function(){
		  	$(document.body).css({ cursor : 'auto' });
		  }, 0);
		  return false;
	},
  
  reparse : function()
  {
	
  }
	
};