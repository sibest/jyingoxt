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

jyingo.completion_element = function(params) 
{
	
	
};

jyingo.completion_element.prototype = {
	
	initialize : function(params) {
		
		

		var children = this.get_parent().get_element().childNodes;
		
		
		
		
		this.value = params.value;
		
		
		var i = 0;
		while (children[i++] != this.get_element());
		
		if (i == children.length)
		{
			this.$.addClass('last');
		}
		
		if (this.value) {
			
			 this.$.addClass('selectable');
			 this.delegate('click', this.click); 
			 this.delegate('mousedown', this.mousedown); 
		}
		 
		 
		
		
	},
	
	mousedown : function(event)
	{
		
		var ev = jyingo.eventize(event);
		jyingo.cancel_event(ev);
				
		return false;		
	},
	
	click : function(event)
	{

		this.get_parent()._element_clicked(this);

	}
	
	
};
 
jyingo.completion = function(params)
{
	this.interval = null;
	this.target = null;
	this._timed_search = null;
	this.showing = false;
  this.has_focus = false;
  this.offset = 0;
  this.offsetY = 0;
  this.width = 0;
};

jyingo.completion.prototype = {
	
	
	initialize : function(params)
	{

		this._element.parentNode.removeChild(this._element);
		document.body.appendChild(this._element);
		
		
		var tgt = params.target ? params.target : null;
		if (tgt) tgt = jyingo.get(tgt);
		
		this.interval = params.interval ? params.interval : 300;
		
		this.target = tgt;
		
		
		this.initialize_target();
		
		this._element.className += ' jyingo_completion';
		this.width = params.width;
		this.offset = params.offset;
		this.offsetY = params.offsetY;
		
	},
	
	_element_clicked : function(obj)
	{
		this.call(null, 'click', obj.get_instance());
		//this.blur();
		this.close();
	},

	
  initialize_target : function()
  {
  	
  	if (this.target)
  	{
  		
  		
  		 this.delegate('keydown', this.keypress, this.target.get_input_element());
  		 this.delegate('focus', this.focus, this.target.get_input_element());
  		 this.delegate('blur', this.text_blur, this.target.get_input_element());
  		 this.$tgt = $(this.target.get_view_object());
  		 this.catch_outside('ev1', 'mousedown', this.create_delegate(this.blur), this._element, this.target.get_input_element());
  		 
  		  this.target.rehandle = this.create_delegate(this.rehandle);
  		 
  		
  	}
  },
  
  rehandle : function()
  {
  		 this.delegate('keydown', this.keypress, this.target.get_input_element());
  		 this.delegate('focus', this.focus, this.target.get_input_element());
  		 this.delegate('blur', this.text_blur, this.target.get_input_element());
  		 this.$tgt = $(this.target.get_view_object());
  		 this.catch_outside('ev1', 'mousedown', this.create_delegate(this.blur), this._element, this.target.get_input_element());	
  },
  
  text_blur : function()
  {
  	this.$.removeClass('_active');
  },
  
  focus : function()
  {
  	//if (this.target.get_data())
  	 this.search();
  	
  	this.$.addClass('_active');
  	this.has_focus = true;
  },
  
  keypress : function(event)
  {
  		
  		
  	if (this._timed_search != null)
  	 clearInterval(this._timed_search);
  		 
  	this._timed_search = setInterval(this.create_delegate(this.search), this.interval);
  		
  
  },
  	
  search : function()
  {
   	if (this._timed_search != null)
  	 clearInterval(this._timed_search); 	
  		
  	this._timed_search = null;
    this.call(this.result, 'complete', this.target.get_input_data());
  			
  },
  
  open : function()
  {
  	if (this.showing == true)
  	 return;
    
    this.$.addClass('_active');
    this.$.css({ top: this.$tgt.offset().top + this.$tgt.outerHeight() - 2 + this.offsetY, left : (this.$tgt.offset().left + this.offset), width: ( this.width ? this.width : this.$tgt.outerWidth() - 2) });
    this.showing = true;
    
    this.$.slideDown('fast');
    
    
    
  },
  
  close : function()
  {
  	if (this.showing == false)
  	 return;  	
  	
  	this.$.removeClass('_active');
  	
  	this.showing = false;
  	this.$.slideUp('fast');
  	
  	
  },
  
  result : function(data)
  {
  	
  	if (!this.has_focus)
  	 return;
  	 
  	 
  	if (!data)
  	 this.close();
  	else
  	 this.open();
  }, 
  	
  blur : function(event)
  {

  	this.close();
  	this.has_focus = false;
  	
   	if (this._timed_search != null)
   	{
  	 clearInterval(this._timed_search);   	
  	 this._timed_search = null;
  	}
  }
  	
  	

};