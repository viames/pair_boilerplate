<?php

use Pair\Country;
use Pair\Form;
use Pair\Model;

class CountriesModel extends Model {

	/**
	 * Returns object list with pagination.
	 *
	 * @return	array:Country
	 */
	public function getCountries() {

		$query =
			'SELECT *' .
			' FROM `countries`' .
			' ORDER BY english_name' .
			' LIMIT ' . $this->pagination->start . ', ' . $this->pagination->limit;

		return Country::getObjectsByQuery($query);

	}

	/**
	 * Returns count of available objects.
	 *
	 * @return	int
	 */
	public function countCountries() {

		return Country::countAllObjects();

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
		$form->addInput('nativeName')->setRequired();
		$form->addInput('englishName')->setRequired();
		
		return $form;
		
	}
				
}