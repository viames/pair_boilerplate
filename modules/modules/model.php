<?php

use Pair\Form;
use Pair\Model;

class ModulesModel extends Model {
	
	public function getModuleForm() {
	
		$form = new Form();
		$form->addControlClass('form-control');
		$form->addInput('package')->setType('file')->setRequired();
		return $form;
	
	}
	
}
