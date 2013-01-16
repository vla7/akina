<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Хостинг картинок Akina</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="{site_http_path}engine.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{template}/style.css" media="screen" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="{site_http_path}js/main.js" type="text/javascript"></script>
</head>
<body>

<div id="wrap">

<div id="header">
<h1><a href="{site_http_path}">Фотохостинг Akina</a></h1>
<h2>Хостинг картинок</h2> 
<div id="limite">Разрешенные форматы: JPG, GIF, PNG; max: {max_size_mb} MB; {max_width}x{max_height} px; {max_quantity} одновременно.<br /></div>

</div>

<div id="menu">
<ul>
<li><a href="{site_http_path}">Главная</a></li>
<li><a href="{site_http_path}?p=rules">Правила</a></li>
</ul>
</div>

<div id="content">
<br/>
    {error}{content}
</div>



</div>

<div id="footer">
Изображений на фотохостинге: {images}; занимают {size}; за сутки загружено: {images24}<br/>
Powered by <a href="http://akina-photohost.org">Akina</a> &copy; 2010-2011</div>

</div>
</body>
</html>