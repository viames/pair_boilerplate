<?php

use Pair\Form;
use Pair\Model;

class TemplatesModel extends Model {
	
	public function getTemplateForm() {

		$accept = 'application/x-compressed,application/x-zip-compressed,application/zip,multipart/x-zip';
	
		$form = new Form();
		$form->addControlClass('form-control');
		$form->addInput('package')->setType('file')->setAccept($accept).setRequired();
		return $form;
	
	}
	
}
