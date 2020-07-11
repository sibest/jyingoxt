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
 
 class jyingo_link extends jyingo_control {
      
     public $active_tag = 'php:link';
     
     public $href_default = '#';
     private $content = NULL;
     private $content_entity = FALSE;
     
     private $text_changed = FALSE;
     
     public $touchstart = false;
     public $anchor;
     public $paging;
     public $loader = false;
     public $icon;
     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
     	 $this->client_vars(array(
     	  "href", "text", "loader"
     	 ));

       $this->paging = $params->paging ? $params->paging : false;
       $this->set_client_instance('jyingo.link');
       $this->allow_event('click');	
       $this->add_class('jyingo_link');
      
       
     }
     
     function anchorize($smooth = true)
     {
      $this->client_call('anchorize', $smooth);	
     }


     function render() {
            
       echo '<a '.($this->anchor ? 'name="'.$this->anchor.'" ' :'').'href="'.$this->href.'" id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
       
       if ($this->icon) 
      	 echo '<i class="'.$this->icon.'"></i>';	
  
       if (count($this->children) && !$this->text_changed)
       {
       
	       ob_start(); 
	       parent::render();
	       $c = ob_get_contents();
         ob_end_clean();
         echo $c;
         
       } else {
         
         if ($this->icon) echo '<span>';
         echo $this->text;	
         if ($this->icon) echo '</span>';
       	
       } 
       
       echo '</a>';
       
       parent::render(false);
      

     } 
      
     function get_client_data()
     {
     	$output = array();
     	if ($this->href) $output['href'] = $this->href;
     	if ($this->paging) $output['paging'] = true;
     	if ($this->touchstart) $output['touchstart'] = true;
     	if ($this->loader) $output['loader'] = true;
     	
      return $output;
     }
     
     function set_property($key, $value)
     {
       
       switch ($key)
       {
         case 'href':
          $this->href = $value;
          $this->update_client_prop('href');
         break;	
         
         case 'paging':
          $this->paging = $this->boolean($value);
         break;
         case 'loader':
          $this->loader = $this->boolean($value);
         break;
         
         case 'icon':
          $this->icon = $value;
         break;
         
         case 'text':
          $this->content = $value;
          $this->content_entity = TRUE;
          $this->text_changed = TRUE;
          
         break;
         
         
         case 'touchstart':
          $this->touchstart = \boolval($value);
         break;

         case 'anchor':
          $this->anchor = $value;
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
         
         
          $this->text_changed = TRUE;
          $this->content = $value;
          $this->content_entity = TRUE;
          $this->update_client_prop('text');
         return;
         

         case 'value':
          $this->text_changed = TRUE;
          $this->content = $value;
          $this->content_entity = FALSE;
          $this->update_client_prop('text');
         return;  
       	 
       }
       
       parent::__set($key, $value);	
     	
     }




 }

?>