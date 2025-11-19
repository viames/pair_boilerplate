<?php

use Pair\Html\Breadcrumb;
use Pair\Core\View;

class ToolsViewDefault extends View {

	public function render(): void {

		$this->pageTitle($this->lang('TOOLS'));

		Breadcrumb::path($this->lang('TOOLS'));

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
