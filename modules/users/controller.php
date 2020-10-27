<?php

use Pair\Acl;
use Pair\Audit;
use Pair\Controller;
use Pair\Group;
use Pair\Input;
use Pair\Options;
use Pair\Router;
use Pair\User;

class UsersController extends Controller {

	/**
	 * Run userList action.
	 */
	public function defaultAction() {
		
		$this->view = 'userList';
		
		$this->userListAction();

	}
	
	public function userAddAction() {
		
		// fallback view
		$this->view = 'userList';
		
		$userField = PAIR_AUTH_BY_EMAIL ? 'email' : 'username';
		
		$username = strtolower(Input::getTrim($userField));
		$password = Input::getTrim('password');
		
		// check on minimum password length
		if (strlen($password) < Options::get('password_min')) {
			$this->enqueueError($this->lang('SHORT_PASSWORD'));
			$this->app->redirect('users/userList');
		}

		// check if username exist
		if (count(User::getAllObjects(array($userField => $username)))) {
			$this->enqueueError($this->lang('USER_EXIST', $username));
			$this->app->redirect('users/userList');
		}

		$form = $this->model->getUserForm();

		$newUser			= new User();
		$newUser->name		= Input::getTrim('name');
		$newUser->surname	= Input::getTrim('surname');
		$newUser->email	= Input::getTrim('email') ? Input::getTrim('email') : NULL;
		$newUser->username	= $username;
		$newUser->enabled	= Input::getBool('enabled');
		$newUser->localeId	= Input::getInt('localeId');
		$newUser->groupId	= Input::getInt('groupId');
		$newUser->admin	= FALSE;
		$newUser->faults	= 0;

		// create hash
		$newUser->hash = User::getHashedPasswordWithSalt($password);

		if ($form->isValid() and $newUser->create()) {
			$this->enqueueMessage($this->lang('USER_HAS_BEEN_CREATED', $newUser->fullName));
			$this->app->redirect('users/userList');
		} else {
			$this->enqueueError($this->lang('USER_HAS_NOT_BEEN_CREATED', $newUser->fullName));
			foreach ($newUser->getErrors() as $error) {
				$this->enqueueError($error);
			}
			$this->view = 'userList';
		}

	}
	
	public function userEditAction() {
	
		$user = $this->getRequestedUser();
	
		if (is_a($user, 'Pair\User') and $user->isLoaded()) {
			$this->view = 'userEdit';
		} else {
			$this->view = 'userList';
		}
	
	}

	/**
	 * Do the user change.
	 */
	public function userChangeAction() {
	
		// fall-back
		$this->view = 'userList';
	
		$user	= new User(Input::getInt('id'));

		// snapshot for Audit
		$oldUser = clone $user;
		
		// controllo validità del form
		if (!$this->model->getUserForm()->isValid()) {
			$this->enqueueError($this->lang('USER_HAS_NOT_BEEN_CHANGED', $user->fullName));
			return;
		}
		
		// controllo validità utente e gruppo
		if (!$user->isLoaded()) {
			$this->enqueueError($this->lang('USER_HAS_NOT_BEEN_CHANGED', $user->fullName));
			return;
		}
		
		// limit changes to non-admin users 
		if (!$this->app->currentUser->admin and $user->admin) {
			$this->enqueueError($this->lang('USER_HAS_NOT_BEEN_CHANGED', $user->fullName));
			return;
		}
		
		$userField = PAIR_AUTH_BY_EMAIL ? 'email' : 'username';
		
		$username = Input::getTrim($userField);
		$password = Input::getTrim('password');
		
		// check on password length
		if (strlen($password) > 0 and strlen($password) < Options::get('password_min')) {
			$this->enqueueError($this->lang('SHORT_PASSWORD'));
			$this->app->redirect('users/userList');
		}

		$user->name		= Input::getTrim('name');
		$user->surname	= Input::getTrim('surname');
		$user->email	= Input::getTrim('email') ? Input::getTrim('email') : NULL;
		$user->username	= $username;
		$user->enabled	= Input::getBool('enabled');
		$user->localeId	= Input::getInt('localeId');

		// if there’s a new password, set it
		if ($password) {
			$user->hash = User::getHashedPasswordWithSalt($password);
		}
		
		if (!$user->store()) {
			$this->raiseError($user);
			return;
		}

		// track the user edit
		Audit::userChanged($oldUser, $user);

		// track user password change
		if ($password) {
			Audit::passwordChanged($user);
		}

		$this->enqueueMessage($this->lang('USER_HAS_BEEN_CHANGED', $user->fullName));
		$this->app->redirect('users');
	
	}

	/**
	 * Do the user deletion.
	 */
	public function userDeleteAction() {

		$user	= new User(Router::get(0));
		$group	= new Group($user->groupId);
		
		$fullName = $user->fullName;

		if (!$user->delete()) {
			$this->raiseError($user);
			return;
		}

		$this->enqueueMessage($this->lang('USER_HAS_BEEN_DELETED', $fullName));
		$this->app->redirect('users');

	}

	public function groupAddAction() {
		
		$form = $this->model->getGroupForm();
				
		$group				= new Group();
		$group->name		= Input::get('name');
		$group->default		= Input::get('default', 'bool');

		if ($form->isValid() and $group->create()) {
			$this->enqueueMessage($this->lang('GROUP_HAS_BEEN_CREATED',   $group->name));
		} else {
			$this->enqueueError($this->lang('GROUP_HAS_NOT_BEEN_CREATED', $group->name));
		}
		
		$this->app->redirect('groups');
		
	}
	
	/**
	 * Shows group-edit page.
	 */
	public function groupEditAction() {

		$group = $this->getRequestedGroup();

		$this->view = $group ? 'groupEdit' : 'groupList';

	}
	
	/**
	 * Performs changes on a group.
	 */
	public function groupChangeAction() {

		$group = new Group(Input::getInt('id'));
				
		$form = $this->model->getGroupForm();

		$group->name = Input::get('name');
				
		// if this group is default, it will stay
		$group->default = $group->default ? 1 : Input::getBool('default');

		if ($form->isValid() and $group->update(array('name', 'default'))) {

			// updates related acl to default
			$group->setDefaultAcl(Input::getInt('defaultAclId'));
			
			// notice only if group change
			$this->enqueueMessage($this->lang('GROUP_HAS_BEEN_CHANGED', $group->name));

			$this->app->redirect('groups');

		} else {
		
			// warn of possible errors
			foreach ($group->getErrors() as $error) {
				$this->enqueueError($error);
			}
			
			$this->view = 'groupList';
			
		}
		
	}
				
	/**
	 * Performs deletion on a group.
	 */
	public function groupDeleteAction() {

		$group = new Group(Router::get(0));

		if ($group->isDeletable()) {

			$groupName = $group->name;

			if ($group->delete()) {
				$this->enqueueMessage($this->lang('GROUP_HAS_BEEN_DELETED', $groupName));
			} else {
				$this->enqueueError($this->lang('GROUP_HAS_NOT_BEEN_DELETED', $groupName));
			}

		} else {

			$this->enqueueError($this->lang('GROUP_CAN_NOT_BEEN_DELETED', $group->name));

		}
		
		$this->app->redirect('groups');
		
	}

	public function aclAddAction() {
	
		$groupId	= Input::getInt('groupId');
		$group		= new Group($groupId);
				 
		foreach ($_POST['aclChecked'] as $c) {

			$acl			= new Acl();
			$acl->ruleId	= $c;
			$acl->groupId	= $group->id;

			$acl->create();
	
		}
	
		$this->enqueueMessage($this->lang('NEW_ACCESS_PERMISSION_HAS_BEEN_CREATED'));
		$this->redirect('users/aclList/' . $group->id);
	
	}
	
	/**
	 * Deletes an ACL upon a request coming by URL after a check on group ownership.
	 */
	public function aclDeleteAction() {
		
		$aclId	= Router::get(0);
		$acl	= new Acl($aclId);
		
		if ($acl->isLoaded()) {
			
			$moduleName	= $acl->getModuleName();
			$groupId	= $acl->groupId;

			if ($acl->delete()) {
				$this->enqueueMessage($this->lang('ACCESS_PERMISSION_HAS_BEEN_DELETED', $moduleName));
			} else {
				$this->enqueueError($this->lang('ACCESS_PERMISSION_HAS_NOT_BEEN_DELETED', $moduleName));
			}
						
		}

		$this->redirect('users/aclList/' . $groupId);
	
	}
	
	/**
	 * Private method to obtain User object to edit.
	 *
	 * @return User|NULL
	 */
	private function getRequestedUser() {
	
		$userId = Router::get('id');
	
		if (!$userId) {
			$this->enqueueError($this->lang('ITEM_TO_EDIT_IS_NOT_VALID'));
			return NULL;
		}
			
		$user = new User($userId);
		
		// valid user
		if ($user->isLoaded()) {

			return $user;

		// not loaded
		} else {
	
			$this->enqueueError($this->lang('ITEM_TO_EDIT_IS_NOT_VALID'));
			$this->logError('User id=' . $userId . ' has not been loaded');
			return NULL;
	
		}
	
	}

	/**
	 * Private method to obtain Group object to edit.
	 *
	 * @return Group|NULL
	 */
	private function getRequestedGroup() {

		$groupId = Router::get('id');

		if (!$groupId) {
			$this->enqueueError($this->lang('ITEM_TO_EDIT_IS_NOT_VALID'));
			return NULL;
		}

		$group = new Group($groupId);

		if ($group->isLoaded()) {
			
			return $group;
				
		// not loaded
		} else {

			$this->enqueueError($this->lang('ITEM_TO_EDIT_IS_NOT_VALID'));
			$this->logError('Group id=' . $groupId . ' has not been loaded');
			return NULL;

		}

	}

}