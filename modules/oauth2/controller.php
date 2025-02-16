<?php

use Pair\Core\Controller;
use Pair\Models\Oauth2Client;
use Pair\Models\Oauth2Token;
use Pair\Helpers\Post;

class Oauth2Controller extends Controller {

	/**
	 * Set states based on selected filters.
	 */
	public function defaultAction(): void {

		if (!Post::submitted()) {
			$this->redirect('login');
		}

		// uncomplete request
		if ('client_credentials'!=Post::trim('grant_type')) {
			sleep(3);
			Oauth2Token::badRequest('Invalid grant_type');
		}

		// search for Basic Auth authentication
		$auth = Oauth2Token::readBasicAuth();

		// check if it is Basic Auth
		if (!is_object($auth) or !$auth->id or !$auth->secret) {
			$auth = new stdClass();
			$auth->id	  = Post::trim('client_id');
			$auth->secret = Post::trim('client_secret');
		}

		// load the client by its ID
		$client = new Oauth2Client($auth->id);

		// wrong credentials
		if (!$client->isLoaded() or $client->secret!=$auth->secret) {
			sleep(3);
			Oauth2Token::unauthorized('Authentication failed');
		}

		// if there is a not expired token, update the date, otherwise create a new one
		$client->sendToken();

	}

}