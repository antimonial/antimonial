<?php

use Antimonial\Routing\Router;
use App\Controllers\HomeController;

/**
 * Web routes.
 *
 * This is a bare starter: a single welcome route. Add your own
 * controllers, models, and routes as you build your application.
 *
 * @var Router $router
 */

$router->get('/', [HomeController::class, 'index']);
