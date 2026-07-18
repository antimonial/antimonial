<?php

declare(strict_types=1);

use Antimonial\Database\Connection;
use Antimonial\Database\Migration;

return new class implements Migration
{
    public function up(Connection $db): void
    {
        if ($db->getDriver() === 'sqlite') {
            $db->execute(
                'CREATE TABLE users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    created_at DATETIME NOT NULL
                )'
            );

            return;
        }

        $db->execute(
            'CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL
            )'
        );
    }

    public function down(Connection $db): void
    {
        $db->execute('DROP TABLE IF EXISTS users');
    }
};
