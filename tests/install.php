<?php

declare(strict_types=1);

/** Test the starter archive with the sibling framework checkout before publishing either release. */
$frameworkRoot = getenv('PAIR_FRAMEWORK_PATH') ?: dirname(__DIR__, 2) . '/pair';
$test = $frameworkRoot . '/tests/Integration/Composer/install.php';
if (!is_file($test)) {
	fwrite(STDERR, "Set PAIR_FRAMEWORK_PATH to a Pair checkout containing the installation test.\n");
	exit(1);
}
$process = proc_open([PHP_BINARY, $test, dirname(__DIR__)], [STDIN, STDOUT, STDERR], $pipes, dirname(__DIR__));
if (!is_resource($process)) {
	fwrite(STDERR, "Unable to start the starter installation test.\n");
	exit(1);
}
exit(proc_close($process));
