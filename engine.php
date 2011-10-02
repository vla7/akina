<?php
include_once 'hal.php';

if (!is_dir ($config['working_thumb_dir']))
        mkdir ($config['working_thumb_dir'], 0777, true);
        
if (!is_dir ($config['uploaddir'].$config['current_path']))
        mkdir ($config['uploaddir'].$config['current_path'], 0777, true);

if (!is_dir ($config['thumbdir'].$config['current_path']))
        mkdir ($config['thumbdir'].$config['current_path'], 0777, true);
?>