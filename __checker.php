<?php
define('akina', 'photohost', true);

// расцветка для подсветки элементов
$check_good="#33AA33";
$check_bad="#FF3333";
$check_empty="#666666";
$check_element="#FF7F00";

//загружаем предустановки
if(!include_once('config.php')) die('Can\'t find config.php');
?>

<head>
	<title>Akina. Проверка перехода на новую версию. Checking move to a new version</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
body {
    font-family: Courier, 'Courier New', 'Times New Roman', Times, serif;
    font-size: 11pt;
}
</style>
</head>
<body >
<?php 

function new_config()
{
/* --------------------------------------------------------------------------
 Далее содержится файл конфигурации config.php поставляемый по "умолчанию" 
 По состоянию на v1.0.9h (r77) Jun 26, 2015 
----------------------------------------------------------------------------- */

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
$config['site_url']='http://'.$_SERVER['HTTP_HOST'].'/'.$folder;
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

/* --------------------------------------------------------------------------
Конец файла конфигурации config.php поставляемый по "умолчанию"
----------------------------------------------------------------------------- */

return ($config);
}

$new_config = new_config();

echo "<p>";
// расцветка для подсветки элементов
echo "Расцветка элементов после проверки:<br />\n";
echo "<ul>";
echo "<li><font color='$check_element'> Название элемента из конфигурации.</font><br />\n";
echo "<li><font color='$check_good'> Элемент <b>совпадает</b> с вашей конфигурацией.</font><br />\n";
echo "<li><font color='$check_bad'> Нужна корректировка параметра. Элемент есть и <b>не совпадает</b> с вашей конфигурацинй.</font><br />\n";
echo "<li><font color='$check_empty'> Элемент <b>отсутствует</b> в вашей конфигурацинй.</font><br />\n";
echo "</ul>";
echo "</p>";

//echo "<pre>"; print_r($new_config); echo "</pre>";

echo "<hr><br>";

$level=0;
function check_array($array,$arr_key)
{
	GLOBAL $config,$new_config,$level;
	GLOBAL $check_good,$check_bad,$check_empty,$check_element;
	foreach ($array as $key => $value)
	{
		$check_b="<font color='$check_empty'>";
		$check_e="</font>";
	
  	if (!is_array($value))
  	{
      if (!empty($arr_key))
      {
				if (in_array($value, $config[$arr_key]))
	 			{
				  $check_b="<font color='$check_good'>";
				  $check_e="</font>";
				}
			}
			else
			if (isset($config[$key]))
			{
				if ($config[$key]===$new_config[$key])
				{
				  $check_b="<font color='$check_good'>";
				  $check_e="</font>";
				}
				else
				{
				  $check_b="<font color='$check_bad'>";
				  $check_e="</font>";
				}
			}

		  for ($i=0;$i < $level+1 ;$i++)  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  
 		  if ($level) $ccf=''; else  $ccf="<font color='$check_element'>\$config</font>";
			echo $ccf."['<font color='$check_element'>".$key."</font>'] => ".$check_b.$value."&nbsp".$check_e."<br />\n";
		}
		else
		{
			if (array_key_exists($key, $config))
			{
			  $check_b="<font color='#00CC00'>";
			  $check_e="</font>";
			}

		  for ($i=0;$i < $level+1 ;$i++)  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "<font color='$check_element'>\$config</font>['<font color='$check_element'>".$key."</font>'] => ".$check_b."Array".$check_e."<br />\n";

		  for ($i=0;$i < $level+1 ;$i++)  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  echo "&nbsp;&nbsp;(<br />\n";
			$level++;
			check_array($value,$key);
			$level--;
		  for ($i=0;$i < $level+1 ;$i++)  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  echo "&nbsp;&nbsp;)<br />\n";
		}
	}
    
}

check_array($new_config,NULL);

if ((!isset($config['cachefile'])) and (file_exists($config['site_dir']."/cache" )) )
echo "<font color='$check_bad'>** Нужна корректировка параметра</font> <font color='$check_element'>\$config</font>['<font color='$check_element'>cachefile</font>']=<font color='$check_element'>\$config</font>['<font color='$check_element'>site_dir</font>'].'/cache'</font>\n";


?>
<br><hr><br>

</body>
</html>
