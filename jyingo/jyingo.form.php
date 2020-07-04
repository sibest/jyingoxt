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
 
 class jyingo_formfield extends jyingo_control {
      
   public $active_tag = 'php:formfield';
   public $subtitle, $title, $small;
   private $component, $has_error, $error_text;
   private $components = array();
   private $autocomplete = FALSE;
   
   function __construct($params)
   {
     parent::__construct($params);
     $this->add_class('formfield');	
     $this->component = NULL;
     $this->has_error = NULL;
     $this->set_client_instance('jyingo.formfield');
   } 
   
   function onload()
   {
   	 if (!count($this->components))
     $this->search_field();
     parent::onload();	
        	        
     if ($this->autocomplete)
     {
     	 $obj =  $this->load('jyingo.completion', array("target" => $this->components[0]));	
       $obj->set_event_handler('complete', array($this,'complete'));
       $obj->set_event_handler('click', array($this,'complete_click'));
     }
   }
   
   function complete($caller, $text)
   {
   	 $this->event('autocomplete', $this, $caller, $text);
   }
   
   function complete_click($caller, $text)
   {
   	 $this->event('complete_click', $this, $caller, $text);
   }
   
   function preview()
   {
   	  parent::preview();
      $this->search_field();
      
  
       	
   }
   
   function set_option($value)
   {
     foreach ($this->components as $component)
      if ($component->get_option() == $value)
        $component->checked = true;
      
   }
   function get_option()
   {
     foreach ($this->components as $component)
      if ($component->checked)
        return $component->get_option();	
      
      return FALSE;
   }
   
   function set_property($n, $v)
   {
     if ($n == 'title')
     {
       $this->title = $v;
       return;	
     } 	
     
     if ($n == 'autocomplete')
     {
     	
        $this->autocomplete = $this->boolean($v);
       	
     } 
     
     if ($n == 'small')
     {
       $this->small = $this->boolean($v);
       //if ($this->component) $this->component->autowidth = !$this->small;
       return;	
     }
     
     if ($n == 'subtitle')
     {
       $this->subtitle = $v;
       return;	
     } 	
          
     parent::set_property($n, $v);
   } 

 	 function render()
 	 {
 	 	
     echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
     
     if ($this->title)
      echo '<label class="title">'.$this->title.'</label>';
     echo '<div class="formobject">';
       parent::render();	
     echo '</div>';
     
     if ($this->subtitle)
      echo '<label class="subtitle">'.$this->subtitle.'</label>';
     echo '</div>';
   }	
   
   private function search_field($children = NULL)
   {
   	global $env;
     $children = $children !== NULL ? $children->get_children() :  $this->get_children();
     foreach ($children as $child)
     {
       if ($child instanceof jyingo_textbox || $child instanceof jyingo_checkbox || $child instanceof jyingo_radio || $child instanceof jyingo_select || $child instanceof jyingo_dropdown || $child instanceof jyingo_upload)
       {
        $child->set_name('formcomponent_'.$this->name.'_'.count($this->components));
        $this->components[] = $child;
       }
       
       if ($child !== NULL)
       $this->search_field($child);
       
     }
     
     foreach ($this->components as $component)
     {
	     	
	     if ($component instanceof jyingo_select)
	        $component->set_event_handler('click', array($this, 'element_click'));	
	     
	     if ($component instanceof jyingo_upload)
	     {
	        $component->set_event_handler('uploadstart', array($this, 'uploadstart'));
	        $component->set_event_handler('uploadend', array($this, 'uploadend'));
	        $component->set_event_handler('uploaderror', array($this, 'uploaderror'));	
	     }
     
     }
     	
     //if ($component instanceof jyingo_textbox && !$this->small)
     // $component->autowidth = true;
     	
     	
   }
   
   function element_click($caller)
   {
     $this->event('element_click', $this, $caller);
   }
   
   function uploadstart($caller)
   {
   	 $this->event('uploadstart', $this, $caller);
   }
   
   function uploadend($caller)
   {
   	 $this->event('uploadend', $this, $caller);   	
   }
   
   function uploaderror($caller)
   {
   	 $this->event('uploaderror',$this,  $caller);
   }
   
   
   function onstart()
   {
   	 $this->has_error = false;
   	 
   	 foreach ($this->components as $component)
      $component->remove_class('error'); 
   }
   
   function success($text)
   {
   	 $this->error($text, true);
   }
   
   function error($text, $success = false)
   {
     	$this->has_error = true;
     	$this->error_text = $text;
     	
     	if ($this->has_loaded())
     	 $this->setup_error_text($success);
     	
     	$this->get_form_object()->add_error($text);
     	
   }
   
   private function get_form_object()
   {
   	 $parent = $this->get_parent();
   	 while (!($parent instanceof jyingo_form))
   	  $parent = $parent->get_parent();
   	 
   	 return $parent;
   }
   
   private function setup_error_text($success = false)
   {
     	
   	  global $env;
   	  
     	if (!$this->get('tip'))
     	{
     		
     		  $tip = $this->components[0]->load('tip:jyingo.tooltip', array("fixed" => true, "text" => $this->error_text));
     		  $tip->add_class($success ? 'green' : 'red');
     		  
     	} else {
     		
     		  $this->get('tip')->text = $this->error_text;
     		  $this->get('tip')->add_class($success ? 'green' : 'red');
     	}   	
     	
     	if (!$success)
     	foreach ($this->components as $component)
     		$component->add_class('error');
   }
   
   function loaded()
   {
     
     if ($this->has_error && $this->error_text)
       $this->setup_error_text();

   }
   
   function postview()
   {
   	 parent::postview();
     if (!$this->has_error && $this->get('tip'))
     {
      $this->get('tip')->remove();
     }
   }
   
   function field($index = 0)
   {
     return $this->get_form_item($index);
   }
   
   function get_form_item($index = 0)
   {
   	 return $this->components[$index];
   }
     
 }
 
 class jyingo_form extends jyingo_control {
      
   public $active_tag = 'php:form';
   private $last_element = NULL;
   private $errorlist;
   
   function __construct($params)
   {
     parent::__construct($params);
     $this->add_class('jyingo_form');	
     $this->set_client_instance('jyingo.frame');
     
   }      
   
   function onstart()
   {
   	$this->errorlist = array();
   }
   
   function errors()
   {
     return $this->errorlist;	
   }
   
   function add_error($error)
   {
     $this->errorlist[] = $error;	
   }
   
   public function autocomplete($formfield, $caller, $text)
   {
     $this->event('autocomplete', $this, $formfield, $caller, $text);	
   }
   
   private function setup_last_element()
   {
   	 parent::preview();
     $ordered = $this->get_children_ordered();
     
     for ($i = 0; $i < count($ordered); $i++)
     {
     	 $obj = $ordered[$i];
     	 if ($obj instanceof jyingo_formfield)
     	 {
     	 	 $obj->remove_class('last');


         $obj->set_event_handler('uploadstart', array($this, 'uploadstart'));
         $obj->set_event_handler('uploadend', array($this, 'uploadend'));
         $obj->set_event_handler('uploaderror', array($this, 'uploaderror'));	
         $obj->set_event_handler('element_click', array($this, 'element_click'));	
         $obj->set_event_handler('autocomplete', array($this, 'autocomplete'));	
         $obj->set_event_handler('complete_click', array($this, 'autocomplete_click'));	
     	  
     	 }
     }
     
     while (count($ordered))
     {
       $last =  array_pop($ordered);
       if (!($last instanceof jyingo_formfield)) continue;
       
       $last->add_class('last');	
       break;
     }	
   }
   
   function autocomplete_click($formfield, $caller, $value)
   {
   	
     $this->event('autocomplete_click', $this, $formfield, $caller, $value);	
   }
   
   function uploadstart($field, $caller)
   {
     $this->event('uploadstart', $this, $field, $caller);	
   }
   function uploadend($field, $caller)
   {
     $this->event('uploadend', $this, $field,  $caller);	
   }
   function uploaderror($field, $caller)
   {
     $this->event('uploaderror', $this, $field,  $caller);	
   }      

   function element_click($field, $caller)
   {
     $this->event('click', $this, $field,  $caller);	
   }      
   
   function add_child($element)
   { 
   	  
   	  parent::add_child($element);
   	  $this->setup_last_element();
   	  
   }
   
   function form($name)
   {
       return $this->get($name);	
   }
   
   function field($name)
   {
     
       return $this->get($name)->get_form_item();
    	
   }
   
   
   
 	 function render()
 	 {
 	 	
     echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
       parent::render();	
     echo '</div>';
   }	
 } 
 
?>