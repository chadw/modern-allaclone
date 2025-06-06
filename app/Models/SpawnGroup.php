<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SpawnGroup extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'spawngroup';

    public function spawn2(): HasMany
    {
        return $this->hasMany(SpawnTwo::class, 'spawngroupID', 'id');
    }

    public function spawnentries(): HasMany
    {
        return $this->hasMany(SpawnEntry::class, 'spawngroupID', 'id');
    }

    public function npcs(): HasManyThrough
    {
        return $this->hasManyThrough(
            NpcType::class,
            SpawnEntry::class,
            'spawngroupID',
            'id',
            'id',
            'npcID'
        );
    }
}
