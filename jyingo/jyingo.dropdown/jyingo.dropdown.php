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
 
 
// FARE ANCHE LA MODALITà CHECKBOX MENU

class jyingo_dropitem extends jyingo_control {
	
     public $active_tag = 'dropitem';

     public $text = NULL;
     public $icon = NULL;
     protected $checked = false;
     protected $value = NULL;
     protected $mode = 'select';
     protected $content;
     protected $href;
     protected $target;
     private $optgroup;
     protected $script = '';
     private $firstselect = false;
     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
       
       if ($params['text'] || $params['icon'])
       {
        $this->text = $params['text'];
        $this->icon = $params['icon'];
        
        
        if ($params['button'] == true)
         $this->mode = 'button';
        elseif ($params['checkbox'] == true)
        {
         $this->mode = 'checkbox';
         $this->icon = '/jyingo/jyingo.dropdown/texture.png';
        }
        elseif ($params['checkbutton'] == true)
         $this->mode = 'checkbutton';
        elseif ($params['href'])
        {
         $this->mode = 'href';
         $this->href = $params['href'];
         $this->target = '';
        }
        elseif ($params['script'])
        {
         $this->script = $params['script'];
         $this->mode = 'script';
        }
        else
         $this->mode = 'select';
        	
       } 
       elseif ($params['separator'])
         $this->mode = 'separator';
       
       if (!empty($params['value']))
        $this->value = $params['value'];
       
       
       $this->optgroup = $params['optgroup'];
       
       $this->set_client_instance('jyingo.dropitem');
       $this->set_name(mt_rand(1,9999999));

     }	

     
     function select()
     {
      if ($this->is_choice())
      {
      	 $this->get_parent()->select($this);
      }	
     }
     
     function is_choice()
     {
       return ($this->mode == 'checkbox' || $this->mode == 'select');	
     }
     
     function is_button()
     {
      return ($this->mode == 'button' || $this->mode == 'checkbutton');	
     }
          
     function get_client_data()
     {

     	 $data = array();
       return array("value" => $this->value, "optgroup" => $this->optgroup, "text" => $this->text, "icon" => $this->icon, "target" => $this->target, 
       "mode" => $this->mode, "content" => $this->content, "checked" => $this->checked, "href" => $this->href, "script" => $this->script);
      	
     }
     
     function onload()
     {
      if ($this->firstselect)
       $this->select();	
      
      parent::onload();
     }
     
     function render()
     {
   		 if (count($this->children))
		   {
		     $this->mode = 'container';	
		   }  	
		   echo '<div id="'.$this->get_instance().'">';
		   parent::render();
		   echo '</div>';
     }

     function update_value($value)
     {
       
       if ($this->mode == 'checkbox' || $this->mode == 'checkbutton')
       {
       
        $this->checked = ($value ? true : false);
        	
       	
       }	
     	
     }
     
     function get_checked()
     {
      return $this->checked;	
     }

     function set_property($name, $value)
     {
     	
     	switch ($name) {
       case 'text':
        $this->text = $value;
       break;
       
       case 'target':
        $this->target = $target;
       break;
       
       case 'icon':
        $this->icon = $value;
       break;

       case 'mode':
       
        if ($value == 'button' || $value == 'checkbox' || $value == 'script' || $value == 'select' || $value == 'separator' || $value == 'container' || $value == 'checkbutton' || $value == 'href')
        {
        	 $this->mode = $value;
        }
        
        
        if ($value == 'checkbox')
        {
        	$this->icon = '/jyingo/jyingo.dropdown/texture.png';
        }
       
       break;
       
       case 'optgroup':
        $this->optgroup = $value;
       break; 
       
       case 'script':
        $this->script = $value;
       break;
       
       case 'href':
        $this->mode = 'href';
        $this->href = $value;
       break;
       
       case 'selected':
        $this->firstselect = $this->boolean($value);
       break;
       
       case 'checked':
        if ($this->mode != 'checkbutton')
         $this->mode = 'checkbox';
         
        $this->checked = $this->boolean($value);
       break;
       
       case 'value':
        $this->value = $value;
       break;
       
       default:
       parent::set_property($name, $value);
      }
     } 
     
     function get_value()
     {
      return $this->value;	
     }
     
     function get_text()
     {
       return $this->text;	
     }
	
}

class jyingo_dropdown extends jyingo_control {
      
     public $active_tag = 'jyingo:dropdown';
     private $possible_layouts = array("button" => "jyingo_dropdown_button",
                                       "text" => "jyingo_dropdown_text");
     
     private $layout = 'button';
     private $button = false;
     private $button_instance = NULL;
     public $position = 'left';
     public $class = NULL;
     private $pressed, $selected, $last_selected;
     public $text, $icon;
     
     public $minwidth = 0;
     
     public $label = true;
     
     function __construct($params)
     {
     	 global $env;
     	 
     	 parent::__construct($params);
  
       $this->button_instance = $env->get_clsid('button_'.mt_rand(1,9999));
       
       $this->minwidth = $params->minwidth ? $params->minwidth : 0;
 
       $this->set_client_instance('jyingo.dropdown');
       $this->allow_event('_click');	
       $this->allow_event('_change');	
       $this->allow_event('_checked_changed');
       //$this->load_order = LOAD_ORDER_BOTTOM;
       $this->set_event_handler('_click', array($this, '_click'));
       $this->set_event_handler('_change', array($this, '_change'));
       $this->set_event_handler('_checked_changed', array($this, '_checked_changed'));

       $this->button = $params->has('button') ? $params->button : true;
       $this->client_vars(array(
     	  "text"
     	 ));
       
     }
     
     function get_item($value)
     {
     	
       
       $children = $this->get_children('jyingo_dropitem');
       foreach( $children as $v )
       {
         if ($v->get_value() == $value || $v->get_name() == $value)
          return $v;
       }
       
       return NULL;
     }
     
     function get_checked_values()
     {
        $array = array();
        $children = $this->get_children('jyingo_dropitem');
        foreach( $children as $v )
         if ($v->checked) $array[] = $v->get_value();
        
        return $array;
        
     }

     function get_checked_items()
     {
        $array = array();
        $children = $this->get_children('jyingo_dropitem');
        foreach( $children as $v )
         if ($v->checked) $array[] = $v;
        
        return $array;
        
     }
     
     function show()
     {
      $this->client_call('show');	
     }
      
     function select($obj)
     {
       if ($obj->is_choice())
       {
       	 
       	 $this->selected = $obj;
       	 $this->client_call('select', $this->selected->get_instance(), true);
        	
       }	
     }
     
     function update_value($value)
     {

      
      $this->pressed = NULL;
      
      
      if ($value[1])
      {
      	
      	 $opt = $this->get_by_instance($value[1]);
      	 if ($opt && $opt->is_button())
      	 	 $this->pressed = $opt;
      }
      
      if ($value[0])
      {	  
      	
      	 $this->last_selected = $this->selected;
      	
       	 $opt = $this->get_by_instance($value[0]);
      	 if ($opt && $opt->is_choice())
      	 	 $this->selected = $opt;     	
      }
      	
     }
     
     function get_selected()
     {
      return $this->selected;	
     }
     
     function _change()
     {
       if ($this->selected && $this->last_selected != $this->selected)
        $this->event('change', $this, $this->selected);	
     }
     
     function _checked_changed()
     {
     	$this->event('checked', $this, $this->get_checked_values());	
     } 
     
     function _click()
     {
     	global $env;

     	
     	 if ($this->pressed)
     	  $this->event('click', $this, $this->pressed);
     }
     
     function add($text, $key = '', $icon = '')
     {
     	  $obj = $this->load('jyingo.dropitem', array("text" => $text, "value" => $key, "icon" => $icon));
        return $obj;
     }
     
     function add_checkbox($text, $key = '', $checked = false, $optgroup = NULL, $icon = '/jyingo/jyingo.dropdown/texture.png')
     {
        $obj = $this->load('jyingo.dropitem', array("text" => $text, "optgroup" => $optgroup, "value" => $key, "icon" => $icon, "checkbox" => true, "checked" => $checked));
      	return $obj;
     }
     
     function add_separator()
     {
     	$obj = $this->load('jyingo.dropitem', array("separator" => true));
     	return $obj;
     }
     
     function add_button($text, $key = '', $icon = '')
     {
       	  $obj = $this->load('jyingo.dropitem', array("text" => $text, "value" => $key, "icon" => $icon, "button" => true));
        return $obj;   	
     	
     }
     
     function add_link($text, $key, $icon)
     {
     	
     	
     }
     
     function add_html($html)
     {
     	
     	
     }
     
     function add_script($text, $key = '', $icon = '', $code = '')
     {
        $obj = $this->load('jyingo.dropitem', array("text" => $text, "value" => $key, "icon" => $icon, "script" => $code));
        return $obj;        	
     }
     


     function render() { 
     	
     	 if ($this->button)
     	 {
     	  echo '<a id="'.$this->button_instance.'">';
     	  echo '</a>';
     	 }
     	 
     	 echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
       parent::render();
       echo '</div>';
     }
     
     function get_client_data()
     {
      
      $out = array ("minwidth" => $this->minwidth, "button" => $this->button, "label" => $this->label, "postback" => true, "button_instance" => $this->button_instance, "position" => $this->position, "text" => $this->text, "icon" => $this->icon, "layout" => $this->possible_layouts[$this->layout]);
      
      if ($this->class)
       $out['class'] = $this->class;
      
     	return $out;
     	
     }
     
     function set_property($name, $value)
     {
     	
     	switch ($name) {
       case 'text':
        $this->text = $value;
       break;
       
       case 'icon':
        $this->icon = $value;
       break;


       case 'label':
        $this->label = $this->boolean($value);
       break; 
			
       case 'position':
        $this->position = $value;
       break;
       
       case 'class':
        $this->class = $value;
       break;
      
       case 'layout':
       
        if (isset($this->possible_layouts[$value]))
        {
        	 $this->layout = $value;
        }
       
       break;
       
       default:
        parent::set_property($name, $value);
     	 return;
     } 
    }
 } 
 
?>