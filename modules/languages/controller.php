<?php

declare(strict_types=1);

use Pair\Core\Router;
use Pair\Exceptions\AppException;
use Pair\Helpers\Translator;
use Pair\Html\Breadcrumb;
use Pair\Html\Form;
use Pair\Html\Pagination;
use Pair\Models\Language;
use Pair\Web\Controller;
use Pair\Web\PageResponse;

class LanguagesController extends Controller {

	/**
	 * Languages module data helper.
	 */
	private LanguagesModel $model;

	/**
	 * Prepare the explicit controller dependencies.
	 */
	protected function boot(): void {

		$this->model = new LanguagesModel();
		Breadcrumb::path($this->translate('LANGUAGES'), 'languages');

	}

	/**
	 * Render the languages list with explicit typed state.
	 */
	public function defaultAction(): PageResponse {

		$requestedFilter = Router::get(0);

		if ($requestedFilter) {
			$this->setPersistentState('languagesAlphaFilter', $requestedFilter);
		} else {
			$this->unsetPersistentState('languagesAlphaFilter');
		}

		$this->pageHeading($this->translate('LANGUAGES'));

		return $this->page(
			'default',
			$this->buildDefaultPageState(),
			$this->translate('LANGUAGES')
		);

	}

	/**
	 * Render the new-language form with explicit typed state.
	 */
	public function newAction(): PageResponse {

		$this->pageHeading($this->translate('NEW_LANGUAGE'));

		return $this->page(
			'new',
			new LanguagesNewPageState($this->model->getLanguageForm()),
			$this->translate('NEW_LANGUAGE')
		);

	}

	/**
	 * Persist a new language or re-render the form with validation errors.
	 */
	public function addAction(): ?PageResponse {

		$language = new Language();
		$language->populateByRequest();

		if ($language->store()) {
			$this->toast($this->translate('LANGUAGE_HAS_BEEN_CREATED'));
			$this->redirect('languages');
		}

		$this->toastError($this->buildErrorsMessage($language, 'LANGUAGE_HAS_NOT_BEEN_CREATED'));
		$this->pageHeading($this->translate('NEW_LANGUAGE'));

		return $this->page(
			'new',
			new LanguagesNewPageState($this->buildLanguageForm($language)),
			$this->translate('NEW_LANGUAGE')
		);

	}

	/**
	 * Render the edit-language form with explicit typed state.
	 */
	public function editAction(): PageResponse {

		$language = $this->loadLanguageFromRoute();
		$this->pageHeading($this->translate('EDIT_LANGUAGE'));

		return $this->buildEditPage($language);

	}

	/**
	 * Persist an edited language or re-render the edit page with validation errors.
	 */
	public function changeAction(): ?PageResponse {

		$language = new Language((int)$this->input()->int('id', 0));
		$language->populateByRequest();

		if ($language->store()) {
			$this->toast($this->translate('LANGUAGE_HAS_BEEN_CHANGED_SUCCESFULLY'));
			$this->redirect('languages');
		}

		$this->toastError($this->buildErrorsMessage($language, 'ERROR_ON_LAST_REQUEST'));
		$this->pageHeading($this->translate('EDIT_LANGUAGE'));

		return $this->buildEditPage($language);

	}

	/**
	 * Delete a language or re-render the edit page with the deletion error.
	 */
	public function deleteAction(): ?PageResponse {

		$language = $this->loadLanguageFromRoute();

		if ($language->delete()) {
			$this->toast($this->translate('LANGUAGE_HAS_BEEN_DELETED_SUCCESFULLY'));
			$this->redirect('languages');
		}

		$this->toastError($this->buildErrorsMessage($language, 'ERROR_DELETING_LANGUAGE'));
		$this->pageHeading($this->translate('EDIT_LANGUAGE'));

		return $this->buildEditPage($language);

	}

	/**
	 * Build the explicit list page state from the legacy model helpers.
	 */
	private function buildDefaultPageState(): LanguagesDefaultPageState {

		$pagination = new Pagination();
		$pagination->page = $this->router->getPage();

		// The legacy model still expects Pagination to be injected before loading rows.
		$this->model->pagination = $pagination;

		$languages = $this->model->getLanguages();
		$pagination->count = $this->model->countListItems();
		$rows = [];

		foreach ($languages as $language) {
			$rows[] = new LanguagesListItemState(
				(int)$language->id,
				(string)$language->englishName,
				(string)$language->nativeName,
				(string)$language->code,
				(string)($language->defaultCountry ?? ''),
				(int)($language->localeCount ?? 0)
			);
		}

		return new LanguagesDefaultPageState($rows, $pagination->render());

	}

	/**
	 * Build the edit page response from a loaded language.
	 */
	private function buildEditPage(Language $language): PageResponse {

		return $this->page(
			'edit',
			new LanguagesEditPageState(
				$this->buildLanguageForm($language),
				(int)$language->id,
				$language->isDeletable()
			),
			$this->translate('EDIT_LANGUAGE')
		);

	}

	/**
	 * Build the create/edit form and optionally preload it with a language.
	 */
	private function buildLanguageForm(?Language $language = null): Form {

		$form = $this->model->getLanguageForm();

		if (!is_null($language) and $language->isLoaded()) {
			$form->values($language);
		}

		return $form;

	}

	/**
	 * Load the requested language id from the router and ensure it exists.
	 */
	private function loadLanguageFromRoute(): Language {

		$languageId = (int)(Router::get(0) ?? 0);

		if ($languageId < 1) {
			throw new AppException($this->translate('NO_ID_OF_ITEM_TO_EDIT', 'Pair\Models\Language'));
		}

		$language = new Language($languageId);

		if (!$language->isLoaded()) {
			throw new AppException($this->translate('ID_OF_ITEM_TO_EDIT_IS_NOT_VALID', 'Pair\Models\Language'));
		}

		return $language;

	}

	/**
	 * Build the toast message for a failed store or delete operation.
	 */
	private function buildErrorsMessage(Language $language, string $fallbackKey): string {

		$errors = $language->getErrors();

		if (count($errors)) {
			return $this->translate($fallbackKey) . ": \n" . implode(" \n", $errors);
		}

		return $this->translate('ERROR_ON_LAST_REQUEST');

	}

	/**
	 * Translate a language key inside the languages module.
	 */
	private function translate(string $key, string|array|null $vars = null): string {

		return Translator::do($key, $vars);

	}

}
