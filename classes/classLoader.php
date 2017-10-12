<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Utilities;

// classes to load before any other
$preload = [];

foreach ($preload as $f) {
	require 'classes/' . $f;
}

// get all classes file
$files = Utilities::getDirectoryFilenames('classes');

$classLoader = basename(__FILE__);

// require each file
foreach ($files as $file) {
	
	// require if not preloaded and isn’t classLoader
	if (!in_array($file, $preload) and $file != $classLoader) {
		require 'classes/' . $file;
	}
	
}
