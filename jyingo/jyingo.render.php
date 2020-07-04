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

 class jyingo_render extends jyingo_control {
 	
 	 private $tagname;
 	 protected $show_id = false;
 	 protected $parentize = true;
 	 
 	 function set_tagname($tagname)
 	 {
 	 	 $this->tagname = strtolower($tagname);
 	 }
 	 
 	 function get_instance() {
 	 	
 	 	if ($this->parentize)
 	 	{
 	   $p = parent::get_parent();
 	   return $p->get_instance();
 	  } else {
 	  	return parent::get_instance();
 	  }
 	 
 	 }
 	 
 	 
 	 
 	 function render()
 	 {
 	   $id = '';
 	   if ($this->show_id)
 	    $id = ' id="'.$this->get_instance().'" ';
 	   
     if (count($this->children) == 0 && $this->tagname != 'div' &&  $this->tagname != 'li' && $this->tagname != 'i'&& $this->tagname != 'p' &&  $this->tagname != 'a' && $this->tagname != 'span' && $this->tagname != 'script' && $this->tagname != 'iframe' && $this->tagname != "scr'+'ipt")
 		 {
 	   	 echo '<'.$this->tagname.$id.$this->get_property_string(' ').' />';
 	   	 parent::render(false);
 	   	 
 	   } else {
 	   
 	    echo '<'.$this->tagname.$id.$this->get_property_string(' ').'>';
 	    parent::render();
 	    echo '</'.$this->tagname.'>';
 	   
 	   }
 	 
 	 }
 	
 	
 }
?>
