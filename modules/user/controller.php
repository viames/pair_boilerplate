<?php

declare(strict_types=1);

use Pair\Core\Env;
use Pair\Helpers\Translator;
use Pair\Html\Breadcrumb;
use Pair\Html\Form;
use Pair\Models\Audit;
use Pair\Models\Session;
use Pair\Models\User;
use Pair\Web\Controller;
use Pair\Web\PageResponse;

class UserController extends Controller {

	/**
	 * User module form factory.
	 */
	private UserModel $model;

	/**
	 * Prepare the explicit user controller.
	 */
	protected function boot(): void {

		$this->model = new UserModel();
		Breadcrumb::path($this->translate('USER'), 'user');

	}

	/**
	 * Redirect to the login page or to the authenticated user profile.
	 */
	public function defaultAction(): void {

		if ($this->app->currentUser) {
			$this->redirect('user/profile');
		}

		$this->redirect('user/login');

	}

	/**
	 * Render or submit the login page through the explicit Pair v4 response path.
	 */
	public function loginAction(): ?PageResponse {

		if ($this->app->currentUser) {
			$this->redirect('user/profile');
		}

		if ($this->input()->method() !== 'POST') {
			return $this->loginPage();
		}

		$username = trim((string)$this->input()->string('username', ''));
		$password = trim((string)$this->input()->string('password', ''));
		$timezone = $this->input()->string('timezone');
		$remember = $this->input()->bool('remember', false) ?? false;

		if ($username === '' or $password === '') {
			$this->toastError($this->translate('AUTHENTICATION_REQUIRES_USERNAME_AND_PASSWORD'));
			return $this->loginPage();
		}

		$result = User::doLogin($username, $password, $timezone);

		if ($result->error) {
			sleep(3);
			$this->toastError((string)$result->message);
			$this->redirect('user/login');
		}

		$user = new User((int)$result->userId);

		if ($remember) {
			$user->createRememberMe($timezone);
		}

		// Restore the interrupted destination when the user was redirected to login.
		$page = $this->getPersistentState('lastRequestedUrl');
		$this->unsetPersistentState('lastRequestedUrl');

		if (is_string($page) and trim($page) !== '') {
			$this->redirect($page);
		}

		$landing = $user->landing();
		$this->redirect($landing->module . '/' . $landing->action);

		return null;

	}

	/**
	 * Render the personal profile page with explicit typed state.
	 */
	public function profileAction(): PageResponse {

		$user = $this->currentUser();
		$form = $this->buildProfileForm($user, true);

		return $this->page(
			'profile',
			new UserProfilePageState($form),
			$this->translate('USER_PROFILE_OF', $user->fullName)
		);

	}

	/**
	 * Render the editable profile page with explicit typed state.
	 */
	public function profileEditAction(): PageResponse {

		$user = $this->currentUser();
		$form = $this->buildProfileForm($user);

		return $this->page(
			'profileEdit',
			new UserProfileEditPageState($form, (string)$user->fullName),
			$this->translate('USER_EDIT', $user->fullName)
		);

	}

	/**
	 * Stop the active session and redirect back to login.
	 */
	public function logoutAction(): void {

		$session = Session::current();
		$formerUser = $session->getFormerUser();

		if ($formerUser) {
			$formerUser->impersonateStop();
			$this->redirect('user/profile');
		}

		User::doLogout(session_id());

		header('Location: ' . BASE_HREF . 'login');
		exit();

	}

	/**
	 * Persist the editable profile form.
	 */
	public function profileChangeAction(): void {

		$user = new User($this->currentUser()->id);
		$oldUser = clone $user;
		$form = $this->model->getUserForm();

		if (!$form->isValid()) {
			$this->toastError($this->translate('ERROR_FORM_IS_NOT_VALID'));
			$this->redirect('user/profileEdit');
		}

		$user->name = trim((string)$this->input()->string('name', ''));
		$user->surname = trim((string)$this->input()->string('surname', ''));
		$user->email = trim((string)$this->input()->string('email', '')) ?: null;
		$user->username = trim((string)$this->input()->string('username', ''));
		$user->localeId = $this->input()->int('localeId');

		$password = (string)$this->input()->string('password', '');

		if ($password !== '') {
			$user->hash = User::getHashedPasswordWithSalt($password);
		}

		if (!$user->store()) {
			$this->redirectToProfileEditWithErrors($user);
		}

		Audit::userChanged($oldUser, $user);

		if ($password !== '') {
			Audit::passwordChanged($user);
		}

		$this->toast($this->translate('YOUR_PROFILE_HAS_BEEN_CHANGED'));
		$this->redirect('user/profile');

	}

	/**
	 * Build the explicit login page response.
	 */
	private function loginPage(): PageResponse {

		$this->app->style = 'login';

		return $this->page(
			'login',
			new UserLoginPageState($this->model->getLoginForm()),
			Env::get('APP_NAME')
		);

	}

	/**
	 * Return the authenticated user as a concrete Pair model.
	 */
	private function currentUser(): User {

		$currentUser = $this->app->currentUser;

		if (!$currentUser instanceof User) {
			throw new RuntimeException('Expected an authenticated user for the user module.');
		}

		return $currentUser;

	}

	/**
	 * Build the profile form with the current user values.
	 */
	private function buildProfileForm(User $user, bool $readonly = false): Form {

		$form = $this->model->getUserForm();
		$form->values($user);

		if ($readonly) {
			$form->allReadonly();
		}

		return $form;

	}

	/**
	 * Translate a language key within the user module.
	 */
	private function translate(string $key, string|array|null $vars = null): string {

		return Translator::do($key, $vars);

	}

	/**
	 * Redirect back to the profile editor after a failed store operation.
	 */
	private function redirectToProfileEditWithErrors(User $user): void {

		$errors = $user->getErrors();
		$message = count($errors)
			? implode(" \n", $errors)
			: $this->translate('ERROR_ON_LAST_REQUEST');

		$this->toastError($message);
		$this->redirect('user/profileEdit');

	}

}
