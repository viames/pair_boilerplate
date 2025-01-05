<?php

use Pair\Html\Form;
use Pair\Core\Model;

class TemplatesModel extends Model {
	
	public function getTemplateForm() {

		$form = new Form();
		$form->classForControls('form-control');
		$form->file('package')->required();
		return $form;
	
	}
	
}
