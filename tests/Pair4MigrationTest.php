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
	 * Verify that module manifests reference files that still exist.
	 */
	public function testModuleManifestsReferenceExistingFiles(): void {

		foreach (glob($this->projectRoot() . '/modules/*/manifest.xml') ?: [] as $manifest) {
			$moduleDir = dirname($manifest);
			$xml = simplexml_load_file($manifest);

			$this->assertNotFalse($xml, 'Manifest must be readable: ' . $manifest);

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
