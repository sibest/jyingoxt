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

 jyingo.listview_item = function(params)
 { 
 	
 	
 	 this.shiftarr = {};
 	 this.tmrctx = null;
 	 this.eat_click = false;
 }
 
 jyingo.listview_item.prototype = {
 	
 	initialize : function(params)
 	{
 		 this.mode = params.mode ? params.mode : 'list';

 	   
 	   if (this.mode == 'select' || this.mode == 'button')
 	   {
 	     this.delegate('click', this.click);	
 	   
 	   }
 	   
 	   if (this.mode == 'button')
 	    this.$.addClass('button');
 	   
 	   this.href = params.href ? params.href : null; 	   
 	   this.delegate('touchstart', this.down);
 	   this.delegate('touchend', this.up);
 	   
 	   this.delegate('contextmenu', this.contextmenu);
 	   
 	   this.delegate('keydown', this.keydown, document);
 	   this.delegate('keyup', this.keyup, document);
 	   
 	},
 	
 	down : function()
 	{
 		if (this.tmrctx != null)
 		{
 			clearInterval(this.tmrctx);
 		  this.tmrctx = null;
 		}
 		
 		this.tmrctx = setInterval(this.create_delegate(this.call_context), 500);
 		return true;
 	},
 	
 	call_context : function()
 	{

 		if (this.tmrctx != null)
 		{
 			clearInterval(this.tmrctx);
 		  this.tmrctx = null;
 		}
    
    this.event('context');
    
 	  this.eat_click = true;
 	  return true;
 	  
 	},
 	
 	up : function(ev)
 	{
 		
 		var ev = jyingo.eventize(ev);
 		
 		if (this.tmrctx != null)
 		{
 			clearInterval(this.tmrctx);
 		  this.tmrctx = null;
 		} 	
 		

 		if (this.eat_click == true)
 		{
 			this.eat_click = false;
 			jyingo.cancel_event(ev);
 			return false;
 		}
 		
 		return true;
	
 	},
 	
 	keydown : function(ev)
 	{
 		 var ev = jyingo.eventize(ev);
 		 var shifted = ev.shiftKey; 
     var ctrld = ev.ctrlKey;
     
     if (shifted)
      this.shiftarr['s'] = true;
     
     if (ctrld)
      this.shiftarr['c'] = true;
      
     
 	},
 	
 	keyup : function(ev)
 	{
 		this.shiftarr = {};
 	},
 	
 	do_end_rename : function()
 	{
 		
 		
 		this.call(function() { }, 'end_rename', this.renaming_obj.get_data());
 		
 		
 	},
 	
 	renaming : function(obj)
 	{
 	
 	
 	   var obj = jyingo.get(obj);
 	   
 	   this.renaming_obj = obj;
 	   
 	   this.delegate('blur', this.do_end_rename, obj.get_element());
 	   
 	   obj.set_press_handler( this.create_delegate( this.do_end_rename ));
 	   
 	},
 	
 	contextmenu : function (ev)
 	{

 		if (this.get_parent().get_parent().get_parent()._renaming == true)
 		 return;
 		
 		var ev = jyingo.eventize(ev);
 		jyingo.cancel_event(ev);
 		
 		this.event('context');
 		
 		return false;
 	},
 	
 	click : function(ev)
 	{
 		

    if (this.href)
    {
    	
    	
			
			if (jyingo.get_window_manager())
			 jyingo.get_window_manager().set_hash_position(this.href);
      
      return;
    	
    }
 		
 		
 		if (this.get_parent().get_parent().get_parent()._renaming == true)
 		 return;
 		 
 		this.event(this.mode);
 	},
 	
 	get_data : function()
 	{

 		 return this.shiftarr;
 	}
	
 };
 
 jyingo.listview = function(params)
 {
 	 this.multiselect = false;
 	 this._renaming = false;
 	
 }
 
 jyingo.listview.prototype = {
 	
 	initialize : function(params)
 	{

    this.mode = params.mode ? params.mode : 'list';

 	   if (this.mode == 'select')
 	    this.$.addClass('select');
 		
 		this.multiselect = params.multiselect ? params.multiselect : false;
 		this.selectable = params.selectable ? params.selectable : false;
 		
 		if (this.selectable)
 		this._element.onselectstart = Function("return true;");
 	},
 	
 	init_scroll : function()
 	{
 	 this.get('tbody').$.niceScroll();
 	},
 	
 	begin_renaming : function()
 	{
 		this._renaming = true;
 		this._element.onselectstart = Function("return true;");
 	},
 	
 	end_rename : function()
 	{
 		this._renaming = false;
 		if (!this.selectable)
 		this._element.onselectstart = Function("return false;"); 		
 	}
	
 };