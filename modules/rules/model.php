<?php

use Pair\Html\Form;
use Pair\Core\Model;
use Pair\Models\Module;

class RulesModel extends Model {

	/**
	 * Returns the modules
	 *
	 * @return array
	 */
	public function getAclModelRules() {

		$query =
			' SELECT r.*, m.name '.
			' FROM rules as r '.
			' INNER JOIN modules as m ON m.id = r.module_id '.
			' ORDER BY name ASC ' .
			' LIMIT ' . $this->pagination->start . ', ' . $this->pagination->limit;

		$this->db->setQuery($query);
		$modules = $this->db->loadObjectList();

		return $modules;

	}

	/**
	 * Returns records count.
	 *
	 * @return	int
	 */
	public function countModules() {

		$query =
			' SELECT COUNT(*) '.
			' FROM rules as r '.
			' INNER JOIN modules as m ON m.id = r.module_id ';

		$this->db->setQuery($query);
		return (int)$this->db->loadResult();

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