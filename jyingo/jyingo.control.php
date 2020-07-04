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
 

 define('LOAD_ORDER_TOP', 0);
 define('LOAD_ORDER_BOTTOM', 1);



 class jyingo_control extends jyingo_child {
 	
 	protected $htmlproperties = array();
 	private $_events = array();
 	private $_calls = array();
 	private $_disabled = false;
 	private $_visible = true;
 	private $_delegate = NULL;
 	
 	public $allow_postback = true;
 	
 	public $tag;
 	
 	private $scriptdata;
 	private $scriptprops = array("disabled", "visible", "classname");
 	
 	private $changed = array();
 	
 	private $styles = array();
  private $clientclass = NULL;
  private $_rendered = false;
  private $_loaded = false;
  
  private $saved_client_vars = array();
  private $event_handlers = array();
  
  private $pushed_styles = array();
  private $pushed_html_props = array();
  private $_skip_finalize;
  
  private $remove_parent = NULL;
  
  protected $load_order = LOAD_ORDER_TOP;
  
  private $_step_data = array();
  
  private $html_name = NULL;
  
  private $last_recurisve_props = array();
  
  function __construct($params = NULL)
  {
  	 
  	 parent::__construct($params);
  	
  	 $this->allow_call('_continue_function_load');
  	 
  	 $x = $this->scriptprops;
  	 $this->scriptprops = array();
  	 
  	 foreach ($x as $y)
  	  $this->allow_script_property($y);
  	  
  	 
  	 if (is_object($params))
  	 $this->remove_parent = $params->remove_parent;
  	 
  	  
  	 
  	 
  }
   
  
  function errtip($text)
  {
    $this->tooltip(array("text" => $text, "fixed" => true, "timeout" => 3000))->add_class('red');	
  }
  function tooltip($params, $name = NULL)
  {
  	 
  	 if (!is_array($params))
  	  $params = array("text" => $params);
  	 
  	 $class = ($name ? $name.':jyingo.tooltip' : 'jyingo.tooltip');
  	 return $this->load($class, $params);
  	 
  }
  
  function release()
  {
  	 global $env;
  	 parent::release();
  
     if (!$this->alive())
      $env->mark_released($this);
     
  }
  
  function has_rendered()
  {
  	return $this->_rendered;
  }
 
  function step_load($step_function, $params = NULL, $start_function = NULL, $end_function = NULL )
  {
   
    $x = md5(mt_rand(1,99999999999).time());
   	$this->_step_data[$x] = array("params" => $params, "start" => $start_function, "end" => $end_function, "function" => $step_function, "type" => "step");
    $this->client_call('_continue_function_load', $x);
  } 
  
  function defer($function, $params = NULL, $wait = 0)
  {
  	
	  $x = md5(mt_rand(1,99999999999).time());
	  $this->_step_data[$x] = array("method" => $function, "params" => $params, "type" => "defer");
      	  
    if (!$wait)
     $this->client_call('_continue_function_load', $x); 
    else
     $this->client_call('_continue_function_load_wait', $x, $wait);  	
  }

  function array_load($array, $step_function, $end_function = NULL, $params = NULL)
  {
      	
      	
      	
      	if (!count($array))
      	{
      		
      		if ($end_function)
      		@call_user_func(array($this, $end_function), $params);
      		
      	} else {
      	
	      	$x = md5(mt_rand(1,99999999999).time());
	      	$this->_step_data[$x] = array("data" => $array, "step" => $step_function, "end" => $end_function, "params" => $params, "type" => "array");
      	  
      	  $this->client_call('_continue_function_load', $x);
      	  
        }
  }
  
  function unregister_step_function($x)
  {
  	 unset($this->_step_data[$x]);
  }
  
  function _continue_function_load($key)
  {
  	 
  	 if (!isset($this->_step_data[$key]))
  	 {
  	  return;	
  	 }

  	 
  	 if ($this->_step_data[$key]['type'] == 'defer')
  	 {  	   	 
  	 	   
  	 	   $params = $this->_step_data[$key]['params'];
  	 	   if ($params)
  	 	   {
  	 	   	@call_user_func(array($this, $this->_step_data[$key]['method']), $params);
  	 	   } else {
  	 	   	
  	 	   	@call_user_func(array($this, $this->_step_data[$key]['method']));
  	 	   }
  	 	   
  	   	 $this->unregister_step_function($key);
  	 }
  	 
  	 if ($this->_step_data[$key]['type'] == 'step')
  	 {  	 
  	   
  	   if ($this->_step_data[$key]['start'])
  	   {
  	    @call_user_func(array($this, $this->_step_data[$key]['start']), $this->_step_data[$key]['params']);
  	    $this->_step_data[$key]['start'] = NULL;	
  	   }
  	   
  	   $ret = @call_user_func(array($this, $this->_step_data[$key]['function']), $this->_step_data[$key]['params']);
  	   if ($ret)
  	   {
  	   	 
  	   	  if (is_array($ret))
  	   	   $this->_step_data[$key]['params'] = $ret;
  	    	$this->client_call('_continue_function_load', $key);
  	   	  
  	   } else {
 
	   	   if ($this->_step_data[$key]['end'])
	  	   {
	  	    @call_user_func(array($this, $this->_step_data[$key]['end']), $this->_step_data[$key]['params']);
	  	   }
 
  	   	 $this->unregister_step_function($key);
  	   	 
  	   	 
  	   	 
  	   }
  	 }
  	 
  	 
  	 if ($this->_step_data[$key]['type'] == 'array')
  	 {
  	 
	  	 if (count($this->_step_data[$key]['data']))
	  	 {
	  	 	
	  	 	 $element = array_shift( $this->_step_data[$key]['data'] );
	  	 	 @call_user_func(array($this, $this->_step_data[$key]['step']), $element, $this->_step_data[$key]['params']);
	  		 $this->client_call('_continue_function_load', $key);
	  	 
	  	 } else {
	  	 	
	  	   
	  	   @call_user_func(array($this, $this->_step_data[$key]['end']), $this->_step_data[$key]['params']);
	  	   unset($this->_step_data[$key]);
	  	   	
	  	 }
  	 }
  }
  
  function __wakeup()
  {
  	
  	foreach ($this->scriptprops as $key)
  	{
  	 unset($this->$key);
  	}
  }
  
  function clear($params = NULL)
  {

  	global $env;
  	$env->client_do($this->get_instance(), 'remove_by_parent', array("parent" => $this->get_instance()));
  	
  	if (!$params)
  	 $params = array("begin" => $this->get_instance(), "dispose" => true);
  	
  	parent::clear($params);
  
  	
  }
  public function shift($classname, $params = NULL, $view = NULL, $context = NULL)
  {
   
   if ($this->_loaded)
    $env->push_loading();
   
   $obj = $env->load($classname, $params, $view, $context);
   
   $this->insert_before($obj);
   
    if ($this->_loaded)
    $env->pop_loading();
    
   return $obj;
  }
  
  public function load($classname, $params = NULL, $view = NULL, $context = NULL)
  {
   global $env;
   
   if ($this->_loaded)
    $env->push_loading();
   
   $obj = $env->load($classname, $params, $view, $context);
   
   $this->add_child($obj);
   
    if ($this->_loaded)
    $env->pop_loading();
   
   return $obj;
  }
  
  function has_loaded()
  {
  	return $this->_loaded;
  }
  
  
  function render( $children = true )
  {
  	 $this->_rendered = true;
  	 
  	 global $env;

  	 if ($children)
  	  parent::render();
  	 else
  	  $this->render_false();
  }
  
  function postback_start()
  {

  	 parent::postback_start();	
     $this->onstart();
     
  }
  
  function onstart()
  {
  	
  	
  }
    
  function postback_load()
  {
     
     global $env;
     
     
  	 if (!$this->_loaded)
  	 {
  	   
  	   $this->onload();
  	   $this->_loaded = true;
  	   
  	   
  	 } else {
  	   parent::postback_load();	
  	 }
  	 
  	 
  	 
  }
  
  function postback_preview()
  {
   
   if (!$this->_rendered)
   {
     $this->preview();	
   } else {
     parent::postback_preview();	
   }
  	
  	
  }

  function postback_render(&$result) 
  {
  	
  	global $env;
  	
  	if ($this->_rendered == false)
  	{
  		

  		ob_start();
  		$this->render();
  		$contenuto = ob_get_contents();
  		ob_end_clean();
  		
  		$this->_rendered = true;
  		
  		$children = $this->get_parent()->get_children();
  		
  		$index = -1;
  		
  		foreach ($children as $index => $child)
  		{
  			if ($child->get_instance() == $this->get_instance())
  			 break;
  		}
  		
  		
  		
  		$before = -1;
  		if ($index == 0)
  		 $before = 0;
  		elseif ($index != count($children)-1)
  		 $before = $children[$index+1]->get_instance();
  		 
  		$out = array("parent" => $this->get_parent()->get_instance(), "data" => ($contenuto));
  		if ($before != -1)
  		 $out['before'] = $before;

  		$result[$this->get_instance()] = $out;

  		
  	} else {

  		foreach ($this->children as $child)
  		{
  			 $child->postback_render($result);
  		}
  		
  	}
  	
  }
  
 	function render_false()
	{
	 		$this->_rendered = true;
	 		
	 		foreach ($this->children as $child)
	 		{
	 			 $child->render_false();
	 		}
	}
	
	function call_delegate($service, $data)
	{
		
	   $return = call_user_func_array(array($this, $service), $data);
	   return $return;
		
	}
  
  function is_event_allowed($event)
  {
  	

  	if (in_array($event, $this->_events))
  	 return true;
  	
  	return false;
  	
  }

  function is_call_allowed($call)
  {
  	

  	if (in_array($call, $this->_calls))
  	 return true;
  	
  	return false;
  	
  }
  
  function update_script_var($var)
  {
  	
  	$this->saved_client_vars[$var] = $this->$var;
  	
  }
  
  function finalize(&$result = NULL)
  {
     global $env;


  	 if ($this->clientclass != NULL)
  	 {
	  	 $s = array();
	  	 
	  	 foreach ($this->changed as $key)
	  	 	$s[$key] = $this->$key;

	 
	  	
	  	 if ($this->_skip_finalize  == false)
	  	 {

     
	  	 	
		  	 if (count($s))
		  	   $result['vars'][$this->get_instance()] = $s;	

		  	 
			  if (count($this->pushed_styles))
			  	   $result['styles'][$this->get_instance()] = $this->pushed_styles;
			  	 
			  if (count($this->pushed_html_props))
			  	   $result['htmlprops'][$this->get_instance()] = $this->pushed_html_props;  			   
			   
			 } else {
			 	
			   $this->update_client_data();
			   $this->_skip_finalize = false;	
			 
			 }  
	  	 
	  	 $this->pushed_styles = array();
	  	 $this->pushed_html_props = array();
  	 }
  	 
  	 foreach ($this->children as $child) 
  	  $child->finalize($result);
  	 
  	 $this->changed = array();
  	 
  	 $this->last_recurisve_props['visible'] = $this->visible;
  	 $this->last_recurisve_props['disabled'] = $this->disabled;
  	
  }
  
 	function allow_script_property($propertyname)
 	{
 		 
 		 $default = $propertyname.'_default';
 		 
 		 $this->scriptprops[] = $propertyname;
 		 
 		 $this->saved_client_vars[$propertyname] = $this->$default;
 		 unset($this->default);
 		 unset($this->propertyname);
 	}
 	
 	function client_vars($vars)
 	{
 		foreach($vars as $varname)
 		 $this->allow_script_property($varname);
 	}
 	
 	function update_value($d)
 	{
 		
 	}

 	function onload() {
 		
 		global $env;
 		
 		$env->notify_load($this->get_instance());
 		
 		$this->_skip_finalize = true;
	
	
 		if ($this->load_order == LOAD_ORDER_TOP)
 		 parent::onload();

 		if ($this->clientclass != NULL && $this->_loaded != true)
 		{
 			
 			$env->client_do($this->get_instance(), "load", array(
 			"name" => $this->get_name(),
 			"instance" => $this->get_instance(),
 			"visible" => $this->visible,
 			"allow_postback" => $this->allow_postback,
 			"disabled" => $this->disabled,
 			"parent" => ($this->get_parent() ? $this->get_parent()->get_instance() : null),
 			"class" => $this->clientclass,
 			"data" => $this->get_client_data()), $this->get_unique_identifier());
 			
 		}

 		if ($this->load_order == LOAD_ORDER_BOTTOM)
 		 parent::onload(); 		
 		
 		$this->_loaded = true;
 		
 	}
 	
 	function update_client_data()
 	{
 	  if ($this->clientclass != NULL && $this->get_parent())
 		{
 			
 			global $env;
 			
	  
 			$env->client_do($this->get_instance(), "load", array(
 			"name" => $this->get_name(),
 			"instance" => $this->get_instance(),
 			"visible" => $this->visible,
 			"allow_postback" => $this->allow_postback,
 			"disabled" => $this->disabled, 			
 			"parent" => $this->get_parent()->get_instance(),
 			"class" => $this->clientclass,
 			"data" => $this->get_client_data()), $this->get_unique_identifier());
 		}	 		
 	}
 	
 	function get_client_data()
 	{
 		return array();
 	}
 	
 	function __get($key)
 	{
 		switch ($key)
 		{
 			
 			case 'visible':
 			 
 			 
 			 if ($this instanceof jyingo_render)
 			  return $this->_visible;
 			 
 			 if (!$this->_visible)
 			  return false;
 			 
 			 if (!$this->get_parent())
 			 {
 			  return true;	
 			 }
 			 
 			 return $this->get_parent()->visible;
 			 
 			break;
 			
 			case 'disabled':

 			 if ($this->_disabled)
 			  return true;
 			 
 			 if (!$this->get_parent())
 			 {
 			  return false;	
 			 }


 			 
 			 return $this->get_parent()->disabled;
 			 
 			break;
 			
 			default:
 			
 			 if (in_array($key, $this->scriptprops))
 			 {
 			 	 
 			 	 return $this->saved_client_vars[$key];
 			 	 
 			 }
 			
 			 return parent::__get($key);
 			break;
 			
 		}
 	}
 	
 	function client_call($function)
 	{
 		
 		global $env;
 		
 		$params = array_slice(func_get_args(), 1);
	  $env->script_call_array('jyingo.get_by_instance(\''.$this->get_instance().'\').'.$function, $params, $this->get_instance());
			
 	}
 	
	function set_client_instance($classname)
	{
	 		
	 		$this->clientclass = $classname;
	 		
	}
	
	function update_client_prop($key)
	{
		$this->changed[] = $key;
		$this->property_changed($key);
	}
 	
 	function recursive_property_change($prop)
 	{
 		 global $env;
 		 if ($prop == 'disabled')
 		 {
 		  
 		   if ($this->$prop)
 		    $this->add_class('disabled');
 		   else
 		    $this->remove_class('disabled');
 		  	
 		 }
 		 
 		 if ($this->last_recurisve_props[$prop] != $this->$prop)
 		 {
 		   
 		   
 		   $this->update_client_prop($prop);
 		   
 		   
 		   foreach ($this->children as $child)
 		    $child->recursive_property_change($prop);
 		   
 		   	
 		 }
 		 
 	}
 	
 	function __set($key, $value)
 	{
 		

 	  global $env;
 	  
 		switch ($key)
 		{
 			
 			case 'visible':
 			 $this->_visible = ($value ? true : false);
       $this->recursive_property_change('visible');
 			break;
 			
 			case 'disabled':
 			 $this->_disabled = ($value ? true : false);
       $this->recursive_property_change('disabled'); 			 

	 		 if (!$env->is_postback())
	 		  $this->update_client_data();
       
 			break;
      
      default:

       
       if (in_array($key, $this->scriptprops))
       {
       	
	       if ($this->$key != $value)
	       {
	       	
	         $this->saved_client_vars[$key] = $value;
	       	 $this->changed[] = $key;
	       	 $this->property_changed($key);
	       }
       	 return;
       } 
      
       parent::__set($key, $value);
      break;
      
 		}
 		
 	}
 	
 	function property_changed($key)
 	{
 		
 	}
 	
 	function set_property_array($arr, $htmlname = false)
 	{  
 		 foreach ($arr as $key => $value)
 		 {
  
	 		  if ($key == 'name' && $htmlname)
	 		  {
	 		  	
	 		  	$this->html_name = $value;
	 		  	
	 		  } else {
	 		  	$this->set_property($key, $value);
	 		  }
	 		  
 	   }
 	}
 	

 	function get_property_string($prefix = '')
 	{
 		
 		$out = array();
 		
 		foreach ($this->htmlproperties as $key => $value)
 		{
 			 $out[] = $key.'="'.$value.'"';
 		}
 		

 		 
 		
 		$p = $this->styles;
 		if ($this->_visible == false)
 		{
 			$p['display'] = 'none';
 		}
 		
 		if (count($p))
 		{
 			$s = array();
 			foreach($p as $k => $v)
 			 $s[] = $k.':'.$v.';';
 			
 			$out[] = 'style="'.trim(implode(' ', $s)).'"';
 			
 		}
 		
 		if ($this->html_name)
 		 $out[] = 'name="'.$this->html_name.'"';
 		

 		
 		if ($this->classname)
 		 $out[] = 'class="'.trim($this->classname).'"';


 		$ret = implode(' ', $out);
 		if ($ret)
 		 return $prefix.$ret; 		
 		
 		return '';
 	}
  
  function allow_event($name)
  {
  	$this->_events[] = $name;
  	
  }
  
  function allow_call($name)
  {
  	$this->_calls[] = $name;
  }
  
  function get_style($key)
  {
   
    return $this->styles[$key];	
  	
  }
  
  function set_style($key, $value = NULL)
  {
  	
  	 if (is_array($key))
  	 {
  	 	 
  	 	 foreach ($key as $k => $v)
  	 	  $this->set_style($k, $v);
  	 	 
  	 	 return;
  	  	
  	 }
  	
  	$this->styles[$key] = $value;
  	$this->pushed_styles[$key] = $value;
  	
  }
  function get_event_handler($event)
  {
  	
  	return $this->event_handlers[$event];
  }  
  function set_event_handler($event, $handler)
  {
  	
  	$this->event_handlers[$event] = $handler;
  }
  
  function event($name, $caller = NULL)
  {
  	  
  	  $param = array();
  	  $args = func_get_args();
  	  
  	  if (count($args) > 2)
  	   $param = array_slice($args, 2);
  	
  	  $function_name = $this->get_name();
  	  $function_index = -1;
  	  
  	  if (!$caller)
  	   $caller = $this;
  	  

  	  if (substr_count($function_name,'['))
  	  {
  	  	 list($function_name, $function_index) = explode('[', $function_name, 2);
  	  	 $function_index = intval($function_index);
  	  }
  	  

  	  $function_name .= '_'.$name;
  	  
  	  if ($this->event_handlers[$name])
  	  {
  	  	 $function_name = $this->event_handlers[$name];
  	  }
  	  
  	  if (!is_array($function_name))
  	  {
  	  	
  	  	 if ($this->_delegate)
  	  	 {
  	  	 	 $function_name = array($this->_delegate, $function_name);
  	  	 } else {
  	  	 	
  	  	   
  	  	    $obj = $this->get_parent();
  	  	    while (!($obj instanceof jyingo_module))
  	  	    {
  	  	    	 $obj = $obj->get_parent();
  	  	    	 if (!$obj) break;
  	  	    }
  	  	    
  	  	    $function_name = array($obj, $function_name);
  	  	 	
  	  	 }
  	  	
  	  }
  	  
  	  if ( !$function_name[0] )
  	  {
  	  	 
  	  	 if (!is_callable($function_name[1]))
  	  	  return;
  	  	 
  	  	 if ( $function_index == -1 )
  	  	 {
  	  	 	
  	  	 	$arr = array($caller);
  	  	 	$arr = array_merge($arr, $param);
  	  	 	
  	  	  $ret = call_user_func_array($function_name[1], $arr);	
  	  	 } else {
  	  	 	
  	  	 	$arr = array($caller, $function_index);
  	  	 	$arr = array_merge($arr, $param);
  	  	 	  	  	 	
  	  	 	
  	  	 	$ret = call_user_func_array($function_name[1], $arr);
  	  	 }
  	  	 
  	  	
  	  } else {

  	  	 if (!is_callable($function_name))
  	  	  return;
  	  	
   	  	 if ( $function_index == -1 )
  	  	 {
  	  	 	
  	  	 	$arr = array($caller);
  	  	 	$arr = array_merge($arr, $param);
  	  	 	
  	  	  $ret = call_user_func_array($function_name, $arr);	
  	  	 } else {
  	  	 	
  	  	 	$arr = array($caller, $function_index);
  	  	 	$arr = array_merge($arr, $param);  	  	 	
  	  	 	
  	  	 	$ret = call_user_func_array($function_name, $arr);
  	  	 } 	  
  	  	
  	  	
  	  }
  	  
  	  return $ret;
  	  
  }
  
  private function parse_css_style($value)
  {
  	
  	 $list = explode(';', $value);
  	 foreach ($list as $line)
  	 {
  	 	 
  	 	 list($key, $value) = explode(':', trim($line), 2);
  	 	 $key = trim($key);
  	 	 $value = trim($value);
  	  
  	   $this->styles[$key] = $value;
  	  	
  	 }
  	 
  }
  
  function set_property($key, $value)
  {
  	
  	if ($key == 'name')
  	{
  		 $this->set_name($value);
  		 return;
  	}
  	
  	if ($key == 'disabled')
  	{
  		 $this->disabled = $this->boolean($value);
  		 
  		 if ($this->boolean($value))
  		 {
  		   
  		   $this->htmlproperties['disabled'] = 'disabled';
  		 	
  		 }
  		 
  		 return;
  	}
  	
  	if ($key == 'style')
  	{
  	   $this->parse_css_style($value);
  	   return;	
  	}
  	
  	if ($key == 'visible')
  	{
  		 $this->visible = $this->boolean($value);
  		 return;
  	}
    
    if ($key == 'allow_postback')
    {
    	 $this->allow_postback = $this->boolean($value);
    	 return;
    }
    
    if ($key == 'class')
    {
    	
    	
    	$this->add_class($value);

    	return;
    	
    }
  	
  	$this->htmlproperties[$key] = $value;
  	$this->pushed_html_props[$key] = $value;
  	
  }
  
  function has_class($classname)
  {
  	
     $classes = explode(' ', $this->classname);
     return in_array($classname, $classes);
  }
  
  
  function set_class($classname)
  {
  	

  	$this->classname = $classname;

  }
  
  function add_class($classname)
  {

     
     $classes = explode(' ', $this->classname);
     
     if (in_array($classname, $classes))
      unset($classes[array_search($classname, $classes)]);
     
     $classes[] = $classname;
     
     $this->classname = trim(implode(' ', $classes));
     
     
  }
  
  function dispose()
  {
  
  }
  
  function remove($params = NULL)
  {
  	

  	
  	if ($this->remove_parent != NULL && !$params)
  	{
  		$this->remove_parent->remove();
  	} else {
  	
	  	global $env;

	  	if (!$params)
	  	{
	  		if ($this->_rendered)
	  		 $env->client_do($this->get_instance(), 'remove', array("what" => $this->get_instance()));
	  	  $params = array("begin" => $this->get_instance());
	  	}
	  	
	  	parent::remove($params);
	  	
 		}
  }

  function remove_class($classname)
  {
     
     $classes = explode(' ', $this->classname);
     
     if (in_array($classname, $classes))
      unset($classes[array_search($classname, $classes)]);
        
     $this->classname = implode(' ', $classes);
    // $this->pushed_html_props['class'] = $this->classname;
     
     
  }
  
  
  function boolean($s)
  {
  	if ($s === 'no')
  	 return false;
  	
  	if ($s === 'false' || !$s)
  	 return false;
  	
  	return true;
  	
  }
  
  function set_delegate($delegate)
  {
  	$this->_delegate = $delegate;
  }
  
  function get_delegate()
  {
  	return $this->_delegate;
  } 	
 	
 }
?>