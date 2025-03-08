<?php

use Pair\Core\Application;
use Pair\Core\Config;
use Pair\Core\Logger;
use Pair\Helpers\Utilities;
use Pair\Orm\ActiveRecord;
use Pair\Orm\Database;

class SelfTest {

	/**
	 * Elenco estensioni PHP necessarie.
	 */
	const REQUIRED_PHP_EXT = ['fileinfo','json','pcre','PDO','pdo_mysql','Reflection','soap'];

	/**
	 * Versione minima di PHP necessaria.
	 */
	const MIN_PHP_VERSION = '8.2';

	/**
	 * Versione minima di MySQL necessaria.
	 */
	const MIN_MYSQL_VERSION = '8.0.26';

	/**
	 * Folders to check for presence and read/write permissions
	 */
	const FOLDERS = ['modules', 'temp', 'templates', 'translations'];

	/**
	 * The tests list.
	 */
	private array $list = [];

	/**
	 * Returns property’s value if set or NULL if not set.
	 *
	 * @param	string	Property’s name.
	 */
	public function __get(string $name): mixed {

		if (property_exists($this, $name)) {
			return $this->$name;
		} else {
			return NULL;
		}

	}

	/**
	 * Verifies that values strictly equal.
	 *
	 * @param	string	Title of this test.
	 * @param	mixed	First value to check.
	 * @param	mixed	Second value to check.
	 * @param	string	Test section.
	 */
	public function assertEquals(string $label, $val1, $val2, string $section): void {

		$result = ($val1===$val2 ? TRUE : FALSE);

		$this->addTest($label, $result, $section);

	}

	/**
	 * Verifies that param object is instance of the specified class name.
	 *
	 * @param	string	Title of this test.
	 * @param	mixed	The object to check.
	 * @param	string	Name of the class.
	 * @param	string	Test section.
	 */
	public function assertInstanceOf(string $label, $object, string $class, string $section): void {

		$result = (is_a($object, $class) ? TRUE : FALSE);

		$this->addTest($label, $result, $section);

	}

	/**
	 * Verifies that a class really has a property with passed name.
	 *
	 * @param	string	Title of this test.
	 * @param	mixed	The attribute to check.
	 * @param	string	Name of the class.
	 * @param	string	Test section.
	 */
	public function assertClassHasAttribute(string $label, $attributeName, string $class, string $section): void {

		$reflect	= new ReflectionClass($class);
		$result		= $reflect->hasProperty($attributeName);

		$this->addTest($label, $result, $section);

	}

	/**
	 * Verifies that a class has static attribute
	 *
	 * @param	string	Title of this test.
	 * @param	mixed	Static attribute to check.
	 * @param	mixed	Name of the class.
	 * @param	string	Test section.
	 */
	public function assertClassHasStaticAttribute(string $label, $staticAttribute, string $class, string $section): void {

		$reflect	= new ReflectionClass($class);
		$attribute	= $reflect->getProperty($staticAttribute);
		$result		= ($attribute->isStatic() ? TRUE : FALSE);

		$this->addTest($label, $result, $section);

	}

	/**
	 * Verifies that a class contains a specified function.
	 *
	 * @param	string	Title of this test.
	 * @param	mixed	Method to check.
	 * @param	mixed	Name of the class.
	 * @param	string	Test section.
	 */
	public function assertClassMethodExist(string $label, $methodName, string $class, string $section): void {

		$reflect = new ReflectionClass($class);
		$result = $reflect->hasMethod($methodName);

		$this->addTest($label, $result, $section);

	}

	/**
	 * Verifies if var is TRUE.
	 *
	 * @param	string	Title of this test.
	 * @param	bool	Value to check.
	 * @param	string	Test section.
	 */
	public function assertTrue(string $label, bool $var, string $section): void {

		$this->addTest($label, $var, $section);

	}

	/**
	 * Verifies that var is exactly 0.
	 *
	 * @param	string	Title of this test.
	 * @param	int		Value to check.
	 * @param	string	Test section.
	 */
	public function assertIsZero(string $label, int $var, string $section): void {

		$this->addTest($label, (0 === $var ? TRUE : FALSE), $section);

	}

	/**
	 * Verifies that var is greater than param value.
	 *
	 * @param	string	Title of this test.
	 * @param	int		Value to check.
	 * @param	int		Value to compare.
	 * @param	string	Test section.
	 */
	public function assertGreaterThan(string $label, int $var, int $comparing, string $section): void {

		$this->addTest($label, ($var > $comparing ? TRUE : FALSE), $section);

	}

	/**
	 * Adds a test to list property.
	 *
	 * @param	string	Test description label.
	 * @param	bool	TRUE if test was ok.
	 * @param	string	Test section.
	 */
	private function addTest(string $label, bool $result, string $section): void {

		$test			= new stdClass();
		$test->label	= $label;
		$test->result	= $result;

		$this->list[$section][]	= $test;

	}

	/**
	 * Check that PHP extensions and version satisfy application requests.
	 *
	 * @return	bool
	 */
	public function checkPhp(): bool {

		$res = TRUE;

		$hiddenExt = [];

		Logger::notice('Checking for PHP extensions ' . implode(', ', self::REQUIRED_PHP_EXT));

		// check each library
		foreach (self::REQUIRED_PHP_EXT as $ext) {

			if (!extension_loaded($ext)) {

				// patch for hidden extensions that reveals themselves only by command line
				if (in_array($ext, $hiddenExt)) {

					$lines = explode("\n", shell_exec('php -i|grep ' . $ext));
					foreach ($lines as $line) {
						if ($ext . ' support => enabled' == $line) continue 2;
					}

				}

				// enqueue failure and show error message
				$res = FALSE;
				Logger::error('Missing PHP extension ' . $ext);

			}

		}

		// check PHP version
		if (version_compare(phpversion(), self::MIN_PHP_VERSION, "<")) {
			$res = FALSE;
			Logger::error('PHP version required is ' . self::MIN_PHP_VERSION . ' or greater. You are using PHP ' . phpversion());
		}

		// check en_US locales for float storage in DB
		if (class_exists('ResourceBundle') and !in_array('en_US', ResourceBundle::getLocales(''))) {
			$res = FALSE;
			Logger::error('en_US locale is necessary to appropriately convert PHP floats when saving in the DB');
		}

		return $res;

	}

	/**
	 * Check that MySQL version is greater than 5.5 and search for charset settings.
	 * Return TRUE if DBMS is ok.
	 *
	 * @return	bool
	 */
	public function checkMysql(): bool {

		$ret = TRUE;

		$db = Database::getInstance();
		$version = $db->getMysqlVersion();

		if (version_compare($version, self::MIN_MYSQL_VERSION) < 0) {
			Logger::error('MySQL version required is ' . self::MIN_MYSQL_VERSION . ' or greater. You are using MySQL ' . $version);
			$ret = FALSE;
		}

		// the right settings list
		$settings = [
			'character_set_client'		=> 'utf8mb4',
			'character_set_connection'	=> 'utf8mb4',
			'character_set_database'	=> 'utf8mb4',
			'character_set_results'		=> 'utf8mb4',
			'character_set_server'		=> 'utf8mb4',
			'collation_connection'		=> 'utf8mb4_unicode_ci',
			'collation_database'		=> 'utf8mb4_unicode_ci',
			'collation_server'			=> 'utf8mb4_unicode_ci'
		];

		// ask to dbms the current settings
		$list = Database::load('SHOW VARIABLES WHERE Variable_name LIKE \'character\_set\_%\' OR Variable_name LIKE \'collation%\'');

		// compare settings
		foreach ($list as $row) {

			if (array_key_exists($row->Variable_name, $settings)) {

				if ($settings[$row->Variable_name] != $row->Value) {
					Logger::warning('DBMS setting parameter ' . $row->Variable_name . ' value is ' . $row->Value . ' should be ' . $settings[$row->Variable_name]);
					$ret = FALSE;
				}

			}

		}

		return $ret;

	}

	/**
	 * Will tests .env file for missing lines or bad entries and returns TRUE if it's ok.
	 */
	public function checkConfigFile(): bool {

		$ret = TRUE;

		// check about missing UTC_DATE constant
		if (is_null(Config::get('UTC_DATE'))) {
			$ret = FALSE;
			Logger::error('In .env configuration file the UTC_DATE option is missing');
		// or check on fall-back timezone
		} else if (Config::get('UTC_DATE') and 'UTC' == date_default_timezone_get()) {
			$ret = FALSE;
			Logger::error('In .env configuration file the UTC_DATE option is FALSE but Timezone results in UTC by php.ini file');
		}

		return $ret;

	}

	/**
	 * Tests needed folders in both read and write.
	 */
	public function checkFolders(): bool {

		$ret = TRUE;

		foreach (self::FOLDERS as $f) {

			$folder = APPLICATION_PATH . '/' . $f;

			if ('temp' == $f and !Application::fixTemporaryFolder()) {
			
				$ret = FALSE;
				Logger::error('Temporary folder is not readable and cannot be fixed');
			
			} else if (!is_dir($folder) or !is_readable($folder)) {
			
				$ret = FALSE;
				Logger::error($folder . ' folder is missing or not readable');
			
			}

		}

		return $ret;

	}

	/**
	 * Run run test on maps and references on all ActiveRecord classes of this application.
	 *
	 * @return int
	 */
	public function checkActiveRecordClasses(): int {

		// the final error count
		$errors = 0;

		// get all ActiveRecord classes
		$classes = Utilities::getActiveRecordClasses();

		// plain list of Pair framework classes with DB mapping
		$pairClasses = ['Acl','Audit','Country','ErrorLog','Group','Language','Locale',
						'Module','Rule','Session','Template','Token','User','UserRemember'];
		array_walk($pairClasses, function(&$c) { $c = 'Pair\\Models\\' . $c; });

		// list of excluded from test
		$excludeClasses = [];

		// build the object and perform the test
		foreach ($classes as $class => &$opts) {

			// build a class object properly
			if ($opts['constructor']) {
				$opts['object'] = new $class;
			} else if ($opts['getInstance']) {
				$opts['object'] = $class::getInstance();
			} else {
				$excludeClasses[] = $class;
				continue;
			}

			// exclude Pair’s parent class of a children that inherit it
			foreach ($pairClasses as $pairClass) {
				if (!$opts['pair'] and is_subclass_of($opts['object'], $pairClass)) {
					$excludeClasses[] = $pairClass;
				}
			}

		}

		// repeat scan to test valid classes
		foreach ($classes as $class => $opts) {

			// run test on class binds
			if (!in_array($class, $excludeClasses)) {
				$errors += $this->checkClassMaps($opts['object']);
			}

		}

		return $errors;

	}

	/**
	 * Test the class couples properties-dbfields. Return the error count.
	 *
	 * @param	ActiveRecord	Object to test.
	 */
	private function checkClassMaps(ActiveRecord $object): int {

		// count nr of errors found on each class
		$errorCount = 0;

		$class = get_class($object);

		// all class-table maps
		$properties = $object->getAllProperties();

		// all class properties
		$properties = get_object_vars($object);

		$db = Database::getInstance();

		// all db fields
		if (!$db->tableExists($class::TABLE_NAME)) {
			$errorCount++;
			Logger::error('DB Table ' . $class::TABLE_NAME . ' doesn’t exist');
			return $errorCount;
		}

		// get the mapped table description
		$describe = $db->describeTable($class::TABLE_NAME);

		// assemble a useful array with field names as key
		foreach ($describe as $f) {
			$fieldList[] = $f->Field;
		}

		// test on each db-field mapped by the class
		foreach ($properties as $property => $value) {

			$field = $class::getMappedField($property);

			// looks for object declared property and db bind field
			if (!in_array($property, $properties)) {
				$errorCount++;
				Logger::error('Class ' . $class . ' is missing property “' . $property . '”');
			}

			if (!array_search($field, $fieldList)) {
				$errorCount++;
				Logger::error('Class ' . $class . ' is managing unexistent field “' . $field . '”');
			}

		}

		// second check for existent field unmapped by the class
		foreach ($fieldList as $field) {

			if (!$class::getMappedProperty($field)) {
				$errorCount++;
				Logger::error('Class ' . $class . ' is not binding “' . $field . '” in method getBinds()');
			}

		}

		return $errorCount;

	}

	/**
	 * Compare version of each installed plugin with application version and return FALSE
	 * if at least one of them is made for older version.
	 */
	public function checkPlugins(): bool {

		$ret = TRUE;

		// list of plugin types with namespace
		$pluginTypes = [
			'Pair\\Models\\Module'	=> TRUE,
			'Pair\\Models\\Template'	=> TRUE
		];

		foreach ($pluginTypes as $type => $pair) {

			// load db records and create objects
			$plugins = $type::all();

			// for each plugin compare version
			foreach ($plugins as $plugin) {

				if (version_compare(Config::get('PRODUCT_VERSION'), $plugin->appVersion) > 0) {
					Logger::warning($type . ' plugin ' . ucfirst($plugin->name) .
							' is compatible with ' . Config::get('PRODUCT_NAME') . ' v' . $plugin->appVersion);
					$ret = FALSE;
				}

			}


		}

		return $ret;

	}

}