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
 
 
  
 jyingo.chart = function(params)
 {
 	
 };

 jyingo.chart.prototype = {
  
   initialize : function(params)
   {
   	  
   	  this._chartObj = null;
   	  this._tmra = null;
   	  this.timer_check = null;
   	  var _fast_call = false;
   	  var _skip_render = false;
   	  
      if (!jyingo.chart.__loaded)
      {
      	jyingo.chart.__loaded = true;
      	_skip_render = true;
      	google.load('visualization', '1', {callback : this.create_delegate(this.render_data), packages: ['corechart']});
      	
      }
      else if (jyingo.chart.__jsloaded == true)
      {
      	_fast_call = true;
      }
      else {
      	 this.timer_check = setInterval(this.create_delegate(this._check_load), 300);
      }
      
      this.$.css({ width : params.width, height : params.height });
      
      this.type = params.type ? params.type : 'linechart';
      this.columns = params.columns ? params.columns : [];
      this.data = params.data ? params.data : [];
      this.options = params.options ? params.options : {};
      
      if (_fast_call == true)
       this.render_data(); 

   },
   
   _check_load : function()
   {
     if (	jyingo.chart.__jsloaded == true )
     {
       clearInterval(	 this.timer_check );
       this.timer_check = null;
       this.render_data();
     }
   },
   
   clearChart : function()
   {
     if (this._tmra != null)
     {
      clearInterval(this._tmra);
      this._tmra = null;	
     }
         	
     this.$.find('span.no_data').show();
     if ( this._chartObj != null )
     {
    		 this._chartObj.clearChart();
    		 delete  this._chartObj;
    		 this._chartObj = null;
     }
    	
     this.data = [];
   	 
   },
   
   addData : function(arr)
   {
     this.data.push(arr);
     
     if (this._tmra != null)
      clearInterval(this._tmra);
      
     this._tmra = setInterval(this.create_delegate(this.render_data), 200);
     
   },
   
   dispose : function()
   {
   	
   	if (this.timer_check != null)
   	{
   		clearInterval(this.timer_check);
   		this.timer_check = null;
   	}
   	
   	this.clearChart();
   },
   
   setOptions : function(columns, options)
   {
     this.options = options;
     this.columns = columns;	
   },
   
   render_data : function()
   {
     
     jyingo.chart.__jsloaded = true;
     
     if (this._tmra != null)
     {
      clearInterval(this._tmra);
      this._tmra = null;	
     }
     
     if (!this.data.length)
      return;
      
     
     this.$.find('span.no_data').hide();
     
     var data_tbl = [['x'].concat(this.columns)].concat(this.data);
   	 var data = google.visualization.arrayToDataTable(data_tbl);
   	 
   	 
   	 var _loader = null;
   	 
   	 this.clearChart();
   	 
   	 switch (this.type)
   	 { 
   	 	
   	 	 case 'linechart':
   	 	  _loader = eval('(function(){ return google.visualization.LineChart; }())');
   	 	 break;
   	 	 
   	 	 case 'areachart':
   	 	  _loader = eval('(function(){ return google.visualization.AreaChart; }())');
   	 	 break;
   	 	
   	 }
   	 
   	  this._chartObj = new _loader(this.get_element());
   	  
   	  this.options.width = this.width;
   	  this.options.height = this.height;
      this._chartObj.draw(data, this.options);
      
   	 
   }
 	
 	
 };