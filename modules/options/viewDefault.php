<?php

use Pair\Breadcrumb;
use Pair\Form;
use Pair\Options;
use Pair\View;
use Pair\Widget;

class OptionsViewDefault extends View {

	public function render() {

		$options = Options::getInstance();

		$this->app->pageTitle = $this->lang('OPTIONS');

		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('OPTIONS'));

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		$form = new Form();
		$form->addControlClass('form-control');
		
		$groupedOptions = array();
		
		foreach ($options->getAll() as $o) {
			
			$groupedOptions[$o->group][] = $o;
			
			// if uppercase, label is a language key
			if (preg_match('#^[A-Z\_]+$#', $o->label)) {
				$o->label = $this->lang($o->label);
			}
		
			switch ($o->type) {
		
				default:
				case 'text':
					$form->addInput($o->name)->setType('text')->setValue($o->value);
					break;
		
				case 'textarea':
					$form->addTextarea($o->name)->setCols(40)->setRows(5)->setValue($o->value);
					break;
					
				case 'int':
					$form->addInput($o->name)->setType('number')->setValue($o->value);
					break;
		
				case 'bool':
					$form->addInput($o->name)->setType('bool')->setValue($o->value);
					break;

				case 'list':
					$form->addSelect($o->name)->setListByObjectArray($o->listItems,'value','text')->setValue($o->value);
					break;

				case 'password':
					$form->addInput($o->name, ['autocomplete'=>'off'])->setType('password')->setValue($o->value);
					break;
			}
		
		}
		
		$this->assign('form',	$form);
		$this->assign('groupedOptions',$groupedOptions);

	}
	
}
