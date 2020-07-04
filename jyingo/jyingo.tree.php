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
 
 class jyingo_tree {
 	
  protected $parent = NULL;
  protected $children = array();
  protected $counter = 0;
  private $disposed = false;
  private $___uid;
  
  function __construct()
  {
  	 global $env;
  	 $this->___uid = $env->get_clsid(mt_rand(1,9999));
  }
  
  function get_unique_identifier()
  {
  	return $this->___uid;
  }
  
  function retain()
  {
  	$this->counter++;
  }
  
  function release()
  {
  	$this->counter--;
  }
  
  function free()
  {
    foreach ($this->children as $child)
     $child->free();
    
    $this->parent = NULL;
    $this->children = NULL;	
  }
  
  function alive()
  {
  	return $this->counter > 0;
  }

  function add_child($child, $after = NULL)
  {
  	
  	if ($after != NULL && in_array($after, $this->children))
  	{

  		 array_splice($this->children, array_search($after, $this->children)+1, 0, array($child));

  		 
  	} else {
  	 $this->children[] = $child;
  	}
  	
  	$child->set_parent($this);
  	$child->retain();
  }
  
  function drop($dispose = false)
  {
  	
  	if (!$this->parent)
  	 return;

  	if ($dispose)
  	{
  	 $this->send_dispose();
  	 $this->remove();
    }	else {
	   $this->parent->remove_child($this);
	   $this->release();
    }
  	
  	$this->parent = NULL;
  		  	
  }
  
  
  function insert_before($child, $before = NULL)
  {
  	 
  	if ($before != NULL && in_array($before, $this->children))
  	{
  		 array_splice($this->children, array_search($before, $this->children), 0, array($child));
  	} else {
  	   array_unshift($this->children, $child);
  	} 
  	
  	$child->set_parent($this); 	 
  	$child->retain();
  }
  
  function clear($params = NULL)
  { 
  	foreach ($this->children as $child)
  	{
  	  $child->remove($params);	
  	  $child->send_dispose();
  	}
  	
  	$this->children = array();
  }
  
  function dispose()
  {
  	
  }
  
  function send_dispose()
  {
  	foreach ($this->children as $child)
  	  $child->send_dispose();

  	if ($this->disposed == false)
  	{
  		$this->dispose();
  		$this->disposed = true;
  	}
  }
  
  function remove_child($child)
  {
  	global $env;
  	$search = array_search ($child, $this->children);

  	if ($search === FALSE)
  	 return;
  	
  	array_splice($this->children, $search, 1);

  }
  
  function search_object($typeof)
  {
  	foreach($this->children as $n => $v)
  	{
  		
  		if ($v instanceof $typeof)
  		 return $v;
  		
  	}
  	
  	foreach($this->children as $n => $v)
  	{
  		
  		$obj = $v->search_object($typeof);
  		if ($obj)
  		 return $obj; 
  		
  	}  	
  	  	
  }
  
  function get_children($typeof = NULL)
  {
  	
  	 
  	if ($typeof  == NULL)
  	 return $this->children;
  	 
  	
  	$arr = array();
  	foreach($this->children as $n => $v)
  	{
  		
  		if ($v instanceof $typeof)
  		 $arr[$n] = $v;
  		
  	}
  	
  	return $arr;
  	 
  }

  function get_children_ordered($typeof = NULL)
  {
  	
  	 
  	if ($typeof  == NULL)
  	 return $this->children;
  	 
  	
  	$arr = array();
  	foreach($this->children as $n => $v)
  	{
  		
  		if ($v instanceof $typeof)
  		 $arr[] = $v;
  		
  	}
  	
  	return $arr;
  	 
  }

  
  function count()
  {
  	return count(array_keys($this->children));
  }
  
  function swap()
  {

    if ($this->parent)
     $this->parent->remove_child($this);
     
      	
  }
  
  function remove($params)
  {
  	
    $this->release();
     
    foreach ($this->children as $child)
    {
      $child->remove($params);
		  if ($params['dispose']) $child->send_dispose();
		}
    if ($this->parent)
     $this->parent->remove_child($this);
     
  	
  }	
  
  function set_parent($parent)
  {
  	 $this->parent = $parent;
  }
 	
  function get_parent()
  {
  	 return $this->parent;
  }
  
  function has_parent()
  {
  	return ($this->parent ? true : false);
  } 	
 	
 }

?>