<?php

use Pair\Core\Controller;
use Pair\Models\Session;
use Pair\Models\User;
use Pair\Support\Post;

class UserController extends Controller {
	
	public function defaultAction() {
		
		$this->view = 'login';
		
	}
	
	/**
	 * Shows login form or try login action.
	 */
	public function loginAction() {
	
		$username	= Post::trim('username');
		$password	= Post::trim('password');
		$timezone	= Post::trim('timezone');
		$remember	= Post::bool('remember');

		// if isn’t post submit, render the page
		if (!Post::submitted()) {
			return;
		}
			
		// username or password missing
		if (!$username or !$password) {
			$this->enqueueError($this->lang('AUTHENTICATION_REQUIRES_USERNAME_AND_PASSWORD'));
			return;
		}
		
		// perform the login and create an user session
		$result = User::doLogin($username, $password, $timezone);
		
		// login denied
		if ($result->error) {
			sleep(3);
			$this->enqueueError($result->message);
			$this->app->redirect('user/login');
		}
		
		// userId of user that is ready logged in
		$user = new User($result->userId);
		
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

		User::doLogout(session_id());

		if ($formerUser) {
			User::loginAs($formerUser, '');
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
		$user->name		= Post::trim('name');
		$user->surname	= Post::trim('surname');
		$user->email	= Post::trim('email') ? Post::trim('email') : NULL;
		$user->username	= Post::trim('username');
		$user->localeId	= Post::int('localeId');
		
		if (Post::get('password')) {
			$user->hash = User::getHashedPasswordWithSalt(Post::get('password'));
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