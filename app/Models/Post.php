<?php

declare(strict_types=1);

namespace App\Models;

use Antimonial\Model\Model;

class Post extends Model
{
    // The framework never guesses the table name — you declare it.
    protected string $table = 'posts';
}
