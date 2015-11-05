<?php
include_once 'hal.php';
use gdenhancer\GDEnhancer;

function preview($filename, $final_filename, $thumb_width, $thumb_height)
{
	global $config, $_POST;

	copy("{$config['working_dir']}$filename", "{$config['working_thumb_dir']}$filename");
	resize("{$config['working_thumb_dir']}$filename", $thumb_width, $thumb_height, $_POST['texttype'],"{$config['working_dir']}$filename");
	rename("{$config['working_thumb_dir']}$filename", "{$config['thumbdir']}{$config['current_path']}/$final_filename");
}

function resize($filename, $resize_width, $resize_height, $texttype=false, $filename_src)
{
	global $config, $_POST, $error;

	$ext = strtolower(strrchr(basename($filename), ".")); // Получаем формат уменьшаемого изображения

	$info = getimagesize($filename); // Возвращает ширину и высоту картинки

	$width=$info['0'];
	$height=$info['1'];

	if($resize_height>$height or $resize_width>$width)
		$error[]="Нельзя уменьшать изображение в большую сторону";
	else
	{
		list($resize_width, $resize_height)=get_resize_proportions($height, $width, $resize_height, $resize_width);
		$type='';

		if (!$info['mime'])
		{
			switch( $ext )
			{
				case '.gif' : $type='gif'; break;
				case '.png' : $type='png'; break;
				case '.jpg' : $type='jpg'; break;
				case '.jpeg': $type='jpg'; break;
				//case '.bmp' : $type='bmp'; break; - здесь уже не должно быть BMP
			}
		}
		else
		{

			switch( $info['mime'] )
			{
				case 'image/gif'  : $type='gif'; break;
				case 'image/pjpeg': $type='jpg'; break;
				case 'image/jpeg' : $type='jpg'; break;
				case 'image/x-png': $type='png'; break;
				case 'image/png'  : $type='png'; break;
				//case 'image/bmp'  : $type='bmp'; break;  - здесь уже не должно быть BMP
				//case 'image/x-ms-bmp' : $type='bmp'; break;
			}
		}

		if ($type!='')
		{
				include_once 'gdenhancer/GDEnhancer.php'; //path of GDEnhancer.php
				$image = new GDEnhancer($filename);
				$image->backgroundResize($resize_width,$resize_height, 'shrink'); //option shrink

				//текст на превью
				if (isset($texttype) and $texttype and $texttype!="nothing")
				{
					$filesize = formatfilesize(filesize($filename));

					if ($texttype == 'dimensions')
						$text = $width.'x'.$height.'('.$filesize.')';
					else
						$text=$_POST['text'];

					$DARKNESS=70;//FIXME: вынести в конфиг!

					//"полупрозрачный" слой подложки под текст
					$imglayer = imagecreatetruecolor($width, 15);
					imagesavealpha($imglayer, true);
					$color = imagecolorallocatealpha($imglayer, 0, 0, 0, $DARKNESS);
					imagefill($imglayer, 0, 0, $color);
					ob_start();
					imagepng($imglayer);
					$image_data = ob_get_contents();
					ob_end_clean();
					imagedestroy($imglayer);

					$image->layerImage($image_data);
					$image->layerMove(0, "top", 0, $resize_height-15);
					$save = $image->save();
					unset($image);

					//сам текст
					$image = new GDEnhancer($save['contents']);
					$image->layerText($text, $config['site_dir']."/bender.otf", '10', '#FFFFFF', 0, 1);
					$image->layerMove(0, "top", 2, $resize_height-14);

				}

				$save = $image->save();
				file_put_contents($filename, $save['contents']);
				unset($image);
		}
	}//else resize_height>height
}

function CURL($url)
{
	global $config, $error;

	$host = str_replace("http://", "", $url);
	$host = preg_replace("#/$#si", "", $host);

	//инициализируем сеанс
	$curl = curl_init($url);
	//указываем адрес страницы
	curl_setopt($curl, CURLOPT_URL,$url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, $config['curl_timeout']);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $config['curl_headers']);
	curl_setopt($curl, CURLOPT_USERAGENT, $config['curl_user_agent']);
	//gzip
	//curl_setopt($curl,CURLOPT_ENCODING,'gzip,deflate');


	$filename=basename($url);
	if($out=@fopen("{$config['working_dir']}$filename", 'wb'))
	{
	  curl_setopt($curl, CURLOPT_FILE, $out);

	  $res = curl_exec($curl);
	  fclose($out);

	  if(curl_errno($curl))
	  {
		  if(curl_errno($curl)==6)
			  $curl_error="Не удалось получить изображение: неверный адрес либо удаленный сервер не отвечает";
		  else
			  $curl_error=curl_errno($curl)." ".curl_error($curl);

	      $error[]=  'Ошибка: '.$curl_error;
	  }
	  else
		  return true;

	  curl_close($curl);
	}
	else
	{
		$error[]="Ошибка локальной загрузки изображения.";
	}

}


function random_string($length, $chartypes)
{
        $chartypes_array=explode(",", $chartypes);
	// Задаем строки символов
	$lower = 'abcdefghijklmnopqrstuvwxyz'; // lowercase
	$upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // uppercase
	$numbers = '1234567890'; // numbers
	$special = '^@*+-+%()!?'; //special characters

        // Определяем символы, из которых будет сгенерирована наша строка
        if(in_array('all', $chartypes_array))
                $chars = $lower.$upper.$numbers.$special;
        else
        {
            if(in_array('lower', $chartypes_array))
                    $chars = $lower;
            if(in_array('upper', $chartypes_array))
                    $chars .= $upper;
            if(in_array('numbers', $chartypes_array))
                    $chars .= $numbers;
            if(in_array('special', $chartypes_array))
                    $chars .= $special;
        }

	// Длина строки с символами
	$chars_length = (strlen($chars) - 1);
	// Создаем нашу строку
	$string = $chars{rand(0, $chars_length)};
	// Генерируем нашу строку
	for ($i = 1; $i < $length; $i = strlen($string))
	{
	// Выбираем случайный элемент из строки с допустимыми символами
	$random = $chars{rand(0, $chars_length)};
	// Убеждаемся в том, что два символа не будут идти подряд
	if ($random != $string{$i - 1}) $string .= $random;
	}
	// Возвращаем результат
	return $string;
}

function check_and_move($filename)
{

	global $config, $_POST;

	$info=getimagesize($config['working_dir'].$filename);

	$final_filename='';

			switch( $info['mime'] )
			{
				case 'image/gif'  : $ext='gif'; break;
				case 'image/pjpeg': $ext='jpg'; break;
				case 'image/jpeg' : $ext='jpg'; break;
				case 'image/x-png': $ext='png'; break;
				case 'image/png'  : $ext='png'; break;
				case 'image/bmp'  : $ext='bmp'; break;
				case 'image/x-ms-bmp' : $ext='bmp'; break;
				default: $ext='fail'; $info['mime']='n/a'; break;
			}

	$stat=stat($config['working_dir'].$filename);

	$partes = explode('.', $filename);
	$extension = strtolower($partes[count($partes) - 1]);

	if ($info['mime']=='n/a')
		$local_error[]="Ошибка: Неверный MIME-тип изображения, допускаются ".implode(', ',$config['mimes']).". Вы пытались залить ".$info['mime'];
		
	elseif (!in_array($ext, $config['extensions']))
		$local_error[]="Ошибка: Неверное расширение изображения, допускаются ".strtoupper(implode(', ',$config['extensions'])).". Вы пытались залить ".strtoupper($extension);

	elseif ($stat['size'] > $config['max_size_byte'])
		$local_error[]="Ошибка: Превышен максимальный размер файла: {$config['max_size_mb']} МБ";

	elseif ($info['1'] > $config['max_height'])
		$local_error[]="Ошибка: Превышена максимальная высота изображения: {$config['max_height']} пикселей";

	elseif ($info['0'] > $config['max_width'])
		$local_error[]="Ошибка: Превышена максимальная ширина изображения: {$config['max_width']} пикселей";

		if (!isset($local_error))
		{

			if ($ext=='bmp') //преобразуем BMP в PNG
			{
				include 'bmp.php';
				$src = imagecreatefrombmp($config['working_dir'].$filename);
				imagepng($src, $config['working_dir'].$filename);
				imagedestroy($src);
				$ext='png';
				$partes[count($partes) - 1]=$ext;
				$filenamepng = implode(".", $partes);
				if (!rename("{$config['working_dir']}$filename", "{$config['working_dir']}$filenamepng"))
					$local_error[]= "Ошибка преобразования изображения";
				$filename=$filenamepng;
				unset($filenamepng);
			}

			$final_filename=random_string($config['random_str_quantity'], 'lower,numbers').".".$ext;
			$uploaded_file_path = strtolower($config['uploaddir'].$config['current_path'].'/'.$final_filename);

			if((isset($_POST['thumb'])) and ($_POST['thumb']=="true"))
			{
				//если пользователь не выставил значение(я) превьюшки
				if ($_POST['thumb_width'] or $_POST['thumb_height'] )
					preview($filename, $final_filename, $_POST['thumb_width'], $_POST['thumb_height'], $config['quality']);
				else
				{
					$local_error[]="Ошибка: Не указан размер превью";
					unlink ("{$config['working_dir']}$filename");
					exit;
				}
			}

			//если установлено уменьшение
			if((isset($_POST['resize'])) and ($_POST['resize']=='true'))
			{
				if ($_POST['width'] or $_POST['height'] )
					resize("{$config['working_dir']}$filename", $_POST['width'], $_POST['height']);

				else
				{
					$local_error[]="Ошибка: Не указан размер уменьшенного рисунка";
					unlink ("{$config['working_dir']}$filename");
					exit;
				}
			}

			if (!rename("{$config['working_dir']}$filename", "{$config['uploaddir']}{$config['current_path']}/$final_filename"))
				$local_error[]= "Ошибка перемещения изображения";
		}//if (!$local_error)
		else
			@unlink ("{$config['working_dir']}$filename");

	if (isset($local_error))
		$local_error_string=implode(', ',$local_error);
	else
    $local_error_string='';

	return array ($final_filename, $local_error_string);
}


function make_img_code ($final_filename, $current_month=false, $current_day=false, $returned_error=false)
{
	global $config, $_POST, $images_array;
	//$view_img_page_arr, $url_img_arr, $bb_img_arr, $html_img_arr, $url_prev_arr, $bb_prev_and_img_arr, $html_prev_and_img_arr, $img_local_path_arr

	$thumb = isset($_POST['thumb']) ? (boolean)$_POST['thumb']:false;

	if(!$current_month)
		$current_month=$config['current_month'];
	if(!$current_day)
		$current_day=$config['current_day'];

	$current_path=$current_month."/".$current_day;
	$current_view_path=$current_month."-".$current_day;

	$img=$config['img_url'].$current_path."/".$final_filename;

	$images_array[$final_filename]['error']=$returned_error;
	$images_array[$final_filename]['local_path']=$config['uploaddir'].$current_path."/".$final_filename;
	$images_array[$final_filename]['view_img_page']=$config['site_url']."?v=".$current_view_path."_".$final_filename;
	$images_array[$final_filename]['url_img']=$img;
	$images_array[$final_filename]['bb_img']="[img]".$img."[/img]";
	$images_array[$final_filename]['html_img']=htmlentities("<img src=\"$img\">");

	if($thumb=="true" or is_file($config['thumbdir'].$current_path."/".$final_filename))
	{
		$prev=$config['thumbs_url'].$current_path."/".$final_filename;
		$images_array[$final_filename]['url_prev']=$prev;
		if ((isset($config['view_page'])) and ($config['view_page']==1))
		{
			$images_array[$final_filename]['bb_prev_and_img']="[url=".$config['site_url']."?v=".$current_view_path."_".$final_filename."][img]".$prev."[/img][/url]";
			$images_array[$final_filename]['html_prev_and_img']=htmlentities("<a href=\"".$config['site_url']."?v=".$current_view_path."_".$final_filename."\" target=\"_blank\"><img src=\"".$prev."\"></a>");
		}
		else
		{
			$images_array[$final_filename]['bb_prev_and_img']="[url=".$img."][img]".$prev."[/img][/url]";
			$images_array[$final_filename]['html_prev_and_img']=htmlentities("<a href=\"".$img."\" target=\"_blank\"><img src=\"".$prev."\"></a>");
		}

	}
}


function get_resize_proportions ($real_height, $real_width, $resize_height=false, $resize_width=false)
{
	//если не задана ширина, а только высота
	if (!$resize_width and $resize_height)
	{
		$coefficient=$real_height/$resize_height; //коэффцициент уменьшения
		$resize_width=$real_width/$coefficient; //новая ширина
	}
	//если не задана высота, а только ширина
	elseif ($resize_width and !$resize_height)
	{
		$coefficient=$real_width/$resize_width; //коэффцициент уменьшения
		$resize_height=$real_height/$coefficient; //новая высота
	}
	//если задана высота и ширина
	else
	{
		$width_koef=$real_width/$resize_width;
		$height_koef=$real_height/$resize_height;

		if($width_koef>$height_koef)
			$coefficient = $width_koef;
		else
			$coefficient = $height_koef;
			$resize_height=$real_height/$coefficient; //новая высота
			$resize_width=$real_width/$coefficient; //новая ширина
	}

	return array($resize_width, $resize_height);
}


function get_template ($tpl_name)
{
	global $config, $error;

	$tpl_path=$config['template_path'];

	$template = file_get_contents($tpl_path.'/'.$tpl_name.'.tpl')
		or $error[] ='Шаблон '.$tpl_name.' не найден по заданному пути';

	return $template;
}


function parse_template ($template_source,$data_array)
{
	return str_replace(array_keys($data_array), array_values($data_array), $template_source);

}


function get_dir_size($dir_name)
{
	$dir_size = 0;
	$file_count = 0;
	$file24_count = 0;
	if (is_dir($dir_name))
	{
		if ($dh = opendir($dir_name))
		{
			while (($file = readdir($dh)) !== false)
			{
				if($file !='.' and $file != '..')
				{
					if(is_file($dir_name.'/'.$file))
					{
						$dir_size += filesize($dir_name.'/'.$file);
						//24*60*60
						if (time()-filemtime($dir_name.'/'.$file)<86400)
						{
							$file24_count++;
						}
						$file_count++;
					}
					if(is_dir($dir_name.'/'.$file))
					{
						list($foo_dir_size, $foo_file_count,$foo_file24_count) =  get_dir_size($dir_name.'/'.$file);
						$dir_size += $foo_dir_size;
						$file_count += $foo_file_count;
						$file24_count += $foo_file24_count;
					}
				}
			}
			closedir($dh);
		}
	}
	return array ($dir_size, $file_count,$file24_count);
}


function formatfilesize($size)
{
	$i=0;
	$iec = array('B', 'Kb', 'Mb', 'Gb', 'Tb');
	while (($size/1024)>1)
	{
		$size /= 1024;
		$i++;
	}
	return(round($size,1)." ".$iec[$i]);
}

?>
