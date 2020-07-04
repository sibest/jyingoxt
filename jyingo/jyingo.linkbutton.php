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
 
 class jyingo_linkbutton extends jyingo_control {
      
     public $active_tag = 'php:linkbutton';
     private $content = NULL;
     private $href = '#';
     private $content_entity = FALSE;
     public $default = false;
     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
     	 $this->client_vars(array(
     	  "icon", "text"
     	 ));

       $this->set_client_instance('jyingo.linkbutton');
       $this->allow_event('click');	
       $this->set_class('jyingo_linkbutton');
     
       
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

       
       echo '<a href="'.$this->href.'" id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
         
       if ($this->icon) 
      	 echo '<i class="'.$this->icon.'"></i>';	
       
       echo '<span>'.$this->text.'</span>';	
       echo '</a>';
       
       
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
         
         case 'href':
          $this->href = $value;
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
          
         return;
         

         case 'value':
          $this->content = $value;
          $this->content_entity = FALSE;

         return;  
       	 
       }
       
       parent::__set($key, $value);	
     	
     }




 }

?>