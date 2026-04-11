<?php

use Pair\Core\Controller;
use Pair\Html\Breadcrumb;
use Pair\Helpers\LogBar;
use Pair\Helpers\Plugin;
use Pair\Helpers\Upload;

class ModulesController extends Controller {

	/**
	 * Prepare the module administration page.
	 */
	protected function _init(): void {

		// removes files older than 30 minutes
		Plugin::removeOldFiles();

		Breadcrumb::path('Moduli', 'modules/default');

	}

	/**
	 * Download the selected module package.
	 */
	public function downloadAction(): void {

		$object = $this->getObjectRequestedById('Pair\Models\Module');
		$plugin = $object->getPlugin();
		$plugin->downloadPackage();

	}

	/**
	 * Install a module package uploaded from the admin UI.
	 */
	public function addAction(): void {

		$this->setView('default');

		// collects file infos
		$upload = new Upload('package');

		// checks for common upload errors
		if ($upload->getLastError()) {
			LogBar::error($this->lang('ERROR_ON_UPLOADED_FILE'));
			return;
		} else if ('zip'!=$upload->type) {
			LogBar::error($this->lang('UPLOADED_FILE_IS_NOT_ZIP'));
			return;
		}

		// saves the file on /temp folder
		$upload->save(TEMP_PATH);

		// installs the package
		$plugin = new Plugin();
		$res = $plugin->installPackage($upload->path . $upload->filename);

		if ($res) {
			$this->toast($this->lang('MODULE_HAS_BEEN_INSTALLED_SUCCESFULLY'));
		} else {
			$this->toastError($this->lang('MODULE_HAS_NOT_BEEN_INSTALLED'));
		}

	}

	/**
	 * Delete the selected installed module.
	 */
	public function deleteAction(): void {

		$module = $this->getObjectRequestedById('Pair\Models\Module');

		if ($module->delete()) {
			$this->toast($this->lang('MODULE_HAS_BEEN_REMOVED_SUCCESFULLY'));
			$this->redirect('modules/default');
		} else {
			$this->toastError($this->lang('MODULE_HAS_NOT_BEEN_REMOVED'));
			$this->setView('default');
			$this->router->action = 'default';
			$this->router->resetParams();
		}

	}

}
