<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Form;
use Pair\Model;

class TemplatesModel extends Model {
	
	public function getTemplateForm() {
	
		$form = new Form();
		$form->addControlClass('form-control');
		$form->addInput('package')->setType('file')->setRequired();
		return $form;
	
	}
	
}
