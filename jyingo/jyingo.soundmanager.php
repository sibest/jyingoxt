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
 
 
 class jyingo_soundmanager extends jyingo_control {

     private $cache, $client_ok;

     function __construct($params)
     {
     	 
     	 parent::__construct($params);
       $this->set_client_instance('jyingo.soundmanager');
       $this->cache = array();
       $this->client_ok = false;
       
       
     }
     
     function add_sound($sound_id, $path, $group = 'default', $channels = 1)
     {
      $this->cache[$sound_id] = array("path" => $path, "group" => $group, "channels" => $channels); 
      if ($this->client_ok)
      {
      	 $this->client_call('add_sound', $sound_id,  $path, $group, $channels);
      }
     }
     
     function play($sound_id, $looped = false)
     {
     	 $this->client_call('play', $sound_id, $looped);
     }
     
     function stop($group = NULL)
     {
       $this->client_call('stop', $group);	
     }
     
     function get_client_data()
     {
     	  $this->client_ok = true;
     	 return array("cache" => $this->cache);
     }
     
     



 }

?>