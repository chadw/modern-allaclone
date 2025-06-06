<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpawnTwo extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'spawn2';

    public function zoneData(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone', 'short_name');
    }

    public function spawnGroup(): BelongsTo
    {
        return $this->belongsTo(SpawnGroup::class, 'spawngroupID', 'id');
    }
}
