<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NpcSpell extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'npc_spells';

    public function npcSpellEntries(): HasMany
    {
        return $this->hasMany(NpcSpellEntry::class, 'npc_spells_id', 'id');
    }

    public function attackProcSpell(): BelongsTo
    {
        return $this->belongsTo(Spell::class, 'attack_proc', 'id');
    }
}
