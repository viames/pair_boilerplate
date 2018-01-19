<?php 

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Application;
use Pair\Translator;

$app = Application::getInstance();
$translator	= Translator::getInstance();

$menu = new BootstrapMenu();

$menu->addItem('users', $translator->translate('USERS'), NULL, 'fa-user');
$menu->addItem('users/groupList', $translator->translate('GROUPS'), NULL, 'fa-users');
$menu->addItem('rules', $translator->translate('RULES'), NULL, 'fa-unlock');

// admin multimenu
if (isset($app->currentUser) and $app->currentUser->admin) {
	
	$menu->addItem('options', $translator->translate('OPTIONS'), NULL, 'fa-sliders-h');
	$menu->addItem('selftest', $translator->translate('SELF_TEST'), NULL, 'fa-check');
	$menu->addItem('tools', $translator->translate('TOOLS'), NULL, 'fa-wrench');
	$menu->addItem('languages/default', $translator->translate('LANGUAGES'), NULL, 'fa-language');
	$menu->addItem('modules/default', 'Moduli', NULL, 'fa-puzzle-piece');
	$menu->addItem('templates/default', 'Template', NULL, 'fa-puzzle-piece');
	$menu->addItem('developer', $translator->translate('DEVELOPER'), NULL, 'fa-magic');

}

$menu->addItem('user/logout', $translator->translate('LOGOUT'), NULL, 'fa-sign-out-alt');

print $menu->render();
