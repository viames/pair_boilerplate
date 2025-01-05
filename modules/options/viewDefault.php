<?php

use Pair\Core\View;
use Pair\Html\Breadcrumb;
use Pair\Html\Form;
use Pair\Support\Options;

class OptionsViewDefault extends View {

	public function render(): void {

		$this->app->loadScript('js/options.js', TRUE);

		$options = Options::getInstance();

		$this->app->pageTitle = $this->lang('OPTIONS');

		Breadcrumb::path($this->lang('OPTIONS'));

		$form = new Form();
		$form->classForControls('form-control');

		$groupedOptions = [];

		foreach ($options->getAll() as $o) {

			$groupedOptions[$o->group][] = $o;

			// if uppercase, label is a language key
			if (preg_match('#^[A-Z\_]+$#', $o->label)) {
				$o->label = $this->lang($o->label);
			}

			switch ($o->type) {

				default:
				case 'text':
					$form->text($o->name)->value($o->value);
					break;

				case 'textarea':
					$form->textarea($o->name)->cols(40)->rows(5)->value($o->value);
					break;

				case 'int':
					$form->number($o->name)->value($o->value);
					break;

				case 'bool':
					$form->checkbox($o->name, ['role'=>'switch'])->value($o->value)->class('form-check-input');
					break;

				case 'list':
					$form->select($o->name)->options($o->listItems,'value','text')->value($o->value)->class('default-select2');
					break;

				case 'password':
					$form->password($o->name, ['autocomplete'=>'off'])->value($o->value);
					break;
			}

		}

		$this->assign('form',	$form);
		$this->assign('groupedOptions',$groupedOptions);

	}

}