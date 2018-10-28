<?php

use Pair\Database;
use Pair\Form;
use Pair\Locale;
use Pair\Model;

class LocalesModel extends Model {

	/**
	 * Returns object list with pagination.
	 *
	 * @return	array:Locale
	 */
	public function getLocales() {

		$alphaFilter = $this->app->getPersistentState('localesAlphaFilter');
		
		if ($alphaFilter) {
			
			// get a filtered list
			$where  = ' WHERE la.code LIKE ?';
			$params = [$alphaFilter . '%'];
			
		} else {
			
			// get all
			$where  = '';
			$params = [];
			
		}

		$query =
			'SELECT lo.*, la.english_name AS language_name, co.english_name AS country_name, CONCAT(la.code, "-", co.code) AS representation' .
			' FROM `locales` AS lo' .
			' INNER JOIN languages AS la ON lo.language_id = la.id' . 
			' INNER JOIN countries AS co ON lo.country_id = co.id' . 
			$where .
			' ORDER BY la.code, co.code' .
			' LIMIT ' . $this->pagination->start . ', ' . $this->pagination->limit;

		return Locale::getObjectsByQuery($query, $params);

	}

	/**
	 * Returns count of available objects.
	 *
	 * @return	int
	 */
	public function countListItems() {

		$alphaFilter = $this->app->getPersistentState('localesAlphaFilter');
		
		if ($alphaFilter) {
			
			// get a filtered list
			$query =
				'SELECT COUNT(1)' .
				' FROM locales AS lo' .
				' INNER JOIN languages AS la ON lo.language_id = la.id' . 
				' WHERE la.code LIKE ?';
			return Database::load($query, $alphaFilter . '%', 'count');
			
		} else {
			
			// get all
			return Locale::countAllObjects();

		}

	}

	/**
	 * Returns the Form object for create/edit Locale objects.
	 * 
	 * @return Form
	 */ 
	public function getLocaleForm() {

		$language = Pair\Language::getAllObjects(NULL, 'englishName');
		$country = Pair\Country::getAllObjects(NULL, 'englishName');
		$form = new Form();
			
		$form->addControlClass('form-control');
			
		$form->addInput('id')->setType('hidden');
		$form->addSelect('languageId')->setListByObjectArray($language, 'id', 'englishName');
		$form->addSelect('countryId')->setListByObjectArray($country, 'id', 'englishName');
		$form->addInput('officialLanguage')->setType('bool');
		$form->addInput('defaultCountry')->setType('bool');
		$form->addInput('appDefault')->setType('bool');
		
		return $form;
		
	}
				
}