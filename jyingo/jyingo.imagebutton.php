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
 class jyingo_imagebutton extends jyingo_control {
      
     public $active_tag = 'php:imagebutton';

     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
     	 $this->client_vars(array(
     	  "src"
     	 ));
     	 
     	 $this->add_class('jyingo_image_button');

       $this->set_client_instance('jyingo.imagebutton');
       $this->allow_event('click');	
     
       
     }



     function render() {
           
       echo '<img src="'.$this->src.'" id="'.$this->get_instance().'"'.$this->get_property_string(' ').' alt="" />';
       
       parent::render(false);
      

     } 
     
     function set_property($key, $value)
     {
       
       switch ($key)
       {
         case 'src':
          $this->src = $value;
         break;	
     
         
         default:
          parent::set_property($key, $value);
         
       }	
     	
     }
     





 }

?>