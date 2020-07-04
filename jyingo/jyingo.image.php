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
 
 class jyingo_image extends jyingo_control {
      
     public $active_tag = 'php:image';
 
     public $content_tag = 'a';
     public $artistic;
     public $scale_default = 1;
     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
     	 $this->client_vars(array(
     	  "src", "width", "height", "scale"
     	 ));
  
       $this->artistic = false;
       $this->allow_postback = false;
       $this->load_order = LOAD_ORDER_BOTTOM;
       
       $this->set_client_instance('jyingo.image');
       $this->add_class('jyingo_image');
       
       $this->allow_event('click');
       
     }


     function get_client_data()
     {
     	 return array("width" => $this->width, "height" => $this->height, "scale" => $this->scale);
     }
     
     function preview()
     {
     	 
       
       if ($this->width)
        $this->set_style('width', $this->width.'px');	
        
       if ($this->height)
        $this->set_style('height', $this->height.'px');	       
       
    	parent::preview();  
     	
     }     
     
     function render() {


       echo '<'.$this->content_tag.' id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
       echo '<img src="'.$this->src.'"  alt=""  />';
       
       if ($this->artistic == true)
        echo '<div class="imgblackmask"></div>';
       
       echo '</'.$this->content_tag.'>';
       
       parent::render(false);
      

     } 
     
     function set_property($key, $value)
     {
       
       switch ($key)
       {
         case 'src':
          $this->src = $value;
         break;	
         
         case 'scale':
          $this->scale = $value;
         break;
         
         case 'width':
          $this->width = intval($value);
         break;
         
         case 'artistic':
          $this->artistic = $this->boolean($value);
         break;

         case 'height':
          $this->height = intval($value);
         break;

         case 'tag':
          $this->content_tag = $value;
         break;

         default:
          parent::set_property($key, $value);
         
       }	
     	
     }
     
    




 }

?>