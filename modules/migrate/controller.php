<?php

use Pair\Core\Controller;
use Pair\Core\Router;
use Pair\Html\Breadcrumb;

class MigrateController extends Controller {

	/**
	 * Prepare breadcrumbs for the migrations module.
	 */
	protected function _init(): void {

		Breadcrumb::path($this->lang('MIGRATIONS'), 'migrate');

	}

	/**
	 * Backward-compatible alias for the historical `migrate/migrate` route.
	 */
	public function migrateAction(): void {

		$this->runAction();

	}

	/**
	 * Execute Pair and application migrations and then redirect back to the list.
	 */
	public function runAction(): void {

		$this->setView('default');

		try {
			$this->model->runAllMigrations();
		} catch (Throwable $e) {
			$this->modal($this->lang('ERROR'), $e->getMessage(), 'error');
			$this->redirect('migrate');
			return;
		}

		if ($this->model->hasIncompleteMigrationForSource('pair') || $this->model->hasIncompleteMigrationForSource('app')) {
			$this->modal($this->lang('WARNING'), $this->lang('MIGRATIONS_COMPLETED_WITH_ERRORS'), 'warning');
			$this->redirect('migrate');
			return;
		}

		$this->modal($this->lang('DONE'), $this->lang('MIGRATIONS_SUCCESSFUL'), 'success');
		$this->redirect('migrate');

	}

	/**
	 * Execute a single pending migration file for the requested source.
	 */
	public function runFileAction(): void {

		$this->setView('default');

		$source = trim((string)Router::get(0));
		$file = urldecode(trim((string)Router::get(1)));

		if ('' === $source || '' === $file) {
			$this->modal($this->lang('ERROR'), $this->lang('MIGRATION_FILE_INVALID'), 'error');
			$this->redirect('migrate');
			return;
		}

		try {
			$this->model->runMigrationFileForSource($file, $source);
		} catch (Throwable $e) {
			$this->modal($this->lang('ERROR'), $e->getMessage(), 'error');
			$this->redirect('migrate');
			return;
		}

		if ($this->model->hasIncompleteMigrationForSource($source, $file)) {
			$this->modal($this->lang('WARNING'), $this->lang('MIGRATION_FILE_COMPLETED_WITH_ERRORS', $file), 'warning');
			$this->redirect('migrate');
			return;
		}

		$this->modal($this->lang('DONE'), $this->lang('MIGRATION_FILE_SUCCESSFUL', $file), 'success');
		$this->redirect('migrate');

	}

}
