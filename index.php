<?php
define('akina', 'photohost', true);

include_once 'config.php';
include_once 'functions.php';

if ($config['site_work']!=true)
	die ("Проводятся сервисные работы. Сервис временно недоступен.");

$parse_main=array();

$view = isset($_GET['v']) ? (boolean)$_GET['v'] : false;
$action = isset($_POST['action']) ? (string)$_POST['action'] : '';

if(!$view && $action=='')
	$parse_main['{content}']=parse_template(get_template('upload'), array());
elseif($action=='upload')
{
	include_once 'engine.php';
	include_once 'upload.php';
	include_once 'view.php';
}
else
	include_once 'view.php';

$parse_main['{max_height}']=$config['max_height'];
$parse_main['{max_width}']=$config['max_width'];
$parse_main['{max_size_mb}']=$config['max_size_mb'];
$parse_main['{max_quantity}']=ini_get('max_file_uploads');

$parse_main['{template}']=$config['template_url'];
if($error)
	$parse_main['{error}']="<div class='errors'>".implode("<br />", $error)."</div>";
else
	$parse_main['{error}']='';
	

$cachefile=$config['site_dir']."/cache"; 
if (time()-@filemtime($cachefile)>$config['cache_time'])
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

echo parse_template(get_template('index'), $parse_main);
?>