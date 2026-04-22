<?php

declare(strict_types=1);

use Pair\Exceptions\AppException;
use Pair\Core\Router;
use Pair\Helpers\Translator;
use Pair\Html\Breadcrumb;
use Pair\Html\Form;
use Pair\Html\Pagination;
use Pair\Models\Country;
use Pair\Web\Controller;
use Pair\Web\PageResponse;

class CountriesController extends Controller {

	/**
	 * Countries module data helper.
	 */
	private CountriesModel $model;

	/**
	 * Prepare the explicit controller dependencies.
	 */
	protected function boot(): void {

		$this->model = new CountriesModel();
		Breadcrumb::path($this->translate('COUNTRIES'), 'countries');

	}

	/**
	 * Render the countries list with explicit typed state.
	 */
	public function defaultAction(): PageResponse {

		$requestedFilter = Router::get(0);

		if ($requestedFilter) {
			$this->setPersistentState('countriesAlphaFilter', $requestedFilter);
		} else {
			$this->unsetPersistentState('countriesAlphaFilter');
		}

		$this->pageHeading($this->translate('COUNTRIES'));

		return $this->page(
			'default',
			$this->buildDefaultPageState(),
			$this->translate('COUNTRIES')
		);

	}

	/**
	 * Render the new-country form with explicit typed state.
	 */
	public function newAction(): PageResponse {

		$this->pageHeading($this->translate('NEW_COUNTRY'));

		return $this->page(
			'new',
			new CountriesNewPageState($this->model->getCountryForm()),
			$this->translate('NEW_COUNTRY')
		);

	}

	/**
	 * Persist a new country or re-render the form with validation errors.
	 */
	public function addAction(): ?PageResponse {

		$country = new Country();
		$country->populateByRequest();

		if ($country->store()) {
			$this->toast($this->translate('COUNTRY_HAS_BEEN_CREATED'));
			$this->redirect('countries');
		}

		$this->toastError($this->buildErrorsMessage($country, 'COUNTRY_HAS_NOT_BEEN_CREATED'));
		$this->pageHeading($this->translate('NEW_COUNTRY'));

		return $this->page(
			'new',
			new CountriesNewPageState($this->buildCountryForm($country)),
			$this->translate('NEW_COUNTRY')
		);

	}

	/**
	 * Render the edit-country form with explicit typed state.
	 */
	public function editAction(): PageResponse {

		$country = $this->loadCountryFromRoute();
		$this->pageHeading($this->translate('EDIT_COUNTRY'));

		return $this->buildEditPage($country);

	}

	/**
	 * Persist an edited country or re-render the edit page with validation errors.
	 */
	public function changeAction(): ?PageResponse {

		$country = new Country((int)$this->input()->int('id', 0));
		$country->populateByRequest();

		if ($country->store()) {
			$this->toast($this->translate('COUNTRY_HAS_BEEN_CHANGED_SUCCESFULLY'));
			$this->redirect('countries');
		}

		$this->toastError($this->buildErrorsMessage($country, 'ERROR_ON_LAST_REQUEST'));
		$this->pageHeading($this->translate('EDIT_COUNTRY'));

		return $this->buildEditPage($country);

	}

	/**
	 * Delete a country or re-render the edit page with the deletion error.
	 */
	public function deleteAction(): ?PageResponse {

		$country = $this->loadCountryFromRoute();

		if ($country->delete()) {
			$this->toast($this->translate('COUNTRY_HAS_BEEN_DELETED_SUCCESFULLY'));
			$this->redirect('countries');
		}

		$this->toastError($this->buildErrorsMessage($country, 'ERROR_DELETING_COUNTRY'));
		$this->pageHeading($this->translate('EDIT_COUNTRY'));

		return $this->buildEditPage($country);

	}

	/**
	 * Build the explicit list page state from the legacy model helpers.
	 */
	private function buildDefaultPageState(): CountriesDefaultPageState {

		$pagination = new Pagination();
		$pagination->page = $this->router->getPage();

		// The legacy model still expects Pagination to be injected before loading rows.
		$this->model->pagination = $pagination;

		$countries = $this->model->getCountries();
		$pagination->count = $this->model->countListItems();
		$rows = [];

		foreach ($countries as $country) {
			$rows[] = new CountriesListItemState(
				(int)$country->id,
				(string)$country->englishName,
				(string)$country->nativeName,
				(string)$country->code,
				implode(', ', $this->model->getOfficialLanguages($country))
			);
		}

		return new CountriesDefaultPageState($rows, $pagination->render());

	}

	/**
	 * Build the edit page response from a loaded country.
	 */
	private function buildEditPage(Country $country): PageResponse {

		return $this->page(
			'edit',
			new CountriesEditPageState(
				$this->buildCountryForm($country),
				(int)$country->id,
				$country->isDeletable()
			),
			$this->translate('EDIT_COUNTRY')
		);

	}

	/**
	 * Build the create/edit form and optionally preload it with a country.
	 */
	private function buildCountryForm(?Country $country = null): Form {

		$form = $this->model->getCountryForm();

		if (!is_null($country) and $country->isLoaded()) {
			$form->values($country);
		}

		return $form;

	}

	/**
	 * Load the requested country id from the router and ensure it exists.
	 */
	private function loadCountryFromRoute(): Country {

		$countryId = (int)(Router::get(0) ?? 0);

		if ($countryId < 1) {
			throw new AppException($this->translate('NO_ID_OF_ITEM_TO_EDIT', 'Pair\Models\Country'));
		}

		$country = new Country($countryId);

		if (!$country->isLoaded()) {
			throw new AppException($this->translate('ID_OF_ITEM_TO_EDIT_IS_NOT_VALID', 'Pair\Models\Country'));
		}

		return $country;

	}

	/**
	 * Build the toast message for a failed store or delete operation.
	 */
	private function buildErrorsMessage(Country $country, string $fallbackKey): string {

		$errors = $country->getErrors();

		if (count($errors)) {
			return $this->translate($fallbackKey) . ": \n" . implode(" \n", $errors);
		}

		return $this->translate('ERROR_ON_LAST_REQUEST');

	}

	/**
	 * Translate a language key inside the countries module.
	 */
	private function translate(string $key, string|array|null $vars = null): string {

		return Translator::do($key, $vars);

	}

}
