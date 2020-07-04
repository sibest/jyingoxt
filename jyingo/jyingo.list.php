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
 
 class jyingo_list_item extends jyingo_render {
  
     function __construct($params)
     {
     	 
     	 
     	 $this->parentize = false;
     	 
     	 parent::__construct($params);
       $this->set_client_instance('jyingo.generic');
       $this->set_tagname('li');
       
       
       $this->show_id = true;

     } 	
 	
 }
 
 class jyingo_list extends jyingo_control {
      
     public $active_tag = 'php:list';
     private $content = NULL;
     private $content_entity = FALSE;
     private $references = array();
     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
       $this->set_client_instance('jyingo.generic');
     
     
       
     }



     function render() {
       

       
       echo '<ul id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
       parent::render();
       echo '</ul>';
       
      

     } 
     
     function get_item($key)
     {
      
      if (isset($this->references[$key]))
       return $this->references[$key][0];
     	
     }
     
     function add_child($object, $icon = NULL, $key = NULL)
     {
     	  global $env;
     	  if (is_string($object))
     	  {
     	  	
     	  	$obj = $env->load('jyingo_label');
     	  	$obj->text = $object;
     	  } else {
     	  	$obj = $object;
     	  }
     	  
     	  $container = $env->load('jyingo_list_item');

     	  if ($icon)
     	  {
     	  	
     	  	$obj_icon = $env->load('jyingo_image');
     	  	$obj_icon->src = $icon;
     	  	$container->add_child($obj_icon);
     	  	
     	  }
     	  
     	  $container->add_child($obj);
     	  
     	  if ($key)
     	   $this->references[$key] = array($obj, $obj_icon);
     	  else
     	   $this->references[count($this->references)] = array($obj, $obj_icon);
     	   
     	  parent::add_child($container);
     	  return $container;
      	
     }
     
     




 }

?>