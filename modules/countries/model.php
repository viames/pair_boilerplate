<?php

use Pair\Country;
use Pair\Database;
use Pair\Form;
use Pair\Model;

class CountriesModel extends Model {

	/**
	 * Returns object list with pagination.
	 *
	 * @return	array:Country
	 */
	public function getCountries() {
		
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
			'SELECT *' .
			' FROM `countries`' .
			$where .
			' ORDER BY english_name' .
			' LIMIT ' . $this->pagination->start . ', ' . $this->pagination->limit;

		return Country::getObjectsByQuery($query, $params);

	}

	/**
	 * Returns count of available objects.
	 *
	 * @return	int
	 */
	public function countListItems() {
		
		$alphaFilter = $this->app->getPersistentState('countriesAlphaFilter');
		
		if ($alphaFilter) {
			
			// get a filtered list
			$query = 'SELECT COUNT(1) FROM countries WHERE english_name LIKE ?';
			return Database::load($query, $alphaFilter . '%', 'count');
			
		} else {
			
			// get all
			return Country::countAllObjects();
			
		}

	}
	
	public function getOfficialLanguages(Country $country) {
		
		$query =
			'SELECT english_name' .
			' FROM languages AS la' .
			' INNER JOIN locales AS lo ON la.id = lo.language_id' .
			' WHERE lo.country_id = ?' .
			' AND lo.official_language = 1' .
			' ORDER BY english_name';
		
		return Database::load($query, $country->id, 'resultlist');
		
	}

	/**
	 * Returns the Form object for create/edit Country objects.
	 * 
	 * @return Form
	 */ 
	public function getCountryForm() {

		$form = new Form();
			
		$form->addControlClass('form-control');
			
		$form->addInput('id')->setType('hidden');
		$form->addInput('code')->setRequired()->setMaxLength(3);
		$form->addInput('nativeName')->setRequired()->setMaxLength(100);
		$form->addInput('englishName')->setRequired()->setMaxLength(100);
		
		return $form;
		
	}
				
}