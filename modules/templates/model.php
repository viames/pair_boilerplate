<?php

use Pair\Core\Model;
use Pair\Html\Form;

class TemplatesModel extends Model {

	/**
	 * Build the upload form used to install a new template package.
	 */
	public function getTemplateForm(): Form {

		$form = new Form();
		$form->classForControls('form-control');
		$form->file('package')->required();

		return $form;

	}

	/**
	 * Build the edit form used to change template metadata.
	 */
	public function getTemplateEditForm(): Form {

		$form = new Form();
		$form->classForControls('form-control');

		$form->hidden('id');
		$form->text('name')->required()->maxLength(50)->label('NAME');
		$form->text('version')->required()->maxLength(10)->label('VERSION');
		$form->text('appVersion')->required()->maxLength(10)->label('APP_VERSION');

		return $form;

	}

}
