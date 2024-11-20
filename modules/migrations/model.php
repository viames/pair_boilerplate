<?php

use Pair\Core\Model;
use Pair\Models\Migration;
use Pair\Orm\Database;

class MigrationsModel extends Model {

	/**
	 * Path to the folder containing the migration files.
	 */
	private $folder = APPLICATION_PATH . '/migrations/';

	/**
	 * File name for the creation of the migrations table.
	 */
	const CREATE_MIGRATION_TABLE_FILE = '001_create_migrations_table.sql';

	public function getQuery(string $class): string {

		return 'SELECT *, `created_at` - `updated_at` AS `execution_time` FROM `migrations`';

	}

	protected function getOrderOptions(): array {

		return [
			1  => '`created_at`',
			2  => '`created_at` DESC',
			3  => '`file`, `query_index`',
			4  => '`file` DESC, `query_index` DESC',
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

	private function continueIncompleteMigration(): void {

		// richiede al DB un eventuale riga con result = 0 (migrazione incompleta)
		$query = 'SELECT `file`, `query_index` FROM `migrations` WHERE `result` = 0 ORDER BY `file`, `query_index`';

		$incomplete = Database::load($query, [], PAIR_DB_OBJECT);

		// se c’è una riga con result = 0, esegue il relativo file a partire dalla riga con result = 0
		if ($incomplete) {

			$this->migrateFile($incomplete->file, $incomplete->query_index);

		}

	}

	/**
	 * Verifica se esiste la tabella `migrations` nel DB, altrimenti la crea.
	 */
	private function checkMigrationTable(): void {

		// verifica se esiste la tabella `migrations` nel DB
		$query = 'SHOW TABLES LIKE "migrations"';

		$result = (bool)Database::load($query, [], PAIR_DB_RESULT);

		// crea la tabella migrations se non esiste
		if (!$result) {

			$this->migrateFile(self::CREATE_MIGRATION_TABLE_FILE);

		}

	}

	/**
	 * Ottiene la lista di file ancora da migrare.
	 */
	public function getListOfMigrationFiles(): array {

		$migrationFiles = [];

		// ottiene la lista completa dei file
		$files = scandir($this->folder);

		// filtra i file .sql
		$files = array_filter($files, function($file) {
			return '.sql' === substr($file, -4);
		});

		$query = 'SELECT COUNT(*) FROM `migrations` WHERE `file` = ?';

		// esegue i restanti file non eseguiti
		foreach ($files as $file) {

			// verifica se il file è già stato eseguito
			if (!(bool)Database::load($query, [$file], PAIR_DB_RESULT)) {
				$migrationFiles[] = $file;
			}

		}

		return $migrationFiles;

	}

	/**
	 * Applica tutte le query di un file. Se indicato un indice di partenza,
	 * esegue solo le query a partire da quell’indice.
	 */
	private function migrateFile(string $file, int $index=1): void {

		// recupera le query e le descrizioni trovate nel file
		$migrations = $this->parseMigrationFile($this->folder . $file);

		foreach ($migrations as $m) {

			// fissa il tempo di inizio, senza salvare
			$migration = new Migration();
			$migration->file = $file;
			$migration->createdAt = new DateTime();

			$affectedRows = Database::run($m->sql);

			$result = FALSE === $this->db->getLastError() ? TRUE : FALSE;

			// si può salvare il record solo dopo la creazione della tabella
			$migration->queryIndex = $index;
			$migration->description = $m->description;
			$migration->result = $result;
			$migration->affectedRows = $affectedRows;
			$migration->store();

			// esce se c’è stato un errore
			if (!$result) {
				return;
			}

			$index++;

		}

	}

	public function runMigration(): bool {

		// verifica l’esistenza della tabella `migrations`
		$this->checkMigrationTable();

		// esegue un’eventuale migrazione incompleta
		$this->continueIncompleteMigration();

		// file da migrare integralmente
		$migrationFiles = $this->getListOfMigrationFiles();

		foreach ($migrationFiles as $file) {
			$this->migrateFile($file);
		}

		return TRUE;

	}

	/**
	 * Restituisce un array di oggetti stdClass con le migrazioni presenti nel file specificato.
	 */
	private function parseMigrationFile(string $filePath): array {

		$handle = fopen($filePath, 'r');

		// oggetto da restituire
		$migrations = [];

		// inizializza i contenitori vuoti
		$description = '';
		$sql = '';

		if ($handle) {

			while (FALSE !== ($line = fgets($handle))) {

				$line = trim($line);

				// la riga con commento inizia con --
				if (strpos($line, '--') === 0) {

					// salva il commento corrente rimuovendo --
					$description .= substr($line, 2);

				} elseif (!empty($line)) {

					// accumula codice SQL fino al ;
					$sql .= "\n" . $line;

					// se c’è il punto e virgola alla fine, assembla l’oggetto
					if (FALSE !== strpos($line, ';')) {

						$m = new stdClass();
						$m->description = trim($description);
						$m->sql = trim($sql);

						// accoda per la restituzione
						$migrations[] = $m;

						// inizializza i contenitori vuoti
						$description = '';
						$sql = '';

					}

				}

			}

			fclose($handle);
		}

		return $migrations;

	}

}