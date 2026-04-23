<?php

use Pair\Core\Application;
use Pair\Helpers\Upload;
use Pair\Html\Breadcrumb;
use Pair\Http\EmptyResponse;
use Pair\Models\Template;
use Pair\Packages\InstallablePackage;
use Pair\Web\PageResponse;

class TemplatesController extends BoilerplateController {

	/**
	 * Template module data helper.
	 */
	private TemplatesModel $model;

	/**
	 * Prepare the template administration page.
	 */
	protected function boot(): void {

		// Remove temporary package archives left by older uploads.
		InstallablePackage::removeOldArchives();

		$this->model = new TemplatesModel();
		Breadcrumb::path($this->translate('TEMPLATES'), 'templates/default');

	}

	/**
	 * Render the installed templates list.
	 */
	public function defaultAction(): PageResponse {

		$this->pageHeading($this->translate('TEMPLATES'));

		return $this->page('default', $this->buildDefaultPageState(), $this->translate('TEMPLATES'));

	}

	/**
	 * Render the upload form for a new template.
	 */
	public function newAction(): PageResponse {

		$this->loadScript('js/templates.js', true);
		Breadcrumb::path($this->translate('NEW_TEMPLATE'), 'templates/new');
		$this->pageHeading($this->translate('NEW_TEMPLATE'));

		$paletteColors = Template::defaultPaletteColors();

		return $this->page(
			'new',
			new TemplatesNewPageState(
				$this->model->getTemplateForm(),
				$paletteColors,
				$paletteColors[0]
			),
			$this->translate('NEW_TEMPLATE')
		);

	}

	/**
	 * Install a template package uploaded from the admin UI and optionally apply the selected palette.
	 */
	public function addAction(): PageResponse {

		$upload = new Upload('package');
		$palette = $this->getPaletteFromRequest();

		if ('zip' != $upload->type) {
			$this->modal($this->translate('ERROR'), $this->translate('UPLOADED_FILE_IS_NOT_ZIP'), 'error');
			return $this->defaultAction();
		}

		$upload->save(TEMP_PATH);
		$package = $upload->path . $upload->filename;
		$templateName = $this->getTemplateNameFromPackage($package);

		$installablePackage = new InstallablePackage();
		$installed = $installablePackage->installArchive($package);

		if (!$installed) {
			$this->modal($this->translate('ERROR'), $this->translate('TEMPLATE_HAS_NOT_BEEN_INSTALLED'), 'error');
			return $this->defaultAction();
		}

		$template = $templateName ? Template::getByName($templateName) : null;

		if (!$template || !$template->isLoaded()) {
			$template = Template::getObjectByQuery('SELECT * FROM `templates` ORDER BY `id` DESC LIMIT 1');
		}

		if ($template && $template->isLoaded()) {
			$template->palette = $palette;

			if (!$template->store()) {
				$this->toastError($this->translate('ERROR'), $this->translate('PALETTE_HAS_NOT_BEEN_APPLIED'));
			}
		} else {
			$this->toastError($this->translate('ERROR'), $this->translate('PALETTE_HAS_NOT_BEEN_APPLIED'));
		}

		$this->modal($this->translate('DONE'), $this->translate('TEMPLATE_HAS_BEEN_INSTALLED_SUCCESFULLY'), 'success');

		return $this->defaultAction();

	}

	/**
	 * Show the edit form for the selected template.
	 */
	public function editAction(): PageResponse {

		$template = $this->loadRecordFromRoute(Template::class);

		if (!$template || !$template->isLoaded()) {
			$this->redirect('templates/default');
		}

		return $this->buildEditPage($template);

	}

	/**
	 * Apply metadata and palette changes to an installed template.
	 */
	public function changeAction(): ?PageResponse {

		$template = new Template((int)$this->input()->int('id', 0));

		if (!$template->isLoaded()) {
			$this->toastError($this->translate('ERROR'), $this->translate('TEMPLATE_HAS_NOT_BEEN_CHANGED'));
			$this->redirect('templates/default');
			return null;
		}

		$oldName = $template->name;
		$newName = trim((string)$this->input()->string('name', ''));

		if (!$this->isValidTemplateName($newName)) {
			$this->toastError($this->translate('ERROR'), $this->translate('TEMPLATE_NAME_IS_INVALID'));
			$this->redirect('templates/edit/' . $template->id);
			return null;
		}

		$template->name = $newName;
		$template->version = trim((string)$this->input()->string('version', ''));
		$template->appVersion = trim((string)$this->input()->string('appVersion', ''));
		$template->palette = $this->getPaletteFromRequest();

		$oldFolder = $this->getExistingTemplateFolderPath($oldName);
		$newFolder = $this->getTemplateFolderPath($newName);
		$folderRenamed = false;

		if ($oldName !== $newName) {

			if (!$oldFolder || (is_dir($newFolder) && $oldFolder !== $newFolder)) {
				$this->toastError($this->translate('ERROR'), $this->translate('TEMPLATE_FOLDER_HAS_NOT_BEEN_RENAMED'));
				$this->redirect('templates/edit/' . $template->id);
				return null;
			}

			if ($oldFolder !== $newFolder && !@rename($oldFolder, $newFolder)) {
				$this->toastError($this->translate('ERROR'), $this->translate('TEMPLATE_FOLDER_HAS_NOT_BEEN_RENAMED'));
				$this->redirect('templates/edit/' . $template->id);
				return null;
			}

			$folderRenamed = ($oldFolder !== $newFolder);

		}

		if ($template->store()) {
			$this->toast($this->translate('DONE'), $this->translate('TEMPLATE_HAS_BEEN_CHANGED_SUCCESFULLY'));
			$this->redirect('templates/default');
			return null;
		}

		// Restore the old folder name if the filesystem rename happened but DB validation failed.
		if ($folderRenamed && is_dir($newFolder) && !is_dir($oldFolder)) {
			@rename($newFolder, $oldFolder);
		}

		$message = $this->translate('TEMPLATE_HAS_NOT_BEEN_CHANGED');
		$errors = $template->getErrors();

		if (count($errors)) {
			$message .= ":\n" . implode("\n", $errors);
		}

		$this->toastError($this->translate('ERROR'), $message);
		$this->redirect('templates/edit/' . $template->id);

		return null;

	}

	/**
	 * Download the selected template package.
	 */
	public function downloadAction(): EmptyResponse {

		$template = $this->loadRecordFromRoute(Template::class);
		$package = $template->getInstallablePackage();
		$package->downloadArchive();

		return new EmptyResponse();

	}

	/**
	 * Delete the selected installed template when development mode allows it.
	 */
	public function deleteAction(): ?PageResponse {

		if (Application::getEnvironment() !== 'development' || !$this->app->currentUser->admin) {
			$this->toastError($this->translate('ERROR'), $this->translate('TEMPLATE_HAS_NOT_BEEN_REMOVED'));
			$this->redirect('templates/default');
			return null;
		}

		try {
			$template = $this->loadRecordFromRoute(Template::class);
			$template->delete();
		} catch (Throwable $e) {
			$this->toastError($this->translate('ERROR'), $this->translate('TEMPLATE_HAS_NOT_BEEN_REMOVED'));
			$this->redirect('templates/default');
			return null;
		}

		$this->toast($this->translate('DONE'), $this->translate('TEMPLATE_HAS_BEEN_REMOVED_SUCCESFULLY'));
		$this->redirect('templates/default');

		return null;

	}

	/**
	 * Build the installed templates list state.
	 */
	private function buildDefaultPageState(): TemplatesDefaultPageState {

		$pagination = $this->buildPagination();
		$this->model->pagination = $pagination;
		$templates = $this->model->getActiveRecordObjects(Template::class, 'name');
		$deletionAllowed = ('development' == Application::getEnvironment() && $this->app->currentUser->admin);

		foreach ($templates as $template) {

			$paletteSamples = [];

			foreach ($template->getPaletteColors() as $color) {
				$safeColor = htmlspecialchars($color, ENT_QUOTES, 'UTF-8');
				$paletteSamples[] = '<span class="d-inline-block rounded border border-secondary-subtle me-1 mb-1" '
					. 'style="width:16px;height:16px;background-color:' . $safeColor . '" '
					. 'title="' . $safeColor . '"></span>';
			}

			$template->paletteSamples = implode('', $paletteSamples);
			$template->isDefaultIcon = $template->isDefault ? PAIR_CHECK_ICON : PAIR_TIMES_ICON;
			$template->dateReleasedText = $template->formatDateTime('dateReleased');
			$template->dateInstalledText = $template->formatDateTime('dateInstalled');

			if ($template->isCompatibleWithApp()) {
				$template->compatible = '<span class="fal fa-check fa-lg text-success"></span>';
			} else if ($template->isCompatibleWithAppMajorVersion()) {
				$template->compatible = '<span class="text-warning">' . htmlspecialchars($template->appVersion, ENT_QUOTES, 'UTF-8') . '</span>';
			} else {
				$template->compatible = '<span class="text-danger">' . htmlspecialchars($template->appVersion, ENT_QUOTES, 'UTF-8') . '</span>';
			}

			$template->downloadUrl = 'templates/download/' . $template->id;
			$template->editUrl = 'templates/edit/' . $template->id;
			$template->deleteUrl = 'templates/delete/' . $template->id;

		}

		return new TemplatesDefaultPageState($templates, $deletionAllowed, $pagination->render());

	}

	/**
	 * Build the template edit page response.
	 */
	private function buildEditPage(Template $template): PageResponse {

		$this->loadScript('js/templates.js', true);
		Breadcrumb::path($this->translate('EDIT_TEMPLATE'), 'templates/edit/' . $template->id);
		$this->pageHeading($this->translate('EDIT_TEMPLATE'));

		$form = $this->model->getTemplateEditForm();
		$form->values($template);

		$paletteColors = $template->getPaletteColors();

		return $this->page(
			'edit',
			new TemplatesEditPageState(
				$form,
				$template,
				$paletteColors,
				$paletteColors[0]
			),
			$this->translate('EDIT_TEMPLATE')
		);

	}

	/**
	 * Read palette colors from request and guarantee a non-empty color list.
	 *
	 * @return string[]
	 */
	private function getPaletteFromRequest(): array {

		$palette = $this->input()->array('palette');
		$cleanPalette = [];

		foreach ($palette as $color) {
			$color = trim((string)$color);

			if ('' !== $color) {
				$cleanPalette[] = $color;
			}
		}

		return count($cleanPalette) ? $cleanPalette : Template::defaultPaletteColors();

	}

	/**
	 * Return the canonical template folder path derived from a template name.
	 */
	private function getTemplateFolderPath(string $templateName): string {

		return APPLICATION_PATH . '/templates/' . strtolower(trim($templateName));

	}

	/**
	 * Return the first existing folder that could belong to the given template name.
	 */
	private function getExistingTemplateFolderPath(string $templateName): ?string {

		$trimmedName = trim($templateName);
		$candidates = [
			APPLICATION_PATH . '/templates/' . $trimmedName,
			APPLICATION_PATH . '/templates/' . strtolower($trimmedName),
			APPLICATION_PATH . '/templates/' . ucfirst(strtolower($trimmedName)),
			APPLICATION_PATH . '/templates/' . strtolower(str_replace([' ', '_'], '', $trimmedName)),
		];

		foreach (array_unique($candidates) as $folder) {
			if (is_dir($folder)) {
				return $folder;
			}
		}

		return null;

	}

	/**
	 * Read the template name from an uploaded ZIP package manifest.
	 */
	private function getTemplateNameFromPackage(string $package): ?string {

		$zip = new ZipArchive();

		if (true !== $zip->open($package)) {
			return null;
		}

		$manifestIndex = $zip->locateName('manifest.xml', ZipArchive::FL_NOCASE | ZipArchive::FL_NODIR);

		if (false === $manifestIndex) {
			$zip->close();
			return null;
		}

		$manifestContent = (string)$zip->getFromIndex($manifestIndex);
		$zip->close();

		$manifest = simplexml_load_string($manifestContent);

		if (!$manifest || !isset($manifest->package->name)) {
			return null;
		}

		$name = trim((string)$manifest->package->name);

		return '' === $name ? null : $name;

	}

	/**
	 * Validate a template name for DB storage and filesystem usage.
	 */
	private function isValidTemplateName(string $templateName): bool {

		return (bool)preg_match('/^[a-zA-Z0-9][a-zA-Z0-9 _-]*$/', $templateName);

	}

}
