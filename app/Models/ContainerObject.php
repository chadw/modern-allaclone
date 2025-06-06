<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerObject extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'object';
}
