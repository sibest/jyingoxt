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
 
jyingo.fixedbox = function(params)
{
	 this.update_size_interval = null;
};

jyingo.fixedbox.prototype = {
	
	initialize : function(data)
	{
	   
	   this.offset = data.offset != undefined ? data.offset : 0;
	   
     this.update_size_interval = setInterval(this.create_delegate(this.set_fixed_size), 10);
     this.placeholder = document.getElementById(this.get_instance() + '_placeholder');
     

     this.margin = (this.$.outerWidth() - this.$.innerWidth()) + parseInt(this.$.css('marginLeft')) + parseInt(this.$.css('marginRight'));
     
     this.real_top = this.$.offset().top;
      
     this.set_fixed_size();
     this.delegate('scroll', this.scroll, window);
     
     window.addEventListener("gesturechange", this.create_delegate(this.scroll), false); 
     
     
    
     
	},
	
	scroll : function()
	{
		
		if ($(document).scrollTop() <= this.offset)
		{
			this.$.removeClass('scrolled');
		} else {
			this.$.addClass('scrolled');
		}
		
	},

	dispose : function()
	{
		if (this.update_size_interval)
			clearInterval(		this.update_size_interval  );
	  
	  //this.placeholder.parentNode.removeChild(this.placeholder);
	  
	},

	set_fixed_size : function()
	{
		
		
		var x = this.get_element().parentNode;
		
		var w = $(x).width() - this.margin + 1;
		
		this._element.style.top ='0px';
		this._element.style.width = w+'px';

		$(this.placeholder).css({height : (this.$.outerHeight()+10) + 'px'});
		
		
		
	}
	
	
	
};