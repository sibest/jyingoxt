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

jyingo.droppable = function(params)
{
	 this._timer = null;
};

jyingo.droppable.prototype = {
	 
	initialize : function(params)
	{
		 var _p = this;
		 this.filter = params.filter;
		 this.activeclass = params.activeclass;
		 this.original = params.original;
		 this.$.droppable({ cursor:'move',
		 	hoverClass : this.activeclass, 
		 	 distance : 16,
		 	 accept : this.filter,
		   start: function(ev, ui) {
		    ui.placeholder.html('&nbsp;'); 	
		    _p._started();
		   }, 	  
		   
		   drop : function(ev, ui)
		   {
		   	
		   	
		   	
		 	  var obj = ui.draggable.get(0);
		 	  _p._dropped(obj);

		   },
		   
		   /*sort : function(ev, ui) {
		 	  
		 	   var obj = ui.item[0];
		 	   return _p._can_sort(obj);
		  
		   },
		   
		   stop : function(ev, ui) { 
		     
		   //  _p._sorted();
		   
		   }*/
		  }); 
		
	},
	
	_dropped : function(obj)
	{
		 	  
	$(obj).removeAttr('style');
	if (obj.parentNode == this.get_element())
		 return;
	 
	 if (this.original == true)
	 { 
	  obj.parentNode.removeChild(obj);
    this.get_element().appendChild(obj);
	 }
	 
	 this.call(function(){}, '_drop', obj.id);
		  		
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

	
	dispose : function()
	{
		 this._started();
	},

	
  reparse : function()
  {
	
  }
	
};