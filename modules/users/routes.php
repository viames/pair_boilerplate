<?php

/**
 * These routes affect only the users module. Module name can be excluded in
 * first parameter and third optional parameter (module) should be set NULL.
 */

use Pair\Core\Router;

Router::addRoute('/new', 'userNew');
Router::addRoute('/edit/:id([0-9])+', 'userEdit');
Router::addRoute('/list', 'userList');
