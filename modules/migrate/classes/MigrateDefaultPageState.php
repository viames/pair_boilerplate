<?php

declare(strict_types=1);

use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;
use Pair\Models\Migration;
use Pair\Orm\Collection;

/**
 * Typed state for the migration dashboard.
 */
final readonly class MigrateDefaultPageState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the page state.
	 *
	 * @param	array<string, array<string, Migration[]>>	$groupedMigrations	Migration rows grouped by source and file.
	 * @param	array<string, string[]>					$pendingMigrationFiles	Pending filenames grouped by source.
	 */
	public function __construct(
		public Collection $migrations,
		public array $groupedMigrations,
		public array $pendingMigrationFiles,
		public string $paginationBar
	) {}

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

		return BoilerplateLayout::translate('pair' === $source ? 'PAIR_MIGRATIONS' : 'APP_MIGRATIONS');

	}

	/**
	 * Export the page state as an array for debugging and migration tooling.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'migrations' => $this->migrations,
			'groupedMigrations' => $this->groupedMigrations,
			'pendingMigrationFiles' => $this->pendingMigrationFiles,
			'paginationBar' => $this->paginationBar,
		];

	}

}
