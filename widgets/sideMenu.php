<?php 

use Pair\Core\Application;
use Pair\Html\BootstrapMenu;
use Pair\Support\Translator;

$app = Application::getInstance();
$translator	= Translator::getInstance();

$menu = new BootstrapMenu();

$menu->item('users', 'USERS', 'fa-user');
$menu->item('groups', 'GROUPS', 'fa-users');
$menu->item('options', 'OPTIONS', 'fa-sliders-h');

// admin multimenu
if (is_a($app->currentUser, 'Pair\User') and $app->currentUser->admin) {

	$menu->separator('DEVELOPER');

	$menu->item('locales', 'LOCALES', 'fa-globe');
	$menu->item('countries', 'COUNTRIES', 'fa-flag');
	$menu->item('languages', 'LANGUAGES', 'fa-language');
	$menu->item('rules', 'RULES', 'fa-unlock');	
	$menu->item('selftest', 'SELF_TEST', 'fa-check');
	$menu->item('tools', 'TOOLS', 'fa-wrench');
	$menu->item('modules/default', 'MODULES', 'fa-puzzle-piece');
	$menu->item('templates/default', 'TEMPLATES', 'fa-puzzle-piece');
	$menu->item('developer', 'DEVELOPER', 'fa-laptop-code');
	$menu->item('translator', 'TRANSLATOR', 'fa-magic');

}

$menu->item('user/logout', 'LOGOUT', 'fa-sign-out-alt');

print $menu->render();
