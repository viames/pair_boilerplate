<?php

use Pair\Application;
use Pair\Controller;
use Pair\Input;
use Pair\User;

class UserController extends Controller {
	
	public function defaultAction() {
		
		$this->view = 'login';
		
	}
	
	/**
	 * Shows login form or try login action based on the AUTH_SOURCE config param.
	 */
	public function loginAction() {
	
		// TODO implement internal security token_check

		$username	= Input::get('username');
		$password	= Input::get('password');
		$timezone	= Input::get('timezone');

		if (Input::formPostSubmitted()) {
			
			// found both username and password
			if ($username and $password) {

				$result = User::doLogin($username, $password, $timezone);
				
				// login success
				if (!$result->error) {

					// userId of user that is ready logged in
					$user = new User($result->userId);

					// get last requested page
					$page = $this->app->getPersistentState('lastRequestedUrl');
					$this->app->unsetPersistentState('lastRequestedUrl');

					// even goes to last page
					if ($page) {
						$this->app->redirect($page);
					}

					// get user default landing page
					$landing = $user->getLanding();

					// goes to default landing page
					$this->app->redirect($landing->module . '/' . $landing->action);

				// login denied
				} else {
					
					sleep(3);
					$this->enqueueError($result->message);
					$this->app->redirect('user/login');
					
				}
			
			// username or password missing
			} else {
				
				$this->enqueueError($this->lang('AUTHENTICATION_REQUIRES_USERNAME_AND_PASSWORD'));
				return FALSE;
				
			}
			
		}

	}

	/**
	 * Do the logout action.
	 */
	public function logoutAction() {

		$app = Application::getInstance();
		
		User::doLogout(session_id());
		
		// manual redirect because of variables clean-up
		header('Location: ' . BASE_HREF . 'login');
		exit();
		
	}

	/**
	 * Do the user profile change.
	 */
	public function profileChangeAction() {
	
		$form = $this->model->getUserForm();
		
		$user			= new User($this->app->currentUser->id);
		$user->name		= Input::get('name');
		$user->surname	= Input::get('surname');
		$user->email	= Input::get('email') ? Input::get('email') : NULL;
		$user->ldapUser	= Input::get('ldapUser') ? Input::get('ldapUser') : NULL;
		$user->username	= Input::get('username');
		$user->localeId	= Input::get('localeId', 'int');
		
		if (Input::get('password')) {
			$user->hash = User::getHashedPasswordWithSalt(Input::get('password'));
		}

		$res = $user->store();

		// we notice just if user changes really
		if (!$form->isValid()) {
			$this->enqueueError($this->lang('ERROR_FORM_IS_NOT_VALID'));
		} else {
			if ($res) {
				$this->enqueueMessage($this->lang('YOUR_PROFILE_HAS_BEEN_CHANGED'));
			} else {
				$errors = $user->getErrors();
				$msg = $this->lang('ERROR_ON_LAST_REQUEST') . (count($errors) ? ':' . implode(" \n", $errors) : '');
				$this->enqueueError($msg);
			}
		}
		
		$this->app->redirect('user/profile');
	
	}

}