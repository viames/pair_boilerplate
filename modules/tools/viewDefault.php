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

		// popolamento uuid
		$tools[] = [
			'title'		=> 'Popola gli uuid mancanti',
			'url'		=> 'tools/setUuid',
			'confirm'	=> FALSE
		];

		// update files and db to latest Pair version
		$tools[] = array(
				'title'	=> $this->lang('UPDATE_PAIR_14'),
				'url'	=> 'tools/updatePair',
				'confirm'	=> TRUE);
		
		$this->assign('tools', $tools);
	
	}

}
