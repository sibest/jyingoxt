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

 class jyingo_radio extends jyingo_control {
 	
     public $active_tag = 'php:radio';
     public $text_default = '';
     public $checked_default = FALSE;
     private $send_change_event = FALSE;
     private $content = NULL;
     private $content_entity = FALSE;
     private $textchanged = FALSE;
     private $group = 'default';
     private $option;
     
     function __construct($params)
     {
       parent::__construct($params);
       $this->set_client_instance('jyingo.radio');
       
     	 $this->client_vars(array(
     	  "text", "checked"
     	 )); 
      
       $this->allow_postback = false;
       $this->allow_event('click');
     //  $this->set_style("vertical-align", "middle");
     }
      	
      	
     function render()
     {
     	 echo '<div class="jyingo_radio"'.$this->get_property_string(' ').'>';
       echo '<input type="radio" name="'.$this->group.'" id="'.$this->get_instance().'" '.($this->checked ? 'checked="checked"' : '').'" />';
       echo '<label id="_'.$this->get_instance().'" for="'.$this->get_instance().'">';
       
       if (count($this->children) && !$this->textchanged)
       {
       
	       ob_start(); 
	       parent::render();
	       $c = ob_get_contents();
         ob_end_clean();
         echo $c;
         
       } else {
         
         echo $this->text;	
       	
       } 
       
       echo '</label>';
       echo '</div>';
       
	       parent::render(false);
       
     }	
     
     function get_option()
     {
       return $this->option;	
     }
     
     function onstart()
     {
     	 if ($this->send_change_event)
     	 {
        $this->event('change');	
        $this->send_change_event = FALSE;
       }
     }
 	   
 	   function update_value($value)
 	   {
 	   	global $env;
 	   	
 	   	$old_value = $this->checked;
 	   	$this->checked = $this->boolean($value);
 	   
 	   	if ($old_value != $this->checked)
 	   	 	$this->send_change_event = TRUE;
 	   	
 	   }
     
     function set_property($key, $value)
     {
       
       switch ($key)
       {

        case 'text':
          $this->content = $value;
          $this->content_entity = TRUE;
	 		   	$this->textchanged = TRUE;

         break;

         case 'group':
           $this->group = $value;
         break;
         
         case 'option':
          $this->option = $value;
         break;

         case 'value':
         
         
          $this->textchanged = TRUE;
          $this->content = $value;
          $this->content_entity = FALSE;
         break;     
         
         case 'checked':
          $this->checked = $this->boolean($value);
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
          $this->textchanged = TRUE;
          
          $this->update_client_prop('text');
          
         return;
         

         case 'value':
          $this->content = $value;
          $this->content_entity = FALSE;
        	$this->textchanged = TRUE;  
        	
        	$this->update_client_prop('text');
        	
         return;  
       	 
       }
       
       parent::__set($key, $value);	
     	
     }
 }
?>