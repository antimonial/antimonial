<?php

declare(strict_types=1);

namespace Tests\Feature;

use Antimonial\Http\Request;
use Antimonial\Http\Response;
use Antimonial\Middleware\AuthMiddleware;
use Antimonial\Security\Auth;
use Antimonial\Session\Session;
use App\Controllers\AuthController;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        // Start from a clean session / auth state for every test.
        Auth::logout();
        Session::forget('errors');
        Session::forget('old');

        $_SERVER = ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/'];
        $_GET = [];
        $_POST = [];
        $_COOKIE = [];
        $_FILES = [];
    }

    private function post(array $data): Request
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = $data;
        $_SERVER['HTTP_REFERER'] = '/register';

        return Request::fromGlobals();
    }

    public function test_register_then_login_then_access_protected_route(): void
    {
        $controller = new AuthController();

        // Register a new user.
        $controller->register($this->post([
            'email' => 'alice@example.com',
            'password' => 'supersecret',
        ]));

        // Password must be hashed, never plaintext.
        $user = (new User())->query()->where('email', 'alice@example.com')->first();
        self::assertNotNull($user);
        self::assertNotSame('supersecret', $user->password);
        self::assertTrue(password_verify('supersecret', $user->password));

        // Registration does not auto-log in.
        self::assertFalse(Auth::check());

        // Log in with the same credentials.
        $controller->login($this->post([
            'email' => 'alice@example.com',
            'password' => 'supersecret',
        ]));

        self::assertTrue(Auth::check());
        self::assertSame((int) $user->id, Auth::id());

        // An authenticated request passes the auth middleware.
        $next = fn (Request $r): Response => (new Response)->body('ok');
        $response = (new AuthMiddleware)->handle(Request::fromGlobals(), $next);
        self::assertSame('ok', $response->getBody());
    }

    public function test_unauthenticated_request_redirects_to_login(): void
    {
        Auth::logout();

        $next = fn (Request $r): Response => (new Response)->body('should not run');
        $response = (new AuthMiddleware)->handle(Request::fromGlobals(), $next);

        self::assertSame(302, $response->getStatusCode());
        self::assertSame('/login', $response->getHeaders()['Location']);
    }

    public function test_registration_requires_unique_email(): void
    {
        $controller = new AuthController();

        $controller->register($this->post([
            'email' => 'dup@example.com',
            'password' => 'supersecret',
        ]));

        // Second registration with the same email must fail validation.
        $this->expectException(\Antimonial\Http\ValidationException::class);

        $controller->register($this->post([
            'email' => 'dup@example.com',
            'password' => 'supersecret',
        ]));
    }
}
