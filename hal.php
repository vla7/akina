<?php
if (!defined('akina')) 
{
	$fp = @fopen("/log.txt", "a");
	$text = date("H:i:s d-m-Y")." - Несанкционированный доступ с ip: ".$_SERVER['REMOTE_ADDR']." к скрипту ".$_SERVER['SCRIPT_NAME']."\r\n";
	$write = @fwrite($fp, $text);
        echo 'Hacking attempt!<br>Данные о незаконной попытке доступа успешно занесены в лог.';
	@fclose($fp);
	die;
}
?>
