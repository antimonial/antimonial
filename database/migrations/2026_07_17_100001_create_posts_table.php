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
                'CREATE TABLE posts (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    title VARCHAR(255) NOT NULL,
                    body TEXT NOT NULL,
                    image_path VARCHAR(255) NULL,
                    created_at DATETIME NOT NULL
                )'
            );

            return;
        }

        $db->execute(
            'CREATE TABLE posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                body TEXT NOT NULL,
                image_path VARCHAR(255) NULL,
                created_at DATETIME NOT NULL
            )'
        );
    }

    public function down(Connection $db): void
    {
        $db->execute('DROP TABLE IF EXISTS posts');
    }
};
