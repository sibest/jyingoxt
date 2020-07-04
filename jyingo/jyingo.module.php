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

 class jyingo_module extends jyingo_control {
 	 
   private $_messagetypes = array();
   
 	 function __construct($params)
 	 {
 	   parent::__construct($params);
 	   $this->set_client_instance('jyingo.generic');	
 		 $this->add_class('j-'.substr(md5(get_class($this)),0,8));
 	 }
 	 
 	 function register_query($identifier, $query, $params)
 	 {
 	 	
 	 	 global $env;
 	 	 $env->register_query($this, $identifier, $query, $params);
 	   
 	   	
 	 }
 	 
 	 function queried($identifier, $result)
 	 {
 	 	
 	 }	
 	 
 	 function render()
 	 {
	
     echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>'; 
 	   parent::render();
 	   echo '</div>';
 	   
 	 }
 	 
 	 function template($template = NULL)
 	 {
 	 
 	   global $env;

 	   if (is_callable(array($this, 'template_changed')))
 	   {
 	     $this->template_changed();	
 	   }
 	   
 	   $this->clear();
 	   
 	   $class = get_class($this);
 	   
 	   $class = explode('_', $class);
 	   $type = array_shift($class);
 	   
 	//   $class = $type.'.'.implode('.', $class);
 	   
 	  
 	   $env->setup_control_template($this, $type, implode('.', $class), $template);
 	   

 	   
 	 	
 	 }
 	 
 	 function loaded()
 	 {
 	 	
 	 }
   
   function preview()
   {
    $this->afterload();
    parent::preview();	
   }
   
 	 function afterload()
 	 {
 	 	
 	 }
 	 
 	 function onload()
 	 {
 	  
 	 	$this->loaded();
 	  parent::onload();	
 	 }
 	 
  function listen($messagetype)
  {
  	$this->_messagetypes[] = $messagetype;
  }
  
 	function message($messagetype, $data = NULL)
 	{
 	  global $env;
 	  $env->propagate_message($this, $messagetype, $data);
 	}
 	
 	function propagate_message($sender, $messagetype, $data)
 	{

 		if ( in_array($messagetype, $this->_messagetypes) !== FALSE )
 		{
 			$this->message_received($sender, $messagetype, $data);
 		}
 		
 		parent::propagate_message($sender, $messagetype, $data);
 		
 	}
 	 
 	function message_received($sender, $messagetype, $data)
 	{

 	}

 }
?>