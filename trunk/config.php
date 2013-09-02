<?php
include_once 'hal.php';
//////////////////////////////////////параметры изображений//////////////////////////////////////
$config['max_size_mb']=5;
$config['max_size_byte']=$config['max_size_mb']*1048576; // (bytes)
$config['max_height']=5000;
$config['max_width']=5000;
$config['view_one_width']="auto"; //ширина картинки на странице просмотра. картинка одна, большая
$config['view_multi_width']="auto"; //ширина картинок на странице просмотра при мультизагрузке

$config['quality']=100;

$config['mimes']=array('image/gif', 'image/pjpeg', 'image/jpeg', 'image/png');
$config['extensions']=array('gif', 'jpeg', 'jpg', 'png');

//////////////////////////////////////абсолютные пути//////////////////////////////////////

$config['site_dir']=getcwd();
$config['uploaddir']=$config['site_dir'].'/img/';
$config['thumbdir']=$config['site_dir'].'/thumbs/';
$config['working_dir']=$config['site_dir'].'/working/';
$config['working_thumb_dir']=$config['working_dir'].'/thumbs/';

//////////////////////////////////////URL//////////////////////////////////////

preg_match('/\/(.*\/)index.php/', $_SERVER['PHP_SELF'], $out);
$folder = isset($out[1]) ? $out[1]:'';
$config['site_url']='http://'.$_SERVER['HTTP_HOST'].'/'.$folder;
$config['thumbs_url']=$config['site_url'].'thumbs/';
$config['img_url']=$config['site_url'].'img/';

//////////////////////////////////////Шаблон//////////////////////////////////////

$config['template_name']='whatsyoursolution';
$config['template_path']=$config['site_dir'].'/templates/'.$config['template_name'];
$config['template_url']=$config['site_url'].'templates/'.$config['template_name'];

//////////////////////////////////////CURL//////////////////////////////////////

$config['curl_timeout'] = 120;//таймаут для curl
$config['curl_user_agent']='User-Agent: Opera/9.80 (X11; Linux i686; U; ru) Presto/2.9.168 Version/11.50';
$config['curl_headers']=array(
'GET / HTTP/1.1',
'Accept: text/html, application/xml;q=0.9, application/xhtml+xml, image/png, image/jpeg, image/gif, image/x-xbitmap, */*;q=0.1',
'Accept-Language: ru,ru-RU;q=0.9,en;q=0.8',
'Accept-Charset: iso-8859-1, utf-8, utf-16, *;q=0.1',
'Accept-Encoding: deflate, gzip, x-gzip, identity, *;q=0',
'Cookie: cookies_enabled=1;',
'Cache-Control: no-cache',
'Connection: Keep-Alive, TE',
'TE: deflate, gzip, chunked, identity, trailers'
);

//////////////////////////////////////Даты - для создания каталогов//////////////////////////////////////

$config['current_month']=date ('Y-m');
$config['current_day']=date ('d');
$config['current_path']=$config['current_month'].'/'.$config['current_day'];

//////////////////////////////////////Прочее//////////////////////////////////////

$config['random_str_quantity']=25;
$config['site_work']=true;
$config['cache_time']=60*60; //в секундах, 1 час.

//////////////////////////////////////Вывод ошибок//////////////////////////////////////

if (!extension_loaded('gd') && !function_exists('gd_info'))
    $error[]='Модуль GD не установлен! Изменение размеров изображения и создание превью не будут работать.';

if($config['max_size_mb']>ini_get('upload_max_filesize'))
    $error[]='Ошибка! Максимально допустимый размер загружаемого изображения в php.ini ('.ini_get('upload_max_filesize').') меньше заданного в настройках фотохостинга ('.$config['max_size_mb'].' МБ)';

if (!function_exists('curl_version'))
	$error[]='Модуль cURL не установлен. Загрузка изображений с удаленных серверов не будет работать';

if($config['max_size_mb']>ini_get('post_max_size'))
    $error[]='Ошибка! Максимальный размер POST в настройках php ('.ini_get('post_max_size').') меньше максимально допустимого размера загружаемого изображения, заданного в настройках фотохостинга ('.$config['max_size_mb'].' МБ)';

if (!function_exists('finfo_open') and !function_exists('mime_content_type'))
  $error[]='Ошибка! Отсутствуют обязательные функции "finfo_open" и "mime_content_type". Должна быть хотя бы одна из них. Обратитесь к хостеру.';
?>