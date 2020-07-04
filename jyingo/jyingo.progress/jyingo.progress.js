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
 
 jyingo.progress = function(params)
 {
 	
 	
 }
 
 jyingo.progress.prototype = {
 	
 	initialize : function(params)
 	{
 		
 		 this._element.className = params.class ? params.class : 'jyingo_progress';
 		 
 		 var el = document.createElement('div');
 		 el.className = 'progress';
 		 
 		 
 		 this.max = params.max ? params.max : 100;
 		 this.value = params.value ? params.value : 0;
 		 this.text = params.text ? params.text : null;
 		 
 		 this._bar = el;
 		 
 		 this._element.appendChild(el);
 		 
 		 el = document.createElement('div');
 		 el.className = 'text';
 		 
 		 this._text = el;
 		 
 		 this._bar.appendChild(el);
 		 
 		 this.render();
 	},
 	
 	set_max : function(value)
 	{
 		this.max = value;
 		this.render();
 	},
 	
 	set_value : function(value)
 	{
 		
 		this.value = value;
 		this.render();
 		
 	},
 	
 	set_text : function(value)
 	{
 		
 		this.text = value;
 		this.render();
 		
 	},
 	
 	render : function()
 	{
 		
 		var iw = $(this._element).innerWidth();
 		var t = this.value/this.max * iw;
 		
 		$(this._bar).css({ width : Math.ceil(t-2) + 'px' });
 		
 		var txt = Math.floor(this.value/this.max * 100) + '%';
 		
 		if (this.text)
 		 txt += ' <strong>'+this.text+'</strong>';
 		
 		this._text.innerHTML = txt;
 		
 		
 	},
 	
 	change : function(k, v)
 	{
 		
 		if (k == 'value')
 		 this.set_value(v);
 		
 		if (k == 'max')
 		 this.set_max(v);
 		
 		if (k == 'text')
 		 this.set_text(v);
 		
 		if (k == 'width')
 		{
 			this.$.css({width : v+'px'});
 			this.render();
 		}
 		
 		if (k == 'classname')
 		{
 			this._element.className = v;
 			this.render();
 		}
 		
 	}
 	
 	
 };