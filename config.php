<?php
include_once 'hal.php';
date_default_timezone_set("Europe/Minsk"); //настройка временной зоны http://php.net/manual/en/timezones.php

//////////////////////////////////////параметры изображений//////////////////////////////////////
$config['max_size_mb']=5;
$config['max_size_byte']=$config['max_size_mb']*1048576; // (bytes)
$config['max_height']=5000;
$config['max_width']=5000;
$config['view_one_width']="auto"; //ширина картинки на странице просмотра. картинка одна, большая
$config['view_multi_width']="auto"; //ширина картинок на странице просмотра при мультизагрузке

$config['quality']=100;

$config['mimes']=array('image/gif', 'image/pjpeg', 'image/jpeg', 'image/png', 'image/bmp', 'image/x-ms-bmp');
//расширения которые можно загружать (jpeg и jpe - это jpg)
$config['extensions']=array(
							'gif',
							'jpg',
							'png',
							'bmp',
);

$config['auto_resize']=0;  //Уменьшить изображение, по умолчанию форма: 0 - выключена, 1 - включена
$config['width_resize_elements']=1024; //уменьшать изображения по ширине, по умолчанию в форме
//$config['height_resize_elements']=768; //уменьшать изображения по высоте, по умолчанию в форме
// для исключения искажения изображения добавляется только один параметр, по ширине - имеет приоритет.

$config['auto_preview']=0;  //Создать превью, по умолчанию форма: 0 - выключена, 1 - включена
$config['width_preview_elements']=240; //превью по ширине, по умолчанию в форме
//$config['height_preview_elements']=180; //превью по высоте, по умолчанию в форме
// для исключения искажения изображения добавляется только один параметр, по ширине - имеет приоритет.

//////////////////////////////////////абсолютные пути//////////////////////////////////////

$config['site_dir']=getcwd();
$config['uploaddir']=$config['site_dir'].'/img/';
$config['thumbdir']=$config['site_dir'].'/thumbs/';
$config['working_dir']=$config['site_dir'].'/working/';
$config['working_thumb_dir']=$config['working_dir'].'thumbs/';

//////////////////////////////////////URL//////////////////////////////////////

preg_match('/\/(.*\/)index.php/', $_SERVER['PHP_SELF'], $out);
$folder = isset($out[1]) ? $out[1]:'';
$config['protocol']=(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$config['site_url']=$config['protocol'].$_SERVER['HTTP_HOST'].'/'.$folder;
$config['thumbs_url']=$config['site_url'].'thumbs/';
$config['img_url']=$config['site_url'].'img/';

//////////////////////////////////////Шаблон//////////////////////////////////////

//доступные шаблоны 'bluestyle', 'graphene' , 'simple', 'whatsyoursolution'
$config['template_name']='whatsyoursolution'; 
$config['template_path']=$config['site_dir'].'/templates/'.$config['template_name'];
$config['template_url']=$config['site_url'].'templates/'.$config['template_name'];

$config['site_title']='Хостинг картинок AKINA'; //Title страницы, возможно динамическое обновление 
$config['site_header_h1']='Фотохостинг Akina'; //Текст в заголовке сайта тэг H1, возможно динамическое обновление 
$config['site_header_h2']='Хостинг картинок'; //Текст в заголовке сайта тэг H2, возможно динамическое обновление

$config['view_page']=1; //добавлять к коду изображения с превью ссылку на "Страницу просмотра" 0 - выключено, 1 - включено
//если выключено всегда будет ссылка вести прямо на оригинальную картинку 

$config['show_upload_date']=1; //показывать дату/время загрузки изображения на "Странице просмотра" 0 - выключено, 1 - включено

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
$debug = true; // [true|false] отладка, показывать ошибки PHP включено/выключено

//////////////////////////////////////Прочее//////////////////////////////////////

$config['random_str_quantity']=25;
$config['site_work']=true; // [true|false] сайт работает да/нет
$config['cache_time']=60*60; //в секундах, 1 час.
$config['cachefile']=$config['working_dir']."/cachefile.dat"; //файл статистики "Изображений на фотохостинге: х; занимают х.х Kb; за сутки загружено: х"

//////////////////////////////////////Проверка и создание каталогов (бывший engine.php)//////////////////////////////////////

$uploaddir=$config['uploaddir'].$config['current_path'];
$thumbdir=$config['thumbdir'].$config['current_path'];

if (!is_dir ($config['working_thumb_dir']))
	mkdir ($config['working_thumb_dir'], 0755, true);

if (!is_dir ($uploaddir))
	mkdir ($uploaddir, 0755, true);

if (!is_dir ($thumbdir))
	mkdir ($thumbdir, 0755, true);

if (!is_writable($uploaddir))
	$error[]="Ошибка! Директория ".$uploaddir." недоступна для записи";
if (!is_writable($thumbdir))
	$error[]="Ошибка! Директория ".$thumbdir." недоступна для записи";

//////////////////////////////////////Вывод ошибок//////////////////////////////////////

if (!extension_loaded('fileinfo') and !function_exists('mime_content_type'))
    $error[]='PHP расширение fileinfo не установлено!';

if (!extension_loaded('gd') and !function_exists('gd_info'))
    $error[]='PHP расширение GD не установлено! Изменение размеров изображения и создание превью не будут работать.';

if (!function_exists('curl_version'))
        $error[]='PHP равширение cURL не установлено. Загрузка изображений с удаленных серверов не будет работать';

if (!file_exists('gdenhancer/GDEnhancer.php') or !file_exists('gdenhancer/models/Run.php') or !file_exists('gdenhancer/models/Actions.php') or !file_exists('gdenhancer/models/Library.php') or !file_exists('gdenhancer/models/Output.php'))
    $error[]='Модуль GD Enhancer не найден! Изменение размеров изображения и создание превью не будут работать.';

if($config['max_size_mb'] > ini_get('upload_max_filesize'))
    $error[]='Ошибка! Максимально допустимый размер загружаемого изображения в php.ini ('.ini_get('upload_max_filesize').') меньше заданного в настройках фотохостинга ('.$config['max_size_mb'].' МБ)';

if($config['max_size_mb'] > ini_get('post_max_size'))
    $error[]='Ошибка! Максимальный размер POST в настройках php ('.ini_get('post_max_size').') меньше максимально допустимого размера загружаемого изображения, заданного в настройках фотохостинга ('.$config['max_size_mb'].' МБ)';


//проверка прав доступа к каталогам
$uid=getmyuid();

$fperm=0; //счетчик ошибок прав доступа к каталогам

$perms = substr(decoct(fileperms($config['working_dir'])), 2);
$fown = fileowner($config['working_dir']);
$fperm += (($uid!=$fown and $perms[2]!='5') || ($uid==$fown and $perms[0]!='7'))?1:0;

$perms = substr(decoct(fileperms($config['working_thumb_dir'])), 2);
$fown = fileowner($config['working_thumb_dir']);
$fperm += (($uid!=$fown and $perms[2]!='5') || ($uid==$fown and $perms[0]!='7'))?1:0;

$perms = substr(decoct(fileperms($config['uploaddir'])), 2);
$fown = fileowner($config['uploaddir']);
$fperm += (($uid!=$fown and $perms[2]!='5') || ($uid==$fown and $perms[0]!='7'))?1:0;

$perms = substr(decoct(fileperms($config['thumbdir'])), 2);
$fown = fileowner($config['thumbdir']);
$fperm += (($uid!=$fown and $perms[2]!='5') || ($uid==$fown and $perms[0]!='7'))?1:0;

if ($fperm)
  $error[]='Ошибка! Некорректно установлены права доступа к каталогам. Обратитесь к инструкции по настройке/установке.';

?>
