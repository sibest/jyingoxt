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
 
  class jyingo_progress extends jyingo_control {
    
    public $active_tag = 'php:progress';
    public $value_default = 0;
    public $width_default = 150;
    public $max_default = 100;
    public $classname = 'jyingo_progress';
    public $text_default = '';
    
    function __construct($params)
    {

     	 parent::__construct($params);
     	 $this->client_vars(array(
     	   'value', 'width', 'max', 'text'
     	 ));

       $this->set_client_instance('jyingo.progress');    	
    	
    }
    
    function render()
    {
    	
    	$this->set_style('width', $this->width.'px');
    	
    	echo '<div id="'.$this->get_instance().'" class="'.$this->classname.'"'.$this->get_property_string(' ').'></div>';
    	parent::render(false);
    }
    
    function get_client_data()
    {
     	return array("value" => $this->value, "width" => $this->width, "max" => $this->max, "text" => $this->text, "class" => $this->classname);
    }
    
     function set_property($key, $value)
     {
       
       switch ($key)
       {
         
         case 'value':
          $this->value = intval($value);
         break;
         
         case 'max':
          $this->max = intval($value);
         break;
         
         case 'width':
          $this->width = intval($value);
         break;
         
         case 'text':
          $this->text = $value;
         break;
         
         default:
          parent::set_property($key, $value);
         
       }	
     	
     }
    
    
  }
 ?>