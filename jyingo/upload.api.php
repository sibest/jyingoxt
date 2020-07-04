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
 define ('BASEPATH','');
 define('UPLOAD_DATA_DIR', BASEPATH.'jdata/uploadcache/');

 
  
	 if (isset($_SERVER['HTTP_X_REAL_IP']))
	 $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];
if (isset($_SERVER['HTTP_CF_CONNECTING_IP']))
 $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
 
 if (isset($_SERVER['HTTP_X_REAL_IP']))
  $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];

 function jyingo_serialize($data) {
 
 	if (is_callable('igbinary_serialize'))
 	 return igbinary_serialize($data);
 	else
 	 return serialize($data);
 	
 }

 
 function jyingo_unserialize($data) {
 	
 	if (is_callable('igbinary_unserialize'))
 	 return igbinary_unserialize($data);
 	else
 	 return unserialize($data);
 } 

 $handle = $_GET['handle'];
 
 ini_set('upload_max_filesize','200M');
 ini_set('post_max_size','200M');
 ini_set('memory_limit','200M');
 ini_set('max_execution_time',0);

 if (!file_exists(UPLOAD_DATA_DIR.$handle.".dat"))
 {
 	echo 'a1';
  exit;
 }

 $p = file_get_contents(UPLOAD_DATA_DIR.$handle.".dat");
 $arr = jyingo_unserialize($p);

 if ($arr["ip_check"] != $_SERVER['REMOTE_ADDR'])
 {
 	echo 'a2';
  exit;
 }

 if (time()-$arr["date"] > 86400)
 {
 echo 'a3';	
  exit;
 }

 function upload_error($i)
 { 	
 	global $arr, $handle;
  $arr["upload_result"] = $i;
  
  file_put_contents(UPLOAD_DATA_DIR.$handle.".dat", jyingo_serialize($arr));
  echo ' ';
  exit;
 }
 
 function upload_ok($fl)
 {
 	 global $arr, $handle;
 
 	 
 	 $list = @jyingo_unserialize(file_get_contents(UPLOAD_DATA_DIR.$handle.".dat"));
 	 if (!is_array($list))
 	  $list = $arr;
 	 
 	 if (!isset($list["upload_result"]))
	 $list["upload_result"] = 1;
   $tmpname = md5(mt_rand(1,99999999999999).time());
 	 
 	 move_uploaded_file($fl['tmp_name'],UPLOAD_DATA_DIR.$tmpname.".upload");
   $item["upload_size"] = $fl['size'];
   $item["upload_realfile"] = $fl['name'];
   $item["upload_tmpfile"] = UPLOAD_DATA_DIR.$tmpname.".upload";

   if (!isset($list['items'])) $list['items'] = array();
   $list['items'][] = $item;
   file_put_contents(UPLOAD_DATA_DIR.$handle.".dat", jyingo_serialize($list)); 	 

 }

  if (isset($_GET['do']))
  {
  	$fl = $_FILES['files'];   
function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}
$filelist = reArrayFiles($fl);
foreach ($filelist as $fl)
{

  	if (!$fl['tmp_name'])
  	{
  		upload_error(-1);
  		
  		?>
  		jyingo_upload_error(-1);
  		<?
  	}
  	else
  	{
  	
  		
  		if (isset($arr["max_upload"]) && $arr["max_upload"] != 0 && $arr["max_upload"]*1024 < $fl['size'])
  			upload_error(-2);
  		elseif ($arr["upload_filter"] == 0)
  		{
  			upload_ok($fl);		
  		}
  		elseif ($arr["upload_filter"] == 1)
  	  {
  			$s = getimagesize($fl['tmp_name']);  			
  			if (!$s)
         upload_error(-3);			 
  			else
  			{
  				
  				$extension = explode('.', $fl['name']);
  				$extension = strtolower($extension[count($extension)-1]);
  			  if (!in_array($extension,array('png','jpg','jpeg','gif')))
  			  {
  			  	upload_error(-3);
  			  } else {
  				
  				
  				list($width, $height, $type, $attr) = $s;
  				if ($type != 2 && $type != 3 && $type!=1)
   			    upload_error(-3);						 	
  				else
  				  upload_ok($fl);		 	
  				  
  				}
  			}
  				 
  		}  				
  				
  	}
  }
  
   echo ' ';
   exit;
}
?>
<html>
<head>
	<link href="/jdata/cache/cache.css?<?=filemtime(BASEPATH.'jdata/cache/cache.css')?>" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="extra/FileReference_List.js?new9"></script>
	
	<style type="text/css">
		
		body { margin:0px; }
		body.upload { background: #fff; }
		div#div_swfobj { position:relative; z-index:9999; }
		input.file { 	width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: -1;
 }
		div#container { position:absolute; top:0; left:0; width:100%; height:100%; }
		a#link { white-space:nowrap; position:absolute;top:0px; left:0px; z-index:2; }
		
  		 #jyingo_upload_container a#link { font-family:Arial,Helvetica; display:block; padding:6px 9px; border-radius:4px; background:#f0f0f0; color:#333; text-decoration:none; }
		#jyingo_upload_container a#link:hover { color:#E52C58; background:#e0e0e0; }
		
	</style>
	<script type="text/javascript">
		var fileRef = new FileReferenceList();
   	var lastBytesSent = 0;
		
		
		function displayErrorMessageUpload()
		{
			alert('Attendi il caricamento');
		}
		
		var listener = new Object();
    fileRef.addListener(listener);

    listener.onSelect = function(lf) {

    	
    	
    	 if (lf.fileList.length < 1)
    	 {
    	 	 cb()._selected=false;
    	 	 return; 
    	 }
    	 cb()._selected=true;
    	 var fl = lf.fileList[0];
    	 
    	 cb()._filesize = fl.size;
    	 cb()._filename = fl.name;
			 cb()._filecount = lf.fileList.length;

       if (cb()._send_after)
       {


         setTimeout(start_upload, 300);
      
       }

    	 
    }
    
function progressHandlingFunction(e){
    if(e.lengthComputable){
    	
    	  
    		 cb()._bytes_sent = e.loaded;
    	   cb()._bytes_total =e.total;
    	   cb()._percent = ((e.loaded/e.total)*100).toFixed(1);
				 cb()._filesize =e.total;
    	 //	 cb()._filename = file.name;    	  	 
    	  cb().upload_progress();
    	  	 
    }
}
    
    function start_upload()
    {


				var lf = $('#file1').get(0);
				
				var formData = new FormData();
				
				for (var i = 0; i < lf.files.length; i++)
				{
					var fl = lf.files[i];
					formData.append('files[]', fl, fl.name);
				}
				
				    $.ajax({
				        url: 'upload.api.php?handle=<?=$handle?>&do=upload',  //Server script to process data
				        type: 'POST',
				        xhr: function() {  // Custom XMLHttpRequest
				            var myXhr = $.ajaxSettings.xhr();
				            if(myXhr.upload){ // Check if upload property exists
				                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
				            }
				            return myXhr;
				        },
				        //Ajax events
				        beforeSend: function() {
				        	cb().upload_started();
				        },
				        success: function() {
				        	send_complete_timed();
				        },
				        error: function() { 
				        	jyingo_upload_error(-1);
				        },
				        // Form data
				        data: formData,
				        //Options to tell jQuery not to process data or worry about content-type.
				        cache: false,
				        contentType: false,
				        processData: false
				    });

				
    	  lastBytesSent = 0;
    	/*  var fl = lf.fileList[0];
    	  
    	
    		var FileListener = new Object();
    		FileListener.serverfile = '../upload.api.php?handle=<?=$handle?>&do=upload';
    	  FileListener.onError = function(file, errorString) {
    	  	 jyingo_upload_error(-1);
    	  	
    	  }
    	  FileListener.onOpen = function(file) {
    	     cb().upload_started();
    	  }
    	  
    	  FileListener.onProgress = function(file, bytesLoaded, bytesTotal) {
    	  	 cb()._bytes_sent = bytesLoaded;
    	  	 cb()._bytes_total = bytesTotal;
    	  	 cb()._percent = ((bytesLoaded/bytesTotal)*100).toFixed(1);
					 cb()._filesize = file.size;
    	 		 cb()._filename = file.name;    	  	 
    	  	 cb().upload_progress();
    	  	 
    	  	 
    	  	  
    	  	 lastBytesSent = bytesLoaded;
    	  	 
    	  }
  
    	  fl.addListener(FileListener);
    	 
    	  
    	  try {
    	  fl.upload(FileListener.serverfile);
    	} catch (err)
    	{
    		alert(err);
    	}*/
    
    }
    
    function send_complete()
    { 
    	setTimeout(send_complete_timed, 500);
    }
    
    function send_complete_timed()
    {
      cb()._bytes_sent = lastBytesSent;
    	cb()._percent = ((lastBytesSent/cb()._bytes_total)*100).toFixed(1);
    	cb().upload_end();
      
      do_reset();
      
    }



		function cb()
		{
			
			if (!window.parent.jyingo)
			 return;
			
			return window.parent.jyingo.get(cb_parent_name);
		}
		
		function jyingo_upload_error(error)
		{
		   cb().push_error(error);
		  
 		}
		function upload_ok()
		{
			 cb().upload_end();
		}
		
		var caricato = false;

		
		function getCaricato()
		{
			 return caricato;
		}
		
		var oBJ = 0;
		var timerCheck = 0;
		
		function checkingLoaded()
		{

			 timerCheck = setTimeout(checkLoaded, 50);
		}
		
		function checkLoaded()
		{
			/*
			if (navigator.userAgent.indexOf('MSIE') != -1)
			 oBJ = document.getElementById(dname);
			else
			 oBJ =document.getElementById(fname);
			 
			if (oBJ)
			if (oBJ.isLoaded)
			 if (oBJ.isLoaded() == true)
			 {

			 	
			 	  clearInterval(timerCheck);
			 	  initBrowse();
			 	  caricato = true;
			 }
			 */
			 
			 initBrowse();
			 
		}
		
		function onfilechange(lf)
		{
    	
    	 if (lf.files.length < 1)
    	 {
    	 	 cb()._selected=false;
    	 	 return; 
    	 }
    	 cb()._selected=true;
    	 var fl = lf.files[0];
    	 
    	 cb()._filesize = fl.size;
    	 cb()._filename = fl.name;
			 cb()._filecount = lf.files.length;


       if (cb()._send_after)
       {
         setTimeout(start_upload, 300, lf);
       }			
		}
		
		function do_reset()
		{
			/*
		 caricato = false;
		 checkingLoaded();
		 
		 delete fileRef;
     fileRef = new FileReferenceList();
     fileRef.addListener(listener);
		 dname = 'swf' + Math.round((Math.random() * 1234567890));
		 fname = 'swf' + Math.round((Math.random() * 1234567890));
			
	
		  flash = '<object onmousemove="document.getElementById(\'jyingo_upload_container\').className=\'hover <?=$arr['classname']?>\'" onmouseout="document.getElementById(\'jyingo_upload_container\').className=\'<?=$arr['classname']?>\'" id="' + dname + '" '
			+ 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '
			+ 'codebase="//fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" '
			+ 'width="500" height="500">'
			+ '<param name="swliveconnect" value="true">'
			+ '<param name="wmode" value="transparent">'
			+ '<param name="movie" value="extra/FileReference_List.swf?'+Math.random()+'" />'
			+ '<embed swliveconnect="true" '
			+ 'id="' + fname + '" '
			+ 'src="extra/FileReference_List.swf?'+Math.random()+'" '
			+ 'type="application/x-shockwave-flash" wmode="transparent" '
			+ 'pluginspage="//www.macromedia.com/go/getflashplayer" width="280" height="60" />'
			+ '</object>';			
			
			document.getElementById('div_swfobj').innerHTML = flash;
			fileRef.init('div_swfobj');
			*/
			
		}

		var globalVar1;
		var cb_parent_name = '<?=$arr["instance"]?>';

    function resize()
    {
    	 var l = $('#link');
    	 
    	 var w = l.outerWidth();
    	 if( w <= 0 || !cb())
    	 {
    	  setTimeout(resize, 100);
    	  return;	
    	 }
    	 
    	 
    	 cb().set_container_size(l.outerWidth(), l.outerHeight());
    	 
	  // fileRef.init('div_swfobj');
    }
		
		function dochoosefiles()
		{
			$('#file1').trigger('click'); 	
			return false;
		}
		
		function initBrowse()
		{
			 var arr = cb().get_filetypes();
			 if(arr[0].extension)
			 {
			  $('#file1').attr('accept', arr[0].extension.split(';').join(',').split('*').join(''));
			 }
			 //fileRef.browse(arr);
		}
		
	</script>

	<script src="//code.jquery.com/jquery-1.7.1.min.js" type="text/javascript"></script>

</head>
<body onload="checkingLoaded();" class="upload">
		<form method="POST" name="form" action="upload.api.php?handle=<?=$handle?>&do=upload" enctype="multipart/form-data">
	<div id="div_swfobj">
	<script type="text/javascript">
		<!--
		
		/*
			var dname = 'swf' + Math.round((Math.random() * 1234567890));
			var fname = 'swf' + Math.round((Math.random() * 1234567890));
	
			var flash = '<object onmousemove="document.getElementById(\'jyingo_upload_container\').className=\'<?=$arr['classname']?> hover\'" onmouseout="document.getElementById(\'jyingo_upload_container\').className=\'<?=$arr['classname']?>\'" id="' + dname + '" '
			+ 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '
			+ 'codebase="//fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" '
			+ 'width="500" height="500">'
			+ '<param name="swliveconnect" value="true">'
			+ '<param name="wmode" value="transparent">'
			+ '<param name="movie" value="extra/FileReference_List.swf?'+Math.random()+'" />'
			+ '<embed swliveconnect="true" '
			+ 'id="' + fname + '" '
			+ 'src="extra/FileReference_List.swf?'+Math.random()+'" '
			+ 'type="application/x-shockwave-flash" wmode="transparent" '
			+ 'pluginspage="//www.macromedia.com/go/getflashplayer" width="280" height="60" />'
			+ '</object>';
			

		
			document.write(flash);*/
			
		-->
		</script>
		</div>
   
		<div id="jyingo_upload_container" class="<?=$arr['classname']?>">
			<input type="file" name="file1" id="file1" class="file" multiple="multiple" onchange="onfilechange(this);"/>
		  <a href="#" onclick="return dochoosefiles();" id="link"><?=$_GET['text']?></a>
   	</div>
   	
    <script type="text/javascript">
    <!--
    
    
	   resize();
    -->
    </script>
  </form>
</body>

</html>