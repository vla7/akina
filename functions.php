<?php
include_once 'hal.php';

function preview($filename, $final_filename, $thumb_width, $thumb_height)
{
	global $config, $_POST;

	copy("{$config['working_dir']}$filename", "{$config['working_thumb_dir']}$filename");
	resize("{$config['working_thumb_dir']}$filename", $thumb_width, $thumb_height, $_POST['texttype']);
	rename("{$config['working_thumb_dir']}$filename", "{$config['thumbdir']}{$config['current_path']}/$final_filename");
}



function resize($filename, $resize_width, $resize_height, $texttype=false)
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

		if (!$info['mime'])
		{
			if ($ext=='.gif')
				$type='gif';
			if ($ext=='.png')
				$type='png';
			if ($ext=='.jpg')
				$type='jpg';
			if ($ext=='.jpeg')
				$type='jpg';
			if ($ext=='.bmp')
				$type='bmp';
		}
		else
		{
			if ($info['mime']=='image/gif')
			$type='gif';
			if ($info['mime']=='image/pjeg')
			$type='jpg';
			if ($info['mime']=='image/jpeg')
			$type='jpg';
			if ($info['mime']=='image/png')
			$type='png';
			if ($info['mime']=='image/bmp')
			$type='bmp';
			if ($info['mime']=='image/x-ms-bmp')
			$type='bmp';
		}

				if ($type=='gif') {$src = imagecreatefromgif($filename);}
				if ($type=='png') {$src = imagecreatefrompng($filename);}
				//if ($ext=='.jpeg') {$src = imagecreatefromjpeg($filename);}
				if ($type=='jpg') {$src = imagecreatefromjpeg($filename);}

				$era_x = imageSX($src);
				$era_y = imageSY($src);
				$filesize = formatfilesize(filesize($filename));
				$destination = imagecreatetruecolor($resize_width,$resize_height);

				// gif
				if ($type=='gif')
				{
					$transparente = imagecolortransparent($src);
					imagepalettecopy($src, $destination);
					imagefill($destination, 0, 0, $transparente);
					imagecolortransparent($destination, $transparente);
					imagetruecolortopalette($destination, true, 256);
				}
				else
				{
					imagecolortransparent($destination, imagecolorallocate($destination, 0, 0, 0) );
				}

				imagealphablending($destination, false);
					imagesavealpha($destination, true);

				imagecopyresampled($destination,$src,0,0,0,0,$resize_width,$resize_height,$era_x,$era_y);


		//текст на превью
		if ($texttype && $texttype!="nothing")
		{
			if ($texttype == 'dimensions')
				$text = $era_x.'x'.$era_y.'('.$filesize.')';
			else
				$text=$_POST['text'];


		$DARKNESS=70;
		for ($i=0;$i<$resize_width;$i++)
		for ($j=0;$j<12;$j++)
		{
			$x=$i;//+550-(100+10);
			$y=$j+($resize_height-12);
			$rgb = imagecolorat($destination, $x, $y);
			$rgb = imagecolorsforindex($destination, $rgb);

			$rgb["red"] = $rgb["red"] > $DARKNESS ? $rgb["red"]-$DARKNESS : 0;
			$rgb["green"] = $rgb["green"] > $DARKNESS ? $rgb["green"]-$DARKNESS : 0;
			$rgb["blue"] = $rgb["blue"] > $DARKNESS ? $rgb["blue"]-$DARKNESS : 0;

			$c=imagecolorallocate($destination,$rgb["red"],$rgb["green"],$rgb["blue"]);
			imagesetpixel($destination, $x, $y, $c);
			}

		$white=imagecolorallocate($destination,255,255,255);

		$mf = imageloadfont ('myfont.phpfont');

		$text = iconv("utf-8", "windows-1251",$text);

			imagestring($destination, $mf, 2, $resize_height-10, $text, $white);
		}
		///////////////////

				if ($type=='gif') { imagegif($destination, $filename); }
				if ($type=='png') { imagepng($destination, $filename); }
				if ($type=='jpg') { imagejpeg($destination, $filename, $config['quality']); }
				if ($type=='bmp') { imagegif($destination, $filename); }
				//if ($ext=='.jpeg') { imagejpeg($destination, $filename, $config['quality']); }

				imagedestroy($destination);
				imagedestroy($src);
	}

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
	$out=fopen("{$config['working_dir']}$filename", 'wb');
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

function get_mime($file)
{
	global $error;
	if (function_exists('finfo_open')) {
		return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file);
	} elseif (function_exists('mime_content_type')) {
		return mime_content_type($file);
	} else
		$error[]="Невозможно определить MIME изображения, нет функций finfo_open и mime_content_type";
}


function check_and_move($filename)
{

	global $config, $_POST;

	$info=getimagesize($config['working_dir'].$filename);

	$mime=get_mime($config['working_dir'].$filename);

	$stat=stat($config['working_dir'].$filename);
	
	$partes = explode('.', $filename);
	$extension = strtolower($partes[count($partes) - 1]);
	
	if (strlen($extension)<6 and !in_array($extension, $config['extensions']))
		$local_error[]="Ошибка: Неверное расширение изображения, допускаются ".implode(', ',$config['extensions']).". Вы пытались залить $extension";

	elseif (!in_array($mime, $config['mimes']))
		$local_error[]="Ошибка: Неверный MIME-тип изображения, допускаются JPEG, GIF, PNG, BMP. Вы пытались залить $mime";

	elseif ($stat['size'] > $config['max_size_byte'])
		$local_error[]="Ошибка: Превышен максимальный размер файла: {$config['max_size_mb']} МБ";

	elseif ($info['1'] > $config['max_height'])
		$local_error[]="Ошибка: Превышена максимальная высота изображения: {$config['max_height']} пикселей";

	elseif ($info['0'] > $config['max_width'])
		$local_error[]="Ошибка: Превышена максимальная ширина изображения: {$config['max_width']} пикселей";

		if (!isset($local_error))
		{
			if ($mime=='image/gif')
				$ext='gif';
			elseif ($mime=='image/pjpeg')
				$ext='jpg';
			elseif ($mime=='image/jpeg')
				$ext='jpg';
			elseif ($mime=='image/png')
				$ext='png';
			elseif ($mime=='image/bmp')
				$ext='bmp';
			elseif ($mime=='image/x-ms-bmp')
				$ext='bmp';

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
	if (!$resize_width && $resize_height)
	{
		$coefficient=$real_height/$resize_height; //коэффцициент уменьшения
		$resize_width=$real_width/$coefficient; //новая ширина
	}
	//если не задана высота, а только ширина
	elseif ($resize_width && !$resize_height)
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
				if($file !='.' && $file != '..')
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