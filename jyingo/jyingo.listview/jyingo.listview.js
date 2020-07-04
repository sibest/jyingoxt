/*
 * jyingoXT, PHP Ajax Toolkit http://www.jyingo.com
 * Copyright 2011-2012 Andrea Pezzino (haxormail@gmail.com) 
 * by the @authors tag. See the copyright.txt in the distribution for a
 * full listing of individual contributors.
 *
 * This is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation; either version 2.1 of
 * the License, or (at your option) any later version.
 *
 * This software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this software; if not, write to the Free
 * Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301 USA, or see the FSF site: http://www.fsf.org.
 */

 jyingo.extend('listview_item', function(self) {
 	

	 	var shiftarr = {};
	 	var tmrctx = null;
	 	var eat_click = false;

	 
	 self.prototype = {
	 	
	 	initialize : function(params)
	 	{
	 		 this.mode = params.mode ? params.mode : 'list';
	
	 	   
	 	   if (this.mode == 'select' || this.mode == 'button')
	 	   {
	 	     this.delegate('click', this.click);	
	 	   
	 	   }
	 	   
	 	   this.delegate('touchstart', this.down);
	 	   this.delegate('touchend', this.up);
	 	   
	 	   this.delegate('contextmenu', this.contextmenu);
	 	   
	 	   this.delegate('keydown', this.keydown, document);
	 	   this.delegate('keyup', this.keyup, document);
	 	   
	 	},
	 	
	 	focus : function()
	 	{
	 		this.get_parent().get_parent().get_parent().focus(this);
	 	},
	 	
	 	down : function()
	 	{
	 		if (tmrctx != null)
	 		{
	 			clearInterval(tmrctx);
	 		  tmrctx = null;
	 		}
	 		
	 		tmrctx = setInterval(this.create_delegate(this.call_context), 500);
	 		return true;
	 	},
	 	
	 	call_context : function()
	 	{
	
	 		if (tmrctx != null)
	 		{
	 			clearInterval(tmrctx);
	 		  tmrctx = null;
	 		}
	    
	    this.event('context');
	    
	 	  eat_click = true;
	 	  return true;
	 	  
	 	},
	 	
	 	up : function(ev)
	 	{
	 		
	 		var ev = jyingo.eventize(ev);
	 		
	 		if (tmrctx != null)
	 		{
	 			clearInterval(tmrctx);
	 		  tmrctx = null;
	 		} 	
	 		
	
	 		if (eat_click == true)
	 		{
	 			eat_click = false;
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
	      shiftarr['s'] = true;
	     
	     if (ctrld)
	      shiftarr['c'] = true;
	      
	     
	 	},
	 	
	 	keyup : function(ev)
	 	{
	 		shiftarr = {};
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
	 		
	
	 		
	 		if (this.get_parent().get_parent().get_parent()._renaming == true)
	 		 return;
	 		 
	 		this.event(this.mode);
	 	},
	 	
	 	get_data : function()
	 	{
	
	 		 return shiftarr;
	 	}
		
	 };
 	 
 	
 	 return new self();
 });

 
 
 jyingo.extend('listview', function(self) {
 	
 	
 	 multiselect = false;
 	 _renaming = false;
 	 
	 self.prototype = {
	 	
	 	initialize : function(params)
	 	{
	
	    this.mode = params.mode ? params.mode : 'list';
	
	     this.$.addClass(this.mode);
	 		
	 		multiselect = params.multiselect ? params.multiselect : false;
	 		this.selectable = params.selectable ? params.selectable : false;
	 		
	 		if (this.selectable)
	 		this._element.onselectstart = Function("return true;");
	 	},
	 	 
	 	init_scroll : function()
	 	{
	 	   this.get('tbody').$.niceScroll();
	 	   this.get('tbody').$.addClass('__ns_scrolled');
	 	},
	 	
	 	begin_renaming : function()
	 	{
	 		_renaming = true;
	 		this._element.onselectstart = Function("return true;");
	 	},
	  
	  focus : function(obj)
	 	{
	 		obj.$.attr("tabindex",-1).focus();
	 	},	
	 	
	 	end_rename : function()
	 	{
	 		_renaming = false;
	 		if (!this.selectable)
	 		this._element.onselectstart = Function("return false;"); 		
	 	}
		
	 };
 	 
 	 return new self();
 });
 
