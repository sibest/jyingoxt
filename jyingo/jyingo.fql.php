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
 
 /* USE:
  
   $db->connect( host, user, pass, db );
   
   **** save: **** 
   
   $res = $db->get('SELECT * FROM ..');
   $res->value = 'new value';
   $res->save();
   
   **** cycle: **** 
   
   $res = $db->get('SELECT * FROM ..');
   while ($res = $res->cont())
   {
        .... 
   }
  
 */
 
 define('FQL_NEWLINE', "\r\n");
 
 class cls_fql_fetch {
 
  private $data;
  var $count;
 	private $start;
 	
 	function __construct($data, $ac)
 	{
 		
 		$this->start = 0;
 		
 		
 		$this->data = $data;
 		$this->count = count($data);
 		
 		$this->cont();
 		$this->start--;
 		
 		
  }
  
  
  
  function reset()
  {
  	 $this->start = 0;
  }
  
  function reverse() {
  	if ($this->count)
  	{
  		
  		
  	  $this->data = array_reverse($this->data);
  	  $this->start = 0;
  	  $this->cont();
  	  $this->start--;
  	}
  }
  
  function cont()
  {
  	 if ($this->start >= $this->count)
  	  return 0;
  	 
  	 if ($this->count == 0)
  	  return 0;
  	 
  	 $this->refresh_vars();
  	 $this->start++;
  	 return $this;
  	 
  }
  
  private function refresh_vars()
  {
  	 if (is_array($this->data[$this->start]))
  	 foreach ($this->data[$this->start] as $field => $value)
  	 {
  	   
  	   
  	   if (is_int($value) || is_float($value))
  	   {
  	   	
  	   
  	   //  $value = sprintf("%.0f", $value);
  	     $this->data[$this->start][$field] = $value;

  	   }
  	   
  	 	 $this->$field = $value;
  	 }

  	 

  }
 

 	
 }

 
 class cls_conn_fql {
 	
 	 private $saved_access_token;
   private $stream;
   
   
   
   function connect()
   {
   	
   	 
   	 $this->stream = 
   	  stream_socket_client('ssl://api.facebook.com:443');
   	
   	
   } 
   
   function set_access_token($access_token)
   {
   	 $this->saved_access_token = $access_token;
   }
   
   function get($query, $access_token = -1)
   {
     $ac = ($access_token == -1 ? $this->saved_access_token : $access_token);
   	 $this->saved_access_token = $ac;


   	 
     $body = file_get_contents('https://api.facebook.com/method/fql.query?format=json&query='.urlencode($query).'&access_token='.$ac);

     $output = json_decode($body, true);

     
     
     $res = new cls_fql_fetch($output, $ac);
     return $res;
     
 
   	   
   } 	
 }
 
 $this->fql = new cls_conn_fql();
 
?>