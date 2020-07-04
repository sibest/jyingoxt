jyingoXT
========

A MVC Event-driven PHP Framework by Andrea Pezzino

Best running on HHVM Facebok HipHop Virtual Machine

Includes:
- jQuery (and plugins)
- jQuery UI
- TweenMax
- BigVideo
- FastEllipsis
- RichText

Folder structure
========

Pages
```
page/page.page_name/page.page_name.php
page/page.page_name/page.page_name.css (optional)
page/page.page_name/page.page_name.js (optional)
```

Modules
```
module/module.module_name/module.module_name.php
module/module.module_name/module.module_name.css (optional)
module/module.module_name/module.module_name.js (optional)
```

Basic usage
========

Basic index.php:
```php
<? include "jyingo/jyingo.php" ?>
<? $env->loader('load_index'); ?>
<?
function load_index() {
  global $env;
  $env->load('page.index', array("title" => "Welcome to JyingoXT"));
  $env->show();
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <php:scriptmanager />
</head>
<body>
	<php:windowmanager />
</body>
</html>
<? $env->end(); ?>
```

Create a page like this:

page/page.index/page.index.php
```php
<?
 class page_index extends jyingo_window {
  
   private $title;
   function __construct($params)
   {
    parent::__construct($params);
    $this->title = $params->title;
   }
    
   function loaded()
   {
    $this->get('title')->text = $this->title;
   }
   
   function change_click($caller)
   {
     $this->get('title')->text = $this->get('text')->text;
   }
 
 }
?>
<php:label name="title" tag="h1" />

<div>
 <php:textbox name="text" placeholder="Change title" />
 <php:linkbutton name="change" text="Change" />
</div>

```
