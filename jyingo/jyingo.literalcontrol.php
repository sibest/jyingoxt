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

 class jyingo_literalcontrol extends jyingo_control {
 	
 	 public $active_tag = 'php:static';
   public $value = NULL;
 	 
 	 function set_property($key, $value)
 	 {
 	  
 	  if ($key == 'value')
 	  {
 	  	 $this->value = $value;
 	  	 return;
 	  }	
 	  
 	  parent::set_property($key, $value);
 	 	
 	 }
 	 
 	 
 	 function render()
 	 {
 	   
 	   
 	   echo $this->value;
 	   parent::render(false);
 	   
 	 
 	 }
 	 
 	 function set_value($value)
 	 {
 	 	 $this->value = $value;
 	 }
 	
 	
 }
?>