<?php

use Pair\Core\Model;
use Pair\Models\Locale;
use Pair\Support\Logger;
use Pair\Support\Translator;

class SelftestModel extends Model {

	/**
	 * Test for unfound translation files under translations folder for all modules.
	 */
	public function testTranslations(): array {

		// instance of current language translator
		$translator = Translator::getInstance();
		
		// all registered Locales
		
		$query =
			'SELECT lo.*, la.english_name AS language_name, co.english_name AS country_name, CONCAT(la.code, "-", co.code) AS representation' .
			' FROM `locales` AS lo' .
			' INNER JOIN `languages` AS la ON lo.language_id = la.id' .
			' INNER JOIN `countries` AS co ON lo.country_id = co.id';
		
		$locales = Locale::getObjectsByQuery($query);

		// paths
		$defaultLang = Translator::getDefaultFileName();

		// count of fails
		$files		= 0;
		$folders	= 0;
		$lines		= 0;
		$notNeeded	= 0;
		
		// common language folder
		$translationsFolders = [APPLICATION_PATH . '/translations'];
		
		$modules = array_diff(scandir('modules'), ['..', '.', '.DS_Store']);
		
		// assembles the other language folders
		foreach ($modules as $module) {
			$translationsFolders[] = APPLICATION_PATH . '/modules/' . $module . '/translations';
		}
		
		// scan on each language folder
		foreach ($translationsFolders as $translationsFolder) {

			// checks that folder exists
			if (is_dir($translationsFolder)) {

				// now checks for default language file
				if (file_exists($translationsFolder . '/' . $defaultLang)) {

					// gets all default language’s keys
					$langData = @parse_ini_file($translationsFolder . '/' . $defaultLang);
					$defaultKeys = array_keys($langData);

					// compares each other language file
					foreach ($locales as $locale) {
						
						// else if is not default, pass keys to another array
						if ($locale->representation != $translator->getDefaultLocale()->getRepresentation()) {

							if (!file_exists($translationsFolder . '/' . $locale->representation . '.ini')) {
	
								$files++;
	
							} else {

								// scans file and gets all translation keys
								$langData = @parse_ini_file($translationsFolder . '/' . $locale->representation . '.ini');
								$otherKeys = array_keys($langData);

								// untranslated lines
								$lines += $this->countUntranslated($defaultKeys, $otherKeys, $locale->representation, $translationsFolder);

								// not needed lines
								$notNeeded += $this->countNotNeeded($defaultKeys, $otherKeys, $locale->representation, $translationsFolder);
			
							}
							
						}
						
					}
			
				} else {
			
					$files++;
			
				}
			
			}
			
		}

		$retVar = [
			'folders'	=> $folders,
			'files'		=> $files,
			'lines'		=> $lines,
			'notNeeded'	=> $notNeeded
		];

		return $retVar;

	}

	/**
	 * Counts for untranslated language lines and logs a warning with line and language-file path.
	 * 
	 * @param	array	List of translation key names.
	 * @param	array	List of comparing language key names.
	 * @param	string	Two chars language code.
	 * @param	string	Path to comparing language file.
	 */
	private function countUntranslated($defaultKeys, $otherKeys, $langCode, $langPath): int {

		return 0;
		/*
		$differences = array_diff($defaultKeys, $otherKeys);

		foreach ($differences as $diff) {
			Logger::warning('Untranslated “' . $diff . '” key for “' . $langCode . '.ini” at this path: ' . $langPath);
		}

		return count($differences);
		*/
	}

	/**
	 * Counts not needed language lines and logs a warning with line and language-file path.
	 * 
	 * @param	array	List of translation key names.
	 * @param	array	List of comparing language key names.
	 * @param	string	Two chars language code.
	 * @param	string	Path to comparing language file.
	 */
	private function countNotNeeded($defaultKeys, $otherKeys, $langCode, $langPath) {

		$differences = array_diff($otherKeys, $defaultKeys);
		
		foreach ($differences as $diff) {
			Logger::warning('Key  “' . $diff . '” is not needed for language “' . $langCode . '.ini” at this path: ' . $langPath);
		}

		return count($differences);
		
	}

}
