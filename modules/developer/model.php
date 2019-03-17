<?php

use Pair\Application;
use Pair\Form;
use Pair\Language;
use Pair\Locale;
use Pair\Model;
use Pair\Translator;
use Pair\Utilities;

class DeveloperModel extends Model {

	/**
	 * Name of db table.
	 * @var string
	 */
	protected $tableName;
	
	/**
	 * Db Table primary or compound key.
	 * @var string|array
	 */
	protected $tableKey;

	/**
	 * List of couples property => db_field.
	 * @var array
	 */
	protected $binds;
	
	/**
	 * Class name with uppercase first char.
	 * @var string
	 */
	protected $objectName;
	
	/**
	 * List of properties type (property_name => type).
	 * @var array
	 */
	protected $propType;
	
	/**
	 * List of values for each enum/set property.
	 * @var array
	 */
	private $members;
	
	/**
	 * Name of CRUD module, all lowercase with no underscore.
	 * @var string
	 */
	protected $moduleName;
	
	/**
	 * File author meta tag.
	 * @var string
	 */
	protected $author;
	
	/**
	 * File package meta tag.
	 * @var string
	 */
	protected $package;

	/**
	 * Custom layouts array.
	 * @var array
	 */
	protected $layouts;
	
	/**
	 * Return db tables name that has no classes mapped.
	 * 
	 * @return array:string
	 */
	public function getUnmappedTables() {
		
		$this->db->setQuery('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
		$allTables = $this->db->loadResultList();
		
		$classesTables = $this->getClassesTables();
		$classesTables[] = 'options';
		
		$unmappedTables = array_diff($allTables, $classesTables);
		
		return $unmappedTables;
		
	}
	
	/**
	 * Return classes name that has no db tables mapped.
	 *
	 * @return array:string
	 */
	public function getUnmappedClasses() {
		
		$this->db->setQuery('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
		$allTables = $this->db->loadResultList();
		
		$classesTables = $this->getClassesTables();
		
		$unmappedClasses = array_diff($classesTables, $allTables);
		
		return $unmappedClasses;
		
	}
	
	/**
	 * Return list of tables mapped by ActiveRecord inherited classes.
	 *
	 * @return array
	 */
	public function getClassesTables() {
	
		$classesTables = [];
		$classes = Utilities::getActiveRecordClasses();
		
		foreach ($classes as $class) {
			$classesTables[] = $class['tableName'];
		}
		
		sort($classesTables);

		return $classesTables;
		
	}
	
	public function getClassWizardForm() {
		
		$form = new Form();
		$form->addControlClass('form-control');
		$form->addInput('objectName')->setRequired();
		$form->addInput('tableName')->setType('hidden')->setRequired();
		return $form;
		
	}

	public function getModuleWizardForm() {
	
		$form = new Form();
		$form->addControlClass('form-control');
		$form->addInput('objectName')->setRequired();
		$form->addInput('moduleName')->setRequired();
		$form->addInput('commonClass')->setType('bool');
		$form->addInput('tableName')->setType('hidden')->setRequired();
		return $form;
	
	}
	
	/**
	 * Create a new db table based on TABLE_FIELDS constant for an ActiveRecord class.
	 * 
	 * @param	string	Class name.
	 * 
	 * @return	boolean
	 * 
	 * @todo
	 */
	public function createTableByClass(string $class) {

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
			
			$escaped = $this->db->escape($name);
			
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
		
		if ($this->db->getLastError()) {
			$this->addError($this->db->getLastError());
			return FALSE;
		}
		
		return TRUE;
		*/
	}
	
	/**
	 * Search a class file by its mapped table.
	 * 
	 * @param	string	Table name.
	 * 
	 * @return	string
	 */
	public function getClassByTable(string $tableName) {
		
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
	
	public function getAvailableTranslations() {
		
		// list of files to exclude
		$excludes = array('..', '.', '.DS_Store', 'thumbs.db');
		
		try {
			
			// reads folders file and dirs as plain array
			$translations = array_diff(scandir(dirname(__FILE__) . '/translations', 0), $excludes);
			
		} catch (Exception $e) {
		
			$this->addError('Translations folder is not found');
			return [];
		
		}
		
		foreach ($translations as &$l) {
			$l = str_replace('.ini', '', $l);
		}
		
		return $translations;
		
	}
	
	/**
	 * Setup all needed variables before to proceed class/module creation.
	 * 
	 * @param	string	Table name all lowercase with underscores.
	 * @param	string	Optional object name with uppercase first and case sensitive.
	 * @param	string	Optional module name all lowercase alpha chars only.
	 */
	public function setupVariables($tableName, $objectName=NULL, $moduleName=NULL) {
		
		$app = Application::getInstance();

		// initialize layouts array
		$layouts = [];

		// custom layouts
		$customLayout = $app->template->getPath() . 'developer_layouts.php';

		// load proper layout contents
		include_once (file_exists($customLayout) ? $customLayout : 'assets/layouts.php');
		
		$this->layouts		= $layouts;		
		
		$this->tableName	= $tableName;
		$this->moduleName	= $moduleName ? $moduleName : strtolower(str_replace('_', '', $tableName));
		$this->objectName	= $objectName ? $objectName : $this->getSingularObjectName($tableName);
		$this->author		= $app->currentUser->fullName;
		$this->package		= PRODUCT_NAME;
		
		$this->propType		= array();
		$this->binds		= array();
		
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
				if (is_string($this->tableKey)) {
					
					$this->tableKey = array($this->tableKey, $column->Field);
				
				// otherwise it’s already a compound key
				} else if (is_array($this->tableKey) and count($this->tableKey)>1) {
					
					$this->tableKey[] = $column->Field;
					
				// otherwise set a primary key
				} else {
					
					$this->tableKey = $column->Field;
					
				}
				
			}

			// split the column Type to recognize field type and length
			preg_match('#^([\w]+)(\([^\)]+\))?#i', $column->Type, $matches);
			$type	= $matches[1];
			$length	= isset($matches[2]) ? substr($matches[2], 1, -1) : NULL;

			// mark type as needed by Pair ActiveRecord
			if (1 == $length and in_array($type, ['tinyint', 'smallint', 'mediumint', 'int'])) {

				// int(1) is recognized like bool type
				$this->propType[$property] = 'bool';
				
			} else {
			
				switch ($type) {
					
					case 'date':
						$this->propType[$property] = 'date';
						break;
						
					case 'datetime':
					case 'timestamp':
						$this->propType[$property] = 'datetime';
						break;

					case 'bool':
					case 'boolean':
						$this->propType[$property] = 'bool';
						break;
						
					case 'enum':
			 		case 'set':
						$this->propType[$property] = $type;
						$this->members[$property] = $length ? explode("','", substr($length, 1, -1)) : NULL;
						break;
						
					case 'tinyint':
					case 'smallint':
					case 'mediumint':
					case 'int':
					case 'year':
						$this->propType[$property] = 'int';
						break;
					
					case 'dec':
			 		case 'decimal':
			 		case 'double':
			 		case 'double precision':
			 		case 'fixed':
			 		case 'float':
			 		case 'numeric':
			 		case 'real':
			 			$this->propType[$property] = 'float';
			 			break;
			 			
			 		case 'tinytext':
			 		case 'text':
			 		case 'mediumtext':
			 		case 'longtext':
						$this->propType[$property] = 'text';
						break;
						
					// char, varchar, json, time etc.
			 		default:
			 			$this->propType[$property] = 'string';
			 			break;
			 			
				}
				
			}
			
			// populates binds with object properties as key and db column as value
			$this->binds[$property] = $column->Field;
			
		}
		
	}
	
	/**
	 * Assemble and save the object class file.
	 * 
	 * @param	string	File name.
	 */
	public function saveClass($file) {
		
		// here starts building of property cast
		$inits		= [];
		$booleans	= [];
		$csvs		= [];
		$datetimes	= [];
		$integers	= [];
		$floats		= [];
		
		$init = '';
		
		// populate the properties declarations
		foreach ($this->binds as $property => $field) {
			
			// set the right property type
			switch ($this->propType[$property]) {
				
				case 'string':
				case 'text':
				case 'enum':
					$phpType = 'string';
					break;

				case 'bool':
					$phpType = 'bool';
					$booleans[] = "'" . $property . "'";
					break;
				
				case 'set':
					$phpType = 'csv';
					$csvs[] = "'" . $property . "'";
					break;
					
				case 'date':
				case 'datetime':
					$phpType = 'DateTime';
					$datetimes[] = "'" . $property . "'";
					break;
					
				case 'float':
					$phpType = 'float';
					$floats[] = "'" . $property . "'";
					break;
					
				case 'int':
					$phpType = 'int';
					$integers[] = "'" . $property . "'";
					break;
					
				default:
					$phpType = 'unknown';
					break;
					
			}

			// assembles property declaration
			$properties[] = "\t/**\n\t * This property maps “" . $field . "” column.\n\t " .
							"* @var " . $phpType . "\n\t */\n\tprotected $" . $property . ";";
			
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
	protected function init() {

		" . implode("\n\n\t\t", $inits) . "

	}\n\n";

		}
		
		// here starts code collect
		$content = 
'<?php

use Pair\ActiveRecord;

class ' . $this->objectName . ' extends ActiveRecord {
	
' . implode("\n\n", $properties) . '
	
	/**
	 * Name of related db table.
	 * @var string
	 */
	const TABLE_NAME = \'' . $this->tableName . '\';
	
	/**
	 * Name of primary key db field.
	 * @var string|array
	 */
	const TABLE_KEY = ' . $this->getFormattedTableKey() . ';

' . $init . '}';
		
		// writes the code into the file
		$this->writeFile($file, $content);
		
	}

	/**
	 * Save the model.php file with all Form entries.
	 * 
	 * @param	string	File name with relative path.
	 */
	public function saveModel($file) {

		$lists  = [];
		$fields = [];
		
		// columns that are referencing other tables
		$foreignKeys = $this->db->getForeignKeys($this->tableName);
		
		foreach ($this->binds as $property => $field) {
			
			// hidden inputs
			if ($this->isTableKey($field)) {
				
				$control = '$form->addInput(\'' . $property . "')->setType('hidden')";

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
					$lists[] = "\t\t" . $fkVar . ' = ' . $fkClass . "::getAllObjects();\n";
					
					// create the new select
					$control  = '$form->addSelect(\'' . $property . '\')';
	
					// search the first string-type field in the class, to use as label
					$firstTextProp = $this->getFirstTextProperty($fkClass);
					if (!$firstTextProp) {
						$firstTextProp = $fkColumn;
					}
					
					// if is nullable field, add an empty option
					if ($this->isFieldNullable($this->tableName, $field)) {
						$control .= '->prependEmpty()';
					}
					
					// set value for the select control
					$control .= '->setListByObjectArray(' . $fkVar . ', \'' . $fkColumn . '\', \'' . $firstTextProp . '\')';
					
				// class not found, create a simple text input control 
				} else {
					
					$control = "\$form->addInput('" . $property . "')";
					
				}
				
			// standard inputs
			} else {
				
				switch ($this->propType[$property]) {
					
					case 'bool':
						$control = "\$form->addInput('" . $property . "')->setType('bool')";
						break;
					
					case 'date':
						$control = "\$form->addInput('" . $property . "')->setType('date')";
						break;

					case 'datetime':
						$control = "\$form->addInput('" . $property . "')->setType('datetime')";
						break;
								
					case 'int':
						$control = "\$form->addInput('" . $property . "')->setType('number')";
						break;

					case 'float':
						$control = "\$form->addInput('" . $property . "')->setType('number')->setStep('0.01')";
						break;

					case 'enum':
						$values = array();
						foreach ($this->members[$property] as $value) {
							$values [] = "'" . $value . "'=>'" . $value . "'";
						}
						$control  = "\$form->addSelect('" . $property . "')";
						if ($this->isFieldNullable($this->tableName, $field)) {
							$control .= '->prependEmpty()';
						}
						if (count($values)) {
							$control .= '->setListByAssociativeArray(array(' . implode(',', $values) . '))';
						}
						break;
						
					// control name for "set" has []
					case 'set':
						$values = array();
						foreach ($this->members[$property] as $value) {
							$values [] = "'" . $value . "'=>'" . $value . "'";
						}
						$control  = "\$form->addSelect('" . $property . "[]')->setMultiple()";
						if ($this->isFieldNullable($this->tableName, $field)) {
							$control .= '->prependEmpty()';
						}
						if (count($values)) {
							$control .= '->setListByAssociativeArray(array(' . implode(',', $values) . '))';
						}
						break;
					
					default: 
					case 'string':
						$control = "\$form->addInput('" . $property . "')";
						$maxLength = $this->getFieldMaxLength($this->tableName, $field);
						if ($maxLength) {
							$control .= '->setMaxLength(' . $maxLength . ')';
						}
						break;
					
					case 'text':
						$control = "\$form->addTextarea('" . $property . "')";
						break;
				
				}
				
			}
			
			// if is not nullable field, set as required
			if ($this->propType[$property] != 'bool' and !$this->isFieldNullable($this->tableName, $field)) {
				$control .= '->setRequired()';
			}
			
			// indentation and label
			$controls[] = "\t\t" . $control . "->setLabel('" . strtoupper($field) . "');";
		
		}
		
		// here starts code collect
		$content = 
'<?php

use Pair\Form;
use Pair\Model;

class ' . ucfirst($this->moduleName) . 'Model extends Model {

	/**
	 * Returns object list with pagination.
	 *
	 * @return	array:' . $this->objectName . '
	 */
	public function get' . ucfirst($this->moduleName) . '() {

		$query = \'SELECT * FROM `' . $this->tableName . '` LIMIT \' . $this->pagination->start . \', \' . $this->pagination->limit;

		return ' . $this->objectName . '::getObjectsByQuery($query);

	}

	/**
	 * Returns count of available objects.
	 *
	 * @return	int
	 */
	public function countListItems() {

		return ' . $this->objectName . '::countAllObjects();

	}

	/**
	 * Returns the Form object for create/edit ' . $this->objectName . ' objects.
	 * 
	 * @return Form
	 */ 
	public function get' . $this->objectName . 'Form() {

' . implode('', $lists) .

'		$form = new Form();
			
		$form->addControlClass(\'form-control\');
			
' . implode("\n", $controls) . '
		
		return $form;
		
	}
				
}';
		
		// writes the code into the file
		$this->writeFile($file, $content);

	}
	
	public function saveController($file) {
		
		// here starts code collect
		$content = 
'<?php

use Pair\Breadcrumb;
use Pair\Controller;
use Pair\Input;
 		
class ' . ucfirst($this->moduleName) . 'Controller extends Controller {

	/**
	 * Initialize the Breadcrumb.
	 * {@inheritDoc}
	 *
	 * @see Pair\Controller::init()
	 */
	protected function init() {
		
		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang(\'' . strtoupper($this->tableName) . '\'), \'' . $this->moduleName . '\');
		
	}
	
	/**
	 * Add a new object.
	 */
	public function addAction() {
	
		$' . lcfirst($this->objectName) . ' = new ' . $this->objectName . '();
		$' . lcfirst($this->objectName) . '->populateByRequest();

		$result = $' . lcfirst($this->objectName) . '->store();
		
		if ($result) {
			$this->enqueueMessage($this->lang(\'' . strtoupper($this->objectName) . '_HAS_BEEN_CREATED\'));
			$this->redirect(\'' . $this->moduleName . '\');
		} else {
			$msg = $this->lang(\'' . strtoupper($this->objectName) . '_HAS_NOT_BEEN_CREATED\') . \':\';
			foreach ($' . lcfirst($this->objectName) . '->getErrors() as $error) {
				$msg .= " \n" . $error;
			}
			$this->enqueueError($msg);
			$this->view = \'default\';
		}					

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

		$' . lcfirst($this->objectName) . ' = new ' . $this->objectName . '(Input::get(' . $this->getFormattedTableKey() . '));
		$' . lcfirst($this->objectName) . '->populateByRequest();

		// apply the update
		$result = $' . lcfirst($this->objectName) . '->store();

		if ($result) {

			// notify the change and redirect
			$this->enqueueMessage($this->lang(\'' . strtoupper($this->objectName) . '_HAS_BEEN_CHANGED_SUCCESFULLY\'));
			$this->redirect(\'' . $this->moduleName . '\');

		} else {

			// get error list from object
			$errors = $' . lcfirst($this->objectName) . '->getErrors();

			if (count($errors)) { 
				$message = $this->lang(\'ERROR_ON_LAST_REQUEST\') . ": \n" . implode(" \n", $errors);
				$this->enqueueError($message);
				$this->view = \'default\';
			} else {
				$this->redirect(\'' . $this->moduleName . '\');
			}

		}

	}

	/**
	 * Delete a ' . $this->objectName . ' object.
	 */
	public function deleteAction() {

	 	$' . lcfirst($this->objectName) . ' = $this->getObjectRequestedById(\'' . $this->objectName . '\');

		// execute deletion
		$result = $' . lcfirst($this->objectName) . '->delete();

		if ($result) {

			$this->enqueueMessage($this->lang(\'' . strtoupper($this->objectName) . '_HAS_BEEN_DELETED_SUCCESFULLY\'));
			$this->redirect(\'' . $this->moduleName . '\');

		} else {

			// get error list from object
			$errors = $' . lcfirst($this->objectName) . '->getErrors();

			if (count($errors)) { 
				$message = $this->lang(\'ERROR_DELETING_' . strtoupper($this->objectName) . '\') . ": \n" . implode(" \n", $errors);
				$this->enqueueError($message);
				$this->view = \'default\';
			} else {
				$this->enqueueError($this->lang(\'ERROR_ON_LAST_REQUEST\'));
				$this->redirect(\'' . $this->moduleName . '\');
			}

		}

	}

}';

		// writes the code into the file
		$this->writeFile($file, $content);

	}

	/**
	 * Create and save a translation file.
	 * 
	 * @param	string	Full path to translation INI file.
	 * @param	string	Language-tag (eg. “en-BR”).
	 */
	public function saveTranslation($file, $representation) {
		
		// get the Translator singleton
		$tran = Translator::getInstance();
		
		// store the current Locale
		$currentLocale = $tran->getCurrentLocale();

		// get a new Locale object
		$newLocale = Locale::getByRepresentation($representation);
		
		// if Locale doesn’t exist in application, return
		if (!$newLocale) {
			return;
		}
		
		// set the new Locale
		$tran->setLocale($newLocale);
		
		$ucObject = strtoupper($this->objectName);
		
		$fields = array();

		// list of table fields
		foreach ($this->binds as $field) {
		
			$fields[] = strtoupper($field) . ' = "' . str_replace('_', ' ', ucfirst($field)) . '"';

		}
		
		$content = '';
		
		// here starts code collect
		$content .= strtoupper($this->tableName) . ' = "' . str_replace('_', ' ', ucfirst($this->tableName)) . "\"\n";
		$content .= $ucObject . '_HAS_BEEN_CREATED = "' . $tran->get('OBJECT_HAS_BEEN_CREATED', $this->objectName) . "\"\n";
		$content .= $ucObject . '_HAS_NOT_BEEN_CREATED = "' . $tran->get('OBJECT_HAS_NOT_BEEN_CREATED', $this->objectName) . "\"\n";
		$content .= $ucObject . '_HAS_BEEN_CHANGED_SUCCESFULLY = "' . $tran->get('OBJECT_HAS_BEEN_CHANGED_SUCCESFULLY', $this->objectName) . "\"\n";
		$content .= $ucObject . '_HAS_BEEN_DELETED_SUCCESFULLY = "' . $tran->get('OBJECT_HAS_BEEN_DELETED_SUCCESFULLY', $this->objectName) . "\"\n";
		$content .= 'ERROR_DELETING_' . $ucObject . ' = "' . $tran->get('ERROR_DELETING_OBJECT', $this->objectName) . "\"\n";
		$content .= 'NEW_' . strtoupper($this->objectName) . ' = "' . $tran->get('NEW_OBJECT', $this->objectName) . "\"\n";
		$content .= 'EDIT_' . strtoupper($this->objectName) . ' = "' . $tran->get('EDIT_OBJECT', $this->objectName) . "\"\n";
		$content .= implode("\n", $fields);
		
		// write the code into the file
		$this->writeFile($file, $content);
		
		// sets back the kept Locale
		$tran->setLocale($currentLocale);
		
	}
	
	public function saveViewDefault($file) {
		
		// here starts code collect
		$content = 
'<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class ' . ucfirst($this->moduleName) . 'ViewDefault extends View {

	/**
	 * Render HTML of this view.
	 * {@inheritDoc}
	 * @see \Pair\View::render()
	 */
	public function render() {

		$this->app->pageTitle		= $this->lang(\'' . strtoupper($this->tableName) . '\');
		$this->app->activeMenuItem	= \'' . $this->moduleName . '\';

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render(\'breadcrumb\');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render(\'sideMenu\');
		
		$' . Utilities::getCamelCase($this->tableName) . ' = $this->model->get' .  ucfirst($this->moduleName) . '();

		$this->pagination->count = $this->model->countListItems();

		$this->assign(\'' . Utilities::getCamelCase($this->tableName) . '\', $' . $this->getCamelCase($this->tableName) . ');

	}

}';

		// writes the code into the file
		$this->writeFile($file, $content);

	}
	
	/**
	 * Based on custom layouts, create the PHP layout file for default action.
	 * 
	 * @param	string	Final file to write.
	 */
	public function saveLayoutDefault($file) {

		// trigger for link on first item
		$fieldWithLink = TRUE;
		
		// get table’s foreign keys
		$foreignKeys = $this->db->getForeignKeys ($this->tableName);
		
		// collects table headers and cells
		foreach ($this->binds as $property=>$field) {
			
			// jump the table keys
			if (!$this->isTableKey($field)) {
				
				// replace the table-header placeholder
				$headers[] = str_replace('{tableHeader}', "<?php \$this->_('" . strtoupper($field) . "') ?>", $this->layouts['default-table-header']);
				
				// get the fk-column for the current field
				$col = $this->getForeignKey($field, $foreignKeys);
				
				// set the proper value for this property visualization
				if (is_null($col)) {
				
					// add the date/datetime format method
					switch ($this->propType[$property]) {
						case 'date':	 $object = '$o->formatDate(\'' . $property . '\')'; break;
						case 'datetime': $object = '$o->formatDateTime(\'' . $property . '\')'; break;
						case 'set':		 $object = 'htmlspecialchars(implode(\', \', $o->' . $property . '))'; break;
						case 'bool':	 $object = '($o->' . $property . ' ? \'<i class="fa fa-check text-success"></i>\' : \'\')'; break;
						default:		 $object = 'htmlspecialchars($o->' . $property . ')'; break;
					}
					
				// or get it by the related Pair object
				} else {
					
					// get the class that’s mapping a table
					$fkClass = $this->getClassByTable($col->REFERENCED_TABLE_NAME);
					
					// class found, get a related object
					if ($fkClass) {
					
						// search the first string-type field in the class, to use as label
						$firstTextProp = $this->getFirstTextProperty($fkClass);
						if (!$firstTextProp) {
							$firstTextProp = Utilities::getCamelCase($col->REFERENCED_COLUMN_NAME);
						}
						
						$object = 'htmlspecialchars($o->getRelatedProperty(\'' . $property . '\', \'' . $firstTextProp . '\'))';
					
					// class not found, show a simple field value
					} else {
						
						$object = 'htmlspecialchars($o->' . $property . ')';
						
					}
					
				}
				
				// complete the property value
				$replace = '<?php print ' . $object . ' ?>';
				
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
		$ph['newElement']	= '<?php $this->_(\'NEW_' . strtoupper($this->objectName) . '\') ?>';
		$ph['itemsArray']	= '$this->' . Utilities::getCamelCase($this->tableName);
		$ph['tableHeaders']	= implode("\n", $headers);
		$ph['tableRows']	= $tableRows;

		// replace value on placeholders
		$content = $this->replaceHolders($this->layouts['default-page'], $ph);

		// writes the code into the file
		$this->writeFile($file, $content);

	}
	
	public function saveViewNew($file) {

		// here starts code collect
		$content = 
'<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class ' . ucfirst($this->moduleName) . 'ViewNew extends View {

	/**
	 * Render HTML of this view.
	 * {@inheritDoc}
	 * @see \Pair\View::render()
	 */
	public function render() {

		$this->app->pageTitle = $this->lang(\'NEW_' . strtoupper($this->objectName) . '\');
		$this->app->activeMenuItem = \'' . $this->moduleName . '\';

		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang(\'NEW_' . strtoupper($this->objectName) . '\'), \'new\');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render(\'breadcrumb\');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render(\'sideMenu\');
		
		$form = $this->model->get' . $this->objectName . 'Form();
		
		$this->assign(\'form\', $form);
		
	}

}
';

		// writes the code into the file
		$this->writeFile($file, $content);

	}
	
	public function saveLayoutNew($file) {
	
		$fields = array();
		
		// collects fields
		foreach ($this->binds as $property=>$field) {
			
			if (!$this->isTableKey($field)) {
				
				$search = ['{fieldLabel}', '{fieldControl}'];
				$replace = ['<?php $this->form->printLabel(\'' . $property . '\') ?>',
							'<?php $this->form->printControl(\'' . $property . '\') ?>'];
				$fields[] = str_replace($search, $replace, $this->layouts['new-field']);
				
			}
			
		}
		
		$ph['pageTitle']	= '<?php $this->_(\'NEW_' . strtoupper($this->objectName) . '\') ?>';
		$ph['formAction']	= $this->moduleName . '/add';
		$ph['fields']		= implode('', $fields);
		$ph['cancelUrl']	= $this->moduleName;

		// replace value on placeholders
		$content = $this->replaceHolders($this->layouts['new-page'], $ph);
		
		// writes the code into the file
		$this->writeFile($file, $content);

	}
	
	public function saveViewEdit($file) {
		
		// need loop route-params for each table key
		if (is_array($this->tableKey)) {

			$params	= '';
			$vars	= array();
			
			foreach ($this->tableKey as $index => $k) {
				$var	= '$' . Utilities::getCamelCase($k);
				$vars[]	= $var;
				$params.= '		' . $var . ' = Router::get(' . $index . ");\n";
			}
			
			$key = 'array(' . implode(', ', $vars) . ')';
			$editId = '$' . lcfirst($this->objectName) . '->' . implode('/', $vars);
				
		} else {
			$key	= '$' . Utilities::getCamelCase($this->tableKey);
			$params	= '		' . $key . ' = Router::get(0);';
			$editId = '$' . lcfirst($this->objectName) . '->' . Utilities::getCamelCase($this->tableKey);
		}		

		// here starts code collect
		$content = 
'<?php

use Pair\Breadcrumb;
use Pair\Router;
use Pair\View;
use Pair\Widget;

class ' . ucfirst($this->moduleName) . 'ViewEdit extends View {

	/**
	 * Render HTML of this view.
	 * {@inheritDoc}
	 * @see \Pair\View::render()
	 */
	public function render() {

		$this->app->pageTitle = $this->lang(\'EDIT_' . strtoupper($this->objectName) . '\');
		$this->app->activeMenuItem = \'' . $this->moduleName . '\';

' . $params . '
		$' . lcfirst($this->objectName) . ' = new ' . $this->objectName . '(' . $key . ');

		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang(\'EDIT_' . strtoupper($this->objectName) . '\'), \'edit/\' . ' . $editId . ');

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render(\'breadcrumb\');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render(\'sideMenu\');
		
		$form = $this->model->get' . ucfirst($this->objectName) . 'Form();
		$form->setValuesByObject($' . lcfirst($this->objectName) . ');

		$this->assign(\'form\', $form);
		$this->assign(\'' . lcfirst($this->objectName) . '\', $' . lcfirst($this->objectName) . ');
		
	}
	
}
';

		// writes the code into the file
		$this->writeFile($file, $content);

	}
	
	public function saveLayoutEdit($file) {
		
		$fields = [];
		$hiddens = [];
		
		// collects fields
		foreach ($this->binds as $property=>$field) {
			
			if (!$this->isTableKey($field)) {
				
				$search = ['{fieldLabel}', '{fieldControl}'];
				$replace = ['<?php $this->form->printLabel(\'' . $property . '\') ?>',
						'<?php $this->form->printControl(\'' . $property . '\') ?>'];
				$fields[] = str_replace($search, $replace, $this->layouts['edit-field']);
				
			} else {
				
				$hiddens[] = '<?php $this->form->printControl(\'' . $property . '\') ?>';
			
			}
			
		}
		
		$ph['pageTitle']	= '<?php $this->_(\'EDIT_' . strtoupper($this->objectName) . '\') ?>';
		$ph['formAction']	= $this->moduleName . '/change';
		$ph['fields']		= implode('', $fields);
		$ph['hiddenFields']	= implode('', $hiddens);
		$ph['object']		= lcfirst($this->objectName);
		$ph['cancelUrl']	= $this->moduleName;
		$ph['deleteUrl']	=  $this->moduleName . '/delete/<?php print ' . $this->getTableKeyAsCgiParams() . ' ?>';
		
		// replace value on placeholders
		$content = $this->replaceHolders($this->layouts['edit-page'], $ph);
		
		// writes the code into the file
		$this->writeFile($file, $content);
		
	}
	
	private function getSingularObjectName($tableName) {
		
		$tmp = explode('_', $tableName);
		$lastWord = strtolower(end($tmp));
		
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
	 * Replace all layout’s placeholders in one shot.
	 *
	 * @param	string	The HTML template with placeholders.
	 * @param	array	List with placeholders name as key and value.
	 *
	 * @return	string
	 */
	private function replaceHolders(string $layout, array $placeholders) {
		
		$search = [];
		$replace = [];
		
		foreach ($placeholders as $placeholder => $value) {
			$search[] = '{' . $placeholder . '}';
			$replace[] = $value;
		}
		
		return str_replace($search, $replace, $layout);
		
	}

	/**
	 * Write content into file with 0777 permissions.
	 * 
	 * @param	string	File name and full path.
	 * @param	string	File content.
	 */
	private function writeFile($file, $content) {
		
		$old = umask(0);
		file_put_contents($file, $content);
		umask($old);
		chmod($file, 0777);
		
	}

	/**
	 * Return the table key formatted for use in class text as string or array.
	 * 
	 * @return string
	 */
	private function getFormattedTableKey() {
		
		if (is_array($this->tableKey)) {
			return "array('" . implode("', '", $this->tableKey) . "')";
		} else {
			return "'" . $this->tableKey . "'";
		}
		
	}
	
	/**
	 * Assert that passed field is in table key.
	 * 
	 * @param	string	Field name.
	 * 
	 * @return	boolean
	 */
	private function isTableKey($field) {
		
		if (is_array($this->tableKey)) {
			return (in_array($field, $this->tableKey));
		} else {
			return ($field == $this->tableKey);
		}
		
	}
	
	/**
	 * Format table key variables to be append on CGI URLs.
	 * 
	 * @param	string	Optional variable name with $ prefix to put before each key var.
	 * 
	 * @return	string
	 */
	private function getTableKeyAsCgiParams($object=NULL) {
		
		if (is_null($object)) {
			$object = '$this->' . lcfirst($this->objectName);
		}
		
		if (is_array($this->tableKey)) {
			
			$vars = array();
			
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
	 * 
	 * @param	string	Field name.
	 * @param	array	Foreign Key list.
	 * 
	 * @return	stdClass|NULL
	 */
	private function getForeignKey(string $field, array $foreignKeys) {
		
		foreach ($foreignKeys as $col) {
			if ($field == $col->COLUMN_NAME) return $col;
		}
		
		return NULL;
	
	}
	
	/**
	 * Return TRUE if the passed field is nullable.
	 * 
	 * @param	string	Table name.
	 * @param	string	Field.
	 * 
	 * @return	bool
	 */
	private function isFieldNullable(string $tableName, string $field) {
		
		$column = $this->db->describeColumn($tableName, $field);
		return (isset($column->Null) and 'YES' == $column->Null);
		
	}
	
	/**
	 * If field has length attribute, return it, otherwise return NULL.
	 * 
	 * @param	string	Table name.
	 * @param	string	Field.
	 * 
	 * @return	int|NULL
	 */
	private function getFieldMaxLength(string $tableName, string $field) {
		
		$column = $this->db->describeColumn($tableName, $field);
		
		// split the column Type to recognize field type and length
		preg_match('#^([\w]+)(\([^\)]+\))?#i', $column->Type, $matches);
		return isset($matches[2]) ? (int)substr($matches[2], 1, -1) : NULL;
		
	}
	
	/**
	 * Return the first text property of a class by its name.
	 */
	private function getFirstTextProperty($class) {
		
		if (!$class) return NULL;
		
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
	
}