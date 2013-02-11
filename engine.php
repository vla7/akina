<?php
include_once 'hal.php';

$uploaddir=$config['uploaddir'].$config['current_path'];
$thumbdir=$config['thumbdir'].$config['current_path'];

if (!is_dir ($config['working_thumb_dir']))
	mkdir ($config['working_thumb_dir'], 0777, true);

if (!is_dir ($uploaddir))
	mkdir ($uploaddir, 0777, true);

if (!is_dir ($thumbdir))
	mkdir ($thumbdir, 0777, true);

if (!is_writable($uploaddir))
	$error[]="Ошибка! Директория ".$uploaddir." недоступна для записи";
if (!is_writable($thumbdir))
	$error[]="Ошибка! Директория ".$thumbdir." недоступна для записи";
?>