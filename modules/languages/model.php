<?php

use Pair\Html\Form;
use Pair\Core\Model;
use Pair\Models\Language;
use Pair\Orm\Database;

class LanguagesModel extends Model {

	/**
	 * Returns object list with pagination.
	 *
	 * @return	array:Language
	 */
	public function getLanguages() {

		$alphaFilter = $this->app->getPersistentState('languagesAlphaFilter');
		
		if ($alphaFilter) {
			
			// get a filtered list
			$where  = ' WHERE la.english_name LIKE ?';
			$params = [$alphaFilter . '%'];
			
		} else {
			
			// get all
			$where  = '';
			$params = [];
			
		}

		$query =
			'SELECT la.*, IFNULL(c.english_name, NULL) AS default_country,' .
			' (SELECT COUNT(1) FROM locales WHERE language_id = la.id) AS locale_count' .
			' FROM ' . Language::TABLE_NAME . ' AS la' .
			' LEFT JOIN locales AS lo ON (lo.language_id = la.id AND lo.default_country = 1)' .
			' LEFT JOIN countries AS c ON lo.country_id = c.id' .
			$where .
			' ORDER BY la.english_name' .
			' LIMIT ' . $this->pagination->start . ', ' . $this->pagination->limit;

		return Language::getObjectsByQuery($query, $params);

	}

	/**
	 * Returns count of available objects.
	 *
	 * @return	int
	 */
	public function countListItems() {

		$alphaFilter = $this->app->getPersistentState('languagesAlphaFilter');
		
		if ($alphaFilter) {
			
			// get a filtered list
			$query = 'SELECT COUNT(1) FROM languages WHERE english_name LIKE ?';
			return Database::load($query, [$alphaFilter . '%'], PAIR_DB_COUNT);
			
		} else {
			
			// get all
			return Language::countAllObjects();

		}

	}

	/**
	 * Returns the Form object for create/edit Language objects.
	 * 
	 * @return Form
	 */ 
	public function getLanguageForm() {

		$form = new Form();
			
		$form->classForControls('form-control');
			
		$form->hidden('id');
		$form->text('code')->required()->maxLength(7);
		$form->text('nativeName')->maxLength(30);
		$form->text('englishName')->maxLength(30)->required();
		
		return $form;
		
	}
				
}