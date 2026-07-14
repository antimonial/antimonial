<?php

namespace App\Controllers;

use Antimonial\Controller\Controller;
use Antimonial\Database\DB;

class HomeController extends Controller
{
    public function index()
    {
        $users = DB::table('users')
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        return $this->view('home', ['users' => $users], 'layouts/main');
    }
}
