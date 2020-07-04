<?
/*
 * jyingoXT, PHP Ajax Toolkit http://www.jyingo.com
 * Copyright 2011-2012 Andrea Pezzino (haxormail@gmail.com) 
 * by the @authors tag. See the copyright.txt in the distribution for a
 * full listing of individual contributors.
 *
 * This is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation; either version 2.1 of
 * the License, or (at your option) any later version.
 *
 * This software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this software; if not, write to the Free
 * Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301 USA, or see the FSF site: http://www.fsf.org.
 */
 
  define('LV_SHIFT_CTRL', 1);
  define('LV_SHIFT_LEFT', 2);
  
  class jyingo_listview_resultset {
  	 
  	 private $result;
  	 
  	 function __construct($result)
  	 {
  	 	 $this->result = $result;
  	 }
  	 
  	 function set($key, $value)
  	 {
  	   foreach ($this->result as $item)
  	    $item->set($key, $value);	
  	 }
  	 
  	 function remove()
  	 {
  	   foreach ($this->result as $item)
  	    $item->remove();
  	 }
  	
  }
  
  class jyingo_listview_item extends jyingo_control {
  	
  	
  	private $column_types = array();
    private $order = NULL;
    private $columns = array();
    
    private $selected = false;
    private $mode = NULL;
    
    private $hidden = array();
    
    private $shifter = 0;
    
    private $renaming_obj = NULL;
    private $list = NULL;
  	
  	function __construct($params)
  	{
  		parent::__construct($params);
  		$this->add_class('trow');
  		
  		$this->list = $params->list;
  		$this->mode = $params->mode;
  		
  		$this->set_client_instance('jyingo.listview_item');
  		
  		$this->allow_event('select');
  		$this->allow_event('button');
  		$this->allow_event('context');
  		
  		$this->set_event_handler('select', array($this, 'select'));
  		
  		$this->allow_call('end_rename');
  	}
  	
  	function focus()
  	{
  		$this->client_call('focus');
  	}
  	
  	function set_selected($sel)
  	{


  	  if ($sel == true && $this->list->multiselect == false)
  	   $this->list->clear_selected();

  		$this->selected = $sel;
  		if ($this->selected)
  	  	$this->add_class('active');
  		else
  			$this->remove_class('active');
  			
  	  
  			
  			  		
  	}
  	
  	function end_rename($txt)
  	{
  		 $col = $this->renaming_obj->tag;
  		 
  		 $this->item($col)->visible = true;
  		 $this->renaming_obj->remove();
  		 
  		 $this->event('end_rename', $this, $col, $this->item($col)->text, $txt);
  		 
  	}
  	
  	function begin_rename($col)
  	{
  	
  	
  	 $p = $this->item($col)->get_parent();
  	 
  	 $obj = $p->load('jyingo.textbox', array("class" => "edit", "text" => $this->item($col)->text, "autowidth" => true));
  	 $obj->tag = $col;
  	 
  	 $this->renaming_obj = $obj;
  	 
  	 $this->item($col)->visible = false;	
  	 
  	 $this->client_call('renaming', $obj->get_instance() );
  	 
  	 $this->add_class('active');

  	 $obj->select();

  		
  	}
  	
  	function get_selected()
  	{
  		
  		return $this->selected;
  		
  	}
  	
  	function update_value($val)
    {
    	 $x = 0;
    	 
    	 global $env;

    	
    	 if ($val['s'])
    	  $x |= LV_SHIFT_LEFT;
    	 
    	 if ($val['c'])
    	  $x |= LV_SHIFT_CTRL;
    	  
    	  $this->shifter = $x;
    	  
    }
  	
  	function select($caller, $skip_false = false)
  	{
  		
  		if ($this->mode != 'select')
  		 return;
  		
  		if ($skip_false == true && $this->selected == true)
  		 return;
  		
  		$this->selected ^= true;
  		
  		if ($this->selected)
  	  	$this->add_class('active');
  		else
  			$this->remove_class('active');
  	  
  	  $this->event('row_select', $this, $this->shifter);
  	  
  	} 
  	
  	function get_client_data()
  	{
  		return array("mode" => $this->mode, "selected" => $this->selected);
  	}
  	
    function render()
    {
    	
    	echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
    	parent::render();
    	echo '</div>';
    } 	
  	
  	function is_after($value)
  	{
  		global $env;
  		$compare = $this->item($this->order);
  		if (is_object($compare)) $compare = $compare->text;
  		else if (is_numeric($compare))
  		{
  			 if ($compare < $value)
  			  return true;
  			 
  			 return false; 
  	  }
  		
  		return strcmp($value, $compare) == -1;
  	}
  	
  	function column_btn_click($caller)
  	{
  		
  		$column = $caller->get_name();
  		$this->event('button_click', $this, $column);
  		
  	}
  	
  	function get_checked($key)
  	{
  		return $this->get($key)->checked;
  	}
  	
  	function set_checked($key, $checked = TRUE)
  	{
  	  $this->get($key)->checked = $checked;
  	}
  	
  	function set($key, $value)
  	{
  	  $this->hidden[$key] = $value;	
  	}
  	
  	function item($key)
  	{
  		if (isset($this->hidden[$key]))
  		 return $this->hidden[$key];
  		else
  	   return $this->get($key);
  	}
  	
  	function add_column($width, $key, $type, $value = NULL, $classes = '', $align = 'left')
  	{
  		
  		if ($type != 'hidden')
  		{
  		 $cell = $this->load('jyingo.frame', array("class" => "tcell"));
  		 if ($classes) 
  		  $cell->add_class($classes);
  		 
  		 if ($align != 'left')
  		  $cell->add_class('align_'.$align);
  		 
  		 if ($width)
  		  $cell->set_style('width', $width.'px');
  		 
  		 $this->columns[$key] = $cell;
  		}
  		switch ($type)
  		{
  			 
  			 case 'object':
  			  $cell->add_child($value);
  			 break;
  			 
  			 case 'progress':
   		    $obj = $cell->load($key.':jyingo.progress', array("value" => $value, "width" => $width, "max" => 1));
  			 break;
  			 
  			 case 'hidden':
  			  $this->hidden[$key] = $value;
  			 break;
  			 
  			 case 'checkbox':
  			 
  			  $checked = false;
  			  $text = '';
  			  
  			  if (is_array($value))
  			  {
  			  	$checked = $value['checked'];
  			  	$text   = $value['text'];
  			  } else {
  			  	$checked = $this->boolean($value);
  			  }
  			 
  			  $obj = $cell->load($key.':jyingo.checkbox', array("text" => $text, "checked" => $checked));
  			 break;
  			 
  			 case 'link':
   		    $obj = $cell->load($key.':jyingo.link', array("text" => $value));
  		    $obj->set_event_handler('click', array($this, 'column_btn_click')); 			 
  			 break;

  			 
  		   case 'textbox':
  		   
  		    $placeholder = '';
  		    $text = '';
  		   
  		    if (is_array($value))
  		    {
  		    	$disabled = $value['disabled'];
  		    	$text = $value['text'];
  		    	$placeholder = $value['placeholder'];
  		    	$elastic = $value['elastic'];
  		    	$textarea = $value['textarea'];
  		    	
  		    } else {
  		    	
  		    	$textarea = false;
  		    	$elastic = false;
  		    	$disabled = false;
  		      $text = $value;	
  		    }
  		      		   
  		    $obj = $cell->load($key.':jyingo.textbox', array("autowidth" => true, "textarea" => $textarea, "elastic" => $elastic, "text" => $text, "placeholder" => $placeholder, "disabled" => $disabled));
  		   break;
  		   
  		   case 'select':
  		    $obj = $cell->load($key.':jyingo.select', array("autowidth" => true));
  		    
  	
  		    $items = $value['items'];
  		    
  		    if (is_array($items))
  		    {
  		     foreach ($items as $val => $text)
  		      $obj->add_item($text, $val);
          
          
           if ($value['selected'])
            $obj->get_item($value['selected'])->select();  		    
          }
  		   break;
  		   
  		   case 'imageframe':
  		    $src = $value;
  		    $width = 200;
  		    $height = 100;
  		    
  		    $object = 'jyingo.imageframe';
  		    
  		    if (is_array($value))
  		    {
  		    	
  		    	$src = $value['src'];
  		    	$width = $value['width'];
  		    	$height = $value['height'];
  		    	
  		    }
  		    
  		    
  		    
  		    $obj = $cell->load($key.':'.$object, array("src" => $src, "height" => $height, "width" => $width));
  		    if (!$src)
  		     $obj->visible = false;
  		    
  		    $cell->load('jyingo.label', array("class" => "centerer"));  		   
  		   break;
  		   
  		   case 'image':
  		   
  		    $src = $value;
  		    $width = 200;
  		    $height = 100;
  		    
  		    $object = 'jyingo.image';
  		    
  		    if (is_array($value))
  		    {
  		    	
  		    	$src = $value['src'];
  		    	$width = $value['width'];
  		    	$height = $value['height'];
  		    	$object = $value['frame'] ? 'jyingo.imageframe' : 'jyingo.image';
  		    	
  		    }
  		    
  		    
  		    
  		    $obj = $cell->load($key.':'.$object, array("src" => $src, "height" => $height, "width" => $width));
  		    if (!$src)
  		     $obj->visible = false;
  		    
  		    $cell->load('jyingo.label', array("class" => "centerer"));
  		   break;
  		   
  		   case 'value':
  		    $obj = $cell->load($key.':jyingo.label', array("value" => $value, "tag" => "div"));
  		   break;
  		   
  		   case 'text':
  		    $obj = $cell->load($key.':jyingo.label', array("text" => $value));
  		    $obj->set_style('width',$width.'px');
  		   break;
  		   case 'label':
  		    $obj = $cell->load($key.':jyingo.label', array("text" => $value));
  		   break;
  		   
  		   case 'button':
  		    $obj = $cell->load($key.':jyingo.linkbutton', array("text" => $value));
  		    $obj->set_event_handler('click', array($this, 'column_btn_click'));
  		   break;
  			
  		}
  		
  		$this->column_types[$key] = $type;
      return $obj;
  	}
  	
  	function set_property($key, $value)
  	{
  		
  		if ($key == 'order')
  		{
  		 $this->order = $value;
  		 return;
  		}
  		
  		parent::set_property($key, $value);
  	}
  	
  	
  }
  
  class jyingo_listview extends jyingo_control {
    
    private $header = NULL, $body = NULL, $footer = NULL, $mode = 'list';
    public $multiselect = false;
     
    private $col_headers = array(), $row_header = NULL, $rows = array();   
    private $col_setup = array(), $col_order = array(), $order = NULL;
    private $height = NULL, $col_widths = array(), $body_container= NULL;
    
    public $selectable = false;
    
    private $_menu = false, $_menurequest, $_selrow;
    
    function __construct($params)
    {

     	 parent::__construct($params);
     	 $this->client_vars(array(
     	   
     	 ));

       $this->set_client_instance('jyingo.listview');    	
       
       $this->header = $this->load('jyingo.frame', array("class" => "thead"));
       
       $this->body_container = $this->load('tbody:jyingo.frame', array("class" => "table_content"));
       
       $this->body = $this->body_container->load('jyingo.frame', array("class" => "tbody"));
       $this->foot = $this->load('jyingo.frame', array("class" => "tfoot"));
       
       $this->foot->load('jyingo.frame', array("class" => "tfootline"));
       
       $this->multiselect = $params->multiselect ? $params->multiselect: false;
       
       $this->add_class('jyingo_listview');
       $this->set_client_instance('jyingo.listview');
       
       
    	
    }
    
    function menu_click($caller, $value)
    {

    	$this->event('menu_click', $this, $this->_selrow, $value);
    	
    }
    
    function menu_change($caller, $value)
    {
    	
    	$this->event('menu_change', $this, $this->_selrow, $value);
    	
    }
    
    function menu($params = array())
    {
    	
    	if ($this->_menu != false)
    	{
    		 $this->_menu->remove();
    		 $this->_menu = false;
    	}
    	
    	$this->_menurequest = true;
    	
    	$params['button'] = false;
    	
    	$obj = $this->load('jyingo.dropdown', $params);
    	
    	$obj->set_event_handler('click', array($this, 'menu_click'));
    	$obj->set_event_handler('change', array($this, 'menu_change'));
    	
    	$this->_menu = $obj;
    	
    	return $obj;
    }
    
    function begin_rename($row, $column)
    {
    	 
    	 $children = $this->body->get_children_ordered();
    	 $row = $children[$row];
    	 
    	 foreach ($children as $child) $child->set_selected(false);
    	 $row->begin_rename($column);
    	 
    	 $this->client_call('begin_renaming');
    	 
    	 
    	 
    }
    
    function select_all()
    {
     	$children = $this->body->get_children();
    	foreach ($children as $child)
    	 $child->set_selected(true);   	
    }
    
    function clear_selected()
    {
    	
    	$children = $this->body->get_children();
    	foreach ($children as $child)
    	 $child->set_selected(false);
    	
    }
    
    function get_client_data()
    {
    	return array("mode" => $this->mode, "multiselect" => $this->multiselect, "selectable" => $this->selectable);
    }
    
    function clear()
    {
    	$this->client_call('end_rename');
    	$this->body->clear();
    }
    
    function end_rename($caller, $column, $oldvalue, $newvalue)
    {
    	$this->client_call('end_rename');
    }
    
    function preview()
    {
    	if ($this->height)
    	{
    		$this->body_container->add_class('fixed_height');
    		$this->body_container->set_style('height', $this->height.'px');
    		$this->client_call('init_scroll');
    	}    	

    	parent::preview(); 
    }
    
    function row_select($caller, $shift = NULL)
    {
    	
    	global $env;


	    $i = -1;
	    	
	    $children = $this->body->get_children_ordered();
	    
	    foreach ($children as $index => $item)
	     if ($item->get_instance() == $caller->get_instance()) {  $i = $index; break; }
    	
      if ($caller->get_selected())
      {
        $ev_name = 'select';


	    	if ($shift == NULL || $this->multiselect == false)
	    	{
	    		
	    	 $children = $this->body->get_children();
	    	 foreach ($children as $child) 
	    	 if ($child->get_instance() != $caller->get_instance()) $child->set_selected(false);
	    	
	    	} elseif ($shift & LV_SHIFT_CTRL)
	    	{
	    		
	    	} elseif ($shift & LV_SHIFT_LEFT)
	    	{
	    		 
	    		$begin_sel = -1;
	    		
	    		
	    		if ( count($this->get_selected())-1 == 0)
	    		{
	    			
	  		    for ($x = 0; $x < $i; $x++)
			    	   $children[$x]->set_selected(true);  			
	    			
	    		} else {
	    		 
		     	foreach ($children as $index => $item)
		    	 if ( $item->get_selected() && $index < $i) $begin_sel = $index;
		    	 elseif ($index >= $i) break;
	    		 
	    		if ($begin_sel == -1)
	    		{
	    			
			     	foreach ($children as $index => $item)
			    	 if ( $item->get_selected() && $index > $i) { $begin_sel = $index; break; }
			    	
			    	if ($begin_sel != -1)
			    	 for ($x = $i; $x < $begin_sel; $x++)
			    	   $children[$x]->set_selected(true);
			    	 
	
	    		} else {
	    		 
	    		 for ($x = $begin_sel; $x < $i; $x++)
	    		  $children[$x]->set_selected(true);
	    		
	    	  }
	    	 } 
	    	}
     
      } else {
      	
      	$ev_name = 'deselect';
      	
      }
    	$this->event($ev_name,  $this, $i, $caller->get_selected());
    	
    }
    
    function get_selected()
    {
    
     $selected = array();
     $children = $this->body->get_children();
     
     foreach ($children as $child)   
      if ($child->get_selected())
      {
      	 $selected[] = $child;
      }
    	
      if ($this->multiselect)
       return $selected;
      else
      {
      	
      	if (count($selected))
      	 return array_pop($selected);
      	
      	
      }
    	
    	return NULL;
    }
    
    function render()
    {
    	

    	
    	
    	echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').' onselectstart="return false;" >';
    	parent::render();
    	echo '</div>';
    }
    
    function add()
    {
    	global $env;
    	$item = $env->load('jyingo.listview.item', array("list" => $this, "order" => $this->order, "mode" => $this->mode));
    	
    	
    	$args = func_get_args();
    	
    	$col_search_index = array_search($this->order, $this->col_order);
    	$search_value = $args[$col_search_index];
    	
    	$children = $this->body->get_children();
    	
    	$before = NULL;
    	
    	if ($this->order)
    	{
	    	foreach ($children as $child)
	    	{
	    		
	         if ($child->is_after($search_value))
	         {
	    		   $before = $child;
	    		   break;
	    		 }
	    	}
    	}
    	if ($before)
    	 $this->body->insert_before($item, $before);
    	else
    	 $this->body->add_child($item);
    	
    	
    	foreach ($args as $index => $value)
    	{
    		
    		$type = $this->col_setup[$this->col_order[$index]]['type'];
    		
    		if ($type == 'textbox' && !is_array($value) && $this->col_setup[$this->col_order[$index]]['placeholder'])
    		{
    			
    			if (is_array($value))
    			{
    				 $textarea = $value['textarea'] ? true : false;
    				 $disabled = $value['disabled'] ? true : false;
    				 $text = $value['text'];
    				 $placeholder = $value['placeholder'] ? $value['placeholder'] : $this->col_setup[$this->col_order[$index]]['placeholder'];
    				 $elastic = $value['elastic'] ? true : false;
    				
    			} else {
    				 $elastic = false;
    				 $text = $value;
    				 $textarea = false;
    				 $disabled = false;
    				 $placeholder = $this->col_setup[$this->col_order[$index]]['placeholder'];
    			}
    			
    			$value = array("text" => $value, "elastic" => $elastic, "textarea" => $textarea, "placeholder" => $placeholder, "disabled" => $disabled);
    			
    		}
    		

    		if ($type == 'select' && !is_array($value) && $this->col_setup[$this->col_order[$index]]['items'])
    		{
    			
    			$value = array("selected" => $value, "items" => $this->col_setup[$this->col_order[$index]]['items']);

    		}
    		
    		if ($type == 'progress')
    		{
    			 
    		}

    		
    		if (($type == 'image' || $type =='imageframe') && !is_array($value) && $this->col_setup[$this->col_order[$index]]['width'] && $this->col_setup[$this->col_order[$index]]['height'])
    		{
    			
    			$value = array("src" => $value, "width" => $this->col_setup[$this->col_order[$index]]['width'], "height" => $this->col_setup[$this->col_order[$index]]['height']);
    			
    		}
    		
    		$item->add_column($this->col_widths[$index], $this->col_order[$index], $type, $value, $this->col_setup[$this->col_order[$index]]['class'], $this->col_setup[$this->col_order[$index]]['align']);
    		
    		
    	}
    	
    	$item->set_event_handler('button_click', array($this, 'button_click'));
    	$item->set_event_handler('row_click', array($this, 'row_click'));
    	$item->set_event_handler('row_select', array($this, 'row_select'));
    	$item->set_event_handler('context', array($this, 'context_click'));
    	$item->set_event_handler('end_rename', array($this, 'end_rename'));
    	
    	return count(array_keys($children));
    	 
    }
    
    function context_click($caller)
    {
    	
      $caller->select($caller, true);
      
      $this->_menurequest = false;
      
    	$i = -1;
    	
    	$children = $this->body->get_children_ordered();
    	
    	foreach ($children as $index => $item)
    	 if ($item->get_instance() == $caller->get_instance()) {  $i = $index; break; }
      
      $this->_selrow = $i;
      
    	$this->event('context', $this, $i);
      
      if ($this->_menurequest)
      {
      	$this->_menu->show();
      }
      
    }
    
    function button_click($caller, $column)
    {
    	$i = -1;
    	
    	$children = $this->body->get_children_ordered();
    	
    	foreach ($children as $index => $item)
    	 if ($item->get_instance() == $caller->get_instance()) {  $i = $index; break; }
    	
    	$this->event('button_click', $this, $i, $column);
    }
    
    function get_item_count()
    {
    	$children = $this->body->get_children();
    	return count($children);
    }
    
    function find($field = NULL, $value = NULL)
    {
    	
    	global $env;
    	$children = $this->body->get_children();
    	$result = array();
    	
    	if (!$field)
    	 $result = array_values($children);
    	else
    	{
	    	foreach($children as $child)
	    	{
	    		
	    		$ch = $child->item($field);
	    		if (is_object($ch))
	    		{
	    		 if ($ch->text == $value)
	    		  $result[] = $child;
	    		}
	    		else
	    		if ($child->item($field) == $value)
	    		 $result[] =  $child;
	    		
	    		
	    	}
    	}
    	

    	
    	if (!count($result))
    	 return false; 
    	
    	if (count($result) == 1)
    	 return array_pop($result);
    	 
    	return new 
    	  jyingo_listview_resultset($result);
    	
    }
    
    function get_item($index)
    {
    	$children = $this->body->get_children_ordered();
    	return $children[$index];
    }
    
    function setup($set)
    {
    	
    	$row = $this->header->load('jyingo.frame', array("class" => "trow"));
    	$cols = array();
    	$i = 0;
    	
    	foreach ($set as $key => $column)
    	{
    		
    		
    	   $value = $column['value'];
    	   $text  = $column['text'];
    	   $width = $column['width'];
    	   $placeholder = $column['placeholder'];
    	   $checked = $column['checked'];
				 $items = $column['items'];
				 $width = $column['width'];
				 $height = $column['height'];
				 $align = $column['align'] ? $column['align'] : 'left';
				 $class = $column['class'] ? $column['class'] : '';
				 $item_width = $width;

				 $this->col_widths[$i++] = $width;
    	   
    	   $type = ($column['type'] ? $column['type'] : 'text');
				 if ($type == 'image')
				 {
				 //  	$width += 2;
				 }
				     	   
    	   if ($this->order == 'auto' && $type == 'label') $this->order = $key;

         $col = NULL;

         if ($type != 'hidden')
         {
	    		 $col = $row->load('jyingo.frame', array("class" => "tcell"));
	    		 if ($width)
	    		  $col->set_style('width', $width.'px');
    		   
    		   $val = $col->load('value:jyingo.label');
    		   
    		   if ($align != 'left')
    		    $col->add_class('align_'.$align);
    		   
    		 }
    		 
    		 $cols[$key] = $col;
    		 
    		
    		 if ($value) $val->value = $value;
    		 else if (is_object($val)) $val->text = $text;
    		 
    		 $this->col_order[] = $key;
    		 $this->col_setup[$key] = array("type" => $type, "class" => $class, "align" => $align, "placeholder" => $placeholder, "items" => $items, "width" => $item_width, "height" => $height);
    		
    	}
    	
    	$this->col_headers = $cols;
    	
    }
 
    
    function set_property($key, $value)
     {
       
       switch ($key)
       {
         
         case 'mode':
          
          $valid_modes = array("list", "select", "button");
          $v = strtolower($value);
          
          if (in_array($v, $valid_modes))
          	 $this->mode = $v;
          
         break;
         
         case 'height':
          $this->height = intval($value);
         break;
         
         case 'order':
          $this->order = $value;
         break;
         
         case 'multiselect':
          $this->multiselect = $this->boolean($value);
         break;
         
         case 'selectable':
          $this->selectable = $this->boolean($value);
         break;
                  
         default:
          parent::set_property($key, $value);
         
       }	
     	
     }
    
    
  }
 ?>