<?php

use Pair\Core\Controller;
use Pair\Html\Breadcrumb;
use Pair\Support\Logger;
use Pair\Support\Plugin;
use Pair\Support\Upload;

class TemplatesController extends Controller {

	protected function init(): void {

		// removes files older than 30 minutes
		Plugin::removeOldFiles();

		Breadcrumb::path('Template', 'templates/default');

	}

	public function downloadAction(): void {

		$object = $this->getObjectRequestedById('Pair\Models\Template');
		$plugin = $object->getPlugin();
		$plugin->downloadPackage();

	}

	public function addAction(): void {

		$this->view = 'default';

		// collects file infos
		$upload = new Upload('package');

		if ('zip'!=$upload->type) {
			$this->app->modal('error', $this->lang('UPLOADED_FILE_IS_NOT_ZIP'));
			return;
		}

		// saves the file on /temp folder
		$upload->save(TEMP_PATH);

		// installs the package
		$plugin = new Plugin();
		$res = $plugin->installPackage($upload->path . $upload->filename);

		if ($res) {
			$this->app->modal('success', $this->lang('TEMPLATE_HAS_BEEN_INSTALLED_SUCCESFULLY'));
		} else {
			$this->app->modal('error', $this->lang('TEMPLATE_HAS_NOT_BEEN_INSTALLED'));
		}

	}

	public function deleteAction(): void {

		$template = $this->getObjectRequestedById('Pair\Models\Template');

		if ($template->delete()) {
			$this->toast($this->lang('TEMPLATE_HAS_BEEN_REMOVED_SUCCESFULLY'));
			$this->redirect('templates/default');
		} else {
			$this->toastError($this->lang('TEMPLATE_HAS_NOT_BEEN_REMOVED'));
			$this->view = 'default';
			$this->router->action = 'default';
			$this->router->resetParams();
		}

	}

}