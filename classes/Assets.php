<?php

use Pair\Core\Application;
use Pair\Core\Config;
use Pair\Support\Translator;

class Assets {

	public static function load(): void {

		$app = Application::getInstance();

		// list of common CSS
		$styles = [
			'assets/fontawesome/css/all.min.css',
			'css/pair.css?' . self::suffix(),
			'css/custom.css?' . self::suffix()
		];

		// load all CSS
		foreach ($styles as $style) {
			$app->loadCss($style);
		}

		// list of common JavaScript
		$scripts = [
			'pwstrength-bootstrap.min.js',
			'plugins/timeago/jquery.timeago.js',
			'js/pair.js?' . self::suffix(),
			'js/custom.js?2' . self::suffix()
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

		// izitoast
		$app->loadScript('https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js', TRUE);
		$app->loadCss('https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css');

		// sweetalert
		$app->loadScript('https://cdn.jsdelivr.net/npm/sweetalert2@9', TRUE);
		$app->loadCss('https://cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.css');

	}

	public static function suffix(): string {
		
		return 'production' != Application::getEnvironment()
		? time()
		: Config::get('PRODUCT_VERSION');
		
	}

}