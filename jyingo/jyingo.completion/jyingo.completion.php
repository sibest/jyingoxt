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
 
 class jyingo_completion_column extends jyingo_control {
 	  
 	  public $text;
 	  
 	  function __construct($params) {
 	  	
 	  	parent::__construct($params);
 	  	
 	  	$this->set_client_instance('jyingo.generic');
      $this->set_class('jyingo_completion_column');
 	  
 	  } 	
 	  
 	  function render()
 	  {
 	  	echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
 	  	echo $this->text;
 	  	echo '</div>';
 	  } 	   	

 	  
 	  function set_property($k, $v)
 	  {
 	  	
 	  	if ($k == 'text')
 	  	{
 	  		$this->text = $v;
 	  		return;
 	  	}
 	  	
 	  	
 	  	parent::set_property($k, $v);
 	  	
 	  }
 	
 };
 
 class jyingo_completion_element extends jyingo_control {
 	  
 	  public $value;
 	  
 	  function __construct($params) {
 	  	
 	  	parent::__construct($params);
 	  	
 	  	$this->set_client_instance('jyingo.completion_element');
      $this->set_class('jyingo_completion_element');

      $this->value = $params->value;
 	  
 	  } 	
 	  
 	  function get_client_data()
 	  {
 	  	
 	  	return array("value" => $this->value);
 	  }
 	  
 	  function html($text)
 	  {
 	  	
 	  	
 	  	$p = $this->load('jyingo.label');
 	  	$p->value = $text;
 	  	
 	  	
 	  }
 	 
 	  function render()
 	  {
 	  	echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
 	  	parent::render();
 	  	echo '</div>';
 	  }
 	  
 	    	
 }

 class jyingo_completion extends jyingo_control {
  	
  
  
  	public $active_tag = 'jyingo:completion';	
 	  
 	  private $_target;
 	  private $_method;
 	  private $_target_differ = NULL;
 	  
 	  public $interval = 250;
 	  
 	  private $_values = array();
 	  public $width = 0, $offset = 0, $offset_y = 0;
 	  
 	  function __construct($params) {
 	  	
 	  	parent::__construct($params);
 	  	
 	  	$this->set_client_instance('jyingo.completion');
 	  	$this->allow_call('complete');
 	  	$this->allow_call('click');
 	  	$this->allow_call('erase');
 	  
 	    if ($params->offset_y) $this->offset_y = $params->offset_y;
 	  
 	    if ($params->target)
 	     $this->_target = $params->target->get_instance();
 	  
 	  }
 	  
 	  function set_parent($obj)
 	  {
 	  	parent::set_parent($obj);
 	  	if ($this->_target_differ != NULL)
 	  	{
 	  		$this->_target = $obj->get($this->_target_differ)->get_instance();
 	  
 	  		$this->_target_differ = NULL;
 	  	}
 	  }
 	  
 	  function render()
 	  {
 	  	echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
 	  	parent::render();
 	  	echo '</div>';
 	  }
 	  
 	  function get_value($value)
 	  {
 	  	return $this->_values[$value];
 	  }
 	  
 	  function click($value)
 	  {

 	  	if ($this->_method == NULL)
 	  	{
 	  		
 	  	 $ret  = $this->event('click', $this, $this->get_value($value));
 	  		
 	  	} else {
 	  	
 	  	 $ret = call_user_func($this->_method, 'click',  $this, $this->get_value($value));
 	  	 
 	  	}
 	  	
 	  	return $ret; 	  	
 	  }
 	  
 	  function erase()
 	  {
 
 	  	if ($this->_method == NULL)
 	  	{
 	  		
 	  	 $ret  = $this->event('erase', $this);
 	  		
 	  	} else {
 	  	
 	  	 $ret = call_user_func($this->_method, 'erase',  $this);
 	  	 
 	  	}
 	  	return $ret; 	  	
 	  }
 	  
 	  function add_column()
 	  {
 	  	return $this->load('jyingo.completion_column');
 	  }
 	  
 	  function add_element($value)
 	  { 	  	 	  	
 	  	$obj =  $this->load('jyingo.completion_element');
 	  	if ($value)
 	  	{
 	  		
 	  		$obj->value = $obj->get_instance();
 	  		$this->_values[$obj->value] = $value;
 	  		
 	  	}
 	  	
 	  	return $obj;
 	  	
 	  }
 	  
 	  function complete($text)
 	  { 
 	  	
 	  	$this->clear();
 	  	
 	  	if ($this->_method == NULL)
 	  	{
 	  		
 	  	 $ret  = $this->event('complete', $this, $text);
 	  		
 	  	} else {
 	  	
 	  	 $ret = call_user_func($this->_method, 'complete', $this, $text);
 	  	 
 	  	}
 	  	
 	  	$children = $this->get_children();
 	  	return count($children);
 	  }
 	  
 	  function get_client_data()
 	  {
 	    return array("target" => $this->_target, "offsetY" => $this->offset_y, "offset" => $this->offset, "interval" => $this->interval, "width" => $this->width);	
 	  }
 	  
 	  
 	  function set_method($method)
 	  {
 	  	 $this->_method = $method;
 	  }
 	  
 	  
 	  function set_target($object)
 	  {
 	  	if ($object instanceof jyingo_control)
 	  	 $this->_target = $object->get_instance();
 	  	else
 	  	 $this->_target = $object;
 	  }
 	  
 	  function blur()
 	  {
 	  	$this->client_call('blur');
 	  }
 	  
 	  function __get($k)
 	  {
 	  	 global $env;
 	  	 
 	  	 if ($k == 'target')
 	  	  return $env->get_by_instance($this->_target);
 	  	 
 	  	 return parent::__get($k); 
 	  }
 	  
 	  function set_property($k, $v)
 	  {
 	  	
 	  	if ($k == 'interval')
 	  	{
 	  		$this->interval = intval($v);
 	  		return;
 	  	}
 	  	
 	  	if ($k == 'target')
 	  	{
 	  		$this->_target_differ = $v;
 	  		return;
 	  	}
 	  	
 	  	if ($k == 'offset_y')
 	  	{
 	  		$this->offset_y = intval($v);
 	  	}

 	  	if ($k == 'width')
 	  	{
 	  		$this->width = intval($v);
 	  		return;
 	  	}
 	  	
 	  	if ($k == 'offset')
 	  	{
 	  		$this->offset = intval($v);
 	  		return;
 	  	} 	  	
 	  	parent::set_property($k, $v);
 	  	
 	  }
 	
 	
 	
 	
 }

?>