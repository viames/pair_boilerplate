<?php

use Pair\Form;
use Pair\Locale;
use Pair\Model;
use Pair\Options;
use Pair\Translator;

class UserModel extends Model {

	/**
	 * Returns login form.
	 * 
	 * @return Form
	 */
	public function getLoginForm() {
		
		$tran = Translator::getInstance();
		$form = new Form();
		
		$form->addControlClass('form-control');
		
		$form->addInput('username', array('autocorrect'=>'off', 'autocapitalize'=>'off'))
			->setRequired()->setMinLength(3)->setPlaceholder($tran->get('USERNAME'))
			->setLabel('USERNAME');
		$form->addInput('password', array('autocorrect'=>'off', 'autocapitalize'=>'off'))
			->setType('password')->setRequired()->setPlaceholder($tran->get('PASSWORD'))
			->setLabel('PASSWORD');
		$form->addInput('timezone')->setType('hidden');
		
		return $form;
		
	}
	
	/**
	 * Returns the Form object for create/edit Users objects.
	 */
	public function getUserForm(): Form {

		$minLength = Options::get('password_min');

		$form = new Form();
		
		$form->addControlClass('form-control');
		
		$locales = Locale::getExistentTranslations();

		$form->addInput('name')->setRequired()->setMinLength(2);
		$form->addInput('surname')->setRequired()->setMinLength(2);
		$form->addInput('email')->setType('email');
		$form->addInput('username')->setRequired()->setMinLength(3)->setPlaceholder('Username');
		$form->addInput('password', array('autocomplete'=>'off', 'autocorrect'=>'off', 'autocapitalize'=>'off'))
			->setType('password')->setMinLength($minLength)->addClass('pwstrength');
		$form->addInput('showPassword')->setType('bool');
		$form->addSelect('localeId')->setListByObjectArray->setRequired()($locales,'id','languageCountry');

		return $form;

	}
}
