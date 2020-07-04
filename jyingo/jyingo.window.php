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

 class jyingo_window extends jyingo_module {
 	
 	
  private $_showing = false;
 	
 	function __construct($params)
 	{
 		parent::__construct($params);

 		$this->set_client_instance('jyingo.generic');		
 		$this->add_class('jyingo_window');
 		$this->add_class('window_'.$this->get_window_type());
 		$this->add_class('j-'.substr(md5(get_class($this)),0,8));
 	}
 	
 	function show()
 	{
 		
 		if ($_showing)
 		 return;
 		
 		$_showing = true;
 		
 		jyingo_windowmanager::get_mgr()->showing($this);
 		 		
 	}
 	
 	 function get_window_type()
 	 {
 	  return 'page';	
 	 }
 	 
 	 function get_transition_style()
 	 {
 	  return 'none';	
 	 }
 	
 	function get_url()
 	{
 		return 'http://localhost/index.php';
 	}
 	
 	function onload()
 	{
 		global $env;
 			$env->client_do($this->get_instance(), "loadwnd", array(
 			"name" => $this->get_name(),
 			"transition" => $this->get_transition_style(),
 			"type" => $this->get_window_type(),
 			"instance" => $this->get_instance(),
 			"parent" => $this->get_parent()->get_instance())); 		
 		parent::onload();
 		
 	}
 	
 	function set_title($title)
 	{
 		
 	}
 }
?>