<?php

use Pair\Core\Model;
use Pair\Html\Form;
use Pair\Models\Locale;
use Pair\Helpers\Options;
use Pair\Helpers\Translator;

class UserModel extends Model {

	/**
	 * Returns login form.
	 */
	public function getLoginForm(): Form {
		
		$form = new Form();
		
		$form->classForControls('form-control');
		
		$form->text('username', array('autocorrect'=>'off', 'autocapitalize'=>'off'))
			->required()->minLength(3)->placeholder(Translator::do('USERNAME'))
			->label('USERNAME');
		$form->password('password', array('autocorrect'=>'off', 'autocapitalize'=>'off'))
			->required()->placeholder(Translator::do('PASSWORD'))
			->label('PASSWORD');
		$form->hidden('timezone');
		
		return $form;
		
	}
	
	/**
	 * Returns the Form object for create/edit Users objects.
	 */
	public function getUserForm(): Form {

		$minLength = Options::get('password_min');

		$form = new Form();
		
		$form->classForControls('form-control');
		
		$locales = Locale::getExistentTranslations();

		$form->text('name')->required()->minLength(2);
		$form->text('surname')->required()->minLength(2);
		$form->email('email');
		$form->text('username')->required()->minLength(3)->placeholder('Username');
		$form->password('password', array('autocomplete'=>'off', 'autocorrect'=>'off', 'autocapitalize'=>'off'))
			->minLength($minLength)->class('pwstrength');
		$form->checkbox('showPassword');
		$form->select('localeId')->options($locales,'id','languageCountry')->required();

		return $form;

	}
}
