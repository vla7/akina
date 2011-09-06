<?php
if (!is_dir ($config['uploaddir']))
	mkdir ($config['uploaddir'], 0700);

if (!is_dir ($config['thumbdir']))
	mkdir ($config['thumbdir'], 0700);

if (!is_dir ($config['working_dir']))
	mkdir ($config['working_dir'], 0700);

if (!is_dir ($config['working_thumb_dir']))
	mkdir ($config['working_thumb_dir'], 0700);

//каталоги месяцев для картинок и превьюшек
if (!is_dir ($config['uploaddir'].$config['current_month']))
	mkdir ($config['uploaddir'].$config['current_month'], 0700);

if (!is_dir ($config['thumbdir'].$config['current_month']))
	mkdir ($config['thumbdir'].$config['current_month'], 0700);

if (!is_dir ($config['uploaddir'].$config['current_path']))
	mkdir ($config['uploaddir'].$config['current_path'], 0700);

if (!is_dir ($config['thumbdir'].$config['current_path']))
	mkdir ($config['thumbdir'].$config['current_path'], 0700);
?>