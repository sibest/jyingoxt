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
 
class jyingo_viewparser extends jyingo_tree {
	
	private $tagname;
	private $tagdata = array();
	private $value;
	private $is_tag;
	
	function __construct($is_tag = true, $line = '')
	{
		
		if ($is_tag)
		{
			
		 $out = array();
			
		 $parts = explode(' ', $line);
		 $this->tagname = strtolower($parts[0]);
	 
		 $this->is_tag = true;
		 
		 preg_match_all ('/([^\=]*)\=\"([^\"]*)\"/', substr($line, strlen($this->tagname)+1), $out, PREG_SET_ORDER);

  	 for ($p = 0; $p < count($out); $p++)
     {
    	$key = strtolower(trim($out[$p][1]));
    	$valore = $out[$p][2];		 
	   
	    $this->tagdata[strtolower($key)] = $valore;
	     
	     
	   }	 
 		} else {
			
		
		 $this->is_tag = false;
		 $this->value = $line;
			
		}
	}
	
	function getProperties()
	{
		return $this->tagdata;
	}
	
	function getValue()
	{
		 return $this->value;
	}
	
	function isTag()
	{
		 return $this->is_tag;
	}
	
	function getTagName()
	{
		 return $this->tagname;
	}
	

}
?>