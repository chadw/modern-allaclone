<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NpcFaction extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'npc_faction';

    public function primaryFaction(): BelongsTo
    {
        return $this->belongsTo(FactionList::class, 'primaryfaction', 'id');
    }
}
