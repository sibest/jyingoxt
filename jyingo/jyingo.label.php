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
 
 class jyingo_label extends jyingo_control {
      
     public $active_tag = 'php:label';
     public $href = NULL;
     private $content = NULL;
     private $content_entity = FALSE;
     private $textchanged = FALSE;
     private $tagname = 'span';
     
     function __construct($params)
     {
     	 
     	 
     	 parent::__construct($params);
     	 $this->client_vars(array(
     	  "text"
     	 ));

       $this->set_client_instance('jyingo.label');
  
     }
     

	 	 
     function render() {
           
       echo '<'.$this->tagname.' id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
       
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
       
       echo '</'.$this->tagname.'>';
       
       parent::render(false);
      

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
         
         case 'tag':
          $this->tagname = $value;
         break;

         case 'value':
         
         
          $this->textchanged = TRUE;
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
     	  
     	  case 'tag':
     	   return $this->tagname;
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
       	 
       	 case 'tag':
       	  $this->tagname = $value;
       	 break;
       	 
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