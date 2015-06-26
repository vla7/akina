<?php
define('akina', 'photohost', true);

//загружаем предустановки
if(!include_once('config.php')) die('Can\'t find config.php');

//загружаем функции
if(!include_once('functions.php')) die('Can\'t find functions.php');

//отладка, показываем ошибки PHP
if(isset($debug) and ($debug)){	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); }
//прячем все сообщения об ошибках от народа
else {	error_reporting(E_ALL^E_NOTICE); ini_set('display_errors', 0); }

$site_title=$config['site_title'];

if ($config['site_work']!=true)
	die ("Проводятся сервисные работы. Сервис временно недоступен.");

$parse_main=array();

$view = isset($_GET['v']) ? (boolean)$_GET['v'] : false;
$action = isset($_POST['action']) ? (string)$_POST['action'] : '';

if(!$view && $action=='' && !isset($_GET['p']))
	$parse_main['{content}']=parse_template(get_template('upload'), array());
elseif($action=='upload')
{
	include_once 'engine.php';
	include_once 'upload.php';
	include_once 'view.php';
	$site_title='Изображение(я) загружено(ы) - '.$config['site_title'];

}
elseif($view)
{
	include_once 'view.php';
	$site_title='Просмотр изображения - '.$config['site_title'];
}
elseif(isset($_GET['p']))
{
	preg_match('/\w+/',$_GET['p'],$matches);
	$page=$config['template_path']."/".$matches['0'].".static.tpl";
	if(is_file($page))
		$parse_main['{content}']= file_get_contents($page);
	else
		include_once 'error404.php';
}
else
{
	include_once 'error404.php';
	$site_title='Страница не найдена - '.$config['site_title'];

}

$parse_main['{template}']=$config['template_url'];

$parse_main['{site_title}']=$site_title;
$parse_main['{site_header_h1}']=$config['site_header_h1'];
$parse_main['{site_header_h2}']=$config['site_header_h2'];

$parse_main['{max_height}']=$config['max_height'];
$parse_main['{max_width}']=$config['max_width'];
$parse_main['{max_size_mb}']=$config['max_size_mb'];
$parse_main['{max_quantity}']=ini_get('max_file_uploads');

if ((isset($config['auto_resize'])) and ($config['auto_resize']==1)) $parse_main['{auto_resize}']='checked ';
else $parse_main['{auto_resize}']='';

$parse_main['{width_resize_elements}']='';
$parse_main['{height_resize_elements}']='';
if ((isset($config['width_resize_elements'])) and ($config['width_resize_elements']!='')) $parse_main['{width_resize_elements}']='value="'.$config['width_resize_elements'].'" ';
elseif ((isset($config['height_resize_elements'])) and ($config['height_resize_elements']!='')) $parse_main['{height_resize_elements}']='value="'.$config['height_resize_elements'].'" ';

if ((isset($config['auto_preview'])) and ($config['auto_preview']==1)) $parse_main['{auto_preview}']='checked ';
else $parse_main['{auto_preview}']='';

$parse_main['{width_preview_elements}']='';
$parse_main['{height_preview_elements}']='';
if ((isset($config['width_preview_elements'])) and ($config['width_preview_elements']!='')) $parse_main['{width_preview_elements}']='value="'.$config['width_preview_elements'].'" ';
elseif ((isset($config['height_preview_elements'])) and ($config['height_preview_elements']!='')) $parse_main['{height_preview_elements}']='value="'.$config['height_preview_elements'].'" ';


if((isset($error)) and (is_array($error)))
	$parse_main['{error}']=parse_template (get_template('info'), array("{type}" =>'error',"{title}" =>"Ошибка!","{text}" => implode("<br />", $error)));
else
	$parse_main['{error}']='';
	

$cachefile=$config['cachefile']; 
if ((!file_exists($cachefile)) or (time()-@filemtime($cachefile)>$config['cache_time']))
{ 
	touch($cachefile);//чтобы только один пользователь запускал подсчет 
	list($size, $images_total, $images_h24)=get_dir_size($config['uploaddir']);
	$size = formatfilesize($size);
	file_put_contents( $cachefile, "$images_total|$size|$images_h24"); 
} 
elseif (file_exists($cachefile))
	list($images_total, $size, $images_h24) = explode("|", file_get_contents($cachefile)); 

$parse_main['{size}']=$size; 
$parse_main['{images}']=$images_total; 
$parse_main['{images24}']=$images_h24;
$parse_main['{site_http_path}']=$config['site_url'];

if(!$parse_main['{content}'])
	$parse_main['{content}']='';

echo parse_template(get_template('index'), $parse_main);
?>