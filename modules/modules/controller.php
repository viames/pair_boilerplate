<?php

declare(strict_types=1);

use Pair\Core\Application;
use Pair\Core\Env;
use Pair\Html\Breadcrumb;
use Pair\Http\EmptyResponse;
use Pair\Helpers\LogBar;
use Pair\Helpers\Plugin;
use Pair\Helpers\Upload;
use Pair\Models\Module;
use Pair\Web\PageResponse;

class ModulesController extends BoilerplateController {

	/**
	 * Module administration data helper.
	 */
	private ModulesModel $model;

	/**
	 * Prepare the module administration page.
	 */
	protected function boot(): void {

		// removes files older than 30 minutes
		Plugin::removeOldFiles();

		Breadcrumb::path('Moduli', 'modules/default');
		$this->model = new ModulesModel();

	}

	/**
	 * Render the installed modules list.
	 */
	public function defaultAction(): PageResponse {

		$this->pageHeading($this->translate('MODULES'));

		return $this->page('default', $this->buildDefaultPageState(), $this->translate('MODULES'));

	}

	/**
	 * Render the module upload form.
	 */
	public function newAction(): PageResponse {

		Breadcrumb::path($this->translate('NEW_MODULE'), 'modules/new');
		$this->pageHeading($this->translate('NEW_MODULE'));

		return $this->page('new', new ModulesNewPageState($this->model->getModuleForm()), $this->translate('NEW_MODULE'));

	}

	/**
	 * Download the selected module package.
	 */
	public function downloadAction(): EmptyResponse {

		$module = $this->loadRecordFromRoute(Module::class);
		$plugin = $module->getPlugin();
		$plugin->downloadPackage();

		return new EmptyResponse();

	}

	/**
	 * Install a module package uploaded from the admin UI.
	 */
	public function addAction(): PageResponse {

		// collects file infos
		$upload = new Upload('package');

		// checks for common upload errors
		if ($upload->getLastError()) {
			LogBar::error($this->translate('ERROR_ON_UPLOADED_FILE'));
			return $this->defaultAction();
		} else if ('zip'!=$upload->type) {
			LogBar::error($this->translate('UPLOADED_FILE_IS_NOT_ZIP'));
			return $this->defaultAction();
		}

		// saves the file on /temp folder
		$upload->save(TEMP_PATH);

		// installs the package
		$plugin = new Plugin();
		$res = $plugin->installPackage($upload->path . $upload->filename);

		if ($res) {
			$this->toast($this->translate('MODULE_HAS_BEEN_INSTALLED_SUCCESFULLY'));
		} else {
			$this->toastError($this->translate('MODULE_HAS_NOT_BEEN_INSTALLED'));
		}

		return $this->defaultAction();

	}

	/**
	 * Delete the selected installed module.
	 */
	public function deleteAction(): ?PageResponse {

		$module = $this->loadRecordFromRoute(Module::class);

		if ($module->delete()) {
			$this->toast($this->translate('MODULE_HAS_BEEN_REMOVED_SUCCESFULLY'));
			$this->redirect('modules/default');
		} else {
			$this->toastError($this->translate('MODULE_HAS_NOT_BEEN_REMOVED'));
			return $this->defaultAction();
		}

		return null;

	}

	/**
	 * Build the installed modules list state.
	 */
	private function buildDefaultPageState(): ModulesDefaultPageState {

		$pagination = $this->buildPagination();
		$this->model->pagination = $pagination;

		$modules = $this->model->getActiveRecordObjects(Module::class, 'name');
		$devMode = ('development' == Application::getEnvironment() and $this->app->currentUser and $this->app->currentUser->admin);

		foreach ($modules as $module) {

			// The layout receives precomputed HTML snippets for compatibility badges and actions.
			$module->compatible = (version_compare(Env::get('APP_VERSION'), $module->appVersion) <= 0)
				? '<span class="fa fa-check fa-lg text-success"></span>'
				: '<div style="color:red">v' . htmlspecialchars((string)$module->appVersion) . '</div>';

			$module->downloadIcon = '<a href="modules/download/'. $module->id .'">'.
					'<span class="fa fa-lg fa-download"></span></a>';

			$module->deleteIcon = $devMode
				? '<a href="modules/delete/'. $module->id .'" class="confirm-delete"><span class="fa fa-lg fa-times"></span></a>'
				: '<span class="fa fa-lg fa-times disabled"></span>';

		}

		return new ModulesDefaultPageState($modules, $pagination->render());

	}

}
