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
