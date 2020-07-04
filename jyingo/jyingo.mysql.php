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
 
  
 $total_query_time = 0;
 
 function ptempo()
 {
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
 }
 
 function put_query_log($testo)
 {
 	 
 	 if (isset($_GET['logquery']))
 	 {
   

    list($usec, $sec) = explode(" ",microtime()); 
    $time =  ((float)$usec + (float)$sec); 
       
    file_put_contents('logquery.txt',@file_get_contents('logquery.txt')."[".number_format($time, 3,'.','')."] ".$testo."\r\n");
   }
 } 
 
 function fine_load_query()
 {
 	 global $total_query_time;
    put_query_log('Tempo totale query: '.number_format($total_query_time, 5).'s'."\r\n");
    	
 }
 
 if (isset($_GET['logquery']))
 {
 	 register_shutdown_function ('fine_load_query');
 	 @unlink('logquery.txt');
   put_query_log("Test avviato");
   put_query_log(var_export($_SERVER, true));
  
   	
 }
 
 class cls_fetch  implements Serializable {
 
  private  $dbg1_res;
  var $count;
 	private  $dbg2_data;
 	private  $dbg3_parent;
 	private  $dbg4_table;
 	private $dbg2_data_off;
 	private $start;
 	private $ids;
 	
 	private $force_eof;
 	
 	private $_need_wakeup = false;
 	
 	public function serialize()
 	{
    global $env;
    
    if ($this->dbg1_res && !$this->ids)
    {
	    $ids = array();
	    
	    @mysql_data_seek($this->dbg1_res, 0);
	    while ($arr =  @mysql_fetch_assoc($this->dbg1_res))
	    {
	    	$ids[] = $arr['id'];
	    }
    } else {
    	$ids = $this->ids;
    }
    
    $arr = array("table" => $this->dbg4_table, "data" => $this->dbg2_data, "count" => $this->count, "start" => $this->start, "fields" => $this->db5_fields, "ids" => $ids);
    
    return $env->serialize($arr);
 		
 	}
 	
 	function get_table()
 	{
 		 return $this->dbg4_table;
 	}
 	
 	function retain()
 	{
 		 
 		 if ($this->dbg1_res &&  $this->dbg3_parent)
 		   $this->dbg3_parent->retain($this->dbg1_res);
 		 
 	}
 	
 	function release()
 	{
 		
 		 if ($this->dbg1_res &&  $this->dbg3_parent)
 		   $this->dbg3_parent->release($this->dbg1_res);
 	}
 	
 	function getArray()
 	{
 		$arr = array();
 		foreach ($this->dbg2_data as $key => $value)
 		{
 		 	 $arr[$key] = $value;
 		} 		
 		return $arr;
 	}
 	
 	function __get($var)
 	{
 		return $this->dbg2_data_off[$var];
 	}
 	
 	function __set($var, $val)
 	{
 		$this->dbg2_data_off[$var] = $val;
 	}
 	
 	public function unserialize($data)
 	{
 	 	global $env;
 	 	

 	 	
 	 	$arr = $env->unserialize($data);


 	 	
 	 	$this->_need_wakeup = true;
 	 	
 	 	
 	 	$this->start = 0;
 	 	$this->count = $arr['count'];
 	 	$this->dbg4_table = $arr['table'];
    $this->db5_fields = $arr['fields'];
    $this->ids = $arr['ids'];
 	 	$this->dbg2_data = $arr['data'];
 	 	$this->start = $arr['start'];
 	 	
 	 	$this->dbg3_parent = $env->db;
 	 	
 	 	if ($this->count)
 	 	$this->refresh_vars();

 	}
 	
 	function wakeup()
 	{
 		$this->__check_wakeup();
 	}
 
 	
 	function __check_wakeup()
 	{ 
 		 
 
 		 if ($this->_need_wakeup == true)
 		 {
 		   
 		   $this->_need_wakeup = false;

		   $i = 0;
		  	
		   $keys = array();
		   $values = array();
		  	
		   $idkey = '';
 		   	
  		 foreach ($this->dbg2_data as $key => $value)
		 	 {  	
		 			
		       if (is_numeric($key)) continue;
		       
		 			 $i++;
		 			 if ($i == 1) { $idkey = $key; continue; }
		 			 
		 			 $keys[] = $key;
		 			 $values[$key] =  $this->$key;	 
		 	 }

	 		 array_unshift($keys, $idkey);
	 		
	 		 $fieldlist = explode(', ', $this->db5_fields);
	 		 foreach ($fieldlist as $field)
	 		 {
	 		   
	 		   list($tbl, $fld) = explode('.', $field, 2);
	 		   if ($fld == $idkey)
	 		   {
	 		     
	 		     $tblkey = $tbl;
	 		     break;	
	 		   	
	 		   }
	 		   	
	 		 }
	 		

	 		 $this->dbg1_res =  $this->dbg3_parent->query("SELECT ".$this->db5_fields." FROM ".$this->dbg4_table." WHERE $tblkey.$idkey IN (".implode(', ',$this->ids).")");	

 		 	 mysql_data_seek($this->dbg1_res, $this->start);
 
 		 }
 	}
 	
 	function __construct()
 	{
 		$this->dbg2_data_off = array();
 		$this->start=0;
  }
  
  function seek($offset)
  {
  	if (!mysql_data_seek($this->dbg1_res, $offset))
  	{
  		
  	  
  	  
  		
  	  $this->force_eof = TRUE;
  	  return;
  	}
  	
  	$this->force_eof = FALSE;
  	
  	$this->next();
  	
  	$this->start = $offset+1;
  	mysql_data_seek($this->dbg1_res, $offset);
  }

 	function set_info($res ,$parent, $table, $fields)
 	{
 		 $this->dbg1_res = $res;
 		 $this->dbg3_parent = $parent;
 		 $this->dbg4_table = trim(array_shift(explode(',',$table)));
 		 $this->db5_fields = $fields;
 		 
 	}
 	
 	function close()
 	{
     
     if ($this->dbg3_parent->is_pooling())
      return;
     
     if (is_resource($this->dbg1_res))
     {
     	
 		    @mysql_free_result($this->dbg1_res);
		 }		
 	}
 	
 	function cont()
 	{
 		$this->__check_wakeup();
 		
 		if ($this->force_eof)
 		 return 0;
 		
 		if ($this->start == 0)
 		{
 			if ($this->count == 0)
 			 return 0;
 			
 		 $this->start++;
 		 return $this;
 	  }
 	  
 	  return $this->next();
 	}
 	
 	function next()
 	{
 		
 		
 		
 		$arr = $this->dbg3_parent->get_array($this->dbg1_res);
 		if (!$arr)
 		{
 			
 			 $this->dbg2_data = 0;
 			 return 0;
 		}
 		else
 		{ 			 
 		   
 			 $this->dbg2_data = $arr;
 			 $this->refresh_vars();
 		}
 		
 		return $this;
 	}
  
  private function update_arr()
  {
 		 foreach ($this->dbg2_data as $key => $value)
 		 {
 		 	  $this->dbg2_data[$key] = $this->$key;
 		 }
  }
  	
  private function refresh_vars()
 	{
 		 foreach ($this->dbg2_data as $key => $value)
 		 {
 		 	 $this->$key = $value;
 		 }
 	}
 	
 	function reset()
 	{
 		 $this->force_eof = false;
 		 
 		 if ($this->start == 0)
 		  return;
 		  
 		 
 		 
 		 mysql_data_seek($this->dbg1_res, 0);
 		 
 		 $this->start = 0;
 		 $this->next();
 		 
 	}
 	
  function duplicate()
  {
  	$i = 0;
  	
  	$keys = array();
  	$values = array();
  	
  	$idkey = '';
  	
 		foreach ($this->dbg2_data as $key => $value)
 		{  	
 			
       if (is_numeric($key)) continue;
       
 			 $i++;
 			 if ($i == 1) { $idkey = $key; continue; }
 			 
 			 $keys[] = $key;
 			 $values[$key] =  $this->$key;	 
 		}
 		

 		$db = $this->dbg3_parent;
 		
 		$db->insert ( $this->dbg4_table, $values );
 		
 		$id = $db->insertid();

 		
 		$this->refresh_vars();
 		
 		array_unshift($keys, $idkey);
 		
 		return $db->get("SELECT ".implode(",",$keys)." FROM ".$this->dbg4_table." WHERE $idkey='".$id."'");

  }
 	
 	function save()
 	{
 		global $LOG_QUERY_HAPPENS;
 				

 		$this->__check_wakeup();
 		
 		$arr = array();
 		$i = 0;
 		
 		if (!is_array($this->dbg2_data))
 		 return;
 		
 		foreach ($this->dbg2_data as $key => $value)
 		{
 			if (is_numeric($key)) continue;
 			
 			if ($i == 0)
 			$check = $key."='".$this->dbg3_parent->escape($value)."'";
 			else
 			{
 				
 			 
 			  if ($value != $this->$key)
 			    $arr[] = $key."='".$this->dbg3_parent->escape($this->$key)."'";
 		  
 		  
 		  }
 		  $i++;
 		}
 		

 		
 		$this->update_arr();
 		if (!count($arr))
 		 return;
 		 
 		$query = "UPDATE ".$this->dbg4_table." SET ".implode(",", $arr)." WHERE ".$check;

 		
    $this->refresh_vars();
     		
 
 	  $this->dbg3_parent->query($query);
 	  
 	}
 	
 }
 
 class cls_db_mgr {
  
  private $insert_point = NULL;
  private $cache_query = false;
  private $cache = array();
  private $_pool = NULL;
  protected $log_slow_time = 0;
  protected $log_accumulator = -1;
  protected $log_accumulator_queries = 0;
  
  function exec($query)
  {
  	 return $this->query($query); 
  }
  
  public function log_accumulator()
  {
  	if ($this->log_accumulator != -1)
  		echo 'Query count: '.$this->log_accumulator_queries.' total time: '.number_format($this->log_accumulator, 2)."\r\n";
    
    $this->log_accumulator = 0;
    $this->log_accumulator_queries = 0;
  }
  
  public function log_slow($time = 0)
  {
  	$this->log_slow_time = $time;
  }
  
  function enable_query_cache()
  {
  	$this->cache_query = true;
  }
  
  function begin()
  {
  	 $this->query('BEGIN');
  }
  

  
  function autoreleasepool()
  {
  	 if (!is_array($this->_pool))
  	 {
  	   $this->_pool = array();	
  	 }
  	 
  	 $new = array();
  	 
  	 
  	 
  	 foreach ($this->_pool as $resid => $value)
  	 {
  	 	 
  	 	   if ( $this->_pool[$resid][1] - 1 == 0)
  	 	   {
  	 	   	  mysql_free_result($this->_pool[$resid][0]);
  	 	   } else {
  	 	     $new[$resid] = $value;
  	 	   } 
  	 	 
  	 }
  	 
  	 
  	 unset($this->_pool);
  	 $this->_pool = $new;	
  	 
  }
  
  function is_pooling()
  {
  	return is_array($this->_pool);
  }
  
  function pool($object)
  {
  	
  	
  	
  	if (!is_resource($object)  || intval($object) < 2)
  	 return;
  	
  	
  	
  	if (!is_array($this->_pool))
  	 return;
    
  		
		$this->_pool[intval($object)] = array($object, 1);
  }
  
  function retain($object)
  {
  	if (!is_resource($object)   || intval($object) < 2)
  	 return;
  	
  	if (!is_array($this->_pool))
  	 return;
    	
    
  	$this->_pool[intval($object)][1]++;
  	
  }
  
  function release($object)
  {
  	
  	
  	if (!is_resource($object)  || intval($object) < 2)
  	 return;
  	
  	if (!is_array($this->_pool))
  	 return;
    	
  	$this->_pool[intval($object)][1]--;  	
  }
  
  
  
  function commit()
  {
  	 $this->query('COMMIT');
  }
  
  function rollback()
  {
  	 $this->query('ROLLBACK');
  }
  
  function multiple_update($table, $index, $fields, $allvalues, $addslashes = true, $batch_size = 100)
  {
  	 $affected = 0;
  	 
  	 $query = 'UPDATE '.$table.' ';
  	 
  	 $tvalues = array();
  	 foreach ($allvalues as $id => $value)
  	 {
  	    $tvalues[] = array($id, $value);
  	 }
  	 
  	 if (!is_array($fields))
  	  $fields = array($fields);
  	  
  	 
  	 for ($i = 0; $i < count($tvalues); $i += $batch_size)
  	 {
  	 	 
  	 	   $values = array_slice($tvalues, $i, $batch_size);
  
  	 	   $setvalues = array();
  	 	   $idslimit = array();
         for ($l = 0; $l < count($fields); $l++)
          $setvalues[$l] = array(); 
         
         
 	 	     foreach ($values as $object)
  	 	   {  
  	 	   	  $id = $object[0];
  	 	   	  $value = $object[1];
  	 	   	  
  	 	   	  $idslimit[] = $id;
  	 	     	if (is_array($value))
  	 	     	{
  	 	     		
  	 	     		for ($l = 0; $l < count($value); $l++)
  	 	     		 $setvalues[$l][] = ' WHEN '.$id.' THEN \''.($addslashes ? $this->escape($value[$l]) : $value[$l]).'\' ';

  	 	     	} else {
  	 	     		
  	 	     	  $setvalues[0][] = ' WHEN '.$id.' THEN \''.($addslashes ? $this->escape($value) : $value).'\' ';
  	 	     	}
  	 	   }
  	 	   
  	 	   
  	 	   foreach ($fields as $tindex => $field)
  	 	   {
  	 	     $subquery = $query.'SET '.$field.' = CASE '.$index.' '.implode(' ', $setvalues[$tindex]).' END WHERE '.$index.' IN ('.implode(',', $idslimit).')';
           $this->query($subquery);
           
           $affected += $this->affectedrows();
  	 	   	
  	 	   }
  	 	  
  	  	
  	 }
  	 return $affected;
  }
  
  function get($query)
  {
  	 if (isset($this->cache[md5($query)]))
  	 {
  	  $ret = $this->cache[md5($query)];
  	  $ret->reset();
  	  return $ret;
  	 }
  	 
  	 $res = $this->query($query);

  	 $dat = new cls_fetch;
  	 $dat->set_info($res , $this, $this->table_list($res), $this->fields_list($res));
  	 
  	 $dat->count = $this->count($res);
  	 $dat->next();
  	 
  	 if ($this->cache_query)
  	 {
  	  $this->cache[md5($query)] = $dat;
  	 }
  	 return $dat;
  }
  
  function apply_echo_on_insert($point)
  {
  	$this->insert_point = $point;
  }
 
  function fields_list($res)
  {
  	
  	if ($res)
  	{
  		
  		$fields = array();
  		for ($i = 0; $i < $this->num_fields($res); $i++)
  		{
  			
  		 
  		  $fields[] = $this->table_name($res, $i).'.'.$this->field_name($res, $i);
  		 	
  		}
  		
  		$fields_str = implode(', ', $fields);
  		return $fields_str;
  		
  	}
  	
  }
  
  function table_list($res)
  {
  	
  	if ($res)
  	{
  		
  		$tables = array();
  		for ($i = 0; $i < $this->num_fields($res); $i++)
  		{
  			
  		 
  		 if (!in_array($this->table_name($res, $i), $tables))
  		  $tables[] = $this->table_name($res, $i);
  		 	
  		}
  		
  		$table_str = implode(', ', $tables);
  		return $table_str;
  		
  	}
  	
  }
  
  function num_fields($res)
  {
  	return mysql_num_fields($res);
  }
  
  function field_name($res, $i)
  {
  	return mysql_field_name ($res, $i);
  }
  
	function get_old_array($res)
	{
		$ret = new db_handle_ret();
		$arr = mysql_fetch_array($res);
		
		if (!$arr)
		 return false;
		
		foreach ($arr as $key => $value)
			 $ret->$key = $value;
		
		return $ret;
	}
	
 	function delete_id($table, $id)
 	{
 		$query = "DELETE FROM $table WHERE id='$id'";
 		$this->query($query);
 	}	
 	
 	function escape($str)
 	{
 		 return $this->escape_string($str);
 	} 
	
	function batch_insert($table, $keys, $values, $slice = 100, $addslashes = true)
	{
		
		 for ($i = 0; $i < count($values); $i += $slice)
		 {
		 	
		 	
		    $chunk = array_slice($values, $i, $slice);
		   	$this->multiple_insert($table, $keys, $chunk, $addslashes, $true);
		 	
		 }
		 
	}
	
	function multiple_insert($table, $keys, $values, $addslashes = true, $ignore = false, $on_duplicate = false, $on_duplicate_raw_value = false)
	{
	  $query = '';
	  $fields = implode(",", $keys);
	  
	  $vals = array();
	  
	  for ($i = 0; $i < count($values); $i++)
	  {
	  	
	  	 $arr = $values[$i];
	  	 if ($addslashes == true)
	  	  for ($v = 0; $v < count($arr); $v++) $arr[$v] = $this->escape($arr[$v]);
	     
	     $vals[] = '(\''.implode("','", $arr).'\')';	 
	  }
	  
	  if ($ignore == true)
	   $modifier = 'IGNORE';
	  else
	   $modifier = '';
	  
	  $query = 'INSERT '.$modifier.' INTO '.$table.' ('.$fields.') VALUES '.implode(",", $vals);
	  
	  if (is_array($on_duplicate))
	  {
	  	 
	  	 $qs = array();
	  	 foreach ($on_duplicate as $key => $val)
	  	 {
	  	 	if ($on_duplicate_raw_value)
	  	 	 $qs[] = $key.'='.$val;
	  	 	else
	  	   $qs[] = $key.'='.($addslashes ? '\''.$this->escape( $val ) . '\'' : "'".$val."'");
	  	 }
	  	 $query .= ' ON DUPLICATE KEY UPDATE '.implode(',',$qs);
	  	 
	  }
	  $query .=';';
	  
    $this->query($query);
    return $this->affectedrows(); 	
	}
	
  function insert_replace($table, $arr, $addslashes = true)
  {
  	 $query = '';
  	 
  	 
  	 $fields = array();
  	 $values = array();
  	 
  	 foreach ($arr as $key => $val)
  	 {
  	 	  $fields[] = $key;
  	 	  
  	 	  if ($addslashes)
  	 	   $values[] = $this->escape($val);
  	 	  else
  	 	   $values[] = ($val);
  	 }
  	 
  	 $query = 'REPLACE '.$table.' ('.implode(',', $fields).') VALUES (\''.implode("','", $values).'\');';
  	 $this->query($query);
  	 return $this->insertid();
  }	 
  
  function insert($table, $arr, $addslashes = true, $on_duplicate = false, $on_duplicate_raw_value = false)
  {
  	 $query = '';
  	 
  	 
  	 $fields = array();
  	 $values = array();
  	 
  	 foreach ($arr as $key => $val)
  	 {
  	 	  $fields[] = $key;
  	 	  

  	 	  
  	 	  if ($addslashes)
  	 	   $values[] = $this->escape($val);
  	 	  else
  	 	   $values[] = ($val);
  	 }
  	 
  	 $query = 'INSERT IGNORE INTO '.$table.' ('.implode(',', $fields).') VALUES (\''.implode("','", $values).'\')';

	   if (is_array($on_duplicate))
	   {
	  	 
	  	 $qs = array();
	  	 foreach ($on_duplicate as $key => $val)
	  	 {
	  	 	if ($on_duplicate_raw_value)
	  	 	 $qs[] = $key.'='.$val;
	  	 	else
	  	   $qs[] = $key.'='.($addslashes ? '\''.$this->escape( $val ) . '\'' : "'".$val."'");
	  	 }
	  	 $query .= ' ON DUPLICATE KEY UPDATE '.implode(',',$qs);
	  	 
	   }
	   $query .=';';
	   
	   
  	 $this->query($query);
  	 
  	 if ($this->insert_point) { echo $this->insert_point; flush(); }
  	 
  	 return $this->insertid();
  }
  
  function get_count($table, $where = '1=1')
  {
  	 
  	$res = $this->query('SELECT COUNT(*) FROM '.$table.' WHERE '.$where);
  	
  	list($num) = $this->get_row($res);
  	return intval($num);
  }
  	
 }
 
 class db_handle_ret  { 
 	
 	private $data = array();
 	
 	function __get($var)
 	{
 		 return $this->data[$var];
 	}
 	
 	function __set($var, $val)
 	{
 	  $this->data[$var] = $val;	
 	}
 	
 	function __construct() {  
 		
 	}
 	 
 }
 
 class cls_db_mysql extends cls_db_mgr {
    
    protected $conn;
    
    function table_name($res, $id)
    {
    	if ($res)
    	 return mysql_field_table($res, $id);
    	else
    	 return '';
    }
  
  function get_driver()
  {
  	return 'mysql';
  }
  
  function close()
  {
  	mysql_close($this->conn);
  }  
   
 
    function duplicate()
    {
    	$this->mysql_infodata[4] = TRUE;
 
    	$this->connect($this->mysql_infodata[0], 
    		                $this->mysql_infodata[1], 
    		                $this->mysql_infodata[2], 
    		                $this->mysql_infodata[3],
    		                $this->mysql_infodata[4],
    		                $this->mysql_infodata[5],
    		                $this->mysql_infodata[6]
    		                );    	
    } 
 
    function reset_conn()
    {
    	    
         mysql_close($this->conn);
         
    		 $this->connect($this->mysql_infodata[0], 
    		                $this->mysql_infodata[1], 
    		                $this->mysql_infodata[2], 
    		                $this->mysql_infodata[3],
    		                $this->mysql_infodata[4],
    		                $this->mysql_infodata[5],
    		                $this->mysql_infodata[6]
    		                );
    	 
    }
    
    
    function check_conn()
    {
    	 if (!$this->conn || @mysql_ping($this->conn) === FALSE)
    	 {
    	 	 
         @mysql_close($this->conn);
    		 $this->connect($this->mysql_infodata[0], 
    		                $this->mysql_infodata[1], 
    		                $this->mysql_infodata[2], 
    		                $this->mysql_infodata[3],
    		                $this->mysql_infodata[4],
    		                $this->mysql_infodata[5],
    		                $this->mysql_infodata[6]
    		                );
    	 }
    }
    
    function escape_string($str)
    {
     return mysql_real_escape_string($str, $this->conn); 	
    }
    
    function error()
    {
    	return mysql_error($this->conn);
    }
    
    function query($sql = '')
    {
    	 global $total_query_time, $LOGGING_DB_QUERY, $LOG_QUERY_HAPPENS;
 
 

 
 
      if ($this->log_slow_time)
      {
      	$start = microtime(true);
      }
 
    	 $query = str_ireplace("unix_timestamp()",time(),$sql);
    	 $ret = mysql_query($query, $this->conn); 

    	 
    	 $err = mysql_error($this->conn);
    	 if ($err)
    	 {
    	 	 file_put_contents('/var/log/sqlerrors.log', @file_get_contents('/var/log/sqlerrors.log').$query."\r\n".$err."\r\n\r\n");
    	 }
    	 if ($ret != $this->conn)
       $this->pool($ret);
 
       if ($this->log_slow_time) {
       	$past = microtime(true) - $start;
       	if ($this->log_accumulator != -1)
       	{
       		$this->log_accumulator += $past;
       		$this->log_accumulator_queries++;
       	}
         if ($past>$this->log_slow_time)
         {
           echo '['.number_format(	$past, 2).'s] '.$query."\r\n";
         }
       }
       
     	 return $ret;
    }
    
    function get_blank_ret()
    {
    	 return new db_handle_ret();
    }

    function get_rows($table, $matches = array())
    {
      $query = "SELECT * FROM $table ";
      if (count($matches))
      {
      	
      	$set = array();
      	
        foreach ($matches as $key => $match)
         $set[] = " $key = '".$this->escape($match)."' ";
        
        $query .= " WHERE ".implode(" AND ", $set);
        
        
      }	
      
      return $this->get($query);
      
    }
    
    function rows($table, $matches = array())
    {
      $query = "SELECT COUNT(*) FROM $table ";
      if (count($matches))
      {
      	
      	$set = array();
      	
        foreach ($matches as $key => $match)
         $set[] = " $key = '".$this->escape($match)."' ";
        
        $query .= " WHERE ".implode(" AND ", $set);
        
        
      }	
      
      return intval($this->get_value($query));
      
    }
	  function get_value_close($sql)
	  {
		  $res = $this->query($sql);		  
		  $row = $this->get_row($res);
		  
		  if (!$this->is_pooling())
		  mysql_free_result($res);
		  return $row[0];
	  }    
	  function get_value($sql)
	  {
		  $res = $this->query($sql);
		  $row = $this->get_row($res);
		  return $row[0];
	  }
    
    function seek($res, $nr)
    {
    	 return mysql_data_seek($res, $nr);
    }
    
    function insertid()
    {
    	 return mysql_insert_id($this->conn);
    }

    function affectedrows()
	  {
		 return mysql_affected_rows($this->conn);
	  }    
	  
	  function is_connected()
	  {
	  	return $this->conn ? true : false;
	  }
    
    function connect($host, $user, $pass = '', $db = '', $newlink = false, $persistent = false, $compress = FALSE)
    {
       global $_DISABLE_PERSISTENT;
       
       $flags = ($compress ? MYSQL_CLIENT_COMPRESS : 0);
       
       if ($persistent && !$_DISABLE_PERSISTENT)
       {
        $this->conn = @mysql_pconnect($host, $user, $pass, $flags);
       }
       else
       {
    	  $this->conn = @mysql_connect($host, $user, $pass, $newlink, $flags);
    	 } 
    	 $this->mysql_infodata = array ($host, $user, $pass, $db, $newlink, $persistent, $compress);
    	 
  
    	 
    	 if ($db)
    	 mysql_select_db ( $db, $this->conn);

    	 
    }
    
    function get_array($res)
    {
    	if ($res)
    	 $arr = mysql_fetch_array($res);
    	else
    	 return 0;
    	 
    	 return $arr;
    }
    
    function use_db($dbname)
    {
    	 mysql_select_db($dbname, $this->conn);
    }
    
    function get_row($res)
    {
      if (!is_resource($res))
    	{
    		$e = new Exception; 
				var_dump($e->getTraceAsString());
    	} 
    	
    	 $arr = mysql_fetch_row($res);
    	 return $arr;
    }
 
    function count($res)
    {
    	if (!$res)                                                     
    	 return 0;
    	                         
    	 return mysql_num_rows($res);
    }
    
    function affected_rows($res)
    {
    	 return mysql_affected_rows($res);
    }
    
    function apply_echo_on_insert($point)
    {
    	$this->insert_point = $point;
    	
    }
    
    	
 } 
        
 
 if (defined('JYINGO_USE_MYSQLI') == false)
 {
	 if ($this)
	  $this->db = new cls_db_mysql();
	 else
	 {
	   $env = new db_handle_ret();
	   $env->db = new cls_db_mysql();	
	 } 	
 }
 

?>