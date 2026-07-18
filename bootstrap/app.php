<?php

// Build and return the Antimonial application instance.
// Shared by the web front controller (public/index.php).

use Antimonial\Core\ErrorHandler;
use Antimonial\Security\Auth;
use App\Models\User;

// Point the framework's file logger at app/storage/logs.
ErrorHandler::setLogDirectory(__DIR__ . '/../app/storage/logs');

// Point the auth facade at the application's user model.
Auth::useModel(User::class);

return new Antimonial\Core\App();
