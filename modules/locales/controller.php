<?php

declare(strict_types=1);

use Pair\Core\Router;
use Pair\Exceptions\AppException;
use Pair\Helpers\Translator;
use Pair\Html\Breadcrumb;
use Pair\Html\Form;
use Pair\Html\Pagination;
use Pair\Models\Locale;
use Pair\Web\Controller;
use Pair\Web\PageResponse;

class LocalesController extends Controller {

	/**
	 * Locales module data helper.
	 */
	private LocalesModel $model;

	/**
	 * Prepare the explicit controller dependencies.
	 */
	protected function boot(): void {

		$this->model = new LocalesModel();
		Breadcrumb::path($this->translate('LOCALES'), 'locales');

	}

	/**
	 * Render the locales list with explicit typed state.
	 */
	public function defaultAction(): PageResponse {

		$requestedFilter = Router::get(0);

		if ($requestedFilter) {
			$this->setPersistentState('localesAlphaFilter', $requestedFilter);
		} else {
			$this->unsetPersistentState('localesAlphaFilter');
		}

		$this->pageHeading($this->translate('LOCALES'));

		return $this->page(
			'default',
			$this->buildDefaultPageState(),
			$this->translate('LOCALES')
		);

	}

	/**
	 * Render the new-locale form with explicit typed state.
	 */
	public function newAction(): PageResponse {

		$this->pageHeading($this->translate('NEW_LOCALE'));

		return $this->page(
			'new',
			new LocalesNewPageState($this->model->getLocaleForm()),
			$this->translate('NEW_LOCALE')
		);

	}

	/**
	 * Persist a new locale or re-render the form with validation errors.
	 */
	public function addAction(): ?PageResponse {

		$locale = new Locale();
		$locale->populateByRequest();

		if ($locale->store()) {
			$this->toast($this->translate('LOCALE_HAS_BEEN_CREATED'));
			$this->redirect('locales');
		}

		$this->toastError($this->buildErrorsMessage($locale, 'LOCALE_HAS_NOT_BEEN_CREATED'));
		$this->pageHeading($this->translate('NEW_LOCALE'));

		return $this->page(
			'new',
			new LocalesNewPageState($this->buildLocaleForm($locale)),
			$this->translate('NEW_LOCALE')
		);

	}

	/**
	 * Render the edit-locale form with explicit typed state.
	 */
	public function editAction(): PageResponse {

		$locale = $this->loadLocaleFromRoute();
		$this->pageHeading($this->translate('EDIT_LOCALE'));

		return $this->buildEditPage($locale);

	}

	/**
	 * Persist an edited locale or re-render the edit page with validation errors.
	 */
	public function changeAction(): ?PageResponse {

		$locale = new Locale((int)$this->input()->int('id', 0));
		$locale->populateByRequest();

		if ($locale->store()) {
			$this->toast($this->translate('LOCALE_HAS_BEEN_CHANGED_SUCCESFULLY'));
			$this->redirect('locales');
		}

		$this->toastError($this->buildErrorsMessage($locale, 'ERROR_ON_LAST_REQUEST'));
		$this->pageHeading($this->translate('EDIT_LOCALE'));

		return $this->buildEditPage($locale);

	}

	/**
	 * Delete a locale or re-render the edit page with the deletion error.
	 */
	public function deleteAction(): ?PageResponse {

		$locale = $this->loadLocaleFromRoute();

		if ($locale->appDefault) {
			$this->toastError($this->translate('ERROR_DELETING_LOCALE'));
			$this->pageHeading($this->translate('EDIT_LOCALE'));

			return $this->buildEditPage($locale);
		}

		if ($locale->delete()) {
			$this->toast($this->translate('LOCALE_HAS_BEEN_DELETED_SUCCESFULLY'));
			$this->redirect('locales');
		}

		$this->toastError($this->buildErrorsMessage($locale, 'ERROR_DELETING_LOCALE'));
		$this->pageHeading($this->translate('EDIT_LOCALE'));

		return $this->buildEditPage($locale);

	}

	/**
	 * Build the explicit list page state from the legacy model helpers.
	 */
	private function buildDefaultPageState(): LocalesDefaultPageState {

		$pagination = new Pagination();
		$pagination->page = $this->router->getPage();

		// The legacy model still expects Pagination to be injected before loading rows.
		$this->model->pagination = $pagination;

		$locales = $this->model->getLocales();
		$pagination->count = $this->model->countListItems();
		$rows = [];

		foreach ($locales as $locale) {
			$rows[] = new LocalesListItemState(
				(int)$locale->id,
				(string)($locale->representation ?? ''),
				(string)($locale->languageName ?? ''),
				(bool)$locale->officialLanguage,
				(string)($locale->countryName ?? ''),
				(bool)$locale->defaultCountry,
				(bool)$locale->appDefault
			);
		}

		return new LocalesDefaultPageState($rows, $pagination->render());

	}

	/**
	 * Build the edit page response from a loaded locale.
	 */
	private function buildEditPage(Locale $locale): PageResponse {

		return $this->page(
			'edit',
			new LocalesEditPageState(
				$this->buildLocaleForm($locale),
				(int)$locale->id,
				$locale->isDeletable() and !$locale->appDefault
			),
			$this->translate('EDIT_LOCALE')
		);

	}

	/**
	 * Build the create/edit form and optionally preload it with a locale.
	 */
	private function buildLocaleForm(?Locale $locale = null): Form {

		$form = $this->model->getLocaleForm();

		if (!is_null($locale) and $locale->isLoaded()) {
			$form->values($locale);
		}

		return $form;

	}

	/**
	 * Load the requested locale id from the router and ensure it exists.
	 */
	private function loadLocaleFromRoute(): Locale {

		$localeId = (int)(Router::get(0) ?? 0);

		if ($localeId < 1) {
			throw new AppException($this->translate('NO_ID_OF_ITEM_TO_EDIT', 'Pair\Models\Locale'));
		}

		$locale = new Locale($localeId);

		if (!$locale->isLoaded()) {
			throw new AppException($this->translate('ID_OF_ITEM_TO_EDIT_IS_NOT_VALID', 'Pair\Models\Locale'));
		}

		return $locale;

	}

	/**
	 * Build the toast message for a failed store or delete operation.
	 */
	private function buildErrorsMessage(Locale $locale, string $fallbackKey): string {

		$errors = $locale->getErrors();

		if (count($errors)) {
			return $this->translate($fallbackKey) . ": \n" . implode(" \n", $errors);
		}

		return $this->translate('ERROR_ON_LAST_REQUEST');

	}

	/**
	 * Translate a language key inside the locales module.
	 */
	private function translate(string $key, string|array|null $vars = null): string {

		return Translator::do($key, $vars);

	}

}
