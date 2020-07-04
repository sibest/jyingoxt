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

var TIMELINE_HEIGHT_STATIC = 1;
var TIMELINE_HEIGHT_AUTO = 2;
var TIMELINE_HEIGHT_ATTACHED = 3;

jyingo.timeline = function(name, element)
{
		
  this.checked = null;
  this.click_handler = null;
  this.disabled = null;
  this.clientclick = null;
  this.prevent_postback = true;
  
  this.resize_handler = null;
  this.months = null;
  this.render_interval = null;
  this.csm_interval = null;
  this.saveddata = new Array();
  this.scrolling_interval = null;
  this.saveddatahashes = new Array();
  this._active = false;
  
}
 
jyingo.timeline.prototype = {
	
	initialize : function(data)
	{
    
     this.height = 			data['height'];
     this.hoffset = 		data['hoffset'];
     this.hmode = 			data['height_mode'];
     this.attached =    data['hattached'];
     this.start =       data['start'];
     this.end   =       data['end'];
     this.current =     data['current'];
     this.months  =     data['months'];
     
     if (data['clientclick'])
      this.clientclick = Function(data['clientclick']);
      
     
     this.selector = document.createElement('div');
     this.selector.className = 'jyingo_timeline_selector';
     
     document.body.appendChild(this.selector);
     
     this.indicatore = document.createElement('div');
     this.indicatore.className = 'jyingo_timeline_indicator';
     
     document.body.appendChild(this.indicatore);

     this.helper = document.createElement('div');
     this.helper.className = 'jyingo_timeline_helper';
     
     document.body.appendChild(this.helper);
     
     this.selector.onselectstart = Function("return false");
     this.selector.ondragstart = Function("return false");

     this.indicatore.onselectstart = Function("return false");
     this.indicatore.ondragstart = Function("return false");
     this.indicatore.onmousemove = jyingo.delegate(this, this.mousemove);
     this.indicatore.onmouseup = jyingo.delegate(this, this.mouseup);
     this.indicatore.onmousedown = jyingo.delegate(this, this.mousedown);

     this.indicatore.ontouchmove = jyingo.delegate(this, this.mousemove);
     this.indicatore.ontouchend = jyingo.delegate(this, this.mouseup);
     this.indicatore.ontouchstart = jyingo.delegate(this, this.mousedown);     

     this.selector.onmousemove = jyingo.delegate(this, this.mousemove);
     this.selector.onmouseup = jyingo.delegate(this, this.mouseup);
     this.selector.onmousedown = jyingo.delegate(this, this.mousedown);

     this.selector.ontouchmove = jyingo.delegate(this, this.mousemove);
     this.selector.ontouchend = jyingo.delegate(this, this.mouseup);
     this.selector.ontouchstart = jyingo.delegate(this, this.mousedown);          
     
     if (this.hmode == TIMELINE_HEIGHT_STATIC)
      this.get_element().style.height = this.height+'px';
     else
     {
       // setup autoresize	
     	 this.get_element().style.position = 'fixed';
     	 
     	 this.delegate('resize', this.resize, window);
     	 
     	
     }
     
     this.last_request = 0;
     
     this.mouse_is_down = false;
     
		 
		 
		 this.delegate('mousemove', this.mousemove, window);
		 this.delegate('mouseup', this.mouseup, window);
		 
		 this.delegate('mousedown', this.mousedown);
		 this.delegate('mouseup', this.mouseup);
		 this.delegate('mousemove', this.mousemove);
		
		 this.delegate('touchmove', this.mousemovemob, window);
		 this.delegate('touchend', this.mouseupmob, window);
		 
		 this.delegate('touchstart', this.mousedown);
		 this.delegate('touchend', this.mouseup);
		 this.delegate('touchmove', this.mousemove);
		 
		 this.delegate('scroll', this.scrolling, window);
		 
		 this.continue_interval = null;
		 this.continue_handler = jyingo.delegate(this, this.continue_to);
		 
		  
		 this.resize();
		 this.start_render();
		 
		 this.datasource_cb = jyingo.delegate(this, this.datasource);
		 
		 var data = new Array(this.current, 'dd',  ++this.last_request);
		 //this.call_service_method(data);		 
		 
		  	
	},
	
	is_active : function()
	{
		return this._active;
	},
	
	update_showing : function() {
		
		var toshow = new Array();
		
		var noregroup_text = 0;
		
		
		for (var dateline in this.saveddata)
		{
			
		  if (dateline >= this.helper_show_from && dateline < this.helper_show_to)
		  {
		  	
		  	
		  	
		  	var info = this.saveddata[dateline];
		  	
		  	for (var i = 0; i < info.length; i++)
		  	{
		  	  
		  	  var local = info[i];
			  	var text = local._t;
			  	var noregroup = local._r;
			  	var _default = local._d;
			  	
			  	
			  	if (noregroup && !noregroup_text)
			  	 noregroup_text = text;
			  	
			  	if (_default)
			  	 noregroup_text = text;
			  	
			  	if (noregroup)
			  	 continue;
			  	
			 	   toshow.push(text);
			 	}
      }
		}
		
		
		if (noregroup_text && !toshow.length)
		 toshow.push(noregroup_text);
		
		
		var code = '<ul>';
		for (var i = 0; i < toshow.length && i < 6; i++)
		  code += '<li>'+toshow[i]+'</li>';
		  
	  code += '</ul>';		
	  
	  if (toshow.length) {
			this.helper.innerHTML =  code;

			
			this.helper.style.left = ($(this.selector).offset().left - $(this.helper).outerWidth())+'px';
			this.helper.style.top = ($(this.selector).offset().top-14)+'px';
			this.helper.style.display='block';	  	
	  } else {
	  	this.helper.style.display='none';	  
	  }
	  
		
	},
	
	datasource : function(data) {
		
	  	var result = data;

      for (var i = 0; i < result['arr'].length; i++)
      {
        
        var set = 	result['arr'][i];
        var dateline = set[0];
        var text = set[1];
        var hash = set[2];
        var noregroup = set[3];
        var _default = set[4];
        
        if (this.saveddatahashes[hash])
         continue;
        
        this.saveddatahashes[hash] = 1;
        
        if (!this.saveddata[dateline])
         this.saveddata[dateline] = new Array();
        
        this.saveddata[dateline].push({ _t : text, _r : noregroup, _d : _default});
      	
      }
		  
		  
		  if (this.mouse_is_down == true)
		  {
		  	 this.update_showing();
		  }

		
	},
	
	start_render : function() {
		
		this.stop_render();
		
		this.render_interval = setInterval(jyingo.delegate(this, this.render), 16);
		
	},
	
	mousedown : function(event, mobile) {
		
		if (mobile == undefined) mobile = false;
		this._active = true;
		this.mouse_is_down = true;
		
		this.stop_render();
		
    var ev = event;
    if (!ev) ev = window.event;
    
    this.tmp_start = this.old_start;
    this.tmp_end = this.old_end;
    this.tmp_cur = this.old_current;
    this.tmp_lvl = this.old_lvl;

    this.bk_start = this.old_start;
    this.bk_end = this.old_end;
    this.bk_cur = this.old_current;
    this.bk_lvl = this.old_lvl;
    
    
    
    this.draw(this.tmp_start, this.tmp_end, this.tmp_cur, this.tmp_lvl, ev);
    
    $(this.selector).addClass('active');
    $(this._element).addClass('active');
		return mobile;
	},
	
	mousemove : function(event) {
		
		
		if (this.mouse_is_down == false)
		 return false;
	
	  var ev = event;
    if (!ev) ev = window.event;
    
    this.draw(this.tmp_start, this.tmp_end, this.tmp_cur, this.tmp_lvl, ev);
    return false;
    
	},

  
  mouseupmob : function(event)
  {
  	this.mouseup(event, true);
  },

  mousedownmob : function(event)
  {
  	this.mousedown(event, true);
  },
	
	mouseup : function(event, mobile) {
		
		if (mobile == undefined) mobile = false;
		
		this._active = false;
		
		if (this.mouse_is_down == false)
		 return (mobile);
		
		var ev = event;
		if (!ev) ev = window.event;
		
	  $(this.selector).removeClass('active');
		$(this._element).removeClass('active');
		this.mouse_is_down = false;
		this.start_render();

		if (this.continue_interval)
		{
				clearInterval(this.continue_interval);
				this.continue_interval = null;
		}

   
		//this.indicatore.style.display='none';
		this.helper.style.display='none';

    this.indicatore.innerHTML = '<img src="'+globals.static_url+'/images/menuloaders.gif" style="padding-left:14px" />';

		//if (ev.target == this.indicatore)
		//{
			
			 this.value = this.newposdate;
			 
			 
			 this.backup_current = this.current;
			  
			 this.call(this.result, '_twist', this.value);
			  
			 this.current = this.newposdate;
			 
			  
			    
		//}
	  return mobile;
	},
	
	select_value : function(t)
	{
		
		this.current = t;
		
	},
	
	result : function(val)
	{
		 this.indicatore.style.display='none'
		 if (val == false)
		 {
		   this.current = this.backup_current;	
		 } else {
		   this.current = val;	
		 }
	},
	
	continue_to : function() {

		if (this.newpos != this.selpos)
		{
			
			this.tmp_lvl = 2;
			if (this.newpos.substr(0,1) == 'y')
			 this.tmp_lvl = 1;

			
			this.tmp_cur = this.newposdate;
   	
			
		} else {
			
			
			if (this.tmp_lvl != 2)
			this.tmp_lvl++;
			
			
			
		}
	  
	  this.draw(this.tmp_start, this.tmp_end, this.tmp_cur, this.tmp_lvl);

	  clearInterval(this.continue_interval);
	  this.continue_interval = null;		
	},

	stop_render : function() {
		
		if (this.render_interval != null)
		{
		 clearInterval(this.render_interval);
		 this.render_interval = null;
		}
	
	},
	
	render : function() {
		
		if ($(this.get_element()).offset().top != this.old_st_pos)
		 this.resize();
		else
		 this.draw(this.start, this.end, this.current, 2);		
	},
	
	resize : function() {
		
		
		if (this.hmode == TIMELINE_HEIGHT_AUTO)
		{
			var wh = $(window).height() < $(document).height() ? $(window).height() : $(document).height();
			this.get_element().style.height = (wh  - this.hoffset)+'px';
		}
		
		if (this.hmode == TIMELINE_HEIGHT_ATTACHED)
		{
			var wh = $($get(this.attached)).offset().top;
			var l = 0;
			var st = $(this.get_element()).offset().top - l;
			
			
			
			
			if (wh > $(window).height()-st) wh = $(window).height();
			var t = (wh - st - this.hoffset);
		  
		  if (t > $($get(this.attached)).offset().top-l-st)
		    t = $($get(this.attached)).offset().top-l-st;
			
			
			
			this.get_element().style.height = t+'px';
		}
		
		
		this.old_st_pos = $(this.get_element()).offset().top;
		
		this.old_lvl = -1;
		
		this.draw(this.start, this.end, this.current, 2);
		
	},
	
	draw : function(start, end, current, deeplvl, event) {
		

		var date_start = new Date(start*1000);
		var date_end = new Date(end*1000);
		var date_cur = new Date(current*1000);
		var wh = $(window).height();
		
			
		if (!(this.old_start == start &&
		    this.old_end == end &&
		    this.old_current == current &&
		    this.old_wh == wh &&
		    this.old_lvl == deeplvl))
		
		{
		
			var code = '';
			
			this.map = new Array();
			this.map2 = new Array();
			this.map3 = new Array();
			
			var now = new Date();

			
			var ls = date_start.getFullYear();
			var lt = date_end.getFullYear();
			
			var th = $(this.get_element()).innerHeight();

		  var day_count = 32 - new Date(date_cur.getFullYear(), date_cur.getMonth(), 32).getDate();
		  var months_count = 12;
		  if (date_cur.getMonth() == now.getMonth() && 
		      date_cur.getFullYear() == now.getFullYear())
		  {
		  	
		  	day_count = now.getDate();
		  	
		  }
		  
		  if (date_cur.getFullYear() == now.getFullYear())
		  {
		  	months_count = now.getMonth()+1;
		  }
		  
			var elements = ((ls-lt)+1);
			if ( deeplvl > 0 ) elements+=months_count;
			if ( deeplvl > 1 ) elements+=5;
	
			var el_h = th / elements - 1;
			
			
			var cur_h = 0;
			
			for (var i = ls; i >= lt; i--)
			{
				
				this.map['y'+i] = cur_h+1;
				this.map3['y'+i] = new Date(i, 0, 1);
				
				cur_h += el_h;
				
				if ( i == date_cur.getFullYear() )
				{
					
					code += '<div class="year selected" style="height:'+el_h+'px" onselectstart="return false" ondragstart="return false"><span>'+i+'</span></div>';
					if (deeplvl > 0)
					{
						
						for (var t = months_count-1; t >-1; t--)
						{
		

						 this.map['m'+t] = cur_h+1;
						 this.map3['m'+t] = new Date(i, t, 1);
						 cur_h += el_h;		
						 				
						 if (t == date_cur.getMonth())
						 {
						 
						 
						 
						  var m_h = el_h;
						  if (deeplvl > 1)
						  {
						  	
						  	var p_h = (5*(el_h+1) / day_count);
						  	m_h = el_h + p_h*day_count;
						  	
						    code += '<div class="months selected" style="height:'+m_h+'px" onselectstart="return false" ondragstart="return false"><span>'+this.months[t]+'</span></div>';	
						    
						    
						    for (var k = day_count; k> 0; k--)
						    {
						     this.map['d'+k] = cur_h;
						     this.map3['d'+k] = new Date(i, t, k);
						     cur_h += p_h;
						    }
						    						 
						  } else {
						  	code += '<div class="months selected" style="height:'+el_h+'px" onselectstart="return false" ondragstart="return false"><span>'+this.months[t]+'</span></div>';
						  	
						  }
						 
						 }
						 else
						 {				 	
						  code += '<div class="months" style="height:'+el_h+'px" onselectstart="return false" ondragstart="return false"><span>'+this.months[t]+'</span></div>';
						 }
						
						}
					}
	
					
				} else {
					
					code += '<div class="year" style="height:'+el_h+'px" onselectstart="return false" ondragstart="return false"><span>'+i+'</span></div>';
					
				}
			}
			

			this.get_element().innerHTML = code;
    	this.old_start = start;
	  	this.old_end = end;
	  	this.old_current = current;
	  	this.old_wh = wh;
			this.old_lvl = deeplvl;
		}
		
		var hoff = 0;
		
	//	if (this.hmode == TIMELINE_HEIGHT_AUTO)
	//	 hoff = $(document).scrollTop();
		 
		 
		if (event != undefined && event != null) {
			
			
	   var x,y;
	 
	 	 if (event.pageX && event.pageY)
	 	   y = event.pageY;	
	 	 else 
	 	   y = event.clientY + $(document).scrollTop();   	
	 	 
	 	 
	 	 x = parseInt($(this.get_element()).offset().left - 2);
	 	 if ( y < hoff + $(this.get_element()).offset().top)
	 	  y = hoff + $(this.get_element()).offset().top;
     
     if ( y > hoff + $(this.get_element()).offset().top + $(this.get_element()).outerHeight())
      y = hoff + $(this.get_element()).offset().top + $(this.get_element()).outerHeight();
    
   
			this.selector.style.left = x+'px';
			this.selector.style.top = (y-$(document).scrollTop())+'px';
			this.selector.style.display='block';	
			
				 
	 	 
	 	 if (x == this.old_x && y == this.old_y)
	 	  return;
	 	 
	 	 this.old_x = x;
	 	 this.old_y = y;
	 	 
	 	 var oy = y - hoff - $(this.get_element()).offset().top;
	 	 
	 	  var t1 = -1, t2 = -1;
	 	  for (var p in this.map)
	 	  {
	 	  	
	 	  	if (oy >=  this.map[p])
	 	  	{
	 	  		t1 = p;
	 	  	}
	 	  	
	 	  	if ( oy <=  this.map[p] && t1 != -1)
	 	  	{
	 	  		t2 = p;
	 	  		break;
	 	  	}
	 	  	
	 	  }
	 	  
	 	  
	 	  this.newposdate = parseInt(this.map3[t1].getTime()/1000);
			
			this.indicatore.style.display='block';
			this.indicatore.style.left = (x+14)+'px';
			this.indicatore.style.top = (y-2)+'px';			
			
			var zyear = this.map3[t1].getFullYear();
			/*if(zyear > 2000)
			 zyear -= 2000;
			else if (zyear < 2000)
			 zyear -= 1900;
			
			if (zyear < 10)
			 zyear = '0'+zyear;
			*/
			
			var zmonth = this.months[this.map3[t1].getMonth()];
			if (zmonth < 10) zmonth = '0'+zmonth;
			
			if (t1.substr(0,1) == 'd')
       this.indicatore.innerHTML = (this.map3[t1].getDate() < 10 ? '0'+this.map3[t1].getDate() : this.map3[t1].getDate()) +' '+(zmonth.substr(0,3));
      else if (t1.substr(0,1) == 'm')
      {
       this.indicatore.innerHTML = (zmonth.substr(0,3))+' '+zyear;
       this.newposdate = parseInt(new Date(this.map3[t1].getFullYear(), this.map3[t1].getMonth()+1, -1).getTime()/1000);
      }
      else 
      {
       
       this.indicatore.innerHTML = this.map3[t1].getFullYear();
       
       
       if (this.map3[t1].getFullYear() == new Date().getFullYear())
        this.newposdate = parseInt(new Date(this.map3[t1].getFullYear(), new Date().getMonth()+1, -1).getTime()/1000);
       else
        this.newposdate = parseInt(new Date(this.map3[t1].getFullYear(), 12, 31).getTime()/1000);
      } 
			if (this.continue_interval)
			{
				clearInterval(this.continue_interval);
			}
	
			this.continue_interval = setInterval(this.continue_handler, 500);		
      
      if (this.newpos != t1)
      {

				
				var data = new Array(this.newposdate, t1,  ++this.last_request);
				this.call_service_method(data);
				
			//	callservicemethod('sm_'+this.get_name(), data, this.datasource_cb);

				var hstart  =  this.map3[t1].getTime()/1000, hend;	

        if (t1.substr(0,1) == 'd')
          hend = hstart+86400;
        
        if (t1.substr(0,1) == 'm')
        {

        	hend = hstart+86400*(32 - new Date(this.map3[t1].getFullYear(), this.map3[t1].getMonth(), 32).getDate()); 
        }
        if (t1.substr(0,1) == 'y')
          hend = hstart+31570560;
        
        this.helper_show_from = hstart;
        this.helper_show_to = hend;
        
        
        this.update_showing();
        
        

      }
      
      this.newpos = t1;
      			
		} else {
			
			var sel = 'd'+date_cur.getDate();
			var pos = (this.map[sel]);
			
			this.selpos = sel;
      this.newpos = sel;
      
      var ly = parseInt(hoff + $(this.get_element()).offset().top + pos - 2);

			
			if (this.mouse_is_down == false)
			{
				this.selector.style.left = parseInt($(this.get_element()).offset().left - 2)+'px';
				this.selector.style.top = (ly - $(document).scrollTop())+'px';
				this.selector.style.display='block';		
			} else {
				
	
			}

			
		}
		
		
		
	},
	
	call_service_method : function(data) {
		
		if (this.csm_interval)
		{
			 clearInterval(this.csm_interval);
		}
		
		this.csm_data = data;
		this.csm_interval = setInterval(jyingo.delegate(this, this.csm_request), 300);
		
		
	},
	
	csm_request : function() {
		
		clearInterval(this.csm_interval);
	  this.csm_interval = null;
	  
	  this.call(this.datasource, '_choosing', this.csm_data);
	  
		
	},
	
	scrolling : function(event)
	{
		

	},
	
	scroll_end : function() {
		this.selector.style.position='absolute';
		clearInterval(this.scrolling_interval);
		this.scrolling_interval = null;
	},
	
	dispose : function()
	{
    

    
    if (this.selector) {
    	
    	document.body.removeChild(this.selector);
    	this.selector = null;
    	
    }
		
		
		this.stop_render();
		
		if (this.selector)
		{
			this.selector.parentNode.removeChild(this.selector);
			this.selector = null;
		}

		if (this.indicatore)
		{
			this.indicatore.parentNode.removeChild(this.indicatore);
			this.indicatore = null;
		}

		if (this.helper)
		{
			this.helper.parentNode.removeChild(this.helper);
			this.helper = null;
		}		



	}
	
};


