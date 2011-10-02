<?php
include_once 'hal.php';
if(($_POST['resize'] == true) and ($_POST['width']+$_POST['height']==0))
	$error[]='Выбрана опция изменения размера, но не установлена ни ширина ни высота!';
elseif(($_POST['thumb'] == true) and ($_POST['thumb_width']+$_POST['thumb_height']==0))
	$error[]='Выбрана опция "Создать превью", но не установлена ни ширина ни высота!';
else
{
	if (isset($_POST['web_uploadfile']))
	{
		$_POST['web_uploadfile'] = preg_replace("/\s/", '', $_POST['web_uploadfile']);//вырезаем переносы строк
		preg_match_all ('#(https?://[\w-]+[\.\w-]+/((?!https?://)[\w- ./?%&=])+)#', $_POST['web_uploadfile'], $out);
		$web_links_quantity=count($out['0']);
		if($web_links_quantity>0)
		{
			foreach($out['0'] as $up) 
			{
				if (CURL($up))
				{

				$partes = explode('/', $up);
				$filename = $partes[count($partes) - 1];

				list($final_filename, $returned_error)=check_and_move($filename);

				make_img_code($final_filename, false, false, $returned_error);
				}
			}
		}
	}

	if (isset($_FILES['local_uploadfile']))
	{
	$local_up = $_FILES['local_uploadfile'];
	foreach ($local_up['name'] as $key => $check) 
	{
		if (empty($check)) 
		{
			foreach($local_up as $type => $value) 
			{
				unset($local_up[$type][$key]);
			}
		}
				
	}
		// Local uploading
		if ($local_up['size'][0] ==! null) 
		{
			foreach($local_up['tmp_name'] as $key => $up) 
			{
				$filename = $local_up['name'][$key];

				copy($up, "{$config['working_dir']}$filename");
				unset($up);

				list($final_filename, $returned_error)=check_and_move($filename);


				make_img_code($final_filename, false, false, $returned_error);
			}
		}
	}
}
?>