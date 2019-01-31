<?php

use Pair\Controller;
use Pair\Group;
use Pair\Router;
use Pair\Session;
use Pair\Token;
use Pair\User;

class ApiController extends Controller {
	
	/**
	 * The token object.
	 * @var NULL|Pair\Token
	 */
	private $token;
	
	/**
	 * The session object.
	 * @var NULL|Pair\Session
	 */
	private $session;
	
	/**
	 * Missing methods.
	 */
	public function __call($name, $arguments) {
		
		sleep(3);
		$name = substr($name, 0, -6);
		$this->sendError('The requested API method does not exist (' . $name . ')');
		
	}

	/**
	 * Set the valid token.
	 * 
	 * @param	Token	The token object.
	 */
	public function setToken(Token $token) {
		
		$this->token = $token;
		
	}
	
	/**
	 * Set the current Session object.
	 *
	 * @param	Session	The session object.
	 */
	public function setSession(Session $session) {
		
		$this->session = $session;
		
	}

	/**
	 * Do login and send session ID if valid.
	 */
	public function loginAction() {
		
		$username = Router::get('username');
		$password = Router::get('password');
		$timezone = Router::get('timezone');
		
		if ($username and $password) {
	
			$result = User::doLogin($username, $password, $timezone);

			if (!$result->error) {

				$data = new stdClass();
				$data->sessionId = $result->sessionId;
				$this->sendData($data);
				
			} else {
				
				sleep(3);
				$this->sendError('Authentication failed');
				
			}
				
		} else {
			
			$this->sendError('Both username and password are required');
						
		}
		
	}
	
	/**
	 * Do logout and delete session ID.
	 */
	public function logoutAction() {
	
		if (!isset($this->session->id)) {
			$this->sendError('Session does not exist');
		}
		
		$res = User::doLogout($this->session->id);
		
		if ($res) {
			$this->sendSuccess();
		} else {
			$this->sendError('Logout unsuccesfully');
		}
		
	}
	
	/**
	 * Sends a JSON object with user name and instance name by user SID.
	 */
	public function getUserInformationsAction() {
		
		$this->checkSession();
		
		if (!Router::get('sid')) {
			$this->sendError('This API method requires an user login');
		}

		$user		= new User($this->session->idUser);
		$group		= new Group($user->groupId);
		$locale		= new Locale($user->localeId);
		
		$data = new stdClass();
		$data->name		= $user->name;
		$data->surname	= $user->surname;
		$data->fullname	= $user->fullName;
		$data->username = $user->username;
		$data->group	= $group->name;
		$data->locale	= $locale->englishName;
		$data->email	= $user->email;
		$data->timezone	= $user->tzName;
		
		$this->sendData($data);
		
	}
	
	/**
	 * Get a parameter from router by its name and return a DateTime object if valid.
	 * 
	 * @param	string	Name of the date param.
	 * 
	 * @return	DateTime
	 */
	private function getDateTimeParam($name) {
		
		$param = Router::get($name);
		
		if (!$this->isTimestampValid($param)) {
			$this->sendError(ucfirst($name) . ' date is not valid');
		}
		
		$dateTime = new DateTime('@' . $param);
		
		return $dateTime;
		
	}

	/**
	 * This method checks if passed timestamp looks valid.
	 * 
	 * @param	mixed	Timestamp value.
	 * 
	 * @return	boolean
	 */
	private function isTimestampValid($timestamp) {
		
		return ((string)(int)$timestamp === $timestamp)
			&& ($timestamp <= PHP_INT_MAX)
			&& ($timestamp >= ~PHP_INT_MAX);
		
	}
	
	/**
	 * Outputs a JSON error message.
	 *
	 * @param	string	Error message to print.
	 * @param	bool	Error code (optional). 
	 */
	public function sendError($message, $code=NULL) {

		$data = new stdClass();
		$data->error = TRUE;
		$data->code = $code;
		$data->message = $message;
		$this->printout($data);
				
	}

	/**
	 * Outputs a confirm of task done within a JSON.
	 */
	public function sendSuccess() {
	
		$data = new stdClass();
		$data->error = FALSE;
		$this->printout($data);
	
	}
	
	/**
	 * Outputs a JSON object with (object)data property.
	 *
	 * @param	object	Structured object containing data.
	 */
	public function sendData($data) {
	
		$this->printout($data);
		
	}
	
	private function printout($data) {
		
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
			$baseName = strtolower(PRODUCT_NAME);
			$base = new SimpleXMLElement('<' . $baseName . '></' . $baseName . '>');
			$nodeRecursion($base, 'response', $data);

			header('Content-Type: text/xml', TRUE);
			print $base->asXML();
			
		} else {

			$json = json_encode($data);
			header('Content-Type: application/json', TRUE);
			print $json;

		}

		die();
		
	}

}