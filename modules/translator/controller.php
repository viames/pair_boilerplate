<?php

use Pair\Breadcrumb;
use Pair\Controller;
use Pair\Input;
use Pair\Locale;
use Pair\Module;
use Pair\Router;

class TranslatorController extends Controller {

	protected function init() {
		
		Breadcrumb::getInstance()->addPath($this->lang('TRANSLATOR'), 'translator/default');
		
	}
	
	/**
	 * Check if is requested to apply an alpha filter.
	 */
	public function defaultAction() {
		
		if (Router::get(0)) {
			
			$this->app->setPersistentState('translatorAlphaFilter', Router::get(0));
			
		} else {
			
			$this->app->unsetPersistentState('translatorAlphaFilter');
			
		}
		
	}
	
	/**
	 * Do the translation strings change.
	 */
	public function changeAction() {
	
		$locale = new Locale(Input::get('locale', 'int'));
		
		if (Input::get('module', 'int')) {
			$module = new Module(Input::get('module', 'int'));
		} else {
			// the fake "common" module
			$module = new stdClass();
			$module->id = 0;
			$module->name = 'common';
		}

		$strings = Input::getInputsByRegex('#[A-Z][A-Z_]+#');

		$res = $locale->writeTranslation($strings, $module);

		// user messages
		if ($res) {
			$this->enqueueMessage($this->lang('TRANSLATION_STRINGS_UPDATED', array($locale->getEnglishNames(), ucfirst($module->name))));
		} else {
			$this->enqueueError($this->lang('TRANSLATION_STRINGS_NOT_UPDATED', array($locale->getEnglishNames(), ucfirst($module->name))));
		}

		$this->app->redirect('translator/details/' . $locale->id);
	
	}
	
}