<?php

use Pair\Helpers\Utilities;

// classes to load before any other
$preload = [];

foreach ($preload as $f) {
	require APPLICATION_PATH . '/classes/' . $f;
}

// get all classes file
$files = Utilities::getDirectoryFilenames(APPLICATION_PATH . '/classes');

$classLoader = basename(__FILE__);

// require each file
foreach ($files as $file) {
	
	// require if not preloaded and isn’t classLoader
	if (!in_array($file, $preload) and $file != $classLoader) {
		require APPLICATION_PATH . '/classes/' . $file;
	}
	
}
