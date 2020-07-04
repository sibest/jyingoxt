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

 class jyingo_i18n {
   
   private $data, $client_vars, $replaced_data;
   
   function __construct()
   {
   	 $this->data = array(); 
   	 $this->replaced_data = array();	
   }
   
   function load($vars)
   {
    foreach ($vars as $varname => $value)
    {
      $this->data[$varname] = $value;		
      $this->replaced_data[$varname] = $this->remove_newlines($value);
    }
   }
   
   function clear_resources()
   {
     $this->data = array();	
     $this->replaced_data = array();
   }
   
   function remove_newlines($line)
   {
     $line = str_replace(array("\n", "\r"), "", $line);
     return $line;	
   }
   
   function replace($data)
   {
   	 
   	 $out = $data;
   	 
   	 foreach ($this->replaced_data as $k => $v)
   	   $out = str_replace('{'.$k.'}', $v, $out);
   	 
   	 return $out;  
   	
   }
   
   function localize()
   {
   	
   	 $args = func_get_args();
   	 $params = array_slice($args, 1);
   	 
   	
   	 $field = $this->replace($args[0]);
   /*  $result = '';
     $cnt = -1;
     $dcnt = 0;
     
     for ($i = 0; $i < strlen($field); $i++)
     {
       
        if (substr($field, $i, 3) == '///' && $cnt != -1)
        {
        	
        	$pos = strpos(substr($field, $i+3), '///');

        	$test = substr($field, $i+3);
        	$test = substr($test, 0, strpos($test,'///'));
        	
        	$i += $pos+5;

        	list($s, $p) = explode(':', $test, 2);
       
        	if ($params[$dcnt] == 1)
        	 $result .= $s;
        	else
        	 $result .= $p;
        	
        	continue;
        	
        }	
        
        
        if ($field[$i] == '%')
         $cnt++;
        
        if (substr($field, $i, 2) == '%d')
         $dcnt = $cnt;
         
        $result .= $field[$i];
     	
     }
   	*/
   	
     $text = vsprintf ($field, $params);

     
     return $text;
   }
   
   function get_resources()
   {
    return $this->data;	
   }
   
   function get_client_resources()
   {
    return $this->client_vars;	
   }
   
   function load_client_resources($arr)
   {
   	global $env;
   	$this->client_vars = $arr;
   	
   	if (!$env->is_postback())
   	{
   	  $env->script("resources = ".json_encode($arr).";");	
   	}
   	
   }
   
   function replace_json($data)
   { 
   	 
   	 $out = $data;
   	 
   	 foreach ($this->replaced_data as $k => $v)
   	 {
   	 	 
   	 	
   	   $out = str_replace('{'.$k.'}', str_replace("'", "\'", $v), $out);
   	   $out = str_replace(urlencode('{'.$k.'}'), urlencode(str_replace("'", "\'", $v)), $out);
   	 }
   	 return $out;  
   	
   	
   }
  
 }
 
 $this->i18n = new jyingo_i18n();
 
?>