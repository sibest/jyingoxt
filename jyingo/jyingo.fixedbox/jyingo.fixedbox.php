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
 
// FARE ANCHE LA MODALITà CHECKBOX MENU

class jyingo_fixedbox extends jyingo_control {
	
     public $active_tag = 'jyingo:fixedbox';
     private $offset = 0;
     
     function get_client_data()
     {
      return array("offset" => $this->offset);	
     }
     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
       $this->set_class('fixedbox');
       $this->set_client_instance('jyingo.fixedbox');

     }	
     
     function set_property($key, $value)
     {
      
      	if ($key == 'offset')
      	{
      		$this->offset = intval($value);
      		return;
      	}
      	
      	parent::set_property($key, $value);
     	
     }
          
     function render()
     {

     	 echo '<div id="'.$this->get_instance().'_placeholder" class="fixedbox_placeholder">';
    	 echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
    	 parent::render();
    	 echo '</div>';    
    	 echo '</div>';
    	
     } 
     
     
 } 
 
?>