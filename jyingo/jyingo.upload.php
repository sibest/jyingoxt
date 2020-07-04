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
 
 class jyingo_upload extends jyingo_control {
      
     public $active_tag = 'php:upload';
     public $session_handle  = NULL;
     private $session;

     public $send_after_default = false;
     
     private $last_status;
     private $errorcode = -1;
     public $items = array();
     private $has_changed = false;
     private $progress_name = NULL;
     
     
     public $upload_filter = 0;
     public $classname = ''; 
     
     
     function __construct($params)
     {
     	 
     	 parent::__construct($params);
     	 $this->client_vars(array(
     	   'text', 'extension', 'send_after', 'progress_instance'
     	 ));

       $this->set_client_instance('jyingo.upload');
      
       $this->allow_event('status_change');
       $this->set_event_handler('status_change', array($this, 'status_change'));
       $this->add_class('jyingo_upload');
       
     }
     
     function get_count()
     {
      return count($this->items);
     }
     
		 function get_filename($index = 0)
		 {
		 	 return $this->items[$index]["upload_realfile"];
		 }
		 
		 function move($to, $index = 0)
		 {
		 	 rename($this->items[$index]["upload_realfile"], $to);
		 }
		 
		 function dispose()
		 {
		   parent::dispose();
		   $this->clear_files(); 
		 } 
		 
		 function clear_files()
		 {
		   if (is_array($this->items))
		   foreach ($this->items as $file)
		   {
		     if (	file_exists($file["upload_realfile"]) )
		      unlink($file["upload_realfile"]);
		   }		 	
		 }
		 
		 function __set($k, $v)
		 {
		   
		   if ($k == 'progress')
		   {
		     $this->progress_instance = $v->get_instance();
		     return;	
		   }
		   
		   parent::__set($k ,$v);
		   	
		 }
		
		 function get_tmpname($index = 0)
		 {
		 	 return $this->items[$index]["upload_tmpfile"];
		 }
		 
		 function get_filesize($index = 0)
		 {
		 	 return $this->items[$index]["upload_size"];
		 } 
     
     function status_change()
     {
     	  global $env;
     	  
     	  $tries = 0;
     	  
     	  
     	  if ($this->last_status == 2)
     	  {
	     	  while (!isset($this->session['upload_result']) && $tries++ < 5)
	     	  {
	     	    $this->session = $env->read_upload_session($this->session_handle);    	  	
	     	  	usleep(500000);
	     	  }
     	  }
     	  
     	  
   
     	  
     	  switch ($this->last_status)
     	  {
     	  	
     	  	case 1:
     	  	 $this->errorcode = 0;
     	  	 $this->event('uploadstart');
     	  	 $this->client_call('_show_progress');
     	  	 
     	  	case 2:
     	  	
     	  	if (isset($this->session['upload_result']))
     	  	{
     	  	
     	  	 if ($this->session['upload_result'] != 1)
     	  	 {
     	  	   $this->errorcode = $this->session['upload_result'];
     	  	   $this->event('uploaderror');
     	  	   $this->client_call('_hide_progress');
     	  	 } else {
     	  	 	 
     	  	 	 
     	  	 	 $this->clear_files();
     	  	 	 $this->items = $this->session['items'];
     	  	 	 $this->session['items'] = array();
     	  	 	 unset($this->session['upload_result']);
     	  	 	 $this->event('uploadend');
     	  	 	 
     	  	 	 $this->has_changed = true;
     	  	 	 $this->client_call('_hide_progress');
     	  	 	 
     	  	 }
     	  	 
     	  	}
     	  	break;
     	  	
     	  	default:
     	  	 $this->errorcode = $this->last_status;
     	  	 $this->event('uploaderror');
     	  	 
     	  	 $this->client_call('_hide_progress');
     	  	 
     	  	break;
     	  	
     	  }
     	  
     	  
     	  
     	  
     } 
     
     function update_value($value)
     {
     	 $this->last_status = $value;     	
     }
     
     function get_client_data()
     {
     	 return array("handle" => $this->session_handle, "extension" => $this->extension, "send_after" => $this->send_after, "progress" => $this->progress_instance, "text" => $this->text);
     }
     
     function onload()
     {
     	 global $env;
     	 
     	
     	 if ($this->session_handle == NULL)
     	 {
     	 	  
     	 	  $this->session_handle = md5(mt_rand(1,99999).time());
     	 	  
     	 	  $data = array("ip_check" => $_SERVER['REMOTE_ADDR'],
     	 	                "instance" => $this->get_instance(),
     	 	                "date" => time());
     	 	                
     	 	  $env->write_upload_session($this->session_handle, $data);
     	 	  
   
     	 }     
     	 
     	 if ($this->progress_name)
     	 {
     	 	 
     	 	 $this->progress_instance = $env->get($this->progress_name)->get_instance();
     	 	 $this->progress_name = null;
     	 	 
     	  	
     	 }
     	 
     	 $this->has_changed = true;
     	 
     	 
     	 parent::onload();	
     }
     
     function preview()
     {
     	 global $env;
     	 $this->session = $env->read_upload_session($this->session_handle);
     	 
     	 parent::preview();
     	 
     }
     
     function postview()
     {
     	
      global $env;
      
      if ($this->has_changed)
      {
      
       $this->session['upload_filter'] = $this->upload_filter;
       $this->session['classname'] = $this->_classname;
       
       $env->write_upload_session($this->session_handle, $this->session);
       $this->has_changed = false;
      }
      parent::postview();
     }
     
     

     function render() {
           
       echo '<div id="'.$this->get_instance().'"'.$this->get_property_string(' ').'>';
       echo '</div>';
       
       parent::render(false);
       $this->session['text'] = $this->text;
       
       

     } 
     
     function set_property($key, $value)
     {
       
       switch ($key)
       {
         
         case 'send_after':
          $this->send_after = $this->boolean($value);
         break;
         
         case 'extension':
          $this->extension = $value;
         break;
         
         case 'text':
          $this->text = $value;
         break;
         
         case 'progress':
          $this->progress_name = $value;
         break;
         
         case 'class':
          $this->_classname = $value;
         break;
         
         case 'filter':
          
          if ($value == 'image')
          {
          	$this->upload_filter = 1;
          }
          
          
         break;
         
         default:
          parent::set_property($key, $value);
         
       }	
     	
     }
     
     



 }

?>