<?php

use Pair\Breadcrumb;
use Pair\Form;
use Pair\Locale;
use Pair\Module;
use Pair\Router;
use Pair\View;
use Pair\Widget;

class TranslatorViewEdit extends View {

	/**
	 * {@inheritdoc}
	 *
	 * @see View::Render()
	 */
	public function render() {

		$this->app->pageTitle		= $this->lang('TRANSLATOR');
		$this->app->activeMenuItem	= 'translator/default';
		
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
		Breadcrumb::getInstance()
			->addPath($this->lang('TRANSLATION_X', $locale->getEnglishNames()), 'translator/details/' . $locale->id)
			->addPath($this->lang('MODULE_X', ucfirst($module->name)));
		
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
		$form->addControlClass('form-control');
		
		$form->addInput('locale')->setType('hidden')->setValue($locale->id);
		$form->addInput('module')->setType('hidden')->setValue($module->id);
		
		foreach ($defaultStrings as $key=>$value) {
		
			$control = $form->addInput($key);
			
			if (isset($strings[$key])) {
				$control->setValue($strings[$key]);
			} else {
				$control->addClass('text-danger');
			}
			
		}
		
		$referer = 'translator/details/' . $locale->id . '/' . $module->id;
		
		$this->assign('form',		$form);
		$this->assign('referer',	$referer);
		$this->assign('defStrings', $defaultStrings);
		$this->assign('module',		$module);
		$this->assign('locale',		$locale);
		$this->assign('isDefault',	$isDefault);

	}

}