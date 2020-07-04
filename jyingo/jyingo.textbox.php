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
 
 define("JFT_VALIDCHARS", 0);
 define("JFT_INVALIDCHARS", 1);
 
 define("JFT_NUMBERS", 1);
 define("JFT_LOWERCASE", 2);
 define("JFT_UPPERCASE", 4);
 define("JFT_CUSTOM", 8);
 
 
 
 class jyingo_textbox extends jyingo_control {
      
     public $active_tag = 'php:textbox';
     public $text_default = '';
     public $password_default = false;
  //   public $placeholder_default = '';
     public $placeholder_color_default = '#cccccc';
     
     private $tags;
     private $prefix;
     private $wysiwyg;
     
     public $filtered_default = false;
     public $custom_chars = NULL;
     public $elastic_default = false;
     public $valid_chars = NULL;
     public $validate_mode = NULL;
     
     private $is_textarea = false;
     
     public $autowidth_default = false;
     public $placeholder;
     private $new_value = NULL, $new_value_changed = false;
     
     public $locked = false;
     public $datepicker = false;
     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
     	 $this->client_vars(array(
     	  "text",  "password", "placeholder_color", "filtered", "filterdata", "autowidth", "elastic", "locked"
     	 ));
			 
			 $this->tags = $params->tags ? $params->tags : false;
			 $this->is_textarea = $params->textarea ? $params->textarea : false;
			 $this->allow_postback = false;
  	   $this->validate_mode = JFT_VALIDCHARS;
 	     $this->valid_chars = JFT_NUMBERS | JFT_LOWERCASE | JFT_UPPERCASE;   

       $this->set_client_instance('jyingo.textbox');
       $this->allow_event('_change');
       
       $this->set_event_handler('_change', array($this, '_change'));
      
       
     }
     
     function _change($caller)
     {
     	
     }
     
     function select()
     {
      $this->client_call('select');	
     }
     
     function focus()
     {
      $this->client_call('focus');	
     }
     
     function __get($k)
     {
       if ($key == 'filterdata')
        return $this->get_filter_data();
       
       
       return parent::__get($k);	
     }

		 function apply_filter($val)
		 {
		 	
		  $lcase = "abcdefghijklmnopqrstuvwxyz";
		  $ucase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		  $nums = "0123456789"; 
		 	$custom = $this->custom_chars;
		 	
		 	$filter = "";
		 	
		 	if ($this->valid_chars & JFT_NUMBERS)
		 	 $filter .= $nums;
		
		 	if ($this->valid_chars & JFT_LOWERCASE)
		 	 $filter .= $lcase;
		
		 	if ($this->valid_chars & JFT_UPPERCASE)
		 	 $filter .= $ucase;
		
		 	if ($this->valid_chars & JFT_CUSTOM)
		 	 $filter .= $custom;
		  
		  $new = "";
		  
		  for ($i = 0; $i < strlen($val); $i++)
		  {
		  
		    $found = substr_count($filter, substr($val, $i, 1)) != 0;
		    if ($this->validate_mode == JFT_INVALIDCHARS)
		     $found ^= true;
		    
		    if ($found == true)
		     $new .= substr($val, $i, 1);
		  
		  }
		  return $new;
		 	
		 }

     function add_tag($tag)
     {
     	 $this->client_call('add_tag', $tag);
     }

     function render() {
       
       global $env;
       
       $value = $env->entities($this->text);
       
       
       if ($this->elastic)
       {
         echo '<textarea name="'.$this->get_instance().'" id="'.$this->get_instance().'"'.$this->get_property_string(' ').' rows="1">'.htmlentities($value, ENT_COMPAT, 'UTF-8').'</textarea>';
       	
       } elseif ($this->is_textarea) {
       	
       	  echo '<textarea name="'.$this->get_instance().'" id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>'.htmlentities($value, ENT_COMPAT, 'UTF-8').'</textarea>';
       	
       	
       } else { 
         echo '<input type="'.($this->password ? 'password' : 'text').'" name="'.$this->get_instance().'" id="'.$this->get_instance().'"'.$this->get_property_string(' ').' value="'.htmlentities($value, ENT_COMPAT, 'UTF-8').'" />';
         	
       }
      
       
       parent::render(false);
      

     } 
     
     function onstart()
     {
     	 
     	 if ($this->new_value_changed == true)
     	 {
     	 	
     	 	 
     	 	 $this->event('beforechange', $this, $this->new_value);
     	 	 $this->text = $this->new_value;
     	 	 $this->event('change');
     	 	 
     	 	 $this->update_script_var('text');
     	 	 
     	 	 $this->new_value = NULL;
     	   $this->new_value_changed = false;	
     	 }
     	
     }
     
     function __toString()
     {
     	 return $this->text;
     }
     
     function update_value($value)
     {
       
       if ($this->locked)
        return; 
       
       if ($this->filtered == true)
        $new_value = $this->apply_filter($value);
       else
        $new_value = $value;
       
       if ($new_value != $this->text)
       {
       	 $this->new_value = $new_value;
       	 $this->new_value_changed = true;
       }
     }
          
     function get_client_data()
     {
     	  
     	 $filterdata = $this->get_filter_data();
     	 return array("pr" => $this->prefix, "wyg" => $this->wysiwyg, /*"placeholder" => $this->placeholder,*/ "dp" => $this->datepicker, "tags" => $this->tags, "elastic" => $this->elastic, "locked" => $this->locked, "autowidth" => $this->autowidth, "placeholder_color" => $this->placeholder_color, "filterdata" => $filterdata, "filtered" => $this->filtered);
     }
     
     private function get_filter_data()
     {
     	 
     	 $k = array("mode" => $this->validate_mode, "valid" => $this->valid_chars, "custom" => $this->custom_chars);
     	 return $k;
      	
     }
     
     function set_property($key, $value)
     {
       
       switch ($key)
       {
         
         case 'password':
          $this->password = $this->boolean($value);
         break;
         
         case 'placeholder':
          $this->placeholder = $value;
          parent::set_property($key, $value);
         break;
         
         
         case 'placeholder_color':
          $this->placeholder_color = $value;
         break;
         
         case 'locked':
          $this->locked = $this->boolean($value);
         break;
         
         case 'text':
          $this->text = $value;
         break;
         
         case 'filtered':
          $this->filtered = $this->boolean($value);
         break;
	
	       case 'textarea':
	        $this->is_textarea = $this->boolean($value);
	       break;
	
		 	   case 'custom_filter':
		  	 	 $this->custom_chars = $value;
		  	 	 $this->valid_chars |= JFT_CUSTOM;
		  	 break;
		  	 
		  	 case 'filter_numbers':
		  	 	 $value = $this->boolean($value);
		  	 	 if ($value == false)
		  	 	  $this->valid_chars ^= JFT_NUMBERS;
		  	 break;
		
		  	 case 'filter_lowercase':
		  	 	 $value = $this->boolean($value);
		  	 	 if ($value == false)
		  	 	  $this->valid_chars ^= JFT_LOWERCASE;
		  	 break;	 
		
		  	 case 'filter_uppercase':
		  	 	 $value = $this->boolean($value);
		  	 	 if ($value == false)
		  	 	  $this->valid_chars ^= JFT_UPPERCASE;
		  	 break;  	   	 
		  	 
		  	 case 'filter_alpha':

		       $value = $this->boolean($value);    
		       
		  	 	 $this->valid_chars |= JFT_UPPERCASE;
		  	 	 $this->valid_chars |= JFT_LOWERCASE;
		  	   $this->valid_chars |= JFT_NUMBERS;
		  	               
           if ($value == false)
           {
		  	  	 $this->valid_chars ^= JFT_UPPERCASE;
		  	 	   $this->valid_chars ^= JFT_LOWERCASE;
		  	     $this->valid_chars ^= JFT_NUMBERS;
		  	   }
		  	   
		  	 break;
		  	 
		  	 case 'filter_text':
		  	 
            $value = $this->boolean($value);
		  	 	  $this->valid_chars |= JFT_UPPERCASE;
		  	 	  $this->valid_chars |= JFT_LOWERCASE;

            if ($value == false)
            {
		  	  	 $this->valid_chars ^= JFT_UPPERCASE;
		  	 	   $this->valid_chars ^= JFT_LOWERCASE;
		  	     $this->valid_chars ^= JFT_NUMBERS;
		  	    }
		  	   
		  	 	  		  	  
		  	 break;
		  	 
		  	 case 'wysiwyg':
		  	  $value = $this->boolean($value);
		  	  if ($value)
		  	  {
		  	  	
		  	  	$this->wysiwyg = true;
		  	  	$this->set_property('textarea', true);
		  	  	
		  	  } else {
		  	    $this->wysiwyg = false;	
		  	  }
		  	 break;
		  	 
		  	 case 'datepicker':
		  	  $this->datepicker = $this->boolean($value);
		  	 break;
		  	 
		  	 case 'filter_mode':
		  	 	 if (strtolower($value) == "valid_chars")
		  	 	  $this->validate_mode = JFT_VALIDCHARS;
		  	 	
		  	 	 if (strtolower($value) == "invalid_chars")
		  	 	  $this->validate_mode = JFT_INVALIDCHARS;
		  	 	  
		  	 break;
	  	 
	  	   case 'autowidth':
	  	    
	  	    $this->autowidth = $this->boolean($value);
	  	    
	  	   break;
	  	   
	  	   case 'prefix':
	  	   
	  	    $this->prefix = $value;
	  	   break;
	  	   case 'tags':
	  	   
	  	    $this->tags = $this->boolean($value);
	  	   break;
	  	   
	  	   case 'elastic':
	  	     
	  	    $this->elastic = $this->boolean($value);
	  	    
	  	   break;

         default:
          parent::set_property($key, $value);
         
       }	
     	
     }
     



 }

?>