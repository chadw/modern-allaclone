<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class NpcType extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'npc_types';

    public function getCleanNameAttribute(): string
    {
        return $this->npcFixName($this->name);
    }

    public function getParsedSpecialAbilitiesAttribute()
    {
        $raw = $this->special_abilities;
        if (!$raw) {
            return [];
        }

        $attacks = explode('^', $raw);
        $labels = [];

        foreach ($attacks as $entry) {
            $parts = explode(',', $entry);
            $id = intval($parts[0]);
            $label = config('everquest.special_attacks.' . $id);

            if ($label) {
                $labels[] = $label;
            }
        }

        return $labels;
    }

    public function firstSpawnEntries(): HasOne
    {
        return $this->hasOne(SpawnEntry::class, 'npcID', 'id');
    }

    public function spawnEntries(): HasMany
    {
        return $this->hasMany(SpawnEntry::class, 'npcID', 'id');
    }

    public function lootTable(): HasOne
    {
        return $this->hasOne(Loottable::class, 'id', 'loottable_id');
    }

    public function loottableEntries(): HasMany
    {
        return $this->hasMany(LoottableEntry::class, 'loottable_id', 'loottable_id');
    }

    public function merchantlist(): HasMany
    {
        return $this->hasMany(Merchantlist::class, 'merchantid', 'merchant_id');
    }

    public function npcSpellset(): HasOne
    {
        return $this->hasOne(NpcSpell::class, 'id', 'npc_spells_id')
            ->select('id', 'name', 'parent_list', 'attack_proc', 'proc_chance');
    }

    public function npcFaction(): BelongsTo
    {
        return $this->belongsTo(NpcFaction::class, 'npc_faction_id', 'id');
    }

    public function npcFactionEntries(): HasMany
    {
        return $this->hasMany(NpcFactionEntry::class, 'npc_faction_id', 'npc_faction_id');
    }

    public function lootDrops(): HasManyThrough
    {
        return $this->hasManyThrough(
            LootdropEntry::class,
            LoottableEntry::class,
            'loottable_id',
            'lootdrop_id',
            'loottable_id',
            'lootdrop_id'
        );
    }

    public static function npcFixName(string $npc): string
    {
        $name = str_replace(['#', '!', '~'], '', $npc);
        $name = str_replace('_', ' ', $name);
        $name = str_replace('-', '`', $name);
        $name = preg_replace('/\d/', '', $name);

        return $name;
    }
}
