<?php

declare(strict_types=1);

use Pair\Core\Application;
use Pair\Core\Env;
use Pair\Core\Router;
use Pair\Models\User;
use Pair\Orm\Collection;
use Pair\Web\PageResponse;
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2) . '/modules/selftest/classes/SelfTest.php';
require_once dirname(__DIR__, 2) . '/modules/selftest/controller.php';
require_once dirname(__DIR__, 2) . '/modules/users/model.php';
require_once dirname(__DIR__, 2) . '/modules/users/controller.php';

/** Check the actual installed pages against a configured fixture database. */
final class InstalledPagesTest extends TestCase {

	/** Database-dependent page checks use only explicitly configured test environments. */
	protected function setUp(): void {
		parent::setUp();
		if (!pairTestsHaveEnvironment()) {
			$this->markTestSkipped('Installed page checks require an application .env file.');
		}
	}

	/** Default server collations must not invalidate a correctly configured application session. */
	public function testMysql(): void {
		$this->assertTrue((new SelfTest())->checkMysql());
	}

	/** Protected bound fields and the first SQL column must pass the schema consistency check. */
	public function testUserMap(): void {
		$user = User::getObjectByQuery('SELECT * FROM users ORDER BY id LIMIT 1');
		$method = new ReflectionMethod(SelfTest::class, 'checkClassMaps');
		$this->assertSame(0, $method->invoke(new SelfTest(), $user));
	}

	/** The Self test controller accepts executable-path checks through its strict boolean contract. */
	public function testSelftestPage(): void {
		$router = Router::getInstance();
		$module = $router->module;
		try {
			$router->module = 'selftest';
			$response = (new SelftestController())->defaultAction();
			$this->assertInstanceOf(PageResponse::class, $response);
			$state = (new ReflectionProperty(PageResponse::class, 'state'))->getValue($response);
			foreach ($state->sections as $checks) {
				foreach ($checks as $check) {
					// Host executables are deployment prerequisites rather than database/schema fixtures.
					if (!str_contains($check->label, 'mysqldump') and !str_contains($check->label, 'bzip2')) {
						$this->assertTrue($check->result, $check->label);
					}
				}
			}
		} finally {
			$router->module = $module;
		}
	}

	/** A real DateTime last-login value must become a renderable list-state string. */
	public function testUsersPage(): void {
		$app = Application::getInstance();
		$formerUser = $app->currentUser;
		$router = Router::getInstance();
		$module = $router->module;
		try {
			$router->module = 'users';
			$user = User::getObjectByQuery('SELECT * FROM users ORDER BY id LIMIT 1');
			$user->lastLogin = new DateTime('2026-11-07 10:00:00', new DateTimeZone('UTC'));
			$app->setCurrentUser($user);
			$model = $this->createStub(UsersModel::class);
			$model->method('getUsers')->willReturn(new Collection([$user]));
			$controller = new UsersController();
			(new ReflectionProperty(UsersController::class, 'model'))->setValue($controller, $model);
			$response = $controller->defaultAction();
			$state = (new ReflectionProperty(PageResponse::class, 'state'))->getValue($response);
			$this->assertSame('2026-11-07 10:00:00', $state->users[0]->lastLogin);
		} finally {
			$router->module = $module;
			(new ReflectionProperty(Application::class, 'currentUser'))->setValue($app, $formerUser);
		}
	}
}
