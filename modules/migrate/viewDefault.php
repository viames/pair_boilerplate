<?php

use Pair\Core\View;
use Pair\Orm\Collection;

class MigrateViewDefault extends View {

	/**
	 * Prepare the migrations list, grouped history and pending files by source.
	 */
	protected function render(): void {

		$this->pageHeading($this->lang('MIGRATIONS'));

		$pendingMigrationFiles = $this->model->getPendingMigrationFilesBySource();
		$migrations = new Collection();
		$groupedMigrations = [
			'pair' => [],
			'app' => []
		];

		if ($this->model->dbTableCheck()) {

			$migrations = $this->model->getItems('Pair\Models\Migration');
			$this->pagination->count = $this->model->countItems('Pair\Models\Migration');

			foreach ($migrations as $migration) {
				$source = $migration->source ?: 'app';

				if (!isset($groupedMigrations[$source])) {
					$groupedMigrations[$source] = [];
				}

				if (!isset($groupedMigrations[$source][$migration->file])) {
					$groupedMigrations[$source][$migration->file] = [];
				}

				$groupedMigrations[$source][$migration->file][] = $migration;

			}

		} else {
			$this->pagination->count = 0;
		}

		$this->assign('migrations', $migrations);
		$this->assign('groupedMigrations', $groupedMigrations);
		$this->assign('pendingMigrationFiles', $pendingMigrationFiles);

	}

	/**
	 * Return the URL used to execute a single pending migration.
	 */
	public function migrationRunFileUrl(string $source, string $file): string {

		return 'migrate/runFile/' . rawurlencode($source) . '/' . rawurlencode($file);

	}

	/**
	 * Return the translated label for a migration source key.
	 */
	public function sourceLabel(string $source): string {

		return $this->lang('pair' === $source ? 'PAIR_MIGRATIONS' : 'APP_MIGRATIONS');

	}

}
