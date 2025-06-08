<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Aura extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'auras';

    public function spell(): HasOne
    {
        return $this->hasOne(Spell::class, 'id', 'spell_id');
    }
}
