<?php

use Pair\Core\Model;
use Pair\Models\Locale;
use Pair\Models\Module;
use Pair\Orm\Collection;
use Pair\Orm\Database;

class TranslatorModel extends Model {
	
	protected function getQuery(string $class): string {

		$alphaFilter = trim((string)$this->app->getPersistentState('translatorAlphaFilter'));
		
		// get a filtered list
		$where = $alphaFilter
			? ' WHERE la.english_name LIKE ' . Database::getInstance()->quote($alphaFilter . '%')
			: '';
		
		$query =
			'SELECT lo.*, la.`english_name` AS `language_name`, co.`english_name` AS `country_name`, CONCAT(la.`code`, "-", co.`code`) AS `representation`
			FROM `locales` AS lo
			INNER JOIN `languages` AS la ON lo.`language_id` = la.`id`
			INNER JOIN `countries` AS co ON lo.`country_id` = co.`id`' .
			$where;
			
		return $query;

	}

	protected function getOrderOptions(): array {

		return [
			1 => '`language_name`',
			2 => '`language_name` DESC',
			3 => '`country_name`',
			4 => '`country_name` DESC',
			5 => '`representation`',
			6 => '`representation` DESC'
		];

	}
	
	/**
	 * Compares all locales files with default locale files and returns percentage of completeness.
	 */
	public function setLocalePercentage(Collection $locales): void {

		// all installed modules
		$modules = Module::getAllObjects(NULL, 'name');

		// the fake "common" module
		$common = new Module();
		$common->id = 0;
		$common->name = 'common';
		
		// patch for common translation file
		$modules->unshift($common);
		
		// the application default locale
		$defaultLocale = Locale::getDefault();
		
		foreach ($modules as $module) {
			$defaultData[$module->id] = $defaultLocale->readTranslation($module);
		}
		
		// compares each other language file
		foreach ($locales as $locale) {
			
			// initialize counter of fails
			$translated[$locale->id] = 0;
		
			// set an empty value for missing translations
			$locale->details = [];
			
			// initialize default lines
			$defaultLines = 0;
			
			// scan on each language folder
			foreach ($modules as $module) {
				
				// details of comparing lines
				$details = new stdClass();
				$details->module  = $module;
				$details->default = count($defaultData[$module->id]);
				
				$representation = isset($locale->representation) ? $locale->representation : $locale->getRepresentation();
				
				// get the proper module name because common translation is a fake module
				if (is_a($module, 'Pair\Models\Module')) {
					$details->moduleName = $module->name;
					$translationFile = APPLICATION_PATH . '/modules/' . $module->name . '/translations/' . $representation . '.ini';
				} else {
					$details->moduleName = 'common';
					$translationFile = APPLICATION_PATH . '/translations/' . $representation . '.ini';
				}
				
				// translated lines for default
				$defaultLines += $details->default;
					
				// get the right translation folder
				
				// sets language details
				if (file_exists($translationFile)) {

					// scans file and gets all translation keys
					$localeData = $locale->readTranslation($module);
					
					// details of translated lines 
					$details->count	= $details->default - count(array_diff(array_keys($defaultData[$module->id]), array_keys($localeData)));
					$details->perc	= $details->default > 0 ? floor(($details->count / $details->default) * 100) : 0;
					$details->date	= filemtime($translationFile);

					// sum to other modules for the same language
					$translated[$locale->id] += $details->count;

				// sets empty detail properties
				} else {
					
					$details->count	= 0;
					$details->perc	= 0;
					$details->date	= NULL;
					
				}
				
				// assigns to a Language property
				$locale->details[] = $details;
							
			}
			
			// sets 100% to default locale and zero to the rest
			if ($defaultLocale->id == $locale->id) {
				$locale->perc		= 100.0;
				$locale->complete	= $defaultLines;
			} else {
				$locale->perc		= floor(($translated[$locale->id] / $defaultLines * 100));
				$locale->complete	= $translated[$locale->id];
			}

		}
		
	}

	/**
	 * Builds and sets a coloured progress bar as property Object->progressBar by reference.
	 *
	 * @param	Object	The target object with integer property “perc” (percentual).
	 */
	public static function setProgressBar(&$object): void {
	
		$object->progressBar = '<div class="progress progress-mini">';
		
		// perc is not set
		if (!property_exists($object, 'perc')) {
			$object->progressBar .= '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
				<span>0%</span></div></div>';
			
			return;
		}
	
		$green = $red = 215;
	
		if (100 == $object->perc) {
			$red = 0;
		} else if (50 <= $object->perc) {
			$red = 255;
			$green = round(($object->perc / 50) * 110);
		} else {
			$green = 0;
		}
	
		$bgColor = 'rgb(' . $red . ',' . $green . ',0)';
	
		$object->progressBar .=
			'<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' .
			$object->perc .'" aria-valuemin="0" aria-valuemax="100"' . ' style="background-color:' . 
			$bgColor . ';width:' . $object->perc . '%"><span>' . $object->perc .
			'%</span></div></div>';
		
	}
	
}
