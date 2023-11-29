<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class ToolsViewDefault extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('TOOLS');

		Breadcrumb::path($this->lang('TOOLS'));

		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');

		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');

		// rebuild existent language files
		$tools[] = [
			'title'		=> $this->lang('REBUILD_TRANSLATION_FILES'),
			'url'		=> 'tools/rebuildLanguageFiles',
			'confirm'	=> FALSE
		];

		// restore db row or manifest file if missing
		$tools[] = [
			'title'		=> $this->lang('FIX_PLUGINS'),
			'url'		=> 'tools/fixPlugins',
			'confirm'	=> FALSE
		];

		$this->assign('tools', $tools);

	}

}
