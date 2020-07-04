<?
/*
 * jyingoXT, PHP Ajax Toolkit http://www.jyingo.com
 * Copyright 2011-2020 Andrea Pezzino (haxormail@gmail.com) 
 * 
 * WARNING: jyingoXT is not a free source code. You are allowed to use
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
 
 
 define('TIMELINE_HEIGHT_STATIC', 1);
 define('TIMELINE_HEIGHT_AUTO', 2);
 define('TIMELINE_HEIGHT_ATTACHED', 3);
 
 class jyingo_timeline extends jyingo_control
 {
 	 var $text;
 	 var $checked;
 	 var $texture;
 	 var $clientclick;
 	 var $prevent_postback;
 	 var $height_mode;
 	 var $height_offset;
 	 var $hattached;
 	 var $start;
 	 var $servicemethod; 	 
 	 var $end;
 	 var $current;
 	 
 	 var $months;
 	 
 	 function __construct($params)
 	 {
 	 	 parent::__construct($params);
 	 	 
 	 	 $this->servicemethod = NULL;
 	 	 $this->height_mode = TIMELINE_HEIGHT_STATIC;
 	 	 $this->height = 0;
 	 	 $this->height_offset = 0;
 	 	 $this->hattached = NULL;
 	   $this->start = 0;
 	   $this->end = 0;
 	   $this->current = 0;
 	   $this->months = array("{L_JANUARY}", 
 	                         "{L_FEBRUARY}", 
 	                         "{L_MARCH}", 
 	                         "{L_APRIL}",
 	                         "{L_MAY}",
 	                         "{L_JUNE}",
 	                         "{L_JULY}",
 	                         "{L_AGOUST}",
 	                         "{L_SEPTEMBER}",
 	                         "{L_OCTOBER}",
 	                         "{L_NOVEMBER}",
 	                         "{L_DECEMBER}");
 	   
 	   
 	   $this->set_client_instance('jyingo.timeline');
 	   $this->allow_call('_twist');
 	   $this->allow_call('_choosing');
 	   
 	   $this->set_event_handler('_twist', array($this, '_twist'));
 	   $this->set_event_handler('_choosing', array($this, '_choosing'));
 	}
 	
 	function _twist($value)
 	{
 		$this->current = $value;
 		$ret = $this->event('twist', $this, $this->current);
 		
 		return $ret;
 	}
 	
 	function select_value($time)
 	{
 		$this->client_call('select_value', $time);
 	}
 	
 	function _choosing($value)
 	{

 		
 		$ret = $this->event('choosing', $this, array("dateline" => $value[0], "mode" => $value[1], "requestid" => $value[2]));
 		
 		$result = array("arr" => array());
 		
 		if (is_array($ret))
 		{
 			
 			foreach ($ret as $dateline => $text)
 			{
 			  
 			  if (substr_count($text, ';noregroup'))
 			   $noregroup = true;
 			  else
 			   $noregroup = false;
 			  
 			  if (substr_count($text, ';default'))
 			   $default = true;
 			  else
 			   $default = false;
 			  
 			  $text = str_replace(';noregroup', '', $text);
 			  $text = str_replace(';default', '', $text);
 			  $result['arr'][] = array($dateline, $text, md5($dateline.'.'.$text), $noregroup, $default);
 			}
 			
 		}
 		
 		return $result;
 		
 	}
 	
 	function get_client_data()
 	{
 		return array("months" => $this->months, "start" => $this->start, "end" => $this->end, "current" => $this->current,
         "height_mode" => $this->height_mode, "height" => $this->height, "hoffset" => $this->height_offset,
         "hattached" => $this->hattached);
 	}
 	 

 function datasource($dati) {
 	
 	$dateline = $dati[0];
 	$datestep = $dati[1];
 	$arr = array();
 	
 	$from = 0;
 	$to = 0;
 	
 	$stepmode = NULL;
 	
 	if ($this->servicemethod != NULL)
  {
  	
  	$mm = @date("m", $dateline);
  	$aaaa = @date("Y", $dateline);
  	
  	if ($datestep[0] == 'y')
  	{
  		 
  		 $from = @mktime(0,0,0,1,1,$aaaa);
  		 $to = @mktime(0,0,0,12,31,$aaaa);
  		 $stepmode = 'month';
  	}
  	else {
  		
  		$from =  @mktime(0,0,0,$mm,1,$aaaa);
  		$to =  @mktime(0,0,0,$mm+1,1,$aaaa);
  		$stepmode = 'day';
  	}
  	
  	$arr = call_user_func($this->servicemethod, $from, $to, $stepmode);
  	
  	
  	foreach ($arr as $index => $t)
  	{ 
  		 $t[2] = md5($t[0].$t[1]);
  		 $arr[$index] = $t;
  	}
  	
  }
 	
  return Zend_Json::encode(  array( "step" => $dati[2],  "arr" => $arr) );
 	
 }

   

 function render()
 {
 	 if ($this->visible == false)
 	  return;

 	 
 	global $env;
 	
 	
 	?><div id="<?=$this->get_instance()?>" <?=$this->get_property_string()?> class="jyingo_timeline"></div><?
 	
 	parent::render(false);
 }
 



 function update_value($new_value)
 {
 	
 	if ($this->disabled == true)
 	 return;
 	 
 	$this->current = $new_value;

 	
 }


  function set_property($name, $value)
  {
  	


 
  	 if ($name == "texture")
  	 {
  	   $this->texture = $value;
  	   return;	
  	 }
  	 
  	 if ($name == "onclientclick")
  	 {
  	   $this->clientclick = $value;
  	   return;	
  	 }
  	 
  	 if ($name == 'height')
  	 {
  	  $this->height = $value;
  	  return;	
  	 }

  	 
  	 if ($name == 'months')
  	 {
  	   $this->months = explode(',', $value);
  	   return;	
  	 }

  	 
  	 if ($name == 'hmode')
  	 {
  	   
  	   switch ($value)
  	   {
  	    case 'fixed':
  	     $this->height_mode = TIMELINE_HEIGHT_STATIC;
  	    break;	
  	    
  	    case 'auto':
  	     $this->height_mode = TIMELINE_HEIGHT_AUTO;
  	    break;
  	   	
  	   	case 'attached':
  	   	 $this->height_mode = TIMELINE_HEIGHT_ATTACHED;
  	   	break;
  	   	
  	   	
  	   }	
  	   return;	
  	 }
  	 
  	 if ($name == 'attach_to')
  	 {
  	 	 $this->hattached = $value;
  	 	 return;
  	 }
  	 
  	 if ($name == 'start')
  	 {
  	   $this->start = (is_numeric($value) ? $value : strtotime($value));
  	   return;	
  	 }

  	 if ($name == 'end')
  	 {
  	   $this->end = (is_numeric($value) ? $value : strtotime($value));
  	   return;	
  	 }  	 

  	 if ($name == 'current')
  	 {
  	   $this->current = (is_numeric($value) ? $value : strtotime($value));
  	   return;	
  	 }  	
  	 
   	 if ($name == "datasource")
  	 {
  	 	 $this->datasource = $value;
  	 	 return;
  	 }
  	   	   	 
  	 parent::set_property($name, $value);
  	
  }
  

 	
 	
 }


 
?>