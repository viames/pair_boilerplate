<?php

// avoid the casting of an incorrect type in the expected scalar
declare (strict_types=1);

use Pair\Core\Application;

// initialize composer
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Tell DB-dependent tests whether an application environment file is available.
 */
function pairTestsHaveEnvironment(): bool {

	return file_exists(dirname(__DIR__) . '/.env');

}

// Start the Application only when .env exists; otherwise Pair would launch the installer and stop the test process.
if (pairTestsHaveEnvironment()) {
	Application::getInstance()->headless();
}
