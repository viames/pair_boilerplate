<?php

use Pair\Html\Breadcrumb;
use Pair\Core\Controller;
use Pair\Support\Logger;
use Pair\Support\Plugin;
use Pair\Support\Upload;

class TemplatesController extends Controller {

	/**
	 * This function being invoked before everything here.
	 * @see Controller::init()
	 */
	protected function init(): void {

		// removes files older than 30 minutes
		Plugin::removeOldFiles();

		Breadcrumb::path('Template', 'templates/default');

	}

	public function downloadAction() {

		$object = $this->getObjectRequestedById('Pair\Models\Template');
		$plugin = $object->getPlugin();
		$plugin->downloadPackage();

	}

	public function addAction() {

		$this->view = 'default';

		// collects file infos
		$upload = new Upload('package');

		// checks for common upload errors
		if ($upload->getLastError()) {
			Logger::error($this->lang('ERROR_ON_UPLOADED_FILE'));
			return;
		} else if ('zip'!=$upload->type) {
			Logger::error($this->lang('UPLOADED_FILE_IS_NOT_ZIP'));
			return;
		}

		// saves the file on /temp folder
		$upload->save(APPLICATION_PATH . '/' . Plugin::TEMP_FOLDER);

		// installs the package
		$plugin = new Plugin();
		$res = $plugin->installPackage($upload->path . $upload->filename);

		if ($res) {
			$this->enqueueMessage($this->lang('TEMPLATE_HAS_BEEN_INSTALLED_SUCCESFULLY'));
		} else {
			$this->enqueueError($this->lang('TEMPLATE_HAS_NOT_BEEN_INSTALLED'));
		}

	}

	public function deleteAction() {

		$template = $this->getObjectRequestedById('Pair\Models\Template');

		if ($template->delete()) {
			$this->enqueueMessage($this->lang('TEMPLATE_HAS_BEEN_REMOVED_SUCCESFULLY'));
			$this->redirect('templates/default');
		} else {
			$this->enqueueError($this->lang('TEMPLATE_HAS_NOT_BEEN_REMOVED'));
			$this->view = 'default';
			$this->router->action = 'default';
			$this->router->resetParams();
		}

	}

}