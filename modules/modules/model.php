<?php

use Pair\Html\Form;
use Pair\Core\Model;

class ModulesModel extends Model {
	
	public function getModuleForm() {
	
		$form = new Form();
		$form->classForControls('form-control');
		$form->file('package')->required();
		return $form;
	
	}
	
}
