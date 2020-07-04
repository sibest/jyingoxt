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

 class jyingo_frame extends jyingo_control {
 	
 	 public $active_tag = 'php:frame';
 	 public $tagname = 'div';
 	 public $class2n = false;
 	 public $clickable;
 	 
   function __construct($params)
   {
    parent::__construct($params);
    
    $this->allow_postback = false;
 	  $this->set_client_instance('jyingo.frame');
 	 }
 	 
 	 function onload()
 	 {
 	 	
 	 	 if ($this->clickable)
 	    $this->allow_event('click');
 	 	
 	   parent::onload();
 	   	
 	 }
 	 
 	 function get_client_data()
 	 {
 	  return array("class2n" => $this->class2n, "clickable" => $this->clickable);	
 	 }
 	 
 	 function render()
 	 {
 	 	
     echo '<'.$this->tagname.' id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
     
     if ($this->class2n)
     {
       	echo '<'.$this->tagname.' class="'.$this->class2n.'">';
     }
     
 	   parent::render();
 	   
 	   
     if ($this->class2n)
     {
     	 echo '</'.$this->tagname.'>';
     }  	
        
 	   echo '</'.$this->tagname.'>';
 	   
 	 }
 	 
 	 function expand()
 	 {
 	 	 $this->client_call('expand');
 	 } 
 	 
 	 function collapse()
 	 {
 	 	$this->client_call('collapse');
 	 }
 	 
 	 function set_property($k, $v)
 	 {
 	 	
 	 	 if ($k == 'tag')
 	 	 {
 	 	   $this->tagname = $v;
 	 	   return;	
 	 	 }
 	 	 
 	 	 if ($k == 'clickable')
 	 	 {
 	 	   $this->clickable = $this->boolean($v);
 	 	   return;	
 	 	 }
 	 	 
 	 	 if ($k == 'instance')
 	 	 {
 	 	  
 	     $this->set_client_instance($v);	
 	     return;
 	     
 	 	 }
 	 	 
 	 	 if ($k == 'class2n')
 	 	 {
 	 	   $this->class2n = $v;
 	 	   return;	
 	 	 }
 	 	
 	   parent::set_property($k, $v);	
 	 }
 	

 }
?>