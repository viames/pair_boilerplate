<?php 

use Pair\Core\Application;
use Pair\Helpers\Translator;
use Pair\Html\BootstrapMenu;

$app = Application::getInstance();
$translator = Translator::getInstance();

$menu = new BootstrapMenu();

$menu->item('users', 'USERS', 'fa-user');
$menu->item('groups', 'GROUPS', 'fa-users');
$menu->item('oauth2clients', 'oAuth2 client', 'fa-key');
$menu->item('options', 'OPTIONS', 'fa-sliders-h');

// admin multimenu
if (is_a($app->currentUser, 'Pair\Models\User') and $app->currentUser->admin) {

	$menu->separator('DEVELOPMENT');

	$menu->item('locales', 'LOCALES', 'fa-globe');
	$menu->item('countries', 'COUNTRIES', 'fa-flag');
	$menu->item('languages', 'LANGUAGES', 'fa-language');
	$menu->item('rules', 'RULES', 'fa-unlock');	
	$menu->item('selftest', 'SELF_TEST', 'fa-check');
	$menu->item('tools', 'TOOLS', 'fa-wrench');
	$menu->item('modules/default', 'MODULES', 'fa-puzzle-piece');
	$menu->item('templates/default', 'TEMPLATES', 'fa-puzzle-piece');
	$menu->item('crafter', 'Crafter', 'fa-laptop-code');
	$menu->item('translator', 'TRANSLATOR', 'fa-magic');

}

$menu->item('user/logout', 'LOGOUT', 'fa-sign-out-alt');

print $menu;
