<?php

use Antimonial\Middleware\AuthMiddleware;
use Antimonial\Middleware\GuestMiddleware;
use Antimonial\Routing\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\PostController;

/**
 * Web routes.
 *
 * @var Router $router
 */

$router->get('/', [HomeController::class, 'index']);

// Guest-only routes: redirect authenticated users away.
$router->group('', function (Router $router) {
    $router->get('/login', [AuthController::class, 'showLogin']);
    $router->post('/login', [AuthController::class, 'login']);
    $router->get('/register', [AuthController::class, 'showRegister']);
    $router->post('/register', [AuthController::class, 'register']);
}, [GuestMiddleware::class]);

// Authenticated routes.
$router->group('', function (Router $router) {
    $router->post('/logout', [AuthController::class, 'logout']);

    $router->get('/posts', [PostController::class, 'index']);
    $router->get('/posts/create', [PostController::class, 'create']);
    $router->post('/posts', [PostController::class, 'store']);
    $router->get('/posts/{id}/edit', [PostController::class, 'edit']);
    $router->post('/posts/{id}', [PostController::class, 'update']);
    $router->delete('/posts/{id}', [PostController::class, 'destroy']);
}, [AuthMiddleware::class]);
