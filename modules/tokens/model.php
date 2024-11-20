<?php

use Pair\Html\Form;
use Pair\Core\Model;
use Pair\Models\Token;

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
			
		$form->classForControls('form-control');
			
		$form->hidden('id')->required()->label('ID');
		$form->text('code')->maxLength(10)->required()->label('CODE');
		$form->text('description')->maxLength(100)->required()->label('DESCRIPTION');
		$form->textarea('details')->maxLength(1000)->label('DETAILS');
		$form->text('value')->maxLength(64)->required()->label('VALUE');
		$form->text('token')->maxLength(64)->required()->label('TOKEN');
		$form->checkbox('enabled')->class('switchery')->label('ENABLED');
		
		return $form;
		
	}
				
}