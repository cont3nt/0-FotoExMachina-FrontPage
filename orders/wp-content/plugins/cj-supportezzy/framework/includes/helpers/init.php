<?php
$helpers_path = dirname(__FILE__).'/';
$dirs = preg_grep('/^([^.])/', scandir($helpers_path));
foreach ($dirs as $dir) {
	$dir_path = $helpers_path.$dir;
	if(is_dir($dir_path)){
		require_once(sprintf('%s/index.php', $dir_path));
	}
}