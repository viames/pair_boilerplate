<?php

declare(strict_types=1);

/**
 * Doppio controller del modulo Crafter usato nei test delle `PageResponse` Pair v4.
 */
final class TestableCrafterController extends CrafterController {

	/**
	 * Stato di test della dashboard principale.
	 */
	private static ?CrafterDefaultPageState $defaultState = null;

	/**
	 * Stato di test della lista nuove classi.
	 */
	private static ?CrafterNewClassPageState $newClassState = null;

	/**
	 * Stato di test della lista nuove tabelle.
	 */
	private static ?CrafterNewTablePageState $newTableState = null;

	/**
	 * Stato di test del wizard classe.
	 */
	private static ?CrafterClassWizardPageState $classWizardState = null;

	/**
	 * Stato di test del wizard modulo.
	 */
	private static ?CrafterModuleWizardPageState $moduleWizardState = null;

	/**
	 * Stato di test del playground.
	 */
	private static ?CrafterPlaygroundPageState $playgroundState = null;

	/**
	 * Ultimo redirect intercettato nei test.
	 */
	private static ?string $capturedRedirectUrl = null;

	/**
	 * Inietta lo stato di test della dashboard.
	 */
	public static function useDefaultState(CrafterDefaultPageState $state): void {

		self::$defaultState = $state;

	}

	/**
	 * Inietta lo stato di test della lista nuove classi.
	 */
	public static function useNewClassState(CrafterNewClassPageState $state): void {

		self::$newClassState = $state;

	}

	/**
	 * Inietta lo stato di test della lista nuove tabelle.
	 */
	public static function useNewTableState(CrafterNewTablePageState $state): void {

		self::$newTableState = $state;

	}

	/**
	 * Inietta lo stato di test del wizard classe.
	 */
	public static function useClassWizardState(CrafterClassWizardPageState $state): void {

		self::$classWizardState = $state;

	}

	/**
	 * Inietta lo stato di test del wizard modulo.
	 */
	public static function useModuleWizardState(CrafterModuleWizardPageState $state): void {

		self::$moduleWizardState = $state;

	}

	/**
	 * Inietta lo stato di test del playground.
	 */
	public static function usePlaygroundState(CrafterPlaygroundPageState $state): void {

		self::$playgroundState = $state;

	}

	/**
	 * Restituisce l'ultimo URL di redirect intercettato nel caso corrente.
	 */
	public static function capturedRedirectUrl(): ?string {

		return self::$capturedRedirectUrl;

	}

	/**
	 * Pulisce gli stati statici tra un test e il successivo.
	 */
	public static function clearTestState(): void {

		self::$defaultState = null;
		self::$newClassState = null;
		self::$newTableState = null;
		self::$classWizardState = null;
		self::$moduleWizardState = null;
		self::$playgroundState = null;
		self::$capturedRedirectUrl = null;

	}

	/**
	 * Riduce il bootstrap al minimo indispensabile per i test unitari delle `PageResponse`.
	 */
	protected function boot(): void {

		$this->model = new CrafterModel();
		$this->app->pageDescription = '';
		$this->app->pageHeaderActions = '';
		$this->app->activeMenuItem = '';
		$currentUser = new Pair\Models\User();
		$currentUser->super = true;
		$this->app->currentUser = $currentUser;

	}

	/**
	 * Intercetta i redirect Pair nei test senza inviare header o terminare il processo.
	 */
	public function redirect(?string $url = null, bool $externalUrl = false): void {

		self::$capturedRedirectUrl = $url ?? 'crafter';

	}

	/**
	 * Restituisce lo stato di test della dashboard quando presente.
	 */
	protected function buildNewClassPageState(): CrafterNewClassPageState {

		return self::$newClassState ?? parent::buildNewClassPageState();

	}

	/**
	 * Restituisce lo stato di test della lista nuove tabelle quando presente.
	 */
	protected function buildNewTablePageState(): CrafterNewTablePageState {

		return self::$newTableState ?? parent::buildNewTablePageState();

	}

	/**
	 * Restituisce lo stato di test del wizard classe quando presente.
	 */
	protected function buildClassWizardPageState(string $tableName): CrafterClassWizardPageState {

		return self::$classWizardState ?? parent::buildClassWizardPageState($tableName);

	}

	/**
	 * Restituisce lo stato di test del wizard modulo quando presente.
	 */
	protected function buildModuleWizardPageState(string $tableName): CrafterModuleWizardPageState {

		return self::$moduleWizardState ?? parent::buildModuleWizardPageState($tableName);

	}

	/**
	 * Restituisce lo stato di test del playground quando presente.
	 */
	protected function buildPlaygroundPageState(): CrafterPlaygroundPageState {

		return self::$playgroundState ?? parent::buildPlaygroundPageState();

	}

	/**
	 * Forza un nome tabella stabile nei test senza dipendere dal router reale.
	 */
	protected function requestedTableName(): string {

		return 'test_table';

	}

	/**
	 * Disattiva il guard d'ambiente nei test del boundary Pair v4.
	 */
	protected function checkAccess(): void {

	}

}
