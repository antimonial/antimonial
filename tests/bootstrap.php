<?php

declare(strict_types=1);

/**
 * Test bootstrap.
 *
 * Uses an isolated SQLite database (recreated per suite) so the feature
 * tests never touch a real MySQL instance. Migrations are run once here
 * against that database.
 */

require __DIR__ . '/../vendor/autoload.php';

$dbFile = sys_get_temp_dir() . '/antimonial_test_' . uniqid('', true) . '.sqlite';
@unlink($dbFile);

putenv('DB_CONNECTION=sqlite');
putenv('DB_DRIVER=sqlite');
putenv('DB_NAME=' . $dbFile);
putenv('DB_HOST=127.0.0.1');
putenv('DB_PORT=3306');
putenv('DB_USER=root');
putenv('DB_PASS=');
putenv('APP_DEBUG=true');

define('ROOT_PATH', __DIR__ . '/..');

Antimonial\Core\Config::load('app');
Antimonial\Core\Config::load('database');
Antimonial\View\View::setViewPath(ROOT_PATH . '/app/Views');
Antimonial\Security\Auth::useModel(App\Models\User::class);

// Run migrations on the isolated test database.
$db = Antimonial\Database\DB::connection([
    'driver' => 'sqlite',
    'database' => $dbFile,
    'username' => '',
    'password' => '',
]);
$migrator = new Antimonial\Database\Migrator($db, ROOT_PATH . '/database/migrations');
$migrator->run();

// Start a session so Auth / old() / errors() work in tests.
Antimonial\Session\Session::start();
