<?php

use Pair\Form;
use Pair\Model;
use Pair\Token;

class TokensModel extends Model {

	/**
	 * Returns object list with pagination.
	 *
	 * @return	array:Token
	 */
	public function getTokens() {

		$query = 'SELECT * FROM `tokens` LIMIT ' . $this->pagination->start . ', ' . $this->pagination->limit;

		return Token::getObjectsByQuery($query);

	}

	/**
	 * Returns count of available objects.
	 *
	 * @return	int
	 */
	public function countListItems() {

		return Token::countAllObjects();

	}

	/**
	 * Returns the Form object for create/edit Token objects.
	 * 
	 * @return Form
	 */ 
	public function getTokenForm() {

		$form = new Form();
			
		$form->addControlClass('form-control');
			
		$form->addInput('id')->setType('hidden')->setRequired()->setLabel('ID');
		$form->addInput('code')->setMaxLength(10)->setRequired()->setLabel('CODE');
		$form->addInput('description')->setMaxLength(100)->setRequired()->setLabel('DESCRIPTION');
		$form->addInput('value')->setMaxLength(64)->setRequired()->setLabel('VALUE');
		$form->addInput('token')->setMaxLength(64)->setRequired()->setLabel('TOKEN');
		$form->addInput('enabled')->setType('bool')->addClass('switchery')->setLabel('ENABLED');
		
		return $form;
		
	}
				
}