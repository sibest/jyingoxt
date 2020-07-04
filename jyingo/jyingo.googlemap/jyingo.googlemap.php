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
 
  class jyingo_googlemap extends jyingo_control {
  	
    public $active_tag = 'google:map';
    public $markers = array();
    private $color;
    
    public $width_default = 0;
    public $height_default = 0;
    
    function __construct($params)
    {
  
     	 parent::__construct($params);

     	 $this->client_vars(array(
     	   'width', 'height'
     	 ));
     	 
       $this->set_client_instance('jyingo.googlemap');    	
    	
    }
    
    function render()
    {
    	
    	if ($this->width)
    		$this->set_style('width', $this->width.'px');
    	
    	if ($this->height)
    		$this->set_style('height', $this->height.'px');
    	
    	echo '<div id="'.$this->get_instance().'" class="'.$this->classname.'"'.$this->get_property_string(' ').'></div>';
    	parent::render(false);
    }
    
    function get_client_data()
    {
     	return array("markers" => $this->markers, "color" => $this->color);
    }
    
    function add_marker($position)
    {
    	$this->markers[] = $position;    	
    }
    
    function set_markers_color($color)
    {
    	$this->color = $color;
    }
    
    function add_markers($markers)
    {
    	$this->markers = array_merge($markers, $this->markers);
    }
    
     function set_property($key, $value)
     {
       
       switch ($key)
       {

         
         case 'width':
          $this->width = intval($value);
         break;
         
         case 'height':
          $this->height = intval($value);
         break;
                  

         
         default:
          parent::set_property($key, $value);
         
       }	
     	
     }
  	
  }

?>