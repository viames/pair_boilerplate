<?php

use Pair\Core\Controller;

class SelftestController extends Controller {

	protected function init(){

		require_once APPLICATION_PATH . '/modules/selftest/classes/SelfTest.php';

	}
	
}