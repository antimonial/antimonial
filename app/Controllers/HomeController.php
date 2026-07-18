<?php

namespace App\Controllers;

use Antimonial\Controller\Controller;
use Antimonial\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return $this->view('home', [], 'layouts/main');
    }
}
