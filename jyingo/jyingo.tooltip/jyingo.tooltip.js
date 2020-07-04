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

jyingo.tooltip_mgr = {
	
	arr : new Array(),
	
	add : function(t)
	{
		
		this.arr.push(t);
		
		
	},
	 
	showing : function(t)
	{
		
		for (var i = 0; i < this.arr.length; i++)
		{
			
			if (this.arr[i] != t)
			 this.arr[i].hide();
			
		}
		
	},
	
	rem : function(t)
	{
		
		 
		var k = new Array();
		 
		for (var i = 0; i < this.arr.length; i++)
		{
			
			if (this.arr[i] != t)
			 k.push(t);
	  }
		
		this.arr = k;
		
	}
	
	
	
	
};

jyingo.tooltip = function(params)
{
	


	this._showing = false;

	
	this._timed_showing = false;
	this._timed_interval = null;
	
	
	this._i = false;
	
};

jyingo.tooltip.prototype = {
	
	reinitialize : function()
	{
		this.initialize(this._params);
	},
	
	initialize : function(params)
	{
		this.clickremove = params.clickremove == undefined ? true : params.clickremove;
		this.$.addClass( 'jyingo_tooltip' );
		this.attacher = params.attacher ? params.attacher : this.setup_parent_attacher();
		this.interactive = params.interactive ? params.interactive : false;
		this.maxwidth = params.maxwidth ? params.maxwidth : 240;
	  this._tracker = params.tracker ? params.tracker : null;
		
		this.fixed = params.fixed == undefined ? false : params.fixed;
		
		if (typeof this.attacher == 'string' || this.attacher instanceof String)
		{
      this.attacher = jyingo.get_by_instance(this.attacher);
		  if (this.attacher) this.attacher = this.attacher.get_position_element();
		}
		
		if (!this.attacher)
		{
			this._params = params;
			setTimeout(this.create_delegate(this.reinitialize), 30);
			return;
		}
		
		this.position = params.position ? params.position : 'auto';


 
		if (!this.fixed)
		{ 
			
		 if (this.interactive)
		 {
		  this.catch_inside('ev1', 'mouseover',  this.create_delegate(this.timed_show ), this.attacher, this.get_element());
		  this.catch_outside('ev1', 'mouseout', this.create_delegate( this.timed_hide ), this.attacher, this.get_element());
		 } else {
		  this.catch_inside('ev1', 'mouseover',  this.create_delegate(this.timed_show ), this.attacher);
		  this.catch_outside('ev1', 'mouseout', this.create_delegate( this.hide ), this.attacher);
		 }
		 
		 
		 if (this.clickremove == true)
		  this.delegate('click', this.hide, this.attacher);
    }
    
		if (this._element.parentNode)
		 this._element.parentNode.removeChild(this._element);
		 
		document.body.appendChild(this._element);
		
		
		this._content = this.$.find('.content');

		if (this._content.length)
		 this._content = this._content.get(0);
		else
		{
	   this._content = document.createElement('div');
	   this._content.className = 'content';
	   this._element.appendChild(this._content);
		}
		
		
		
		var dot = document.createElement('div');
	  this._element.appendChild(dot);
	  dot.className = 'jyingo_tooltip_arrow';
	  
		
		this._dot = dot;
		this.$dot = $(this._dot);

    this._span = null;
    if (params.text && params.text.length)
		 this.set_text(params.text);
		
		jyingo.tooltip_mgr.add(this);
		
		this.check_parent_timer = setInterval( this.create_delegate(this.check_parent), 1000 );
		
		if (this.fixed)
		 this.show();
		 
		this.timeout_timer = null;
		if (params.timeout && params.timeout > 0)
		{
			 this.timeout_timer = setInterval(this.create_delegate(this.do_timeout), params.timeout);
		}
		
	},
	
	do_timeout : function()
	{
		this.fixed = false;
		this.hide();
	},
	
	check_parent : function()
	{
		if (!this.attacher.parentNode)
		{
			this.dispose();
			return;
		}
	},
	
  change : function(k, v)
  {
  	 
  	 if (k == 'text')
  	 {
  	 	 this.set_text(v);
  	 }
	},
	
	dispose : function()
	{
		
		if (
		this.timeout_timer)
		{
			clearInterval(this.timeout_timer);
	  	this.timeout_timer = null;
		}
		
		if (this.check_parent_timer != null)
		{
		 clearInterval(this.check_parent_timer);
		 this.check_parent_timer = null;
		}
		
		if (this._element && this._element.parentNode)
		 this._element.parentNode.removeChild(this._element);
		 
		jyingo.tooltip_mgr.rem(this);
	},
	
	timed_show : function()
	{
		
		if (this._showing)
		{
			if (this._timed_interval)
			 clearInterval(this._timed_interval);
			 this._timed_interval = null;
			 return;
		}
		
		this._timed_showing = true;
		
		if (this._timed_interval == null)
		 this._timed_interval = setInterval(this.create_delegate(this.show), 250);
		
	},
	
	setup_parent_attacher : function()
	{
		
		var el = this.get_parent();
		
		if (!el)
		 return null;
		 
		

		return el.get_element();
	},
	
	set_visible : function(v)
	{
		
	},
	
	set_text : function(text)
	{
		
		if (text.length == 0)
		 return;
		
		if (!this._span)
		{
		
			var span = document.createElement('span');
			this._content.appendChild(span);
			span.className = 'txt';
			this._span = span;		
				
		}
		
		this._span.innerHTML = text;
		this.text = text;
		
	},
	
	render : function()
	{
		
		this.$.show();
		
		
		var dot_size = $(this._dot).outerWidth();
		var pos = $(this.attacher).offset();
		
		var size = { width: $(this.attacher).outerWidth(), height: $(this.attacher).outerHeight() };
		
		var w = $(this._content).outerWidth(), h = $(this._content).outerHeight();
    if (w > this.maxwidth)
    {
    	 w = this.maxwidth+14;
    	 $(this._content).css({ width : this.maxwidth });
    	 this.$.css({ width : this.maxwidth+12 });
    	 h = $(this._content).outerHeight();
    } 

		
		var position = '';
		this.$.hide();
		

		// calcola posizione migliore in automatico
		if (this.position == 'auto')
		{
			
			if (pos.left+size.width<= 250 && pos.top < $(window).height()*0.8)
			 position = 'right';
			else if (pos.top - dot_size - h >= 0 && pos.left + w/2 + 16 + size.width/2 < $(window).width())
			 position = 'top';
			else if (pos.top + dot_size + h + size.height < $(window).height() && pos.left + w/2 + 16 + size.width/2  < $(window).width())
			 position = 'bottom';
			else if (pos.left + w + dot_size < $(window).width())
			 position = 'right';
			else
			 position = 'left';
			
		} else {
		   position = this.position;	
		}
		
		var _left = 0, _add_left = 0;
		if (position == 'top' || position == 'bottom')
		{
			
			 _left = Math.ceil(pos.left + size.width/2 - w/2);
			 if (_left < 0)
			 {
			   _add_left = _left;
			   _left = 0;	
			 }
		}
		 
		switch (position)
		{
			case 'top':
			
			
			
			 this.$.css({top:Math.ceil(pos.top-h-7)+'px', left:_left+'px'});
			 this.$dot.css({top:Math.ceil(h-1)+'px', left:Math.ceil(this.$.offset().left+w/2-13/2 + _add_left)+'px'});
			 this.$dot.get(0).className = 'jyingo_tooltip_arrow jyingo_tooltip_arrow_bottom';
			break;
			
			case 'left':
			 this.$.css({top:Math.ceil(pos.top + size.height/2 - h/2)+'px', left:Math.ceil(pos.left - w - 5)+'px'});
			 this.$dot.css({top:Math.ceil(h/2 - 6)+'px', left:Math.floor(this.$.width())+'px'});
			 this.$dot.get(0).className = 'jyingo_tooltip_arrow jyingo_tooltip_arrow_right';	
	  	break;
	  	
			case 'right':
			 var _top = Math.ceil(pos.top + size.height/2 - h/2);

			 var _diff = 0;
			 if (_top < 0) { _diff = _top; _top = 0; }
			 
			 this.$.css({top:_top+'px', left:Math.ceil(pos.left + size.width - 5)+'px'});
			 this.$dot.css({top:Math.ceil(h/2 - 6 + _diff)+'px', left:-13});
			 this.$dot.get(0).className = 'jyingo_tooltip_arrow jyingo_tooltip_arrow_left';	
	  	break;
	  	
			case 'bottom':
			 this.$.css({top:Math.ceil(pos.top+dot_size+size.height-7)+'px', left:_left+'px'});
			 this.$dot.css({top:Math.ceil(-6)+'px', left:Math.ceil(this.$.offset().left+w/2-13/2 + _add_left)+'px'});
			 this.$dot.get(0).className = 'jyingo_tooltip_arrow jyingo_tooltip_arrow_top';
			break;			
			
			
		}
		
		return position;
	},
	
	show : function()
	{
		
		if (this._timed_interval != null)
		{
			clearInterval(this._timed_interval);
			this._timed_interval = null;
		}
		
		if (this._showing)
		 return;
		 
		if (!this.attacher.parentNode)
		{
			this.dispose();
			return;
		}
		
		jyingo.tooltip_mgr.showing(this);
		
		this._showing = true;

    this.render();
		var pos = this.render();
	  
	  if ( this._tracker)
	  {
	  	
			trackmgr.event(this._tracker[0],this._tracker[1]);
	  }

	  
	  this.$.css({ opacity: 0, display: 'block' })
	  var _l = this.$.position().left, _t = this.$.position().top;
	  
	  switch(pos)
	  {
	    case 'bottom':
	     this.$.css({ top : _t - 12 });
	     this.$.animate({ top : _t, opacity: 1 }, 150);
	    break;

	    case 'left':
	    
	     this.$.css({ left : _l + 12 });
	     this.$.animate({ left : _l, opacity: 1 }, 150);
	     
	    break;	
	    
	    case 'right':
	    
	     this.$.css({ left : _l - 12 });
	     this.$.animate({ left : _l, opacity: 1 }, 150);
	     
	    break;	
	    
	    case 'top':
	    
	     this.$.css({ top : _t + 12 });
	     this.$.animate({ top : _t, opacity: 1 }, 150);
	     
	    break;	
	  }
	  
	},
	
	timed_hide : function()
	{
		
		if (this._timed_interval == null)
		 this._timed_interval = setInterval(this.create_delegate(this.hide), 500);
	},
	
	hide : function()
	{
    

		if (this.fixed)
		 return;


		if (
		this.timeout_timer)
		{
			clearInterval(this.timeout_timer);
	  	this.timeout_timer = null;
		}
		
		
		this._timed_showing = false;
		if (this._timed_interval != null)
		{
			clearInterval(this._timed_interval);
			this._timed_interval = null;
		}
		
		if (!this._showing)
		 return;
		
		this.$.animate({ opacity: 0 }, 'fast', function() { $(this).hide(); });
		
		this._showing = false;
	}
	
	
	
};