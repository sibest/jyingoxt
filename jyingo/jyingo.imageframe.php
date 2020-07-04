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
 
 class jyingo_imageframe extends jyingo_control {
      
     public $active_tag = 'php:imageframe';
 
     public $artistic;
     public $src;
     
     private $autosize = NULL;
     private $frame_tag;
     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
       $this->artistic = false;
       $this->allow_postback = false;
       $this->load_order = LOAD_ORDER_BOTTOM;
       $this->add_class('jyingo_imageframe');
       $this->set_client_instance('jyingo.generic');
       $this->frame_tag = $params->tag ? $params->tag : 'a';
     }

     
     function preview()
     {
     	
       
       if ($this->width)
        $this->set_style('width', $this->width.'px');	
        
       if ($this->height)
        $this->set_style('height', $this->height.'px');	       
       
       if ($this->src)
        $this->set_style('background-image', 'url('.$this->src.')');	       
       
    	parent::preview();  
     	
     }     
     
     function set_src($src)
     { 
     	$this->src = $src;
     	$this->set_style('background-image', 'url('.$this->src.')');	  
     }
     
     function render() {


       echo '<'.$this->frame_tag.' id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
       if ($this->autosize) echo '<div style="padding-top: '.$this->autosize.'%"></div>';
       
       parent::render();
       echo '</'.$this->frame_tag.'>';
      

     } 
     
     function set_property($key, $value)
     {
       
       switch ($key)
       {
         case 'src':
          $this->src = $value;
         break;	
         
         case 'tag':
          $this->frame_tag = $value;
         break;	
         
         case 'autosize':
          $this->autosize = intval($value);
         break;
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