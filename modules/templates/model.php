<?php

use Pair\Html\Form;
use Pair\Core\Model;

class TemplatesModel extends Model {
	
	public function getTemplateForm() {

		$accept = 'application/x-compressed,application/x-zip-compressed,application/zip,multipart/x-zip';
	
		$form = new Form();
		$form->classForControls('form-control');
		$form->file('package')->accept($accept)->required();
		return $form;
	
	}
	
}
