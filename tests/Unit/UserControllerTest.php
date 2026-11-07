<?php

declare(strict_types=1);

use Pair\Core\Application;
use Pair\Html\Form;
use Pair\Http\Input;
use Pair\Models\User;
use Pair\Orm\ActiveRecord;
use Pair\Web\PageResponse;
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2) . '/modules/user/model.php';
require_once dirname(__DIR__, 2) . '/modules/user/controller.php';

/** Prevent guest User objects from causing login/profile redirect loops. */
final class UserControllerTest extends TestCase {

	/** An unloaded guest must receive a real login page, with no redirect. */
	public function testGuestLogin(): void {
		$controller = $this->controller(false);
		$response = $controller->loginAction();
		$this->assertInstanceOf(PageResponse::class, $response);
		$this->assertInstanceOf(UserLoginPageState::class, (new ReflectionProperty(PageResponse::class, 'state'))->getValue($response));
	}

	/** A loaded user visiting login is redirected to their profile. */
	public function testLoadedLogin(): void {
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('user/profile');
		$this->controller(true)->loginAction();
	}

	/** The default user route sends a guest to login instead of profile. */
	public function testGuestDefault(): void {
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('user/login');
		$this->controller(false)->defaultAction();
	}

	/** The default user route retains the authenticated profile destination. */
	public function testLoadedDefault(): void {
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('user/profile');
		$this->controller(true)->defaultAction();
	}

	/** Inject real guest/loaded flags without opening a database or initializing a session. */
	private function controller(bool $loaded): LoginProbeController {
		$user = (new ReflectionClass(User::class))->newInstanceWithoutConstructor();
		(new ReflectionProperty(ActiveRecord::class, 'loadedFromDb'))->setValue($user, $loaded);
		$app = (new ReflectionClass(Application::class))->newInstanceWithoutConstructor();
		(new ReflectionProperty(Application::class, 'currentUser'))->setValue($app, $user);
		$model = $this->createStub(UserModel::class);
		$model->method('getLoginForm')->willReturn(new Form());
		$controller = (new ReflectionClass(LoginProbeController::class))->newInstanceWithoutConstructor();
		(new ReflectionProperty(Pair\Web\Controller::class, 'app'))->setValue($controller, $app);
		(new ReflectionProperty(Pair\Web\Controller::class, 'requestInput'))->setValue($controller, new Input('GET'));
		(new ReflectionProperty(UserController::class, 'model'))->setValue($controller, $model);
		return $controller;
	}
}

/** Capture redirect destinations without terminating the PHPUnit process. */
final class LoginProbeController extends UserController {

	/** Report any redirect so a guest cannot silently pass through a profile loop. */
	public function redirect(?string $url = null, bool $externalUrl = false): void {
		throw new LogicException((string)$url);
	}

	/** Preserve the explicit page contract without constructor-owned runtime state. */
	protected function page(string $layout, object $state, ?string $title = null): PageResponse {
		return new PageResponse(dirname(__DIR__, 2) . '/modules/user/layouts/' . $layout . '.php', $state, $title);
	}
}
