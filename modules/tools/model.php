<?php

use Pair\Core\Application;
use Pair\Core\Logger;
use Pair\Core\Model;
use Pair\Models\Locale;
use Pair\Models\Module;
use Pair\Packages\InstallablePackage;

class ToolsModel extends Model {

	/**
	 * Reads all existent translations files and then put quotes, remove duplicates and compact them.
	 * @return int Count of rebuilt files.
	 */
	public function rebuildTranslationFiles(): int {

		$counter = 0;

		// all registered Locales
		$locales = Locale::all();

		// all available modules
		$modules = Module::getAllObjects([], 'name');

		// the fake "common" module
		$common = new stdClass();
		$common->id = 0;
		$common->name = 'common';

		// patch for common translation file
		$modules->unshift($common);

		foreach ($locales as $locale) {

			foreach ($modules as $module) {

				$file = $locale->getFilePath($module->name);

				// gets and sets translation strings again
				if (file_exists($file) and is_writable($file)) {
					$mod = is_a($module, 'stdClass') ? NULL : $module;
					$strings = $locale->readTranslation($mod);
					$locale->writeTranslation($strings, $mod);
					$counter++;
				}

			}

		}

		return $counter;

	}

	/**
	 * Creates both manifest.xml files or db records of existing packages.
	 */
	public function fixPackages(): int {

		$fixes	= 0;
		$names	= [];

		// Package-backed record types handled by this maintenance task.
		$packageTypes = [
			'module'	=> TRUE,
			'template'	=> TRUE
		];

		foreach ($packageTypes as $type => $pair) {

			// compute names
			$class	= ($pair ? 'Pair\\Models\\' : '') . ucfirst($type);
			$folder	= strtolower($type . 's');
			$names	= [];

			$packagesFolder = APPLICATION_PATH . '/' . $folder;

			// Scan the main package folder for records missing from the database.
			$dirs = array_diff(scandir($packagesFolder), ['..', '.', '.DS_Store']);

			// gets db records and makes objects
			$pObjects = $class::all();

			// collects name only
			foreach ($pObjects as $pObj) {
				$names[] = strtolower($pObj->name);
			}

			// fix db records
			foreach ($dirs as $dir) {

				// db record is missing
				if (!in_array(strtolower($dir), $names)) {

					// manifest file path
					$manifestFile = $packagesFolder . '/' . $dir . '/manifest.xml';

					// manifest file is missing
					if (!file_exists($manifestFile)) {

						Logger::warning('File manifest.xml is missing for ' . ucfirst($type) . ' package at path /' . $folder . '/' . $dir);

					} else {

						// get XML content of manifest by reading a file
						$manifest = InstallablePackage::readManifestFile($manifestFile);

						// creates db record by its manifest file
						InstallablePackage::createRecordFromManifest($manifest);

						// logging
						Logger::notice('Inserted a new package record for ' . $type . ' ' . $dir);
						$fixes++;

					}

				}

			}

			// fix manifest files
			foreach ($pObjects as $pObj) {

				// paths
				$baseFolder	= $packagesFolder . '/' . strtolower($pObj->name);
				$manifest	= $baseFolder . '/manifest.xml';

				// manifest file is missing, create it now
				if (!file_exists($manifest)) {

					$package = $pObj->getInstallablePackage();
					$package->writeManifestFile();

					// logging
					Logger::notice('Created manifest file for ' . $type . ' ' . $pObj->name);
					$fixes++;

				}

			}

		}

		return $fixes;

	}

}
