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
 
jyingo.dropitem = function(params)
{
	
//	var element = document.createElement('div');
	
	
	
//	this._element = element;
	
	

  
};

jyingo.dropitem.prototype = {
	
	initialize : function(data)
	{
	
	  this.text = data['text'];
	  this.icon = data['icon'];
	  this.href = data['href'];
	  this.value = data['value'];
	  this.paging = data['paging'];
	  this.mode = data['mode'];
	  this.checked = data['checked'];
	  this.target = data['target'];
	  this.optgroup = data['optgroup'];
	  this.script = data['script'];
	  
	  this._element.className = 'jyingo_dropdown_menu_item';
    if (this.mode == 'container') $(this._element).addClass('container');
	  
	  if ( document.getElementById(this.get_instance()) )
	  {
	 // 	this._element = document.getElementById(this.get_instance());
	 //   this._element.parentNode.removeChild(this._element);
	 //   this._element.className = 'jyingo_dropdown_menu_item';
	    
	    if (this._element.childNodes.length)
	     this.mode = 'container';
	    
	  } else {
	 	//  this._element.id = this.get_instance();	
	  }
	 // this.$ = $(this._element);
	  
	  this.delegate('ontouchstart' in document.documentElement ? 'touchstart' : 'click', this.click);
	  if ('ontouchstart' in document.documentElement)
	  {
	      	$(this.$).click(function (event) {
	      		 
	      		 event.stopPropagation();
	      		 jyingo.cancel_event(event);
	      		 
	      	});
	  }
	  
//	  this.delegate('click', this.click);
	  this.delegate('mousedown', this.mousedown);
	  
	  if (this.mode != 'container')
	  {
		  var img = document.createElement('div');
		  this._element.appendChild(img);
		  this._element.img = img;
		  this._element.img.className = 'icon';
		  
		  var span = document.createElement('span');
		  this._element.appendChild(span);
		  this._element.span = span;
		}
		
		if (this.mode == 'container')
		{
			this.content = data['content'];
		}

	  this.render();
	  
	  
	  
	 // this.get_parent().get_element().appendChild(this.get_element());
	  
	  
	},
	mousedown : function(event)
	{
	 
	  var ev = jyingo.eventize(event);
	  jyingo.cancel_event(ev);
	  return false;
		
	},
	
	uncheck : function()
	{
		 this.checked = false;
		 this.render();
	},
	
	click : function()
	{
		 
		if (this.mode == 'script')
		{
			if (typeof this.script == 'string')
			 eval(this.script);
			else
			 this.script();
		}
		
	  if (this.mode == 'checkbox' || this.mode == 'checkbutton')
	  {
	  	this.checked ^= true;
	  	this.render();
	  	this.get_parent()._has_changed(this);
	  	return;
	  }
	  
	  if (this.mode == 'href')
	  {
	  	
	  	if (this.paging)
	  	{
	  		
	  		
			
				if (jyingo.get_window_manager())
				 jyingo.get_window_manager().set_hash_position(this.href);
				 
	      this.get_parent().hide();
	  		
	  	} else {
		  	
		  	if (this.target == '_blank')
		  	 window.open(this.href);
		  	else
		  	 location.href = this.href;
		  }
	  }
	  
	  if (this.mode == 'checkbutton' || this.mode == 'button')
	  {
	  	this.get_parent().pressed(this);
	  }
	  
	  if (this.mode == 'select')
	  {
	  	this.set_selected();
	  }
	  this.get_parent().hide();
	},
	
	set_selected : function()
	{
		this.get_parent().select(this);
	},
	
	get_value : function()
	{
		return this.value;
	},
	
	unselect : function()
	{
		this.selected = false;
	},
	
	get_data : function()
	{
		return this.checked;
	},

	
	is_first : function(value)
	{
		if (value)
		{
			this.$.addClass('jyingo_dropdown_menu_item_first');
		} else {
			this.$.removeClass('jyingo_dropdown_menu_item_first');
		}
	},
	
	render : function()
	{
		
		if (this.mode == 'container')
		{
			
			if (this.content)
			{
			 this._element.innerHTML = this.content;
			}
			return;
			
		}
		
		if (this.mode == 'separator')
		{
			
			this._element.className = 'jyingo_dropdown_menu_item_separator';
			return;
		} 
		
	  this._element.span.innerHTML = this.text;
		if (!this.icon || this.icon == undefined)
		{
			this._element.img.style.background = '';
		} else {
			this._element.img.style.backgroundImage = 'url('+this.icon+')';
		}
		
		if (this.mode == 'checkbox' || this.mode == 'checkbutton')
		{
			
			if (this.checked == true)
			{
				this._element.img.style.backgroundPosition = 'left bottom';
			} else {
				this._element.img.style.backgroundPosition = 'left top';
			}
			
		}
		
		

	},
	
	change : function(key, value)
	{
		
		
	},
	set_visible : function(v)
	{
		
	},
	
	
	
	
};
 
jyingo.dropdown = function(params)
{
 	
 	this.button = false;
 	this.selected = false;
 	this.attacher = null;
 	
 	this.allow_postback = false;
 	
 	this.output = new Array(0, 0);
 	this._rowfix = null;
 	this._label = true;
 	this._eat = false;
  
};

jyingo.dropdown.prototype = {
	
	initialize : function(data)
	{
		
		this.onclick = data.onclick ? data.onclick : null;
		
		
		if (this._element.parentNode)
	  this._element.parentNode.removeChild(this._element);
	  document.body.appendChild(this._element);
		
		$(this._element).addClass('jyingo_dropdown_menu');
		if (data['class']) $(this._element).addClass(data['class']);
	 	//this._element.className = (data['class'] ? data['class'] : 'jyingo_dropdown_menu');

	  
	  this.hide_handler = (data['onhide'] ? data['onhide'] : null);
	  
	  this.offsetX = (data['offsetX'] ? data['offsetX'] : 0);
	  this.offsetY = (data['offsetY'] ? data['offsetY'] : 0);
	  
	  this.minwidth = data.minwidth ? data.minwidth : 0;

	  
	  this.showing = false;
	  
	  this.catch_outside('ev1', 'mouseup', this.create_delegate(this.hide), this._element);
	  
	  this.delegate('resize', this.resize, window);
	  
	  if (data['position'] != undefined) 
	  {
	   this.position = data['position'];
	  } else {
	   this.position = 'left';
	  }
	  
	  if (data['selected'] != undefined)
	  {
	    this.selected = jyingo.get(data['selected']);	
	  }
	  
	  if (data['label'] === false)
	   this._label = false;
	  
	  
	  if (data['postback'] != undefined)
	   this.allow_postback = data['postback'];
	  
	  if (data['attacher'] != undefined)
	  {
	  	this.attacher = data['attacher'];
	    this.delegate('click', this.button_click, this.attacher);
	  }
	  
	  if (data['button'] == true)
	  {
	   
	    this.button = true;
	    this.button_instance = data['button_instance'];
	    
	    
	    this.button_obj =    $('#'+this.button_instance).get(0);
	    
	    
	    if (this.button_obj.tagName.toUpperCase() != 'A')
	    {
	    	
	    	var obj = document.createElement('a');
	    	this.button_obj.parentNode.insertBefore(obj, this.button_obj);
	    	
	    	this.button_obj.id = '';
	    	this.button_obj.parentNode.removeChild(this.button_obj);
	    	
	    	this.button_obj = obj;
	    	this.button_obj.id = this.button_instance;
	    
	    }
	    
	    
	    var img = document.createElement('img');
	    this.button_img = img;
	    
	    this.button_obj.href = '#';
	    
	    //this.delegate('mouseout', this.button_up, this.button_obj);
	    this.delegate('ontouchstart' in document.documentElement ? 'touchstart' : 'click', this.button_down, this.button_obj);
	    if ('ontouchstart' in document.documentElement)
	    {
	      	$(this.button_obj).click(function (event) {
	      		 
	      		 event.stopPropagation();
	      		 jyingo.cancel_event(event);
	      		 
	      	});
	    }
	    //this.delegate('mouseup', this.button_up, this.button_obj);
	    
	    
	//    this.delegate('click', this.button_click, this.button_obj);
	    
	    this.button_obj.appendChild(img);
	    this.button_obj.img = img;
	    this.button_obj.img.style.display = 'none';
	    
	    var span = document.createElement('span');
	    this.button_span = span;
	    
	    this.button_obj.appendChild(span);
	    this.button_obj.textElement = span;
	    
	    if (this._label == false)
	     this.button_obj.img.style.paddingRight='0';
	    

	    
	    
	    
	    this.button_obj.className = data['layout'];
	    
	    this.button_text     = data['text'];
	    this.button_icon     = data['icon'];
	    this.button_layout   = data['layout'];
	    this.attacher = this.button_obj;
	    
	   
	    
	    this.render_button();

	  }
	  
	  this.delegate('contextmenu', this.context);
	  	 	
	},
	
	context : function(ev)
	{
		var s = jyingo.eventize(ev);
		jyingo.cancel_event(s);
		return false;
	},
	
	set_visible : function(b)
	{
		
		this._visible = b;
		
		this._element.style.display = (b ? '' : 'none');
		if (this.button_obj)
		{
	
			this.button_obj.style.display  = (b ? '' : 'none');
		}
		
		
	},
	
	create_element : function()
	{
		return document.createElement('div');
	},
	
	resize : function()
	{
		if (this.showing == true)
		this.show();
	},
	
	add : function(text, key, icon)
	{
		var obj = this.load(jyingo.dropitem, {
			
			icon : icon,
			text:text,
			value:key,
			mode:'select'
			
		});

		return obj;
	},

	add_button : function(text, key, icon)
	{
		var obj = this.load(jyingo.dropitem, key,  {
			
			icon : icon,
			text:text,
			value:key,
			mode:'button'
			
		});

		return obj;
	},
	
	get_data : function()
	{
		
		if (!this.selected)
		{
			this.output[0] = null;
		} else {
			this.output[0] = this.selected.get_instance();
		}
		
		return this.output;
		
	},
	
	select : function(what, skip_postback)
	{
		 

		if (skip_postback == undefined) 
		 skip_postback = false;
		 
		if (what.initialize == undefined)
		  what = jyingo.get_by_instance(what);
		  
		if (this.selected == what)
		 return;
		
		this.selected = what;
		this.render_button();
		
		if (this.allow_postback == true && skip_postback == false)
		 this.event('_change');
      
		
	},
	
	_has_changed : function(obj)
	{

		
		if (obj.optgroup == undefined || !obj.optgroup)
		{
			
		} else {
			
						
			if (!obj.checked)
			 return;
			
			var items = this.get_children(jyingo.dropitem);
			for (var item in items)
			{
				 var _obj = items[item];
				 if (_obj != obj && obj.optgroup === _obj.optgroup)
				   _obj.uncheck();
			}			
		}
		

		
		
		if (this.allow_postback == true )
		 this.event('_checked_changed');
		 
	},
	
	pressed : function(what)
	{
		
		if (this.onclick != null)
		{
			 this.onclick(what);
		} else {
		 this.output[1] = what.get_instance();
		 this.event('_click');
	  }
	},
	
	hide : function() {
		 
		 
		 if (this._eat)
		 {
		  this._eat = false;
		  return;	
		 }
		 
		 if (this.showing == false)
		  return;
		  
		 this.showing = false;
		  
		 $(this.button_obj).removeClass(this.button_layout+'_active');
		
		 
		 if (this.hide_handler != null)
		 {
		  this.hide_handler();
		  this.$.hide();
		 } else {
		   this.$.fadeOut('fast');	
		 }
		 
	},
	
	button_up : function()
	{
		if (this.is_mouse_down != true) 
		 return;
		
		this.is_mouse_down = false;
		
		$(this.button_obj).removeClass(this.button_layout+'_active');
	},
	
	parse_position : function(x,y,x2,y2,ew,eh,w,h)
	{
		
		var pos = {};
		
		if (this.position.indexOf('left') != -1) 
		 pos.formula_x = x;
		
		if (this.position.indexOf('right') != -1) 
		 pos.formula_x = x2;		
		 
		if (this.position.indexOf('right-width') != -1) 
		 pos.formula_x = x2-w;	
		 
		if (this.position.indexOf('center') != -1) 
		 pos.formula_x = x+ew/2-w/2;			

		if (pos.formula_x == undefined)
		 pos.formula_x = x;			
		
		
		if (this.position.indexOf('top') != -1) 
		 pos.formula_y = y-eh;
		
		if (this.position.indexOf('middle') != -1) 
		 pos.formula_y = y+eh/2-h/2;		

		if (this.position.indexOf('bottom') != -1) 
		 pos.formula_y = y2;			

		if (this.position.indexOf('top-height') != -1) 
		 pos.formula_y = y-h;			
		
		if (pos.formula_y == undefined)
		 pos.formula_y = y2;			
		 
		 
		pos = { left:(pos.formula_x), top:(pos.formula_y) };
		 
		if (pos.left < 0) pos.left = 0;
		if (pos.top < 0) pos.top = 0;
		
		return pos;
		
	},
	
	button_down : function(event)
	{
		var ev = jyingo.eventize(event);
		
		this.is_mouse_down = true;
		
		$(this.button_obj).addClass(this.button_layout+'_active');
		this.button_click();
		
		jyingo.cancel_event(ev);
		
		if ('ontouchstart' in document.documentElement)
		 this._eat = true;
		
		return false;
	},
	
	button_click : function()
	{
		
		this.show();
		
	},
	
	get_position_element : function()
	{
		 return this.button_obj;
	},
	
	render_button : function()
	{
		
		if (!this.button)
		 return;
		
		if (this.selected == false)
		{
			
			  this.button_span.innerHTML = (this._label == true ? this.button_text : '');// + '<img src="/jyingo/jyingo.dropdown/d1.gif" class="drop_icon"/>';
			  if (this.button_icon)
			  {
			  	
			  	this.button_obj.img.src = this.button_icon;
			  	this.button_obj.img.style.display = '';
			  	
			  } else {
			  	this.button_obj.img.style.display = 'none';
			  }                       
			
		} else {
			
			this.button_span.innerHTML = (this._label == true ? this.selected.text : '');// + '<img src="/jyingo/jyingo.dropdown/d1.gif" class="drop_icon"/>';
			if (this.selected.icon)
			{	
			  this.button_obj.img.src = this.selected.icon;
			  this.button_obj.img.style.display = '';	
			} else {
			  this.button_obj.img.style.display = 'none';
			}   			
		}
		
	},
	
	show : function(event)
	{
	  this.showing = true;
	  
	  if (this.button)
	  {
		 $(this.button_obj).addClass(this.button_layout+'_active');
		}
		
		this.create_menu();
		
		var x, y, x2, y2, w, h;
		
		w = $(this.get_element()).outerWidth();
		h = $(this.get_element()).outerHeight();
		
		var minw = 0;
		
		var ew = 0, eh = 0;
		
		

		if (this.attacher)
		{
		
		  var el = $(this.attacher);
      var pos = el.offset();
      
      x =  pos.left; 
      y =  pos.top - 1 ;
       
      
       
      x2 = pos.left + el.outerWidth() + this.offsetX;
      y2 = pos.top + el.outerHeight() + this.offsetY - 1;
      
      minw = $(this.attacher).outerWidth();
	  
	    ew = el.outerWidth();
	    eh = el.outerHeight();
	  
	  } else {
	  	
	  	coords = jyingo.get_mouse_coords();
	  	
	  	x = coords.x;
	  	y = coords.y;
	  	
	  	x2 = x + this.offsetX + x;
	  	y2 = y + this.offsetY - 1;
	    	
	  	 
	  	minw =  this.minwidth;

		  if (this.$.outerWidth() < minw)
		  {
		   this.$.css({width:minw+'px'});
	  	 w = minw;
	  	}
	  }
	  
	 
	  
	  /*
	  if (this.$.outerWidth() < minw)
	   this.$.css({width:minw+'px'});
	  else
	   this.$.css({width:this.$.outerWidth()+'px'});
	  */
	  var pos = this.parse_position(x, y, x2, y2, ew, eh,
	                                w, h);
	  

	  if (this._rowfix)
	  {
	  	
	  	var l = $(this.button_obj).offset().left - pos.left;
	  	
	  	$(this._rowfix).css({left:l+'px', width : $(this.button_obj).width() + 'px'});
	  }
	  
	  this.$.css({left:pos.left+'px', top:pos.top+'px', display:'block'});
	  
    
	},
	
	
	
	create_menu : function()
	{
		
		var children = this.get_children_ordered();
	  
	  var is_first = true, first_object = null;
	  
	  for (var index in children)
	  {
	  	var instance = children[index].get_instance();
	  	var obj = children[index];
	  	
	  	
	  	if (is_first)
	  	 first_object = obj.get_element();
	  	
	  	obj.is_first(is_first);
	  	is_first = false;
	  	
	    var element = obj.get_element();
	    
	    if (element.parentNode)
	     element.parentNode.removeChild(element);
	    
	    this._element.appendChild(element);
	    
	    
	  	
	  }
	  
	  if (this._rowfix == null && this.button)
	  {
	  	this._rowfix = document.createElement('div');
	  	this._rowfix.className = 'rowfix';
	
	  	this._element.insertBefore(this._rowfix, first_object);
	  }
	  


		
		
	},
	
	change : function(key, value)
	{
		if (key == 'text')
		{
			 this.button_text = value;
			 this.render_button();
		}
		
	}
	
	
	
	
};