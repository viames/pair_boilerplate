<?php

declare(strict_types=1);

require_once APPLICATION_PATH . '/modules/crafter/classes/CrafterAccessDeniedPageState.php';
require_once APPLICATION_PATH . '/modules/crafter/classes/CrafterBootstrapReferencePageState.php';
require_once APPLICATION_PATH . '/modules/crafter/classes/CrafterClassWizardPageState.php';
require_once APPLICATION_PATH . '/modules/crafter/classes/CrafterDefaultPageState.php';
require_once APPLICATION_PATH . '/modules/crafter/classes/CrafterModuleWizardPageState.php';
require_once APPLICATION_PATH . '/modules/crafter/classes/CrafterNewClassPageState.php';
require_once APPLICATION_PATH . '/modules/crafter/classes/CrafterNewTablePageState.php';
require_once APPLICATION_PATH . '/modules/crafter/classes/CrafterPlaygroundPageState.php';

use Pair\Core\Application;
use Pair\Core\Router;
use Pair\Exceptions\AppException;
use Pair\Exceptions\CriticalException;
use Pair\Exceptions\ErrorCodes;
use Pair\Exceptions\PairException;
use Pair\Helpers\Translator;
use Pair\Html\Breadcrumb;
use Pair\Models\Group;
use Pair\Web\Controller;
use Pair\Web\PageResponse;

/**
 * Controller Pair v4 del modulo tecnico Crafter.
 */
class CrafterController extends Controller {

	/**
	 * Model tecnico del modulo Crafter.
	 */
	protected CrafterModel $model;

	/**
	 * Inizializza guard d'ambiente e breadcrumb base del modulo.
	 */
	protected function boot(): void {

		$this->model = new CrafterModel();
		$this->checkAccess();
		Breadcrumb::path('Crafter module', 'crafter');

	}

	/**
	 * Mostra la pagina di diniego oppure rientra sulla dashboard in ambiente sviluppo.
	 */
	public function accessDeniedAction(): ?PageResponse {

		if ('development' === Application::getEnvironment()) {
			$this->redirect('crafter');
			return null;
		}

		$this->pageHeading('Crafter');

		return $this->page('accessDeniedPage', new CrafterAccessDeniedPageState(), 'Crafter');

	}

	/**
	 * Mostra la dashboard principale del modulo tecnico.
	 */
	public function defaultAction(): PageResponse {

		$this->pageTitle('Crafter');
		$this->pageHeading('Crafter');
		$this->menuUrl('crafter');

		return $this->page('defaultPage', new CrafterDefaultPageState(), 'Crafter');

	}

	/**
	 * Mostra l'elenco delle tabelle non ancora mappate a classi.
	 */
	public function newClassAction(): PageResponse {

		$this->pageHeading('Crafter');
		$this->menuUrl('crafter');

		return $this->page('newClassPage', $this->buildNewClassPageState(), 'Crafter');

	}

	/**
	 * Mostra il wizard di creazione classe per la tabella richiesta.
	 */
	public function newClassWizardAction(): ?PageResponse {

		$tableName = $this->requestedTableName();

		if ('' === $tableName) {
			$this->toastError($this->translate('TABLE_NAME_NOT_SPECIFIED'));
			$this->redirect('crafter/newClass');
			return null;
		}

		$this->pageHeading('Crafter');
		$this->menuUrl('crafter');

		return $this->page('newClassWizardPage', $this->buildClassWizardPageState($tableName), 'Crafter');

	}

	/**
	 * Mantiene compatibile il vecchio route del wizard classe.
	 */
	public function classWizardAction(): ?PageResponse {

		return $this->newClassWizardAction();

	}

	/**
	 * Crea la classe richiesta e reindirizza alla lista tabelle non mappate.
	 */
	public function classCreationAction(): void {

		$tableName = $this->bodyRawString('tableName');
		$objectName = $this->bodyRawString('objectName');

		if ($tableName and $objectName) {
			$this->model->setupVariables($tableName, $objectName);
			$file = $this->model->commonClassPath();

			if (!file_exists($file)) {
				try {
					$this->model->saveClass($file);
					$this->model->createMigrationFile();
					$this->toast($this->translate('CLASS_HAS_BEEN_CREATED', $this->model->objectName));
				} catch (Exception $e) {
					$this->toastError($e->getMessage());
					$this->redirect('crafter/newClass');
					return;
				}
			} else {
				$this->toastError($this->translate('CLASS_FILE_ALREADY_EXISTS', $this->model->objectName));
			}
		} else {
			$this->toastError($this->translate('CLASS_HAS_NOT_BEEN_CREATED'));
		}

		$this->redirect('crafter/newClass');

	}

	/**
	 * Mostra il wizard di creazione modulo per la tabella richiesta.
	 */
	public function newModuleWizardAction(): ?PageResponse {

		$tableName = $this->requestedTableName();

		if ('' === $tableName) {
			$this->toastError($this->translate('TABLE_NAME_NOT_SPECIFIED'));
			$this->redirect('crafter/newClass');
			return null;
		}

		$this->pageHeading('Crafter');
		$this->menuUrl('crafter');

		return $this->page('newModuleWizardPage', $this->buildModuleWizardPageState($tableName), 'Crafter');

	}

	/**
	 * Mantiene compatibile il vecchio route del wizard modulo.
	 */
	public function moduleWizardAction(): ?PageResponse {

		return $this->newModuleWizardAction();

	}

	/**
	 * Mostra l'elenco delle classi non ancora materializzate come tabella.
	 */
	public function newTableAction(): ?PageResponse {

		if (!(bool)($this->app->currentUser->super ?? false)) {
			return $this->accessDeniedAction();
		}

		$this->pageHeading('Crafter');
		$this->menuUrl('crafter');

		return $this->page('newTablePage', $this->buildNewTablePageState(), 'Crafter');

	}

	/**
	 * Crea la tabella richiesta e torna al workspace corretto con feedback esplicito.
	 */
	public function createTableAction(): void {

		$tableName = $this->requestedTableName();
		$class = $this->model->getClassByTable($tableName);

		if (!$class) {
			$this->toastError($this->translate('CLASS_NOT_FOUND_FOR_TABLE', $tableName));
			$this->redirect('crafter/newTable');
			return;
		}

		$res = $this->model->createTableByClass($class);

		if ($res) {
			$this->toast($this->translate('TABLE_HAS_BEEN_CREATED', $tableName));
			$this->redirect('crafter');
			return;
		}

		$errors = $this->model->getErrors();
		$this->toastError($this->translate('ERROR_ON_LAST_REQUEST') . "\n" . implode("\n", $errors));
		$this->redirect('crafter/newTable');

	}

	/**
	 * Esegue la creazione del modulo e torna alla dashboard tecnica.
	 */
	public function moduleCreationAction(): void {

		$tableName = $this->bodyRawString('tableName');
		$objectName = $this->bodyRawString('objectName');
		$moduleName = $this->bodyRawString('moduleName');
		$commonClass = $this->bodyBool('commonClass', false);
		$migration = $this->bodyBool('migration', false);

		if (!$tableName or !$objectName or !$moduleName) {
			$this->toastError($this->translate('MODULE_HAS_NOT_BEEN_CREATED'));
			$this->redirect('crafter');
			return;
		}

		$grantedGroups = [];

		// Ricostruisce i gruppi concessi per il nuovo modulo dal POST del wizard.
		foreach (Group::all() as $group) {
			$value = $this->bodyBool('group' . $group->id, false);
			if ($value) {
				$grantedGroups[] = new Group($group->id);
			}
		}

		$this->model->setupVariables($tableName, $objectName, $moduleName);

		try {
			$this->model->createModule($commonClass);
			$this->model->registerModule($grantedGroups);

			if ($migration) {
				$this->model->createMigrationFile();
			}
		} catch (Throwable $e) {
			$this->modal('Error', $e->getMessage(), 'danger');
			$this->redirect('crafter');
			return;
		}

		$this->toast('Info', $this->translate('MODULE_HAS_BEEN_CREATED', $moduleName));
		$this->redirect('crafter');

	}

	/**
	 * Mostra l'area di prova per eccezioni e query sperimentali.
	 */
	public function playgroundAction(): PageResponse {

		$this->pageHeading('Crafter');
		$this->menuUrl('crafter');
		Breadcrumb::path('Playground', 'crafter/playground');

		return $this->page('playgroundPage', $this->buildPlaygroundPageState(), 'Crafter');

	}

	/**
	 * Mostra l'elenco storico delle tabelle da sincronizzare con classi e moduli.
	 */
	public function synchronizeClassAction(): PageResponse {

		$this->pageHeading('Crafter');
		$this->menuUrl('crafter');

		return $this->page('synchronizeClassPage', $this->buildNewClassPageState(), 'Crafter');

	}

	/**
	 * Espone la pagina autonoma di riferimento per i componenti Bootstrap del modulo.
	 */
	public function bootstrapReferenceAction(): PageResponse {

		$this->pageTitle('Componenti Bootstrap');
		$this->pageHeading('Componenti Bootstrap');
		$this->menuUrl('crafter');
		$this->loadScript('js/crafter.js?' . Assets::suffix(), true);
		Breadcrumb::path('Componenti Bootstrap', 'crafter/bootstrapReference');

		return $this->page('bootstrapReferencePage', new CrafterBootstrapReferencePageState(), 'Componenti Bootstrap');

	}

	/**
	 * Costruisce lo stato della lista tabelle non ancora mappate.
	 */
	protected function buildNewClassPageState(): CrafterNewClassPageState {

		return new CrafterNewClassPageState($this->model->getUnmappedTables());

	}

	/**
	 * Costruisce lo stato della lista classi non ancora materializzate come tabella.
	 */
	protected function buildNewTablePageState(): CrafterNewTablePageState {

		return new CrafterNewTablePageState($this->model->getUnmappedClasses());

	}

	/**
	 * Costruisce lo stato del wizard di creazione classe.
	 */
	protected function buildClassWizardPageState(string $tableName): CrafterClassWizardPageState {

		$this->model->setupVariables($tableName);
		$form = $this->model->getClassWizardForm();
		$form->control('objectName')->value($this->model->objectName);
		$form->control('tableName')->value($tableName);

		return new CrafterClassWizardPageState($form);

	}

	/**
	 * Costruisce lo stato del wizard di creazione modulo.
	 */
	protected function buildModuleWizardPageState(string $tableName): CrafterModuleWizardPageState {

		$this->model->setupVariables($tableName);
		$groups = Group::all();
		$form = $this->buildModuleWizardForm($tableName, $groups);

		return new CrafterModuleWizardPageState($form, $groups);

	}

	/**
	 * Costruisce lo stato della pagina playground, mantenendo i side effect storici.
	 */
	protected function buildPlaygroundPageState(): CrafterPlaygroundPageState {

		$results = [];
		$choice = Router::get(0);

		// Mantiene i casi eccezione e query sperimentali già esposti dal playground tecnico.
		switch ($choice) {
			case 'AppException':
				throw new AppException('AppException message');
			case 'PairException':
				throw new PairException('PairException message');
			case 'PairExceptionWithCode':
				throw new PairException('PairException message', ErrorCodes::VIEW_RUNTIME_ERROR);
			case 'CriticalException':
				throw new CriticalException('CriticalException message');
			case 'FailureQuery':
				Pair\Orm\Database::load('SELECT `test` FROM `users`');
				break;
			default:
				break;
		}

		return new CrafterPlaygroundPageState($results);

	}

	/**
	 * Costruisce il form del wizard modulo con ACL iniziali.
	 */
	protected function buildModuleWizardForm(string $tableName, iterable $groups): Pair\Html\Form {

		$form = new Pair\Html\Form();
		$form->classForControls('form-control');
		$form->text('objectName')->required()->value($this->model->objectName)->label('OBJECT_NAME');
		$form->text('moduleName')->required()->value($this->model->moduleName)->label('MODULE_NAME');
		$form->checkbox('commonClass')->value(false)->class('switchery')->label('COMMON_CLASS');
		$form->checkbox('migration')->value(true)->class('switchery')->label('MIGRATION');
		$form->hidden('tableName')->required()->value($tableName)->label('TABLE_NAME');

		// Prepara gli switch ACL per tutti i gruppi disponibili nel runtime corrente.
		foreach ($groups as $group) {
			$form->checkbox('group' . $group->id)->value($group->default)->class('switchery')->label($group->name);
		}

		return $form;

	}

	/**
	 * Impedisce l'accesso fuori dal solo ambiente sviluppo.
	 */
	protected function checkAccess(): void {

		$alwaysGranted = in_array((string)$this->router->action, ['playground', 'accessDenied'], true);
		$isDevelopment = 'development' === Application::getEnvironment();

		if (!$alwaysGranted and !$isDevelopment) {
			$this->redirect('crafter/accessDenied');
		}

	}

	/**
	 * Restituisce il nome tabella richiesto dal route tecnico corrente.
	 */
	protected function requestedTableName(): string {

		return trim((string)(Router::get(0) ?: ''));

	}

	/**
	 * Restituisce una traduzione esplicita senza dipendere dal controller legacy.
	 */
	private function translate(string $key, string|array|null $vars = null): string {

		return Translator::do($key, $vars);

	}

	/**
	 * Legge una stringa non trim dal body della richiesta Pair v4.
	 */
	private function bodyRawString(string $key): string {

		$value = $this->input()->body($key, '');

		return (string)$value;

	}

	/**
	 * Legge un booleano normalizzato dal body della richiesta Pair v4.
	 */
	private function bodyBool(string $key, bool $default): bool {

		$value = $this->input()->body($key, $default);

		if (is_bool($value)) {
			return $value;
		}

		$normalized = strtolower(trim((string)$value));

		if (in_array($normalized, ['1', 'true', 'yes', 'on'], true)) {
			return true;
		}

		if (in_array($normalized, ['0', 'false', 'no', 'off'], true)) {
			return false;
		}

		return $default;

	}

}
