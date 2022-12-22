<?php
declare(strict_types=1);

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Extremely simple autoloader helper, has lots of shortcomings
 */
spl_autoload_register(function ($class) {
	if (mb_substr($class, 0, 9) !== 'Cloudlog\\') {
		return false;
	}
	$class=substr($class,9);
	$file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
	$file = __DIR__ . "/../../src/" . $file;
	if (file_exists($file)) {
		require $file;
		return true;
	}
	return false;
});
