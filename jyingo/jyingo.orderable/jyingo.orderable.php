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
 
 class jyingo_orderable extends jyingo_control {
 	 
   public $active_tag = 'php:orderable';
 	 public $tagname = 'div';
 	 public $class2n = false;
 	 public $filter = '';
 	 
   function __construct($params)
   {
    parent::__construct($params);
 	  $this->set_client_instance('jyingo.orderable');
 	  $this->allow_call('_sorted');
 	 }
 	 
 	 function _sorted($data)
 	 {
 	 	 global $env;
 	 	 $objects = array();
 	 	 foreach ($data as $item)
 	 	 {
 	 	   $obj =	($env->get_by_instance($item));
 	 	   if ($obj) $objects[] = $obj;
 	 	 }
 	 	 
 	 	 $this->event('sorted', $this, $objects);
 	 	 return true;
 	 }
 	 
 	 function update()
 	 {
 	  $this->client_call('update');	
 	 }
 	 
 	 function get_client_data()
 	 {
 	  return array("class2n" => $this->class2n, "filter" => $this->filter);	
 	 }
 	 
 	 function render()
 	 {
 	 	
     echo '<'.$this->tagname.' id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
     
     if ($this->class2n)
     {
       	echo '<'.$this->tagname.' class="'.$this->class2n.'">';
     }
     
 	   parent::render();
 	   
 	   echo '<div style="clear:left"></div>';
 	   
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
 	 	 
 	 	 if ($k == 'filter')
 	 	 {
 	 	   $this->filter = $v;
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