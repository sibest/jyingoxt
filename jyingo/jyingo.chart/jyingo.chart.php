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
  
 class jyingo_chart extends jyingo_control {
  
    private $type, $width, $height, $data, $columns, $options, $no_data_text;
    public $active_tag = 'jyingo:chart';
     
    function __construct($params)
    {
     	 
     	 parent::__construct($params);
       $this->set_class('jyingo_chart');
       $this->set_client_instance('jyingo.chart');
       
       $this->type = $params->type ? $params->type : 'linechart';
       $this->data = array();
       
       $this->columns = array("Data");

    }
    
    function get_client_data()
    {
     return array("width" => $this->width,
                  "height" => $this->height,
                  "options" => $this->options,
                  "type" => $this->type,
                  "columns" => $this->columns,
                  "data" => $this->data);
    }
    
    function setup($options)
    { 
    	if (isset($options['columns']))
    	{
    		 $this->columns = $options['columns'];
    		 unset($options['columns']);
      }
      
      $this->options = $options;  
      if ($this->has_rendered())
      {
      	 $this->client_call('setOptions', $this->columns, $this->options);
      }
    }
    
    function clear()
    {
    	 
    	 
    	 $this->data = array();
    	 if ($this->has_rendered())
    	  $this->client_call('clearChart');
 
    }
    
    function add_data()
    {
    	 
    	 $args = func_get_args();
    	 if (count($args) == 1 && is_array($args[0]))
    	  $args = $args[0];
    	 
    	 $this->data[] = $args;
    	 
    	 if ($this->has_rendered())
    	  $this->client_call('addData', $args);
    	 
    }
    
    function render()
    {
    	 echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
    	 echo '<span class="no_data" style="height:'.$this->height.'px;line-height:'.$this->height.'px;">'.$this->no_data_text.'</span>';
    	 parent::render(false);
    	 echo '</div>';    
    }	 
    
    function set_property($key, $value)
    {
      
      if ($key == 'width')
      {
      		$this->width = intval($value);
      		if ($this->width == 0) $this->width = '100%';
      		return;
      }
      
      if ($key == 'type')
      {
      		$this->type = $value;
      		return;
      }         
      
      if ($key == 'no_data_text')
      {
      	  $this->no_data_text = $value;
      	  return;
      }
      
      if ($key == 'height')
      {
      		$this->height = intval($value);
      		return;
      }      	
      parent::set_property($key, $value);
     	
    }
              	
 	
 }
 
?>