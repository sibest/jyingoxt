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
 
 class jyingo_option extends jyingo_control {
   
   public $active_tag = 'php:option';
   public $text = NULL;
   public $value = NULL;
   public $_selected = false;
   
   function __construct($params)
   {
     	 
     	 
     	 
     	 parent::__construct($params);

       
       
       $this->set_client_instance('jyingo.option');
   }
   
   function get_client_data()
   {
    return array("text" => $this->text);	
   }
   
   function render()
   {
    
      if (count($this->children))
      {
       
	       ob_start(); 
	       parent::render();
	       $c = ob_get_contents();
         ob_end_clean();
         
         $this->text = $c;
       	
      } 
      
      
      echo '<option value="'.$this->get_instance().'"></option>'; 

      parent::render(false);    	
    	
   }
   
   function __get($k)
   {
    	
    	if ($k == 'selected')
    	{
    		 
    		 
    		 return $this->_selected;
    		 
    	}
    	
    	return parent::__get($k);
    	
   }
   
   function __set($k, $v)
   {
   	
   	 if ($k == 'selected')
   	 {
   	 	  if ($v == true)
   	 	   $this->select();
   	 	  else
   	 	   $this->unselect();
   	 	  
   	  	return;
   	 }
   	
     parent::__set($k, $v);	
   }
   
   function select()
   {
     $this->client_call('select');	
     $this->_selected = true;
     $this->get_parent()->_select($this);
   }

 	     
   
   function unselect()
   {
     $this->client_call('unselect');	
     $this->_selected = false;
     $this->get_parent()->_unselect($this);  	
   }
   
   function set_property($name, $value)
   {
     
     if ($name == 'value')
     {
      $this->value = $value;	
      $this->set_name($value);
      return;	
     }
     
     if ($name == 'selected')
     {
       $this->_selected = \boolval($value);
       return;	
     }
     
     if ($name == 'text')
     {
       $this->text = $value;
       return;	
     }
     
     parent::set_property($name, $value);	
   	
   }   
   	
 }
 
 class jyingo_select extends jyingo_control {
 	
 
     public $autowidth_default = false;	
      
     public $active_tag = 'php:select';
     private $selected = NULL;
     
     function __construct($params)
     {

     	
     	 parent::__construct($params);
       $this->load_order = LOAD_ORDER_BOTTOM;
       $this->set_client_instance('jyingo.select');
       
       $this->allow_event('change');
       
       $this->allow_postback = false;

     	 $this->client_vars(array(
     	  "autowidth"
     	 ));
         	
     }
     
     function update_value($data)
     {
     	 
     	  
     	  $obj  = $this->get_by_instance($data);
     	  if ($obj && $obj instanceof jyingo_option && $this->selected != $obj)
     	  {
     	  	
     	     $children = $this->get_children('jyingo_option');
	       	 foreach ($children as $child)
	     	  	 $child->_selected = false;
     	  
     	  	 $obj->select();
     	  	 
     	  }
     }

     function get_client_data()
     {
     	 
     	 return array("autowidth" => $this->autowidth);
     }
     
     function _select($element)
     {
       $this->selected = $element;	
     }
     
     function _unselect($element)
     {
       
       if ($this->_selected == $element)
       {
         $this->selected = NULL;
       }		
     	
     }
     
     function get_item_count()
     {
     	 $children = $this->get_children('jyingo_option');
       return count($children);
     }
     
     function get_item($value)
     {
       $children = $this->get_children('jyingo_option');
       foreach ($children as $child)
       {
       	
       	 if ($child->value == $value)
       	  return $child;
        	
       }	
       return NULL;
     }
     
     function set_property($key, $value)
     {

	  	  if ($key == 'autowidth')
	  	  {
	  	    
	  	    $this->autowidth = $this->boolean($value);
	  	    return; 
	  	  }
     	
     	  parent::set_property($key, $value);
     }
     
     
     function get_selected()
     {
     	 
     	 $children = $this->get_children('jyingo_option');
     	 foreach ($children as $child)
     	  if ($child->selected)
     	  	 return $child;
     	  
     	 return NULL;
     	 
     }
     
     function add_item($text = '', $value = false)
     {
        
        if ($value === false)
         $value = $text;
        
      	return $this->load('jyingo.option', array("text" => $text, "value" => $value));
     }
     
     function render()
     {
     	
     	echo '<select name="'.$this->get_instance().'" id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
     	parent::render();
     	echo '</select>';
     	
     }
 	
 	
 }
?>