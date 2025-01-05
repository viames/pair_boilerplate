<?php

use Pair\Core\Controller;
use Pair\Models\Session;
use Pair\Models\User;
use Pair\Support\Post;

class UserController extends Controller {
	
	public function defaultAction(): void {
		
		$this->view = 'login';
		
	}
	
	/**
	 * Shows login form or try login action.
	 */
	public function loginAction() {
	
		$username	= Post::trim('username');
		$password	= Post::trim('password');
		$timezone	= Post::trim('timezone');
		$remember	= Post::bool('remember', FALSE);

		// if isn’t post submit, render the page
		if (!Post::submitted()) {
			return;
		}
			
		// username or password missing
		if (!$username or !$password) {
			$this->toastError($this->lang('AUTHENTICATION_REQUIRES_USERNAME_AND_PASSWORD'));
			return;
		}
		
		// perform the login and create an user session
		$result = User::doLogin($username, $password, $timezone);
		
		// login denied
		if ($result->error) {
			sleep(3);
			$this->toastError($result->message);
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
		$this->unsetPersistentState('lastRequestedUrl');

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
	public function logoutAction(): void {

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
	public function profileChangeAction(): void {
	
		$user = new User($this->app->currentUser->id);

		// snapshot for Audit
		$oldUser = clone $user;

		$form = $this->model->getUserForm($user);

		if (!$form->isValid()) {
			$this->toastError($this->lang('ERROR_FORM_IS_NOT_VALID'));
			return;
		}

		$user->name		= Post::trim('name');
		$user->surname	= Post::trim('surname');
		$user->email	= Post::trim('email') ? Post::trim('email') : NULL;
		$user->username	= Post::trim('username');
		$user->localeId	= Post::int('localeId');
		
		if (Post::get('password')) {
			$user->hash = User::getHashedPasswordWithSalt(Post::get('password'));
		}

		if (!$user->store()) {
			$this->raiseError($user);
			return;
		}

		// track the user edit
		Audit::userChanged($oldUser, $user);

		// track user password change
		if (Post::get('password')) {
			Audit::passwordChanged($user);
		}

		$this->toast($this->lang('YOUR_PROFILE_HAS_BEEN_CHANGED'));
		$this->app->redirect('user/profile');
	
	}

}