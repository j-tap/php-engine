<?php

/*
* Автозагрузка классов
*/

function __autoload ($className) {

	$aDir = array(
		'/components/',
		'/models/',
	);

	foreach ($aDir as $dir) {
		$path = ROOT.$dir.$className.'.php';
		if (is_file($path)) include_once $path;
	}
}

?>