<?php

use Pair\Html\Breadcrumb;
use Pair\Html\Form;
use Pair\Models\Locale;
use Pair\Models\Module;
use Pair\Core\Router;
use Pair\Core\View;
use Pair\Html\Widget;

class TranslatorViewEdit extends View {

	/**
	 * {@inheritdoc}
	 *
	 * @see View::Render()
	 */
	public function render() {

		$this->app->pageTitle = $this->lang('TRANSLATOR');
		
		// build objects
		$locale	= new Locale(Router::get(0));
		
		// patch for "common" fake module
		if (Router::get(1) > 0) {
			$module	= new Module(Router::get(1));
		} else {
			$module = new stdClass();
			$module->id = 0;
			$module->name = 'common';
		}
		
		// add breadcrumb path
		Breadcrumb::path($this->lang('TRANSLATION_X', $locale->getEnglishNames()), 'translator/details/' . $locale->id);
		Breadcrumb::path($this->lang('MODULE_X', ucfirst($module->name)));
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$defaultLocale = Locale::getDefault();
		
		$isDefault = ($defaultLocale->id == $locale->id);
		
		// get strings
		$strings	= $locale->readTranslation($module);
		$defaultStrings	= $defaultLocale->readTranslation($module);

		$form = new Form();
		$form->classForControls('form-control');
		
		$form->hidden('locale')->value($locale->id);
		$form->hidden('module')->value($module->id);
		
		foreach ($defaultStrings as $key=>$value) {
		
			$control = $form->text($key);
			
			if (isset($strings[$key])) {
				$control->value($strings[$key]);
			} else {
				$control->class('text-danger');
			}
			
		}
		
		$parent = 'translator/details/' . $locale->id . '/' . $module->id;
		
		$this->assign('form',		$form);
		$this->assign('parent',		$parent);
		$this->assign('defStrings', $defaultStrings);
		$this->assign('module',		$module);
		$this->assign('locale',		$locale);
		$this->assign('isDefault',	$isDefault);

	}

}