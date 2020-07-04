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

class jyingo_tooltip extends jyingo_control {
   
   public $text, $position = 'auto', $clickremove = false, $attacher = NULL, $fixed = NULL, $interactive = NULL, $maxwidth = NULL, $event_tracker = NULL;
   private $_set;
   function __construct($params)
   {
   	 parent::__construct($params);
   	 $this->set_client_instance('jyingo.tooltip');

  	 $this->client_vars(array(
     	  "text"
     	 ));
      
     $this->_set = false;
     $this->timeout = $params->timeout ? $params->timeout : -1;
     $this->event_tracker = $params->event ? $params->event : NULL;
     $this->clickremove = !$params->clickhang;
     $this->fixed = $params->fixed ? $params->fixed : false;
     $this->interactive = $params->interactive ? $params->interactive : false;
     $this->maxwidth = $params->maxwidth ? $params->maxwidth : 240;
   }
    
   function render()
   {
     echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>'; 
     echo '<div class="content">';
		  parent::render();
		 echo '</div>';
 	   echo '</div>';   	 
   }
   
   function set_parent($object)
   {
     parent::set_parent($object);
    
     if (!$this->_set && $this->get_parent()->get_parent())
     {
     	 
     	 $this->attacher = $this->get_parent()->get_instance();
     	 $parent = $this->get_parent()->get_parent();
     	 
     	 $this->drop();
     	 $this->_set = true;
     	 
     	 $parent->add_child($this);
       
     }
     	
   }
   
   function get_client_data()
   {
    return array("text" => $this->text, "tracker" => $this->event_tracker, "fixed" => $this->fixed, "maxwidth" => $this->maxwidth, "interactive" => $this->interactive, "timeout" => $this->timeout, "position" => $this->position, "clickremove" => $this->clickremove, "attacher" => $this->attacher);	
   }
   
   function set_property($k, $v)
   {
     if ($k == 'text')
     {
       $this->text = $v;
       return;	
     }		
     
     if ($k == 'fixed')
     {
       $this->fixed = $this->boolean($v);
       return;	
     }
     
     if ($k == 'clickremove')
     {
       $this->clickremove = $this->boolean($v);
       return;	
     }
     
     if ($k == 'position')
     {
      $this->position = $v;
      return;	
     }
     
     parent::set_property($k, $v);
   }

}
?>