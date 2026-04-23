<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class Pair4MigrationTest extends TestCase {

	/**
	 * Return the project root used by repository-level migration checks.
	 */
	private function projectRoot(): string {

		return dirname(__DIR__);

	}

	/**
	 * Verify that shared Pair 4 migration helpers are available through Composer autoload.
	 */
	public function testSharedPair4HelpersAreAutoloadable(): void {

		$this->assertTrue(class_exists(BoilerplateController::class));
		$this->assertTrue(class_exists(BoilerplateLayout::class));

	}

	/**
	 * Verify that the boilerplate selects the Pair v4 renderer matching its Bootstrap template.
	 */
	public function testAssetsSelectBootstrapRenderer(): void {

		$content = (string)file_get_contents($this->projectRoot() . '/classes/Assets.php');

		$this->assertStringContainsString("->uiFramework('bootstrap')", $content);

	}

	/**
	 * Verify that application code no longer depends on the deprecated Pair v4 MVC bridge.
	 */
	public function testApplicationCodeAvoidsDeprecatedPairMvcBridge(): void {

		$forbidden = [
			'use Pair\\Core\\' . 'Controller',
			'use Pair\\Core\\' . 'View',
			'extends \\Pair\\Core\\' . 'Controller',
			'extends \\Pair\\Core\\' . 'View',
		];

		foreach ($this->applicationPhpFiles() as $file) {
			$content = (string)file_get_contents($file);

			foreach ($forbidden as $pattern) {
				$this->assertStringNotContainsString($pattern, $content, $file);
			}
		}

	}

	/**
	 * Verify that application code uses the current Pair v4 CSS asset signature.
	 */
	public function testApplicationCodeUsesCurrentLoadCssSignature(): void {

		foreach ($this->applicationPhpFiles() as $file) {
			$content = (string)file_get_contents($file);

			$this->assertDoesNotMatchRegularExpression('/->loadCss\\([^;\\n]+,[^;\\n]+\\)/', $content, $file);
		}

	}

	/**
	 * Verify that application code uses current Pair 4 package and extension names.
	 */
	public function testApplicationCodeUsesCurrentPackageAndExtensionNames(): void {

		$forbidden = [
			'Pair\\Helpers\\' . 'InstallablePlugin',
			'Pair\\Helpers\\' . 'Plugin',
			'Pair\\Helpers\\' . 'PluginBase',
			'PluginInterface',
			'RuntimePluginInterface',
			'registerPlugin(',
			'registerRuntimePlugin(',
			'getInstallablePlugin(',
			'installPackage(',
			'downloadPackage(',
			'createManifestFile(',
			'getManifestByFile(',
			'removeOldFiles(',
			'fixPlugins(',
			'fixPluginsAction(',
		];

		foreach ($this->applicationPhpFiles() as $file) {
			$content = (string)file_get_contents($file);

			foreach ($forbidden as $pattern) {
				$this->assertStringNotContainsString($pattern, $content, $file);
			}
		}

	}

	/**
	 * Verify that module manifests reference files that still exist.
	 */
	public function testModuleManifestsReferenceExistingFiles(): void {

		foreach (glob($this->projectRoot() . '/modules/*/manifest.xml') ?: [] as $manifest) {
			$moduleDir = dirname($manifest);
			$xml = simplexml_load_file($manifest);

			$this->assertNotFalse($xml, 'Manifest must be readable: ' . $manifest);
			$manifestContents = (string)file_get_contents($manifest);

			$this->assertStringContainsString('<package', $manifestContents, $manifest);
			$this->assertStringNotContainsString('<plugin', $manifestContents, $manifest);
			$this->assertStringNotContainsString('</plugin>', $manifestContents, $manifest);

			foreach ($xml->xpath('//file') ?: [] as $fileNode) {
				$file = trim((string)$fileNode);

				if ($file === '') {
					continue;
				}

				$this->assertFileExists($moduleDir . '/' . $file, 'Manifest references a missing file.');
			}
		}

	}

	/**
	 * Return application PHP files that should stay on current Pair runtime APIs.
	 *
	 * @return list<string>
	 */
	private function applicationPhpFiles(): array {

		$root = $this->projectRoot();
		$paths = [
			$root . '/classes',
			$root . '/modules',
			$root . '/public',
			$root . '/cronjob.php',
			$root . '/migrate-cli.php',
		];
		$files = [];

		foreach ($paths as $path) {
			if (is_file($path) and str_ends_with($path, '.php')) {
				$files[] = $path;
				continue;
			}

			if (!is_dir($path)) {
				continue;
			}

			// Scan production application code while leaving tests and vendor out of this guard.
			$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));

			foreach ($iterator as $file) {
				if ($file->isFile() and 'php' === $file->getExtension()) {
					$files[] = $file->getPathname();
				}
			}
		}

		sort($files);

		return $files;

	}

	/**
	 * Verify that legacy Pair 3 view files have been removed from bundled modules.
	 */
	public function testLegacyViewFilesWereRemoved(): void {

		$legacyViews = glob($this->projectRoot() . '/modules/*/view*.php') ?: [];

		$this->assertSame([], $legacyViews);

	}

	/**
	 * Verify that application controllers use Pair 4 immutable input instead of the legacy Post helper.
	 */
	public function testControllersDoNotUseLegacyPostHelper(): void {

		foreach (glob($this->projectRoot() . '/modules/*/controller.php') ?: [] as $controller) {
			$content = (string)file_get_contents($controller);

			$this->assertStringNotContainsString('use Pair\\Helpers\\Post', $content, $controller);
			$this->assertStringNotContainsString('Post::', $content, $controller);
		}

	}

	/**
	 * Verify that API controllers use explicit Pair 4 responses instead of direct output termination.
	 */
	public function testApiControllersDoNotEmitDirectResponses(): void {

		foreach (glob($this->projectRoot() . '/modules/{api,oauth2}/controller.php', GLOB_BRACE) ?: [] as $controller) {
			$content = (string)file_get_contents($controller);

			$this->assertStringNotContainsString('die(', $content, $controller);
			$this->assertStringNotContainsString('header(', $content, $controller);
			$this->assertStringNotContainsString('http_response_code(', $content, $controller);
		}

	}

	/**
	 * Verify that stable API payloads are represented as Pair 4 read models.
	 */
	public function testApiPayloadsUseReadModels(): void {

		$content = (string)file_get_contents($this->projectRoot() . '/modules/api/model.php');

		$this->assertStringContainsString('class ApiErrorState implements ReadModel', $content);
		$this->assertStringContainsString('class ApiSuccessState implements ReadModel', $content);
		$this->assertStringContainsString('class ApiLoginState implements ReadModel', $content);
		$this->assertStringContainsString('class ApiUserInformationState implements ReadModel', $content);

	}

	/**
	 * Verify that generated modules are emitted with Pair 4 input contracts.
	 */
	public function testCrafterGeneratorUsesPair4InputContracts(): void {

		$content = (string)file_get_contents($this->projectRoot() . '/modules/crafter/model.php');

		$this->assertStringNotContainsString('use Pair\\Helpers\\Post;', $content);
		$this->assertStringNotContainsString('Post::get(', $content);
		$this->assertStringContainsString('$this->input()->value(', $content);

	}

}
