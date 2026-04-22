<?php

declare(strict_types=1);

use Pair\Http\EmptyResponse;
use Pair\Http\JsonResponse;
use Pair\Http\ResponseInterface;
use Pair\Models\OAuth2Client;
use Pair\Models\OAuth2Token;

class Oauth2Controller extends BoilerplateController {

	/**
	 * Issue an OAuth2 client-credentials token.
	 */
	public function defaultAction(): ResponseInterface {

		$this->headless();

		if ('POST' !== $this->input()->method()) {
			$this->redirect('login');
			return new EmptyResponse();
		}

		// uncomplete request
		if ('client_credentials' != trim((string)$this->input()->string('grant_type', ''))) {
			sleep(3);
			return $this->problemResponse('#sec10.4.1', 'Bad Request', 400, 'Invalid grant_type');
		}

		// search for Basic Auth authentication
		$auth = OAuth2Token::basicCredentials();

		// check if it is Basic Auth
		if (!$auth) {
			$auth = [
				'id' => trim((string)$this->input()->string('client_id', '')),
				'secret' => trim((string)$this->input()->string('client_secret', '')),
			];
		}

		// load the client by its ID
		$client = new OAuth2Client($auth['id']);

		// wrong credentials
		if (!$client->isLoaded() or $client->secret != $auth['secret']) {
			sleep(3);
			return $this->problemResponse('#sec10.4.2', 'Unauthorized', 401, 'Authentication failed', [
				'WWW-Authenticate' => 'Bearer'
			]);
		}

		// if there is a not expired token, update the date, otherwise create a new one.
		return new JsonResponse([
			'access_token'	=> $client->issueAccessToken(),
			'expires_in'	=> OAuth2Token::getLifetimeSeconds(),
			'scope'			=> null,
			'token_type'	=> 'Bearer'
		]);

	}

	/**
	 * Build an RFC 7807 JSON error response without terminating the Pair v4 action flow.
	 *
	 * @param	array<string, string>	$headers	Extra response headers.
	 */
	private function problemResponse(string $type, string $title, int $status, ?string $detail = null, array $headers = []): JsonResponse {

		return new JsonResponse([
			'type'	=> 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html' . $type,
			'title'	=> $title,
			'status'=> $status,
			'detail'=> $detail
		], $status, $headers);

	}

}
