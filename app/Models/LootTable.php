<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LootTable extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'loottable';

    public function loottableEntries(): HasMany
    {
        return $this->hasMany(LoottableEntry::class, 'loottable_id', 'id');
    }
}
