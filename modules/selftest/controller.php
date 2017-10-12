<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Controller;

class SelftestController extends Controller {

	protected function init(){

		require APPLICATION_PATH . '/modules/selftest/classes/SelfTest.php';

	}
	
}