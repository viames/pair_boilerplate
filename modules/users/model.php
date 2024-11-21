<?php

use Pair\Core\Application;
use Pair\Core\Model;
use Pair\Html\Form;
use Pair\Models\Acl;
use Pair\Models\Group;
use Pair\Models\Locale;
use Pair\Models\Rule;
use Pair\Models\User;
use Pair\Orm\Collection;
use Pair\Support\Options;
use Pair\Support\Translator;

class UsersModel extends Model {

	/**
	 * Returns User objects of an instance found into DB.
	 */
	public function getUsers(): Collection {
	
		$query =
			'SELECT u.*, g.name AS group_name
			FROM `users` AS u
			INNER JOIN `groups` AS g ON u.group_id = g.id
			ORDER BY u.name ASC
			LIMIT ' . $this->pagination->start . ', ' . $this->pagination->limit;
		
		return User::getObjectsByQuery($query);

	}

	/**
	 * Returns array of Group objects of an instance with userCount for Users that belongs to.
	 */
	public function getGroups(): Collection {

		$query = 
			'SELECT g.*, m.name AS module_name,
			(SELECT COUNT(*) FROM `users` WHERE group_id = g.id) AS user_count,
			(SELECT COUNT(*) FROM `acl` WHERE group_id = g.id) AS acl_count
			FROM `groups` AS g
			LEFT JOIN `acl` AS a ON (g.id = a.group_id AND a.is_default=1)
			LEFT JOIN `rules` AS r ON r.id = a.rule_id
			LEFT JOIN `modules` AS m ON m.id = r.module_id
			ORDER BY g.name';
		
		return Group::getObjectsByQuery($query);

	}
	
	/**
	 * Returns all access list of a group by its ID.
	 *
	 * @param	int		Group ID.
	 */
	public function getAcl(int $groupId): Collection {
	
		$query =
			'SELECT a.*, r.action, m.name AS module_name,
			CONCAT_WS(" ", m.name, r.action) AS module_action
			FROM `acl` AS a
			INNER JOIN `rules` AS r ON a.rule_id = r.id
			INNER JOIN `modules` AS m ON r.module_id = m.id
			WHERE a.group_id = ?
			ORDER BY m.name ASC, r.action ASC';
	
		return Acl::getObjectsByQuery($query, [$groupId]);

	}
	
	/**
	 * Returns all rules.
	 */
	public function getRules(): Collection {
	
		$query =
			'SELECT r.*, m.name AS module_name,' .
			' CONCAT_WS(" ", m.name, r.action) AS module_action' .
			' FROM `rules` AS r' .
			' INNER JOIN `modules` AS m ON r.module_id = m.id' .
			' WHERE admin_only = 0' .
			' ORDER BY m.name ASC, r.action ASC';
	
		return Rule::getObjectsByQuery($query);
	
	}

	/**
	 * Returns the Form object for create/edit User objects.
	 */
	public function getUserForm(): Form {

		$minLength = Options::get('password_min');

		// lists for select
		$groups	= Group::getAllObjects(NULL, 'name');
		
		$locales = Locale::getExistentTranslations(FALSE);
		
		$form = new Form();
		$form->classForControls('form-control');
		
		$form->hidden('id');
		$form->text('name')->required()->minLength(2)->label('NAME');
		$form->text('surname')->required()->minLength(2)->label('SURNAME');
		$form->email('email')->required()->label('EMAIL');
		$form->checkbox('enabled')->label('ENABLED');
		$form->text('username', array('autocomplete'=>'off'))->required()->minLength(3)
			->label('USERNAME');
		$form->password('password', array('autocomplete'=>'off', 'autocorrect'=>'off'))
			->minLength($minLength)->class('pwstrength')->label('PASSWORD');
		$form->checkbox('showPassword');
		$form->select('groupId')->options($groups,'id','name')->required()->label('GROUP');
		$form->select('localeId')->options($locales,'id','languageCountry')->required()->label('LANGUAGE');

		return $form;

	}

	/**
	 * Returns the Form object for create/edit Group objects.
	 */
	public function getGroupForm(): Form {

		$form = new Form();
		$form->classForControls('form-control');

		$form->hidden('id');
		$form->text('name')->required()->minLength(3);
		$form->checkbox('default');
		$form->select('defaultAclId')->required();

		return $form;

	}

	/**
	 * Returns the Form object for create/edit Acl objects.
	 */
	public function getAclListForm(): Form {

		$form = new Form();
		$form->classForControls('form-control');
		
		$form->hidden('groupId');
		
		return $form;

	}
	
}
