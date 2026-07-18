<?php

declare(strict_types=1);

namespace App\Controllers;

use Antimonial\Controller\Controller;
use Antimonial\Http\Request;
use Antimonial\Http\Response;
use Antimonial\Security\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister(Request $request): Response
    {
        return $this->view('auth/register', [], 'layouts/main');
    }

    public function register(Request $request): Response
    {
        $data = $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = new User();
        $user->insert([
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->redirect('/login');
    }

    public function showLogin(Request $request): Response
    {
        return $this->view('auth/login', [], 'layouts/main');
    }

    public function login(Request $request): Response
    {
        $data = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return $this->redirect('/login');
        }

        return $this->redirect('/posts');
    }

    public function logout(Request $request): Response
    {
        Auth::logout();

        return $this->redirect('/login');
    }
}
