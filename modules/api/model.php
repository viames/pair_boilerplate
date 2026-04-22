<?php

declare(strict_types=1);

use Pair\Core\Model;
use Pair\Data\ArraySerializableData;
use Pair\Data\ReadModel;

class ApiModel extends Model {

	/**
	 * Error registry keyed by the historical numeric API code.
	 *
	 * @var array<int, array{0: int, 1: string}>
	 */
	private const ERROR_LIST = [
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

	/**
	 * Return the HTTP status code for a numeric API error.
	 */
	public function getErrorStatusCode(int $errorNumber): int {

		$errorNumber = $this->normalizeErrorNumber($errorNumber);

		return self::ERROR_LIST[$errorNumber][0];

	}

	/**
	 * Return the API error payload for a numeric API error.
	 */
	public function getErrorData(int $errorNumber, ?array $params=NULL): ApiErrorState {

		$errorNumber = $this->normalizeErrorNumber($errorNumber);

		return new ApiErrorState($errorNumber, $this->formatErrorMessage($errorNumber, $params));

	}

	/**
	 * Return a known error number or the generic fallback code.
	 */
	private function normalizeErrorNumber(int $errorNumber): int {

		if (!array_key_exists($errorNumber, self::ERROR_LIST)) {
			return 1;
		}

		return $errorNumber;

	}

	/**
	 * Format the translated API error message with optional parameters.
	 */
	private function formatErrorMessage(int $errorNumber, ?array $params = null): string {

		$message = self::ERROR_LIST[$errorNumber][1];

		// Code 1 can expose a concrete unexpected-error detail when the caller provides it.
		if (1 == $errorNumber and is_array($params) and isset($params[0]) and strlen((string)$params[0]) > 0) {
			$message = '%s';
		}

		return is_array($params) ? vsprintf($message, $params) : $message;

	}

}

/**
 * Typed API error payload for legacy numeric API errors.
 */
final readonly class ApiErrorState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the API error response state.
	 */
	public function __construct(
		public int $code,
		public string $message,
		public bool $error = true
	) {}

	/**
	 * Export the API error payload.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'error' => $this->error,
			'code' => $this->code,
			'message' => $this->message,
		];

	}

}

/**
 * Typed API success payload for commands that return no additional data.
 */
final readonly class ApiSuccessState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the API success response state.
	 */
	public function __construct(public bool $error = false) {}

	/**
	 * Export the API success payload.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'error' => $this->error,
		];

	}

}

/**
 * Typed API login response payload.
 */
final readonly class ApiLoginState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the login response state.
	 */
	public function __construct(public string $sessionId) {}

	/**
	 * Export the login response payload.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'sessionId' => $this->sessionId,
		];

	}

}

/**
 * Typed API user information response payload.
 */
final readonly class ApiUserInformationState implements ReadModel {

	use ArraySerializableData;

	/**
	 * Build the user information response state.
	 */
	public function __construct(
		public string $name,
		public string $surname,
		public string $username,
		public string $group,
		public string $locale,
		public ?string $timezone,
		public bool $enabled,
		public ?string $email
	) {}

	/**
	 * Export the user information response payload.
	 *
	 * @return	array<string, mixed>
	 */
	public function toArray(): array {

		return [
			'name' => $this->name,
			'surname' => $this->surname,
			'username' => $this->username,
			'group' => $this->group,
			'locale' => $this->locale,
			'timezone' => $this->timezone,
			'enabled' => $this->enabled,
			'email' => $this->email,
		];

	}

}
