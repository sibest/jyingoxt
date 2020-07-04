<?

  define('LV_SHIFT_CTRL', 1);
  define('LV_SHIFT_LEFT', 2);
  
class jyingo_table_resultset {
  	 
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
   
  class jyingo_table_item extends jyingo_control {
  	
  	
  	private $column_types = array();
    private $order = NULL;
    private $columns = array();
    
    private $selected = false;
    private $mode = NULL;
    
    private $hidden = array();
    
    private $shifter = 0;
    
    private $renaming_obj = NULL;
    private $list = NULL;
    
    private $filter = false;
  	
  	function __construct($params)
  	{
  		parent::__construct($params);
  		$this->add_class('trow');
  		
  		$this->list = $params->list;
  		$this->mode = $params->mode;
  		
  		
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
  	
  	function add_column($width, $key, $type, $value = NULL, $classes = '', $align = 'left', $filter = false, $maxstr = 0)
  	{
  		
  		$this->filter = $filter;
  		
  		$original_value = $value;
  		if( $filter ) $value = call_user_func($filter, $value);
  		if ($maxstr && strlen($value) > $maxstr)
        $value = substr($value,0,$maxstr-2).'..';
  		
  		if ($type != 'hidden')
  		{
  		 $cell = $this->load('<php:frame>', array("class" => "tcell"));
  		 if ($classes) 
  		  $cell->add_class($classes);
  		 
  		 if ($align != 'left')
  		  $cell->add_class('align_'.$align);
  		 
  		 if (is_numeric($width))
  		  $cell->set_style('width', $width.'px');
  		 else if ($width)
  		  $cell->set_style('width', $width);
  		  
  		 $this->columns[$key] = $cell;
  		}
  		switch ($type)
  		{
  			 
  			 case 'object':
  			  $cell->addChild($value);
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
   		    $obj = $cell->load($key.':<php:link>', array("text" => $value));
  		    $obj->set_event_handler('click', array($this, 'column_btn_click')); 			 
  			 break;
  			 
  			 case 'url':
   		    $obj = $cell->load($key.':<php:link>', array("text" => str_replace('mailto://', '', $value), "href" => $value, "target" => "_blank"));
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
  		    
  		    $cell->load('<php:label>', array("class" => "centerer"));  		   
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
  		    
  		    $cell->load('<php:label>', array("class" => "centerer"));
  		   break;  		   
  		   case 'value':
  		    $obj = $cell->load($key.':<php:label>', array("value" => $value, "tag" => "div"));
  		   break;
  		   
  		   case 'text':
  		    $obj = $cell->load($key.':<php:label>', array("text" => $value));
  		    $obj->set_style('width',$width.'px');
  		   break;
   		   case 'label':
  		    $obj = $cell->load($key.':<php:label>', array("text" => $value));
  		   break; 		   
  		   case 'button':
  		   if ($value)
  		   {
  		   	 $obj = $cell->load($key.':<php:button>', array("text" => $value, "class" => "btn btn-default"));
  		     $obj->set_event_handler('click', array($this, 'column_btn_click'));
  		   }
  		   break;   
  		   case 'imagebutton':
  		   if ($value)
  		   {
  		    $obj = $cell->load($key.':jyingo.imagebutton', array("src" => $value));
  		    $obj->set_event_handler('click', array($this, 'column_btn_click'));
  		   }
  		   break;
  			
  		}
  		
  		if ($maxstr != 0)
  		 $obj->set_property('title', $original_value);  
  		
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
  
  class jyingo_table extends jyingo_control {
    
    
    public $active_tag = 'php:table';
    private $table = NULL, $header = NULL, $body = NULL, $footer = NULL, $mode = 'list';
    public $multiselect = false;
     
    private $col_headers = array(), $row_header = NULL, $rows = array();   
    private $col_setup = array(), $col_order = array(), $order = NULL;
    private $height = NULL, $col_widths = array(), $body_container= NULL;
    
    public $selectable = false;
    
    private $_menu = false, $_menurequest, $_selrow;
    private $_limit_from, $_limit, $_sortable_key, $_sortable_order, $_search_text;
    private $_total_elements = 0;
    
    private $_page_next, $_page_previous;
    
    private $layout = 'full';
    
    private $bar;
    private $bottombar;
    private $search_box;
    private $limit_choose;
    private $pagelabel;
    
    function __construct($params)
    {

     	 parent::__construct($params);
     	 $this->client_vars(array(
     	   
     	 ));
      
      $this->set_client_instance('jyingo.table');
       
       
       $this->bar = $this->load('<php:frame>', array("class" => "tbar"));
       $this->table = $this->load('<php:frame>', array("class" => "tble"));
       
       $this->header = $this->table->load('<php:frame>', array("class" => "thead"));
       
    //   $this->body_container = $this->table->load('tbody:<php:frame>', array("class" => "table_content"));
       
       $this->body = $this->table->load('<php:frame>', array("class" => "tbody"));
       $this->foot = $this->table->load('<php:frame>', array("class" => "tfoot"));
       $this->bottombar = $this->load('<php:frame>', array("class" => "tbar"));
       
       $this->foot->load('<php:frame>', array("class" => "tfootline"));
       
       $this->multiselect = $params->multiselect ? $params->multiselect: false;
       
       $this->add_class('jyingo_table');
       
       $this->_limit_from = 0;
       $this->_limit = 10;
       $this->_sortable_order = true;
       $this->_current_real_count = 0;
       
       
       $this->allow_call('search');
       if ($params->table == true)
        $this->add_class('table_mode');
       
    	
    }
    
    function set_sortable_key($key)
    {
    	$this->_sortable_key = $key;
    	foreach ($this->col_setup as $_key => $data)
    	{
    		 if ($data['sortable'])
    		 {
    		 	 
    		 	  $data['head']->text = str_replace('↑', '', $data['head']->text);
    		 	  $data['head']->text = str_replace('↓', '', $data['head']->text);
    		 	  if ($key != $_key)
    		 	  {
    		 	  
    		  	} else {
    		  	 
    		  	 if ($this->_sortable_order)
    		  	   $data['head']->text .= ' ↓';
    		  	 else
    		  	   $data['head']->text .= ' ↑';
    		  	   
    		  	 
    		  	 
    		  	}
    		 	
    		 }
    	}
    }
    
    function set_real_count($count)
    {
    	$this->_current_real_count = $count;

    	if ($this->_limit_from > $this->_current_real_count -1)
    	 $this->_limit_from = $this->_current_real_count-1;
    }
    
    function go_next()
    {
    	$this->_limit_from += $this->_limit;
    	if ($this->_limit_from > $this->_current_real_count -1)
    	 $this->_limit_from = $this->_current_real_count-1;
    	 
    	$this->event('navigate');
    	 
    }
    
    function go_back()
    {
    	$this->_limit_from -= $this->_limit;
    	if ($this->_limit_from <= 0)
    	 $this->_limit_from = 0;
    	
    	$this->event('navigate');
    }
    
    function get_search_text()
    {
    	return $this->_search_text;
    }
    
    function set_sortable_order($order)
    {
    	$this->_sortable_order = $order;
    }
    
    function get_sortable_key()
    {
     return $this->_sortable_key;
    }
    
    function set_total_elements($total)
    {
    	$this->_total_elements = $total;
    }
    
    function get_limit_from()
    {
    	return $this->_limit_from;
    }

    function get_sortable_order()
    {
    	return $this->_sortable_order ? 'DESC' : 'ASC';
    }
    
    function get_current_limit()
    {
    	return $this->_limit;
    }
 
    
    function menu_click($caller, $value)
    {

    	$this->event('menu_click', $this, $this->_selrow, $value);
    	
    }
    
    function menu_change($caller, $value)
    {
    	
    	$this->event('menu_change', $this, $this->_selrow, $value);
    	
    }
    
    function menu_checked($caller, $value)
    {
    	
    	$this->event('menu_checked', $this, $this->_selrow, $value);
    	
    }    
    function menu($index = -1, $params = array())
    {
    	
    	if ($this->_menu != false)
    	{
    		 $this->_menu->remove();
    		 $this->_menu = false;
    	}
    	
    	$this->_menurequest = true;
    	if ($index != -1)
    	 $this->_selrow = $index;
    	
    	$params['button'] = false;
    	
    	$obj = $this->load('jyingo.dropdown', $params);
    	
    	$obj->set_event_handler('click', array($this, 'menu_click'));
    	$obj->set_event_handler('change', array($this, 'menu_change'));
    	$obj->set_event_handler('checked', array($this,'menu_checked'));
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
  //  	$this->client_call('end_rename');
    	$this->body->clear();
    }
    
    function end_rename($caller, $column, $oldvalue, $newvalue)
    {
    	$this->client_call('end_rename');
    	
      $i = -1;
	    	
	    $children = $this->body->get_children_ordered();
	    
	    foreach ($children as $index => $item)
	    if ($item->get_instance() == $caller->get_instance()) {  $i = $index; break; }    	
	    
	    $this->event('rename', $this, $i, $column, $oldvalue, $newvalue); 
    	
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
    		$item = $this->body->shift('jyingo.table_item', array("list" => $this, "order" => $this->order, "mode" => $this->mode));
    	else
    		$item = $this->body->load('jyingo.table_item', array("list" => $this, "order" => $this->order, "mode" => $this->mode));
    	
    
    	
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
    		
    		$item->add_column($this->col_widths[$index], $this->col_order[$index], $type, $value, $this->col_setup[$this->col_order[$index]]['class'], $this->col_setup[$this->col_order[$index]]['align'], $this->col_setup[$this->col_order[$index]]['filter'], $this->col_setup[$this->col_order[$index]]['maxstr']);
    		
    		
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
    	  jyingo_table_resultset($result);
    	
    }
    
    function get_item($index)
    {
    	$children = $this->body->get_children_ordered();
    	return $children[$index];
    }
    
    function previous_click()
    {
    	$this->_limit_from -= $this->_limit;
    	$this->request_data();
    }
    
    public function refresh($count = FALSE)
    {
    	 $this->request_data($count);
    }
    
    function next_click()
    {
    	$this->_limit_from += $this->_limit;
    	$this->request_data();
    }    
    private function set_page_setting()
    {
    	
    	 $page = floor($this->_limit_from/$this->_limit) + 1;
    	 $pages = ceil($this->_total_elements/$this->_limit);
    	 if ($pages == 0) $pages = 1;
    	 
    	 $this->pagelabel->text = 'Pagina '.$page.' di '.$pages.' ('.$this->_total_elements.' element'.($this->_total_elements == 1 ? 'o' : 'i').')';
    	 $this->_page_previous->visible = $this->_limit_from != 0;
    	 $this->_page_next->visible = $page != $pages;
    }
    
    function limit_change($caller, $value)
    {
    	$this->_limit = $value->get_value();
    	$this->_limit_from = 0;
    	
    	$this->request_data();
    }
    
    function setup($set)
    {
    	
    	if ($this->layout == 'full')
    	{
    		 
    		 $bar_left = $this->bar->load('<php:frame>', array("class" => "tl"));
    		 $bar_right = $this->bar->load('<php:frame>', array("class" => "tr"));
    		 
    		  
    		 $dropdown = $bar_left->load('<jyingo:dropdown>');
    		 $dropdown->add('10 elementi per pagina', 10);
    		 $dropdown->add('20 elementi per pagina', 20);
    		 $dropdown->add('50 elementi per pagina', 50);
    		 $dropdown->add('100 elementi per pagina', 100);
         $dropdown->get_item(10)->select();
         $dropdown->set_event_handler('change', array($this, 'limit_change')); 
         
         
         $bar_right->load('search:<php:textbox>', array("placeholder" => "Cerca"));
         
         
    		 
    		 $bottom_left = $this->bottombar->load('<php:frame>', array("class" => "tl"));
    		 $bottom_right = $this->bottombar->load('<php:frame>', array("class" => "tr"));
    		 
    		 $this->_page_previous = $bottom_right->load('previous:<php:link>', array("text" => "« indietro"));
    		 $this->_page_previous->set_event_handler('click', array($this,'previous_click'));
    		 
    		 $bottom_right->load('<php:label>', array("value" => "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"));
    		 $this->_page_next     = $bottom_right->load('next:<php:link>', array("text" => "avanti »"));
    		 $this->_page_next->set_event_handler('click', array($this,'next_click'));
    		 $bottom_right->load('<php:label>', array("value" => "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"));
    		 
    		 
    		 $this->pagelabel = 
    		  $bottom_left->load('<php:label>');
         
    	}
    	
    	$row = $this->header->load('<php:frame>', array("class" => "trow"));
    	$cols = array();
    	$i = 0;
    	$s = NULL;
    	
    	foreach ($set as $key => $column)
    	{
    		
    		
    	   $value = $column['value'];
    	   $text  = $column['text'];
    	   $width = $column['width'];
    	   $placeholder = $column['placeholder'];
    	   $checked = $column['checked'];
				 $items = $column['items'];
				 $sortable = $column['sortable'] ? true : false;
				 $width = $column['width'];
				 $maxstr = isset($column['maxstr']) ? $column['maxstr'] : 0;
				 $height = $column['height'];
				 $align = $column['align'] ? $column['align'] : 'left';
				 $class = $column['class'] ? $column['class'] : '';
				 $filter = isset($column['filter']) ? $column['filter'] : false;
				 
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
	    		 $col = $row->load('<php:frame>', array("class" => "tcell"));
	    		 if (is_numeric($width))
	    		  $col->set_style('width', $width.'px');
	    		 else if ($width)
	    		  $col->set_style('width', $width);
           					
					 if ($sortable)
					 {
					 	if (!$s) $s = $key;
					 	$val = $col->load('value:<php:link>');
					 	$val->set_event_handler('click', array($this, '_set_column_sortable_key'));
					 	$val->tag = $key;
					 	$val->tooltip('Ordina colonna');
					 	
					 } else {
    		   	$val = $col->load('value:<php:label>');
					 }    		   
    		   
    		   if ($align != 'left')
    		    $col->add_class('align_'.$align);


    		   
    		 } else {
    		    $sortable = false;	
    		 }
    		 
    		 $cols[$key] = $col;
    		 
    		
    		 if ($value) $val->value = $value;
    		 else if (is_object($val)) $val->text = $text;
    		 
    		 $this->col_order[] = $key;
    		 $this->col_setup[$key] = array("type" => $type, "maxstr" => $maxstr, "head" => $val, "filter" => $filter, "sortable" => $sortable, "class" => $class, "align" => $align, "placeholder" => $placeholder, "items" => $items, "width" => $item_width, "height" => $height);
    		
    	}
    	
    	$this->col_headers = $cols;
    	
    	if ($s)
    	 $this->set_sortable_key($s);
    	
    	$this->request_data(true);
    }
    
    function search($text)
    {
    	$this->_limit_from = 0;
    	$value = trim($text);
    	
    	$this->_search_text = $value;
      $this->request_data(true);
    }
    
    
    
    private function request_data($need_count = false)
    {
    	$this->clear();
    	
    	$result = $this->event('data', $this, $need_count);
    	if ($need_count) $this->_total_elements = $result;
    	$this->set_page_setting();
    }
    
    function _set_column_sortable_key($caller)
    {
     global $env;
     $key = $caller->tag;
     
     if ($key == $this->_sortable_key)
      $this->_sortable_order ^= true;
     
     $this->set_sortable_key($key);
     $this->request_data();
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
         
         case 'layout':
           $this->layout = $value;
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