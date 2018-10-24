<?php

/**
 * These routes affect the whole application. This is useful to
 * create fake module names that route to real existing modules.
 */

use Pair\Router;

Router::addRoute('/login', 'login', 'user');
Router::addRoute('/logout', 'logout', 'user');

Router::addRoute('/groups', 'groupList', 'users');
Router::addRoute('/groups/edit/:id([0-9])+', 'groupEdit', 'users');
Router::addRoute('/groups/new', 'groupNew', 'users');

/**
 * Examples
 *
// overwrite the default module/action, now invokes locales/default
Router::addRoute('', 'default', 'locales');

// force the second parameter to be an integer; use Router::get(1) instead of Router::get(0)
Router::addRoute('/edit/:([0-9])+', 'edit', 'countries');

// works with url /edit/100 but must use Router::get('id') instead of Router::get(0)
Router::addRoute('/edit/:id([0-9])+', 'edit', 'countries');

// customize order and module name: e.g. /100/editCountry
Router::addRoute('/:([0-9])+/editCountry', 'edit', 'countries');
*/