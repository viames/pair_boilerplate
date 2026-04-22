<?php

declare(strict_types=1);

use Pair\Core\Router;
use Pair\Exceptions\AppException;
use Pair\Helpers\Translator;
use Pair\Html\Pagination;
use Pair\Orm\ActiveRecord;
use Pair\Web\Controller;

/**
 * Shared Pair v4 controller helpers for boilerplate modules.
 */
abstract class BoilerplateController extends Controller {

	/**
	 * Build a pagination object aligned with the current router page.
	 */
	protected function buildPagination(?int $count = null): Pagination {

		$pagination = new Pagination();
		$pagination->page = $this->router->getPage();

		if (!is_null($count)) {
			$pagination->count = $count;
		}

		return $pagination;

	}

	/**
	 * Build a translated error message from an ActiveRecord validation result.
	 */
	protected function buildRecordErrorMessage(ActiveRecord $record, string $fallbackKey): string {

		$errors = $record->getErrors();

		if (count($errors)) {
			return $this->translate($fallbackKey) . ":\n" . implode("\n", $errors);
		}

		return $this->translate($fallbackKey);

	}

	/**
	 * Load an ActiveRecord by URL parameter and ensure it exists.
	 *
	 * @param	class-string<ActiveRecord>	$class	ActiveRecord class name.
	 */
	protected function loadRecordFromRoute(string $class, ?int $position = 0): ActiveRecord {

		$id = Router::get($position);

		if (!$id) {
			throw new AppException($this->translate('NO_ID_OF_ITEM_TO_EDIT', $class));
		}

		$record = new $class($id);

		if (!$record->isLoaded()) {
			throw new AppException($this->translate('ID_OF_ITEM_TO_EDIT_IS_NOT_VALID', $class));
		}

		return $record;

	}

	/**
	 * Translate a language key in the current application context.
	 */
	protected function translate(string $key, string|array|null $vars = null, bool $warning = true): string {

		return Translator::do($key, $vars, $warning);

	}

}
