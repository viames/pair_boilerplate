<?php

// avoid the casting of an incorrect type in the expected scalar
declare(strict_types=1);

use Pair\Core\Application;
use Pair\Support\Translator;

// true/false icons
define ('PAIR_CHECK_ICON', '<i class="fa fa-check fa-lg text-success"></i>');
define ('PAIR_TIMES_ICON', '<i class="fa fa-times fa-lg text-danger"></i>');

// initialize composer
require dirname(__DIR__) . '/vendor/autoload.php';

// start the Application
$app = Application::getInstance();

// oAuth2 token creation
$app->setGuestModule('oauth2');

// initialize project classes
require APPLICATION_PATH . '/classes/classLoader.php';

// any API requests
$app->runApi('api');

// any session
$app->manageSession();

// suffix js and css for local development
define('ASSET_SUFFIX', Application::isDevelopmentHost() ? time() : PRODUCT_VERSION);

// list of common CSS
$styles = [
	'plugins/sweetalert2/sweetalert2.min.css',
	'plugins/iziToast-1.4/iziToast.min.css',
	'assets/fontawesome/css/all.min.css',
	'css/pair.css?' . ASSET_SUFFIX,
	'css/custom.css?' . ASSET_SUFFIX
];

// load all CSS
foreach ($styles as $style) {
	$app->loadCss($style);
}

// list of common JavaScript
$scripts = [
	'pwstrength-bootstrap.min.js',
	'plugins/timeago/jquery.timeago.js',
	'plugins/iziToast-1.4/iziToast.min.js',
	'plugins/sweetalert2/sweetalert2.min.js',
	'js/pair.js?' . ASSET_SUFFIX,
	'js/custom.js?2' . ASSET_SUFFIX
];

$languageCode = Translator::getCurrentLanguageCode();

// check about localization files
if ($languageCode and 'en'!=$languageCode) {
	$scripts[] = 'plugins/timeago/jquery.timeago.' . $languageCode . '.js';
}

// load all JavaScript
foreach ($scripts as $script) {
	$app->loadScript($script);
}

// cookiePrefix
$app->addScript('let cookiePrefix = "' . $app->getCookiePrefix() . '";');

// start controller and then display
$app->startMvc();
