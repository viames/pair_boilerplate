<?php

use Pair\Application;
use Pair\Locale;
use Pair\Model;
use Pair\Module;
use Pair\Plugin;
use Pair\Utilities;

class ToolsModel extends Model {
	
	/**
	 * Reads all existent translations files and then put quotes, remove duplicates and compact them.
	 * 
	 * @return	int		Count of rebuilt files.
	 */
	public function rebuildTranslationFiles() {
		
		$counter = 0;
		
		// all registered Locales
		$locales = Locale::getAllObjects();

		// all available modules
		$modules = Module::getAllObjects(NULL, 'name');
		
		// the fake "common" module
		$common = new stdClass();
		$common->id = 0;
		$common->name = 'common';
		
		// patch for common translation file
		array_unshift($modules, $common);

		foreach ($locales as $locale) {

			foreach ($modules as $module) {
			
				$file = $locale->getFilePath($module->name);
			
				// gets and sets translation strings again 
				if (file_exists($file) and is_writable($file)) {
					$strings = $locale->readTranslation($module);
					$locale->writeTranslation($strings, $module);
					$counter++;
				}
			
			}
			
		}
		
		return $counter;
		
	}
	
	/**
	 * Creates both manifest.xml files or db records of existent plugins.
	 * 
	 * @return	int
	 */
	public function fixPlugins() {

		$app	= Application::getInstance();
		$fixes	= 0;
		$names	= array();

		// all plugin types
		$pluginTypes = array(
				'module'	=> TRUE,
				'template'	=> TRUE);
				
		foreach ($pluginTypes as $type => $pair) {
			
			// compute names
			$class	= ($pair ? 'Pair\\' : '') . ucfirst($type);
			$folder	= $dbtable = strtolower($type . 's');
			$names	= array();
			
			$pluginsFolder = APPLICATION_PATH . '/' . $folder;
			
			// main plugins folder scanning
			$dirs = array_diff(scandir($folder), array('..', '.', '.DS_Store'));
			
			// gets db records and makes objects
			$pObjects = $class::getAllObjects();
			
			// collects name only
			foreach ($pObjects as $pObj) {
				$names[] = strtolower($pObj->name);
			}
			
			// fix db records
			foreach ($dirs as $dir) {

				// db record is missing
				if (!in_array(strtolower($dir), $names)) {
					
					// manifest file path
					$manifestFile = $pluginsFolder . '/' . $dir . '/manifest.xml';
					
					// manifest file is missing
					if (!file_exists($manifestFile)) {

						$app->logWarning('File manifest.xml is missing for ' . ucfirst($type) . ' plugin at path /' . $folder . '/' . $dir);
					
					} else {

						// get XML content of manifest by reading a file
						$manifest = Plugin::getManifestByFile($manifestFile);
						
						// creates db record by its manifest file
						Plugin::createPluginByManifest($manifest);
						
						// logging
						$app->logEvent('Inserted a new plugin record for ' . $type . ' ' . $dir);
						$fixes++;

					}

				}

			}

			// fix manifest files
			foreach ($pObjects as $pObj) {

				// paths
				$baseFolder	= $pluginsFolder . '/' . strtolower($pObj->name);
				$manifest	= $baseFolder . '/manifest.xml';

				// manifest file is missing, create it now
				if (!file_exists($manifest)) {

					$plugin = $pObj->getPlugin();
					$plugin->createManifestFile();
					
					// logging
					$app->logEvent('Created manifest file for ' . $type . ' ' . $pObj->name);
					$fixes++;
					
				}
				
			}
			
		}
		
		return $fixes;
		
	}
	
	/**
	 * Prepare this project to update to Pair v1.4.
	 * 
	 * @return boolean
	 */
	public function updatePair14() {

		// check and installation of new modules
		$packages = ['countries', 'locales', 'translator'];
		foreach ($packages as $package) {
			if (!Module::pluginExists($package)) {
				$plugin = new Plugin();
				$res = $plugin->installPackage(APPLICATION_PATH . '/modules/tools/assets/' . $package . 'Module.zip');
				if ($res) {
					$this->logEvent('Table `locales` already exists');
				} else {
					$this->addError('Plugin ' . $package . ' installation failed');
				}
			} else {
				$this->logEvent('Plugin module “' . $package . '” already exists, skip installation');
			}
		}
		
		// update of changed modules: developer, languages, selftest, users
		// TODO
		
		// check and create countries table
		$this->db->setQuery('SELECT COUNT(table_name) FROM information_schema.`tables` WHERE table_schema = "' . DB_NAME . '" AND table_name = "countries"');
		$res = $this->db->loadCount();
		if (!$res) {
			$this->runQueryByFile('countries.sql');
			$this->logEvent('Table `countries` has been created');
		} else {
			$this->logEvent('Table `countries` already exists, skip creation');
		}
		
		// check and remove language fk with user table
		$this->db->setQuery(
			'SELECT * FROM information_schema.`REFERENTIAL_CONSTRAINTS` WHERE CONSTRAINT_SCHEMA = "' . DB_NAME . '"' .
			' AND TABLE_NAME = "users" AND CONSTRAINT_NAME = "fk_users_languages"');
		
		if ($this->db->loadCount()) {
			$res = $this->db->exec('ALTER TABLE users DROP FOREIGN KEY fk_users_languages');
		}
		
		// check if languages table is updated
		$this->db->setQuery('SHOW COLUMNS FROM `languages` LIKE "representation"');
		if ($this->db->loadCount()) {
			$this->db->exec('DROP TABLE IF EXISTS `languages_old`');
			$this->db->exec('RENAME TABLE `languages` TO `languages_old`');
			$this->runQueryByFile('languages.sql');
		}
		
		// check existence of locales table
		$this->db->setQuery('SELECT COUNT(table_name) FROM information_schema.`tables` WHERE table_schema = "' . DB_NAME . '" AND table_name = "locales"');
		if (!$this->db->loadCount()) {
			$this->runQueryByFile('locales.sql');
			$this->logEvent('Table `locales` has been created and populated');
		} else {
			$this->logEvent('Table `locales` already exists, skip installation');
		}
		
		// update the users table
		$this->db->setQuery('SHOW COLUMNS FROM `users` LIKE "language_id"');
		if ($this->db->loadCount()) {
			$res = $this->db->exec('ALTER TABLE `users` CHANGE COLUMN `language_id` `locale_id` SMALLINT(3) NOT NULL');
			// set the app_default locale for all users
			//$defaultLocale = Locale::getDefault();
			$this->db->exec('UPDATE `users` SET locale_id = ?', 82); //[$defaultLocale->id]);
			// change users table struct and create a new fk
			$res = $this->db->exec('ALTER TABLE users ADD CONSTRAINT fk_users_locales FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`) ON UPDATE CASCADE');
			$this->logEvent('Users table has been updated');
		} else {
			$this->logEvent('Table `users` is already updated');
		}
		
		// set a new Pair version in composer.json
		$composerFile = APPLICATION_PATH . '/composer.json';
		$content = file_get_contents($composerFile);
		$pattern = '#([\t\s]*"viames/pair"[\t\s]*:[\t\s]*")[^"]+("[\t\s]*[,]?[\t\s]*)#i';
		file_put_contents($composerFile, preg_replace($pattern, '\1^1.4\2', $content));
		$this->logEvent('Pair version is updated to ^1.4 in composer.json');
		
		// rename translation folders
		$counter = 0;
		$modules = Module::getAllObjects();
		foreach ($modules as $module) {
			$mFolder = APPLICATION_PATH . '/modules/' . $module->name;
			if (is_dir($mFolder . '/languages')) {
				rename($mFolder . '/languages', $mFolder . '/translations');
				$counter++;
			}
			$this->renameTranslations($mFolder . '/translations');
		}
		
		if (is_dir(APPLICATION_PATH . '/languages')) {
			rename(APPLICATION_PATH . '/languages', APPLICATION_PATH . '/translations');
			$counter++;
			$this->renameTranslations(APPLICATION_PATH . '/translations');
		}
		
		if ($counter) {
			$this->logEvent('Renamed ' . $counter . ' folders from languages to translations');
		}
		
		return TRUE;
		
	}
	
	private function renameTranslations($folder) {
		
		$files = Utilities::getDirectoryFilenames($folder);

		foreach ($files as $file) {
			
			$basename = pathinfo($file, PATHINFO_FILENAME);

			if (strlen($basename) > 2) continue;
			
			$query =
				'SELECT CONCAT(la.code, "-", co.code) AS representation' .
				' FROM `locales` AS lo' .
				' INNER JOIN `languages` AS la ON lo.language_id = la.id' .
				' INNER JOIN `countries` AS co ON lo.country_id = co.id' .
				' WHERE la.code = ?' .
				' AND lo.default_country = 1';
			
			$this->db->setQuery($query);
			$res = $this->db->loadResult($basename);

			if ($res) {
				$newFile = $res . '.ini';
				rename($folder . '/' . $file, $folder . '/' . $newFile);
			}
		
		}
		
	}
	
	private function runQueryByFile($file) {
		
		// load Pair’s DB dump by SQL file
		$queries = file_get_contents(APPLICATION_PATH . '/modules/tools/assets/' . $file);
		
		// create all tables, if not exist
		try {
			$this->db->exec('USE `' . DB_NAME . '`');
			//$this->db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, 1);
			$this->db->exec($queries);
		} catch (Exception $e) {
			$this->addError('Table creation failed: ' . $e->getMessage());
			return FALSE;
		}
		
	}
	
}
