<?php

namespace App\Models;

use Antimonial\Model\Model;

class User extends Model
{
    // The framework never guesses the table name — you declare it.
    protected string $table = 'users';
}
