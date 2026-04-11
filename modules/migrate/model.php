<?php

use Pair\Core\Model;
use Pair\Exceptions\PairException;
use Pair\Models\Migration;
use Pair\Orm\Database;

class MigrateModel extends Model {

	/**
	 * Path to the folder containing application migration files.
	 */
	private string $folder = APPLICATION_PATH . '/migrations/';

	/**
	 * Path to the folder containing Pair vendor migration files.
	 */
	private string $pairFolder = APPLICATION_PATH . '/vendor/viames/pair/migrations/';

	/**
	 * Suffix used to discover the bootstrap file that creates the `migrations` table.
	 */
	private const CREATE_MIGRATION_TABLE_SUFFIX = '_create_migrations_table.sql';

	/**
	 * File names of Pair migrations that need local idempotency guards.
	 */
	private const GUARDED_PAIR_MIGRATIONS = [
		'20260227_migration_source.sql',
		'20260228_templates_palette.sql',
		'20260403_user_auth_tables.sql'
	];

	/**
	 * Check if the database connection is currently available.
	 */
	public function hasDatabaseConnection(): bool {

		return $this->db->isConnected();

	}

	/**
	 * Check if database can connect and the `migrations` table exists.
	 */
	public function dbTableCheck(): bool {

		if (!$this->hasDatabaseConnection()) {
			return false;
		}

		try {
			return $this->db->tableExists('migrations');
		} catch (Throwable $e) {
			return false;
		}

	}

	/**
	 * Return the base query used by the generic Pair list helpers.
	 */
	public function getQuery(string $class): string {

		return 'SELECT *, `created_at` - `updated_at` AS `execution_time` FROM `migrations`';

	}

	/**
	 * Return the available sort options for the migrations list.
	 */
	protected function getOrderOptions(): array {

		return [
			1  => '`created_at` DESC, `id` DESC',
			2  => '`created_at`, `id`',
			3  => '`source`, `file`, `query_index`',
			4  => '`source` DESC, `file` DESC, `query_index` DESC',
			5  => '`description`',
			6  => '`description` DESC',
			7  => '`result`',
			8  => '`result` DESC',
			9  => '`affected_rows`',
			10 => '`affected_rows` DESC',
			11 => '`execution_time`',
			12 => '`execution_time` DESC',
		];

	}

	/**
	 * Return the absolute path of the application migrations folder.
	 */
	public function getAppMigrationFolder(): string {

		return $this->folder;

	}

	/**
	 * Return the absolute path of the Pair vendor migrations folder.
	 */
	public function getPairMigrationFolder(): string {

		return $this->pairFolder;

	}

	/**
	 * Execute Pair migrations first and application migrations immediately after.
	 *
	 * @throws PairException
	 */
	public function runAllMigrations(): void {

		$this->runMigrationsFromFolder($this->getPairMigrationFolder(), Migration::SOURCE_PAIR);
		$this->runMigrationsFromFolder($this->getAppMigrationFolder(), Migration::SOURCE_APP);

	}

	/**
	 * Execute the pending application migrations only.
	 *
	 * @throws PairException
	 */
	public function runMigration(): void {

		$this->runMigrationsFromFolder($this->getAppMigrationFolder(), Migration::SOURCE_APP);

	}

	/**
	 * Execute a single pending application migration file.
	 *
	 * @throws PairException
	 */
	public function runMigrationFile(string $file): void {

		$this->runMigrationFileForSource($file, Migration::SOURCE_APP);

	}

	/**
	 * Execute a single pending migration file for the given source.
	 *
	 * @throws PairException
	 */
	public function runMigrationFileForSource(string $file, string $source): void {

		$source = $this->validateSource($source);
		$folder = $this->getFolderBySource($source);

		$this->ensureMigrationEnvironment($folder);

		$file = $this->validateMigrationFileForSource($file, $folder, $source);

		// An incomplete file must always be resumed before a new pending file is launched.
		$this->continueIncompleteMigrationForSource($folder, $source);

		if ($this->hasIncompleteMigrationForSource($source)) {
			throw new PairException('È presente una migration incompleta per la source ' . $source . '. Correggila prima di proseguire.');
		}

		if (!$this->isPendingMigrationFileForSource($file, $folder, $source)) {
			throw new PairException('Il file migration è già stato eseguito oppure non è più pendente: ' . $file);
		}

		$this->migrateFileForSource($file, 1, $folder, $source);

	}

	/**
	 * Return the pending application migrations only.
	 */
	public function getListOfMigrationFiles(): array {

		return $this->getListOfMigrationFilesForSource($this->getAppMigrationFolder(), Migration::SOURCE_APP);

	}

	/**
	 * Return pending migration files grouped by source.
	 *
	 * @return array{pair:string[],app:string[]}
	 */
	public function getPendingMigrationFilesBySource(): array {

		return [
			Migration::SOURCE_PAIR => $this->getListOfMigrationFilesForSource($this->getPairMigrationFolder(), Migration::SOURCE_PAIR),
			Migration::SOURCE_APP => $this->getListOfMigrationFilesForSource($this->getAppMigrationFolder(), Migration::SOURCE_APP)
		];

	}

	/**
	 * Check whether the application source has incomplete migration rows.
	 */
	public function hasIncompleteMigration(?string $file = null): bool {

		return $this->hasIncompleteMigrationForSource(Migration::SOURCE_APP, $file);

	}

	/**
	 * Check whether a source has incomplete migration rows.
	 */
	public function hasIncompleteMigrationForSource(string $source, ?string $file = null): bool {

		$source = $this->validateSource($source);

		if (!$this->dbTableCheck()) {
			return false;
		}

		$query = 'SELECT COUNT(*) FROM `migrations` WHERE `result` = 0 AND `source` = ?';
		$params = [$source];

		if ($file !== null) {
			$query .= ' AND `file` = ?';
			$params[] = $file;
		}

		return (bool)Database::load($query, $params, Database::RESULT);

	}

	/**
	 * Execute all pending migration files found in the given folder for the given source.
	 *
	 * @throws PairException
	 */
	public function runMigrationsFromFolder(string $folder, string $source): void {

		$source = $this->validateSource($source);
		$folder = $this->normalizeFolder($folder);

		$this->ensureMigrationEnvironment($folder);

		$this->continueIncompleteMigrationForSource($folder, $source);

		if ($this->hasIncompleteMigrationForSource($source)) {
			return;
		}

		foreach ($this->getListOfMigrationFilesForSource($folder, $source) as $file) {
			$this->migrateFileForSource($file, 1, $folder, $source);
		}

	}

	/**
	 * Create the `migrations` table using the local bootstrap file.
	 *
	 * The bootstrap file is intentionally executed without writing history rows because the
	 * target table does not exist yet and because this file is an internal bootstrap concern.
	 *
	 * @throws PairException
	 */
	private function createMigrationTable(): void {

		$file = $this->discoverCreateMigrationTableFile();
		$bootstrapStatements = $this->parseMigrationFile($this->getAppMigrationFolder() . $file);

		if (!count($bootstrapStatements)) {
			throw new PairException('Il file bootstrap delle migration è vuoto: ' . $file);
		}

		foreach ($bootstrapStatements as $statement) {
			Database::run($statement->sql);
		}

	}

	/**
	 * Discover the bootstrap migration filename by suffix instead of hard-coding a prefix.
	 *
	 * @throws PairException
	 */
	private function discoverCreateMigrationTableFile(): string {

		$files = $this->listSqlFiles($this->getAppMigrationFolder(), false);

		foreach ($files as $file) {
			if ($this->isCreateMigrationTableFile($file)) {
				return $file;
			}
		}

		throw new PairException('Il file bootstrap per la tabella migrations non è stato trovato.');

	}

	/**
	 * Return the list of still-pending files for a folder/source pair.
	 */
	private function getListOfMigrationFilesForSource(string $folder, string $source): array {

		$source = $this->validateSource($source);
		$folder = $this->normalizeFolder($folder, false);

		if (!is_dir($folder)) {
			return [];
		}

		$files = $this->listSqlFiles($folder, true);

		if (Migration::SOURCE_APP === $source) {
			$files = array_values(array_filter($files, fn (string $file): bool => !$this->isCreateMigrationTableFile($file)));
		}

		if (!$this->dbTableCheck()) {
			return $files;
		}

		$query = 'SELECT COUNT(*) FROM `migrations` WHERE `file` = ? AND `source` = ?';
		$pendingFiles = [];

		foreach ($files as $file) {
			if (!(bool)Database::load($query, [$file, $source], Database::RESULT)) {
				$pendingFiles[] = $file;
			}
		}

		return $pendingFiles;

	}

	/**
	 * Resume the first incomplete migration for the given source, if one exists.
	 *
	 * @throws PairException
	 */
	private function continueIncompleteMigrationForSource(string $folder, string $source): void {

		if (!$this->dbTableCheck()) {
			return;
		}

		$query =
			'SELECT `file`, `query_index`
			FROM `migrations`
			WHERE `result` = 0 AND `source` = ?
			ORDER BY `file`, `query_index`
			LIMIT 1';

		$incomplete = Database::load($query, [$source], Database::OBJECT);

		if ($incomplete) {
			$this->migrateFileForSource((string)$incomplete->file, (int)$incomplete->query_index, $folder, $source);
		}

	}

	/**
	 * Execute a migration file from the given start index and persist execution history by source.
	 *
	 * @throws PairException
	 */
	private function migrateFileForSource(string $file, int $index, string $folder, string $source): void {

		$file = $this->validateMigrationFileForSource($file, $folder, $source);
		$migrations = $this->getGuardedMigrationsForSource($folder . $file, $file, $source);

		if ($this->dbTableCheck()) {
			// Failed rows must be removed before a retry so the file can be resumed cleanly.
			Database::run(
				'DELETE FROM `migrations` WHERE `file` = ? AND `source` = ? AND `result` = 0 AND `query_index` >= ?',
				[$file, $source, $index]
			);
		}

		if ($index > 1) {
			$migrations = array_slice($migrations, $index - 1);
		}

		if (!count($migrations)) {
			$this->storeSkippedMigration($file, $source, $index);
			return;
		}

		foreach ($migrations as $migrationData) {

			$migration = new Migration();
			$migration->file = $file;
			$migration->source = $source;
			$migration->queryIndex = $index;
			$migration->description = $migrationData->description;
			$migration->createdAt = new DateTime();
			$migration->result = true;

			try {
				$migration->affectedRows = Database::run($migrationData->sql);
			} catch (Throwable $e) {
				$migration->result = false;
				$migration->affectedRows = 0;
			}

			$migration->store();

			// Stop on the first failure so resume can restart from the exact failed query.
			if (!$migration->result) {
				break;
			}

			$index++;

		}

	}

	/**
	 * Store a synthetic history row when a guarded migration is already satisfied by the current schema.
	 */
	private function storeSkippedMigration(string $file, string $source, int $index): void {

		if (!$this->dbTableCheck()) {
			return;
		}

		$migration = new Migration();
		$migration->file = $file;
		$migration->source = $source;
		$migration->queryIndex = $index;
		$migration->description = 'Migration già allineata allo schema corrente.';
		$migration->affectedRows = 0;
		$migration->result = true;
		$migration->createdAt = new DateTime();
		$migration->store();

	}

	/**
	 * Parse a migration file and apply local guards when running known Pair migrations.
	 */
	private function getGuardedMigrationsForSource(string $filePath, string $file, string $source): array {

		$migrations = $this->parseMigrationFile($filePath);

		if (Migration::SOURCE_PAIR !== $source || !in_array($file, self::GUARDED_PAIR_MIGRATIONS, true)) {
			return $migrations;
		}

		switch ($file) {
			case '20260227_migration_source.sql':
				return $this->guardPairMigrationSource($migrations);
			case '20260228_templates_palette.sql':
				return $this->guardPairTemplatesPaletteMigration();
			case '20260403_user_auth_tables.sql':
				return $this->guardPairUserAuthTablesMigration();
			default:
				return $migrations;
		}

	}

	/**
	 * Skip the Pair source migration when the `source` column is already present.
	 */
	private function guardPairMigrationSource(array $migrations): array {

		if ($this->db->describeColumn('migrations', 'source')) {
			return [];
		}

		return $migrations;

	}

	/**
	 * Build a safe local version of the Pair palette migration based on the current schema state.
	 */
	private function guardPairTemplatesPaletteMigration(): array {

		$paletteColumn = $this->db->describeColumn('templates', 'palette');
		$derivedColumn = $this->db->describeColumn('templates', 'derived');
		$isJsonPalette = $paletteColumn && str_starts_with(strtolower((string)$paletteColumn->Type), 'json');
		$migrations = [];

		if (!$isJsonPalette) {
			$sql = <<<'SQL'
UPDATE `templates`
SET `palette` = CASE
	WHEN `palette` IS NULL OR TRIM(`palette`) = '' THEN JSON_ARRAY()
	WHEN JSON_VALID(`palette`) THEN CAST(`palette` AS JSON)
	ELSE CAST(
		CONCAT(
			'["',
			REPLACE(
				REPLACE(
					REPLACE(
						REPLACE(
							TRIM(`palette`),
							', ',
							','
						),
						' ,',
						','
					),
					'\\',
					'\\\\'
				),
				'"',
				'\\"'
			),
			'"]'
		) AS JSON
	)
END;
SQL;

			$migrations[] = $this->createMigrationStatement(
				'Converte la palette legacy da CSV a JSON.',
				$sql
			);
		}

		$alterClauses = [];

		if ($derivedColumn) {
			$alterClauses[] = 'DROP COLUMN `derived`';
		}

		if (!$isJsonPalette) {
			$alterClauses[] = 'MODIFY COLUMN `palette` JSON NOT NULL';
		}

		if (count($alterClauses)) {
			$migrations[] = $this->createMigrationStatement(
				'Allinea la struttura della tabella templates al formato Pair attuale.',
				"ALTER TABLE `templates`\n\t" . implode(",\n\t", $alterClauses) . ';'
			);
		}

		return $migrations;

	}

	/**
	 * Build safe rename statements for Pair auth tables only when source and destination states require it.
	 */
	private function guardPairUserAuthTablesMigration(): array {

		$migrations = [];

		if ($this->db->tableExists('users_remembers') && !$this->db->tableExists('user_remembers')) {
			$migrations[] = $this->createMigrationStatement(
				'Rinomina la tabella remember-me al formato Pair attuale.',
				'RENAME TABLE `users_remembers` TO `user_remembers`;'
			);
		}

		if ($this->db->tableExists('users_passkeys') && !$this->db->tableExists('user_passkeys')) {
			$migrations[] = $this->createMigrationStatement(
				'Rinomina la tabella passkeys al formato Pair attuale.',
				'RENAME TABLE `users_passkeys` TO `user_passkeys`;'
			);
		}

		return $migrations;

	}

	/**
	 * Create a standard migration statement object.
	 */
	private function createMigrationStatement(string $description, string $sql): stdClass {

		$statement = new stdClass();
		$statement->description = $description;
		$statement->sql = $sql;

		return $statement;

	}

	/**
	 * Validate a migration filename and ensure it belongs to the given folder/source pair.
	 *
	 * @throws PairException
	 */
	private function validateMigrationFileForSource(string $file, string $folder, string $source): string {

		$file = basename(trim($file));

		if (!preg_match('/^\d+_[A-Za-z0-9._-]+\.sql$/', $file)) {
			throw new PairException('Il file migration richiesto non è valido: ' . $file);
		}

		if (Migration::SOURCE_APP === $source && $this->isCreateMigrationTableFile($file)) {
			throw new PairException('Il file bootstrap della tabella migrations non può essere eseguito manualmente.');
		}

		if (!file_exists($folder . $file)) {
			throw new PairException('Il file migration non esiste: ' . $file);
		}

		return $file;

	}

	/**
	 * Check whether the given migration file is still pending for the given folder/source.
	 */
	private function isPendingMigrationFileForSource(string $file, string $folder, string $source): bool {

		return in_array($file, $this->getListOfMigrationFilesForSource($folder, $source), true);

	}

	/**
	 * Ensure that the migration folder exists and that the database is available.
	 *
	 * @throws PairException
	 */
	private function ensureMigrationEnvironment(string $folder): void {

		$folder = $this->normalizeFolder($folder);

		if (!$this->hasDatabaseConnection()) {
			throw new PairException('Connessione al database non riuscita.');
		}

		if (!$this->dbTableCheck()) {
			$this->createMigrationTable();
		}

	}

	/**
	 * Normalize a folder path and optionally ensure it exists.
	 *
	 * @throws PairException
	 */
	private function normalizeFolder(string $folder, bool $mustExist = true): string {

		$folder = rtrim($folder, '/') . '/';

		if ($mustExist && !is_dir($folder)) {
			throw new PairException('Cartella migration non trovata: ' . $folder);
		}

		return $folder;

	}

	/**
	 * Resolve a folder path from a known migration source.
	 *
	 * @throws PairException
	 */
	private function getFolderBySource(string $source): string {

		return match ($this->validateSource($source)) {
			Migration::SOURCE_APP => $this->getAppMigrationFolder(),
			Migration::SOURCE_PAIR => $this->getPairMigrationFolder(),
		};

	}

	/**
	 * Validate a migration source value.
	 *
	 * @throws PairException
	 */
	private function validateSource(string $source): string {

		$source = strtolower(trim($source));

		if (!in_array($source, [Migration::SOURCE_APP, Migration::SOURCE_PAIR], true)) {
			throw new PairException('La source della migration non è valida: ' . $source);
		}

		return $source;

	}

	/**
	 * Return whether a filename is the internal bootstrap file for the migrations table.
	 */
	private function isCreateMigrationTableFile(string $file): bool {

		return str_ends_with($file, self::CREATE_MIGRATION_TABLE_SUFFIX);

	}

	/**
	 * List SQL files from a migration folder using a predictable numeric-prefix ordering.
	 */
	private function listSqlFiles(string $folder, bool $includeOnlyValidFiles = true): array {

		$files = array_values(array_filter(scandir($folder) ?: [], function (string $file) use ($includeOnlyValidFiles): bool {

			if (!str_ends_with($file, '.sql')) {
				return false;
			}

			return !$includeOnlyValidFiles || (bool)preg_match('/^\d+_[A-Za-z0-9._-]+\.sql$/', $file);

		}));

		usort($files, function (string $left, string $right): int {

			$leftPrefix = $this->getMigrationPrefix($left);
			$rightPrefix = $this->getMigrationPrefix($right);

			if ($leftPrefix === $rightPrefix) {
				return strcmp($left, $right);
			}

			return $leftPrefix <=> $rightPrefix;

		});

		return $files;

	}

	/**
	 * Extract the numeric prefix of a migration filename for deterministic ordering.
	 */
	private function getMigrationPrefix(string $file): int {

		if (preg_match('/^(\d+)_/', $file, $matches)) {
			return (int)$matches[1];
		}

		return PHP_INT_MAX;

	}

	/**
	 * Parse a migration file into an array of objects with `description` and `sql`.
	 *
	 * Notes:
	 * - Supports `DELIMITER <token>` lines.
	 * - Supports stored routines written with the default `;` delimiter by waiting for `END;`.
	 *
	 * @return stdClass[]
	 */
	private function parseMigrationFile(string $filePath): array {

		$lines = file($filePath, FILE_IGNORE_NEW_LINES);

		if (false === $lines) {
			return [];
		}

		$migrations = [];
		$description = '';
		$sql = '';
		$delimiter = ';';
		$expectsEndKeyword = false;

		foreach ($lines as $rawLine) {

			$line = trim($rawLine);

			if ('' === $line) {
				continue;
			}

			if (preg_match('/^DELIMITER\s+(.+)$/i', $line, $matches)) {
				$delimiter = trim($matches[1]);
				continue;
			}

			if (str_starts_with($line, '--') && '' === $sql) {
				$comment = trim(substr($line, 2));
				$description .= '' === $description ? $comment : ' ' . $comment;
				continue;
			}

			if ('' === $sql) {
				$expectsEndKeyword = (';' === $delimiter)
					&& (bool)preg_match('/^\s*CREATE\s+(?:DEFINER\s*=\s*[^\s]+\s+)?(TRIGGER|PROCEDURE|FUNCTION|EVENT)\b/i', $rawLine);
			}

			$sql .= "\n" . $rawLine;
			$trimmedSql = rtrim($sql);
			$statementComplete = false;

			if (';' !== $delimiter) {
				$statementComplete = str_ends_with($trimmedSql, $delimiter);
			} else if ($expectsEndKeyword) {
				$statementComplete = (bool)preg_match('/\bEND\s*;$/i', $trimmedSql);
			} else {
				$statementComplete = str_ends_with($trimmedSql, ';');
			}

			if ($statementComplete) {

				if (';' !== $delimiter && str_ends_with($trimmedSql, $delimiter)) {
					$trimmedSql = substr($trimmedSql, 0, -strlen($delimiter)) . ';';
				}

				$migrations[] = $this->createMigrationStatement(trim($description), trim($trimmedSql));
				$description = '';
				$sql = '';
				$expectsEndKeyword = false;

			}

		}

		return $migrations;

	}

}
