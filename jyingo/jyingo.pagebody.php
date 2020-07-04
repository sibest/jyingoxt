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

 class jyingo_pagebody extends jyingo_control {
 	 
 	 
 	 
 	 public $active_tag = 'body';
   private $title = NULL;
   
   function __construct($params)
   {
    global $env;
    parent::__construct($params);
   } 
 	 
 	 function render()
 	 {
     
      global $env, $_LOCALE;
      echo '<body>';
      
      
      echo '<script>'."\r\n";
      echo '<!--'."\r\n";
      echo 'if (typeof viewport_resize !== \'undefined\') viewport_resize(); document.write(\'<form name="form1" onsubmit="return false">\');'."\r\n";
      echo '--></script>';
      
      ob_start();
      parent::render();
      
      $html = ob_get_contents();
      ob_end_clean();
      
      $code = $env->i18n->replace($html);
      $code = str_replace('<html_script>',"<script><!--\r\n", $code);
      $code = str_replace('</html_script>',"\r\n--></script>", $code);
      ?><?=$code?><?
      
      echo '<script>'."\r\n";
      echo '<!--'."\r\n";
      echo 'document.write(\'</form>\');'."\r\n"; 
           
      echo '--></script>';
      
      echo '<div id="fb-root"></div>';  
      
      echo '<script src="//connect.facebook.net/'.$_LOCALE.'/all.js"></script>';
      echo '<script src="//www.googleadservices.com/pagead/conversion_async.js"></script>';
 
	    echo '<script>'."\r\n";
             echo '<!--'."\r\n";
             		  echo '(function() {
var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement(\'script\');
    fbds.async = true;
    fbds.src = \'//connect.facebook.net/en_US/fbds.js\';
    var s = document.getElementsByTagName(\'script\')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
})();';
 	 	  echo $env->i18n->replace_json('jyingo.init(\''.$env->getInstanceId().'\');');    
	    echo $env->i18n->replace_json($env->getScriptData());
	      
	
	    echo "\r\n".'-->'."\r\n";
	    echo '</script>'."\r\n";
      echo '</body>';
 	 }
 	 
 }
?>