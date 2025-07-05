<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'pets';

    public function npcs(): HasOne
    {
        return $this->hasOne(NpcType::class, 'name', 'type');
            //->select('id', 'name', 'race', 'level', 'class', 'hp', 'mana', 'AC', 'mindmg', 'maxdmg');
    }
}
