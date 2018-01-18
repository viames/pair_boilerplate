<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Application;
use Pair\Language;
use Pair\Model;
use Pair\Plugin;

class ToolsModel extends Model {
	
	/**
	 * Reads all existent language files and then put quotes, remove duplicates, fix
	 * headers and compact them.
	 * 
	 * @return	int		Count of rebuilt files.
	 */
	public function rebuildLanguageFiles() {
		
		$counter = 0;
		
		// all registered languages
		$languages = Language::getAllObjects();

		// all available modules
		$this->db->setQuery('SELECT name FROM modules');
		$modules = $this->db->loadResultList();
		
		// common language
		$modules[] = 'common';

		foreach ($languages as $lang) {

			foreach ($modules as $module) {
			
				$file = $lang->getFilePath($module);
			
				// gets and sets translation strings again 
				if (file_exists($file) and is_writable($file)) {
					$strings = $lang->getStrings($module);
					$lang->setStrings($strings, $module);
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
					$manifest = $pluginsFolder . '/' . $dir . '/manifest.xml';
					
					// manifest file is missing
					if (!file_exists($manifest)) {

						$app->logWarning('File manifest.xml is missing for ' . ucfirst($type) . ' plugin at path /' . $folder . '/' . $dir);
					
					} else {

						// creates db record by its manifest file
						Plugin::createPluginByManifest(file_get_contents($manifest));
						
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
	
}
