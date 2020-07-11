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
 class jyingo_child extends jyingo_tree {
 	
 	  
 	  private $name = NULL;
 	  private $obj_name = NULL;
 	  private $child_ref_cache = array();
 	  
 	  
 	  function set_name($name)
 	  {
 	  	 global $env;
 	  	 
 	  	 if ($this->name != NULL)
 	  	  return false;
 	  	  
 	  	 if ($name && (substr($name,0,1) == '/' && substr($name, strlen($name)-1, 1) == '/'))
 	  	 {
          
         $name = substr($name, 1, strlen($name) - 2);
         
	 	  	 $this->name = $name;
	 	  	 $this->obj_name = $name;
	  	 	
 	  	 } else {
 	  	 
	 	  	 $this->name = $name;
	 	  	 $this->obj_name = $env->get_clsid($name);
			 
			 }
			  	  	 
 	  	 $env->setup_child( $this->obj_name, $this );
 	  	 
 	  	 return true;
 	  	 
 	  }
 	  

 	  
 	  function free()
 	  {
 	  	 $this->name  = NULL;
 	  	 $this->obj_name = NULL;
 	  	 $this->child_ref_cache  = NULL;
 	  	 parent::free();
 	  }
 	  
 	  function is_instance($id)
 	  {

 	  	 return ($id === $this->obj_name);
 	  	
 	  }
 	  
 	  function get_name()
 	  {
 	  	
 	  	return $this->name;
 	  	
 	  }

 	  function get_instance()
 	  {
 	  	
 	  	return $this->obj_name;
 	  	
 	  }
 	  
 	  function has_name($name)
 	  {
 	  	 
 	
 	  	 if ($name == $this->name)
 	  	  return true;
 	  	 else
 	  	  return false;
 	  }
 	  
 	  function drop($dispose = true)
 	  {
 	  	
    	if ($this->get_parent())
    	 $this->get_parent()->remove_child_ref_recursive($this->get_instance(), $this->get_name());
    	 
    	
    	parent::drop($dispose);
 	  }


    function remove_child_ref_recursive($instance, $name)
    {

    	if (isset($this->child_ref_cache[$name]))
    	 unset($this->child_ref_cache[$name]);
  
    	if ($this->get_parent())
    	 $this->get_parent()->remove_child_ref_recursive($instance, $name);
    
    }

    function remove($params)
    {
    	global $env;
    	$env->delete_child( $this->obj_name );
    	
    	if ($this->get_parent())
    	 $this->get_parent()->remove_child_ref_recursive($this->get_instance(), $this->get_name());
    	 
    	
    	parent::remove($params);
    }
 	  
 	  function get_by_instance($instance_id)
 	  {
 	  	global $env;
 	  	return $env->get_by_instance($instance_id);
 	  	
 	  }
 	  
 	  function __set($key, $value)
 	  {
 	  	 $this->$key = $value;
 	  }
 	  
 	  function __get($key)
 	  {
 	  	if (!$key) {
 	    		$e = new Exception; 
				var_dump($e->getTraceAsString());
		  }
 	  	return $this->$key;
 	  }
 	  
 	  function count($object_name = NULL)
 	  {
 	  	
 	  	if (!$object_name)
 	  	 return parent::count();
 	  	
 	  	$count = 0;
 	  	
 	  	foreach ($this->children as $child)
 	  	{
 	  		
 	  		 
 	  		 $name = $child->get_name();
 	  		 if (strlen($name) > $object_name && substr($name, 0, strlen($object_name)+1) == $object_name.'[')
 	  		  $count++;
 	  		
 	  	}
 	  	
 	  	if ($count)
 	  	 return $count;
 	  	
 	  	foreach ($this->children as $child)
 	  	{
 	  		
 	  		
 	  		if (($ret = $child->count($object_name)))
 	  		 return $ret;
 	  		
 	  	}
 	  	
 	  	return 0;
 	  }
 	  
 	  function get($object_name, $index = -1)
 	  {
 	  	
 
 	  	if ($index == -1)
 	  	 $object = $object_name;
 	  	else
 	  	 $object = $object_name.'['.$index.']';
 	  	 
 	  	
 	  	if (isset($this->child_ref_cache[$object]))
 	  	 return $this->child_ref_cache[$object];
 	  	 
 	  	
 	  	foreach ($this->children as $child)
 	  	{
 	  		 
 	  		 if ($child->has_name($object_name) && $child->index == $index)
 	  		 { 
 	  		 	$this->child_ref_cache[$object] = $child;
 	  		  return $child;
 	  		 }
 	  	}
 	  	
 	  	foreach ($this->children as $child)
 	  	{
 	  		$obj = $child->get($object_name, $index);
 	  		
 	  		if ($obj)
 	  		{
 	  			
  	  		$this->child_ref_cache[$object] = $obj;
 	  		  return $obj;	  			
 	  			
 	  		}
 	  	}
 	  	
 	  	return NULL;
 	  	
 	  }

	 	function render()
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->render();
	 		}
	 	}
	 	
	 	function postback_start()
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->postback_start();
	 		}
	 	}


	 	function postback_render()
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->postback_render();
	 		}
	 	}

		function propagate_message($caller, $messagetype, $data)
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->propagate_message($caller, $messagetype, $data);
	 		}	 		
	 	}

	 	function postback_load()
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->postback_load();
	 		}
	 	}

	 	function preview()
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->preview();
	 		}
	 	}

	 	function postview()
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->postview();
	 		}
	 	}

	 	function postback_preview()
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->postback_preview();
	 		}
	 	}

	 	function finalize()
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->finalize();
	 		}
	 	}

	 	function onload()
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->onload();
	 		}
	 	}
	 		 	


	 	function render_handlers()
	 	{
	 		foreach ($this->children as $child)
	 		{
	 			 $child->render_handlers();
	 		}
	 	}
 	
 	

 }
?>