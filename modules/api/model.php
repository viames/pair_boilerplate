<?php

use Pair\Model;

class ApiModel extends Model {

	/**
	 * Imposta il codice HTTP dell’errore e restituisce un oggetto da spedire.
	 */
	public function getErrorData(int $errorNumber, ?array $params=NULL): stdClass {

		// elenco dei codici di errore
		// https://it.wikipedia.org/wiki/Codici_di_stato_HTTP
		$errorList = [
			1	=> [500, 'There was an unexpected error'],
			2	=> [500, 'There was an unexpected error: %s'],
			18	=> [400, 'Password length is too short'],
			19	=> [401, 'The token is invalid'],
			20	=> [401, 'Both username and password are required'],
			21	=> [501, 'The requested API method does not exist'],
			22	=> [401, 'Authentication failed'],
			23	=> [403, 'Logout unsuccesfully'],
			25	=> [403, 'The user does not have the privileges to perform the requested action'],
			27	=> [401, 'The session is invalid'],
			28	=> [400, 'The form sent is not complete with required data'],
			30	=> [400, 'Postal code or city name is invalid'],
			34	=> [400, 'Invalid fields: %s'],
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
			100 => [400, 'The object was not found (%s, %s)']
		];

		// codice non valido
		if (!in_array($errorNumber, array_keys($errorList))) {
			$errorNumber = 1;
		}

		// aggiunge il dettaglio dell'errore per i codici 1 (errore imprevisto)
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