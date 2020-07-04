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
 
var cached_fb_page_infos = null;
jyingo._popup_showing_count = 0;
jyingo._popup_stack = new Array();
 
jyingo.popupbar = function(params)
{
	 
}
 
jyingo.popupbar.prototype = {
	
	initialize : function(params) {
	
	}

}
 
jyingo.popupbutton = function(params)
{
	
	
}

jyingo.popupbutton.prototype = {
	
	initialize : function(params) {
		
		
		
		this.default = params.default ? params.default : false;
		this.cancel = params.cancel ? params.cancel : false;
		this.close  = params.close ? params.close : false;
		this.icon   = params.icon  ? params.icon : null;
		
		$(this._element).addClass('jyingo_popupbutton');
		this._element.innerHTML = params.text ? params.text : null;
		
		if (this.icon)
		{
			this._element.innerHTML = '<img src="' + this.icon + '" />';
			this._element.className = 'closebtn';
		}
		
		
		this.delegate('mousedown', this._down);
		this.delegate('mouseup', this._up);
		this.delegate('mouseout', this._up);
		this.delegate('click', this._click);
		
		this.delegate('keydown', this._keydown, document);
		this.delegate('contextmenu', this._context);
		
		this._element.onselectstart = Function("return false");

		
    if (params.default == true)
     this.$.addClass('default');
		
    if (params.cancel == true)
     this.$.addClass('cancel');
    
		
	},
	
	_keydown : function(event)
	{

    var ev = jyingo.eventize(event);

    var keyCode = ev.keyCode ? ev.keyCode :  ev.which;
  
    
    if (keyCode == 27)
    {
    	 if (this.cancel)
    	  this._click();
    }
    
    return true;		
	},	
	_context : function(event)
	{
		
		jyingo.cancel_event(jyingo.eventize(event));
		return false;
		
	},
	
	_click : function()
	{
		
		if (this._disabled)
		 return;
		
		if (this.cancel)
		{
			 
			 var parent = this.get_parent();
			 if (parent._will_autoremove_after_cancel())
			  return;
			
		}
		
		this.event('click');

		
	},
	
	_down : function()
	{
		
		this.$.addClass('active');
	},
	
	_up : function()
	{
		this.$.removeClass('active');
	},
	
	change : function(k, v)
	{
		if (k == 'text')
		 this._element.innerHTML = v;
	}
	
	
};
 
 
var _jyingo_popup_transition_style = 'slide-fade';

jyingo.extend('popup', function(self, settings, shared) {
	
	if (!shared.popup_stack)
	{
		 shared.popup_stack = [];
     shared.popup_animating = false;
  }
  
	
	self.prototype = {
		
		initialize : function(params) {
			
			this._hide_after_show = false;
		  this._going_to_show = false;
		  this._showing = false;
		  this.autoremove = false;
		  this._showing_index = -1;
			this._fix_popup_bar = false;
			this._to_remove_content = null;
			this._transition = _jyingo_popup_transition_style;
			
			var title = document.createElement('h1');
			
			this._title = title;
			
			var tit = params.title ? params.title : '';
			this._title.innerHTML = tit;
			
			if (!tit.length)
			 $(this._title).css({ display : 'none' });
			
			this.mask_class = params.mask ? params.mask : 'jyingo_popup_mask';
	    
	    this.autoremove = params.autoremove ? params.autoremove : false;
	    
			this._container = document.createElement('div');
			
			var s = 0;
	    this._show_nr = 0;
	    this.__active = false;
			
			while (this._element.childNodes.length - s)
			{
			  
			  var el = this._element.childNodes[0];
			  
			  if (el.jyingoObject instanceof jyingo.popupbutton)
			  {
			   s++;
			   continue;
			  }
			  el.parentNode.removeChild(el);
			  
			  this._container.appendChild(el);
			  
			  	
			}
			
			var popup_container_element = document.createElement('div');
			popup_container_element.className = 'popupcontainer';
			this._element.appendChild(popup_container_element);
			
			popup_container_element.appendChild(title);
			popup_container_element.appendChild(this._container);
			
			this._container.className = 'container';
			
			
			var _bottombarContainer = document.createElement('div');
			this._bottombarContainer = _bottombarContainer;
			
			popup_container_element.appendChild(_bottombarContainer);
			_bottombarContainer.className = '_bottombar_container';
			
			this._bottombar = document.createElement('div');
		  this._bottombar.className = '_bottombar';
		  _bottombarContainer.appendChild(this._bottombar);
			
			this._bar = document.createElement('div');
			_bottombarContainer.appendChild(this._bar);
			
			this._bar.className = 'bar';
			
			var sclr = document.createElement('div');
			sclr.className = 'clr';
			_bottombarContainer.appendChild(sclr);
			
			this.popup_container_element = popup_container_element;
			
			this.delegate('resize', this._resize, window);
			this.catch_outside('ev1', 'mousedown', this.create_delegate(this._outclick), this._element);
			
			this._element._get_frame = this.create_delegate(this._get_frame);
			
			if (this._element.parentNode)
			{
			 var classes = this._element.parentNode.className;
			 classes = classes.replace('jyingo_window','');
			 classes = classes.replace('window_popup','');
			 
			 this._element.className += ' '+classes;
			 this._element.parentNode.removeChild(this._element);
			}
			
			if (!this.$.hasClass('jyingo_popup'))
			 this.$.addClass('jyingo_popup');
			
		  document.body.appendChild(this._element);
			
	
			this.fb_del1 = null;
			
		//	this._update_timer = setInterval( this.create_delegate(this._resize), 100 );
	
	
		},
		
		_get_frame : function()
		{
			
			var result = this.$.offset();
			
			result.width = this.$.outerWidth();
			result.height = this.$.outerHeight() + $(this._bottombarContainer).outerHeight();
			
			return result;
			
		},
		
		add_button : function(params)
		{
			 
			 var el = this.load(jyingo.popupbutton, params);
	  	 $(this._bar).append(el.get_element());
			 
			 
		},
		
	  append : function(el)
	  {
	  	 $(this._container).append(el);
	  },
		
		change : function(k, v)
		{
			if (k == 'width')
			 this.$.css({width : (parseInt(v)+4) + 'px'});
			
			if (k == 'title')
			{
			  $(this._title).css({ display : (v.length ? '' : 'none') });
				this._title.innerHTML = v;
			}
		},
		
		dispose : function()
		{
			if (this.fb_del1 != null)
			{
				clearInterval(this.fb_del1);
				this.fb_del1 = null;
			}
			
			clearInterval(this._update_timer);
		},
		
		_outclick : function()
		{
			
			if (!this._showing)
			 return;
			
			if (jyingo._popup_showing_count == this._showing_index)
			{
				
				
				var btns = this.get_children(jyingo.popupbutton);
	
				for (var i in btns)
				 if (btns[i].cancel) btns[i]._click();
	
			}
			
		},
		
		_showMask : function()
		{
			
			if (jyingo._showing_mask == true)
			 return;
			 
			var el = document.createElement('div');
			el.className = this.mask_class;
			document.body.appendChild(el);
			
	
			
			$(el).css({top:0, left:0, width : $(window).width() + 'px', height : $(document).height() + 'px'});
			
			jyingo._showing_mask = true;
			jyingo._showing_mask_div = el;
	
			
		},
		
		addChild : function(child)
		{
			
			this._container.appendChild(child);
			
		},
		
		insertBefore : function(obj, before)
		{		
			  if (before == 0)
				{
						
						if (this._container.childNodes.length == 0)
						 this._addChild(obj);
						else
						 this._container.insertBefore(obj, this._container.childNodes[0]);
						
				}	else {
					
					this._container.insertBefore(obj, before.get_element());
					
				}		
			
		},
		
		canvas_scroll_top : function(mode_def)
		{
			 
			 
			 if (cached_fb_page_infos == null)
			  return $(document).scrollTop();
			 else
			 { 
			 	
			 	if (mode_def === true)
			 	 return 0;
			 	 
	       return cached_fb_page_infos.top();
	       
	
			 	
			 }
		},
		 
		canvas_window_height : function()
		{
			
			 
			if (cached_fb_page_infos == null)
			  return jyingo.get_window_height();
			 else
			 {
	
				 return cached_fb_page_infos.height();
			 	
			 }
		},
		
		
		_resize : function()
		{
			
			if (!this._showing)
			 return;
			
			$(jyingo._showing_mask_div).css({top:0, left:0, width : $(window).width() + 'px', height : $(document).height() + 'px'});
		  
		   var w = this.$.width()+10,
			     h = this._get_frame().height;
			 
			var top = this.canvas_window_height()/2 - h/2 - 150;
			if (top < 150) top = 150;
			if (  this.canvas_window_height() - (top + h) < 150 )
			 top = this.canvas_window_height()/2 - h/2; 
		  this.$.css({position:'fixed'});
			if (h+top > this.canvas_window_height() || cached_fb_page_infos)
			{
			 		 top = this.canvas_scroll_top() + this.canvas_window_height()/2  - h/2;
			 		 if ( top < 0 ) top = 20 + this.canvas_scroll_top();
			     this.$.css({position:'absolute'});
			}
			
			var _left =  (jyingo.get_window_width()- jyingo.get_window_offsetW())/2 - w/2 + jyingo.get_window_offsetX();
	
			 
			  this.$.css({left : _left,
			  	top : top + 10});
	
		},
		
		set_visible : function(v)
		{ 
		  
		  if (this._visible == true && v == false)
		  {
		  	this.$.css({display : 'none'});
		  }
		  
			this._visible = v;
		},
		
		_will_autoremove_after_cancel : function()
		{ 
			 this.hide();
			 if (this.autoremove == true)
			   return true;

			 return false;
		},
		
		hide : function()
		{
			
			if (!this._showing && this._going_to_show == true)
			 this._hide_after_show = true;
				
			if (!this._showing)
			 return;
			
      if (this.beforehide) this.beforehide(this);
		 	jyingo._customcall('popupHide', this);
			
		//	if (jyingo.get_window())
		//	 jyingo.get_window().pop_state();
			
			jyingo._popup_showing_count--;
	
			if (jyingo._popup_showing_count == 0)
			{
				this._hideMask();
			} else {
				jyingo._showing_mask_div.style.zIndex = jyingo._popup_showing_count + 199;
			}
			
			this._showing = false;
			this.$.css({display : 'block'});
	    this.$.find('div.__ns_scrolled').niceScroll().remove();
			
			if (this._transition == 'slide')
				this.t3d.animate({ y : jyingo.get_window_height() - this._get_frame().top  + 60 }, 700,  this.create_delegate(this._after_hiding));
			else if (this._transition == 'fade')
			  this.t3d.animate({ opacity : 0 }, 250,  this.create_delegate(this._after_hiding));
			else if (this._transition == 'slide-fade')
				this.t3d.animate({ opacity : 0, y : - this._get_frame().height/2 }, 500,  this.create_delegate(this._after_hiding));
			else
			{
				$(this.get_element()).css({ display : 'none' });
				this.immediate(this._after_hiding);
			}
			
	    jyingo._popup_stack.pop();
	    
	    if (jyingo._popup_stack[jyingo._popup_stack.length-1])
	     jyingo._popup_stack[jyingo._popup_stack.length-1]._activate();
	    else if (shared.popup_animating == false && shared.popup_stack.length)
			{
				
				var popup = shared.popup_stack.pop();
				popup.show();
			}	    
	
			
		},
		
		_after_hiding : function()
		{
			 if (this.onhide) this.onhide(this);
			 if (this.autoremove == true)
			  this._do_autoremove();
			 else
			 {
			 	this.$.css({ display : 'none' });
			  this.call(function(){},'_afterhide');
			 }
			 
			 if (this._to_remove_content)
			 {
			   $(this._to_remove_content).remove();	
			   this._to_remove_content = null;
			 }
		},
		 
		_do_autoremove : function()
		{
		  this.call(function() { }, '_autoremove');		
		  this.remove();
		},
		
		_hideMask : function()
		{
			
			 
			 
			 
			 if (jyingo._showing_mask)
			 {
			 	 
			 	 jyingo._showing_mask = false;
			 	 document.body.removeChild(jyingo._showing_mask_div);
			 	 jyingo._showing_mask_div = null;
			  	
			 	
			 }
			
		},
		
		_activate : function()
		{
			 
			if (this.__active)
			  return;
			
			this.__active = true;
			this.$.addClass('active');
			
			if (this._transition != 'slide')
			 $(this.get_element()).css({ opacity : 1 });
			else
			 this.t3d.animate({ y : 0 }, 500);
		},
		
		_deactivate : function()
		{
			
			if (!this.__active)
			 return;
			
			this.__active = false;
			this.$.removeClass('active');
			
			if (this._transition != 'slide')
			 $(this.get_element()).css({ opacity : 0.5 });
			
			else
			 this.t3d.animate({ y :  - (this._get_frame().top + this._get_frame().height) }, 700);
		},
		
		dispose : function()
		{
			
			if (this._showing)
			{
			 this._to_remove_content = this._element;
			 this._element = document.createElement('div');
			 this.hide();
			}
		},
		
		show : function(attacher)
		{ 
			
			if (shared.popup_animating == true)
			{
				shared.popup_stack.push(this);
				return;
			}
			
			if (this._showing)
			 return;
			 
			
			
			/*setTimeout(function() {
				if (jyingo.get_window()) {
					jyingo.get_window().push_state();
					jyingo.get_window().hide();
			  }
			}, 0);
			*/
	
		 	jyingo._customcall('popupShow', this);
			
			if (jyingo._popup_showing_count == 0)
			{
				this._showMask();
			}
			
			
			if (this._fix_popup_bar == false)
			{
				var btmbar = this.get_children(jyingo.popupbar, true);
				for (var i in btmbar)
				{
					
				
		
					var s = btmbar[i].get_element();
					
					if (s.parentNode)
					s.parentNode.removeChild(s);
					
					this._bottombar.appendChild(s);
				}
			  this._fix_popup_bar = true;
	
			  this._bar.innerHTML =  '';
		
				var btns = this.get_children_ordered(jyingo.popupbutton);
		
				
				for (var i = 0; i < btns.length; i++)
				{
					 
					 var btn = btns[i];
					 
					 if (btn.get_element().parentNode)
					  btn.get_element().parentNode.removeChild(btn.get_element());
					 
					 if (btn.close == true)
					  this._element.appendChild(btn.get_element());
					 else
					  this._bar.appendChild(btn.get_element());
					 
					 
				}
	
			}
			
			
			jyingo._popup_showing_count++;
			
			this._showing_index = jyingo._popup_showing_count;
			
	
			this._element.style.zIndex = jyingo._popup_showing_count + 200;
			jyingo._showing_mask_div.style.zIndex = jyingo._popup_showing_count + 199;
			
			
			
	
			
			this.$.show();
			/*this.$.css({overflow : 'visible'});
			*/
			
		  var w = this.$.width()+10,
				  h = this._get_frame().height;
		 
		  
		/*  this.$.css({overflow : 'hidden'});
			*/
			
			this.$.hide();		
	
			var top = this.canvas_window_height()/2 - h/2 - 150;
			if (top < 150) top = 150;
			if (  this.canvas_window_height() - (top + h) < 150 )
			 top = this.canvas_window_height()/2 - h/2; 
		  this.$.css({position:'fixed'});
		  
		  if (this._transition == 'slide-fade' && (attacher == undefined || !attacher))
		  	top = 50;
		  
		  
			if (h+top > this.canvas_window_height() || cached_fb_page_infos )
			{
			 		 top = this.canvas_scroll_top() + this.canvas_window_height()/2  - h/2;
			 		 if ( top < 0 ) top = 20 + this.canvas_scroll_top();
			     this.$.css({position:'absolute'});
			}
			
	
			
			// extendable
			var _left = (jyingo.get_window_width()- jyingo.get_window_offsetW())/2 - w/2 + jyingo.get_window_offsetX();
			
	
				  

				 
			if (this._show_nr++ == 0)
			{
				 var _initial_offset = jyingo.get_window_height() - top + 60;
				 this.$.css({ left : _left, top : top + 10 });
				 
				 
				 if (attacher == undefined || !attacher)
			   {
					 if (this._transition == 'slide')
					 	this.t3d.set({ y : _initial_offset });
					 if (this._transition == 'fade')
					 	this.t3d.set({ opacity : 0 });
					 if (this._transition == 'slide-fade')
					 	this.t3d.set({ opacity : 0, y : -h/2 });
					 
					 
				 } else {
				 	
				 	  this._transition  = 'fade';
			 			var inst = attacher;
		 				if (attacher.get_instance)
		 					 inst = attacher.get_instance();
		 				var obj = $(jyingo.get(inst).get_position_element());
		 				
		 				var _scale_x = $(obj).width()/w, _scale_y = $(obj).height()/h;
		 			 	var _offset_x = $(obj).offset().left-_left-w/2;
		 			 	var _offset_y = $(obj).offset().top-top-h/2 - $(window).scrollTop();
		 			 	
		 			 	
				 	  this.t3d.set({ scaleX : _scale_x, scaleY : _scale_y, x : _offset_x, y : _offset_y });
				 	   
				 	  	
				 }
			}
				 
			this.$.show();
			this.__active = true;
			this.__call_and_push_deactivate();
			this.get_element().__popup = this;
			 
			shared.popup_animating = true;
			this._going_to_show = true;
			
			if (this.beforeshow) this.beforeshow(this);
			
			var _shw_cb = function(obj) {
				
		      $(this).addClass('shadowed');
			    obj.__showing(); 	
			    shared.popup_animating = false;
			    
			    if (obj._hide_after_show)
			    {
			      obj.hide();	
			      obj._hide_after_show = false;
			    }
			    
			    obj._going_to_show = false;
		  };
			
			if (this._transition != 'none' || attacher)
			{
				this.t3d.animate('identity', (this._transition == 'fade' ? 300 : 500), 'ease-out', _shw_cb);
			} else {
				 
				var o = { r : jyingo.delegate(this.get_element(), _shw_cb), o : this, c : function() { this.r(this.o); }};
				o.c();
				 
			}
		},
		
		__call_and_push_deactivate : function()
		{
			for (var i = 0; i < jyingo._popup_stack.length; i++)
			{
			 	jyingo._popup_stack[i]._deactivate();
			}
				
			jyingo._popup_stack.push(this);			
		},
		
		__showing : function()
		{ 
			$('.auto_nicescroll').niceScroll();
			$('.__ns_scrolled').niceScroll().resize();
			this._showing = true;
			this._activate();
			
			__msg_reset_cursor_positions = (new Date()).getTime();
			
			this.$.css({ height: 'auto'});
			if (this.onshow) this.onshow(this);
				
			
		}
	};
	
	return new self();
	
});