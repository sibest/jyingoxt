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
 
 class jyingo_button extends jyingo_control {
      
     public $active_tag = 'php:button';
     private $content = NULL;
     private $content_entity = FALSE;
     public $default = false;
     private $text_changed = FALSE;
     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
     	 $this->client_vars(array(
     	  "icon", "text"
     	 ));

       $this->set_client_instance('jyingo.button');
       $this->allow_event('click');	
   //    $this->set_class('jyingo_button');
     
       
     }
     
     function set_default()
     {
       $this->default = true;
       $this->add_class('default');	
     }

     function unset_default()
     {
       $this->default = false;
       $this->remove_class('default');	
     }

      function render() {
       
       if ($this->default)
        $this->add_class('default');
       if ($this->cancel)
        $this->add_class('cancel');

       
       echo '<input type="button" id="'.$this->get_instance().'"'.$this->get_property_string(' ').' value="'.$this->content.'">';

       
       
       parent::render(false);
      

     } 
     
     function set_property($key, $value)
     {
       
       switch ($key)
       {
         case 'icon':
          $this->icon = $value;
         break;	
         
         case 'text':
          $this->content = $value;
          $this->content_entity = TRUE;
         break;
         
         case 'default':
          $this->default = $this->boolean($value);
          
         break;
   
         case 'cancel':
          $this->cancel = $this->boolean($value);
         break;
         
         case 'value':
         
         
         
          $this->content = $value;
          $this->content_entity = FALSE;
         break;         
         
         default:
          parent::set_property($key, $value);
         
       }	
     	
     }
     
     function __get($key)
     {
     	 global $env;
     	 
     	 switch ($key)
     	 {
     	  
     	  case 'text':
     	   
     	   if ($this->content_entity == TRUE)
     	    return $env->entities($this->content);
     	   else
     	    return $this->content;
     	   
     	  break;
     	  
     	  case 'value':
     	   return $this->content;
     	  break;	
     	 	
     	 	default:
     	 	 return parent::__get($key);
     	 	
     	 	
     	 }
     	 
     }	
     
     function __set($key, $value)
     {
      
       switch ($key)
       {
       	
         case 'text':
          $this->content = $value;
          $this->content_entity = TRUE;
          $this->text_changed = TRUE;
          
         return;
         

         case 'value':
          $this->content = $value;
          $this->content_entity = FALSE;
          $this->text_changed = TRUE;

         return;  
       	 
       }
       
       parent::__set($key, $value);	
     	
     }




 }

?>