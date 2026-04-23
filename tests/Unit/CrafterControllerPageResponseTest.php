<?php

declare(strict_types=1);

if (!defined('APPLICATION_PATH')) {
	define('APPLICATION_PATH', dirname(__DIR__, 2));
}

require_once dirname(__DIR__, 2) . '/modules/crafter/model.php';
require_once dirname(__DIR__, 2) . '/modules/crafter/controller.php';
require_once dirname(__DIR__, 2) . '/modules/crafter/TestableCrafterController.php';

use Pair\Core\Application;
use Pair\Core\Router;
use Pair\Html\Form;
use Pair\Web\PageResponse;
use PHPUnit\Framework\TestCase;

/**
 * Test unitario dei route Pair v4 del modulo Crafter.
 */
final class CrafterControllerPageResponseTest extends TestCase {

	/**
	 * Snapshot del router da ripristinare dopo ogni caso.
	 *
	 * @var array<string, mixed>
	 */
	private array $routerState = [];

	/**
	 * Prepara router e runtime headless per i route Pair v4 del modulo Crafter.
	 */
	protected function setUp(): void {

		parent::setUp();

		if (!pairTestsHaveEnvironment()) {
			$this->markTestSkipped('Crafter PageResponse tests require an application .env file.');
		}

		$this->snapshotRouter();
		Application::getInstance()->headless(true);
		$router = Router::getInstance();
		$router->module = 'crafter';
		$router->action = 'default';
		$router->vars = [];

	}

	/**
	 * Verifica che `crafter/default` risponda con `PageResponse` Pair v4.
	 */
	public function testDefaultActionReturnsTypedPageResponse(): void {

		$controller = new TestableCrafterController();
		$response = $controller->defaultAction();
		$state = $this->extractState($response);

		$this->assertInstanceOf(PageResponse::class, $response);
		$this->assertInstanceOf(CrafterDefaultPageState::class, $state);
		$this->assertStringEndsWith('/modules/crafter/layouts/defaultPage.php', $this->extractTemplateFile($response));

	}

	/**
	 * Verifica che `crafter/newClass` risponda con `PageResponse` e stato tipizzato.
	 */
	public function testNewClassActionReturnsTypedPageResponse(): void {

		TestableCrafterController::useNewClassState(new CrafterNewClassPageState(['users', 'parties']));

		$router = Router::getInstance();
		$router->action = 'newClass';
		$controller = new TestableCrafterController();
		$response = $controller->newClassAction();
		$state = $this->extractState($response);

		$this->assertInstanceOf(PageResponse::class, $response);
		$this->assertInstanceOf(CrafterNewClassPageState::class, $state);
		$this->assertSame('users', $state->unmappedTables[0]);
		$this->assertStringEndsWith('/modules/crafter/layouts/newClassPage.php', $this->extractTemplateFile($response));

	}

	/**
	 * Verifica che `crafter/newTable` risponda con `PageResponse` e stato tipizzato.
	 */
	public function testNewTableActionReturnsTypedPageResponse(): void {

		TestableCrafterController::useNewTableState(new CrafterNewTablePageState(['User', 'Party']));

		$router = Router::getInstance();
		$router->action = 'newTable';
		$controller = new TestableCrafterController();
		$response = $controller->newTableAction();
		$state = $this->extractState($response);

		$this->assertInstanceOf(PageResponse::class, $response);
		$this->assertInstanceOf(CrafterNewTablePageState::class, $state);
		$this->assertSame('User', $state->unmappedClasses[0]);
		$this->assertStringEndsWith('/modules/crafter/layouts/newTablePage.php', $this->extractTemplateFile($response));

	}

	/**
	 * Verifica che `crafter/newClassWizard` risponda con `PageResponse` e stato tipizzato.
	 */
	public function testNewClassWizardActionReturnsTypedPageResponse(): void {

		$form = new Form();
		$form->hidden('tableName')->value('test_table');
		$form->text('objectName')->value('TestTable');
		TestableCrafterController::useClassWizardState(new CrafterClassWizardPageState($form));

		$router = Router::getInstance();
		$router->action = 'newClassWizard';
		$controller = new TestableCrafterController();
		$response = $controller->newClassWizardAction();
		$state = $this->extractState($response);

		$this->assertInstanceOf(PageResponse::class, $response);
		$this->assertInstanceOf(CrafterClassWizardPageState::class, $state);
		$this->assertStringEndsWith('/modules/crafter/layouts/newClassWizardPage.php', $this->extractTemplateFile($response));

	}

	/**
	 * Verifica che `crafter/newModuleWizard` risponda con `PageResponse` e stato tipizzato.
	 */
	public function testNewModuleWizardActionReturnsTypedPageResponse(): void {

		$form = new Form();
		$form->hidden('tableName')->value('test_table');
		$form->text('objectName')->value('TestTable');
		$form->text('moduleName')->value('testtable');
		TestableCrafterController::useModuleWizardState(new CrafterModuleWizardPageState($form, []));

		$router = Router::getInstance();
		$router->action = 'newModuleWizard';
		$controller = new TestableCrafterController();
		$response = $controller->newModuleWizardAction();
		$state = $this->extractState($response);

		$this->assertInstanceOf(PageResponse::class, $response);
		$this->assertInstanceOf(CrafterModuleWizardPageState::class, $state);
		$this->assertStringEndsWith('/modules/crafter/layouts/newModuleWizardPage.php', $this->extractTemplateFile($response));

	}

	/**
	 * Verifica che `crafter/playground` risponda con `PageResponse` e stato tipizzato.
	 */
	public function testPlaygroundActionReturnsTypedPageResponse(): void {

		TestableCrafterController::usePlaygroundState(new CrafterPlaygroundPageState([]));

		$router = Router::getInstance();
		$router->action = 'playground';
		$controller = new TestableCrafterController();
		$response = $controller->playgroundAction();
		$state = $this->extractState($response);

		$this->assertInstanceOf(PageResponse::class, $response);
		$this->assertInstanceOf(CrafterPlaygroundPageState::class, $state);
		$this->assertStringEndsWith('/modules/crafter/layouts/playgroundPage.php', $this->extractTemplateFile($response));

	}

	/**
	 * Verifica che il campionario Bootstrap renda i componenti UI sensibili al tema Pair.
	 */
	public function testBootstrapReferenceActionRendersThemeSmokeMarkup(): void {

		$router = Router::getInstance();
		$router->action = 'bootstrapReference';
		$controller = new TestableCrafterController();
		$response = $controller->bootstrapReferenceAction();
		$state = $this->extractState($response);
		$output = $this->renderPageResponse($response);

		$this->assertInstanceOf(PageResponse::class, $response);
		$this->assertInstanceOf(CrafterBootstrapReferencePageState::class, $state);
		$this->assertStringEndsWith('/modules/crafter/layouts/bootstrapReferencePage.php', $this->extractTemplateFile($response));
		$this->assertStringContainsString('data-crafter-ui-reference', $output);
		$this->assertStringContainsString('data-crafter-tomselect', $output);
		$this->assertStringContainsString('data-bs-toggle="tooltip"', $output);
		$this->assertStringContainsString('class="pagination mb-0 flex-wrap"', $output);
		$this->assertStringContainsString('class="alert alert-info h-100 mb-0"', $output);
		$this->assertStringContainsString('class="badge text-bg-success"', $output);
		$this->assertStringContainsString('id="crafterDemoToast"', $output);
		$this->assertStringContainsString('id="crafterDemoModal"', $output);

	}

	/**
	 * Ripristina router e stato headless al termine del caso.
	 */
	protected function tearDown(): void {

		TestableCrafterController::clearTestState();

		if ($this->routerState !== []) {
			$this->restoreRouter();
			Application::getInstance()->headless(false);
		}

		parent::tearDown();

	}

	/**
	 * Salva lo stato router corrente prima del test.
	 */
	private function snapshotRouter(): void {

		$router = Router::getInstance();
		$this->routerState = [
			'module' => $router->module,
			'action' => $router->action,
			'vars' => $router->vars,
		];

	}

	/**
	 * Ripristina modulo, action e vars del router precedente al test.
	 */
	private function restoreRouter(): void {

		$router = Router::getInstance();
		$router->module = $this->routerState['module'];
		$router->action = $this->routerState['action'];
		$router->vars = $this->routerState['vars'];

	}

	/**
	 * Estrae lo stato tipizzato contenuto nella `PageResponse`.
	 */
	private function extractState(PageResponse $response): object {

		$reflection = new ReflectionClass($response);
		$property = $reflection->getProperty('state');

		return $property->getValue($response);

	}

	/**
	 * Estrae il file template fisico usato dalla `PageResponse`.
	 */
	private function extractTemplateFile(PageResponse $response): string {

		$reflection = new ReflectionClass($response);
		$property = $reflection->getProperty('templateFile');

		return (string)$property->getValue($response);

	}

	/**
	 * Renderizza una `PageResponse` e ne restituisce l'HTML prodotto.
	 */
	private function renderPageResponse(PageResponse $response): string {

		ob_start();
		$response->send();

		return (string)ob_get_clean();

	}

}
