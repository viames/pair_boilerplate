<?php

declare(strict_types=1);

use Pair\Api\ApiController as PairApiController;
use Pair\Core\Env;
use Pair\Core\Router;
use Pair\Data\ReadModel;
use Pair\Http\JsonResponse;
use Pair\Http\ResponseInterface;
use Pair\Http\TextResponse;
use Pair\Models\User;

class ApiController extends PairApiController {

	/**
	 * API response payload helper.
	 */
	private ApiModel $model;

	/**
	 * Prepare API dependencies while preserving Pair API middleware bootstrapping.
	 */
	protected function boot(): void {

		parent::boot();
		$this->model = new ApiModel();

	}

	/**
	 * Allow public session bootstrap endpoints before SID or bearer authentication.
	 */
	public function allowsUnauthenticatedAction(string $routerAction, string $methodName): bool {

		return in_array($methodName, ['loginAction'], true);

	}
	
	/**
	 * Missing methods.
	 */
	public function __call(mixed $name, mixed $arguments): ResponseInterface {
		
		sleep(3);
		return $this->sendError(21);

	}

	/**
	 * Do login and send session ID if valid.
	 * POST /login
	 */
	public function loginAction(): ResponseInterface {
		
		$username = trim((string)$this->input()->string('username', ''));
		$password = trim((string)$this->input()->string('password', ''));
		$timezone = trim((string)$this->input()->string('timezone', ''));
		
		if ($username and $password) {
	
			$result = User::doLogin($username, $password, $timezone);

			if (!$result->error) {

				return $this->sendData(new ApiLoginState((string)$result->sessionId));

			} else {

				sleep(3);
				return $this->sendError(22);

			}

		} else {

			return $this->sendError(20);

		}

	}

	/**
	 * Do logout and delete session ID.
	 * GET /logout
	 */
	public function logoutAction(): ResponseInterface {
	
		if (!isset($this->session->id)) {
			return $this->sendError(27); // 401 The session is invalid
		}
		
		$res = User::doLogout($this->session->id);
		
		if ($res) {
			return $this->sendSuccess(); // 200 logout succesfully
		}

		return $this->sendError(23); // 403 logout unsuccesfully
		
	}
	
	/**
	 * Sends a JSON object with user name and instance name by user SID.
	 * Method for users access only.
	 * GET /getUserInfo
	 */
	public function getUserInformationsAction(): ResponseInterface {
		
		$user = $this->getUser();
		
		if (is_null($user)) {
			return $this->sendError(25); // user privileges
		}

		return $this->sendData(new ApiUserInformationState(
			(string)$user->name,
			(string)$user->surname,
			(string)$user->username,
			(string)$user->getGroup()->name,
			(string)$user->getLocale()->getLanguage()->englishName,
			$user->tzName ? (string)$user->tzName : null,
			(bool)$user->enabled,
			$user->email ? (string)$user->email : null
		));
		
	}

	/**
	 * Delete the currently authenticated user account data.
	 */
	public function deleteAccountAction(): ResponseInterface {

		$user = $this->getUser();

		if (!$user) {
			return $this->sendError(25); // user privileges
		}

		$user->username	= '';
		$user->name		= '';
		$user->surname	= '';
		$user->email	= NULL;
		$user->enabled	= FALSE;
		$user->pwReset	= NULL;

		if (!$user->store()) {
			return $this->sendError(2, [$user->getLastError()]); // unexpected error
		}

		// TODO eliminare la sessione
		//Session::current();

		return $this->sendSuccess();

	}
	
	/**
	 * Build an explicit API error response with the historical numeric payload.
	 */
	public function sendError(int $errorNumber, ?array $params=NULL): ResponseInterface {

		$data = $this->model->getErrorData($errorNumber, $params);
		return $this->response($data, $this->model->getErrorStatusCode($errorNumber));
				
	}

	/**
	 * Build an explicit success response.
	 */
	public function sendSuccess(): ResponseInterface {
	
		return $this->response(new ApiSuccessState());
	
	}
	
	/**
	 * Build an explicit data response.
	 */
	public function sendData(mixed $data): ResponseInterface {
	
		return $this->response($data);
		
	}
	
	/**
	 * Build either a JSON or XML response depending on the legacy route flag.
	 */
	private function response(mixed $data, int $httpCode = 200): ResponseInterface {

		$payload = $data instanceof ReadModel ? $data->toArray() : $data;

		if (Router::get('xml')) {
			return $this->xmlResponse($payload, $httpCode);
		}

		return new JsonResponse($data, $httpCode);

	}

	/**
	 * Convert a payload to the legacy XML response format.
	 */
	private function xmlResponse(mixed $data, int $httpCode): TextResponse {

		$baseName = strtolower((string)Env::get('APP_NAME'));
		$baseName = preg_replace('/[^a-z0-9_:-]/i', '', $baseName) ?: 'response';
		$base = new SimpleXMLElement('<' . $baseName . '></' . $baseName . '>');

		$this->appendXmlNode($base, 'response', $data);

		return new TextResponse((string)$base->asXML(), $httpCode, 'text/xml; charset=utf-8');

	}

	/**
	 * Append a scalar, array, or object value to the XML response tree.
	 */
	private function appendXmlNode(SimpleXMLElement $node, string $name, mixed $value): void {

		if (is_bool($value)) {
			$node->addChild($name, $value ? 'true' : 'false');
			return;
		}

		if (is_array($value)) {
			foreach ($value as $newName => $newValue) {
				$this->appendXmlNode($node, is_numeric($newName) ? 'item' : (string)$newName, $newValue);
			}
			return;
		}

		if (is_object($value)) {
			$newNode = $node->addChild($name);
			foreach (get_object_vars($value) as $newName => $newValue) {
				$this->appendXmlNode($newNode, (string)$newName, $newValue);
			}
			return;
		}

		$node->addChild($name, htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'));

	}

}
