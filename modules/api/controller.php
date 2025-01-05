<?php

use Pair\Core\Config;
use Pair\Core\Controller;
use Pair\Core\Router;
use Pair\Models\Audit;
use Pair\Models\Session;
use Pair\Models\Token;
use Pair\Models\User;
use Pair\Orm\ActiveRecord;
use Pair\Support\Post;
use Pair\Support\Options;
use Pair\Support\Upload;

class ApiController extends Controller {
	
	/**
	 * The token object.
	 */
	private ?Token $token;

	/**
	 * The Bearer token string.
	 */
	private ?string $bearerToken;
	
	/**
	 * The session object.
	 */
	private ?Session $session;
	
	/**
	 * Missing methods.
	 */
	public function __call($name, $arguments): void {
		
		sleep(3);
		$name = substr($name, 0, -6);
		$this->sendError(21);
	}

	/**
	 * Set the Bearer token.
	 */
	public function setBearerToken(string $bearerToken): void {

		$this->bearerToken = $bearerToken;
	}

	/**
	 * Set the valid token.
	 */
	public function setToken(Token $token): void {
		
		$this->token = $token;
		
	}
	
	/**
	 * Set the current Session object.
	 */
	public function setSession(Session $session): void {
		
		$this->session = $session;
		
	}

	/**
	 * Do login and send session ID if valid.
	 * POST /login
	 */
	public function loginAction(): void {
		
		$username = Post::trim('username');
		$password = Post::trim('password');
		$timezone = Post::trim('timezone');
		
		if ($username and $password) {
	
			$result = User::doLogin($username, $password, $timezone);

			if (!$result->error) {

				$data = new stdClass();
				$data->sessionId = $result->sessionId;
				$this->sendData($data);

			} else {

				sleep(3);
				$this->sendError(22);

			}

		} else {

			$this->sendError(20);

		}

	}

	/**
	 * Do logout and delete session ID.
	 * GET /logout
	 */
	public function logoutAction(): void {
	
		if (!isset($this->session->id)) {
			$this->sendError(27); // 401 The session is invalid
		}
		
		$res = User::doLogout($this->session->id);
		
		if ($res) {
			$this->sendSuccess(); // 200 logout succesfully
		} else {
			$this->sendError(23); // 403 logout unsuccesfully
		}
		
	}
	
	/**
	 * Sends a JSON object with user name and instance name by user SID.
	 * Method for users access only.
	 * GET /getUserInfo
	 */
	public function getUserInformationsAction(): void {
		
		$user = $this->getUser();
		
		if (is_null($user)) {
			$this->sendError(25); // user privileges
		}

		$data = new stdClass();

		$data->name		= $user->name;
		$data->surname	= $user->surname;
		$data->username = $user->username;
		$data->group	= $user->getGroup()->name;
		$data->locale	= $user->getLocale()->getLanguage()->englishName;
		$data->timezone	= $user->tzName;
		$data->enabled	= $user->enabled;
		$data->email	= $user->email;
		
		$this->sendData($data);
		
	}

	public function deleteAccountAction(): void {

		$user = $this->getUser();

		if (!$user) {
			$this->sendError(25); // user privileges
		}

		$user->username	= '';
		$user->name		= '';
		$user->surname	= '';
		$user->email	= NULL;
		$user->enabled	= FALSE;
		$user->pwReset	= NULL;

		if (!$user->store()) {
			$this->sendError(2, [$user->getLastError()]); // unexpected error
		}

		// TODO eliminare la sessione
		//Session::current();

		$this->sendSuccess();
	}

	/**
	 * Restituisce l’oggetto JSON inviato allo stream di input.
	 */
	private function getJsonStream() {

		// receive the RAW post data via the php://input IO stream
		//$content = file_get_contents("php://input");

		// make sure that it is a POST request and application/json content type
		if (
			!isset($_SERVER['REQUEST_METHOD']) or 'POST' != strtoupper($_SERVER['REQUEST_METHOD']) or
			!isset($_SERVER['CONTENT_TYPE']) or 'application/json' != $_SERVER['CONTENT_TYPE']
		) {
			$this->sendError(43); // application/json POST is expected
			return NULL;
		}

		// receive and convert RAW post data
		return json_decode(file_get_contents("php://input"));

	}

	/**
	 * Get a parameter from router by its name and return a DateTime object if valid.
	 */
	private function getDateTimeParam($paramName): ?DateTime {
		
		$param = Router::get($paramName);
		
		if (!$this->isTimestampValid($param)) {
			$this->sendError(50, $paramName); // invalid timestamp
		}
		
		return new DateTime('@' . $param);
		
	}

	/**
	 * This method checks if passed timestamp looks valid.
	 */
	private function isTimestampValid($timestamp) {
		
		return ((string)(int)$timestamp === $timestamp)
			and ($timestamp <= PHP_INT_MAX)
			and ($timestamp >= ~PHP_INT_MAX);
		
	}
	
	/**
	 * Outputs a JSON error message with HTTP status code.
	 */
	public function sendError(int $errorNumber, ?array $params=NULL): void {

		$data = $this->model->getErrorData($errorNumber, $params);
		$this->printOut($data);
				
	}

	/**
	 * Outputs a confirm of task done within a JSON.
	 */
	public function sendSuccess(): void {
	
		$data = new stdClass();
		$data->error = FALSE;
		$this->printout($data);
	
	}
	
	/**
	 * Outputs a JSON object with (object)data property.
	 */
	public function sendData($data) {
	
		$this->printout($data);
		
	}
	
	/**
	 * Stampa in output come XML o JSON le informazioni data passate.
	 */
	private function printout($data): void {
		
		// anonymous function to extract latest SVN
		$nodeRecursion = function (&$node, $name, $value) use (&$nodeRecursion) {
			
			switch (gettype($value)) {
			
				case 'boolean':
					$node->addChild($name, ($value ? 'true' : 'false'));
					break;
			
				case 'array':
					foreach ($value as $newName=>$newValue) {
						if (is_numeric($newName)) {
							$newName = 'item';
						}
						$nodeRecursion($node, $newName, $newValue);
					}
					break;

				case 'object':
					$props = get_object_vars($value);
					$newNode = $node->addChild($name);
					foreach ($props as $newName=>$newValue) {
						$nodeRecursion($newNode, $newName, $newValue);
					}
					break;

				default:
					$node->addChild($name, $value);
					break;

			}
			
		};
		
		// check if return is required to be XML.
		if (Router::get('xml')) {
			
			// initialize node
			$baseName = strtolower(Config::get('PRODUCT_NAME'));
			$base = new SimpleXMLElement('<' . $baseName . '></' . $baseName . '>');
			$nodeRecursion($base, 'response', $data);

			header('Content-Type: text/xml', TRUE);
			print $base->asXML();
			
		} else {

			// DECODE = json_decode($json, false, 512, JSON_UNESCAPED_UNICODE);
			$json = json_encode($data, JSON_UNESCAPED_UNICODE);

			header('Content-Type: application/json', TRUE);
			print $json;

		}

		die();
		
	}

}