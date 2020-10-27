<?php

use Pair\Audit;
use Pair\Application;
use Pair\Controller;
use Pair\Input;
use Pair\User;

class UserController extends Controller {
	
	public function defaultAction() {
		
		$this->view = 'login';
		
	}
	
	/**
	 * Shows login form or try login action.
	 */
	public function loginAction() {
	
		$username	= Input::getTrim('username');
		$password	= Input::getTrim('password');
		$timezone	= Input::getTrim('timezone');
		$remember	= Input::getBool('remember');

		// if isn’t post submit, render the page
		if (!Input::formPostSubmitted()) {
			return;
		}
			
		// username or password missing
		if (!$username or !$password) {
			$this->enqueueError($this->lang('AUTHENTICATION_REQUIRES_USERNAME_AND_PASSWORD'));
			return;
		}
		
		// perform the login and create an user session
		$result = PgUser::doLogin($username, $password, $timezone);
		
		// login denied
		if ($result->error) {
			sleep(3);
			$this->enqueueError($result->message);
			$this->app->redirect('user/login');
		}
		
		// userId of user that is ready logged in
		$user = new PgUser($result->userId);
		
		// evaluate the remember-me checkbox value
		if ($remember) {
			$user->createRememberMe($timezone);
		}

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

	}

	/**
	 * Do the logout action.
	 */
	public function logoutAction() {

		$formerUser = Session::current()->getFormerUser();

		PgUser::doLogout(session_id());

		if ($formerUser) {
			PgUser::loginAs($formerUser, '');
			$formerUser->redirectToDefault();
		}
		
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
		$user->name		= Input::getTrim('name');
		$user->surname	= Input::getTrim('surname');
		$user->email	= Input::getTrim('email') ? Input::getTrim('email') : NULL;
		$user->username	= Input::getTrim('username');
		$user->localeId	= Input::getInt('localeId');
		
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