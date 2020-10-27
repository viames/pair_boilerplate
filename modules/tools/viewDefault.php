<?php

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class ToolsViewDefault extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('TOOLS');
		$this->app->activeMenuItem	= 'tools';

		Breadcrumb::path($this->lang('TOOLS'));
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		// rebuild existent language files
		$tools[] = array(
			'title'		=> $this->lang('REBUILD_TRANSLATION_FILES'),
			'url'		=> 'tools/rebuildLanguageFiles',
			'confirm'	=> FALSE
		);
		
		// restore db row or manifest file if missing
		$tools[] = array(
			'title'		=> $this->lang('FIX_PLUGINS'),
			'url'		=> 'tools/fixPlugins',
			'confirm'	=> FALSE
		);

		// update files and db to latest Pair version
		$tools[] = array(
				'title'	=> $this->lang('UPDATE_PAIR_14'),
				'url'	=> 'tools/updatePair',
				'confirm'	=> TRUE);
		
		$this->assign('tools', $tools);
	
	}

}
