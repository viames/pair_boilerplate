<?php

declare(strict_types=1);

/** Exercise fresh-install schema, administrator creation and configuration using an explicit disposable DB. */
$project = dirname(__DIR__);
$name = getenv('PAIR_INSTALL_DB_NAME') ?: '';
if (!preg_match('/^pair_test_[A-Za-z0-9_]+$/D', $name)) {
	fwrite(STDERR, "PAIR_INSTALL_DB_NAME must begin with pair_test_.\n");
	exit(1);
}
if (file_exists($project . '/.env')) {
	fwrite(STDERR, "The database installation test requires an unconfigured disposable project.\n");
	exit(1);
}
define('APPLICATION_PATH', $project);
define('TEMP_PATH', $project . '/temp/');
require $project . '/vendor/autoload.php';
$host = getenv('PAIR_INSTALL_DB_HOST') ?: '127.0.0.1';
$user = getenv('PAIR_INSTALL_DB_USER') ?: 'pair_test';
$password = getenv('PAIR_INSTALL_DB_PASS') ?: '';
$pdo = new PDO('mysql:host=' . $host . ';charset=utf8mb4', $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$tables = $pdo->prepare('SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?');
$tables->execute([$name]);
if ((int)$tables->fetchColumn() !== 0) {
	fwrite(STDERR, "Refusing to test or clean a database containing existing tables.\n");
	exit(1);
}
$source = file_get_contents($project . '/installer/start.php');
$end = strpos($source, '$installer = new Installer();');
if ($end === false) {
	throw new RuntimeException('Installer entrypoint marker not found.');
}
eval(substr($source, 5, $end - 5));
$installer = (new ReflectionClass(Installer::class))->newInstanceWithoutConstructor();
(new ReflectionProperty(Installer::class, 'rootFolder'))->setValue($installer, $project);
(new ReflectionProperty(Installer::class, 'dbh'))->setValue($installer, $pdo);
$_POST = ['productName' => 'Pair installation test', 'dbHost' => $host, 'dbName' => $name,
	'dbUser' => $user, 'dbPass' => $password, 'name' => 'Test', 'surname' => 'Administrator', 'email' => 'admin@example.invalid'];
$exitCode = 0;
try {
	if (!$installer->createDb()) {
		throw new RuntimeException('Fresh schema import failed: ' . implode('; ', $installer->getErrors()));
	}
	$installer->createUser();
	$installer->createTempFolder();
	$installer->createConfigFile();
	if ($installer->getErrors() !== []) {
		throw new RuntimeException('Administrator/configuration setup failed.');
	}
	$credential = null;
	foreach ($installer->getNotifications() as $notification) {
		if (preg_match('/Password: (\S+)/', $notification, $match)) {
			$credential = $match[1];
		}
	}
	$hash = $pdo->query("SELECT hash FROM users WHERE username = 'admin@example.invalid'")->fetchColumn();
	if (!is_string($credential) || !is_string($hash) || !password_verify($credential, $hash)) {
		throw new RuntimeException('Generated administrator credential does not verify.');
	}
	$pdo->query('SELECT credential_id, public_key, sign_count FROM user_passkeys LIMIT 0');
	$migrations = $pdo->query("SELECT file FROM migrations WHERE source = 'pair' AND result = 1")->fetchAll(PDO::FETCH_COLUMN);
	foreach (glob($project . '/vendor/viames/pair/migrations/*.sql') as $migration) {
		if (!in_array(basename($migration), $migrations, true)) {
			throw new RuntimeException('Missing baseline migration: ' . basename($migration));
		}
	}
	Pair\Core\Env::load();
	if (Pair\Orm\Database::value('SELECT @@character_set_connection') !== 'utf8mb4') {
		throw new RuntimeException('Generated configuration did not open a Unicode framework connection.');
	}
	if (!defined('BASE_TIMEZONE')) {
		define('BASE_TIMEZONE', Pair\Core\Env::get('UTC_DATE') ? 'UTC' : date_default_timezone_get());
	}
	// Authenticate through the framework to catch schema/binding drift beyond password hashing.
	$login = Pair\Models\User::doTokenLogin('admin@example.invalid', $credential);
	$user = new Pair\Models\User((int)$login->userId);
	if ($login->error || !$user->isLoaded() || !$user->super || !$user->canAccess('selftest', 'default')) {
		throw new RuntimeException('The generated administrator cannot authenticate or access protected modules.');
	}
	$phpunit = getenv('PAIR_INSTALL_PHPUNIT');
	if ($phpunit !== false && is_file($phpunit)) {
		// With the fresh .env present, exercise controller cases skipped by the unconfigured smoke.
		$process = proc_open([PHP_BINARY, $phpunit, '-c', $project . '/phpunit.xml.dist'], [STDIN, STDOUT, STDERR], $pipes, $project);
		if (!is_resource($process) || proc_close($process) !== 0) {
			throw new RuntimeException('Configured starter PHPUnit suite failed.');
		}
	}
	if ($installer->createDb() !== false) {
		throw new RuntimeException('A repeated installation accepted a populated database.');
	}
	if ($pdo->query("SELECT hash FROM users WHERE username = 'admin@example.invalid'")->fetchColumn() !== $hash) {
		throw new RuntimeException('Repeated setup changed an existing administrator.');
	}
	echo 'PASS: fresh MySQL schema, all migration markers, passkeys, verifiable administrator, generated .env and occupied-schema safeguard.' . PHP_EOL;
} catch (Throwable $error) {
	fwrite(STDERR, $error->getMessage() . PHP_EOL);
	$exitCode = 1;
} finally {
	// This schema was verified empty before the test claimed ownership; never clean an existing app.
	$pdo->exec('DROP DATABASE `' . $name . '`');
	@unlink($project . '/.env');
	$_POST = [];
}
exit($exitCode);
