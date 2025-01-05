<?php

use Pair\Core\Model;
use Pair\Html\Form;
use Pair\Models\Module;
use Pair\Orm\Database;

class RulesModel extends Model {

	/**
	 * Returns the modules
	 */
	public function getAclModelRules(): array {

		$query = 'SELECT r.*, m.`name`
			FROM `rules` as r
			INNER JOIN `modules` as m ON m.`id` = r.`module_id`
			ORDER BY `name` ASC
			LIMIT ' . $this->pagination->start . ', ' . $this->pagination->limit;

		return Database::load($query, [], Database::OBJECT);

	}

	/**
	 * Returns records count.
	 */
	public function countModules() {

		$query = 'SELECT COUNT(*) FROM `rules` as r INNER JOIN `modules` as m ON m.`id` = r.`module_id`';

		return (int)Database::load($query, [], Database::COUNT);

	}

	/**
	 * Returns the Form object for create/edit Rules objects.
	 * 
	 * @return Form
	 */ 
	public function getRulesForm() {
		
		$modules = Module::getAllObjects(NULL, array('name'));
		
		$form = new Form();
		$form->classForControls('form-control');
			
		$form->hidden('id');
		$form->select('moduleId')->options($modules, 'id', 'name')->required();
		$form->text('actionField');
		$form->checkbox('adminOnly')->class('switchery');
		
		return $form;
		
	}
				
}