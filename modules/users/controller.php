<?php

declare(strict_types=1);

use Pair\Core\Router;
use Pair\Exceptions\AppException;
use Pair\Helpers\Options;
use Pair\Helpers\Translator;
use Pair\Html\Breadcrumb;
use Pair\Html\Form;
use Pair\Html\Pagination;
use Pair\Models\Acl;
use Pair\Models\Audit;
use Pair\Models\Group;
use Pair\Models\Locale;
use Pair\Models\User;
use Pair\Web\Controller;
use Pair\Web\PageResponse;

class UsersController extends Controller {

	/**
	 * Users module data helper.
	 */
	private UsersModel $model;

	/**
	 * Prepare the explicit controller dependencies.
	 */
	protected function boot(): void {

		$this->model = new UsersModel();
		Breadcrumb::path($this->translate('USERS'), 'users');

	}

	/**
	 * Render the users list page.
	 */
	public function defaultAction(): PageResponse {

		return $this->buildUsersListPage();

	}

	/**
	 * Render the new-user page.
	 */
	public function userNewAction(): PageResponse {

		return $this->buildUserNewPage();

	}

	/**
	 * Persist a new user or re-render the form with explicit state.
	 */
	public function userAddAction(): ?PageResponse {

		$userField = PAIR_AUTH_BY_EMAIL ? 'email' : 'username';
		$username = strtolower(trim((string)$this->input()->string($userField, '')));
		$password = trim((string)$this->input()->string('password', ''));
		$minPassword = (int)Options::get('password_min');

		$newUser = new User();
		$this->fillUserFromInput($newUser, $username, true);

		if (strlen($password) < $minPassword) {
			$this->toastError($this->translate('SHORT_PASSWORD'));
			return $this->buildUserNewPage($newUser);
		}

		if (count(User::getAllObjects([$userField => $username]))) {
			$this->toastError($this->translate('USER_EXIST', $username));
			return $this->buildUserNewPage($newUser);
		}

		$form = $this->buildUserNewForm($newUser);
		$newUser->hash = User::getHashedPasswordWithSalt($password);

		if ($form->isValid() and $newUser->create()) {
			$this->toast($this->translate('USER_HAS_BEEN_CREATED', $newUser->fullName));
			$this->redirect('users/userList');
		}

		$this->toastError($this->translate('USER_HAS_NOT_BEEN_CREATED', $newUser->fullName));
		$this->toastObjectErrors($newUser);

		return $this->buildUserNewPage($newUser);

	}

	/**
	 * Render the user-edit page.
	 */
	public function userEditAction(): PageResponse {

		return $this->buildUserEditPage($this->loadUserFromRoute());

	}

	/**
	 * Persist the user changes or re-render the edit page.
	 */
	public function userChangeAction(): ?PageResponse {

		$user = new User((int)$this->input()->int('id', 0));
		$oldUser = clone $user;

		if (!$user->isLoaded()) {
			$this->toastError($this->translate('ITEM_TO_EDIT_IS_NOT_VALID'));
			return $this->buildUsersListPage();
		}

		if (!$this->canEditUser($user)) {
			$this->toastError($this->translate('ITEM_TO_EDIT_IS_NOT_VALID'));
			$this->redirect('users');
		}

		$form = $this->buildUserEditForm($user);

		if (!$form->isValid()) {
			$this->toastError($this->translate('USER_HAS_NOT_BEEN_CHANGED', $user->fullName));
			return $this->buildUserEditPage($user);
		}

		$userField = PAIR_AUTH_BY_EMAIL ? 'email' : 'username';
		$username = trim((string)$this->input()->string($userField, ''));
		$password = trim((string)$this->input()->string('password', ''));

		if (strlen($password) > 0 and strlen($password) < (int)Options::get('password_min')) {
			$this->toastError($this->translate('SHORT_PASSWORD'));
			return $this->buildUserEditPage($user);
		}

		$this->fillUserFromInput($user, $username);
		$user->groupId = (int)$this->input()->int('groupId', $user->groupId);

		if ($password !== '') {
			$user->hash = User::getHashedPasswordWithSalt($password);
		}

		if (!$user->store()) {
			$this->toastError($this->translate('USER_HAS_NOT_BEEN_CHANGED', $user->fullName));
			$this->toastObjectErrors($user);
			return $this->buildUserEditPage($user);
		}

		Audit::userChanged($oldUser, $user);

		if ($password !== '') {
			Audit::passwordChanged($user);
		}

		$this->toast($this->translate('USER_HAS_BEEN_CHANGED', $user->fullName));
		$this->redirect('users');

	}

	/**
	 * Delete a user or re-render the edit page with errors.
	 */
	public function userDeleteAction(): ?PageResponse {

		$user = $this->loadUserFromRoute();

		if (!$this->canEditUser($user)) {
			$this->toastError($this->translate('ITEM_TO_EDIT_IS_NOT_VALID'));
			$this->redirect('users');
		}

		$fullName = $user->fullName;

		if ($user->delete()) {
			$this->toast($this->translate('USER_HAS_BEEN_DELETED', $fullName));
			$this->redirect('users');
		}

		$this->toastObjectErrors($user);
		$this->pageHeading($this->translate('USER_EDIT'));

		return $this->buildUserEditPage($user);

	}

	/**
	 * Render the groups list page.
	 */
	public function groupListAction(): PageResponse {

		return $this->buildGroupListPage();

	}

	/**
	 * Render the new-group page.
	 */
	public function groupNewAction(): PageResponse {

		return $this->buildGroupNewPage();

	}

	/**
	 * Persist a new group or re-render the form with explicit state.
	 */
	public function groupAddAction(): ?PageResponse {

		$group = new Group();
		$group->name = trim((string)$this->input()->string('name', ''));
		$group->default = (bool)$this->input()->bool('default', false);
		$selectedDefaultAclId = $this->input()->int('defaultAclId');
		$form = $this->buildGroupNewForm($group, $selectedDefaultAclId);

		if ($form->isValid() and $group->create()) {

			if (!is_null($selectedDefaultAclId)) {
				$group->setDefaultAcl($selectedDefaultAclId);
			}

			$this->toast($this->translate('GROUP_HAS_BEEN_CREATED', $group->name));
			$this->redirect('groups');
		}

		$this->toastError($this->translate('GROUP_HAS_NOT_BEEN_CREATED', $group->name));
		$this->toastObjectErrors($group);

		return $this->buildGroupNewPage($group, $selectedDefaultAclId);

	}

	/**
	 * Render the group-edit page.
	 */
	public function groupEditAction(): PageResponse {

		return $this->buildGroupEditPage($this->loadGroupFromRoute());

	}

	/**
	 * Persist a group update or re-render the edit page.
	 */
	public function groupChangeAction(): ?PageResponse {

		$group = new Group((int)$this->input()->int('id', 0));

		if (!$group->isLoaded()) {
			$this->toastError($this->translate('ITEM_TO_EDIT_IS_NOT_VALID'));
			return $this->buildGroupListPage();
		}

		$group->name = trim((string)$this->input()->string('name', ''));
		$group->default = $group->default ? true : (bool)$this->input()->bool('default', false);
		$selectedDefaultAclId = $this->input()->int('defaultAclId');
		$form = $this->buildGroupEditForm($group, $selectedDefaultAclId);

		if (!$form->isValid()) {
			$this->toastError($this->translate('ERROR_ON_LAST_REQUEST'));
			return $this->buildGroupEditPage($group, $selectedDefaultAclId);
		}

		if (!$group->update(['name', 'default'])) {
			$this->toastObjectErrors($group);
			return $this->buildGroupEditPage($group, $selectedDefaultAclId);
		}

		if (!is_null($selectedDefaultAclId)) {
			$group->setDefaultAcl($selectedDefaultAclId);
		}

		$this->toast($this->translate('GROUP_HAS_BEEN_CHANGED', $group->name));
		$this->redirect('groups');

	}

	/**
	 * Delete a group and redirect back to the list.
	 */
	public function groupDeleteAction(): void {

		$group = $this->loadGroupFromRoute();

		if ($group->isDeletable()) {

			$groupName = $group->name;

			if ($group->delete()) {
				$this->toast($this->translate('GROUP_HAS_BEEN_DELETED', $groupName));
			} else {
				$this->toastError($this->translate('GROUP_HAS_NOT_BEEN_DELETED', $groupName));
			}

		} else {

			$this->toastError($this->translate('GROUP_CAN_NOT_BEEN_DELETED', $group->name));

		}

		$this->redirect('groups');

	}

	/**
	 * Render the ACL list page for one group.
	 */
	public function aclListAction(): PageResponse {

		return $this->buildAclListPage($this->loadGroupFromRoute());

	}

	/**
	 * Render the add-ACL page for one group.
	 */
	public function aclNewAction(): PageResponse {

		return $this->buildAclNewPage($this->loadGroupFromRoute());

	}

	/**
	 * Persist new ACL rows and redirect back to the group ACL list.
	 */
	public function aclAddAction(): void {

		$group = $this->loadGroupById((int)$this->input()->int('groupId', 0));

		foreach ($this->input()->array('aclChecked') as $ruleId) {

			$acl = new Acl();
			$acl->ruleId = (int)$ruleId;
			$acl->groupId = $group->id;
			$acl->create();

		}

		$this->toast($this->translate('NEW_ACCESS_PERMISSION_HAS_BEEN_CREATED'));
		$this->redirect('users/aclList/' . $group->id);

	}

	/**
	 * Delete an ACL row and redirect back to the group ACL list.
	 */
	public function aclDeleteAction(): void {

		$acl = $this->loadAclFromRoute();
		$moduleName = $acl->getModuleName();
		$groupId = $acl->groupId;

		if ($acl->delete()) {
			$this->toast($this->translate('ACCESS_PERMISSION_HAS_BEEN_DELETED', $moduleName));
		} else {
			$this->toastError($this->translate('ACCESS_PERMISSION_HAS_NOT_BEEN_DELETED', $moduleName));
		}

		$this->redirect('users/aclList/' . $groupId);

	}

	/**
	 * Build the users list page.
	 */
	private function buildUsersListPage(): PageResponse {

		$this->setUsersMenu();
		$this->pageHeading($this->translate('USERS'));

		return $this->page(
			'default',
			$this->buildUserListPageState(),
			$this->translate('USERS')
		);

	}

	/**
	 * Build the users list state.
	 */
	private function buildUserListPageState(): UsersDefaultPageState {

		$pagination = new Pagination();
		$pagination->page = $this->router->getPage();

		// The legacy model still expects Pagination to be injected before loading rows.
		$this->model->pagination = $pagination;

		$users = $this->model->getUsers();
		$pagination->count = User::countAllObjects(['admin' => 0]);
		$rows = [];

		foreach ($users as $user) {
			$rows[] = new UsersUserListItemState(
				(int)$user->id,
				(string)$user->fullName,
				(string)$user->username,
				(string)($user->email ?? ''),
				(string)($user->groupName ?? ''),
				(bool)$user->enabled,
				(string)($user->lastLogin ?? ''),
				$this->canEditUser($user)
			);
		}

		return new UsersDefaultPageState($rows, $pagination->render());

	}

	/**
	 * Build the new-user page and optionally preload the form with request data.
	 */
	private function buildUserNewPage(?User $user = null): PageResponse {

		$this->setUsersMenu();
		$this->pageHeading($this->translate('NEW_USER'));
		Breadcrumb::path($this->translate('NEW_USER'), 'users/new');

		return $this->page(
			'userNew',
			new UsersUserNewPageState($this->buildUserNewForm($user)),
			$this->translate('NEW_USER')
		);

	}

	/**
	 * Build the edit-user page from one loaded user.
	 */
	private function buildUserEditPage(User $user): PageResponse {

		$this->setUsersMenu();
		$this->pageHeading($this->translate('USER_EDIT'));
		Breadcrumb::path($this->translate('USER_EDIT') . ' ' . $user->fullName, 'users/edit/' . $user->id);

		return $this->page(
			'userEdit',
			new UsersUserEditPageState(
				$this->buildUserEditForm($user),
				(int)$user->id,
				$user->isDeletable()
			),
			$this->translate('USER_EDIT')
		);

	}

	/**
	 * Build the create-user form and optionally preload it with a user object.
	 */
	private function buildUserNewForm(?User $user = null): Form {

		$form = $this->model->getUserForm();
		$form->control('password')->required();

		if (is_null($user)) {
			$form->control('enabled')->value(true);
			$form->control('localeId')->value(Locale::getDefault()->id);
			return $form;
		}

		$form->values($user);

		return $form;

	}

	/**
	 * Build the edit-user form from one loaded or mutated user.
	 */
	private function buildUserEditForm(User $user): Form {

		$form = $this->model->getUserForm();
		$form->values($user);
		$form->control('id')->value($user->id)->required();

		return $form;

	}

	/**
	 * Copy request values into a user object.
	 */
	private function fillUserFromInput(User $user, string $username, bool $isNew = false): void {

		$user->name = trim((string)$this->input()->string('name', ''));
		$user->surname = trim((string)$this->input()->string('surname', ''));
		$user->email = trim((string)$this->input()->string('email', '')) ?: null;
		$user->username = $username;
		$user->enabled = (bool)$this->input()->bool('enabled', false);
		$user->localeId = (int)$this->input()->int('localeId', 0);

		if ($isNew) {
			$user->groupId = (int)$this->input()->int('groupId', 0);
			$user->admin = false;
			$user->faults = 0;
		}

	}

	/**
	 * Build the groups list page.
	 */
	private function buildGroupListPage(): PageResponse {

		$this->setGroupsMenu();
		$this->pageHeading($this->translate('GROUPS'));
		Breadcrumb::path($this->translate('GROUPS'), 'groups');

		if (is_null(Group::getDefault())) {
			$this->toast($this->translate('DEFAULT_GROUP_NOT_FOUND'));
		}

		return $this->page(
			'groupList',
			$this->buildGroupListPageState(),
			$this->translate('GROUPS')
		);

	}

	/**
	 * Build the groups list state.
	 */
	private function buildGroupListPageState(): UsersGroupListPageState {

		$pagination = new Pagination();
		$pagination->page = $this->router->getPage();

		// The legacy model expects Pagination on the model even if the query is wide.
		$this->model->pagination = $pagination;

		$groups = $this->model->getGroups();
		$pagination->count = Group::countAllObjects();
		$rows = [];

		foreach ($groups as $group) {
			$rows[] = new UsersGroupListItemState(
				(int)$group->id,
				(string)$group->name,
				(bool)$group->default,
				(int)($group->userCount ?? 0),
				(string)($group->moduleName ?? ''),
				(int)($group->aclCount ?? 0),
				(int)($group->aclCount ?? 0) === 0
			);
		}

		return new UsersGroupListPageState($rows, $pagination->render());

	}

	/**
	 * Build the new-group page and optionally preload the form with request data.
	 */
	private function buildGroupNewPage(?Group $group = null, ?int $selectedDefaultAclId = null): PageResponse {

		$this->setGroupsMenu();
		$this->pageHeading($this->translate('NEW_GROUP'));
		Breadcrumb::path($this->translate('GROUPS'), 'groups');
		Breadcrumb::path($this->translate('NEW_GROUP'), 'groups/new');

		return $this->page(
			'groupNew',
			new UsersGroupNewPageState($this->buildGroupNewForm($group, $selectedDefaultAclId)),
			$this->translate('NEW_GROUP')
		);

	}

	/**
	 * Build the edit-group page from one loaded or mutated group.
	 */
	private function buildGroupEditPage(Group $group, ?int $selectedDefaultAclId = null): PageResponse {

		$this->setGroupsMenu();
		$this->pageHeading($this->translate('GROUP_EDIT'));
		Breadcrumb::path($this->translate('GROUPS'), 'groups');
		Breadcrumb::path($group->name, 'groups/edit/' . $group->id);

		$hasModules = count($this->model->getAcl($group->id)) > 0;

		return $this->page(
			'groupEdit',
			new UsersGroupEditPageState(
				$this->buildGroupEditForm($group, $selectedDefaultAclId),
				(int)$group->id,
				(string)$group->name,
				$hasModules,
				$group->isDeletable()
			),
			$this->translate('GROUP_EDIT')
		);

	}

	/**
	 * Build the create-group form and preload dynamic ACL options.
	 */
	private function buildGroupNewForm(?Group $group = null, ?int $selectedDefaultAclId = null): Form {

		$form = $this->model->getGroupForm();
		$rules = $this->model->getRules();
		$defaultGroup = Group::getDefault();

		$form->control('defaultAclId')
			->required()
			->options($rules, 'id', 'moduleAction')
			->empty('- Seleziona -');

		if (is_null($group)) {
			$form->control('default')->value(is_null($defaultGroup));
		} else {
			$form->values($group);
		}

		if (!is_null($selectedDefaultAclId)) {
			$form->control('defaultAclId')->value($selectedDefaultAclId);
		}

		return $form;

	}

	/**
	 * Build the edit-group form and preload dynamic ACL options.
	 */
	private function buildGroupEditForm(Group $group, ?int $selectedDefaultAclId = null): Form {

		$form = $this->model->getGroupForm();
		$modules = $this->model->getAcl($group->id);
		$hasModules = count($modules) > 0;

		$form->values($group);

		if ($group->default) {
			$form->control('default')->disabled();
		}

		$form->control('defaultAclId')->required($hasModules);

		if ($hasModules) {
			$form->control('defaultAclId')->options($modules, 'id', 'moduleAction');
		}

		$resolvedDefaultAclId = $this->resolveSelectedDefaultAclId($group, $selectedDefaultAclId);

		if (!is_null($resolvedDefaultAclId)) {
			$form->control('defaultAclId')->value($resolvedDefaultAclId);
		}

		return $form;

	}

	/**
	 * Resolve the selected default ACL value for the group form.
	 */
	private function resolveSelectedDefaultAclId(Group $group, ?int $selectedDefaultAclId = null): ?int {

		if (!is_null($selectedDefaultAclId)) {
			return $selectedDefaultAclId;
		}

		$defaultAcl = $group->getDefaultAcl();

		return is_null($defaultAcl) ? null : (int)$defaultAcl->id;

	}

	/**
	 * Build the ACL list page for one group.
	 */
	private function buildAclListPage(Group $group): PageResponse {

		$this->setGroupsMenu();
		$this->pageHeading($this->translate('ACCESS_LIST'));
		Breadcrumb::path($this->translate('GROUPS'), 'groups');
		Breadcrumb::path($group->name, 'groups/edit/' . $group->id);
		Breadcrumb::path($this->translate('ACCESS_LIST'), 'users/aclList/' . $group->id);

		return $this->page(
			'aclList',
			$this->buildAclListPageState($group),
			$this->translate('ACCESS_LIST_OF_GROUP', $group->name)
		);

	}

	/**
	 * Build the ACL list state for one group.
	 */
	private function buildAclListPageState(Group $group): UsersAclListPageState {

		$aclList = $this->model->getAcl($group->id);
		$rows = [];

		foreach ($aclList as $item) {
			$rows[] = new UsersAclListItemState(
				(int)$item->id,
				ucfirst((string)$item->moduleName),
				$item->action ? ucfirst((string)$item->action) : 'full access',
				!$item->default and $item->isDeletable()
			);
		}

		return new UsersAclListPageState(
			$rows,
			(int)$group->id,
			(string)$group->name,
			(bool)$group->getAllNotExistRules()
		);

	}

	/**
	 * Build the add-ACL page for one group.
	 */
	private function buildAclNewPage(Group $group): PageResponse {

		$this->setGroupsMenu();
		$this->pageHeading($this->translate('NEW_ACL'));
		Breadcrumb::path($this->translate('GROUPS'), 'groups');
		Breadcrumb::path($group->name, 'groups/edit/' . $group->id);
		Breadcrumb::path($this->translate('ACCESS_LIST'), 'users/aclList/' . $group->id);
		Breadcrumb::path($this->translate('NEW_ACL'));

		return $this->page(
			'aclNew',
			$this->buildAclNewPageState($group),
			$this->translate('NEW_ACL')
		);

	}

	/**
	 * Build the add-ACL page state for one group.
	 */
	private function buildAclNewPageState(Group $group): UsersAclNewPageState {

		$rules = [];

		foreach ($group->getAllNotExistRules() as $rule) {
			$rules[] = new UsersAclRuleItemState(
				(int)$rule->id,
				(string)$rule->moduleName,
				(string)$rule->action
			);
		}

		return new UsersAclNewPageState(
			$this->buildAclNewForm($group),
			(int)$group->id,
			(string)$group->name,
			$rules
		);

	}

	/**
	 * Build the ACL helper form for one group.
	 */
	private function buildAclNewForm(Group $group): Form {

		$form = $this->model->getAclListForm();
		$form->control('groupId')->value($group->id);

		return $form;

	}

	/**
	 * Load one user from the router id and ensure that it exists.
	 */
	private function loadUserFromRoute(): User {

		return $this->loadUserById((int)(Router::get('id') ?? 0));

	}

	/**
	 * Load one user by id and ensure that it exists.
	 */
	private function loadUserById(int $userId): User {

		if ($userId < 1) {
			throw new AppException($this->translate('NO_ID_OF_ITEM_TO_EDIT', 'Pair\Models\User'));
		}

		$user = new User($userId);

		if (!$user->isLoaded()) {
			throw new AppException($this->translate('ID_OF_ITEM_TO_EDIT_IS_NOT_VALID', 'Pair\Models\User'));
		}

		return $user;

	}

	/**
	 * Load one group from the router id and ensure that it exists.
	 */
	private function loadGroupFromRoute(): Group {

		return $this->loadGroupById((int)(Router::get('id') ?? Router::get(0) ?? 0));

	}

	/**
	 * Load one group by id and ensure that it exists.
	 */
	private function loadGroupById(int $groupId): Group {

		if ($groupId < 1) {
			throw new AppException($this->translate('NO_ID_OF_ITEM_TO_EDIT', 'Pair\Models\Group'));
		}

		$group = new Group($groupId);

		if (!$group->isLoaded()) {
			throw new AppException($this->translate('ID_OF_ITEM_TO_EDIT_IS_NOT_VALID', 'Pair\Models\Group'));
		}

		return $group;

	}

	/**
	 * Load one ACL row from the router id and ensure that it exists.
	 */
	private function loadAclFromRoute(): Acl {

		$aclId = (int)(Router::get(0) ?? 0);

		if ($aclId < 1) {
			throw new AppException($this->translate('ITEM_TO_EDIT_IS_NOT_VALID'));
		}

		$acl = new Acl($aclId);

		if (!$acl->isLoaded()) {
			throw new AppException($this->translate('ITEM_TO_EDIT_IS_NOT_VALID'));
		}

		return $acl;

	}

	/**
	 * Tell whether the current user can edit the requested user row.
	 */
	private function canEditUser(User $user): bool {

		return $this->app->currentUser->admin or !$user->admin;

	}

	/**
	 * Set the active menu for users pages.
	 */
	private function setUsersMenu(): void {

		$this->app->activeMenuItem = 'users/userList';

	}

	/**
	 * Set the active menu for groups and ACL pages.
	 */
	private function setGroupsMenu(): void {

		$this->app->activeMenuItem = 'groups';

	}

	/**
	 * Show the validation errors queued by one persisted object.
	 */
	private function toastObjectErrors(User|Group|Acl $object): void {

		$errors = $object->getErrors();

		if (!count($errors)) {
			$this->toastError($this->translate('ERROR_ON_LAST_REQUEST'));
			return;
		}

		foreach ($errors as $error) {
			$this->toastError((string)$error);
		}

	}

	/**
	 * Translate one language key inside the users module.
	 */
	private function translate(string $key, string|array|null $vars = null): string {

		return Translator::do($key, $vars);

	}

}
