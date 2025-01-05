<?php

use Pair\Core\Application;
use Pair\Core\Config;
use Pair\Core\Model;
use Pair\Exceptions\PairException;
use Pair\Helpers\Translator;
use Pair\Helpers\Utilities;
use Pair\Html\Form;
use Pair\Models\Acl;
use Pair\Models\Locale;
use Pair\Models\Migration;
use Pair\Models\Module;
use Pair\Models\Rule;
use Pair\Orm\ActiveRecord;
use Pair\Orm\Database;

class CrafterModel extends Model {

	/**
	 * Name of db table.
	 */
	protected string $tableName;

	/**
	 * Db Table primary or compound key.
	 */
	protected string|array $tableKey;

	/**
	 * List of couples property => db_field.
	 */
	protected array $binds;

	/**
	 * Class name with uppercase first char.
	 */
	protected string $objectName;

	/**
	 * List of properties type (property_name => type).
	 */
	protected array $propTypes;

	/**
	 * List of values for each enum/set property.
	 */
	private array $members;

	/**
	 * Name of CRUD module, all lowercase with no underscore.
	 */
	protected string $moduleName;

	/**
	 * File author meta tag.
	 */
	protected string $author;

	/**
	 * File package meta tag.
	 */
	protected string $package;

	/**
	 * Custom layouts array.
	 */
	protected array $layouts = [];

	/**
	 * Optional list of sortable db fields.
	 */
	protected array $orderOptions = [];

	/**
	 * List of DDL queries for migrations.
	 */
	protected array $queries = [];

	const INTERNAL_FIELDS = ['created_at', 'created_by', 'updated_at', 'updated_at'];

	/**
	 * Create an Migration Query for an ActiveRecord object.
	 */
	private function addMigrationQuery(ActiveRecord $activeRecord, string $comment): void {

		$class = get_class($activeRecord);
		$properties = $activeRecord->getAllProperties();

		// add the fields
		foreach ($properties as $propertyName => $value) {
			
			$fields[] = '`' . $class::getMappedField($propertyName) . '`';

			$type = gettype($value);

			// only expected DateTime|bool|NULL
			switch (gettype($value)) {
				case 'boolean':
					$value = $value ? 1 : 0;
					break;
				case 'object':
					$value = $this->db->quote($value->format('Y-m-d H:i:s'));
					break;
				case 'NULL':
					$value = 'NULL';
					break;
				default:
					$value = $this->db->quote($value);
					break;
			}
		
			$values[] = $value;
		
		}
		
		// remove the last comma
		$query = 'INSERT INTO `' . $activeRecord::TABLE_NAME . '` ' . '(' . implode(',', $fields) . ")\n" .
			'VALUES (' . implode(',', $values) . ');';

		// add the query to the list
		$this->queries[] = ['comment' => $comment, 'query' => $query, 'affectedRows' => 1];

	}

	/**
	 * Create the module folder and subfolders.
	 */
	private function createFolder(string $moduleName, bool $commonClass): string {

		$folder = APPLICATION_PATH . '/modules/' . $this->moduleName;

		if (file_exists($folder) ) {
			throw new PairException(Translator::do('MODULE_FOLDER_ALREADY_EXISTS', $this->moduleName));
		}

		// module folders
		$folders = [
			$folder,
			$folder . '/translations/',
			$folder . '/layouts/'
		];

		if (!$commonClass) {
			$folders[] = $folder . '/classes/';
		}

		foreach ($folders as $f) {
			$old = umask(0);
			mkdir($f, 0777, TRUE);
			umask($old);
		}

		return $folder;

	}

	/**
	 * Save the file with Migration queries and set the migration as applied.
	 */
	public function createMigrationFile(): void {
		
		// search in the migrations folder for higher prefix index
		$files = scandir(APPLICATION_PATH . '/migrations');

		$prefix = 001;

		// search for the highest prefix index
		foreach ($files as $file) {
			if (preg_match('#^(\d+)_#', $file, $matches)) {
				$prefix = max($prefix, (int)$matches[1]);
			}
		}
		
		// file name with 3 digits next-prefix and module name
		$prefix = str_pad(++$prefix, 3, '0', STR_PAD_LEFT);
		$fileName = $prefix . '_' . $this->moduleName . '_model.sql';
		$filePath = APPLICATION_PATH . '/migrations/' . $fileName;

		$lines = [];

		foreach ($this->queries as $query) {
			$lines[] = '-- ' . $query['comment'] . "\n" . $query['query'];
		}
		
		// create a new file with queries content
		file_put_contents($filePath, implode("\n\n", $lines));

		// set the migration as applied in the current app
		foreach ($this->queries as $index => $query) {

			$migration = new Migration();
			$migration->file = $fileName;
			$migration->queryIndex = $index+1;
			$migration->description = $query['comment'];
			$migration->affectedRows = $query['affectedRows'] ?? 0;
			$migration->result = TRUE;
			
			$migration->store();

		}

	}

	/**
	 * Run all methods to create a new module.
	 */
	public function createModule(bool $commonClass): void {

		$folder = $this->createFolder($this->moduleName, $commonClass);

		// translations
		$translations = $this->getAvailableTranslations();
		foreach ($translations as $t) {
			$this->saveTranslation($folder . '/translations/' . $t . '.ini', $t);
		}

		// set the default locale
		Translator::resetLocale();

		// object class file
		$path = ($commonClass ? APPLICATION_PATH : $folder) . '/classes/' . $this->objectName . '.php';
		$this->saveClass($path);

		// create the MVC files
		$this->saveController($folder . '/controller.php');
		$this->saveModel($folder . '/model.php');
		$this->saveViewDefault($folder . '/viewDefault.php');
		$this->saveViewNew($folder . '/viewNew.php');
		$this->saveViewEdit($folder . '/viewEdit.php');
		$this->saveLayoutDefault($folder . '/layouts/default.php');
		$this->saveLayoutNew($folder . '/layouts/new.php');
		$this->saveLayoutEdit($folder . '/layouts/edit.php');

	}

	/**
	 * Create a new db table based on TABLE_FIELDS constant for an ActiveRecord class.
	 * @todo
	 */
	public function createTableByClass(string $class): bool {

		$this->addError('Function under development');
		return FALSE;

		/*
		if (!defined($class . '::TABLE_FIELDS')) {
			$this->addError('TABLE_FIELDS constant is missing in the class ' . $class);
			return FALSE;
		}

		if (0==count($class::TABLE_FIELDS)) {
			$this->addError('TABLE_FIELDS constant has no fields in the class ' . $class);
			return FALSE;
		}

		$fields = [];
		$primaryKeys = [];
		$indexes = [];

		foreach ($class::TABLE_FIELDS as $name => $props) {

			$escaped = '`' . $name  . '`';

			// switch on primary keys, indexes and unique columns
			switch ($props[2]) {

				case 'PRI':
					$primaryKeys[] = $escaped;
					break;

				case 'MUL':
					$indexes[] = '  KEY ' . $escaped . ' (' . $escaped . ')';
					break;

				case 'UNI':
					$indexes[] = '  UNIQUE KEY ' . $escaped . ' (' . $escaped . ')';
					break;

			}

			// field name and type
			$field = '  ' . $escaped . ' ' . strtoupper($props[0]);

			// not nullable
			if ('NO' == $props[1]) {
				$field.=  ' NOT NULL';
			}

			// default value
			if (is_null($props[3])) {
				$field.= 'YES' == $props[1] ? ' DEFAULT NULL' : '';
			} else if (!is_null($props[3])) {
				$field.= " DEFAULT '" . $props[3] . "'";
			}

			// auto_increment
			$field.= $props[4] ? ' ' . $props[4] : '';

			$fields[] = $field;

		}

		// build up the ddl query
		$query = 'CREATE TABLE IF NOT EXISTS `' . $class::TABLE_NAME . "` (\n";
		$query.= implode(",\n", $fields) . ",\n";
		$query.= count($primaryKeys) ? '  PRIMARY KEY (' . implode(',', $primaryKeys) . "),\n" : '';
		$query.= count($indexes) ? implode(",\n", $indexes) . "\n" : '';
		$query.= ') ENGINE = InnoDB';

		$this->db->exec($query);

		return TRUE;
		*/
	}

	/**
	 * Return all DB tables.
	 * @return string[]
	 */
	private function getAllTables(): array {

		$query = 'SHOW FULL TABLES WHERE `Table_type` = "BASE TABLE"';
		return Database::load($query, [], Database::RESULT_LIST);

	}

	public function getAvailableTranslations() {

		// list of files to exclude
		$excludes = ['..', '.', '.DS_Store', 'thumbs.db'];

		try {

			// reads folders file and dirs as plain array
			$translations = array_diff(scandir(dirname(__FILE__) . '/translations', 0), $excludes);

		} catch (PairException $e) {

			$this->addError('Translations folder is not found');
			return [];

		}

		foreach ($translations as &$l) {
			$l = str_replace('.ini', '', $l);
		}

		return $translations;

	}

	/**
	 * Returns the list of tables mapped by inherited ActiveRecord classes except Pair ones.
	 * @return string[]
	 */
	private function getClassesTables(): array {

		$classesTables = [];
		$classes = Utilities::getActiveRecordClasses();

		foreach ($classes as $class) {
			$classesTables[] = $class['tableName'];
		}

		sort($classesTables);

		return $classesTables;

	}

	public function getClassWizardForm(): Form {

		$form = new Form();
		$form->classForControls('form-control');
		$form->text('objectName')->required();
		$form->hidden('tableName')->required();
		return $form;

	}

	/**
	 * Search a class file by its mapped table.
	 */
	public function getClassByTable(string $tableName): string {

		// get all ActiveRecord classes
		$classes = Utilities::getActiveRecordClasses();

		// search for required one
		foreach ($classes as $class => $opts) {
			if ($opts['tableName'] == $tableName) {
				include_once($opts['folder'] . '/' . $opts['file']);
				return $class;
			}
		}

		$this->addError('Class not found for table: ' . $tableName);
		return '';

	}

	/**
	 * Return the table key formatted for use in class text as string or array.
	 */
	private function getFormattedTableKey(): string {

		if (is_array($this->tableKey)) {
			return "['" . implode("', '", $this->tableKey) . "']";
		} else {
			return "'" . $this->tableKey . "'";
		}

	}

	private function getSingularObjectName(string $tableName): string {

		$tmp = explode('_', $tableName);
		$lastWord = strtolower(end($tmp));

		// da implementare nella nuova versione di Pair
		//$singular = Utilities::getSingularObjectName($lastWord);
		//$singleName = str_replace($lastWord, $singular, $tableName);

		$specials = [
			'woman' => 'women', 'man' => 'men', 'child' => 'children', 'tooth' => 'teeth',
			'foot' => 'feet', 'person' => 'people', 'leaf' => 'leaves', 'mouse' => 'mice',
			'goose' => 'geese', 'half' => 'halves', 'knife' => 'knives', 'wife' => 'wives',
			'life' => 'lives', 'elf' => 'elves', 'loaf' => 'loaves', 'potato' => 'potatoes',
			'tomato' => 'tomatoes', 'cactus' => 'cacti', 'focus' => 'foci', 'fungus' => 'fungi',
			'nucleus' => 'nuclei', 'syllabus' => 'syllabi', 'analysis' => 'analyses',
			'diagnosis' => 'diagnoses', 'oasis' => 'oases', 'thesis' => 'theses',
			'crisis' => 'crises', 'phenomenon' => 'phenomena', 'criterion' => 'criteria',
			'datum' => 'data'];

		// irregular noun plurals
		if (in_array($lastWord, $specials)) {
			$singleWord = array_search($lastWord, $specials);
			$singleName = substr($tableName, 0, strpos($tableName, $lastWord)) . $singleWord;
		// singular noun ending in a consonant and then y makes the plural by dropping the y and adding-ies
		} else if ('ies' == substr($tableName, -3)) {
			$singleName = substr($tableName,0,-3) . 'y';
		// a singular noun ending in s, x, z, ch, sh makes the plural by adding-es
		} else if ('es' == substr($tableName,-2) and (in_array(substr($tableName,-4,-2),['ch','sh']) or
				(in_array(substr($tableName,-3,-2),['s','x','z'])))) {
			$singleName = substr($tableName,0,-2);
		// singular nouns form the plural by adding -s
		} else if ('s' == substr($tableName,-1)) {
			$singleName = substr($tableName,0,-1);
		// maybe it’s already singular
		} else {
			$singleName = $tableName;
		}

		return Utilities::getCamelCase($singleName, TRUE);

	}

	/**
	 * Format table key variables to be append on CGI URLs.
	 * @param	string	Optional variable name with $ prefix to put before each key var.
	 */
	private function getTableKeyAsCgiParams(?string $object=NULL): string {

		if (is_null($object)) {
			$object = '$this->' . lcfirst($this->objectName);
		}

		if (is_array($this->tableKey)) {

			$vars = [];

			foreach ($this->tableKey as $k) {
				$vars[] = $object . '->' . Utilities::getCamelCase($k);
			}

			return implode(" . '/' . ", $vars);

		} else {

			return $object . '->' . Utilities::getCamelCase($this->tableKey);

		}

	}

	/**
	 * Search in fk list the field name and return it if found; NULL otherwise.
	 */
	private function getForeignKey(string $field, array $foreignKeys): ?stdClass {

		foreach ($foreignKeys as $col) {
			if ($field == $col->COLUMN_NAME) return $col;
		}

		return NULL;

	}

	/**
	 * If field has length attribute, return it, otherwise return NULL.
	 */
	private function getFieldMaxLength(string $tableName, string $field): ?int {

		$column = $this->db->describeColumn($tableName, $field);

		// split the column Type to recognize field type and length
		preg_match('#^([\w]+)(\([^\)]+\))?#i', $column->Type, $matches);
		return isset($matches[2]) ? (int)substr($matches[2], 1, -1) : NULL;

	}

	/**
	 * Return the first text property of a class by its name.
	 */
	private function getFirstTextProperty(string $class): ?string {

		$obj = new $class();
		$props = $obj->getAllProperties();

		// search for string property
		foreach ($props as $prop => $value) {
			if ('string' == $obj->getPropertyType($prop)) {
				return $prop;
			}
		}

		// in case not found any string property
		reset($props);
		return key($props);

	}

	/**
	 * Return db tables name that has no classes mapped.
	 * @return string[]
	 */
	public function getUnmappedTables(): array {

		// all db tables
		$allTables = $this->getAllTables();

		// all tables mapped by ActiveRecord inherited classes
		$classesTables = $this->getClassesTables();
		$classesTables[] = 'options';

		return array_diff($allTables, $classesTables);

	}

	/**
	 * Return classes name that has no db tables mapped.
	 * @return string[]
	 */
	public function getUnmappedClasses(): array {

		$allTables = $this->getAllTables();

		$classesTables = $this->getClassesTables();

		return array_diff($classesTables, $allTables);

	}

	/**
	 * Return TRUE if the passed field is nullable.
	 */
	private function isFieldNullable(string $tableName, string $field): bool {

		$column = $this->db->describeColumn($tableName, $field);
		return (isset($column->Null) and 'YES' == $column->Null);

	}

	/**
	 * Assert that passed field is in table key.
	 */
	private function isTableKey(string $field): bool {

		if (is_array($this->tableKey)) {
			return (in_array($field, $this->tableKey));
		} else {
			return ($field == $this->tableKey);
		}

	}

	/**
	 * Register groups granted to the module Rule and queue the insert-query.
	 */
	private function registerGrantedGroups(Rule $rule, array $grantedGroups): void {

		if (!count($grantedGroups)) {
			return;
		}

		$groupNames = [];

		// DDL query for ACL
		$aclQuery = "INSERT INTO `acl` (`id`, `rule_id`, `group_id`, `is_default`)\nVALUES ";

		foreach ($grantedGroups as $group) {
			
			$acl = new Acl();
			$acl->groupId = $group->id;
			$acl->ruleId = $rule->id;
			$acl->store();

			$aclQuery .= '(' . $acl->id . ', ' . $acl->ruleId . ', ' . $acl->groupId . ', 0), ';
			
			$groupNames[] = $group->name;

		}

		$aclComment = Translator::do('REGISTRATION_OF_ACL_FOR_GROUPS', implode(', ', $groupNames));
		
		$this->queries[] = [
			'comment' => $aclComment,
			'query' => substr($aclQuery, 0, -2).';',
			'affectedRows' => count($grantedGroups)
		];

	}

	public function registerModule(array $grantedGroups): void {

		// create Module object
		$module					= new Module();
		$module->name			= $this->moduleName;
		$module->version		= '1.0';
		$module->dateReleased	= date('Y-m-d H:i:s');
		$module->appVersion		= Config::get('PRODUCT_VERSION');
		$module->installedBy	= $this->app->currentUser->id;
		$module->dateInstalled	= date('Y-m-d H:i:s');
		$module->store();
		
		// Module registration
		$moduleComment = Translator::do('MODULE_REGISTRATION', $this->moduleName);
		$this->addMigrationQuery($module, $moduleComment);

		// create manifest file
		$plugin = $module->getPlugin();
		$plugin->createManifestFile();

		// create Rule object
		$rule = new Rule();
		$rule->moduleId = $module->id;
		$rule->adminOnly = FALSE;
		$rule->store();

		// Rule registration
		$ruleComment = Translator::do('RULE_REGISTRATION', $this->moduleName);
		$this->addMigrationQuery($rule, $ruleComment);

		$this->registerGrantedGroups($rule, $grantedGroups);

	}

	/**
	 * Replace all layout’s placeholders in one shot.
	 *
	 * @param	string	The HTML template with placeholders.
	 * @param	array	List with placeholders name as key and value.
	 * @return	string
	 */
	private function replaceHolders(string $layout, array $placeholders): string {

		$search = [];
		$replace = [];

		foreach ($placeholders as $placeholder => $value) {
			$search[] = '{' . $placeholder . '}';
			$replace[] = $value;
		}

		return str_replace($search, $replace, $layout);

	}

	/**
	 * Assemble and save the object class file.
	 */
	public function saveClass(string $file): void {

		// here starts building of property cast
		$inits		= [];
		$booleans	= [];
		$csvs		= [];
		$datetimes	= [];
		$integers	= [];
		$floats		= [];

		$init = '';

		// populate the properties declarations
		foreach ($this->binds as $propertyName => $columnName) {

			// set the right property type
			switch ($this->propTypes[$propertyName]) {

				case 'string':
				case 'text':
				case 'enum':
					$phpType = 'string';
					break;

				case 'bool':
					$phpType = 'bool';
					$booleans[] = "'" . $propertyName . "'";
					break;

				case 'set': // csv
					$phpType = 'array';
					$csvs[] = "'" . $propertyName . "'";
					break;

				case 'date':
				case 'datetime':
					$phpType = 'DateTime';
					$datetimes[] = "'" . $propertyName . "'";
					break;

				case 'float':
					$phpType = 'float';
					$floats[] = "'" . $propertyName . "'";
					break;

				case 'int':
					$phpType = 'int';
					$integers[] = "'" . $propertyName . "'";
					break;

				default: // unknown
					$phpType = '';
					break;

			}

			// il tipo PHP può essere vuoto
			if ($phpType) {
				// verifica se la colonna è NULL-able
				$db = Database::getInstance();
				$column = $db->describeColumn($this->tableName, $columnName);
				$phpType = ' ' . ('YES'==$column->Null ? '?' : '') . $phpType;
			}

			// assembles property declaration
			$properties[] = "\t/**\n\t * This property maps “" . $columnName . "” column.\n\t */\n" .
							"\tprotected" . $phpType . " $" . $propertyName . ";";

		}

		if (count($booleans)) {
			$inits[] = '$this->bindAsBoolean(' . implode(', ', $booleans) . ');';
		}

		if (count($csvs)) {
			$inits[] = '$this->bindAsCsv(' . implode(', ', $csvs) . ');';
		}

		if (count($datetimes)) {
			$inits[] = '$this->bindAsDatetime(' . implode(', ', $datetimes) . ');';
		}

		if (count($floats)) {
			$inits[] = '$this->bindAsFloat(' . implode(', ', $floats) . ');';
		}

		if (count($integers)) {
			$inits[] = '$this->bindAsInteger(' . implode(', ', $integers) . ');';
		}

		// at least one var group need to be cast
		if (count($inits)) {

			$init =
"	/**
	 * Method called by constructor just after having populated the object.
	 */
	protected function init(): void {

		" . implode("\n\n\t\t", $inits) . "

	}\n\n";

		}

		// here starts code collect
		$content =
'<?php

use Pair\Orm\ActiveRecord;

class ' . $this->objectName . ' extends ActiveRecord {

' . implode("\n\n", $properties) . '

	/**
	 * Name of related db table.
	 */
	const TABLE_NAME = \'' . $this->tableName . '\';

	/**
	 * Name of primary key db field.
	 */
	const TABLE_KEY = ' . $this->getFormattedTableKey() . ';

' . $init . '}';

		// writes the code into the file
		$this->writeFile($file, $content);

		// start listing Data Definition Language queries for migrations
		$tableComment = Translator::do('CREATION_OF_TABLE', $this->tableName);
		$createTable = Database::load('SHOW CREATE TABLE ' . $this->tableName, [], Database::OBJECT);
		$this->queries[] = ['comment' => $tableComment,'query' => $createTable->{'Create Table'}.';', 'affectedRows' => 0];
		
	}

	private function saveController(string $file): void {

		// here starts code collect
		$content =
'<?php

use Pair\Core\Controller;
use Pair\Helpers\Post;
use Pair\Html\Breadcrumb;

class ' . ucfirst($this->moduleName) . 'Controller extends Controller {

	protected function init(): void {

		Breadcrumb::path($this->lang(\'' . strtoupper($this->tableName) . '\'), \'' . $this->moduleName . '\');

	}

	/**
	 * Add a new object.
	 */
	public function addAction() {

		$' . lcfirst($this->objectName) . ' = new ' . $this->objectName . '();
		$' . lcfirst($this->objectName) . '->populateByRequest();

		// create the record
		if (!$' . lcfirst($this->objectName) . '->store()) {
			$this->raiseError($' . lcfirst($this->objectName) . ');
			return;
		}

		// notify the creation and redirect
		$this->toast($this->lang(\'OBJECT_HAS_BEEN_CREATED\'));
		$this->redirect($this->router->module);

	}

	/**
	 * Show form for edit a ' . $this->objectName . ' object.
	 */
	public function editAction() {

		$' . lcfirst($this->objectName) . ' = $this->getObjectRequestedById(\'' . $this->objectName . '\');

		$this->view = $' . lcfirst($this->objectName) . ' ? \'edit\' : \'default\';

	}

	/**
	 * Modify a ' . $this->objectName . ' object.
	 */
	public function changeAction() {

		$' . lcfirst($this->objectName) . ' = new ' . $this->objectName . '(Post::get(' . $this->getFormattedTableKey() . '));
		$' . lcfirst($this->objectName) . '->populateByRequest();

		// apply the update
		if (!$' . lcfirst($this->objectName) . '->store()) {
			$this->raiseError($' . lcfirst($this->objectName) . ');
			return;
		}

		// notify the change and redirect
		$this->toast($this->lang(\'OBJECT_HAS_BEEN_CHANGED_SUCCESFULLY\'));
		$this->redirect($this->router->module);

	}

	/**
	 * Delete a ' . $this->objectName . ' object.
	 */
	public function deleteAction() {

	 	$' . lcfirst($this->objectName) . ' = $this->getObjectRequestedById(\'' . $this->objectName . '\');

		// execute deletion
		if (!$' . lcfirst($this->objectName) . '->delete()) {
			$this->raiseError($' . lcfirst($this->objectName) . ');
			return;
		}

		// notify the deletion and redirect
		$this->toast($this->lang(\'OBJECT_HAS_BEEN_DELETED_SUCCESFULLY\'));
		$this->redirect($this->router->module);

	}

}';

		// writes the code into the file
		$this->writeFile($file, $content);

	}

	/**
	 * Save the model.php file with all Form entries.
	 *
	 * @param	string	File name with relative path.
	 */
	private function saveModel(string $file): void {

		$lists = [];

		// columns that are referencing other tables
		$foreignKeys = $this->db->getForeignKeys($this->tableName);

		foreach ($this->binds as $property => $field) {

			// internal fields
			if (in_array($field, self::INTERNAL_FIELDS)) {

				continue;

			// hidden inputs
			} else if ($this->isTableKey($field)) {

				$control = '$form->hidden(\'' . $property . "')";

			// foreign key
			} else if (!is_null($this->getForeignKey($field, $foreignKeys))) {

				// get the fk-column for the current field
				$col = $this->getForeignKey($field, $foreignKeys);

				// get class of a referenced table
				$fkClass = $this->getClassByTable($col->REFERENCED_TABLE_NAME);

				// class found, add a select with options from related object
				if ($fkClass) {

					// some useful variables
					$tmp		= explode('\\', $fkClass);
					$fkVar		= '$' . Utilities::getCamelCase(end($tmp));
					$fkColumn	= Utilities::getCamelCase($col->REFERENCED_COLUMN_NAME);

					// add the list of all fk-class objects
					$lists[] = "\t\t" . $fkVar . ' = ' . $fkClass . "::all();\n";

					// create the new select
					$control  = '$form->select(\'' . $property . '\')';

					// search the first string-type field in the class, to use as label
					$firstTextProp = $this->getFirstTextProperty((string)$fkClass);
					if (!$firstTextProp) {
						$firstTextProp = $fkColumn;
					}

					// if is nullable field, add an empty option
					if ($this->isFieldNullable($this->tableName, $field)) {
						$control .= '->empty()';
					}

					// set value for the select control
					$control .= '->options(' . $fkVar . ', \'' . $fkColumn . '\', \'' . $firstTextProp . '\')';

				// class not found, create a simple text input control
				} else {

					$control = "\$form->text('" . $property . "')";

				}

			// standard inputs
			} else {

				switch ($this->propTypes[$property]) {

					case 'bool':
						$control = "\$form->checkbox('" . $property . "')";
						break;

					case 'date':
						$control = "\$form->date('" . $property . "')";
						break;

					case 'datetime':
						$control = "\$form->datetime('" . $property . "')";
						break;

					case 'int':
						$control = "\$form->number('" . $property . "')";
						break;

					case 'float':
						$control = "\$form->number('" . $property . "')->step('0.01')";
						break;

					case 'enum':
						$values = [];
						foreach ($this->members[$property] as $value) {
							$values [] = "'" . $value . "'=>'" . $value . "'";
						}
						$control  = "\$form->select('" . $property . "')";
						if ($this->isFieldNullable($this->tableName, $field)) {
							$control .= '->empty()';
						}
						if (count($values)) {
							$control .= '->options([' . implode(',', $values) . '])';
						}
						break;

					// control name for "set" has []
					case 'set':
						$values = [];
						foreach ($this->members[$property] as $value) {
							$values [] = "'" . $value . "'=>'" . $value . "'";
						}
						$control  = "\$form->select('" . $property . "[]')->multiple()";
						if ($this->isFieldNullable($this->tableName, $field)) {
							$control .= '->empty()';
						}
						if (count($values)) {
							$control .= '->options([' . implode(',', $values) . '])';
						}
						break;

					default:
					case 'string':
						$control = "\$form->text('" . $property . "')";
						$maxLength = $this->getFieldMaxLength($this->tableName, $field);
						if ($maxLength) {
							$control .= '->maxLength(' . $maxLength . ')';
						}
						break;

					case 'text':
						$control = "\$form->textarea('" . $property . "')";
						break;

				}

			}

			// if is not nullable field, set as required
			if ($this->propTypes[$property] != 'bool' and !$this->isFieldNullable($this->tableName, $field)) {
				$control .= '->required()';
			}

			// indentation and label
			$controls[] = "\t\t" . $control . "->label('" . strtoupper($field) . "');";

		}

		$orderIndex = 1;

		// look for the first text property to add the sortable headers list
		foreach ($this->propTypes as $pName => $pType) {
			if ('string' == $pType) {
				$this->orderOptions[$orderIndex++]= '`' . $this->binds[$pName] . '`';
				$this->orderOptions[$orderIndex++]= '`' . $this->binds[$pName] . '` DESC';
			}
		}

		// here starts code collect
		$content =
'<?php

use Pair\Core\Model;
use Pair\Html\Form;

class ' . ucfirst($this->moduleName) . 'Model extends Model {

	public function getQuery(string $class): string {

		$query = \'SELECT * FROM `' . $this->tableName . '`\';

		return $query;

	}
';

		if (count($this->orderOptions)) {

			$content .= '
	protected function getOrderOptions(): array {

		return [';

			foreach ($this->orderOptions as $idx => $orderOption) {
				$content .= "\n\t\t\t" . $idx . ' => \'' . $orderOption . "',";
			}

			$content .= '
		];

	}
';

		}

		$content .= '
	/**
	 * Returns the Form object for create/edit ' . $this->objectName . ' objects.
	 */
	public function get' . $this->objectName . 'Form(' . ucfirst($this->objectName) . ' $' . lcfirst($this->objectName) . '): Form {

' . implode('', $lists) .

'		$form = new Form();

' . implode("\n", $controls) . '

		$form->classForControls(\'form-control\');
		$form->values($' . lcfirst($this->objectName) . ');

		return $form;

	}

}';

		// writes the code into the file
		$this->writeFile($file, $content);

	}

	/**
	 * Create and save a translation file.
	 *
	 * @param	string	Full path to translation INI file.
	 * @param	string	Language-tag (eg. “en-GB”).
	 */
	private function saveTranslation(string $file, string $representation): void {

		// get the Translator singleton
		$tran = Translator::getInstance();

		// store the current Locale
		//$currentLocale = $tran->getCurrentLocale();

		// get a new Locale object
		$newLocale = Locale::getByRepresentation($representation);

		// if Locale doesn’t exist in application, return
		if (!$newLocale) {
			return;
		}

		// set the new Locale
		$tran->setLocale($newLocale);

		$fields = [];

		// list of table fields
		foreach ($this->binds as $field) {

			// internal fields
			if (in_array($field, self::INTERNAL_FIELDS)) {
				continue;
			}

			$fields[] = strtoupper($field) . ' = "' . str_replace('_', ' ', ucfirst($field)) . '"';

		}

		$content = '';

		// here starts code collect
		$content .= strtoupper($this->tableName) . ' = "' . str_replace('_', ' ', ucfirst($this->tableName)) . "\"\n";
		$content .= 'OBJECT_HAS_BEEN_CREATED = "' . Translator::do('OBJECT_HAS_BEEN_CREATED') . "\"\n";
		$content .= 'OBJECT_HAS_BEEN_CHANGED_SUCCESFULLY = "' . Translator::do('OBJECT_HAS_BEEN_CHANGED_SUCCESFULLY') . "\"\n";
		$content .= 'OBJECT_HAS_BEEN_DELETED_SUCCESFULLY = "' . Translator::do('OBJECT_HAS_BEEN_DELETED_SUCCESFULLY') . "\"\n";
		$content .= 'NEW_OBJECT = "' . Translator::do('NEW_OBJECT') . "\"\n";
		$content .= 'EDIT_OBJECT = "' . Translator::do('EDIT_OBJECT') . "\"\n";
		$content .= implode("\n", $fields);

		// write the code into the file
		$this->writeFile($file, $content);

		// sets back the kept Locale
		//$tran->setLocale($currentLocale);

	}

	private function saveViewDefault(string $file): void {

		// here starts code collect
		$content =
'<?php

use Pair\Core\View;

class ' . ucfirst($this->moduleName) . 'ViewDefault extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang(\'' . strtoupper($this->tableName) . '\');

		$' . Utilities::getCamelCase($this->tableName) . ' = $this->model->getItems(\'' .  $this->objectName . '\');

		$this->pagination->count = $this->model->countItems(\'' .  $this->objectName . '\');

		$this->assign(\'' . Utilities::getCamelCase($this->tableName) . '\', $' . Utilities::getCamelCase($this->tableName) . ');

	}

}';

		// writes the code into the file
		$this->writeFile($file, $content);

	}

	/**
	 * Based on custom layouts, create the PHP layout file for default action.
	 */
	private function saveLayoutDefault(string $file): void {

		// trigger for link on first item
		$fieldWithLink = TRUE;

		// get table’s foreign keys
		$foreignKeys = $this->db->getForeignKeys($this->tableName);

		// collects table headers and cells
		foreach ($this->binds as $property=>$field) {

			// internal fields
			if (in_array($field, self::INTERNAL_FIELDS)) {

				continue;

			// jump the table keys
			} else if (!$this->isTableKey($field)) {

				// replace the table-header placeholder
				$headers[] = str_replace('{tableHeader}', "<?php \$this->_('" . strtoupper($field) . "') ?>", $this->layouts['default-table-header']);

				// get the fk-column for the current field
				$col = $this->getForeignKey($field, $foreignKeys);

				// set the proper value for this property visualization
				if (is_null($col)) {

					// add the date/datetime format method
					switch ($this->propTypes[$property]) {
						case 'date':	 $object = 'print $o->formatDate(\'' . $property . '\')'; break;
						case 'datetime': $object = 'print $o->formatDateTime(\'' . $property . '\')'; break;
						default:		 $object = '$o->printHtml(\'' . $property . '\')'; break;
					}

				// or get it by the related Pair object
				} else {

					// get the class that’s mapping a table
					$fkClass = $this->getClassByTable($col->REFERENCED_TABLE_NAME);

					// class found, get a related object
					if ($fkClass) {

						// search the first string-type field in the class, to use as label
						$firstTextProp = $this->getFirstTextProperty((string)$fkClass);
						if (!$firstTextProp) {
							$firstTextProp = Utilities::getCamelCase($col->REFERENCED_COLUMN_NAME);
						}

						$object = 'print htmlspecialchars($o->getParentProperty(\'' . $property . '\', \'' . $firstTextProp . '\'))';

					// class not found, show a simple field value
					} else {

						$object = 'print htmlspecialchars($o->' . $property . ')';

					}

				}

				// complete the property value
				$replace = '<?php ' . $object . ' ?>';

				// replace the table-cell placeholder with link on the first item
				if ($fieldWithLink) {
					$replace = '<a href="' . $this->moduleName . '/edit/<?php print ' . $this->getTableKeyAsCgiParams('$o') . ' ?>">' . $replace . '</a>';
					$fieldWithLink = FALSE;
				}

				// format table cell based on layout
				$cells[] = str_replace('{tableCell}', $replace, $this->layouts['default-table-cell']);

			}

		}

		// the cycle that outputs a full table-row
		$tableRows =
			'<?php foreach ($this->' . Utilities::getCamelCase($this->tableName) . ' as $o) {' .
			" ?>\n<tr>\n" . implode("\n", $cells) . "\n</tr>\n<?php } ?>\n";

		$ph['pageTitle']	= '<?php $this->_(\'' . strtoupper($this->tableName) . '\') ?>';
		$ph['linkAdd']		= $this->moduleName . '/new';
		$ph['newElement']	= '<?php $this->_(\'NEW_OBJECT\') ?>';
		$ph['itemsArray']	= '$this->' . Utilities::getCamelCase($this->tableName);
		$ph['tableHeaders']	= implode("\n", $headers);
		$ph['tableRows']	= $tableRows;

		// replace value on placeholders
		$content = $this->replaceHolders($this->layouts['default-page'], $ph);

		// writes the code into the file
		$this->writeFile($file, $content);

	}

	private function saveViewNew(string $file): void {

		// here starts code collect
		$content =
'<?php

use Pair\Core\View;
use Pair\Html\Breadcrumb;

class ' . ucfirst($this->moduleName) . 'ViewNew extends View {

	public function render(): void {

		$this->app->pageTitle = $this->lang(\'NEW_OBJECT\');

		Breadcrumb::path($this->lang(\'NEW_OBJECT\'), \'new\');

		$form = $this->model->get' . $this->objectName . 'Form(new ' . $this->objectName . ');

		$this->assign(\'form\', $form);

	}

}
';

		// writes the code into the file
		$this->writeFile($file, $content);

	}

	private function saveLayoutNew(string $file): void {

		$fields = [];

		// collects fields
		foreach ($this->binds as $property=>$field) {

			// internal fields
			if (in_array($field, self::INTERNAL_FIELDS)) {

				continue;

			} else if (!$this->isTableKey($field)) {

				$search = ['{fieldLabel}', '{fieldControl}'];
				$replace = ['<?php $this->form->printLabel(\'' . $property . '\') ?>',
							'<?php $this->form->printControl(\'' . $property . '\') ?>'];
				$fields[] = str_replace($search, $replace, $this->layouts['new-field']);

			}

		}

		$ph['pageTitle']	= '<?php $this->_(\'NEW_OBJECT\') ?>';
		$ph['formAction']	= $this->moduleName . '/add';
		$ph['fields']		= implode('', $fields);
		$ph['cancelUrl']	= $this->moduleName;

		// replace value on placeholders
		$content = $this->replaceHolders($this->layouts['new-page'], $ph);

		// writes the code into the file
		$this->writeFile($file, $content);

	}

	private function saveViewEdit(string $file): void {

		// need loop route-params for each table key
		if (is_array($this->tableKey)) {

			$params	= '';
			$vars	= [];

			foreach ($this->tableKey as $index => $k) {
				$var	= '$' . Utilities::getCamelCase($k);
				$vars[]	= $var;
				$params.= '		' . $var . ' = Router::get(' . $index . ");\n";
			}

			$key = '[' . implode(', ', $vars) . ']';
			$editId = '$' . lcfirst($this->objectName) . '->' . implode('/', $vars);

		} else {

			$key	= '$' . Utilities::getCamelCase($this->tableKey);
			$params	= '		' . $key . ' = Router::get(0);';
			$editId = '$' . lcfirst($this->objectName) . '->' . Utilities::getCamelCase($this->tableKey);

		}

		// here starts code collect
		$content =
'<?php

use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Breadcrumb;

class ' . ucfirst($this->moduleName) . 'ViewEdit extends View {

	public function render(): void {

' . $params . '
		$' . lcfirst($this->objectName) . ' = ' . $this->objectName . '::find(' . $key . ');

		$this->app->pageTitle = $this->lang(\'EDIT_OBJECT\');

		Breadcrumb::path($this->lang(\'EDIT_OBJECT\'), \'edit/\' . ' . $editId . ');

		$form = $this->model->get' . ucfirst($this->objectName) . 'Form($' . lcfirst($this->objectName) . ');

		$this->assign(\'form\', $form);
		$this->assign(\'' . lcfirst($this->objectName) . '\', $' . lcfirst($this->objectName) . ');

	}

}
';

		// writes the code into the file
		$this->writeFile($file, $content);

	}

	private function saveLayoutEdit(string $file): void {

		$fields = [];
		$hiddens = [];

		// collects fields
		foreach ($this->binds as $property=>$field) {

			// internal fields
			if (in_array($field, self::INTERNAL_FIELDS)) {

				continue;

			} else if (!$this->isTableKey($field)) {

				$search = ['{fieldLabel}', '{fieldControl}'];
				$replace = ['<?php $this->form->printLabel(\'' . $property . '\') ?>',
						'<?php $this->form->printControl(\'' . $property . '\') ?>'];
				$fields[] = str_replace($search, $replace, $this->layouts['edit-field']);

			} else {

				$hiddens[] = '<?php $this->form->printControl(\'' . $property . '\') ?>';

			}

		}

		$ph['pageTitle']	= '<?php $this->_(\'EDIT_OBJECT\') ?>';
		$ph['formAction']	= $this->moduleName . '/change';
		$ph['fields']		= implode('', $fields);
		$ph['hiddenFields']	= implode('', $hiddens);
		$ph['object']		= lcfirst($this->objectName);
		$ph['cancelUrl']	= $this->moduleName;
		$ph['deleteUrl']	= $this->moduleName . '/delete/<?php print ' . $this->getTableKeyAsCgiParams() . ' ?>';

		// replace value on placeholders
		$content = $this->replaceHolders($this->layouts['edit-page'], $ph);

		// writes the code into the file
		$this->writeFile($file, $content);

	}

	/**
	 * Setup all needed variables before to proceed class/module creation.
	 *
	 * @param	string	Table name all lowercase with underscores.
	 * @param	string	Optional object name with uppercase first and case sensitive.
	 * @param	string	Optional module name all lowercase alpha chars only.
	 */
	public function setupVariables(string $tableName, ?string $objectName=NULL, ?string $moduleName=NULL): void {

		$app = Application::getInstance();

		// initialize layouts array
		$layouts = [];

		// custom layouts
		$customLayout = $app->template->getPath() . 'crafter_layouts.php';

		// load proper layout contents
		include_once (file_exists($customLayout) ? $customLayout : 'assets/layouts.php');

		$this->layouts		= $layouts;

		$this->tableName	= $tableName;
		$this->moduleName	= $moduleName ? $moduleName : strtolower(str_replace('_', '', $tableName));
		$this->objectName	= $objectName ? $objectName : $this->getSingularObjectName($tableName);
		$this->author		= $app->currentUser->fullName ?? '';
		$this->package		= Config::get('PRODUCT_NAME');

		$this->propTypes	= [];
		$this->binds		= [];

		// get table columns list
		$columns = $this->db->describeTable($this->tableName);

		if (!$columns) {
			return;
		}

		// iterates all found columns
		foreach ($columns as $column) {

			$property = Utilities::getCamelCase($column->Field);

			// set the table key
			if ('PRI' == $column->Key) {

				// if already set a primary key, change to compound key
				if (isset($this->tableKey) and is_string($this->tableKey)) {

					$this->tableKey = [$this->tableKey, $column->Field];

				// otherwise it’s already a compound key
				} else if (isset($this->tableKey) and is_array($this->tableKey) and count($this->tableKey)>1) {

					$this->tableKey[] = $column->Field;

				// otherwise set a primary key
				} else {

					$this->tableKey = (string)$column->Field;

				}

			}

			// split the column Type to recognize field type and length
			preg_match('#^([\w]+)(\([^\)]+\))?#i', $column->Type, $matches);
			$type	= $matches[1];
			$length	= isset($matches[2]) ? substr($matches[2], 1, -1) : NULL;

			// mark type as needed by Pair ActiveRecord
			if (1 == $length and in_array($type, ['tinyint', 'smallint', 'mediumint', 'int'])) {

				// int(1) is recognized like bool type
				$this->propTypes[$property] = 'bool';

			} else {

				switch ($type) {

					case 'date':
						$this->propTypes[$property] = 'date';
						break;

					case 'datetime':
					case 'timestamp':
						$this->propTypes[$property] = 'datetime';
						break;

					case 'bool':
					case 'boolean':
						$this->propTypes[$property] = 'bool';
						break;

					case 'enum':
			 		case 'set':
						$this->propTypes[$property] = $type;
						$this->members[$property] = $length ? explode("','", substr($length, 1, -1)) : NULL;
						break;

					case 'tinyint':
					case 'smallint':
					case 'mediumint':
					case 'int':
					case 'year':
						$this->propTypes[$property] = 'int';
						break;

					case 'dec':
			 		case 'decimal':
			 		case 'double':
			 		case 'double precision':
			 		case 'fixed':
			 		case 'float':
			 		case 'numeric':
			 		case 'real':
			 			$this->propTypes[$property] = 'float';
			 			break;

			 		case 'tinytext':
			 		case 'text':
			 		case 'mediumtext':
			 		case 'longtext':
						$this->propTypes[$property] = 'text';
						break;

					// char, varchar, json, time etc.
			 		default:
			 			$this->propTypes[$property] = 'string';
			 			break;

				}

			}

			// populates binds with object properties as key and db column as value
			$this->binds[$property] = $column->Field;

		}

	}

	/**
	 * Write content into file with 0777 permissions.
	 *
	 * @param	string	File name and full path.
	 * @param	string	File content.
	 */
	private function writeFile(string $file, string $content): void {

		$old = umask(0);
		file_put_contents($file, $content);
		umask($old);
		chmod($file, 0777);

	}

}