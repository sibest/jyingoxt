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
 
  class jyingo_popupbar extends jyingo_control {
  	
  	public $active_tag = 'php:popupbar';
  	
  	function __construct($params)
  	{
  			
  		parent::__construct($params);
  		

  		$this->set_client_instance('jyingo.popupbar');  

  	}
  	
    function render()
    { 
    	echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
    	 parent::render();
    	echo '</div>';    	
    }
  	  	
  }
  
  class jyingo_popupbutton extends jyingo_control {
  	
  	public $active_tag = 'php:popupbutton';

  	public $cancel_default = false;
  	public $default_default = false;
  	public $close = false;
  	public $class;
  	
  	function __construct($params)
  	{
  			
  		parent::__construct($params);
     	 $this->client_vars(array(
     	   "text", "cancel", "default", "icon"
     	 ));
     	 
     	
     	 

    	$this->add_class('jyingo_popupbutton');     	 
     	$this->allow_event('click');
  		
  		$this->set_client_instance('jyingo.popupbutton');  

  	}
  	
    function get_client_data()
    {
     	return array("text" => $this->text, "cancel" => $this->cancel, "default" => $this->default, "close" => $this->close, "icon" => $this->icon);
    }
  	
  	function onload()
  	{
  		
  		parent::onload();
  		$this->set_event_handler('click', array($this, 'click'));
  	}
  	
  	function click()
  	{
  		$this->get_parent()->button_clicked($this);
  		
  		
  	}
    
    function render()
    {
    	
    	
    	
    	
    	echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
    	parent::render();
    	echo '</div>';
    }
  	
    function set_property($key, $value)
    {
       
       switch ($key)
       {
         
         case 'cancel':
          $this->cancel = $this->boolean($value);
    	    $this->add_class('cancel');
         break;

         case 'close':
          $this->close = $this->boolean($value);
         break;
         
         case 'class':
          $this->class = $value;
    	    $this->add_class($value);
         break;
                  
         case 'text':
          $this->text = $value;
         break;

         case 'icon':
          $this->icon = $value;
         break;
         
         case 'default':
          $this->default = $this->boolean($value);
    	    $this->add_class('default');
         break;

         default:
          parent::set_property($key, $value);
         
       }	
     	
     }
  	
  	
  	
  };
  
  class jyingo_popup extends jyingo_control {
    
    public $active_tag = 'php:popup';

    
    public $autoremove = true;
    
    public $mask = 'jyingo_popup_mask';
    
    function __construct($params)
    {

     	 parent::__construct($params);
     	 $this->client_vars(array(
     	    'title', 'width'
     	 ));
     	 
     	 $this->allow_call('_autoremove');
     	 $this->allow_call('_afterhide');
       
       $this->visible = false;
       $this->set_client_instance('jyingo.popup');    
       $this->add_class('jyingo_popup');	
    	
    }
    
    function button_clicked($button)
    {
      if ($button->cancel == true)
       $this->_autoremove();
      
      $this->event('click', $this, $button);
      	
    }
    
    function _afterhide()
    {
    	$this->event('hide');
    }
        
    function _autoremove()
    {
    	
    	if (!$this->autoremove)
    	 return;
    	
    	$this->event('autoremove');
    	$this->remove();
    }
    
    function preview()
    {

    	
    	if ($this->width)
    	 $this->set_style('width', $this->width.'px');
    	 
    	parent::preview();    	
    }
    
    function render()
    {
    	
    	
    	echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
    	parent::render();
    	echo '</div>';
    	
    	
    }
    
    
    function get_client_data()
    {
     	return array("title" => $this->title, "mask" => $this->mask, "autoremove" => $this->autoremove);
    }
    
    function show($caller = null)
    {
    	global $env;
    	$this->visible = true;
    	
    	if ($caller)
    	{
    		 $this->client_call('show', $caller->get_instance());
    	} else {
    	   $this->client_call('show');
    	}
    	
    	
    }
    
    function hide()
    {
    	
    	$this->client_call('hide');
    	
    }
    

    
    function set_property($key, $value)
    {
       
       switch ($key)
       {
         
         case 'width':
          $this->width = intval($value);
         break;
         
         case 'mask':
          $this->mask = $value;
         break;
         
         case 'title':
          $this->title = $value;
         break;
         
         case 'autoremove':
          $this->autoremove = $this->boolean($value);
         break;

         default:
          parent::set_property($key, $value);
         
       }	
     	
     }
    
    
  }
 ?>