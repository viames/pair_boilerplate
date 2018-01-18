<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Breadcrumb;
use Pair\View;
use Pair\Widget;

class ToolsViewDefault extends View {

	public function render() {

		$this->app->pageTitle = $this->lang('TOOLS');
		$this->app->activeMenuItem	= 'tools';

		$breadcrumb = Breadcrumb::getInstance();
		$breadcrumb->addPath($this->lang('TOOLS'));
		
		$widget = new Widget();
		$this->app->breadcrumbWidget = $widget->render('breadcrumb');
		
		$widget = new Widget();
		$this->app->sideMenuWidget = $widget->render('sideMenu');
		
		// rebuild existent language files
		$tools[] = array(
				'title'	=> $this->lang('REBUILD_LANGUAGE_FILES'),
				'url'	=> 'tools/rebuildLanguageFiles');
		
		// restore db row or manifest file if missing
		$tools[] = array(
				'title'	=> $this->lang('FIX_PLUGINS'),
				'url'	=> 'tools/fixPlugins');

		$this->assign('tools', $tools);
	
	}

}
