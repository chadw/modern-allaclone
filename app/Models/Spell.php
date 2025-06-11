<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Spell extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'spells_new';

    public function desc(): BelongsTo
    {
        return $this->belongsTo(DbStr::class, 'descnum', 'id')->where('type', 6);
    }

    public function typedesc(): BelongsTo
    {
        return $this->belongsTo(DbStr::class, 'typedescnum', 'id')->where('type', 6);
    }

    public function effectdesc(): BelongsTo
    {
        return $this->belongsTo(DbStr::class, 'effectdescnum', 'id')->where('type', 6);
    }

    public function effectdesc2(): BelongsTo
    {
        return $this->belongsTo(DbStr::class, 'effectdescnum2', 'id')->where('type', 6);
    }

    public function scrolleffect(): HasMany
    {
        return $this->hasMany(Item::class, 'scrolleffect', 'id')->select('id', 'Name', 'icon', 'scrolleffect');
    }

    public function clickeffect(): HasMany
    {
        return $this->hasMany(Item::class, 'clickeffect', 'id')->select('id', 'Name', 'icon', 'clickeffect');
    }

    public function proceffect(): HasMany
    {
        return $this->hasMany(Item::class, 'proceffect', 'id')->select('id', 'Name', 'icon', 'proceffect');
    }

    public function worneffect(): HasMany
    {
        return $this->hasMany(Item::class, 'worneffect', 'id')->select('id', 'Name', 'icon', 'worneffect');
    }

    public function focuseffect(): HasMany
    {
        return $this->hasMany(Item::class, 'focuseffect', 'id')->select('id', 'Name', 'icon', 'focuseffect');
    }

    public function pets(): HasOne
    {
        return $this->hasOne(Pet::class, 'type', 'teleport_zone')
            ->where('temp', 0);
    }

    public function npcs(): HasOne
    {
        return $this->hasOne(NpcType::class, 'name', 'teleport_zone')
            ->select('id', 'name', 'race', 'level', 'class', 'hp', 'mana', 'AC', 'mindmg', 'maxdmg');
    }
}
