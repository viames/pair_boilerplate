<?php

declare(strict_types=1);

use Pair\Core\Router;
use Pair\Html\Breadcrumb;
use Pair\Models\Migration;
use Pair\Orm\Collection;
use Pair\Web\PageResponse;

class MigrateController extends BoilerplateController {

	/**
	 * Migration module data helper.
	 */
	private MigrateModel $model;

	/**
	 * Prepare breadcrumbs for the migrations module.
	 */
	protected function boot(): void {

		$this->model = new MigrateModel();
		Breadcrumb::path($this->translate('MIGRATIONS'), 'migrate');

	}

	/**
	 * Render migration history and pending migration files.
	 */
	public function defaultAction(): PageResponse {

		$this->pageHeading($this->translate('MIGRATIONS'));

		return $this->page('default', $this->buildDefaultPageState(), $this->translate('MIGRATIONS'));

	}

	/**
	 * Backward-compatible alias for the historical `migrate/migrate` route.
	 */
	public function migrateAction(): ?PageResponse {

		return $this->runAction();

	}

	/**
	 * Execute Pair and application migrations and then redirect back to the list.
	 */
	public function runAction(): ?PageResponse {

		try {
			$this->model->runAllMigrations();
		} catch (Throwable $e) {
			$this->modal($this->translate('ERROR'), $e->getMessage(), 'error');
			$this->redirect('migrate');
			return null;
		}

		if ($this->model->hasIncompleteMigrationForSource('pair') || $this->model->hasIncompleteMigrationForSource('app')) {
			$this->modal($this->translate('WARNING'), $this->translate('MIGRATIONS_COMPLETED_WITH_ERRORS'), 'warning');
			$this->redirect('migrate');
			return null;
		}

		$this->modal($this->translate('DONE'), $this->translate('MIGRATIONS_SUCCESSFUL'), 'success');
		$this->redirect('migrate');

		return null;

	}

	/**
	 * Execute a single pending migration file for the requested source.
	 */
	public function runFileAction(): ?PageResponse {

		$source = trim((string)Router::get(0));
		$file = urldecode(trim((string)Router::get(1)));

		if ('' === $source || '' === $file) {
			$this->modal($this->translate('ERROR'), $this->translate('MIGRATION_FILE_INVALID'), 'error');
			$this->redirect('migrate');
			return null;
		}

		try {
			$this->model->runMigrationFileForSource($file, $source);
		} catch (Throwable $e) {
			$this->modal($this->translate('ERROR'), $e->getMessage(), 'error');
			$this->redirect('migrate');
			return null;
		}

		if ($this->model->hasIncompleteMigrationForSource($source, $file)) {
			$this->modal($this->translate('WARNING'), $this->translate('MIGRATION_FILE_COMPLETED_WITH_ERRORS', $file), 'warning');
			$this->redirect('migrate');
			return null;
		}

		$this->modal($this->translate('DONE'), $this->translate('MIGRATION_FILE_SUCCESSFUL', $file), 'success');
		$this->redirect('migrate');

		return null;

	}

	/**
	 * Build the migration dashboard state.
	 */
	private function buildDefaultPageState(): MigrateDefaultPageState {

		$pendingMigrationFiles = $this->model->getPendingMigrationFilesBySource();
		$migrations = new Collection();
		$groupedMigrations = [
			Migration::SOURCE_PAIR => [],
			Migration::SOURCE_APP => [],
		];

		$pagination = $this->buildPagination();
		$this->model->pagination = $pagination;

		if ($this->model->dbTableCheck()) {

			$migrations = $this->model->getItems(Migration::class);
			$pagination->count = $this->model->countItems(Migration::class);

			foreach ($migrations as $migration) {
				$source = $migration->source ?: Migration::SOURCE_APP;

				if (!isset($groupedMigrations[$source])) {
					$groupedMigrations[$source] = [];
				}

				if (!isset($groupedMigrations[$source][$migration->file])) {
					$groupedMigrations[$source][$migration->file] = [];
				}

				$groupedMigrations[$source][$migration->file][] = $migration;
			}

		} else {
			$pagination->count = 0;
		}

		return new MigrateDefaultPageState(
			$migrations,
			$groupedMigrations,
			$pendingMigrationFiles,
			$pagination->render()
		);

	}

}
