<?php

declare(strict_types=1);

namespace Tests\Feature;

use Antimonial\Http\Request;
use Antimonial\Routing\Router;
use PHPUnit\Framework\TestCase;

class WelcomeTest extends TestCase
{
    private function dispatchGet(string $uri): \Antimonial\Http\Response
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = $uri;
        $_GET = [];
        $_POST = [];
        $_COOKIE = [];
        $_FILES = [];

        $router = new Router();
        $routesFile = ROOT_PATH . '/app/Routes/web.php';
        require $routesFile;

        $request = Request::fromGlobals();
        $match = $router->dispatch($request);

        /** @var array{0: class-string, 1: string} $handler */
        $handler = $match['handler'];
        $controller = new ($handler[0])();

        return $controller->{$handler[1]}($request);
    }

    public function test_welcome_route_returns_200(): void
    {
        $response = $this->dispatchGet('/');

        self::assertSame(200, $response->getStatusCode());
        self::assertStringContainsString('Antimonial', $response->getBody());
    }

    public function test_unknown_route_returns_404(): void
    {
        $this->expectException(\Antimonial\Routing\HttpNotFoundException::class);

        $this->dispatchGet('/this-route-does-not-exist');
    }
}
