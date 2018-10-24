<?php 

use Pair\Application;
use Pair\Translator;

$app = Application::getInstance();
$translator	= Translator::getInstance();

$menu = new BootstrapMenu();

$menu->addItem('users', $translator->get('USERS'), NULL, 'fa-user');
$menu->addItem('groups', $translator->get('GROUPS'), NULL, 'fa-users');
$menu->addItem('options', $translator->get('OPTIONS'), NULL, 'fa-sliders-h');

// admin multimenu
if (is_a($app->currentUser, 'Pair\User') and $app->currentUser->admin) {

	$menu->addItem('locales', $translator->get('LOCALES'), NULL, 'fa-globe');
	$menu->addItem('countries', $translator->get('COUNTRIES'), NULL, 'fa-flag');
	$menu->addItem('languages', $translator->get('LANGUAGES'), NULL, 'fa-language');
	$menu->addItem('rules', $translator->get('RULES'), NULL, 'fa-unlock');	
	$menu->addItem('selftest', $translator->get('SELF_TEST'), NULL, 'fa-check');
	$menu->addItem('tools', $translator->get('TOOLS'), NULL, 'fa-wrench');
	$menu->addItem('modules/default', $translator->get('MODULES'), NULL, 'fa-puzzle-piece');
	$menu->addItem('templates/default', $translator->get('TEMPLATES'), NULL, 'fa-puzzle-piece');
	$menu->addItem('developer', $translator->get('DEVELOPER'), NULL, 'fa-magic');
	$menu->addItem('translator', $translator->get('TRANSLATOR'), NULL, 'fa-magic');

}

$menu->addItem('user/logout', $translator->get('LOGOUT'), NULL, 'fa-sign-out-alt');

print $menu->render();
