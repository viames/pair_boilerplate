<?php

class Installer {

	/**
	 * Absolute path to project root.
	 */
	private string $rootFolder;

	/**
	 * Subfolder of this project from web-server root.
	 */
	private string $baseUri;

	/**
	 * DB handler.
	 */
	private ?PDO $dbh;

	/**
	 * Flag to set DB_UTF8 constant.
	 */
	private bool $forceDbUtf8 = false;

	/**
	 * List of installer notifications.
	 */
	private array $notifications = [];

	/**
	 * List of installer errors.
	 */
	private array $errors = [];

	/**
	 * Initialize installer paths from the current request.
	 */
	public function __construct() {

		// the app root
		$this->rootFolder = dirname(dirname(__FILE__));

		// Normalize the project subpath so generated URLs do not start with a double slash.
		$baseUri = dirname(dirname((string)parse_url($_SERVER['PHP_SELF'], PHP_URL_PATH)));
		$this->baseUri = '/' === $baseUri ? '' : rtrim($baseUri, '/');

		if (PHP_SESSION_NONE === session_status()) {
			session_start();
		}

	}

	/**
	 * Build a root-relative URL for installer assets and form actions.
	 */
	private function url(string $path = ''): string {

		return $this->baseUri . '/' . ltrim($path, '/');

	}

	/**
	 * Escape public installer output for safe HTML rendering.
	 */
	private function escape(string $value): string {

		return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

	}

	/**
	 * Normalize user input that must fit on one line in generated files or messages.
	 */
	private function singleLine(?string $value): string {

		return trim(str_replace(["\r", "\n", "\0"], [' ', ' ', ''], (string)$value));

	}

	/**
	 * Format a string value for the simple Pair .env parser.
	 */
	private function envString(?string $value): string {

		return '"' . $this->singleLine($value) . '"';

	}

	/**
	 * Return true when a MySQL identifier is safe to embed in quoted SQL identifier syntax.
	 */
	private function isValidMysqlIdentifier(string $identifier): bool {

		return (bool)preg_match('/^[A-Za-z0-9_]{1,64}$/', $identifier);

	}

	/**
	 * Quote a MySQL identifier after validating its allowed installer format.
	 */
	private function mysqlIdentifier(string $identifier): string {

		if (!$this->isValidMysqlIdentifier($identifier)) {
			throw new \InvalidArgumentException('Invalid MySQL identifier: ' . $identifier);
		}

		return '`' . $identifier . '`';

	}

	/**
	 * Return the current installer CSRF token, creating it when missing.
	 */
	private function csrfToken(): string {

		if (!isset($_SESSION['installer_csrf_token']) || !is_string($_SESSION['installer_csrf_token'])) {
			$_SESSION['installer_csrf_token'] = bin2hex(random_bytes(32));
		}

		return $_SESSION['installer_csrf_token'];

	}

	/**
	 * Validate the submitted installer CSRF token.
	 */
	private function hasValidCsrfToken(): bool {

		$token = $_POST['csrfToken'] ?? '';

		return is_scalar($token) && hash_equals($this->csrfToken(), (string)$token);

	}

	/**
	 * Add an informational message to the installer output.
	 */
	public function addNotification($text) {

		$this->notifications[] = $text;

	}

	/**
	 * Return collected informational messages.
	 */
	public function getNotifications() {

		return $this->notifications;

	}

	/**
	 * Add an error message to the installer output.
	 */
	public function addError($text) {

		$this->errors[] = $text;

	}

	/**
	 * Return collected installer errors.
	 */
	public function getErrors() {

		return $this->errors;

	}

	/**
	 * Check filesystem permissions required by the installer.
	 */
	public function checkFoldersErrors() {

		// check root folder writable
		if (!is_writable($this->rootFolder)) {
			$this->addError('The folder ' . $this->rootFolder . ' is not writable. Please grant ' .
					'write permission to web-server running this application.');
		}

		foreach (['/installer/sql/schema.sql', '/installer/sql/seed.sql'] as $relativePath) {
			$file = $this->rootFolder . $relativePath;

			if (!is_readable($file)) {
				$this->addError('Installer SQL file ' . $file . ' is not readable.');
			}
		}

	}

	/**
	 * Check Apache requirements used by the boilerplate.
	 */
	public function checkApacheErrors() {

		// test apache
		if (function_exists('apache_get_modules')) {
			$aModules = \apache_get_modules();
			if (!in_array('mod_rewrite', $aModules)) {
				$this->addError('Apache mod_rewrite is not loaded.');
			}
		}

		// htaccess
		if (!file_exists($this->rootFolder . '/.htaccess')) {
			$this->addError('Apache .htaccess file not found in application root folder.');
		}

	}

	/**
	 * Check PHP version and extension requirements.
	 */
	public function checkPhpErrors($requiredVersion, $extensions) {

		// check PHP extensions
		foreach ($extensions as $ext) {
			if (!extension_loaded($ext)) {
				$this->addError('Missing PHP extension ' . $ext);
			}
		}

		// check PHP version
		if (version_compare(phpversion(), $requiredVersion, "<")) {
			$this->addError('PHP version required is ' . $requiredVersion . ' or greater. This web-server is running PHP ' . phpversion());
		}

	}

	/**
	 * Validate required submitted installer fields.
	 */
	public function checkRequiredFields() {

		$v = $this->getPostVars();

		if (!$this->hasValidCsrfToken()) {
			$this->addError('Installer session token is not valid. Reload the page and try again.');
			return;
		}

		// list of required field names
		$requiredFields = ['productName', 'dbHost', 'dbName',
				'dbUser', 'name', 'surname', 'email'];

		// check that all fields are submitted
		foreach ($requiredFields as $f) {
			if ('' == $v[$f]) {
				$this->addError('A valid value for ' . $f . ' is required');
			}
		}

		if ('' !== $v['email'] and false === filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
			$this->addError('A valid administrator email address is required.');
		}

		if ('' !== $v['dbName'] and !$this->isValidMysqlIdentifier($v['dbName'])) {
			$this->addError('Database name can contain only letters, numbers and underscores, up to 64 characters.');
		}

	}

	/**
	 * Connect to the configured database server.
	 */
	public function connectToDbms() {

		$v = $this->getPostVars();

		try {
			$this->dbh = new \PDO(
				'mysql:host=' . $v['dbHost'] . ';charset=utf8mb4',
				$v['dbUser'],
				$v['dbPass'],
				[
					\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
					\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
				]
			);

			if (!is_a($this->dbh, 'PDO')) {
				throw new \PDOException('DB-handler is not valid, connection failed');
			}
		} catch (Exception $e) {
			$this->addError('MySQL connection failed: ' . $e->getMessage());
			return false;
		}

		return true;

	}

	/**
	 * Check the connected database server version.
	 */
	public function checkDbmsVersion($requiredVersion) {

		try {
			// Read the server version from the active connection before importing schema files.
			$sth = $this->dbh->prepare('SELECT VERSION()');
			$sth->execute();
			$version = $sth->fetch(\PDO::FETCH_COLUMN);
		} catch (Exception $e) {
			$this->addError('MySQL version check failed: ' . $e->getMessage());
			return;
		}

		if (version_compare($version, $requiredVersion) < 0) {
			$this->addError('MySQL version required is ' . $requiredVersion . ' or greater. You are using MySQL ' . $version);
		}

	}

	/**
	 * Detect whether the database server needs UTF-8 normalization.
	 */
	public function checkDbUtf8() {

		// check about charsets and collations
		$settings = array(
			'character_set_client'		=> 'utf8mb4',
			'character_set_connection'	=> 'utf8mb4',
			'character_set_database'	=> 'utf8mb4',
			'character_set_results'		=> 'utf8mb4',
			'character_set_server'		=> 'utf8mb4',
			'collation_connection'		=> 'utf8mb4_unicode_ci',
			'collation_database'		=> 'utf8mb4_unicode_ci',
			'collation_server'			=> 'utf8mb4_unicode_ci');

		try {
			// Ask the DBMS for the active charset and collation settings.
			$sth = $this->dbh->prepare('SHOW VARIABLES WHERE Variable_name LIKE \'character\_set\_%\' OR Variable_name LIKE \'collation%\'');
			$sth->execute();
			$list = $sth->fetchAll(\PDO::FETCH_OBJ);
		} catch (Exception $e) {
			$this->addError('Charset and collation check failed: ' . $e->getMessage());
			return;
		}

		// compare settings
		foreach ($list as $row) {
			if (array_key_exists($row->Variable_name, $settings)) {
				if ($settings[$row->Variable_name] != $row->Value) {
					$this->addNotification('Charset and collation will be forced to utf8mb4');
					$this->forceDbUtf8 = true;
				}
			}
		}

	}

	/**
	 * Create or prepare the application database and import baseline SQL files.
	 */
	public function createDb() {

		// get the variables by http post
		$v = $this->getPostVars();
		$dbIdentifier = null;

		try {
			$dbIdentifier = $this->mysqlIdentifier($v['dbName']);
		} catch (InvalidArgumentException $e) {
			$this->addError('Database name can contain only letters, numbers and underscores, up to 64 characters.');
			return false;
		}

		// check if db exists
		try {
			$sth = $this->dbh->prepare('SELECT * FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?');
			$sth->execute([$v['dbName']]);
			$db = $sth->fetch(\PDO::FETCH_OBJ);
		} catch (Exception $e) {
			$this->addError('Cannot check if DB exists by information schema: ' . $e->getMessage());
			return false;
		}

		// not exists, create a new database
		if (false === $db) {

			$query = 'CREATE DATABASE ' . $dbIdentifier . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';

			try {
				if (!$this->dbh->exec($query)) {
					throw new \PDOException($this->dbh->errorInfo()[2]);
				}
				$this->addNotification('New database `' . $v['dbName'] . '` has been created.');
			} catch (Exception $e) {
				$this->addError('DB `' . $v['dbName'] . '` creation failed: ' . $e->getMessage());
				return false;
			}

		// db exists, check if charset and collation are utf8mb4
		} else if ('utf8mb4'!=$db->DEFAULT_CHARACTER_SET_NAME or 'utf8mb4_unicode_ci'!=$db->DEFAULT_COLLATION_NAME) {

			$query = 'ALTER DATABASE ' . $dbIdentifier . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';

			try {
				if (!$this->dbh->exec($query)) {
					throw new \PDOException($this->dbh->errorInfo()[2]);
				}
				$this->addNotification('Existing database ' . $v['dbName'] . ' has been converted to charset and collation utf8mb4.');
			} catch (Exception $e) {
				$this->addError('Change of DB charset and collation failed: ' . $e->getMessage());
				return false;
			}

		}

		// import the schema baseline first, then the bootstrap seed data
		try {
			$this->dbh->exec('USE ' . $dbIdentifier);
			$this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, 1);
		} catch (Exception $e) {
			$this->addError('Database selection failed: ' . $e->getMessage());
			return false;
		}

		if (!$this->importSqlFile('/installer/sql/schema.sql', 'Schema import failed')) {
			return false;
		}

		if (!$this->importSqlFile('/installer/sql/seed.sql', 'Seed import failed')) {
			return false;
		}

		return true;

	}

	/**
	 * Import a SQL file into the already-selected database.
	 */
	private function importSqlFile(string $relativePath, string $errorPrefix): bool {

		$file = $this->rootFolder . $relativePath;

		if (!file_exists($file)) {
			$this->addError($errorPrefix . ': file ' . $file . ' not found');
			return false;
		}

		$queries = file_get_contents($file);

		if (false === $queries) {
			$this->addError($errorPrefix . ': file ' . $file . ' cannot be read');
			return false;
		}

		try {
			$this->dbh->exec($queries);
		} catch (Exception $e) {
			$this->addError($errorPrefix . ': ' . $e->getMessage());
			return false;
		}

		return true;

	}

	/**
	 * Create and insert a new application user with random password.
	 */
	public function createUser() {

		// get the variables by http post
		$v = $this->getPostVars();

		// check if user exists
		try {
			$sth = $this->dbh->prepare('SELECT COUNT(1) FROM `users` WHERE username = ?');
			$sth->execute([$v['email']]);
			$exists = (bool)$sth->fetchColumn();
		} catch (Exception $e) {
			$this->addError('Error while checking if username is already in use: ' . $e->getMessage());
			$exists = false;
		}

		if ($exists) {
			$this->addError('Cannot create a user with username ' . $v['email'] . ' as it already exists');
			return;
		}

		// random password
		$password = '';

		// list all chars/numbers
		$chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

		$charsCount = count($chars);

		// fill in each char of the random password
		for ($i=1; $i <= 15; $i++) {
			if (0 == $i%4) {
				$password .= '-';
				continue;
			}
			$password .= $chars[mt_rand(0, $charsCount-1)];
		}

		// Use the current Pair password policy so fresh installs follow Pair 4 alpha.
		$hash = \Pair\Models\User::getHashedPasswordWithSalt($password);

		// assemble the query
		$query =
			'INSERT INTO `users` (`group_id`, `locale_id`, `username`, `hash`, `name`, `surname`, `email`, `admin`, `enabled`, `last_login`, `faults`, `pw_reset`)
			VALUES (1, 82, ?, ?, ?, ?, ?, 1, 1, NULL, 0, NULL)';

		// query parameters
		$params = [$v['email'], $hash, $v['name'], $v['surname'], $v['email']];

		// do the query and log
		try {
			$sth = $this->dbh->prepare($query);
			if (!$sth->execute($params)) {
				throw new \PDOException($sth->errorInfo()[2]);
			}
			$this->addNotification('Created a new user. Please keep note.' . "\n" . 'Username: ' . $v['email'] . "\n" . 'Password: ' . $password);
		} catch (Exception $e) {
			$this->addError('User creation failed: ' . $e->getMessage());
		}

	}

	/**
	 * Render collected installer notifications.
	 */
	public function printNotifications() {

		if (count($this->notifications)) {

			foreach ($this->notifications as $p) {
				?><div class="installer-alert installer-alert-info" role="alert"><span class="installer-alert-icon">i</span><span><?php print nl2br($this->escape((string)$p)) ?></span></div><?php
			}

		}

	}

	/**
	 * Render collected installer errors.
	 */
	public function printErrors() {

		if (count($this->errors)) {

			foreach ($this->errors as $p) {
				?><div class="installer-alert installer-alert-danger" role="alert"><span class="installer-alert-icon">!</span><span><?php print nl2br($this->escape((string)$p)) ?></span></div><?php
			}

		}

	}

	/**
	 * Render the shared HTML head and installer visual system.
	 */
	private function printHtmlHead(): void {

?><head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Pair sample installer</title>
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	    <style>
			:root {
				--installer-ink: #143836;
				--installer-muted: #687976;
				--installer-line: #d7e4de;
				--installer-paper: #ffffff;
				--installer-soft: #f4f7f2;
				--installer-tint: #e8f5f1;
				--installer-accent: #0e8f77;
				--installer-accent-strong: #08715f;
				--installer-warm: #f0a64f;
				--installer-warm-soft: #fff4df;
				--installer-danger: #b42318;
				--installer-shadow: 0 22px 60px rgba(30, 50, 44, .14);
			}

			body {
				min-height: 100vh;
				background:
					linear-gradient(135deg, rgba(14, 143, 119, .13) 0%, rgba(240, 166, 79, .14) 100%),
					var(--installer-soft);
				color: var(--installer-ink);
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
			}

			.installer-shell {
				width: min(1080px, calc(100% - 32px));
				margin: 0 auto;
				padding: 28px 0 24px;
			}

			.installer-topbar,
			.installer-footer {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 16px;
			}

			.installer-topbar {
				margin-bottom: 18px;
			}

			.installer-brand {
				display: flex;
				align-items: center;
				gap: 12px;
				color: var(--installer-ink);
				font-size: 17px;
				font-weight: 700;
			}

			.installer-brand img {
				width: 64px;
				height: 36px;
				object-fit: contain;
				padding: 6px 8px;
				border: 1px solid rgba(255, 255, 255, .50);
				border-radius: 8px;
				background: linear-gradient(135deg, var(--installer-ink), var(--installer-accent));
				box-shadow: 0 10px 24px rgba(20, 56, 54, .16);
			}

			.installer-badge {
				display: inline-flex;
				align-items: center;
				min-height: 32px;
				padding: 6px 12px;
				border: 1px solid rgba(8, 113, 95, .16);
				border-radius: 8px;
				background: rgba(255, 255, 255, .72);
				color: var(--installer-accent-strong);
				font-size: 13px;
				font-weight: 700;
				box-shadow: 0 8px 22px rgba(30, 50, 44, .07);
			}

			.installer-grid {
				display: grid;
				grid-template-columns: minmax(260px, 320px) minmax(0, 1fr);
				gap: 18px;
				align-items: start;
			}

			.installer-aside,
			.installer-panel,
			.installer-success {
				border: 1px solid rgba(23, 61, 58, .10);
				border-radius: 8px;
				background: rgba(255, 255, 255, .96);
				box-shadow: var(--installer-shadow);
			}

			.installer-aside {
				display: flex;
				flex-direction: column;
				justify-content: space-between;
				overflow: hidden;
				padding: 24px;
				position: sticky;
				top: 24px;
				color: var(--installer-ink);
			}

			.installer-aside::before,
			.installer-panel::before,
			.installer-success::before {
				content: "";
				display: block;
				height: 4px;
				margin: -24px -24px 22px;
				background: linear-gradient(90deg, var(--installer-accent), var(--installer-warm));
			}

			.installer-panel::before {
				margin: -28px -28px 24px;
			}

			.installer-success::before {
				margin: -30px -30px 28px;
			}

			.installer-aside h1,
			.installer-aside h2 {
				margin: 0 0 12px;
				font-size: 30px;
				line-height: 1.12;
				letter-spacing: 0;
			}

			.installer-aside p {
				margin-bottom: 0;
				color: var(--installer-muted);
				font-size: 15px;
				line-height: 1.5;
			}

			.installer-steps {
				margin: 22px 0 18px;
			}

			.installer-step {
				display: flex;
				gap: 12px;
				padding: 13px 0;
				border-top: 1px solid var(--installer-line);
			}

			.installer-step-index {
				flex: 0 0 32px;
				width: 32px;
				height: 32px;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				border-radius: 8px;
				background: var(--installer-tint);
				color: var(--installer-accent-strong);
				font-weight: 700;
			}

			.installer-step strong {
				display: block;
				color: var(--installer-ink);
			}

			.installer-step span {
				display: block;
				color: var(--installer-muted);
				font-size: 14px;
			}

			.installer-requirements {
				display: grid;
				gap: 8px;
				margin: 0;
				padding: 18px 0 0;
				border-top: 1px solid var(--installer-line);
				list-style: none;
			}

			.installer-requirements li {
				display: flex;
				align-items: center;
				gap: 8px;
				color: var(--installer-muted);
				font-size: 14px;
			}

			.installer-check {
				width: 18px;
				height: 18px;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				border-radius: 50%;
				background: var(--installer-warm);
				color: #372307;
				font-size: 12px;
				font-weight: 900;
			}

			.installer-panel {
				overflow: hidden;
				padding: 28px;
			}

			.installer-section {
				padding: 0 0 22px;
				margin-bottom: 22px;
				border-bottom: 1px solid var(--installer-line);
			}

			.installer-section:last-of-type {
				border-bottom: 0;
				margin-bottom: 0;
			}

			.installer-section-title {
				display: flex;
				align-items: flex-start;
				gap: 12px;
				margin-bottom: 16px;
			}

			.installer-section-title h2 {
				margin: 0;
				font-size: 19px;
				letter-spacing: 0;
			}

			.installer-section-title p {
				margin: 2px 0 0;
				color: var(--installer-muted);
				font-size: 14px;
			}

			.installer-section-icon {
				width: 34px;
				height: 34px;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				border-radius: 8px;
				background: var(--installer-tint);
				border: 1px solid rgba(8, 113, 95, .08);
				color: var(--installer-accent-strong);
				font-weight: 800;
			}

			.installer-panel label {
				color: var(--installer-ink);
				font-size: 14px;
				font-weight: 700;
			}

			.installer-panel .form-control {
				height: 46px;
				border-color: #cfded9;
				border-radius: 8px;
				background: #fbfdfc;
				color: var(--installer-ink);
				transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
			}

			.installer-panel .form-control:focus {
				border-color: var(--installer-accent);
				background: #fff;
				box-shadow: 0 0 0 .2rem rgba(24, 160, 133, .16);
			}

			.installer-help {
				color: var(--installer-muted);
				font-size: 13px;
			}

			.installer-alert {
				display: flex;
				gap: 10px;
				align-items: flex-start;
				padding: 12px 14px;
				margin-bottom: 14px;
				border: 1px solid transparent;
				border-radius: 8px;
				font-size: 14px;
			}

			.installer-alert-info {
				border-color: rgba(14, 143, 119, .24);
				background: rgba(14, 143, 119, .09);
				color: var(--installer-accent-strong);
			}

			.installer-alert-danger {
				border-color: rgba(180, 35, 24, .24);
				background: rgba(180, 35, 24, .08);
				color: var(--installer-danger);
			}

			.installer-alert-icon {
				flex: 0 0 22px;
				width: 22px;
				height: 22px;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				border-radius: 50%;
				background: currentColor;
				color: #fff;
				font-size: 13px;
				font-weight: 800;
			}

			.installer-submit {
				height: 50px;
				border: 0;
				border-radius: 8px;
				background: linear-gradient(135deg, var(--installer-accent-strong), var(--installer-accent));
				box-shadow: 0 12px 24px rgba(8, 113, 95, .24);
				font-weight: 800;
				letter-spacing: 0;
			}

			.installer-submit:hover,
			.installer-submit:focus {
				background: linear-gradient(135deg, #075f50, #0b806b);
				box-shadow: 0 14px 28px rgba(8, 113, 95, .28);
			}

			.installer-footer {
				margin-top: 22px;
				color: var(--installer-muted);
				font-size: 14px;
			}

			.installer-footer a {
				color: var(--installer-accent-strong);
				font-weight: 700;
			}

			.installer-footer-links {
				display: flex;
				gap: 12px;
				margin: 0;
				padding: 0;
				list-style: none;
			}

			.installer-success {
				overflow: hidden;
				padding: 30px;
			}

			.installer-success h1 {
				margin: 0 0 12px;
				font-size: 30px;
				letter-spacing: 0;
			}

			.installer-login-link {
				display: inline-flex;
				margin-top: 12px;
				padding: 10px 14px;
				border-radius: 8px;
				background: var(--installer-tint);
				color: var(--installer-accent-strong);
				font-weight: 800;
			}

			@media (max-width: 900px) {
				.installer-grid {
					grid-template-columns: 1fr;
				}

				.installer-aside h1,
				.installer-aside h2 {
					font-size: 27px;
				}

				.installer-aside {
					position: static;
				}
			}

			@media (max-width: 640px) {
				.installer-shell {
					width: min(100% - 20px, 1120px);
					padding: 18px 0;
				}

				.installer-topbar,
				.installer-footer {
					align-items: flex-start;
					flex-direction: column;
				}

				.installer-panel,
				.installer-aside,
				.installer-success {
					padding: 20px;
				}
			}
		</style>
	</head><?php

	}

	/**
	 * Render the shared installer footer and scripts.
	 */
	private function printFooterAndScripts(): void {

?>
	      <footer class="installer-footer">
	        <p class="mb-0"><a href="https://github.com/viames/pair_boilerplate">Pair boilerplate</a> powered by <a href="https://github.com/viames/Pair">Pair</a></p>
	        <ul class="installer-footer-links">
	          <li><a href="https://github.com/viames/pair_boilerplate/issues">Issues</a></li>
	          <li><a href="https://github.com/viames/pair_boilerplate/wiki">Wiki</a></li>
	          <li><a href="https://github.com/viames/pair_boilerplate/releases">Releases</a></li>
	        </ul>
	      </footer>
	    </div>
	   	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pwstrength-bootstrap/2.1.1/pwstrength-bootstrap.min.js"></script>
	    <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
	</body>
</html><?php

	}

	/**
	 * Render the initial installer form.
	 */
	public function printSetupPage() {

		$v = $this->getPostVars();

?><!DOCTYPE html>
<html lang="en">
	<?php $this->printHtmlHead() ?>
	<body>
	    <div class="installer-shell">
	      <header class="installer-topbar">
	        <div class="installer-brand">
	          <img src="<?php print $this->escape($this->url('images/pair-logo.png')) ?>" alt="Pair logo">
	          <span>Pair Boilerplate</span>
	        </div>
	        <span class="installer-badge">Pair 4 alpha setup</span>
	      </header>

	      <div class="installer-grid">
	        <aside class="installer-aside">
	          <div>
	            <h1>Project setup</h1>
	            <p>Configure the first database connection and administrator account for this Pair application.</p>
	            <div class="installer-steps" aria-label="Installer steps">
	              <div class="installer-step">
	                <span class="installer-step-index">1</span>
	                <div><strong>System check</strong><span>PHP, extensions and SQL files</span></div>
	              </div>
	              <div class="installer-step">
	                <span class="installer-step-index">2</span>
	                <div><strong>Database</strong><span>Schema, seed data and Pair migrations</span></div>
	              </div>
	              <div class="installer-step">
	                <span class="installer-step-index">3</span>
	                <div><strong>Administrator</strong><span>First user and generated credentials</span></div>
	              </div>
	            </div>
	          </div>
	          <ul class="installer-requirements">
	            <li><span class="installer-check">✓</span> PHP 8.4.1 or newer</li>
	            <li><span class="installer-check">✓</span> MySQL 8.0 or newer</li>
	            <li><span class="installer-check">✓</span> Pair 4 alpha baseline</li>
	          </ul>
	        </aside>

	        <main class="installer-panel">
	          <?php $this->printErrors() ?>
	          <?php $this->printNotifications() ?>
	          <form action="<?php print $this->escape($this->url()) ?>" method="post">
	            <input type="hidden" name="csrfToken" value="<?php print $this->escape($this->csrfToken()) ?>">

	            <section class="installer-section" aria-labelledby="product-section">
	              <div class="installer-section-title">
	                <span class="installer-section-icon">1</span>
	                <div>
	                  <h2 id="product-section">Product</h2>
	                  <p>Name and environment defaults written to the generated .env file.</p>
	                </div>
	              </div>
	              <div class="form-group mb-0">
	                <label for="productName">Product name</label>
	                <input type="text" class="form-control" name="productName" id="productName" value="<?php print $this->escape((string)$v['productName']) ?>" autocomplete="organization" required>
	                <div class="installer-help mt-2">Use the public application name your users will recognize.</div>
	              </div>
	            </section>

	            <section class="installer-section" aria-labelledby="database-section">
	              <div class="installer-section-title">
	                <span class="installer-section-icon">2</span>
	                <div>
	                  <h2 id="database-section">Database</h2>
	                  <p>The installer creates the database when it does not already exist.</p>
	                </div>
	              </div>
	              <div class="row">
	                <div class="col-md-6 mb-3">
	                  <label for="dbHost">Host</label>
	                  <input type="text" class="form-control" name="dbHost" id="dbHost" value="<?php print $this->escape((string)$v['dbHost']) ?>" autocomplete="off" required>
	                  <div class="installer-help mt-2">Hostname or IP address.</div>
	                </div>
	                <div class="col-md-6 mb-3">
	                  <label for="dbName">Database name</label>
	                  <input type="text" class="form-control" name="dbName" id="dbName" value="<?php print $this->escape((string)$v['dbName']) ?>" autocomplete="off" required>
	                  <div class="installer-help mt-2">Letters, numbers and underscores only.</div>
	                </div>
	              </div>
	              <div class="row mb-n3">
	                <div class="col-md-6 mb-3">
	                  <label for="dbUser">User</label>
	                  <input type="text" class="form-control" name="dbUser" id="dbUser" value="<?php print $this->escape((string)$v['dbUser']) ?>" autocomplete="username" required>
	                </div>
	                <div class="col-md-6 mb-3">
	                  <label for="dbPass">Password</label>
	                  <input type="password" class="form-control" name="dbPass" id="dbPass" value="<?php print $this->escape((string)$v['dbPass']) ?>" autocomplete="current-password">
	                </div>
	              </div>
	            </section>

	            <section class="installer-section" aria-labelledby="admin-section">
	              <div class="installer-section-title">
	                <span class="installer-section-icon">3</span>
	                <div>
	                  <h2 id="admin-section">Administrator account</h2>
	                  <p>The first administrator receives a generated temporary password.</p>
	                </div>
	              </div>
	              <div class="row">
	                <div class="col-md-6 mb-3">
	                  <label for="name">Name</label>
	                  <input type="text" class="form-control" name="name" id="name" value="<?php print $this->escape((string)$v['name']) ?>" autocomplete="given-name" required>
	                </div>
	                <div class="col-md-6 mb-3">
	                  <label for="surname">Surname</label>
	                  <input type="text" class="form-control" name="surname" id="surname" value="<?php print $this->escape((string)$v['surname']) ?>" autocomplete="family-name" required>
	                </div>
	              </div>
	              <div class="form-group mb-0">
	                <label for="email">Email</label>
	                <input type="email" class="form-control" name="email" id="email" value="<?php print $this->escape((string)$v['email']) ?>" autocomplete="email" required>
	                <div class="installer-help mt-2">This email is used as the administrator username.</div>
	              </div>
	            </section>

	            <button class="btn btn-primary btn-block installer-submit" type="submit">Apply configuration</button>
	          </form>
	        </main>
	      </div>

	      <?php $this->printFooterAndScripts() ?><?php

	}

	/**
	 * Render the installation completion page.
	 */
	public function printFinalPage() {

		$v = $this->getPostVars();

?><!DOCTYPE html>
<html lang="en">
	<?php $this->printHtmlHead() ?>
	<body>
	    <div class="installer-shell">
	      <header class="installer-topbar">
	        <div class="installer-brand">
	          <img src="<?php print $this->escape($this->url('images/pair-logo.png')) ?>" alt="Pair logo">
	          <span>Pair Boilerplate</span>
	        </div>
	        <span class="installer-badge">Configuration complete</span>
	      </header>

	      <div class="installer-grid">
	        <aside class="installer-aside">
	          <div>
	            <h2>Installation completed</h2>
	            <p>The baseline schema, seed data and first administrator account are ready.</p>
	            <div class="installer-steps" aria-label="Completed installer steps">
	              <div class="installer-step">
	                <span class="installer-step-index">✓</span>
	                <div><strong>Configuration saved</strong><span>.env has been written</span></div>
	              </div>
	              <div class="installer-step">
	                <span class="installer-step-index">✓</span>
	                <div><strong>Database initialized</strong><span>Pair 4 alpha baseline applied</span></div>
	              </div>
	              <div class="installer-step">
	                <span class="installer-step-index">✓</span>
	                <div><strong>Installer removed</strong><span>The application can now start</span></div>
	              </div>
	            </div>
	          </div>
	          <ul class="installer-requirements">
	            <li><span class="installer-check">✓</span> Keep the generated password safe</li>
	            <li><span class="installer-check">✓</span> Log in and change it after first access</li>
	          </ul>
	        </aside>

	        <main class="installer-success">
	          <?php $this->printErrors() ?>
	          <?php $this->printNotifications() ?>
	          <h1><?php print $this->escape((string)$v['productName']) ?> is ready.</h1>
	          <p class="mb-0">You can now open the application and log in with the generated administrator credentials.</p>
	          <a class="installer-login-link" href="<?php print $this->escape($this->url()) ?>"><?php print $this->escape($this->url()) ?></a>
	        </main>
	      </div>

	      <?php $this->printFooterAndScripts() ?><?php

	}

	/**
	 * Return normalized installer POST variables.
	 */
	public function getPostVars() {

		// list of required field names
		$postVars = ['productName', 'dbHost', 'dbName',
				'dbUser', 'dbPass', 'name', 'surname', 'email'];

		$vars = [];

		foreach ($postVars as $v) {
			$value = $_POST[$v] ?? '';
			$vars[$v] = is_scalar($value) ? $this->singleLine((string)$value) : '';
		}

		return $vars;

	}

	/**
	 * Write the generated environment configuration file.
	 */
	public function createConfigFile() {

		$vars = $this->getPostVars();

		$content =
'# Product
APP_NAME=' . $this->envString($vars['productName']) . '
APP_VERSION=1.0.0
APP_ENV=development
APP_DEBUG=true
UTC_DATE=false

# Database
DB_HOST=' . $this->envString($vars['dbHost']) . '
DB_NAME=' . $this->envString($vars['dbName']) . '
DB_USER=' . $this->envString($vars['dbUser']) . '
DB_PASS=' . $this->envString($vars['dbPass']) . '
DB_UTF8=' . ($this->forceDbUtf8 ? 'true' : 'false') . '

# Options
PAIR_AUDIT_ALL=true
PAIR_SINGLE_SESSION=true
PAIR_AUTH_BY_EMAIL=true
PAIR_LOGGER_EMAIL_RECIPIENTS=
PAIR_LOGGER_EMAIL_THRESHOLD=4
PAIR_LOGGER_TELEGRAM_CHAT_IDS=
PAIR_LOGGER_TELEGRAM_THRESHOLD=4
PAIR_MOBILE_ACCESS_TOKEN_LIFETIME=900
PAIR_MOBILE_REFRESH_TOKEN_LIFETIME=2592000

# Crypt keys
OPTIONS_CRYPT_KEY=' . bin2hex(random_bytes(32)) . '
AES_CRYPT_KEY=' . bin2hex(random_bytes(32)) . '

# Sentry
SENTRY_DSN=

# Insight Hub
INSIGHT_HUB_API_KEY=
INSIGHT_HUB_PERFORMANCE=

# MySqlDump
MYSQLDUMP_PATH=';

		$envFile = $this->rootFolder . '/.env';
		$tempFile = $this->rootFolder . '/.env.tmp';
		$res = file_put_contents($tempFile, $content, LOCK_EX);

		if (false === $res) {
			$this->addError('Write of .env file failed');
			return;
		}

		if (!chmod($tempFile, 0660)) {
			@unlink($tempFile);
			$this->addError('Permission update of .env file failed');
			return;
		}

		if (!rename($tempFile, $envFile)) {
			@unlink($tempFile);
			$this->addError('Activation of .env file failed');
		}

	}

	/**
	 * Create the runtime temp folder when missing.
	 */
	public function createTempFolder() {

		$tempFolder = $this->rootFolder . '/temp';

		if (!(file_exists($tempFolder) and is_dir($tempFolder))) {
			$old = umask(0002);

			try {
				$created = mkdir($tempFolder, 0775, true);
			} finally {
				umask($old);
			}

			if (!$created and !is_dir($tempFolder)) {
				$this->addError('Temporary folder creation failed.');
				return;
			}
		}

		if (!is_writable($tempFolder)) {
			$this->addError('Temporary folder is not writable.');
		}

	}

	/**
	 * Recursively delete a directory tree.
	 */
	public static function deleteDir($dirPath) {

		if (!is_dir($dirPath)) {
			return;
		}

		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}

		$files = glob($dirPath . '*', GLOB_MARK);

		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDir($file);
			} else {
				unlink($file);
			}
		}

		rmdir($dirPath);
	}

	/**
	 * Remove the installer folder after a successful installation.
	 */
	public function selfRemove(): bool {

		$folder = $this->rootFolder . '/installer';

		if (!Pair\Helpers\Utilities::deleteFolder($folder)) {
			$this->addError('Folder ' . $folder . ' deletion failed');
			return false;
		}

		$this->addNotification('Installer was deleted');

		return true;

	}

}

$installer = new Installer();

$installer->checkFoldersErrors();
$installer->checkApacheErrors();
$installer->checkPhpErrors('8.4.1', ['curl','fileinfo','intl','json','mbstring','pdo','pdo_mysql']);

// form is submitted, check if can proceed to install
if (count($_POST)) {

	$installer->checkRequiredFields();

	$connected = count($installer->getErrors()) ? false : $installer->connectToDbms();

	// only if db connected
	if ($connected) {

		// check and configure db
			$installer->checkDbmsVersion('8.0');
			$dbCreated = count($installer->getErrors()) ? false : $installer->createDb();

			if ($dbCreated && !count($installer->getErrors())) {

				$installer->checkDbUtf8();

				if (!count($installer->getErrors())) {
					$installer->createUser();
				}

			}

			if ($dbCreated && !count($installer->getErrors())) {

				// create a temporary empty folder
				$installer->createTempFolder();

			// create .env file
			$installer->createConfigFile();

		}

	}

}

// show the setup page
if (!isset($_POST) or !count($_POST) or count($installer->getErrors())) {

	$installer->printSetupPage();

} else {

	// delete itself
	if (!$installer->selfRemove()) {

		$installer->printSetupPage();

	} else {

		// show all notifications and print link to application root
		$installer->printFinalPage();

	}

}
