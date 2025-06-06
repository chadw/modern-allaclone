<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbStr extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'db_str';
}
