<?php

use Pair\Core\Application;
use Pair\Core\Controller;
use Pair\Helpers\Plugin;
use Pair\Helpers\Post;
use Pair\Helpers\Upload;
use Pair\Html\Breadcrumb;
use Pair\Models\Template;

class TemplatesController extends Controller {

	/**
	 * Prepare the template administration page.
	 */
	protected function _init(): void {

		// Remove temporary plugin archives left by older uploads.
		Plugin::removeOldFiles();

		Breadcrumb::path($this->lang('TEMPLATES'), 'templates/default');

	}

	/**
	 * Install a template package uploaded from the admin UI and optionally apply the selected palette.
	 */
	public function addAction(): void {

		$this->setView('default');

		$upload = new Upload('package');
		$palette = $this->getPaletteFromRequest();

		if ('zip' != $upload->type) {
			$this->modal($this->lang('ERROR'), $this->lang('UPLOADED_FILE_IS_NOT_ZIP'), 'error');
			return;
		}

		$upload->save(TEMP_PATH);
		$package = $upload->path . $upload->filename;
		$templateName = $this->getTemplateNameFromPackage($package);

		$plugin = new Plugin();
		$installed = $plugin->installPackage($package);

		if (!$installed) {
			$this->modal($this->lang('ERROR'), $this->lang('TEMPLATE_HAS_NOT_BEEN_INSTALLED'), 'error');
			return;
		}

		$template = $templateName ? Template::getPluginByName($templateName) : null;

		if (!$template || !$template->isLoaded()) {
			$template = Template::getObjectByQuery('SELECT * FROM `templates` ORDER BY `id` DESC LIMIT 1');
		}

		if ($template && $template->isLoaded()) {
			$template->palette = $palette;

			if (!$template->store()) {
				$this->toastError($this->lang('ERROR'), $this->lang('PALETTE_HAS_NOT_BEEN_APPLIED'));
			}
		} else {
			$this->toastError($this->lang('ERROR'), $this->lang('PALETTE_HAS_NOT_BEEN_APPLIED'));
		}

		$this->modal($this->lang('DONE'), $this->lang('TEMPLATE_HAS_BEEN_INSTALLED_SUCCESFULLY'), 'success');

	}

	/**
	 * Show the edit form for the selected template.
	 */
	public function editAction(): void {

		$template = $this->getObjectRequestedById('Pair\Models\Template');

		if (!$template || !$template->isLoaded()) {
			$this->redirect('templates/default');
			return;
		}

		$this->setView('edit');

	}

	/**
	 * Apply metadata and palette changes to an installed template.
	 */
	public function changeAction(): void {

		$template = new Template(Post::int('id'));

		if (!$template->isLoaded()) {
			$this->toastError($this->lang('ERROR'), $this->lang('TEMPLATE_HAS_NOT_BEEN_CHANGED'));
			$this->redirect('templates/default');
			return;
		}

		$oldName = $template->name;
		$newName = trim(Post::get('name'));

		if (!$this->isValidTemplateName($newName)) {
			$this->toastError($this->lang('ERROR'), $this->lang('TEMPLATE_NAME_IS_INVALID'));
			$this->redirect('templates/edit/' . $template->id);
			return;
		}

		$template->name = $newName;
		$template->version = trim(Post::get('version'));
		$template->appVersion = trim(Post::get('appVersion'));
		$template->palette = $this->getPaletteFromRequest();

		$oldFolder = $this->getExistingTemplateFolderPath($oldName);
		$newFolder = $this->getTemplateFolderPath($newName);
		$folderRenamed = false;

		if ($oldName !== $newName) {

			if (!$oldFolder || (is_dir($newFolder) && $oldFolder !== $newFolder)) {
				$this->toastError($this->lang('ERROR'), $this->lang('TEMPLATE_FOLDER_HAS_NOT_BEEN_RENAMED'));
				$this->redirect('templates/edit/' . $template->id);
				return;
			}

			if ($oldFolder !== $newFolder && !@rename($oldFolder, $newFolder)) {
				$this->toastError($this->lang('ERROR'), $this->lang('TEMPLATE_FOLDER_HAS_NOT_BEEN_RENAMED'));
				$this->redirect('templates/edit/' . $template->id);
				return;
			}

			$folderRenamed = ($oldFolder !== $newFolder);

		}

		if ($template->store()) {
			$this->toast($this->lang('DONE'), $this->lang('TEMPLATE_HAS_BEEN_CHANGED_SUCCESFULLY'));
			$this->redirect('templates/default');
			return;
		}

		// Restore the old folder name if the filesystem rename happened but DB validation failed.
		if ($folderRenamed && is_dir($newFolder) && !is_dir($oldFolder)) {
			@rename($newFolder, $oldFolder);
		}

		$message = $this->lang('TEMPLATE_HAS_NOT_BEEN_CHANGED');
		$errors = $template->getErrors();

		if (count($errors)) {
			$message .= ":\n" . implode("\n", $errors);
		}

		$this->toastError($this->lang('ERROR'), $message);
		$this->redirect('templates/edit/' . $template->id);

	}

	/**
	 * Download the selected template package.
	 */
	public function downloadAction(): void {

		$template = $this->getObjectRequestedById('Pair\Models\Template');
		$plugin = $template->getPlugin();
		$plugin->downloadPackage();

	}

	/**
	 * Delete the selected installed template when development mode allows it.
	 */
	public function deleteAction(): void {

		if (Application::getEnvironment() !== 'development' || !$this->app->currentUser->admin) {
			$this->toastError($this->lang('ERROR'), $this->lang('TEMPLATE_HAS_NOT_BEEN_REMOVED'));
			$this->redirect('templates/default');
			return;
		}

		try {
			$template = $this->getObjectRequestedById('Pair\Models\Template');
			$template->delete();
		} catch (Throwable $e) {
			$this->toastError($this->lang('ERROR'), $this->lang('TEMPLATE_HAS_NOT_BEEN_REMOVED'));
			$this->redirect('templates/default');
			return;
		}

		$this->toast($this->lang('DONE'), $this->lang('TEMPLATE_HAS_BEEN_REMOVED_SUCCESFULLY'));
		$this->redirect('templates/default');

	}

	/**
	 * Read palette colors from request and guarantee a non-empty color list.
	 *
	 * @return string[]
	 */
	private function getPaletteFromRequest(): array {

		$palette = Post::array('palette');
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

		if (!$manifest || !isset($manifest->plugin->name)) {
			return null;
		}

		$name = trim((string)$manifest->plugin->name);

		return '' === $name ? null : $name;

	}

	/**
	 * Validate a template name for DB storage and filesystem usage.
	 */
	private function isValidTemplateName(string $templateName): bool {

		return (bool)preg_match('/^[a-zA-Z0-9][a-zA-Z0-9 _-]*$/', $templateName);

	}

}
