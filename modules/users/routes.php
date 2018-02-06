<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

$r = Pair\Router::getInstance();

$r->addRoute('\d+', 'userEdit/{userId}');
$r->addRoute('\d+/prova/\d+', 'userEdit/{userId}{prova}{boh}');
$r->addRoute('\d+/\w+/\d+', 'userEdit/{userId}{testo}{boh}');
$r->addRoute('new', 'userNew');

