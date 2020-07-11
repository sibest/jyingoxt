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

/* misc */

function std_file_get_contents($file)
{
	 $fd = fopen($file, "r");
	 $contents = fread($fd, filesize($file));
 	 fclose($fd);	 
 	 
 	 return $contents;
}

function std_file_put_contents($file, $data)
{
	
	@unlink($file);
	$fp = fopen($file, 'w');
	fwrite($fp, $data);
	fclose($fp);
}

 function jyingo_check_utf8($str) { 
    $len = strlen($str); 
    for($i = 0; $i < $len; $i++){ 
        $c = ord($str[$i]); 
        if ($c > 128) { 
            if (($c > 247)) return false; 
            elseif ($c > 239) $bytes = 4; 
            elseif ($c > 223) $bytes = 3; 
            elseif ($c > 191) $bytes = 2; 
            else return false; 
            if (($i + $bytes) > $len) return false; 
            while ($bytes > 1) { 
                $i++; 
                $b = ord($str[$i]); 
                if ($b < 128 || $b > 191) return false; 
                $bytes--; 
            } 
        } 
    } 
    return true; 
 }
 
 function jyingo_check_utf8_encode($str) {
  if (	jyingo_check_utf8($str)) return $str;
  return utf8_encode($str);
 }

// utilizzare igbinary per il json
include_once "jyingo/jyingo.tree.php"; // temporaneo

class jyingo_params implements ArrayAccess {
	
	private $arr, $v_arr;
	
	function __construct($params = array())
	{
		$this->v_arr = $params;
		$this->arr = $params;
		
	}
	
	function offsetGet($v)
	{
		return $this->arr[$v];
	}
	
	function offsetSet($v, $k)
	{
		
	}
	
	function offsetUnset($k)
	{
		
	}
	
	function has($v)
	{
		return isset($this->v_arr[$v]);
	}
	
	function offsetExists($v)
	{
	  return isset($this->v_arr[$v]);
	}
	
	function __get($v)
	{
		 
		 unset($this->arr[$v]);
		 return $this->v_arr[$v];
		 
	}
	
	function get_array()
	{
		return $this->arr;
	}
	
	
}
 
class jyingo {
 
 private $_ispostback;
 private $_soundmgr;
 private $_memcached;
 
 public static $_S = array();
 public static $_PS = array();
 
 private $_use_apc = true;
 
 private $num_of_clsid = 0;
 
 public static $_G;
 private $_seq = 0;
 private $_requestseq = 0;
 
 private $_serialized_length = 0,
         $_unserialized_length = 0;

 private $debug_text = '';

 private $JYINGO_DIR;
 private $SESSION_DIR;
 private $PSESSION_DIR;
 private $INSTANCE_DIR;
 private $UPLOADCACHE_DIR;
 private $CACHE_DIR;
 private $COOKIE_DOMAIN = '.playruneverse.com';
 private $COOKIE_PATH = '/';
 
 private $_marked = array();
 
 private $_loading_stack = array();
 private $_event_loading = false;
 private $_loading_checklist = array();
 
 private $INCLUDE_PATHS = array();
 
 private $session_id = NULL;
 
 private $appdata = NULL;
 private $appinstance = NULL;
 private $instance_cache = array();
 
 private $psession_id = NULL; 
 private $psession_identifier = NULL;
 
 private $jscache = array();
 private $csscache = array();
 private $scopedcsscache = array();
 private $active_tags = array();
 
 private $root = NULL;
 private $scriptdata = array();
 
 private $loaded_modules = array();
 private $postback_caller = NULL;
 private $postback_event = NULL;
 private $postback_data = NULL;
 private $postback_type = NULL;
 private $postback_code = NULL;
 private $postback_service = NULL;
 
 private $write_callbacks = array();
  
 private $loaders = array();
 private $templates = array();
 private $includes = array();
 
 private $object_retained = array();
 private $batch_queries = array();
 
 public $db = NULL;
 public $fql = NULL;
 public $i18n = NULL;
 
 private $do_code = array();
 private $memcached = NULL;
 
 private $_invalidate_write = false;
 private $requestData;

 private $_important = false;
 private $_page_seq = 0;

 function cache_set($key, $value, $expire = 0)
 {
 	if ($this->use_apc_cache())
 	{ 
 	  apc_store($key, $value, $expire); 
 	} else {
   	memcache_set($this->memcached, $key, $value, 0, $expire);
  }
 }
 
 function invalidate_write()
 {
 	
   $this->_invalidate_write = true;	
 }
 
 function cache_get($key)
 {
 	if ($this->use_apc_cache())
 	{ 
 	  return apc_fetch($key); 
 	} else {
 	  return memcache_get($this->memcached, $key);
 	}
 }	
 
 function serialize($data) {
 
 	/*if (is_callable('igbinary_serialize'))
 	 return igbinary_serialize($data);
 	else*/
 	 return  serialize($data);
 	
 }
 
 function object_retain($obj)
 {
 	$this->object_retained[] = $obj;
 }
 
 function unserialize($data) {
 	
 	/*if (is_callable('igbinary_unserialize'))
 	 return igbinary_unserialize($data);
 	else*/
 	 return unserialize($data);
 }
 
 function getInstanceId() {
  return $this->appinstance;
 }
 
 public function add_path($path, $scoped = false)
 {
 	$this->INCLUDE_PATHS[] = $path;
 	
  
 	$this->jscache = array_merge($this->jscache, $this->dirsearch($path, 'js'));
 	$csslist = $this->dirsearch($path, 'css');
 	if ($scoped) $this->scopedcsscache = array_merge($this->scopedcsscache, $csslist); 	

  $this->csscache = array_merge($this->csscache, $csslist); 	
  
 }
 
 function write_upload_session($session, $data)
 {
 	
 	 std_file_put_contents($this->UPLOADCACHE_DIR.$session.'.dat', $this->serialize($data));
 	 return TRUE;
 	
 }
 
 function read_upload_session($session)
 {
 	 
 	 $content = @std_file_get_contents($this->UPLOADCACHE_DIR.$session.'.dat');
 	 
 	 $out = $this->unserialize($content);
 	 if (!$out)
 	  return FALSE;
 	 
 	 return $out;
 	 
 }
 
 private function dirsearch($path, $extension = NULL, $prefix = NULL)
 {
 	
 	$result = array();
 	$dirs = array();
 	$d = dir($path);
	while ($entry = $d->read()) {
	    
	    $parts = explode('.', $entry);
	    $ext = $parts[count($parts)-1];
	    $pre = $parts[0];
	    
	    if ($entry == '.' || $entry == '..') continue;

	    if ($prefix && $prefix != $pre)
	     continue;
	    
	    
	    if (is_dir($path.$entry))
	    {
	    	
	    	$dirs[] = $path.$entry.'/';
	    	
	    	
	    } else {


		    if ($extension && $extension != $ext)
		     continue;
	    
	    	    	
	    	$result[] = $path.$entry;
	    	
	    }
	    
	}
	$d->close();
	
 	
 	sort($result);
 	
 	foreach ($dirs as $dir)
 	{
	 $result = array_merge($result, $this->dirsearch($dir, $extension, $prefix)); 		
 	}
 	
 	return $result;
 	
 }
 
 function get_page()
 {
 	return jyingo_windowmanager::get_mgr()->get_page();
 	
 }
 
 function __destruct()
 {
 	global $_DISABLE_PERSISTENT, $DISABLE_JYINGO;
 	
 	if (!$_DISABLE_PERSISTENT || $DISABLE_JYINGO)
   return;
 	
 	if ($this->memcached)
	 memcache_close($this->memcached);
	
 }
 
 function log_http($call, $msg)
 {  
 /*	  $fp = fopen('/var/log/jyingo.log','a+');
    fwrite($fp, @date('d/m/Y H:i:s').' ['.$_SERVER['REMOTE_ADDR'].'] '.$call.' :: '.$msg."\r\n");
    fclose($fp); */
 }
 
 function __construct() {

 
  $this->JYINGO_DIR = 'jyingo/'; 
  $this->SESSION_DIR = ($GLOBALS['JYINGOXT_FOLDER'] ? $GLOBALS['JYINGOXT_FOLDER'] : 'jyingo/'). 'session/';
  $this->PSESSION_DIR = ($GLOBALS['JYINGOXT_FOLDER'] ? $GLOBALS['JYINGOXT_FOLDER'] : 'jyingo/'). 'psession/';
  $this->INSTANCE_DIR = ($GLOBALS['JYINGOXT_FOLDER'] ? $GLOBALS['JYINGOXT_FOLDER'] : 'jyingo/'). 'instance/';
  $this->UPLOADCACHE_DIR = ($GLOBALS['JYINGOXT_FOLDER'] ? $GLOBALS['JYINGOXT_FOLDER'] : 'jyingo/'). 'uploadcache/';
  $this->CACHE_DIR = ($GLOBALS['JYINGOXT_FOLDER'] ? $GLOBALS['JYINGOXT_FOLDER'] : 'jyingo/'). 'cache/';
 
  global $env;
  $env = $this;
  
  
 	global $DISABLE_JYINGO, $_DISABLE_PERSISTENT;

  
  
  $this->num_of_clsid = time();
  
 	if (!$DISABLE_JYINGO) 
 	$this->memcached = ($_DISABLE_PERSISTENT  ? memcache_connect('127.0.0.1', 11211) : memcache_pconnect('127.0.0.1', 11211));
  
  

  $this->INCLUDE_PATHS[] = $this->JYINGO_DIR;
  
	$phps = array();  
	$phps = array_merge($phps,  $this->dirsearch($this->JYINGO_DIR, 'php', 'jyingo')); 	
	$phps = array_merge($phps,  $this->dirsearch($this->JYINGO_DIR.'../module/', 'php'));  
	$phps = array_merge($phps,  $this->dirsearch($this->JYINGO_DIR.'../page/', 'php'));
	
	
	$max_php_time = 0;
	foreach ($phps as $phpfile)
	{
	 $mtime = filemtime($phpfile);
	 if ($mtime > $max_php_time)
	  $max_php_time = $mtime;
	} 
	
  if (!$DISABLE_JYINGO) {
  	
			$declared = NULL;
			
			if ( intval($this->cache_get('declared_classes_cache')) < $max_php_time )
			{
			  $declared = get_declared_classes();	
			  
			  
		 	 	 if (is_callable('_put_ptempo'))
		 	  _put_ptempo('jyingo: getting declared classes');
			  
			} else {
						
		 	 	 if (is_callable('_put_ptempo'))
		 	  _put_ptempo('jyingo: fetching declared classes');
		 	  
				$classes = json_decode($this->cache_get('declared_classes_cache_classes'), true);
			}
			
	}
	
	

	include_once $this->JYINGO_DIR.'jyingo.child.php';
	include_once $this->JYINGO_DIR.'jyingo.control.php';
	include_once $this->JYINGO_DIR.'jyingo.module.php';
	include_once $this->JYINGO_DIR.'jyingo.render.php';


	foreach ($phps as $php)
	{
	 ob_start();
	 include_once $php;	
	 $template = ob_get_contents();
	 ob_end_clean();
	  
	 $classname = str_replace('.php', '', array_pop(explode('/', $php)));

	 $this->templates[$classname] = $template;
	 
	}
  if ($DISABLE_JYINGO)
   return; 
  
  
  if ($declared)
  {
  	
	 $classes = array_diff(get_declared_classes(), $declared);
	 $this->cache_set('declared_classes_cache', $max_php_time);
	 $this->cache_set('declared_classes_cache_classes', json_encode($classes)); 
	
	 
  }
  
  
 	 
 	 
  foreach ($classes as $class)
  {
	 	 	
	 	 	 $vars = get_class_vars($class);
	 	 	 if (isset($vars['active_tag']))
	 	 	 {
	 	 	 	
	 	 	 	 $this->active_tags[$vars['active_tag']] = $class;
	 	 	 	
	 	 	 }
	 	 	
	 }



 	 ob_start();
 	 
   $sid = $_COOKIE['jyingo_session'];
 
 	 if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$DISABLE_JYINGO) 
 	 {

     global $__TDATA;
     
     $data = file_get_contents('php://input'); 
     
     $this->_requestseq = intval($_GET['seq']);
     if (intval(ini_get("magic_quotes_gpc"))) $data = stripslashes($data);
     
     $d = json_decode($data, true);
     if (!is_array($d))
      $d = json_decode($data.']', true); 
     
     
     
     if ($d === NULL)
     {
      
     	 $this->log_http($d[1],'invalid postback');
       $this->error('invalid postback');
     }
     if (!isset($d[0]))
     {
     	    $this->log_http($d[1],'missing instance');
     	    $this->error('missing instance');   
     }
     $sid = $d[0];
     
     if (!$this->load_instance($sid))
     {
      
       
     	 $this->log_http($d[1],'unable to load application instance');
       $this->error('unable to load application instance');
     }
     
     $type = $d[1];
     if ($type != 'call' && $type != 'event')
     {
     
     	$this->log_http($d[1],'call type unknown');
     	$this->error('Call type unknown');
     }
     $this->postback_type = $d[1];
     
     $this->postback_caller = $d[2];
     
     $this->postback_data = ($d[4]);
     
     $this->requestData = var_export($d, true)."\r\n\r\n".$this->cache_get_data($sid);
     
     
     $obj = $this->root->get_by_instance($d[2]);
     	  
     if ($obj === NULL)
     {
        
       	$this->log_http($d[1],'unknown object');
      	$this->error('Unknown object', false);
     }
     
      
     $this->postback_event = $d[3];
      
     if ($type == 'call')
     {

       if (!is_object($obj))
       {
       
     	    $this->log_http($d[1],'not an object');
		     	$this->error('not an object', false);
       }
       $allowed = false;
       
       if (!is_callable(array($obj,'is_call_allowed')))
       {
     	    $this->log_http($d[1],'object can\'t spawn events');
		     	$this->error('Object can\'t spawn this event at the moment', false);
       } else {
         try {
          $allowed = $obj->is_call_allowed($d[3]);
         } catch (Exception $exc)
         {
        
         }
       }
       
		   if (!$allowed ||
		       !$obj->visible ||
		        $obj->disabled)
		   {
		   	
     	    $this->log_http($d[1],'object can\'t spawn events');
		     	$this->error('Object can\'t spawn this event at the moment', false);
		   }     
	     
	     
	     
	     
	     $this->postback_code = $d[5];
	     $this->postback_data = $d[4];
	     $this->postback_service = $d[3];
	     

    	
     } elseif ($type == 'event')
     {
       
       $evs = array($d[3]);
       if (is_array($d[3])) $evs = $d[3];
 
        $this->postback_event = $evs;
       
       if (!is_object($obj))
       {
       
     	    $this->log_http($d[1],'not an object');
		     	$this->error('not an object', false);
       }
       
       $allowed = false;
       
       if (!is_callable(array($obj,'is_event_allowed')))
       {
     	    $this->log_http($d[1],'object can\'t spawn events');
		     	$this->error('Object can\'t spawn this event at the moment', false);
       } else {
         try {
          
          $allowed = true;
          
          foreach ($evs as $e)
          {
          
           $allowed = $obj->is_event_allowed($e);
           if (!$allowed) break;
           
          }
          
         } catch (Exception $exc)
         {
            $allowed = false;
         }
       }
        
    
		   if (!$allowed ||
		         !$obj->visible ||
		         !$obj->allow_postback ||
		         $obj->disabled)
		   {
		     
     	   $this->log_http($d[1],'Object can\'t spawn this event at the moment');
		     $this->error('Object can\'t spawn this event at the moment', false);
		     	
		   }
		    
	    
	     
	
		   $data = $this->postback_data;
	
		   foreach ($data as $arr)
		   {
		     list($key, $value) = $arr;
		     
		     
		     if ($this->root->get_by_instance($key))
		     {
	         
	         if (!$this->root->get_by_instance($key)->disabled &&
	              $this->root->get_by_instance($key)->visible)
	              
		     	 $this->root->get_by_instance($key)->update_value($value);
		     
		     } else {
		     	 
		     	 $_POST[$key] = $value;
		     	 
		     }	
		   	
		   }
		   
     }
     
     $this->postback_caller_object = $obj;
     
 	   $this->_ispostback = true;
 	 } else {
 	 	
	   if (!$this->load_session($sid))
	     $this->error('unable to load application session');     
    
   
	
	 	 $this->jscache = $this->dirsearch($this->JYINGO_DIR, 'js', 'jyingo');
	 	 $this->jscache = array_merge($this->jscache, $this->dirsearch($this->JYINGO_DIR.'extra/', 'js'));
	 	 
	   $this->csscache = $this->dirsearch($this->JYINGO_DIR, 'css', 'jyingo');
	 	 $this->csscache = array_merge($this->csscache, $this->dirsearch($this->JYINGO_DIR.'extra/', 'css'));

 	 	
 	 	 if (!$this->create_instance())
 	 	   $this->error('unable to create application instance');


 	   $this->_ispostback = false;
 	   
 	   
 	   
 	 }
 
 	
 }
 
 function get_debug_info()
 {
  return $this->requestData;	
 }
 
 function is_postback()
 {
  return $this->_ispostback;	
 }
 
 public function redirect($location, $delay = 0)
 {
 	
 	 if (headers_sent() == false && !$this->is_postback())
 	 {
 	   header('Location: '.$location);
 	   exit;
 	 }
 	
 	 $this->client_do(0, 'redirect', array("location" => $location, "delay" => $delay));
 	
 	 
 }
 
 function setup_child($instance, $object)
 {
   $this->instance_cache[$instance] = $object;	
 }
 
 function delete_child($instance)
 {
  unset($this->instance_cache[$instance]);	
 }
 
 private function create_instance()
 {  
 	  $id = $this->cache_free_id();


 	
  	$this->appinstance = $id;
    return true;
 } 
 
 function cache_store_data($id, $value, $time = 900, $compress = TRUE)
 {
 	
 	if ($this->use_apc_cache())
 	{
 		 
 		 
 		 apc_store($id, /*$compress ? lz4compress($value) :*/ $value, $time);
 		 
 	} else {
 	 
 	$compressed = null;
 	if (is_callable('igbinary_serialize'))
 	  $compressed = $value;
 	else
    $compressed = $compress ? lz4compress($value) : $value; 	 
 	
 	 	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: compress time');
 	  

 	 memcache_set($this->memcached, $id,  $compressed, 0, $time);
 	}
 }
 
 function use_apc_cache($use_apc = -1)
 {
 	 
 	 if ($use_apc !== -1)
 	  $this->_use_apc = $use_apc;
 	
   return $this->_use_apc;
 }
   
 function cache_get_data($id, $compress = TRUE)
 { 
 	  
 	  if ($this->use_apc_cache())
 	  {
 	  	
 	  	$data = apc_fetch($id);
 	  /*	if ($data && $compress)
 	  		$data = lz4uncompress($data);*/
 	  	
 	  } else {
 	   
	 		$data = memcache_get($this->memcached, $id);
	 		
	 	  $this->_serialized_length += strlen($data);
	 		if (!is_callable('igbinary_serialize'))
	 		{
	 			if ($data && $compress)
	 	  	 return  lz4uncompress($data);
	 	  	 
	 	  	return NULL;
	 		}
 	  }
 	  
 		return $data;
 		 
 	
 	/*
 	if (file_exists($this->filetree($this->INSTANCE_DIR.$id)))
 	{
 		return file_get_contents($this->filetree($this->INSTANCE_DIR.$id));
 	}
 	return FALSE;*/
 }
 
 function cache_free_id()
 {
 	
 	
 	 $id = 0;
 	
	 	if ($this->use_apc_cache())
	 	{
	 				 	
		 	while (!$id || apc_exists($id))//file_exists($this->filetree($this->INSTANCE_DIR.$id)))
		 	   $id = md5(mt_rand(1,999999999).';'.time().';'.$_SERVER['REMOTE_ADDR']);
		 	   
	 	} else {
		 	
		 	while (!$id || memcache_get($this->memcached, $id))//file_exists($this->filetree($this->INSTANCE_DIR.$id)))
		 	   $id = md5(mt_rand(1,999999999).';'.time().';'.$_SERVER['REMOTE_ADDR']);
	 	}
 	
 	 return $id;
 	
/*
    $id = 0;
    while (!$id || file_exists($this->filetree($this->INSTANCE_DIR.$id)))
    {
    	$id = md5(mt_rand(1,99999).time().mt_rand(1,999));
    }
    return $id;
*/

 }
 
 function write_session()
 {
 	
 	 if ($this->session_id != NULL)
 	 {
 	 	
 	 	$serialized = $this->serialize( array("jyingo_ip" => $_SERVER['REMOTE_ADDR'],
 	                           "data" => jyingo::$_S));
 	   
 	   /*std_file_put_contents( $this->filetree($this->SESSION_DIR.$this->session_id),
 	   $serialized);
 	   	*/
 	   	
 	   	
 	   	 	   $this->cache_store_data($this->session_id, 
 				$serialized, 86400);
 	   	
 	 }
 	 

 }
 
 function write_instance()
 {
 	 	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: before serialize');
 	  
     $this->appdata = /*$this->serialize(*/array("root" => $this->root, "cache" => $this->instance_cache)/*)*/;
     
     
 	 	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: after serialize');
     
     $data = array("jyingo_ip" => $_SERVER['REMOTE_ADDR'],
                   "seq" => $this->_requestseq,
 	                           "data" => $this->appdata,
 	                           "retained" => $this->object_retained,
 	                           "sessiondata" => jyingo::$_S,
 	                           "sessionid" => $this->session_id/*,
 	                           "loaded_modules" => $this->loaded_modules,
 	                           "includes" => $this->includes*/);
     
     
     $serialized = $this->serialize( $data );
 	                           
 	                           
 	 	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: after secondary serialize');
 	                      	                           
 	    if (isset($_GET['print_instance']))
 	    {
 	     file_put_contents('cache/unserialized.txt', $serialized);
     }
 	   //file_put_contents( $this->filetree($this->INSTANCE_DIR.$this->appinstance), 
 	   $this->cache_store_data($this->appinstance, 
 				$serialized);
 	  
 	                          
 	 	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: after store data'); 
 	    
 	                          
 	                           

 } 
 
 function propagate_message($caller, $messagetype, $data)
 {
  $this->root->propagate_message($caller, $messagetype, $data);
 }
 
 function write_psession()
 {
   
   if ($this->psession_id != NULL)
   {

 	   std_file_put_contents( $this->filetree($this->PSESSION_DIR.$this->psession_id),
 	   $this->serialize( array("psess_identifier" => $this->psession_identifier,
 	                           "data" => jyingo::$_PS)));   	
   		
 	 }
 }
 
 function get_by_instance($item)
 {
  return isset($this->instance_cache[$item]) ? $this->instance_cache[$item] : NULL ;	
 }
 
 function free()
 {
   $this->root->free();
   $this->root = NULL;
   $this->db = NULL;
   $this->i81n = NULL;
   $this->instance_cache = NULL;
   
   
   	
 }	
 
 function get($item, $index = -1)
 {
   return $this->root->get($item, $index);
 } 
 
 function add_child($item)
 {
  $this->root->add_child($item);	
 }
 
 function important()
 {
 	 $this->_important = true;
 	 $this->_page_seq++;
 }
 
 function write()
 {
   
   
   
   if ($this->_invalidate_write == true)
    return;
   

 	 foreach ($this->write_callbacks as $callback)
 	  call_user_func($callback);
   
   $last = 0;
   
   if ($this->_requestseq && !$this->_important)
   {
     $last = intval(@$this->cache_get_data($this->appinstance.'_seq', FALSE));
     if ($last > $this->_page_seq)
     	return;
     
   }
   
  
   if ($this->_seq <= $this->_requestseq || $this->_requestseq == 0 || $this->_important)
   { 
   	
    $this->cache_store_data($this->appinstance.'_seq', $this->_page_seq, 900, FALSE);
   	 
    $this->write_session();
    $this->write_psession();     
    $this->write_instance();	
    
 	 }
 }
 
 public function filetree($folder)
 {
	
	$path = explode('/', $folder);
	
	$base_path = implode('/', array_slice($path, 0, count($path)-1));


	
	$filename = $path[count($path)-1];
	$path1 = $base_path.'/'.$filename[0];


	$path2 = $base_path.'/'.$filename[0].'/'.$filename[0].$filename[1];
	$path3 = $base_path.'/'.$filename[0].'/'.$filename[0].$filename[1].'/'.$filename;
	
 
	
	
	if (!is_dir($path1)) @mkdir($path1,0777);
	if (!is_dir($path2)) @mkdir($path2,0777);

	return $path3;

 }
 
 private function makesession($path)
 {
 	
 	 return $this->cache_free_id();
 	 
 	 $sess = '';
 	 
 	 while (!$sess || file_exists($this->filetree($path.$sess)))
 	  $sess = md5(mt_rand(1,9999999999).time());
 	 
 	 return $sess;
 	
 }
 
 private function is_md5($string) {
  return !empty($string) && preg_match('/^[a-f0-9]{32}$/', $string);	
 }
 
 public function load_session($id)
 {
 	
 	  $new = false;

 	  
 	  if (!$id)
 	  {
 	 
 	  	 // crea una nuova sessione
 	  	 $sess = $this->makesession($this->SESSION_DIR);
 	  	 $this->cookie('jyingo_session', $sess);

 	  } elseif (!$this->is_md5($id)) {
 	  	 	  	
 	
 	  	 return false;
 	  	 
 	  /*} elseif (!file_exists($this->filetree($this->SESSION_DIR.$id))) {
 	  	
 	  	
 	  	 // crea una nuova sessione
 	  	 $sess = $this->makesession($this->SESSION_DIR);
 	  	 $this->cookie('jyingo_session', $sess);

 	  } else {*/

 	  } elseif (!($data = $this->cache_get_data($id))) {
 	  
 	   	   $d = array();
 	  	   $sess = strtolower($id);

 	  } else {
 	 
 	  	// $d = $this->unserialize(std_file_get_contents($this->filetree($this->SESSION_DIR.$id)));
       $d = $this->unserialize($data);
       
 	  	 if ($d['jyingo_ip'] != $_SERVER['REMOTE_ADDR'])
 	  	 {
 	  	 	
	 	  	 $sess = $this->makesession($this->SESSION_DIR);
	 	  	 $this->cookie('jyingo_session', $sess);
 	  	   
 	  	 } else {
 	  	 
 	  	   $sess = strtolower($id);
 	  	  
 	  	   jyingo::$_S = $d['data'];
 	  	 }
 
 	  }

 	  $this->session_id = $sess;
 	  return true;
 	  
 	  
 }
 
 public function cookie($name, $value, $expire = NULL) 
 {
 	
   setcookie($name, $value, $expire, $this->COOKIE_PATH, $this->COOKIE_DOMAIN, FALSE, TRUE);
   	
 }
 

 
 function get_debug_size()
 {
  return array(	$this->_serialized_length, $this->_unserialized_length);
 }
 
 private function load_instance($id, $testing = false)
 {
   global $__TDATA;
   
   if (!$this->is_md5($id)) {
 	  	 return false;
 	  	 
 	  } elseif (!($data = $this->cache_get_data($id))) {
 	  	 return false;

 	  } else {
 	  	
 	  	 
 	  	// $this->_unserialized_length += strlen($data);
 	  	 $d = $this->unserialize($data);//file_get_contents($this->filetree($this->INSTANCE_DIR.$id)));

 	  	 if ($d['jyingo_ip'] != $_SERVER['REMOTE_ADDR'])
 	  	 {
 	  	   return false;	
 	  	 }
 	  	 
 	  	 
 	  	 jyingo::$_S = $d['sessiondata'];
       $this->session_id =  $d['sessionid'];
       
       
       $this->_seq = $d['seq'];
       $this->_page_seq = intval(@$this->cache_get_data($id.'_seq', FALSE));
       
 	  	 $this->appinstance = strtolower($id);	 

/*
 	  	 foreach ($d['includes'] as $classname => $file)
 	  	 {
 	  	 	ob_start();
 	      include $file;
 	      $this->templates[$classname] = ob_get_contents();
 	      ob_end_clean();
       }
       */
     //  $this->loaded_modules = $d['loaded_modules'];
     //  $this->includes = $d['includes'];     
 	  	 $appdata =  $d['data'];//$this->unserialize($d['data']);
 	  	 
 	  	 $this->root = $appdata['root'];
 	  	 
 	  	 
 	  	 $this->instance_cache = $appdata['cache'];

 	  	 $this->object_retained = $d['retained'];

 	  	 
 	  	 
 	  	 foreach ($this->object_retained as $obj)
 	  	  $obj->retained();
      
 
       
       
 	  }
 	  
 	  return true;
 
 	
 	
 }
 
 private function error($msg, $check_refresh = true)
 {
 
   if (!$check_refresh)
    exit;

   if ($this->_seq <= $this->_requestseq || $this->_requestseq == 0)
   {
   	 echo 'e0_refresh_page';
   }
   
	 exit;
 }
 
 public function reload()
 { 	$this->write();
   	 echo 'e0_refresh_page';	
   	 exit;
 }

  
 public function requires($classname)
 { 
 	  if (!is_array($classname) && strpos($classname,'<') !== FALSE)
 	  {
 	  	list($a, $b) = explode('<', $classname, 2);
 	  	$b = substr($b, 0, strlen($b) - 1);
 	  	$classname = $a.$this->active_tags[$b];
 	  }
 	  
 	  if (is_array($classname)) {
 	  	
 	  	list($classname, $name, $index) = $classname;
 	  	if ($index)
 	  	 $name = $name.'['.$index.']';
 	  	
 	  } elseif (substr_count($classname,':'))
 	  {
 	  
 	  	list($name, $classname) = explode(':', $classname, 2);
 	  
 	  } else {
 	  	
 	    $name = "unnamed_control_".mt_rand(1,9999999);
 	     	
 	  }
 	  /*
 	  if (!array_key_exists($classname, $this->loaded_modules))
 	  {
 	  	  
 	  	  $this->loaded_modules[$classname] = 1;
 	      foreach ($this->INCLUDE_PATHS as $path)
 	      {
 	      	
 	      	if (file_exists($path.$classname) && file_exists($path.$classname.'/'.$classname.'.php'))
 	      	{
 	      	  
 	      	  	
 	      	  	ob_start();
 	      	  	include $path.$classname.'/'.$classname.'.php';
 	      	  	
 	      	  	$template = ob_get_contents();
 	      	  	ob_end_clean();
 	      	  	
 	      	  	
 	      	  	$this->templates[$classname] = $template;
 	      	  	$this->includes[$classname] = $path.$classname.'/'.$classname.'.php';
 	      		
 	      	
 	      	}
 	      	
 	      }
 	  	
 	  } 	*/
 }

 public function load($classname, $params = NULL, $view = NULL, $context = NULL)
 {
 	  if (!is_array($classname) && strpos($classname,'<') !== FALSE)
 	  {
 	  	list($a, $b) = explode('<', $classname, 2);
 	  	$b = substr($b, 0, strlen($b) - 1);
 	  	$classname = $a.$this->active_tags[$b];
 	  }
 	  
 	  if (is_array($classname)) {
 	  	
 	  	list($classname, $name, $index) = $classname;
 	  	if ($index)
 	  	 $name = $name.'['.$index.']';
 	  	
 	  } elseif (substr_count($classname,':'))
 	  {
 	  
 	  	list($name, $classname) = explode(':', $classname, 2);
 	  
 	  } else {
 	  	
 	    $name = "unnamed_control_".mt_rand(1,9999999);
 	     	
 	  }
 	  /*
 	  if (!array_key_exists($classname, $this->loaded_modules))
 	  {
 	  	  
 	  	  $this->loaded_modules[$classname] = 1;
 	  	
 	      foreach ($this->INCLUDE_PATHS as $path)
 	      {
 	      	
 	      	if (file_exists($path.$classname) && file_exists($path.$classname.'/'.$classname.'.php'))
 	      	{
 	      	  
 	      	  	
 	      	  	ob_start();
 	      	  	include $path.$classname.'/'.$classname.'.php';
 	      	  	
 	      	  	$template = ob_get_contents();
 	      	  	ob_end_clean();
 	      	  	
 	      	  	
 	      	  	$this->templates[$classname] = $template;
 	      	  	$this->includes[$classname] = $path.$classname.'/'.$classname.'.php';
 	      		
 	      	
 	      	}
 	      	
 	      }
 	  	
 	  }*/
 	  
 	  $classname_clean = str_replace('.', '_', $classname);
 	  $arr = new jyingo_params($params);

 	  $obj = new $classname_clean($arr);
 	  $obj->set_name($name);
 	  
 	  
 	  if (is_array($params))
 	  {
     
     $array = $arr->get_array(); 	  
     
   	

 	   foreach ($array as $param => $value) 
 	    if (!is_object($value))
 	     $obj->set_property($param, $value);
    }
    
 	  if ($context != NULL)
 	  {
 	  	 $obj->set_delegate($context);
 	  }
 	  
 	  $_class = $classname;
 	  if (substr_count($view, ':'))
 	  {
 	  	$_class = str_replace('.', '_', $_class);
 	  	list($_class, $view) = explode(':', $view, 2);
 	  	

 	  }
 	  
 	  if (isset($this->templates[$_class]))
 	  {
 	   
 	   	$this->buildView($this->templates[$_class], $obj, ($view ? $view : 'default'));
 	  	
 	  }
 	  
 	  if ($this->_event_loading)
 	   $this->_loading_checklist[$obj->get_instance()] = $obj;
   
    
 	  return $obj;
 } 
 
 function setup_control_template($object, $type, $class, $template = NULL)
 {
 	$this->buildView(isset($this->templates[$type.'.'.$class]) ? $this->templates[$type.'.'.$class] : $this->templates[$type.'.'.str_replace('.','_',$class)], $object, ($template ? $template : 'default'));
 	
 }
 

 
 function notify_load($instance)
 {
  
  if (isset($this->_loading_checklist[$instance]))
   unset($this->_loading_checklist[$instance]);
  	
 }
 
 function alert($text)
 {
 	
  	$this->script_call('alert', $text);
  	
 }
 
 public function getScriptData()
 {
  
  
  $remove = $this->get_do_code('remove_by_parent');
  if ($remove)
  {
  	
  	foreach ($remove as $arr)
  	 $script.='jyingo.d('.json_encode(array("do" => 'remove_by_parent', "p" => $arr)).');';
    
    
  } 
  
  $remove = $this->get_do_code('remove');
  if ($remove)
  {
  	
  	foreach ($remove as $arr)
  	 $script.='jyingo.d('.json_encode(array("do" => 'remove', "p" => $arr)).');';
 
  }  
  
  
  $load = $this->get_do_code('load');

  if ($load)
  {
  	$script .= 'jyingo.d('.json_encode(array("do" => 'load_r', "p" => $load )).');';
  }

  
  foreach ($this->do_code as $do => $params)
  {
  	
  	foreach ($params as $arr)
  	{
  		
  		$script.='jyingo.d('.json_encode(array("do" => $do, "p" => $arr[0])).');';
  		
  	}
  	
  }


  
  $script .=  implode('', $this->scriptdata);	
 	return $script;
 }
 
 function mark_released($obj)
 {
   $this->_marked[] = $obj; 	
 }
 
 private function get_do_code($key)
 {
    
    if (!isset($this->do_code[$key]) ||
        !count($this->do_code[$key]))
        return false;
 	  
 	  $ret = array();
 	  foreach ($this->do_code[$key] as $s => $v)
 	   $ret[$v[2]] = $v[0];
 	  
 	  unset($this->do_code[$key]);
 	  return $ret;
 }

 public function end()
 {
  
  if ($this->_ispostback) {
  
  
  
   ob_end_clean();
   
      
   $response = array();


   $this->root->postback_start();
   
   if ($this->postback_type == 'event')
   {
	   $response['caller'] = $this->postback_caller;
	   
	   foreach ($this->postback_event as $e)
	    $this->postback_caller_object->event($e);   	
   }
 	 elseif ($this->postback_type == 'call')
 	 {
 	  $return = $this->postback_caller_object->call_delegate( $this->postback_service, $this->postback_data); 

	  $response['service'] = $this->postback_code;
	  $response['service_data'] = $return;
 	 }


   $this->_set_loading(true);   
   $this->root->postback_load();
   $this->_set_loading(false);
   
   $this->parse_load_checklist('postback_load');
   
    $this->_set_loading(true);   
   $this->root->postback_preview();
   $this->execute_batch_queries();
    $this->_set_loading(false);   
   $this->parse_load_checklist('onload');
 	 
   /* finalize objects */
   $out = array();
   
   $this->root->finalize($out);

   if (!empty($out))
    $response['c'] = $out;
   /* --- */

   $out = array();
   $this->root->postback_render($out);

   
   $this->root->postview();
  
  $remove = $this->get_do_code('remove_by_parent');

  
  if ($remove)
  {
  	
  	$parents = array();
  	
  	foreach ($remove as $arr)
  	{
  		 $parents[] = $arr['parent'];
  	}
  	
  	$response['clear'] = $parents;
  	
    
  }

  if (!empty($out))
   $response['load'] = $out;
   
   

   $data = $this->getScriptData();
   if ($data) $response['s'] = $data;
   
   
 

   
   echo $this->i18n->replace_json(json_encode($response));
   echo '[;--DEBUGINFO--:]';
   echo $this->debug_text;
   
  } else {
   
   $contenuto = ob_get_contents();	
 	 ob_end_clean();
 	 
 	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: step1');
 	 
 	
 	 $this->root = $this->buildView($contenuto);
 	 

 	 
 	 echo '<!DOCTYPE html>';

 	 
 	 $loader_r = array_reverse($this->loaders);
 	 
 	 foreach ($loader_r as $loader)
 	  if (!$loader())break;
   
   
    	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: step2');
   $this->_set_loading(true);
 	 $this->root->onload();
 	 $this->_set_loading(false);
 	 
 	 
 	 $this->parse_load_checklist('onload');
 	 
 	  $this->_set_loading(true);   
 	 $this->root->preview();
 	 $this->execute_batch_queries();
 	  $this->_set_loading(false);
 	     
 	 $this->parse_load_checklist('onload');
 	 
 	 
 	 $this->root->render_handlers();
 	  	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: step3');
 	 
 	 
 	 
 	 
 	 ob_start();
 	 $this->root->render();
 	  	  	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: step3.1');
   $this->root->postview();
 	  	  	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: step3.2');
 	 $this->root->finalize();
 	 
 	  	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: step4');
 	 
 	 $data = ob_get_contents();
 	 ob_end_clean();
 	 
 	 echo $this->i18n->replace($data);
   echo $this->debug_text; 
 	}
 	 	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: step5');
 	$this->write();
 	 	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: step6');
 	
 	foreach ($this->_marked as $obj)
 	{
 		
 		if (!$obj->alive())
 		 $obj->send_dispose();
 		
 	}
 	  	 if (is_callable('_put_ptempo'))
 	  _put_ptempo('jyingo: step7');
 	 
 }

 	function register_query($object, $identifier, $query, $params)
 	{
 		
 		if (!isset($this->batch_queries[$identifier]))
 		 $this->batch_queries[$identifier] = array("items" => array(), "query" => $query);
 		
 		$this->batch_queries[$identifier]['items'][] = array("object" => $object, "params" => $params);
 		
 		
 	}
 
 function execute_batch_queries()
 {
 	 global $db;
 	 foreach ($this->batch_queries as $identifier => $data)
 	 {
 	 	 
 	 	 $query = $data['query'];
 	   $items = $data['items'];
 	   
 	   $replace_part = array();
 	   $result_part = array();
 	   $replace_in = array();
 	   $replace_in_key = NULL;
 	   
 	   $fields_check = array();
 	   
 	   foreach ($items as $dd)
 	   {
 	   	 
 	   	 $obj = $dd['object'];
 	   	 $item = $dd['params'];
 	   	 
 	   	 foreach ($item as $key => $value)
 	   	 {
 	   	 	 
 	   	  	 if (count($item) == 1)
 	   	  	 {
 	   	  	 	  if (!isset($fields_check[$key]))
 	   	  	 	   $fields_check[$key] = array();
 	   	  	 	
 	   	  	 	  if (!in_array($value, $fields_check[$key]))
 	   	  	 	  {
 	   	  	  	 $replace_in[] = "'".$db->escape($value)."'";
 	   	  	  	 $replace_in_key = $key;
 	   	  	  	 $fields_check[$key][] = $value;
 	   	  	  	}
 	   	  	 }
 	   	  	 
 	   	  	 if (count($item) == 2)
 	   	  	 {
 	   	  	    // ..	
 	   	  	 } 
 	   	 }
 	   	 
 	   	 $str = md5(implode(':', array_values($item)));
 	   	 
 	   	 if (isset($result_part[$str]))
 	   	  $result_part[$str][] = $obj;
 	   	 else
 	   	  $result_part[$str] = array($obj);
 	    	
 	   }
 	   
 	   if ($replace_in_key)
 	   {
 	     
 	     $querypart = 	$replace_in_key .' IN ('.implode(',', $replace_in).') ';
 	   	 $replace_part[] = $querypart;
 	   }
 	   
 	   $replace_string = implode(' AND ', $replace_part);
 	   $query = str_replace('$1', $replace_string, $query);
 	   
 	   
 	   $results = $db->get($query);
 	   
 	   $fields_check = array_keys($fields_check);
 	   
 	   while ($results->cont())
 	   {
 	   	  
 	   	  $signature = array();
 	   	  
 	   	 
 	   	  
 	   	  foreach ($fields_check as $field)
 	   	   $signature[] = $results->$field;
 	   	  
 	   	  
 	   	  $signature = md5(implode(':', $signature));
 	    	
 	  
 	    	if (isset($result_part[$signature]))
 	    	{
 	    		$s = $result_part[$signature];
 	    		foreach ($s as $o)
 	    		 $o->queried($identifier, $results);
 	    		 
 	    	}
 	   	
 	   }
 	   
 	   
 	 }
 } 
 
 function _set_loading($v)
 {
   $this->_event_loading = $v;
 }
 
 function push_loading()
 {
   $this->_loading_stack[] = $this->_event_loading;
   $this->_event_loading = true;
 }
 
 function pop_loading()
 {
   $this->_event_loading = array_pop($this->_loading_stack);
 }
 
 function parse_load_checklist($function)
 {
   
   
   do
   {
   	
     $cnt = 0;
     
     foreach ($this->_loading_checklist as $instance => $object)
     {
       
       call_user_func(array($object, $function));
       unset($this->_loading_checklist[$instance]);
       
       $cnt++;
       
     }	
     	
   	
   } while ($cnt);
 	
 }
 
 function debug($text)
 {
 	if (isset($_GET['debug']))
 	 $this->debug_text .= $text.'<br />';
 }
 
 function debug_tree($obj, $lvl = 0)
 {
  
   	
   	$out = array();
   	foreach ($obj->get_children() as $child)
   	{
   		
   		$p = array("name" => $child->get_name(), "class" => get_class($child), "instance" => $child->get_instance(), "children" => $this->debug_tree($child, $lvl+1));
   		$out[] = $p;
   	}
   	
    if ($lvl == 0)
    {
    	print_r($out);
    } else {
    	return $out;
    }
   	
 	
 }
 
 public function unloader($callback)
 {
   $this->write_callbacks[] = $callback;	
 }
 
 public function loader($callback)
 {
 	 $this->loaders[] = $callback;
 }
 
 private function parseViewArray($array, $parent)
 {
 	 
 
 	 $text_data = NULL;
 	 $is_closing = FALSE;	 

 	 $tag_chiusura = NULL;
 	 $arr_chiusura = array();
 	 
 	 for ($i = 0; $i < count($array); $i++)
 	 {
 	 	 
 	 	 $key = $i % 3;

 	 	 
 	 	 switch ($key)
 	 	 {
 	 	 	
 	 	  case 0:
 	 	   $text_data .= $array[$i];
 	 	  break;	
 	 	 	
 	 	 	case 1:
 	 	 	 $is_closing = ($array[$i] == '/');
 	 	 	break;
 	 	 	
 	 	 	case 2:
 	 	 	 
 	 	 	 if ($is_closing)
 	 	 	 { 
         
       
 	 	 	   if ($tag_chiusura != NULL)
 	 	 	   {
 	 	 	   	
 	 	 	   	
 	 	 	   	
 	 	 	   	if ($text_data)
 	 	 	   	{
 	 	 	   		 
 	 	 	   		 $parent->add_child( new jyingo_viewparser(false, $text_data) );
 	 	 	   		 $text_data = NULL;
 	 	 	   	}
 	 	 	   	
 	 	 	   	$parent = $parent->get_parent();
	 	   	
 	 	 	    array_pop($arr_chiusura);
 	 	 	    $tag_chiusura = array_pop($arr_chiusura);
 	 	 	   	$arr_chiusura[] = $tag_chiusura;
 	 	 	   	
 	 	 	   } else {
 	 	 	     $text_data .= '</'.$array[$i].'>';	
 	 	 	   }
 	 	 	 
 	 	   } else {
 	 	   	
 	 	 	   if ($text_data)
 	 	 	   {
 	 	 	   		 
 	 	 	   		 $parent->add_child( new jyingo_viewparser(false, $text_data) );
 	 	 	   		 $text_data = NULL;
 	 	 	   }

 	 	   	
 	 	   	 if (substr($array[$i], strlen($array[$i])-1, 1) == '/')
 	 	   	 {
 	 	   	   // tag si apre e chiude
 	 	 	     $parent->add_child( new jyingo_viewparser(true, $array[$i]) );

 	 	   	   	
 	 	   	 } else {
 	 	   	   
 	 	   	   // tag aperto	
 	 	   	   $obj = new jyingo_viewparser(true, $array[$i]);
 	 	   	 	 $parent->add_child( $obj );
 	 	   	 	 $parent = $obj;
	 	 
 	 	   	 	 $arr_chiusura[] = $obj->getTagName();
 	 	   	 	 $tag_chiusura = $obj->getTagName();
 	 	   	 	
 	 	   	 	 
 	 	   	 }
 	 	 	
 	 	 	 
 	 	   }
 	 	   
 	 	 	break;
 	 	 	
 	 	 	
 	 	 }
 	 }
 	 
 	 if ($text_data)
 	 {  		 
 	 	 $parent->add_child( new jyingo_viewparser(false, $text_data) );
 	 	 $text_data = NULL;
 	 }

 	 

 	 return $parent;
 	 
 }
 
 private function timelap($tag = '')
 {
 	
 	list($usec, $sec) = explode(" ",microtime()); 
  $now =  ((float)$usec + (float)$sec); 
  
  if ($this->oldtimelap)
  {
  	 
  	 echo '['.$tag.'] took '.number_format($now-$this->oldtimelap, 2).'s'."\r\n";
  	 
  }
  
  $this->oldtimelap = $now;
  
 	 
 }
 
 private function parseView($contenuto, $parent = NULL, $template = 'default')
 { 
 	  
 	  
 	  if (strpos($contenuto, '<!-- template-parse: false -->') !== FALSE)
 	  {

	 	   if ($parent === NULL)
	 	    $parent = new jyingo_viewparser(false);

 	     $parent->add_child(new jyingo_viewparser(false, $this->minify_html($contenuto)));
 	     
 	     
 	  	 return $parent;
 	  } 
 	  
 	  
 	  $content = $contenuto;
 	  if ($template != 'default')
 	  {

 	  	list($x, $content) = explode('<!-- template: '.$template.' -->', $content, 2);

 	  }
 	  
 	  list($content, $x) = explode('<!-- template:', $content, 2);
 	  
 	  $content = str_replace(array("\r", "\n"), "", $content);
 	  
 	  if ($parent === NULL)
 	   $parent = new jyingo_viewparser(false);
 	
  	$tree = preg_split("~<(/?)([^>]*)>~", $content, -1, PREG_SPLIT_DELIM_CAPTURE);
    $result = $this->parseViewArray($tree, $parent);
    
    
    
    if ($result === NULL)
     return false;
     
    return $result;   
  	
 }
 
 public function entities($string)
 {
  
  return $string;	
 	
 }
 
 function get_js()
 {
 	$p = @filemtime($this->CACHE_DIR.'cache.js');
 	$do_cache = false;
 	
 	
 	
 	foreach ($this->jscache as $file)
 	{
 		if (filemtime($file) > $p)
 		{
       $do_cache = true;
    	 break;		
 		}
 	}
 	
 	$content = '';
 	$packable_content = '';
 	$encrypted = false;
 	
 	if ($do_cache)
 	{
 		
 		if (!file_exists($this->CACHE_DIR.'compiler/'))
 		 mkdir($this->CACHE_DIR.'compiler/', 0777);
 		
 		$index = 0;
 		foreach ($this->jscache as $file)
 		{
 			$data = file_get_contents($file);
 			$content .= $data;
 			
 			if (stripos($file,'module.') !== FALSE ||
 			    stripos($file,'jyingo.') !== FALSE ||
 			    stripos($file,'page.') !== FALSE)
 			 {
 			 	  $packable_content .= $data;
 			 }
 			 else
 			 {
 			 	  if ($packable_content)
 			 	  {
 			 	  	
 			   		 file_put_contents($this->CACHE_DIR.'compiler/'.$index.'.dat', '!++'.$packable_content);
 			   		 $packable_content = '';
 						 $index++;
 			 	  }
 			    file_put_contents($this->CACHE_DIR.'compiler/'.$index.'.dat', '!--'.$data);
 			    $index++;
 			 }
 		}

 		if ($packable_content)
 	  { 	
 		 file_put_contents($this->CACHE_DIR.'compiler/'.$index.'.dat', '!++'.$packable_content);
 		 $packable_content = '';
 		}
 		
 	  file_put_contents($this->CACHE_DIR.'compiler/'.$index.'.dat', '!--++!');
 	  file_put_contents($this->CACHE_DIR.'cache.js', $content);
 	}
 	
 	return $this->CACHE_DIR.'cache.js'; 	
 }
 
 function get_css($cache_writable)
 {
 	
 	if ($cache_writable)
 	{
 		
	 	$p = @filemtime($this->CACHE_DIR.'cache.css');
	 	$do_cache = false;
	 	
	 	foreach ($this->csscache as $file)
	 	{
	 		if (filemtime($file) > $p)
	 		{
	       $do_cache = true;
	    	 break;		
	 		}
	 	}
	 	
	 	$content = '';
	 	
	 	if ($do_cache)
	 	{
	 		
	 		
		  if (is_callable('_put_ptempo'))
		 	 _put_ptempo('jyingo: writing cache');
	 		
	 		foreach ($this->csscache as $file)
	 		{
	 		 $css = file_get_contents($file);
	 		 if (in_array($file, $this->scopedcsscache))
	 		 {
	 		 	 
	 		 	 $classname = '.j-'.substr(md5(str_replace('.','_',str_replace('.css','',array_pop(split('/', $file))))),0,8);
	 		 	 
				 $regex = array(
				 "`^([\t\s]+)`ism"=>'',
				 "`([:;}{]{1})([\t\s]+)(\S)`ism"=>'$1$3',
				 "`(\S)([\t\s]+)([:;}{]{1})`ism"=>'$1$3',
				 "`\/\*(.+?)\*\/`ism"=>"",
				 "`([\n|\A|;]+)\s//(.+?)[\n\r]`ism"=>"$1\n",
				 "`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"=>"\n"
				 );
				
				
				 $clean = preg_replace(array_keys($regex),$regex,str_replace(array("\r","\n")," ", $css));
				 $lines = explode('}', $clean);
				 $open = 0;
				 $out = '';
				 $in_scoped = false;
				 $scoped_class = '';
				 foreach ($lines as $line)
				 {
				 	
				 	 $trim = trim(explode('{', $line, 2)[0]);
				 	 if ($open == 0 && substr($trim,0,7) == '@scoped')
				 	 {
				 	 	
				 	 	 $scoped_class = trim(substr($trim,7));				 	 	
				 	   $in_scoped = true;
				 	   $open = substr_count($line, '{')-2;
				 	   $trim = trim(explode('{', $line, 2)[1]);

				 	   if ($trim) $out .= $classname.$scoped_class.' '.$trim.'}';
				 	    
				 	    
				 	    
				 	   continue; 	
				 	 }
				 	
				 	 $open += substr_count($line, '{');
				 	 $open--;
				 	 
				 	 if ($open == -1 && $in_scoped)
				 	 {
				 	 	 $in_scoped = false;
				 	 	 $open = 0;
				 	 	 continue;
				 	 }
				 	 
				 	 $line = str_replace('@self', $classname, $line);
				 	 
				 	 //if (substr(trim(explode('{', $line, 2)[0]),0,5) == '@self')
				 	 //	 $line = $classname.substr($line,5);
				 	 
				 	 if ($in_scoped && $open == 0 && trim($line[0]) != '@') $out .= $classname.$scoped_class;
				 	 
				 	 
				 	 if ($open >= 0)
				 	 $out .= $line.'}';
				 	 
				 }
				 
				  				 				 
				 $css = preg_replace('/\s+/', ' ', $out);

	 		 }
	 		 $content .= $css;
	 		}
	 		
			$regex = array(
			"`^([\t\s]+)`ism"=>'',
			"`([:;}{]{1})([\t\s]+)(\S)`ism"=>'$1$3',
			"`(\S)([\t\s]+)([:;}{]{1})`ism"=>'$1$3',
			"`\/\*(.+?)\*\/`ism"=>"",
			"`([\n|\A|;]+)\s//(.+?)[\n\r]`ism"=>"$1\n",
			"`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"=>"\n"
			);
			
			
			$output = preg_replace(array_keys($regex),$regex,$content);
	 	  file_put_contents($this->CACHE_DIR.'cache.css', $output);
	 	}
 	
  }
 	
 	return $this->CACHE_DIR.'cache.css';
 }
 
 
 function body()
 {
   return $this->root->search_object('jyingo_pagebody');
 }

 function get_sound_manager()
 {
 	 if ($this->body()->get('smgr') === NULL)
 	 $this->_soundmgr =
 	  $this->body()->load('smgr:jyingo.soundmanager');
 	 
 	 return $this->body()->get('smgr');
 }
 
 public function client_do($id, $do, $params = array(), $__uid = NULL)
 {
 	 
 	 if (!$__uid)
 	  $__uid = $id;
 	
 	 $this->do_code[$do][$__uid] = array($params, $index, $id);
 }
 
 public function script_call()
 {
   $args = func_get_args();
   $function = $args[0];
   $params = array_slice($args, 1);
   
   $this->client_do( $function.mt_rand(1,999), 'call', array ("x" => $function, "p" => $params));
   	
 }
 
 public function script_function_call($method, $params, $owner = NULL)
 {
   $this->client_do( $method.mt_rand(1,999), 'func', array ("f" => $method, "p" => $params, "o" => $owner));
 }
 
 public function script_call_array($method, $params, $owner = NULL)
 {

   $this->client_do( $method.mt_rand(1,999), 'call', array ("x" => $method, "p" => $params, "owner" => $owner));
   	
 }
 
 public function event($event, $data)
 {
   $this->script_call_array('jyingo._customcall', array($event, $data), 'jyingo');  	
 }
 
 public function script($code, $id = -1)
 {
 	if ($id == -1)
 	 $this->scriptdata[] = $code.';';
 	else
 	 $this->scriptdata[$id] = $code.';';
 }
 
 public function get_clsid($data)
 {
 	 
 	 $this->num_of_clsid++;
 	 $p = md5($data.mt_rand(1,99999).mt_rand(1,99999).';'.$this->num_of_clsid);
 	 
 	 return 'e'.substr($p,0,4).'-'. 
 	        substr($p,4,4).'-'.
 	        substr($p,8,4).'-'.
 	        substr($p,12,4);
 	 
 	 
 	 
 }
 
 private function buildView($contenuto, $parent = NULL, $template = 'default')
 {
 	 
 	 
 	 $res = $this->parseView($contenuto, NULL, $template);
 	 
 	 if ($parent == NULL)
 	 	$parent = new jyingo_control();
   
   if ($this->buildViewArray($res, $parent) === NULL)
   {
     $this->error('buildView exception');	
   }
 	  	 
 	 return $parent; 	 
 
 }
 
 private function minify_html($buffer) {

    $search = array(
        '/\>[^\S ]+/s',
        '/[^\S ]+\</s',
        '/(\s)+/s',
        '/<!--(.|\s)*?-->/'
    );

    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
 }
 
 private function buildViewArray($contenuto, $offset)
 {
 	
 	 foreach ($contenuto->get_children() as $child)
 	 {
 	 	
 	    $noparams = new jyingo_params;
 	    
 	    if ($child->isTag())
 	    {

 
        if (isset($this->active_tags[$child->getTagName()]))
        {
            
            $obj = new $this->active_tags[$child->getTagName()]($noparams);
            $obj->set_property_array($child->getProperties());
            
            $offset->add_child($obj);
 
                    	
        } else {
        	  
        	 $obj = new jyingo_render($noparams);
        	 $obj->set_property_array($child->getProperties(), true);
        	 $obj->set_tagname($child->getTagName());
        	 
        	 $offset->add_child($obj);

        }

 	      if ($this->buildViewArray($child, $obj) === NULL)
 	    	  return false; 	   
 	    	
 	    } else {
 	    	
 	    	$obj = new jyingo_literalcontrol($noparams);
 	    	$obj->set_value($child->getValue());
 	    	
 	    	$offset->add_child($obj);
 	    	

 	    }
 	    
 	    
 	 }
 	  
 	 return true;
 }

 
 
}

new jyingo();

?>
