<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NpcFactionEntry extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'npc_faction_entries';

    public function factionList(): BelongsTo
    {
        return $this->belongsTo(FactionList::class, 'faction_id', 'id');
    }

    public function npc()
    {
        return $this->belongsTo(NpcType::class, 'npc_faction_id', 'npc_faction_id');
    }
}
