<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{site_title}</title>
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

<div id="content">
	<div class="header">
		<img src="{template}/images/logo.png" align="left" alt="logo" style="margin: 10px 15px 10px 45px;">
		<h1><a href="{site_http_path}">{site_header_h1}</a></h1>
		<h2>{site_header_h2}</h2> 
	</div><!--"header"-->
	

  <div class="left">
			<div id="menu" valign="center"><a href="{site_http_path}"> ГЛАВНАЯ </a></div>
			<div id="menu" valign="center"><a href="{site_http_path}?p=rules">ПРАВИЛА</a></div>
			<div id="menu" valign="center"><a href="{site_http_path}?p=about">О ФОТОХОСТИНГЕ</a></div>
			<div id="menu" valign="center"><a href="{site_http_path}?p=nopage">Просто страница</a></div>
  </div><!--"left"-->
  <div class="right">
			<fieldset class="fieldsetmain">
    	{error}{content}
			</fieldset>
  </div><!--"right"-->
  <div class="footer">
    <p>Изображений на фотохостинге: {images}; занимают {size}; за сутки загружено: {images24}</p>
    <p>Powered by <a href="http://akina-photohost.org/">Аkina</a> | Дизайн: reflex | Кодинг: TerVel</p>
  </div><!--"footer"-->

</div><!--"content"-->
</body>
</html>