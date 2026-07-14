<?php

use Antimonial\Routing\Router;
use App\Controllers\HomeController;

/**
 * Web routes.
 *
 * @var Router $router
 */

$router->get('/', [HomeController::class, 'index']);
