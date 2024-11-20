<?php

use Pair\Core\Model;

class ApiModel extends Model {

	/**
	 * Set the HTTP error code and return an object to send.
	 */
	public function getErrorData(int $errorNumber, ?array $params=NULL): stdClass {

		$errorList = [
			1	=> [500, 'There was an unexpected error'],
			2	=> [500, 'There was an unexpected error: %s'],
			4	=> [500, '%s'],
			18	=> [400, 'Password length is too short'],
			19	=> [401, 'The token is invalid'],
			20	=> [401, 'Both username and password are required'],
			21	=> [501, 'The requested API method does not exist'],
			22	=> [401, 'Authentication failed'],
			23	=> [403, 'Logout unsuccesfully'],
			24	=> [401, 'Bearer token is invalid'],
			25	=> [403, 'The user does not have the privileges to perform the requested action'],
			27	=> [401, 'The session is invalid'],
			28	=> [400, 'The form sent is not complete with required data'],
			29	=> [400, 'The form sent is missing field: %s'],
			30	=> [400, 'Postal code or city name is invalid'],
			34	=> [400, 'Invalid fields: %s'],
			35	=> [400, 'Value %s is not valid for field %s'],
			36	=> [400, 'The field % is expected to be of type %s'],
			40	=> [400, 'The parameters passed to the method are invalid'],
			41	=> [400, 'This code does not correspond to any city'],
			43	=> [400, 'An application/json request with the HTTP POST method is expected'],
			44	=> [401, 'The tracing token is invalid'],
			48	=> [400, 'The quantity is not valid'],
			50	=> [400, 'Timestamp parameter %s is not valid'],
			51	=> [400, 'Email address is not valid'],
			52	=> [400, 'The Reward ID:%s exists but it is not valid'],
			53	=> [400, 'The Service is not valid'],
			54	=> [400, 'VAT number already exists'],
			55	=> [400, 'Email address already exists'],
			56	=> [400, 'The VAT number is not registered in VIES'],
			57	=> [400, 'Duplicated object: %s'],
			100	=> [400, 'The object was not found (%s, %s)'],
			101	=> [400, 'Error when save file to storage']
		];

		// unvalid error number
		if (!in_array($errorNumber, array_keys($errorList))) {
			$errorNumber = 1;
		}

		// add error detail for code 1 (unexpected error)
		$message = $errorList[$errorNumber][1];
		if (1==$errorNumber and is_array($params) and isset($params[0]) and strlen((string)$params[0]) > 0) {
			$message = '%s';
		}

		http_response_code($errorList[$errorNumber][0]);

		$data = new stdClass();
		$data->error = TRUE;
		$data->code = $errorNumber;
		$data->message = is_array($params) ? vsprintf($message, $params) : $message;

		return $data;

	}

}