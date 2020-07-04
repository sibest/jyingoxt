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

 class jyingo_windowmanager extends jyingo_control {
 	
 	     public $active_tag = 'php:windowmanager';
 	     public $_windowlist = array();
 	     
 	     public static $_mgr;
 	     
 	     public static function get_mgr()
 	     {
 	      return jyingo_windowmanager::$_mgr;	
 	     }
 	     
 	     function showing($what)
 	     {
 	       global $env;
 	       $env->important();
 	       
 	       foreach ($this->_windowlist as $window)
 	       {
 	       	$window->event('onhide');
 	        $window->drop(true);
 	       }
 	       
 	       
 	       
 	       $this->_windowlist = array($what);
 	       $this->add_child($what);
 	       $what->event('onshow');
 	       $what->message('window_show', true);
 	       
 	       $env->script('jyingo._wshowing(\''.$what->get_instance().'\');');
 	       
 	       return true;
 	       	
 	     }
 	     
 	     function remove_loader()
 	     {
 	      $this->client_call('remove_loader');  	
 	    }
 	     
 	     function show_loader()
 	     {
 	      $this->client_call('set_loader');  	
 	    } 	     
 	     function set_url($url)
 	     {
 	       $this->client_call('set_hash_position', $url);	
 	     }
 	     
 	     function get_page()
 	     {
 	      return $this->_windowlist[0];
 	     }
 	     
 	     function __wakeup()
 	     {
 	     	jyingo_windowmanager::$_mgr = $this;
 	     }
 	     
 	     function __construct()
 	     {
 	     	
 	     	
 	     	jyingo_windowmanager::$_mgr = $this;
 	     	$this->set_name('_windowmgr');
 	     	$this->set_client_instance('jyingo.windowmanager');
 	     	$this->allow_call('_request_page');
 	     }
 	     
 	     function _request_page($page)
 	     {
 	       $this->event('page_request', $this, $page);	
 	     }
 	     
 	     function preview()
 	     {
 	     	
 	      jyingo_windowmanager::$_mgr = $this;
 	      parent::preview();
 	     }
 	     
 	     function render() {

        echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
         parent::render();
        echo '</div>';
        

      } 
 	
 	
 }
?>