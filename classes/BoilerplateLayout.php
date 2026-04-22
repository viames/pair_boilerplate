<?php

declare(strict_types=1);

use Pair\Core\Router;
use Pair\Helpers\Translator;
use Pair\Helpers\Utilities;

/**
 * Shared layout helpers used by Pair v4 explicit page templates.
 */
final class BoilerplateLayout {

	/**
	 * Print a translated string.
	 */
	public static function printText(string $key, string|array|null $vars = null): void {

		print self::translate($key, $vars);

	}

	/**
	 * Print a no-data alert using the active Pair UI theme.
	 */
	public static function printNoData(?string $customMessage = null): void {

		Utilities::showNoDataAlert($customMessage);

	}

	/**
	 * Print a sortable table header link for the current route.
	 */
	public static function printSortable(string $title, int $ascOrder, int $descOrder): void {

		$router = Router::getInstance();
		$label = strtoupper($title) === $title ? self::translate($title) : $title;

		print '<div style="white-space:nowrap">';

		if ($ascOrder == $router->order) {
			print '<a href="' . htmlspecialchars($router->getOrderUrl($descOrder)) . '">' . htmlspecialchars($label) . '</a> <i class="fa fa-arrow-up"></i>';
		} else if ($descOrder == $router->order) {
			print '<a href="' . htmlspecialchars($router->getOrderUrl(0)) . '">' . htmlspecialchars($label) . '</a> <i class="fa fa-arrow-down"></i>';
		} else {
			print '<a href="' . htmlspecialchars($router->getOrderUrl($ascOrder)) . '">' . htmlspecialchars($label) . '</a>';
		}

		print '</div>';

	}

	/**
	 * Return a translated string.
	 */
	public static function translate(string $key, string|array|null $vars = null, bool $warning = true): string {

		return Translator::do($key, $vars, $warning);

	}

}
