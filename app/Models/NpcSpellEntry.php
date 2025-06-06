<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NpcSpellEntry extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'npc_spells_entries';

    public function spells(): HasOne
    {
        return $this->hasOne(Spell::class, 'id', 'spellid')
            ->select('id', 'name', 'new_icon');
    }
}
