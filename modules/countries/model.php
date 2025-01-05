<?php

use Pair\Core\Model;
use Pair\Html\Form;
use Pair\Models\Country;
use Pair\Orm\Collection;
use Pair\Orm\Database;

class CountriesModel extends Model {

	/**
	 * Returns object list with pagination.
	 * @return Country[]
	 */
	public function getCountries(): Collection {

		$alphaFilter = $this->app->getPersistentState('countriesAlphaFilter');

		if ($alphaFilter) {

			// get a filtered list
			$where  = ' WHERE english_name LIKE ?';
			$params = [$alphaFilter . '%'];

		} else {

			// get all
			$where  = '';
			$params = [];

		}

		$query =
			'SELECT *
			FROM `countries`
			' . $where . '
			ORDER BY `english_name`
			LIMIT ' . $this->pagination->start . ', ' . $this->pagination->limit;

		return Country::getObjectsByQuery($query, $params);

	}

	/**
	 * Returns count of available objects.
	 */
	public function countListItems(): int {

		$alphaFilter = $this->app->getPersistentState('countriesAlphaFilter');

		if ($alphaFilter) {

			// get a filtered list
			$query = 'SELECT COUNT(1) FROM countries WHERE english_name LIKE ?';
			return Database::load($query, [$alphaFilter . '%'], Database::COUNT);

		} else {

			// get all
			return Country::countAllObjects();

		}

	}

	/**
	 * Returns the list of official languages for a Country.
	 * @return string[]
	 */
	public function getOfficialLanguages(Country $country): array {

		$query =
			'SELECT `english_name`
			FROM `languages` AS la
			INNER JOIN `locales` AS lo ON la.`id` = lo.`language_id`
			WHERE lo.`country_id` = ?
			AND lo.`official_language` = 1
			ORDER BY `english_name`';

		return Database::load($query, [$country->id], Database::RESULT_LIST);

	}

	/**
	 * Returns the Form object for create/edit Country objects.
	 */
	public function getCountryForm(): Form {

		$form = new Form();

		$form->classForControls('form-control');

		$form->hidden('id');
		$form->text('code')->required()->maxLength(3);
		$form->text('nativeName')->required()->maxLength(100);
		$form->text('englishName')->required()->maxLength(100);

		return $form;

	}

}