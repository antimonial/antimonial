<?php

namespace App\Controllers;

use Antimonial\Controller\Controller;
use Antimonial\Http\Request;
use Antimonial\Security\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return $this->redirect('/posts');
        }

        return $this->redirect('/login');
    }
}
