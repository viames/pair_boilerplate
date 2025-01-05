<?php

use Pair\Html\Breadcrumb;
use Pair\Core\Controller;
use Pair\Support\Post;
use Pair\Models\Locale;
use Pair\Models\Module;
use Pair\Core\Router;

class TranslatorController extends Controller {

	protected function init(): void {
		
		Breadcrumb::path($this->lang('TRANSLATOR'), 'translator/default');
		
	}
	
	/**
	 * Check if is requested to apply an alpha filter.
	 */
	public function defaultAction(): void {
		
		if (Router::get(0)) {
			$this->app->setPersistentState('translatorAlphaFilter', Router::get(0));
		} else {
			$this->app->unsetPersistentState('translatorAlphaFilter');
		}
		
	}
	
	/**
	 * Do the translation strings change.
	 */
	public function changeAction(): void {
	
		$locale = new Locale(Post::get('locale', 'int'));
		
		if (Post::get('module', 'int')) {
			$module = new Module(Post::get('module', 'int'));
		} else {
			// the fake "common" module
			$module = new stdClass();
			$module->id = 0;
			$module->name = 'common';
		}

		$strings = Post::byRegex('#[A-Z][A-Z_]+#');

		$res = $locale->writeTranslation($strings, $module);

		// user messages
		if ($res) {
			$this->toast($this->lang('TRANSLATION_STRINGS_UPDATED', array($locale->getEnglishNames(), ucfirst($module->name))));
		} else {
			$this->toastError($this->lang('TRANSLATION_STRINGS_NOT_UPDATED', array($locale->getEnglishNames(), ucfirst($module->name))));
		}

		$this->app->redirect('translator/details/' . $locale->id);
	
	}
	
}