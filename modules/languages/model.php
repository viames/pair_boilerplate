<?php

use Pair\Form;
use Pair\Language;
use Pair\Model;

class LanguagesModel extends Model {

	/**
	 * Returns object list with pagination.
	 *
	 * @return	array:Language
	 */
	public function getLanguagescopy() {

		$query = 'SELECT * FROM ' . Language::TABLE_NAME . ' LIMIT ' . $this->pagination->start . ', ' . $this->pagination->limit;

		return Language::getObjectsByQuery($query);

	}

	/**
	 * Returns count of available objects.
	 *
	 * @return	int
	 */
	public function countLanguagescopy() {

		return Language::countAllObjects();

	}

	/**
	 * Returns the Form object for create/edit Language objects.
	 * 
	 * @return Form
	 */ 
	public function getLanguageForm() {

		$form = new Form();
			
		$form->addControlClass('form-control');
			
		$form->addInput('id')->setType('hidden');
		$form->addInput('code')->setMaxLength(7)->setRequired();
		$form->addInput('nativeName')->setMaxLength(30);
		$form->addInput('englishName')->setMaxLength(30)->setRequired();
		
		return $form;
		
	}
				
}